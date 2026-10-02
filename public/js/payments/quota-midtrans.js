/**
 * Quota Payment Gateway Handler
 * ─────────────────────────────
 * Flow: klik Bayar → POST midtrans URL (server membuat order_id unik + Snap token)
 *       → window.snap.pay()
 *       → onSuccess/onPending → TAMPILKAN panel "memproses" dan POLLING ke
 *         status-url (server memverifikasi LANGSUNG ke API Midtrans).
 *
 * TIDAK ADA auto-confirm: status 'paid' hanya bila SERVER mengembalikannya
 * setelah verifikasi Midtrans/webhook.
 *
 * Setelah timeout polling, status DIANGGAP BELUM DIKETAHUI (bukan gagal):
 * user bisa menekan "Cek Status Lagi", atau membayar ulang via tombol utama.
 */

function showQuotaError(message) {
    const el = document.getElementById('midtransError');
    if (!el) return;
    el.textContent = message || 'Terjadi kesalahan saat menghubungi layanan pembayaran.';
    el.classList.remove('hidden');
}

function hideQuotaError() {
    const el = document.getElementById('midtransError');
    if (el) el.classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('payQuotaBtn');
    if (!btn || typeof window.snap === 'undefined') return;

    const processing = document.getElementById('quotaPayProcessing');
    const recheckBtn = document.getElementById('recheckQuotaBtn');
    const originalLabel = btn.innerHTML;

    function showProcessing() {
        if (processing) processing.classList.remove('hidden');
        if (recheckBtn) recheckBtn.classList.add('hidden');
        btn.disabled = true;
    }
    function hideProcessing() {
        if (processing) processing.classList.add('hidden');
        btn.disabled = false;
        btn.innerHTML = originalLabel;
    }
    function showRecheck() {
        if (recheckBtn) {
            recheckBtn.disabled = false;
            recheckBtn.innerHTML = '<i class="fa-solid fa-rotate-right text-xs"></i> <span>Cek Status Lagi</span>';
            recheckBtn.classList.remove('hidden');
        }
    }

    let pollTimer = null;
    let pollCount = 0;

    function stopPolling() {
        if (pollTimer) { clearInterval(pollTimer); pollTimer = null; }
    }

    /**
     * Pesan informatif berdasarkan detail dari server — TIDAK mengklaim
     * pembayaran gagal bila status sebenarnya belum diketahui.
     */
    function explainStatus(data) {
        const detail = data && data.detail ? data.detail : 'unknown';

        switch (detail) {
            case 'not_found':
                return 'Transaksi belum ditemukan di Midtrans. Kemungkinan popup pembayaran tertutup sebelum transaksi terbentuk. Silakan klik "Bayar Sekarang" untuk mencoba lagi.';
            case 'pending':
                return 'Pembayaran Anda tercatat dan sedang menunggu konfirmasi Midtrans. Klik "Cek Status Lagi" sesekali, atau tunggu notifikasi otomatis.';
            case 'not_created':
                return 'Belum ada percobaan pembayaran pada invoice ini. Silakan klik "Bayar Sekarang".';
            case 'settled':
                return null; // server sudah handle reload/status paid
            default:
                return 'Status pembayaran belum dapat dipastikan (gangguan komunikasi ke Midtrans). Klik "Cek Status Lagi", atau tunggu beberapa saat lalu coba lagi.';
        }
    }

    function checkStatus(manual) {
        return fetch(btn.getAttribute('data-status-url'), {
            headers: { 'Accept': 'application/json' },
            'X-Requested-With': 'XMLHttpRequest'
        })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.status === 'paid') {
                    stopPolling();
                    // Reload agar server render ulang state "Pembayaran Berhasil".
                    window.location.reload();
                    return true;
                }

                if (data.status === 'rejected') {
                    stopPolling();
                    hideProcessing();
                    showRecheck();
                    showQuotaError('Pembayaran ditolak/gagal diproses Midtrans. Silakan klik "Bayar Sekarang" untuk membayar kembali.');
                    return true;
                }

                if (manual) {
                    // Jawaban non-final pada pengecekan manual → tampilkan penjelasan.
                    hideProcessing();
                    showRecheck();
                    const msg = explainStatus(data);
                    showQuotaError(msg || 'Status masih menunggu konfirmasi.');
                }

                return false;
            })
            .catch(function () {
                if (manual) {
                    hideProcessing();
                    showRecheck();
                    showQuotaError('Gagal menghubungi server. Periksa koneksi lalu klik "Cek Status Lagi".');
                }
                return false; // gangguan jaringan → polling coba lagi di tick berikutnya
            });
    }

    function startPolling() {
        showProcessing();
        hideQuotaError();
        stopPolling();
        pollCount = 0;
        pollTimer = setInterval(function () {
            pollCount++;
            checkStatus(false).then(function (done) {
                if (done) return;
                if (pollCount >= 60) { // ~3 menit
                    // STATUS BELUM DIKETAHUI — bukan kegagalan pembayaran.
                    stopPolling();
                    hideProcessing();
                    showRecheck();
                    showQuotaError('Konfirmasi pembayaran belum diterima dalam batas waktu tunggu otomatis. Status tetap dapat dicek — klik "Cek Status Lagi", atau klik "Bayar Sekarang" bila pembayaran belum selesai.');
                }
            });
        }, 3000);
    }

    btn.addEventListener('click', function () {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> <span>Memproses…</span>';
        hideQuotaError();
        if (recheckBtn) recheckBtn.classList.add('hidden');

        fetch(btn.getAttribute('data-midtrans-url'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            },
            body: '{}'
        })
            .then(async function (response) {
                const data = await response.json().catch(function () { return {}; });
                if (!response.ok) {
                    throw new Error(data.message || ('HTTP ' + response.status));
                }
                return data;
            })
            .then(function (data) {
                if (!data.success || !data.snap_token) {
                    throw new Error(data.message || 'Snap token tidak diterima.');
                }

                btn.innerHTML = originalLabel; // label normal, tetap disabled selama snap terbuka

                window.snap.pay(data.snap_token, {
                    onSuccess: function () {
                        // JANGAN langsung anggap paid — minta server verifikasi.
                        startPolling();
                    },
                    onPending: function () {
                        startPolling();
                    },
                    onError: function () {
                        hideProcessing();
                        showQuotaError('Pembayaran gagal diproses Midtrans. Silakan coba lagi.');
                    },
                    onClose: function () {
                        // Popup ditutup tanpa bayar → kembalikan tombol.
                        hideProcessing();
                    }
                });
            })
            .catch(function (error) {
                hideProcessing();
                showQuotaError(error.message);
                console.error('[Quota Payment Error]', error);
            });
    });

    // ── Tombol manual "Cek Status Lagi" ─────────────────────────
    if (recheckBtn) {
        recheckBtn.addEventListener('click', function () {
            recheckBtn.disabled = true;
            recheckBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> <span>Memeriksa…</span>';
            hideQuotaError();

            checkStatus(true);
        });
    }
});
