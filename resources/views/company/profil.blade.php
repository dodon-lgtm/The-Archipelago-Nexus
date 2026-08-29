<!DOCTYPE html>
<html lang="id" class="h-full bg-[#f6f9ff] dark:bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.theme-boot')
    <title>Profil Perusahaan | ApexForge Labs</title>

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

        {{-- Sidebar Perusahaan --}}
        @include('navbar.navigasi')

        {{-- Main Content Container --}}
        <div class="flex-1 flex flex-col min-h-screen w-full overflow-hidden">

            {{-- Top Navbar --}}
            <div class="sticky top-0 z-40 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-100 dark:border-slate-800/80">
                @include('navbar.nav')
            </div>

            <main class="flex-1 w-full overflow-y-auto p-4 sm:p-6 lg:p-8 space-y-6">

                @php
                    // Inisialisasi variabel internal tanpa menggunakan external helper function
                    $user = Auth::user();
                    $profileData = $profile ?? ($companyProfile ?? null);

                    $missingFields = [];
                    if (!$user->name) $missingFields[] = 'Nama Lengkap';
                    if (!$user->email) $missingFields[] = 'Email';
                    if (!($user->phone ?? null) && !($profileData->phone ?? null)) $missingFields[] = 'Nomor Telepon';
                    if (!($profileData->location ?? null)) $missingFields[] = 'Lokasi';
                    if (!($profileData->company_name ?? null)) $missingFields[] = 'Nama Perusahaan';

                    $totalFieldsCount = 5;
                    $filledFieldsCount = $totalFieldsCount - count($missingFields);
                    $completionPercentage = round(($filledFieldsCount / $totalFieldsCount) * 100);
                    $isComplete = $completionPercentage >= 80;
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
                    <a href="{{ route('company.dashboard') }}"
                       class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors group">
                        <i class="fa fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                        Kembali ke Dashboard
                    </a>
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
                                @if(isset($profileData->company_logo) && $profileData->company_logo)
                                    <img src="{{ asset('storage/'.$profileData->company_logo) }}"
                                         alt="Logo Perusahaan"
                                         class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl object-cover bg-white ring-4 ring-white/25 shadow-xl">
                                @else
                                    <img src="{{ asset('images/company.png') }}"
                                         alt="Logo Perusahaan"
                                         class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl object-cover bg-white ring-4 ring-white/25 shadow-xl"
                                         onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($profileData->company_name ?? 'Company') }}&background=2563eb&color=fff&size=112';">
                                @endif
                                <span class="absolute -bottom-1.5 -right-1.5 flex h-5 w-5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-5 w-5 bg-emerald-500 border-2 border-white/90 dark:border-slate-900"></span>
                                </span>
                            </div>

                            <div class="min-w-0 space-y-2">
                                <div class="inline-flex items-center gap-2 bg-white/10 dark:bg-white/5 backdrop-blur-md px-3.5 py-1.5 rounded-full text-white text-xs font-semibold ring-1 ring-white/20 shadow-inner">
                                    <i class="fa-solid fa-circle-check text-xs text-emerald-300"></i>
                                    Client Terverifikasi
                                </div>
                                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight truncate">
                                    {{ $profileData->company_name ?? 'Nama Perusahaan' }}
                                </h1>
                                <div class="flex flex-wrap gap-x-4 gap-y-1.5 text-blue-100/90 dark:text-slate-300 text-xs sm:text-sm font-medium">
                                    <span class="inline-flex items-center gap-1.5">
                                        <i class="fa-solid fa-location-dot text-blue-200 dark:text-blue-300"></i>
                                        {{ $profileData->location ?? 'Belum mengisi lokasi' }}
                                    </span>
                                    <span class="inline-flex items-center gap-1.5">
                                        <i class="fa-solid fa-envelope text-blue-200 dark:text-blue-300"></i>
                                        {{ Auth::user()->email }}
                                    </span>
                                    <span class="inline-flex items-center gap-1.5">
                                        <i class="fa-solid fa-phone text-blue-200 dark:text-blue-300"></i>
                                        {{ $profileData->phone ?? 'Belum mengisi nomor telepon' }}
                                    </span>
                                    <span class="inline-flex items-center gap-1.5">
                                        <i class="fa-regular fa-calendar-days text-blue-200 dark:text-blue-300"></i>
                                        Bergabung sejak {{ Auth::user()->created_at->translatedFormat('d F Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 shrink-0">
                            <span class="inline-flex items-center gap-2 bg-white/10 dark:bg-white/5 backdrop-blur-md px-4 py-3 rounded-2xl text-white text-sm font-bold ring-1 ring-white/20 shadow-inner">
                                <i class="fa-solid fa-briefcase text-xs text-blue-200 dark:text-blue-300"></i>
                                {{ $profileData->industry ?? 'Bidang Usaha' }}
                            </span>
                            <a href="{{ route('company.profile.edit') }}"
                               class="btn-shimmer inline-flex items-center gap-2 bg-white dark:bg-slate-900 text-brand dark:text-blue-400 hover:bg-[#f6f9ff] dark:hover:bg-slate-800 border border-transparent dark:border-slate-800 px-5 py-3 rounded-2xl text-sm font-bold shadow-lg shadow-black/5 transition">
                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                                <span>Edit Profil</span>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- =================================================
                    STAT METRICS
                ================================================= --}}
                <div class="reveal reveal-2 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-5">

                    {{-- Card: Project Dibuka --}}
                    <div class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl shadow-sm p-4 sm:p-5 transition-colors duration-300">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-xl bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-folder-open text-blue-600 dark:text-blue-300 text-lg"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs text-slate-400 dark:text-slate-400 font-bold uppercase tracking-wider">Project Dibuka</p>
                                <h3 class="text-lg sm:text-xl font-black text-slate-800 dark:text-white tracking-tight leading-tight">{{ $totalProjects ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>

                    {{-- Card: Project Selesai --}}
                    <div class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl shadow-sm p-4 sm:p-5 transition-colors duration-300">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-check-circle text-emerald-600 dark:text-emerald-300 text-lg"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs text-slate-400 dark:text-slate-400 font-bold uppercase tracking-wider">Project Selesai</p>
                                <h3 class="text-lg sm:text-xl font-black text-slate-800 dark:text-white tracking-tight leading-tight">{{ $completedProjects ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>

                    {{-- Card: Ketepatan Bayar --}}
                    <div class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl shadow-sm p-4 sm:p-5 transition-colors duration-300">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-xl bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-cash-stack text-amber-600 dark:text-amber-300 text-lg"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs text-slate-400 dark:text-slate-400 font-bold uppercase tracking-wider">Ketepatan Bayar</p>
                                <h3 class="text-lg sm:text-xl font-black text-slate-800 dark:text-white tracking-tight leading-tight">{{ $paymentRate ?? '100%' }}</h3>
                            </div>
                        </div>
                    </div>

                    {{-- Card: Keberhasilan --}}
                    <div class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl shadow-sm p-4 sm:p-5 transition-colors duration-300">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-xl bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-shield-check text-indigo-600 dark:text-indigo-300 text-lg"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs text-slate-400 dark:text-slate-400 font-bold uppercase tracking-wider">Keberhasilan</p>
                                <h3 class="text-lg sm:text-xl font-black text-slate-800 dark:text-white tracking-tight leading-tight">{{ $successRate ?? '0%' }}</h3>
                            </div>
                        </div>
                    </div>

                </div>


                {{-- =================================================
                    TENTANG PERUSAHAAN
                ================================================= --}}
                <div class="reveal reveal-2 bg-white dark:bg-slate-900 border border-blue-100/80 dark:border-slate-800 rounded-2xl shadow-sm p-5 sm:p-6 transition-colors duration-300">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-building text-blue-600 dark:text-blue-400"></i>
                        Tentang Perusahaan
                    </h3>
                    @if(isset($profileData->description) && $profileData->description)
                        <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">{{ $profileData->description }}</p>
                    @else
                        <div class="border border-dashed border-blue-100 dark:border-slate-700 rounded-xl p-5 text-center text-slate-400 dark:text-slate-400 text-xs font-medium">
                            <i class="fa-regular fa-note-sticky text-lg mb-1 block text-slate-300 dark:text-slate-600"></i>
                            Belum ada deskripsi perusahaan yang ditambahkan.
                        </div>
                    @endif
                </div>

                {{-- =================================================
                    INFORMASI DETAIL & WEBSITE
                ================================================= --}}
                <div class="reveal reveal-3 grid grid-cols-1 lg:grid-cols-2 gap-5">

                    {{-- Informasi Perusahaan --}}
                    <div class="bg-white dark:bg-slate-900 border border-blue-100/80 dark:border-slate-800 rounded-2xl shadow-sm p-5 sm:p-6 transition-colors duration-300">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-circle-info text-blue-600 dark:text-blue-400"></i>
                            Informasi Detail Perusahaan
                        </h3>
                        <dl class="divide-y divide-blue-50 dark:divide-slate-800">
                            <div class="flex items-start justify-between gap-3 py-3">
                                <dt class="text-xs font-semibold text-slate-500 dark:text-slate-400">Nama</dt>
                                <dd class="text-sm font-bold text-slate-800 dark:text-white text-right">{{ $profileData->company_name ?? '-' }}</dd>
                            </div>
                            <div class="flex items-start justify-between gap-3 py-3">
                                <dt class="text-xs font-semibold text-slate-500 dark:text-slate-400">Bidang</dt>
                                <dd class="text-sm font-bold text-slate-800 dark:text-white text-right">{{ $profileData->industry ?? '-' }}</dd>
                            </div>
                            <div class="flex items-start justify-between gap-3 py-3">
                                <dt class="text-xs font-semibold text-slate-500 dark:text-slate-400">Lokasi</dt>
                                <dd class="text-sm font-bold text-slate-800 dark:text-white text-right">{{ $profileData->location ?? '-' }}</dd>
                            </div>
                            <div class="flex items-start justify-between gap-3 py-3">
                                <dt class="text-xs font-semibold text-slate-500 dark:text-slate-400">Email</dt>
                                <dd class="text-sm font-bold text-slate-800 dark:text-white text-right break-all">{{ Auth::user()->email }}</dd>
                            </div>
                            <div class="flex items-start justify-between gap-3 py-3">
                                <dt class="text-xs font-semibold text-slate-500 dark:text-slate-400">Telepon</dt>
                                <dd class="text-sm font-bold text-slate-800 dark:text-white text-right">{{ $profileData->phone ?? '-' }}</dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Website & Atribut Tambahan --}}
                    <div class="bg-white dark:bg-slate-900 border border-blue-100/80 dark:border-slate-800 rounded-2xl shadow-sm p-5 sm:p-6 transition-colors duration-300">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-globe text-blue-600 dark:text-blue-400"></i>
                            Website &amp; Atribut
                        </h3>

                        @if(isset($profileData->website) && $profileData->website)
                            <a href="{{ \Illuminate\Support\Str::startsWith($profileData->website, ['http://', 'https://']) ? $profileData->website : 'https://' . $profileData->website }}" target="_blank"
                               class="btn-shimmer w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-xl text-xs font-bold transition shadow-sm mb-3">
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                Kunjungi Website Resmi
                            </a>
                            <div class="p-3 bg-blue-50/60 dark:bg-slate-800 rounded-xl text-slate-500 dark:text-slate-400 text-xs font-medium break-all border border-blue-100/80 dark:border-slate-700">
                                <i class="fa-solid fa-link text-blue-500 dark:text-blue-400 mr-1.5"></i>
                                {{ $profileData->website }}
                            </div>
                        @else
                            <div class="border border-dashed border-blue-100 dark:border-slate-700 rounded-xl p-4 text-center text-slate-400 dark:text-slate-400 text-xs font-medium mb-4">
                                <i class="fa-solid fa-globe text-lg mb-1 block text-slate-300 dark:text-slate-600"></i>
                                Website perusahaan belum ditambahkan.
                            </div>
                        @endif

                        <div class="mt-5 pt-5 border-t border-blue-50 dark:border-slate-800 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1.5">Bidang Usaha</span>
                                @if(isset($profileData->industry) && $profileData->industry)
                                    <span class="inline-flex items-center px-3 py-1.5 bg-blue-50/80 dark:bg-slate-800 border border-blue-100 dark:border-slate-800 text-blue-700 dark:text-blue-400 text-xs font-semibold rounded-full">
                                        <i class="fa-solid fa-briefcase text-[10px] mr-1.5"></i>
                                        {{ $profileData->industry }}
                                    </span>
                                @else
                                    <span class="text-xs italic text-slate-400 dark:text-slate-500">Belum diisi</span>
                                @endif
                            </div>
                            <div>
                                <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1.5">Tanggal Bergabung</span>
                                <p class="text-sm font-bold text-slate-800 dark:text-white">
                                    <i class="fa-regular fa-calendar text-blue-500 dark:text-blue-400 mr-1.5"></i>
                                    {{ Auth::user()->created_at->format('d M Y') }}
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- =================================================
                    PROGRESS KELENGKAPAN PROFIL
                ================================================= --}}
                <div class="reveal reveal-3 bg-white dark:bg-slate-900 border border-blue-100/80 dark:border-slate-800 rounded-2xl shadow-sm p-5 sm:p-6 transition-colors duration-300">
                    <div class="flex items-center justify-between gap-4 mb-4">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400 flex items-center gap-2">
                            <i class="fa-solid fa-chart-line text-blue-600 dark:text-blue-400"></i>
                            Progress Kelengkapan Profil
                        </h3>
                        <span class="text-lg font-black text-brand dark:text-blue-400">{{ $completionPercentage }}%</span>
                    </div>

                    <div class="h-2.5 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden mb-5">
                        <div class="h-full bg-gradient-to-r from-blue-600 to-sky-400 rounded-full transition-all duration-500"
                             style="width: {{ $completionPercentage }}%"></div>
                    </div>

                    @if($isComplete)
                        <div class="flex items-center gap-3 px-4 py-3 bg-emerald-50/90 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900 text-emerald-700 dark:text-emerald-300 text-sm font-medium rounded-xl mb-4">
                            <i class="fa-solid fa-circle-check text-emerald-500 dark:text-emerald-400"></i>
                            Profil Anda sudah lengkap. Anda dapat menggunakan semua fitur aplikasi.
                        </div>
                    @else
                        <div class="flex items-center gap-3 px-4 py-3 bg-amber-50/90 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900 text-amber-700 dark:text-amber-300 text-sm font-medium rounded-xl mb-4">
                            <i class="fa-solid fa-circle-exclamation text-amber-500 dark:text-amber-400"></i>
                            Lengkapi minimal 80% profil untuk membuat proyek, memilih freelancer, dan fitur lainnya.
                        </div>
                    @endif

                    @php
                        $fields = [
                            ['label' => 'Nama Lengkap', 'check' => Auth::user()->name],
                            ['label' => 'Email', 'check' => Auth::user()->email],
                            ['label' => 'Nomor Telepon', 'check' => Auth::user()->phone ?? ($profileData->phone ?? null)],
                            ['label' => 'Lokasi', 'check' => $profileData->location ?? null],
                            ['label' => 'Nama Perusahaan', 'check' => $profileData->company_name ?? null],
                        ];
                    @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-2.5 text-xs font-semibold">
                        @foreach($fields as $field)
                            <div class="inline-flex items-center gap-1.5 {{ $field['check'] ? 'text-emerald-600 dark:text-emerald-300' : 'text-slate-400 dark:text-slate-400' }}">
                                <i class="{{ $field['check'] ? 'fa-solid fa-circle-check' : 'fa-regular fa-circle' }}"></i>
                                {{ $field['label'] }}
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Footer --}}
                @include('navbar.footer')

            </main>
        </div>
    </div>

</body>
</html>