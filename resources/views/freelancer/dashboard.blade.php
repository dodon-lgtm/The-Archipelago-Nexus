<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - The Archipelago Nexus</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
    </style>
</head>
<body class="bg-slate-50 text-slate-800">

<div class="flex h-screen overflow-hidden">

    @include('navbar.navigasi')

    <div class="flex-1 flex flex-col overflow-hidden">

        <div class="sticky top-0 z-40 bg-white border-b">
            @include('navbar.nav')
        </div>

        <main class="flex-1 overflow-y-auto p-8">

            {{-- Welcome --}}
            <div class="reveal reveal-1 relative overflow-hidden rounded-2xl mb-8 shadow-sm bg-gradient-to-br from-cyan-600 via-cyan-500 to-blue-600 p-8">
                <div class="blob absolute -top-16 -right-10 w-64 h-64 bg-white/10 rounded-full"></div>
                <div class="blob absolute -bottom-20 -left-10 w-72 h-72 bg-white/10 rounded-full" style="animation-delay:1.4s;"></div>
                <div class="absolute inset-0 opacity-[.08]" style="background-image: radial-gradient(rgba(255,255,255,.7) 1.5px, transparent 1.5px); background-size: 18px 18px;"></div>

                <div class="relative">
                    <h2 class="text-3xl font-black text-white flex items-center gap-2">
                        Selamat Datang, <span class="text-white/95 underline decoration-white/30 decoration-4 underline-offset-4">{{ auth()->user()->name ?? 'User' }}</span>
                        <span class="wave-hand text-3xl">👋</span>
                    </h2>
                    <p class="text-cyan-50/90 mt-2 max-w-lg">Temukan pekerjaan freelance terbaik dan mulai karirmu sekarang.</p>
                </div>
            </div>

            {{-- Statistik --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-10">

                {{-- Card 1: Proyek Baru --}}
                <div class="reveal reveal-2 stat-card bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="stat-icon w-14 h-14 rounded-xl bg-gradient-to-br from-cyan-100 to-cyan-50 flex items-center justify-center shadow-inner">
                            <i class="fa-solid fa-folder-plus text-cyan-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-wide">Proyek Baru</p>
                            <h3 class="text-2xl font-black">{{ $projects->count() }}</h3>
                        </div>
                    </div>
                    <a href="{{ route('freelancer.proyek') }}" class="btn-shimmer block text-center text-xs font-bold text-cyan-700 py-2.5 rounded-lg bg-cyan-50 hover:bg-cyan-600 hover:text-white transition-colors duration-300">
                        Lihat Semua
                    </a>
                </div>

                {{-- Card 2: Lamaran Saya --}}
                <div class="reveal reveal-3 stat-card bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="stat-icon w-14 h-14 rounded-xl bg-gradient-to-br from-blue-100 to-blue-50 flex items-center justify-center shadow-inner">
                            <i class="fa-solid fa-paper-plane text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-wide">Lamaran Saya</p>
                            <h3 class="text-2xl font-black">{{ $lamaranCount }}</h3>
                        </div>
                    </div>
                    <a href="{{ route('freelancer.lamaran') }}" class="btn-shimmer block text-center text-xs font-bold text-blue-700 py-2.5 rounded-lg bg-blue-50 hover:bg-blue-600 hover:text-white transition-colors duration-300">
                        Lihat Semua
                    </a>
                </div>

                {{-- Card 3: Tersimpan --}}
                <div class="reveal reveal-4 stat-card bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="stat-icon w-14 h-14 rounded-xl bg-gradient-to-br from-purple-100 to-purple-50 flex items-center justify-center shadow-inner">
                            <i class="fa-solid fa-heart text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-wide">Tersimpan</p>
                            <h3 class="text-2xl font-black">{{ $savedCount }}</h3>
                        </div>
                    </div>
                    <a href="{{ route('freelancer.saved-projects.index') }}" class="btn-shimmer block text-center text-xs font-bold text-purple-700 py-2.5 rounded-lg bg-purple-50 hover:bg-purple-600 hover:text-white transition-colors duration-300">
                        Lihat Semua
                    </a>
                </div>

                {{-- Card 4: Pesan Baru --}}
                <div class="reveal reveal-5 stat-card bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="stat-icon w-14 h-14 rounded-xl bg-gradient-to-br from-amber-100 to-amber-50 flex items-center justify-center shadow-inner">
                            <i class="fa-solid fa-comment text-amber-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-wide">Pesan Baru</p>
                            <h3 class="text-2xl font-black">0</h3>
                        </div>
                    </div>
                    <a href="/freelancer/workspaces" class="btn-shimmer block text-center text-xs font-bold text-amber-700 py-2.5 rounded-lg bg-amber-50 hover:bg-amber-600 hover:text-white transition-colors duration-300">
                        Lihat Pesan
                    </a>
                </div>

            </div>

            {{-- GRID UTAMA: KIRI (Proyek) & KANAN (Lamaran) --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">

                {{-- KIRI: Rekomendasi Pekerjaan (2 Kolom) --}}
                <div class="reveal reveal-6 lg:col-span-2 space-y-6">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-black flex items-center gap-3">
                            <span class="section-accent h-6"></span>
                            Rekomendasi Pekerjaan
                        </h2>
                        <a href="{{ route('freelancer.projects.index') }}" class="text-cyan-600 font-semibold text-sm hover:text-cyan-700 transition flex items-center gap-1 group">
                            Lihat Semua <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>

                    <div class="space-y-4">
                        @forelse($projects as $project)
                            <div class="job-card bg-white border border-slate-200 rounded-2xl p-4 flex items-center justify-between shadow-sm">
                                <div class="flex items-center gap-4 min-w-0">
                                    <div class="job-thumb w-16 h-16 rounded-xl overflow-hidden shrink-0 bg-slate-100">
                                        <img src="{{ $project->image ? asset('storage/'.$project->image) : asset('images/no-image.png') }}" class="w-16 h-16 object-cover">
                                    </div>
                                    <div class="min-w-0">
                                        <h2 class="text-sm font-bold truncate">{{ $project->project_name }}</h2>
                                        <p class="text-[11px] text-slate-500 mt-0.5 inline-flex items-center gap-1">
                                            <i class="fa-solid fa-tag text-[9px] text-cyan-400"></i>
                                            {{ $project->category->name ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right shrink-0 pl-3">
                                    <p class="font-bold text-cyan-600 text-xs">Rp {{ number_format($project->budget,0,',','.') }}</p>
                                    <a href="{{ route('freelancer.projects.show',$project) }}" class="text-[10px] font-semibold bg-slate-100 hover:bg-cyan-600 hover:text-white text-slate-600 px-3 py-1.5 rounded-lg mt-2 inline-block transition-colors duration-300">Detail</a>
                                </div>
                            </div>
                        @empty
                            <div class="bg-white rounded-2xl p-8 text-center border border-dashed border-slate-200">
                                <i class="fa-regular fa-folder-open text-3xl text-slate-300 mb-2"></i>
                                <p class="text-sm text-slate-400">Belum ada proyek.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- KANAN: Lamaran Terbaru --}}
                <div class="reveal reveal-6 bg-white rounded-2xl shadow-sm border border-slate-100 p-5 h-fit">

                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-black flex items-center gap-3">
                            <span class="section-accent h-6"></span>
                            Lamaran Terbaru
                        </h2>

                        <a href="{{ route('freelancer.lamaran') }}"
                           class="text-cyan-600 font-semibold text-sm hover:text-cyan-700 transition">
                            Lihat Semua
                        </a>
                    </div>

                    <div class="space-y-3">

                        @forelse($latestApplications as $app)

                        <div class="relative pl-4 border-l-2 border-slate-100">
                            <span class="dot-pulse absolute -left-[7px] top-4 w-3 h-3 rounded-full
                                {{ $app->status == 'Menunggu' ? 'bg-amber-400' : ($app->status == 'Diterima' ? 'bg-emerald-500' : 'bg-red-400') }}"></span>

                            <div class="border border-slate-200 rounded-xl p-3 hover:shadow-md hover:border-cyan-200 transition-all duration-300 ml-2">

                                <div class="flex justify-between gap-2">

                                    <div class="min-w-0">

                                        <h3 class="font-semibold text-sm truncate">
                                            {{ $app->project->project_name }}
                                        </h3>

                                        <p class="text-xs text-gray-500 mt-0.5">
                                            Rp {{ number_format($app->harga_penawaran,0,',','.') }}
                                        </p>

                                        <p class="text-xs text-gray-400">
                                            Estimasi {{ $app->estimasi_hari }} Hari
                                        </p>

                                    </div>

                                    <div class="text-right shrink-0">

                                        @if($app->status=='Menunggu')
                                            <span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">
                                                Menunggu
                                            </span>
                                        @elseif($app->status=='Diterima')
                                            <span class="px-2 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                                Diterima
                                            </span>
                                        @else
                                            <span class="px-2 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                                                Ditolak
                                            </span>
                                        @endif

                                        <div class="text-[11px] text-gray-400 mt-2">
                                            {{ $app->created_at->format('d M Y') }}
                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>

                        @empty

                        <div class="text-center py-10 text-gray-400">
                            <i class="fa-regular fa-paper-plane text-3xl text-slate-300 mb-2"></i>
                            <p>Belum ada lamaran.</p>
                        </div>

                        @endforelse

                    </div>

                </div>

            </div>

            @include('navbar.footer')

        </main>
    </div>
</div>

</body>
</html>