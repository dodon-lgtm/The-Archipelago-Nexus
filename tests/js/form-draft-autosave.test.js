/**
 * Unit test engine auto-save draft Freelancer:
 * `public/js/form-draft-autosave.js`.
 *
 * Engine dijalankan di sandbox `node:vm` dengan DOM tiruan
 * (tests/js/fake-dom.js) supaya perilaku inti â€” pemilihan field, filter data
 * sensitif, serialisasi, key per form, pending/validasi gagal, marker sukses,
 * dan pemangkasan storage â€” bisa diuji tanpa browser.
 *
 * Jalankan: node --test "tests/js/*.test.js"
 */

import assert from 'node:assert/strict';
import fs from 'node:fs';
import path from 'node:path';
import { describe, it } from 'node:test';
import { fileURLToPath } from 'node:url';
import vm from 'node:vm';

import { createDom, createStorage, el, fire } from './fake-dom.js';

const HERE = path.dirname(fileURLToPath(import.meta.url));
const ENGINE_PATH = path.resolve(HERE, '..', '..', 'public', 'js', 'form-draft-autosave.js');
const ENGINE_SRC = fs.readFileSync(ENGINE_PATH, 'utf8');

/** Namespace localStorage milik engine (lihat konstanta NS di engine). */
const NS = 'apexforge.fl.draft.v1:';
const INDEX_KEY = NS + '__index__';

/** Jalankan engine di sandbox vm memakai DOM tiruan. */
function runEngine(dom) {
    const sandbox = { window: dom.window, document: dom.document, setTimeout, clearTimeout, console };
    vm.createContext(sandbox);
    vm.runInContext(ENGINE_SRC, sandbox, { filename: ENGINE_PATH });
    return dom.window.FormDraftAutosave;
}

/** Boot engine + DOM tiruan sekaligus. */
function boot(options = {}) {
    const dom = createDom(options);
    const api = runEngine(dom);
    return { dom, api, internals: api._internals, storage: dom.window.localStorage };
}

function makeDraft(key, fields, overrides = {}) {
    return Object.assign({
        v: 1,
        key,
        path: '/freelancer/penawaran/create/9',
        savedAt: Date.now(),
        pending: null,
        fields
    }, overrides);
}

/**
 * Tulis draft langsung ke storage + index, meniru apa yang dilakukan engine
 * (writeDraft akan mencatat meta ke index — penting untuk pending & prune).
 */
function seedDraft(storage, key, fields, overrides = {}) {
    const rec = makeDraft(key, fields, overrides);
    storage.setItem(NS + key, JSON.stringify(rec));

    const idx = JSON.parse(storage.getItem(INDEX_KEY) || '{"v":1,"keys":{}}');
    idx.keys[key] = { savedAt: rec.savedAt, path: rec.path };
    storage.setItem(INDEX_KEY, JSON.stringify(idx));

    return rec;
}

/** Rapikan field ala collectFields(): buang yang dilewati engine. */
function picked(internals, fields) {
    return fields.filter((f) => !internals.skipField(f));
}

/**
 * Normalisasi nilai hasil engine ke realm test (engine berjalan di sandbox vm,
 * sehingga array/object-nya punya prototype realm lain).
 */
function normalize(value) {
    return JSON.parse(JSON.stringify(value));
}

