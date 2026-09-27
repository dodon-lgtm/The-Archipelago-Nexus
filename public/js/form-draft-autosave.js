/**
 * Auto-Save Draft Form Freelancer — menyimpan isi form secara otomatis
 * (localStorage) lalu memulihkannya saat user kembali ke form tersebut.
 *
 * Cakupan: HANYA role Freelancer. Script ini hanya di-include oleh
 * `resources/views/partials/form-draft-autosave.blade.php` yang dibungkus
 * guard `auth()->user()->role === 'freelancer'`, sehingga Company & Admin
 * tidak pernah memuat file ini.
 *
 * Halaman pengguna (form ber-atribut data-draft-form):
 *   - resources/views/freelancer/penawaran/create.blade.php   (Kirim Penawaran)
 *   - resources/views/freelancer/edit_profile.blade.php       (Edit Profil)
 *   - resources/views/freelancer/pendapatan/index.blade.php   (Modal Tarik Saldo)
 *   - resources/views/freelancer/reports/create.blade.php     (Buat Laporan)
 *   - resources/views/freelancer/reports/show.blade.php       (Unggah Bukti Tambahan)
 *   - resources/views/workspace/show.blade.php                (Catatan Progress, Tambah Tahap, Modal Update Progress)
 *   - resources/views/workspace/_submissions.blade.php        (Modal Upload Hasil Pekerjaan)
 *
 * Kontrak dengan view (Blade):
 *   1. <form ... data-draft-form>                      → form yang draft-nya disimpan
 *   2. data-draft-key="fl:xxx:123"                     → key unik per form (create/edit dibedakan per ID)
 *   3. data-draft-variant pada sebuah field            → nilai field (mis. hidden `stage`) jadi pembeda key
 *   4. data-draft-include pada input hidden            → hidden yang memang perlu disimpan (mis. harga_penawaran)
 *   5. data-draft-skip pada sebuah field               → field yang tidak boleh disimpan
 *   6. data-draft-submit-trigger pada tombol JS        → tombol yang memanggil form.submit() manual
 *   7. data-draft-clear="fl:key"                       → elemen sukses; draft dengan key tsb dihapus
 *   8. data-draft-clear-prefix="fl:prefix:"            → hapus semua draft dengan awalan key tsb
 *   9. window.FormDraftAutosave.restoreForm(form)      → pulihkan draft secara manual (mis. saat modal dibuka)
 *
 * Perilaku:
 *   - Text/textarea   : event "input" + debounce 400ms (tanpa tombol simpan)
 *   - select/checkbox/radio/date/number : event "change" → langsung disimpan
 *   - Refresh/pindah halaman : draft tetap tersimpan (pagehide juga di-flush)
 *   - Form dibuka lagi : draft dipulihkan otomatis + notifikasi kecil "Draft dipulihkan"
 *   - Validation gagal : draft TIDAK dihapus (redirect balik ke path yang sama)
 *   - Submit berhasil  : draft dihapus (redirect ke path lain / data-draft-clear)
 *   - Reset / "Buang"  : draft dihapus
 *
 * Data sensitif tidak pernah disimpan: password, token CSRF, OTP, CVV,
 * data kartu, secret/credential, dan seluruh input type="file".
 */
