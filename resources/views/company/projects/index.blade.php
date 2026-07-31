<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Proyek | The Archipelago Nexus</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body class="bg-slate-50 text-slate-800 antialiased">

    {{-- =====================================================
        LAYOUT UTAMA
    ====================================================== --}}
    <div class="min-h-screen flex">

        {{-- =====================================================
            SIDEBAR
        ====================================================== --}}
        @include('navbar.navigasi')

        {{-- =====================================================
            AREA KANAN
        ====================================================== --}}
        <div class="flex-1 min-w-0">

            {{-- =================================================
                NAVBAR ATAS
            ================================================== --}}
            @include('navbar.nav')

            {{-- =================================================
                KONTEN
            ================================================== --}}
            <main class="px-4 sm:px-6 lg:px-10 py-6 sm:py-8">
                <div class="max-w-6xl mx-auto">

                    {{-- =================================================
                        HEADER PREMIUM
                    ================================================== --}}
                    <section class="relative bg-gradient-to-br from-white to-slate-50/80 border border-slate-200/70 rounded-2xl sm:rounded-3xl px-5 sm:px-8 py-6 sm:py-7 shadow-sm overflow-hidden">
                        {{-- Accent gradient bar --}}
                        <div class="absolute left-0 top-0 bottom-0 w-1 sm:w-1.5 bg-gradient-to-b from-cyan-400 via-cyan-500 to-teal-500"></div>

                        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pl-3 sm:pl-4">
                            <div class="min-w-0">
                                <div class="flex items-center gap-3 mb-1.5">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-50 to-teal-50 flex items-center justify-center text-cyan-600 shadow-sm ring-1 ring-cyan-200/50 shrink-0">
                                        <i class="fa-solid fa-folder-open text-lg"></i>
                                    </div>
                                    <div>
                                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">
                                            Daftar Proyek
                                        </h1>
                                        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed max-w-xl mt-0.5">
                                            Kelola semua proyek yang telah kamu buat dan temukan freelancer terbaik untuk mengerjakannya.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Summary badge --}}
                            @if ($projects->count() > 0)
                                <div class="shrink-0 self-start sm:self-center flex items-center gap-2.5 px-4 py-2 rounded-xl bg-white border border-slate-200/70 shadow-sm">
                                    <div class="w-8 h-8 rounded-lg bg-cyan-50 flex items-center justify-center text-cyan-500">
                                        <i class="fa-solid fa-layer-group text-xs"></i>
                                    </div>
                                    <div class="text-right">
                                        <span class="block text-lg font-extrabold text-slate-800 leading-none">{{ $projects->total() }}</span>
                                        <span class="block text-[11px] font-medium text-slate-400 leading-tight">Proyek</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </section>

                    {{-- =================================================
                        SUCCESS MESSAGE
                    ================================================== --}}
                    @if (session('success'))
                        <div class="mt-5 sm:mt-6 flex items-center gap-3 px-4 sm:px-5 py-3.5 sm:py-4 rounded-xl sm:rounded-2xl bg-emerald-50 border border-emerald-200 shadow-sm">
                            <div class="w-9 h-9 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-check text-emerald-600"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-emerald-700">Berhasil</p>
                                <p class="text-sm text-emerald-600 truncate">{{ session('success') }}</p>
                            </div>
                            <button onclick="this.parentElement.remove()" class="ml-auto shrink-0 w-7 h-7 rounded-full flex items-center justify-center text-emerald-400 hover:text-emerald-600 hover:bg-emerald-100 transition-colors">
                                <i class="fa-solid fa-xmark text-sm"></i>
                            </button>
                        </div>
                    @endif

                    {{-- =================================================
                        HEADER LIST
                    ================================================== --}}
                    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 mt-7 sm:mt-8 mb-4 sm:mb-5">
                        <div>
                            <h2 class="text-lg sm:text-xl font-extrabold text-slate-800">
                                Semua Proyek
                            </h2>
                            <p class="mt-0.5 sm:mt-1 text-xs sm:text-sm text-slate-500">
                                Daftar proyek yang telah kamu buat.
                            </p>
                        </div>
                    </div>

                    {{-- =================================================
                        LIST PROYEK — MODERN CARDS
                    ================================================== --}}
                    <div class="space-y-3 sm:space-y-4">
                        @forelse ($projects as $project)
                            <a href="{{ route('company.projects.show', $project) }}"
                               class="group relative block bg-white border border-slate-200/70 rounded-xl sm:rounded-2xl shadow-sm hover:shadow-md hover:border-cyan-300/60 transition-all duration-200">

                                {{-- Inner flex layout: vertical on mobile, horizontal on sm+ --}}
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-4 sm:p-5">

                                    {{-- LEFT: Icon + Info --}}
                                    <div class="flex items-start gap-3.5 sm:gap-4 min-w-0 flex-1">

                                        {{-- Icon --}}
                                        <div class="w-10 h-10 sm:w-12 sm:h-12 shrink-0 rounded-xl bg-gradient-to-br from-cyan-50 to-teal-50 flex items-center justify-center text-cyan-600 group-hover:from-cyan-500 group-hover:to-teal-500 group-hover:text-white transition-all duration-200 ring-1 ring-cyan-200/40 group-hover:ring-0">
                                            <i class="fa-solid fa-briefcase text-sm sm:text-base"></i>
                                        </div>

                                        {{-- Info --}}
                                        <div class="min-w-0 flex-1">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <h3 class="text-sm sm:text-base font-bold text-slate-800 group-hover:text-cyan-600 transition-colors truncate">
                                                    {{ $project->project_name }}
                                                </h3>
                                                {{-- Category badge --}}
                                                @if($project->relationLoaded('category') && $project->category)
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-100 text-[11px] font-medium text-slate-500 border border-slate-200/60 shrink-0">
                                                        <i class="fa-solid fa-tag text-[10px]"></i>
                                                        {{ $project->category->name }}
                                                    </span>
                                                @endif
                                            </div>

                                            {{-- Description --}}
                                            @if($project->project_description)
                                                <p class="mt-1 text-xs sm:text-sm text-slate-500 line-clamp-2 sm:line-clamp-1">
                                                    {{ Str::limit($project->project_description, 100) }}
                                                </p>
                                            @endif

                                            {{-- Metadata row --}}
                                            <div class="mt-2.5 sm:mt-3 flex flex-wrap items-center gap-x-4 gap-y-1.5 text-xs text-slate-500">
                                                @if(isset($project->budget) && $project->budget)
                                                    <span class="inline-flex items-center gap-1.5 font-semibold text-slate-700">
                                                        <i class="fa-solid fa-wallet text-cyan-500 text-[11px]"></i>
                                                        Rp{{ number_format($project->budget, 0, ',', '.') }}
                                                    </span>
                                                @endif
                                                @if(isset($project->deadline))
                                                    <span class="inline-flex items-center gap-1.5 text-slate-400">
                                                        <i class="fa-regular fa-calendar text-[11px]"></i>
                                                        {{ \Carbon\Carbon::parse($project->deadline)->format('d M Y') }}
                                                    </span>
                                                @endif
                                                {{-- Skills preview --}}
                                                @if($project->skills)
                                                    @php $skillList = explode(',', $project->skills); @endphp
                                                    <span class="inline-flex items-center gap-1 text-slate-400 truncate max-w-[140px] sm:max-w-[200px]">
                                                        <i class="fa-solid fa-code text-[11px]"></i>
                                                        {{ trim($skillList[0]) }}{{ count($skillList) > 1 ? ', …' : '' }}
                                                    </span>
                                                @endif
                                                {{-- Penawaran count --}}
                                                @if($project->relationLoaded('penawarans'))
                                                    <span class="inline-flex items-center gap-1 text-slate-400">
                                                        <i class="fa-solid fa-handshake text-[11px]"></i>
                                                        {{ $project->penawarans->count() }} penawaran
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    {{-- RIGHT: Status + Action --}}
                                    <div class="flex items-center justify-between sm:justify-end gap-3 pt-3 sm:pt-0 border-t sm:border-t-0 border-slate-100 sm:pl-4 sm:ml-0">
                                        {{-- Dynamic status badge --}}
                                        @php
                                            $status = $project->status ?? 'Open';
                                        @endphp
                                        @if(strtolower($status) === 'open')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/70 shadow-sm">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                {{ $status }}
                                            </span>
                                        @elseif(strtolower($status) === 'closed')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-full bg-slate-100 text-slate-600 border border-slate-200/70">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                                {{ $status }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-full bg-slate-100 text-slate-600 border border-slate-200/70">
                                                {{ $status }}
                                            </span>
                                        @endif

                                        {{-- Arrow button --}}
                                        <div class="w-8 h-8 sm:w-9 sm:h-9 shrink-0 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-cyan-500 group-hover:text-white group-hover:shadow-sm transition-all duration-200">
                                            <i class="fa-solid fa-arrow-right text-xs"></i>
                                        </div>
                                    </div>

                                </div>
                            </a>
                        @empty
                            {{-- =================================================
                                EMPTY STATE — PROFESSIONAL
                            ================================================== --}}
                            <div class="relative bg-white border border-dashed border-slate-200 rounded-2xl sm:rounded-3xl px-6 sm:px-8 py-14 sm:py-20 text-center shadow-sm overflow-hidden">
                                {{-- Subtle background decoration --}}
                                <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-cyan-50/40 blur-2xl"></div>
                                <div class="absolute -bottom-10 -left-10 w-40 h-40 rounded-full bg-teal-50/30 blur-2xl"></div>

                                <div class="relative z-10">
                                    <div class="w-20 h-20 sm:w-24 sm:h-24 mx-auto rounded-2xl sm:rounded-3xl bg-gradient-to-br from-cyan-50 to-teal-50 flex items-center justify-center text-cyan-400 ring-1 ring-cyan-200/50">
                                        <i class="fa-solid fa-folder-open text-3xl sm:text-4xl"></i>
                                    </div>
                                    <h3 class="mt-5 sm:mt-6 text-xl sm:text-2xl font-extrabold text-slate-800">
                                        Belum Ada Proyek
                                    </h3>
                                    <p class="mt-2 max-w-md mx-auto text-sm sm:text-base text-slate-500 leading-relaxed">
                                        Kamu belum membuat proyek. Buat proyek pertamamu dan temukan freelancer terbaik untuk membantu pekerjaanmu.
                                    </p>
                                    <a href="{{ route('company.projects.create') }}"
                                       class="inline-flex items-center gap-2 mt-6 sm:mt-7 px-5 sm:px-6 py-3 sm:py-3.5 rounded-xl bg-gradient-to-r from-cyan-500 to-teal-500 text-white text-sm font-bold hover:from-cyan-600 hover:to-teal-600 shadow-sm hover:shadow-md transition-all duration-200">
                                        <i class="fa-solid fa-plus"></i>
                                        Buat Proyek Baru
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    {{-- =================================================
                        PAGINATION
                    ================================================== --}}
                    @if ($projects->hasPages())
                        <div class="mt-8 sm:mt-10 flex justify-center">
                            <div class="bg-white border border-slate-200/70 rounded-xl shadow-sm overflow-hidden">
                                {{ $projects->links() }}
                            </div>
                        </div>
                    @endif

                </div>
            </main>

            {{-- =================================================
                FOOTER
            ================================================== --}}
            <div class="px-4 sm:px-6 lg:px-10">
                @include('navbar.footer')
            </div>

        </div>
    </div>

</body>
</html>
