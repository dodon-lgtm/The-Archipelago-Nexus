<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - ApexForge Labs</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Google Font --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>

    @stack('styles')
<style>

/* ApexForge Labs — Unified UI System */
:root{
    --af-primary:#2563eb;
    --af-primary-dark:#1d4ed8;
    --af-primary-soft:#eff6ff;
    --af-sky:#38bdf8;
    --af-ink:#0f172a;
    --af-muted:#64748b;
    --af-border:#dbeafe;
    --af-surface:#ffffff;
    --af-page:#f6f9ff;
}
html{scroll-behavior:smooth}
body{
    font-family:'Plus Jakarta Sans',sans-serif;
    background:
        radial-gradient(circle at 10% -10%,rgba(56,189,248,.10),transparent 30%),
        radial-gradient(circle at 100% 0%,rgba(37,99,235,.08),transparent 28%),
        var(--af-page);
}
::selection{background:rgba(37,99,235,.18);color:#0f172a}
::-webkit-scrollbar{width:7px;height:7px}
::-webkit-scrollbar-track{background:rgba(241,245,249,.7)}
::-webkit-scrollbar-thumb{background:rgba(37,99,235,.22);border-radius:999px}
::-webkit-scrollbar-thumb:hover{background:rgba(37,99,235,.38)}

input,select,textarea{
    border-color:var(--af-border)!important;
    background:rgba(255,255,255,.92);
    transition:border-color .2s ease,box-shadow .2s ease,background .2s ease;
}
input:focus,select:focus,textarea:focus{
    border-color:rgba(37,99,235,.55)!important;
    box-shadow:0 0 0 4px rgba(37,99,235,.09)!important;
    outline:none!important;
}
button,a,[role="button"]{transition:all .2s ease}
button:focus-visible,a:focus-visible,[role="button"]:focus-visible{
    outline:2px solid rgba(37,99,235,.55);
    outline-offset:2px;
}
table{border-collapse:separate;border-spacing:0}
thead th{
    background:rgba(239,246,255,.72)!important;
    color:#334155;
    font-weight:700;
}
tbody tr{transition:background .18s ease}
tbody tr:hover{background:rgba(239,246,255,.48)}
[class*="bg-blue-600"]{
    box-shadow:0 8px 22px -12px rgba(37,99,235,.72);
}
[class*="bg-blue-600"]:hover{
    box-shadow:0 12px 28px -12px rgba(37,99,235,.78);
    transform:translateY(-1px);
}
.glass-panel,.glass-card,.glass-surface{
    background:rgba(255,255,255,.72);
    border:1px solid rgba(219,234,254,.85);
    backdrop-filter:blur(18px);
    -webkit-backdrop-filter:blur(18px);
    box-shadow:0 18px 50px -32px rgba(30,64,175,.32);
}
.apex-page-glow{
    position:fixed;inset:auto -10rem -12rem auto;width:28rem;height:28rem;
    background:rgba(56,189,248,.09);filter:blur(70px);border-radius:999px;
    pointer-events:none;z-index:-1;
}
@media (max-width:767px){
    main{padding-left:1rem!important;padding-right:1rem!important}
    table{min-width:680px}
    .overflow-x-auto{-webkit-overflow-scrolling:touch}
}
@media (prefers-reduced-motion:reduce){
    *,*::before,*::after{animation-duration:.01ms!important;animation-iteration-count:1!important;transition-duration:.01ms!important;scroll-behavior:auto!important}
}

</style>
</head>
<body class="bg-[#f6f9ff] text-slate-800 antialiased">

    <div class="flex h-screen overflow-hidden">

        {{-- SIDEBAR --}}
        @include('navbar.navigasi')

        {{-- MAIN CONTENT --}}
        <div class="flex-1 flex flex-col overflow-hidden">

            {{-- TOP NAVBAR --}}
            @include('navbar.nav')

            {{-- PAGE CONTENT --}}
            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>

        </div>

    </div>

    @stack('scripts')

    {{-- Auto-hide flash messages --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const alerts = document.querySelectorAll('.flash-message');
            alerts.forEach(function (alert) {
                setTimeout(function () {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(function () {
                        alert.remove();
                    }, 500);
                }, 4000);
            });
        });
    </script>

    {{-- NOTE: Settings Modal versi ApexForge (Tailwind penuh) berada di
         resources/views/navbar/nav.blade.php (#modalSettings, dibuka via
         #btnBukaPengaturan). Duplikat Bootstrap lama yang ada di file ini
         telah dihapus karena tidak berfungsi (Bootstrap JS tidak pernah
         dimuat di layout Admin) dan menyebabkan konflik ID dengan modal
         Tailwind yang aktif. --}}

{{-- ApexForge Labs — Global Custom Confirmation Popup --}}
<div id="afConfirmOverlay" class="af-confirm-overlay" role="dialog" aria-modal="true" aria-labelledby="afConfirmTitle" aria-describedby="afConfirmMessage">
    <div class="af-confirm-card">
        <button type="button" class="af-confirm-close" id="afConfirmClose" aria-label="Tutup">&times;</button>
        <div class="af-confirm-icon">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <h3 id="afConfirmTitle" class="af-confirm-title">Konfirmasi</h3>
        <p id="afConfirmMessage" class="af-confirm-message"></p>
        <div class="af-confirm-actions">
            <button type="button" id="afConfirmCancel" class="af-btn af-btn-cancel">Batal</button>
            <button type="button" id="afConfirmOk" class="af-btn af-btn-ok">Ya, Lanjutkan</button>
        </div>
    </div>
</div>

<style>
    .af-confirm-overlay{
        position:fixed;inset:0;z-index:99999;
        display:flex;align-items:center;justify-content:center;padding:1rem;
        background:rgba(15,23,42,.55);
        backdrop-filter:blur(4px);-webkit-backdrop-filter:blur(4px);
        opacity:0;visibility:hidden;pointer-events:none;
        transition:opacity .2s ease,visibility .2s ease;
    }
    .af-confirm-overlay.af-open{opacity:1;visibility:visible;pointer-events:auto}
    .af-confirm-card{
        position:relative;width:100%;max-width:26rem;
        background:#ffffff;border:1px solid #dbeafe;border-radius:1.25rem;
        padding:2rem;box-shadow:0 25px 60px -15px rgba(30,64,175,.45);
        transform:translateY(12px) scale(.96);
        transition:transform .2s ease;
    }
    .af-open .af-confirm-card{transform:translateY(0) scale(1)}
    .af-confirm-close{
        position:absolute;top:.85rem;right:.95rem;
        width:2rem;height:2rem;display:flex;align-items:center;justify-content:center;
        border:none;background:transparent;color:#94a3b8;font-size:1.35rem;line-height:1;
        border-radius:.6rem;cursor:pointer;
    }
    .af-confirm-close:hover{background:#f1f5f9;color:#475569}
    .af-confirm-icon{
        width:3.25rem;height:3.25rem;margin-bottom:1rem;
        display:flex;align-items:center;justify-content:center;
        background:#fef3c7;color:#d97706;font-size:1.35rem;border-radius:1rem;
    }
    .af-confirm-title{font-weight:800;font-size:1.125rem;color:#0f172a;margin:0 0 .5rem}
    .af-confirm-message{font-size:.9rem;line-height:1.55;color:#64748b;margin:0 0 1.5rem}
    .af-confirm-actions{display:flex;justify-content:flex-end;gap:.65rem}
    .af-btn{
        padding:.625rem 1.25rem;font-size:.875rem;font-weight:600;
        border-radius:.75rem;cursor:pointer;border:none;transition:all .2s ease;
    }
    .af-btn-cancel{background:#f1f5f9;color:#475569}
    .af-btn-cancel:hover{background:#e2e8f0}
    .af-btn-ok{background:#2563eb;color:#ffffff;box-shadow:0 8px 22px -12px rgba(37,99,235,.72)}
    .af-btn-ok:hover{background:#1d4ed8}
    @media (prefers-color-scheme:dark){
        .af-confirm-card{background:#0f172a;border-color:#1e293b;box-shadow:0 25px 60px -15px rgba(0,0,0,.7)}
        .af-confirm-title{color:#f1f5f9}
        .af-confirm-message{color:#94a3b8}
        .af-confirm-close:hover{background:#1e293b;color:#cbd5e1}
        .af-btn-cancel{background:#1e293b;color:#cbd5e1}
        .af-btn-cancel:hover{background:#334155}
    }
.custom-setting-nav .nav-link.active i {
        color: #0d6efd !important;
    }
</style>


{{-- ==========================================================
    ADMIN CONFIRM MODAL (reusable — pengganti confirm() native)
    Dipakai via: adminConfirm('pesan', thisForm)
    Z-index 9999 -> selalu di atas navbar/sidebar/modal.
========================================================== --}}
<div id="adminConfirmModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-slate-950/60 backdrop-blur-sm p-4">
    <div class="w-full max-w-sm bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-2xl p-6 relative">
        <button type="button" onclick="adminConfirmClose()" class="absolute top-3 right-3 w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-600 dark:hover:text-white transition-colors" aria-label="Tutup">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="flex items-start gap-4">
            <span class="shrink-0 w-11 h-11 rounded-xl bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 flex items-center justify-center text-lg">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </span>
            <div class="min-w-0 flex-1">
                <h3 class="text-sm font-bold text-slate-800 dark:text-white">Konfirmasi</h3>
                <p id="adminConfirmMessage" class="mt-1 text-xs text-slate-500 dark:text-slate-400 leading-relaxed break-words"></p>
            </div>
        </div>

        <div class="mt-5 grid grid-cols-2 gap-2.5">
            <button type="button" id="adminConfirmCancel" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">Batal</button>
            <button type="button" id="adminConfirmOk" class="px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-red-600 hover:bg-red-700 transition-colors">Ya, Lanjutkan</button>
        </div>
    </div>
</div>

<script>
    (function () {
        const modal          = document.getElementById('adminConfirmModal');
        const messageEl      = document.getElementById('adminConfirmMessage');
        const cancelBtn      = document.getElementById('adminConfirmCancel');
        const okBtn          = document.getElementById('adminConfirmOk');
        let   pendingForm    = null;

        window.adminConfirm = function (message, formEl) {
            pendingForm = formEl || null;
            messageEl.textContent = message;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            okBtn.disabled = false;
            okBtn.innerHTML = '<i class="fa-solid fa-check mr-1"></i>Ya, Lanjutkan';
            return false;
        };

        window.adminConfirmClose = function () {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            pendingForm = null;
        };

        cancelBtn.addEventListener('click', adminConfirmClose);

        okBtn.addEventListener('click', function () {
            if (!pendingForm) { adminConfirmClose(); return; }
            okBtn.disabled = true;
            okBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i>Memproses...';
            pendingForm.submit();
        });

        modal.addEventListener('click', function (e) {
            if (e.target === modal) adminConfirmClose();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) adminConfirmClose();
        });
    })();
</script>

</body>
</html>
