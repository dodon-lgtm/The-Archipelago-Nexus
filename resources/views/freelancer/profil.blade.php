<!DOCTYPE html>
<html lang="id" class="h-full bg-[#f6f9ff] dark:bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.theme-boot')
    <title>Profil Freelancer | ApexForge Labs</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif']
                    },
                    colors: {
                        brand: {
                            DEFAULT: '#2563EB',
                            dark: '#1D4ED8',
                            light: '#EFF6FF',
                        },
                        surface: '#F8FAFC'
                    }
                }
            }
        }
    </script>

    <style>
        /* =========================================
        ENTRANCE ANIMATIONS & EFFECTS
        ========================================= */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .reveal { opacity: 0; animation: fadeInUp .65s cubic-bezier(.16,1,.3,1) forwards; }
        .reveal-1 { animation-delay: .05s; }
        .reveal-2 { animation-delay: .1s; }
        .reveal-3 { animation-delay: .15s; }

        @keyframes meshGradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .animate-mesh { background-size: 200% 200%; animation: meshGradient 12s ease infinite; }

        /* =========================================
        CARD & HOVER EFFECTS
        ========================================= */
        .modern-row { transition: all .3s cubic-bezier(.16,1,.3,1); }
        .modern-row:hover { transform: translateY(-2px); box-shadow: 0 12px 30px -10px rgba(37,99,235,0.12); }
        .dark .modern-row:hover { box-shadow: 0 12px 30px -10px rgba(0,0,0,0.6); }

        /* SHIMMER BUTTON */
        .btn-shimmer { position: relative; overflow: hidden; isolation: isolate; }
        .btn-shimmer::after {
            content: '';
            position: absolute; top: 0; left: -100%;
            width: 60%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.3), transparent);
            transform: skewX(-20deg);
            transition: left .7s ease;
        }
        .btn-shimmer:hover::after { left: 150%; }

        /* CUSTOM SCROLLBAR */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .dark ::-webkit-scrollbar-track { background: #0f172a; }
        .dark ::-webkit-scrollbar-thumb { background: #334155; }
        .dark ::-webkit-scrollbar-thumb:hover { background: #475569; }
    </style>
</head>

<body class="bg-surface dark:bg-slate-950 text-slate-800 dark:text-slate-100 min-h-screen flex font-sans antialiased selection:bg-brand selection:text-white transition-colors duration-300">

    <div class="flex h-screen w-full overflow-hidden">

        {{-- Sidebar Freelancer --}}
        @include('navbar.navigasi')

        {{-- Main Content Container --}}
        <div class="flex-1 flex flex-col min-h-screen w-full overflow-hidden">

            {{-- Top Navbar --}}
            <div class="sticky top-0 z-40 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-100 dark:border-slate-800/80">
                @include('navbar.nav')
            </div>

            <main class="flex-1 w-full overflow-y-auto p-4 sm:p-6 lg:p-8 space-y-6">

                @php
                    $progress = profile_completion_percentage();
                    $missingFields = get_missing_profile_fields();
                    $isViewOnly = isset($isViewOnly) && $isViewOnly;
                    $skillsCount = $profile->skills ? count(explode(',', $profile->skills)) : 0;
                @endphp

                {{-- =================================================
                    FLASH MESSAGES
                ================================================= --}}
                @if(session('error'))
                    <div class="reveal reveal-1 flex items-start gap-3 px-5 py-4 bg-rose-50/95 dark:bg-rose-950/50 backdrop-blur-md border border-rose-200 dark:border-rose-800/60 text-rose-800 dark:text-rose-300 text-sm font-medium rounded-2xl shadow-lg shadow-rose-500/5">
                        <div class="w-9 h-9 rounded-xl bg-rose-500 text-white flex items-center justify-center shrink-0 shadow-sm shadow-rose-500/30">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-rose-600 dark:text-rose-400 uppercase tracking-wide">Perhatian</p>
                            <p class="mt-0.5">{{ session('error') }}</p>
                            @if(count($missingFields) > 0)
                                <ul class="mt-2 space-y-1 list-disc list-inside text-xs">
                                    @foreach($missingFields as $field)
                                        <li>{{ $field }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                @endif

                @if(session('success'))
                    <div class="reveal reveal-1 flex items-center gap-3 px-5 py-4 bg-emerald-50/95 dark:bg-emerald-950/50 backdrop-blur-md border border-emerald-200 dark:border-emerald-800/60 text-emerald-800 dark:text-emerald-300 text-sm font-medium rounded-2xl shadow-lg shadow-emerald-500/5">
                        <div class="w-9 h-9 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-sm shadow-emerald-500/30">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wide">Berhasil</p>
                            <span class="block truncate">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                {{-- Back Link --}}
                <div class="reveal reveal-1">
                    @if($isViewOnly)
                        <a href="{{ url()->previous() }}"
                           class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors group">
                            <i class="fa fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                            Kembali
                        </a>
                    @else
                        <a href="{{ route('freelancer.dashboard') }}"
                           class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors group">
                            <i class="fa fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                            Kembali ke Dashboard
                        </a>
                    @endif
                </div>

                {{-- =================================================
                    HERO HEADER
                ================================================= --}}
                <div class="reveal reveal-1 relative overflow-hidden rounded-3xl shadow-xl shadow-blue-600/10 border border-blue-500/20 dark:border-blue-500/30 w-full">
                    <div class="absolute inset-0 animate-mesh bg-gradient-to-r from-blue-700 via-brand to-blue-600 dark:from-slate-900 dark:via-blue-900 dark:to-slate-900"></div>
                    <div class="absolute -top-20 -right-20 w-72 h-72 bg-white/10 dark:bg-blue-500/10 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="absolute -bottom-24 -left-20 w-80 h-80 bg-blue-400/20 dark:bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="absolute inset-0 opacity-[0.08] dark:opacity-[0.12] pointer-events-none"
                        style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>

                    <div class="relative p-6 sm:p-8 lg:p-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                        <div class="flex items-center gap-4 sm:gap-6 min-w-0">
                            <div class="relative shrink-0">
                                @if($profile->photo)
                                    <img src="{{ asset('storage/'.$profile->photo) }}"
                                         alt="Foto profil {{ $user->name }}"
                                         class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl object-cover ring-4 ring-white/25 shadow-xl">
                                @else
                                    <img src="{{ asset('images/default-profile.png') }}"
                                         alt="Foto profil {{ $user->name }}"
                                         class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl object-cover bg-white ring-4 ring-white/25 shadow-xl"
                                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=2563eb&color=fff&size=112'">
                                @endif
                                <span class="absolute -bottom-1.5 -right-1.5 flex h-5 w-5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-5 w-5 bg-emerald-500 border-2 border-white/90 dark:border-slate-900"></span>
                                </span>
                            </div>

                            <div class="min-w-0 space-y-2">
                                <div class="inline-flex items-center gap-2 bg-white/10 dark:bg-white/5 backdrop-blur-md px-3.5 py-1.5 rounded-full text-white text-xs font-semibold ring-1 ring-white/20 shadow-inner">
                                    <i class="fa-solid fa-circle-check text-xs text-emerald-300"></i>
                                    Verified Freelancer
                                </div>
                                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight truncate">
                                    {{ $user->name }}
                                </h1>
                                <div class="flex flex-wrap gap-x-4 gap-y-1.5 text-blue-100/90 dark:text-slate-300 text-xs sm:text-sm font-medium">
                                    <span class="inline-flex items-center gap-1.5">
                                        <i class="fa-solid fa-location-dot text-blue-200 dark:text-blue-300"></i>
                                        {{ $profile->location ?: 'Belum mengisi lokasi' }}
                                    </span>
                                    @if($user->phone)
                                        <span class="inline-flex items-center gap-1.5">
                                            <i class="fa-solid fa-phone text-blue-200 dark:text-blue-300"></i>
                                            {{ $user->phone }}
                                        </span>
                                    @endif
                                    <span class="inline-flex items-center gap-1.5">
                                        <i class="fa-regular fa-calendar-days text-blue-200 dark:text-blue-300"></i>
                                        Bergabung sejak {{ $user->created_at->translatedFormat('d F Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 shrink-0">
                            @if($isViewOnly)
                                @if(Auth::check() && Auth::user()->role === 'company' && isset($user) && (int)$user->id !== (int)Auth::id())
                                    <a href="{{ route('company.reports.create', ['reported_user_id' => $user->id]) }}"
                                       class="btn-shimmer inline-flex items-center gap-2 bg-gradient-to-r from-red-600 to-rose-500 text-white px-5 py-3 rounded-2xl text-sm font-bold shadow-lg shadow-red-900/30 hover:shadow-red-900/40 hover:-translate-y-0.5 transition">
                                        <i class="fa-solid fa-flag text-xs"></i>
                                        Laporkan Freelancer
                                    </a>
                                @else
                                    <span class="inline-flex items-center gap-2 bg-white/10 dark:bg-white/5 backdrop-blur-md px-5 py-3 rounded-2xl text-white text-sm font-bold ring-1 ring-white/20 shadow-inner">
                                        <i class="fa-solid fa-eye text-xs text-blue-200 dark:text-blue-300"></i>
                                        Mode Lihat Profil
                                    </span>
                                @endif
                            @else
                                <a href="{{ route('freelancer.profile.edit') }}"
                                   class="btn-shimmer inline-flex items-center gap-2 bg-white dark:bg-slate-900 text-brand dark:text-blue-400 hover:bg-[#f6f9ff] dark:hover:bg-slate-800 border border-transparent dark:border-slate-800 px-5 py-3 rounded-2xl text-sm font-bold shadow-lg shadow-black/5 transition">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    <span>Edit Profil</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- =================================================
                    STAT METRICS
                ================================================= --}}
                <div class="reveal reveal-2 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-5">

                    {{-- Card: Rating Performa --}}
                    <div class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl shadow-sm p-4 sm:p-5 transition-colors duration-300">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-xl bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-star text-amber-600 dark:text-amber-300 text-lg"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs text-slate-400 dark:text-slate-400 font-bold uppercase tracking-wider">Rating Performa</p>
                                <h3 class="text-lg sm:text-xl font-black text-amber-500 dark:text-amber-300 tracking-tight leading-tight">
                                    {{ number_format($averageRating ?? 0, 1) }}
                                    <span class="text-xs font-semibold text-slate-400">/5.0</span>
                                </h3>
                            </div>
                        </div>
                    </div>

                    {{-- Card: Total Ulasan --}}
                    <div class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl shadow-sm p-4 sm:p-5 transition-colors duration-300">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-xl bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-comments text-blue-600 dark:text-blue-300 text-lg"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs text-slate-400 dark:text-slate-400 font-bold uppercase tracking-wider">Total Ulasan</p>
                                <h3 class="text-lg sm:text-xl font-black text-blue-600 dark:text-blue-300 tracking-tight leading-tight">
                                    {{ $totalReview ?? 0 }}
                                    <span class="text-xs font-semibold text-slate-400">Klien</span>
                                </h3>
                            </div>
                        </div>
                    </div>

                    {{-- Card: Keahlian --}}
                    <div class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl shadow-sm p-4 sm:p-5 transition-colors duration-300">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-xl bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-code text-indigo-600 dark:text-indigo-300 text-lg"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs text-slate-400 dark:text-slate-400 font-bold uppercase tracking-wider">Keahlian</p>
                                <h3 class="text-lg sm:text-xl font-black text-indigo-600 dark:text-indigo-300 tracking-tight leading-tight">
                                    {{ $skillsCount }}
                                    <span class="text-xs font-semibold text-slate-400">Skill</span>
                                </h3>
                            </div>
                        </div>
                    </div>

                    {{-- Card: Status Akun --}}
                    <div class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl shadow-sm p-4 sm:p-5 transition-colors duration-300">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-300 text-lg"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs text-slate-400 dark:text-slate-400 font-bold uppercase tracking-wider">Status Akun</p>
                                <h3 class="text-lg sm:text-xl font-black text-emerald-600 dark:text-emerald-300 tracking-tight leading-tight">Aktif</h3>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- =================================================
                    TENTANG SAYA & KEAHLIAN
                ================================================= --}}
                <div class="reveal reveal-2 grid grid-cols-1 lg:grid-cols-3 gap-5">

                    {{-- Tentang Saya + Informasi Kontak --}}
                    <div class="lg:col-span-2 bg-white dark:bg-slate-900 border border-blue-100/80 dark:border-slate-800 rounded-2xl shadow-sm p-5 sm:p-6 transition-colors duration-300">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-user-pen text-blue-600 dark:text-blue-400"></i>
                            Tentang Saya
                        </h3>

                        @if($profile->bio)
                            <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">{{ $profile->bio }}</p>
                        @else
                            <div class="border border-dashed border-blue-100 dark:border-slate-700 rounded-xl p-5 text-center text-slate-400 dark:text-slate-400 text-xs font-medium">
                                <i class="fa-regular fa-pen-to-square text-lg mb-1 block text-slate-300 dark:text-slate-600"></i>
                                Belum ada deskripsi profil yang ditambahkan.
                            </div>
                        @endif

                        <div class="mt-5 pt-5 border-t border-blue-50 dark:border-slate-800">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400 mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-id-card text-blue-600 dark:text-blue-400"></i>
                                Informasi Kontak
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                                <div>
                                    <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">Nama Lengkap</span>
                                    <p class="text-sm font-bold text-slate-800 dark:text-white">{{ $user->name }}</p>
                                </div>
                                <div>
                                    <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">Email Utama</span>
                                    <p class="text-sm font-bold text-slate-800 dark:text-white break-all">{{ $user->email }}</p>
                                </div>
                                <div>
                                    <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">Nomor HP / WhatsApp</span>
                                    <p class="text-sm font-bold text-slate-800 dark:text-white">{{ $user->phone ?: '-' }}</p>
                                </div>
                                <div>
                                    <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">Lokasi Domisili</span>
                                    <p class="text-sm font-bold text-slate-800 dark:text-white">{{ $profile->location ?: '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Keahlian --}}
                    <div class="bg-white dark:bg-slate-900 border border-blue-100/80 dark:border-slate-800 rounded-2xl shadow-sm p-5 sm:p-6 transition-colors duration-300">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-code text-blue-600 dark:text-blue-400"></i>
                            Keahlian
                        </h3>

                        @if($profile->skills)
                            <div class="flex flex-wrap gap-2">
                                @foreach(explode(',', $profile->skills) as $skill)
                                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-50/80 dark:bg-slate-800/80 border border-blue-100 dark:border-slate-800 text-blue-700 dark:text-blue-400 text-xs font-semibold rounded-xl">
                                        <i class="fa-solid fa-code text-[10px] text-blue-400 dark:text-slate-400"></i>
                                        {{ trim($skill) }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <div class="border border-dashed border-blue-100 dark:border-slate-700 rounded-xl p-5 text-center text-slate-400 dark:text-slate-400 text-xs font-medium">
                                <i class="fa-regular fa-lightbulb text-lg mb-1 block text-slate-300 dark:text-slate-600"></i>
                                Belum menambahkan skill/keahlian.
                            </div>
                        @endif
                    </div>

                </div>

                {{-- =================================================
                    PORTOFOLIO & CV
                ================================================= --}}
                <div class="reveal reveal-3 grid grid-cols-1 lg:grid-cols-2 gap-5">

                    {{-- Portofolio --}}
                    <div class="bg-white dark:bg-slate-900 border border-blue-100/80 dark:border-slate-800 rounded-2xl shadow-sm p-5 sm:p-6 transition-colors duration-300 flex flex-col">
                        <div class="flex-1">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400 mb-2 flex items-center gap-2">
                                <i class="fa-solid fa-folder-open text-blue-600 dark:text-blue-400"></i>
                                Portofolio
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Tampilkan hasil karya terbaik Anda kepada klien.</p>
                        </div>
                        <div class="mt-4">
                            @if($profile->portfolio_link)
                                <a href="{{ $profile->portfolio_link }}" target="_blank"
                                   class="btn-shimmer w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-xl text-xs font-bold transition shadow-sm">
                                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                    Buka Link Portofolio
                                </a>
                            @else
                                <div class="border border-dashed border-blue-100 dark:border-slate-700 rounded-xl p-4 text-center text-slate-400 dark:text-slate-400 text-xs font-medium">
                                    <i class="fa-solid fa-link text-lg mb-1 block text-slate-300 dark:text-slate-600"></i>
                                    Tautan portofolio belum diatur.
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- CV --}}
                    <div class="bg-white dark:bg-slate-900 border border-blue-100/80 dark:border-slate-800 rounded-2xl shadow-sm p-5 sm:p-6 transition-colors duration-300 flex flex-col">
                        <div class="flex-1">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400 mb-2 flex items-center gap-2">
                                <i class="fa-solid fa-file-arrow-down text-blue-600 dark:text-blue-400"></i>
                                Curriculum Vitae (CV)
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Unduh dokumen CV terbaru Anda untuk keperluan review.</p>
                        </div>
                        <div class="mt-4">
                            @if($profile->cv)
                                <a href="{{ asset('storage/'.$profile->cv) }}" target="_blank"
                                   class="btn-shimmer w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-xl text-xs font-bold transition shadow-sm">
                                    <i class="fa-solid fa-download text-[10px]"></i>
                                    Unduh Berkas CV
                                </a>
                            @else
                                <div class="border border-dashed border-blue-100 dark:border-slate-700 rounded-xl p-4 text-center text-slate-400 dark:text-slate-400 text-xs font-medium">
                                    <i class="fa-solid fa-file-lines text-lg mb-1 block text-slate-300 dark:text-slate-600"></i>
                                    Belum ada file CV yang diunggah.
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                {{-- =================================================
                    ULASAN & RATING
                ================================================= --}}
                <div class="reveal reveal-3 bg-white dark:bg-slate-900 border border-blue-100/80 dark:border-slate-800 rounded-2xl shadow-sm p-5 sm:p-6 transition-colors duration-300">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-5 pb-5 border-b border-blue-50 dark:border-slate-800">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400 flex items-center gap-2">
                            <i class="fa-solid fa-star text-amber-400"></i>
                            Ulasan &amp; Rating dari Perusahaan
                        </h3>
                        <div class="inline-flex items-center gap-3 bg-blue-50/70 dark:bg-slate-800 px-4 py-2 rounded-full border border-blue-100 dark:border-slate-700">
                            <span class="font-black text-slate-900 dark:text-white text-xl">{{ number_format($averageRating ?? 0, 1) }}</span>
                            <div class="text-amber-400 text-xs">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fa-{{ $i <= round($averageRating ?? 0) ? 'solid' : 'regular' }} fa-star"></i>
                                @endfor
                            </div>
                            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">({{ $totalReview ?? 0 }} Ulasan)</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($reviews ?? [] as $review)
                            <div class="modern-row bg-slate-50/80 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 rounded-2xl p-4 sm:p-5">
                                <div class="flex items-start justify-between gap-2 mb-2">
                                    <h4 class="font-bold text-sm text-slate-800 dark:text-white truncate">{{ $review->project->project_name ?? 'Project Selesai' }}</h4>
                                    <small class="text-[11px] text-slate-400 dark:text-slate-400 font-semibold shrink-0">{{ $review->created_at->translatedFormat('d M Y') }}</small>
                                </div>
                                <div class="flex items-center gap-1 text-amber-400 text-xs mb-2.5">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star"></i>
                                    @endfor
                                    <span class="text-slate-400 dark:text-slate-400 ms-2 text-[11px]">
                                        oleh <strong class="text-slate-600 dark:text-slate-300">{{ $review->client->name ?? 'Perusahaan' }}</strong>
                                    </span>
                                </div>
                                @if ($review->review)
                                    <p class="text-xs text-slate-600 dark:text-slate-300 italic leading-relaxed bg-white dark:bg-slate-900 p-3 rounded-xl border border-slate-100 dark:border-slate-800">"{{ $review->review }}"</p>
                                @endif
                            </div>
                        @empty
                            <div class="md:col-span-2 text-center py-10 text-slate-400 dark:text-slate-400">
                                <i class="fa-regular fa-face-smile text-3xl text-slate-300 dark:text-slate-600 mb-2"></i>
                                <p class="text-xs">Belum ada ulasan atau rating yang diterima dari perusahaan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- =================================================
                    PROGRESS KELENGKAPAN PROFIL
                ================================================= --}}
                @if(!$isViewOnly)
                    <div class="reveal reveal-3 bg-white dark:bg-slate-900 border border-blue-100/80 dark:border-slate-800 rounded-2xl shadow-sm p-5 sm:p-6 transition-colors duration-300">
                        <div class="flex items-center justify-between gap-4 mb-4">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400 flex items-center gap-2">
                                <i class="fa-solid fa-chart-line text-blue-600 dark:text-blue-400"></i>
                                Progress Kelengkapan Profil
                            </h3>
                            <span class="text-lg font-black text-brand dark:text-blue-400">{{ $progress }}%</span>
                        </div>

                        <div class="h-2.5 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden mb-5">
                            <div class="h-full bg-gradient-to-r from-blue-600 to-sky-400 rounded-full transition-all duration-500"
                                 style="width: {{ $progress }}%"></div>
                        </div>

                        @php
                            $checks = [
                                ['label' => 'Nama Lengkap',  'done' => (bool) Auth::user()->name],
                                ['label' => 'Email',         'done' => (bool) Auth::user()->email],
                                ['label' => 'Nomor Telepon', 'done' => (bool) Auth::user()->phone],
                                ['label' => 'Lokasi',        'done' => (bool) $profile->location],
                                ['label' => 'Keahlian',      'done' => (bool) $profile->skills],
                                ['label' => 'Foto Profil',   'done' => (bool) $profile->photo],
                                ['label' => 'Tentang Saya',  'done' => (bool) $profile->bio],
                            ];
                        @endphp
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-2.5 text-xs font-semibold">
                            @foreach($checks as $check)
                                <div class="inline-flex items-center gap-1.5 {{ $check['done'] ? 'text-emerald-600 dark:text-emerald-300' : 'text-slate-400 dark:text-slate-400' }}">
                                    <i class="{{ $check['done'] ? 'fa-solid fa-circle-check' : 'fa-regular fa-circle' }}"></i>
                                    {{ $check['label'] }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Footer --}}
                @include('navbar.footer')

            </main>
        </div>
    </div>

</body>
</html>