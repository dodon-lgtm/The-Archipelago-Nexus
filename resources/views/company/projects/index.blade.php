<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Proyek | ApexForge Labs</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes pulseGlow {
            0%, 100% { opacity: 0.35; transform: scale(1); }
            50%      { opacity: 0.6; transform: scale(1.06); }
        }

        /* Entrance kartu — TIDAK pernah mulai dari opacity 0 penuh,
           supaya tidak ada resiko elemen "hilang" saat baru dimuat */
        .reveal-item {
            opacity: 0.001;
            animation: fadeInUp 0.5s ease-out forwards;
        }

        .animate-pulse-glow { animation: pulseGlow 5s ease-in-out infinite; }

        /* Pattern titik header — tanpa filter, tanpa resiko render aneh */
        .bg-dot-pattern {
            background-image: radial-gradient(rgba(255,255,255,0.25) 1.5px, transparent 1.5px);
            background-size: 22px 22px;
        }

        /* Kartu proyek — hover jelas, kontras selalu terjaga */
        .project-card {
            background: #ffffff;
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        }
        .project-card:hover {
            border-color: #3b82f6;
            transform: translateY(-3px);
            box-shadow: 0 14px 28px -10px rgba(37, 99, 235, 0.25);
        }
        .project-card:focus-visible {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.4);
        }
    </style>
</head>

<body class="bg-slate-100 text-slate-800 antialiased selection:bg-blue-600 selection:text-white">

    <div class="min-h-screen flex">

        @include('navbar.navigasi')

        <div class="flex-1 min-w-0 flex flex-col justify-between">

            <div>
                @include('navbar.nav')

                <main class="px-4 sm:px-6 lg:px-10 py-8">
                    <div class="max-w-6xl mx-auto space-y-6">

                        {{-- =================================================
                            HEADER BANNER — biru solid, selalu tegas & jelas
                        ================================================ --}}
                        <section class="relative rounded-3xl overflow-hidden shadow-xl shadow-blue-900/20">
                            <div class="absolute inset-0 bg-blue-600"></div>
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-blue-600 to-indigo-700"></div>
                            <div class="absolute inset-0 bg-dot-pattern opacity-60"></div>
                            <div class="animate-pulse-glow absolute -right-10 -top-16 w-56 h-56 bg-cyan-300/30 rounded-full blur-3xl pointer-events-none"></div>
                            <div class="animate-pulse-glow absolute -left-10 -bottom-16 w-56 h-56 bg-indigo-300/30 rounded-full blur-3xl pointer-events-none" style="animation-delay: 2s;"></div>

                            <div class="relative flex flex-col sm:flex-row sm:items-center justify-between gap-6 p-6 sm:p-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl bg-white/20 border border-white/30 text-white flex items-center justify-center shadow-lg shrink-0">
                                        <i class="fa-solid fa-layer-group text-2xl"></i>
                                    </div>
                                    <div>
                                        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                                            Daftar Proyek
                                        </h1>
                                        <p class="text-sm text-blue-50 font-medium mt-1">
                                            Kelola portofolio proyek aktif dan temukan talenta freelancer terbaik Anda.
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    @if ($projects->count() > 0)
                                        <div class="flex items-center gap-3 px-4 py-2.5 rounded-2xl bg-white shadow-lg shadow-blue-950/20">
                                            <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                                                <i class="fa-solid fa-folder text-sm"></i>
                                            </div>
                                            <div class="text-right pr-0.5">
                                                <span class="block text-xl font-black text-slate-900 leading-none">{{ $projects->total() }}</span>
                                                <span class="block text-[10px] font-bold uppercase tracking-wider text-blue-600 mt-0.5">Total Proyek</span>
                                            </div>
                                        </div>
                                    @endif