describe('Filter field & data sensitif', () => {
    it('menganggap password, OTP, dan data kartu sebagai field sensitif', () => {
        const { internals, dom } = boot();
        const mk = (attrs) => el('input', { attrs, parent: dom.body });

        assert.equal(internals.isSensitiveField(mk({ name: 'password' })), true);
        assert.equal(internals.isSensitiveField(mk({ name: 'user_password', type: 'text' })), true);
        assert.equal(internals.isSensitiveField(mk({ name: 'email', autocomplete: 'current-password' })), true);
        assert.equal(internals.isSensitiveField(mk({ name: 'otp_code' })), true);
        assert.equal(internals.isSensitiveField(mk({ id: 'existingPassword' })), true);
        assert.equal(internals.isSensitiveField(mk({ name: 'card_number' })), true);
        assert.equal(internals.isSensitiveField(mk({ name: '_token' })), true);
        assert.equal(internals.isSensitiveField(mk({ name: 'client_secret' })), true);

        assert.equal(internals.isSensitiveField(mk({ name: 'nama_lengkap' })), false);
        assert.equal(internals.isSensitiveField(mk({ name: 'deskripsi' })), false);
        assert.equal(internals.isSensitiveField(mk({ name: 'email', autocomplete: 'email' })), false);
        assert.equal(internals.isSensitiveField(null), true);
    });

    it('melewati file, hidden tanpa flag, dan field disabled', () => {
        const { internals, dom } = boot();
        const mk = (tag, attrs, props) => el(tag, { attrs, props, parent: dom.body });

        assert.equal(internals.skipField(mk('input', { name: 'lampiran', type: 'file' })), true);
        assert.equal(internals.skipField(mk('input', { name: '_token', type: 'hidden' })), true);
        assert.equal(internals.skipField(mk('input', { name: 'submit', type: 'submit' })), true);
        assert.equal(internals.skipField(mk('input', { name: 'password', type: 'password' })), true);
        assert.equal(internals.skipField(mk('input', { name: 'catatan', 'data-draft-skip': '' })), true);
        assert.equal(
            internals.skipField(mk('input', { name: 'judul' }, { disabled: true })),
            true
        );
        // Field tanpa name & id tetap "tidak dilewati" oleh skipField;
        // penyaring identitas dilakukan collectFields (lihat fieldIdentity).
        assert.equal(internals.skipField(mk('input', { name: 'tanpa_nama_dan_id' })), false);

        assert.equal(
            internals.skipField(mk('input', { name: 'harga_penawaran', type: 'hidden', 'data-draft-include': '' })),
            false
        );
        assert.equal(internals.skipField(mk('input', { name: 'judul' })), false);
        assert.equal(internals.skipField(mk('textarea', { name: 'deskripsi' })), false);
        assert.equal(internals.skipField(mk('select', { name: 'durasi_bulan' })), false);
    });

    it('fieldIdentity memakai name, lalu id, dan null kalau keduanya kosong', () => {
        const { internals, dom } = boot();
        assert.equal(internals.fieldIdentity(el('input', { attrs: { name: 'judul' }, parent: dom.body })), 'n:judul');
        assert.equal(internals.fieldIdentity(el('input', { attrs: { id: 'noteField' }, parent: dom.body })), 'i:noteField');
        assert.equal(internals.fieldIdentity(el('input', { parent: dom.body })), null);
    });
});

describe('Serialisasi & deteksi isi', () => {
    it('mengumpulkan text, checkbox, radio, hidden ber-flag, dan select multiple', () => {
        const { internals, dom } = boot();
        const form = el('form', { attrs: { 'data-draft-form': '', 'data-draft-key': 'fl:penawaran:9' }, parent: dom.body });
        const mk = (tag, attrs, props) => el(tag, { attrs, props, parent: form });

        const judul = mk('input', { name: 'judul' }, { value: 'Desain Logo' });
        const deskripsi = mk('textarea', { name: 'deskripsi' }, { value: 'Rincian pekerjaan' });
        const harga = mk('input', { name: 'harga_penawaran', type: 'hidden', 'data-draft-include': '' }, { value: '1500000' });
        const token = mk('input', { name: '_token', type: 'hidden' }, { value: 'csrf-abc' });
        const lampiran = mk('input', { name: 'lampiran', type: 'file' });
        const setuju = mk('input', { name: 'setuju', type: 'checkbox' }, { checked: true, value: '1' });
        const syarat = mk('input', { name: 'syarat', type: 'checkbox' }, { checked: false, value: '1' });
        const paketBasic = mk('input', { name: 'paket', type: 'radio' }, { value: 'basic' });
        const paketPro = mk('input', { name: 'paket', type: 'radio' }, { value: 'pro', checked: true });
        const kategori = mk('select', { name: 'kategori' }, { multiple: true });
        kategori.options = [
            { value: 'web', selected: true },
            { value: 'design', selected: false },
            { value: 'video', selected: true }
        ];
        const durasi = mk('select', { name: 'durasi' }, { value: '3' });

        const fields = picked(internals, [
            judul, deskripsi, harga, token, lampiran, setuju, syarat, paketBasic, paketPro, kategori, durasi
        ]);

        // normalize() dipakai karena array hasil engine berasal dari realm vm
        // (prototype beda, sehingga deepStrictEqual menolak tanpa normalisasi).
        assert.deepEqual(normalize(internals.serialize(fields)), [
            ['n:judul', 'v', 'Desain Logo'],
            ['n:deskripsi', 'v', 'Rincian pekerjaan'],
            ['n:harga_penawaran', 'v', '1500000'],
            ['n:setuju', 'c', '1', true],
            ['n:syarat', 'c', '1', false],
            ['n:paket', 'r', 'pro'],
            ['n:kategori', 'v', ['web', 'video']],
            ['n:durasi', 'v', '3']
        ]);

        // CSRF token & file tidak pernah ikut terserialisasi.
        const dump = JSON.stringify(internals.serialize(fields));
        assert.equal(dump.includes('csrf-abc'), false);
        assert.equal(dump.includes('lampiran'), false);
    });

    it('hasAnyValue membedakan form kosong dari form terisi', () => {
        const { internals } = boot();

        assert.equal(internals.hasAnyValue([]), false);
        assert.equal(internals.hasAnyValue([['n:judul', 'v', '']]), false);
        assert.equal(internals.hasAnyValue([['n:judul', 'v', '   ']]), false);
        assert.equal(internals.hasAnyValue([['n:setuju', 'c', '1', false]]), false);
        assert.equal(internals.hasAnyValue([['n:kategori', 'v', []]]), false);
        assert.equal(internals.hasAnyValue([['n:paket', 'r', null]]), false);

        assert.equal(internals.hasAnyValue([['n:judul', 'v', 'Logo']]), true);
        assert.equal(internals.hasAnyValue([['n:setuju', 'c', '1', true]]), true);
        assert.equal(internals.hasAnyValue([['n:kategori', 'v', ['web']]]), true);
    });

    it('signature stabil untuk isi yang sama', () => {
        const { internals } = boot();
        const a = [['n:judul', 'v', 'Logo']];
        const b = [['n:judul', 'v', 'Logo']];
        assert.equal(internals.signature(a) === internals.signature(b), true);
        assert.equal(internals.signature(a) === internals.signature([['n:judul', 'v', 'Lain']]), false);
    });
});


