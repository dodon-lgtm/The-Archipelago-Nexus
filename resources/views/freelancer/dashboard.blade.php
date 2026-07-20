<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - The Archipelago Nexus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800">

<div class="flex h-screen overflow-hidden">

    @include('navbar.navigasi')

    <div class="flex-1 flex flex-col overflow-hidden">

        <div class="sticky top-0 z-40 bg-white border-b">
            @include('navbar.nav')
        </div>

        <main class="flex-1 overflow-y-auto p-8">

            {{-- Welcome --}}
            <div class="bg-white rounded-2xl border p-6 shadow-sm mb-8">
                <h2 class="text-3xl font-black">
                    Selamat Datang, <span class="text-cyan-600">{{ auth()->user()->name ?? 'User' }}</span>
                </h2>
                <p class="text-slate-500 mt-2">Temukan pekerjaan freelance terbaik dan mulai karirmu sekarang.</p>
            </div>

            {{-- Statistik --}}
           {{-- Grid Stat Cards yang Interaktif --}}
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-10">
    
    {{-- Card 1: Proyek Baru --}}
    <div class="bg-white rounded-2xl border p-5 shadow-sm hover:shadow-md transition duration-300 group">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-14 h-14 rounded-xl bg-cyan-100 flex items-center justify-center">
                <i class="fa-solid fa-folder-plus text-cyan-600 text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-bold">Proyek Baru</p>
                <h3 class="text-2xl font-black">{{ $projects->count() }}</h3>
            </div>
        </div>
        <a href="{{ route('freelancer.projects.index') }}" class="block text-center text-xs font-bold text-cyan-600 py-2 rounded-lg bg-cyan-50 hover:bg-cyan-600 hover:text-white transition">
            Lihat Semua
        </a>
    </div>

    {{-- Card 2: Lamaran Saya --}}
    <div class="bg-white rounded-2xl border p-5 shadow-sm hover:shadow-md transition duration-300 group">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-14 h-14 rounded-xl bg-blue-100 flex items-center justify-center">
                <i class="fa-solid fa-paper-plane text-blue-600 text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-bold">Lamaran Saya</p>
                <h3 class="text-2xl font-black">0</h3>
            </div>
        </div>
        <a href="#" class="block text-center text-xs font-bold text-blue-600 py-2 rounded-lg bg-blue-50 hover:bg-blue-600 hover:text-white transition">
            Lihat Semua
        </a>
    </div>

    {{-- Card 3: Tersimpan --}}
    <div class="bg-white rounded-2xl border p-5 shadow-sm hover:shadow-md transition duration-300 group">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-14 h-14 rounded-xl bg-purple-100 flex items-center justify-center">
                <i class="fa-solid fa-heart text-purple-600 text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-bold">Tersimpan</p>
                <h3 class="text-2xl font-black">0</h3>
            </div>
        </div>
        <a href="#" class="block text-center text-xs font-bold text-purple-600 py-2 rounded-lg bg-purple-50 hover:bg-purple-600 hover:text-white transition">
            Lihat Semua
        </a>
    </div>

    {{-- Card 4: Pesan Baru --}}
    <div class="bg-white rounded-2xl border p-5 shadow-sm hover:shadow-md transition duration-300 group">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-14 h-14 rounded-xl bg-amber-100 flex items-center justify-center">
                <i class="fa-solid fa-comment text-amber-600 text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-bold">Pesan Baru</p>
                <h3 class="text-2xl font-black">0</h3>
            </div>
        </div>
        <a href="#" class="block text-center text-xs font-bold text-amber-600 py-2 rounded-lg bg-amber-50 hover:bg-amber-600 hover:text-white transition">
            Lihat Pesan
        </a>
    </div>

</div>

            {{-- GRID UTAMA: KIRI (Proyek) & KANAN (Lamaran) --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
                
                {{-- KIRI: Rekomendasi Pekerjaan (2 Kolom) --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-black">Rekomendasi Pekerjaan</h2>
                        <a href="{{ route('freelancer.projects.index') }}" class="text-cyan-600 font-semibold text-sm">Lihat Semua</a>
                    </div>

                    <div class="space-y-4">
                        @forelse($projects as $project)
                            <div class="bg-white border border-slate-200 rounded-2xl p-4 flex items-center justify-between shadow-sm hover:shadow-md transition">
                                <div class="flex items-center gap-4">
                                    <img src="{{ $project->image ? asset('images/projects/'.$project->image) : asset('images/no-image.png') }}" class="w-16 h-16 rounded-xl object-cover">
                                    <div>
                                        <h2 class="text-sm font-bold">{{ $project->project_name }}</h2>
                                        <p class="text-[11px] text-slate-500">{{ $project->category->name ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-cyan-600 text-xs">Rp {{ number_format($project->budget,0,',','.') }}</p>
                                    <a href="{{ route('freelancer.projects.show',$project) }}" class="text-[10px] bg-slate-100 px-3 py-1 rounded-lg mt-2 inline-block">Detail</a>
                                </div>
                            </div>
                        @empty
                            <div class="bg-white rounded-2xl p-6 text-center border">Belum ada proyek.</div>
                        @endforelse
                    </div>
                </div>

                {{-- KANAN: Lamaran Terbaru (1 Kolom) --}}
                <div class="space-y-6">
                    <h2 class="text-xl font-black">Lamaran Terbaru</h2>
                    <div class="bg-white rounded-2xl border p-6 text-center text-slate-500 min-h-[200px] flex items-center justify-center">
                        Belum ada lamaran.
                    </div>
                </div>

            </div>

            @include('navbar.footer')

        </main>
    </div>
</div>

</body>
</html>