<a href="{{ route('company.projects.archive') }}" class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl bg-white/15 hover:bg-white/25 border border-white/30 text-white font-bold text-sm shadow-lg shadow-blue-950/20 transition-colors duration-200 active:scale-95">
                                        <i class="fa-solid fa-box-archive text-xs"></i>
                                        <span>Arsip</span>
                                    </a>
                                    <a href="{{ route('company.projects.create') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-white hover:bg-blue-50 text-blue-700 font-bold text-sm shadow-lg shadow-blue-950/20 transition-colors duration-200 active:scale-95">
                                        <i class="fa-solid fa-plus text-xs"></i>
                                        <span>Buat Proyek</span>
                                    </a>
                                </div>
                            </div>
                        </section>

                        {{-- =================================================
                            SUCCESS MESSAGE
                        ================================================ --}}
                        @if (session('success'))
                            <div class="flex items-center gap-3.5 px-5 py-4 rounded-2xl bg-emerald-50 border border-emerald-200 shadow-sm">
                                <div class="w-10 h-10 rounded-xl bg-emerald-500 flex items-center justify-center text-white shrink-0 shadow-sm">
                                    <i class="fa-solid fa-check text-sm"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-bold text-emerald-900">Berhasil!</p>
                                    <p class="text-xs sm:text-sm text-emerald-700 font-medium truncate">{{ session('success') }}</p>
                                </div>
                                <button onclick="this.parentElement.remove()" class="ml-auto shrink-0 w-8 h-8 rounded-xl flex items-center justify-center text-emerald-500 hover:bg-emerald-100 transition-colors">
                                    <i class="fa-solid fa-xmark text-sm"></i>
                                </button>
                            </div>
                        @endif

                        {{-- =================================================
                            HEADER LIST
                        ================================================ --}}
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">Semua Proyek</h2>
                                <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Portofolio proyek aktif yang sedang kamu kelola</p>
                            </div>
                            @if ($projects->count() > 0)
                                <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-500 text-xs font-semibold shadow-sm">
                                    <i class="fa-solid fa-list-ul text-blue-500"></i>
                                    {{ $projects->count() }} ditampilkan
                                </span>
                            @endif
                        </div>

                        {{-- =================================================
                            LIST PROYEK
                        ================================================ --}}
                        <div class="space-y-4">
                            @forelse ($projects as $project)
                                @php
                                    $status = $project->status ?? 'Open';
                                    $isOpen = strtolower($status) === 'open';
                                @endphp
                                <a href="{{ route('company.projects.show', $project) }}"
                                   class="reveal-item project-card group flex flex-col md:flex-row md:items-center justify-between gap-4 rounded-2xl p-5 sm:p-6 border-2 border-slate-200 shadow-sm relative overflow-hidden"
                                   style="animation-delay: {{ min($loop->index * 60, 300) }}ms;">

                                    {{-- Aksen bar kiri sesuai status --}}
                                    <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $isOpen ? 'bg-emerald-500' : 'bg-slate-300' }}"></div>

                                    {{-- Left Content --}}
                                    <div class="flex items-start gap-4 min-w-0 flex-1 pl-2">
                                        <div class="w-12 h-12 sm:w-14 sm:h-14 shrink-0 rounded-2xl bg-blue-600 flex items-center justify-center text-white shadow-md shadow-blue-500/30 group-hover:scale-105 transition-transform duration-300">
                                            <i class="fa-solid fa-briefcase text-lg"></i>
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-blue-700 transition-colors truncate">
                                                    {{ $project->project_name }}
                                                </h3>
                                                @if($project->relationLoaded('category') && $project->category)
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-blue-50 border border-blue-200 text-blue-700 text-xs font-bold">
                                                        {{ $project->category->name }}
                                                    </span>
                                                @endif
                                            </div>

                                            @if($project->project_description)
                                                <p class="mt-1 text-xs sm:text-sm text-slate-500 line-clamp-1 leading-relaxed">
                                                    {{ $project->project_description }}
                                                </p>
                                            @endif

                                            <div class="mt-3 flex flex-wrap items-center gap-2 text-xs font-bold">
                                                @if(isset($project->budget) && $project->budget)
                                                    <span class="inline-flex items-center gap-1.5 text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-1.5 rounded-lg">
                                                        <i class="fa-solid fa-wallet text-emerald-600 text-[11px]"></i>
                                                        Rp{{ number_format($project->budget, 0, ',', '.') }}
                                                    </span>
                                                @endif
                                                @if(isset($project->deadline))
                                                    <span class="inline-flex items-center gap-1.5 text-amber-700 bg-amber-50 border border-amber-200 px-2.5 py-1.5 rounded-lg">
                                                        <i class="fa-regular fa-calendar text-[11px]"></i>
                                                        {{ \Carbon\Carbon::parse($project->deadline)->format('d M Y') }}
                                                    </span>
                                                @endif
                                                @if($project->skills)
                                                    @php $skillList = explode(',', $project->skills); @endphp
                                                    <span class="inline-flex items-center gap-1.5 text-indigo-700 bg-indigo-50 border border-indigo-200 px-2.5 py-1.5 rounded-lg">
                                                        <i class="fa-solid fa-code text-[11px]"></i>
                                                        {{ trim($skillList[0]) }}{{ count($skillList) > 1 ? '…' : '' }}
                                                    </span>
                                                @endif
                                                @if($project->relationLoaded('penawarans'))
                                                    <span class="inline-flex items-center gap-1.5 text-blue-700 bg-blue-50 border border-blue-200 px-2.5 py-1.5 rounded-lg">
                                                        <i class="fa-solid fa-handshake text-[11px]"></i>
                                                        {{ $project->penawarans->count() }} Penawaran
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

