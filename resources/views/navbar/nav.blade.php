{{-- =========================================================
    APEXFORGE LABS — SHARED NAVBAR
    Support:
    - Guest
    - Freelancer
    - Company
    - Admin
========================================================= --}}

<header
    class="h-16 bg-white/90 backdrop-blur-3xl border-b border-blue-100 px-6 flex items-center justify-between sticky top-0 z-40 shadow-[0_10px_30px_-15px_rgba(59,130,246,0.1)]">

{{-- ApexForge Labs — Shared UI polish --}}
<header id="mainHeader" class="h-16 bg-white/90 dark:bg-slate-900/90 backdrop-blur-3xl border-b border-blue-100 dark:border-slate-800 px-6 flex items-center justify-between sticky top-0 z-40 shadow-[0_10px_30px_-15px_rgba(59,130,246,0.1)] transition-colors duration-300">

    <div class="flex items-center gap-8">
        <nav class="hidden lg:flex items-center gap-6">

            {{-- ================= GUEST ================= --}}
            @guest

                <a href="{{ url('/') }}"
                    class="text-sm font-bold text-blue-900/60 hover:text-blue-600 transition-all duration-300">
                    Beranda
                </a>

                <a href="{{ url('/projects') }}"
                    class="text-sm font-bold text-blue-900/60 hover:text-blue-600 transition-all duration-300">
                    Jelajahi Proyek
                </a>

            @endguest


            {{-- ================= AUTH ================= --}}
            @auth

                {{-- ================= FREELANCER ================= --}}
                @if(Auth::user()->role == 'freelancer')

                    <a href="/freelancer/dashboard"
                        class="text-sm font-bold transition-all duration-300 relative group
                        {{ request()->is('freelancer/dashboard') ? 'text-blue-700' : 'text-blue-900/50 hover:text-blue-600' }}">

                        Dashboard

                        @if(request()->is('freelancer/dashboard'))
                            <span
                                class="absolute -bottom-1 left-0 w-full h-0.5 bg-blue-500 rounded-full shadow-[0_0_8px_rgba(59,130,246,0.6)]">
                            </span>
                        @endif

                    </a>

                    <a href="/freelancer/projects"
                        class="text-sm font-bold transition-all duration-300 relative group
                        {{ request()->is('freelancer/projects*') ? 'text-blue-700' : 'text-blue-900/50 hover:text-blue-600' }}">

                        Proyek

                        @if(request()->is('freelancer/projects*'))
                            <span
                                class="absolute -bottom-1 left-0 w-full h-0.5 bg-blue-500 rounded-full shadow-[0_0_8px_rgba(59,130,246,0.6)]">
                            </span>
                        @endif

                    </a>

                {{-- ================= COMPANY ================= --}}
                @elseif(Auth::user()->role == 'company')

                    <a href="/company/dashboard"
                        class="text-sm font-bold transition-all duration-300 relative group
                        {{ request()->is('company/dashboard') ? 'text-blue-700' : 'text-blue-900/50 hover:text-blue-600' }}">

                        Dashboard

                        @if(request()->is('company/dashboard'))
                            <span
                                class="absolute -bottom-1 left-0 w-full h-0.5 bg-blue-500 rounded-full shadow-[0_0_8px_rgba(59,130,246,0.6)]">
                            </span>
                        @endif

                    </a>

                    <a href="/company/projects"
                        class="text-sm font-bold transition-all duration-300 relative group
                        {{ request()->is('company/projects*') ? 'text-blue-700' : 'text-blue-900/50 hover:text-blue-600' }}">

                        Proyek Saya

                        @if(request()->is('company/projects*'))
                            <span
                                class="absolute -bottom-1 left-0 w-full h-0.5 bg-blue-500 rounded-full shadow-[0_0_8px_rgba(59,130,246,0.6)]">
                            </span>
                        @endif

                    </a>

                    <a href="{{ route('company.projects.create') }}"
                        class="text-sm font-bold text-blue-900/50 hover:text-blue-600 transition-all duration-300">
                        Tambah Proyek
                    </a>


                {{-- ================= ADMIN ================= --}}
                @elseif(Auth::user()->role == 'admin')

                    <a href="{{ route('admin.dashboard') }}"
                        class="text-sm font-bold transition-all duration-300 relative group
                        {{ request()->routeIs('admin.dashboard') ? 'text-blue-700' : 'text-blue-900/50 hover:text-blue-600' }}">

                        Dashboard

                        @if(request()->routeIs('admin.dashboard'))
                            <span
                                class="absolute -bottom-1 left-0 w-full h-0.5 bg-blue-500 rounded-full shadow-[0_0_8px_rgba(59,130,246,0.6)]">
                            </span>
                        @endif

                    </a>

                    <a href="{{ route('admin.users.index') }}"
                        class="text-sm font-bold transition-all duration-300 relative group
                        {{ request()->routeIs('admin.users.*') ? 'text-blue-700' : 'text-blue-900/50 hover:text-blue-600' }}">

                        Pengguna

                        @if(request()->routeIs('admin.users.*'))
                            <span
                                class="absolute -bottom-1 left-0 w-full h-0.5 bg-blue-500 rounded-full shadow-[0_0_8px_rgba(59,130,246,0.6)]">
                            </span>
                        @endif

                    </a>

                    <a href="{{ route('admin.projects.index') }}"
                        class="text-sm font-bold transition-all duration-300 relative group
                        {{ request()->routeIs('admin.projects.*') ? 'text-blue-700' : 'text-blue-900/50 hover:text-blue-600' }}">

                        Proyek

                        @if(request()->routeIs('admin.projects.*'))
                            <span
                                class="absolute -bottom-1 left-0 w-full h-0.5 bg-blue-500 rounded-full shadow-[0_0_8px_rgba(59,130,246,0.6)]">
                            </span>
                        @endif

                    </a>

                    <a href="{{ route('admin.reports.index') }}"
                        class="text-sm font-bold transition-all duration-300 relative group
                        {{ request()->routeIs('admin.reports.*') ? 'text-blue-700' : 'text-blue-900/50 hover:text-blue-600' }}">

                        Laporan

                        @if(request()->routeIs('admin.reports.*'))
                            <span
                                class="absolute -bottom-1 left-0 w-full h-0.5 bg-blue-500 rounded-full shadow-[0_0_8px_rgba(59,130,246,0.6)]">
                            </span>
                        @endif

                    </a>

                @endif

            @endauth

        </nav>
    </div>

    <div class="flex items-center gap-5">
        <div class="relative">
            <button id="notificationButton" aria-label="Notifikasi"
                class="relative w-10 h-10 rounded-2xl border border-blue-100 dark:border-slate-800 hover:bg-blue-50 dark:hover:bg-slate-800 hover:border-blue-200 dark:hover:border-slate-700 hover:shadow-[0_0_15px_rgba(59,130,246,0.15)] flex items-center justify-center transition-all duration-300 group">
                <i class="fa-regular fa-bell text-blue-600 dark:text-blue-400 group-hover:animate-swing"></i>
                <span id="notificationBadge"
                    class="absolute -top-1.5 -right-1.5 min-w-[20px] h-[20px] rounded-full bg-gradient-to-r from-blue-600 to-blue-400 shadow-[0_0_10px_rgba(59,130,246,0.5)] text-white text-[10px] font-black flex items-center justify-center px-1 border-2 border-white dark:border-slate-900 transition-transform transform scale-0"></span>
            </button>

            <div id="notificationDropdown"
                class="hidden absolute right-0 mt-4 w-[380px] bg-white/95 dark:bg-slate-900/95 backdrop-blur-2xl rounded-[1.5rem] border border-blue-100 dark:border-slate-800 shadow-[0_20px_50px_-10px_rgba(30,58,138,0.15)] overflow-hidden z-[100] transform transition-all">
                <div class="p-5 border-b border-blue-50/50 dark:border-slate-800 flex items-center justify-between bg-gradient-to-b from-blue-50/30 dark:from-slate-800/50 to-transparent">
                    <h3 class="font-black text-sm text-blue-950 dark:text-white tracking-wide">Notifikasi Sistem</h3>
                    <button id="markAllReadBtn" class="text-[11px] text-blue-500 font-bold hover:text-blue-700 dark:hover:text-blue-400 transition-colors">Tandai semua dibaca</button>
                </div>
                <div id="notificationList" class="max-h-[360px] overflow-y-auto custom-sidebar-scroll">
                    <div class="p-8 text-center text-sm text-blue-300 dark:text-slate-500 font-semibold">
                        <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-slate-800 flex items-center justify-center mx-auto mb-3 text-blue-400 dark:text-slate-400">
                            <i class="fa-regular fa-bell-slash text-xl"></i>
                        </div>
                        Tidak ada notifikasi aktif
                    </div>
                </div>
            </div>
        </div>

        <div class="relative">
            @auth
                <button id="userButton" class="flex items-center gap-3 border border-transparent hover:border-blue-100 dark:hover:border-slate-800 hover:bg-blue-50/50 dark:hover:bg-slate-800/50 rounded-2xl px-2 py-1.5 transition-all duration-300 group">
                    <div class="text-right hidden sm:block pr-1">
                        <h2 class="text-[13px] font-black text-blue-950 dark:text-white leading-tight">{{ Auth::user()->name }}</h2>
                        <p class="text-[10px] font-bold tracking-widest uppercase text-blue-400 dark:text-slate-400">{{ ucfirst(Auth::user()->role) }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-[0.9rem] overflow-hidden bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white shrink-0 shadow-[0_4px_15px_rgba(59,130,246,0.3)] group-hover:scale-105 transition-transform">
                        @if (Auth::user()->role == 'company' && Auth::user()->companyProfile && Auth::user()->companyProfile->company_logo)
                            <img src="{{ asset('storage/' . Auth::user()->companyProfile->company_logo) }}" alt="Logo" class="w-full h-full object-cover">
                        @elseif(Auth::user()->role == 'freelancer' && Auth::user()->freelanceProfile && Auth::user()->freelanceProfile->photo)
                            <img src="{{ asset('storage/' . Auth::user()->freelanceProfile->photo) }}" alt="Profil" class="w-full h-full object-cover">
                        @else
                            <i class="fa-solid fa-user text-sm"></i>
                        @endif
                    </div>
                    <i class="fa-solid fa-chevron-down text-[10px] text-blue-300 dark:text-slate-500 group-hover:text-blue-500 transition-colors"></i>
                </button>

                <div id="userDropdown"
                    class="hidden absolute right-0 mt-4 w-72 bg-white/95 dark:bg-slate-900/95 backdrop-blur-2xl rounded-[1.5rem] border border-blue-100 dark:border-slate-800 shadow-[0_20px_50px_-10px_rgba(30,58,138,0.15)] overflow-hidden z-[100]">
                    
                    <div class="p-6 border-b border-blue-50/50 dark:border-slate-800 bg-gradient-to-b from-blue-50/50 dark:from-slate-800/50 to-transparent">
                        <h2 class="font-black text-blue-950 dark:text-white tracking-tight">{{ Auth::user()->name }}</h2>
                        <p class="text-xs font-semibold text-blue-500/70 truncate">{{ Auth::user()->email }}</p>
                    </div>


                    <div
                        id="notificationList"
                        class="max-h-[360px] overflow-y-auto">

                        <div class="p-8 text-center text-sm text-blue-300 font-semibold">

                            <div
                                class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center mx-auto mb-3 text-blue-400">

                                <i class="fa-regular fa-bell-slash text-xl"></i>

                            </div>

                            Tidak ada notifikasi aktif

                        </div>

                    </div>

                </div>

            </div>


            <!-- =================================================
                USER DROPDOWN
            ================================================== -->
            <div class="relative">

                <button
                    id="userButton"
                    class="flex items-center gap-3 border border-transparent hover:border-blue-100 hover:bg-blue-50/50 rounded-2xl px-2 py-1.5 transition-all duration-300 group">

                    <!-- USER NAME -->
                    <div class="text-right hidden sm:block pr-1">

                        <h2 class="text-[13px] font-black text-blue-950 leading-tight">
                            {{ Auth::user()->name }}
                        </h2>

                        <p class="text-[10px] font-bold tracking-widest uppercase text-blue-400">
                            {{ ucfirst(Auth::user()->role) }}
                        </p>

                    </div>


                    <!-- PROFILE IMAGE -->
                    <div
                        class="w-10 h-10 rounded-[0.9rem] overflow-hidden bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white shrink-0 shadow-[0_4px_15px_rgba(59,130,246,0.3)] group-hover:scale-105 transition-transform">

                        @if(
                            Auth::user()->role == 'company' &&
                            Auth::user()->companyProfile &&
                            Auth::user()->companyProfile->company_logo
                        )

                            <img
                                src="{{ asset('storage/' . Auth::user()->companyProfile->company_logo) }}"
                                alt="Logo"
                                class="w-full h-full object-cover">

                        @elseif(
                            Auth::user()->freelanceProfile &&
                            Auth::user()->freelanceProfile->photo
                        )

                            <img
                                src="{{ asset('storage/' . Auth::user()->freelanceProfile->photo) }}"
                                alt="Profil"
                                class="w-full h-full object-cover">

                        @else

                            <i class="fa-solid fa-user text-sm"></i>

                        @endif

                    </div>


                    <i
                        class="fa-solid fa-chevron-down text-[10px] text-blue-300 group-hover:text-blue-500 transition-colors">
                    </i>

                </button>


                <!-- USER DROPDOWN -->
                <div
                    id="userDropdown"
                    class="hidden absolute right-0 mt-4 w-72 bg-white/95 backdrop-blur-2xl rounded-[1.5rem] border border-blue-100 shadow-[0_20px_50px_-10px_rgba(30,58,138,0.15)] overflow-hidden z-[100]">

                    <!-- USER INFO -->
                    <div
                        class="p-6 border-b border-blue-50/50 bg-gradient-to-b from-blue-50/50 to-transparent">

                        <h2 class="font-black text-blue-950 tracking-tight">
                            {{ Auth::user()->name }}
                        </h2>

                        <p class="text-xs font-semibold text-blue-500/70 truncate">
                            {{ Auth::user()->email }}
                        </p>

                    </div>


                    <!-- MENU -->
                    <div class="p-2 space-y-1">

                        {{-- FREELANCER --}}
                        @if(Auth::user()->role == 'freelancer')

                            <a
                                href="{{ route('freelancer.profile') }}"
                                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-blue-900/70 hover:bg-blue-50 hover:text-blue-700 transition-colors">

                                <i class="fa-regular fa-user text-blue-500 w-5 text-center"></i>

                                Profil Utama

                            </a>

                            <a
                                href="#"
                                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-blue-900/70 hover:bg-blue-50 hover:text-blue-700 transition-colors">

                                <i class="fa-regular fa-file-lines text-blue-500 w-5 text-center"></i>

                                Lamaran Saya

                            </a>

                        {{-- COMPANY --}}
                        @elseif(Auth::user()->role == 'company')

                            <a
                                href="{{ route('company.profile') }}"
                                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-blue-900/70 hover:bg-blue-50 hover:text-blue-700 transition-colors">

                                <i class="fa-regular fa-building text-blue-500 w-5 text-center"></i>

                                Profil Perusahaan

                            </a>

                            <a
                                href="{{ route('company.projects.create') }}"
                                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-blue-900/70 hover:bg-blue-50 hover:text-blue-700 transition-colors">

                                <i class="fa-solid fa-plus text-blue-500 w-5 text-center"></i>

                                Tambah Proyek

                            </a>

                        {{-- ADMIN --}}
                        @elseif(Auth::user()->role == 'admin')

                            <a
                                href="#"
                                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-blue-900/70 hover:bg-blue-50 hover:text-blue-700 transition-colors">

                                <i class="fa-solid fa-shield-halved text-blue-500 w-5 text-center"></i>

                                Profil Admin

                            </a>

                            <a
                                href="#"
                                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-blue-900/70 hover:bg-blue-50 hover:text-blue-700 transition-colors">

                                <i class="fa-solid fa-chart-pie text-blue-500 w-5 text-center"></i>

                                Analitik Sistem

                            </a>

                        @endif


                        <!-- SETTINGS -->
                        <a
                            href="#"
                            id="btnBukaPengaturan"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-blue-900/70 hover:bg-blue-50 hover:text-blue-700 transition-colors">

                            <i class="fa-solid fa-gear text-blue-500 w-5 text-center"></i>

                            Pengaturan

                        </a>

                    </div>


                    <!-- LOGOUT -->
                    <div class="p-2 border-t border-blue-50">

                        <form action="{{ url('/logout') }}" method="POST">

                            @csrf

                            <button
                                type="submit"
                                class="w-full text-left px-4 py-3 flex items-center gap-3 rounded-xl text-sm font-bold text-blue-600 hover:bg-blue-600 hover:text-white transition-colors group">

                                <i
                                    class="fa-solid fa-power-off w-5 text-center transition-transform group-hover:scale-110">
                                </i>

                                Logout

                            </button>

                        </form>

                    </div>

                </div>
            @else
                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}"
                        class="px-5 py-2 text-sm font-semibold text-slate-700 hover:text-slate-900 border border-blue-100/80 rounded-xl hover:bg-blue-50/60 transition">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                        class="px-5 py-2 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition shadow-md shadow-blue-500/20">
                        Daftar
                    </a>
                </div>
            @endauth
        </div>
    </div>

