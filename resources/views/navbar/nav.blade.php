{{-- ApexForge Labs — Shared UI polish --}}
<header class="h-16 bg-white/90 backdrop-blur-3xl border-b border-blue-100 px-6 flex items-center justify-between sticky top-0 z-40 shadow-[0_10px_30px_-15px_rgba(59,130,246,0.1)]">

    <!-- ================= LEFT ================= -->
    <div class="flex items-center gap-8">

        <!-- Menu -->
        <nav class="hidden lg:flex items-center gap-6">
            @auth
                {{-- ================= FREELANCER ================= --}}
                @if (Auth::user()->role == 'freelancer')
                    <a href="/freelancer/dashboard" class="text-lg font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-700 to-blue-500 tracking-tight drop-shadow-[0_2px_10px_rgba(59,130,246,0.2)]">LABS</a>

                {{-- ================= COMPANY ================= --}}
                @elseif(Auth::user()->role == 'company')
                    <a href="/company/dashboard" class="text-lg font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-700 to-blue-500 tracking-tight drop-shadow-[0_2px_10px_rgba(59,130,246,0.2)]">LABS</a>

                {{-- ================= ADMIN ================= --}}
                @elseif(Auth::user()->role == 'admin')
                    <a href="{{ route('admin.dashboard') }}"
                        class="text-sm font-bold transition-all duration-300 relative group
                        {{ request()->routeIs('admin.dashboard') ? 'text-blue-700' : 'text-blue-900/50 hover:text-blue-600' }}">
                        Dashboard
                        @if(request()->routeIs('admin.dashboard')) <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-blue-500 rounded-full shadow-[0_0_8px_rgba(59,130,246,0.6)]"></span> @endif
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                        class="text-sm font-bold transition-all duration-300 relative group
                        {{ request()->routeIs('admin.users.*') ? 'text-blue-700' : 'text-blue-900/50 hover:text-blue-600' }}">
                        Pengguna
                        @if(request()->routeIs('admin.users.*')) <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-blue-500 rounded-full shadow-[0_0_8px_rgba(59,130,246,0.6)]"></span> @endif
                    </a>
                    <a href="{{ route('admin.projects.index') }}"
                        class="text-sm font-bold transition-all duration-300 relative group
                        {{ request()->routeIs('admin.projects.*') ? 'text-blue-700' : 'text-blue-900/50 hover:text-blue-600' }}">
                        Proyek
                        @if(request()->routeIs('admin.projects.*')) <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-blue-500 rounded-full shadow-[0_0_8px_rgba(59,130,246,0.6)]"></span> @endif
                    </a>
                    <a href="{{ route('admin.reports.index') }}"
                        class="text-sm font-bold transition-all duration-300 relative group
                        {{ request()->routeIs('admin.reports.*') ? 'text-blue-700' : 'text-blue-900/50 hover:text-blue-600' }}">
                        Laporan
                        @if(request()->routeIs('admin.reports.*')) <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-blue-500 rounded-full shadow-[0_0_8px_rgba(59,130,246,0.6)]"></span> @endif
                    </a>
                @endif
            @endauth
        </nav>
    </div>

    <!-- ================= RIGHT ================= -->
    <div class="flex items-center gap-5">
        <!-- NOTIF -->
        <div class="relative">
            <button id="notificationButton" aria-label="Notifikasi"
                class="relative w-10 h-10 rounded-2xl border border-blue-100 hover:bg-blue-50 hover:border-blue-200 hover:shadow-[0_0_15px_rgba(59,130,246,0.15)] flex items-center justify-center transition-all duration-300 group">
                <i class="fa-regular fa-bell text-blue-600 group-hover:animate-swing"></i>
                <span id="notificationBadge"
                    class="absolute -top-1.5 -right-1.5 min-w-[20px] h-[20px] rounded-full bg-gradient-to-r from-blue-600 to-blue-400 shadow-[0_0_10px_rgba(59,130,246,0.5)] text-white text-[10px] font-black flex items-center justify-center px-1 border-2 border-white transition-transform transform scale-0"></span>
            </button>

            <!-- Dropdown Notifikasi -->
            <div id="notificationDropdown"
                class="hidden absolute right-0 mt-4 w-[380px] bg-white/95 backdrop-blur-2xl rounded-[1.5rem] border border-blue-100 shadow-[0_20px_50px_-10px_rgba(30,58,138,0.15)] overflow-hidden z-[100] transform transition-all">
                <div class="p-5 border-b border-blue-50/50 flex items-center justify-between bg-gradient-to-b from-blue-50/30 to-transparent">
                    <h3 class="font-black text-sm text-blue-950 tracking-wide">Notifikasi Sistem</h3>
                    <button id="markAllReadBtn" class="text-[11px] text-blue-500 font-bold hover:text-blue-700 transition-colors">Tandai semua dibaca</button>
                </div>
                <div id="notificationList" class="max-h-[360px] overflow-y-auto custom-sidebar-scroll">
                    <div class="p-8 text-center text-sm text-blue-300 font-semibold">
                        <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center mx-auto mb-3 text-blue-400">
                            <i class="fa-regular fa-bell-slash text-xl"></i>
                        </div>
                        Tidak ada notifikasi aktif
                    </div>
                </div>
            </div>
        </div>

        <!-- USER -->
        <div class="relative">
            <button id="userButton" class="flex items-center gap-3 border border-transparent hover:border-blue-100 hover:bg-blue-50/50 rounded-2xl px-2 py-1.5 transition-all duration-300 group">
                <div class="text-right hidden sm:block pr-1">
                    <h2 class="text-[13px] font-black text-blue-950 leading-tight">{{ Auth::user()->name }}</h2>
                    <p class="text-[10px] font-bold tracking-widest uppercase text-blue-400">{{ ucfirst(Auth::user()->role) }}</p>
                </div>
                <div class="w-10 h-10 rounded-[0.9rem] overflow-hidden bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white shrink-0 shadow-[0_4px_15px_rgba(59,130,246,0.3)] group-hover:scale-105 transition-transform">
                    @if (Auth::user()->role == 'company' && Auth::user()->companyProfile && Auth::user()->companyProfile->company_logo)
                        <img src="{{ asset('storage/' . Auth::user()->companyProfile->company_logo) }}" alt="Logo" class="w-full h-full object-cover">
                    @elseif(Auth::user()->freelanceProfile && Auth::user()->freelanceProfile->photo)
                        <img src="{{ asset('storage/' . Auth::user()->freelanceProfile->photo) }}" alt="Profil" class="w-full h-full object-cover">
                    @else
                        <i class="fa-solid fa-user text-sm"></i>
                    @endif
                </div>
                <i class="fa-solid fa-chevron-down text-[10px] text-blue-300 group-hover:text-blue-500 transition-colors"></i>
            </button>

            <!-- Dropdown User -->
            <div id="userDropdown"
                class="hidden absolute right-0 mt-4 w-72 bg-white/95 backdrop-blur-2xl rounded-[1.5rem] border border-blue-100 shadow-[0_20px_50px_-10px_rgba(30,58,138,0.15)] overflow-hidden z-[100]">
                
                <div class="p-6 border-b border-blue-50/50 bg-gradient-to-b from-blue-50/50 to-transparent">
                    <h2 class="font-black text-blue-950 tracking-tight">{{ Auth::user()->name }}</h2>
                    <p class="text-xs font-semibold text-blue-500/70 truncate">{{ Auth::user()->email }}</p>
                </div>

                <div class="p-2 space-y-1">
                    @if (Auth::user()->role == 'freelancer')
                        <a href="{{ route('freelancer.profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-blue-900/70 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                            <i class="fa-regular fa-user text-blue-500 w-5 text-center"></i> Profil Utama
                        </a>
                        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-blue-900/70 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                            <i class="fa-regular fa-file-lines text-blue-500 w-5 text-center"></i> Lamaran Saya
                        </a>
                    @elseif(Auth::user()->role == 'company')
                        <a href="{{ route('company.profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-blue-900/70 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                            <i class="fa-regular fa-building text-blue-500 w-5 text-center"></i> Profil Perusahaan
                        </a>
                        <a href="{{ route('company.projects.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-blue-900/70 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                            <i class="fa-solid fa-plus text-blue-500 w-5 text-center"></i> Tambah Proyek
                        </a>
                    @elseif(Auth::user()->role == 'admin')
                        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-blue-900/70 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                            <i class="fa-solid fa-shield-halved text-blue-500 w-5 text-center"></i> Profil Admin
                        </a>
                        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-blue-900/70 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                            <i class="fa-solid fa-chart-pie text-blue-500 w-5 text-center"></i> Analitik Sistem
                        </a>
                    @endif

                    {{-- TOMBOL PENGATURAN --}}
                    <a href="#" id="btnBukaPengaturan" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-blue-900/70 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                        <i class="fa-solid fa-gear text-blue-500 w-5 text-center"></i> Pengaturan
                    </a>
                </div>

                <div class="p-2 border-t border-blue-50">
                    <form action="{{ url('/logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-3 flex items-center gap-3 rounded-xl text-sm font-bold text-blue-600 hover:bg-blue-600 hover:text-white transition-colors group">
                            <i class="fa-solid fa-power-off w-5 text-center transition-transform group-hover:scale-110"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- ================= MODAL SETTINGS (GAYA DASHBOARD UI) ================= -->
