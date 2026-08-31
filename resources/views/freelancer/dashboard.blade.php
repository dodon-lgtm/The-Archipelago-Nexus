<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.theme-boot')
    <title>Dashboard - ApexForge Labs</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* ---- Entrance animations ---- */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .reveal {
            opacity: 0;
            /* DIBETULKAN: cuabic-bezier -> cubic-bezier */
            animation: fadeInUp .6s cubic-bezier(.22,1,.36,1) forwards;
        }
        .reveal-1 { animation-delay: .05s; }
        .reveal-2 { animation-delay: .12s; }
        .reveal-3 { animation-delay: .19s; }
        .reveal-4 { animation-delay: .26s; }
        .reveal-5 { animation-delay: .33s; }
        .reveal-6 { animation-delay: .40s; }

        /* ---- Blobs in hero ---- */
        @keyframes floatBlob {
            0%, 100% { transform: translate(0,0) scale(1); }
            50% { transform: translate(10px,-14px) scale(1.06); }
        }
        .blob { animation: floatBlob 7s ease-in-out infinite; }

        /* ---- Waving hand ---- */
        @keyframes wave {
            0%, 60%, 100% { transform: rotate(0deg); }
            10%, 30% { transform: rotate(14deg); }
            20% { transform: rotate(-8deg); }
        }
        .wave-hand { display: inline-block; transform-origin: 70% 70%; animation: wave 2.4s ease-in-out infinite; }

        /* ---- Stat card ---- */
        .stat-card {
            transition: transform .3s cubic-bezier(.22,1,.36,1), box-shadow .3s ease, border-color .3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 18px 34px -14px rgba(15,23,42,.18);
        }
        .stat-icon {
            transition: transform .35s cubic-bezier(.34,1.56,.64,1);
        }
        .stat-card:hover .stat-icon { transform: rotate(-8deg) scale(1.08); }

        /* ---- Shimmer button ---- */
        .btn-shimmer { position: relative; overflow: hidden; isolation: isolate; }
        .btn-shimmer::after {
            content: '';
            position: absolute; top: 0; left: -75%;
            width: 50%; height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255,255,255,.55), transparent);
            transform: skewX(-20deg);
            transition: left .6s ease;
        }
        .btn-shimmer:hover::after { left: 130%; }

        /* ---- Job card ---- */
        .job-card {
            transition: transform .3s cubic-bezier(.22,1,.36,1), box-shadow .3s ease, border-color .3s ease;
        }
        .job-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 30px -16px rgba(8,145,178,.28);
            border-color: rgba(8,145,178,.35);
        }
        .job-card .job-thumb img { transition: transform .5s ease; }
        .job-card:hover .job-thumb img { transform: scale(1.08); }

        /* ---- Timeline dot pulse ---- */
        @keyframes dotPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(8,145,178,.3); }
            50% { box-shadow: 0 0 0 6px rgba(8,145,178,0); }
        }
        .dot-pulse { animation: dotPulse 2s ease-in-out infinite; }

        /* ---- Section heading accent ---- */
        .section-accent {
            width: 5px;
            border-radius: 999px;
            background: linear-gradient(180deg, #06b6d4, #2563eb);
        }

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
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background:
                radial-gradient(circle at 10% -10%, rgba(56,189,248,.10), transparent 30%),
                radial-gradient(circle at 100% 0%, rgba(37,99,235,.08), transparent 28%),
                var(--af-page);
        }
        ::selection { background: rgba(37,99,235,.18); color: #0f172a; }
        ::-webkit-scrollbar { width: 7px; height: 7px; }
        ::-webkit-scrollbar-track { background: rgba(241,245,249,.7); }
        ::-webkit-scrollbar-thumb { background: rgba(37,99,235,.22); border-radius: 999px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(37,99,235,.38); }

        input, select, textarea {
            border-color: var(--af-border) !important;
            background: rgba(255,255,255,.92);
            transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
        }
        input:focus, select:focus, textarea:focus {
            border-color: rgba(37,99,235,.55) !important;
            box-shadow: 0 0 0 4px rgba(37,99,235,.09) !important;
            outline: none !important;
        }
        button, a, [role="button"] { transition: all .2s ease; }
        button:focus-visible, a:focus-visible, [role="button"]:focus-visible {
            outline: 2px solid rgba(37,99,235,.55);
            outline-offset: 2px;
        }
        table { border-collapse: separate; border-spacing: 0; }
        thead th {
            background: rgba(239,246,255,.72) !important;
            color: #334155;
            font-weight: 700;
        }
        tbody tr { transition: background .18s ease; }
        tbody tr:hover { background: rgba(239,246,255,.48); }
        [class*="bg-blue-600"] {
            box-shadow: 0 8px 22px -12px rgba(37,99,235,.72);
        }
        [class*="bg-blue-600"]:hover {
            box-shadow: 0 12px 28px -12px rgba(37,99,235,.78);
            transform: translateY(-1px);
        }
        @media (max-width:767px) {
            main { padding-left:1rem !important; padding-right:1rem !important; }
            table { min-width: 680px; }
            .overflow-x-auto { -webkit-overflow-scrolling: touch; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
                scroll-behavior: auto !important;
            }
        }
    </style>
</head>
<body class="bg-[#f6f9ff] dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-300">

<div class="flex h-screen overflow-hidden">

    @include('navbar.navigasi')

    <div class="flex-1 flex flex-col overflow-hidden">

        <div class="sticky top-0 z-40 bg-white dark:bg-slate-900 border-b border-blue-50 dark:border-slate-800 transition-colors duration-300">
            @include('navbar.nav')
        </div>

        <main class="flex-1 overflow-y-auto p-8">

            {{-- Welcome --}}
            <div class="reveal reveal-1 relative overflow-hidden rounded-2xl mb-8 shadow-sm bg-gradient-to-br from-blue-600 via-blue-500 to-blue-600 p-8">
                <div class="blob absolute -top-16 -right-10 w-64 h-64 bg-white/10 dark:bg-slate-800/20 rounded-full"></div>
                <div class="blob absolute -bottom-20 -left-10 w-72 h-72 bg-white/10 dark:bg-slate-800/20 rounded-full" style="animation-delay:1.4s;"></div>
                <div class="absolute inset-0 opacity-[.08]" style="background-image: radial-gradient(rgba(255,255,255,.7) 1.5px, transparent 1.5px); background-size: 18px 18px;"></div>

                <div class="relative">
                    <h2 class="text-3xl font-black text-white flex items-center gap-2">
                        Selamat Datang, <span class="text-white/95 underline decoration-white/30 decoration-4 underline-offset-4">{{ auth()->user()->name ?? 'User' }}</span>
                        <span class="wave-hand text-3xl">👋</span>
                    </h2>
                    <p class="text-blue-50/90 mt-2 max-w-lg">Temukan pekerjaan freelance terbaik dan mulai karirmu sekarang.</p>
                </div>
            </div>

            {{-- Statistik --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-10">

                {{-- Card 1: Proyek Baru --}}
                <div class="reveal reveal-2 stat-card bg-white dark:bg-slate-900 rounded-2xl border border-blue-50 dark:border-slate-800 p-5 shadow-sm transition-colors duration-300">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="stat-icon w-14 h-14 rounded-xl bg-gradient-to-br from-blue-100 to-blue-50 dark:from-slate-800 dark:to-slate-800 flex items-center justify-center shadow-inner">
                            <i class="fa-solid fa-folder-plus text-blue-600 dark:text-blue-400 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 dark:text-slate-400 font-bold uppercase tracking-wide">Proyek Baru</p>
                            <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ isset($projects) ? $projects->count() : 0 }}</h3>
                        </div>
                    </div>
                    <a href="{{ route('freelancer.proyek') }}" class="btn-shimmer block text-center text-xs font-bold text-blue-700 dark:text-slate-300 py-2.5 rounded-lg bg-blue-50 dark:bg-slate-800 hover:bg-blue-600 dark:hover:bg-slate-800 hover:text-white dark:hover:text-blue-400 transition-colors duration-300">
                        Lihat Semua
                    </a>
                </div>

                {{-- Card 2: Lamaran Saya --}}
                <div class="reveal reveal-3 stat-card bg-white dark:bg-slate-900 rounded-2xl border border-blue-50 dark:border-slate-800 p-5 shadow-sm transition-colors duration-300">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="stat-icon w-14 h-14 rounded-xl bg-gradient-to-br from-blue-100 to-blue-50 dark:from-slate-800 dark:to-slate-800 flex items-center justify-center shadow-inner">
                            <i class="fa-solid fa-paper-plane text-blue-600 dark:text-blue-400 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 dark:text-slate-400 font-bold uppercase tracking-wide">Lamaran Saya</p>
                            <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ $lamaranCount ?? 0 }}</h3>
                        </div>
                    </div>
                    <a href="{{ route('freelancer.lamaran') }}" class="btn-shimmer block text-center text-xs font-bold text-blue-700 dark:text-slate-300 py-2.5 rounded-lg bg-blue-50 dark:bg-slate-800 hover:bg-blue-600 dark:hover:bg-slate-800 hover:text-white dark:hover:text-blue-400 transition-colors duration-300">
                        Lihat Semua
                    </a>
                </div>

                {{-- Card 3: Tersimpan --}}
                <div class="reveal reveal-4 stat-card bg-white dark:bg-slate-900 rounded-2xl border border-blue-50 dark:border-slate-800 p-5 shadow-sm transition-colors duration-300">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="stat-icon w-14 h-14 rounded-xl bg-gradient-to-br from-purple-100 to-purple-50 dark:from-slate-800 dark:to-slate-800 flex items-center justify-center shadow-inner">
                            <i class="fa-solid fa-heart text-purple-600 dark:text-purple-400 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 dark:text-slate-400 font-bold uppercase tracking-wide">Tersimpan</p>
                            <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ $savedCount ?? 0 }}</h3>
                        </div>
                    </div>
                    <a href="{{ route('freelancer.saved-projects.index') }}" class="btn-shimmer block text-center text-xs font-bold text-purple-700 dark:text-purple-300 py-2.5 rounded-lg bg-purple-50 dark:bg-slate-800 hover:bg-purple-600 dark:hover:bg-slate-800 hover:text-white dark:hover:text-purple-400 transition-colors duration-300">
                        Lihat Semua
                    </a>
                </div>

                {{-- Card 4: Pesan Baru --}}
                <div class="reveal reveal-5 stat-card bg-white dark:bg-slate-900 rounded-2xl border border-blue-50 dark:border-slate-800 p-5 shadow-sm transition-colors duration-300">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="stat-icon relative w-14 h-14 rounded-xl bg-gradient-to-br from-amber-100 to-amber-50 dark:from-slate-800 dark:to-slate-800 flex items-center justify-center shadow-inner">
                            <i class="fa-solid fa-comment text-amber-600 dark:text-amber-400 text-xl"></i>
                            @if (($unreadMessagesCount ?? 0) > 0)
                                <span class="absolute -top-1 -right-1 flex h-4 w-4">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-4 w-4 bg-amber-500 border-2 border-white dark:border-slate-900"></span>
                                </span>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 dark:text-slate-400 font-bold uppercase tracking-wide">Pesan Baru</p>
                            <h3 class="text-2xl font-black text-slate-800 dark:text-white">{{ $unreadMessagesCount ?? 0 }}</h3>
                        </div>
                    </div>
                    <a href="{{ route('freelancer.workspaces.index') }}" class="btn-shimmer block text-center text-xs font-bold text-amber-700 dark:text-amber-300 py-2.5 rounded-lg bg-amber-50 dark:bg-slate-800 hover:bg-amber-600 dark:hover:bg-slate-800 hover:text-white dark:hover:text-amber-400 transition-colors duration-300">
                        Lihat Pesan
                    </a>
                </div>

            </div>

            {{-- GRID UTAMA: KIRI (Proyek) & KANAN (Lamaran) --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">

                {{-- KIRI: Pekerjaan Terbaru (2 Kolom) --}}
                <div class="reveal reveal-6 lg:col-span-2 space-y-6">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-black text-slate-800 dark:text-white flex items-center gap-3">
                            <span class="section-accent h-6"></span>
                            Pekerjaan Terbaru
                        </h2>
                        <a href="{{ route('freelancer.projects.index') }}" class="text-blue-600 dark:text-blue-400 font-semibold text-sm hover:text-blue-700 dark:hover:text-blue-400 transition flex items-center gap-1 group">
                            Lihat Semua <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>

                    <div class="space-y-4">
                        @forelse($projects ?? [] as $project)
                            <div class="job-card bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl p-4 flex items-center justify-between gap-4 shadow-sm transition-colors duration-300">
                                <div class="flex items-center gap-4 min-w-0">
                                    <div class="job-thumb w-16 h-16 rounded-xl overflow-hidden shrink-0 bg-blue-50 dark:bg-slate-800">
                                        <img src="{{ $project->image ? asset('storage/'.$project->image) : asset('images/no-image.png') }}" class="w-16 h-16 object-cover" alt="Thumb">
                                    </div>
                                    <div class="min-w-0">
                                        <h2 class="text-sm font-bold truncate text-slate-800 dark:text-white">{{ $project->project_name }}</h2>
                                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 inline-flex items-center gap-1">
                                            <i class="fa-solid fa-tag text-[9px] text-blue-400 dark:text-slate-500"></i>
                                            {{ $project->category->name ?? '-' }}
                                        </p>
                                        @if($project->owner && $project->owner->name)
                                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 inline-flex items-center gap-1">
                                                <i class="fa-regular fa-building text-[9px] text-blue-400 dark:text-slate-500"></i>
                                                {{ $project->owner->name }}
                                            </p>
                                        @endif
                                        @if($project->project_description)
                                            <p class="text-[11px] text-slate-500 dark:text-slate-400 line-clamp-1 mt-1">
                                                {{ \Illuminate\Support\Str::limit($project->project_description, 70) }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right shrink-0 pl-3">
                                    <p class="font-bold text-blue-600 dark:text-blue-400 text-xs">Rp {{ number_format($project->budget,0,',','.') }}</p>
                                    @if($project->deadline)
                                        <p class="text-[10px] text-slate-400 dark:text-slate-400 mt-1 inline-flex items-center gap-1">
                                            <i class="fa-regular fa-calendar-alt text-[9px]"></i>
                                            {{ \Carbon\Carbon::parse($project->deadline)->isoFormat('D MMM YYYY') }}
                                        </p>
                                    @endif
                                    <a href="{{ route('freelancer.projects.show',$project) }}" class="text-[10px] font-semibold bg-blue-50 dark:bg-slate-800 hover:bg-blue-600 dark:hover:bg-slate-800 hover:text-white dark:hover:text-blue-400 text-slate-600 dark:text-slate-300 px-3 py-1.5 rounded-lg mt-2 inline-block transition-colors duration-300">Lihat Detail</a>
                                </div>
                            </div>
                        @empty
                            <div class="bg-white dark:bg-slate-900 rounded-2xl p-8 text-center border border-dashed border-blue-100 dark:border-slate-800 transition-colors duration-300">
                                <i class="fa-regular fa-folder-open text-3xl text-slate-300 dark:text-slate-600 mb-2"></i>
                                <p class="text-sm text-slate-400 dark:text-slate-400">Belum ada proyek.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- KANAN: Lamaran Terbaru --}}
                <div class="reveal reveal-6 bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-blue-50 dark:border-slate-800 p-5 h-fit transition-colors duration-300">

                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-black text-slate-800 dark:text-white flex items-center gap-3">
                            <span class="section-accent h-6"></span>
                            Lamaran Terbaru
                        </h2>

                        <a href="{{ route('freelancer.lamaran') }}" class="text-blue-600 dark:text-blue-400 font-semibold text-sm hover:text-blue-700 dark:hover:text-blue-400 transition">
                            Lihat Semua
                        </a>
                    </div>

                    <div class="space-y-3">
                        @forelse($latestApplications ?? [] as $app)
                            <div class="relative pl-4 border-l-2 border-blue-50 dark:border-slate-800">
                                <span class="dot-pulse absolute -left-[7px] top-4 w-3 h-3 rounded-full
                                    {{ $app->status == 'Menunggu' ? 'bg-amber-400' : ($app->status == 'Diterima' ? 'bg-emerald-500' : 'bg-red-400') }}"></span>

                                <div class="border border-blue-100 dark:border-slate-800 rounded-xl p-3 hover:shadow-md hover:border-blue-200 dark:hover:border-slate-700 transition-all duration-300 ml-2">
                                    <div class="flex justify-between gap-2">
                                        <div class="min-w-0">
                                            <h3 class="font-semibold text-sm truncate text-slate-800 dark:text-white">
                                                {{ $app->project->project_name ?? 'Proyek' }}
                                            </h3>
                                            <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">
                                                Rp {{ number_format($app->harga_penawaran,0,',','.') }}
                                            </p>
                                            <p class="text-xs text-gray-400 dark:text-slate-400">
                                                Estimasi {{ $app->estimasi_hari }} Hari
                                            </p>
                                        </div>

                                        <div class="text-right shrink-0">
                                            @if($app->status == 'Menunggu')
                                                <span class="px-2 py-1 rounded-full bg-yellow-100 dark:bg-yellow-900/40 text-yellow-700 dark:text-yellow-300 text-xs font-semibold">
                                                    Menunggu
                                                </span>
                                            @elseif($app->status == 'Diterima')
                                                <span class="px-2 py-1 rounded-full bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300 text-xs font-semibold">
                                                    Diterima
                                                </span>
                                            @else
                                                <span class="px-2 py-1 rounded-full bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 text-xs font-semibold border border-red-100 dark:border-red-900">
                                                    Ditolak
                                                </span>
                                            @endif

                                            <div class="text-[11px] text-gray-400 dark:text-slate-400 mt-2">
                                                {{ optional($app->created_at)->format('d M Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10 text-gray-400 dark:text-slate-400">
                                <i class="fa-regular fa-paper-plane text-3xl text-slate-300 dark:text-slate-600 mb-2"></i>
                                <p>Belum ada lamaran.</p>
                            </div>
                        @endforelse
                    </div>

                </div>

            </div>

          

        </main>
    </div>
</div>

</body>
</html>