<!DOCTYPE html>
<html lang="id" class="h-full bg-[#f6f9ff] dark:bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.theme-boot')
    <title>Proyek Terbaru - ApexForge Labs</title>
    
    {{-- Dark Mode Script --}}
    
    
    {{-- Tailwind CSS & FontAwesome --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = tailwind.config || {};
    tailwind.config.darkMode = 'class';
        tailwind.config.darkMode = 'class';
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- Custom Styles & Typography --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        
        :root {
            --af-primary: #2563eb;
            --af-primary-dark: #1d4ed8;
            --af-primary-soft: #eff6ff;
            --af-sky: #38bdf8;
            --af-ink: #0f172a;
            --af-muted: #64748b;
            --af-border: #dbeafe;
            --af-surface: #ffffff;
            --af-page: #f6f9ff;
        }

        html { scroll-behavior: smooth; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background:
                radial-gradient(circle at 10% -10%, rgba(56, 189, 248, .10), transparent 30%),
                radial-gradient(circle at 100% 0%, rgba(37, 99, 235, .08), transparent 28%),
                var(--af-page);
        }

        ::selection { background: rgba(37, 99, 235, .18); color: #0f172a; }
        ::-webkit-scrollbar { width: 7px; height: 7px; }
        ::-webkit-scrollbar-track { background: rgba(241, 245, 249, .7); }
        ::-webkit-scrollbar-thumb { background: rgba(37, 99, 235, .22); border-radius: 999px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(37, 99, 235, .38); }

        input, select, textarea {
            border-color: var(--af-border) !important;
            background: rgba(255, 255, 255, .92);
            transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
        }
        input:focus, select:focus, textarea:focus {
            border-color: rgba(37, 99, 235, .55) !important;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, .09) !important;
            outline: none !important;
        }

        button, a, [role="button"] { transition: all .2s ease; }
        button:focus-visible, a:focus-visible, [role="button"]:focus-visible {
            outline: 2px solid rgba(37, 99, 235, .55);
            outline-offset: 2px;
        }

        table { border-collapse: separate; border-spacing: 0; }
        thead th {
            background: rgba(239, 246, 255, .72) !important;
            color: #334155;
            font-weight: 700;
        }
        tbody tr { transition: background .18s ease; }
        tbody tr:hover { background: rgba(239, 246, 255, .48); }

        .glass-panel, .glass-card, .glass-surface {
            background: rgba(255, 255, 255, .72);
            border: 1px solid rgba(219, 234, 254, .85);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            box-shadow: 0 18px 50px -32px rgba(30, 64, 175, .32);
        }

        @media (max-width: 767px) {
            main { padding-left: 1rem !important; padding-right: 1rem !important; }
            table { min-width: 680px; }
            .overflow-x-auto { -webkit-overflow-scrolling: touch; }
        }
        /* Filter layout — wrap-safe, no overflow, consistent spacing */
        .filter-card { overflow: visible; position: relative; z-index: 10; width: 100%; max-width: 100%; box-sizing: border-box; }
        .filter-form { overflow: visible; width: 100%; max-width: 100%; box-sizing: border-box; }
        .filter-form > * { min-width: 0; box-sizing: border-box; }
        /* Searchable dropdowns — positioning & layering fix (category + budget) */
        #categoryDropdownRoot, #budgetDropdownRoot { position: relative; isolation: isolate; }
        #filter-category-panel,
        #filter-budget-panel {
            position: absolute;
            left: 0;
            right: 0;
            top: 100%;
            margin-top: .5rem;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            z-index: 50;
            display: flex;
            flex-direction: column;
            max-height: min(16rem, 50vh);
        }
        #filter-category-panel.hidden,
        #filter-budget-panel.hidden { display: none !important; }
        #filter-category-panel .cat-search-wrap,
        #filter-budget-panel .cat-search-wrap {
            flex-shrink: 0;
            position: sticky;
            top: 0;
            z-index: 1;
            background: inherit;
        }
        #filter-category-list,
        #filter-budget-list {
            flex: 1 1 auto;
            min-height: 0;
            overflow-y: auto;
            overscroll-behavior: contain;
            -webkit-overflow-scrolling: touch;
        }
        @media (max-width: 1023px) {
            #filter-category-panel,
            #filter-budget-panel {
                left: 0;
                right: 0;
                width: 100%;
                max-width: calc(100vw - 2rem);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
                scroll-behavior: auto !important;
            }
        }
    </style>
