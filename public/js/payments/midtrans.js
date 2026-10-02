/**
 * Midtrans Payment Gateway - CSP Compliant
 * 
 * This file contains the payWithMidtrans function that was previously
 * inline in the Blade template. Moving it to an external JS file resolves
 * the Content Security Policy violation (Executing inline script violates CSP).
 * 
 * Key changes from original:
 * - Uses relative URL fetch('/...') which inherits the page protocol (HTTPS)
 * - CSRF token is sent via meta tag or Laravel-generated header
 * - Error handling shows HTTP status + response body
 * - Snap token retrieval from backend endpoint
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('[Midtrans JS] File berhasil diload!');
    // Initialize Midtrans Snap
    if (window.snap) {
        // Snap SDK already loaded via <script src="{{ $snapUrl }}" data-client-key="...">
    }

    /**
     * Initiate Midtrans Snap payment
     * Reads workspace ID and amount from button data attributes
     */
    window.payWithMidtrans = function() {
        const btn = document.getElementById('payMidtransBtn');
        if (!btn) {
            console.error('[Midtrans] Button #payMidtransBtn not found');
            return;
        }        const workspaceId = btn.getAttribute('data-workspace-id');
        const amount = btn.getAttribute('data-amount');

        if (!workspaceId || !amount) {
            showMidtransError('Data pembayaran tidak lengkap. Silakan muat ulang halaman.');
            return;
        }

        // Disable button during request
        btn.disabled = true;
        btn.setAttribute('data-original-text', btn.innerHTML);
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> <span>Memproses...</span>';

        // URL relatif — pakai protokol yang sama dengan halaman (HTTPS lewat ngrok)
        fetch('/company/workspaces/' + workspaceId + '/payment/midtrans', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                amount: amount
            })
        })
        .then(async (response) => {
            const data = await response.json().catch(() => ({}));
            if (!response.ok) {
                const detail = data.message || response.statusText || ('HTTP ' + response.status);
                throw new Error('HTTP ' + response.status + ': ' + detail);
            }
            return data;
        })
        .then((data) => {
            if (data.success && data.snap_token) {
                // Re-enable button before opening Snap
                btn.disabled = false;
                const originalText = btn.getAttribute('data-original-text');
                if (originalText) {
                    btn.innerHTML = originalText;
                }

                window.snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        // UX only — status `paid` TIDAK diubah di frontend.
                        // Backend yang memutuskan: konfirmasi (temporary flow)
                        // atau webhook Midtrans sebagai sumber utama.
                        btn.disabled = true;
                        btn.setAttribute('data-original-text', btn.innerHTML);
                        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> <span>Mengonfirmasi pembayaran...</span>';

                        fetch('/company/workspaces/' + workspaceId + '/payment/confirm', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                                'Accept': 'application/json'
                            }
                        })
                            .then(async function (response) {
                                const confirmData = await response.json().catch(function () { return {}; });
                                if (!response.ok || !confirmData.success) {
                                    throw new Error(confirmData.message || ('HTTP ' + response.status));
                                }
                                showToast('Pembayaran berhasil dikonfirmasi.', 'success');
                                window.location.href = confirmData.redirect_url
                                    || ('/company/workspaces/' + workspaceId);
                            })
                            .catch(function (error) {
                                // Konfirmasi gagal → kembali ke gateway; webhook tetap
                                // bisa mengupdate status belakangan.
                                btn.disabled = false;
                                const originalText = btn.getAttribute('data-original-text');
                                if (originalText) btn.innerHTML = originalText;
                                showToast(
                                    error.message ||
                                    'Pembayaran tercatat, namun konfirmasi otomatis gagal. Status akan diperbarui setelah verifikasi.',
                                    'warning'
                                );
                                console.error('[Midtrans Confirm Error]', error);
                            });
                    },
                    onPending: function(result) {
                        showToast('Pembayaran tertunda. Silakan selesaikan pembayaran sesuai instruksi.', 'warning');
                    },
                    onError: function(result) {
                        showToast('Pembayaran gagal atau terjadi kesalahan. Silakan coba lagi.', 'error');
                    },
                    onClose: function() {
                        console.log('Pelanggan menutup popup Snap tanpa menyelesaikan pembayaran.');
                    }
                });
            } else {
                btn.disabled = false;
                const originalText = btn.getAttribute('data-original-text');
                if (originalText) {
                    btn.innerHTML = originalText;
                }
                showMidtransError(data.message || 'Gagal mendapatkan Snap Token dari server.');
            }
        })
        .catch((error) => {
            btn.disabled = false;
            const originalText = btn.getAttribute('data-original-text');
            if (originalText) {
                btn.innerHTML = originalText;
            }
            // Tampilkan detail error: HTTP status + response body (bukan pesan generik)
            const msg = error.message || 'Terjadi kesalahan saat menghubungi layanan pembayaran Midtrans. Silakan coba lagi.';
            showMidtransError(msg);
            console.error('[Midtrans Gateway Error]', error);
        });
    };

    /**
     * Show Midtrans error message
     * @param {string} message - Error message
     */
    function showMidtransError(message) {
        const errorEl = document.getElementById('midtransError');
        if (errorEl) {
            errorEl.textContent = message || 'Terjadi kesalahan saat menghubungi layanan pembayaran Midtrans. Silakan coba lagi.';
            errorEl.classList.remove('hidden');
        } else {
            showToast(message || 'Terjadi kesalahan saat menghubungi layanan pembayaran Midtrans. Silakan coba lagi.', 'error');
        }
    }

    // ── Auto-bind tombol bayar (paritas dengan resources/js versi) ──
    const payBtn = document.getElementById('payMidtransBtn');
    if (payBtn && !payBtn.dataset.midtransBound) {
        payBtn.dataset.midtransBound = '1';
        payBtn.addEventListener('click', window.payWithMidtrans);
    }
});