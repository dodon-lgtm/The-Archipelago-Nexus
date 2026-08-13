@php
    $title = 'Laporan Saya';
@endphp

<!DOCTYPE html>
<html lang="id" class="h-full bg-[#f6f9ff]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Saya - ApexForge Labs</title>
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = tailwind.config || {};
        tailwind.config.darkMode = 'class';
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
    </style>
<style>

/* ApexForge Labs — Unified UI System */
:root{
    --af-primary:#2563eb;
    --af-primary-dark:#1d4ed8;
    --af-primary-soft:#eff6ff;
    --af-sky:#38bdf8;
    --af-ink:#0f172a;
    --af-muted:#64748b;
    --af-border:#dbeafe;
    --af-surface:#ffffff;
    --af-page:#f6f9ff;
}
html{scroll-behavior:smooth}
body{
    font-family:'Plus Jakarta Sans',sans-serif;
    background:
        radial-gradient(circle at 10% -10%,rgba(56,189,248,.10),transparent 30%),
        radial-gradient(circle at 100% 0%,rgba(37,99,235,.08),transparent 28%),
        var(--af-page);
}
::selection{background:rgba(37,99,235,.18);color:#0f172a}
::-webkit-scrollbar{width:7px;height:7px}
::-webkit-scrollbar-track{background:rgba(241,245,249,.7)}
::-webkit-scrollbar-thumb{background:rgba(37,99,235,.22);border-radius:999px}
::-webkit-scrollbar-thumb:hover{background:rgba(37,99,235,.38)}

input,select,textarea{
    border-color:var(--af-border)!important;
    background:rgba(255,255,255,.92);
    transition:border-color .2s ease,box-shadow .2s ease,background .2s ease;
}
input:focus,select:focus,textarea:focus{
    border-color:rgba(37,99,235,.55)!important;
    box-shadow:0 0 0 4px rgba(37,99,235,.09)!important;
    outline:none!important;
}
button,a,[role="button"]{transition:all .2s ease}
button:focus-visible,a:focus-visible,[role="button"]:focus-visible{
    outline:2px solid rgba(37,99,235,.55);
    outline-offset:2px;
}
table{border-collapse:separate;border-spacing:0}
thead th{
    background:rgba(239,246,255,.72)!important;
    color:#334155;
    font-weight:700;
}
tbody tr{transition:background .18s ease}
tbody tr:hover{background:rgba(239,246,255,.48)}
[class*="bg-blue-600"]{
    box-shadow:0 8px 22px -12px rgba(37,99,235,.72);
}
[class*="bg-blue-600"]:hover{
    box-shadow:0 12px 28px -12px rgba(37,99,235,.78);
    transform:translateY(-1px);
}
.glass-panel,.glass-card,.glass-surface{
    background:rgba(255,255,255,.72);
    border:1px solid rgba(219,234,254,.85);
    backdrop-filter:blur(18px);
    -webkit-backdrop-filter:blur(18px);
    box-shadow:0 18px 50px -32px rgba(30,64,175,.32);
}
.apex-page-glow{
    position:fixed;inset:auto -10rem -12rem auto;width:28rem;height:28rem;
    background:rgba(56,189,248,.09);filter:blur(70px);border-radius:999px;
    pointer-events:none;z-index:-1;
}
@media (max-width:767px){
    main{padding-left:1rem!important;padding-right:1rem!important}
    table{min-width:680px}
    .overflow-x-auto{-webkit-overflow-scrolling:touch}
}
@media (prefers-reduced-motion:reduce){
    *,*::before,*::after{animation-duration:.01ms!important;animation-iteration-count:1!important;transition-duration:.01ms!important;scroll-behavior:auto!important}
}

</style>
</head>
<body class="h-full bg-[#f6f9ff] dark:bg-slate-950 text-slate-800 dark:text-white antialiased selection:bg-blue-500 selection:text-white flex transition-colors duration-300">

    @include('navbar.navigasi')

    <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
        
        <div class="sticky top-0 z-40 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-blue-100/80 dark:border-slate-800 shadow-xs">
            @include('navbar.nav')
        </div>

        <main class="flex-1 overflow-y-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="max-w-7xl mx-auto">

                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="mb-6 flex items-center gap-3 px-5 py-4 bg-emerald-50/90 dark:bg-emerald-900/40 border border-emerald-200 dark:border-emerald-900 text-emerald-800 dark:text-emerald-300 text-sm font-semibold rounded-2xl shadow-xs backdrop-blur-sm">
                        <span class="w-8 h-8 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center text-emerald-600 dark:text-emerald-300 shrink-0">
                            <i class="fa-solid fa-check text-sm"></i>
                        </span>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-6 flex items-center gap-3 px-5 py-4 bg-rose-50/90 dark:bg-rose-900/40 border border-rose-200 dark:border-rose-900 text-rose-800 dark:text-rose-300 text-sm font-semibold rounded-2xl shadow-xs backdrop-blur-sm">
                        <span class="w-8 h-8 rounded-xl bg-rose-100 dark:bg-rose-900/40 flex items-center justify-center text-rose-600 dark:text-rose-300 shrink-0">
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
                            <div class="group bg-white dark:bg-slate-900 rounded-2xl border border-blue-100/80 dark:border-slate-800 shadow-xs hover:shadow-md hover:border-blue-200 dark:hover:border-slate-700 transition-all duration-200 p-5 sm:p-6">
                                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                                    
                                    {{-- Left: Details & Metadata --}}
                                    <div class="flex-1 min-w-0">
                                        {{-- Header Row: Title & Status Badge --}}
                                        <div class="flex flex-wrap items-center gap-3 mb-2.5">
                                            <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200">
                                                {{ $report->subject }}
                                            </h3>

{{-- Dynamic Status Pill --}}
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold shrink-0 border
                                                @if($report->status == 'menunggu') bg-amber-50 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300 border-amber-200/80 dark:border-amber-900
                                                @elseif($report->status == 'ditinjau') bg-blue-50 dark:bg-slate-800 text-blue-700 dark:text-blue-400 border-blue-200/80 dark:border-slate-800
                                                @elseif($report->status == 'menunggu-bukti') bg-violet-50 dark:bg-violet-900/40 text-violet-700 dark:text-violet-300 border-violet-200/80 dark:border-violet-900
                                                @elseif($report->status == 'selesai') bg-emerald-50 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 border-emerald-200/80 dark:border-emerald-900
                                                @else bg-rose-50 dark:bg-rose-900/40 text-rose-700 dark:text-rose-300 border-rose-200/80 dark:border-rose-900 @endif">
                                                
                                                <span class="w-1.5 h-1.5 rounded-full 
                                                    @if($report->status == 'menunggu') bg-amber-500
                                                    @elseif($report->status == 'ditinjau') bg-blue-500 animate-pulse
                                                    @elseif($report->status == 'menunggu-bukti') bg-violet-500 animate-pulse
                                                    @elseif($report->status == 'selesai') bg-emerald-500
                                                    @else bg-rose-500 @endif">
                                                </span>
                                                {{ \App\Models\Report::statusLabel($report->status) }}<span class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-blue-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-[10px] font-semibold rounded-full">{{ \App\Models\Report::targetLabel($report->target) }}</span>
                                            </span>
                                        </div>

                                        {{-- Description --}}
                                        <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed line-clamp-2 mb-4">
                                            {{ Str::limit($report->description, 150) }}
                                        </p>

                                        {{-- Tags & Reference Badges --}}
                                        <div class="flex flex-wrap items-center gap-2 text-xs">
                                            {{-- Kategori --}}
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 dark:bg-slate-800 text-slate-700 dark:text-white font-semibold rounded-lg border border-blue-100/60 dark:border-slate-800">
                                                <i class="fa-solid fa-tag text-slate-400 text-[10px]"></i>
                                                {{ \App\Models\Report::categoryLabel($report->category) }}
                                            </span>

                                            {{-- Tanggal --}}
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-[#f6f9ff] dark:bg-slate-950 text-slate-500 dark:text-slate-400 font-medium rounded-lg border border-blue-100/50 dark:border-slate-800">
                                                <i class="fa-regular fa-calendar text-slate-400 text-[10px]"></i>
                                                {{ $report->created_at->format('d M Y') }}
                                            </span>

                                            {{-- User Terlapor --}}
                                            @if($report->reportedUser)
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-orange-50/80 dark:bg-orange-900/40 text-orange-700 dark:text-orange-300 font-medium rounded-lg border border-orange-200/60 dark:border-orange-900">
                                                    <i class="fa-solid fa-user-shield text-orange-500 dark:text-orange-400 text-[10px]"></i>
                                                    {{ $report->reportedUser->name }}
                                                </span>
                                            @endif

                                            {{-- Context (Workspace / Project) --}}
                                            @if($report->workspace)
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-indigo-50/80 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 font-medium rounded-lg border border-indigo-200/60 dark:border-indigo-900">
                                                    <i class="fa-solid fa-layer-group text-indigo-500 dark:text-indigo-400 text-[10px]"></i>
                                                    {{ Str::limit($report->workspace->project->project_name ?? 'Workspace', 30) }}
                                                </span>
                                            @elseif($report->project)
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50/80 dark:bg-slate-800/80 text-blue-700 dark:text-blue-400 font-medium rounded-lg border border-blue-200/60 dark:border-slate-800">
                                                    <i class="fa-solid fa-folder-open text-blue-500 dark:text-blue-400 text-[10px]"></i>
                                                    {{ Str::limit($report->project->project_name, 30) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Right: Actions --}}
                                    <div class="flex items-center justify-end pt-3 lg:pt-0 border-t lg:border-t-0 border-blue-50 dark:border-slate-800 shrink-0">
                                        <a href="{{ route('freelancer.reports.show', $report) }}"
                                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 text-xs font-bold bg-blue-50 dark:bg-slate-800 hover:bg-blue-600 text-blue-600 dark:text-blue-400 hover:text-white rounded-xl transition-all duration-200 border border-blue-100 dark:border-slate-800 shadow-xs">
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
                    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-blue-100/80 dark:border-slate-800 p-12 text-center max-w-lg mx-auto my-8 shadow-xs">
                        <div class="w-20 h-20 rounded-2xl bg-blue-50 dark:bg-slate-800 text-slate-400 flex items-center justify-center mx-auto mb-6">
                            <i class="fa-solid fa-shield-cat text-4xl text-slate-300 dark:text-slate-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Belum Ada Laporan</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed mb-6">
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