</header>

@auth
<div id="modalSettings" class="hidden fixed inset-0 z-[150] flex items-center justify-center bg-blue-950/30 dark:bg-slate-950/60 backdrop-blur-sm transition-opacity p-4">
    <div class="bg-white dark:bg-slate-900 w-full max-w-4xl rounded-[2rem] shadow-[0_20px_50px_-10px_rgba(30,58,138,0.2)] overflow-hidden transform transition-all border border-blue-100 dark:border-slate-800 flex flex-col md:flex-row max-h-[85vh]">
        
        <div class="w-full md:w-72 bg-blue-50/40 dark:bg-slate-950/50 p-6 border-b md:border-b-0 md:border-r border-blue-100 dark:border-slate-800 flex flex-col justify-between shrink-0">
            <div>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-black text-blue-950 dark:text-white tracking-tight">Settings</h2>
                    <button id="closeModalSettingsMobile" class="md:hidden text-blue-400 hover:text-blue-600">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>
                <p class="text-xs font-medium text-blue-400 dark:text-slate-400 mb-6">Kelola pengaturan akun dan preferensi Anda</p>

                <nav class="space-y-1.5">

                    <button
                        type="button"
                        data-tab="security"
                        class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition-all bg-blue-600 text-white shadow-md shadow-blue-500/20">

                        <i class="fa-solid fa-shield-halved w-5 text-center text-sm"></i>
                        Security

                    </button>


                    <button
                        type="button"
                        data-tab="payment"
                        class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition-all text-blue-900/70 hover:bg-blue-100/50 hover:text-blue-950">

                        <i class="fa-solid fa-wallet w-5 text-center text-sm text-blue-500"></i>
                        Payment & Payout

                    </button>


                    <button
                        type="button"
                        data-tab="notifications"
                        class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition-all text-blue-900/70 hover:bg-blue-100/50 hover:text-blue-950">

                        <i class="fa-solid fa-bell w-5 text-center text-sm text-blue-500"></i>
                        Notifications

                    </button>


                    <button
                        type="button"
                        data-tab="privacy"
                        class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition-all text-blue-900/70 hover:bg-blue-100/50 hover:text-blue-950">

                        <i class="fa-solid fa-lock w-5 text-center text-sm text-blue-500"></i>
                        Privacy

                    </button>


                    <button
                        type="button"
                        data-tab="appearance"
                        class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition-all text-blue-900/70 hover:bg-blue-100/50 hover:text-blue-950">

                        <i class="fa-solid fa-palette w-5 text-center text-sm text-blue-500"></i>
                        Appearance

                    </button>


                    <button
                        type="button"
                        data-tab="account"
                        class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition-all text-blue-900/70 hover:bg-blue-100/50 hover:text-blue-950">

                        <i class="fa-solid fa-user-gear w-5 text-center text-sm text-blue-500"></i>
                        Account

                    </button>

                </nav>

            </div>


            <div class="pt-4 hidden md:block">

                <button
                    id="closeModalSettings"
                    class="w-full py-2.5 rounded-xl text-xs font-bold text-blue-600 bg-blue-100/60 hover:bg-blue-200/60 transition-colors">

                    Tutup Jendela

                </button>

            </div>

        </div>

        <div class="flex-1 p-6 md:p-8 overflow-y-auto max-h-[75vh] text-slate-800 dark:text-slate-100">
            
            <div id="content-security" class="tab-content space-y-6">

                <div class="flex items-center justify-between border-b border-blue-50 pb-4">

                    <div>

                        <h3 class="text-sm font-black text-blue-950 tracking-tight">
                            Security
                        </h3>

                        <p class="text-xs text-blue-400 mt-0.5">
                            Kelola keamanan akun Anda untuk menjaga akun tetap aman.
                        </p>

                    </div>

                </div>


                <div class="space-y-3">
                    <div class="p-4 rounded-2xl border border-blue-100/80 dark:border-slate-800 bg-white dark:bg-slate-800/50 hover:border-blue-200 dark:hover:border-slate-700 transition-all flex items-center justify-between gap-4">
                        <div class="flex items-start gap-3.5">

                            <div
                                class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center shrink-0">

                                <i class="fa-solid fa-key text-sm"></i>

                            </div>

                            <div>

                                <h4 class="text-xs font-black text-blue-950">
                                    Ubah Password
                                </h4>

                                <p class="text-[11px] text-blue-400 mt-0.5">
                                    Gunakan password yang kuat untuk melindungi akun Anda.
                                </p>

                            </div>

                        </div>

                        <button type="button" id="btnBukaUbahPassword"
                            class="px-3.5 py-2 rounded-xl text-xs font-bold text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-slate-700 hover:bg-blue-50 dark:hover:bg-slate-700 transition-colors shrink-0">
                            Ubah Password

                        </button>

                    </div>

                    <div class="p-4 rounded-2xl border border-blue-100/80 dark:border-slate-800 bg-white dark:bg-slate-800/50 hover:border-blue-200 dark:hover:border-slate-700 transition-all flex items-center justify-between gap-4">
                        <div class="flex items-start gap-3.5">

                            <div
                                class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">

                                <i class="fa-solid fa-envelope-circle-check text-sm"></i>

                            </div>

                            <div>

                                <h4 class="text-xs font-black text-blue-950">
                                    Verifikasi Email
                                </h4>

                                <p class="text-[11px] text-blue-400 mt-0.5">
                                    Email Anda telah diverifikasi.
                                </p>

                            </div>

                        </div>

                        <span
                            class="px-3 py-1.5 rounded-lg text-[10px] font-bold bg-emerald-100/70 text-emerald-700 shrink-0 flex items-center gap-1.5">

                            <i class="fa-solid fa-check text-[9px]"></i>
                            Terverifikasi

                        </span>

                    </div>

                    <div class="p-4 rounded-2xl border border-blue-100/80 dark:border-slate-800 bg-white dark:bg-slate-800/50 hover:border-blue-200 dark:hover:border-slate-700 transition-all flex items-center justify-between gap-4">
                        <div class="flex items-start gap-3.5">

                            <div
                                class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center shrink-0">

                                <i class="fa-solid fa-shield-cat text-sm"></i>

                            </div>

                            <div>

                                <h4 class="text-xs font-black text-blue-950">
                                    Verifikasi 2FA
                                </h4>

                                <p class="text-[11px] text-blue-400 mt-0.5">
                                    Tambahkan lapisan keamanan ekstra dengan autentikasi dua faktor.
                                </p>

                            </div>

                        </div>

            <div id="content-appearance" class="tab-content hidden space-y-6">
                <div class="border-b border-blue-50 dark:border-slate-800 pb-4">
                    <h3 class="text-sm font-black text-blue-950 dark:text-white tracking-tight">Appearance</h3>
                    <p class="text-xs text-blue-400 dark:text-slate-400 mt-0.5">Sesuaikan tema, bahasa, dan tampilan aplikasi.</p>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 rounded-2xl border border-blue-100 dark:border-slate-800 bg-white dark:bg-slate-800/50">
                        <div>
                            <p class="text-xs font-bold text-blue-950 dark:text-white">Mode Gelap (Dark Mode)</p>
                            <p class="text-[11px] text-blue-400 dark:text-slate-400">Ganti tema aplikasi menjadi gelap</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="darkModeToggle" class="sr-only peer">
                            <div class="w-10 h-5 bg-blue-100 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-blue-200 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>
            </div>

            <div id="content-account" class="tab-content hidden space-y-6">
                <div class="border-b border-blue-50 dark:border-slate-800 pb-4">
                    <h3 class="text-sm font-black text-blue-950 dark:text-white tracking-tight">Account</h3>
                    <p class="text-xs text-blue-400 dark:text-slate-400 mt-0.5">Kelola informasi akun dasar Anda.</p>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-blue-900/70 dark:text-slate-300 mb-1">Nama Lengkap</label>
                        <input type="text" value="{{ Auth::user()->name }}" class="w-full px-4 py-2.5 rounded-xl border border-blue-100 dark:border-slate-700 bg-blue-50/30 dark:bg-slate-800 text-blue-950 dark:text-white text-xs font-bold focus:outline-none focus:border-blue-300 dark:focus:border-slate-500 focus:bg-white dark:focus:bg-slate-800 transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-blue-900/70 dark:text-slate-300 mb-1">Email</label>
                        <input type="email" value="{{ Auth::user()->email }}" class="w-full px-4 py-2.5 rounded-xl border border-blue-100 dark:border-slate-700 bg-blue-50/30 dark:bg-slate-800 text-blue-950 dark:text-white text-xs font-bold focus:outline-none focus:border-blue-300 dark:focus:border-slate-500 focus:bg-white dark:focus:bg-slate-800 transition-colors">
                    </div>
                    <button class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition-all">Simpan Perubahan</button>
                </div>
            </div>

        </div>

    </div>