</head>
<body class="h-full bg-[#f6f9ff] dark:bg-slate-950 text-slate-800 dark:text-white antialiased selection:bg-blue-500 selection:text-white transition-colors duration-300">

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar Navigation --}}
    @include('navbar.navigasi')

    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Top Header / Navbar --}}
        <div class="sticky top-0 z-40 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-blue-100/80 dark:border-slate-800 shadow-sm">
            @include('navbar.nav')
        </div>

        {{-- Main Content Container --}}
        <main class="flex-1 overflow-y-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="max-w-7xl mx-auto">

                {{-- Hero Header --}}
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-800 p-8 sm:p-10 mb-8 shadow-lg shadow-blue-500/10 text-white">
                    <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="absolute right-20 -top-10 w-40 h-40 bg-blue-400/20 rounded-full blur-xl pointer-events-none"></div>
                    
                    <div class="relative z-10 max-w-2xl">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/30 text-blue-100 border border-blue-400/30 backdrop-blur-md mb-4">
                            <i class="fa-solid fa-sparkles text-amber-300"></i> Eksplorasi Peluang
                        </span>
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white">
                            Proyek Terbaru
                        </h1>
                        <p class="text-blue-100 mt-3 text-sm sm:text-base leading-relaxed">
                            Temukan proyek freelance terbaru yang dipublikasikan oleh berbagai perusahaan ternama dan mulai berkarya hari ini.
                        </p>
                    </div>
                </div>

                {{-- FILTER CARD --}}
                @php
                    $hasFilter = request('search') || request('category') || request('budget') || request('sort');
                @endphp
                
                <div class="filter-card bg-white dark:bg-slate-900 rounded-2xl border border-blue-100/80 dark:border-slate-800 shadow-sm p-5 sm:p-6 mb-8 overflow-visible w-full max-w-full box-border">
                    <form method="GET" action="{{ route('freelancer.proyek') }}" class="filter-form flex flex-wrap gap-4 items-end w-full max-w-full overflow-visible box-border">
                        
                        {{-- Search Input --}}
                        <div class="flex-[1_1_260px] min-w-[220px] max-w-full flex flex-col">
                            <label for="filter-search" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2 leading-none">
                                Cari Proyek
                            </label>
                            <div class="relative w-full">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                                </span>
                                <input type="text" id="filter-search" name="search" value="{{ request('search') }}"
                                       placeholder="Cari proyek..."
                                       class="w-full min-w-0 pl-10 pr-4 py-2.5 text-sm rounded-xl border border-blue-100 dark:border-slate-700 focus:ring-2 focus:ring-blue-100 outline-none dark:bg-slate-800 dark:text-white dark:placeholder:text-slate-500 box-border">
                            </div>
                        </div>

                        {{-- Category Filter — Searchable Dropdown (custom, tanpa native select panjang) --}}
                        <div class="flex-[1_1_180px] min-w-[160px] max-w-[240px] w-full flex flex-col relative" id="categoryDropdownRoot">
                            <label id="filter-category-label-title" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2 leading-none">
                                Kategori
                            </label>
                            <input type="hidden" name="category" id="filter-category-value" value="{{ request('category') }}">
                            <button type="button" id="filter-category-btn" aria-haspopup="listbox" aria-expanded="false" aria-labelledby="filter-category-label-title"
                                    class="w-full flex items-center justify-between gap-2 px-4 py-2.5 text-sm rounded-xl border border-blue-100 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-white focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer hover:border-blue-200 dark:hover:border-slate-600 transition min-w-0 box-border">
                                <span id="filter-category-label" class="truncate text-left min-w-0 @if(!request('category')) text-slate-400 dark:text-slate-500 @endif">
                                    @php $selectedCat = $categories->firstWhere('id', (int)request('category')); @endphp
                                    {{ $selectedCat ? $selectedCat->name : 'Semua Kategori' }}
                                </span>
                                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 shrink-0 transition-transform" id="filter-category-chevron"></i>
                            </button>
                            <div id="filter-category-panel" class="hidden absolute left-0 top-full z-50 mt-2 w-full min-w-0 max-w-full bg-white dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded-xl shadow-xl shadow-blue-500/10 overflow-hidden">
                                <div class="cat-search-wrap p-2 border-b border-blue-50 dark:border-slate-700 bg-white dark:bg-slate-800 shrink-0">
                                    <div class="relative min-w-0">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                                        </span>
                                        <input type="text" id="filter-category-search" autocomplete="off" placeholder="Cari kategori..."
                                               class="w-full min-w-0 box-border pl-9 pr-3 py-2 text-sm rounded-lg border border-blue-100 dark:border-slate-700 bg-white dark:bg-slate-900 focus:ring-2 focus:ring-blue-100 outline-none dark:text-white dark:placeholder:text-slate-500">
                                    </div>
                                </div>
                                <div id="filter-category-list" class="max-h-48 overflow-y-auto overscroll-contain py-1 min-h-0">
                                    <button type="button" data-value="" data-name="Semua Kategori"
                                            class="category-option w-full text-left px-4 py-2.5 text-sm hover:bg-blue-50 dark:hover:bg-slate-700/60 transition flex items-center justify-between gap-2 {{ !request('category') ? 'bg-blue-50 dark:bg-slate-700/50 text-blue-700 dark:text-blue-300 font-semibold' : 'text-slate-700 dark:text-slate-300' }}">
                                        <span>Semua Kategori</span>
                                        @if(!request('category'))<i class="fa-solid fa-check text-[10px] text-blue-600 dark:text-blue-400"></i>@endif
                                    </button>
                                    @foreach($categories as $cat)
                                        <button type="button" data-value="{{ $cat->id }}" data-name="{{ $cat->name }}"
                                                class="category-option w-full text-left px-4 py-2.5 text-sm hover:bg-blue-50 dark:hover:bg-slate-700/60 transition flex items-center justify-between gap-2 {{ (string)request('category') === (string)$cat->id ? 'bg-blue-50 dark:bg-slate-700/50 text-blue-700 dark:text-blue-300 font-semibold' : 'text-slate-700 dark:text-slate-300' }}">
                                            <span class="truncate">{{ $cat->name }}</span>
                                            @if((string)request('category') === (string)$cat->id)<i class="fa-solid fa-check text-[10px] text-blue-600 dark:text-blue-400 shrink-0"></i>@endif
                                        </button>
                                    @endforeach
                                </div>
                                <div id="filter-category-empty" class="hidden px-4 py-6 text-center">
                                    <i class="fa-regular fa-folder-open text-lg text-slate-300 dark:text-slate-600 mb-1"></i>
                                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Kategori tidak ditemukan.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Budget Filter — searchable + typable (preset + angka bebas ≥) --}}
                        <div class="flex-[1_1_170px] min-w-[150px] max-w-[200px] w-full flex flex-col relative" id="budgetDropdownRoot">
                            <label id="filter-budget-label-title" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2 leading-none">
                                Budget
                            </label>
                            <input type="hidden" name="budget" id="filter-budget-value" value="{{ request('budget') }}">
                            <button type="button" id="filter-budget-btn" aria-haspopup="listbox" aria-expanded="false" aria-labelledby="filter-budget-label-title"
                                    class="w-full flex items-center justify-between gap-2 px-3 py-2.5 text-sm rounded-xl border border-blue-100 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-white focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer hover:border-blue-200 dark:hover:border-slate-600 transition min-w-0 box-border">
                                @php
                                    $budgetVal = request('budget');
                                    $budgetLabel = 'Semua Budget';
                                    if ($budgetVal === 'under-1m') $budgetLabel = 'Di bawah Rp1.000.000';
                                    elseif ($budgetVal === '1m-5m') $budgetLabel = 'Rp1.000.000 – Rp5.000.000';
                                    elseif ($budgetVal === 'above-5m') $budgetLabel = 'Di atas Rp5.000.000';
                                    elseif ($budgetVal !== null && $budgetVal !== '') {
                                        $num = (int) preg_replace('/[^\d]/','', $budgetVal);
                                        if ($num > 0) $budgetLabel = '≥ Rp ' . number_format($num, 0, ',', '.');
                                        else $budgetLabel = $budgetVal;
                                    }
                                @endphp
                                <span id="filter-budget-label" class="truncate text-left min-w-0 @if(!request('budget')) text-slate-400 dark:text-slate-500 @endif">{{ $budgetLabel }}</span>
                                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 shrink-0 transition-transform" id="filter-budget-chevron"></i>
                            </button>
                            <div id="filter-budget-panel" class="hidden absolute left-0 top-full z-50 mt-2 w-full min-w-0 max-w-full bg-white dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded-xl shadow-xl shadow-blue-500/10 overflow-hidden">
                                <div class="cat-search-wrap p-2 border-b border-blue-50 dark:border-slate-700 bg-white dark:bg-slate-800 shrink-0">
                                    <div class="relative min-w-0">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                                        </span>
                                        <input type="text" id="filter-budget-search" autocomplete="off" inputmode="numeric" placeholder="Ketik budget, mis. 2500000"
                                               class="w-full min-w-0 box-border pl-9 pr-3 py-2 text-sm rounded-lg border border-blue-100 dark:border-slate-700 bg-white dark:bg-slate-900 focus:ring-2 focus:ring-blue-100 outline-none dark:text-white dark:placeholder:text-slate-500">
                                    </div>
                                    <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1.5 px-1">Bisa pilih preset atau ketik angka bebas (akan difilter ≥ nilai).</p>
                                </div>
                                <div id="filter-budget-list" class="max-h-48 overflow-y-auto overscroll-contain py-1 min-h-0">
                                    <button type="button" data-value="" data-name="Semua Budget"
                                            class="budget-option w-full text-left px-4 py-2.5 text-sm hover:bg-blue-50 dark:hover:bg-slate-700/60 transition flex items-center justify-between gap-2 {{ !request('budget') ? 'bg-blue-50 dark:bg-slate-700/50 text-blue-700 dark:text-blue-300 font-semibold' : 'text-slate-700 dark:text-slate-300' }}">
                                        <span>Semua Budget</span>
                                        @if(!request('budget'))<i class="fa-solid fa-check text-[10px] text-blue-600 dark:text-blue-400"></i>@endif
                                    </button>
                                    <button type="button" data-value="under-1m" data-name="Di bawah Rp1.000.000"
                                            class="budget-option w-full text-left px-4 py-2.5 text-sm hover:bg-blue-50 dark:hover:bg-slate-700/60 transition flex items-center justify-between gap-2 min-w-0 {{ request('budget') === 'under-1m' ? 'bg-blue-50 dark:bg-slate-700/50 text-blue-700 dark:text-blue-300 font-semibold' : 'text-slate-700 dark:text-slate-300' }}">
                                        <span class="truncate min-w-0 flex-1">Di bawah Rp1.000.000</span>
                                        @if(request('budget') === 'under-1m')<i class="fa-solid fa-check text-[10px] text-blue-600 dark:text-blue-400 shrink-0"></i>@endif
                                    </button>
                                    <button type="button" data-value="1m-5m" data-name="Rp1.000.000 – Rp5.000.000"
                                            class="budget-option w-full text-left px-4 py-2.5 text-sm hover:bg-blue-50 dark:hover:bg-slate-700/60 transition flex items-center justify-between gap-2 min-w-0 {{ request('budget') === '1m-5m' ? 'bg-blue-50 dark:bg-slate-700/50 text-blue-700 dark:text-blue-300 font-semibold' : 'text-slate-700 dark:text-slate-300' }}">
                                        <span class="truncate min-w-0 flex-1">Rp1.000.000 – Rp5.000.000</span>
                                        @if(request('budget') === '1m-5m')<i class="fa-solid fa-check text-[10px] text-blue-600 dark:text-blue-400 shrink-0"></i>@endif
                                    </button>
                                    <button type="button" data-value="above-5m" data-name="Di atas Rp5.000.000"
                                            class="budget-option w-full text-left px-4 py-2.5 text-sm hover:bg-blue-50 dark:hover:bg-slate-700/60 transition flex items-center justify-between gap-2 min-w-0 {{ request('budget') === 'above-5m' ? 'bg-blue-50 dark:bg-slate-700/50 text-blue-700 dark:text-blue-300 font-semibold' : 'text-slate-700 dark:text-slate-300' }}">
                                        <span class="truncate min-w-0 flex-1">Di atas Rp5.000.000</span>
                                        @if(request('budget') === 'above-5m')<i class="fa-solid fa-check text-[10px] text-blue-600 dark:text-blue-400 shrink-0"></i>@endif
                                    </button>
                                    <div id="filter-budget-custom" class="hidden border-t border-blue-50 dark:border-slate-700 mt-1 pt-1">
                                        <button type="button" id="filter-budget-custom-btn" class="w-full text-left px-4 py-2.5 text-sm hover:bg-amber-50 dark:hover:bg-amber-900/20 transition flex items-center gap-2 text-amber-700 dark:text-amber-300 font-medium">
                                            <i class="fa-solid fa-keyboard text-[11px]"></i>
                                            <span id="filter-budget-custom-label"></span>
                                        </button>
                                    </div>
                                </div>
                                <div id="filter-budget-empty" class="hidden px-4 py-6 text-center">
                                    <i class="fa-regular fa-folder-open text-lg text-slate-300 dark:text-slate-600 mb-1"></i>
                                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Preset tidak ditemukan. Ketik angka untuk custom.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Sort Filter --}}
                        <div class="flex-[1_1_160px] min-w-[150px] max-w-[180px] w-full flex flex-col">
                            <label for="filter-sort" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2 leading-none">
                                Urutkan
                            </label>
                            <select id="filter-sort" name="sort"
                                    class="w-full min-w-0 px-3 py-2.5 text-sm rounded-xl border border-blue-100 dark:border-slate-700 focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer dark:bg-slate-800 dark:text-white box-border">
                                <option value="terbaru" @selected(request('sort', 'terbaru') === 'terbaru')>Terbaru</option>
                                <option value="deadline" @selected(request('sort') === 'deadline')>Deadline Terdekat</option>
                                <option value="budget-tinggi" @selected(request('sort') === 'budget-tinggi')>Budget Tertinggi</option>
                                <option value="budget-rendah" @selected(request('sort') === 'budget-rendah')>Budget Terendah</option>
                            </select>
                        </div>

                        {{-- Filter & Reset Buttons --}}
                        <div class="flex flex-wrap items-end gap-2 shrink-0 flex-none min-w-0 max-w-full">
                            <button type="submit"
                                    class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-sm font-semibold rounded-xl shadow-sm shadow-blue-500/20 transition-all duration-200 cursor-pointer shrink-0">
                                <i class="fa-solid fa-filter text-xs"></i> Filter
                            </button>
                            
                            @if($hasFilter)
                                <a href="{{ route('freelancer.proyek') }}"
                                   class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-sm font-semibold rounded-xl transition-all duration-200 shrink-0">
                                    <i class="fa-solid fa-rotate-left text-xs"></i> Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- Project Grid --}}
                @if($projects->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                        @foreach($projects as $project)
                            <div class="group bg-white dark:bg-slate-900 rounded-2xl border border-blue-100/80 dark:border-slate-800 shadow-sm hover:shadow-xl hover:border-blue-200 dark:hover:border-slate-700 hover:-translate-y-1.5 transition-all duration-300 ease-out overflow-hidden flex flex-col justify-between">
                                
                                <div>
                                    {{-- Image Header --}}
                                    <div class="relative h-48 overflow-hidden bg-blue-50 dark:bg-slate-800">
                                        @if($project->image)
                                            <img src="{{ asset('storage/'.$project->image) }}" 
                                                 alt="{{ $project->project_name }}"
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
                                        @else
                                            <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-slate-50 dark:from-slate-800 to-blue-50/50 dark:to-slate-800/50 text-slate-300 dark:text-slate-600">
                                                <i class="fa-solid fa-image text-4xl mb-1 text-slate-300 dark:text-slate-600"></i>
                                                <span class="text-xs font-medium text-slate-400 dark:text-slate-400">Tidak ada gambar</span>
                                            </div>
                                        @endif

                                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                                        {{-- Badge Terbaru (Proyek 7 Hari Terakhir) --}}
                                        @if($project->created_at && $project->created_at->gte(\Carbon\Carbon::now()->subDays(7)))
                                            <span class="absolute top-3 left-3 inline-flex items-center gap-1.5 px-3 py-1 bg-blue-600/90 backdrop-blur-md text-white text-[11px] font-bold rounded-full shadow-md border border-blue-400/30">
                                                <span class="relative flex h-2 w-2">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-sky-300"></span>
                                                </span>
                                                Terbaru
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Content Body --}}
                                    <div class="p-6">
                                        {{-- Category Badge --}}
                                        @if($project->category && $project->category->name)
                                            <span class="inline-flex items-center text-[11px] font-bold tracking-wide uppercase text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-slate-800 border border-blue-100 dark:border-slate-800 px-3 py-1 rounded-full mb-3">
                                                {{ $project->category->name }}
                                            </span>
                                        @endif

                                        {{-- Project Name --}}
                                        <h3 class="text-lg font-bold text-slate-900 dark:text-white leading-snug mb-2 group-hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200 line-clamp-2">
                                            {{ $project->project_name }}
                                        </h3>

                                        {{-- Owner / Company --}}
                                        @if($project->owner && $project->owner->name)
                                            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 flex items-center gap-2 mb-3">
                                                <span class="w-5 h-5 rounded-md bg-blue-50 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400">
                                                    <i class="fa-regular fa-building text-[10px]"></i>
                                                </span>
                                                {{ $project->owner->name }}
                                            </p>
                                        @endif

                                        {{-- Project Description --}}
                                        @if($project->project_description)
                                            <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed line-clamp-2">
                                                {{ \Illuminate\Support\Str::limit($project->project_description, 100) }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Footer Card Section --}}
                                <div class="px-6 pb-6 pt-0">
                                    {{-- Budget & Deadline --}}
                                    <div class="flex items-center justify-between border-t border-blue-50 dark:border-slate-800 pt-4 mb-4">
                                        <div>
                                            <p class="text-[10px] text-slate-400 dark:text-slate-400 font-bold uppercase tracking-wider">Budget</p>
                                            <p class="text-base font-extrabold text-blue-600 dark:text-blue-400">
                                                Rp {{ number_format($project->budget ?? 0, 0, ',', '.') }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[10px] text-slate-400 dark:text-slate-400 font-bold uppercase tracking-wider">Deadline</p>
                                            <p class="text-xs font-bold text-slate-700 dark:text-white bg-blue-50 dark:bg-slate-800 px-2.5 py-1 rounded-md mt-0.5">
                                                <i class="fa-regular fa-calendar-alt mr-1 text-slate-400 dark:text-slate-400"></i>
                                                {{ $project->deadline ? \Carbon\Carbon::parse($project->deadline)->isoFormat('D MMM YYYY') : '-' }}
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Action Button --}}
                                    <a href="{{ route('freelancer.projects.show', $project) }}" 
                                       class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-sm font-semibold rounded-xl shadow-sm shadow-blue-500/20 hover:shadow-md hover:shadow-blue-500/30 transition-all duration-200">
                                        Lihat Detail
                                        <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform duration-200"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination Link --}}
                    @if(method_exists($projects, 'links'))
                        <div class="mt-12 flex justify-center">
                            {{ $projects->links() }}
                        </div>
                    @endif

                @else
                    {{-- Empty State --}}
                    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-blue-100/80 dark:border-slate-800 p-12 text-center max-w-lg mx-auto my-8 shadow-sm">
                        <div class="w-20 h-20 rounded-2xl bg-blue-50 dark:bg-slate-800 text-blue-600 dark:text-blue-400 flex items-center justify-center mx-auto mb-6 shadow-inner">
                            <i class="fa-solid {{ $hasFilter ? 'fa-magnifying-glass' : 'fa-briefcase' }} text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">
                            {{ $hasFilter ? 'Tidak Ada Proyek yang Cocok' : 'Belum Ada Proyek' }}
                        </h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed mb-6">
                            @if($hasFilter)
                                Tidak ada proyek yang sesuai dengan filter Anda. Coba ubah kata kunci pencarian atau hapus sebagian filter.
                            @else
                                Saat ini belum ada proyek terbaru yang dipublikasikan. Silakan kembali lagi nanti untuk melihat proyek-proyek terbaru.
                            @endif
                        </p>
                        <div class="flex items-center justify-center gap-3 flex-wrap">
                            @if($hasFilter)
                                <a href="{{ route('freelancer.proyek') }}"
                                   class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-all duration-200 shadow-sm">
                                    <i class="fa-solid fa-rotate-left text-xs"></i> Reset Filter
                                </a>
                            @endif
                            <a href="{{ route('freelancer.dashboard') }}"
                               class="inline-flex items-center gap-2 px-6 py-3 bg-slate-900 dark:bg-slate-800 hover:bg-slate-800 dark:hover:bg-slate-700 text-white text-sm font-semibold rounded-xl transition-all duration-200 shadow-sm">
                                <i class="fa-solid fa-arrow-left text-xs"></i> Kembali ke Dashboard
                            </a>
                        </div>
                    </div>
                @endif

            </div>
        </main>