<div id="modalSettings" class="hidden fixed inset-0 z-[150] flex items-center justify-center bg-blue-950/30 backdrop-blur-sm transition-opacity p-4">
    <div class="bg-white w-full max-w-4xl rounded-[2rem] shadow-[0_20px_50px_-10px_rgba(30,58,138,0.2)] overflow-hidden transform transition-all border border-blue-100 flex flex-col md:flex-row max-h-[85vh]">
        
        <!-- SIDEBAR MENU SETTINGS (KIRI) -->
        <div class="w-full md:w-72 bg-blue-50/40 p-6 border-b md:border-b-0 md:border-r border-blue-100 flex flex-col justify-between shrink-0">
            <div>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-black text-blue-950 tracking-tight">Settings</h2>
                    <!-- Tombol Close khusus Mobile -->
                    <button id="closeModalSettingsMobile" class="md:hidden text-blue-400 hover:text-blue-600">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>
                <p class="text-xs font-medium text-blue-400 mb-6">Kelola pengaturan akun dan preferensi Anda</p>

                <!-- Menu Tab List -->
                <nav class="space-y-1.5">
                    <button type="button" data-tab="security" class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition-all bg-blue-600 text-white shadow-md shadow-blue-500/20">
                        <i class="fa-solid fa-shield-halved w-5 text-center text-sm"></i> Security
                    </button>
                    <button type="button" data-tab="payment" class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition-all text-blue-900/70 hover:bg-blue-100/50 hover:text-blue-950">
                        <i class="fa-solid fa-wallet w-5 text-center text-sm text-blue-500"></i> Payment & Payout
                    </button>
                    <button type="button" data-tab="notifications" class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition-all text-blue-900/70 hover:bg-blue-100/50 hover:text-blue-950">
                        <i class="fa-solid fa-bell w-5 text-center text-sm text-blue-500"></i> Notifications
                    </button>
                    <button type="button" data-tab="privacy" class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition-all text-blue-900/70 hover:bg-blue-100/50 hover:text-blue-950">
                        <i class="fa-solid fa-lock w-5 text-center text-sm text-blue-500"></i> Privacy
                    </button>
                    <button type="button" data-tab="appearance" class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition-all text-blue-900/70 hover:bg-blue-100/50 hover:text-blue-950">
                        <i class="fa-solid fa-palette w-5 text-center text-sm text-blue-500"></i> Appearance
                    </button>
                    <button type="button" data-tab="account" class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition-all text-blue-900/70 hover:bg-blue-100/50 hover:text-blue-950">
                        <i class="fa-solid fa-user-gear w-5 text-center text-sm text-blue-500"></i> Account
                    </button>
                </nav>
            </div>
            
            <div class="pt-4 hidden md:block">
                <button id="closeModalSettings" class="w-full py-2.5 rounded-xl text-xs font-bold text-blue-600 bg-blue-100/60 hover:bg-blue-200/60 transition-colors">Tutup Jendela</button>
            </div>
        </div>

        <!-- CONTENT AREA SETTINGS (KANAN) -->
        <div class="flex-1 p-6 md:p-8 overflow-y-auto max-h-[75vh]">
            
            <!-- TAB CONTENT: SECURITY -->
            <div id="content-security" class="tab-content space-y-6">
                <div class="flex items-center justify-between border-b border-blue-50 pb-4">
                    <div>
                        <h3 class="text-sm font-black text-blue-950 tracking-tight">Security</h3>
                        <p class="text-xs text-blue-400 mt-0.5">Kelola keamanan akun Anda untuk menjaga akun tetap aman.</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <!-- Item 1: Ubah Password -->
                    <div class="p-4 rounded-2xl border border-blue-100/80 bg-white hover:border-blue-200 transition-all flex items-center justify-between gap-4">
                        <div class="flex items-start gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-key text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-black text-blue-950">Ubah Password</h4>
                                <p class="text-[11px] text-blue-400 mt-0.5">Gunakan password yang kuat untuk melindungi akun Anda.</p>
                            </div>
                        </div>
                        <button class="px-3.5 py-2 rounded-xl text-xs font-bold text-blue-600 border border-blue-200 hover:bg-blue-50 transition-colors shrink-0">Ubah Password</button>
                    </div>

                    <!-- Item 2: Verifikasi Email -->
                    <div class="p-4 rounded-2xl border border-blue-100/80 bg-white hover:border-blue-200 transition-all flex items-center justify-between gap-4">
                        <div class="flex items-start gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-envelope-circle-check text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-black text-blue-950">Verifikasi Email</h4>
                                <p class="text-[11px] text-blue-400 mt-0.5">Email Anda telah diverifikasi.</p>
                            </div>
                        </div>
                        <span class="px-3 py-1.5 rounded-lg text-[10px] font-bold bg-emerald-100/70 text-emerald-700 shrink-0 flex items-center gap-1.5">
                            <i class="fa-solid fa-check text-[9px]"></i> Terverifikasi
                        </span>
                    </div>

                    <!-- Item 3: Verifikasi 2FA -->
                    <div class="p-4 rounded-2xl border border-blue-100/80 bg-white hover:border-blue-200 transition-all flex items-center justify-between gap-4">
                        <div class="flex items-start gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-shield-cat text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-black text-blue-950">Verifikasi 2FA</h4>
                                <p class="text-[11px] text-blue-400 mt-0.5">Tambahkan lapisan keamanan ekstra dengan autentikasi dua faktor.</p>
                            </div>
                        </div>
                        <button class="px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition-all shrink-0">Aktifkan 2FA</button>
                    </div>

                    <!-- Item 4: Sesi Aktif -->
                    <div class="p-4 rounded-2xl border border-blue-100/80 bg-white hover:border-blue-200 transition-all flex items-center justify-between gap-4">
                        <div class="flex items-start gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-laptop text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-black text-blue-950">Sesi Aktif</h4>
                                <p class="text-[11px] text-blue-400 mt-0.5">Kelola perangkat yang sedang login ke akun Anda.</p>
                            </div>
                        </div>
                        <button class="px-3.5 py-2 rounded-xl text-xs font-bold text-blue-600 border border-blue-200 hover:bg-blue-50 transition-colors shrink-0">Kelola Sesi</button>
                    </div>

                    <!-- Item 5: Logout Semua -->
                    <div class="p-4 rounded-2xl border border-red-100/80 bg-red-50/10 hover:border-red-200 transition-all flex items-center justify-between gap-4">
                        <div class="flex items-start gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-red-50 border border-red-100 text-red-500 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-right-from-bracket text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-black text-red-950">Logout dari Semua Perangkat</h4>
                                <p class="text-[11px] text-red-400 mt-0.5">Keluar dari semua perangkat kecuali perangkat yang Anda gunakan sekarang.</p>
                            </div>
                        </div>
                        <button class="px-3.5 py-2 rounded-xl text-xs font-bold text-red-600 border border-red-200 hover:bg-red-50 transition-colors shrink-0">Logout Semua</button>
                    </div>
                </div>
            </div>

            <!-- TAB CONTENT: PAYMENT & PAYOUT -->
            <div id="content-payment" class="tab-content hidden space-y-6">
                <div class="border-b border-blue-50 pb-4">
                    <h3 class="text-sm font-black text-blue-950 tracking-tight">Payment & Payout</h3>
                    <p class="text-xs text-blue-400 mt-0.5">Atur metode pembayaran, rekening bank, dan pencairan dana.</p>
                </div>
                <div class="p-8 text-center text-xs text-blue-400 font-semibold bg-blue-50/30 rounded-2xl border border-dashed border-blue-200">
                    <i class="fa-solid fa-wallet text-2xl text-blue-300 mb-2"></i>
                    <p>Belum ada metode pembayaran yang ditambahkan.</p>
                    <button class="mt-3 px-4 py-2 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition-all">Tambah Rekening</button>
                </div>
            </div>

            <!-- TAB CONTENT: NOTIFICATIONS -->
            <div id="content-notifications" class="tab-content hidden space-y-6">
                <div class="border-b border-blue-50 pb-4">
                    <h3 class="text-sm font-black text-blue-950 tracking-tight">Notifications</h3>
                    <p class="text-xs text-blue-400 mt-0.5">Atur preferensi pemberitahuan sistem dan email.</p>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-4 rounded-2xl border border-blue-100 bg-white">
                        <div>
                            <p class="text-xs font-bold text-blue-950">Notifikasi Push</p>
                            <p class="text-[11px] text-blue-400">Terima notifikasi langsung di browser</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-10 h-5 bg-blue-100 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-blue-200 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- TAB CONTENT: PRIVACY -->
            <div id="content-privacy" class="tab-content hidden space-y-6">
                <div class="border-b border-blue-50 pb-4">
                    <h3 class="text-sm font-black text-blue-950 tracking-tight">Privacy</h3>
                    <p class="text-xs text-blue-400 mt-0.5">Kontrol privasi data dan visibilitas profil Anda.</p>
                </div>
                <div class="p-6 rounded-2xl border border-blue-100 bg-white text-xs text-blue-900/70 font-medium">
                    Visibilitas profil publik saat ini diatur aktif.
                </div>
            </div>

            <!-- TAB CONTENT: APPEARANCE -->
            <div id="content-appearance" class="tab-content hidden space-y-6">
                <div class="border-b border-blue-50 pb-4">
                    <h3 class="text-sm font-black text-blue-950 tracking-tight">Appearance</h3>
                    <p class="text-xs text-blue-400 mt-0.5">Sesuaikan tema, bahasa, dan tampilan aplikasi.</p>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 rounded-2xl border border-blue-100 bg-white">
                        <div>
                            <p class="text-xs font-bold text-blue-950">Mode Gelap (Dark Mode)</p>
                            <p class="text-[11px] text-blue-400">Ganti tema aplikasi menjadi gelap</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer">
                            <div class="w-10 h-5 bg-blue-100 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-blue-200 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- TAB CONTENT: ACCOUNT -->
            <div id="content-account" class="tab-content hidden space-y-6">
                <div class="border-b border-blue-50 pb-4">
                    <h3 class="text-sm font-black text-blue-950 tracking-tight">Account</h3>
                    <p class="text-xs text-blue-400 mt-0.5">Kelola informasi akun dasar Anda.</p>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-blue-900/70 mb-1">Nama Lengkap</label>
                        <input type="text" value="{{ Auth::user()->name }}" class="w-full px-4 py-2.5 rounded-xl border border-blue-100 bg-blue-50/30 text-blue-950 text-xs font-bold focus:outline-none focus:border-blue-300 focus:bg-white transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-blue-900/70 mb-1">Email</label>
                        <input type="email" value="{{ Auth::user()->email }}" class="w-full px-4 py-2.5 rounded-xl border border-blue-100 bg-blue-50/30 text-blue-950 text-xs font-bold focus:outline-none focus:border-blue-300 focus:bg-white transition-colors">
                    </div>
                    <button class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition-all">Simpan Perubahan</button>
                </div>
            </div>

        </div>

    </div>
