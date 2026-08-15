<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ApexForge Labs — Marketplace Freelance Indonesia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        /* ApexForge Labs — Unified UI System */
        :root {
            --af-primary: #2563eb;
            --af-primary-dark: #1d4ed8;
            --af-primary-soft: #eff6ff;
            --af-sky: #38bdf8;
            --af-ink: #0f172a;
            --af-muted: #64748b;
            --af-border: #dbeafe;
            --af-surface: #ffffff;
            --af-page: #f6f9ff;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background:
                radial-gradient(circle at 10% -10%, rgba(56, 189, 248, .10), transparent 30%),
                radial-gradient(circle at 100% 0%, rgba(37, 99, 235, .08), transparent 28%),
                var(--af-page);
        }

        .scroll-offset {
            scroll-margin-top: 80px;
        }

        ::selection {
            background: rgba(37, 99, 235, .18);
            color: #0f172a;
        }

        ::-webkit-scrollbar {
            width: 7px;
            height: 7px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(241, 245, 249, .7);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(37, 99, 235, .22);
            border-radius: 999px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(37, 99, 235, .38);
        }

        input,
        select,
        textarea {
            border-color: var(--af-border) !important;
            background: rgba(255, 255, 255, .92);
            transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: rgba(37, 99, 235, .55) !important;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, .09) !important;
            outline: none !important;
        }

        button,
        a,
        [role="button"] {
            transition: all .2s ease;
        }

        button:focus-visible,
        a:focus-visible,
        [role="button"]:focus-visible {
            outline: 2px solid rgba(37, 99, 235, .55);
            outline-offset: 2px;
        }

        table {
            border-collapse: separate;
            border-spacing: 0;
        }

        thead th {
            background: rgba(239, 246, 255, .72) !important;
            color: #334155;
            font-weight: 700;
        }

        tbody tr {
            transition: background .18s ease;
        }

        tbody tr:hover {
            background: rgba(239, 246, 255, .48);
        }

        [class*="bg-blue-600"] {
            box-shadow: 0 8px 22px -12px rgba(37, 99, 235, .72);
        }

        [class*="bg-blue-600"]:hover {
            box-shadow: 0 12px 28px -12px rgba(37, 99, 235, .78);
            transform: translateY(-1px);
        }

        .glass-panel,
        .glass-card,
        .glass-surface {
            background: rgba(255, 255, 255, .72);
            border: 1px solid rgba(219, 234, 254, .85);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            box-shadow: 0 18px 50px -32px rgba(30, 64, 175, .32);
        }

        .apex-page-glow {
            position: fixed;
            inset: auto -10rem -12rem auto;
            width: 28rem;
            height: 28rem;
            background: rgba(56, 189, 248, .09);
            filter: blur(70px);
            border-radius: 999px;
            pointer-events: none;
            z-index: -1;
        }

        @media (max-width:767px) {
            main {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }

            table {
                min-width: 680px;
            }

            .overflow-x-auto {
                -webkit-overflow-scrolling: touch;
            }
        }

        @media (prefers-reduced-motion:reduce) {
            *,
            *::before,
            *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
                scroll-behavior: auto !important;
            }
        }
    </style>
</head>

