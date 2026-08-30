{{-- ApexForge Labs — Shared UI --}}

{{-- TOMBOL HAMBURGER MOBILE (FIXED & HIGH Z-INDEX) --}}
<button type="button" id="mobileSidebarToggleBtn"
    class="lg:hidden fixed top-3.5 left-4 z-[60] w-10 h-10 rounded-xl bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 text-blue-600 dark:text-blue-400 flex items-center justify-center shadow-lg hover:bg-blue-50 dark:hover:bg-slate-800 active:scale-95 transition-transform"
    aria-label="Buka Navigasi Mobile">
    <i class="fa-solid fa-bars text-lg"></i>
</button>

<aside id="sidebar"
    class="w-64 bg-white/95 dark:bg-slate-900/95 backdrop-blur-3xl border-r border-blue-100 dark:border-slate-800 flex flex-col h-screen sticky top-0 shrink-0 z-50 shadow-[10px_0_40px_-10px_rgba(59,130,246,0.08)] transition-colors duration-300">

    {{-- DECORATIVE RIGHT EDGE GLOW --}}
    <div class="absolute top-0 right-0 w-[1px] h-full bg-gradient-to-b from-transparent via-blue-400/30 to-transparent">
    </div>

    {{-- LOGO SECTION --}}
    <div
        class="sidebar-logo-wrapper h-[88px] px-5 flex items-center border-b border-blue-50 dark:border-slate-800 shrink-0 transition-all duration-300 relative">

        {{-- Mobile hamburger (di dalam sidebar) --}}
        <button type="button" id="mobileSidebarToggle"
            class="sidebar-hamburger-mobile w-10 h-10 rounded-xl hover:bg-blue-50 dark:hover:bg-slate-800 flex items-center justify-center shrink-0 transition-colors text-blue-600 dark:text-blue-400">
            <i class="fa-solid fa-bars text-lg"></i>
        </button>

        {{-- LOGO --}}
        <div class="sidebar-logo-container relative shrink-0">
            <div
                class="absolute inset-0 bg-blue-500 rounded-full blur-[10px] opacity-40 group-hover:opacity-60 transition-opacity duration-300">
            </div>
            <div
                class="relative w-10 h-10 rounded-full overflow-hidden border-2 border-white dark:border-slate-800 shadow-sm">
                <img src="{{ asset('images/nexus.jpg') }}" alt="ApexForge Labs Logo" class="w-full h-full object-cover">
            </div>
        </div>

        {{-- LOGO TEXT --}}
        <div class="sidebar-logo-text ml-3 transition-all duration-300 overflow-hidden">
            <h2
                class="font-black text-[13px] leading-tight text-blue-950 dark:text-white whitespace-nowrap tracking-tight">
                ApexForge<br>
                <span class="text-blue-600 dark:text-blue-400">Labs</span>
            </h2>
        </div>

        {{-- DESKTOP TOGGLE --}}
        <button type="button" id="sidebarToggle"
            class="sidebar-toggle-desktop w-8 h-8 rounded-xl hover:bg-blue-50 dark:hover:bg-slate-800 flex items-center justify-center shrink-0 ml-auto transition-all duration-300 group border border-transparent hover:border-blue-100 dark:hover:border-slate-700">
            <i
                class="sidebar-toggle-icon fa-solid fa-chevron-left text-blue-400 dark:text-slate-500 text-xs transition-transform duration-300"></i>
        </button>

        {{-- COLLAPSED TOGGLE --}}
        <button type="button" id="sidebarToggleCollapsed"
            class="sidebar-toggle-collapsed w-7 h-7 rounded-lg bg-white/90 dark:bg-slate-800 border border-blue-100 dark:border-slate-700 items-center justify-center transition-all duration-300 shadow-sm group">
            <i
                class="sidebar-toggle-icon fa-solid fa-chevron-right text-blue-400 dark:text-slate-400 text-[10px] transition-transform duration-300"></i>
        </button>

    </div>

    {{-- MENU NAVIGATION --}}
    <nav class="mt-6 px-4 space-y-1.5 flex-1 overflow-y-auto custom-sidebar-scroll relative z-10">

        @auth

            {{-- ================= FREELANCER MENU ================= --}}
            @if (Auth::user()->role == 'freelancer')
                <a href="{{ route('freelancer.dashboard') }}"
                    class="relative flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group
                   {{ request()->routeIs('freelancer.dashboard')
                       ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-[0_8px_20px_-6px_rgba(59,130,246,0.6)] font-bold'
                       : 'text-blue-900/60 dark:text-slate-400 hover:bg-blue-50/80 dark:hover:bg-slate-800/80 hover:text-blue-700 dark:hover:text-blue-400 font-semibold' }}"
                    data-tooltip="Dashboard">
                    <i class="fa-solid fa-house w-5 text-center transition-transform group-hover:scale-110"></i>
                    <span class="tracking-wide text-sm">Dashboard</span>
                </a>

                <a href="{{ route('freelancer.proyek') }}"
                    class="relative flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group
                   {{ request()->routeIs('freelancer.proyek')
                       ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-[0_8px_20px_-6px_rgba(59,130,246,0.6)] font-bold'
                       : 'text-blue-900/60 dark:text-slate-400 hover:bg-blue-50/80 dark:hover:bg-slate-800/80 hover:text-blue-700 dark:hover:text-blue-400 font-semibold' }}"
                    data-tooltip="Cari Proyek">
                    <i class="fa-solid fa-magnifying-glass w-5 text-center transition-transform group-hover:scale-110"></i>
                    <span class="tracking-wide text-sm">Cari Proyek</span>
                </a>

                <a href="{{ route('freelancer.workspaces.index') }}"
                    class="relative flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group
                   {{ request()->routeIs('freelancer.workspaces.*')
                       ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-[0_8px_20px_-6px_rgba(59,130,246,0.6)] font-bold'
                       : 'text-blue-900/60 dark:text-slate-400 hover:bg-blue-50/80 dark:hover:bg-slate-800/80 hover:text-blue-700 dark:hover:text-blue-400 font-semibold' }}"
                    data-tooltip="Workspace Saya">
                    <i class="fa-solid fa-layer-group w-5 text-center transition-transform group-hover:scale-110"></i>
                    <span class="tracking-wide text-sm">Workspace Saya</span>
                </a>

                <a href="{{ route('freelancer.pendapatan.index') }}"
                    class="relative flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group
                   {{ request()->routeIs('freelancer.pendapatan.*')
                       ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-[0_8px_20px_-6px_rgba(59,130,246,0.6)] font-bold'
                       : 'text-blue-900/60 dark:text-slate-400 hover:bg-blue-50/80 dark:hover:bg-slate-800/80 hover:text-blue-700 dark:hover:text-blue-400 font-semibold' }}"
                    data-tooltip="Pendapatan">
                    <i class="fa-solid fa-wallet w-5 text-center transition-transform group-hover:scale-110"></i>
                    <span class="tracking-wide text-sm">Pendapatan</span>
                </a>

                <a href="{{ route('freelancer.reports.index') }}"
                    class="relative flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group
                   {{ request()->routeIs('freelancer.reports.*')
                       ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-[0_8px_20px_-6px_rgba(59,130,246,0.6)] font-bold'
                       : 'text-blue-900/60 dark:text-slate-400 hover:bg-blue-50/80 dark:hover:bg-slate-800/80 hover:text-blue-700 dark:hover:text-blue-400 font-semibold' }}"
                    data-tooltip="Laporan">
                    <i class="fa-solid fa-flag w-5 text-center transition-transform group-hover:scale-110"></i>
                    <span class="tracking-wide text-sm">Laporan</span>
                </a>

                <div class="pt-4 pb-1">
                    <div
                        class="h-px w-full bg-gradient-to-r from-transparent via-blue-100 dark:via-slate-700 to-transparent">
                    </div>
                </div>

                <div>
                    <button type="button" data-bantuan-toggle
                        class="relative w-full flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group text-blue-900/60 dark:text-slate-400 hover:bg-blue-50/80 dark:hover:bg-slate-800/80 hover:text-blue-700 dark:hover:text-blue-400 font-semibold"
                        data-tooltip="Bantuan">
                        <i
                            class="fa-solid fa-circle-question w-5 text-center transition-transform group-hover:scale-110"></i>
                        <span class="tracking-wide text-sm">Bantuan</span>
                        <i class="fa-solid fa-chevron-down ml-auto text-[10px] opacity-50 transition-transform"></i>
                    </button>
                    <div class="bantuan-submenu hidden pl-12 space-y-1 mt-1">

                        {{-- Pusat Bantuan --}}
                        <a href="{{ route('help.index') }}"
                            class="relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group
                            {{ request()->routeIs('help.index')
                                ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-[0_8px_20px_-6px_rgba(59,130,246,0.6)] font-bold'
                                : 'text-blue-900/60 dark:text-slate-400 hover:bg-blue-50/80 dark:hover:bg-slate-800/80 hover:text-blue-700 dark:hover:text-blue-400 font-semibold' }}"
                            data-tooltip="Pusat Bantuan">
                            <i
                                class="fa-solid fa-circle-question w-5 text-center transition-transform group-hover:scale-110"></i>
                            <span class="tracking-wide text-sm">Pusat Bantuan</span>
                        </a>

                        {{-- Laporkan Bug --}}
                        <a href="{{ route('reports.create') }}"
                            class="relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group
                            {{ request()->routeIs('reports.create')
                                ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-[0_8px_20px_-6px_rgba(59,130,246,0.6)] font-bold'
                                : 'text-blue-900/60 dark:text-slate-400 hover:bg-red-50/80 dark:hover:bg-red-950/50 hover:text-red-600 dark:hover:text-red-400 font-semibold' }}"
                            data-tooltip="Laporkan Bug">
                            <i class="fa-solid fa-bug w-5 text-center transition-transform group-hover:scale-110"></i>
                            <span class="tracking-wide text-sm">Laporkan Bug</span>
                        </a>
                    </div>
                </div>

                {{-- ================= COMPANY MENU ================= --}}
            @elseif(Auth::user()->role == 'company')
                <a href="{{ route('company.dashboard') }}"
                    class="relative flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group
                   {{ request()->routeIs('company.dashboard')
                       ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-[0_8px_20px_-6px_rgba(59,130,246,0.6)] font-bold'
                       : 'text-blue-900/60 dark:text-slate-400 hover:bg-blue-50/80 dark:hover:bg-slate-800/80 hover:text-blue-700 dark:hover:text-blue-400 font-semibold' }}"
                    data-tooltip="Dashboard">
                    <i class="fa-solid fa-house w-5 text-center transition-transform group-hover:scale-110"></i>
                    <span class="tracking-wide text-sm">Dashboard</span>
                </a>

                <a href="{{ route('company.projects.create') }}"
                    class="relative flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group
                   {{ request()->routeIs('company.projects.create')
                       ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-[0_8px_20px_-6px_rgba(59,130,246,0.6)] font-bold'
                       : 'text-blue-900/60 dark:text-slate-400 hover:bg-blue-50/80 dark:hover:bg-slate-800/80 hover:text-blue-700 dark:hover:text-blue-400 font-semibold' }}"
                    data-tooltip="Tambah Proyek">
                    <i class="fa-solid fa-plus w-5 text-center transition-transform group-hover:scale-110"></i>
                    <span class="tracking-wide text-sm">Tambah Proyek</span>
                </a>

                <a href="{{ route('company.projects.index') }}"
                    class="relative flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group
                   {{ request()->routeIs('company.projects.index') ||
                   request()->routeIs('company.projects.show') ||
                   request()->routeIs('company.projects.edit')
                       ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-[0_8px_20px_-6px_rgba(59,130,246,0.6)] font-bold'
                       : 'text-blue-900/60 dark:text-slate-400 hover:bg-blue-50/80 dark:hover:bg-slate-800/80 hover:text-blue-700 dark:hover:text-blue-400 font-semibold' }}"
                    data-tooltip="Proyek Saya">
                    <i class="fa-solid fa-folder-open w-5 text-center transition-transform group-hover:scale-110"></i>
                    <span class="tracking-wide text-sm">Proyek Saya</span>
                </a>

                <a href="{{ route('company.projects.archive') }}"
                    class="relative flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group
                   {{ request()->routeIs('company.projects.archive')
                       ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-[0_8px_20px_-6px_rgba(59,130,246,0.6)] font-bold'
                       : 'text-blue-900/60 dark:text-slate-400 hover:bg-blue-50/80 dark:hover:bg-slate-800/80 hover:text-blue-700 dark:hover:text-blue-400 font-semibold' }}"
                    data-tooltip="Arsip Proyek">
                    <i class="fa-solid fa-box-archive w-5 text-center transition-transform group-hover:scale-110"></i>
                    <span class="tracking-wide text-sm">Arsip Proyek</span>
                </a>

                <a href="{{ route('company.workspaces.index') }}"
                    class="relative flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group
                   {{ request()->routeIs('company.workspaces.*')
                       ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-[0_8px_20px_-6px_rgba(59,130,246,0.6)] font-bold'
                       : 'text-blue-900/60 dark:text-slate-400 hover:bg-blue-50/80 dark:hover:bg-slate-800/80 hover:text-blue-700 dark:hover:text-blue-400 font-semibold' }}"
                    data-tooltip="Workspace">
                    <i class="fa-solid fa-layer-group w-5 text-center transition-transform group-hover:scale-110"></i>
                    <span class="tracking-wide text-sm">Workspace</span>
                </a>

                <a href="{{ route('company.reports.index') }}"
                    class="relative flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group
                   {{ request()->routeIs('company.reports.*')
                       ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-[0_8px_20px_-6px_rgba(59,130,246,0.6)] font-bold'
                       : 'text-blue-900/60 dark:text-slate-400 hover:bg-blue-50/80 dark:hover:bg-slate-800/80 hover:text-blue-700 dark:hover:text-blue-400 font-semibold' }}"
                    data-tooltip="Laporan">
                    <i class="fa-solid fa-flag w-5 text-center transition-transform group-hover:scale-110"></i>
                    <span class="tracking-wide text-sm">Laporan</span>
                </a>

                <div class="pt-4 pb-1">
                    <div
                        class="h-px w-full bg-gradient-to-r from-transparent via-blue-100 dark:via-slate-700 to-transparent">
                    </div>
                </div>

                <div>
                    <button type="button" data-bantuan-toggle
                        class="relative w-full flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group text-blue-900/60 dark:text-slate-400 hover:bg-blue-50/80 dark:hover:bg-slate-800/80 hover:text-blue-700 dark:hover:text-blue-400 font-semibold"
                        data-tooltip="Bantuan">
                        <i
                            class="fa-solid fa-circle-question w-5 text-center transition-transform group-hover:scale-110"></i>
                        <span class="tracking-wide text-sm">Bantuan</span>
                        <i class="fa-solid fa-chevron-down ml-auto text-[10px] opacity-50 transition-transform"></i>
                    </button>
                    <div class="bantuan-submenu hidden pl-12 space-y-1 mt-1">
                        {{-- Pusat Bantuan --}}
                        <a href="{{ route('help.index') }}"
                            class="relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group
                            {{ request()->routeIs('help.index')
                            ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-[0_8px_20px_-6px_rgba(59,130,246,0.6)] font-bold'
                            : 'text-blue-900/60 dark:text-slate-400 hover:bg-blue-50/80 dark:hover:bg-slate-800/80 hover:text-blue-700 dark:hover:text-blue-400 font-semibold' }}"
                            data-tooltip="Pusat Bantuan">
                            <i
                                class="fa-solid fa-circle-question w-5 text-center transition-transform group-hover:scale-110"></i>
                            <span class="tracking-wide text-sm">Pusat Bantuan</span>
                        </a>

                        {{-- Laporkan Bug --}}
                        <a href="{{ route('reports.create') }}"
                            class="relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group
                            {{ request()->routeIs('reports.create')
                            ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-[0_8px_20px_-6px_rgba(59,130,246,0.6)] font-bold'
                            : 'text-blue-900/60 dark:text-slate-400 hover:bg-red-50/80 dark:hover:bg-red-950/50 hover:text-red-600 dark:hover:text-red-400 font-semibold' }}"
                            data-tooltip="Laporkan Bug">
                            <i class="fa-solid fa-bug w-5 text-center transition-transform group-hover:scale-110"></i>
                            <span class="tracking-wide text-sm">Laporkan Bug</span>
                        </a>

                    </div>
                </div>

                {{-- ================= ADMIN MENU ================= --}}
            @elseif(Auth::user()->role == 'admin')
                <a href="{{ route('admin.dashboard') }}"
                    class="relative flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group
                   {{ request()->routeIs('admin.dashboard')
                       ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-[0_8px_20px_-6px_rgba(59,130,246,0.6)] font-bold'
                       : 'text-blue-900/60 dark:text-slate-400 hover:bg-blue-50/80 dark:hover:bg-slate-800/80 hover:text-blue-700 dark:hover:text-blue-400 font-semibold' }}"
                    data-tooltip="Dashboard">
                    <i class="fa-solid fa-chart-line w-5 text-center transition-transform group-hover:scale-110"></i>
                    <span class="tracking-wide text-sm">Dashboard</span>
                </a>

                <a href="{{ route('admin.users.index') }}"
                    class="relative flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group
                   {{ request()->routeIs('admin.users.*')
                       ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-[0_8px_20px_-6px_rgba(59,130,246,0.6)] font-bold'
                       : 'text-blue-900/60 dark:text-slate-400 hover:bg-blue-50/80 dark:hover:bg-slate-800/80 hover:text-blue-700 dark:hover:text-blue-400 font-semibold' }}"
                    data-tooltip="Pengguna">
                    <i class="fa-solid fa-users w-5 text-center transition-transform group-hover:scale-110"></i>
                    <span class="tracking-wide text-sm">Pengguna</span>
                </a>

                <a href="{{ route('admin.categories.index') }}"
                    class="relative flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group
                   {{ request()->routeIs('admin.categories.*')
                       ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-[0_8px_20px_-6px_rgba(59,130,246,0.6)] font-bold'
                       : 'text-blue-900/60 dark:text-slate-400 hover:bg-blue-50/80 dark:hover:bg-slate-800/80 hover:text-blue-700 dark:hover:text-blue-400 font-semibold' }}"
                    data-tooltip="Kategori">
                    <i class="fa-solid fa-tags w-5 text-center transition-transform group-hover:scale-110"></i>
                    <span class="tracking-wide text-sm">Kategori</span>
                </a>

                <a href="{{ route('admin.projects.index') }}"
                    class="relative flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group
                   {{ request()->routeIs('admin.projects.*')
                       ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-[0_8px_20px_-6px_rgba(59,130,246,0.6)] font-bold'
                       : 'text-blue-900/60 dark:text-slate-400 hover:bg-blue-50/80 dark:hover:bg-slate-800/80 hover:text-blue-700 dark:hover:text-blue-400 font-semibold' }}"
                    data-tooltip="Proyek">
                    <i class="fa-solid fa-folder-open w-5 text-center transition-transform group-hover:scale-110"></i>
                    <span class="tracking-wide text-sm">Proyek</span>
                </a>

                <a href="{{ route('admin.penawarans.index') }}"
                    class="relative flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group
                   {{ request()->routeIs('admin.penawarans.*')
                       ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-[0_8px_20px_-6px_rgba(59,130,246,0.6)] font-bold'
                       : 'text-blue-900/60 dark:text-slate-400 hover:bg-blue-50/80 dark:hover:bg-slate-800/80 hover:text-blue-700 dark:hover:text-blue-400 font-semibold' }}"
                    data-tooltip="Penawaran">
                    <i class="fa-solid fa-file-invoice w-5 text-center transition-transform group-hover:scale-110"></i>
                    <span class="tracking-wide text-sm">Penawaran</span>
                </a>

                <a href="{{ route('admin.hasil-pekerjaan.index') }}"
                    class="relative flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group
                   {{ request()->routeIs('admin.hasil-pekerjaan.*')
                       ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-[0_8px_20px_-6px_rgba(59,130,246,0.6)] font-bold'
                       : 'text-blue-900/60 dark:text-slate-400 hover:bg-blue-50/80 dark:hover:bg-slate-800/80 hover:text-blue-700 dark:hover:text-blue-400 font-semibold' }}"
                    data-tooltip="Hasil Pekerjaan">
                    <i class="fa-solid fa-layer-group w-5 text-center transition-transform group-hover:scale-110"></i>
                    <span class="tracking-wide text-sm">Hasil Pekerjaan</span>
                </a>

                <a href="{{ route('admin.reports.index') }}"
                    class="relative flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group
                   {{ request()->routeIs('admin.reports.*')
                       ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-[0_8px_20px_-6px_rgba(59,130,246,0.6)] font-bold'
                       : 'text-blue-900/60 dark:text-slate-400 hover:bg-blue-50/80 dark:hover:bg-slate-800/80 hover:text-blue-700 dark:hover:text-blue-400 font-semibold' }}"
                    data-tooltip="Laporan">
                    <i class="fa-solid fa-flag w-5 text-center transition-transform group-hover:scale-110"></i>
                    <span class="tracking-wide text-sm">Laporan</span>
                </a>

                <a href="{{ route('admin.company-account-requests.index') }}"
                    class="relative flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group
                   {{ request()->routeIs('admin.company-account-requests.*')
                       ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-[0_8px_20px_-6px_rgba(59,130,246,0.6)] font-bold'
                       : 'text-blue-900/60 dark:text-slate-400 hover:bg-blue-50/80 dark:hover:bg-slate-800/80 hover:text-blue-700 dark:hover:text-blue-400 font-semibold' }}"
                    data-tooltip="Verifikasi Perusahaan">
                    <i class="fa-solid fa-building w-5 text-center transition-transform group-hover:scale-110"></i>
                    <span class="tracking-wide text-sm">Verifikasi Company</span>
                </a>

                <a href="{{ route('admin.withdrawals.index') }}"
                    class="relative flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group
                   {{ request()->routeIs('admin.withdrawals.*')
                       ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-[0_8px_20px_-6px_rgba(59,130,246,0.6)] font-bold'
                       : 'text-blue-900/60 dark:text-slate-400 hover:bg-blue-50/80 dark:hover:bg-slate-800/80 hover:text-blue-700 dark:hover:text-blue-400 font-semibold' }}"
                    data-tooltip="Penarikan Dana">
                    <i
                        class="fa-solid fa-money-bill-transfer w-5 text-center transition-transform group-hover:scale-110"></i>
                    <span class="tracking-wide text-sm">Penarikan Dana</span>
                </a>

                <a href="{{ route('admin.wallet.index') }}"
                    class="relative flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-300 group
                   {{ request()->routeIs('admin.wallet.*')
                       ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-[0_8px_20px_-6px_rgba(59,130,246,0.6)] font-bold'
                       : 'text-blue-900/60 dark:text-slate-400 hover:bg-blue-50/80 dark:hover:bg-slate-800/80 hover:text-blue-700 dark:hover:text-blue-400 font-semibold' }}"
                    data-tooltip="Wallet Admin">
                    <i class="fa-solid fa-wallet w-5 text-center transition-transform group-hover:scale-110"></i>
                    <span class="tracking-wide text-sm">Wallet Admin</span>
                </a>
            @endif

        @endauth

    </nav>

    {{-- SIDEBAR FOOTER --}}
    <div
        class="p-5 shrink-0 sidebar-footer-wrapper border-t border-blue-50 dark:border-slate-800 bg-white/50 dark:bg-slate-900/50 relative z-10">
        <div
            class="sidebar-footer-card rounded-[1.25rem] bg-gradient-to-br from-blue-600 to-blue-500 p-5 text-white overflow-hidden transition-all duration-300 relative shadow-[0_10px_25px_-5px_rgba(59,130,246,0.4)]">
            <div class="absolute -top-10 -right-10 w-24 h-24 bg-white/20 rounded-full blur-[20px]"></div>
            <div class="absolute -bottom-5 -left-5 w-16 h-16 bg-blue-300/30 rounded-full blur-[15px]"></div>

            <div class="relative z-10">
                <h3 class="font-black text-sm whitespace-nowrap tracking-wide">
                    ApexForge Labs
                </h3>
                <p class="text-[11px] mt-1 text-blue-100 font-medium whitespace-nowrap">
                    Marketplace Freelance Indonesia
                </p>
                <div class="mt-4 text-[10px] text-blue-200/80 font-bold whitespace-nowrap uppercase tracking-widest">
                    © 2026 ApexForge Labs
                </div>
            </div>
        </div>

        {{-- Collapsed footer minimal --}}
        <div class="sidebar-footer-mini hidden">
            <div
                class="w-10 h-10 rounded-2xl bg-gradient-to-br from-blue-600 to-blue-500 flex items-center justify-center mx-auto shadow-[0_5px_15px_rgba(59,130,246,0.4)]">
                <i class="fa-solid fa-globe text-white text-sm"></i>
            </div>
        </div>
    </div>

