<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyek Terbaru - ApexForge Labs</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="h-full bg-slate-50 text-slate-800 antialiased selection:bg-blue-500 selection:text-white">

<div class="flex h-screen overflow-hidden">

    @include('navbar.navigasi')

    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        <div class="sticky top-0 z-40 bg-white/80 backdrop-blur-md border-b border-slate-200/80 shadow-xs">
            @include('navbar.nav')
        </div>

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

                {{-- Project Grid --}}
                @if($projects->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                        @foreach($projects as $project)
                            <div class="group bg-white rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:border-blue-200 hover:-translate-y-1.5 transition-all duration-300 ease-out overflow-hidden flex flex-col justify-between">
                                
                                <div>
                                    {{-- Image Header --}}
                                    <div class="relative h-48 overflow-hidden bg-slate-100">
                                        @if($project->image)
                                            <img src="{{ asset('storage/'.$project->image) }}" 
                                                 alt="{{ $project->project_name }}"
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
                                        @else
                                            <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-slate-50 to-blue-50/50 text-slate-300">
                                                <i class="fa-solid fa-image text-4xl mb-1 text-slate-300"></i>
                                                <span class="text-xs font-medium text-slate-400">Tidak ada gambar</span>
                                            </div>
                                        @endif

                                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                                        {{-- Badge Terbaru --}}
                                        @if($project->created_at->gte(\Carbon\Carbon::now()->subDays(7)))
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
                                        {{-- Category --}}
                                        @if($project->category && $project->category->name)
                                            <span class="inline-flex items-center text-[11px] font-bold tracking-wide uppercase text-blue-600 bg-blue-50 border border-blue-100 px-3 py-1 rounded-full mb-3">
                                                {{ $project->category->name }}
                                            </span>
                                        @endif

                                        {{-- Project Name --}}
                                        <h3 class="text-lg font-bold text-slate-900 leading-snug mb-2 group-hover:text-blue-600 transition-colors duration-200 line-clamp-2">
                                            {{ $project->project_name }}
                                        </h3>

                                        {{-- Company / Owner --}}
                                        @if($project->owner && $project->owner->name)
                                            <p class="text-xs font-medium text-slate-500 flex items-center gap-2 mb-3">
                                                <span class="w-5 h-5 rounded-md bg-slate-100 flex items-center justify-center text-slate-500">
                                                    <i class="fa-regular fa-building text-[10px]"></i>
                                                </span>
                                                {{ $project->owner->name }}
                                            </p>
                                        @endif

                                        {{-- Description --}}
                                        @if($project->project_description)
                                            <p class="text-sm text-slate-600 leading-relaxed line-clamp-2">
                                                {{ \Illuminate\Support\Str::limit($project->project_description, 100) }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Footer Card Section --}}
                                <div class="px-6 pb-6 pt-0">
                                    {{-- Budget & Deadline --}}
                                    <div class="flex items-center justify-between border-t border-slate-100 pt-4 mb-4">
                                        <div>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Budget</p>
                                            <p class="text-base font-extrabold text-blue-600">Rp {{ number_format($project->budget, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Deadline</p>
                                            <p class="text-xs font-bold text-slate-700 bg-slate-100 px-2.5 py-1 rounded-md mt-0.5">
                                                <i class="fa-regular fa-calendar-alt mr-1 text-slate-400"></i>
                                                {{ \Carbon\Carbon::parse($project->deadline)->isoFormat('D MMM YYYY') }}
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

                    {{-- Pagination --}}
                    @if(method_exists($projects, 'links'))
                        <div class="mt-12 flex justify-center">
                            {{ $projects->links() }}
                        </div>
                    @endif

                @else
                    {{-- Empty State --}}
                    <div class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center max-w-lg mx-auto my-8 shadow-xs">
                        <div class="w-20 h-20 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-6 shadow-inner">
                            <i class="fa-solid fa-briefcase text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Belum Ada Proyek</h3>
                        <p class="text-sm text-slate-500 leading-relaxed mb-6">
                            Saat ini belum ada proyek terbaru yang dipublikasikan. Silakan kembali lagi nanti untuk melihat proyek-proyek terbaru dari perusahaan.
                        </p>
                        <a href="{{ route('freelancer.dashboard') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold rounded-xl transition-all duration-200 shadow-sm">
                            <i class="fa-solid fa-arrow-left text-xs"></i>
                            Kembali ke Dashboard
                        </a>
                    </div>
                @endif

                <div class="mt-16">
                    @include('navbar.footer')
                </div>
            </div>

        </main>
    </div>
</div>

</body>
</html>