<body class="bg-[#f6f9ff] text-slate-800 antialiased selection:bg-blue-600 selection:text-white">

    <!-- ============================================================ -->
    <!-- 1. NAVBAR -->
    <!-- ============================================================ -->
    <header class="bg-white/80 backdrop-blur-md border-b border-blue-100/80 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 sm:h-20 flex items-center justify-between">
            <!-- Brand -->
            <a href="{{ route('landing') }}" class="flex items-center gap-2.5 sm:gap-3 group">
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl overflow-hidden ring-2 ring-blue-100 group-hover:ring-blue-400 transition-all shadow-xs">
                    <img src="{{ asset('images/nexus.jpg') }}" alt="ApexForge Labs" class="w-full h-full object-cover">
                </div>
                <span class="font-extrabold text-base sm:text-xl tracking-tight text-slate-900 group-hover:text-blue-600 transition">
                    ApexForge Labs
                </span>
            </a>

            <!-- Mobile Menu Toggle -->
            <button id="mobile-menu-btn" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-blue-50 transition focus:outline-hidden" aria-label="Toggle menu">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>

            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex items-center gap-1">
                <a href="#home" class="px-3.5 py-2 text-sm font-semibold text-slate-600 hover:text-blue-600 hover:bg-blue-50/80 rounded-xl transition">Beranda</a>
                <a href="#categories" class="px-3.5 py-2 text-sm font-semibold text-slate-600 hover:text-blue-600 hover:bg-blue-50/80 rounded-xl transition">Kategori</a>
                <a href="#projects" class="px-3.5 py-2 text-sm font-semibold text-slate-600 hover:text-blue-600 hover:bg-blue-50/80 rounded-xl transition">Proyek Terbaru</a>
                <a href="#freelancer" class="px-3.5 py-2 text-sm font-semibold text-slate-600 hover:text-blue-600 hover:bg-blue-50/80 rounded-xl transition">Freelancer</a>
                <a href="#company" class="px-3.5 py-2 text-sm font-semibold text-slate-600 hover:text-blue-600 hover:bg-blue-50/80 rounded-xl transition">Perusahaan</a>
            </nav>

            <!-- Desktop Auth -->
            <div class="hidden lg:flex items-center gap-3">
                @auth
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-blue-50 rounded-xl border border-blue-100/60">
                        <span class="text-xs text-slate-500 font-medium">Halo,</span>
                        <span class="text-xs font-bold text-slate-900">{{ Auth::user()->name }}</span>
                    </div>
                    @if (Auth::user()->role === 'freelancer')
                        <a href="{{ route('freelancer.dashboard') }}" class="px-4 py-2 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition shadow-sm shadow-blue-500/20">
                            <i class="fa-solid fa-gauge-high mr-1.5"></i>Dashboard
                        </a>
                    @elseif(Auth::user()->role === 'company')
                        <a href="{{ route('company.dashboard') }}" class="px-4 py-2 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition shadow-sm shadow-blue-500/20">
                            <i class="fa-solid fa-building mr-1.5"></i>Dashboard
                        </a>
                    @elseif(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition shadow-sm shadow-blue-500/20">
                            <i class="fa-solid fa-shield-halved mr-1.5"></i>Admin
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 text-sm font-semibold text-slate-700 border border-blue-100/80 hover:bg-blue-50/80 rounded-xl transition">
                            <i class="fa-solid fa-right-from-bracket mr-1.5"></i>Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-5 py-2 text-sm font-semibold text-slate-700 hover:text-slate-900 border border-blue-100/80 rounded-xl hover:bg-blue-50/60 transition">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="px-5 py-2 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition shadow-md shadow-blue-500/20">
                        Daftar
                    </a>
                @endauth
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden lg:hidden bg-white border-b border-blue-100 px-4 py-4 space-y-3">
            <a href="#home" class="block px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-[#f6f9ff] rounded-lg">Beranda</a>
            <a href="#categories" class="block px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-[#f6f9ff] rounded-lg">Kategori</a>
            <a href="#projects" class="block px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-[#f6f9ff] rounded-lg">Proyek Terbaru</a>
            <a href="#freelancer" class="block px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-[#f6f9ff] rounded-lg">Freelancer</a>
            <a href="#company" class="block px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-[#f6f9ff] rounded-lg">Perusahaan</a>

            <div class="pt-3 border-t border-blue-50">
                @auth
                    <div class="flex items-center gap-2 pb-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 font-bold flex items-center justify-center text-xs">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <span class="font-bold text-sm text-slate-900">{{ Auth::user()->name }}</span>
                    </div>
                    @if (Auth::user()->role === 'freelancer')
                        <a href="{{ route('freelancer.dashboard') }}" class="block px-4 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-xl text-center mb-2">
                            <i class="fa-solid fa-gauge-high mr-1.5"></i>Dashboard Freelancer
                        </a>
                    @elseif(Auth::user()->role === 'company')
                        <a href="{{ route('company.dashboard') }}" class="block px-4 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-xl text-center mb-2">
                            <i class="fa-solid fa-building mr-1.5"></i>Dashboard Perusahaan
                        </a>
                    @elseif(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-xl text-center mb-2">
                            <i class="fa-solid fa-shield-halved mr-1.5"></i>Dashboard Admin
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-center px-4 py-2 text-sm font-semibold text-rose-600 hover:bg-rose-50 rounded-xl">
                            <i class="fa-solid fa-right-from-bracket mr-2"></i>Logout
                        </button>
                    </form>
                @else
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('login') }}" class="block px-4 py-2.5 text-sm font-semibold text-slate-700 bg-blue-50 hover:bg-slate-200 text-center rounded-xl">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="block px-4 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 text-center rounded-xl">
                            Daftar
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- ============================================================ -->
    <!-- 2. HERO SECTION -->
    <!-- ============================================================ -->
    <section id="home" class="relative overflow-hidden bg-gradient-to-b from-blue-50/70 via-slate-50 to-slate-50 border-b border-blue-100/50">
        <div class="absolute inset-0 z-0 pointer-events-none">
            <img src="{{ asset('images/gedung.jpg') }}" alt="Gedung" class="w-full h-full object-cover opacity-10">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-50 via-slate-50/80 to-transparent"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-20 lg:py-28">
            <div class="grid lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                <!-- Left Text -->
                <div class="lg:col-span-7 space-y-6 sm:space-y-8">
                    <div>
                        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-blue-100/80 border border-blue-200 text-blue-700 text-xs font-bold rounded-full tracking-wide">
                            <i class="fa-solid fa-sparkles text-blue-500"></i> Marketplace Freelance #1 di Indonesia
                        </span>
                    </div>

                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 leading-[1.15] tracking-tight">
                        Temukan Talenta Terbaik.<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-sky-500 to-indigo-600">Wujudkan Proyek Impian.</span>
                    </h1>

                    <p class="text-base sm:text-lg text-slate-600 leading-relaxed max-w-2xl font-medium">
                        ApexForge Labs mempertemukan <strong>freelancer berbakat</strong> dengan
                        <strong>perusahaan terpercaya</strong> untuk menggarap proyek digital secara transparan,
                        efisien, dan profesional.
                    </p>

                    <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-2.5 px-7 py-3.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-bold rounded-2xl shadow-lg shadow-blue-500/25 hover:shadow-xl transition-all duration-200 hover:-translate-y-0.5">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            Cari Proyek
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-2.5 px-7 py-3.5 bg-white hover:bg-blue-50 text-slate-800 font-bold rounded-2xl border border-blue-100 shadow-xs hover:shadow-md transition-all duration-200 hover:-translate-y-0.5">
                            <i class="fa-solid fa-plus-circle text-blue-600 text-xs"></i>
                            Publikasikan Proyek
                        </a>
                    </div>

                    <div class="flex items-center gap-4 pt-4 border-t border-blue-100/60">
                        <div class="flex -space-x-2.5">
                            <div class="w-9 h-9 rounded-full bg-blue-600 ring-2 ring-white flex items-center justify-center text-white text-xs font-bold shadow-xs">A</div>
                            <div class="w-9 h-9 rounded-full bg-indigo-600 ring-2 ring-white flex items-center justify-center text-white text-xs font-bold shadow-xs">B</div>
                            <div class="w-9 h-9 rounded-full bg-sky-500 ring-2 ring-white flex items-center justify-center text-white text-xs font-bold shadow-xs">C</div>
                            <div class="w-9 h-9 rounded-full bg-slate-800 ring-2 ring-white flex items-center justify-center text-white text-xs font-bold shadow-xs">+</div>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-500 font-medium">
                            Dipercaya oleh <strong class="text-slate-900 font-extrabold">100+</strong> Perusahaan &
                            <strong class="text-slate-900 font-extrabold">500+</strong> Freelancer
                        </p>
                    </div>
                </div>

                <!-- Right Hero Card / Visual -->
                <div class="lg:col-span-5 relative flex justify-center items-center">
                    <div class="absolute w-72 h-72 sm:w-96 sm:h-96 bg-gradient-to-tr from-blue-400/30 to-indigo-400/30 rounded-full blur-3xl pointer-events-none"></div>

                    <div class="relative bg-white/90 backdrop-blur-md border border-blue-100/80 rounded-3xl shadow-2xl p-4 w-full max-w-sm sm:max-w-md flex flex-col gap-3">
                        <!-- Window bar mockup -->
                        <div class="flex items-center justify-between pb-2 border-b border-blue-50 px-1">
                            <div class="flex gap-1.5">
                                <span class="w-2.5 h-2.5 bg-rose-400 rounded-full"></span>
                                <span class="w-2.5 h-2.5 bg-amber-400 rounded-full"></span>
                                <span class="w-2.5 h-2.5 bg-emerald-400 rounded-full"></span>
                            </div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Labs Hub</span>
                        </div>

                        <!-- Hero Image -->
                        <div class="h-60 sm:h-72 rounded-2xl overflow-hidden relative group">
                            <img src="{{ asset('images/beranda.png') }}" alt="Freelancer Workspace" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/50 via-transparent to-transparent"></div>
                        </div>

                        <!-- Floating Badge 1 -->
                        <div class="absolute -bottom-4 -left-4 sm:-bottom-5 sm:-left-6 bg-white/95 backdrop-blur-md border border-blue-100/80 rounded-2xl shadow-xl p-3 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 font-bold">
                                <i class="fa-solid fa-circle-check text-lg"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase">Status Platform</p>
                                <p class="text-xs font-extrabold text-slate-900">25+ Proyek Aktif</p>
                            </div>
                        </div>

                        <!-- Floating Badge 2 -->
                        <div class="absolute -top-3 -right-3 sm:-top-4 sm:-right-4 bg-white/95 backdrop-blur-md border border-blue-100/80 rounded-2xl shadow-xl p-3 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-500 flex items-center justify-center shrink-0 font-bold">
                                <i class="fa-solid fa-star text-lg"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase">Kepuasan</p>
                                <p class="text-xs font-extrabold text-slate-900">4.9 / 5.0 Rating</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================ -->
    <!-- 3. STATISTIK -->
    <!-- ============================================================ -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 sm:-mt-12 relative z-20">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 border border-blue-100/80 p-5 sm:p-6 text-center hover:-translate-y-1 transition duration-300">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <i class="fa-solid fa-briefcase text-xl"></i>
                </div>
                <p class="text-2xl sm:text-3xl font-extrabold text-slate-900">
                    {{ number_format($totalProjects, 0, ',', '.') }}
                </p>
                <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">Total Proyek</p>
            </div>
            <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 border border-blue-100/80 p-5 sm:p-6 text-center hover:-translate-y-1 transition duration-300">
                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <i class="fa-solid fa-user-tie text-xl"></i>
                </div>
                <p class="text-2xl sm:text-3xl font-extrabold text-slate-900">
                    {{ number_format($totalFreelancers, 0, ',', '.') }}
                </p>
                <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">Freelancer Berbakat</p>
            </div>
            <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 border border-blue-100/80 p-5 sm:p-6 text-center hover:-translate-y-1 transition duration-300">
                <div class="w-12 h-12 bg-sky-50 text-sky-600 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <i class="fa-solid fa-building text-xl"></i>
                </div>
                <p class="text-2xl sm:text-3xl font-extrabold text-slate-900">
                    {{ number_format($totalCompanies, 0, ',', '.') }}
                </p>
                <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">Perusahaan Terdaftar</p>
            </div>
            <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 border border-blue-100/80 p-5 sm:p-6 text-center hover:-translate-y-1 transition duration-300">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <i class="fa-solid fa-circle-check text-xl"></i>
                </div>
                <p class="text-2xl sm:text-3xl font-extrabold text-slate-900">
                    {{ number_format($totalProjectsCompleted, 0, ',', '.') }}
                </p>
                <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">Proyek Selesai</p>
            </div>
        </div>
    </section>

    <!-- ============================================================ -->
    <!-- 4. KATEGORI -->
    <!-- ============================================================ -->
    <section id="categories" class="scroll-offset max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
        <div class="text-center mb-10 sm:mb-14">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full uppercase tracking-wider mb-3">
                Kategori
            </span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Jelajahi Kategori Proyek</h2>
            <p class="text-slate-500 mt-2 max-w-lg mx-auto text-sm sm:text-base">Temukan pekerjaan atau talenta spesifik sesuai kebutuhan industri Anda</p>
        </div>

        @if ($categories->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach ($categories as $category)
                    <a href="{{ route('login') }}" class="group bg-white border border-blue-100/80 rounded-2xl p-5 text-center hover:border-blue-300 hover:shadow-lg hover:shadow-blue-500/5 transition-all duration-200 hover:-translate-y-1 flex flex-col items-center">
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-3 group-hover:bg-blue-600 group-hover:text-white transition-all duration-200">
                            <i class="fa-solid fa-folder-open text-lg"></i>
                        </div>
                        <span class="text-xs sm:text-sm font-bold text-slate-800 group-hover:text-blue-600 transition line-clamp-1">
                            {{ $category->name }}
                        </span>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-white rounded-3xl border border-blue-100/80">
                <p class="text-slate-400 font-medium">Belum ada kategori tersedia saat ini.</p>
            </div>
        @endif
    </section>

    <!-- ============================================================ -->
    <!-- 5. PROYEK TERBARU -->
    <!-- ============================================================ -->
    <section id="projects" class="scroll-offset bg-white py-16 sm:py-24 border-y border-blue-100/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10 sm:mb-14">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full uppercase tracking-wider mb-3">
                    Proyek Terbaru
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Peluang Kerja Terbaru</h2>
                <p class="text-slate-500 mt-2 max-w-lg mx-auto text-sm sm:text-base">Gunakan keahlian Anda untuk memenangkan proyek freelance berkualitas</p>
            </div>

            @if ($recentProjects->count() > 0)
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                    @foreach ($recentProjects as $project)
                    <a href="{{ route('projects.public.show', $project) }}"
   class="group block">
                        <div class="group bg-[#f6f9ff]/50 hover:bg-white border border-blue-100/80 rounded-2xl overflow-hidden shadow-xs hover:shadow-xl hover:border-blue-200 transition-all duration-300 hover:-translate-y-1.5 flex flex-col justify-between">
                            <div>
                                <!-- Image Header -->
                                <div class="h-44 overflow-hidden bg-blue-50 relative">
                                    @if ($project->image)
                                        <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->project_name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    @else
                                        <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-slate-100 to-blue-50 text-slate-300">
                                            <i class="fa-solid fa-image text-3xl mb-1"></i>
                                            <span class="text-[10px] font-medium text-slate-400">Tidak ada gambar</span>
                                        </div>
                                    @endif

                                    <span class="absolute top-3 right-3 px-3 py-1 text-[11px] font-bold rounded-full shadow-xs backdrop-blur-md {{ ($project->status ?? 'open') === 'open' ? 'bg-emerald-500/90 text-white' : 'bg-slate-700/80 text-white' }}">
                                        {{ \App\Models\Project::statusLabel($project->status ?? 'open') }}
                                    </span>
                                </div>

                                <!-- Card Body -->
                                <div class="p-6">
                                    <span class="inline-block text-[11px] font-bold text-blue-600 bg-blue-50 border border-blue-100 px-2.5 py-0.5 rounded-md mb-2">
                                        {{ optional($project->category)->name ?? 'Umum' }}
                                    </span>
                                    <h3 class="font-bold text-base sm:text-lg text-slate-900 line-clamp-1 group-hover:text-blue-600 transition mb-2">
                                        {{ $project->project_name }}
                                    </h3>
                                    <p class="text-xs sm:text-sm text-slate-500 line-clamp-2 leading-relaxed">
                                        {{ Str::limit($project->project_description ?? 'Tidak ada deskripsi.', 90) }}
                                    </p>

                                    <!-- Owner Info -->
                                    <div class="flex items-center gap-2 mt-4 pt-4 border-t border-blue-50">
                                        <div class="w-6 h-6 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-[10px] font-bold">
                                            <i class="fa-regular fa-building"></i>
                                        </div>
                                        <span class="text-xs font-semibold text-slate-600 truncate">
                                            {{ optional($project->owner)->name ?? 'Perusahaan' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Footer -->
                            <div class="px-6 pb-6 pt-0 flex items-center justify-between border-t border-blue-50/80 mt-2">
                                <div>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase">Budget</p>
                                    <p class="text-base font-extrabold text-blue-600">
                                        Rp {{ number_format($project->budget ?? 0, 0, ',', '.') }}
                                    </p>
                                </div>
                                @if ($project->deadline)
                                    <div class="text-right">
                                        <p class="text-[10px] text-slate-400 font-bold uppercase">Deadline</p>
                                        <span class="text-xs font-semibold text-slate-600">
                                            <i class="fa-regular fa-calendar text-slate-400 mr-1"></i>{{ \Carbon\Carbon::parse($project->deadline)->format('d M Y') }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-12">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-8 py-3.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-2xl shadow-md transition hover:shadow-lg">
                        Lihat Semua Proyek
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <i class="fa-solid fa-briefcase text-2xl"></i>
                    </div>
                    <p class="text-slate-500 font-medium">Belum ada proyek terbaru saat ini.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- ============================================================ -->
    <!-- 6. UNTUK FREELANCER -->
    <!-- ============================================================ -->
    <section id="freelancer" class="scroll-offset max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full uppercase tracking-wider">
                    Untuk Freelancer
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 leading-tight tracking-tight">
                    Kembangkan Karir Freelance <span class="text-emerald-600">Anda</span>
                </h2>
                <p class="text-slate-600 leading-relaxed font-medium">
                    Bergabunglah bersama ribuan talenta digital Indonesia dan mulailah menghasilkan pendapatan dari proyek-proyek menarik.
                </p>

                <div class="grid sm:grid-cols-2 gap-4 pt-2">
                    <div class="p-4 bg-white border border-blue-100/80 rounded-2xl shadow-xs">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-2 font-bold">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </div>
                        <h4 class="font-bold text-sm text-slate-900">Temukan Proyek</h4>
                        <p class="text-xs text-slate-500 mt-1">Akses ratusan proyek sesuai spesialisasi utama Anda.</p>
                    </div>
                    <div class="p-4 bg-white border border-blue-100/80 rounded-2xl shadow-xs">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-2 font-bold">
                            <i class="fa-solid fa-paper-plane text-xs"></i>
                        </div>
                        <h4 class="font-bold text-sm text-slate-900">Kirim Penawaran</h4>
                        <p class="text-xs text-slate-500 mt-1">Ajukan proposal menarik langsung ke perusahaan.</p>
                    </div>
                    <div class="p-4 bg-white border border-blue-100/80 rounded-2xl shadow-xs">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-2 font-bold">
                            <i class="fa-solid fa-shield-halved text-xs"></i>
                        </div>
                        <h4 class="font-bold text-sm text-slate-900">Sistem Aman</h4>
                        <p class="text-xs text-slate-500 mt-1">Pembayaran terjamin melalui alur proyek yang transparan.</p>
                    </div>
                    <div class="p-4 bg-white border border-blue-100/80 rounded-2xl shadow-xs">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-2 font-bold">
                            <i class="fa-solid fa-star text-xs"></i>
                        </div>
                        <h4 class="font-bold text-sm text-slate-900">Bangun Reputasi</h4>
                        <p class="text-xs text-slate-500 mt-1">Dapatkan ulasan positif untuk meningkatkan tarif Anda.</p>
                    </div>
                </div>

                <div class="pt-2">
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-7 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl shadow-md shadow-emerald-500/20 transition hover:shadow-lg">
                        <i class="fa-solid fa-user-plus text-xs"></i>
                        Daftar Sebagai Freelancer
                    </a>
                </div>
            </div>

            <div class="relative h-[340px] sm:h-[420px] rounded-3xl overflow-hidden shadow-2xl border border-blue-100/60">
                <img src="{{ asset('images/beranda.png') }}" alt="Freelancer Workspace" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>
                <div class="absolute bottom-6 left-6 right-6">
                    <div class="bg-white/90 backdrop-blur-md rounded-2xl p-4 border border-white/20 shadow-lg">
                        <p class="text-xs sm:text-sm font-bold text-slate-900">"Sistem kerja yang transparan memudahkan saya mendapat klien tetap!"</p>
                        <p class="text-[11px] text-slate-500 font-semibold mt-1">— Freelancer Aktif Labs</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================ -->
    <!-- 7. UNTUK PERUSAHAAN -->
    <!-- ============================================================ -->
    <section id="company" class="scroll-offset bg-white py-16 sm:py-24 border-y border-blue-100/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">

                <!-- Image Card -->
                <div class="relative h-[340px] sm:h-[420px] rounded-3xl overflow-hidden shadow-xl border border-blue-100/80 order-2 lg:order-1">
                    <img src="{{ asset('images/image.png') }}" alt="Perusahaan" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent"></div>

                    <div class="absolute bottom-6 left-6 right-6">
                        <div class="bg-white/95 backdrop-blur-md rounded-2xl p-4 border border-blue-100/80 shadow-lg">
                            <p class="text-xs sm:text-sm font-bold text-slate-900">"Kualitas talenta di platform ini sangat memuaskan dan tepat waktu."</p>
                            <p class="text-[11px] text-blue-600 font-semibold mt-1">— Partner Mitra Perusahaan</p>
                        </div>
                    </div>
                </div>

                <!-- Text Content -->
                <div class="space-y-6 order-1 lg:order-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full uppercase tracking-wider">
                        Untuk Perusahaan
                    </span>

                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 leading-tight tracking-tight">
                        Temukan Talenta Terbaik <span class="text-blue-600">Untuk Bisnis Anda</span>
                    </h2>

                    <p class="text-slate-600 leading-relaxed font-medium">
                        Selesaikan pekerjaan digital bisnis Anda lebih cepat dengan dukungan tim profesional independen yang terverifikasi.
                    </p>

                    <div class="space-y-3 pt-2">
                        <div class="flex items-center gap-3.5 p-3.5 rounded-2xl bg-[#f6f9ff] border border-blue-100/80 shadow-xs">
                            <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center shrink-0 font-bold">
                                <i class="fa-solid fa-plus text-xs"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-slate-900">Publikasikan Proyek Mudah</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Tentukan kriteria, budget, dan batas waktu proyek Anda.</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3.5 p-3.5 rounded-2xl bg-[#f6f9ff] border border-blue-100/80 shadow-xs">
                            <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center shrink-0 font-bold">
                                <i class="fa-solid fa-layer-group text-xs"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-slate-900">Workspace Terintegrasi</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Pantau kemajuan pekerjaan secara real-time di satu tempat.</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2">
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-7 py-3.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-bold rounded-2xl shadow-md shadow-blue-500/20 transition-all duration-200">
                            <i class="fa-solid fa-building text-xs"></i>
                            Daftar Sebagai Perusahaan
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ============================================================ -->
    <!-- 8. CARA KERJA -->
    <!-- ============================================================ -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
        <div class="text-center mb-12">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full uppercase tracking-wider mb-3">
                Cara Kerja
            </span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Bagaimana Cara Kerjanya?</h2>
            <p class="text-slate-500 mt-2 max-w-lg mx-auto text-sm sm:text-base">Mulai kolaborasi Anda hanya dalam 4 langkah sederhana</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white border border-blue-100/80 rounded-2xl p-6 text-center shadow-xs hover:shadow-xl transition duration-300">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 font-extrabold text-lg">
                    1
                </div>
                <h3 class="font-bold text-base text-slate-900">Daftar Akun</h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">Buat profil Anda sebagai Freelancer atau Perusahaan secara gratis.</p>
            </div>
            <div class="bg-white border border-blue-100/80 rounded-2xl p-6 text-center shadow-xs hover:shadow-xl transition duration-300">
                <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4 font-extrabold text-lg">
                    2
                </div>
                <h3 class="font-bold text-base text-slate-900">Temukan Proyek</h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">Cari proyek menarik atau buka lowongan proyek baru.</p>
            </div>
            <div class="bg-white border border-blue-100/80 rounded-2xl p-6 text-center shadow-xs hover:shadow-xl transition duration-300">
                <div class="w-12 h-12 bg-sky-100 text-sky-600 rounded-2xl flex items-center justify-center mx-auto mb-4 font-extrabold text-lg">
                    3
                </div>
                <h3 class="font-bold text-base text-slate-900">Kolaborasi</h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">Sepakati penawaran dan kerjakan proyek di ruang kerja khusus.</p>
            </div>
            <div class="bg-white border border-blue-100/80 rounded-2xl p-6 text-center shadow-xs hover:shadow-xl transition duration-300">
                <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-4 font-extrabold text-lg">
                    4
                </div>
                <h3 class="font-bold text-base text-slate-900">Selesai & Review</h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">Serahkan hasil pekerjaan dan berikan penilaian hasil kerja.</p>
            </div>
        </div>
    </section>

    <!-- ============================================================ -->
    <!-- 9. FINAL CTA -->
    <!-- ============================================================ -->
    <section class="relative overflow-hidden py-16 sm:py-20 my-8 mx-4 sm:mx-8 lg:mx-auto max-w-7xl rounded-3xl bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-800 text-white shadow-2xl shadow-blue-500/20">
        <div class="relative z-10 text-center px-4 sm:px-6 lg:px-8 max-w-3xl mx-auto">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight">
                Siap Memulai Perjalanan Anda?
            </h2>
            <p class="text-blue-100 mt-4 text-sm sm:text-base leading-relaxed">
                Bergabunglah bersama ribuan talenta digital dan perusahaan di platform ApexForge Labs sekarang juga.
            </p>
            <div class="flex flex-wrap justify-center gap-4 mt-8">
                <a href="{{ route('register') }}" class="px-8 py-3.5 bg-white text-blue-700 hover:bg-blue-50 font-extrabold rounded-2xl shadow-lg transition hover:-translate-y-0.5">
                    Daftar Gratis Sekarang
                </a>
                <a href="{{ route('login') }}" class="px-8 py-3.5 bg-blue-500/30 hover:bg-blue-500/40 text-white font-bold rounded-2xl border border-blue-400/30 transition hover:-translate-y-0.5">
                    Masuk Akun
                </a>
            </div>
        </div>
    </section>

    <!-- ============================================================ -->
    <!-- 10. FOOTER -->
    <!-- ============================================================ -->
    <footer class="bg-white border-t border-blue-100/80 pt-12 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                <div class="space-y-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl overflow-hidden ring-1 ring-slate-200">
                            <img src="{{ asset('images/nexus.jpg') }}" alt="Logo" class="w-full h-full object-cover">
                        </div>
                        <span class="font-extrabold text-base text-slate-900">ApexForge Labs</span>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Platform marketplace freelance terpercaya yang menghubungkan talenta berbakat dengan perusahaan untuk mewujudkan proyek digital secara profesional.
                    </p>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4">Freelancer</h4>
                    <ul class="space-y-2.5 text-xs font-semibold text-slate-600">
                        <li><a href="{{ route('register') }}" class="hover:text-blue-600 transition">Daftar Freelancer</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-blue-600 transition">Cari Proyek</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-blue-600 transition">Mulai Bekerja</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4">Perusahaan</h4>
                    <ul class="space-y-2.5 text-xs font-semibold text-slate-600">
                        <li><a href="{{ route('register') }}" class="hover:text-blue-600 transition">Daftar Perusahaan</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-blue-600 transition">Publikasikan Proyek</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-blue-600 transition">Cari Talenta</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4">Dukungan</h4>
                    <ul class="space-y-2.5 text-xs font-semibold text-slate-600">
                        <li><a href="{{ route('help.index') }}" class="hover:text-blue-600 transition">Pusat Bantuan</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-blue-600 transition">Kebijakan Privasi</a></li>
                        <li class="pt-2 text-slate-400 font-normal">
                            <i class="fa-regular fa-envelope mr-1.5"></i> support@apexforgelabs.id
                        </li>
                    </ul>
                </div>
            </div>

            <div class="pt-6 border-t border-blue-50 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-medium text-slate-500">
                <p>&copy; 2026 ApexForge Labs. Hak Cipta Dilindungi.</p>
                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}" class="hover:text-blue-600 transition">Privasi</a>
                    <a href="{{ route('login') }}" class="hover:text-blue-600 transition">Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu Script -->
    <script>
        document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>
</body>

</html>