(function () {
    'use strict';

    // ────────────────────────────────────────────── Konstanta
    var NS = 'apexforge.fl.draft.v1:';
    var INDEX_KEY = NS + '__index__';
    var SCHEMA = 1;
    var TEXT_DEBOUNCE_MS = 400;
    var TTL_MS = 7 * 24 * 60 * 60 * 1000;   // draft kedaluwarsa setelah 7 hari
    var MAX_DRAFTS = 60;                     // batas jumlah draft per browser
    var MAX_PAYLOAD_BYTES = 200 * 1024;      // batas ukuran 1 draft
    var PILL_AUTOHIDE_MS = 6000;
    var API_NAME = 'FormDraftAutosave';

    // Field/atribut sensitif yang tidak boleh disimpan.
    var SENSITIVE_NAME_RE = /(pass|pwd|otp|cvv|cvc|card|kartu|secret|credential|token|pin)/i;
    var SENSITIVE_AUTOCOMPLETE = {
        'current-password': 1,
        'new-password': 1,
        'password': 1,
        'one-time-code': 1,
        'cc-number': 1,
        'cc-csc': 1,
        'cc-exp': 1,
        'cc-exp-month': 1,
        'cc-exp-year': 1,
        'cc-name': 1,
        'cc-type': 1
    };
    var SKIP_TYPES = {
        file: 1,
        submit: 1,
        reset: 1,
        button: 1,
        image: 1,
        password: 1
    };

    // ────────────────────────────────────────────── Helper DOM
    function attr(el, name) {
        if (!el || typeof el.getAttribute !== 'function') return null;
        var v = el.getAttribute(name);
        return v === null || v === '' ? null : v;
    }

    function hasFlag(el, name) {
        if (!el) return false;
        if (typeof el.hasAttribute === 'function') return el.hasAttribute(name);
        return attr(el, name) !== null;
    }

    function tag(el) {
        return String((el && el.tagName) || '').toLowerCase();
    }

    function inputType(el) {
        var tg = tag(el);
        if (tg === 'select') return 'select';
        if (tg === 'textarea') return 'textarea';
        var t = String((el && el.type) || '').toLowerCase();
        return t || 'text';
    }

    function isTextLike(el) {
        var t = inputType(el);
        if (t === 'textarea') return true;
        if (t === 'text' || t === 'email' || t === 'tel' || t === 'url' || t === 'search') return true;
        return false;
    }

    // ────────────────────────────────────────────── Lapisan storage (aman gagal)
    function resolveStorage() {
        try {
            var ls = window.localStorage;
            if (!ls) return null;
            var probe = NS + '__probe__';
            ls.setItem(probe, '1');
            ls.removeItem(probe);
            return ls;
        } catch (e) {
            // Private mode / storage penuh / diblokir → engine non-aktif, tanpa error.
            return null;
        }
    }

    function readJson(key) {
        var ls = storage;
        if (!ls) return null;
        var raw;
        try {
            raw = ls.getItem(key);
        } catch (e) {
            return null;
        }
        if (!raw) return null;
        try {
            var parsed = JSON.parse(raw);
            return parsed && typeof parsed === 'object' ? parsed : null;
        } catch (e) {
            // JSON rusak → buang supaya tidak error berulang.
            removeKey(key);
            return null;
        }
    }

    function writeJson(key, value) {
        var ls = storage;
        if (!ls) return false;
        try {
            var payload = JSON.stringify(value);
            if (payload.length > MAX_PAYLOAD_BYTES) return false;
            ls.setItem(key, payload);
            return true;
        } catch (e) {
            return false; // quota penuh / storage diblokir
        }
    }

    function removeKey(key) {
        var ls = storage;
        if (!ls) return;
        try {
            ls.removeItem(key);
        } catch (e) { /* diamkan */ }
    }

    function readIndex() {
        var idx = readJson(INDEX_KEY);
        if (!idx || typeof idx.keys !== 'object' || idx.keys === null) {
            return { v: SCHEMA, keys: {} };
        }
        return { v: SCHEMA, keys: idx.keys };
    }

    function writeIndex(idx) {
        if (Object.keys(idx.keys).length === 0) {
            removeKey(INDEX_KEY);
            return;
        }
        writeJson(INDEX_KEY, idx);
    }

    function touchIndex(key, meta) {
        var idx = readIndex();
        idx.keys[key] = meta;
        writeIndex(idx);
    }

    function forgetIndex(key) {
        var idx = readIndex();
        if (Object.prototype.hasOwnProperty.call(idx.keys, key)) {
            delete idx.keys[key];
            writeIndex(idx);
        }
    }

    // Hapus draft kedaluwarsa / yatim, lalu batasi jumlah draft.
    function pruneStorage(now) {
        var idx = readIndex();
        var keys = Object.keys(idx.keys);
        var changed = false;
        var alive = [];

        for (var i = 0; i < keys.length; i++) {
            var key = keys[i];
            var meta = idx.keys[key] || {};
            var savedAt = Number(meta.savedAt || 0);
            var exists = !storage ? false : (function () {
                try {
                    return storage.getItem(NS + key) !== null;
                } catch (e) {
                    return false;
                }
            })();

            if (!exists || !savedAt || (now - savedAt) > TTL_MS) {
                removeKey(NS + key);
                delete idx.keys[key];
                changed = true;
                continue;
            }
            alive.push([key, savedAt]);
        }

        if (alive.length > MAX_DRAFTS) {
            alive.sort(function (a, b) { return b[1] - a[1]; });
            for (var j = MAX_DRAFTS; j < alive.length; j++) {
                removeKey(NS + alive[j][0]);
                delete idx.keys[alive[j][0]];
                changed = true;
            }
        }

        if (changed) writeIndex(idx);
    }

    function readDraft(key) {
        var rec = readJson(NS + key);
        if (!rec || rec.v !== SCHEMA || !Array.isArray(rec.fields)) return null;
        return rec;
    }

    function writeDraft(key, rec) {
        var ok = writeJson(NS + key, rec);
        if (ok) {
            touchIndex(key, { savedAt: rec.savedAt, path: rec.path });
        }
        return ok;
    }

    function dropDraft(key) {
        removeKey(NS + key);
        forgetIndex(key);
    }

    function dropDraftByPrefix(prefix) {
        var idx = readIndex();
        var keys = Object.keys(idx.keys);
        var hit = 0;
        for (var i = 0; i < keys.length; i++) {
            if (keys[i].indexOf(prefix) === 0) {
                removeKey(NS + keys[i]);
                delete idx.keys[keys[i]];
                hit++;
            }
        }
        if (hit > 0) writeIndex(idx);
        return hit;
    }
    var storage = null;

    // ────────────────────────────────────────────── Field: filter & identitas
    function fieldIdentity(el) {
        var name = attr(el, 'name') || (el ? el.name : '');
        if (name) return 'n:' + name;
        var id = attr(el, 'id') || (el ? el.id : '');
        if (id) return 'i:' + id;
        return null;
    }

    function isSensitiveField(el) {
        if (!el) return true;
        var name = String(attr(el, 'name') || el.name || '').toLowerCase();
        var id = String(attr(el, 'id') || el.id || '').toLowerCase();
        var ac = String(attr(el, 'autocomplete') || '').toLowerCase().trim();

        if (SENSITIVE_NAME_RE.test(name) || SENSITIVE_NAME_RE.test(id)) return true;
        if (ac && SENSITIVE_AUTOCOMPLETE[ac]) return true;
        return false;
    }

    function skipField(el) {
        if (!el) return true;
        if (hasFlag(el, 'data-draft-skip')) return true;
        if (isSensitiveField(el)) return true;
        if (el.disabled) return true;

        var t = inputType(el);
        if (SKIP_TYPES[t]) return true;
        if (t === 'hidden' && !hasFlag(el, 'data-draft-include')) return true;
        return false;
    }

    function collectFields(form) {
        if (!form || typeof form.querySelectorAll !== 'function') return [];
        var nodes = form.querySelectorAll('input, select, textarea');
        var out = [];
        for (var i = 0; i < nodes.length; i++) {
            var el = nodes[i];
            if (skipField(el)) continue;
            if (!fieldIdentity(el)) continue;
            out.push(el);
        }
        return out;
    }

    // ────────────────────────────────────────────── Serialisasi
    function fieldValue(el) {
        var t = inputType(el);
        if (t === 'select' && el.multiple && el.options) {
            var vals = [];
            for (var i = 0; i < el.options.length; i++) {
                if (el.options[i].selected) vals.push(String(el.options[i].value));
            }
            return vals;
        }
        return String(el.value === undefined || el.value === null ? '' : el.value);
    }

    function serialize(fields) {
        var entries = [];
        var seenRadio = {};
        var seenCheckbox = {};

        for (var i = 0; i < fields.length; i++) {
            var el = fields[i];
            var id = fieldIdentity(el);
            var t = inputType(el);

            if (t === 'radio') {
                if (seenRadio[id]) continue;
                seenRadio[id] = true;
                var checkedVal = null;
                for (var g = 0; g < fields.length; g++) {
                    if (inputType(fields[g]) === 'radio' && fieldIdentity(fields[g]) === id && fields[g].checked) {
                        checkedVal = String(fields[g].value);
                    }
                }
                entries.push([id, 'r', checkedVal]);
                continue;
            }

            if (t === 'checkbox') {
                var ckey = id + '|' + String(el.value);
                if (seenCheckbox[ckey]) continue;
                seenCheckbox[ckey] = true;
                entries.push([id, 'c', String(el.value), !!el.checked]);
                continue;
            }

            entries.push([id, 'v', fieldValue(el)]);
        }

        return entries;
    }

    function signature(entries) {
        try {
            return JSON.stringify(entries);
        } catch (e) {
            return '';
        }
    }

    function hasAnyValue(entries) {
        for (var i = 0; i < entries.length; i++) {
            var e = entries[i];
            if (e[1] === 'c') {
                if (e[3]) return true;
                continue;
            }
            var v = e[2];
            if (v === null || v === undefined) continue;
            if (Array.isArray(v)) {
                if (v.length > 0) return true;
                continue;
            }
            if (String(v).trim() !== '') return true;
        }
        return false;
    }

    // ────────────────────────────────────────────── Terapkan draft ke form
    function notify(el) {
        if (!el || typeof window.Event !== 'function' || typeof el.dispatchEvent !== 'function') return;
        try {
            el.dispatchEvent(new window.Event('input', { bubbles: true }));
            el.dispatchEvent(new window.Event('change', { bubbles: true }));
        } catch (e) { /* diamkan */ }
    }

    function applyEntries(fields, entries) {
        var changed = 0;
        var groups = {};
        var i;

        for (i = 0; i < fields.length; i++) {
            var fid = fieldIdentity(fields[i]);
            if (!groups[fid]) groups[fid] = [];
            groups[fid].push(fields[i]);
        }

        for (i = 0; i < entries.length; i++) {
            var entry = entries[i];
            if (!entry || entry.length < 2) continue;

            var key = entry[0];
            var type = entry[1];
            var group = groups[key];
            if (!group || !group.length) continue;

            if (type === 'r') {
                var want = entry[2];
                for (var r = 0; r < group.length; r++) {
                    var should = want !== null && String(group[r].value) === String(want);
                    if (group[r].checked !== should) {
                        group[r].checked = should;
                        changed++;
                        notify(group[r]);
                    }
                }
                continue;
            }

            if (type === 'c') {
                var wantValue = String(entry[2]);
                var wantChecked = !!entry[3];
                for (var c = 0; c < group.length; c++) {
                    if (String(group[c].value) !== wantValue) continue;
                    if (group[c].checked !== wantChecked) {
                        group[c].checked = wantChecked;
                        changed++;
                        notify(group[c]);
                    }
                }
                continue;
            }

            var el = group[0];
            var next = entry[2];
            if (Array.isArray(next) && el.options) {
                var touched = false;
                for (var o = 0; o < el.options.length; o++) {
                    var wantSelected = next.indexOf(String(el.options[o].value)) !== -1;
                    if (el.options[o].selected !== wantSelected) {
                        el.options[o].selected = wantSelected;
                        touched = true;
                    }
                }
                if (touched) {
                    changed++;
                    notify(el);
                }
                continue;
            }

            var nextValue = next === null || next === undefined ? '' : String(next);
            if (String(el.value) !== nextValue) {
                el.value = nextValue;
                changed++;
                notify(el);
            }
        }

        return changed;
    }

    // ────────────────────────────────────────────── Notifikasi kecil (tanpa popup)
    var pillEl = null;
    var pillTimer = null;
    var restoredForms = [];

    function hidePill() {
        if (pillTimer) {
            clearTimeout(pillTimer);
            pillTimer = null;
        }
        if (pillEl && pillEl.parentNode) {
            try {
                pillEl.parentNode.removeChild(pillEl);
            } catch (e) { /* diamkan */ }
        }
        pillEl = null;
        restoredForms = [];
    }

    function showRestorePill() {
        if (typeof document.body === 'undefined' || !document.body) return;
        var count = restoredForms.length;

        if (pillEl) {
            var label = pillEl.querySelector('[data-draft-pill-text]');
            if (label) {
                label.textContent = count > 1 ? 'Draft dipulihkan (' + count + ' form)' : 'Draft dipulihkan';
            }
            if (pillTimer) clearTimeout(pillTimer);
            pillTimer = setTimeout(hidePill, PILL_AUTOHIDE_MS);
            return;
        }

        pillEl = document.createElement('div');
        pillEl.setAttribute('role', 'status');
        pillEl.setAttribute('aria-live', 'polite');
        pillEl.style.cssText = [
            'position:fixed',
            'left:50%',
            'bottom:20px',
            'transform:translateX(-50%)',
            'z-index:2147483000',
            'display:flex',
            'align-items:center',
            'gap:10px',
            'padding:8px 10px 8px 14px',
            'border-radius:9999px',
            'background:rgba(15,23,42,0.92)',
            'color:#f8fafc',
            'font:600 12px/1.2 "Plus Jakarta Sans",system-ui,-apple-system,"Segoe UI",sans-serif',
            'box-shadow:0 10px 30px -12px rgba(15,23,42,0.55)',
            'max-width:calc(100vw - 32px)'
        ].join(';');

        var text = document.createElement('span');
        text.setAttribute('data-draft-pill-text', '1');
        text.textContent = count > 1 ? 'Draft dipulihkan (' + count + ' form)' : 'Draft dipulihkan';
        pillEl.appendChild(text);

        var discard = document.createElement('button');
        discard.type = 'button';
        discard.textContent = 'Buang';
        discard.title = 'Hapus draft dan kembalikan form ke isi semula';
        discard.style.cssText = [
            'border:0',
            'cursor:pointer',
            'padding:5px 10px',
            'border-radius:9999px',
            'background:rgba(248,250,252,0.16)',
            'color:#f8fafc',
            'font:700 11px/1 "Plus Jakarta Sans",system-ui,sans-serif'
        ].join(';');
        discard.addEventListener('click', function () {
            discardRestoredDrafts();
            hidePill();
        });
        pillEl.appendChild(discard);

        var close = document.createElement('button');
        close.type = 'button';
        close.setAttribute('aria-label', 'Tutup notifikasi');
        close.textContent = '\u00d7';
        close.style.cssText = [
            'border:0',
            'cursor:pointer',
            'width:22px',
            'height:22px',
            'border-radius:9999px',
            'background:transparent',
            'color:rgba(248,250,252,0.7)',
            'font:700 14px/1 system-ui,sans-serif'
        ].join(';');
        close.addEventListener('click', hidePill);
        pillEl.appendChild(close);

        document.body.appendChild(pillEl);
        pillTimer = setTimeout(hidePill, PILL_AUTOHIDE_MS);
    }

    function discardRestoredDrafts() {
        for (var i = 0; i < restoredForms.length; i++) {
            var state = restoredForms[i];
            try {
                dropDraft(currentKey(state));
                state.suppress = true;
                applyEntries(collectFields(state.form), state.baselineEntries);
                state.suppress = false;
                state.restored = false;
            } catch (e) { /* diamkan */ }
        }
        restoredForms = [];
    }

    function applyDraftToForm(state, rec) {
        var fields = collectFields(state.form);
        state.suppress = true;
        var changed = applyEntries(fields, rec.fields);
        state.suppress = false;

        if (changed > 0) {
            state.restored = true;
            if (restoredForms.indexOf(state) === -1) restoredForms.push(state);
            showRestorePill();
        }
        return changed;
    }

    function initForm(form, index) {
        var state = {
            form: form,
            index: index,
            key: '',
            baseline: '',
            baselineEntries: [],
            suppress: false,
            timer: null,
            restored: false
        };

        form.__afDraftState = state;
        form.setAttribute('data-draft-ready', '1');

        var fields = collectFields(form);
        state.baselineEntries = serialize(fields);
        state.baseline = signature(state.baselineEntries);
        currentKey(state);

        // Auto restore: kalau ada draft sebelumnya → pulihkan otomatis.
        var rec = readDraft(state.key);
        if (rec && rec.fields && rec.fields.length) {
            applyDraftToForm(state, rec);
        }

        form.addEventListener('input', function (ev) {
            var el = ev.target;
            if (!el || skipField(el)) return;
            schedulePersist(state, !isTextLike(el));
        });

        form.addEventListener('change', function (ev) {
            var el = ev.target;
            if (!el || skipField(el)) return;
            schedulePersist(state, true);
        });

        form.addEventListener('reset', function () {
            if (state.timer) {
                clearTimeout(state.timer);
                state.timer = null;
            }
            dropDraft(currentKey(state));
            setTimeout(function () {
                var now = serialize(collectFields(form));
                state.baselineEntries = now;
                state.baseline = signature(now);
            }, 0);
        });

        form.addEventListener('submit', function () {
            if (state.timer) {
                clearTimeout(state.timer);
                state.timer = null;
            }
            markPending(state);
        });

        return state;
    }

    var registeredStates = [];
    function computeKey(form, index) {
        var base = attr(form, 'data-draft-key');
        if (!base) {
            var action = attr(form, 'action') || '';
            base = 'auto:' + (action || (window.location ? window.location.pathname : '')) + '#' + (index || 0);
        }

        var variantEl = typeof form.querySelector === 'function' ? form.querySelector('[data-draft-variant]') : null;
        if (variantEl) {
            var raw = variantEl.value;
            var variant = String(raw === undefined || raw === null ? '' : raw).trim();
            base += ':' + (variant === '' ? '-' : variant);
        }

        return base;
    }

    function currentKey(state) {
        state.key = computeKey(state.form, state.index);
        return state.key;
    }

    function currentEntries(state) {
        return serialize(collectFields(state.form));
    }

    function draftPath() {
        return window.location ? window.location.pathname : '';
    }

    function persist(state) {
        if (state.suppress || !storage) return false;

        var key = currentKey(state);
        var entries = currentEntries(state);

        // Isi form sama dengan kondisi awal / kosong → tidak perlu menyimpan draft.
        if (signature(entries) === state.baseline || !hasAnyValue(entries)) {
            dropDraft(key);
            return false;
        }

        var prev = readDraft(key);
        return writeDraft(key, {
            v: SCHEMA,
            key: key,
            path: draftPath(),
            savedAt: Date.now(),
            pending: prev && prev.pending ? prev.pending : null,
            fields: entries
        });
    }

    function schedulePersist(state, immediate) {
        if (state.suppress) return;
        if (state.timer) {
            clearTimeout(state.timer);
            state.timer = null;
        }

        if (immediate) {
            persist(state);
            return;
        }

        state.timer = setTimeout(function () {
            state.timer = null;
            persist(state);
        }, TEXT_DEBOUNCE_MS);
    }

    function flush(state) {
        if (state.timer) {
            clearTimeout(state.timer);
            state.timer = null;
        }
        persist(state);
    }

    // Dipanggil saat form di-submit: draft TIDAK dihapus, hanya ditandai pending.
    // Halaman berikutnya yang memutuskan (redirect ke path lain = submit sukses).
    function markPending(state) {
        if (!storage) return;

        var entries = currentEntries(state);
        // Tidak ada isi yang berarti (mis. form berisi file saja) → tidak perlu ditandai.
        if (!hasAnyValue(entries) && signature(entries) === state.baseline) return;

        var key = currentKey(state);
        writeDraft(key, {
            v: SCHEMA,
            key: key,
            path: draftPath(),
            savedAt: Date.now(),
            pending: { at: Date.now(), path: draftPath() },
            fields: entries
        });
    }

    // ────────────────────────────────────────────── Pending & marker sukses
    function processPending(now) {
        var idx = readIndex();
        var keys = Object.keys(idx.keys);
        var path = draftPath();

        for (var i = 0; i < keys.length; i++) {
            var rec = readDraft(keys[i]);
            if (!rec || !rec.pending) continue;

            if (rec.pending.path !== path) {
                // Submit sukses (redirect ke halaman lain) → draft dihapus.
                dropDraft(keys[i]);
                continue;
            }

            // Kembali ke form yang sama → kemungkinan validation gagal.
            // Draft TIDAK dihapus, hanya tanda pending-nya yang dilepas.
            rec.pending = null;
            rec.savedAt = rec.savedAt || now;
            writeDraft(keys[i], rec);
        }
    }

    // Elemen penanda sukses (mis. banner session('success')) → hapus draft terkait.
    function processClearMarkers() {
        var els = document.querySelectorAll('[data-draft-clear]');
        for (var i = 0; i < els.length; i++) {
            var key = attr(els[i], 'data-draft-clear');
            if (key) dropDraft(key);
        }

        var prefixes = document.querySelectorAll('[data-draft-clear-prefix]');
        for (var p = 0; p < prefixes.length; p++) {
            var prefix = attr(prefixes[p], 'data-draft-clear-prefix');
            if (prefix) dropDraftByPrefix(prefix);
        }
    }

    // ────────────────────────────────────────────── Interaksi & flush
    function closestMatch(node, selector) {
        var el = node;
        while (el && el !== document) {
            if (typeof el.matches === 'function' && el.matches(selector)) return el;
            el = el.parentNode;
        }
        return null;
    }

    function onDocumentClick(ev) {
        var target = ev.target;
        if (!target) return;

        // Tombol yang memanggil form.submit() manual (submit event tidak terpicu).
        var trigger = closestMatch(target, '[data-draft-submit-trigger]');
        if (trigger) {
            var triggerForm = closestMatch(trigger, 'form[data-draft-form]');
            var triggerState = triggerForm ? triggerForm.__afDraftState : null;
            if (triggerState) {
                // setTimeout(0) → jalan setelah handler tombol selesai,
                // supaya nilai field terbaru ikut tersimpan.
                setTimeout(function () { markPending(triggerState); }, 0);
            }
            return;
        }

        var discard = closestMatch(target, '[data-draft-discard]');
        if (discard) {
            var discardForm = closestMatch(discard, 'form[data-draft-form]');
            if (discardForm && discardForm.__afDraftState) {
                dropDraft(currentKey(discardForm.__afDraftState));
            }
        }
    }

    function flushAll() {
        for (var i = 0; i < registeredStates.length; i++) {
            try {
                flush(registeredStates[i]);
            } catch (e) { /* diamkan */ }
        }
    }

    function disabledApi() {
        var noop = function () { return false; };
        return {
            version: SCHEMA,
            enabled: false,
            restoreForm: noop,
            clear: noop,
            clearPrefix: noop,
            discardForm: noop
        };
    }

    function init() {
        storage = resolveStorage();

        if (!storage) {
            window[API_NAME] = disabledApi();
            return;
        }

        var now = Date.now();
        try { pruneStorage(now); } catch (e) { /* diamkan */ }
        try { processClearMarkers(); } catch (e) { /* diamkan */ }
        try { processPending(now); } catch (e) { /* diamkan */ }

        var forms = document.querySelectorAll('form[data-draft-form]');
        for (var i = 0; i < forms.length; i++) {
            try {
                registeredStates.push(initForm(forms[i], i));
            } catch (e) { /* satu form error tidak boleh mematikan form lain */ }
        }

        if (registeredStates.length === 0) return;

        document.addEventListener('click', onDocumentClick, false);
        window.addEventListener('pagehide', flushAll);
        window.addEventListener('beforeunload', flushAll);
        document.addEventListener('visibilitychange', function () {
            if (document.visibilityState === 'hidden') flushAll();
        });
    }

    // ────────────────────────────────────────────── API publik
    window[API_NAME] = {
        version: SCHEMA,
        enabled: false,

        /**
         * Pulihkan draft untuk sebuah form. Dipakai untuk form di dalam modal
         * yang nilainya di-reset oleh JS (mis. Modal Update Progress workspace).
         */
        restoreForm: function (form) {
            if (!storage || !form || typeof form.querySelectorAll !== 'function') return false;

            var state = form.__afDraftState;
            var index = state ? state.index : 0;
            var key = computeKey(form, index);
            var rec = readDraft(key);
            if (!rec || !rec.fields || !rec.fields.length) return false;

            var fields = collectFields(form);

            if (state) {
                state.key = key;
                state.suppress = true;
                var changed = applyEntries(fields, rec.fields);
                state.suppress = false;
                if (changed > 0) {
                    state.restored = true;
                    if (restoredForms.indexOf(state) === -1) restoredForms.push(state);
                    showRestorePill();
                }
                return changed > 0;
            }

            return applyEntries(fields, rec.fields) > 0;
        },

        /** Hapus draft berdasarkan key. */
        clear: function (key) {
            if (!storage || !key) return false;
            dropDraft(key);
            return true;
        },

        /** Hapus semua draft yang key-nya berawalan prefix. */
        clearPrefix: function (prefix) {
            if (!storage || !prefix) return 0;
            return dropDraftByPrefix(prefix);
        },

        /** Buang draft sebuah form + kembalikan isinya ke kondisi awal halaman. */
        discardForm: function (form) {
            var state = form ? form.__afDraftState : null;
            if (!state) return false;
            dropDraft(currentKey(state));
            state.suppress = true;
            applyEntries(collectFields(state.form), state.baselineEntries);
            state.suppress = false;
            return true;
        },

        /** Daftar draft tersimpan (untuk pemeriksaan manual dari console). */
        list: function () {
            var idx = readIndex();
            return Object.keys(idx.keys).map(function (key) {
                return { key: key, savedAt: idx.keys[key].savedAt, path: idx.keys[key].path };
            });
        },

        /** Helper murni — dipakai unit test Node: tests/js/form-draft-autosave.test.js */
        _internals: {
            NS: NS,
            INDEX_KEY: INDEX_KEY,
            TTL_MS: TTL_MS,
            MAX_DRAFTS: MAX_DRAFTS,
            TEXT_DEBOUNCE_MS: TEXT_DEBOUNCE_MS,
            fieldIdentity: fieldIdentity,
            isSensitiveField: isSensitiveField,
            skipField: skipField,
            fieldValue: fieldValue,
            serialize: serialize,
            signature: signature,
            hasAnyValue: hasAnyValue,
            applyEntries: applyEntries,
            computeKey: computeKey,
            readIndex: readIndex,
            readDraft: readDraft,
            writeDraft: writeDraft,
            dropDraft: dropDraft,
            dropDraftByPrefix: dropDraftByPrefix,
            pruneStorage: pruneStorage,
            setStorage: function (ls) { storage = ls; },
            hasStorage: function () { return !!storage; }
        }
    };

    function boot() {
        try {
            init();
            window[API_NAME].enabled = !!storage;
        } catch (e) {
            // Jangan pernah melempar error ke console halaman user.
            window[API_NAME] = disabledApi();
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }
})();

