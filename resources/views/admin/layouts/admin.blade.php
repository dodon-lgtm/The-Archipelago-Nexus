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

    <!-- MODAL SETTINGS (Muncul di tengah layar) -->
<div class="modal fade" id="modalSettings" tabindex="-1" aria-labelledby="modalSettingsLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 1.5rem; overflow: hidden;">
            
            <div class="row g-0">
                <!-- Sisi Kiri: Menu Sidebar -->
                <div class="col-md-4 bg-light p-4 p-md-5 border-end">
                    <h3 class="fw-bold text-dark mb-1">Settings</h3>
                    <p class="text-muted small mb-4">Kelola pengaturan akun dan preferensi Anda</p>

                    <div class="nav flex-column nav-pills custom-setting-nav" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <!-- Menu Security (Aktif) -->
                        <button class="nav-link active d-flex align-items-start py-3 mb-2" data-bs-toggle="pill" data-bs-target="#tab-security" type="button">
                            <i class="fas fa-shield-alt mt-1 me-3 fs-5 text-primary"></i>
                            <div class="text-start">
                                <div class="fw-semibold">Security</div>
                                <small style="font-size: 0.75rem;">Password, 2FA, dan sesi akun</small>
                            </div>
                        </button>

                        <!-- Menu Payment -->
                        <button class="nav-link d-flex align-items-start py-3 mb-2" type="button">
                            <i class="fas fa-credit-card mt-1 me-3 fs-5 text-secondary"></i>
                            <div class="text-start">
                                <div class="fw-semibold text-dark">Payment & Payout</div>
                                <small class="text-muted" style="font-size: 0.75rem;">Metode pembayaran dan pencairan dana</small>
                            </div>
                        </button>

                        <!-- Menu Notifications -->
                        <button class="nav-link d-flex align-items-start py-3 mb-2" type="button">
                            <i class="fas fa-bell mt-1 me-3 fs-5 text-secondary"></i>
                            <div class="text-start">
                                <div class="fw-semibold text-dark">Notifications</div>
                                <small class="text-muted" style="font-size: 0.75rem;">Atur preferensi notifikasi Anda</small>
                            </div>
                        </button>

                        <!-- Menu Account -->
                        <button class="nav-link d-flex align-items-start py-3 mb-2" type="button">
                            <i class="fas fa-user mt-1 me-3 fs-5 text-secondary"></i>
                            <div class="text-start">
                                <div class="fw-semibold text-dark">Account</div>
                                <small class="text-muted" style="font-size: 0.75rem;">Kelola akun dan data Anda</small>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Sisi Kanan: Konten Utama -->
                <div class="col-md-8 p-4 p-md-5 bg-white position-relative">
                    <!-- Tombol Close Modal (X) -->
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-4" data-bs-dismiss="modal" aria-label="Close"></button>

                    <div class="tab-content" id="v-pills-tabContent">
                        
                        <!-- Konten Security -->
                        <div class="tab-pane fade show active" id="tab-security" role="tabpanel">
                            <h4 class="fw-bold text-dark mb-1">Security</h4>
                            <p class="text-muted small mb-4">Kelola keamanan akun Anda untuk menjaga akun tetap aman.</p>

                            <!-- Item 1: Ubah Password -->
                            <div class="card border border-light-subtle shadow-sm mb-3" style="border-radius: 1rem;">
                                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                                            <i class="fas fa-lock text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Ubah Password</h6>
                                            <p class="text-muted small mb-0">Gunakan password yang kuat untuk melindungi akun Anda.</p>
                                        </div>
                                    </div>
                                    <button class="btn btn-outline-primary btn-sm px-3 rounded-pill fw-semibold">Ubah Password</button>
                                </div>
                            </div>

                            <!-- Item 2: Verifikasi Email -->
                            <div class="card border border-light-subtle shadow-sm mb-3" style="border-radius: 1rem;">
                                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                                            <i class="fas fa-envelope text-success"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Verifikasi Email</h6>
                                            <p class="text-muted small mb-0">Email Anda telah diverifikasi.</p>
                                        </div>
                                    </div>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3 py-2">
                                        <i class="fas fa-check-circle me-1"></i> Terverifikasi
                                    </span>
                                </div>
                            </div>

                            <!-- Item 3: 2FA -->
                            <div class="card border border-light-subtle shadow-sm mb-3" style="border-radius: 1rem;">
                                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                                            <i class="fas fa-shield-alt text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Verifikasi 2FA</h6>
                                            <p class="text-muted small mb-0">Tambahkan lapisan keamanan ekstra dengan autentikasi dua faktor.</p>
                                        </div>
                                    </div>
                                    <button class="btn btn-outline-primary btn-sm px-3 rounded-pill fw-semibold">Aktifkan 2FA</button>
                                </div>
                            </div>

                            <!-- Item 4: Logout Semua -->
                            <div class="card border border-danger-subtle shadow-sm" style="border-radius: 1rem;">
                                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-danger bg-opacity-10 p-3 rounded-circle me-3">
                                            <i class="fas fa-sign-out-alt text-danger"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1 text-danger">Logout dari Semua Perangkat</h6>
                                            <p class="text-muted small mb-0">Keluar dari semua perangkat kecuali yang Anda gunakan sekarang.</p>
                                        </div>
                                    </div>
                                    <button class="btn btn-outline-danger btn-sm px-3 rounded-pill fw-semibold">Logout Semua</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan sedikit CSS agar hover dan tombol sampingnya mirip desain -->
<style>
    .custom-setting-nav .nav-link {
        color: #495057;
        border-radius: 12px;
        transition: all 0.3s ease;
        background-color: transparent;
        border: 1px solid transparent;
    }
    .custom-setting-nav .nav-link:hover {
        background-color: #f8f9fa;
        border-color: #e9ecef;
    }
    .custom-setting-nav .nav-link.active {
        background-color: #e7f1ff; /* Biru pudar */
        color: #0d6efd;
        border-color: #0d6efd33;
    }
    .custom-setting-nav .nav-link.active .text-dark,
    .custom-setting-nav .nav-link.active .text-muted {
        color: #0d6efd !important;
    }
    .custom-setting-nav .nav-link.active i {
        color: #0d6efd !important;
    }
</style>

</body>
</html>
