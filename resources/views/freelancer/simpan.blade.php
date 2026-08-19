<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyek Tersimpan - ApexForge Labs</title>
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
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
<body class="bg-[#f6f9ff] dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-300">

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar --}}
    @include('navbar.navigasi')

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Top Navbar --}}
        <div class="sticky top-0 z-40 bg-white dark:bg-slate-900 border-b dark:border-slate-800">
            @include('navbar.nav')
        </div>

        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">

            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white">Proyek Tersimpan</h1>
                <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm sm:text-base">Kumpulan proyek yang telah kamu simpan untuk dilamar nanti.</p>
            </div>

            {{-- Daftar Proyek Tersimpan --}}
            @if($savedProjects->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5 sm:gap-6">
                    @foreach($savedProjects as $saved)
                        @php $project = $saved->project; @endphp
                        @if($project)
                        <div class="group bg-white dark:bg-slate-900 rounded-2xl border border-blue-100 dark:border-slate-800 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 ease-in-out overflow-hidden flex flex-col">

                            {{-- Image --}}
                            <div class="relative h-44 sm:h-48 overflow-hidden bg-blue-50 dark:bg-slate-800">
                                @if($project->image)
                                    <img src="{{ asset('storage/'.$project->image) }}"
                                         alt="{{ $project->project_name }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300 dark:text-slate-600">
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
                                    <span class="text-[11px] font-semibold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-slate-800 px-2.5 py-1 rounded-full w-fit mb-3">
                                        {{ $project->category->name }}
                                    </span>
                                @endif

                                {{-- Project Name --}}
                                <h3 class="text-base font-bold text-slate-900 dark:text-white leading-snug mb-2 line-clamp-2">
                                    {{ $project->project_name }}
                                </h3>

                                {{-- Company / Owner --}}
                                @if($project->owner && $project->owner->name)
                                    <p class="text-xs text-slate-400 dark:text-slate-400 flex items-center gap-1.5 mb-3">
                                        <i class="fa-regular fa-building"></i>
                                        {{ $project->owner->name }}
                                    </p>
                                @endif

                                {{-- Spacer --}}
                                <div class="flex-1"></div>

                                {{-- Budget --}}
                                <div class="flex items-center justify-between border-t border-blue-50 dark:border-slate-800 pt-4 mt-2">
                                    <div>
                                        <p class="text-[10px] text-slate-400 dark:text-slate-400 font-medium uppercase tracking-wider">Budget</p>
                                        <p class="text-sm font-bold text-blue-600 dark:text-blue-400">Rp {{ number_format($project->budget, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[10px] text-slate-400 dark:text-slate-400 font-medium uppercase tracking-wider">Disimpan</p>
                                        <p class="text-xs font-semibold text-slate-600 dark:text-slate-300">
                                            {{ $saved->created_at->isoFormat('D MMM') }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Buttons --}}
                                <div class="mt-4 grid grid-cols-2 gap-2">
                                    <a href="{{ route('freelancer.projects.show', $project) }}"
                                       class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl transition-colors duration-200">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                        Lihat Detail
                                    </a>

                                    <form action="{{ route('freelancer.saved-projects.destroy', $project) }}" method="POST"
                                          onsubmit="return confirm('Hapus proyek ini dari daftar tersimpan?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 border border-red-300 dark:border-red-900 text-red-600 dark:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/40 text-xs font-semibold rounded-xl transition-colors duration-200">
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
                    <div class="w-24 h-24 rounded-full bg-blue-50 dark:bg-slate-800 flex items-center justify-center mb-6">
                        <i class="fa-regular fa-bookmark text-4xl text-slate-300 dark:text-slate-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-700 dark:text-white mb-2">Belum Ada Proyek Tersimpan</h3>
                    <p class="text-sm text-slate-400 dark:text-slate-400 text-center max-w-md">
                        Kamu belum menyimpan proyek apa pun. Simpan proyek yang menarik agar mudah ditemukan kembali.
                    </p>
                    <a href="{{ route('freelancer.proyek') }}"
                       class="mt-6 inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors duration-200">
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

