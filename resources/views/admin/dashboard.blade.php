@extends('layouts.admin')

@section('title', 'Dashboard Control Center')
@section('breadcrumb', 'Dashboard')

@section('content')
    {{-- SHADER & FUTURISTIC ANIMATION STYLES --}}
    <style>
        /* Ambient Shader Glows */
        .shader-glow-blue { box-shadow: 0 0 40px rgba(59, 130, 246, 0.25); }
        .shader-glow-cyan { box-shadow: 0 0 35px rgba(6, 182, 212, 0.3); }
        
        /* High-End Glassmorphism */
        .glass-panel-light {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 10px 40px -10px rgba(15, 23, 42, 0.1), inset 0 1px 0 rgba(255, 255, 255, 1);
        }

        .glass-panel-dark {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(59, 130, 246, 0.2);
            box-shadow: 0 20px 50px -10px rgba(8, 14, 26, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.05);
        }

        /* Cyber Grid Background */
        .hologram-grid-bg {
            background-color: #f8fafc;
            background-image: 
                linear-gradient(to right, rgba(59, 130, 246, 0.04) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(59, 130, 246, 0.04) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        /* Fluid Floating Animations */
        @keyframes ambient-drift {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -40px) scale(1.05); }
            66% { transform: translate(-20px, 20px) scale(0.95); }
        }
        @keyframes pulse-ring {
            0% { transform: scale(0.8); opacity: 0.2; }
            50% { transform: scale(1.1); opacity: 0.5; }
            100% { transform: scale(0.8); opacity: 0.2; }
        }

        .animate-drift-slow { animation: ambient-drift 18s ease-in-out infinite; }
        .animate-drift-fast { animation: ambient-drift 12s ease-in-out infinite reverse; }
        .animate-tech-ring { animation: pulse-ring 6s cubic-bezier(0.4, 0, 0.6, 1) infinite; }

        /* Custom Scrollbar for inner lists */
        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: rgba(59, 130, 246, 0.2); border-radius: 10px; }
    </style>

    {{-- DYNAMIC BACKGROUND WITH SHADER LIGHTING --}}
    <div class="fixed inset-0 z-0 pointer-events-none hologram-grid-bg overflow-hidden">
        {{-- Deep Navy/Blue Base Gradient overlay at the top --}}
        <div class="absolute top-0 inset-x-0 h-[60vh] bg-gradient-to-b from-slate-900 via-blue-900/10 to-transparent"></div>
        
        {{-- Ambient Glowing Orbs (Shader Highlights) --}}
        <div class="absolute -top-32 -left-32 w-[40rem] h-[40rem] bg-gradient-to-br from-blue-600/20 to-blue-400/20 rounded-full blur-[100px] animate-drift-slow mix-blend-multiply"></div>
        <div class="absolute top-1/4 -right-20 w-[35rem] h-[35rem] bg-gradient-to-bl from-indigo-500/15 to-sky-400/20 rounded-full blur-[80px] animate-drift-fast mix-blend-multiply"></div>
        
        {{-- High-tech rings --}}
        <div class="absolute top-20 right-32 w-[40rem] h-[40rem] border-[0.5px] border-blue-500/10 rounded-full animate-tech-ring hidden xl:block"></div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="relative z-10 px-4 sm:px-6 lg:px-8 py-8 max-w-[1600px] mx-auto space-y-12">

        {{-- HERO SECTION: COMMAND CENTER --}}
        <div class="glass-panel-dark relative overflow-hidden rounded-[2.5rem] p-8 sm:p-12 text-white">
            {{-- Inner Glows --}}
            <div class="absolute -right-20 -bottom-20 w-96 h-96 bg-blue-500/30 rounded-full blur-[80px] pointer-events-none"></div>
            <div class="absolute left-1/4 -top-20 w-72 h-72 bg-blue-600/30 rounded-full blur-[60px] pointer-events-none"></div>

            <div class="relative z-10 flex flex-col xl:flex-row xl:items-center justify-between gap-10">
                <div class="space-y-6 max-w-2xl">
                    <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full text-xs font-bold bg-white/5 border border-white/10 backdrop-blur-md">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-500"></span>
                        </span>
                        <span class="text-blue-300 tracking-wider uppercase">Hologram Command Center Live</span>
                    </div>
                    
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tighter text-transparent bg-clip-text bg-gradient-to-r from-white via-blue-100 to-blue-200 drop-shadow-sm">
                        Pusat Kontrol<br />Administrator.
                    </h1>
                    
                    <p class="text-slate-300 text-sm sm:text-base leading-relaxed font-light">
                        Ringkasan performa platform, verifikasi akun perusahaan, dan pengawasan aktivitas ekosistem secara real-time dengan antarmuka canggih berkedalaman spasial.
                    </p>
                </div>

                <div class="flex items-center gap-5 bg-white/5 border border-white/10 p-5 rounded-[2rem] backdrop-blur-xl shadow-2xl self-start xl:self-auto relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-500/10 to-blue-400/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-500 to-blue-600 flex items-center justify-center text-white shadow-[0_0_20px_rgba(6,182,212,0.4)]">
                        <i class="fa-solid fa-shield-halved text-2xl"></i>
                    </div>
                    <div class="pr-4 relative z-10">
                        <p class="text-[11px] text-blue-300 uppercase font-black tracking-widest mb-0.5">Status Sistem</p>
                        <p class="text-lg font-bold text-white">DEWA GACOR ACTIVE</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- HIGH-CONTRAST METRICS GRID --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6">
            
            {{-- Reusable Component Logic structure applied to cards --}}
            @php
                $metrics = [
                    ['title' => 'Pengguna', 'value' => $totalUsers, 'icon' => 'fa-users', 'color' => 'blue', 'desc' => 'Akun Terdaftar'],
                    ['title' => 'Freelancer', 'value' => $totalFreelancers, 'icon' => 'fa-user-tie', 'color' => 'cyan', 'desc' => 'Talenta Aktif'],
                    ['title' => 'Perusahaan', 'value' => $totalCompanies, 'icon' => 'fa-building', 'color' => 'indigo', 'desc' => 'Mitra Usaha'],
                    ['title' => 'Total Proyek', 'value' => $totalProjects, 'icon' => 'fa-folder-open', 'color' => 'sky', 'desc' => 'Proyek Terbuka'],
                    ['title' => 'Penawaran', 'value' => $totalPenawarans, 'icon' => 'fa-file-invoice', 'color' => 'blue', 'desc' => 'Proposal Masuk'],
                    ['title' => 'Laporan', 'value' => $totalReports, 'icon' => 'fa-flag', 'color' => 'cyan', 'desc' => 'Aduan Sistem']
                ];
            @endphp

            @foreach($metrics as $metric)
                <div class="glass-panel-light relative overflow-hidden rounded-[2rem] p-6 group hover:-translate-y-2 hover:shader-glow-{{ $metric['color'] == 'cyan' ? 'cyan' : 'blue' }} transition-all duration-500 cursor-default">
                    {{-- Shader Highlight --}}
                    <div class="absolute -right-10 -top-10 w-32 h-32 bg-{{ $metric['color'] }}-500/10 rounded-full blur-2xl group-hover:bg-{{ $metric['color'] }}-500/20 group-hover:scale-150 transition-all duration-700"></div>
                    
                    <div class="flex items-start justify-between relative z-10">
                        <div class="space-y-1">
                            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">{{ $metric['title'] }}</p>
                            <p class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tighter">{{ number_format($metric['value']) }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-[1.25rem] bg-[#f6f9ff] border border-blue-50 flex items-center justify-center text-{{ $metric['color'] }}-600 shadow-sm group-hover:scale-110 group-hover:rotate-6 transition-transform duration-500">
                            <i class="fa-solid {{ $metric['icon'] }} text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-5 pt-4 border-t border-blue-50/50 flex items-center text-xs font-semibold text-slate-500">
                        <span class="w-1.5 h-1.5 rounded-full bg-{{ $metric['color'] }}-500 mr-2 shadow-[0_0_8px_currentColor]"></span>
                        {{ $metric['desc'] }}
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ASYMMETRIC DATA BOARDS --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            {{-- 1. Company Requests (Spans 7 cols) --}}
            <div class="lg:col-span-7 glass-panel-light rounded-[2.5rem] flex flex-col overflow-hidden shader-glow-blue border border-blue-100/50">
                <div class="px-8 py-6 flex items-center justify-between relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-50/50 to-transparent pointer-events-none"></div>
                    <div class="flex items-center gap-4 relative z-10">
                        <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-[0_0_20px_rgba(59,130,246,0.3)]">
                            <i class="fa-solid fa-building-circle-exclamation text-xl"></i>
                        </div>
                        <div>
                            <h2 class="font-black text-slate-900 text-lg tracking-tight">Verifikasi Perusahaan</h2>
                            <p class="text-xs font-medium text-slate-500 mt-0.5">Permintaan pendaftaran akun mitra bisnis</p>
                        </div>
                    </div>
                    @if($pendingCompanyRequests > 0)
                        <div class="relative z-10 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-100 text-blue-700 font-bold text-xs">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                            </span>
                            {{ $pendingCompanyRequests }} Antrean
                        </div>
                    @endif
                </div>

                <div class="flex-1 overflow-y-auto custom-scroll max-h-[400px] px-8 pb-4">
                    <div class="space-y-3">
                        @forelse($recentRequests as $req)
                            <div class="group flex items-center justify-between p-4 rounded-2xl bg-white border border-blue-50 hover:border-blue-200 hover:shadow-[0_8px_30px_-12px_rgba(59,130,246,0.2)] transition-all duration-300">
                                <div>
                                    <p class="font-extrabold text-slate-900">{{ $req->company_name }}</p>
                                    <div class="flex items-center gap-3 mt-1.5 text-xs text-slate-500 font-medium">
                                        <span class="flex items-center gap-1.5"><i class="fa-regular fa-user text-slate-400"></i>{{ $req->contact_person }}</span>
                                        <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                        <span class="flex items-center gap-1.5"><i class="fa-regular fa-envelope text-slate-400"></i>{{ $req->company_email }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('admin.company-account-requests.show', $req) }}" class="flex items-center justify-center w-10 h-10 rounded-xl bg-[#f6f9ff] text-slate-400 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors">
                                    <i class="fa-solid fa-arrow-right -rotate-45"></i>
                                </a>
                            </div>
                        @empty
                            <div class="py-12 flex flex-col items-center justify-center text-slate-400">
                                <div class="w-16 h-16 rounded-3xl bg-[#f6f9ff] border border-blue-50 flex items-center justify-center mb-4">
                                    <i class="fa-solid fa-check text-2xl text-slate-300"></i>
                                </div>
                                <p class="text-sm font-bold">Semua permintaan terselesaikan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                
                <div class="p-6 border-t border-blue-50 bg-white/50 text-center backdrop-blur-md">
                    <a href="{{ route('admin.company-account-requests.index') }}" class="text-xs font-black tracking-widest uppercase text-blue-600 hover:text-blue-800 transition-colors">
                        Lihat Selengkapnya <i class="fa-solid fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>

            {{-- 2. Recent Projects (Spans 5 cols) --}}
            <div class="lg:col-span-5 glass-panel-light rounded-[2.5rem] flex flex-col overflow-hidden relative">
                <div class="px-8 py-6 flex items-center justify-between border-b border-blue-50">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-8 rounded-full bg-blue-500 shadow-[0_0_15px_rgba(6,182,212,0.5)]"></div>
                        <div>
                            <h2 class="font-black text-slate-900 text-lg tracking-tight">Proyek Aktif</h2>
                            <p class="text-xs font-medium text-slate-500">Aktivitas publikasi terbaru</p>
                        </div>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto custom-scroll max-h-[400px] p-6">
                    <div class="space-y-4 relative before:absolute before:inset-y-0 before:left-[19px] before:w-px before:bg-slate-200">
                        @forelse($recentProjects as $project)
                            <div class="relative pl-12 group">
                                <div class="absolute left-0 top-1.5 w-10 h-10 rounded-xl bg-white border border-blue-100 flex items-center justify-center shadow-sm z-10 group-hover:border-blue-400 group-hover:text-blue-500 transition-colors">
                                    <div class="w-2.5 h-2.5 rounded-full bg-slate-300 group-hover:bg-blue-500 group-hover:shadow-[0_0_10px_rgba(6,182,212,0.8)] transition-all"></div>
                                </div>
                                <div class="bg-white p-4 rounded-2xl border border-blue-50 shadow-sm group-hover:shadow-md transition-shadow">
                                    <div class="flex justify-between items-start gap-4">
                                        <div>
                                            <p class="text-sm font-extrabold text-slate-900 leading-tight">{{ $project->project_name }}</p>
                                            <p class="text-xs font-semibold text-slate-500 mt-1.5"><i class="fa-regular fa-building mr-1"></i> {{ $project->owner->name ?? '—' }}</p>
                                        </div>
                                        <span class="text-[10px] px-2.5 py-1 rounded-lg font-black uppercase tracking-wider
                                            @if($project->status == 'Open') bg-blue-50 text-blue-600 border border-blue-100
                                            @else bg-[#f6f9ff] text-slate-500 border border-blue-100 @endif">
                                            {{ $project->status }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="pl-12 py-8 text-sm font-semibold text-slate-400">Belum ada proyek terbaru.</div>
                        @endforelse
                    </div>
                </div>

                <div class="p-6 bg-[#f6f9ff] text-center">
                    <a href="{{ route('admin.projects.index') }}" class="text-xs font-black tracking-widest uppercase text-blue-600 hover:text-blue-800 transition-colors">
                        Eksplorasi Proyek <i class="fa-solid fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>

            {{-- 3. Recent Penawarans (Spans 5 cols) --}}
            <div class="lg:col-span-5 glass-panel-light rounded-[2.5rem] flex flex-col overflow-hidden relative">
                <div class="px-8 py-6 flex items-center justify-between border-b border-blue-50">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-8 rounded-full bg-indigo-500 shadow-[0_0_15px_rgba(99,102,241,0.5)]"></div>
                        <div>
                            <h2 class="font-black text-slate-900 text-lg tracking-tight">Proposal Masuk</h2>
                            <p class="text-xs font-medium text-slate-500">Tawaran freelancer terkini</p>
                        </div>
                    </div>
                </div>

                <div class="flex-1 p-6 space-y-3">
                    @forelse($recentPenawarans as $penawaran)
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-white border border-blue-50 hover:border-indigo-200 hover:shadow-md transition-all group">
                            <div class="flex-1 min-w-0 pr-4">
                                <p class="text-sm font-extrabold text-slate-900 truncate">{{ $penawaran->freelancer->name ?? '—' }}</p>
                                <p class="text-xs text-slate-500 mt-1 truncate">
                                    {{ $penawaran->project->project_name ?? '—' }}
                                </p>
                                <p class="text-xs font-black text-indigo-600 mt-1">Rp {{ number_format($penawaran->harga_penawaran) }}</p>
                            </div>
                            <div class="shrink-0">
                                <span class="text-[10px] px-3 py-1.5 rounded-xl font-bold border
                                    @if($penawaran->status == 'Diterima') bg-emerald-50 text-emerald-600 border-emerald-100
                                    @elseif($penawaran->status == 'Ditolak') bg-rose-50 text-rose-600 border-rose-100
                                    @else bg-[#f6f9ff] text-slate-600 border-blue-100 @endif">
                                    {{ $penawaran->status }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-sm font-semibold text-slate-400">Belum ada penawaran.</div>
                    @endforelse
                </div>

                <div class="p-6 bg-[#f6f9ff] text-center mt-auto">
                    <a href="{{ route('admin.penawarans.index') }}" class="text-xs font-black tracking-widest uppercase text-indigo-600 hover:text-indigo-800 transition-colors">
                        Kelola Proposal <i class="fa-solid fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>

            {{-- 4. Recent Reports (Spans 7 cols) --}}
            <div class="lg:col-span-7 glass-panel-light rounded-[2.5rem] flex flex-col overflow-hidden shader-glow-cyan border border-blue-100/50">
                <div class="px-8 py-6 flex items-center justify-between relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-50/50 to-transparent pointer-events-none"></div>
                    <div class="flex items-center gap-4 relative z-10">
                        <div class="w-12 h-12 rounded-2xl bg-blue-500 text-white flex items-center justify-center shadow-[0_0_20px_rgba(6,182,212,0.4)]">
                            <i class="fa-solid fa-shield-cat text-xl"></i>
                        </div>
                        <div>
                            <h2 class="font-black text-slate-900 text-lg tracking-tight">Radar Aduan Sistem</h2>
                            <p class="text-xs font-medium text-slate-500 mt-0.5">Pengawasan masalah & laporan terkini</p>
                        </div>
                    </div>
                </div>

                <div class="flex-1 p-8 grid gap-4 grid-cols-1 md:grid-cols-2">
                    @forelse($recentReports as $report)
                        <div class="p-5 rounded-2xl bg-white border border-blue-50 hover:border-blue-300 hover:shadow-[0_10px_30px_-10px_rgba(6,182,212,0.2)] transition-all flex flex-col justify-between h-full">
                            <div>
                                <div class="flex items-start justify-between gap-2 mb-3">
                                    <span class="text-[10px] px-2.5 py-1 rounded-lg font-black uppercase tracking-widest
                                        @if($report->status == 'menunggu') bg-amber-100 text-amber-700
                                        @elseif($report->status == 'diproses') bg-blue-100 text-blue-700
                                        @elseif($report->status == 'selesai') bg-emerald-100 text-emerald-700
                                        @else bg-blue-50 text-slate-600 @endif">
                                        {{ ucfirst($report->status) }}
                                    </span>
                                    <span class="text-[10px] font-bold text-slate-400"><i class="fa-regular fa-clock mr-1"></i>{{ $report->created_at->format('d M y') }}</span>
                                </div>
                                <p class="text-sm font-extrabold text-slate-900 mb-2">{{ $report->subject }}</p>
                            </div>
                            <div class="pt-3 mt-3 border-t border-blue-50/80 flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-blue-50 flex items-center justify-center text-[10px] text-slate-500"><i class="fa-solid fa-user"></i></div>
                                <p class="text-xs font-semibold text-slate-500 truncate">{{ $report->reporter->name ?? 'Unknown' }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 flex flex-col items-center justify-center text-slate-400">
                            <div class="w-16 h-16 rounded-3xl bg-[#f6f9ff] border border-blue-50 flex items-center justify-center mb-4">
                                <i class="fa-solid fa-shield-check text-2xl text-slate-300"></i>
                            </div>
                            <p class="text-sm font-bold">Ekosistem aman. Belum ada aduan masuk.</p>
                        </div>
                    @endforelse
                </div>
                
                <div class="p-6 border-t border-blue-50 bg-white/50 text-center backdrop-blur-md mt-auto">
                    <a href="{{ route('admin.reports.index') }}" class="text-xs font-black tracking-widest uppercase text-blue-600 hover:text-blue-800 transition-colors">
                        Buka Pusat Aduan <i class="fa-solid fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>
@endsection