</div>
 </div>

<script>
(function(){
    const root = document.getElementById('categoryDropdownRoot');
    const btn = document.getElementById('filter-category-btn');
    const panel = document.getElementById('filter-category-panel');
    const searchInput = document.getElementById('filter-category-search');
    const list = document.getElementById('filter-category-list');
    const emptyEl = document.getElementById('filter-category-empty');
    const hiddenVal = document.getElementById('filter-category-value');
    const labelEl = document.getElementById('filter-category-label');
    const chevron = document.getElementById('filter-category-chevron');
    if(!root || !btn || !panel || !searchInput || !list || !hiddenVal || !labelEl) return;

    function isOpen(){ return !panel.classList.contains('hidden'); }
    function adjustPosition(){
        // reset to default bottom
        panel.style.top = '100%';
        panel.style.bottom = 'auto';
        panel.style.marginTop = '0.5rem';
        panel.style.marginBottom = '0';
        // ensure width follows parent (w-full handles) but clamp for viewport on mobile
        // flip upward if not enough space below
        const rect = btn.getBoundingClientRect();
        const panelH = panel.offsetHeight || 300;
        const spaceBelow = window.innerHeight - rect.bottom - 16;
        const spaceAbove = rect.top - 16;
        if(spaceBelow < 180 && spaceAbove > spaceBelow){
            panel.style.top = 'auto';
            panel.style.bottom = '100%';
            panel.style.marginTop = '0';
            panel.style.marginBottom = '0.5rem';
        }
        // prevent horizontal overflow on small screens
        const rootRect = root.getBoundingClientRect();
        const viewportPad = 16;
        // panel is w-full = root width, so it stays inside root; on mobile root is full width minus main padding, safe.
        // extra safety: if panel would overflow viewport (e.g., due to transform), clamp
        const panelRect = panel.getBoundingClientRect();
        if(panelRect.right > window.innerWidth - viewportPad){
            const overflow = panelRect.right - (window.innerWidth - viewportPad);
            panel.style.left = (-overflow) + 'px';
            panel.style.right = 'auto';
        } else {
            panel.style.left = '0';
            panel.style.right = '0';
        }
        // max-height respect viewport — compact, not too tall
        panel.style.maxHeight = 'min(16rem, 50vh)';
    }
    function openPanel(){
        // tutup dropdown budget jika terbuka agar tidak tumpuk
        const bPanel = document.getElementById('filter-budget-panel');
        if(bPanel && !bPanel.classList.contains('hidden')){
            bPanel.classList.add('hidden');
            const bRoot = document.getElementById('budgetDropdownRoot');
            const bBtn = document.getElementById('filter-budget-btn');
            const bChev = document.getElementById('filter-budget-chevron');
            if(bRoot) bRoot.style.zIndex='';
            if(bBtn) bBtn.setAttribute('aria-expanded','false');
            if(bChev) bChev.style.transform='';
        }
        panel.classList.remove('hidden');
        adjustPosition();
        btn.setAttribute('aria-expanded','true');
        if(chevron) chevron.style.transform='rotate(180deg)';
        // keep panel above card grid but inside filter context
        root.style.zIndex = '30';
        searchInput.focus();
        filterList(searchInput.value);
    }
    function closePanel(){
        panel.classList.add('hidden');
        btn.setAttribute('aria-expanded','false');
        if(chevron) chevron.style.transform='';
        root.style.zIndex = '';
        panel.style.top = '';
        panel.style.bottom = '';
        panel.style.marginTop = '';
        panel.style.marginBottom = '';
        panel.style.left = '';
        panel.style.right = '';
    }
    function filterList(q){
        q = (q||'').trim().toLowerCase();
        const opts = list.querySelectorAll('.category-option');
        let visible = 0;
        opts.forEach(el=>{
            const name = (el.getAttribute('data-name')||'').toLowerCase();
            const show = !q || name.includes(q);
            el.style.display = show ? '' : 'none';
            if(show) visible++;
        });
        if(emptyEl){
            emptyEl.classList.toggle('hidden', visible>0);
            list.classList.toggle('hidden', visible===0 && q);
        }
    }
    function selectCategory(val, name){
        hiddenVal.value = val;
        labelEl.textContent = name || 'Semua Kategori';
        labelEl.classList.toggle('text-slate-400', !val);
        labelEl.classList.toggle('dark:text-slate-500', !val);
        // update selected styling
        list.querySelectorAll('.category-option').forEach(el=>{
            const v = el.getAttribute('data-value')||'';
            const isSel = v === val;
            el.classList.toggle('bg-blue-50', isSel);
            el.classList.toggle('dark:bg-slate-700/50', isSel);
            el.classList.toggle('text-blue-700', isSel);
            el.classList.toggle('dark:text-blue-300', isSel);
            el.classList.toggle('font-semibold', isSel);
            // check icon
            let chk = el.querySelector('i.fa-check');
            if(isSel && !chk){
                const i=document.createElement('i');
                i.className='fa-solid fa-check text-[10px] text-blue-600 dark:text-blue-400 shrink-0';
                el.appendChild(i);
            } else if(!isSel && chk){
                chk.remove();
            }
        });
        closePanel();
        btn.focus();
    }

    btn.addEventListener('click', (e)=>{
        e.stopPropagation();
        if(isOpen()) closePanel(); else openPanel();
    });
    searchInput.addEventListener('input', ()=> filterList(searchInput.value));
    // prevent panel click from closing via document
    panel.addEventListener('click', e=> e.stopPropagation());
    list.addEventListener('click', e=>{
        const opt = e.target.closest('.category-option');
        if(!opt) return;
        selectCategory(opt.getAttribute('data-value')||'', opt.getAttribute('data-name')||'Semua Kategori');
    });
    document.addEventListener('click', (e)=>{
        if(!root.contains(e.target)) closePanel();
    });
    document.addEventListener('keydown', (e)=>{
        if(e.key==='Escape' && isOpen()){ e.preventDefault(); closePanel(); btn.focus(); }
    });
    window.addEventListener('resize', ()=>{ if(isOpen()) adjustPosition(); });
    // close on scroll of main container to avoid detached dropdown
    const mainScroll = document.querySelector('main');
    if(mainScroll) mainScroll.addEventListener('scroll', ()=>{ if(isOpen()) adjustPosition(); }, {passive:true});
})();
(function(){
    const root = document.getElementById('budgetDropdownRoot');
    const btn = document.getElementById('filter-budget-btn');
    const panel = document.getElementById('filter-budget-panel');
    const searchInput = document.getElementById('filter-budget-search');
    const list = document.getElementById('filter-budget-list');
    const emptyEl = document.getElementById('filter-budget-empty');
    const customWrap = document.getElementById('filter-budget-custom');
    const customBtn = document.getElementById('filter-budget-custom-btn');
    const customLabel = document.getElementById('filter-budget-custom-label');
    const hiddenVal = document.getElementById('filter-budget-value');
    const labelEl = document.getElementById('filter-budget-label');
    const chevron = document.getElementById('filter-budget-chevron');
    if(!root || !btn || !panel || !searchInput || !list || !hiddenVal || !labelEl) return;

    function isOpen(){ return !panel.classList.contains('hidden'); }
    function formatRupiah(n){
        try { return 'Rp ' + Number(n).toLocaleString('id-ID'); } catch(e){ return 'Rp '+n; }
    }
    function parseDigits(s){
        const d = (s||'').replace(/[^\d]/g,'');
        return d ? parseInt(d,10) : 0;
    }
    function adjustPosition(){
        panel.style.top = '100%';
        panel.style.bottom = 'auto';
        panel.style.marginTop = '0.5rem';
        panel.style.marginBottom = '0';
        const rect = btn.getBoundingClientRect();
        const spaceBelow = window.innerHeight - rect.bottom - 16;
        const spaceAbove = rect.top - 16;
        if(spaceBelow < 180 && spaceAbove > spaceBelow){
            panel.style.top = 'auto';
            panel.style.bottom = '100%';
            panel.style.marginTop = '0';
            panel.style.marginBottom = '0.5rem';
        }
        const viewportPad = 16;
        const panelRect = panel.getBoundingClientRect();
        if(panelRect.right > window.innerWidth - viewportPad){
            const overflow = panelRect.right - (window.innerWidth - viewportPad);
            panel.style.left = (-overflow) + 'px';
            panel.style.right = 'auto';
        } else {
            panel.style.left = '0';
            panel.style.right = '0';
        }
        panel.style.maxHeight = 'min(16rem, 50vh)';
    }
    function openPanel(){
        // tutup dropdown kategori jika terbuka
        const cPanel = document.getElementById('filter-category-panel');
        if(cPanel && !cPanel.classList.contains('hidden')){
            cPanel.classList.add('hidden');
            const cRoot = document.getElementById('categoryDropdownRoot');
            const cBtn = document.getElementById('filter-category-btn');
            const cChev = document.getElementById('filter-category-chevron');
            if(cRoot) cRoot.style.zIndex='';
            if(cBtn) cBtn.setAttribute('aria-expanded','false');
            if(cChev) cChev.style.transform='';
        }
        panel.classList.remove('hidden');
        adjustPosition();
        btn.setAttribute('aria-expanded','true');
        if(chevron) chevron.style.transform='rotate(180deg)';
        root.style.zIndex = '30';
        searchInput.focus();
        // prefill search with numeric value if current is numeric
        const cur = hiddenVal.value||'';
        if(cur && !['','under-1m','1m-5m','above-5m'].includes(cur)){
            const n = parseDigits(cur);
            if(n) searchInput.value = String(n);
        } else {
            searchInput.value = '';
        }
        filterList(searchInput.value);
    }
    function closePanel(){
        panel.classList.add('hidden');
        btn.setAttribute('aria-expanded','false');
        if(chevron) chevron.style.transform='';
        root.style.zIndex = '';
        panel.style.top=''; panel.style.bottom=''; panel.style.marginTop=''; panel.style.marginBottom=''; panel.style.left=''; panel.style.right='';
    }
    function filterList(q){
        q = (q||'').trim();
        const qLower = q.toLowerCase();
        const opts = list.querySelectorAll('.budget-option');
        let visible = 0;
        opts.forEach(el=>{
            const name = (el.getAttribute('data-name')||'').toLowerCase();
            const val = (el.getAttribute('data-value')||'').toLowerCase();
            const show = !q || name.includes(qLower) || val.includes(qLower);
            el.style.display = show ? '' : 'none';
            if(show) visible++;
        });
        // custom numeric handling
        const num = parseDigits(q);
        const isPreset = ['','under-1m','1m-5m','above-5m'].includes(qLower);
        const showCustom = !isPreset && num>0;
        if(showCustom && customWrap && customLabel){
            customWrap.classList.remove('hidden');
            customLabel.textContent = 'Gunakan: ≥ ' + formatRupiah(num) + '  (ketik bebas)';
            if(emptyEl) emptyEl.classList.add('hidden');
        } else if(customWrap){
            customWrap.classList.add('hidden');
            if(emptyEl){
                emptyEl.classList.toggle('hidden', visible>0);
            }
        } else if(emptyEl){
            emptyEl.classList.toggle('hidden', visible>0);
        }
        // if no preset visible but custom visible, keep list visible
        if(showCustom) list.classList.remove('hidden');
    }
    function selectBudget(val, name){
        hiddenVal.value = val;
        // name may be custom formatted
        let display = name;
        if(val && !['','under-1m','1m-5m','above-5m'].includes(val)){
            const n = parseDigits(val);
            if(n) display = '≥ ' + formatRupiah(n);
        }
        labelEl.textContent = display || 'Semua Budget';
        labelEl.classList.toggle('text-slate-400', !val);
        labelEl.classList.toggle('dark:text-slate-500', !val);
        list.querySelectorAll('.budget-option').forEach(el=>{
            const v = el.getAttribute('data-value')||'';
            const isSel = v === val;
            el.classList.toggle('bg-blue-50', isSel);
            el.classList.toggle('dark:bg-slate-700/50', isSel);
            el.classList.toggle('text-blue-700', isSel);
            el.classList.toggle('dark:text-blue-300', isSel);
            el.classList.toggle('font-semibold', isSel);
            let chk = el.querySelector('i.fa-check');
            if(isSel && !chk){
                const i=document.createElement('i');
                i.className='fa-solid fa-check text-[10px] text-blue-600 dark:text-blue-400 shrink-0';
                el.appendChild(i);
            } else if(!isSel && chk){ chk.remove(); }
        });
        closePanel();
        btn.focus();
    }

    btn.addEventListener('click', (e)=>{ e.stopPropagation(); if(isOpen()) closePanel(); else openPanel(); });
    searchInput.addEventListener('input', ()=> filterList(searchInput.value));
    searchInput.addEventListener('keydown', (e)=>{
        if(e.key==='Enter'){
            e.preventDefault();
            const q = searchInput.value.trim();
            const num = parseDigits(q);
            if(num>0){
                // use custom
                selectBudget(String(num), '≥ ' + formatRupiah(num));
            } else if(!q){
                selectBudget('','Semua Budget');
            }
        }
    });
    panel.addEventListener('click', e=> e.stopPropagation());
    list.addEventListener('click', e=>{
        const opt = e.target.closest('.budget-option');
        if(opt){
            selectBudget(opt.getAttribute('data-value')||'', opt.getAttribute('data-name')||'Semua Budget');
            return;
        }
    });
    if(customBtn) customBtn.addEventListener('click', (e)=>{
        e.stopPropagation();
        const q = searchInput.value.trim();
        const num = parseDigits(q);
        if(num>0) selectBudget(String(num), '≥ ' + formatRupiah(num));
    });
    // also allow typing + clicking Filter without selecting: sync on form submit
    const form = root.closest('form');
    if(form){
        form.addEventListener('submit', ()=>{
            if(isOpen()){
                const q = searchInput.value.trim();
                const num = parseDigits(q);
                const isPresetVal = ['','under-1m','1m-5m','above-5m'].includes(hiddenVal.value);
                // if user typed numeric but hasn't selected, use typed value
                if(q && num>0 && isPresetVal && hiddenVal.value!==q){
                    // if search differs from hidden and is numeric, treat as custom
                    const curVisible = customWrap && !customWrap.classList.contains('hidden');
                    if(curVisible) hiddenVal.value = String(num);
                }
            }
        });
    }
    document.addEventListener('click', (e)=>{ if(!root.contains(e.target)) closePanel(); });
    document.addEventListener('keydown', (e)=>{ if(e.key==='Escape' && isOpen()){ e.preventDefault(); closePanel(); btn.focus(); } });
    window.addEventListener('resize', ()=>{ if(isOpen()) adjustPosition(); });
    const mainScroll = document.querySelector('main');
    if(mainScroll) mainScroll.addEventListener('scroll', ()=>{ if(isOpen()) adjustPosition(); }, {passive:true});
})();
</script>

 </body>
</html>