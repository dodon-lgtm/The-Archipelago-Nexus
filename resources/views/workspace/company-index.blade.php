<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.theme-boot')
    <title>Workspace Perusahaan - ApexForge Labs</title>
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
                        <h1 class="text-2xl font-extrabold text-slate-800 dark:text-white">Workspace</h1>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Pantau progress proyek yang sedang dikerjakan freelancer.</p>
                    </div>
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

                {{-- Filter Project — Search Marketplace Style --}}
                @php
                    $hasFilterProjects = isset($filterProjects) && $filterProjects->count() > 0;
                    $activeProject = $activeProject ?? 'all';
                    $activeStatus = $activeStatus ?? 'all';
                    $searchValue = $search ?? '';
                    // status options sesuai ENUM project_workspaces.status
                    $statusOptions = [
                        'all' => 'Semua Status',
                        'Sedang Dikerjakan' => 'Sedang Berjalan',
                        'Menunggu Review' => 'Menunggu Review',
                        'Menunggu Revisi' => 'Menunggu Revisi',
                        'Menunggu Pembayaran' => 'Menunggu Pembayaran',
                        'Menunggu Verifikasi Admin' => 'Menunggu Verifikasi',
                        'Selesai' => 'Selesai',
                        'Melewati Batas Waktu' => 'Melewati Batas Waktu',
                    ];
                @endphp
                <div class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl shadow-sm p-4 sm:p-5 space-y-4 overflow-visible">
                    {{-- Row 1: Search --}}
                    <div class="relative" id="workspaceSearchRoot">
                        <label for="workspaceSearchInput" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Cari Project</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            </span>
                            <input type="text" id="workspaceSearchInput" name="project_search"
                                   autocomplete="off"
                                   placeholder="Pilih atau ketik nama project..."
                                   value="{{ $searchValue }}"
                                   class="w-full pl-10 pr-10 py-2.5 text-sm rounded-xl border border-blue-100 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-blue-100 outline-none box-border">
                            <button type="button" id="workspaceSearchClear" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 {{ $searchValue ? '' : 'hidden' }}">
                                <i class="fa-solid fa-circle-xmark text-sm"></i>
                            </button>
                        </div>
                        {{-- Dropdown hasil search --}}
                        <div id="workspaceSearchDropdown" class="hidden absolute left-0 right-0 top-full mt-2 bg-white dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded-xl shadow-xl shadow-blue-500/10 overflow-hidden z-40">
                            <div class="max-h-64 overflow-y-auto py-1" id="workspaceSearchList"></div>
                            <div id="workspaceSearchEmpty" class="hidden px-4 py-6 text-center">
                                <i class="fa-regular fa-folder-open text-lg text-slate-300 dark:text-slate-600 mb-1"></i>
                                <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Project tidak ditemukan</p>
                            </div>
                        </div>
                    </div>

                    {{-- Row 2: Project + Status + Reset --}}
                    <div class="flex flex-col sm:flex-row gap-4 items-stretch sm:items-end">
                        {{-- Project Dropdown --}}
                        <div class="relative flex-1 min-w-0" id="projectFilterRoot">
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Project</label>
                            <button type="button" id="projectFilterBtn" aria-haspopup="listbox" aria-expanded="false"
                                    class="w-full flex items-center justify-between gap-2 px-4 py-2.5 text-sm rounded-xl border border-blue-100 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-white focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer hover:border-blue-200 dark:hover:border-slate-600 transition min-w-0 box-border">
                                <span id="projectFilterLabel" class="truncate text-left min-w-0">{{ $activeProject === 'all' ? 'Semua Project' : ($filterProjects->firstWhere('id', (int)$activeProject)->project_name ?? 'Semua Project') }}</span>
                                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 shrink-0 transition-transform" id="projectFilterChevron"></i>
                            </button>
                            <div id="projectFilterPanel" class="hidden absolute left-0 right-0 top-full mt-2 bg-white dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded-xl shadow-xl shadow-blue-500/10 overflow-hidden z-30 max-h-64 flex flex-col">
                                <div class="p-2 border-b border-blue-50 dark:border-slate-700 shrink-0">
                                    <p class="text-[10px] text-slate-400 dark:text-slate-500 px-1">Pilih project atau gunakan search di atas</p>
                                </div>
                                <div class="overflow-y-auto py-1 flex-1 min-h-0" id="projectFilterList">
                                    <button type="button" data-value="all" data-name="Semua Project"
                                            class="project-option w-full text-left px-4 py-2.5 text-sm hover:bg-blue-50 dark:hover:bg-slate-700/60 transition flex items-center justify-between gap-2 {{ $activeProject === 'all' ? 'bg-blue-50 dark:bg-slate-700/50 text-blue-700 dark:text-blue-300 font-semibold' : 'text-slate-700 dark:text-slate-300' }}">
                                        <span>Semua Project</span>
                                        @if($activeProject === 'all')<i class="fa-solid fa-check text-[10px] text-blue-600 dark:text-blue-400"></i>@endif
                                    </button>
                                    @foreach($filterProjects as $proj)
                                        <button type="button" data-value="{{ $proj->id }}" data-name="{{ $proj->project_name }}"
                                                class="project-option w-full text-left px-4 py-2.5 text-sm hover:bg-blue-50 dark:hover:bg-slate-700/60 transition flex items-center justify-between gap-2 min-w-0 {{ (string)$activeProject === (string)$proj->id ? 'bg-blue-50 dark:bg-slate-700/50 text-blue-700 dark:text-blue-300 font-semibold' : 'text-slate-700 dark:text-slate-300' }}">
                                            <span class="truncate flex-1 min-w-0">{{ $proj->project_name }}</span>
                                            @if((string)$activeProject === (string)$proj->id)<i class="fa-solid fa-check text-[10px] text-blue-600 dark:text-blue-400 shrink-0"></i>@endif
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Status Dropdown --}}
                        <div class="relative flex-1 min-w-0" id="statusFilterRoot">
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Status</label>
                            <button type="button" id="statusFilterBtn" aria-haspopup="listbox" aria-expanded="false"
                                    class="w-full flex items-center justify-between gap-2 px-4 py-2.5 text-sm rounded-xl border border-blue-100 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-white focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer hover:border-blue-200 dark:hover:border-slate-600 transition min-w-0 box-border">
                                <span id="statusFilterLabel" class="truncate text-left min-w-0">{{ $statusOptions[$activeStatus] ?? 'Semua Status' }}</span>
                                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 shrink-0 transition-transform" id="statusFilterChevron"></i>
                            </button>
                            <div id="statusFilterPanel" class="hidden absolute left-0 right-0 top-full mt-2 bg-white dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded-xl shadow-xl shadow-blue-500/10 overflow-hidden z-30">
                                <div class="py-1">
                                    @foreach($statusOptions as $val => $label)
                                        <button type="button" data-value="{{ $val }}" data-name="{{ $label }}"
                                                class="status-option w-full text-left px-4 py-2.5 text-sm hover:bg-blue-50 dark:hover:bg-slate-700/60 transition flex items-center justify-between gap-2 {{ (string)$activeStatus === (string)$val ? 'bg-blue-50 dark:bg-slate-700/50 text-blue-700 dark:text-blue-300 font-semibold' : 'text-slate-700 dark:text-slate-300' }}">
                                            <span>{{ $label }}</span>
                                            @if((string)$activeStatus === (string)$val)<i class="fa-solid fa-check text-[10px] text-blue-600 dark:text-blue-400"></i>@endif
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Reset --}}
                        <div class="shrink-0 flex items-end">
                            <button type="button" id="workspaceResetBtn"
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-sm font-semibold rounded-xl transition border border-transparent">
                                <i class="fa-solid fa-rotate-left text-xs"></i> Reset
                            </button>
                        </div>
                    </div>

                    {{-- Hidden form values untuk backend fallback (jika JS disabled) --}}
                    <form method="GET" action="{{ route('company.workspaces.index') }}" id="workspaceFilterForm" class="hidden" autocomplete="off">
                        <input type="hidden" name="search" id="filterSearchValue" value="{{ $searchValue }}">
                        <input type="hidden" name="project" id="filterProjectValue" value="{{ $activeProject }}">
                        <input type="hidden" name="status" id="filterStatusValue" value="{{ $activeStatus }}">
                    </form>
                </div>

                {{-- Daftar Workspace --}}
                @if($workspaces->count() > 0)
                    <div id="workspaceGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5" data-total="{{ $workspaces->total() }}" data-per-page="{{ $workspaces->perPage() }}">
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
                                    'Melewati Batas Waktu' => 'bg-red-50 text-red-600 border-red-200 dark:bg-red-900/40 dark:text-red-300 dark:border-red-900',
                                ];
                                $wsStatusColor = $stageColors[$ws->status] ?? 'bg-[#f6f9ff] text-slate-600 border-blue-100 dark:bg-slate-950 dark:text-slate-300 dark:border-slate-800';
                            @endphp
                            <div class="ws-card relative bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl shadow-sm hover:shadow-md transition overflow-hidden" data-project-id="{{ $ws->project_id }}" data-status="{{ $ws->status }}" data-project-name="{{ strtolower($ws->project->project_name ?? '') }}">
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
                                                <i class="fa-solid fa-user-tie mr-1"></i>{{ $ws->freelancer->name }}
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

                                    @if($ws->latestProgress)
                                        <p class="text-xs text-slate-500 dark:text-slate-400">
                                            <span class="font-semibold">Stage:</span> {{ $ws->latestProgress->stage }}
                                        </p>
                                    @endif
                                </div>

                                <div class="px-5 py-3 bg-[#f6f9ff] dark:bg-slate-950 border-t border-blue-50 dark:border-slate-800">
                                    <a href="{{ route('company.workspaces.show', $ws) }}"
                                       class="block text-center text-sm font-semibold text-brand hover:text-white bg-brand/10 hover:bg-brand px-4 py-2 rounded-xl transition">
                                        <i class="fa-solid fa-external-link-alt mr-1"></i> Buka Workspace
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div id="workspaceEmptyFiltered" class="hidden bg-white dark:bg-slate-900 border border-dashed border-blue-200 dark:border-slate-700 rounded-2xl p-10 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-amber-50 dark:bg-amber-900/30 rounded-2xl flex items-center justify-center">
                            <i class="fa-solid fa-filter-circle-xmark text-2xl text-amber-500"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-700 dark:text-white">Tidak ada workspace untuk project ini</h3>
                        <p class="text-xs text-slate-400 mt-1">Coba pilih <span class="font-semibold">Semua</span> atau project lain.</p>
                        <button type="button" id="workspaceClearFilter" class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-brand text-white rounded-xl text-xs font-semibold hover:bg-blue-700 transition">
                            <i class="fa-solid fa-rotate-left text-[11px]"></i> Tampilkan Semua
                        </button>
                    </div>

                    @if(method_exists($workspaces, 'links'))
                        <div id="workspacePagination" class="mt-8">{{ $workspaces->links() }}</div>
                    @endif
                @else
                    <div class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl p-16 text-center transition-colors duration-300">
                        <div class="w-20 h-20 mx-auto mb-5 bg-blue-50 dark:bg-slate-800 rounded-2xl flex items-center justify-center">
                            <i class="fa-solid fa-layer-group text-3xl text-slate-400 dark:text-slate-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-700 dark:text-white">Belum Ada Workspace</h3>
                        <p class="text-sm text-slate-400 mt-2">Workspace akan muncul setelah Anda memilih freelancer untuk proyek.</p>
                        <a href="{{ route('company.projects.index') }}"
                           class="inline-flex items-center gap-2 mt-6 px-5 py-2.5 bg-brand text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
                            <i class="fa-solid fa-folder-open"></i> Lihat Proyek
                        </a>
                    </div>
                @endif

            </div>
        </main>

        @include('navbar.footer')
    </div>