</aside>

{{-- MOBILE OVERLAY --}}
<div id="sidebarOverlay"
    class="hidden fixed inset-0 bg-blue-950/40 backdrop-blur-sm z-40 lg:hidden transition-opacity duration-300"></div>

{{-- COMPANY MOBILE BOTTOM NAV (hanya company, hanya < md) --}}
@include('company.partials.mobile-nav')

{{-- CUSTOM CSS FOR SIDEBAR --}}
<style>
    /* Custom Scrollbar for Sidebar */
    .custom-sidebar-scroll::-webkit-scrollbar {
        width: 4px;
    }

    .custom-sidebar-scroll::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-sidebar-scroll::-webkit-scrollbar-thumb {
        background: rgba(59, 130, 246, 0.2);
        border-radius: 10px;
    }

    .custom-sidebar-scroll:hover::-webkit-scrollbar-thumb {
        background: rgba(59, 130, 246, 0.4);
    }

    /* Sidebar base transition */
    #sidebar {
        transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1), transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* ===== COLLAPSED STATE (DESKTOP) ===== */
    #sidebar.collapsed {
        width: 88px !important;
    }

    #sidebar.collapsed .sidebar-logo-text {
        opacity: 0;
        visibility: hidden;
        width: 0;
        margin: 0;
        overflow: hidden;
        transition: opacity 0.2s ease, visibility 0.2s ease, width 0.3s ease;
    }

    #sidebar.collapsed .sidebar-logo-text * {
        white-space: nowrap;
    }

    #sidebar.collapsed nav a,
    #sidebar.collapsed nav button[data-bantuan-toggle] {
        justify-content: center;
        padding: 0;
        width: 48px;
        height: 48px;
        margin-left: auto;
        margin-right: auto;
        gap: 0;
        border-radius: 16px;
    }

    #sidebar.collapsed nav a i,
    #sidebar.collapsed nav button[data-bantuan-toggle] i {
        margin: 0;
        width: auto;
        font-size: 1.1rem;
    }

    #sidebar.collapsed nav a span,
    #sidebar.collapsed nav a .fa-chevron-down,
    #sidebar.collapsed nav button[data-bantuan-toggle] span,
    #sidebar.collapsed nav button[data-bantuan-toggle] .fa-chevron-down {
        display: none;
    }

    #sidebar.collapsed .bantuan-submenu {
        display: none !important;
    }

    #sidebar.collapsed .sidebar-footer-card {
        opacity: 0;
        visibility: hidden;
        height: 0;
        padding: 0;
        margin: 0;
        overflow: hidden;
        border: none;
        transition: opacity 0.2s ease, visibility 0.2s ease, height 0.3s ease, padding 0.3s ease;
    }

    #sidebar.collapsed .sidebar-footer-mini {
        display: block !important;
    }

    #sidebar.collapsed #sidebarToggle .sidebar-toggle-icon {
        transform: rotate(180deg);
    }

    #sidebar.collapsed .sidebar-logo-wrapper {
        padding: 0;
        width: 88px;
        height: 88px;
        justify-content: center;
        position: relative;
    }

    #sidebar.collapsed .sidebar-logo-container {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }

    #sidebar.collapsed .sidebar-logo-text {
        display: none !important;
    }

    #sidebar.collapsed .sidebar-hamburger-mobile {
        display: none !important;
    }

    #sidebar.collapsed .sidebar-toggle-desktop {
        display: none !important;
    }

    #sidebar.collapsed .sidebar-toggle-collapsed {
        display: flex !important;
        position: absolute;
        right: 8px;
        bottom: 8px;
        z-index: 10;
    }

    #sidebar:not(.collapsed) .sidebar-toggle-collapsed {
        display: none !important;
    }

    /* ===== TOOLTIP ON COLLAPSED ===== */
    #sidebar.collapsed nav a,
    #sidebar.collapsed nav button[data-bantuan-toggle] {
        position: relative;
    }

    #sidebar.collapsed nav a:hover::after,
    #sidebar.collapsed nav button[data-bantuan-toggle]:hover::after {
        content: attr(data-tooltip);
        position: fixed;
        left: 100px;
        top: var(--tooltip-top, 50%);
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        color: #1e3a8a;
        padding: 8px 16px;
        border-radius: 12px;
        border: 1px solid rgba(59, 130, 246, 0.2);
        font-size: 13px;
        font-weight: 800;
        white-space: nowrap;
        z-index: 100;
        pointer-events: none;
        box-shadow: 0 10px 25px rgba(59, 130, 246, 0.15);
        animation: tooltipFadeIn 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }

    #sidebar.collapsed nav a:hover::before,
    #sidebar.collapsed nav button[data-bantuan-toggle]:hover::before {
        content: '';
        position: fixed;
        left: 90px;
        top: var(--tooltip-top, 50%);
        transform: translateY(-50%);
        border: 6px solid transparent;
        border-right-color: rgba(255, 255, 255, 0.95);
        z-index: 100;
        pointer-events: none;
        animation: tooltipFadeIn 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }

    @keyframes tooltipFadeIn {
        from {
            opacity: 0;
            transform: translate(-10px, -50%);
        }

        to {
            opacity: 1;
            transform: translate(0, -50%);
        }
    }

    html.dark #sidebar.collapsed nav a:hover::after {
        background: rgba(30, 41, 59, 0.95);
        color: #e2e8f0;
        border-color: rgba(51, 65, 85, 0.8);
    }

    html.dark #sidebar.collapsed nav a:hover::before {
        border-right-color: rgba(30, 41, 59, 0.95);
    }

    /* ===== MOBILE RESPONSIVE CONTROL ===== */
    @media (max-width: 1023px) {
        #mobileSidebarToggleBtn {
            display: flex !important;
        }

        #sidebar {
            position: fixed !important;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 50 !important;
            transform: translateX(-100%);
            width: 300px !important;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border-right: 1px solid rgba(59, 130, 246, 0.15) !important;
        }

        #sidebar.mobile-open {
            transform: translateX(0) !important;
        }

        #sidebar.collapsed {
            width: 300px !important;
        }

        #sidebar.collapsed .sidebar-logo-text {
            opacity: 1;
            visibility: visible;
            width: auto;
            margin: 0;
        }

        #sidebar.collapsed nav a,
        #sidebar.collapsed nav button[data-bantuan-toggle] {
            justify-content: flex-start;
            padding: 14px 16px;
            width: auto;
            height: auto;
            margin: 0;
            gap: 12px;
            border-radius: 16px;
        }

        #sidebar.collapsed nav a i,
        #sidebar.collapsed nav button[data-bantuan-toggle] i {
            margin: 0;
            width: 20px;
        }

        #sidebar.collapsed nav a span,
        #sidebar.collapsed nav a .fa-chevron-down,
        #sidebar.collapsed nav button[data-bantuan-toggle] span,
        #sidebar.collapsed nav button[data-bantuan-toggle] .fa-chevron-down {
            display: inline;
        }

        #sidebar.collapsed .bantuan-submenu {
            display: block !important;
        }

        #sidebar.collapsed .bantuan-submenu.hidden {
            display: none !important;
        }

        #sidebar.collapsed .sidebar-footer-card {
            opacity: 1;
            visibility: visible;
            height: auto;
            padding: 20px;
            margin: 0;
        }

        #sidebar.collapsed .sidebar-footer-mini {
            display: none !important;
        }

        #sidebar.collapsed .sidebar-toggle-icon {
            transform: rotate(0deg);
        }

        #sidebar.collapsed .sidebar-logo-wrapper {
            justify-content: flex-start;
            padding: 20px 24px;
        }

        #sidebar.collapsed .sidebar-logo-wrapper>button,
        #sidebar.collapsed .sidebar-logo-wrapper>.sidebar-logo-text {
            display: flex;
        }

        #sidebar.collapsed .sidebar-logo-wrapper>.sidebar-toggle-collapsed {
            display: none !important;
        }

        #sidebar.collapsed nav a:hover::after,
        #sidebar.collapsed nav a:hover::before,
        #sidebar.collapsed nav button[data-bantuan-toggle]:hover::after,
        #sidebar.collapsed nav button[data-bantuan-toggle]:hover::before {
            display: none !important;
            content: none !important;
        }

        .sidebar-toggle-desktop {
            display: none !important;
        }

        .sidebar-hamburger-mobile {
            display: flex !important;
        }
    }

    @media (min-width: 1024px) {

        .sidebar-hamburger-mobile,
        #mobileSidebarToggleBtn {
            display: none !important;
        }

        .sidebar-toggle-desktop {
            display: flex !important;
        }
    }