</div>
@endauth

@auth
<div id="modalUbahPassword"
    class="hidden fixed inset-0 z-[200] flex items-center justify-center bg-blue-950/30 dark:bg-slate-950/70 backdrop-blur-sm p-4">

    <div class="w-full max-w-md bg-white dark:bg-slate-900 rounded-[2rem] border border-blue-100 dark:border-slate-800 shadow-[0_20px_50px_-10px_rgba(30,58,138,0.25)] overflow-hidden">

        <div class="p-6 border-b border-blue-50 dark:border-slate-800 flex items-center justify-between">
            <div>
                <h3 id="modalPasswordTitle" class="text-base font-black text-blue-950 dark:text-white">
                    Verifikasi Keamanan
                </h3>
                <p id="modalPasswordSubtitle" class="text-xs text-blue-400 dark:text-slate-400 mt-1">
                    Masukkan password saat ini untuk melanjutkan.
                </p>
            </div>

            <button type="button" id="closeModalUbahPassword"
                class="w-9 h-9 rounded-xl flex items-center justify-center text-blue-400 dark:text-slate-400 hover:bg-blue-50 dark:hover:bg-slate-800 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="p-6">

            <div id="step1Card" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-blue-900/70 dark:text-slate-300 mb-1.5">
                        Password Saat Ini
                    </label>

                    <div class="relative">
                        <input
                            type="password"
                            id="currentPassword"
                            placeholder="Masukkan password saat ini"
                            class="w-full px-4 py-3 pr-11 rounded-xl border border-blue-100 dark:border-slate-700 bg-blue-50/30 dark:bg-slate-800 text-blue-950 dark:text-white placeholder-blue-300 dark:placeholder-slate-500 text-xs font-bold focus:outline-none focus:border-blue-300 dark:focus:border-slate-500 focus:bg-white dark:focus:bg-slate-800 transition-colors">

                        <button type="button"
                            data-password-toggle="currentPassword"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-blue-400 dark:text-slate-500 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                            <i class="fa-regular fa-eye"></i>
                        </button>

                    </div>


                    <!-- SESSION -->
                    <div
                        class="p-4 rounded-2xl border border-blue-100/80 bg-white hover:border-blue-200 transition-all flex items-center justify-between gap-4">

                        <div class="flex items-start gap-3.5">

                            <div
                                class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center shrink-0">

                                <i class="fa-solid fa-laptop text-sm"></i>

                            </div>

                            <div>

                                <h4 class="text-xs font-black text-blue-950">
                                    Sesi Aktif
                                </h4>

                                <p class="text-[11px] text-blue-400 mt-0.5">
                                    Kelola perangkat yang sedang login ke akun Anda.
                                </p>

                            </div>

                        </div>

                        <button
                            class="px-3.5 py-2 rounded-xl text-xs font-bold text-blue-600 border border-blue-200 hover:bg-blue-50 transition-colors shrink-0">

                            Kelola Sesi

                        </button>

                    </div>


                    <!-- LOGOUT ALL -->
                    <div
                        class="p-4 rounded-2xl border border-red-100/80 bg-red-50/10 hover:border-red-200 transition-all flex items-center justify-between gap-4">

                        <div class="flex items-start gap-3.5">

                            <div
                                class="w-10 h-10 rounded-xl bg-red-50 border border-red-100 text-red-500 flex items-center justify-center shrink-0">

                                <i class="fa-solid fa-right-from-bracket text-sm"></i>

                            </div>

                            <div>

                                <h4 class="text-xs font-black text-red-950">
                                    Logout dari Semua Perangkat
                                </h4>

                                <p class="text-[11px] text-red-400 mt-0.5">
                                    Keluar dari semua perangkat kecuali perangkat yang Anda gunakan sekarang.
                                </p>

                            </div>

                        </div>

                        <button
                            class="px-3.5 py-2 rounded-xl text-xs font-bold text-red-600 border border-red-200 hover:bg-red-50 transition-colors shrink-0">

                            Logout Semua

                        </button>

                    </div>

                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" id="btnBatalStep1"
                        class="px-4 py-2.5 rounded-xl text-xs font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-slate-800 hover:bg-blue-100 dark:hover:bg-slate-700 transition-colors">
                        Batal
                    </button>

                </div>

            </div>

            <div id="step2Card" class="hidden space-y-4">
                <div>
                    <label class="block text-xs font-bold text-blue-900/70 dark:text-slate-300 mb-1.5">
                        Password Baru
                    </label>

                <div class="border-b border-blue-50 pb-4">

                    <h3 class="text-sm font-black text-blue-950 tracking-tight">
                        Notifications
                    </h3>

                    <p class="text-xs text-blue-400 mt-0.5">
                        Atur preferensi pemberitahuan sistem dan email.
                    </p>

                </div>

                <div>
                    <label class="block text-xs font-bold text-blue-900/70 dark:text-slate-300 mb-1.5">
                        Konfirmasi Password Baru
                    </label>

                    <div>

                        <button type="button"
                            data-password-toggle="confirmPassword"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-blue-400 dark:text-slate-500 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="p-3.5 rounded-xl bg-blue-50/50 dark:bg-slate-800 border border-blue-100 dark:border-slate-700">
                    <div class="flex items-start gap-2.5">
                        <i class="fa-solid fa-circle-info text-blue-500 dark:text-blue-400 text-sm mt-0.5"></i>
                        <p class="text-[11px] leading-relaxed text-blue-700 dark:text-slate-300">
                            Gunakan kombinasi minimal 8 karakter agar akun Anda semakin aman.
                        </p>

                        <p class="text-[11px] text-blue-400">
                            Terima notifikasi langsung di browser
                        </p>

                    </div>

                    <label class="relative inline-flex items-center cursor-pointer">

                        <input type="checkbox" class="sr-only peer" checked>

                        <div
                            class="w-10 h-5 bg-blue-100 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600">
                        </div>

                    </label>

                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" id="btnBackStep1"
                        class="px-4 py-2.5 rounded-xl text-xs font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-slate-800 hover:bg-blue-100 dark:hover:bg-slate-700 transition-colors">
                        Kembali
                    </button>


            <!-- PRIVACY -->
            <div id="content-privacy" class="tab-content hidden space-y-6">

                <div class="border-b border-blue-50 pb-4">

                    <h3 class="text-sm font-black text-blue-950 tracking-tight">
                        Privacy
                    </h3>

                    <p class="text-xs text-blue-400 mt-0.5">
                        Kontrol privasi data dan visibilitas profil Anda.
                    </p>

                </div>

                <div
                    class="p-6 rounded-2xl border border-blue-100 bg-white text-xs text-blue-900/70 font-medium">

                    Visibilitas profil publik saat ini diatur aktif.

                </div>

            </div>


            <!-- APPEARANCE -->
            <div id="content-appearance" class="tab-content hidden space-y-6">

                <div class="border-b border-blue-50 pb-4">

                    <h3 class="text-sm font-black text-blue-950 tracking-tight">
                        Appearance
                    </h3>

                    <p class="text-xs text-blue-400 mt-0.5">
                        Sesuaikan tema, bahasa, dan tampilan aplikasi.
                    </p>

                </div>

                <div
                    class="flex items-center justify-between p-4 rounded-2xl border border-blue-100 bg-white">

                    <div>

                        <p class="text-xs font-bold text-blue-950">
                            Mode Gelap
                        </p>

                        <p class="text-[11px] text-blue-400">
                            Ganti tema aplikasi menjadi gelap
                        </p>

                    </div>

                    <label class="relative inline-flex items-center cursor-pointer">

                        <input type="checkbox" class="sr-only peer">

                        <div
                            class="w-10 h-5 bg-blue-100 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600">
                        </div>

                    </label>

                </div>

            </div>


            <!-- ACCOUNT -->
            <div id="content-account" class="tab-content hidden space-y-6">

                <div class="border-b border-blue-50 pb-4">

                    <h3 class="text-sm font-black text-blue-950 tracking-tight">
                        Account
                    </h3>

                    <p class="text-xs text-blue-400 mt-0.5">
                        Kelola informasi akun dasar Anda.
                    </p>

                </div>


                <div class="space-y-4">

                    <div>

                        <label class="block text-xs font-bold text-blue-900/70 mb-1">
                            Nama Lengkap
                        </label>

                        <input
                            type="text"
                            value="{{ Auth::user()->name }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-blue-100 bg-blue-50/30 text-blue-950 text-xs font-bold focus:outline-none focus:border-blue-300 focus:bg-white transition-colors">

                    </div>


                    <div>

                        <label class="block text-xs font-bold text-blue-900/70 mb-1">
                            Email
                        </label>

                        <input
                            type="email"
                            value="{{ Auth::user()->email }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-blue-100 bg-blue-50/30 text-blue-950 text-xs font-bold focus:outline-none focus:border-blue-300 focus:bg-white transition-colors">

                    </div>


                    <button
                        class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition-all">

                        Simpan Perubahan

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>
@endauth


