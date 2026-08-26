<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.theme-boot')
    
    <title>Daftar Proyek | ApexForge Labs</title>

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
        /* ---- Entrance animations ---- */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .reveal { opacity: 0; animation: fadeInUp .7s cubic-bezier(.16,1,.3,1) forwards; }
        .reveal-1 { animation-delay: .05s; }
        .reveal-2 { animation-delay: .1s; }
        .reveal-3 { animation-delay: .15s; }

        /* ---- Modern Mesh / Gradient Animation ---- */
        @keyframes meshGradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .animate-mesh {
            background-size: 200% 200%;
            animation: meshGradient 12s ease infinite;
        }

        /* ---- Floating Ambient Blobs ---- */
        @keyframes floatBlob {
            0%, 100% { transform: translate(0px, 0px) scale(1); }
            50% { transform: translate(15px, -20px) scale(1.05); }
        }
        .blob { animation: floatBlob 9s ease-in-out infinite; }

        /* ---- Interactive Rows ---- */
        .modern-row {
            transition: all .3s cubic-bezier(.16,1,.3,1);
        }
        .modern-row:hover {
            transform: translateY(-2px);
            background-color: #ffffff;
            box-shadow: 0 10px 30px -10px rgba(37, 99, 235, 0.08);
            border-color: rgba(37, 99, 235, 0.25);
        }

        /* ---- Shimmer Button Effect ---- */
        .btn-shimmer {
            position: relative;
            overflow: hidden;
            isolation: isolate;
        }
        .btn-shimmer::after {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 60%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.3), transparent);
            transform: skewX(-20deg);
            transition: left .7s ease;
        }
        .btn-shimmer:hover::after {
            left: 150%;
        }

        /* Scrollbar Halus */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>

