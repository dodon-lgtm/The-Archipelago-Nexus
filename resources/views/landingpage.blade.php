<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Archipelago Nexus — Marketplace Freelance Indonesia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        html { scroll-behavior: smooth; }
        .scroll-offset { scroll-margin-top: 80px; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

    <!-- ============================================================ -->
    <!-- 1. NAVBAR -->
    <!-- ============================================================ -->
    <header class="bg-white/90 backdrop-blur-md border-b border-slate-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 sm:h-20 flex items-center justify-between">
            <!-- Brand -->
            <a href="{{ route('landing') }}" class="flex items-center gap-2.5 sm:gap-3 group">
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full overflow-hidden ring-2 ring-cyan-100 group-hover:ring-cyan-300 transition-all">
                    <img src="{{ asset('images/nexus.jpg') }}" alt="The Archipelago Nexus" class="w-full h-full object-cover">
                </div>
                <span class="font-bold text-sm sm:text-lg tracking-tight text-slate-900 group-hover:text-cyan-700 transition">
                    The Archipelago Nexus
                </span>
            </a>

            <!-- Mobile Menu Toggle -->
            <button id="mobile-menu-btn" class="lg:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100 transition" aria-label="Toggle menu">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>

            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex items-center gap-1">
                <a href="#home" class="nav-link px-3 py-2 text-sm font-medium text-slate-600 hover:text-cyan-700 hover:bg-cyan-50 rounded-lg transition">Beranda</a>
                <a href="#categories" class="nav-link px-3 py-2 text-sm font-medium text-slate-600 hover:text-cyan-700 hover:bg-cyan-50 rounded-lg transition">Kategori</a>
                <a href="#projects" class="nav-link px-3 py-2 text-sm font-medium text-slate-600 hover:text-cyan-700 hover:bg-cyan-50 rounded-lg transition">Proyek Terbaru</a>
                <a href="#freelancer" class="nav-link px-3 py-2 text-sm font-medium text-slate-600 hover:text-cyan-700 hover:bg-cyan-50 rounded-lg transition">Freelancer</a>
                <a href="#company" class="nav-link px-3 py-2 text-sm font-medium text-slate-600 hover:text-cyan-700 hover:bg-cyan-50 rounded-lg transition">Perusahaan</a>
            </nav>

            <!-- Desktop Auth -->
            <div class="hidden lg:flex items-center gap-3">
                @auth
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-slate-600">Halo,</span>
                        <span class="text-sm font-semibold text-slate-900">{{ Auth::user()->name }}</span>
                    </div>
                    @if(Auth::user()->role === 'freelancer')
                        <a href="{{ route('freelancer.dashboard') }}" class="px-4 py-2 text-sm font-semibold text-white bg-cyan-600 hover:bg-cyan-700 rounded-xl transition shadow-sm">
                            <i class="fa-solid fa-gauge-high mr-1.5"></i>Dashboard
                        </a>
                    @elseif(Auth::user()->role === 'company')
                        <a href="{{ route('company.dashboard') }}" class="px-4 py-2 text-sm font-semibold text-white bg-cyan-600 hover:bg-cyan-700 rounded-xl transition shadow-sm">
                            <i class="fa-solid fa-building mr-1.5"></i>Dashboard
                        </a>
                    @elseif(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 text-sm font-semibold text-white bg-cyan-600 hover:bg-cyan-700 rounded-xl transition shadow-sm">
                            <i class="fa-solid fa-shield-halved mr-1.5"></i>Admin
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 text-sm font-semibold text-slate-700 border border-slate-300 hover:bg-slate-100 rounded-xl transition">
                            <i class="fa-solid fa-right-from-bracket mr-1.5"></i>Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-5 py-2 text-sm font-semibold text-slate-700 hover:text-slate-950 border border-slate-300 rounded-xl hover:bg-slate-50 transition">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="px-5 py-2 text-sm font-semibold text-white bg-slate-900 hover:bg-slate-800 rounded-xl transition shadow-sm">
                        Daftar
                    </a>
                @endauth
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden lg:hidden bg-white border-t border-slate-100 px-4 py-4 space-y-3">
            @auth
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                    <div class="w-8 h-8 rounded-full bg-cyan-100 flex items-center justify-center text-cyan-700 font-bold text-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <span class="font-semibold text-sm">{{ Auth::user()->name }}</span>
                </div>
                @if(Auth::user()->role === 'freelancer')
                    <a href="{{ route('freelancer.dashboard') }}" class="block px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 rounded-lg">
                        <i class="fa-solid fa-gauge-high mr-2 text-cyan-600"></i>Dashboard Freelancer
                    </a>
                @elseif(Auth::user()->role === 'company')
                    <a href="{{ route('company.dashboard') }}" class="block px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 rounded-lg">
                        <i class="fa-solid fa-building mr-2 text-cyan-600"></i>Dashboard Perusahaan
                    </a>
                @elseif(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 rounded-lg">
                        <i class="fa-solid fa-shield-halved mr-2 text-cyan-600"></i>Dashboard Admin
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg">
                        <i class="fa-solid fa-right-from-bracket mr-2"></i>Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 rounded-lg">
                    <i class="fa-solid fa-arrow-right-to-bracket mr-2"></i>Masuk
                </a>
                <a href="{{ route('register') }}" class="block px-4 py-2 text-sm font-medium text-white bg-slate-900 hover:bg-slate-800 text-center rounded-lg">
                    Daftar Sekarang
                </a>
            @endauth
        </div>
    </header>

    <!-- ============================================================ -->
    <!-- 2. HERO SECTION -->
    <!-- ============================================================ -->
    <section id="home" class="relative overflow-hidden bg-gradient-to-br from-slate-50 via-white to-cyan-50">
        <div class="absolute inset-0 z-0 pointer-events-none">
            <img src="{{ asset('images/gedung.jpg') }}" alt="Gedung" class="w-full h-full object-cover opacity-30 sm:opacity-40">
            <div class="absolute inset-0 bg-gradient-to-r from-white/90 via-white/70 to-transparent"></div>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-24">
            <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
                <!-- Left: Text -->
                <div class="space-y-6 sm:space-y-8">
                    <div>
                        <span class="inline-block px-3 py-1 bg-cyan-100 text-cyan-700 text-xs font-bold rounded-full tracking-wide uppercase">
                            Marketplace Freelance #1 di Indonesia
                        </span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900 leading-tight">
                        Temukan Talenta Terbaik.
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-600 to-teal-500">Wujudkan Proyek Hebat.</span>
                    </h1>
                    <p class="text-base sm:text-lg text-slate-600 leading-relaxed max-w-xl">
                        The Archipelago Nexus adalah platform <strong>marketplace freelance</strong> yang mempertemukan 
                        <strong>freelancer</strong> berbakat dengan <strong>perusahaan</strong> untuk mengerjakan proyek digital 
                        secara aman, transparan, dan profesional.
                    </p>
                    <div class="flex flex-wrap gap-3 sm:gap-4">
                        <a href="{{ route('register') }}" 
                           class="inline-flex items-center gap-2 px-6 sm:px-8 py-3 sm:py-3.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            Cari Proyek
                        </a>
                        <a href="{{ route('register') }}" 
                           class="inline-flex items-center gap-2 px-6 sm:px-8 py-3 sm:py-3.5 bg-white hover:bg-slate-50 text-slate-800 font-semibold rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition transform hover:-translate-y-0.5">
                            <i class="fa-solid fa-plus-circle text-cyan-600"></i>
                            Publikasikan Proyek
                        </a>
                    </div>
                    <div class="flex items-center gap-4 pt-2">
                        <div class="flex -space-x-2">
                            <div class="w-8 h-8 rounded-full bg-cyan-500 border-2 border-white flex items-center justify-center text-white text-xs font-bold">A</div>
                            <div class="w-8 h-8 rounded-full bg-teal-500 border-2 border-white flex items-center justify-center text-white text-xs font-bold">B</div>
                            <div class="w-8 h-8 rounded-full bg-blue-500 border-2 border-white flex items-center justify-center text-white text-xs font-bold">C</div>
                            <div class="w-8 h-8 rounded-full bg-slate-400 border-2 border-white flex items-center justify-center text-white text-xs font-bold">+</div>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-500">
                            Dipercaya oleh <strong class="text-slate-800">100+</strong> perusahaan dan <strong class="text-slate-800">500+</strong> freelancer
                        </p>
                    </div>
                </div>
                <!-- Right: Hero Mockup -->
                <div class="relative flex justify-center items-center h-[280px] sm:h-[360px] lg:h-[420px]">
                    <div class="absolute right-2 sm:right-6 w-[220px] h-[220px] sm:w-[300px] sm:h-[300px] lg:w-[380px] lg:h-[380px] bg-gradient-to-br from-cyan-400 via-teal-400 to-emerald-400 rounded-full opacity-80 shadow-2xl blur-sm"></div>
                    <div class="relative bg-white/95 backdrop-blur-md border border-slate-200/70 rounded-2xl shadow-2xl p-3 sm:p-4 w-full max-w-[260px] sm:max-w-[320px] lg:max-w-[360px] h-[220px] sm:h-[280px] lg:h-[320px] flex flex-col">
                        <div class="flex gap-1.5 mb-2">
                            <span class="w-2 h-2 sm:w-3 sm:h-3 bg-red-400 rounded-full"></span>
                            <span class="w-2 h-2 sm:w-3 sm:h-3 bg-yellow-400 rounded-full"></span>
                            <span class="w-2 h-2 sm:w-3 sm:h-3 bg-green-400 rounded-full"></span>
                        </div>
                        <div class="flex-1 rounded-xl overflow-hidden bg-gradient-to-t from-cyan-50 to-transparent relative">
                            <img src="{{ asset('images/beranda.png') }}" alt="Freelancer" class="w-full h-full object-cover object-center">
                        </div>
                        <div class="absolute -bottom-3 -left-4 sm:-bottom-4 sm:-left-6 bg-white border border-slate-100 rounded-lg shadow-lg py-1 sm:py-1.5 px-2 sm:px-3 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                            <span class="text-[8px] sm:text-[10px] font-bold text-slate-700">25+ Proyek Aktif</span>
                        </div>
                        <div class="absolute -top-2 -right-3 sm:-top-3 sm:-right-4 bg-white border border-slate-100 rounded-lg shadow-lg p-1.5 sm:p-2">
                            <p class="text-[7px] sm:text-[8px] text-gray-400 font-medium">Rating</p>
                            <div class="w-8 sm:w-10 h-1.5 bg-blue-100 rounded-full my-1 overflow-hidden">
                                <div class="w-4/5 h-full bg-teal-500 rounded-full"></div>
                            </div>
                            <p class="text-[7px] sm:text-[8px] font-bold text-slate-700">4.8 / 5.0</p>
                        </div>
                    </div>
                    <div class="absolute top-1/2 -right-3 sm:-right-5 w-8 h-8 sm:w-10 sm:h-10 bg-white border border-gray-100 rounded-full shadow-lg flex items-center justify-center text-emerald-500 text-base sm:text-lg">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div class="absolute top-20 sm:top-24 left-2 sm:left-4 bg-white w-6 h-6 sm:w-7 sm:h-7 rounded-full shadow-md flex items-center justify-center text-xs sm:text-sm">
                        &#128525;
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================ -->
    <!-- 3. STATISTIK -->
    <!-- ============================================================ -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 sm:-mt-10 relative z-20">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-4 sm:p-6 text-center hover:shadow-xl transition hover:-translate-y-1">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-cyan-100 rounded-xl flex items-center justify-center mx-auto mb-2 sm:mb-3">
                    <i class="fa-solid fa-briefcase text-cyan-600 text-lg sm:text-xl"></i>
                </div>
                <p class="text-xl sm:text-3xl font-extrabold text-slate-900">{{ number_format($totalProjects, 0, ',', '.') }}</p>
                <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">Total Proyek</p>
            </div>
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-4 sm:p-6 text-center hover:shadow-xl transition hover:-translate-y-1">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-teal-100 rounded-xl flex items-center justify-center mx-auto mb-2 sm:mb-3">
                    <i class="fa-solid fa-user-tie text-teal-600 text-lg sm:text-xl"></i>
                </div>
                <p class="text-xl sm:text-3xl font-extrabold text-slate-900">{{ number_format($totalFreelancers, 0, ',', '.') }}</p>
                <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">Freelancer</p>
            </div>
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-4 sm:p-6 text-center hover:shadow-xl transition hover:-translate-y-1">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-2 sm:mb-3">
                    <i class="fa-solid fa-building text-blue-600 text-lg sm:text-xl"></i>
                </div>
                <p class="text-xl sm:text-3xl font-extrabold text-slate-900">{{ number_format($totalCompanies, 0, ',', '.') }}</p>
                <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">Perusahaan</p>
            </div>
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-4 sm:p-6 text-center hover:shadow-xl transition hover:-translate-y-1">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-emerald-100 rounded-xl flex items-center justify-center mx-auto mb-2 sm:mb-3">
                    <i class="fa-solid fa-check-circle text-emerald-600 text-lg sm:text-xl"></i>
                </div>
                <p class="text-xl sm:text-3xl font-extrabold text-slate-900">{{ number_format($totalProjectsCompleted, 0, ',', '.') }}</p>
                <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">Proyek Selesai</p>
            </div>
        </div>
    </section>

    <!-- ============================================================ -->
    <!-- 4. KATEGORI -->
    <!-- ============================================================ -->
    <section id="categories" class="scroll-offset max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
        <div class="text-center mb-8 sm:mb-12">
            <span class="inline-block px-3 py-1 bg-cyan-100 text-cyan-700 text-xs font-bold rounded-full uppercase tracking-wide mb-3">Kategori</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Jelajahi Kategori Proyek</h2>
            <p class="text-slate-500 mt-2 max-w-lg mx-auto">Temukan proyek sesuai dengan keahlian dan minat Anda</p>
        </div>
        @if($categories->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-4">
            @foreach($categories as $category)
            <a href="{{ route('login') }}" class="group bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 text-center hover:border-cyan-300 hover:shadow-lg transition-all hover:-translate-y-1">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-cyan-100 to-teal-100 rounded-xl flex items-center justify-center mx-auto mb-2 sm:mb-3 group-hover:from-cyan-500 group-hover:to-teal-500 transition-all">
                    <i class="fa-solid fa-folder-open text-cyan-600 text-sm sm:text-base group-hover:text-white transition"></i>
                </div>
                <span class="text-xs sm:text-sm font-semibold text-slate-800 group-hover:text-cyan-700 transition line-clamp-1">{{ $category->name }}</span>
            </a>
            @endforeach
        </div>
        @else
        <div class="text-center py-8">
            <p class="text-slate-400">Belum ada kategori tersedia.</p>
        </div>
        @endif
    </section>

    <!-- ============================================================ -->
    <!-- 5. PROYEK TERBARU -->
    <!-- ============================================================ -->
    <section id="projects" class="scroll-offset bg-white py-12 sm:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 sm:mb-12">
                <span class="inline-block px-3 py-1 bg-cyan-100 text-cyan-700 text-xs font-bold rounded-full uppercase tracking-wide mb-3">Proyek Terbaru</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Proyek Terbaru dari Perusahaan</h2>
                <p class="text-slate-500 mt-2 max-w-lg mx-auto">Temukan peluang proyek menarik dari berbagai perusahaan</p>
            </div>
            @if($recentProjects->count() > 0)
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @foreach($recentProjects as $project)
                <div class="group bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all hover:-translate-y-1">
                    <!-- Project Image -->
                    <div class="h-36 sm:h-44 overflow-hidden bg-slate-100 relative">
                        @if($project->image)
                            <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->project_name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-cyan-50 to-teal-50">
                                <i class="fa-solid fa-image text-4xl text-slate-300"></i>
                            </div>
                        @endif
                        <div class="absolute top-2 right-2">
                            <span class="px-2 py-1 text-[10px] font-bold rounded-lg
                                {{ $project->status === 'Open' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ $project->status }}
                            </span>
                        </div>
                    </div>
                    <!-- Project Info -->
                    <div class="p-4 sm:p-5">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-[10px] font-semibold text-cyan-600 bg-cyan-50 px-2 py-0.5 rounded-md">
                                {{ optional($project->category)->name ?? 'Umum' }}
                            </span>
                        </div>
                        <h3 class="font-bold text-sm sm:text-base text-slate-900 line-clamp-1 group-hover:text-cyan-700 transition">
                            {{ $project->project_name }}
                        </h3>
                        <p class="text-xs text-slate-500 mt-1 line-clamp-2">
                            {{ Str::limit($project->project_description ?? 'Tidak ada deskripsi.', 80) }}
                        </p>
                        <div class="flex items-center gap-2 mt-3">
                            <div class="w-5 h-5 rounded-full bg-slate-300 flex items-center justify-center text-white text-[8px] font-bold">
                                {{ strtoupper(substr(optional($project->owner)->name ?? '?', 0, 1)) }}
                            </div>
                            <span class="text-xs text-slate-600 font-medium">{{ optional($project->owner)->name ?? 'Perusahaan' }}</span>
                        </div>
                        <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-100">
                            <p class="text-sm sm:text-base font-extrabold text-cyan-700">
                                Rp {{ number_format($project->budget ?? 0, 0, ',', '.') }}
                            </p>
                            @if($project->deadline)
                                <span class="text-[10px] text-slate-400">
                                    <i class="fa-regular fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($project->deadline)->format('d M Y') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-8 sm:mt-10">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-xl transition shadow-md hover:shadow-lg">
                    Lihat Semua Proyek
                    <i class="fa-solid fa-arrow-right text-sm"></i>
                </a>
            </div>
            @else
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fa-solid fa-briefcase text-2xl text-slate-400"></i>
                </div>
                <p class="text-slate-500">Belum ada proyek terbaru.</p>
            </div>
            @endif
        </div>
    </section>

    <!-- ============================================================ -->
    <!-- 6. UNTUK FREELANCER -->
    <!-- ============================================================ -->
    <section id="freelancer" class="scroll-offset max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
        <div class="grid lg:grid-cols-2 gap-8 sm:gap-12 items-center">
            <div class="space-y-6">
                <span class="inline-block px-3 py-1 bg-teal-100 text-teal-700 text-xs font-bold rounded-full uppercase tracking-wide">Untuk Freelancer</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-tight">
                    Kembangkan Karir Freelance <span class="text-teal-600">Anda</span>
                </h2>
                <p class="text-slate-600 leading-relaxed">
                    Bergabunglah dengan ribuan freelancer berbakat dan temukan proyek yang sesuai dengan keahlian Anda.
                </p>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-teal-100 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fa-solid fa-magnifying-glass text-teal-600 text-sm"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-slate-900">Temukan Proyek Sesuai Skill</h4>
                            <p class="text-xs text-slate-500">Jelajahi berbagai proyek dari perusahaan terpercaya</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-teal-100 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fa-solid fa-paper-plane text-teal-600 text-sm"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-slate-900">Ajukan Penawaran</h4>
                            <p class="text-xs text-slate-500">Kirim proposal dan dapatkan proyek impian Anda</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-teal-100 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fa-solid fa-folder-open text-teal-600 text-sm"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-slate-900">Bangun Portofolio</h4>
                            <p class="text-xs text-slate-500">Kumpulkan pengalaman dan rating dari setiap proyek</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-teal-100 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fa-solid fa-coins text-teal-600 text-sm"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-slate-900">Dapatkan Peluang Kerja</h4>
                            <p class="text-xs text-slate-500">Dapatkan penghasilan dari project yang kamu selesaikan</p>
                        </div>
                    </div>
                </div>
                <div class="pt-2">
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-xl transition shadow-md hover:shadow-lg">
                        <i class="fa-solid fa-user-plus"></i>
                        Daftar Sebagai Freelancer
                    </a>
                </div>
            </div>
            <div class="relative h-[300px] sm:h-[400px] rounded-2xl overflow-hidden shadow-xl">
                <img src="{{ asset('images/beranda.png') }}" alt="Freelancer" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                <div class="absolute bottom-4 left-4 right-4">
                    <div class="bg-white/90 backdrop-blur rounded-xl p-3 sm:p-4">
                        <p class="text-xs sm:text-sm font-bold text-slate-900">"Platform ini membantu saya mendapatkan proyek pertama!"</p>
                        <p class="text-[10px] sm:text-xs text-slate-500 mt-1">— Freelancer Aktif</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================ -->
    <!-- 7. UNTUK PERUSAHAAN -->
    <!-- ============================================================ -->
    <section id="company" class="scroll-offset bg-gradient-to-br from-slate-900 to-slate-800 py-12 sm:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-8 sm:gap-12 items-center">
                <div class="relative h-[300px] sm:h-[400px] rounded-2xl overflow-hidden shadow-xl order-2 lg:order-1">
                    <img src="{{ asset('images/image.png') }}" alt="Perusahaan" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                    <div class="absolute bottom-4 left-4 right-4">
                        <div class="bg-white/90 backdrop-blur rounded-xl p-3 sm:p-4">
                            <p class="text-xs sm:text-sm font-bold text-slate-900">"Kualitas freelancer sangat memuaskan!"</p>
                            <p class="text-[10px] sm:text-xs text-slate-500 mt-1">— Perusahaan Terdaftar</p>
                        </div>
                    </div>
                </div>
                <div class="space-y-6 text-white order-1 lg:order-2">
                    <span class="inline-block px-3 py-1 bg-cyan-800 text-cyan-200 text-xs font-bold rounded-full uppercase tracking-wide">Untuk Perusahaan</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold leading-tight">
                        Temukan Talenta Terbaik <span class="text-cyan-400">Untuk Bisnis Anda</span>
                    </h2>
                    <p class="text-slate-300 leading-relaxed">
                        Publikasikan proyek Anda dan temukan freelancer berkualitas untuk mewujudkannya.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-cyan-800 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="fa-solid fa-plus-circle text-cyan-300 text-sm"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm">Publikasikan Proyek</h4>
                                <p class="text-xs text-slate-400">Buat proyek dan dapatkan penawaran dari freelancer</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-cyan-800 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="fa-solid fa-search text-cyan-300 text-sm"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm">Temukan Freelancer</h4>
                                <p class="text-xs text-slate-400">Cari talenta dengan skill yang Anda butuhkan</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-cyan-800 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="fa-solid fa-gear text-cyan-300 text-sm"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm">Kelola Proyek</h4>
                                <p class="text-xs text-slate-400">Pantau proses proyek melalui workspace terintegrasi</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-cyan-800 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="fa-solid fa-star text-cyan-300 text-sm"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm">Pilih Talenta Terbaik</h4>
                                <p class="text-xs text-slate-400">Review portofolio dan pilih freelancer ideal</p>
                            </div>
                        </div>
                    </div>
                    <div class="pt-2">
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-cyan-600 hover:bg-cyan-500 text-white font-semibold rounded-xl transition shadow-md hover:shadow-lg">
                            <i class="fa-solid fa-building"></i>
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
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
        <div class="text-center mb-8 sm:mb-12">
            <span class="inline-block px-3 py-1 bg-cyan-100 text-cyan-700 text-xs font-bold rounded-full uppercase tracking-wide mb-3">Cara Kerja</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Bagaimana Cara Kerjanya?</h2>
            <p class="text-slate-500 mt-2 max-w-lg mx-auto">Mulai perjalanan Anda hanya dalam 4 langkah mudah</p>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <div class="relative bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 text-center hover:shadow-xl transition group">
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-cyan-100 rounded-2xl flex items-center justify-center mx-auto mb-3 sm:mb-4 group-hover:bg-cyan-600 transition">
                    <span class="text-xl sm:text-2xl font-extrabold text-cyan-700 group-hover:text-white transition">1</span>
                </div>
                <h3 class="font-bold text-sm sm:text-base text-slate-900">Daftar Akun</h3>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Buat akun sebagai Freelancer atau Perusahaan</p>
            </div>
            <div class="relative bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 text-center hover:shadow-xl transition group">
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-teal-100 rounded-2xl flex items-center justify-center mx-auto mb-3 sm:mb-4 group-hover:bg-teal-600 transition">
                    <span class="text-xl sm:text-2xl font-extrabold text-teal-700 group-hover:text-white transition">2</span>
                </div>
                <h3 class="font-bold text-sm sm:text-base text-slate-900">Temukan / Publikasikan</h3>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Cari proyek atau publikasikan proyek Anda</p>
            </div>
            <div class="relative bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 text-center hover:shadow-xl transition group">
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-3 sm:mb-4 group-hover:bg-blue-600 transition">
                    <span class="text-xl sm:text-2xl font-extrabold text-blue-700 group-hover:text-white transition">3</span>
                </div>
                <h3 class="font-bold text-sm sm:text-base text-slate-900">Bekerja Sama</h3>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Pilih freelancer atau kirim penawaran terbaik</p>
            </div>
            <div class="relative bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 text-center hover:shadow-xl transition group">
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-3 sm:mb-4 group-hover:bg-emerald-600 transition">
                    <span class="text-xl sm:text-2xl font-extrabold text-emerald-700 group-hover:text-white transition">4</span>
                </div>
                <h3 class="font-bold text-sm sm:text-base text-slate-900">Selesaikan Proyek</h3>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Selesaikan proyek dan berikan review</p>
            </div>
        </div>
    </section>

    <!-- ============================================================ -->
    <!-- 9. FINAL CTA -->
    <!-- ============================================================ -->
    <section class="relative overflow-hidden py-16 sm:py-24">
        <div class="absolute inset-0 bg-gradient-to-r from-cyan-600 via-teal-600 to-emerald-600"></div>
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-64 h-64 bg-white rounded-full -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full translate-x-1/3 translate-y-1/3"></div>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-tight">
                Siap Memulai Perjalanan Anda?
            </h2>
            <p class="text-cyan-100 mt-4 max-w-xl mx-auto text-base sm:text-lg">
                Bergabunglah dengan The Archipelago Nexus dan temukan peluang tak terbatas untuk berkembang.
            </p>
            <div class="flex flex-wrap justify-center gap-3 sm:gap-4 mt-8">
                <a href="{{ route('register') }}" 
                   class="inline-flex items-center gap-2 px-8 py-3.5 bg-white hover:bg-slate-100 text-slate-900 font-bold rounded-xl shadow-xl hover:shadow-2xl transition transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-user-plus"></i>
                    Daftar Gratis
                </a>
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center gap-2 px-8 py-3.5 bg-white/20 hover:bg-white/30 text-white font-semibold rounded-xl border border-white/40 transition transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                    Masuk
                </a>
            </div>
        </div>
    </section>

    <!-- ============================================================ -->
    <!-- 10. FOOTER -->
    <!-- ============================================================ -->
    <footer class="bg-white border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-12">
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Brand -->
                <div class="sm:col-span-2 lg:col-span-1 space-y-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full overflow-hidden">
                            <img src="{{ asset('images/nexus.jpg') }}" alt="Logo" class="w-full h-full object-cover">
                        </div>
                        <span class="font-bold text-sm sm:text-base text-slate-900">The Archipelago Nexus</span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed max-w-xs">
                        Platform marketplace freelance terpercaya yang menghubungkan talenta berbakat dengan perusahaan untuk mewujudkan proyek digital.
                    </p>
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                        <span class="text-[10px] sm:text-xs text-slate-400">Online — 100+ aktif</span>
                    </div>
                </div>
                <!-- Untuk Freelancer -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Freelancer</h4>
                    <div class="space-y-2">
                        <a href="{{ route('register') }}" class="block text-xs sm:text-sm text-slate-500 hover:text-cyan-700 transition font-medium">Daftar Freelancer</a>
                        <a href="{{ route('login') }}" class="block text-xs sm:text-sm text-slate-500 hover:text-cyan-700 transition font-medium">Cari Proyek</a>
                        <a href="{{ route('register') }}" class="block text-xs sm:text-sm text-slate-500 hover:text-cyan-700 transition font-medium">Mulai Bekerja</a>
                    </div>
                </div>
                <!-- Untuk Perusahaan -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Perusahaan</h4>
                    <div class="space-y-2">
                        <a href="{{ route('register') }}" class="block text-xs sm:text-sm text-slate-500 hover:text-cyan-700 transition font-medium">Daftar Perusahaan</a>
                        <a href="{{ route('login') }}" class="block text-xs sm:text-sm text-slate-500 hover:text-cyan-700 transition font-medium">Publikasikan Proyek</a>
                        <a href="{{ route('login') }}" class="block text-xs sm:text-sm text-slate-500 hover:text-cyan-700 transition font-medium">Cari Talenta</a>
                    </div>
                </div>
                <!-- Dukungan -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Dukungan</h4>
                    <div class="space-y-2">
                        <a href="{{ route('login') }}" class="block text-xs sm:text-sm text-slate-500 hover:text-cyan-700 transition font-medium">Pusat Bantuan</a>
                        <a href="{{ route('login') }}" class="block text-xs sm:text-sm text-slate-500 hover:text-cyan-700 transition font-medium">Kebijakan Privasi</a>
                        <a href="{{ route('login') }}" class="block text-xs sm:text-sm text-slate-500 hover:text-cyan-700 transition font-medium">Syarat & Ketentuan</a>
                        <p class="text-xs sm:text-sm text-slate-400 font-medium flex items-center gap-1.5">
                            <i class="fa-regular fa-envelope"></i> support@archipelagonexus.id
                        </p>
                    </div>
                </div>
            </div>
            <!-- Bottom -->
            <div class="mt-8 sm:mt-10 pt-6 sm:pt-8 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs sm:text-sm text-slate-500">
                <p>&copy; 2026 <strong class="text-slate-700">The Archipelago Nexus</strong>. Hak Cipta Dilindungi.</p>
                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}" class="hover:text-cyan-700 transition font-medium">Kebijakan Privasi</a>
                    <a href="{{ route('login') }}" class="hover:text-cyan-700 transition font-medium">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu Toggle Script -->
    <script>
        document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>
</body>
</html>
