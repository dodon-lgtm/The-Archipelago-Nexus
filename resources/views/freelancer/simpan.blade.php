<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyek Tersimpan - The Archipelago Nexus</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800">

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar --}}
    @include('navbar.navigasi')

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Top Navbar --}}
        <div class="sticky top-0 z-40 bg-white border-b">
            @include('navbar.nav')
        </div>

        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">

            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-3xl sm:text-4xl font-black text-slate-900">Proyek Tersimpan</h1>
                <p class="text-slate-500 mt-2 text-sm sm:text-base">Kumpulan proyek yang telah kamu simpan untuk dilamar nanti.</p>
            </div>

            {{-- Daftar Proyek Tersimpan --}}
            @if($savedProjects->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5 sm:gap-6">
                    @foreach($savedProjects as $saved)
                        @php $project = $saved->project; @endphp
                        @if($project)
                        <div class="group bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 ease-in-out overflow-hidden flex flex-col">

                            {{-- Image --}}
                            <div class="relative h-44 sm:h-48 overflow-hidden bg-slate-100">
                                @if($project->image)
                                    <img src="{{ asset('storage/'.$project->image) }}"
                                         alt="{{ $project->project_name }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                        <i class="fa-solid fa-image text-5xl"></i>
                                    </div>
                                @endif

                                {{-- Saved Badge --}}
                                <span class="absolute top-3 left-3 inline-flex items-center gap-1 px-3 py-1.5 bg-blue-500 text-white text-[10px] font-bold rounded-full shadow-lg">
                                    <i class="fa-solid fa-bookmark text-[10px]"></i>
                                    Tersimpan
                                </span>
                            </div>

                            {{-- Content --}}
                            <div class="p-5 flex flex-col flex-1">

                                {{-- Category --}}
                                @if($project->category && $project->category->name)
                                    <span class="text-[11px] font-semibold text-cyan-600 bg-cyan-50 px-2.5 py-1 rounded-full w-fit mb-3">
                                        {{ $project->category->name }}
                                    </span>
                                @endif

                                {{-- Project Name --}}
                                <h3 class="text-base font-bold text-slate-900 leading-snug mb-2 line-clamp-2">
                                    {{ $project->project_name }}
                                </h3>

                                {{-- Company / Owner --}}
                                @if($project->owner && $project->owner->name)
                                    <p class="text-xs text-slate-400 flex items-center gap-1.5 mb-3">
                                        <i class="fa-regular fa-building"></i>
                                        {{ $project->owner->name }}
                                    </p>
                                @endif

                                {{-- Spacer --}}
                                <div class="flex-1"></div>

                                {{-- Budget --}}
                                <div class="flex items-center justify-between border-t border-slate-100 pt-4 mt-2">
                                    <div>
                                        <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">Budget</p>
                                        <p class="text-sm font-bold text-cyan-600">Rp {{ number_format($project->budget, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">Disimpan</p>
                                        <p class="text-xs font-semibold text-slate-600">
                                            {{ $saved->created_at->isoFormat('D MMM') }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Buttons --}}
                                <div class="mt-4 grid grid-cols-2 gap-2">
                                    <a href="{{ route('freelancer.projects.show', $project) }}"
                                       class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white text-xs font-semibold rounded-xl transition-colors duration-200">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                        Lihat Detail
                                    </a>

                                    <form action="{{ route('freelancer.saved-projects.destroy', $project) }}" method="POST"
                                          onsubmit="return confirm('Hapus proyek ini dari daftar tersimpan?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 border border-red-300 text-red-600 hover:bg-red-50 text-xs font-semibold rounded-xl transition-colors duration-200">
                                            <i class="fa-solid fa-bookmark-slash text-xs"></i>
                                            Batalkan
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if(method_exists($savedProjects, 'links'))
                    <div class="mt-10">
                        {{ $savedProjects->links() }}
                    </div>
                @endif

            @else
                {{-- Empty State --}}
                <div class="flex flex-col items-center justify-center py-20 px-4">
                    <div class="w-24 h-24 rounded-full bg-slate-100 flex items-center justify-center mb-6">
                        <i class="fa-regular fa-bookmark text-4xl text-slate-300"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-700 mb-2">Belum Ada Proyek Tersimpan</h3>
                    <p class="text-sm text-slate-400 text-center max-w-md">
                        Kamu belum menyimpan proyek apa pun. Simpan proyek yang menarik agar mudah ditemukan kembali.
                    </p>
                    <a href="{{ route('freelancer.projects.index') }}"
                       class="mt-6 inline-flex items-center gap-2 px-5 py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white text-sm font-semibold rounded-xl transition-colors duration-200">
                        <i class="fa-solid fa-search text-xs"></i>
                        Cari Proyek
                    </a>
                </div>
            @endif

            {{-- Footer --}}
            @include('navbar.footer')

        </main>
    </div>
</div>

</body>
</html>