{{-- Right Status & Action --}}
                                    <div class="flex flex-col items-start md:items-end gap-2 pt-3 md:pt-0 border-t md:border-t-0 border-slate-100 pl-2 md:pl-0">
                                        <div class="flex flex-wrap items-center gap-1.5 justify-end">
                                            {{-- Status buka/tutup penawaran (projects.status) --}}
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-xl border {{ $isOpen ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-600 border-slate-200' }}">
                                                <span class="w-2 h-2 rounded-full {{ $isOpen ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span>
                                                {{ $status }}
                                            </span>

                                            {{-- Status pekerjaan (Workspace) — info tambahan, tidak mengganti projects.status --}}
                                            @php
                                                $workStatus = $project->workspace?->status;
                                                $workBadge = match($workStatus) {
                                                    'Selesai' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                    'Menunggu Review' => 'bg-sky-50 text-sky-700 border-sky-200',
                                                    'Menunggu Revisi' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                    'Menunggu Pembayaran' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                                    'Menunggu Verifikasi Admin' => 'bg-purple-50 text-purple-700 border-purple-200',
                                                    'Sedang Dikerjakan' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                    default => null,
                                                };
                                            @endphp

                                            @if($workStatus)
                                                <span class="inline-flex items-center gap-1 text-xs font-bold rounded-xl border px-2.5 py-1.5 {{ $workBadge }}">
                                                    <i class="fa-solid fa-circle-half-stroke text-[10px] {{ $workStatus === 'Selesai' ? 'fa-circle-check' : '' }}"></i>
                                                    {{ $workStatus }}
                                                </span>
                                            @elseif($isOpen)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-bold rounded-xl border bg-slate-50 text-slate-500 border-slate-200">
                                                    <i class="fa-regular fa-clock text-[10px]"></i>
                                                    Menunggu Freelancer
                                                </span>
                                            @endif
                                        </div>

                                        <div class="w-9 h-9 shrink-0 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white group-hover:border-transparent group-hover:translate-x-1 transition-all duration-300">
                                            <i class="fa-solid fa-arrow-right text-xs"></i>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="relative bg-white border border-dashed border-blue-200 rounded-3xl p-12 sm:p-16 text-center shadow-sm overflow-hidden">
                                    <div class="relative">
                                        <div class="w-16 h-16 mx-auto rounded-2xl bg-blue-600 text-white flex items-center justify-center text-2xl shadow-lg shadow-blue-500/25 mb-4">
                                            <i class="fa-solid fa-folder-open"></i>
                                        </div>
                                        <h3 class="text-lg font-bold text-slate-900">Belum Ada Proyek</h3>
                                        <p class="text-sm text-slate-500 max-w-sm mx-auto mt-1 mb-6">
                                            Mulai buat proyek pertamamu sekarang dan temukan talenta freelancer profesional.
                                        </p>
                                        <a href="{{ route('company.projects.create') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-blue-600 text-white text-sm font-bold shadow-lg shadow-blue-600/25 hover:bg-blue-700 transition-colors">
                                            <i class="fa-solid fa-plus text-xs"></i>
                                            Buat Proyek Baru
                                        </a>
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        {{-- =================================================
                            PAGINATION
                        ================================================ --}}
                        @if ($projects->hasPages())
                            <div class="pt-4 flex justify-center">
                                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm px-4 py-2">
                                    {{ $projects->links() }}
                                </div>
                            </div>
                        @endif

                    </div>
                </main>
            </div>

            <div class="px-4 sm:px-6 lg:px-10 mt-12">
                @include('navbar.footer')
            </div>

        </div>
    </div>

</body>
</html>
