<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Proyek | The Archipelago Nexus</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body class="bg-slate-50 text-slate-800">

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
            <main class="px-6 py-8 lg:px-10">
                <div class="max-w-6xl mx-auto">

                    {{-- =================================================
                        HEADER (Desain Baru Lebih Elegan & Minimalis)
                    ================================================== --}}
                    <section class="relative bg-white border border-slate-200/80 rounded-3xl px-8 py-7 shadow-sm overflow-hidden flex items-center justify-between">
                        <div class="absolute left-0 top-0 bottom-0 w-2 bg-cyan-500"></div>
                        <div class="relative z-10 pl-2">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 rounded-xl bg-cyan-50 flex items-center justify-center text-cyan-600">
                                    <i class="fa-solid fa-folder-open text-lg"></i>
                                </div>
                                <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">
                                    Daftar Proyek
                                </h1>
                            </div>
                            <p class="text-sm text-slate-500 leading-relaxed max-w-xl">
                                Kelola semua proyek yang telah kamu buat dan temukan freelancer terbaik untuk mengerjakannya.
                            </p>
                        </div>
                    </section>

                    {{-- =================================================
                        SUCCESS MESSAGE
                    ================================================== --}}
                    @if (session('success'))
                        <div class="mt-6 flex items-center gap-3 px-5 py-4 rounded-2xl bg-emerald-50 border border-emerald-200">
                            <div class="w-9 h-9 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-check text-emerald-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-emerald-700">Berhasil</p>
                                <p class="text-sm text-emerald-600">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- =================================================
                        HEADER LIST PROYEK
                    ================================================== --}}
                    <div class="flex items-end justify-between gap-4 mt-8 mb-5">
                        <div>
                            <h2 class="text-xl font-extrabold text-slate-800">
                                Semua Proyek
                            </h2>
                            <p class="mt-1 text-sm text-slate-500">
                                Daftar proyek yang telah kamu buat.
                            </p>
                        </div>

                        @if ($projects->count() > 0)
                            <div class="hidden sm:flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-slate-200 shadow-sm">
                                <i class="fa-solid fa-folder text-cyan-500 text-sm"></i>
                                <span class="text-sm font-semibold text-slate-600">
                                    {{ $projects->total() }} Proyek
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- =================================================
                        LIST PROYEK (Interaktif & Berubah Warna Halus)
                    ================================================== --}}
                    <div class="space-y-4">
                        @forelse ($projects as $project)
                            <a href="{{ route('company.projects.show', $project) }}" 
                               class="group relative flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-5 bg-white border border-slate-200/80 rounded-2xl shadow-sm hover:border-cyan-400 hover:bg-cyan-50/30 transition-colors duration-200">
                                
                                <div class="flex items-start sm:items-center gap-4 min-w-0 flex-1">
                                    {{-- ICON --}}
                                    <div class="w-12 h-12 shrink-0 rounded-xl bg-cyan-50 flex items-center justify-center text-cyan-600 group-hover:bg-cyan-500 group-hover:text-white transition-colors duration-200">
                                        <i class="fa-solid fa-briefcase text-base"></i>
                                    </div>

                                    {{-- INFO --}}
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-base font-bold text-slate-800 group-hover:text-cyan-600 transition-colors truncate">
                                            {{ $project->project_name }}
                                        </h3>
                                        <p class="mt-1 text-sm text-slate-500 truncate">
                                            {{ Str::limit($project->project_description ?? '-', 90) }}
                                        </p>

                                        {{-- Informasi Budget & Tanggal --}}
                                        <div class="mt-3 flex items-center gap-4 text-xs text-slate-500">
                                            @if(isset($project->budget))
                                                <span class="flex items-center gap-1.5 font-semibold text-slate-700">
                                                    <i class="fa-solid fa-wallet text-cyan-500"></i> Rp {{ number_format($project->budget, 0, ',', '.') }}
                                                </span>
                                            @endif
                                            @if(isset($project->deadline) || isset($project->created_at))
                                                <span class="flex items-center gap-1.5 text-slate-400">
                                                    <i class="fa-regular fa-calendar text-slate-400"></i> {{ optional($project->deadline ?? $project->created_at)->format('d M Y') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- BADGE STATUS & ARROW --}}
                                <div class="flex items-center justify-between sm:justify-end gap-3 pt-3 sm:pt-0 border-t sm:border-t-0 border-slate-100">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100">
                                        Open
                                    </span>
                                    <div class="w-9 h-9 shrink-0 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-cyan-500 group-hover:text-white transition-colors duration-200">
                                        <i class="fa-solid fa-arrow-right text-xs"></i>
                                    </div>
                                </div>

                            </a>
                        @empty
                            {{-- =================================================
                                EMPTY STATE
                            ================================================== --}}
                            <div class="bg-white border border-dashed border-slate-300 rounded-3xl px-6 py-16 text-center shadow-sm">
                                <div class="w-20 h-20 mx-auto rounded-3xl bg-cyan-50 flex items-center justify-center text-cyan-500">
                                    <i class="fa-solid fa-folder-open text-3xl"></i>
                                </div>
                                <h3 class="mt-6 text-xl font-bold text-slate-800">
                                    Belum Ada Proyek
                                </h3>
                                <p class="mt-2 max-w-md mx-auto text-sm text-slate-500 leading-relaxed">
                                    Kamu belum membuat proyek. Buat proyek pertamamu dan temukan freelancer terbaik untuk membantu pekerjaanmu.
                                </p>
                                <a href="{{ route('company.projects.create') }}" class="inline-flex items-center gap-2 mt-6 px-5 py-3 rounded-xl bg-cyan-500 text-white text-sm font-bold hover:bg-cyan-600 shadow-sm transition">
                                    <i class="fa-solid fa-plus"></i>
                                    Buat Proyek
                                </a>
                            </div>
                        @endforelse
                    </div>

                    {{-- =================================================
                        PAGINATION
                    ================================================== --}}
                    @if ($projects->hasPages())
                        <div class="mt-8 flex justify-center">
                            {{ $projects->links() }}
                        </div>
                    @endif

                </div>
            </main>

            {{-- =================================================
                FOOTER
            ================================================== --}}
            <div class="px-6 lg:px-10">
                @include('navbar.footer')
            </div>

        </div>
    </div>

</body>
</html>