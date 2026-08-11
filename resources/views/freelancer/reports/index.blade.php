@php
    $title = 'Laporan Saya';
@endphp

<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Saya - ApexForge Labs</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
    </style>
</head>
<body class="h-full bg-slate-50 text-slate-800 antialiased selection:bg-blue-500 selection:text-white flex">

    @include('navbar.navigasi')

    <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
        
        <div class="sticky top-0 z-40 bg-white/80 backdrop-blur-md border-b border-slate-200/80 shadow-xs">
            @include('navbar.nav')
        </div>

        <main class="flex-1 overflow-y-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="max-w-7xl mx-auto">

                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="mb-6 flex items-center gap-3 px-5 py-4 bg-emerald-50/90 border border-emerald-200 text-emerald-800 text-sm font-semibold rounded-2xl shadow-xs backdrop-blur-sm">
                        <span class="w-8 h-8 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
                            <i class="fa-solid fa-check text-sm"></i>
                        </span>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-6 flex items-center gap-3 px-5 py-4 bg-rose-50/90 border border-rose-200 text-rose-800 text-sm font-semibold rounded-2xl shadow-xs backdrop-blur-sm">
                        <span class="w-8 h-8 rounded-xl bg-rose-100 flex items-center justify-center text-rose-600 shrink-0">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </span>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                {{-- Header Laporan khusus (Dengan aksen Pusat Aduan/Keamanan) --}}
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-blue-950 to-indigo-900 p-8 sm:p-10 mb-8 shadow-lg shadow-blue-950/20 text-white">
                    <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-blue-500/10 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="absolute right-20 -top-10 w-40 h-40 bg-indigo-500/20 rounded-full blur-xl pointer-events-none"></div>
                    
                    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="max-w-xl">
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/20 text-blue-200 border border-blue-400/20 backdrop-blur-md mb-3">
                                <i class="fa-solid fa-shield-halved text-blue-400"></i> Pusat Transparansi & Pengaduan
                            </span>
                            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-white">
                                Laporan Saya
                            </h1>
                            <p class="text-slate-300 mt-2 text-sm sm:text-base leading-relaxed">
                                Pantau riwayat aduan, status penanganan masalah, dan laporan kendala proyek Anda secara langsung.
                            </p>
                        </div>

                        <div class="shrink-0">
                            <a href="{{ route('reports.create') }}"
                               class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-blue-600 hover:bg-blue-500 active:bg-blue-700 text-white text-sm font-bold rounded-2xl transition-all duration-200 shadow-lg shadow-blue-600/30 hover:shadow-blue-500/40 hover:-translate-y-0.5">
                                <i class="fa-solid fa-plus text-xs"></i>
                                Buat Laporan Baru
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Daftar Laporan --}}
                @if($reports->count() > 0)
                    <div class="space-y-4">
                        @foreach($reports as $report)
                            <div class="group bg-white rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md hover:border-blue-200 transition-all duration-200 p-5 sm:p-6">
                                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                                    
                                    {{-- Left: Details & Metadata --}}
                                    <div class="flex-1 min-w-0">
                                        {{-- Header Row: Title & Status Badge --}}
                                        <div class="flex flex-wrap items-center gap-3 mb-2.5">
                                            <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors duration-200">
                                                {{ $report->subject }}
                                            </h3>

