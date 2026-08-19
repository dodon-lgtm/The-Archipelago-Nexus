<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Saya | ApexForge Labs</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
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

<body class="bg-surface dark:bg-slate-950 text-slate-800 dark:text-white min-h-screen flex font-sans antialiased selection:bg-brand selection:text-white">

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
                                <i class="fa-solid fa-flag text-xs text-blue-200"></i>
                                Pusat Bantuan & Laporan
                            </div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                                Laporan Saya
                            </h1>
                            <p class="text-blue-100/90 text-sm max-w-xl font-medium leading-relaxed">
                                Pantau dan kelola laporan masalah atau kendala yang telah Anda ajukan ke administrator.
                            </p>
                        </div>

                        <div class="flex flex-wrap items-center gap-3 shrink-0">
                            <a href="{{ route('reports.create') }}" class="btn-shimmer inline-flex items-center gap-2 bg-white dark:bg-slate-900 text-brand hover:bg-[#f6f9ff] dark:hover:bg-slate-800 px-5 py-3 rounded-2xl text-sm font-bold shadow-lg shadow-black/5 transition">
                                <i class="fa-solid fa-plus text-xs"></i>
                                <span>Buat Laporan Baru</span>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- FLASH MESSAGES --}}
                @if(session('success'))
                    <div class="reveal reveal-1 flex items-center justify-between gap-3 px-5 py-4 bg-emerald-50/80 dark:bg-emerald-900/40 backdrop-blur-md border border-emerald-200/60 dark:border-emerald-900 text-emerald-800 dark:text-emerald-300 text-sm font-medium rounded-2xl shadow-sm">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-sm shadow-emerald-500/30">
                                <i class="fa-solid fa-check text-xs"></i>
                            </div>
                            <span class="truncate">{{ session('success') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-emerald-500 dark:text-emerald-300 hover:text-emerald-700 dark:hover:text-emerald-300 p-1">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="reveal reveal-1 flex items-center justify-between gap-3 px-5 py-4 bg-rose-50/80 dark:bg-red-900/40 backdrop-blur-md border border-rose-200/60 dark:border-red-900 text-rose-800 dark:text-red-300 text-sm font-medium rounded-2xl shadow-sm">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center shrink-0 shadow-sm shadow-rose-500/30">
                                <i class="fa-solid fa-xmark text-xs"></i>
                            </div>
                            <span class="truncate">{{ session('error') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-rose-500 dark:text-red-300 hover:text-rose-700 dark:hover:text-red-300 p-1">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                {{-- SUB HEADER --}}
                <div class="reveal reveal-2 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900 dark:text-white tracking-tight">Riwayat Pengaduan</h2>
                        <p class="text-xs text-slate-400 dark:text-slate-400 font-medium">Daftar keluhan dan progres penanganan dari admin</p>
                    </div>
                </div>

                {{-- DAFTAR LAPORAN --}}
                @if($reports->count() > 0)
                    <div class="reveal reveal-3 space-y-3">
                        @foreach($reports as $report)
                            @php
                                $statusBg = match($report->status) {
                                    'menunggu' => 'bg-amber-50 dark:bg-yellow-900/40 text-amber-700 dark:text-yellow-300 border-amber-200 dark:border-yellow-900',
                                    'ditinjau' => 'bg-blue-50 dark:bg-slate-800 text-blue-700 dark:text-blue-400 border-blue-200 dark:border-slate-700',
                                    'menunggu-bukti' => 'bg-violet-50 dark:bg-purple-900/40 text-violet-700 dark:text-purple-300 border-violet-200 dark:border-purple-900',
                                    'selesai' => 'bg-emerald-50 dark:bg-green-900/40 text-emerald-700 dark:text-green-300 border-emerald-200 dark:border-green-900',
                                    default => 'bg-rose-50 dark:bg-red-900/40 text-rose-700 dark:text-red-300 border-rose-200 dark:border-red-900',
                                };

                                $accentBg = match($report->status) {
                                    'menunggu' => 'bg-amber-500',
                                    'ditinjau' => 'bg-blue-500',
                                    'menunggu-bukti' => 'bg-violet-500',
                                    'selesai' => 'bg-emerald-500',
                                    default => 'bg-rose-500',
                                };
                            @endphp

                            <div class="modern-row block bg-white dark:bg-slate-900 border border-blue-100/80 dark:border-slate-800 rounded-2xl p-5 shadow-sm relative overflow-hidden group">
                                
                                {{-- Left Accent Bar --}}
                                <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $accentBg }}"></div>

                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pl-2">
                                    {{-- Left Information --}}
                                    <div class="flex items-start gap-4 min-w-0 flex-1">
                                        <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-slate-800 text-brand border border-blue-100 dark:border-slate-800 flex items-center justify-center shrink-0 text-lg shadow-inner group-hover:bg-brand group-hover:text-white transition-colors duration-300">
                                            <i class="fa-solid fa-shield-halved"></i>
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <h3 class="text-base font-bold text-slate-800 dark:text-white group-hover:text-brand transition-colors truncate">
                                                    {{ $report->subject }}
                                                </h3>
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $statusBg }}">
                                                    {{ \App\Models\Report::statusLabel($report->status) }} 
                                                    <span class="opacity-60 text-[10px]">({{ \App\Models\Report::targetLabel($report->target) }})</span>
                                                </span>
                                            </div>

                                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed">
                                                {{ \Illuminate\Support\Str::limit($report->description, 150) }}
                                            </p>

                                            <div class="mt-3 flex flex-wrap items-center gap-2 text-xs font-semibold">
                                                <span class="inline-flex items-center gap-1.5 text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-slate-800 border border-blue-100 dark:border-slate-800 px-2.5 py-1 rounded-lg">
                                                    <i class="fa-solid fa-tag text-[10px]"></i>
                                                    {{ \App\Models\Report::categoryLabel($report->category) }}
                                                </span>

                                                <span class="inline-flex items-center gap-1.5 text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 px-2.5 py-1 rounded-lg">
                                                    <i class="fa-regular fa-calendar text-[10px]"></i>
                                                    {{ $report->created_at->format('d M Y') }}
                                                </span>

                                                @if($report->reportedUser)
                                                    <span class="inline-flex items-center gap-1.5 text-orange-700 dark:text-orange-300 bg-orange-50 dark:bg-orange-900/40 border border-orange-100 dark:border-orange-900 px-2.5 py-1 rounded-lg">
                                                        <i class="fa-solid fa-user text-[10px]"></i>
                                                        {{ $report->reportedUser->name }}
                                                    </span>
                                                @endif

                                                @if($report->workspace)
                                                    <span class="inline-flex items-center gap-1.5 text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/40 border border-indigo-100 dark:border-indigo-900 px-2.5 py-1 rounded-lg">
                                                        <i class="fa-solid fa-layer-group text-[10px]"></i>
                                                        {{ \Illuminate\Support\Str::limit($report->workspace->project->project_name ?? 'Workspace', 30) }}
                                                    </span>
                                                @elseif($report->project)
                                                    <span class="inline-flex items-center gap-1.5 text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/40 border border-indigo-100 dark:border-indigo-900 px-2.5 py-1 rounded-lg">
                                                        <i class="fa-solid fa-folder text-[10px]"></i>
                                                        {{ \Illuminate\Support\Str::limit($report->project->project_name, 30) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Right Action Button --}}
                                    <div class="flex items-center justify-end shrink-0 pt-3 md:pt-0 border-t md:border-t-0 border-slate-100 dark:border-slate-800">
                                        <a href="{{ route('company.reports.show', $report) }}"
                                           class="inline-flex items-center gap-2 px-4 py-2.5 text-xs font-bold bg-brand/10 dark:bg-blue-900/40 text-brand dark:text-blue-300 hover:bg-brand hover:text-white rounded-xl transition-all duration-200">
                                            <span>Lihat Detail</span>
                                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- PAGINATION --}}
                    @if(method_exists($reports, 'links') && $reports->hasPages())
                        <div class="pt-4 flex justify-center">
                            <div class="bg-white dark:bg-slate-900 border border-blue-100/80 dark:border-slate-800 rounded-2xl shadow-sm px-4 py-2">
                                {{ $reports->links() }}
                            </div>
                        </div>
                    @endif
                @else
                    {{-- EMPTY STATE --}}
                    <div class="reveal reveal-3 bg-white dark:bg-slate-900 border border-blue-100/80 dark:border-slate-800 rounded-3xl p-12 text-center shadow-sm">
                        <div class="w-14 h-14 mx-auto mb-3 bg-blue-50 dark:bg-slate-800 text-slate-400 dark:text-slate-400 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                            <i class="fa-solid fa-flag"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-700 dark:text-white">Belum Ada Laporan</h3>
                        <p class="text-xs text-slate-400 dark:text-slate-400 mt-1 max-w-xs mx-auto">Anda belum membuat laporan apa pun. Jika menemukan masalah, silakan buat laporan baru.</p>
                        <a href="{{ route('reports.create') }}" class="btn-shimmer inline-flex items-center gap-2 mt-4 px-4 py-2.5 bg-brand text-white rounded-xl text-xs font-bold shadow-md shadow-brand/20">
                            <i class="fa-solid fa-plus text-[10px]"></i> Buat Laporan Baru
                        </a>
                    </div>
                @endif

            </div>
        </main>

        {{-- FOOTER --}}
       

    </div>

</body>
</html>