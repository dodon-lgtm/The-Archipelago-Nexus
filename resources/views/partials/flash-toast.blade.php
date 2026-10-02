{{-- Popup notifikasi hasil aksi (pengganti pesan statis) --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof window.showToast !== 'function') {
            return;
        }

        @if (session('success'))
            showToast(@js(session('success')), 'success');
        @endif

        @if (session('error'))
            showToast(@js(session('error')), 'error');
        @endif

        @if (session('warning'))
            showToast(@js(session('warning')), 'warning');
        @endif
    });
</script>
