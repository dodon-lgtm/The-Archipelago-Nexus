<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Perusahaan | Professional Workspace</title>

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
        .reveal-4 { animation-delay: .2s; }
        .reveal-5 { animation-delay: .25s; }
        .reveal-6 { animation-delay: .3s; }
        .reveal-7 { animation-delay: .35s; }

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

        /* ---- Professional Stat Card Effects ---- */
        .stat-card {
            transition: all .4s cubic-bezier(.16,1,.3,1);
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.08);
            border-color: rgba(37, 99, 235, 0.2);
        }
        .stat-icon {
            transition: transform .4s cubic-bezier(.34,1.56,.64,1);
        }
        .stat-card:hover .stat-icon {
            transform: scale(1.1) rotate(-5deg);
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

        /* ---- Interactive Rows ---- */
        .modern-row {
            transition: all .3s cubic-bezier(.16,1,.3,1);
        }
        .modern-row:hover {
            transform: translateX(4px);
            background-color: #ffffff;
            box-shadow: 0 10px 30px -10px rgba(37, 99, 235, 0.08);
            border-color: rgba(37, 99, 235, 0.25);
        }

        /* ---- Section Accent Bar ---- */
        .section-accent {
            width: 4px;
            background: linear-gradient(180deg, #2563EB, #06B6D4);
            border-radius: 9999px;
        }

        /* Scrollbar Halus */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>

<body class="bg-surface text-slate-800 min-h-screen flex font-sans antialiased selection:bg-brand selection:text-white">

    {{-- SIDEBAR --}}
    @include('navbar.navigasi')

    {{-- AREA KANAN (MAIN CONTENT) - Menggunakan w-full agar melebar penuh --}}
    <div class="flex-1 flex flex-col min-h-screen w-full overflow-hidden">

        @include('navbar.nav')

        {{-- KONTEN UTAMA - max-w-none & w-full agar mengisi seluruh ruang kosong kanan --}}
        <main class="flex-1 w-full overflow-y-auto p-4 sm:p-6 lg:p-8">

            <div class="w-full mx-auto space-y-6">

                {{-- NOTIFIKASI SESSION SUCCESS --}}
                @if(session('success'))
                    <div class="reveal reveal-1 flex items-center gap-3 px-5 py-4 bg-emerald-50/80 backdrop-blur-md border border-emerald-200/60 text-emerald-800 text-sm font-medium rounded-2xl shadow-sm">
                        <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-sm shadow-emerald-500/30">
                            <i class="fa-solid fa-check text-xs"></i>
                        </div>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                {{-- WELCOME / HERO BANNER --}}
                <div class="reveal reveal-1 relative overflow-hidden rounded-3xl shadow-xl shadow-blue-600/10 border border-blue-500/20 w-full">
                    <div class="absolute inset-0 animate-mesh bg-gradient-to-r from-blue-700 via-brand to-cyan-600"></div>
                    
                    {{-- Ambient Decorative Blobs --}}
                    <div class="blob absolute -top-20 -right-20 w-72 h-72 bg-white/10 rounded-full blur-2xl"></div>
                    <div class="blob absolute -bottom-24 -left-20 w-80 h-80 bg-cyan-400/20 rounded-full blur-2xl" style="animation-delay: 2s;"></div>
                    
                    {{-- Subtle Dot Pattern Overlay --}}
                    <div class="absolute inset-0 opacity-[0.07]" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>

                    <div class="relative p-6 sm:p-8 lg:p-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                        <div class="space-y-3">
                            <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md px-3.5 py-1.5 rounded-full text-white text-xs font-semibold ring-1 ring-white/20 shadow-inner">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                Perusahaan Verified Dashboard
                            </div>
                            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight">
                                Selamat datang kembali, Rekan! 👋
                            </h1>
                            <p class="text-blue-100/90 text-sm md:text-base max-w-2xl font-medium leading-relaxed">
                                Pantau progres proyek secara real-time, kelola penawaran masuk, dan perluas bisnis Anda bersama freelancer profesional terbaik.
                            </p>
                        </div>

                        <a href="{{ route('company.projects.create') }}"
                           class="btn-shimmer inline-flex items-center justify-center gap-2.5 bg-white text-brand px-6 py-3.5 rounded-2xl text-sm font-bold shadow-lg shadow-black/5 hover:bg-slate-50 hover:scale-[1.02] active:scale-[0.98] transition-all shrink-0">
                            <i class="fa-solid fa-plus text-xs"></i>
                            <span>Buat Proyek Baru</span>
                        </a>
                    </div>
                </div>


                {{-- STATISTIK GRID - Menggunakan w-full & grid responsif tanpa batasan lebar tengah --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 w-full">

                    {{-- TOTAL PROYEK --}}
                    <div class="reveal reveal-2 stat-card bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-sm relative overflow-hidden">
                        <div class="flex items-center justify-between">
                            <div class="space-y-1">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Proyek</p>
                                <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight" data-count="{{ $totalProjects }}">0</h3>
                            </div>
                            <div class="stat-icon w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-blue-50 text-brand flex items-center justify-center text-xl shadow-inner border border-blue-100/50">
                                <i class="fa-regular fa-folder-open"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center gap-1.5 text-xs font-semibold text-blue-600 bg-blue-50/60 w-fit px-2.5 py-1 rounded-lg">
                            <i class="fa-solid fa-chart-pie"></i> Keseluruhan Portofolio
                        </div>
                    </div>

                    {{-- PROYEK AKTIF --}}
                    <div class="reveal reveal-3 stat-card bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-sm relative overflow-hidden">
                        <div class="flex items-center justify-between">
                            <div class="space-y-1">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Proyek Aktif</p>
                                <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight" data-count="{{ $activeProjects }}">0</h3>
                            </div>
                            <div class="stat-icon w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shadow-inner border border-emerald-100/50">
                                <i class="fa-solid fa-briefcase"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center gap-1.5 text-xs font-semibold text-emerald-600 bg-emerald-50/60 w-fit px-2.5 py-1 rounded-lg">
                            <i class="fa-solid fa-bolt"></i> Sedang Berjalan
                        </div>
                    </div>

                    {{-- FREELANCER BEKERJA --}}
                    <div class="reveal reveal-4 stat-card bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-sm relative overflow-hidden">
                        <div class="flex items-center justify-between">
                            <div class="space-y-1">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Freelancer Aktif</p>
                                <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight" data-count="{{ $activeFreelancers }}">0</h3>
                            </div>
                            <div class="stat-icon w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shadow-inner border border-amber-100/50">
                                <i class="fa-solid fa-user-group"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center gap-1.5 text-xs font-semibold text-amber-600 bg-amber-50/60 w-fit px-2.5 py-1 rounded-lg">
                            <i class="fa-solid fa-handshake"></i> Mitra Kolaborasi
                        </div>
                    </div>

                    {{-- TOTAL PENGELUARAN --}}
                    <div class="reveal reveal-5 stat-card bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-sm relative overflow-hidden">
                        <div class="flex items-center justify-between">
                            <div class="space-y-1">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Pengeluaran</p>
                                <h3 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight mt-1">Rp <span data-count="{{ $totalSpending }}">0</span></h3>
                            </div>
                            <div class="stat-icon w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl shadow-inner border border-rose-100/50">
                                <i class="fa-solid fa-wallet"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center gap-1.5 text-xs font-semibold text-rose-600 bg-rose-50/60 w-fit px-2.5 py-1 rounded-lg">
                            <i class="fa-solid fa-arrow-trend-up"></i> Investasi Proyek
                        </div>
                    </div>

                </div>


                {{-- GRID UTAMA: PROYEK & PROPOSAL (Memenuhi layar secara penuh) --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 w-full">

                    {{-- KARTU PROYEK ANDA --}}
                    <div class="reveal reveal-6 bg-white border border-slate-200/80 rounded-3xl p-5 sm:p-6 lg:p-7 shadow-sm flex flex-col justify-between w-full">
                        <div>
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-3">
                                    <span class="section-accent h-7"></span>
                                    <div>
                                        <h2 class="font-extrabold text-slate-900 text-lg">Proyek Anda</h2>
                                        <p class="text-xs text-slate-400 font-medium">Daftar proyek terbaru yang dipublikasikan</p>
                                    </div>
                                </div>
                                <a href="{{ route('company.projects.index') }}" class="text-xs text-brand font-bold hover:text-brand-dark flex items-center gap-1.5 group bg-brand/5 px-3 py-1.5 rounded-xl transition">
                                    <span>Lihat Semua</span> 
                                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                                </a>
                            </div>

                            @if($recentProjects->count() > 0)
                                <div class="space-y-3">
                                    @foreach($recentProjects as $project)
                                    <div class="modern-row flex items-center justify-between p-4 bg-slate-50/70 rounded-2xl border border-slate-100">
                                        <div class="min-w-0 flex-1 pr-4">
                                            <h4 class="text-sm font-bold text-slate-800 truncate">
                                                <a href="{{ route('company.projects.show', $project) }}" class="hover:text-brand transition">
                                                    {{ $project->project_name }}
                                                </a>
                                            </h4>
                                            <div class="flex flex-wrap items-center gap-3 mt-1.5 text-xs font-medium text-slate-400">
                                                @if($project->budget)
                                                <span class="flex items-center gap-1"><i class="fa-regular fa-money-bill-1 text-slate-400"></i>Rp {{ number_format($project->budget, 0, ',', '.') }}</span>
                                                @endif
                                                @if($project->deadline)
                                                <span class="flex items-center gap-1"><i class="fa-regular fa-calendar text-slate-400"></i>{{ \Carbon\Carbon::parse($project->deadline)->format('d M Y') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <span class="inline-flex items-center gap-1.5 text-[11px] font-bold px-3 py-1.5 rounded-full shrink-0
                                            @if($project->status == 'Open') bg-emerald-50 text-emerald-600 border border-emerald-200/60
                                            @elseif($project->status == 'Closed') bg-rose-50 text-rose-600 border border-rose-200/60
                                            @else bg-slate-100 text-slate-600 border border-slate-200 @endif
                                        ">
                                            @if($project->status == 'Open')
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                                            @endif
                                            {{ $project->status ?? 'Draft' }}
                                        </span>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="py-12 text-center">
                                    <div class="w-14 h-14 mx-auto mb-3 bg-slate-100 text-slate-400 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                                        <i class="fa-regular fa-folder-open"></i>
                                    </div>
                                    <h3 class="text-sm font-bold text-slate-700">Belum ada proyek</h3>
                                    <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto">Mulai buat proyek pertama Anda dan temukan talenta terbaik.</p>
                                    <a href="{{ route('company.projects.create') }}" class="btn-shimmer inline-flex items-center gap-2 mt-4 px-4 py-2 bg-brand text-white rounded-xl text-xs font-bold shadow-md shadow-brand/20">
                                        <i class="fa-solid fa-plus text-[10px]"></i> Buat Proyek
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>


                    {{-- KARTU PROPOSAL MASUK --}}
                    <div class="reveal reveal-7 bg-white border border-slate-200/85 rounded-3xl p-5 sm:p-6 lg:p-7 shadow-sm flex flex-col justify-between w-full">
                        <div>
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-3">
                                    <span class="section-accent h-7"></span>
                                    <div>
                                        <h2 class="font-extrabold text-slate-900 text-lg">Proposal Masuk</h2>
                                        <p class="text-xs text-slate-400 font-medium">Tawaran & lamaran dari freelancer</p>
                                    </div>
                                </div>
                                <a href="{{ route('company.projects.index') }}" class="text-xs text-brand font-bold hover:text-brand-dark flex items-center gap-1.5 group bg-brand/5 px-3 py-1.5 rounded-xl transition">
                                    <span>Kelola</span> 
                                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                                </a>
                            </div>

                            @if($incomingProposals->count() > 0)
                                <div class="space-y-3">
                                    @foreach($incomingProposals as $proposal)
                                    <div class="modern-row flex items-center justify-between p-4 bg-slate-50/70 rounded-2xl border border-slate-100">
                                        <div class="min-w-0 flex-1 pr-4">
                                            <h4 class="text-sm font-bold text-slate-800 truncate">
                                                {{ $proposal->freelancer->name ?? 'Freelancer' }}
                                            </h4>
                                            <div class="flex flex-wrap items-center gap-3 mt-1.5 text-xs font-medium text-slate-400">
                                                <span class="truncate max-w-[140px]"><i class="fa-regular fa-file-lines mr-1"></i>{{ $proposal->project->project_name ?? '-' }}</span>
                                                <span class="text-slate-600 font-semibold"><i class="fa-regular fa-money-bill-1 mr-1 text-slate-400"></i>Rp {{ number_format($proposal->harga_penawaran, 0, ',', '.') }}</span>
                                                <span><i class="fa-regular fa-clock mr-1 text-slate-400"></i>{{ $proposal->estimasi_hari }} hari</span>
                                            </div>
                                        </div>
                                        <span class="inline-flex items-center gap-1.5 text-[11px] font-bold px-3 py-1.5 rounded-full shrink-0
                                            @if($proposal->status == 'Menunggu') bg-amber-50 text-amber-700 border border-amber-200/60
                                            @elseif($proposal->status == 'Diterima') bg-emerald-50 text-emerald-600 border border-emerald-200/60
                                            @else bg-rose-50 text-rose-600 border border-rose-200/60 @endif
                                        ">
                                            {{ $proposal->status }}
                                        </span>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="py-12 text-center">
                                    <div class="w-14 h-14 mx-auto mb-3 bg-slate-100 text-slate-400 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                                        <i class="fa-regular fa-envelope"></i>
                                    </div>
                                    <h3 class="text-sm font-bold text-slate-700">Belum ada proposal</h3>
                                    <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto">Penawaran dan lamaran dari freelancer ahli akan muncul secara instan di sini.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

            </div>

        </main>

        {{-- FOOTER --}}
        @include('navbar.footer')

    </div>

    <script>
        // Smooth count-up animation script for stats
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-count]').forEach(function (el) {
                const target = parseInt(el.getAttribute('data-count'), 10) || 0;
                const duration = 1200; // Durasi animasi dalam milidetik
                const start = performance.now();
                
                function step(now) {
                    const progress = Math.min((now - start) / duration, 1);
                    // Easing cubic out for professional feel
                    const eased = 1 - Math.pow(1 - progress, 3);
                    
                    const currentVal = Math.round(target * eased);
                    el.textContent = currentVal.toLocaleString('id-ID');
                    
                    if (progress < 1) {
                        requestAnimationFrame(step);
                    }
                }
                requestAnimationFrame(step);
            });
        });
    </script>

</body>
</html>