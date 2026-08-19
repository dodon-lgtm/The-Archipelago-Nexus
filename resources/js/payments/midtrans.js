/**
 * Midtrans Payment Gateway
 */

function showMidtransError(message) {
    const errorEl = document.getElementById('midtransError');
    if (errorEl) {
        errorEl.textContent = message || 'Terjadi kesalahan saat menghubungi layanan pembayaran Midtrans.';
        errorEl.classList.remove('hidden');
    } else {
        alert(message || 'Terjadi kesalahan saat menghubungi layanan pembayaran Midtrans.');
    }
}

/**
 * ── TEMPORARY / TESTING FLOW ──────────────────────────────────────────
 * Konfirmasi pembayaran ke endpoint backend setelah Snap Midtrans sukses.
 * Backend yang memutuskan status (amount diambil dari database, bukan browser).
 * Hanya aktif bila PAYMENT_TEMPORARY_CONFIRMATION=true di sisi server.
 */
function confirmPayment(workspaceId) {
    return fetch('/company/workspaces/' + workspaceId + '/payment/confirm', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            'Accept': 'application/json'
        }
    })
    .then(async (response) => {
        const data = await response.json().catch(() => ({}));
        if (!response.ok) {
            const detail = data.message || response.statusText || ('HTTP ' + response.status);
            throw new Error('HTTP ' + response.status + ': ' + detail);
        }
        return data;
    });
}

function handlePayWithMidtrans() {
    const btn = document.getElementById('payMidtransBtn');
    if (!btn) {
        console.error('[Midtrans] Button #payMidtransBtn tidak ditemukan');
        return;
    }

    const workspaceId = btn.getAttribute('data-workspace-id');
    const amount = btn.getAttribute('data-amount');

    if (!workspaceId || !amount) {
        showMidtransError('Data pembayaran tidak lengkap. Silakan muat ulang halaman.');
        return;
    }

    // Disable button during request
    btn.disabled = true;
    btn.setAttribute('data-original-text', btn.innerHTML);
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> <span>Memproses...</span>';

    fetch('/company/workspaces/' + workspaceId + '/payment/midtrans', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ amount: amount })
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
            btn.disabled = false;
            const originalText = btn.getAttribute('data-original-text');
            if (originalText) btn.innerHTML = originalText;

            window.snap.pay(data.snap_token, {
                onSuccess: function(result) {
                    // Tampilkan status loading pada tombol
                    btn.disabled = true;
                    btn.setAttribute('data-original-text', btn.innerHTML);
                    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> <span>Mengonfirmasi pembayaran...</span>';

                    // Konfirmasi ke backend (backend yang menentukan status payment/escrow/workspace)
                    confirmPayment(workspaceId)
                        .then((confirmData) => {
                            if (confirmData.success) {
                                alert('Pembayaran berhasil dikonfirmasi.');
                                window.location.href = confirmData.redirect_url
                                    || ('/company/workspaces/' + workspaceId);
                            } else {
                                btn.disabled = false;
                                const originalText = btn.getAttribute('data-original-text');
                                if (originalText) btn.innerHTML = originalText;
                                showMidtransError(confirmData.message || 'Konfirmasi pembayaran gagal.');
                            }
                        })
                        .catch((error) => {
                            btn.disabled = false;
                            const originalText = btn.getAttribute('data-original-text');
                            if (originalText) btn.innerHTML = originalText;
                            showMidtransError(error.message);
                            console.error('[Midtrans Confirm Error]', error);
                        });
                },
                onPending: function(result) {
                    alert('Pembayaran tertunda. Silakan selesaikan pembayaran Anda.');
                },
                onError: function(result) {
                    alert('Pembayaran gagal.');
                },
                onClose: function() {
                    console.log('Popup Snap ditutup.');
                }
            });
        } else {
            btn.disabled = false;
            const originalText = btn.getAttribute('data-original-text');
            if (originalText) btn.innerHTML = originalText;
            showMidtransError(data.message || 'Gagal mendapatkan Snap Token.');
        }
    })
    .catch((error) => {
        btn.disabled = false;
        const originalText = btn.getAttribute('data-original-text');
        if (originalText) btn.innerHTML = originalText;
        showMidtransError(error.message);
        console.error('[Midtrans Gateway Error]', error);
    });
}

// Pasang Event Listener otomatis setelah DOM siap
document.addEventListener('DOMContentLoaded', function() {
    const payBtn = document.getElementById('payMidtransBtn');
    if (payBtn) {
        payBtn.addEventListener('click', handlePayWithMidtrans);
    }
});