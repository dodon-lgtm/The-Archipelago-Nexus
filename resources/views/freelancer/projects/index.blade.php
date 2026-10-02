<!DOCTYPE html>
<html lang="id" class="h-full bg-[#f6f9ff]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.theme-boot')
    <title>Daftar Proyek - ApexForge Labs</title>

    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = tailwind.config || {};
    tailwind.config.darkMode = 'class';
        tailwind.config.darkMode = 'class';
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        
        /* ApexForge Labs — Unified UI System */
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
                radial-gradient(circle at 10% -10%, rgba(56,189,248,.10), transparent 30%),
                radial-gradient(circle at 100% 0%, rgba(37,99,235,.08), transparent 28%),
                var(--af-page);
        }
        ::selection { background: rgba(37,99,235,.18); color: #0f172a; }
        ::-webkit-scrollbar { width: 7px; height: 7px; }
        ::-webkit-scrollbar-track { background: rgba(241,245,249,.7); }
        ::-webkit-scrollbar-thumb { background: rgba(37,99,235,.22); border-radius: 999px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(37,99,235,.38); }

        input, select, textarea {
            border-color: var(--af-border) !important;
            background: rgba(255,255,255,.92);
            transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
        }
        input:focus, select:focus, textarea:focus {
            border-color: rgba(37,99,235,.55) !important;
            box-shadow: 0 0 0 4px rgba(37,99,235,.09) !important;
            outline: none !important;
        }
        button, a, [role="button"] { transition: all .2s ease; }
    </style>
</head>

<body class="h-full bg-[#f6f9ff] dark:bg-slate-950 text-slate-800 dark:text-white antialiased flex transition-colors duration-300">

    <!-- Sidebar Navigation -->
    @include('navbar.navigasi')

    <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">

        <!-- Top Header Navigation -->
    <div class="sticky top-0 z-40 bg-white/95 dark:bg-slate-900/95 border-b border-blue-100/80 dark:border-slate-800 shadow-xs">
            @include('navbar.nav')
        </div>

        <main class="flex-1 overflow-y-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="max-w-7xl mx-auto">

                <!-- Header Section -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                            Eksplorasi Proyek Freelance
                        </h1>
                        <p class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm mt-1 font-medium">
                            Temukan peluang pekerjaan terbaru yang sesuai dengan keahlian Anda
                        </p>
                    </div>
                </div>

                <!-- Search & Filter Card -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-blue-100/80 dark:border-slate-800 shadow-xs p-5 sm:p-6 mb-8 transition-colors duration-300">
                    <form method="GET" action="{{ route('freelancer.projects.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                        
                        <!-- Search Input -->
                        <div class="md:col-span-6">
                            <label for="search" class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2 uppercase tracking-wider">
                                Cari Judul / Kata Kunci
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                                </span>
                                <input type="text" id="search" name="search" value="{{ request('search') }}" 
                                       placeholder="Cari proyek web, desain, dsb..." 
                                       class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm rounded-2xl border border-blue-100 dark:border-slate-700 focus:ring-2 focus:ring-blue-100 outline-none dark:bg-slate-800 dark:text-white dark:placeholder:text-slate-500">
                            </div>
                        </div>

                        <!-- Category Filter -->
                        <div class="md:col-span-4">
                            <label for="category_id" class="block text-xs font-bold text-slate-600 dark:text-slate-300 mb-2 uppercase tracking-wider">
                                Kategori
                            </label>
                            <select id="category_id" name="category_id" class="w-full px-4 py-2.5 text-xs sm:text-sm rounded-2xl border border-blue-100 dark:border-slate-700 focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer dark:bg-slate-800 dark:text-white">
                                <option value="">Semua Kategori</option>
                                @foreach($categories ?? [] as $category)
                                    <option value="{{ $category->id }}" {{ (string)request('category_id') === (string)$category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="md:col-span-2 flex gap-2">
                            <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-bold text-xs rounded-2xl shadow-xs shadow-blue-500/20 transition flex items-center justify-center gap-2 cursor-pointer">
                                <i class="fa-solid fa-filter"></i> Filter
                            </button>
                            @if(request('search') || request('category_id'))
                                <a href="{{ route('freelancer.projects.index') }}" class="px-3 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold text-xs rounded-2xl transition flex items-center justify-center" title="Reset Filter">
                                    <i class="fa-solid fa-rotate-left"></i>
                                </a>
                            @endif
                        </div>

                    </form>
                </div>

                <!-- Main Content Grid -->
                <div class="grid lg:grid-cols-3 gap-8">

                    <!-- Left: Projects List (2 Cols) -->
                    <div class="lg:col-span-2 space-y-4">
                        @forelse ($projects as $project)
                            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-blue-100/80 dark:border-slate-800 p-5 shadow-xs hover:shadow-md hover:border-blue-200 dark:hover:border-slate-700 transition-all duration-300 flex flex-col sm:flex-row justify-between gap-4">
                                
                                <div class="flex items-start gap-4 min-w-0">
                                    <!-- Image Thumbnail -->
                                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl overflow-hidden shrink-0 bg-blue-50 dark:bg-slate-800 border border-blue-100 dark:border-slate-800">
                                        @if($project->image)
                                            <img src="{{ asset('storage/'.$project->image) }}" class="w-full h-full object-cover" alt="{{ $project->project_name }}">
                                        @else
                                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-300 dark:text-slate-600">
                                                <i class="fa-solid fa-image text-xl"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Content Info -->
                                    <div class="min-w-0 space-y-1.5">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-blue-50 dark:bg-slate-800 text-blue-700 dark:text-blue-400 text-[10px] font-bold rounded-md border border-blue-100 dark:border-slate-800">
                                                <i class="fa-solid fa-tag text-[9px] text-blue-400 dark:text-slate-400"></i>
                                                {{ optional($project->category)->name ?? 'Umum' }}
                                            </span>
                                            <span class="text-[11px] text-slate-400 font-medium">
                                                <i class="fa-regular fa-building text-slate-300 dark:text-slate-600 mr-1"></i>
                                                {{ optional($project->owner)->name ?? 'Perusahaan' }}
                                            </span>
                                        </div>

                                        <h2 class="text-base font-bold text-slate-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition truncate">
                                            <a href="{{ route('freelancer.projects.show', $project) }}">
                                                {{ $project->project_name }}
                                            </a>
                                        </h2>

                                        @if(!empty($project->project_description))
                                            <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed">
                                                {{ \Illuminate\Support\Str::limit($project->project_description, 120) }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Action & Budget -->
                                <div class="flex sm:flex-col justify-between items-end shrink-0 pt-3 sm:pt-0 border-t sm:border-t-0 border-slate-100 dark:border-slate-800">
                                    <div class="text-left sm:text-right">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Anggaran</p>
                                        <p class="text-sm font-extrabold text-blue-600 dark:text-blue-400">Rp {{ number_format($project->budget ?? 0, 0, ',', '.') }}</p>
                                    </div>
                                    <a href="{{ route('freelancer.projects.show', $project) }}" 
                                       class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-50 dark:bg-slate-800 hover:bg-blue-600 hover:text-white text-blue-700 dark:text-blue-400 font-bold text-xs rounded-xl transition">
                                        Detail <i class="fa-solid fa-chevron-right text-[9px]"></i>
                                    </a>
                                </div>

                            </div>
                        @empty
                            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-dashed border-blue-200 dark:border-slate-700 p-12 text-center">
                                <i class="fa-regular fa-folder-open text-4xl text-slate-300 dark:text-slate-600 mb-3"></i>
                                <h3 class="text-sm font-bold text-slate-700 dark:text-white">Tidak ada proyek ditemukan</h3>
                                <p class="text-xs text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau filter kategori Anda.</p>
                            </div>
                        @endforelse

                        <!-- Pagination Links -->
                        <div class="pt-4">
                            {{ $projects->links() }}
                        </div>
                    </div>

                    <!-- Right: Latest Applications Sidebar -->
                    <div class="space-y-6">
                        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-blue-100/80 dark:border-slate-800 shadow-xs p-6 sticky top-24 transition-colors duration-300">
                            <div class="flex items-center justify-between mb-4 pb-3 border-b border-blue-50 dark:border-slate-800">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">
                                    Lamaran Saya Terbaru
                                </h3>
                                <a href="{{ route('freelancer.lamaran') }}" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline">
                                    Lihat Semua
                                </a>
                            </div>

                            <div class="space-y-3">
                                @forelse($latestApplications ?? [] as $app)
                                    <div class="p-3 rounded-2xl bg-slate-50/80 dark:bg-slate-800/80 border border-slate-100 dark:border-slate-700 hover:border-blue-200 dark:hover:border-slate-700 transition">
                                        <h4 class="font-bold text-xs text-slate-800 dark:text-white truncate">
                                            {{ optional($app->project)->project_name ?? 'Proyek Dihapus' }}
                                        </h4>
                                        <div class="flex items-center justify-between mt-2 pt-2 border-t border-slate-200/50 dark:border-slate-700">
                                            <span class="text-[10px] text-slate-400 font-medium">
                                                {{ optional($app->created_at)->format('d M Y') }}
                                            </span>
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold
                                                {{ $app->status == 'Menunggu' ? 'bg-amber-100 text-amber-700' : ($app->status == 'Diterima' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700') }}">
                                                {{ $app->status }}
                                            </span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-6 text-slate-400">
                                        <i class="fa-regular fa-paper-plane text-2xl text-slate-300 dark:text-slate-600 mb-2"></i>
                                        <p class="text-xs">Belum ada lamaran diajukan.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Footer -->
                <div class="mt-16">
                    @include('navbar.footer')
                </div>

            </div>
        </main>
    </div>

</body>
</html>