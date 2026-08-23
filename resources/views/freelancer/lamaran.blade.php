<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Script Inisialisasi Dark Mode --}}
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <title>Lamaran Saya | ApexForge Labs</title>

    @vite('resources/css/app.css')

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- SweetAlert2 CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            from {
                opacity: 0;
                transform: translateY(18px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .reveal {
            opacity: 0;
            animation: fadeInUp .65s cubic-bezier(.16,1,.3,1) forwards;
        }

        .reveal-1 { animation-delay: .05s; }
        .reveal-2 { animation-delay: .1s; }
        .reveal-3 { animation-delay: .15s; }

        @keyframes meshGradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .animate-mesh {
            background-size: 200% 200%;
            animation: meshGradient 12s ease infinite;
        }

        /* =========================================
        CARD & HOVER EFFECTS
        ========================================= */
        .modern-row {
            transition: all .3s cubic-bezier(.16,1,.3,1);
        }

        .modern-row:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px -10px rgba(37, 99, 235, 0.12);
        }

        .dark .modern-row:hover {
            box-shadow: 0 12px 30px -10px rgba(0, 0, 0, 0.6);
        }

        /* SHIMMER BUTTON */
        .btn-shimmer {
            position: relative;
            overflow: hidden;
            isolation: isolate;
        }

        .btn-shimmer::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 60%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.3), transparent);
            transform: skewX(-20deg);
            transition: left .7s ease;
        }

        .btn-shimmer:hover::after {
            left: 150%;
        }

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

        {{-- Sidebar --}}
        @include('navbar.navigasi')

        {{-- Main Content Container --}}
        <div class="flex-1 flex flex-col min-h-screen w-full overflow-hidden">

            {{-- Top Navbar --}}
            <div class="sticky top-0 z-40 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-100 dark:border-slate-800/80">
                @include('navbar.nav')
            </div>

            <main class="flex-1 w-full overflow-y-auto p-4 sm:p-6 lg:p-8 space-y-6">

                {{-- =================================================
                    HERO HEADER
                ================================================== --}}
                <div class="reveal reveal-1 relative overflow-hidden rounded-3xl shadow-xl shadow-blue-600/10 border border-blue-500/20 dark:border-blue-500/30 w-full">
                    
                    {{-- Mesh Gradient Overlay --}}
                    <div class="absolute inset-0 animate-mesh bg-gradient-to-r from-blue-700 via-brand to-blue-600 dark:from-slate-900 dark:via-blue-900 dark:to-slate-900"></div>

                    {{-- Decorative Blobs --}}
                    <div class="absolute -top-20 -right-20 w-72 h-72 bg-white/10 dark:bg-blue-500/10 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="absolute -bottom-24 -left-20 w-80 h-80 bg-blue-400/20 dark:bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>

                    {{-- Dot Pattern --}}
                    <div class="absolute inset-0 opacity-[0.08] dark:opacity-[0.12] pointer-events-none"
                        style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;">
                    </div>

                    <div class="relative p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                        <div class="space-y-2">
                            <div class="inline-flex items-center gap-2 bg-white/10 dark:bg-white/5 backdrop-blur-md px-3.5 py-1.5 rounded-full text-white text-xs font-semibold ring-1 ring-white/20 shadow-inner">
                                <i class="fa-regular fa-paper-plane text-xs text-blue-200 dark:text-blue-300"></i>
                                Portofolio Penawaran
                            </div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                                Lamaran Saya
                            </h1>
                            <p class="text-blue-100/90 dark:text-slate-300 text-sm max-w-xl font-medium leading-relaxed">
                                Pantau semua status lamaran proyek dan penawaran yang telah kamu kirimkan secara real-time.
                            </p>
                        </div>

                        <div class="flex items-center gap-3 shrink-0">
                            <a href="{{ route('freelancer.proyek') }}"
                               class="btn-shimmer inline-flex items-center gap-2 bg-white dark:bg-slate-900 text-brand dark:text-blue-400 hover:bg-[#f6f9ff] dark:hover:bg-slate-800 border border-transparent dark:border-slate-800 px-5 py-3 rounded-2xl text-sm font-bold shadow-lg shadow-black/5 transition">
                                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                                <span>Cari Proyek Lain</span>
                            </a>
                        </div>
                    </div>
                </div>


                {{-- =================================================
                    FLASH MESSAGES
                ================================================== --}}
                @if(session('success'))
                    <div class="reveal reveal-2 flex items-center justify-between gap-3 px-5 py-4 bg-emerald-50/95 dark:bg-emerald-950/50 backdrop-blur-md border border-emerald-200 dark:border-emerald-800/60 text-emerald-800 dark:text-emerald-300 text-sm font-medium rounded-2xl shadow-lg shadow-emerald-500/5">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-sm shadow-emerald-500/30">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wide">Berhasil</p>
                                <span class="block truncate">{{ session('success') }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="reveal reveal-2 flex items-center justify-between gap-3 px-5 py-4 bg-rose-50/95 dark:bg-rose-950/50 backdrop-blur-md border border-rose-200 dark:border-rose-800/60 text-rose-800 dark:text-rose-300 text-sm font-medium rounded-2xl shadow-lg shadow-rose-500/5">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-xl bg-rose-500 text-white flex items-center justify-center shrink-0 shadow-sm shadow-rose-500/30">
                                <i class="fa-solid fa-xmark"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-rose-600 dark:text-rose-400 uppercase tracking-wide">Terjadi Kesalahan</p>
                                <span class="block truncate">{{ session('error') }}</span>
                            </div>
                        </div>
                    </div>
                @endif


                {{-- =================================================
                    DAFTAR LAMARAN
                ================================================== --}}
                @if($lamaran->count() > 0)
                    <div class="reveal reveal-3 space-y-4">
                        @foreach($lamaran as $item)
                            <div class="modern-row bg-white dark:bg-slate-900 border border-blue-100/80 dark:border-slate-800/80 rounded-2xl p-5 sm:p-6 shadow-sm relative overflow-hidden group">
                                
                                {{-- Status Indicator Bar On Left --}}
                                @php
                                    $borderStatusColor = match($item->status) {
                                        'Diterima' => 'bg-emerald-500',
                                        'Menunggu' => 'bg-amber-500',
                                        default => 'bg-rose-500'
                                    };
                                @endphp
                                <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $borderStatusColor }}"></div>

                                <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-5 pl-2">

                                    {{-- Left: Project Details --}}
                                    <div class="flex-1 min-w-0 space-y-3">
                                        <div class="flex flex-wrap items-center gap-2">
                                            {{-- Category Badge --}}
                                            @if($item->project?->category?->name)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-blue-50 dark:bg-blue-950/60 border border-blue-100 dark:border-blue-900/60 text-brand dark:text-blue-300 text-[11px] font-bold">
                                                    {{ $item->project->category->name }}
                                                </span>
                                            @endif

                                            {{-- Company / Owner --}}
                                            @if($item->project?->owner?->name)
                                                <span class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1.5 font-medium">
                                                    <i class="fa-regular fa-building text-slate-400 dark:text-slate-500"></i>
                                                    {{ $item->project->owner->name }}
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Project Title --}}
                                        <h3 class="text-base sm:text-lg font-bold text-slate-800 dark:text-white group-hover:text-brand dark:group-hover:text-blue-400 transition-colors leading-snug">
                                            {{ $item->project->project_name ?? 'Proyek Tidak Ditemukan' }}
                                        </h3>

                                        {{-- Cover Letter / Message --}}
                                        @if($item->pesan)
                                            <div class="bg-slate-50/80 dark:bg-slate-950/40 border border-slate-100 dark:border-slate-800/60 rounded-xl p-3.5 text-xs text-slate-600 dark:text-slate-300 leading-relaxed italic">
                                                "{{ \Illuminate\Support\Str::limit($item->pesan, 150) }}"
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Right: Status & Stats Badges --}}
                                    <div class="flex flex-col sm:flex-row lg:flex-col items-start sm:items-center lg:items-end justify-between gap-4 border-t lg:border-t-0 border-slate-100 dark:border-slate-800/80 pt-4 lg:pt-0 shrink-0">
                                        
                                        {{-- Status Pill Badge --}}
                                        <div>
                                            @if($item->status === 'Menunggu')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 text-xs font-bold border border-amber-200 dark:border-amber-800/60 shadow-sm">
                                                    <i class="fa-solid fa-clock text-[10px]"></i>
                                                    Menunggu
                                                </span>
                                            @elseif($item->status === 'Diterima')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 text-xs font-bold border border-emerald-200 dark:border-emerald-800/60 shadow-sm">
                                                    <i class="fa-solid fa-circle-check text-[10px]"></i>
                                                    Diterima
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-rose-50 dark:bg-rose-950/50 text-rose-700 dark:text-rose-300 text-xs font-bold border border-rose-200 dark:border-rose-800/60 shadow-sm">
                                                    <i class="fa-solid fa-circle-xmark text-[10px]"></i>
                                                    Ditolak
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Price & Days Meta Info --}}
                                        <div class="flex items-center gap-2">
                                            <div class="bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-100 dark:border-emerald-900/60 px-3 py-1.5 rounded-xl text-left sm:text-right">
                                                <p class="text-[9px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Penawaran</p>
                                                <p class="text-xs sm:text-sm font-extrabold text-emerald-700 dark:text-emerald-300">
                                                    Rp {{ number_format($item->harga_penawaran ?? 0, 0, ',', '.') }}
                                                </p>
                                            </div>

                                            <div class="bg-blue-50 dark:bg-blue-950/40 border border-blue-100 dark:border-blue-900/60 px-3 py-1.5 rounded-xl text-left sm:text-right">
                                                <p class="text-[9px] font-bold uppercase tracking-wider text-brand dark:text-blue-400">Estimasi</p>
                                                <p class="text-xs sm:text-sm font-extrabold text-slate-700 dark:text-slate-200">
                                                    {{ $item->estimasi_hari ?? '-' }} Hari
                                                </p>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                {{-- Bottom Action Footer --}}
                                <div class="mt-4 pt-3.5 border-t border-slate-100 dark:border-slate-800/80 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 pl-2">
                                    {{-- Submission Date --}}
                                    <p class="text-xs text-slate-400 dark:text-slate-400 flex items-center gap-1.5 font-medium">
                                        <i class="fa-regular fa-calendar text-slate-400"></i>
                                        Diajukan {{ optional($item->created_at)->isoFormat('D MMMM YYYY') ?? '-' }}
                                    </p>

                                    {{-- Actions --}}
                                    <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                                        @if($item->project)
                                            <button type="button"
                                                data-negosiasi-open="{{ $item->id }}"
                                                data-project-title="{{ $item->project->project_name ?? 'Proyek' }}"
                                                data-peer-name="{{ $item->project->owner->name ?? 'Perusahaan' }}"
                                                data-peer-type="company"
                                                class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 bg-blue-50 hover:bg-blue-100 dark:bg-blue-950/40 dark:hover:bg-blue-900/60 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-900/50 text-xs font-bold rounded-xl transition">
                                                <i class="fa-regular fa-comments"></i>
                                                <span>Negosiasi</span>
                                            </button>

                                            <a href="{{ route('freelancer.projects.show', $item->project) }}"
                                               class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl transition">
                                                <span>Detail Proyek</span>
                                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                            </a>
                                        @endif

                                        @if($item->status === 'Diterima' && $item->project?->workspace)
                                            <a href="{{ route('freelancer.workspaces.show', $item->project->workspace) }}"
                                               class="btn-shimmer inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-brand hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md shadow-brand/20 transition">
                                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                                <span>Buka Workspace</span>
                                            </a>
                                        @endif

                                        @if($item->status === 'Menunggu')
                                            <form id="delete-form-{{ $item->id }}" action="{{ route('freelancer.penawaran.destroy', $item) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                        onclick="confirmCancel('delete-form-{{ $item->id }}')"
                                                        class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 dark:hover:bg-rose-900/50 text-rose-600 dark:text-rose-300 text-xs font-bold rounded-xl border border-rose-200 dark:border-rose-800/60 transition">
                                                    <i class="fa-solid fa-ban text-[10px]"></i>
                                                    <span>Batalkan Penawaran</span>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if(method_exists($lamaran, 'links'))
                        <div class="pt-4 flex justify-center">
                            <div class="bg-white dark:bg-slate-900 border border-blue-100/80 dark:border-slate-800 rounded-2xl shadow-sm px-4 py-2">
                                {{ $lamaran->links() }}
                            </div>
                        </div>
                    @endif

                @else
                    {{-- =================================================
                        EMPTY STATE
                    ================================================== --}}
                    <div class="reveal reveal-2 bg-white dark:bg-slate-900 border border-blue-100/80 dark:border-slate-800 rounded-3xl p-12 text-center shadow-sm">
                        <div class="w-16 h-16 mx-auto mb-4 bg-blue-50 dark:bg-slate-800 text-brand dark:text-blue-400 rounded-2xl flex items-center justify-center text-2xl shadow-inner border border-blue-100/50 dark:border-slate-700/50">
                            <i class="fa-regular fa-paper-plane"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-800 dark:text-white">
                            Belum Ada Lamaran
                        </h3>
                        <p class="text-xs text-slate-400 dark:text-slate-400 mt-1 max-w-xs mx-auto">
                            Kamu belum mengirimkan lamaran atau penawaran ke proyek mana pun saat ini.
                        </p>
                        <a href="{{ route('freelancer.proyek') }}"
                           class="btn-shimmer inline-flex items-center gap-2 mt-5 px-5 py-2.5 bg-brand dark:bg-blue-600 text-white rounded-xl text-xs font-bold shadow-md shadow-brand/20 transition">
                            <i class="fa-solid fa-magnifying-glass text-[10px]"></i>
                            Cari Proyek
                        </a>
                    </div>
                @endif

                {{-- Footer --}}
                @include('navbar.footer')

            </main>
        </div>
    </div>

    {{-- SweetAlert2 Confirmation Script --}}
    <script>
        function confirmCancel(formId) {
            const isDarkMode = document.documentElement.classList.contains('dark');

            Swal.fire({
                title: 'Batalkan Penawaran?',
                text: 'Anda yakin ingin membatalkan penawaran ini? Setelah dibatalkan, Anda dapat mengirim penawaran baru pada proyek ini.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Batalkan',
                cancelButtonText: 'Batal',
                background: isDarkMode ? '#0f172a' : '#ffffff',
                color: isDarkMode ? '#f8fafc' : '#0f172a',
                customClass: {
                    popup: 'rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xl',
                    title: 'text-slate-900 dark:text-white font-extrabold',
                    confirmButton: 'rounded-xl font-bold px-5 py-2.5',
                    cancelButton: 'rounded-xl font-bold px-5 py-2.5'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>

    {{-- Modal Negosiasi Chat --}}
    @include('negotiations.modal')

</body>
</html>