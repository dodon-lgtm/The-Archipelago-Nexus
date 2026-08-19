<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lamaran Saya - ApexForge Labs</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- SweetAlert2 CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
<body class="bg-[#f6f9ff] text-slate-800">

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

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-3">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- Daftar Lamaran --}}
            @if($lamaran->count() > 0)
                <div class="space-y-4">
                    @foreach($lamaran as $item)
                        <div class="bg-white rounded-2xl border border-blue-100 shadow-sm hover:shadow-md transition-all duration-300 p-5 sm:p-6">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">

                                {{-- Left: Project Info --}}
                                <div class="flex-1 min-w-0">
                                    {{-- Project Name --}}
                                    <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-1">
                                        {{ $item->project->project_name ?? 'Proyek Tidak Ditemukan' }}
                                    </h3>

                                    {{-- Company / Owner --}}
                                    @if($item->project?->owner?->name)
                                        <p class="text-xs sm:text-sm text-slate-400 flex items-center gap-1.5 mb-2">
                                            <i class="fa-regular fa-building"></i>
                                            {{ $item->project->owner->name }}
                                        </p>
                                    @endif

                                    {{-- Category --}}
                                    @if($item->project?->category?->name)
                                        <span class="inline-block text-[11px] font-semibold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full mb-3">
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
                                        <p class="text-sm sm:text-base font-bold text-blue-600">
                                            Rp {{ number_format($item->harga_penawaran ?? 0, 0, ',', '.') }}
                                        </p>
                                    </div>

                                    {{-- Estimated Days --}}
                                    <div class="text-left sm:text-right">
                                        <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">Estimasi</p>
                                        <p class="text-xs sm:text-sm font-semibold text-slate-600">
                                            {{ $item->estimasi_hari ?? '-' }} Hari
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
                            <div class="mt-4 pt-4 border-t border-blue-50 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                                {{-- Submission Date --}}
                                <p class="text-xs text-slate-400 flex items-center gap-1.5">
                                    <i class="fa-regular fa-calendar"></i>
                                    Diajukan {{ optional($item->created_at)->isoFormat('D MMMM YYYY') ?? '-' }}
                                </p>

                                {{-- Action Button --}}
                                <div class="flex items-center gap-2">
                                    @if($item->project)
                                        <a href="{{ route('freelancer.projects.show', $item->project) }}"
                                           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl transition-colors duration-200">
                                            Lihat Detail Proyek
                                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                        </a>
                                    @endif
                                    @if($item->status === 'Diterima' && $item->project?->workspace)
                                        <a href="{{ route('freelancer.workspaces.show', $item->project->workspace) }}"
                                           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl transition-colors duration-200">
                                            <i class="fa-solid fa-external-link-alt text-[10px]"></i>
                                            Buka Workspace
                                        </a>
                                    @endif
                                    @if($item->status === 'Menunggu')
                                        <form id="delete-form-{{ $item->id }}" action="{{ route('freelancer.penawaran.destroy', $item) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    onclick="confirmCancel('delete-form-{{ $item->id }}')"
                                                    class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold rounded-xl transition-colors duration-200 border border-red-200">
                                                <i class="fa-solid fa-ban text-[10px]"></i>
                                                Batalkan Penawaran
                                            </button>
                                        </form>
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
                    <div class="w-24 h-24 rounded-full bg-blue-50 flex items-center justify-center mb-6">
                        <i class="fa-regular fa-paper-plane text-4xl text-slate-300"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-700 mb-2">Belum Ada Lamaran</h3>
                    <p class="text-sm text-slate-400 text-center max-w-md">
                        Kamu belum mengirim lamaran ke proyek mana pun.
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

{{-- SweetAlert2 Confirmation Script --}}
<script>
    function confirmCancel(formId) {
        Swal.fire({
            title: 'Batalkan Penawaran?',
            text: 'Anda yakin ingin membatalkan penawaran ini? Setelah dibatalkan, Anda dapat mengirim penawaran baru pada proyek ini.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Tidak',
            customClass: {
                popup: 'rounded-3xl',
                title: 'text-slate-900 font-bold',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }
</script>

</body>
</html>