describe('Key draft per form', () => {
    it('memakai data-draft-key dan pembeda data-draft-variant', () => {
        const { internals, dom } = boot();
        const form = el('form', {
            attrs: { 'data-draft-form': '', 'data-draft-key': 'fl:ws-note:7' },
            parent: dom.body
        });

        assert.equal(internals.computeKey(form, 0), 'fl:ws-note:7');

        const stage = el('input', {
            attrs: { type: 'hidden', name: 'stage', 'data-draft-variant': '' },
            props: { value: 'Stage 1' },
            parent: form
        });

        assert.equal(internals.computeKey(form, 0), 'fl:ws-note:7:Stage 1');

        stage.value = '';
        assert.equal(internals.computeKey(form, 0), 'fl:ws-note:7:-');
    });

    it('fallback ke action + index kalau data-draft-key tidak ada', () => {
        const { internals, dom } = boot();
        const form = el('form', {
            attrs: { 'data-draft-form': '', action: '/freelancer/penawaran' },
            parent: dom.body
        });

        assert.equal(internals.computeKey(form, 3), 'auto:/freelancer/penawaran#3');
    });
});

describe('Penerapan draft ke form', () => {
    it('mengisi nilai, idempoten, dan mengabaikan field asing', () => {
        const { internals, dom } = boot();
        const form = el('form', { attrs: { 'data-draft-form': '' }, parent: dom.body });
        const judul = el('input', { attrs: { name: 'judul' }, parent: form });
        const deskripsi = el('textarea', { attrs: { name: 'deskripsi' }, parent: form });
        const fields = [judul, deskripsi];

        assert.equal(internals.applyEntries(fields, [['n:judul', 'v', 'Logo'], ['n:deskripsi', 'v', 'Rincian']]), 2);
        assert.equal(judul.value, 'Logo');
        assert.equal(deskripsi.value, 'Rincian');
        assert.equal(internals.applyEntries(fields, [['n:judul', 'v', 'Logo']]), 0);
        assert.equal(internals.applyEntries(fields, [['n:field_asing', 'v', 'x'], null]), 0);
    });

    it('menangani radio, checkbox, dan select multiple', () => {
        const { internals, dom } = boot();
        const form = el('form', { attrs: { 'data-draft-form': '' }, parent: dom.body });
        const mk = (tag, attrs, props) => el(tag, { attrs, props, parent: form });

        const basic = mk('input', { name: 'paket', type: 'radio' }, { value: 'basic', checked: true });
        const pro = mk('input', { name: 'paket', type: 'radio' }, { value: 'pro' });
        const fiturA = mk('input', { name: 'fitur[]', type: 'checkbox' }, { value: 'a' });
        const fiturB = mk('input', { name: 'fitur[]', type: 'checkbox' }, { value: 'b', checked: true });
        const kategori = mk('select', { name: 'kategori' }, { multiple: true });
        kategori.options = [
            { value: 'web', selected: true },
            { value: 'design', selected: false },
            { value: 'video', selected: false }
        ];
        const durasi = mk('select', { name: 'durasi' }, { value: '1' });

        const fields = [basic, pro, fiturA, fiturB, kategori, durasi];
        const entries = [
            ['n:paket', 'r', 'pro'],
            ['n:fitur[]', 'c', 'a', true],
            ['n:fitur[]', 'c', 'b', false],
            ['n:kategori', 'v', ['web', 'video']],
            ['n:durasi', 'v', '6']
        ];

        assert.equal(internals.applyEntries(fields, entries), 6);
        assert.equal(basic.checked, false);
        assert.equal(pro.checked, true);
        assert.equal(fiturA.checked, true);
        assert.equal(fiturB.checked, false);
        assert.deepEqual(kategori.options.map((o) => o.selected), [true, false, true]);
        assert.equal(durasi.value, '6');

        assert.equal(internals.applyEntries(fields, entries), 0);
    });
});