{{-- Dynamic Status Pill --}}
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold shrink-0 border
                                                @if($report->status == 'menunggu') bg-amber-50 text-amber-700 border-amber-200/80
                                                @elseif($report->status == 'ditinjau') bg-blue-50 text-blue-700 border-blue-200/80
                                                @elseif($report->status == 'menunggu-bukti') bg-violet-50 text-violet-700 border-violet-200/80
                                                @elseif($report->status == 'selesai') bg-emerald-50 text-emerald-700 border-emerald-200/80
                                                @else bg-rose-50 text-rose-700 border-rose-200/80 @endif">
                                                
                                                <span class="w-1.5 h-1.5 rounded-full 
                                                    @if($report->status == 'menunggu') bg-amber-500
                                                    @elseif($report->status == 'ditinjau') bg-blue-500 animate-pulse
                                                    @elseif($report->status == 'menunggu-bukti') bg-violet-500 animate-pulse
                                                    @elseif($report->status == 'selesai') bg-emerald-500
                                                    @else bg-rose-500 @endif">
                                                </span>
                                                {{ \App\Models\Report::statusLabel($report->status) }}<span class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-slate-100 text-slate-500 text-[10px] font-semibold rounded-full">{{ \App\Models\Report::targetLabel($report->target) }}</span>
                                            </span>
                                        </div>

                                        {{-- Description --}}
                                        <p class="text-sm text-slate-600 leading-relaxed line-clamp-2 mb-4">
                                            {{ Str::limit($report->description, 150) }}
                                        </p>

                                        {{-- Tags & Reference Badges --}}
                                        <div class="flex flex-wrap items-center gap-2 text-xs">
                                            {{-- Kategori --}}
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 text-slate-700 font-semibold rounded-lg border border-slate-200/60">
                                                <i class="fa-solid fa-tag text-slate-400 text-[10px]"></i>
                                                {{ \App\Models\Report::categoryLabel($report->category) }}
                                            </span>

                                            {{-- Tanggal --}}
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-50 text-slate-500 font-medium rounded-lg border border-slate-200/50">
                                                <i class="fa-regular fa-calendar text-slate-400 text-[10px]"></i>
                                                {{ $report->created_at->format('d M Y') }}
                                            </span>

                                            {{-- User Terlapor --}}
                                            @if($report->reportedUser)
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-orange-50/80 text-orange-700 font-medium rounded-lg border border-orange-200/60">
                                                    <i class="fa-solid fa-user-shield text-orange-500 text-[10px]"></i>
                                                    {{ $report->reportedUser->name }}
                                                </span>
                                            @endif

                                            {{-- Context (Workspace / Project) --}}
                                            @if($report->workspace)
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-indigo-50/80 text-indigo-700 font-medium rounded-lg border border-indigo-200/60">
                                                    <i class="fa-solid fa-layer-group text-indigo-500 text-[10px]"></i>
                                                    {{ Str::limit($report->workspace->project->project_name ?? 'Workspace', 30) }}
                                                </span>
                                            @elseif($report->project)
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50/80 text-blue-700 font-medium rounded-lg border border-blue-200/60">
                                                    <i class="fa-solid fa-folder-open text-blue-500 text-[10px]"></i>
                                                    {{ Str::limit($report->project->project_name, 30) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Right: Actions --}}
                                    <div class="flex items-center justify-end pt-3 lg:pt-0 border-t lg:border-t-0 border-slate-100 shrink-0">
                                        <a href="{{ route('freelancer.reports.show', $report) }}"
                                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 text-xs font-bold bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white rounded-xl transition-all duration-200 border border-blue-100 shadow-xs">
                                            Lihat Detail
                                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                        </a>
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if(method_exists($reports, 'links'))
                        <div class="mt-8 flex justify-center">
                            {{ $reports->links() }}
                        </div>
                    @endif
                @else
                    {{-- Empty State --}}
                    <div class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center max-w-lg mx-auto my-8 shadow-xs">
                        <div class="w-20 h-20 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-6">
                            <i class="fa-solid fa-shield-cat text-4xl text-slate-300"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Belum Ada Laporan</h3>
                        <p class="text-sm text-slate-500 leading-relaxed mb-6">
                            Anda belum pernah membuat laporan aduan. Jika Anda menemukan penyalahgunaan sistem, pengguna mencurigakan, atau kendala transaksi, silakan sampaikan melalui tombol di bawah.
                        </p>
                        <a href="{{ route('freelancer.reports.create') }}"
                           class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-all duration-200 shadow-sm shadow-blue-500/20">
                            <i class="fa-solid fa-plus text-xs"></i>
                            Buat Laporan Baru
                        </a>
                    </div>
                @endif

                <div class="mt-16">
                    @include('navbar.footer')
                </div>

            </div>
        </main>
    </div>

</body>
</html>