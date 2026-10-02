{{-- =========================================================
    NEGOTIATION CHAT MODAL (Shared)
    Dipicu oleh elemen ber-atribut data-negosiasi-open:
      data-negosiasi-open  = ID penawaran
      data-project-title   = Judul proyek
      data-peer-name       = Nama lawan bicara
      data-peer-type       = 'company' | 'freelancer'
========================================================= --}}

<div id="negotiationModal" class="hidden fixed inset-0 z-[120] bg-black/50 backdrop-blur-sm items-center justify-center p-3 sm:p-6">
    <div class="bg-white dark:bg-slate-900 w-full max-w-lg rounded-2xl shadow-2xl border border-blue-100 dark:border-slate-800 overflow-hidden flex flex-col max-h-[88vh] sm:max-h-[84vh] h-[min(88vh,640px)]">

        {{-- Header (stabil, tidak ikut scroll chat) --}}
        <div class="shrink-0 px-4 py-3 border-b border-blue-100 dark:border-slate-800 bg-gradient-to-r from-blue-600/10 via-transparent to-transparent flex items-center justify-between gap-3">
            <div class="flex items-center gap-2.5 min-w-0">
                <div id="negoPeerAvatar" class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-600 to-blue-500 text-white flex items-center justify-center shrink-0 shadow-[0_4px_12px_rgba(59,130,246,0.3)]">
                    <i class="fa-solid fa-user text-sm"></i>
                </div>
                <div class="min-w-0">
                    <h3 class="text-[13px] font-black text-slate-800 dark:text-white flex items-center gap-1.5 leading-none">
                        <i class="fa-regular fa-comments text-blue-500 text-xs"></i> Negosiasi
                    </h3>
                    <p id="negoProjectTitle" class="text-[11px] text-slate-500 dark:text-slate-400 truncate font-semibold leading-tight">Proyek</p>
                    <p class="text-[10px] text-blue-500 dark:text-blue-400 font-bold truncate leading-tight">dengan <span id="negoPeerName">-</span></p>
                </div>
            </div>
            <button type="button" id="negoCloseBtn" class="w-7 h-7 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-slate-600 dark:hover:text-white flex items-center justify-center shrink-0 transition-colors">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        {{-- Harga berjalan (stabil) --}}
        <div class="shrink-0 px-4 py-1.5 bg-slate-50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800 flex flex-wrap items-center justify-between gap-2">
            <p id="negoCurrentPrice" class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 leading-none">Harga saat ini: -</p>
            <p id="negoCurrentDays" class="text-[11px] font-bold text-blue-600 dark:text-blue-400 leading-none">Estimasi: -</p>
        </div>

        {{-- Banner: Negosiasi Dikunci / Deal (compact, stabil di atas chat, max 38% tinggi modal) --}}
        <div id="negoLockedBanner" class="hidden shrink-0 px-3 py-2 bg-amber-50 dark:bg-amber-950/30 border-b border-amber-200 dark:border-amber-800/40 max-h-[38%] overflow-y-auto">
            <div class="flex items-start gap-2">
                <div class="w-6 h-6 rounded-lg bg-amber-500 text-white flex items-center justify-center shrink-0 mt-0.5">
                    <i class="fa-solid fa-lock text-[11px]"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] font-extrabold text-amber-800 dark:text-amber-200 flex flex-wrap items-center gap-1.5 leading-none">
                        <span id="negoLockedTitle">Negosiasi Selesai / Dikunci</span>
                        <span id="negoLockedBadge" class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300 text-[9px] font-bold border border-amber-200 dark:border-amber-800 leading-none">Deal</span>
                    </p>
                    <p id="negoLockedDesc" class="text-[10px] leading-snug text-amber-700 dark:text-amber-300/90 mt-1 line-clamp-2">Negosiasi sudah selesai. Riwayat tetap tersimpan (read-only). Lanjutkan di Workspace.</p>
                    <div id="negoDealSummary" class="mt-2 hidden rounded-lg bg-white dark:bg-slate-900 border border-amber-200 dark:border-amber-800/50 p-2"></div>
                    <div id="negoWorkspaceLinkWrap" class="mt-2 hidden flex flex-wrap items-center gap-2">
                        <a id="negoWorkspaceLink" href="#" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-[11px] font-bold transition shadow-sm leading-none">
                            <i class="fa-solid fa-external-link-alt text-[10px]"></i> Buka Workspace
                        </a>
                        <span class="text-[10px] text-slate-500 dark:text-slate-400">Chat pengerjaan di Workspace</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Body Chat (flex-1, scroll internal, min tinggi agar beberapa pesan terakhir terlihat) --}}
        <div id="negotiationMessages" class="flex-1 overflow-y-auto overscroll-contain px-3 sm:px-4 py-3 bg-slate-50/50 dark:bg-slate-950/40 min-h-[180px] sm:min-h-[220px]"></div>

        {{-- Footer Form (composer) - stabil di bawah, tidak ikut scroll chat --}}
        <div id="negoComposer" class="shrink-0 border-t border-slate-100 dark:border-slate-800 p-3 bg-white dark:bg-slate-900">
            <div class="grid grid-cols-2 gap-2 mb-2" id="negoOfferInputs">
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-emerald-500">
                        <i class="fa-solid fa-tags"></i>
                    </span>
                    <input type="text" id="negoProposedPrice" inputmode="numeric" placeholder="Harga baru (opsional)"
                        class="w-full rounded-xl border border-blue-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 pl-9 pr-3 py-2 text-xs text-slate-700 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:focus:ring-slate-700">
                </div>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-blue-500">
                        <i class="fa-regular fa-clock"></i>
                    </span>
                    <input type="number" id="negoProposedDays" inputmode="numeric" min="1" max="3650" placeholder="Estimasi (hari)"
                        class="w-full rounded-xl border border-blue-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 pl-9 pr-3 py-2 text-xs text-slate-700 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:focus:ring-slate-700">
                </div>
            </div>
            <div class="flex items-end gap-2">
                <textarea id="negoMessage" rows="2" placeholder="Ketik pesan negosiasi..." autocomplete="off"
                    class="flex-1 resize-none rounded-xl border border-blue-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2 text-xs text-slate-700 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-100 dark:focus:ring-slate-700"></textarea>
                <button type="button" id="negoSendBtn" class="shrink-0 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-md shadow-blue-600/20">
                    <i class="fa-solid fa-paper-plane"></i> Kirim
                </button>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const CSRF = '{{ csrf_token() }}';
    const CURRENT_ROLE = '{{ auth()->user()->role ?? 'guest' }}';
    const modal = document.getElementById('negotiationModal');
    let currentPenawaranId = null;
    let messagesLoaded = false;

    function formatRp(num) {
        if (num === null || num === undefined || num === '') return null;
        const n = Number(num);
        if (isNaN(n)) return null;
        return 'Rp ' + n.toLocaleString('id-ID');
    }

    function esc(str) {
        const div = document.createElement('div');
        div.textContent = String(str ?? '');
        return div.innerHTML;
    }

    // Hanya menerima angka; tampil dengan pemisah ribuan id-ID (1.000.000).
    // Nilai yang dikirim ke backend tetap angka bersih (digit saja).
    function formatRibuan(value) {
        const digits = String(value ?? '').replace(/[^\d]/g, '').replace(/^0+(?=\d)/, '');
        return digits === '' ? '' : Number(digits).toLocaleString('id-ID');
    }

    function initPriceFormatter() {
        const priceEl = document.getElementById('negoProposedPrice');
        if (!priceEl || priceEl.dataset.ribuanBound === '1') return;
        priceEl.dataset.ribuanBound = '1';
        priceEl.addEventListener('input', function () {
            const cursorAtEnd = this.selectionStart === this.value.length;
            const formatted = formatRibuan(this.value);
            if (formatted !== this.value) {
                this.value = formatted;
                if (cursorAtEnd) this.setSelectionRange(this.value.length, this.value.length);
            }
        });
        // Pengaman terakhir sebelum kirim: pastikan isinya angka berformat.
        priceEl.addEventListener('blur', function () {
            this.value = formatRibuan(this.value);
        });
    }

    function offerCard(msg) {
        const price = formatRp(msg.proposed_price);
        const days = msg.proposed_days;

        let badge = '';
        let actions = '';
        if (msg.status === 'accepted') {
            badge = '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 text-[10px] font-bold"><i class="fa-solid fa-check"></i> Disetujui</span>';
        } else if (msg.status === 'rejected') {
            badge = '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-rose-100 dark:bg-rose-900/40 text-rose-700 dark:text-rose-300 text-[10px] font-bold"><i class="fa-solid fa-xmark"></i> Ditolak</span>';
        } else {
            badge = '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300 text-[10px] font-bold"><i class="fa-solid fa-hourglass-half"></i> Menunggu</span>';
            if (CURRENT_ROLE === 'freelancer') {
                actions =
                    '<div class="flex flex-wrap items-center gap-2 mt-3">' +
                        '<button type="button" data-nego-accept="' + msg.id + '" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-lg transition shadow-sm">' +
                            '<i class="fa-solid fa-check"></i> Setuju</button>' +
                        '<button type="button" data-nego-reject="' + msg.id + '" class="inline-flex items-center gap-1.5 px-4 py-2 bg-rose-500 hover:bg-rose-600 text-white text-xs font-bold rounded-lg transition shadow-sm">' +
                            '<i class="fa-solid fa-xmark"></i> Tolak</button>' +
                    '</div>';
            }
        }

        return '<div class="mt-2.5 rounded-xl border border-dashed ' +
            (msg.status === 'accepted' ? 'border-emerald-300 dark:border-emerald-700 bg-emerald-50/60 dark:bg-emerald-950/30' :
            msg.status === 'rejected' ? 'border-rose-300 dark:border-rose-700 bg-rose-50/60 dark:bg-rose-950/30' :
            'border-blue-300 dark:border-blue-700 bg-blue-50/60 dark:bg-blue-950/30') +
            ' p-3">' +
            '<p class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2 flex items-center gap-1.5"><i class="fa-solid fa-file-signature text-blue-500"></i> Tawaran Negosiasi ' + badge + '</p>' +
            '<div class="flex flex-wrap gap-2">' +
                (price ? '<span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white dark:bg-slate-800 border border-emerald-200 dark:border-emerald-900 text-emerald-700 dark:text-emerald-300 text-xs font-extrabold"><i class="fa-solid fa-tags"></i> ' + esc(price) + '</span>' : '') +
                (days ? '<span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white dark:bg-slate-800 border border-blue-200 dark:border-blue-900 text-blue-700 dark:text-blue-300 text-xs font-extrabold"><i class="fa-regular fa-clock"></i> ' + esc(days) + ' hari</span>' : '') +
            '</div>' + actions +
        '</div>';
    }

    function buildBubble(msg) {
        const senderName = msg.is_mine
            ? ''
            : '<span class="block text-[10px] font-bold text-blue-500 dark:text-blue-400 mb-0.5">' + esc(msg.sender_name) + ' <span class="normal-case font-semibold text-slate-400">(Perusahaan)</span></span>';
        const time = '<span class="block text-[9px] opacity-70 mt-1 ' +
            (msg.is_mine ? 'text-white/70 text-right' : 'text-slate-400 dark:text-slate-500') + '">' + esc(msg.created_at) + '</span>';

        const isOffer = (msg.proposed_price !== null || msg.proposed_days !== null) && msg.sender_type === 'company';
        const offerHtml = isOffer ? offerCard(msg) : '';

        return '<div class="flex ' + (msg.is_mine ? 'justify-end' : 'justify-start') + '">' +
            '<div class="max-w-[85%] ' +
                (msg.is_mine
                    ? 'bg-blue-600 text-white rounded-2xl rounded-br-md'
                    : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 border border-blue-100 dark:border-slate-700 rounded-2xl rounded-bl-md') +
                ' px-4 py-2.5 shadow-sm">' +
            senderName +
            '<p class="text-xs leading-relaxed whitespace-pre-wrap break-words">' + esc(msg.message) + '</p>' +
            offerHtml + time +
            '</div></div>';
    }

    function scrollToBottom() {
        const box = document.getElementById('negotiationMessages');
        if (box) box.scrollTop = box.scrollHeight;
    }

    let currentIsLocked = false;

    function applyLockedState(isLocked, dealSummary, workspaceUrl) {
        currentIsLocked = !!isLocked;
        const banner = document.getElementById('negoLockedBanner');
        const composer = document.getElementById('negoComposer');
        const summaryEl = document.getElementById('negoDealSummary');
        const wsWrap = document.getElementById('negoWorkspaceLinkWrap');
        const wsLink = document.getElementById('negoWorkspaceLink');
        const titleEl = document.getElementById('negoLockedTitle');
        const badgeEl = document.getElementById('negoLockedBadge');
        const descEl = document.getElementById('negoLockedDesc');

        if (isLocked) {
            banner.classList.remove('hidden');
            composer.classList.add('hidden');
            // Ringkasan deal
            if (dealSummary) {
                const isWinner = !!dealSummary.is_winner;
                const statusLabel = esc(dealSummary.status || 'Selesai');
                const harga = formatRp(dealSummary.harga_deal) || '-';
                const estimasi = dealSummary.estimasi_hari ? esc(dealSummary.estimasi_hari) + ' hari' : '-';
                const selectedAt = dealSummary.selected_at ? esc(dealSummary.selected_at) : '-';
                const freelancerName = esc(dealSummary.freelancer_name || '-');
                const projectName = esc(dealSummary.project_name || '-');
                titleEl.textContent = isWinner ? 'Deal — Negosiasi Selesai' : 'Negosiasi Dikunci';
                badgeEl.textContent = isWinner ? 'Deal / Selesai' : 'Dikunci';
                badgeEl.className = isWinner
                    ? 'inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 text-[10px] font-bold border border-emerald-200 dark:border-emerald-800'
                    : 'inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300 text-[10px] font-bold border border-amber-200 dark:border-amber-800';
                descEl.textContent = isWinner
                    ? 'Freelancer telah dipilih. Negosiasi dikunci (read-only). Riwayat tetap tersimpan. Lanjutkan komunikasi pengerjaan di Workspace.'
                    : 'Penawaran ini sudah tidak aktif (ditolak / workspace telah dibuat). Riwayat tetap dapat dibaca. Lanjutkan di Workspace jika Anda freelancer terpilih.';
                summaryEl.classList.remove('hidden');
                // Compact layout: Project & Freelancer full-width, 4 field lain 2 kolom agar hemat tinggi (35-40% target)
                summaryEl.innerHTML =
                    '<div class="flex items-center gap-1.5 text-[10px] font-bold text-slate-700 dark:text-slate-200 leading-none"><i class="fa-solid fa-circle-info text-blue-500 text-[11px]"></i> Ringkasan Deal</div>' +
                    '<div class="mt-1.5 grid grid-cols-1 gap-1 text-[10px]">' +
                        '<div class="flex justify-between gap-2 leading-tight"><span class="text-slate-500 dark:text-slate-400 shrink-0">Project</span><span class="font-bold text-slate-800 dark:text-white text-right truncate">' + projectName + '</span></div>' +
                        '<div class="flex justify-between gap-2 leading-tight"><span class="text-slate-500 dark:text-slate-400 shrink-0">Freelancer</span><span class="font-bold text-slate-800 dark:text-white text-right truncate">' + freelancerName + '</span></div>' +
                    '</div>' +
                    '<div class="mt-1.5 grid grid-cols-2 gap-1.5 text-[10px]">' +
                        '<div class="rounded-md bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 px-2 py-1.5"><div class="text-[9px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide leading-none">Harga Deal</div><div class="font-extrabold text-emerald-600 dark:text-emerald-400 leading-tight truncate">' + esc(harga) + '</div></div>' +
                        '<div class="rounded-md bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 px-2 py-1.5"><div class="text-[9px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide leading-none">Estimasi</div><div class="font-bold text-slate-800 dark:text-white leading-tight truncate">' + estimasi + '</div></div>' +
                        '<div class="rounded-md bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 px-2 py-1.5"><div class="text-[9px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide leading-none">Status</div><div class="font-bold leading-tight truncate ' + (isWinner ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400') + '">' + statusLabel + '</div></div>' +
                        '<div class="rounded-md bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 px-2 py-1.5"><div class="text-[9px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide leading-none">Dipilih</div><div class="font-semibold text-slate-600 dark:text-slate-300 leading-tight truncate">' + selectedAt + '</div></div>' +
                    '</div>';
            } else {
                summaryEl.classList.add('hidden');
                summaryEl.innerHTML = '';
            }
            if (workspaceUrl) {
                wsWrap.classList.remove('hidden');
                wsLink.href = workspaceUrl;
            } else {
                wsWrap.classList.add('hidden');
            }
        } else {
            banner.classList.add('hidden');
            composer.classList.remove('hidden');
            summaryEl.classList.add('hidden');
            wsWrap.classList.add('hidden');
        }
    }

    function loadMessages() {
        messagesLoaded = false;
        const box = document.getElementById('negotiationMessages');
        box.innerHTML = '<div class="flex justify-center py-10 text-slate-400 text-xs"><i class="fa-solid fa-spinner fa-spin mr-2"></i> Memuat percakapan...</div>';

        fetch('/negotiations/' + currentPenawaranId, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) throw new Error('Gagal memuat pesan');
            const msgs = data.messages || [];
            const price = formatRp(data.penawaran?.harga_penawaran);
            const days = data.penawaran?.estimasi_hari;
            document.getElementById('negoCurrentPrice').textContent = price ? 'Harga saat ini: ' + price : 'Harga saat ini: -';
            document.getElementById('negoCurrentDays').textContent = days ? 'Estimasi: ' + days + ' hari' : 'Estimasi: -';

            // Terapkan status dikunci / ringkasan deal dari server
            applyLockedState(!!data.is_locked, data.deal_summary || null, data.workspace_url || null);

            if (msgs.length === 0) {
                box.innerHTML = '<div class="flex flex-col items-center justify-center py-10 text-slate-400 text-center">' +
                    '<i class="fa-regular fa-comments text-3xl mb-2"></i>' +
                    '<p class="text-xs font-semibold">Belum ada pesan</p>' +
                    '<p class="text-[11px] mt-1">' + (data.is_locked ? 'Riwayat negosiasi tetap tersimpan (read-only).' : 'Mulai negosiasi dengan mengirim pesan pertama.') + '</p></div>';
            } else {
                // Jika dikunci, sembunyikan tombol Setuju/Tolak di dalam offerCard (sudah pending check), tapi juga pastikan tidak ada action button yang tampil
                let html = msgs.map(buildBubble).join('');
                if (data.is_locked) {
                    // Hapus tombol accept/reject dari HTML jika ada (defense in depth: backend juga menolak)
                    html = html.replace(/data-nego-accept="[^"]*"/g, 'data-nego-locked="1"').replace(/data-nego-reject="[^"]*"/g, 'data-nego-locked="1"');
                    // Sembunyikan container aksi yang mengandung button Setuju/Tolak (fallback sederhana)
                    html = html.replace(/<div class="flex flex-wrap items-center gap-2 mt-3">[\s\S]*?Tolak<\/button><\/div>/g, '');
                }
                box.innerHTML = html;
            }
            messagesLoaded = true;
            scrollToBottom();

            // Percakapan sudah dibuka → server menandai notifikasi terkait
            // sebagai read. Hilangkan badge unread untuk penawaran ini di
            // halaman agar konsisten dengan database.
            document.querySelectorAll('[data-nego-unread="' + currentPenawaranId + '"]')
                .forEach(function (badgeEl) { badgeEl.remove(); });
        })
        .catch(err => {
            box.innerHTML = '<div class="text-center py-10 text-red-500 text-xs">' + esc(err.message || 'Terjadi kesalahan') + '</div>';
        });
    }

    function openModal(btn) {
        currentPenawaranId = btn.getAttribute('data-negosiasi-open');
        document.getElementById('negoProjectTitle').textContent = btn.getAttribute('data-project-title') || 'Proyek';
        document.getElementById('negoPeerName').textContent = btn.getAttribute('data-peer-name') || '-';
        const peerType = btn.getAttribute('data-peer-type');
        document.getElementById('negoPeerAvatar').innerHTML =
            peerType === 'company' ? '<i class="fa-solid fa-building text-sm"></i>' : '<i class="fa-solid fa-user text-sm"></i>';
        document.getElementById('negoMessage').value = '';
        document.getElementById('negoProposedPrice').value = '';
        document.getElementById('negoProposedDays').value = '';
        document.getElementById('negoOfferInputs').style.display = (CURRENT_ROLE === 'company') ? 'grid' : 'none';
        initPriceFormatter();
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
        loadMessages();
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
        currentPenawaranId = null;
    }

    function sendMessage() {
        if (currentIsLocked) {
            showToast('Negosiasi sudah dikunci. Lanjutkan di Workspace.', 'error');
            return;
        }
        const messageInput = document.getElementById('negoMessage');
        const priceInput = document.getElementById('negoProposedPrice');
        const daysInput = document.getElementById('negoProposedDays');
        const sendBtn = document.getElementById('negoSendBtn');
        const message = messageInput.value.trim();

        if (!message) { messageInput.focus(); return; }

        const payload = { message: message };
        const rawPrice = priceInput.value.trim();
        if (rawPrice !== '') {
            const priceNum = Number(String(rawPrice).replace(/[^\d]/g, ''));
            if (!isNaN(priceNum) && priceNum > 0) payload.proposed_price = priceNum;
        }
        const rawDays = daysInput.value.trim();
        if (rawDays !== '') {
            const daysNum = Number(rawDays);
            if (!isNaN(daysNum) && daysNum >= 1) payload.proposed_days = Math.floor(daysNum);
        }

        sendBtn.disabled = true;
        sendBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i>';

        fetch('/negotiations/' + currentPenawaranId + '/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(payload)
        })
        .then(r => r.json().then(d => ({ ok: r.ok, data: d })))
        .then(({ ok, data }) => {
            if (!ok) {
                // Jika backend mengembalikan 423 locked, tampilkan banner locked dan reload
                if (data && data.is_locked) {
                    showToast(data.message || 'Negosiasi sudah dikunci.', 'error');
                    loadMessages();
                    throw new Error(data.message || 'Negosiasi dikunci');
                }
                const errs = data.errors ? Object.values(data.errors).flat().join(', ') : (data.message || 'Gagal mengirim pesan');
                throw new Error(errs);
            }
            const box = document.getElementById('negotiationMessages');
            if (!messagesLoaded) box.innerHTML = '';
            box.insertAdjacentHTML('beforeend', buildBubble(data.message));
            scrollToBottom();
            messageInput.value = '';
            priceInput.value = '';
            daysInput.value = '';
        })
        .catch(err => showToast(err.message, 'error'))
        .finally(() => {
            sendBtn.disabled = false;
            sendBtn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Kirim';
        });
    }

    function respondOffer(action, id) {
        if (currentIsLocked) {
            showToast('Negosiasi sudah dikunci. Tidak dapat memproses tawaran.', 'error');
            return;
        }
        fetch('/negotiations/' + currentPenawaranId + '/' + id + '/' + action, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(r => r.json().then(d => ({ ok: r.ok, data: d })))
        .then(({ ok, data }) => {
            if (!ok) {
                if (data && data.is_locked) {
                    showToast(data.message || 'Negosiasi sudah dikunci.', 'error');
                    loadMessages();
                    throw new Error(data.message || 'Negosiasi dikunci');
                }
                const errs = data.errors ? Object.values(data.errors).flat().join(', ') : (data.message || 'Gagal memproses tawaran');
                throw new Error(errs);
            }
            loadMessages();
        })
        .catch(err => showToast(err.message, 'error'));
    }

    document.addEventListener('click', function (e) {
        const trigger = e.target.closest('[data-negosiasi-open]');
        if (trigger) { e.preventDefault(); openModal(trigger); return; }

        const acceptBtn = e.target.closest('[data-nego-accept]');
        if (acceptBtn) { respondOffer('accept', acceptBtn.getAttribute('data-nego-accept')); return; }

        const rejectBtn = e.target.closest('[data-nego-reject]');
        if (rejectBtn) { respondOffer('reject', rejectBtn.getAttribute('data-nego-reject')); return; }
    });

    const closeBtn = document.getElementById('negoCloseBtn');
    if (closeBtn) closeBtn.addEventListener('click', closeModal);

    if (modal) {
        modal.addEventListener('click', function (e) { if (e.target === modal) closeModal(); });
    }

    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeModal(); });

    const sendBtn = document.getElementById('negoSendBtn');
    if (sendBtn) sendBtn.addEventListener('click', sendMessage);

    const msgInput = document.getElementById('negoMessage');
    if (msgInput) {
        msgInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
        });
    }
})();
</script>