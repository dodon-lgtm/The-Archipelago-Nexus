<!DOCTYPE html>
<html lang="id" class="h-full bg-[#f6f9ff] dark:bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyek Terbaru - ApexForge Labs</title>
    
    {{-- Dark Mode Script --}}
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>
    
    {{-- Tailwind CSS & FontAwesome --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = tailwind.config || {};
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
                
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-blue-100/80 dark:border-slate-800 shadow-sm p-5 sm:p-6 mb-8">
                    <form method="GET" action="{{ route('freelancer.proyek') }}" class="flex flex-col lg:flex-row gap-4 lg:items-end">
                        
                        {{-- Search Input --}}
                        <div class="flex-1 min-w-0">
                            <label for="filter-search" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">
                                Cari Proyek
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                                </span>
                                <input type="text" id="filter-search" name="search" value="{{ request('search') }}"
                                       placeholder="Cari proyek..."
                                       class="w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border border-blue-100 dark:border-slate-700 focus:ring-2 focus:ring-blue-100 outline-none dark:bg-slate-800 dark:text-white dark:placeholder:text-slate-500">
                            </div>
                        </div>

                        {{-- Category Filter --}}
                        <div class="lg:w-56">
                            <label for="filter-category" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">
                                Kategori
                            </label>
                            <select id="filter-category" name="category"
                                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-blue-100 dark:border-slate-700 focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer dark:bg-slate-800 dark:text-white">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" @selected((string)request('category') === (string)$cat->id)>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Budget Filter --}}
                        <div class="lg:w-56">
                            <label for="filter-budget" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">
                                Budget
                            </label>
                            <select id="filter-budget" name="budget"
                                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-blue-100 dark:border-slate-700 focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer dark:bg-slate-800 dark:text-white">
                                <option value="">Semua Budget</option>
                                <option value="under-1m" @selected(request('budget') === 'under-1m')>Di bawah Rp1.000.000</option>
                                <option value="1m-5m" @selected(request('budget') === '1m-5m')>Rp1.000.000 – Rp5.000.000</option>
                                <option value="above-5m" @selected(request('budget') === 'above-5m')>Di atas Rp5.000.000</option>
                            </select>
                        </div>

                        {{-- Sort Filter --}}
                        <div class="lg:w-56">
                            <label for="filter-sort" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">
                                Urutkan
                            </label>
                            <select id="filter-sort" name="sort"
                                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-blue-100 dark:border-slate-700 focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer dark:bg-slate-800 dark:text-white">
                                <option value="terbaru" @selected(request('sort', 'terbaru') === 'terbaru')>Terbaru</option>
                                <option value="deadline" @selected(request('sort') === 'deadline')>Deadline Terdekat</option>
                                <option value="budget-tinggi" @selected(request('sort') === 'budget-tinggi')>Budget Tertinggi</option>
                                <option value="budget-rendah" @selected(request('sort') === 'budget-rendah')>Budget Terendah</option>
                            </select>
                        </div>

                        {{-- Filter & Reset Buttons --}}
                        <div class="flex items-center gap-2 shrink-0">
                            <button type="submit"
                                    class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-sm font-semibold rounded-xl shadow-sm shadow-blue-500/20 transition-all duration-200 cursor-pointer">
                                <i class="fa-solid fa-filter text-xs"></i> Filter
                            </button>
                            
                            @if($hasFilter)
                                <a href="{{ route('freelancer.proyek') }}"
                                   class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-sm font-semibold rounded-xl transition-all duration-200">
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

</body>
</html>