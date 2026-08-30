<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.theme-boot')
    <title>Workspace Saya - ApexForge Labs</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: { brand: '#2563EB', surface: '#F8FAFC' }
                }
            }
        }
    </script>
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
<body class="bg-surface dark:bg-slate-950 text-slate-800 dark:text-white min-h-screen flex font-sans transition-colors duration-300">

    @include('navbar.navigasi')

    <div class="flex-1 min-w-0 flex flex-col min-h-screen">
        @include('navbar.nav')

        <main class="flex-1 min-w-0 overflow-y-auto">
            <div class="max-w-7xl mx-auto px-6 py-8 space-y-6">

                {{-- Header --}}
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-extrabold text-slate-800 dark:text-white">Workspace Saya</h1>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Semua proyek yang sedang dan sudah kamu kerjakan.</p>
                    </div>
                </div>

                {{-- FILTER BAR (Ditambahkan tanpa mengurangi kode asli) --}}
                <div class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
                    <form method="GET" action="{{ url()->current() }}" class="flex flex-col sm:flex-row items-center gap-3">
                        <div class="relative flex-1 w-full">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <i class="fa-solid fa-search"></i>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama proyek atau perusahaan..." class="w-full pl-11 pr-4 py-2.5 rounded-xl text-sm border border-blue-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white focus:outline-none">
                        </div>
                        <div class="w-full sm:w-64">
                            <select name="status" onchange="this.form.submit()" class="w-full px-4 py-2.5 rounded-xl text-sm border border-blue-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white focus:outline-none">
                                <option value="">Semua Status</option>
                                <option value="Sedang Dikerjakan" {{ request('status') == 'Sedang Dikerjakan' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                                <option value="Menunggu Review" {{ request('status') == 'Menunggu Review' ? 'selected' : '' }}>Menunggu Review</option>
                                <option value="Menunggu Revisi" {{ request('status') == 'Menunggu Revisi' ? 'selected' : '' }}>Menunggu Revisi</option>
                                <option value="Menunggu Pembayaran" {{ request('status') == 'Menunggu Pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                <option value="Menunggu Verifikasi Admin" {{ request('status') == 'Menunggu Verifikasi Admin' ? 'selected' : '' }}>Menunggu Verifikasi Admin</option>
                                <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                        @if(request('search') || request('status'))
                            <a href="{{ url()->current() }}" class="w-full sm:w-auto px-4 py-2.5 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-sm font-semibold text-center transition">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                @if(session('success'))
                    <div class="flex items-center gap-3 px-4 py-3 bg-emerald-50 dark:bg-emerald-900/40 border border-emerald-200 dark:border-emerald-900 text-emerald-700 dark:text-emerald-300 rounded-xl text-sm font-medium">
                        <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="flex items-center gap-3 px-4 py-3 bg-red-50 dark:bg-red-900/40 border border-red-200 dark:border-red-900 text-red-700 dark:text-red-300 rounded-xl text-sm font-medium">
                        <i class="fa-solid fa-xmark-circle"></i> {{ session('error') }}
                    </div>
                @endif

                {{-- Daftar Workspace --}}
                @if($workspaces->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        @foreach($workspaces as $ws)
                            @php
                                $progress = $ws->currentProgress();
                                $stageColors = [
                                    'Sedang Dikerjakan' => 'bg-blue-100 text-blue-700 border-blue-200 dark:bg-slate-800 dark:text-blue-300 dark:border-slate-700',
                                    'Menunggu Review' => 'bg-amber-100 text-amber-700 border-amber-200 dark:bg-amber-900/40 dark:text-amber-300 dark:border-amber-900',
                                    'Menunggu Revisi' => 'bg-blue-50 text-blue-600 border-blue-200 dark:bg-slate-800 dark:text-blue-300 dark:border-slate-700',
                                    'Menunggu Pembayaran' => 'bg-purple-50 text-purple-600 border-purple-200 dark:bg-purple-900/40 dark:text-purple-300 dark:border-purple-900',
                                    'Menunggu Verifikasi Admin' => 'bg-orange-50 text-orange-600 border-orange-200 dark:bg-orange-900/40 dark:text-orange-300 dark:border-orange-900',
                                    'Selesai' => 'bg-emerald-50 text-emerald-600 border-emerald-200 dark:bg-emerald-900/40 dark:text-emerald-300 dark:border-emerald-900',
                                ];
                                $wsStatusColor = $stageColors[$ws->status] ?? 'bg-[#f6f9ff] text-slate-600 border-blue-100 dark:bg-slate-950 dark:text-slate-300 dark:border-slate-800';
                            @endphp
                            <div class="relative bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl shadow-sm hover:shadow-md transition overflow-hidden">
                                {{-- Red dot jika ada notifikasi unread terkait workspace ini --}}
                                @if (($unreadByWorkspace[$ws->id] ?? 0) > 0)
                                    <span class="absolute top-3 right-3 inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 rounded-full bg-red-500 text-white text-[10px] font-bold shadow-md z-10">
                                        {{ $unreadByWorkspace[$ws->id] }}
                                    </span>
                                @endif
                                <div class="p-5">
                                    <div class="flex items-start justify-between gap-3 mb-4">
                                        <div class="min-w-0 flex-1">
                                            <h3 class="font-bold text-slate-800 dark:text-white truncate">{{ $ws->project->project_name }}</h3>
                                            <p class="text-xs text-slate-400 mt-1">
                                                <i class="fa-solid fa-building mr-1"></i>{{ $ws->company->name }}
                                            </p>
                                        </div>
                                        <span class="text-[10px] font-bold px-2.5 py-1 rounded-full border {{ $wsStatusColor }} shrink-0">
                                            {{ $ws->status }}
                                        </span>
                                    </div>

                                    {{-- Progress Bar --}}
                                    <div class="mb-3">
                                        <div class="flex items-center justify-between text-xs mb-1">
                                            <span class="text-slate-500 dark:text-slate-400">Progress</span>
                                            <span class="font-bold text-brand">{{ $progress }}%</span>
                                        </div>
                                        <div class="w-full bg-blue-50 dark:bg-slate-800 rounded-full h-2 overflow-hidden">
                                            <div class="h-full rounded-full bg-gradient-to-r from-brand to-blue-400 transition-all" style="width: {{ $progress }}%"></div>
                                        </div>
                                    </div>

                                    {{-- Stage terakhir --}}
                                    @if($ws->latestProgress)
                                        <p class="text-xs text-slate-500 dark:text-slate-400">
                                            <span class="font-semibold">Stage:</span> {{ $ws->latestProgress->stage }}
                                        </p>
                                    @endif
                                </div>

                                <div class="px-5 py-3 bg-[#f6f9ff] dark:bg-slate-950 border-t border-blue-50 dark:border-slate-800">
                                    <a href="{{ route('freelancer.workspaces.show', $ws) }}"
                                       class="block text-center text-sm font-semibold text-brand hover:text-white bg-brand/10 hover:bg-brand px-4 py-2 rounded-xl transition">
                                        <i class="fa-solid fa-external-link-alt mr-1"></i> Buka Workspace
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if(method_exists($workspaces, 'links'))
                        <div class="mt-8">{{ $workspaces->appends(request()->query())->links() }}</div>
                    @endif
                @else
                    {{-- Empty State --}}
                    <div class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl p-16 text-center transition-colors duration-300">
                        <div class="w-20 h-20 mx-auto mb-5 bg-blue-50 dark:bg-slate-800 rounded-2xl flex items-center justify-center">
                            <i class="fa-solid fa-layer-group text-3xl text-slate-400 dark:text-slate-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-700 dark:text-white">Belum Ada Workspace</h3>
                        <p class="text-sm text-slate-400 mt-2">Kamu belum memiliki workspace aktif. Cari proyek dan kirim penawaran untuk memulai.</p>
                        <a href="{{ route('freelancer.proyek') }}"
                           class="inline-flex items-center gap-2 mt-6 px-5 py-2.5 bg-brand text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
                            <i class="fa-solid fa-search"></i> Cari Proyek
                        </a>
                    </div>
                @endif

            </div>
        </main>

    
    </div>

</body>
</html>