<body class="bg-surface text-slate-800 min-h-screen flex font-sans antialiased selection:bg-brand selection:text-white dark:bg-slate-900 dark:text-white transition-colors duration-300">

    {{-- SIDEBAR --}}
    @include('navbar.navigasi')

    {{-- AREA KANAN (MAIN CONTENT) --}}
    <div class="flex-1 flex flex-col min-h-screen w-full overflow-hidden">

        @include('navbar.nav')

        <main class="flex-1 w-full overflow-y-auto p-4 sm:p-6 lg:p-8">
            <div class="w-full mx-auto space-y-6">

                {{-- HERO BANNER HEADER --}}
                <div class="reveal reveal-1 relative overflow-hidden rounded-3xl shadow-xl shadow-blue-600/10 border border-blue-500/20 w-full">
                    <div class="absolute inset-0 animate-mesh bg-gradient-to-r from-blue-700 via-brand to-blue-600"></div>
                    
                    {{-- Ambient Decorative Blobs --}}
                    <div class="blob absolute -top-20 -right-20 w-72 h-72 bg-white/10 rounded-full blur-2xl"></div>
                    <div class="blob absolute -bottom-24 -left-20 w-80 h-80 bg-blue-400/20 rounded-full blur-2xl" style="animation-delay: 2s;"></div>
                    
                    {{-- Dot Pattern Overlay --}}
                    <div class="absolute inset-0 opacity-[0.07]" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>

                    <div class="relative p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                        <div class="space-y-2">
                            <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md px-3.5 py-1.5 rounded-full text-white text-xs font-semibold ring-1 ring-white/20 shadow-inner">
                                <i class="fa-solid fa-layer-group text-xs text-blue-200"></i>
                                Portofolio Proyek
                            </div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                                Daftar Proyek
                            </h1>
                            <p class="text-blue-100/90 text-sm max-w-xl font-medium leading-relaxed">
                                Kelola portofolio proyek aktif dan temukan talenta freelancer terbaik Anda.
                            </p>
                        </div>

                        <div class="flex flex-wrap items-center gap-3 shrink-0">
                            @if ($projects->count() > 0)
                                <div class="hidden md:flex items-center gap-3 px-4 py-2.5 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 text-white">
                                    <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center text-white">
                                        <i class="fa-solid fa-folder text-xs"></i>
                                    </div>
                                    <div class="text-right">
                                        <span class="block text-lg font-black leading-none">{{ $projects->total() }}</span>
                                        <span class="block text-[10px] font-bold uppercase tracking-wider text-blue-100 mt-0.5">Total</span>
                                    </div>
                                </div>
                            @endif

                            <a href="{{ route('company.projects.archive') }}" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white px-4 py-3 rounded-2xl text-sm font-bold transition">
                                <i class="fa-solid fa-box-archive text-xs"></i>
                                <span>Arsip</span>
                            </a>

                            <a href="{{ route('company.projects.create') }}" class="btn-shimmer inline-flex items-center gap-2 bg-white text-brand hover:bg-[#f6f9ff] px-5 py-3 rounded-2xl text-sm font-bold shadow-lg shadow-black/5 transition dark:bg-slate-900 dark:hover:bg-slate-800">
                                <i class="fa-solid fa-plus text-xs"></i>
                                <span>Buat Proyek</span>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- SESSION SUCCESS NOTIFICATION --}}
                @if (session('success'))
                    <div class="reveal reveal-1 flex items-center justify-between gap-3 px-5 py-4 bg-emerald-50/80 backdrop-blur-md border border-emerald-200/60 text-emerald-800 text-sm font-medium rounded-2xl shadow-sm dark:bg-emerald-900/40 dark:border-emerald-900 dark:text-emerald-300">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-sm shadow-emerald-500/30">
                                <i class="fa-solid fa-check text-xs"></i>
                            </div>
                            <span class="truncate">{{ session('success') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 p-1 dark:text-emerald-300">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                {{-- SUB HEADER & TITLE --}}
                <div class="reveal reveal-2 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900 tracking-tight dark:text-white">Semua Proyek</h2>
                        <p class="text-xs text-slate-400 font-medium dark:text-slate-400">Portofolio proyek aktif yang sedang Anda kelola</p>
                    </div>
                    @if ($projects->count() > 0)
                        <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white border border-blue-100 text-slate-500 text-xs font-semibold shadow-sm dark:bg-slate-900 dark:border-slate-800 dark:text-slate-400">
                            <i class="fa-solid fa-list-ul text-brand"></i>
                            {{ $projects->count() }} ditampilkan
                        </span>
                    @endif
                </div>

                {{-- PROJECT LIST --}}
                <div class="reveal reveal-3 space-y-3">
                    @forelse ($projects as $project)
                        @php
                            $status = $project->status ?? 'open';
                            $isOpen = $status === 'open';
                            $statusBadge = match($status) {
                                'open' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
                                'closed' => 'bg-rose-50 text-rose-700 border-rose-200/60',
                                'archived' => 'bg-slate-100 text-slate-600 border-slate-200',
                                default => 'bg-slate-100 text-slate-600 border-slate-200',
                            };
                            $statusLabel = \App\Models\Project::statusLabel($status);
                        @endphp
                        
                        <a href="{{ route('company.projects.show', $project) }}" 
                           class="modern-row block bg-white border border-blue-100/80 rounded-2xl p-5 shadow-sm relative overflow-hidden group dark:bg-slate-900 dark:border-slate-800">
                            
                            {{-- Bar Status Kiri --}}
                            <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $isOpen ? 'bg-emerald-500' : 'bg-slate-300' }}"></div>

                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pl-2">
                                {{-- Detail Kiri --}}
                                <div class="flex items-start gap-4 min-w-0 flex-1">
                                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-brand border border-blue-100 flex items-center justify-center shrink-0 text-lg shadow-inner group-hover:bg-brand group-hover:text-white transition-colors duration-300 dark:bg-slate-800 dark:border-slate-800">
                                        <i class="fa-solid fa-briefcase"></i>
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h3 class="text-base font-bold text-slate-800 group-hover:text-brand transition-colors truncate dark:text-white">
                                                {{ $project->project_name }}
                                            </h3>
                                            @if($project->relationLoaded('category') && $project->category)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-blue-50 border border-blue-100 text-brand text-[11px] font-bold dark:bg-slate-800 dark:border-slate-800">
                                                    {{ $project->category->name }}
                                                </span>
                                            @endif
                                        </div>

                                        @if($project->project_description)
                                            <p class="mt-1 text-xs text-slate-500 line-clamp-1 leading-relaxed dark:text-slate-400">
                                                {{ $project->project_description }}
                                            </p>
                                        @endif

                                        <div class="mt-3 flex flex-wrap items-center gap-2 text-xs font-semibold">
                                            @if(isset($project->budget) && $project->budget)
                                                <span class="inline-flex items-center gap-1.5 text-emerald-700 bg-emerald-50 border border-emerald-100 px-2.5 py-1 rounded-lg dark:text-emerald-300 dark:bg-emerald-900/40 dark:border-emerald-900">
                                                    <i class="fa-solid fa-wallet text-emerald-600 text-[10px] dark:text-emerald-300"></i>
                                                    Rp {{ number_format($project->budget, 0, ',', '.') }}
                                                </span>
                                            @endif
                                            @if(isset($project->deadline))
                                                <span class="inline-flex items-center gap-1.5 text-amber-700 bg-amber-50 border border-amber-100 px-2.5 py-1 rounded-lg dark:text-amber-300 dark:bg-amber-900/40 dark:border-amber-900">
                                                    <i class="fa-regular fa-calendar text-[10px]"></i>
                                                    {{ \Carbon\Carbon::parse($project->deadline)->format('d M Y') }}
                                                </span>
                                            @endif
                                            @if($project->skills)
                                                @php $skillList = explode(',', $project->skills); @endphp
                                                <span class="inline-flex items-center gap-1.5 text-indigo-700 bg-indigo-50 border border-indigo-100 px-2.5 py-1 rounded-lg dark:text-indigo-300 dark:bg-indigo-900/40 dark:border-indigo-900">
                                                    <i class="fa-solid fa-code text-[10px]"></i>
                                                    {{ trim($skillList[0]) }}{{ count($skillList) > 1 ? '…' : '' }}
                                                </span>
                                            @endif
                                            @if($project->relationLoaded('penawarans'))
                                                <span class="inline-flex items-center gap-1.5 text-blue-700 bg-blue-50 border border-blue-100 px-2.5 py-1 rounded-lg dark:text-blue-400 dark:bg-slate-800 dark:border-slate-800">
                                                    <i class="fa-solid fa-handshake text-[10px]"></i>
                                                    {{ $project->penawarans->count() }} Penawaran
                                                </span>
                                            @endif
                                            @if(($negoUnreadByProject[$project->id] ?? 0) > 0)
                                                <span data-nego-unread-project="{{ $project->id }}"
                                                    class="inline-flex items-center gap-1.5 text-white bg-red-500 border border-red-500 px-2.5 py-1 rounded-lg shadow-sm">
                                                    <i class="fa-solid fa-comment-dots text-[10px]"></i>
                                                    {{ $negoUnreadByProject[$project->id] }}
                                                    Pesan Negosiasi
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Status Kanan & Aksi --}}
                                <div class="flex items-center justify-between md:justify-end gap-3 pt-3 md:pt-0 border-t md:border-t-0 border-slate-100">
                                    <div class="flex flex-wrap items-center gap-1.5">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-xl border dark:bg-slate-800 {{ $statusBadge }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $isOpen ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span>
                                            {{ $statusLabel }}
                                        </span>

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
                                            <span class="inline-flex items-center gap-1 text-xs font-bold rounded-xl border px-2.5 py-1 dark:bg-slate-800 {{ $workBadge }}">
                                                <i class="fa-solid fa-circle-half-stroke text-[10px]"></i>
                                                {{ $workStatus }}
                                            </span>
                                        @elseif($isOpen)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold rounded-xl border bg-slate-50 text-slate-500 border-slate-200">
                                                <i class="fa-regular fa-clock text-[10px]"></i>
                                                Menunggu Freelancer
                                            </span>
                                        @endif
                                    </div>

                                    <div class="w-8 h-8 rounded-xl bg-blue-50 text-brand flex items-center justify-center group-hover:bg-brand group-hover:text-white transition-all dark:bg-slate-800">
                                        <i class="fa-solid fa-chevron-right text-xs"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="bg-white border border-blue-100/80 rounded-3xl p-12 text-center shadow-sm dark:bg-slate-900 dark:border-slate-800">
                            <div class="w-14 h-14 mx-auto mb-3 bg-blue-50 text-slate-400 rounded-2xl flex items-center justify-center text-xl shadow-inner dark:bg-slate-800 dark:text-slate-400">
                                <i class="fa-regular fa-folder-open"></i>
                            </div>
                            <h3 class="text-sm font-bold text-slate-700 dark:text-white">Belum ada proyek</h3>
                            <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto dark:text-slate-400">Mulai buat proyek pertama Anda dan temukan talenta terbaik.</p>
                            <a href="{{ route('company.projects.create') }}" class="btn-shimmer inline-flex items-center gap-2 mt-4 px-4 py-2.5 bg-brand text-white rounded-xl text-xs font-bold shadow-md shadow-brand/20">
                                <i class="fa-solid fa-plus text-[10px]"></i> Buat Proyek
                            </a>
                        </div>
                    @endforelse
                </div>

                {{-- PAGINATION --}}
                @if ($projects->hasPages())
                    <div class="pt-4 flex justify-center">
                        <div class="bg-white border border-blue-100/80 rounded-2xl shadow-sm px-4 py-2 dark:bg-slate-900 dark:border-slate-800">
                            {{ $projects->links() }}
                        </div>
                    </div>
                @endif

            </div>
        </main>

        {{-- FOOTER --}}
      

    </div>

</body>
</html>