<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.theme-boot')
    <title>@yield('title', 'Admin Panel') - ApexForge Labs</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = tailwind.config || {};
        tailwind.config.darkMode = 'class';
        tailwind.config.theme = {
            extend: {
                colors: {
                    brand: {
                        50: '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe', 300: '#93c5fd',
                        400: '#60a5fa', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8',
                        800: '#1e40af', 900: '#1e3a8a', 950: '#0f172a',
                    }
                }
            }
        };
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(148, 163, 184, 0.35); border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(148, 163, 184, 0.55); }
        html.dark ::-webkit-scrollbar-thumb { background: rgba(71, 85, 105, 0.45); }
        html.dark ::-webkit-scrollbar-thumb:hover { background: rgba(71, 85, 105, 0.7); }
        .custom-sidebar-scroll { scrollbar-width: thin; scrollbar-color: rgba(148, 163, 184, 0.35) transparent; }
        .custom-sidebar-scroll::-webkit-scrollbar { width: 5px; }
        .custom-sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(148, 163, 184, 0.28); border-radius: 9999px; }
        .custom-sidebar-scroll::-webkit-scrollbar-thumb:hover { background: rgba(148, 163, 184, 0.5); }
        html.dark .custom-sidebar-scroll { scrollbar-color: rgba(71, 85, 105, 0.55) transparent; }
        html.dark .custom-sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(71, 85, 105, 0.5); }
        html.dark .custom-sidebar-scroll::-webkit-scrollbar-thumb:hover { background: rgba(71, 85, 105, 0.75); }
        /* ============ APP SHELL: viewport terkunci, hanya konten yang scroll ============ */
        /* Body = kerangka setinggi layar. 100vh sebagai fallback, lalu 100dvh agar tidak
           terpotong saat URL bar mobile muncul/hilang. Ditulis di CSS (bukan utility class)
           supaya JS yang menambah/menghapus kelas 'overflow-hidden' pada body tidak
           pernah menimpa kunci tinggi ini. */
        body { height: 100vh; height: 100dvh; overflow: hidden; }
        /* Saat drawer/modal mengunci body (JS menambah .overflow-hidden ATAU mengeset
           inline body.style.overflow='hidden'), area <main> ikut terkunci supaya konten
           tidak ikut scroll di belakang overlay. */
        body.overflow-hidden main,
        body[style*="overflow"] main { overflow-y: hidden !important; }
        /* Sidebar selalu setinggi viewport (100dvh, fallback 100vh) & tidak pernah terpotong/kerut */
        #sidebar { height: 100vh; min-height: 100vh; height: 100dvh; min-height: 100dvh; transition: width 0.25s cubic-bezier(0.4, 0, 0.2, 1), transform 0.25s cubic-bezier(0.4, 0, 0.2, 1); }
        #sidebar.collapsed { width: 80px !important; }
        #sidebar.collapsed .sidebar-logo-text, #sidebar.collapsed .sidebar-nav-label, #sidebar.collapsed .sidebar-footer-card, #sidebar.collapsed .sidebar-badge { display: none !important; opacity: 0; visibility: hidden; }
        #sidebar.collapsed nav a { justify-content: center; padding-left: 0; padding-right: 0; margin-left: auto; margin-right: auto; width: 44px; height: 44px; border-radius: 12px; }
        #sidebar.collapsed nav a i { margin: 0; font-size: 1.15rem; }
        #sidebar.collapsed .sidebar-logo-wrapper { justify-content: center; padding-left: 0.5rem; padding-right: 0.5rem; }
        #sidebar.collapsed .sidebar-footer-mini { display: flex !important; }
        #sidebar.collapsed .sidebar-toggle-icon { transform: rotate(180deg); }
        #sidebar.collapsed nav a { position: relative; }
        #sidebar.collapsed nav a:hover::after { content: attr(data-tooltip); position: fixed; left: 92px; top: var(--tooltip-top, 50%); transform: translateY(-50%); background: #0f172a; color: #ffffff; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 700; letter-spacing: 0.02em; white-space: nowrap; z-index: 100; pointer-events: none; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3); border: 1px solid rgba(255, 255, 255, 0.1); }
        html.dark #sidebar.collapsed nav a:hover::after { background: #1e293b; color: #f8fafc; border-color: #334155; }
        @media (max-width: 1023px) {
            #sidebar { position: fixed !important; top: 0; left: 0; bottom: 0; z-index: 50 !important; transform: translateX(-100%); width: 290px !important; box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25); }
            #sidebar.mobile-open { transform: translateX(0); }
            #sidebar.collapsed { width: 290px !important; }
            #sidebar.collapsed .sidebar-logo-text, #sidebar.collapsed .sidebar-nav-label, #sidebar.collapsed .sidebar-footer-card, #sidebar.collapsed .sidebar-badge { display: block !important; opacity: 1; visibility: visible; }
            #sidebar.collapsed nav a { justify-content: flex-start; padding-left: 1rem; padding-right: 1rem; width: 100%; height: auto; }
            #sidebar.collapsed nav a i { margin-right: 0.75rem; font-size: 1rem; }
            #sidebar.collapsed .sidebar-footer-mini { display: none !important; }
            #sidebar.collapsed nav a:hover::after { display: none !important; }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 dark:bg-[#0B1120] text-slate-900 dark:text-slate-100 antialiased flex flex-col selection:bg-blue-600 selection:text-white transition-colors duration-200">


    {{-- TOP MOBILE BAR / HAMBURGER TRIGGER --}}
    <div class="lg:hidden shrink-0 flex items-center justify-between h-14 px-4 bg-white/95 dark:bg-[#0F172A]/95 backdrop-blur-md border-b border-slate-200/80 dark:border-slate-800 z-40">
        <div class="flex items-center gap-3">
            <button type="button" id="mobileSidebarToggleBtn" aria-label="Buka Menu"
                class="w-10 h-10 rounded-xl flex items-center justify-center text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 active:scale-95 transition-all">
                <i class="fa-solid fa-bars text-lg"></i>
            </button>
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg overflow-hidden bg-blue-600 flex items-center justify-center text-white text-xs font-black shadow-sm">
                    AF
                </div>
                <span class="font-extrabold text-sm tracking-tight text-slate-900 dark:text-white">ApexForge <span class="text-blue-600 dark:text-blue-400">Admin</span></span>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.dashboard') }}" class="w-8 h-8 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white text-xs font-bold shadow-sm">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </a>
        </div>
    </div>

    {{-- SHELL: baris flex setinggi body (h-screen body via CSS) & tidak pernah ikut scroll --}}
    <div class="flex flex-1 min-h-0 min-w-0 w-full overflow-hidden">

        {{-- =============== SIDEBAR =============== --}}
        <aside id="sidebar"
            class="w-64 h-screen bg-white dark:bg-[#0F172A] border-r border-slate-200/80 dark:border-slate-800/80 flex flex-col justify-between shrink-0 z-30 transition-colors duration-200 select-none">

            {{-- Sidebar Header / Brand --}}
            <div class="sidebar-logo-wrapper h-16 px-5 flex items-center justify-between border-b border-slate-100 dark:border-slate-800/80 shrink-0">
                <div class="flex items-center gap-3 overflow-hidden">
                    <div class="sidebar-logo-circle w-9 h-9 rounded-xl overflow-hidden shrink-0 border border-slate-200 dark:border-slate-700 shadow-sm bg-blue-600 flex items-center justify-center text-white font-black text-sm">
                        <img src="{{ asset('images/nexus.jpg') }}" alt="Logo" class="w-full h-full object-cover" onerror="this.remove()">
                    </div>
                    <div class="sidebar-logo-text truncate">
                        <h2 class="font-extrabold text-sm tracking-tight text-slate-900 dark:text-white leading-tight">ApexForge</h2>
                        <span class="text-[10px] font-bold text-blue-600 dark:text-blue-400 tracking-wider uppercase">Control Center</span>
                    </div>
                </div>

                <button id="sidebarToggle" type="button" aria-label="Collapse Sidebar"
                    class="hidden lg:flex w-8 h-8 rounded-lg text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 items-center justify-center transition-colors">
                    <i class="sidebar-toggle-icon fa-solid fa-chevron-left text-xs transition-transform duration-200"></i>
                </button>
                <button id="mobileSidebarClose" type="button" aria-label="Tutup Sidebar"
                    class="lg:hidden w-8 h-8 rounded-lg text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            {{-- Sidebar Navigation --}}
            <nav class="flex-1 min-h-0 px-3 py-4 space-y-1 overflow-y-auto overscroll-contain custom-sidebar-scroll">
                <div class="px-3 pb-1.5 pt-1 text-[10px] font-extrabold uppercase tracking-widest text-slate-400 dark:text-slate-500 sidebar-nav-label">
                    Menu Utama
                </div>

                {{-- Dashboard --}}
                <a href="{{ route('admin.dashboard') }}"
                    class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150
                    {{ request()->routeIs('admin.dashboard')
                        ? 'bg-blue-600 text-white shadow-sm shadow-blue-500/30 font-bold'
                        : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100/90 dark:hover:bg-slate-800/80 hover:text-slate-900 dark:hover:text-white' }}"
                    data-tooltip="Dashboard">
                    <i class="fa-solid fa-chart-pie w-5 text-center text-[15px] {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-400 dark:text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400' }} transition-colors"></i>
                    <span class="sidebar-nav-label truncate">Dashboard</span>
                </a>

                {{-- Pengguna --}}
                <a href="{{ route('admin.users.index') }}"
                    class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150
                    {{ request()->routeIs('admin.users.*')
                        ? 'bg-blue-600 text-white shadow-sm shadow-blue-500/30 font-bold'
                        : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100/90 dark:hover:bg-slate-800/80 hover:text-slate-900 dark:hover:text-white' }}"
                    data-tooltip="Pengguna">
                    <i class="fa-solid fa-users w-5 text-center text-[15px] {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-slate-400 dark:text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400' }} transition-colors"></i>
                    <span class="sidebar-nav-label truncate">Pengguna</span>
                </a>

                {{-- Permintaan Akun Perusahaan --}}
                <a href="{{ route('admin.company-account-requests.index') }}"
                    class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150
                    {{ request()->routeIs('admin.company-account-requests.*')
                        ? 'bg-blue-600 text-white shadow-sm shadow-blue-500/30 font-bold'
                        : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100/90 dark:hover:bg-slate-800/80 hover:text-slate-900 dark:hover:text-white' }}"
                    data-tooltip="Verifikasi Perusahaan">
                    <i class="fa-solid fa-building-circle-check w-5 text-center text-[15px] {{ request()->routeIs('admin.company-account-requests.*') ? 'text-white' : 'text-slate-400 dark:text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400' }} transition-colors"></i>
                    <span class="sidebar-nav-label truncate">Akun Perusahaan</span>
                </a>

                {{-- Kategori --}}
                <a href="{{ route('admin.categories.index') }}"
                    class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150
                    {{ request()->routeIs('admin.categories.*')
                        ? 'bg-blue-600 text-white shadow-sm shadow-blue-500/30 font-bold'
                        : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100/90 dark:hover:bg-slate-800/80 hover:text-slate-900 dark:hover:text-white' }}"
                    data-tooltip="Kategori">
                    <i class="fa-solid fa-tags w-5 text-center text-[15px] {{ request()->routeIs('admin.categories.*') ? 'text-white' : 'text-slate-400 dark:text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400' }} transition-colors"></i>
                    <span class="sidebar-nav-label truncate">Kategori</span>
                </a>

                {{-- Proyek --}}
                <a href="{{ route('admin.projects.index') }}"
                    class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150
                    {{ request()->routeIs('admin.projects.*')
                        ? 'bg-blue-600 text-white shadow-sm shadow-blue-500/30 font-bold'
                        : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100/90 dark:hover:bg-slate-800/80 hover:text-slate-900 dark:hover:text-white' }}"
                    data-tooltip="Proyek">
                    <i class="fa-solid fa-folder-open w-5 text-center text-[15px] {{ request()->routeIs('admin.projects.*') ? 'text-white' : 'text-slate-400 dark:text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400' }} transition-colors"></i>
                    <span class="sidebar-nav-label truncate">Proyek</span>
                </a>

                {{-- Penawaran --}}
                <a href="{{ route('admin.penawarans.index') }}"
                    class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150
                    {{ request()->routeIs('admin.penawarans.*')
                        ? 'bg-blue-600 text-white shadow-sm shadow-blue-500/30 font-bold'
                        : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100/90 dark:hover:bg-slate-800/80 hover:text-slate-900 dark:hover:text-white' }}"
                    data-tooltip="Penawaran">
                    <i class="fa-solid fa-file-invoice-dollar w-5 text-center text-[15px] {{ request()->routeIs('admin.penawarans.*') ? 'text-white' : 'text-slate-400 dark:text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400' }} transition-colors"></i>
                    <span class="sidebar-nav-label truncate">Penawaran</span>
                </a>

                {{-- Hasil Pekerjaan --}}
                <a href="{{ route('admin.hasil-pekerjaan.index') }}"
                    class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150
                    {{ request()->routeIs('admin.hasil-pekerjaan.*')
                        ? 'bg-blue-600 text-white shadow-sm shadow-blue-500/30 font-bold'
                        : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100/90 dark:hover:bg-slate-800/80 hover:text-slate-900 dark:hover:text-white' }}"
                    data-tooltip="Hasil Pekerjaan">
                    <i class="fa-solid fa-layer-group w-5 text-center text-[15px] {{ request()->routeIs('admin.hasil-pekerjaan.*') ? 'text-white' : 'text-slate-400 dark:text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400' }} transition-colors"></i>
                    <span class="sidebar-nav-label truncate">Hasil Pekerjaan</span>
                </a>

                <div class="px-3 pb-1.5 pt-4 text-[10px] font-extrabold uppercase tracking-widest text-slate-400 dark:text-slate-500 sidebar-nav-label">
                    Keuangan & Sistem
                </div>

                {{-- Pembayaran --}}
                <a href="{{ route('admin.payments.index') }}"
                    class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150
                    {{ request()->routeIs('admin.payments.*')
                        ? 'bg-blue-600 text-white shadow-sm shadow-blue-500/30 font-bold'
                        : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100/90 dark:hover:bg-slate-800/80 hover:text-slate-900 dark:hover:text-white' }}"
                    data-tooltip="Pembayaran">
                    <i class="fa-solid fa-credit-card w-5 text-center text-[15px] {{ request()->routeIs('admin.payments.*') ? 'text-white' : 'text-slate-400 dark:text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400' }} transition-colors"></i>
                    <span class="sidebar-nav-label truncate">Pembayaran</span>
                </a>

                {{-- Penarikan Dana (Withdrawals) --}}
                <a href="{{ route('admin.withdrawals.index') }}"
                    class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150
                    {{ request()->routeIs('admin.withdrawals.*')
                        ? 'bg-blue-600 text-white shadow-sm shadow-blue-500/30 font-bold'
                        : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100/90 dark:hover:bg-slate-800/80 hover:text-slate-900 dark:hover:text-white' }}"
                    data-tooltip="Penarikan Dana">
                    <i class="fa-solid fa-money-bill-transfer w-5 text-center text-[15px] {{ request()->routeIs('admin.withdrawals.*') ? 'text-white' : 'text-slate-400 dark:text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400' }} transition-colors"></i>
                    <span class="sidebar-nav-label truncate">Penarikan Dana</span>
                </a>

                {{-- Wallet Admin --}}
                <a href="{{ route('admin.wallet.index') }}"
                    class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150
                    {{ request()->routeIs('admin.wallet.*')
                        ? 'bg-blue-600 text-white shadow-sm shadow-blue-500/30 font-bold'
                        : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100/90 dark:hover:bg-slate-800/80 hover:text-slate-900 dark:hover:text-white' }}"
                    data-tooltip="Wallet Platform">
                    <i class="fa-solid fa-wallet w-5 text-center text-[15px] {{ request()->routeIs('admin.wallet.*') ? 'text-white' : 'text-slate-400 dark:text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400' }} transition-colors"></i>
                    <span class="sidebar-nav-label truncate">Wallet Platform</span>
                </a>

                {{-- Financial Settings --}}
                <a href="{{ route('admin.financial-settings.edit') }}"
                    class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150
                    {{ request()->routeIs('admin.financial-settings.*')
                        ? 'bg-blue-600 text-white shadow-sm shadow-blue-500/30 font-bold'
                        : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100/90 dark:hover:bg-slate-800/80 hover:text-slate-900 dark:hover:text-white' }}"
                    data-tooltip="Biaya Platform">
                    <i class="fa-solid fa-sliders w-5 text-center text-[15px] {{ request()->routeIs('admin.financial-settings.*') ? 'text-white' : 'text-slate-400 dark:text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400' }} transition-colors"></i>
                    <span class="sidebar-nav-label truncate">Biaya Platform</span>
                </a>

                {{-- Laporan --}}
                <a href="{{ route('admin.reports.index') }}"
                    class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150
                    {{ request()->routeIs('admin.reports.*')
                        ? 'bg-blue-600 text-white shadow-sm shadow-blue-500/30 font-bold'
                        : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100/90 dark:hover:bg-slate-800/80 hover:text-slate-900 dark:hover:text-white' }}"
                    data-tooltip="Laporan Masalah">
                    <i class="fa-solid fa-flag w-5 text-center text-[15px] {{ request()->routeIs('admin.reports.*') ? 'text-white' : 'text-slate-400 dark:text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400' }} transition-colors"></i>
                    <span class="sidebar-nav-label truncate">Laporan & Dispute</span>
                </a>

                <div class="px-3 pb-1.5 pt-4 text-[10px] font-extrabold uppercase tracking-widest text-slate-400 dark:text-slate-500 sidebar-nav-label">
                    Konfigurasi
                </div>

                {{-- Kebijakan & Privasi --}}
                <a href="{{ route('admin.policies.index') }}"
                    class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150
                    {{ request()->routeIs('admin.policies.*')
                        ? 'bg-blue-600 text-white shadow-sm shadow-blue-500/30 font-bold'
                        : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100/90 dark:hover:bg-slate-800/80 hover:text-slate-900 dark:hover:text-white' }}"
                    data-tooltip="Kebijakan Privasi">
                    <i class="fa-solid fa-shield-halved w-5 text-center text-[15px] {{ request()->routeIs('admin.policies.*') ? 'text-white' : 'text-slate-400 dark:text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400' }} transition-colors"></i>
                    <span class="sidebar-nav-label truncate">Kebijakan & Privasi</span>
                </a>

                {{-- Footer Settings --}}
                <a href="{{ route('admin.footer-settings.edit') }}"
                    class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150
                    {{ request()->routeIs('admin.footer-settings.*')
                        ? 'bg-blue-600 text-white shadow-sm shadow-blue-500/30 font-bold'
                        : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100/90 dark:hover:bg-slate-800/80 hover:text-slate-900 dark:hover:text-white' }}"
                    data-tooltip="Footer Website">
                    <i class="fa-solid fa-window-maximize w-5 text-center text-[15px] {{ request()->routeIs('admin.footer-settings.*') ? 'text-white' : 'text-slate-400 dark:text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400' }} transition-colors"></i>
                    <span class="sidebar-nav-label truncate">Footer Website</span>
                </a>

                {{-- Back to Home --}}
                <a href="{{ url('/') }}"
                    class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-slate-500 dark:text-slate-400 hover:bg-slate-100/80 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white transition-colors"
                    data-tooltip="Lihat Website Publik">
                    <i class="fa-solid fa-arrow-up-right-from-square w-5 text-center text-[14px]"></i>
                    <span class="sidebar-nav-label truncate">Halaman Publik</span>
                </a>
            </nav>

            {{-- Sidebar Footer --}}
            <div class="shrink-0 p-4 border-t border-slate-200 dark:border-slate-800">
                <div class="sidebar-footer-card p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/70 dark:border-slate-700/60 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 text-xs">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-bold text-slate-900 dark:text-white truncate">Sistem Aktif</p>
                        <p class="text-[10px] font-semibold text-slate-500 dark:text-slate-400 truncate">ApexForge Admin v2.0</p>
                    </div>
                    <span class="shrink-0 w-2 h-2 rounded-full bg-emerald-500 animate-pulse" aria-hidden="true"></span>
                </div>
                <div class="sidebar-footer-mini hidden justify-center py-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 ring-4 ring-emerald-500/20" title="Sistem Aktif"></span>
                </div>
            </div>
        </aside>

        {{-- MOBILE OVERLAY --}}
        <div id="sidebarOverlay" class="hidden fixed inset-0 bg-slate-950/50 backdrop-blur-sm z-40 lg:hidden transition-opacity duration-200"></div>

        {{-- =============== MAIN CONTENT AREA =============== --}}
        <div class="flex-1 min-w-0 min-h-0 flex flex-col">

            {{-- Desktop Topbar --}}
            <header class="h-16 shrink-0 bg-white/95 dark:bg-[#0F172A]/95 backdrop-blur-md border-b border-slate-200/80 dark:border-slate-800/80 px-4 sm:px-6 lg:px-8 flex items-center justify-between gap-4 z-20 transition-colors duration-200">

                {{-- Left: Page Breadcrumb / Title --}}
                <div class="min-w-0 flex-1 flex items-center gap-3">
                    <nav class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400 min-w-0" aria-label="Breadcrumb">
                        <a href="{{ route('admin.dashboard') }}" class="font-bold text-slate-800 dark:text-slate-200 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Admin</a>
                        <i class="fa-solid fa-chevron-right text-[9px] text-slate-400 dark:text-slate-600"></i>
                        <span class="text-blue-600 dark:text-blue-400 font-bold truncate">@yield('breadcrumb', 'Dashboard')</span>
                    </nav>
                </div>

                {{-- Right: Quick Actions & Profile --}}
                <div class="flex items-center gap-2.5 sm:gap-3.5 shrink-0">

                    {{-- Notifications Button & Dropdown --}}
                    <div class="relative">
                        <button id="adminNotificationButton" aria-label="Notifikasi" type="button"
                            class="relative w-10 h-10 rounded-xl border border-slate-200 dark:border-slate-700/80 hover:border-slate-300 dark:hover:border-slate-600 bg-white dark:bg-slate-800/80 hover:bg-slate-50 dark:hover:bg-slate-700 flex items-center justify-center text-slate-600 dark:text-slate-300 transition-all focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                            <i class="fa-regular fa-bell text-sm"></i>
                            <span id="adminNotificationBadge"
                                class="hidden absolute -top-1 -right-1 min-w-[18px] h-[18px] rounded-full bg-rose-500 text-white text-[9px] font-black flex items-center justify-center px-1 border-2 border-white dark:border-[#0F172A] shadow-sm">
                            </span>
                        </button>

                        {{-- Dropdown Notifikasi --}}
                        <div id="adminNotificationDropdown"
                            class="hidden absolute right-0 mt-3 w-[min(380px,calc(100vw-1.5rem))] bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl dark:shadow-black/50 overflow-hidden z-[100] transition-all">
                            <div class="px-5 py-3.5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/50">
                                <h3 class="font-extrabold text-xs text-slate-900 dark:text-white uppercase tracking-wider">Notifikasi Sistem</h3>
                                <button id="adminMarkAllReadBtn" type="button"
                                    class="text-[11px] font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 transition-colors">
                                    Tandai dibaca
                                </button>
                            </div>
                            <div id="adminNotificationList" class="max-h-[340px] overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800">
                                <div class="p-8 text-center text-xs text-slate-400">
                                    <i class="fa-regular fa-bell-slash text-xl mb-2 block opacity-40"></i>
                                    Tidak ada notifikasi baru
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Administrator Profile Menu --}}
                    <div class="relative">
                        <button id="adminProfileButton" type="button" onclick="toggleProfileDropdown()"
                            aria-haspopup="true" aria-expanded="false" aria-label="Menu profil admin"
                            class="flex items-center gap-2.5 rounded-xl pl-2 pr-3 py-1.5 border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-800/80 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white text-xs font-black shadow-sm shrink-0">
                                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                            </div>
                            <div class="text-left hidden sm:block leading-tight pr-1">
                                <span class="block text-xs font-bold text-slate-900 dark:text-white truncate max-w-[130px]">{{ auth()->user()->name ?? 'Admin' }}</span>
                                <span class="block text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Superadmin</span>
                            </div>
                            <i id="adminProfileChevron" class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200"></i>
                        </button>

                        {{-- Profile Dropdown --}}
                        <div id="profileDropdown"
                            class="hidden absolute right-0 mt-3 w-64 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl dark:shadow-black/50 overflow-hidden z-50">
                            <div class="p-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40">
                                <p class="font-extrabold text-sm text-slate-900 dark:text-white truncate">{{ auth()->user()->name ?? 'Administrator' }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 truncate mt-0.5">{{ auth()->user()->email ?? '' }}</p>
                            </div>
                            <div class="p-1.5 space-y-0.5">
                                <a href="{{ route('admin.dashboard') }}"
                                    class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                    <i class="fa-solid fa-chart-pie w-4 text-blue-500"></i> Dashboard Control
                                </a>
                                <a href="{{ route('admin.wallet.index') }}"
                                    class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                    <i class="fa-solid fa-wallet w-4 text-emerald-500"></i> Wallet Platform
                                </a>
                                <a href="{{ route('admin.financial-settings.edit') }}"
                                    class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                    <i class="fa-solid fa-sliders w-4 text-slate-500"></i> Pengaturan Biaya
                                </a>
                            </div>
                            {{-- Pengalih Tema (Light / Dark) — sliding switch --}}
                            <div class="p-1.5 border-t border-slate-100 dark:border-slate-800">
                                <button type="button" id="adminThemeToggle" role="switch" aria-checked="false"
                                    aria-label="Ganti tema terang/gelap" title="Ganti tema terang/gelap"
                                    class="group w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors text-left">
                                    <span class="flex items-center gap-3 min-w-0">
                                        <span class="relative w-4 h-4 shrink-0 flex items-center justify-center">
                                            <i class="admin-theme-moon fa-regular fa-moon w-4 text-center text-slate-500 dark:text-slate-400 transition-colors"></i>
                                            <i class="admin-theme-sun fa-solid fa-sun w-4 text-center text-amber-400 hidden"></i>
                                        </span>
                                        <span class="min-w-0 leading-tight">
                                            <span class="block truncate">Tema Tampilan</span>
                                            <span id="adminThemeLabel" class="block text-[10px] font-semibold text-slate-400 dark:text-slate-500 truncate">Mode Terang</span>
                                        </span>
                                    </span>
                                    <span id="adminThemeSwitch" aria-hidden="true"
                                        class="relative inline-flex h-6 w-11 shrink-0 items-center rounded-full transition-colors duration-300 ease-out bg-slate-300 dark:bg-blue-600">
                                        <span id="adminThemeKnob"
                                            class="inline-block h-[18px] w-[18px] translate-x-[3px] dark:translate-x-[23px] transform rounded-full bg-white shadow-sm ring-1 ring-slate-900/5 transition-transform duration-300 ease-out"></span>
                                    </span>
                                </button>
                            </div>
                            <div class="p-1.5 border-t border-slate-100 dark:border-slate-800">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-bold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors text-left">
                                        <i class="fa-solid fa-arrow-right-from-bracket w-4"></i> Keluar (Logout)
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </header>

            {{-- Main Page Content Body --}}
            <main class="flex-1 min-h-0 overflow-y-auto overscroll-contain p-4 sm:p-6 lg:p-8">

                {{-- Flash Message Alerts --}}
                @if (session('success'))
                    <div class="flash-message mb-5 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 px-4 py-3 rounded-2xl text-xs sm:text-sm font-semibold flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-base"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 dark:hover:text-emerald-100 text-xs">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="flash-message mb-5 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 px-4 py-3 rounded-2xl text-xs sm:text-sm font-semibold flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-2.5">
                            <i class="fa-solid fa-circle-xmark text-rose-500 text-base"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-800 dark:hover:text-rose-100 text-xs">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif
                @if (session('warning'))
                    <div class="flash-message mb-5 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-200 px-4 py-3 rounded-2xl text-xs sm:text-sm font-semibold flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-2.5">
                            <i class="fa-solid fa-triangle-exclamation text-amber-500 text-base"></i>
                            <span>{{ session('warning') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-amber-600 hover:text-amber-800 dark:hover:text-amber-100 text-xs">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                @yield('content')
            </main>

        </div>

    </div>

    {{-- REUSABLE CONFIRMATION MODAL (Dual Support: adminConfirm & afConfirm) --}}
    <div id="afConfirmOverlay" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-slate-950/60 backdrop-blur-sm p-4">
        <div class="w-full max-w-sm bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl p-6 relative">
            <button type="button" id="afConfirmClose" class="absolute top-3.5 right-3.5 w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-600 dark:hover:text-white transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div class="flex items-start gap-4">
                <span class="shrink-0 w-11 h-11 rounded-xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </span>
                <div class="min-w-0 flex-1">
                    <h3 id="afConfirmTitle" class="text-sm font-bold text-slate-900 dark:text-white">Konfirmasi Aksi</h3>
                    <p id="afConfirmMessage" class="mt-1 text-xs text-slate-500 dark:text-slate-400 leading-relaxed break-words"></p>
                </div>
            </div>

            <div class="mt-5 grid grid-cols-2 gap-2.5">
                <button type="button" id="afConfirmCancel" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">Batal</button>
                <button type="button" id="afConfirmOk" class="px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 transition-colors">Ya, Lanjutkan</button>
            </div>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script>
        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            const button = document.getElementById('adminProfileButton');
            const chevron = document.getElementById('adminProfileChevron');
            if (!dropdown) return;
            const willOpen = dropdown.classList.contains('hidden');
            dropdown.classList.toggle('hidden');
            if (button) button.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
            if (chevron) chevron.classList.toggle('rotate-180', willOpen);
        }

        function closeProfileDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            const button = document.getElementById('adminProfileButton');
            const chevron = document.getElementById('adminProfileChevron');
            if (!dropdown) return;
            dropdown.classList.add('hidden');
            if (button) button.setAttribute('aria-expanded', 'false');
            if (chevron) chevron.classList.remove('rotate-180');
        }

        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('profileDropdown');
            const button = document.getElementById('adminProfileButton');
            if (dropdown && !dropdown.classList.contains('hidden')) {
                if (!dropdown.contains(e.target) && !button.contains(e.target)) {
                    closeProfileDropdown();
                }
            }
        });

        (function() {
            const overlay = document.getElementById('afConfirmOverlay');
            const messageEl = document.getElementById('afConfirmMessage');
            const cancelBtn = document.getElementById('afConfirmCancel');
            const okBtn = document.getElementById('afConfirmOk');
            const closeBtn = document.getElementById('afConfirmClose');
            let pendingForm = null;

            const iconWrap = overlay ? overlay.querySelector('span > i.fa-triangle-exclamation') : null;
            const iconBox = iconWrap ? iconWrap.parentElement : null;

            function openModal(msg, form, options) {
                options = options || {};

                // GUARD: requestSubmit() memicu ulang event submit sehingga
                // adminConfirm() terpanggil kedua kali. Di pemanggilan kedua
                // ini kita lepas guard-nya dan izinkan form benar-benar submit.
                if (form && form.dataset && form.dataset.afConfirmed === '1') {
                    delete form.dataset.afConfirmed;
                    return true;
                }

                pendingForm = form || null;
                messageEl.textContent = msg || 'Apakah Anda yakin ingin melanjutkan tindakan ini?';

                const danger = !!options.danger;
                overlay.classList.toggle('af-danger', danger);

                if (iconBox) {
                    ['bg-amber-50', 'dark:bg-amber-950/50', 'text-amber-600', 'dark:text-amber-400']
                        .forEach(c => iconBox.classList.toggle(c, !danger));
                    ['bg-rose-50', 'dark:bg-rose-950/50', 'text-rose-600', 'dark:text-rose-400']
                        .forEach(c => iconBox.classList.toggle(c, danger));
                }

                ['bg-blue-600', 'hover:bg-blue-700'].forEach(c => okBtn.classList.toggle(c, !danger));
                ['bg-rose-600', 'hover:bg-rose-700'].forEach(c => okBtn.classList.toggle(c, danger));

                overlay.classList.remove('hidden');
                overlay.classList.add('flex');
                okBtn.disabled = false;
                okBtn.textContent = options.confirmText || 'Ya, Lanjutkan';
                if (cancelBtn) cancelBtn.focus();
                return false;
            }

            function closeModal() {
                overlay.classList.add('hidden');
                overlay.classList.remove('flex');
                pendingForm = null;
            }

            window.adminConfirm = openModal;
            window.afConfirm = openModal;
            window.adminConfirmClose = closeModal;

            if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
            if (closeBtn) closeBtn.addEventListener('click', closeModal);

            if (okBtn) {
                okBtn.addEventListener('click', function() {
                    const form = pendingForm;
                    if (!form) { closeModal(); return; }
                    okBtn.disabled = true;
                    okBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i>Memproses...';
                    closeModal();
                    if (form.dataset) form.dataset.afConfirmed = '1';
                    if (typeof form.requestSubmit === 'function') {
                        form.requestSubmit();
                    } else {
                        form.submit();
                    }
                });
            }

            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) closeModal();
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeModal();
            });
        })();
    </script>
    <script>
        // Notifications System
        document.addEventListener('DOMContentLoaded', function() {
            const notifButton = document.getElementById('adminNotificationButton');
            const notifDropdown = document.getElementById('adminNotificationDropdown');
            const notifList = document.getElementById('adminNotificationList');
            const notifBadge = document.getElementById('adminNotificationBadge');
            const markAllBtn = document.getElementById('adminMarkAllReadBtn');

            if (notifButton && notifDropdown) {
                notifButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    notifDropdown.classList.toggle('hidden');
                    if (!notifDropdown.classList.contains('hidden')) {
                        fetchNotifications();
                    }
                });

                window.addEventListener('click', function(e) {
                    if (!notifDropdown.contains(e.target) && !notifButton.contains(e.target)) {
                        notifDropdown.classList.add('hidden');
                    }
                });
            }

            function fetchNotifications() {
                fetch('{{ route('notifications.index') }}')
                    .then(res => res.json())
                    .then(data => {
                        updateBadge(data.unread_count);
                        renderNotifications(data.notifications);
                    })
                    .catch(() => {});
            }

            function updateBadge(count) {
                if (!notifBadge) return;
                if (count > 0) {
                    notifBadge.textContent = count > 99 ? '99+' : count;
                    notifBadge.classList.remove('hidden');
                } else {
                    notifBadge.classList.add('hidden');
                }
            }

            function renderNotifications(notifications) {
                if (!notifList) return;
                if (!notifications || notifications.length === 0) {
                    notifList.innerHTML = `
                        <div class="p-8 text-center text-xs text-slate-400">
                            <i class="fa-regular fa-bell-slash text-xl mb-2 block opacity-40"></i>
                            Tidak ada notifikasi baru
                        </div>
                    `;
                    return;
                }

                let html = '';
                notifications.forEach(notif => {
                    const isUnread = !notif.is_read;
                    const redirectUrl = (notif.data && notif.data.redirect) ? notif.data.redirect : '#';
                    const icon = getNotifIcon(notif.type);

                    html += `
                        <button type="button" class="notification-item w-full text-left p-3.5 hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-colors ${isUnread ? 'bg-blue-50/50 dark:bg-blue-950/20' : ''}"
                            data-id="${notif.id}" data-url="${redirectUrl}">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-xl bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs shrink-0 mt-0.5">
                                    <i class="${icon}"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2">
                                        <p class="text-xs font-bold ${isUnread ? 'text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400'} truncate">${notif.title || 'Pemberitahuan Sistem'}</p>
                                        ${isUnread ? '<span class="w-1.5 h-1.5 rounded-full bg-blue-500 shrink-0"></span>' : ''}
                                    </div>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 line-clamp-2 mt-0.5">${notif.message || ''}</p>
                                    <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">${getTimeAgo(notif.created_at)}</p>
                                </div>
                            </div>
                        </button>
                    `;
                });
                notifList.innerHTML = html;

                notifList.querySelectorAll('.notification-item').forEach(item => {
                    item.addEventListener('click', function() {
                        const id = this.dataset.id;
                        const url = this.dataset.url;

                        fetch('{{ url('/notifications') }}/' + id + '/read', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(res => res.ok ? res.json() : Promise.reject('Failed'))
                            .then(data => {
                                if (typeof data.unread_count !== 'undefined') {
                                    updateBadge(data.unread_count);
                                }
                                if (data.redirect_url) {
                                    window.location.href = data.redirect_url;
                                } else if (url && url !== '#') {
                                    window.location.href = url;
                                }
                            })
                            .catch(() => {
                                if (url && url !== '#') {
                                    window.location.href = url;
                                }
                            });
                    });
                });
            }

            function getNotifIcon(type) {
                const iconMap = {
                    'company_request.created': 'fa-solid fa-building',
                    'payment.waiting': 'fa-solid fa-credit-card',
                    'payment.verified': 'fa-solid fa-check-circle',
                    'payment.rejected': 'fa-solid fa-times-circle',
                    'offer.sent': 'fa-solid fa-paper-plane',
                    'offer.accepted': 'fa-solid fa-check',
                    'offer.rejected': 'fa-solid fa-ban',
                    'workspace.message': 'fa-regular fa-comment-dots',
                    'submission.uploaded': 'fa-solid fa-upload',
                    'submission.accepted': 'fa-solid fa-check-double',
                    'submission.revision_requested': 'fa-solid fa-pen',
                    'report.created': 'fa-solid fa-flag',
                };
                return iconMap[type] || 'fa-regular fa-bell';
            }

            function getTimeAgo(dateString) {
                const now = new Date();
                const date = new Date(dateString);
                const diffMs = now - date;
                const diffSec = Math.floor(diffMs / 1000);
                const diffMin = Math.floor(diffSec / 60);
                const diffHour = Math.floor(diffMin / 60);
                const diffDay = Math.floor(diffHour / 24);

                if (diffSec < 60) return 'Baru saja';
                if (diffMin < 60) return diffMin + ' menit yang lalu';
                if (diffHour < 24) return diffHour + ' jam yang lalu';
                if (diffDay < 7) return diffDay + ' hari yang lalu';
                return date.toLocaleDateString('id-ID');
            }

            if (markAllBtn) {
                markAllBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    fetch('{{ route('notifications.mark-all-read') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            updateBadge(0);
                            fetchNotifications();
                        }
                    })
                    .catch(() => {});
                });
            }

            fetch('{{ route('notifications.index') }}')
                .then(res => res.json())
                .then(data => updateBadge(data.unread_count))
                .catch(() => {});

            // Polling ringan tiap 60 detik agar badge tetap sinkron
            setInterval(function() {
                fetch('{{ route('notifications.index') }}')
                    .then(res => res.json())
                    .then(data => updateBadge(data.unread_count))
                    .catch(() => {});
            }, 60000);
        });
    </script>

    <script>
        // Theme Toggle (Supports both desktop and mobile toggle buttons)
        (function() {
            function adminThemeKey() {
                var uid = @json(Auth::id());
                return uid ? ('theme_user_' + uid) : 'theme_user_';
            }

            function syncThemeIcons() {
                var isDark = document.documentElement.classList.contains('dark');
                document.querySelectorAll('.admin-theme-moon').forEach(el => el.classList.toggle('hidden', isDark));
                document.querySelectorAll('.admin-theme-sun').forEach(el => el.classList.toggle('hidden', !isDark));

                // Sinkronkan sliding switch + label di dropdown profil
                var sw = document.getElementById('adminThemeToggle');
                if (sw) sw.setAttribute('aria-checked', isDark ? 'true' : 'false');
                var lbl = document.getElementById('adminThemeLabel');
                if (lbl) lbl.textContent = isDark ? 'Mode Gelap' : 'Mode Terang';
            }

            function toggleTheme() {
                var dark = document.documentElement.classList.toggle('dark');
                try {
                    localStorage.setItem(adminThemeKey(), dark ? 'dark' : 'light');
                } catch (e) {}
                syncThemeIcons();
            }

            syncThemeIcons();

            var dt = document.getElementById('adminThemeToggle');
            var mt = document.getElementById('adminThemeToggleMobile');
            if (dt) dt.addEventListener('click', toggleTheme);
            if (mt) mt.addEventListener('click', toggleTheme);

            // Re-sync state switch setelah DOM benar-benar siap
            document.addEventListener('DOMContentLoaded', syncThemeIcons);
        })();

        // Sidebar Responsive & Collapse Handler
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const toggleDesktop = document.getElementById('sidebarToggle');
            const toggleMobile = document.getElementById('mobileSidebarToggleBtn');
            const closeMobile = document.getElementById('mobileSidebarClose');
            const overlay = document.getElementById('sidebarOverlay');

            function updateTooltips() {
                if (window.innerWidth >= 1024 && sidebar && sidebar.classList.contains('collapsed')) {
                    sidebar.querySelectorAll('nav a').forEach(link => {
                        const r = link.getBoundingClientRect();
                        link.style.setProperty('--tooltip-top', (r.top + r.height / 2) + 'px');
                    });
                }
            }

            function openMobile() {
                sidebar.classList.add('mobile-open');
                overlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeMobileDrawer() {
                sidebar.classList.remove('mobile-open');
                overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            if (toggleMobile) toggleMobile.addEventListener('click', openMobile);
            if (closeMobile) closeMobile.addEventListener('click', closeMobileDrawer);
            if (overlay) overlay.addEventListener('click', closeMobileDrawer);

            if (toggleDesktop) {
                toggleDesktop.addEventListener('click', function(e) {
                    e.stopPropagation();
                    if (window.innerWidth >= 1024) {
                        const collapsed = sidebar.classList.toggle('collapsed');
                        localStorage.setItem('sidebarCollapsed', collapsed ? 'true' : 'false');
                        setTimeout(updateTooltips, 50);
                    }
                });
            }

            if (window.innerWidth >= 1024) {
                if (localStorage.getItem('sidebarCollapsed') === 'true') {
                    sidebar.classList.add('collapsed');
                }
            }

            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) {
                    closeMobileDrawer();
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && window.innerWidth < 1024) {
                    closeMobileDrawer();
                }
            });

            document.querySelectorAll('.flash-message').forEach(el => {
                setTimeout(() => {
                    el.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                    el.style.opacity = '0';
                    el.style.transform = 'translateY(-6px)';
                    setTimeout(() => el.remove(), 400);
                }, 4000);
            });
        });
    </script>

    @stack('scripts')
    <script src="{{ asset('js/toast.js') }}" defer></script>
    @include('partials.flash-toast')
    @include('partials.notification-toasts')
    @yield('script')

</body>
</html>