</style>

{{-- SIDEBAR JAVASCRIPT --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleDesktop = document.getElementById('sidebarToggle');
        const toggleDesktopCollapsed = document.getElementById('sidebarToggleCollapsed');
        const overlay = document.getElementById('sidebarOverlay');
        const sidebar = document.getElementById('sidebar');

        function updateTooltipPositions() {
            if (window.innerWidth >= 1024 && sidebar && sidebar.classList.contains('collapsed')) {
                const links = sidebar.querySelectorAll('nav a, nav button[data-bantuan-toggle]');
                links.forEach(link => {
                    const rect = link.getBoundingClientRect();
                    link.style.setProperty('--tooltip-top', (rect.top + rect.height / 2) + 'px');
                });
            }
        }

        function openMobileSidebar() {
            if (!sidebar) return;
            sidebar.classList.add('mobile-open');
            if (overlay) overlay.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeMobileSidebar() {
            if (!sidebar) return;
            sidebar.classList.remove('mobile-open');
            if (overlay) overlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function toggleMobileSidebar() {
            if (sidebar.classList.contains('mobile-open')) {
                closeMobileSidebar();
            } else {
                openMobileSidebar();
            }
        }

        function toggleSidebarDesktop(e) {
            e.stopPropagation();

            if (window.innerWidth >= 1024) {
                const collapsed = sidebar.classList.toggle('collapsed');
                localStorage.setItem('sidebarCollapsed', collapsed ? 'true' : 'false');

                window.dispatchEvent(new CustomEvent('sidebar-toggle', {
                    detail: {
                        collapsed: collapsed
                    }
                }));

                setTimeout(updateTooltipPositions, 50);
            }
        }

        if (toggleDesktop) {
            toggleDesktop.addEventListener('click', toggleSidebarDesktop);
        }

        if (toggleDesktopCollapsed) {
            toggleDesktopCollapsed.addEventListener('click', toggleSidebarDesktop);
        }

        // Event delegation untuk tombol hamburger mobile (luar dan dalam sidebar)
        document.addEventListener('click', function(e) {
            const mobileTrigger = e.target.closest(
                '#mobileSidebarToggle, #mobileSidebarToggleBtn, [data-mobile-sidebar-toggle], .sidebar-hamburger-mobile'
            );
            if (mobileTrigger && window.innerWidth < 1024) {
                e.stopPropagation();
                toggleMobileSidebar();
            }
        });

        // Toggle bantuan dropdown
        document.querySelectorAll('[data-bantuan-toggle]').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const submenu = btn.nextElementSibling;
                if (!submenu) return;
                submenu.classList.toggle('hidden');
                btn.querySelector('.fa-chevron-down')?.classList.toggle('rotate-180');
            });
        });

        if (overlay) {
            overlay.addEventListener('click', function() {
                closeMobileSidebar();
            });
        }

        if (window.innerWidth >= 1024) {
            const savedState = localStorage.getItem('sidebarCollapsed');
            if (savedState === 'true') {
                sidebar.classList.add('collapsed');
            }
        }

        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (window.innerWidth >= 1024) {
                    closeMobileSidebar();
                    const savedState = localStorage.getItem('sidebarCollapsed');
                    if (savedState === 'true') {
                        sidebar.classList.add('collapsed');
                    } else {
                        sidebar.classList.remove('collapsed');
                    }
                } else {
                    sidebar.style.width = '';
                    sidebar.classList.remove('collapsed');
                }
                window.dispatchEvent(new CustomEvent('sidebar-toggle', {
                    detail: {
                        collapsed: sidebar.classList.contains('collapsed')
                    }
                }));
            }, 250);
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && window.innerWidth < 1024) {
                closeMobileSidebar();
            }
        });

        window.addEventListener('scroll', updateTooltipPositions, {
            passive: true
        });
        window.addEventListener('resize', updateTooltipPositions);

        if (sidebar) {
            const observer = new MutationObserver(function() {
                if (window.innerWidth >= 1024 && sidebar.classList.contains('collapsed')) {
                    updateTooltipPositions();
                }
            });
            observer.observe(sidebar, {
                attributes: true,
                attributeFilter: ['class']
            });
        }

        setTimeout(function() {
            window.dispatchEvent(new CustomEvent('sidebar-toggle', {
                detail: {
                    collapsed: sidebar.classList.contains('collapsed')
                }
            }));
        }, 100);
    });
</script>