describe('Lapisan storage (localStorage)', () => {
    const KEY = 'fl:test:1';

    function ctx() {
        const storage = createStorage();
        const { internals } = boot({ storage });
        return { internals, storage };
    }

    it('menyimpan, membaca, dan menghapus draft beserta index-nya', () => {
        const { internals, storage } = ctx();

        assert.equal(internals.hasStorage(), true);
        assert.deepEqual(normalize(internals.readIndex()), { v: 1, keys: {} });

        assert.equal(internals.writeDraft(KEY, makeDraft(KEY, [['n:judul', 'v', 'Logo']])), true);
        assert.equal(storage.getItem(NS + KEY) !== null, true);
        assert.deepEqual(normalize(internals.readDraft(KEY).fields), [['n:judul', 'v', 'Logo']]);
        assert.deepEqual(Object.keys(internals.readIndex().keys), [KEY]);
        assert.equal(internals.readIndex().keys[KEY].path, '/freelancer/penawaran/create/9');

        internals.dropDraft(KEY);
        assert.equal(storage.getItem(NS + KEY), null);
        assert.equal(internals.readDraft(KEY), null);
        assert.deepEqual(Object.keys(internals.readIndex().keys), []);
        assert.equal(storage.getItem(INDEX_KEY), null);
    });

    it('menghapus draft berdasarkan prefix key', () => {
        const { internals } = ctx();
        ['fl:ws-note:7:Stage 1', 'fl:ws-note:7:Stage 2', 'fl:withdraw:3'].forEach((key) => {
            internals.writeDraft(key, makeDraft(key, [['n:a', 'v', '1']]));
        });

        assert.equal(internals.dropDraftByPrefix('fl:ws-note:7:'), 2);
        assert.deepEqual(Object.keys(internals.readIndex().keys), ['fl:withdraw:3']);
        assert.equal(internals.readDraft('fl:ws-note:7:Stage 1'), null);
        assert.equal(internals.readDraft('fl:withdraw:3') !== null, true);
        assert.equal(internals.dropDraftByPrefix('fl:tidak-ada:'), 0);
    });

    it('mengabaikan JSON rusak / skema lama tanpa melempar error', () => {
        const { internals, storage } = ctx();

        storage.setItem(NS + 'fl:bad:1', '{bukan json');
        assert.equal(internals.readDraft('fl:bad:1'), null);
        assert.equal(storage.getItem(NS + 'fl:bad:1'), null); // dibersihkan

        storage.setItem(NS + 'fl:old:1', JSON.stringify({ v: 0, fields: [] }));
        assert.equal(internals.readDraft('fl:old:1'), null);
        assert.equal(storage.getItem(NS + 'fl:old:1') !== null, true); // dibiarkan

        storage.setItem(NS + 'fl:nofields:1', JSON.stringify({ v: 1 }));
        assert.equal(internals.readDraft('fl:nofields:1'), null);
    });

    it('menolak draft yang melebihi batas ukuran payload', () => {
        const { internals, storage } = ctx();
        const huge = 'x'.repeat(300 * 1024);

        assert.equal(internals.writeDraft('fl:huge:1', makeDraft('fl:huge:1', [['n:catatan', 'v', huge]])), false);
        assert.equal(storage.getItem(NS + 'fl:huge:1'), null);
        assert.equal(internals.readDraft('fl:huge:1'), null);

        assert.equal(internals.writeDraft('fl:normal:1', makeDraft('fl:normal:1', [['n:catatan', 'v', 'oke']])), true);
    });

    it('pruneStorage membuang draft kedaluwarsa dan index yatim', () => {
        const { internals, storage } = ctx();
        const now = Date.now();

        internals.writeDraft('fl:fresh:1', makeDraft('fl:fresh:1', [['n:a', 'v', '1']], { savedAt: now - 1000 }));
        internals.writeDraft('fl:expired:1', makeDraft('fl:expired:1', [['n:a', 'v', '1']], { savedAt: now - internals.TTL_MS - 60000 }));
        internals.writeDraft('fl:orphan:1', makeDraft('fl:orphan:1', [['n:a', 'v', '1']], { savedAt: now - 1000 }));
        storage.removeItem(NS + 'fl:orphan:1'); // isi hilang, index tertinggal

        internals.pruneStorage(now);

        assert.equal(internals.readDraft('fl:fresh:1') !== null, true);
        assert.equal(internals.readDraft('fl:expired:1'), null);
        assert.deepEqual(Object.keys(internals.readIndex().keys), ['fl:fresh:1']);
    });

    it('pruneStorage membatasi jumlah draft, menyisakan yang terbaru', () => {
        const { internals, storage } = ctx();
        const now = Date.now();
        const total = internals.MAX_DRAFTS + 3;

        for (let i = 0; i < total; i++) {
            const key = 'fl:cap:' + i;
            internals.writeDraft(key, makeDraft(key, [['n:a', 'v', String(i)]], { savedAt: now - (total - i) * 1000 }));
        }

        assert.equal(Object.keys(internals.readIndex().keys).length, total);

        internals.pruneStorage(now);

        assert.equal(Object.keys(internals.readIndex().keys).length, internals.MAX_DRAFTS);
        assert.equal(storage.keys().filter((k) => k.indexOf(NS + 'fl:cap:') === 0).length, internals.MAX_DRAFTS);
        assert.equal(internals.readDraft('fl:cap:0'), null);
        assert.equal(internals.readDraft('fl:cap:2'), null);
        assert.equal(internals.readDraft('fl:cap:3') !== null, true);
        assert.equal(internals.readDraft('fl:cap:' + (total - 1)) !== null, true);
    });

    it('tetap aman kalau storage dinonaktifkan', () => {
        const { internals } = ctx();
        internals.setStorage(null);

        assert.equal(internals.hasStorage(), false);
        assert.equal(internals.writeDraft('fl:x:1', makeDraft('fl:x:1', [])), false);
        assert.equal(internals.readDraft('fl:x:1'), null);
        assert.equal(internals.dropDraftByPrefix('fl:'), 0);
        assert.deepEqual(normalize(internals.readIndex().keys), {});
    });
});