<script>
(function(){
    const grid = document.getElementById('workspaceGrid');
    const emptyFiltered = document.getElementById('workspaceEmptyFiltered');
    const pagination = document.getElementById('workspacePagination');
    const clearBtn = document.getElementById('workspaceClearFilter');
    // Search
    const searchRoot = document.getElementById('workspaceSearchRoot');
    const searchInput = document.getElementById('workspaceSearchInput');
    const searchClear = document.getElementById('workspaceSearchClear');
    const searchDropdown = document.getElementById('workspaceSearchDropdown');
    const searchList = document.getElementById('workspaceSearchList');
    const searchEmpty = document.getElementById('workspaceSearchEmpty');
    // Project
    const projectRoot = document.getElementById('projectFilterRoot');
    const projectBtn = document.getElementById('projectFilterBtn');
    const projectPanel = document.getElementById('projectFilterPanel');
    const projectList = document.getElementById('projectFilterList');
    const projectLabel = document.getElementById('projectFilterLabel');
    const projectChevron = document.getElementById('projectFilterChevron');
    // Status
    const statusRoot = document.getElementById('statusFilterRoot');
    const statusBtn = document.getElementById('statusFilterBtn');
    const statusPanel = document.getElementById('statusFilterPanel');
    const statusLabel = document.getElementById('statusFilterLabel');
    const statusChevron = document.getElementById('statusFilterChevron');
    const resetBtn = document.getElementById('workspaceResetBtn');
    // Hidden form for backend fallback
    const filterForm = document.getElementById('workspaceFilterForm');
    const filterSearchValue = document.getElementById('filterSearchValue');
    const filterProjectValue = document.getElementById('filterProjectValue');
    const filterStatusValue = document.getElementById('filterStatusValue');

    if(!grid) return;
    const cards = grid.querySelectorAll('.ws-card');
    const total = parseInt(grid.dataset.total || '0', 10);
    const perPage = parseInt(grid.dataset.perPage || '10', 10);
    const isSinglePage = total <= perPage;

    // Data project untuk search — otomatis dari workspace company (tidak buat manual)
    const allProjects = @json($filterProjects->map(fn($p) => ['id' => (string)$p->id, 'name' => $p->project_name])->values());
    let activeProject = '{{ $activeProject ?? "all" }}';
    let activeStatus = '{{ $activeStatus ?? "all" }}';
    let searchQuery = @json($search ?? '');

    function setProjectLabel(val){
        activeProject = String(val);
        const found = allProjects.find(p=> String(p.id)===String(val));
        projectLabel.textContent = found ? found.name : 'Semua Project';
        // update active styling in project dropdown
        projectList.querySelectorAll('.project-option').forEach(opt=>{
            const v = opt.getAttribute('data-value');
            const isActive = String(v)===String(val);
            opt.classList.toggle('bg-blue-50', isActive);
            opt.classList.toggle('dark:bg-slate-700/50', isActive);
            opt.classList.toggle('text-blue-700', isActive);
            opt.classList.toggle('dark:text-blue-300', isActive);
            opt.classList.toggle('font-semibold', isActive);
            let chk = opt.querySelector('i.fa-check');
            if(isActive && !chk){
                const i=document.createElement('i');
                i.className='fa-solid fa-check text-[10px] text-blue-600 dark:text-blue-400 shrink-0';
                opt.appendChild(i);
            } else if(!isActive && chk){
                chk.remove();
            }
        });
        if(filterProjectValue) filterProjectValue.value = val;
    }
    function setStatusLabel(val){
        activeStatus = String(val);
        const map = {
            'all': 'Semua Status',
            'Sedang Dikerjakan': 'Sedang Berjalan',
            'Menunggu Review': 'Menunggu Review',
            'Menunggu Revisi': 'Menunggu Revisi',
            'Menunggu Pembayaran': 'Menunggu Pembayaran',
            'Menunggu Verifikasi Admin': 'Menunggu Verifikasi',
            'Selesai': 'Selesai',
            'Melewati Batas Waktu': 'Melewati Batas Waktu'
        };
        statusLabel.textContent = map[val] || 'Semua Status';
        statusPanel.querySelectorAll('.status-option').forEach(opt=>{
            const v = opt.getAttribute('data-value');
            const isActive = String(v)===String(val);
            opt.classList.toggle('bg-blue-50', isActive);
            opt.classList.toggle('dark:bg-slate-700/50', isActive);
            opt.classList.toggle('text-blue-700', isActive);
            opt.classList.toggle('dark:text-blue-300', isActive);
            opt.classList.toggle('font-semibold', isActive);
            let chk = opt.querySelector('i.fa-check');
            if(isActive && !chk){
                const i=document.createElement('i');
                i.className='fa-solid fa-check text-[10px] text-blue-600 dark:text-blue-400';
                opt.appendChild(i);
            } else if(!isActive && chk){
                chk.remove();
            }
        });
        if(filterStatusValue) filterStatusValue.value = val;
    }

    function applyFilters(pushState=true){
        const q = (searchQuery||'').trim().toLowerCase();
        const pid = String(activeProject);
        const st = String(activeStatus);
        let visible = 0;
        cards.forEach(card=>{
            const projId = String(card.getAttribute('data-project-id')||'');
            const projName = (card.getAttribute('data-project-name')||'').toLowerCase();
            const status = String(card.getAttribute('data-status')||'');
            const matchSearch = !q || projName.includes(q);
            const matchProject = (pid==='all' || projId===pid);
            const matchStatus = (st==='all' || status===st);
            const show = matchSearch && matchProject && matchStatus;
            card.style.display = show ? '' : 'none';
            if(show) visible++;
        });
        if(emptyFiltered){
            emptyFiltered.classList.toggle('hidden', visible>0);
        }
        if(pagination){
            if((pid!=='all' || st!=='all' || q) && isSinglePage){
                pagination.style.display = 'none';
            } else {
                pagination.style.display = '';
            }
        }
        if(searchClear){
            searchClear.classList.toggle('hidden', !searchQuery);
        }
        if(filterSearchValue) filterSearchValue.value = searchQuery;
        if(pushState){
            const url = new URL(window.location);
            if(searchQuery) url.searchParams.set('search', searchQuery);
            else url.searchParams.delete('search');
            if(pid && pid!=='all') url.searchParams.set('project', pid);
            else url.searchParams.delete('project');
            if(st && st!=='all') url.searchParams.set('status', st);
            else url.searchParams.delete('status');
            history.pushState({}, '', url);
        }
    }

    function renderSearchDropdown(){
        const q = (searchInput.value||'').trim().toLowerCase();
        const filtered = !q ? [] : allProjects.filter(p=> p.name.toLowerCase().includes(q)).slice(0,6);
        searchList.innerHTML='';
        if(!q){
            // jika kosong, jangan tampilkan dropdown panjang — cukup tutup atau tampilkan Semua Project
            searchDropdown.classList.add('hidden');
            searchEmpty.classList.add('hidden');
            return;
        }
        if(filtered.length===0){
            searchEmpty.classList.remove('hidden');
            searchDropdown.classList.remove('hidden');
            return;
        }
        searchEmpty.classList.add('hidden');
        filtered.forEach(p=>{
            const btn=document.createElement('button');
            btn.type='button';
            btn.className='w-full text-left px-4 py-2.5 text-sm hover:bg-blue-50 dark:hover:bg-slate-700/60 transition flex items-center gap-2.5 text-slate-700 dark:text-slate-300';
            btn.innerHTML='<span class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-slate-700 flex items-center justify-center shrink-0"><i class="fa-solid fa-rocket text-[11px] text-brand"></i></span><span class="truncate flex-1">'+p.name+'</span><i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-400"></i>';
            btn.addEventListener('click', (e)=>{
                e.stopPropagation();
                searchInput.value = p.name;
                searchQuery = p.name;
                setProjectLabel(p.id);
                searchDropdown.classList.add('hidden');
                applyFilters(true);
                // jika multi-page, reload backend untuk hasil lengkap lintas halaman
                if(!isSinglePage){
                    // submit backend
                    if(filterForm) filterForm.submit();
                }
            });
            searchList.appendChild(btn);
        });
        // tambah opsi Semua jika ada hasil
        const allBtn=document.createElement('button');
        allBtn.type='button';
        allBtn.className='w-full text-left px-4 py-2.5 text-xs font-semibold border-t border-blue-50 dark:border-slate-700 hover:bg-blue-50 dark:hover:bg-slate-700/60 transition flex items-center gap-2 text-brand';
        allBtn.innerHTML='<i class="fa-solid fa-layer-group text-[11px]"></i> Tampilkan semua yang mengandung "'+searchInput.value.trim()+'"';
        allBtn.addEventListener('click', (e)=>{
            e.stopPropagation();
            searchQuery = searchInput.value.trim();
            searchDropdown.classList.add('hidden');
            applyFilters(true);
            if(!isSinglePage && filterForm) filterForm.submit();
        });
        searchList.appendChild(allBtn);
        searchDropdown.classList.remove('hidden');
    }

    function openProjectPanel(){
        projectPanel.classList.remove('hidden');
        projectBtn.setAttribute('aria-expanded','true');
        if(projectChevron) projectChevron.style.transform='rotate(180deg)';
        // filter project list sesuai search saat ini (max 20)
        const q = (searchQuery||'').trim().toLowerCase();
        let shown=0;
        projectList.querySelectorAll('.project-option').forEach(opt=>{
            const name=(opt.getAttribute('data-name')||'').toLowerCase();
            const val=opt.getAttribute('data-value');
            if(val==='all'){ opt.style.display=''; shown++; return; }
            const match = !q || name.includes(q);
            opt.style.display = match ? '' : 'none';
            if(match) shown++;
        });
        // batasi tinggi handled by CSS max-h-64
    }
    function closeProjectPanel(){
        projectPanel.classList.add('hidden');
        projectBtn.setAttribute('aria-expanded','false');
        if(projectChevron) projectChevron.style.transform='';
    }
    function openStatusPanel(){
        statusPanel.classList.remove('hidden');
        statusBtn.setAttribute('aria-expanded','true');
        if(statusChevron) statusChevron.style.transform='rotate(180deg)';
    }
    function closeStatusPanel(){
        statusPanel.classList.add('hidden');
        statusBtn.setAttribute('aria-expanded','false');
        if(statusChevron) statusChevron.style.transform='';
    }

    // Event: search input
    if(searchInput){
        searchInput.addEventListener('input', ()=>{
            searchQuery = searchInput.value;
            renderSearchDropdown();
            applyFilters(true);
            // live filter, if multi-page we still do frontend but backend will handle on next reload
        });
        searchInput.addEventListener('focus', ()=>{
            if(searchInput.value.trim()) renderSearchDropdown();
        });
        searchInput.addEventListener('keydown', (e)=>{
            if(e.key==='Escape'){
                searchDropdown.classList.add('hidden');
                searchInput.blur();
            }
            if(e.key==='Enter'){
                e.preventDefault();
                searchDropdown.classList.add('hidden');
                applyFilters(true);
                if(!isSinglePage && filterForm) filterForm.submit();
            }
        });
    }
    if(searchClear){
        searchClear.addEventListener('click', (e)=>{
            e.stopPropagation();
            searchInput.value='';
            searchQuery='';
            searchDropdown.classList.add('hidden');
            applyFilters(true);
            searchInput.focus();
        });
    }

    // Project dropdown
    if(projectBtn){
        projectBtn.addEventListener('click', (e)=>{
            e.stopPropagation();
            const isHidden = projectPanel.classList.contains('hidden');
            closeStatusPanel();
            searchDropdown.classList.add('hidden');
            if(isHidden) openProjectPanel(); else closeProjectPanel();
        });
    }
    projectList.querySelectorAll('.project-option').forEach(opt=>{
        opt.addEventListener('click', (e)=>{
            e.stopPropagation();
            const val=opt.getAttribute('data-value');
            const name=opt.getAttribute('data-name');
            setProjectLabel(val);
            if(val!=='all'){
                searchInput.value = name;
                searchQuery = name;
            }
            closeProjectPanel();
            applyFilters(true);
            if(!isSinglePage && filterForm) filterForm.submit();
        });
    });

    // Status dropdown
    if(statusBtn){
        statusBtn.addEventListener('click', (e)=>{
            e.stopPropagation();
            const isHidden = statusPanel.classList.contains('hidden');
            closeProjectPanel();
            searchDropdown.classList.add('hidden');
            if(isHidden) openStatusPanel(); else closeStatusPanel();
        });
    }
    statusPanel.querySelectorAll('.status-option').forEach(opt=>{
        opt.addEventListener('click', (e)=>{
            e.stopPropagation();
            const val=opt.getAttribute('data-value');
            setStatusLabel(val);
            closeStatusPanel();
            applyFilters(true);
            if(!isSinglePage && filterForm) filterForm.submit();
        });
    });

    // Reset
    function doReset(){
        searchInput.value='';
        searchQuery='';
        setProjectLabel('all');
        setStatusLabel('all');
        searchDropdown.classList.add('hidden');
        closeProjectPanel();
        closeStatusPanel();
        applyFilters(true);
        if(!isSinglePage && filterForm) filterForm.submit();
    }
    if(resetBtn) resetBtn.addEventListener('click', doReset);
    if(clearBtn) clearBtn.addEventListener('click', doReset);

    // Click outside & Escape
    document.addEventListener('click', (e)=>{
        if(searchRoot && !searchRoot.contains(e.target)){
            searchDropdown.classList.add('hidden');
        }
        if(projectRoot && !projectRoot.contains(e.target)){
            closeProjectPanel();
        }
        if(statusRoot && !statusRoot.contains(e.target)){
            closeStatusPanel();
        }
    });
    document.addEventListener('keydown', (e)=>{
        if(e.key==='Escape'){
            searchDropdown.classList.add('hidden');
            closeProjectPanel();
            closeStatusPanel();
        }
    });

    // Init
    setProjectLabel(activeProject);
    setStatusLabel(activeStatus);
    // Apply initial combined filter for single page (backend already filtered, but ensure frontend matches)
    if(isSinglePage) applyFilters(false);

    window.addEventListener('popstate', ()=>{
        const url=new URL(window.location);
        const s=url.searchParams.get('search')||'';
        const p=url.searchParams.get('project')||'all';
        const st=url.searchParams.get('status')||'all';
        searchInput.value=s;
        searchQuery=s;
        setProjectLabel(p);
        setStatusLabel(st);
        if(isSinglePage) applyFilters(false);
        else window.location.reload();
    });
})();
</script>
</body>
</html>

