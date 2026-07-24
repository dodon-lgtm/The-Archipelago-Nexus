<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lamaran Saya - The Archipelago Nexus</title>
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
                <h1 class="text-3xl sm:text-4xl font-black text-slate-900">Lamaran Saya</h1>
                <p class="text-slate-500 mt-2 text-sm sm:text-base">Pantau semua lamaran proyek yang telah kamu kirim.</p>
            </div>

            {{-- Daftar Lamaran --}}
            @if($lamaran->count() > 0)
                <div class="space-y-4">
                    @foreach($lamaran as $item)
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all duration-300 p-5 sm:p-6">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">

                                {{-- Left: Project Info --}}
                                <div class="flex-1 min-w-0">
                                    {{-- Project Name --}}
                                    <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-1">
                                        {{ $item->project->project_name ?? 'Proyek Tidak Ditemukan' }}
                                    </h3>

                                    {{-- Company / Owner --}}
                                    @if($item->project && $item->project->owner && $item->project->owner->name)
                                        <p class="text-xs sm:text-sm text-slate-400 flex items-center gap-1.5 mb-2">
                                            <i class="fa-regular fa-building"></i>
                                            {{ $item->project->owner->name }}
                                        </p>
                                    @endif

                                    {{-- Category --}}
                                    @if($item->project && $item->project->category && $item->project->category->name)
                                        <span class="inline-block text-[11px] font-semibold text-cyan-600 bg-cyan-50 px-2.5 py-1 rounded-full mb-3">
                                            {{ $item->project->category->name }}
                                        </span>
                                    @endif

                                    {{-- Cover Letter / Pesan --}}
                                    @if($item->pesan)
                                        <p class="text-sm text-slate-500 leading-relaxed mt-1 line-clamp-2">
                                            {{ \Illuminate\Support\Str::limit($item->pesan, 120) }}
                                        </p>
                                    @endif
                                </div>

                                {{-- Right: Price, Status, Date --}}
                                <div class="flex flex-row sm:flex-col items-start sm:items-end gap-4 sm:gap-2 flex-shrink-0">
                                    
                                    {{-- Proposed Price --}}
                                    <div class="text-left sm:text-right">
                                        <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">Harga Penawaran</p>
                                        <p class="text-sm sm:text-base font-bold text-cyan-600">
                                            Rp {{ number_format($item->harga_penawaran, 0, ',', '.') }}
                                        </p>
                                    </div>

                                    {{-- Estimated Days --}}
                                    <div class="text-left sm:text-right">
                                        <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">Estimasi</p>
                                        <p class="text-xs sm:text-sm font-semibold text-slate-600">
                                            {{ $item->estimasi_hari }} Hari
                                        </p>
                                    </div>

                                    {{-- Status Badge --}}
                                    <div class="mt-0 sm:mt-1">
                                        @if($item->status === 'Menunggu')
                                            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold border border-yellow-200">
                                                <i class="fa-solid fa-clock"></i>
                                                Menunggu
                                            </span>
                                        @elseif($item->status === 'Diterima')
                                            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-green-100 text-green-700 text-xs font-bold border border-green-200">
                                                <i class="fa-solid fa-check-circle"></i>
                                                Diterima
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-red-100 text-red-700 text-xs font-bold border border-red-200">
                                                <i class="fa-solid fa-times-circle"></i>
                                                Ditolak
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Bottom: Date & Action --}}
                            <div class="mt-4 pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                                {{-- Submission Date --}}
                                <p class="text-xs text-slate-400 flex items-center gap-1.5">
                                    <i class="fa-regular fa-calendar"></i>
                                    Diajukan {{ $item->created_at->isoFormat('D MMMM YYYY') }}
                                </p>

                                {{-- Action Button --}}
                                <div class="flex items-center gap-2">
                                    @if($item->project)
                                        <a href="{{ route('freelancer.projects.show', $item->project) }}"
                                           class="inline-flex items-center gap-2 px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white text-xs font-semibold rounded-xl transition-colors duration-200">
                                            Lihat Detail Proyek
                                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                        </a>
                                    @endif
                                    @if($item->status === 'Diterima' && $item->project && $item->project->workspace)
                                        <a href="{{ route('freelancer.workspaces.show', $item->project->workspace) }}"
                                           class="inline-flex items-center gap-2 px-4 py-2 bg-brand hover:bg-blue-700 text-white text-xs font-semibold rounded-xl transition-colors duration-200">
                                            <i class="fa-solid fa-external-link-alt text-[10px]"></i>
                                            Buka Workspace
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if(method_exists($lamaran, 'links'))
                    <div class="mt-10">
                        {{ $lamaran->links() }}
                    </div>
                @endif

            @else
                {{-- Empty State --}}
                <div class="flex flex-col items-center justify-center py-20 px-4">
                    <div class="w-24 h-24 rounded-full bg-slate-100 flex items-center justify-center mb-6">
                        <i class="fa-regular fa-paper-plane text-4xl text-slate-300"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-700 mb-2">Belum Ada Lamaran</h3>
                    <p class="text-sm text-slate-400 text-center max-w-md">
                        Kamu belum mengirim lamaran ke proyek mana pun.
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