<style>

@keyframes swing {

    0% {
        transform: rotate(0deg);
    }

    25% {
        transform: rotate(15deg);
    }

    50% {
        transform: rotate(-10deg);
    }

    75% {
        transform: rotate(5deg);
    }

    100% {
        transform: rotate(0deg);
    }

}

.animate-swing {
    animation: swing 0.5s ease-in-out;
}

</style>


{{-- =========================================================
    JAVASCRIPT
========================================================= --}}

@auth

<script>
    document.addEventListener('DOMContentLoaded', () => {
// ============= PERBAIKAN: PINDAHKAN MODAL KE <BODY> =============
        // Modal Settings & modal Ubah Password memakai `position: fixed` (fixed inset-0).
        // Pada beberapa halaman (mis. "Cari Proyek" & "Laporan"), `navbar.nav` di-include
        // di dalam wrapper header yang memakai `backdrop-filter` (backdrop-blur-*).
        // Menurut spesifikasi CSS, elemen dengan `backdrop-filter`/`filter`/`transform`
        // menjadi CONTAINING BLOCK bagi keturunan ber-`position: fixed`, sehingga modal
        // terhitung relatif terhadap strip header (bukan viewport) => terdorong ke atas,
        // overlay tidak menutupi layar, dan z-index terjebak di dalam stacking context header.
        // Solusi: pindahkan node modal ke <body> agar `fixed inset-0` kembali merujuk ke
        // viewport => berada di tengah, menutupi seluruh layar, dan di atas sidebar/navbar.
        ['modalSettings', 'modalUbahPassword'].forEach(function (id) {
            const el = document.getElementById(id);
            if (el && el.parentNode && el.parentNode !== document.body) {
                document.body.appendChild(el);
            }
        });
        // ============= DARK MODE INITIALIZER & TOGGLE (PER AKUN) =============
        const darkModeToggle = document.getElementById('darkModeToggle');
        const htmlElement = document.documentElement;
        
        @auth
            const currentUserId = "{{ Auth::id() }}";
        @else
            const currentUserId = 'guest';
        @endauth
        const storageKey = 'theme_user_' + currentUserId;

        if (localStorage.getItem(storageKey) === 'dark') {
            htmlElement.classList.add('dark');
            if (darkModeToggle) darkModeToggle.checked = true;
        } else {
            htmlElement.classList.remove('dark');
            if (darkModeToggle) darkModeToggle.checked = false;
        }

    /* =====================================================
       USER DROPDOWN
    ====================================================== */

    const userButton = document.getElementById('userButton');
    const userDropdown = document.getElementById('userDropdown');

    if (userButton && userDropdown) {

        userButton.addEventListener('click', function (e) {

        // ============= SCRIPT BUKA-TUTUP MODAL SETTINGS =============
        const btnPengaturan = document.getElementById('btnBukaPengaturan');
        const modalSettings = document.getElementById('modalSettings');
        const closeBtn = document.getElementById('closeModalSettings');
        const closeBtnMobile = document.getElementById('closeModalSettingsMobile');

        if (btnPengaturan && modalSettings) {
            btnPengaturan.addEventListener('click', (e) => {
                e.preventDefault();
                modalSettings.classList.remove('hidden');
                if (userDropdown) userDropdown.classList.add('hidden');
            });
        }

        function tutupModal() {
            if (modalSettings) modalSettings.classList.add('hidden');
        }

        if (closeBtn) closeBtn.addEventListener('click', tutupModal);
        if (closeBtnMobile) closeBtnMobile.addEventListener('click', tutupModal);

        if (modalSettings) {
            modalSettings.addEventListener('click', (e) => {
                if (e.target === modalSettings) {
                    tutupModal();
                }
            });
        }

        // ============= MODAL UBAH PASSWORD (MULTI-STEP) =============
        const btnBukaUbahPassword = document.getElementById('btnBukaUbahPassword');
        const modalUbahPassword = document.getElementById('modalUbahPassword');
        const closeModalUbahPassword = document.getElementById('closeModalUbahPassword');
        const btnBatalStep1 = document.getElementById('btnBatalStep1');
        
        const step1Card = document.getElementById('step1Card');
        const step2Card = document.getElementById('step2Card');
        const modalPasswordTitle = document.getElementById('modalPasswordTitle');
        const modalPasswordSubtitle = document.getElementById('modalPasswordSubtitle');

        const btnNextStep = document.getElementById('btnNextStep');
        const btnBackStep1 = document.getElementById('btnBackStep1');
        const btnSimpanPassword = document.getElementById('btnSimpanPassword');

        function bukaModalUbahPassword() {
            if (modalUbahPassword) {
                step1Card.classList.remove('hidden');
                step2Card.classList.add('hidden');
                modalPasswordTitle.textContent = 'Verifikasi Keamanan';
                modalPasswordSubtitle.textContent = 'Masukkan password saat ini untuk melanjutkan.';
                
                document.getElementById('currentPassword').value = '';
                document.getElementById('newPassword').value = '';
                document.getElementById('confirmPassword').value = '';

                modalUbahPassword.classList.remove('hidden');
            }
        }

        function tutupModalUbahPassword() {
            if (modalUbahPassword) {
                modalUbahPassword.classList.add('hidden');
            }
        }

        if (btnBukaUbahPassword) {
            btnBukaUbahPassword.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                bukaModalUbahPassword();
            });
        }

        if (closeModalUbahPassword) closeModalUbahPassword.addEventListener('click', tutupModalUbahPassword);
        if (btnBatalStep1) btnBatalStep1.addEventListener('click', tutupModalUbahPassword);

        if (modalUbahPassword) {
            modalUbahPassword.addEventListener('click', (e) => {
                if (e.target === modalUbahPassword) {
                    tutupModalUbahPassword();
                }
            });
        }

        if (btnBackStep1) {
            btnBackStep1.addEventListener('click', () => {
                step2Card.classList.add('hidden');
                step1Card.classList.remove('hidden');
                modalPasswordTitle.textContent = 'Verifikasi Keamanan';
                modalPasswordSubtitle.textContent = 'Masukkan password saat ini untuk melanjutkan.';
            });
        }

        // ============= FITUR TOGGLE LIHAT/SEMBUNYIKAN PASSWORD =============
        document.querySelectorAll('[data-password-toggle]').forEach(toggleBtn => {
            toggleBtn.addEventListener('click', () => {
                const targetId = toggleBtn.getAttribute('data-password-toggle');
                const inputField = document.getElementById(targetId);
                const icon = toggleBtn.querySelector('i');

                if (inputField) {
                    if (inputField.type === 'password') {
                        inputField.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        inputField.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                }
            });
        });

        // ================= STEP 1: VALIDASI PASSWORD LAMA KE BACKEND =================
        if (btnNextStep) {
            btnNextStep.addEventListener('click', async () => {
                const currentPassword = document.getElementById('currentPassword').value.trim();

                if (!currentPassword) {
                    alert('Password saat ini wajib diisi.');
                    return;
                }

                btnNextStep.disabled = true;
                btnNextStep.textContent = 'Memeriksa...';

                try {
                    const verifyResponse = await fetch(
                        '{{ route("settings.password.verify") }}',
                        {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                current_password: currentPassword
                            })
                        }
                    );

                    const verifyData = await verifyResponse.json();

                    if (!verifyResponse.ok || !verifyData.success) {
                        throw new Error(verifyData.message || 'Password saat ini salah.');
                    }

                    step1Card.classList.add('hidden');
                    step2Card.classList.remove('hidden');
                    modalPasswordTitle.textContent = 'Ubah Password';
                    modalPasswordSubtitle.textContent = 'Silakan masukkan password baru Anda.';

                } catch (error) {
                    console.error('Verify error:', error);
                    alert(error.message);
                } finally {
                    btnNextStep.disabled = false;
                    btnNextStep.textContent = 'Lanjutkan';
                }
            });
        }

        // ================= STEP 2: SIMPAN PASSWORD BARU =================
        if (btnSimpanPassword) {
            btnSimpanPassword.addEventListener('click', async () => {
                const newPassword = document.getElementById('newPassword').value;
                const confirmPassword = document.getElementById('confirmPassword').value;

                if (!newPassword || !confirmPassword) {
                    alert('Semua kolom password baru wajib diisi.');
                    return;
                }

                if (newPassword.length < 8) {
                    alert('Password baru minimal 8 karakter.');
                    return;
                }

                if (newPassword !== confirmPassword) {
                    alert('Konfirmasi password tidak cocok.');
                    return;
                }

                btnSimpanPassword.disabled = true;
                btnSimpanPassword.textContent = 'Menyimpan...';

                try {
                    const updateResponse = await fetch(
                        '{{ route("settings.password.update") }}',
                        {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                new_password: newPassword,
                                new_password_confirmation: confirmPassword
                            })
                        }
                    );

                    const updateData = await updateResponse.json();

                    if (!updateResponse.ok || !updateData.success) {
                        throw new Error(updateData.message || 'Gagal memperbarui password.');
                    }

                    alert('Password berhasil diperbarui!');
                    tutupModalUbahPassword();

                } catch (error) {
                    console.error('Password update error:', error);
                    alert(error.message);
                } finally {
                    btnSimpanPassword.disabled = false;
                    btnSimpanPassword.textContent = 'Simpan Password';
                }
            });
        }

        // ============= SCRIPT TAB SWITCHING SETTINGS =============
        const tabButtons = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const targetTab = button.getAttribute('data-tab');

                tabButtons.forEach(btn => {
                    btn.classList.remove('bg-blue-600', 'text-white', 'shadow-md', 'shadow-blue-500/20');
                    btn.classList.add('text-blue-900/70', 'dark:text-slate-300', 'hover:bg-blue-100/50', 'dark:hover:bg-slate-800', 'hover:text-blue-950', 'dark:hover:text-white');
                });

                button.classList.add('bg-blue-600', 'text-white', 'shadow-md', 'shadow-blue-500/20');
                button.classList.remove('text-blue-900/70', 'dark:text-slate-300', 'hover:bg-blue-100/50', 'dark:hover:bg-slate-800', 'hover:text-blue-950', 'dark:hover:text-white');

                tabContents.forEach(content => {
                    content.classList.add('hidden');
                });

                const activeContent = document.getElementById('content-' + targetTab);
                if (activeContent) {
                    activeContent.classList.remove('hidden');
                }
            });
        });

        // ============= NOTIFIKASI SYSTEM =============
        const notifButton = document.getElementById('notificationButton');
        const notifDropdown = document.getElementById('notificationDropdown');
        const notifList = document.getElementById('notificationList');
        const notifBadge = document.getElementById('notificationBadge');
        const markAllBtn = document.getElementById('markAllReadBtn');

        if (!notifButton) return;

        notifButton.addEventListener('click', (e) => {
            e.stopPropagation();

            userDropdown.classList.toggle('hidden');

            const notifDropdown =
                document.getElementById('notificationDropdown');

            if (notifDropdown) {
                notifDropdown.classList.add('hidden');
            }

        });

    }


    /* =====================================================
       NOTIFICATION DROPDOWN
    ====================================================== */

    const notifButton =
        document.getElementById('notificationButton');

    const notifDropdown =
        document.getElementById('notificationDropdown');

    const notifList =
        document.getElementById('notificationList');

    const notifBadge =
        document.getElementById('notificationBadge');

    const markAllBtn =
        document.getElementById('markAllReadBtn');


    if (notifButton && notifDropdown) {

        notifButton.addEventListener('click', function (e) {

            e.stopPropagation();

            notifDropdown.classList.toggle('hidden');

            if (!notifDropdown.classList.contains('hidden')) {

                if (userDropdown) {
                    userDropdown.classList.add('hidden');
                }

                fetchNotifications();

            }

        });

    }


    /* =====================================================
       CLOSE DROPDOWNS
    ====================================================== */

    window.addEventListener('click', function () {

        if (userDropdown) {
            userDropdown.classList.add('hidden');
        }

        if (notifDropdown) {
            notifDropdown.classList.add('hidden');
        }

    });


    /* =====================================================
       SETTINGS MODAL
    ====================================================== */

    const btnPengaturan =
        document.getElementById('btnBukaPengaturan');

    const modalSettings =
        document.getElementById('modalSettings');

    const closeBtn =
        document.getElementById('closeModalSettings');

    const closeBtnMobile =
        document.getElementById('closeModalSettingsMobile');


    if (btnPengaturan && modalSettings) {

        btnPengaturan.addEventListener('click', function (e) {

            e.preventDefault();
            e.stopPropagation();

            modalSettings.classList.remove('hidden');

            if (userDropdown) {
                userDropdown.classList.add('hidden');
            }

        });

    }


    function tutupModal() {

        if (modalSettings) {
            modalSettings.classList.add('hidden');
        }

    }


    if (closeBtn) {
        closeBtn.addEventListener('click', tutupModal);
    }

    if (closeBtnMobile) {
        closeBtnMobile.addEventListener('click', tutupModal);
    }


    if (modalSettings) {

        modalSettings.addEventListener('click', function (e) {

            if (e.target === modalSettings) {
                tutupModal();
            }

        });

    }


    /* =====================================================
       SETTINGS TABS
    ====================================================== */

    const tabButtons =
        document.querySelectorAll('.tab-btn');

    const tabContents =
        document.querySelectorAll('.tab-content');


    tabButtons.forEach(function (button) {

        button.addEventListener('click', function () {

            const targetTab =
                button.getAttribute('data-tab');


            tabButtons.forEach(function (btn) {

                btn.classList.remove(
                    'bg-blue-600',
                    'text-white',
                    'shadow-md',
                    'shadow-blue-500/20'
                );

                btn.classList.add(
                    'text-blue-900/70',
                    'hover:bg-blue-100/50',
                    'hover:text-blue-950'
                );

            });


            button.classList.add(
                'bg-blue-600',
                'text-white',
                'shadow-md',
                'shadow-blue-500/20'
            );


            button.classList.remove(
                'text-blue-900/70',
                'hover:bg-blue-100/50',
                'hover:text-blue-950'
            );


            tabContents.forEach(function (content) {

                content.classList.add('hidden');

            });


            const activeContent =
                document.getElementById(
                    'content-' + targetTab
                );


            if (activeContent) {

                activeContent.classList.remove('hidden');

            }

        });

    });


    /* =====================================================
       NOTIFICATION SYSTEM
    ====================================================== */

    function updateBadge(count) {

        if (!notifBadge) return;

        count = parseInt(count) || 0;


        if (count > 0) {

            notifBadge.textContent =
                count > 99 ? '99+' : count;

            notifBadge.classList.remove('scale-0');
            notifBadge.classList.add('scale-100');

        } else {

            notifBadge.classList.remove('scale-100');
            notifBadge.classList.add('scale-0');

        }

    }


    function fetchNotifications() {

        fetch('{{ route('notifications.index') }}', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })

        .then(function (response) {

            if (!response.ok) {
                throw new Error('Notification request failed');
            }

            return response.json();

        })

        .then(function (data) {

            updateBadge(data.unread_count || 0);

            renderNotifications(
                data.notifications || []
            );

        })

        .catch(function (error) {

            console.error(
                'Notification error:',
                error
            );

        });

    }


    function renderNotifications(notifications) {

        if (!notifList) return;


        if (
            !notifications ||
            notifications.length === 0
        ) {

            notifList.innerHTML = `

                <div class="p-8 text-center text-sm text-blue-300 font-semibold">

                    <div
                        class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center mx-auto mb-3 text-blue-400">

                        <i class="fa-regular fa-bell-slash text-xl"></i>

                    </div>

                    Sistem bersih. Tidak ada notifikasi.

                </div>

            `;

            return;

        }


        let html = '';


        notifications.forEach(function (notif) {

            const isUnread =
                !notif.is_read;

            const timeAgo =
                getTimeAgo(notif.created_at);

            const redirectUrl =
                notif.data?.redirect || '';


            let iconClass =
                'fa-solid fa-satellite-dish';

            let iconBg =
                'bg-white border border-blue-100 text-blue-500 shadow-sm';


            if (notif.type) {

                if (
                    notif.type.startsWith('offer.accepted') ||
                    notif.type.startsWith('payment.verified')
                ) {

                    iconClass =
                        'fa-solid fa-check-double';

                    iconBg =
                        'bg-blue-600 text-white shadow-[0_0_10px_rgba(37,99,235,0.4)]';

                }

                else if (
                    notif.type.startsWith('offer.rejected') ||
                    notif.type.startsWith('payment.rejected')
                ) {

                    iconClass =
                        'fa-solid fa-ban';

                    iconBg =
                        'bg-white border-2 border-blue-200 text-blue-400';

                }

                else if (
                    notif.type.startsWith('workspace.message')
                ) {

                    iconClass =
                        'fa-solid fa-comment-dots';

                    iconBg =
                        'bg-blue-50 border border-blue-200 text-blue-600';

                }

                else if (
                    notif.type.startsWith('submission.')
                ) {

                    iconClass =
                        'fa-solid fa-cloud-arrow-up';

                    iconBg =
                        'bg-blue-100 text-blue-700';

                }

                else if (
                    notif.type === 'payment.waiting'
                ) {

                    iconClass =
                        'fa-solid fa-wallet';

                    iconBg =
                        'bg-white border border-blue-300 text-blue-500';

                }

                else if (
                    notif.type.startsWith('company_request') ||
                    notif.type === 'report.created'
                ) {

                    iconClass =
                        'fa-solid fa-shield-halved';

                    iconBg =
                        'bg-blue-50 text-blue-500';

                }

            }


            html += `

                <div
                    class="notification-item p-4 border-b border-blue-50/50 cursor-pointer hover:bg-blue-50/50 transition-colors ${isUnread ? 'bg-blue-50/30' : ''}"
                    data-id="${notif.id}"
                    data-url="${redirectUrl}">

                    <div class="flex items-start gap-4">

                        <div
                            class="w-10 h-10 rounded-xl ${iconBg} flex items-center justify-center shrink-0 text-sm">

                            <i class="${iconClass}"></i>

                        </div>


                        <div class="flex-1 min-w-0 pt-0.5">

                            <div class="flex items-center gap-2">

                                <h4
                                    class="text-[13px] font-black tracking-tight ${isUnread ? 'text-blue-950' : 'text-blue-900/60'}">

                                    ${notif.title ?? 'Notifikasi'}

                                </h4>

                                ${
                                    isUnread
                                    ?
                                    '<span class="w-1.5 h-1.5 rounded-full bg-blue-500 shadow-[0_0_5px_rgba(59,130,246,0.8)] shrink-0 ml-auto"></span>'
                                    :
                                    ''
                                }

                            </div>


                            <p
                                class="text-[11px] font-medium ${isUnread ? 'text-blue-700/80' : 'text-blue-400'} mt-1 leading-relaxed">

                                ${notif.message ?? ''}

                            </p>


                            <p
                                class="text-[9px] font-bold tracking-widest uppercase text-blue-300 mt-2">

                                ${timeAgo}

                            </p>

                        </div>

                    </div>

                </div>

            `;

        });


        notifList.innerHTML = html;


        document
            .querySelectorAll('.notification-item')
            .forEach(function (item) {

                item.addEventListener('click', function () {

                    const id =
                        this.dataset.id;

                    const url =
                        this.dataset.url;

                    markAsRead(id, url);

                });

            });

    }


    /* =====================================================
       MARK AS READ
    ====================================================== */

    function markAsRead(id, redirectUrl) {

        fetch(
            '{{ url('/notifications') }}/' +
            id +
            '/read',
            {
                method: 'POST',

                headers: {
                    'X-CSRF-TOKEN':
                        '{{ csrf_token() }}',

                    'Content-Type':
                        'application/json',

                    'Accept':
                        'application/json'
                }
            }
        )

        .then(function (response) {

            if (!response.ok) {
                throw new Error(
                    'Mark notification failed'
                );
            }

            return response.json();

        })

        .then(function (data) {

            if (data.redirect_url) {

                window.location.href =
                    data.redirect_url;

            }

            else if (redirectUrl) {

                window.location.href =
                    redirectUrl;

            }

            else {

                fetchNotifications();

            }

        })

        .catch(function (error) {

            console.error(
                'Mark read error:',
                error
            );

        });

    }


    /* =====================================================
       MARK ALL READ
    ====================================================== */

    if (markAllBtn) {

        markAllBtn.addEventListener(
            'click',
            function (e) {

                e.stopPropagation();


                fetch(
                    '{{ route('notifications.mark-all-read') }}',
                    {
                        method: 'POST',

                        headers: {

                            'X-CSRF-TOKEN':
                                '{{ csrf_token() }}',

                            'Content-Type':
                                'application/json',

                            'Accept':
                                'application/json'

                        }

                    }
                )

                .then(function (response) {

                    if (!response.ok) {
                        throw new Error(
                            'Mark all failed'
                        );
                    }

                    return response.json();

                })

                .then(function (data) {

                    if (data.success) {

                        updateBadge(0);

                        fetchNotifications();

                    }

                })

                .catch(function (error) {

                    console.error(
                        'Mark all read error:',
                        error
                    );

                });

            }
        );

    }


    /* =====================================================
       TIME AGO
    ====================================================== */

    function getTimeAgo(dateString) {

        if (!dateString) {
            return '';
        }


        const now =
            new Date();

        const date =
            new Date(dateString);

        const diffMs =
            now - date;

        const diffSec =
            Math.floor(diffMs / 1000);

        const diffMin =
            Math.floor(diffSec / 60);

        const diffHour =
            Math.floor(diffMin / 60);

        const diffDay =
            Math.floor(diffHour / 24);


        if (diffSec < 60) {
            return 'TRANSMISI BARU';
        }

        if (diffMin < 60) {
            return diffMin + ' MENIT LALU';
        }

        if (diffHour < 24) {
            return diffHour + ' JAM LALU';
        }

        if (diffDay < 7) {
            return diffDay + ' HARI LALU';
        }


        return date.toLocaleDateString(
            'id-ID'
        );

    }


    /* =====================================================
       INITIAL NOTIFICATION BADGE
    ====================================================== */

    fetchNotifications();


    /* =====================================================
       AUTO REFRESH NOTIFICATION
    ====================================================== */

    setInterval(
        function () {

            fetch(
                '{{ route('notifications.index') }}',
                {
                    headers: {
                        'Accept':
                            'application/json',

                        'X-Requested-With':
                            'XMLHttpRequest'
                    }
                }
            )

            .then(function (response) {

                if (!response.ok) {
                    throw new Error(
                        'Notification failed'
                    );
                }

                return response.json();

            })

            .then(function (data) {

                updateBadge(
                    data.unread_count || 0
                );

            })

            .catch(function () {});

        },
        60000
    );

});

</script>

@endauth