</div>

<style>
    @keyframes swing {
        0% { transform: rotate(0deg); }
        25% { transform: rotate(15deg); }
        50% { transform: rotate(-10deg); }
        75% { transform: rotate(5deg); }
        100% { transform: rotate(0deg); }
    }
    .animate-swing {
        animation: swing 0.5s ease-in-out;
    }
</style>

{{-- Script untuk mengontrol Dropdown, Notifikasi, Tab Settings & Modal --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // ============= KONTROL DROPDOWN USER =============
        const userButton = document.getElementById('userButton');
        const userDropdown = document.getElementById('userDropdown');

        if (userButton && userDropdown) {
            userButton.addEventListener('click', (e) => {
                e.stopPropagation();
                userDropdown.classList.toggle('hidden');
            });
        }

        window.addEventListener('click', () => {
            if (userDropdown && !userDropdown.classList.contains('hidden')) {
                userDropdown.classList.add('hidden');
            }
        });

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

        // Menutup modal jika klik di luar box modal
        if (modalSettings) {
            modalSettings.addEventListener('click', (e) => {
                if (e.target === modalSettings) {
                    tutupModal();
                }
            });
        }

        // ============= SCRIPT TAB SWITCHING SETTINGS =============
        const tabButtons = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const targetTab = button.getAttribute('data-tab');

                // Reset semua tombol tab (style tidak aktif)
                tabButtons.forEach(btn => {
                    btn.classList.remove('bg-blue-600', 'text-white', 'shadow-md', 'shadow-blue-500/20');
                    btn.classList.add('text-blue-900/70', 'hover:bg-blue-100/50', 'hover:text-blue-950');
                });

                // Aktifkan tombol yang diklik
                button.classList.add('bg-blue-600', 'text-white', 'shadow-md', 'shadow-blue-500/20');
                button.classList.remove('text-blue-900/70', 'hover:bg-blue-100/50', 'hover:text-blue-950');

                // Sembunyikan semua konten tab
                tabContents.forEach(content => {
                    content.classList.add('hidden');
                });

                // Tampilkan konten yang sesuai
                const activeContent = document.getElementById('content-' + targetTab);
                if (activeContent) {
                    activeContent.classList.remove('hidden');
                }
            });
        });


        // ============= PURE BLUE NOTIFIKASI SYSTEM =============
        const notifButton = document.getElementById('notificationButton');
        const notifDropdown = document.getElementById('notificationDropdown');
        const notifList = document.getElementById('notificationList');
        const notifBadge = document.getElementById('notificationBadge');
        const markAllBtn = document.getElementById('markAllReadBtn');

        if (!notifButton) return;

        notifButton.addEventListener('click', (e) => {
            e.stopPropagation();
            notifDropdown.classList.toggle('hidden');
            if (!notifDropdown.classList.contains('hidden')) {
                fetchNotifications();
            }
        });

        window.addEventListener('click', () => {
            if (notifDropdown && !notifDropdown.classList.contains('hidden')) {
                notifDropdown.classList.add('hidden');
            }
        });

        function fetchNotifications() {
            fetch('{{ route('notifications.index') }}')
                .then(res => res.json())
                .then(data => {
                    updateBadge(data.unread_count);
                    renderNotifications(data.notifications);
                })
                .catch(err => console.error('Notif fetch error:', err));
        }

        function updateBadge(count) {
            if (count > 0) {
                notifBadge.textContent = count;
                notifBadge.classList.remove('scale-0');
                notifBadge.classList.add('scale-100');
            } else {
                notifBadge.classList.remove('scale-100');
                notifBadge.classList.add('scale-0');
            }
        }

        function renderNotifications(notifications) {
            if (!notifications || notifications.length === 0) {
                notifList.innerHTML = `
                    <div class="p-8 text-center text-sm text-blue-300 font-semibold">
                        <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center mx-auto mb-3 text-blue-400">
                            <i class="fa-regular fa-bell-slash text-xl"></i>
                        </div>
                        Sistem bersih. Tidak ada notifikasi.
                    </div>
                `;
                return;
            }

            let html = '';
            notifications.forEach(notif => {
                const isUnread = !notif.is_read;
                const timeAgo = getTimeAgo(notif.created_at);
                const redirectUrl = notif.data?.redirect || '';
                
                let iconClass = 'fa-solid fa-satellite-dish';
                let iconBg = 'bg-white border border-blue-100 text-blue-500 shadow-sm';
                
                if (notif.type) {
                    if (notif.type.startsWith('offer.accepted') || notif.type.startsWith('payment.verified')) {
                        iconClass = 'fa-solid fa-check-double';
                        iconBg = 'bg-blue-600 text-white shadow-[0_0_10px_rgba(37,99,235,0.4)]';
                    } else if (notif.type.startsWith('offer.rejected') || notif.type.startsWith('payment.rejected')) {
                        iconClass = 'fa-solid fa-ban';
                        iconBg = 'bg-white border-2 border-blue-200 text-blue-400';
                    } else if (notif.type.startsWith('workspace.message')) {
                        iconClass = 'fa-solid fa-comment-dots';
                        iconBg = 'bg-blue-50 border border-blue-200 text-blue-600';
                    } else if (notif.type.startsWith('submission.')) {
                        iconClass = 'fa-solid fa-cloud-arrow-up';
                        iconBg = 'bg-blue-100 text-blue-700';
                    } else if (notif.type === 'payment.waiting') {
                        iconClass = 'fa-solid fa-wallet';
                        iconBg = 'bg-white border border-blue-300 text-blue-500';
                    } else if (notif.type.startsWith('company_request') || notif.type === 'report.created') {
                        iconClass = 'fa-solid fa-shield-halved';
                        iconBg = 'bg-blue-50 text-blue-500';
                    }
                }
                
                html += `
                    <div class="notification-item p-4 border-b border-blue-50/50 cursor-pointer hover:bg-blue-50/50 transition-colors ${isUnread ? 'bg-blue-50/30' : ''}" data-id="${notif.id}" data-url="${redirectUrl}">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl ${iconBg} flex items-center justify-center shrink-0 text-sm">
                                <i class="${iconClass}"></i>
                            </div>
                            <div class="flex-1 min-w-0 pt-0.5">
                                <div class="flex items-center gap-2">
                                    <h4 class="text-[13px] font-black tracking-tight ${isUnread ? 'text-blue-950' : 'text-blue-900/60'}">${notif.title}</h4>
                                    ${isUnread ? '<span class="w-1.5 h-1.5 rounded-full bg-blue-500 shadow-[0_0_5px_rgba(59,130,246,0.8)] shrink-0 ml-auto"></span>' : ''}
                                </div>
                                <p class="text-[11px] font-medium ${isUnread ? 'text-blue-700/80' : 'text-blue-400'} mt-1 leading-relaxed line-clamp-2">${notif.message}</p>
                                <p class="text-[9px] font-bold tracking-widest uppercase text-blue-300 mt-2">${timeAgo}</p>
                            </div>
                        </div>
                    </div>
                `;
            });

            notifList.innerHTML = html;

            document.querySelectorAll('.notification-item').forEach(item => {
                item.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const url = this.dataset.url;
                    markAsRead(id, url);
                });
            });
        }

        function markAsRead(id, redirectUrl) {
            fetch('{{ url('/notifications') }}/' + id + '/read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.redirect_url) window.location.href = data.redirect_url;
                    else if (redirectUrl) window.location.href = redirectUrl;
                })
                .catch(err => console.error('Mark read error:', err));
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
                    .catch(err => console.error('Mark all read error:', err));
            });
        }

        function getTimeAgo(dateString) {
            const now = new Date();
            const date = new Date(dateString);
            const diffMs = now - date;
            const diffSec = Math.floor(diffMs / 1000);
            const diffMin = Math.floor(diffSec / 60);
            const diffHour = Math.floor(diffMin / 60);
            const diffDay = Math.floor(diffHour / 24);

            if (diffSec < 60) return 'TRANSMISI BARU';
            if (diffMin < 60) return diffMin + ' MENIT LALU';
            if (diffHour < 24) return diffHour + ' JAM LALU';
            if (diffDay < 7) return diffDay + ' HARI LALU';
            return date.toLocaleDateString('id-ID');
        }

        fetch('{{ route('notifications.index') }}')
            .then(res => res.json())
            .then(data => updateBadge(data.unread_count))
            .catch(err => console.error('Notif init error:', err));

        setInterval(() => {
            fetch('{{ route('notifications.index') }}')
                .then(res => res.json())
                .then(data => updateBadge(data.unread_count))
                .catch(() => {});
        }, 60000);
    });
</script>