describe('Perilaku engine saat halaman dibuka', () => {
    const PATH = '/freelancer/penawaran/create/9';
    const FORM_KEY = 'fl:penawaran:9';

    function start(dom) {
        const api = runEngine(dom);
        return { api, internals: api._internals };
    }

    function buildPenawaran(dom) {
        const form = el('form', {
            attrs: { 'data-draft-form': '', 'data-draft-key': FORM_KEY },
            parent: dom.body
        });
        const judul = el('input', { attrs: { name: 'judul' }, parent: form });
        const deskripsi = el('textarea', { attrs: { name: 'deskripsi' }, parent: form });
        return { form, judul, deskripsi };
    }

    const sleep = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

    it('memulihkan draft otomatis + menampilkan pill "Draft dipulihkan"', () => {
        const storage = createStorage();
        const dom = createDom({ pathname: PATH, storage });
        const ui = buildPenawaran(dom);
        seedDraft(storage, FORM_KEY, [
            ['n:judul', 'v', 'Draft Judul'],
            ['n:deskripsi', 'v', 'Draft isi']
        ], { path: PATH });

        const { api } = start(dom);

        assert.equal(api.enabled, true);
        assert.equal(ui.form.getAttribute('data-draft-ready'), '1');
        assert.equal(ui.judul.value, 'Draft Judul');
        assert.equal(ui.deskripsi.value, 'Draft isi');

        // body berisi form + pill notifikasi (pill ditambahkan ke document.body).
        assert.equal(dom.body.children.length, 2);
        const pill = dom.body.children[1];
        assert.equal(pill.getAttribute('role'), 'status');
        assert.equal(!!pill.querySelector('[data-draft-pill-text]'), true);
    });

    it('tidak memunculkan pill kalau tidak ada draft tersimpan', () => {
        const dom = createDom({ pathname: PATH, storage: createStorage() });
        const ui = buildPenawaran(dom);
        const { api } = start(dom);

        assert.equal(api.enabled, true);
        assert.equal(ui.judul.value, '');
        assert.equal(dom.document.querySelectorAll('[role="status"]').length, 0);
        assert.equal(!!ui.form.__afDraftState, true);
    });

    it('menyimpan draft otomatis saat user mengetik (debounce) dan membuangnya saat kembali kosong', async () => {
        const storage = createStorage();
        const dom = createDom({ pathname: PATH, storage });
        const ui = buildPenawaran(dom);
        const { internals } = start(dom);

        ui.judul.value = 'Judul baru';
        fire(ui.form, 'input', { target: ui.judul });
        assert.equal(internals.readDraft(FORM_KEY), null); // masih menunggu debounce

        await sleep(internals.TEXT_DEBOUNCE_MS + 80);

        const rec = internals.readDraft(FORM_KEY);
        assert.equal(rec !== null, true);
        assert.equal(rec.path, PATH);
        assert.equal(rec.pending, null);
        assert.deepEqual(normalize(rec.fields), [['n:judul', 'v', 'Judul baru'], ['n:deskripsi', 'v', '']]);

        // Dikosongkan lagi → sama dengan kondisi awal halaman → draft dibuang.
        ui.judul.value = '';
        fire(ui.form, 'input', { target: ui.judul });
        await sleep(internals.TEXT_DEBOUNCE_MS + 80);

        assert.equal(internals.readDraft(FORM_KEY), null);
    });

    it('menghapus draft setelah submit sukses (redirect ke path lain)', () => {
        const storage = createStorage();
        seedDraft(storage, FORM_KEY, [['n:judul', 'v', 'Draft']], {
            path: PATH,
            pending: { at: Date.now(), path: PATH }
        });

        const dom = createDom({ pathname: '/freelancer/lamaran', storage });
        const { internals } = start(dom);

        assert.equal(internals.readDraft(FORM_KEY), null);
    });

    it('membiarkan draft saat kembali ke form yang sama (validasi gagal)', () => {
        const storage = createStorage();
        seedDraft(storage, FORM_KEY, [['n:judul', 'v', 'Draft']], {
            path: PATH,
            pending: { at: Date.now(), path: PATH }
        });

        const dom = createDom({ pathname: PATH, storage });
        const ui = buildPenawaran(dom);
        const { internals } = start(dom);

        const kept = internals.readDraft(FORM_KEY);
        assert.equal(kept !== null, true);
        assert.equal(kept.pending, null);      // tanda pending dilepas
        assert.equal(ui.judul.value, 'Draft'); // dan draft dipulihkan ke form
    });

    it('menghapus draft lewat penanda sukses data-draft-clear / -prefix', () => {
        const storage = createStorage();
        seedDraft(storage, 'fl:withdraw:5', [['n:jumlah', 'v', '100000']]);
        seedDraft(storage, 'fl:ws-modal:3:A', [['n:description', 'v', 'x']]);
        seedDraft(storage, 'fl:ws-modal:3:B', [['n:description', 'v', 'y']]);

        const dom = createDom({ pathname: '/freelancer/pendapatan', storage });
        el('div', { attrs: { 'data-draft-clear': 'fl:withdraw:5' }, parent: dom.body });
        el('div', { attrs: { 'data-draft-clear-prefix': 'fl:ws-modal:3:' }, parent: dom.body });

        const { internals } = start(dom);

        assert.equal(internals.readDraft('fl:withdraw:5'), null);
        assert.equal(internals.readDraft('fl:ws-modal:3:A'), null);
        assert.equal(internals.readDraft('fl:ws-modal:3:B'), null);
        assert.deepEqual(Object.keys(internals.readIndex().keys), []);
    });

    it('restoreForm(form) memulihkan draft setelah JS mengganti data-draft-variant (modal)', () => {
        const pathname = '/freelancer/workspaces/3';
        const storage = createStorage();
        seedDraft(storage, 'fl:ws-modal:3:Stage 1', [
            ['n:description', 'v', 'Catatan tersimpan']
        ], { path: pathname });

        const dom = createDom({ pathname, storage });
        const form = el('form', {
            attrs: { 'data-draft-form': '', 'data-draft-key': 'fl:ws-modal:3' },
            parent: dom.body
        });
        const stage = el('input', {
            attrs: { type: 'hidden', name: 'stage', id: 'updateProgressStageInput', 'data-draft-variant': '' },
            parent: form
        });
        const note = el('textarea', { attrs: { name: 'description' }, parent: form });

        const api = start(dom).api;

        assert.equal(note.value, ''); // variant masih kosong → key belum cocok
        stage.value = 'Stage 1';
        assert.equal(api.restoreForm(form), true);
        assert.equal(note.value, 'Catatan tersimpan');
        assert.equal(api.restoreForm(form), false); // sudah sama, tidak ada perubahan
    });

    it('menandai draft pending saat tombol data-draft-submit-trigger diklik', async () => {
        const pathname = '/freelancer/workspaces/3';
        const storage = createStorage();
        const dom = createDom({ pathname, storage });
        const form = el('form', {
            attrs: { 'data-draft-form': '', 'data-draft-key': 'fl:ws-modal:3' },
            parent: dom.body
        });
        el('input', {
            attrs: { type: 'hidden', name: 'stage', id: 'updateProgressStageInput', 'data-draft-variant': '' },
            props: { value: 'Stage 2' },
            parent: form
        });
        const note = el('textarea', { attrs: { name: 'description' }, parent: form });
        note.value = 'Progress tahap dua';
        const btn = el('button', {
            attrs: { type: 'button', id: 'saveProgressBtn', 'data-draft-submit-trigger': '' },
            parent: form
        });

        const { internals } = start(dom);
        assert.equal(Array.isArray(dom.listeners['doc:click']), true);

        dom.listeners['doc:click'][0]({ type: 'click', target: btn });
        await sleep(10);

        const rec = internals.readDraft('fl:ws-modal:3:Stage 2');
        assert.equal(rec !== null, true);
        assert.equal(rec.pending.path, pathname);
    });

    it('API publik clear / clearPrefix / discardForm bekerja', () => {
        const storage = createStorage();
        const dom = createDom({ pathname: PATH, storage });
        const ui = buildPenawaran(dom);
        const { api, internals } = start(dom);

        internals.writeDraft(FORM_KEY, makeDraft(FORM_KEY, [['n:judul', 'v', 'Draft']], { path: PATH }));
        ui.judul.value = 'Draft';
        assert.equal(api.list().length, 1);
        assert.equal(api.clear(FORM_KEY), true);
        assert.equal(api.list().length, 0);
        assert.equal(api.clear(''), false);

        internals.writeDraft('fl:ws-modal:3:A', makeDraft('fl:ws-modal:3:A', [['n:a', 'v', '1']]));
        internals.writeDraft('fl:ws-modal:3:B', makeDraft('fl:ws-modal:3:B', [['n:a', 'v', '1']]));
        assert.equal(api.clearPrefix('fl:ws-modal:3:'), 2);

        internals.writeDraft(FORM_KEY, makeDraft(FORM_KEY, [['n:judul', 'v', 'Draft']], { path: PATH }));
        assert.equal(api.discardForm(ui.form), true);
        assert.equal(internals.readDraft(FORM_KEY), null);
        assert.equal(ui.judul.value, ''); // dikembalikan ke isi awal halaman
        assert.equal(api.discardForm(null), false);
    });

    it('menonaktifkan diri tanpa error kalau localStorage diblokir', () => {
        const dom = createDom({ pathname: PATH, storage: createStorage({ throwOnSet: true }) });
        const ui = buildPenawaran(dom);
        const api = start(dom).api;

        assert.equal(api.enabled, false);
        assert.equal(api.restoreForm(ui.form), false);
        assert.equal(api.clear('fl:x:1'), false);
        assert.equal(api.clearPrefix('fl:'), false);
        assert.equal(api.discardForm(ui.form), false);
        assert.equal(ui.form.hasAttribute('data-draft-ready'), false);
        assert.equal(dom.document.querySelectorAll('[role="status"]').length, 0);
    });
});

