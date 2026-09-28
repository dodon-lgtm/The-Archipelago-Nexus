@extends('layouts.admin')

@section('title', 'Dashboard Control Center')
@section('breadcrumb', 'Dashboard')

@section('content')
    <style>
        .cc-scroll::-webkit-scrollbar { width: 4px; }
        .cc-scroll::-webkit-scrollbar-track { background: transparent; }
        .cc-scroll::-webkit-scrollbar-thumb { background: rgba(148, 163, 184, .35); border-radius: 9999px; }
        @keyframes cc-drift {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(18px, -22px) scale(1.06); }
        }
        .cc-drift { animation: cc-drift 16s ease-in-out infinite; }
        .cc-drift-alt { animation: cc-drift 12s ease-in-out infinite reverse; }
    </style>

    {{-- ═══════════════ HERO / COMMAND STRIP ═══════════════ --}}
    <section class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 dark:from-slate-900 dark:via-slate-900 dark:to-blue-950 p-6 sm:p-8 mb-6 shadow-xl shadow-blue-900/10">
        <div class="absolute -right-16 -top-20 w-72 h-72 rounded-full bg-cyan-400/20 blur-3xl pointer-events-none cc-drift"></div>
        <div class="absolute -left-20 -bottom-24 w-72 h-72 rounded-full bg-indigo-400/20 blur-3xl pointer-events-none cc-drift-alt"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="min-w-0">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 border border-white/15 backdrop-blur-md">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
                    </span>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-blue-100">Control Center Online</span>
                </div>

                <h1 class="mt-4 text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-white">
                    Halo, {{ auth()->user()->name ?? 'Administrator' }}
                </h1>
                <p class="mt-2 text-xs sm:text-sm text-blue-100/80 font-medium max-w-2xl leading-relaxed">
                    Ringkasan performa platform, verifikasi akun mitra, dan pengawasan aktivitas ekosistem ApexForge Labs.
                </p>

                <div class="mt-4 flex flex-wrap items-center gap-x-5 gap-y-2 text-[11px] font-semibold text-blue-100/70">
                    <span class="inline-flex items-center gap-1.5"><i class="fa-regular fa-calendar"></i> {{ now()->translatedFormat('l, d F Y') }}</span>
                    <span class="inline-flex items-center gap-1.5"><i class="fa-regular fa-clock"></i> {{ now()->format('H:i') }} WIB</span>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <a href="{{ route('admin.company-account-requests.index') }}"
                    class="inline-flex items-center gap-2.5 px-5 py-3 rounded-2xl text-xs font-extrabold text-white bg-white/10 border border-white/20 backdrop-blur-md hover:bg-white/20 transition-all">
                    <i class="fa-solid fa-building-circle-check"></i>
                    Verifikasi Perusahaan
                    @if($pendingCompanyRequests > 0)
                        <span class="ml-0.5 min-w-[22px] h-[22px] px-1.5 rounded-lg bg-amber-400 text-slate-900 text-[10px] font-black inline-flex items-center justify-center">{{ $pendingCompanyRequests }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.projects.index') }}"
                    class="inline-flex items-center gap-2.5 px-5 py-3 rounded-2xl text-xs font-extrabold text-blue-700 bg-white hover:bg-blue-50 transition-all shadow-lg shadow-blue-900/20">
                    <i class="fa-solid fa-diagram-project"></i> Kelola Proyek
                </a>
            </div>
        </div>
    </section>

    {{-- ═══════════════ KEY METRICS ═══════════════ --}}
    @php
        $metrics = [
            ['title' => 'Pengguna',       'value' => $totalUsers,             'icon' => 'fa-users',        'desc' => 'Akun Terdaftar',    'tone' => 'bg-blue-50 text-blue-600 dark:bg-blue-950/50 dark:text-blue-400',             'dot' => 'bg-blue-500'],
            ['title' => 'Freelancer',     'value' => $totalFreelancers,       'icon' => 'fa-user-tie',     'desc' => 'Talenta Aktif',     'tone' => 'bg-cyan-50 text-cyan-600 dark:bg-cyan-950/50 dark:text-cyan-400',             'dot' => 'bg-cyan-500'],
            ['title' => 'Perusahaan',     'value' => $totalCompanies,         'icon' => 'fa-building',     'desc' => 'Mitra Usaha',       'tone' => 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400',     'dot' => 'bg-indigo-500'],
            ['title' => 'Total Proyek',   'value' => $totalProjects,          'icon' => 'fa-folder-open',  'desc' => 'Proyek Dibuat',     'tone' => 'bg-blue-50 text-blue-600 dark:bg-blue-950/50 dark:text-blue-400',             'dot' => 'bg-blue-500'],
            ['title' => 'Proyek Selesai', 'value' => $totalCompletedProjects, 'icon' => 'fa-circle-check', 'desc' => 'Workspace Selesai', 'tone' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400', 'dot' => 'bg-emerald-500'],
            ['title' => 'Penawaran',      'value' => $totalPenawarans,        'icon' => 'fa-file-invoice', 'desc' => 'Proposal Masuk',    'tone' => 'bg-violet-50 text-violet-600 dark:bg-violet-950/50 dark:text-violet-400',     'dot' => 'bg-violet-500'],
            ['title' => 'Laporan',        'value' => $totalReports,           'icon' => 'fa-flag',         'desc' => 'Aduan Sistem',      'tone' => 'bg-rose-50 text-rose-600 dark:bg-rose-950/50 dark:text-rose-400',             'dot' => 'bg-rose-500'],
        ];
    @endphp

    <section class="rounded-xl overflow-hidden border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-[#0F172A] mb-6">
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7 divide-x divide-y xl:divide-y-0 divide-slate-200/80 dark:divide-slate-800">
            @foreach($metrics as $metric)
                <div class="group min-w-0 p-4 sm:p-5 flex flex-col items-center justify-center text-center hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                    <span class="shrink-0 w-9 h-9 rounded-xl flex items-center justify-center text-sm {{ $metric['tone'] }} group-hover:scale-110 transition-transform">
                        <i class="fa-solid {{ $metric['icon'] }}"></i>
                    </span>
                    <p class="mt-2.5 w-full text-[10px] font-extrabold uppercase tracking-widest text-slate-400 dark:text-slate-500 truncate">{{ $metric['title'] }}</p>
                    <p class="mt-1 w-full text-xl sm:text-2xl font-extrabold tracking-tight tabular-nums text-slate-900 dark:text-white truncate">{{ number_format($metric['value']) }}</p>
                    <div class="mt-2 flex items-center justify-center gap-1.5 min-w-0 max-w-full">
                        <span class="shrink-0 w-1.5 h-1.5 rounded-full {{ $metric['dot'] }}"></span>
                        <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 truncate">{{ $metric['desc'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ═══════════════ ADMIN WALLET SUMMARY ═══════════════ --}}
    <section class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 via-slate-900 to-blue-950 border border-slate-800 p-6 sm:p-8 mb-6 shadow-xl shadow-slate-900/10">
        <div class="absolute -top-24 -right-24 w-80 h-80 rounded-full bg-cyan-500/15 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-28 -left-20 w-72 h-72 rounded-full bg-blue-600/15 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col xl:flex-row xl:items-center justify-between gap-8">
            <div>
                <div class="flex items-center gap-3">
                    <span class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-cyan-400 to-blue-500 text-white flex items-center justify-center shadow-lg shadow-cyan-500/30">
                        <i class="fa-solid fa-wallet"></i>
                    </span>
                    <div>
                        <p class="text-[10px] font-extrabold uppercase tracking-widest text-cyan-300">Saldo Wallet Admin</p>
                        <p class="text-[11px] font-semibold text-slate-400">Dihitung dari wallet_ledger (credit &minus; debit)</p>
                    </div>
                </div>

                <p id="adminWalletBalance" data-balance="{{ $walletBalance }}"
                    class="mt-4 text-3xl sm:text-4xl font-extrabold tracking-tight text-white">
                    Rp {{ number_format($walletBalance, 0, ',', '.') }}
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-10 gap-y-4">
                <div>
                    <p class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-1">Total Pemasukan</p>
                    <p id="statIncome" data-value="{{ $walletIncome }}" class="text-base font-extrabold text-emerald-400 tracking-tight">+ Rp {{ number_format($walletIncome, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-1">Total Pengeluaran</p>
                    <p id="statExpense" data-value="{{ $walletExpense }}" class="text-base font-extrabold text-rose-400 tracking-tight">- Rp {{ number_format($walletExpense, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-1">Pemasukan Bulan Ini</p>
                    <p id="statMonthIncome" data-value="{{ $walletMonthlyIncome }}" class="text-xs font-bold text-emerald-300/90">{{ now()->translatedFormat('M Y') }} &middot; + Rp {{ number_format($walletMonthlyIncome, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-1">Pengeluaran Bulan Ini</p>
                    <p id="statMonthExpense" data-value="{{ $walletMonthlyExpense }}" class="text-xs font-bold text-rose-300/90">{{ now()->translatedFormat('M Y') }} &middot; - Rp {{ number_format($walletMonthlyExpense, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="flex flex-col gap-3 shrink-0 min-w-[220px]">
                <button type="button" onclick="openWithdrawModal()"
                    class="inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-2xl text-xs font-extrabold text-slate-900 bg-cyan-400 hover:bg-cyan-300 transition-all shadow-lg shadow-cyan-500/25">
                    <i class="fa-solid fa-arrow-up-from-bracket"></i> Tarik Saldo
                </button>
                <a href="{{ route('admin.wallet.index') }}"
                    class="inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-2xl text-xs font-bold text-cyan-100 border border-cyan-400/25 bg-white/5 hover:bg-white/10 transition-all">
                    Lihat Semua Transaksi <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- ═══════════════ DATA BOARDS ═══════════════ --}}
    <div class="space-y-6">

        <section class="rounded-xl bg-white dark:bg-[#0F172A] border border-slate-200/80 dark:border-slate-800 overflow-hidden">
            <div class="px-6 py-4 flex items-center gap-3 border-b border-slate-100 dark:border-slate-800">
                <span class="w-1.5 h-4 rounded-full bg-blue-600"></span>
                <h2 class="text-[11px] font-extrabold uppercase tracking-widest text-slate-500 dark:text-slate-400 truncate">Operasional Harian</h2>
                <span class="ml-auto hidden sm:block text-[10px] font-bold text-slate-400 dark:text-slate-500 truncate">Verifikasi mitra &amp; publikasi proyek</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-slate-200/80 dark:divide-slate-800">
                {{-- ── 1. Verifikasi Perusahaan ── --}}
                <div class="flex flex-col min-w-0">
                    <div class="px-6 py-5 flex items-center justify-between gap-3 border-b border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-sm shadow-blue-600/30">
                                <i class="fa-solid fa-building-circle-check"></i>
                            </span>
                            <div class="min-w-0">
                                <h2 class="text-sm font-extrabold tracking-tight text-slate-900 dark:text-white truncate">Verifikasi Perusahaan</h2>
                                <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 truncate">Permintaan pendaftaran akun mitra bisnis</p>
                            </div>
                        </div>
                        @if($pendingCompanyRequests > 0)
                            <span class="shrink-0 inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-400 text-[10px] font-extrabold uppercase tracking-wider border border-amber-200/70 dark:border-amber-900/60">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                {{ $pendingCompanyRequests }} Antrean
                            </span>
                        @endif
                    </div>

                    <div class="flex-1 p-5 overflow-y-auto cc-scroll max-h-[24rem]">
                        <div class="space-y-2.5">
                            @forelse($recentRequests as $req)
                                <div class="group flex items-center justify-between gap-3 p-3.5 rounded-xl border border-slate-100 dark:border-slate-800 hover:border-blue-200 dark:hover:border-blue-900 hover:bg-blue-50/40 dark:hover:bg-blue-950/20 transition-all">
                                    <div class="min-w-0">
                                        <p class="text-xs font-extrabold text-slate-900 dark:text-white truncate">{{ $req->company_name }}</p>
                                        <div class="mt-1.5 flex flex-wrap items-center gap-x-3 gap-y-1 text-[11px] font-semibold text-slate-500 dark:text-slate-400">
                                            <span class="inline-flex items-center gap-1.5 truncate"><i class="fa-regular fa-user text-slate-400"></i>{{ $req->contact_person }}</span>
                                            <span class="hidden sm:inline w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                                            <span class="inline-flex items-center gap-1.5 truncate"><i class="fa-regular fa-envelope text-slate-400"></i>{{ $req->company_email }}</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.company-account-requests.show', $req) }}"
                                        class="shrink-0 w-9 h-9 rounded-xl flex items-center justify-center bg-slate-50 dark:bg-slate-800/60 text-slate-400 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                        <i class="fa-solid fa-arrow-right text-xs"></i>
                                    </a>
                                </div>
                            @empty
                                <div class="py-14 flex flex-col items-center justify-center text-slate-400">
                                    <div class="w-14 h-14 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 flex items-center justify-center mb-3">
                                        <i class="fa-solid fa-circle-check text-xl text-slate-300 dark:text-slate-600"></i>
                                    </div>
                                    <p class="text-xs font-bold">Semua permintaan terselesaikan.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-900/40 text-center mt-auto">
                        <a href="{{ route('admin.company-account-requests.index') }}" class="text-[11px] font-extrabold uppercase tracking-widest text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors">
                            Lihat Selengkapnya <i class="fa-solid fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>

                {{-- ── 2. Proyek Aktif ── --}}
                <div class="flex flex-col min-w-0">
                    <div class="px-6 py-5 flex items-center gap-3 border-b border-slate-100 dark:border-slate-800">
                        <span class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center shrink-0 shadow-sm shadow-indigo-600/30">
                            <i class="fa-solid fa-folder-open"></i>
                        </span>
                        <div class="min-w-0">
                            <h2 class="text-sm font-extrabold tracking-tight text-slate-900 dark:text-white truncate">Proyek Aktif</h2>
                            <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 truncate">Aktivitas publikasi terbaru</p>
                        </div>
                    </div>

                    <div class="flex-1 p-5 overflow-y-auto cc-scroll max-h-[24rem]">
                        <div class="relative space-y-3 before:absolute before:left-[13px] before:top-3 before:bottom-3 before:w-px before:bg-slate-200 dark:before:bg-slate-800">
                            @forelse($recentProjects as $project)
                                <div class="group relative pl-10">
                                    <span class="absolute left-0 top-2 w-7 h-7 rounded-xl bg-white dark:bg-[#0F172A] border border-slate-200 dark:border-slate-700 flex items-center justify-center z-10 group-hover:border-blue-400 transition-colors">
                                        <span class="w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-600 group-hover:bg-blue-500 transition-colors"></span>
                                    </span>
                                    <div class="rounded-xl border border-slate-100 dark:border-slate-800 p-3.5 group-hover:border-blue-200 dark:group-hover:border-blue-900 transition-colors">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="min-w-0">
                                                <p class="text-xs font-extrabold text-slate-900 dark:text-white truncate">{{ $project->project_name }}</p>
                                                <p class="mt-1 text-[11px] font-semibold text-slate-500 dark:text-slate-400 truncate"><i class="fa-regular fa-building mr-1"></i>{{ $project->owner->name ?? '—' }}</p>
                                            </div>
                                            <span class="shrink-0 text-[10px] px-2 py-1 rounded-lg font-extrabold uppercase tracking-wider
                                                @if($project->status == 'open') bg-blue-50 text-blue-600 dark:bg-blue-950/50 dark:text-blue-400
                                                @else bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400 @endif">
                                                {{ \App\Models\Project::statusLabel($project->status ?? 'open') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="pl-10 py-12 flex flex-col items-start gap-3 text-slate-400">
                                    <div class="w-12 h-12 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 flex items-center justify-center">
                                        <i class="fa-solid fa-folder-open text-lg text-slate-300 dark:text-slate-600"></i>
                                    </div>
                                    <p class="text-xs font-bold">Belum ada proyek terbaru.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-900/40 text-center mt-auto">
                        <a href="{{ route('admin.projects.index') }}" class="text-[11px] font-extrabold uppercase tracking-widest text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors">
                            Eksplorasi Proyek <i class="fa-solid fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>

            </div>
        </section>

        <section class="rounded-xl bg-white dark:bg-[#0F172A] border border-slate-200/80 dark:border-slate-800 overflow-hidden">
            <div class="px-6 py-4 flex items-center gap-3 border-b border-slate-100 dark:border-slate-800">
                <span class="w-1.5 h-4 rounded-full bg-blue-600"></span>
                <h2 class="text-[11px] font-extrabold uppercase tracking-widest text-slate-500 dark:text-slate-400 truncate">Proposal &amp; Aduan</h2>
                <span class="ml-auto hidden sm:block text-[10px] font-bold text-slate-400 dark:text-slate-500 truncate">Aliran penawaran &amp; laporan sistem</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-slate-200/80 dark:divide-slate-800">
                {{-- ── 3. Proposal Masuk ── --}}
                <div class="flex flex-col min-w-0">
                    <div class="px-6 py-5 flex items-center gap-3 border-b border-slate-100 dark:border-slate-800">
                        <span class="w-10 h-10 rounded-xl bg-violet-600 text-white flex items-center justify-center shrink-0 shadow-sm shadow-violet-600/30">
                            <i class="fa-solid fa-file-invoice"></i>
                        </span>
                        <div class="min-w-0">
                            <h2 class="text-sm font-extrabold tracking-tight text-slate-900 dark:text-white truncate">Proposal Masuk</h2>
                            <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 truncate">Tawaran freelancer terkini</p>
                        </div>
                    </div>

                    <div class="flex-1 p-5 space-y-2.5">
                        @forelse($recentPenawarans as $penawaran)
                            <div class="group flex items-center justify-between gap-3 p-3.5 rounded-xl border border-slate-100 dark:border-slate-800 hover:border-blue-200 dark:hover:border-blue-900 hover:bg-blue-50/40 dark:hover:bg-blue-950/20 transition-all">
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-extrabold text-slate-900 dark:text-white truncate">{{ $penawaran->freelancer->name ?? '—' }}</p>
                                    <p class="mt-1 text-[11px] font-semibold text-slate-500 dark:text-slate-400 truncate">{{ $penawaran->project->project_name ?? '—' }}</p>
                                    <p class="mt-1.5 text-xs font-extrabold text-blue-600 dark:text-blue-400">Rp {{ number_format($penawaran->harga_penawaran) }}</p>
                                </div>
                                <span class="shrink-0 text-[10px] px-2.5 py-1.5 rounded-xl font-extrabold uppercase tracking-wider border
                                    @if($penawaran->status == 'Diterima') bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-900/60
                                    @elseif($penawaran->status == 'Ditolak') bg-rose-50 text-rose-600 border-rose-100 dark:bg-rose-950/40 dark:text-rose-400 dark:border-rose-900/60
                                    @else bg-slate-50 text-slate-500 border-slate-200 dark:bg-slate-800/60 dark:text-slate-400 dark:border-slate-700 @endif">
                                    {{ $penawaran->status }}
                                </span>
                            </div>
                        @empty
                            <div class="py-14 text-center text-xs font-bold text-slate-400">Belum ada penawaran.</div>
                        @endforelse
                    </div>

                    <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-900/40 text-center mt-auto">
                        <a href="{{ route('admin.penawarans.index') }}" class="text-[11px] font-extrabold uppercase tracking-widest text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors">
                            Kelola Proposal <i class="fa-solid fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>

                {{-- ── 4. Radar Aduan Sistem ── --}}
                <div class="flex flex-col min-w-0">
                    <div class="px-6 py-5 flex items-center gap-3 border-b border-slate-100 dark:border-slate-800">
                        <span class="w-10 h-10 rounded-xl bg-rose-600 text-white flex items-center justify-center shrink-0 shadow-sm shadow-rose-600/30">
                            <i class="fa-solid fa-shield-halved"></i>
                        </span>
                        <div class="min-w-0">
                            <h2 class="text-sm font-extrabold tracking-tight text-slate-900 dark:text-white truncate">Radar Aduan Sistem</h2>
                            <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 truncate">Pengawasan masalah &amp; laporan terkini</p>
                        </div>
                    </div>

                    <div class="flex-1 p-5 grid gap-3 grid-cols-1 md:grid-cols-2 overflow-y-auto cc-scroll max-h-[24rem]">
                        @forelse($recentReports as $report)
                            <div class="rounded-xl border border-slate-100 dark:border-slate-800 p-4 flex flex-col justify-between gap-3 hover:border-blue-200 dark:hover:border-blue-900 transition-colors">
                                <div>
                                    <div class="flex items-start justify-between gap-2 mb-2.5">
                                        <span class="text-[10px] px-2 py-1 rounded-lg font-extrabold uppercase tracking-wider
                                            @if($report->status == 'menunggu') bg-amber-50 text-amber-700 dark:bg-amber-950/50 dark:text-amber-400
                                            @elseif($report->status == 'diproses') bg-blue-50 text-blue-700 dark:bg-blue-950/50 dark:text-blue-400
                                            @elseif($report->status == 'selesai') bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400
                                            @else bg-slate-50 text-slate-600 dark:bg-slate-800/60 dark:text-slate-400 @endif">
                                            {{ ucfirst($report->status) }}
                                        </span>
                                        <span class="shrink-0 text-[10px] font-bold text-slate-400"><i class="fa-regular fa-clock mr-1"></i>{{ $report->created_at->format('d M y') }}</span>
                                    </div>
                                    <p class="text-xs font-extrabold text-slate-900 dark:text-white">{{ $report->subject }}</p>
                                </div>
                                <div class="pt-2.5 border-t border-slate-100 dark:border-slate-800 flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-slate-50 dark:bg-slate-800/60 flex items-center justify-center text-[10px] text-slate-400"><i class="fa-solid fa-user"></i></span>
                                    <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 truncate">{{ $report->reporter->name ?? 'Unknown' }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-14 flex flex-col items-center justify-center text-slate-400">
                                <div class="w-14 h-14 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 flex items-center justify-center mb-3">
                                    <i class="fa-solid fa-shield-check text-xl text-slate-300 dark:text-slate-600"></i>
                                </div>
                                <p class="text-xs font-bold">Ekosistem aman. Belum ada aduan masuk.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-900/40 text-center mt-auto">
                        <a href="{{ route('admin.reports.index') }}" class="text-[11px] font-extrabold uppercase tracking-widest text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors">
                            Buka Pusat Aduan <i class="fa-solid fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>

            </div>
        </section>

        <section class="rounded-xl bg-white dark:bg-[#0F172A] border border-slate-200/80 dark:border-slate-800 overflow-hidden">
            <div class="px-6 py-4 flex items-center gap-3 border-b border-slate-100 dark:border-slate-800">
                <span class="w-1.5 h-4 rounded-full bg-blue-600"></span>
                <h2 class="text-[11px] font-extrabold uppercase tracking-widest text-slate-500 dark:text-slate-400 truncate">Pengguna &amp; Pintasan</h2>
                <span class="ml-auto hidden sm:block text-[10px] font-bold text-slate-400 dark:text-slate-500 truncate">Registrasi terbaru &amp; modul administrasi</span>
            </div>
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-px bg-slate-100 dark:bg-slate-800">
                {{-- ── 5. Pengguna Terbaru ── --}}
                <div class="bg-white dark:bg-[#0F172A] flex flex-col min-w-0 xl:col-span-5">
                    <div class="px-6 py-5 flex items-center justify-between gap-3 border-b border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="w-10 h-10 rounded-xl bg-cyan-600 text-white flex items-center justify-center shrink-0 shadow-sm shadow-cyan-600/30">
                                <i class="fa-solid fa-user-plus"></i>
                            </span>
                            <div class="min-w-0">
                                <h2 class="text-sm font-extrabold tracking-tight text-slate-900 dark:text-white truncate">Pengguna Terbaru</h2>
                                <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 truncate">Registrasi akun terakhir</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.users.index') }}" class="shrink-0 text-[11px] font-extrabold uppercase tracking-widest text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors">
                            Semua
                        </a>
                    </div>

                    <div class="flex-1 p-5 space-y-2.5 overflow-y-auto cc-scroll max-h-[24rem]">
                        @forelse($recentUsers as $user)
                            <div class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 dark:border-slate-800 hover:border-blue-200 dark:hover:border-blue-900 transition-colors">
                                <span class="shrink-0 w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white text-xs font-extrabold flex items-center justify-center uppercase">
                                    {{ substr($user->name ?? 'U', 0, 1) }}
                                </span>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-extrabold text-slate-900 dark:text-white truncate">{{ $user->name }}</p>
                                    <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 truncate">{{ $user->email }}</p>
                                </div>
                                <span class="shrink-0 text-[10px] px-2 py-1 rounded-lg font-extrabold uppercase tracking-wider
                                    @if(($user->role ?? '') === 'admin') bg-rose-50 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400
                                    @elseif(($user->role ?? '') === 'company') bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400
                                    @else bg-cyan-50 text-cyan-600 dark:bg-cyan-950/40 dark:text-cyan-400 @endif">
                                    {{ $user->role ?? 'user' }}
                                </span>
                            </div>
                        @empty
                            <div class="py-14 flex flex-col items-center justify-center text-slate-400">
                                <div class="w-14 h-14 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 flex items-center justify-center mb-3">
                                    <i class="fa-solid fa-users text-xl text-slate-300 dark:text-slate-600"></i>
                                </div>
                                <p class="text-xs font-bold">Belum ada pengguna terdaftar.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- ── 6. Aksi Cepat ── --}}
                <div class="bg-white dark:bg-[#0F172A] flex flex-col min-w-0 xl:col-span-7">
                    <div class="px-6 py-5 flex items-center gap-3 border-b border-slate-100 dark:border-slate-800">
                        <span class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-sm shadow-amber-500/30">
                            <i class="fa-solid fa-bolt"></i>
                        </span>
                        <div class="min-w-0">
                            <h2 class="text-sm font-extrabold tracking-tight text-slate-900 dark:text-white truncate">Aksi Cepat</h2>
                            <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 truncate">Pintasan modul administrasi</p>
                        </div>
                    </div>

                    @php
                        $quickActions = [
                            ['route' => 'admin.users.index',             'label' => 'Pengguna',         'icon' => 'fa-users',            'tone' => 'bg-blue-50 text-blue-600 dark:bg-blue-950/50 dark:text-blue-400'],
                            ['route' => 'admin.categories.index',        'label' => 'Kategori',         'icon' => 'fa-tags',             'tone' => 'bg-cyan-50 text-cyan-600 dark:bg-cyan-950/50 dark:text-cyan-400'],
                            ['route' => 'admin.projects.index',          'label' => 'Proyek',           'icon' => 'fa-folder-open',      'tone' => 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400'],
                            ['route' => 'admin.penawarans.index',        'label' => 'Penawaran',        'icon' => 'fa-file-invoice',     'tone' => 'bg-violet-50 text-violet-600 dark:bg-violet-950/50 dark:text-violet-400'],
                            ['route' => 'admin.hasil-pekerjaan.index',   'label' => 'Hasil Pekerjaan',  'icon' => 'fa-clipboard-check',  'tone' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400'],
                            ['route' => 'admin.payments.index',          'label' => 'Pembayaran',       'icon' => 'fa-credit-card',      'tone' => 'bg-sky-50 text-sky-600 dark:bg-sky-950/50 dark:text-sky-400'],
                            ['route' => 'admin.withdrawals.index',       'label' => 'Penarikan',        'icon' => 'fa-money-bill-transfer', 'tone' => 'bg-teal-50 text-teal-600 dark:bg-teal-950/50 dark:text-teal-400'],
                            ['route' => 'admin.reports.index',           'label' => 'Pusat Aduan',      'icon' => 'fa-shield-halved',    'tone' => 'bg-rose-50 text-rose-600 dark:bg-rose-950/50 dark:text-rose-400'],
                            ['route' => 'admin.financial-settings.edit', 'label' => 'Pengaturan Biaya', 'icon' => 'fa-sliders',          'tone' => 'bg-amber-50 text-amber-600 dark:bg-amber-950/50 dark:text-amber-400'],
                        ];
                    @endphp

                    <div class="flex-1 p-5 grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($quickActions as $action)
                            <a href="{{ route($action['route']) }}"
                                class="group flex flex-col gap-2.5 p-3.5 rounded-xl border border-slate-100 dark:border-slate-800 hover:border-blue-200 dark:hover:border-blue-900 hover:bg-blue-50/40 dark:hover:bg-blue-950/20 transition-all">
                                <span class="w-9 h-9 rounded-xl flex items-center justify-center text-sm {{ $action['tone'] }} group-hover:scale-110 transition-transform">
                                    <i class="fa-solid {{ $action['icon'] }}"></i>
                                </span>
                                <span class="text-[11px] font-extrabold text-slate-700 dark:text-slate-200">{{ $action['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

            </div>
        </section>

        {{-- ── 7. Riwayat Transaksi Wallet Platform ── --}}
        <div class="rounded-xl bg-white dark:bg-[#0F172A] border border-slate-200/80 dark:border-slate-800 overflow-hidden flex flex-col">
            <div class="px-6 py-5 flex items-center justify-between gap-3 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-3 min-w-0">
                    <span class="w-10 h-10 rounded-xl bg-slate-900 dark:bg-slate-800 text-white flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </span>
                    <div class="min-w-0">
                        <h2 class="text-sm font-extrabold tracking-tight text-slate-900 dark:text-white truncate">Riwayat Transaksi Terbaru</h2>
                        <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 truncate">{{ $recentWalletTransactions->count() }} mutasi terakhir wallet platform</p>
                    </div>
                </div>
                <a href="{{ route('admin.wallet.index') }}"
                    class="shrink-0 inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-[11px] font-extrabold text-slate-700 dark:text-slate-200 bg-slate-50 dark:bg-slate-800/60 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i> Wallet
                </a>
            </div>

            <div class="hidden sm:grid grid-cols-[7rem_10rem_1fr_11rem] gap-x-5 px-6 py-3 bg-slate-50/60 dark:bg-slate-900/40 border-b border-slate-100 dark:border-slate-800 text-[10px] font-extrabold uppercase tracking-widest text-slate-400">
                <span>Waktu</span>
                <span>Jenis</span>
                <span>Keterangan</span>
                <span class="text-right">Nominal</span>
            </div>

            <div class="flex-1 divide-y divide-slate-100 dark:divide-slate-800 overflow-y-auto cc-scroll max-h-[26rem]">
                @forelse($recentWalletTransactions as $tx)
                    <div class="px-6 py-4 grid grid-cols-[auto_1fr_auto] sm:grid-cols-[7rem_10rem_1fr_11rem] items-center gap-x-5 gap-y-2 hover:bg-blue-50/40 dark:hover:bg-blue-950/20 transition-colors">
                        <div class="text-[11px] font-bold text-slate-400 leading-tight">
                            {{ $tx->created_at?->format('d M Y') ?? '-' }}
                            <span class="block font-semibold text-slate-300 dark:text-slate-600">{{ $tx->created_at?->format('H:i') ?? '' }}</span>
                        </div>

                        <div class="min-w-0">
                            <p class="text-xs font-extrabold text-slate-800 dark:text-slate-100 truncate">{{ $tx->type_label }}</p>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-0.5 truncate">{{ $tx->source ?? $tx->display_code }}</p>
                        </div>

                        <div class="hidden sm:block min-w-0 pr-4">
                            <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 truncate">{{ \Illuminate\Support\Str::limit($tx->description ?? '-', 90) }}</p>
                            <span class="inline-block mt-1.5 text-[9px] font-extrabold uppercase tracking-widest px-2 py-0.5 rounded-lg border
                                @if($tx->direction === \App\Models\WalletLedger::DIRECTION_CREDIT) bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-900/60 @else bg-rose-50 text-rose-600 border-rose-100 dark:bg-rose-950/40 dark:text-rose-400 dark:border-rose-900/60 @endif">
                                @if($tx->direction === \App\Models\WalletLedger::DIRECTION_CREDIT) Pemasukan (credit) @else Pengeluaran (debit) @endif
                            </span>
                        </div>

                        <div class="text-right">
                            @if($tx->direction === \App\Models\WalletLedger::DIRECTION_CREDIT)
                                <p class="text-sm font-extrabold text-emerald-600 dark:text-emerald-400 tracking-tight">+ Rp{{ number_format($tx->amount, 0, ',', '.') }}</p>
                            @else
                                <p class="text-sm font-extrabold text-rose-600 dark:text-rose-400 tracking-tight">- Rp{{ number_format($tx->amount, 0, ',', '.') }}</p>
                            @endif
                            <p class="text-[10px] font-semibold text-slate-400 mt-0.5">Saldo: Rp {{ number_format($tx->balance_after ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="py-14 text-center flex flex-col items-center justify-center text-slate-400">
                        <div class="w-14 h-14 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 flex items-center justify-center mb-3">
                            <i class="fa-solid fa-wallet text-xl text-slate-300 dark:text-slate-600"></i>
                        </div>
                        <p class="text-xs font-bold">Belum ada transaksi wallet platform.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ═══════════════ MODAL TARIK SALDO ADMIN ═══════════════ --}}
    <div id="withdrawModal" class="fixed inset-0 z-[120] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-950/70 backdrop-blur-sm" onclick="closeWithdrawModal()"></div>

        <div class="relative w-full max-w-md bg-white dark:bg-[#0F172A] rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-2xl">
            <div class="px-6 py-5 bg-gradient-to-r from-slate-900 to-blue-900 relative overflow-hidden">
                <div class="absolute -top-16 -right-16 w-40 h-40 bg-cyan-500/20 rounded-full blur-3xl"></div>
                <div class="relative z-10 flex items-center gap-3.5">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-tr from-cyan-400 to-blue-500 flex items-center justify-center text-white shadow-lg shadow-cyan-500/30">
                        <i class="fa-solid fa-arrow-up-from-bracket"></i>
                    </span>
                    <div>
                        <h3 class="text-sm font-extrabold text-white tracking-tight">Tarik Saldo Admin</h3>
                        <p class="text-[11px] text-blue-200/80 font-medium mt-0.5">Tercatat sebagai debit wallet_ledger</p>
                    </div>
                </div>
            </div>

            <form id="withdrawForm" onsubmit="submitWithdraw(event)" class="p-6 space-y-5">
                <div class="flex items-center justify-between px-4 py-3 rounded-2xl bg-cyan-50 dark:bg-cyan-950/30 border border-cyan-100 dark:border-cyan-900/60">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-cyan-700 dark:text-cyan-400">Saldo Tersedia</span>
                    <span class="text-sm font-extrabold text-cyan-800 dark:text-cyan-200">Rp <span id="withdrawAvailable">{{ number_format($walletBalance, 0, ',', '.') }}</span></span>
                </div>

                <div>
                    <label class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-2">Nominal Penarikan</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">Rp</span>
                        <input type="text" id="withdrawAmount" name="amount" inputmode="numeric" placeholder="0"
                               oninput="formatWithdrawAmount(this)"
                               class="w-full pl-11 pr-4 py-3.5 text-sm font-bold text-slate-900 dark:text-white bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-950 focus:border-blue-500 transition-all" />
                    </div>
                    <p id="withdrawAmountError" class="hidden mt-1.5 text-[11px] font-semibold text-rose-500"></p>
                </div>

                <div>
                    <label class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-2">Metode</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="method" value="bank" checked class="peer sr-only" onchange="toggleWithdrawPlaceholder()" />
                            <span class="flex items-center justify-center gap-2 py-3 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-bold text-slate-500 dark:text-slate-400 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 transition-all">
                                <i class="fa-solid fa-building-columns"></i> Bank
                            </span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="method" value="ewallet" class="peer sr-only" onchange="toggleWithdrawPlaceholder()" />
                            <span class="flex items-center justify-center gap-2 py-3 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-bold text-slate-500 dark:text-slate-400 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 transition-all">
                                <i class="fa-solid fa-mobile-screen"></i> E-Wallet
                            </span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-2">Nama Pemilik Rekening</label>
                    <input type="text" name="account_name" placeholder="Nama sesuai rekening"
                           class="w-full px-4 py-3.5 text-sm font-semibold text-slate-900 dark:text-white bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-950 focus:border-blue-500 transition-all" />
                </div>

                <div>
                    <label class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-2"><span id="withdrawAccountLabel">Nomor Rekening Bank</span></label>
                    <input type="text" id="withdrawAccountNumber" name="account_number" placeholder="1234567890"
                           class="w-full px-4 py-3.5 text-sm font-semibold text-slate-900 dark:text-white bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl focus:outline-none focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-950 focus:border-blue-500 transition-all" />
                </div>

                <div class="flex items-center gap-3 pt-1">
                    <button type="button" onclick="closeWithdrawModal()"
                            class="flex-1 py-3.5 rounded-2xl text-sm font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="withdrawSubmitBtn"
                            class="flex-[1.4] py-3.5 rounded-2xl text-sm font-extrabold text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-600/25 transition-all disabled:opacity-60 disabled:pointer-events-none">
                        <i class="fa-solid fa-arrow-up-from-bracket mr-2"></i>Tarik Saldo
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Toast Container — TANPA alert() --}}
    <div id="toastContainer" class="fixed top-6 right-6 z-[200] flex flex-col gap-3 pointer-events-none"></div>

    <script>
        // ─── TOAST (pengganti alert()) ───────────────────────────────
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            if (!container) return;
            const toast = document.createElement('div');
            const isOk  = type === 'success';
            toast.className = 'pointer-events-auto flex items-center gap-3 px-5 py-4 rounded-2xl shadow-2xl backdrop-blur-xl border transform translate-x-10 opacity-0 transition-all duration-500 '
                + (isOk ? 'bg-slate-900/95 border-emerald-400/30 text-white' : 'bg-rose-600/95 border-rose-300/40 text-white');
            toast.innerHTML = `<i class="fa-solid ${isOk ? 'fa-circle-check text-emerald-300' : 'fa-circle-exclamation text-rose-100'} text-lg"></i>`
                + `<span class="text-xs font-bold tracking-tight max-w-xs">${message}</span>`;
            container.appendChild(toast);
            setTimeout(() => toast.classList.remove('translate-x-10', 'opacity-0'), 10);
            setTimeout(() => {
                toast.classList.add('translate-x-10', 'opacity-0');
                setTimeout(() => toast.remove(), 500);
            }, 4200);
        }

        // ─── MODAL TARIK SALDO ──────────────────────────────────────
        const fmtIDR = n => Number(n || 0).toLocaleString('id-ID');

        function openWithdrawModal() {
            const m = document.getElementById('withdrawModal');
            if (!m) return;
            m.classList.remove('hidden');
            m.classList.add('flex');
        }

        function closeWithdrawModal() {
            const m = document.getElementById('withdrawModal');
            if (!m) return;
            m.classList.add('hidden');
            m.classList.remove('flex');
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeWithdrawModal();
        });

        function formatWithdrawAmount(input) {
            const digits = input.value.replace(/[^\d]/g, '');
            input.value = digits ? parseInt(digits, 10).toLocaleString('id-ID') : '';
        }

        function toggleWithdrawPlaceholder() {
            const checked = document.querySelector('#withdrawForm input[name="method"]:checked');
            if (!checked) return;
            const method = checked.value;
            document.getElementById('withdrawAccountLabel').textContent =
                method === 'bank' ? 'Nomor Rekening Bank' : 'Nomor E-Wallet';
            document.getElementById('withdrawAccountNumber').placeholder =
                method === 'bank' ? '1234567890' : '081234567890';
        }

        function updateBalanceDisplay(balance) {
            const el = document.getElementById('adminWalletBalance');
            if (el) {
                el.dataset.balance = balance;
                el.textContent = 'Rp ' + fmtIDR(Math.floor(balance));
            }
            const avail = document.getElementById('withdrawAvailable');
            if (avail) avail.textContent = fmtIDR(Math.floor(balance));
        }

        function adjustExpenseStats(amount) {
            ['statExpense', 'statMonthExpense'].forEach(id => {
                const el = document.getElementById(id);
                if (!el) return;
                const next = parseFloat(el.dataset.value || '0') + amount;
                el.dataset.value = next;
                const prefix = id === 'statExpense'
                    ? '- Rp '
                    : `{{ now()->translatedFormat('M Y') }} · - Rp `;
                el.textContent = prefix + fmtIDR(next);
            });
        }
    </script>

    <script>
        async function submitWithdraw(e) {
            e.preventDefault();
            const form  = document.getElementById('withdrawForm');
            const errEl = document.getElementById('withdrawAmountError');
            const btn   = document.getElementById('withdrawSubmitBtn');
            errEl.classList.add('hidden');

            // Validasi klien (server tetap otoritatif)
            const raw    = form.amount.value.replace(/[^\d]/g, '');
            const amount = parseInt(raw || '0', 10);
            const saldo  = parseFloat(document.getElementById('adminWalletBalance').dataset.balance || '0');

            if (!raw || isNaN(amount) || amount <= 0) {
                errEl.textContent = 'Nominal wajib diisi dan harus angka lebih besar dari 0.';
                errEl.classList.remove('hidden'); return;
            }
            if (amount > saldo) {
                errEl.textContent = `Nominal melebihi saldo tersedia (Rp ${fmtIDR(saldo)}).`;
                errEl.classList.remove('hidden'); return;
            }

            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Memproses...';

            try {
                const response = await fetch('{{ route("admin.wallet.withdraw") }}', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: new FormData(form),
                });

                const contentType = response.headers.get('content-type');
                const rawText     = await response.text();

                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error(`Server tidak mengembalikan JSON (${response.status}).`);
                }
                const data = JSON.parse(rawText);

                if (!response.ok || !data.success) {
                    showToast(data.message || 'Gagal menarik saldo.', 'error');
                    if (data.balance !== undefined) updateBalanceDisplay(data.balance);
                    return;
                }

                // Sukses — saldo dashboard langsung berubah + toast
                updateBalanceDisplay(data.balance);
                adjustExpenseStats(data.withdrawn_amount);
                showToast(data.message, 'success');
                closeWithdrawModal();
                setTimeout(() => location.reload(), 1500); // refresh riwayat debit
            } catch (err) {
                console.error('WITHDRAW ERROR:', err);
                showToast(err.message || 'Terjadi kesalahan jaringan.', 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-arrow-up-from-bracket mr-2"></i>Tarik Saldo';
            }
        }
    </script>
@endsection
