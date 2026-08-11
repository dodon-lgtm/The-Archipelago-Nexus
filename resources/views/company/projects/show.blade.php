<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $project->project_name }} - Detail Proyek</title>

    {{-- Google Font --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Lexend:wght@600;700;800&display=swap"
        rel="stylesheet"
    >

    {{-- Font Awesome --}}
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    >

    {{-- Tailwind --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* ==========================================================
           NOTE: Semua warna di bawah pakai kelas Tailwind bawaan
           (blue/indigo/sky/cyan/slate) — sengaja TIDAK bergantung pada
           tailwind.config custom, supaya tidak ada risiko kelas gagal
           ter-generate dan elemen jadi kosong/putih.
           ========================================================== */

        body {
            font-family: 'Inter', sans-serif;
            background-color: #EEF2FF;
            background-image:
                radial-gradient(circle at 10% 0%, rgba(59, 130, 246, 0.16), transparent 40%),
                radial-gradient(circle at 90% 30%, rgba(14, 165, 233, 0.14), transparent 40%),
                radial-gradient(circle at 20% 100%, rgba(99, 102, 241, 0.14), transparent 40%);
            background-attachment: fixed;
        }

        .font-display { font-family: 'Lexend', sans-serif; }

        /* ==========================================================
           ANIMASI — semua berbasis transform, TIDAK PERNAH opacity:0
           permanen, jadi konten selalu terlihat walau animasi gagal
           dijalankan oleh browser.
           ========================================================== */
        @keyframes slideUp {
            from { transform: translateY(14px); }
            to { transform: translateY(0); }
        }

        @keyframes scalePop {
            from { transform: scale(0.97); }
            to { transform: scale(1); }
        }

        @keyframes pulseRing {
            0% { box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.55); }
            70% { box-shadow: 0 0 0 9px rgba(255, 255, 255, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 255, 255, 0); }
        }

        @keyframes pulseRingAmber {
            0% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.4); }
            70% { box-shadow: 0 0 0 9px rgba(245, 158, 11, 0); }
            100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
        }

        @keyframes floatBlob1 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(16px, -18px) scale(1.06); }
        }
        @keyframes floatBlob2 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(-20px, 16px) scale(1.08); }
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes shine {
            0% { left: -75%; }
            100% { left: 125%; }
        }

        .animate-slide { animation: slideUp 0.55s cubic-bezier(0.22, 1, 0.36, 1) both; }
        .animate-pop { animation: scalePop 0.4s cubic-bezier(0.22, 1, 0.36, 1) both; }

        .delay-1 { animation-delay: 0.05s; }
        .delay-2 { animation-delay: 0.12s; }
        .delay-3 { animation-delay: 0.2s; }

        .status-dot-live { animation: pulseRing 2s infinite; }
        .status-dot-live-amber { animation: pulseRingAmber 2s infinite; }

        .hero-gradient {
            background: linear-gradient(120deg, #1D4ED8, #2563EB, #0EA5E9, #1D4ED8);
            background-size: 300% 300%;
            animation: gradientShift 9s ease infinite;
        }

        .blob {
            position: absolute;
            border-radius: 9999px;
            filter: blur(28px);
            pointer-events: none;
        }
        .blob-1 { animation: floatBlob1 9s ease-in-out infinite; }
        .blob-2 { animation: floatBlob2 11s ease-in-out infinite; }

        .lift-card {
            transition: transform 0.28s cubic-bezier(0.22, 1, 0.36, 1),
                        box-shadow 0.28s cubic-bezier(0.22, 1, 0.36, 1),
                        border-color 0.28s ease;
        }
        .lift-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 40px -14px rgba(37, 99, 235, 0.35);
            border-color: rgb(191 219 254);
        }

        .lift-card-strong {
            transition: transform 0.3s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.3s ease;
        }
        .lift-card-strong:hover {
            transform: translateY(-6px) scale(1.015);
            box-shadow: 0 20px 40px -16px rgba(37, 99, 235, 0.45);
        }

        .icon-pop { transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
        .icon-pop:hover { transform: scale(1.1) rotate(-3deg); }

        .btn-shine { position: relative; overflow: hidden; }
        .btn-shine::after {
            content: "";
            position: absolute;
            top: 0;
            left: -75%;
            width: 50%;
            height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255,255,255,0.4), transparent);
            transform: skewX(-20deg);
        }
        .btn-shine:hover::after { animation: shine 0.7s ease forwards; }

        .stat-watermark {
            position: absolute;
            right: -8px;
            bottom: -16px;
            font-size: 5.5rem;
            opacity: 0.18;
            transform: rotate(-8deg);
            pointer-events: none;
        }

        .dot-grid {
            background-image: radial-gradient(rgba(255,255,255,0.35) 1px, transparent 1px);
            background-size: 16px 16px;
        }

        .reveal-on-scroll {
            transform: translateY(18px);
            opacity: 0.001;
            transition: opacity 0.55s cubic-bezier(0.22, 1, 0.36, 1),
                        transform 0.55s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .reveal-on-scroll.revealed { opacity: 1; transform: translateY(0); }
        /* fallback: kalau JS/observer gagal, tampilkan penuh setelah 1.2s */
        .reveal-fallback { animation: forceShow 0.01s 1.2s forwards; }
        @keyframes forceShow { to { opacity: 1; transform: none; } }

        @media (prefers-reduced-motion: reduce) {
            .animate-slide, .animate-pop, .status-dot-live, .status-dot-live-amber,
            .hero-gradient, .blob, .reveal-on-scroll {
                animation: none !important;
                transition: none !important;
                opacity: 1 !important;
                transform: none !important;
            }
        }
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


<body class="text-slate-800 min-h-screen flex">


    {{-- =====================================================
        SIDEBAR
    ====================================================== --}}
    @include('navbar.navigasi')


    {{-- =====================================================
        AREA KANAN
    ====================================================== --}}
    <div class="flex-1 min-w-0 flex flex-col min-h-screen">


        {{-- =================================================
            NAVBAR ATAS
        ================================================== --}}
        @include('navbar.nav')


        {{-- =================================================
            KONTEN UTAMA
        ================================================== --}}
        <main class="flex-1 min-w-0 overflow-y-auto">

            <div class="max-w-7xl mx-auto px-6 py-6">


                {{-- =========================================
                    HERO BANNER (breadcrumb + judul + aksi + ringkasan)
                ========================================== --}}
                <div class="hero-gradient animate-slide relative overflow-hidden rounded-3xl p-6 md:p-8 mb-6 shadow-xl shadow-blue-500/25">

                    {{-- blob dekoratif --}}
                    <div class="blob blob-1 w-40 h-40 bg-white/15 -top-10 -left-10"></div>
                    <div class="blob blob-2 w-56 h-56 bg-sky-300/25 -bottom-16 right-10"></div>
                    <div class="absolute inset-0 dot-grid opacity-20"></div>

                    <div class="relative z-10">

                        {{-- BREADCRUMB --}}
                        <div class="flex items-center gap-2 text-xs text-blue-100 mb-4">

                            <a href="{{ route('company.dashboard') }}" class="hover:text-white transition-colors duration-200">
                                Dashboard
                            </a>

                            <i class="fa-solid fa-chevron-right text-[9px]"></i>

                            <a href="{{ route('company.projects.index') }}" class="hover:text-white transition-colors duration-200">
                                Proyek
                            </a>

                            <i class="fa-solid fa-chevron-right text-[9px]"></i>

                            <span class="text-white font-medium">Detail</span>

                        </div>

                        {{-- HEADER HALAMAN --}}
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">

                            <div class="flex items-center gap-4 min-w-0">

                                <div class="w-14 h-14 shrink-0 rounded-2xl bg-white/15 backdrop-blur-sm border border-white/25 text-white flex items-center justify-center shadow-lg text-2xl icon-pop">
                                    <i class="fa-solid fa-folder-open"></i>
                                </div>

                                <div class="min-w-0">
                                    <h1 class="font-display text-2xl md:text-3xl font-bold text-white tracking-tight truncate">
                                        {{ $project->project_name }}
                                    </h1>
                                    <p class="text-sm text-blue-100 mt-1">
                                        Kelola detail proyek dan penawaran freelancer.
                                    </p>
                                </div>

                            </div>

                            {{-- TOMBOL KEMBALI --}}
                            <a
                                href="{{ route('company.projects.index') }}"
                                class="inline-flex items-center justify-center gap-2
                                       px-4 py-2.5
                                       bg-white
                                       rounded-xl
                                       text-sm font-semibold
                                       text-blue-700
                                       shadow-sm
                                       hover:bg-blue-50
                                       hover:-translate-y-0.5
                                       transition-all duration-200
                                       w-fit shrink-0"
                            >
                                <i class="fa-solid fa-arrow-left text-xs"></i>
                                Kembali ke Proyek
                            </a>

                        </div>

                        {{-- RINGKASAN CEPAT (mengisi ruang hero, tetap ambil data yang sama) --}}
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-6">

                            @php
                                $status = $project->status ?? 'Draft';
                            @endphp

                            <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl px-4 py-3">
                                <p class="text-[11px] text-blue-100 uppercase tracking-wider">Status</p>
                                <p class="text-sm font-bold text-white mt-1 flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-white status-dot-live"></span>
                                    {{ $status }}
                                </p>
                            </div>

                            <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl px-4 py-3">
                                <p class="text-[11px] text-blue-100 uppercase tracking-wider">Budget</p>
                                <p class="text-sm font-bold text-white mt-1">
                                    @if($project->budget)
                                        Rp {{ number_format($project->budget, 0, ',', '.') }}
                                    @else
                                        Belum ditentukan
                                    @endif
                                </p>
                            </div>

                            <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl px-4 py-3">
                                <p class="text-[11px] text-blue-100 uppercase tracking-wider">Deadline</p>
                                <p class="text-sm font-bold text-white mt-1">
                                    @if($project->deadline)
                                        {{ \Carbon\Carbon::parse($project->deadline)->format('d M Y') }}
                                    @else
                                        Belum ditentukan
                                    @endif
                                </p>
                            </div>

                            <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl px-4 py-3">
                                <p class="text-[11px] text-blue-100 uppercase tracking-wider">Penawaran</p>
                                <p class="text-sm font-bold text-white mt-1">
                                    {{ $project->penawarans->count() }} Freelancer
                                </p>
                            </div>

                        </div>

                    </div>

                </div>


                {{-- =========================================
                    FLASH MESSAGE
                ========================================== --}}
                @if(session('success'))

                    <div class="mb-6 flex items-center gap-3
                                px-4 py-3
                                bg-gradient-to-r from-emerald-50 to-teal-50
                                border border-emerald-200
                                text-emerald-700
                                rounded-xl
                                text-sm font-medium
                                shadow-sm
                                animate-pop">

                        <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-check text-emerald-600"></i>
                        </div>

                        <span>{{ session('success') }}</span>

                    </div>

                @endif


                @if(session('error'))

                    <div class="mb-6 flex items-center gap-3
                                px-4 py-3
                                bg-gradient-to-r from-red-50 to-rose-50
                                border border-red-200
                                text-red-700
                                rounded-xl
                                text-sm font-medium
                                shadow-sm
                                animate-pop">

                        <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-xmark text-red-600"></i>
                        </div>

                        <span>{{ session('error') }}</span>

                    </div>

                @endif



                {{-- =================================================
                    INFORMASI PROYEK
                ================================================== --}}
                <div class="bg-white border border-blue-100 rounded-2xl overflow-hidden shadow-lg shadow-blue-500/5 mb-6 animate-slide delay-1">


                    {{-- HEADER CARD --}}
                    <div class="relative overflow-hidden px-6 py-5 border-b border-blue-100
                                bg-gradient-to-r from-blue-600 to-sky-500
                                flex flex-col md:flex-row
                                md:items-center
                                md:justify-between
                                gap-4">

                        <div class="absolute inset-0 dot-grid opacity-10"></div>

                        <div class="relative z-10 flex items-center gap-4">

                            <div class="w-12 h-12 rounded-xl
                                        bg-white/20
                                        backdrop-blur-sm
                                        border border-white/25
                                        text-white
                                        flex items-center justify-center
                                        text-xl
                                        icon-pop">
                                <i class="fa-solid fa-folder-open"></i>
                            </div>

                            <div>
                                <h2 class="font-bold text-lg text-white">
                                    Informasi Proyek
                                </h2>
                                <p class="text-xs text-blue-100 mt-1">
                                    Detail informasi proyek yang Anda buat
                                </p>
                            </div>

                        </div>


                        {{-- STATUS --}}
                        @php
                            $status = $project->status ?? 'Draft';
                        @endphp

                        @if($status === 'Open')

                            <span class="relative z-10 inline-flex items-center gap-2
                                         px-3 py-1.5
                                         rounded-full
                                         bg-white
                                         text-emerald-600
                                         text-xs font-bold
                                         shadow-sm w-fit">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 status-dot-live-amber"></span>
                                Open
                            </span>

                        @elseif($status === 'Closed')

                            <span class="relative z-10 inline-flex items-center gap-2
                                         px-3 py-1.5
                                         rounded-full
                                         bg-white
                                         text-red-600
                                         text-xs font-bold
                                         shadow-sm w-fit">
                                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                Closed
                            </span>

                        @else

                            <span class="relative z-10 inline-flex items-center gap-2
                                         px-3 py-1.5
                                         rounded-full
                                         bg-white
                                         text-slate-600
                                         text-xs font-bold
                                         shadow-sm w-fit">
                                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                {{ $status }}
                            </span>

                        @endif

                    </div>



                    {{-- ISI INFORMASI --}}
                    <div class="p-6">


                        {{-- DESKRIPSI --}}
                        <div class="mb-6">

                            <p class="text-xs font-bold uppercase tracking-wider text-blue-500 mb-3 flex items-center gap-2">
                                <i class="fa-solid fa-align-left"></i>
                                Deskripsi Proyek
                            </p>

                            <div class="relative bg-blue-50/60
                                        border border-blue-100
                                        border-l-4 border-l-blue-500
                                        rounded-xl
                                        p-5">

                                @if($project->project_description)

                                    <p class="text-sm text-slate-600 leading-relaxed whitespace-pre-line">
                                        {{ $project->project_description }}
                                    </p>

                                @else

                                    <p class="text-sm text-slate-400 italic">
                                        Tidak ada deskripsi proyek.
                                    </p>

                                @endif

                            </div>

                        </div>



                        {{-- INFORMASI TAMBAHAN (kartu statistik, tema biru) --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">


                            {{-- BUDGET --}}
                            <div class="relative overflow-hidden rounded-xl p-5
                                        bg-gradient-to-br from-blue-600 to-blue-500
                                        text-white
                                        shadow-lg shadow-blue-500/30
                                        lift-card-strong">

                                <i class="fa-solid fa-sack-dollar stat-watermark"></i>

                                <div class="relative z-10 flex items-center gap-3">

                                    <div class="w-10 h-10 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                        <i class="fa-solid fa-wallet"></i>
                                    </div>

                                    <div>
                                        <p class="text-xs text-blue-100">Budget</p>
                                        <p class="text-base font-bold mt-1">

                                            @if($project->budget)
                                                Rp {{ number_format($project->budget, 0, ',', '.') }}
                                            @else
                                                Belum ditentukan
                                            @endif

                                        </p>
                                    </div>

                                </div>

                            </div>



                            {{-- DEADLINE --}}
                            <div class="relative overflow-hidden rounded-xl p-5
                                        bg-gradient-to-br from-sky-600 to-blue-500
                                        text-white
                                        shadow-lg shadow-sky-500/30
                                        lift-card-strong">

                                <i class="fa-regular fa-calendar stat-watermark"></i>

                                <div class="relative z-10 flex items-center gap-3">

                                    <div class="w-10 h-10 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                        <i class="fa-regular fa-calendar"></i>
                                    </div>

                                    <div>
                                        <p class="text-xs text-blue-50">Deadline</p>
                                        <p class="text-base font-bold mt-1">

                                            @if($project->deadline)
                                                {{ \Carbon\Carbon::parse($project->deadline)->format('d M Y') }}
                                            @else
                                                Belum ditentukan
                                            @endif

                                        </p>
                                    </div>

                                </div>

                            </div>



                            {{-- JUMLAH PENAWARAN --}}
                            <div class="relative overflow-hidden rounded-xl p-5
                                        bg-gradient-to-br from-indigo-600 to-blue-600
                                        text-white
                                        shadow-lg shadow-indigo-500/30
                                        lift-card-strong
                                        sm:col-span-2 lg:col-span-1">

                                <i class="fa-solid fa-users stat-watermark"></i>

                                <div class="relative z-10 flex items-center gap-3">

                                    <div class="w-10 h-10 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                        <i class="fa-solid fa-users"></i>
                                    </div>

                                    <div>
                                        <p class="text-xs text-indigo-100">Penawaran Masuk</p>
                                        <p class="text-base font-bold mt-1">
                                            {{ $project->penawarans->count() }} Freelancer
                                        </p>
                                    </div>

                                </div>

                            </div>

                        </div>



{{-- ACTION BUTTON --}}
                        <div class="flex flex-wrap gap-3 pt-5 border-t border-blue-100">

                            @php
                                $isCompleted = $project->isCompleted();
                                $hasWorkspace = $project->workspace()->exists();
                            @endphp

                            @if(!$isCompleted)
                                <a
                                    href="{{ route('company.projects.edit', $project) }}"
                                    class="btn-shine
                                           inline-flex items-center gap-2
                                           px-4 py-2.5
                                           bg-gradient-to-r from-blue-600 to-sky-500
                                           text-white
                                           rounded-lg
                                           text-sm font-semibold
                                           shadow-md shadow-blue-500/30
                                           hover:-translate-y-0.5
                                           transition-all duration-200"
                                >
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    Edit Proyek
                                </a>
                            @endif

{{-- Tutup Proyek: hanya jika Open, belum punya workspace, dan belum selesai --}}
                            @if($project->status === 'Open' && !$hasWorkspace && !$isCompleted)
                                <form
                                    method="POST"
                                    action="{{ route('company.projects.close', $project) }}"
                                    onsubmit="return confirm('Tutup proyek ini? Proyek tidak lagi menerima penawaran baru. Proyek tidak otomatis dianggap selesai.');"
                                >
                                    @csrf
                                    <button
                                        type="submit"
                                        class="inline-flex items-center gap-2
                                               px-4 py-2.5
                                               bg-amber-50
                                               text-amber-700
                                               border border-amber-200
                                               rounded-lg
                                               text-sm font-semibold
                                               hover:bg-amber-100
                                               hover:-translate-y-0.5
                                               transition-all duration-200"
                                    >
                                        <i class="fa-solid fa-lock"></i>
                                        Tutup Proyek
                                    </button>
                                </form>
                            @endif

                            {{-- ARSIP / NONAKTIF / AKTIFKAN --}}
                            @if($project->isArchived() || $project->isInactive())
                                {{-- Project yang sudah selesai hanya bisa dilihat di arsip, tidak bisa diaktifkan kembali --}}
                                @if(!$isCompleted)
                                    <form
                                        method="POST"
                                        action="{{ route('company.projects.activate', $project) }}"
                                        onsubmit="return confirm('Aktifkan kembali proyek ini? Project akan tampil kembali di daftar proyek aktif.');"
                                    >
                                        @csrf
                                        <button
                                            type="submit"
                                            class="inline-flex items-center gap-2
                                                   px-4 py-2.5
                                                   bg-emerald-50
                                                   text-emerald-700
                                                   border border-emerald-200
                                                   rounded-lg
                                                   text-sm font-semibold
                                                   hover:bg-emerald-100
                                                   hover:-translate-y-0.5
                                                   transition-all duration-200"
                                        >
                                            <i class="fa-solid fa-rotate-left"></i>
                                            Aktifkan Kembali
                                        </button>
                                    </form>
                                @endif
                            @else
                                <form
                                    method="POST"
                                    action="{{ route('company.projects.archive', $project) }}"
                                    onsubmit="return confirm('Arsipkan proyek ini? Proyek akan dipindahkan ke halaman arsip dan tidak lagi menerima penawaran baru.');"
                                >
                                    @csrf
                                    <button
                                        type="submit"
                                        class="inline-flex items-center gap-2
                                               px-4 py-2.5
                                               bg-indigo-50
                                               text-indigo-700
                                               border border-indigo-200
                                               rounded-lg
                                               text-sm font-semibold
                                               hover:bg-indigo-100
                                               hover:-translate-y-0.5
                                               transition-all duration-200"
                                    >
                                        <i class="fa-solid fa-box-archive"></i>
                                        Arsipkan Proyek
                                    </button>
                                </form>
                            @endif

                            {{-- Hapus Proyek: hanya jika belum ada penawaran & belum ada workspace --}}
                            @if(!$hasWorkspace && $project->penawarans()->count() === 0)
                                <form
                                    method="POST"
                                    action="{{ route('company.projects.destroy', $project) }}"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus proyek ini?');"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="inline-flex items-center gap-2
                                               px-4 py-2.5
                                               bg-red-50
                                               text-red-600
                                               border border-red-100
                                               rounded-lg
                                               text-sm font-semibold
                                               hover:bg-red-100
                                               hover:-translate-y-0.5
                                               transition-all duration-200"
                                    >
                                        <i class="fa-solid fa-trash"></i>
                                        Hapus Proyek
                                    </button>
                                </form>
                            @endif

                        </div>

                    </div>

                </div>



                {{-- =================================================
                        PENAWARAN FREELANCER
                    ================================================== --}}
                <div class="bg-white border border-blue-100 rounded-2xl overflow-hidden shadow-lg shadow-blue-500/5 animate-slide delay-2">

                    {{-- HEADER --}}
                    <div class="relative overflow-hidden px-6 py-5 border-b border-blue-100 bg-gradient-to-r from-blue-700 to-indigo-700">
                        <div class="absolute inset-0 dot-grid opacity-10"></div>
                        <div class="relative z-10 flex flex-col md:flex-row
                                    md:items-center
                                    md:justify-between
                                    gap-4">
                            <div>
                                <h2 class="text-lg font-bold text-white">
                                    Penawaran Freelancer
                                </h2>
                                <p class="text-xs text-blue-100 mt-1">
                                    Lihat dan pilih freelancer yang mengajukan penawaran untuk proyek ini.
                                </p>
                            </div>

                            <div class="inline-flex items-center gap-2
                                        px-3 py-2
                                        bg-white/15
                                        backdrop-blur-sm
                                        text-white
                                        rounded-lg
                                        text-xs font-semibold
                                        w-fit">
                                <i class="fa-solid fa-users"></i>
                                {{ $project->penawarans->count() }} Penawaran
                            </div>
                        </div>
                    </div>

                    {{-- ISI PENAWARAN --}}
                    <div class="p-6">
                        @if ($project->penawarans->isEmpty())

                            {{-- EMPTY STATE --}}
                            <div class="relative overflow-hidden py-16 text-center rounded-2xl bg-gradient-to-br from-blue-50 via-sky-50 to-indigo-50">
                                <div class="w-16 h-16 mx-auto mb-5
                                            bg-white
                                            shadow-sm
                                            rounded-2xl
                                            flex items-center justify-center">
                                    <i class="fa-regular fa-file-lines text-2xl text-blue-600"></i>
                                </div>
                                <h3 class="text-base font-bold text-slate-700">
                                    Belum Ada Penawaran
                                </h3>
                                <p class="text-sm text-slate-400 mt-2">
                                    Penawaran dari freelancer akan muncul di sini.
                                </p>
                            </div>

                        @else

                            {{-- CEK APAKAH SUDAH ADA YANG DITERIMA --}}
                            @php
                                $hasAccepted = $project->penawarans->contains(
                                    fn($p) => $p->status === 'Diterima'
                                );
                            @endphp

                            <div class="space-y-4">

                                @foreach ($project->penawarans as $penawaran)
                                    @php
                                        $accentClass = match($penawaran->status) {
                                            'Diterima' => 'border-l-emerald-500',
                                            'Menunggu' => 'border-l-amber-400',
                                            default => 'border-l-red-400',
                                        };
                                    @endphp

                                    <div class="reveal-on-scroll reveal-fallback border border-blue-100 border-l-4 {{ $accentClass }}
                                                rounded-xl
                                                p-5
                                                bg-gradient-to-r from-white to-blue-50/50
                                                lift-card">

                                        {{-- BAGIAN ATAS --}}
                                        <div class="flex flex-col lg:flex-row
                                                    lg:items-start
                                                    lg:justify-between
                                                    gap-5">

                                            {{-- FREELANCER (DENGAN LINK KE PROFIL) --}}
                                            <div class="flex items-center gap-4">
                                                @php
                                                    // Mengambil foto dari relasi freelance_profile -> kolom photo
                                                    $fotoFreelancer = optional($penawaran->freelancer->freelanceProfile)->photo;
                                                @endphp

                                                @if($penawaran->freelancer && $fotoFreelancer)
                                                    <a href="{{ route('company.freelancer.profile', $penawaran->freelancer->id) }}" class="block shrink-0">
                                                        <img src="{{ Str::startsWith($fotoFreelancer, ['http://', 'https://']) ? $fotoFreelancer : asset('storage/' . $fotoFreelancer) }}" 
                                                            alt="{{ $penawaran->freelancer->name }}" 
                                                            class="w-12 h-12 rounded-full object-cover ring-2 ring-offset-2 ring-transparent hover:ring-blue-400 hover:opacity-90 transition-all duration-200">
                                                    </a>
                                                @else
                                                    <a href="{{ $penawaran->freelancer ? route('company.freelancer.profile', $penawaran->freelancer->id) : '#' }}" 
                                                    class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-600 to-sky-500 text-white flex items-center justify-center font-bold shrink-0 hover:from-blue-700 hover:to-sky-600 transition-all duration-200 shadow-sm">
                                                        {{ strtoupper(substr($penawaran->freelancer->name ?? 'F', 0, 1)) }}
                                                    </a>
                                                @endif

                                                <div>
                                                    <h3 class="font-bold text-slate-800">
                                                        @if($penawaran->freelancer)
                                                            <a href="{{ route('company.freelancer.profile', $penawaran->freelancer->id) }}" class="hover:text-blue-700 transition-colors duration-200">
                                                                {{ $penawaran->freelancer->name }}
                                                            </a>
                                                        @else
                                                            Tidak diketahui
                                                        @endif
                                                    </h3>
                                                    <p class="text-xs text-slate-400 mt-1">
                                                        Freelancer
                                                    </p>
                                                </div>
                                            </div>

                                            {{-- STATUS --}}
                                            <div>
                                                @if ($penawaran->status === 'Menunggu')
                                                    <span class="inline-flex items-center gap-2
                                                                 px-3 py-1.5
                                                                 rounded-full
                                                                 bg-amber-50
                                                                 text-amber-600
                                                                 border border-amber-100
                                                                 text-xs font-bold">
                                                        <span class="w-2 h-2 rounded-full bg-amber-500 status-dot-live-amber"></span>
                                                        Menunggu
                                                    </span>
                                                @elseif ($penawaran->status === 'Diterima')
                                                    <span class="inline-flex items-center gap-2
                                                                 px-3 py-1.5
                                                                 rounded-full
                                                                 bg-emerald-50
                                                                 text-emerald-600
                                                                 border border-emerald-100
                                                                 text-xs font-bold">
                                                        <i class="fa-solid fa-check"></i>
                                                        Freelancer Terpilih
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-2
                                                                 px-3 py-1.5
                                                                 rounded-full
                                                                 bg-red-50
                                                                 text-red-600
                                                                 border border-red-100
                                                                 text-xs font-bold">
                                                        <i class="fa-solid fa-xmark"></i>
                                                        Ditolak
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- DETAIL PENAWARAN --}}
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-5">
                                            {{-- HARGA --}}
                                            <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 hover:bg-blue-100/70 transition-colors duration-200">
                                                <p class="text-xs text-blue-600 font-semibold">
                                                    Harga Penawaran
                                                </p>
                                                <p class="text-base font-bold text-slate-800 mt-1">
                                                    Rp {{ number_format($penawaran->harga_penawaran, 0, ',', '.') }}
                                                </p>
                                            </div>

                                            {{-- ESTIMASI --}}
                                            <div class="bg-sky-50 border border-sky-100 rounded-lg p-4 hover:bg-sky-100/70 transition-colors duration-200">
                                                <p class="text-xs text-sky-600 font-semibold">
                                                    Estimasi Pengerjaan
                                                </p>
                                                <p class="text-base font-bold text-slate-800 mt-1">
                                                    {{ $penawaran->estimasi_hari }} Hari
                                                </p>
                                            </div>

                                            {{-- WAKTU --}}
                                            <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-4 hover:bg-indigo-100/70 transition-colors duration-200">
                                                <p class="text-xs text-indigo-600 font-semibold">
                                                    Waktu Dipilih
                                                </p>
                                                <p class="text-sm font-bold text-slate-800 mt-1">
                                                    @if ($penawaran->selected_at)
                                                        {{ $penawaran->selected_at->format('d M Y H:i') }}
                                                    @else
                                                        Belum dipilih
                                                    @endif
                                                </p>
                                            </div>
                                        </div>

                                        {{-- PESAN --}}
                                        @if($penawaran->pesan)
                                            <div class="mt-4">
                                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">
                                                    Pesan Freelancer
                                                </p>
                                                <div class="bg-[#f6f9ff]
                                                            border border-blue-50
                                                            border-l-4 border-l-blue-300
                                                            rounded-lg
                                                            p-4">
                                                    <p class="text-sm text-slate-600 leading-relaxed">
                                                        {{ $penawaran->pesan }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- BAGIAN BAWAH --}}
                                        <div class="flex flex-col sm:flex-row
                                                    sm:items-center
                                                    sm:justify-between
                                                    gap-4
                                                    mt-5
                                                    pt-5
                                                    border-t border-blue-100">
                                            {{-- PROPOSAL --}}
                                            <div>
                                                @if ($penawaran->proposal)
                                                    <a
                                                        href="{{ asset('storage/' . $penawaran->proposal) }}"
                                                        target="_blank"
                                                        class="inline-flex items-center gap-2
                                                               px-3 py-2
                                                               border border-blue-200
                                                               text-blue-600
                                                               bg-blue-50
                                                               rounded-lg
                                                               text-xs font-semibold
                                                               hover:bg-blue-100
                                                               hover:-translate-y-0.5
                                                               transition-all duration-200"
                                                    >
                                                        <i class="fa-regular fa-file-lines"></i>
                                                        Lihat Proposal
                                                    </a>
                                                @else
                                                    <span class="text-xs text-slate-400">
                                                        Tidak ada file proposal
                                                    </span>
                                                @endif
                                            </div>

{{-- ACTION --}}
                                            <div>
                                                @if ($penawaran->status === 'Menunggu' && !$hasAccepted && $project->acceptsOffers())
                                                    <form
                                                        method="POST"
                                                        action="{{ route('company.projects.penawaran.select', [$project, $penawaran]) }}"
                                                        onsubmit="return confirm('Pilih freelancer ini? Penawaran lain akan otomatis ditolak.');"
                                                    >
                                                        @csrf
                                                        <button
                                                            type="submit"
                                                            class="btn-shine
                                                                   inline-flex items-center gap-2
                                                                   px-4 py-2.5
                                                                   bg-gradient-to-r from-emerald-500 to-teal-500
                                                                   text-white
                                                                   rounded-lg
                                                                   text-xs font-bold
                                                                   shadow-sm
                                                                   hover:-translate-y-0.5
                                                                   transition-all duration-200"
                                                        >
                                                            <i class="fa-solid fa-check"></i>
                                                            Pilih Freelancer
                                                        </button>
                                                    </form>
                                                @elseif ($penawaran->status === 'Diterima')
                                                    <div class="flex items-center gap-2">
                                                        <span class="inline-flex items-center gap-2
                                                                     px-4 py-2.5
                                                                     bg-emerald-50
                                                                     text-emerald-600
                                                                     rounded-lg
                                                                     text-xs font-bold">
                                                            <i class="fa-solid fa-circle-check"></i>
                                                            Freelancer Terpilih
                                                        </span>

                                                        @if($project->workspace)
                                                            <a href="{{ route('company.workspaces.show', $project->workspace) }}"
                                                               class="btn-shine inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-sky-500 text-white rounded-lg text-xs font-bold shadow-sm hover:-translate-y-0.5 transition-all duration-200">
                                                                <i class="fa-solid fa-external-link-alt"></i>
                                                                Buka Workspace
                                                            </a>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-xs text-slate-400">
                                                        Penawaran tidak tersedia
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                @endforeach

                            </div>

                        @endif
                    </div>
                </div>
            </div>
        </main>

        {{-- =================================================
            FOOTER
        ================================================== --}}
        @include('navbar.footer')


    </div>

    {{-- =====================================================
        SCRIPT ANIMASI (reveal-on-scroll)
        Murni presentasional, tidak mengubah logika halaman.
        Kartu tetap tampil (via .reveal-fallback) walau JS gagal jalan.
    ====================================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var items = document.querySelectorAll('.reveal-on-scroll');
            if (!items.length) return;

            if (!('IntersectionObserver' in window)) {
                items.forEach(function (el) { el.classList.add('revealed'); });
                return;
            }

            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('revealed');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

            items.forEach(function (el, index) {
                el.style.transitionDelay = Math.min(index * 60, 300) + 'ms';
                observer.observe(el);
            });
        });
    </script>

</body>

</html>

reports -> <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Perusahaan - ApexForge Labs</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif']
                    },
                    colors: {
                        brand: {
                            DEFAULT: '#2563EB',
                            dark: '#1D4ED8',
                            light: '#EFF6FF',
                        },
                        surface: '#F8FAFC'
                    }
                }
            }
        }
    </script>

    <style>
        /* ---- Entrance animations ---- */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .reveal { opacity: 0; animation: fadeInUp .7s cubic-bezier(.16,1,.3,1) forwards; }
        .reveal-1 { animation-delay: .05s; }
        .reveal-2 { animation-delay: .1s; }
        .reveal-3 { animation-delay: .15s; }
        .reveal-4 { animation-delay: .2s; }
        .reveal-5 { animation-delay: .25s; }
        .reveal-6 { animation-delay: .3s; }
        .reveal-7 { animation-delay: .35s; }

        /* ---- Modern Mesh / Gradient Animation ---- */
        @keyframes meshGradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .animate-mesh {
            background-size: 200% 200%;
            animation: meshGradient 12s ease infinite;
        }

        /* ---- Floating Ambient Blobs ---- */
        @keyframes floatBlob {
            0%, 100% { transform: translate(0px, 0px) scale(1); }
            50% { transform: translate(15px, -20px) scale(1.05); }
        }
        .blob { animation: floatBlob 9s ease-in-out infinite; }

        /* ---- Professional Stat Card Effects ---- */
        .stat-card {
            transition: all .4s cubic-bezier(.16,1,.3,1);
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.08);
            border-color: rgba(37, 99, 235, 0.2);
        }
        .stat-icon {
            transition: transform .4s cubic-bezier(.34,1.56,.64,1);
        }
        .stat-card:hover .stat-icon {
            transform: scale(1.1) rotate(-5deg);
        }

        /* ---- Shimmer Button Effect ---- */
        .btn-shimmer {
            position: relative;
            overflow: hidden;
            isolation: isolate;
        }
        .btn-shimmer::after {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 60%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.3), transparent);
            transform: skewX(-20deg);
            transition: left .7s ease;
        }
        .btn-shimmer:hover::after {
            left: 150%;
        }

        /* ---- Interactive Rows ---- */
        .modern-row {
            transition: all .3s cubic-bezier(.16,1,.3,1);
        }
        .modern-row:hover {
            transform: translateX(4px);
            background-color: #ffffff;
            box-shadow: 0 10px 30px -10px rgba(37, 99, 235, 0.08);
            border-color: rgba(37, 99, 235, 0.25);
        }

        /* ---- Section Accent Bar ---- */
        .section-accent {
            width: 4px;
            background: linear-gradient(180deg, #2563EB, #06B6D4);
            border-radius: 9999px;
        }

        /* Scrollbar Halus */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>

<body class="bg-surface text-slate-800 min-h-screen flex font-sans antialiased selection:bg-brand selection:text-white">

    {{-- SIDEBAR --}}
    @include('navbar.navigasi')

    {{-- AREA KANAN (MAIN CONTENT) - Menggunakan w-full agar melebar penuh --}}
    <div class="flex-1 flex flex-col min-h-screen w-full overflow-hidden">

        @include('navbar.nav')

        {{-- KONTEN UTAMA - max-w-none & w-full agar mengisi seluruh ruang kosong kanan --}}
        <main class="flex-1 w-full overflow-y-auto p-4 sm:p-6 lg:p-8">

            <div class="w-full mx-auto space-y-6">

                {{-- NOTIFIKASI SESSION SUCCESS --}}
                @if(session('success'))
                    <div class="reveal reveal-1 flex items-center gap-3 px-5 py-4 bg-emerald-50/80 backdrop-blur-md border border-emerald-200/60 text-emerald-800 text-sm font-medium rounded-2xl shadow-sm">
                        <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-sm shadow-emerald-500/30">
                            <i class="fa-solid fa-check text-xs"></i>
                        </div>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                {{-- WELCOME / HERO BANNER --}}
                <div class="reveal reveal-1 relative overflow-hidden rounded-3xl shadow-xl shadow-blue-600/10 border border-blue-500/20 w-full">
                    <div class="absolute inset-0 animate-mesh bg-gradient-to-r from-blue-700 via-brand to-blue-600"></div>
                    
                    {{-- Ambient Decorative Blobs --}}
                    <div class="blob absolute -top-20 -right-20 w-72 h-72 bg-white/10 rounded-full blur-2xl"></div>
                    <div class="blob absolute -bottom-24 -left-20 w-80 h-80 bg-blue-400/20 rounded-full blur-2xl" style="animation-delay: 2s;"></div>
                    
                    {{-- Subtle Dot Pattern Overlay --}}
                    <div class="absolute inset-0 opacity-[0.07]" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>

                    <div class="relative p-6 sm:p-8 lg:p-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                        <div class="space-y-3">
                            <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md px-3.5 py-1.5 rounded-full text-white text-xs font-semibold ring-1 ring-white/20 shadow-inner">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                Perusahaan Verified Dashboard
                            </div>
                            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight">
                                Selamat datang kembali, Rekan! 👋
                            </h1>
                            <p class="text-blue-100/90 text-sm md:text-base max-w-2xl font-medium leading-relaxed">
                                Pantau progres proyek secara real-time, kelola penawaran masuk, dan perluas bisnis Anda bersama freelancer profesional terbaik.
                            </p>
                        </div>

                        <a href="{{ route('company.projects.create') }}"
                           class="btn-shimmer inline-flex items-center justify-center gap-2.5 bg-white text-brand px-6 py-3.5 rounded-2xl text-sm font-bold shadow-lg shadow-black/5 hover:bg-[#f6f9ff] hover:scale-[1.02] active:scale-[0.98] transition-all shrink-0">
                            <i class="fa-solid fa-plus text-xs"></i>
                            <span>Buat Proyek Baru</span>
                        </a>
                    </div>
                </div>


                {{-- STATISTIK GRID - Menggunakan w-full & grid responsif tanpa batasan lebar tengah --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 w-full">

                    {{-- TOTAL PROYEK --}}
                    <div class="reveal reveal-2 stat-card bg-white border border-blue-100/80 rounded-2xl p-5 sm:p-6 shadow-sm relative overflow-hidden">
                        <div class="flex items-center justify-between">
                            <div class="space-y-1">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Proyek</p>
                                <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight" data-count="{{ $totalProjects }}">0</h3>
                            </div>
                            <div class="stat-icon w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-blue-50 text-brand flex items-center justify-center text-xl shadow-inner border border-blue-100/50">
                                <i class="fa-regular fa-folder-open"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center gap-1.5 text-xs font-semibold text-blue-600 bg-blue-50/60 w-fit px-2.5 py-1 rounded-lg">
                            <i class="fa-solid fa-chart-pie"></i> Keseluruhan Portofolio
                        </div>
                    </div>

                    {{-- PROYEK AKTIF --}}
                    <div class="reveal reveal-3 stat-card bg-white border border-blue-100/80 rounded-2xl p-5 sm:p-6 shadow-sm relative overflow-hidden">
                        <div class="flex items-center justify-between">
                            <div class="space-y-1">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Proyek Aktif</p>
                                <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight" data-count="{{ $activeProjects }}">0</h3>
                            </div>
                            <div class="stat-icon w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shadow-inner border border-emerald-100/50">
                                <i class="fa-solid fa-briefcase"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center gap-1.5 text-xs font-semibold text-emerald-600 bg-emerald-50/60 w-fit px-2.5 py-1 rounded-lg">
                            <i class="fa-solid fa-bolt"></i> Sedang Berjalan
                        </div>
                    </div>

                    {{-- FREELANCER BEKERJA --}}
                    <div class="reveal reveal-4 stat-card bg-white border border-blue-100/80 rounded-2xl p-5 sm:p-6 shadow-sm relative overflow-hidden">
                        <div class="flex items-center justify-between">
                            <div class="space-y-1">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Freelancer Aktif</p>
                                <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight" data-count="{{ $activeFreelancers }}">0</h3>
                            </div>
                            <div class="stat-icon w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shadow-inner border border-amber-100/50">
                                <i class="fa-solid fa-user-group"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center gap-1.5 text-xs font-semibold text-amber-600 bg-amber-50/60 w-fit px-2.5 py-1 rounded-lg">
                            <i class="fa-solid fa-handshake"></i> Mitra Kolaborasi
                        </div>
                    </div>

                    {{-- TOTAL PENGELUARAN --}}
                    <div class="reveal reveal-5 stat-card bg-white border border-blue-100/80 rounded-2xl p-5 sm:p-6 shadow-sm relative overflow-hidden">
                        <div class="flex items-center justify-between">
                            <div class="space-y-1">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Pengeluaran</p>
                                <h3 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight mt-1">Rp <span data-count="{{ $totalSpending }}">0</span></h3>
                            </div>
                            <div class="stat-icon w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl shadow-inner border border-rose-100/50">
                                <i class="fa-solid fa-wallet"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center gap-1.5 text-xs font-semibold text-rose-600 bg-rose-50/60 w-fit px-2.5 py-1 rounded-lg">
                            <i class="fa-solid fa-arrow-trend-up"></i> Investasi Proyek
                        </div>
                    </div>

                </div>


                {{-- GRID UTAMA: PROYEK & PROPOSAL (Memenuhi layar secara penuh) --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 w-full">

                    {{-- KARTU PROYEK ANDA --}}
                    <div class="reveal reveal-6 bg-white border border-blue-100/80 rounded-3xl p-5 sm:p-6 lg:p-7 shadow-sm flex flex-col justify-between w-full">
                        <div>
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-3">
                                    <span class="section-accent h-7"></span>
                                    <div>
                                        <h2 class="font-extrabold text-slate-900 text-lg">Proyek Anda</h2>
                                        <p class="text-xs text-slate-400 font-medium">Daftar proyek terbaru yang dipublikasikan</p>
                                    </div>
                                </div>
                                <a href="{{ route('company.projects.index') }}" class="text-xs text-brand font-bold hover:text-brand-dark flex items-center gap-1.5 group bg-brand/5 px-3 py-1.5 rounded-xl transition">
                                    <span>Lihat Semua</span> 
                                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                                </a>
                            </div>

                            @if($recentProjects->count() > 0)
                                <div class="space-y-3">
                                    @foreach($recentProjects as $project)
                                    <div class="modern-row flex items-center justify-between p-4 bg-[#f6f9ff]/70 rounded-2xl border border-blue-50">
                                        <div class="min-w-0 flex-1 pr-4">
                                            <h4 class="text-sm font-bold text-slate-800 truncate">
                                                <a href="{{ route('company.projects.show', $project) }}" class="hover:text-brand transition">
                                                    {{ $project->project_name }}
                                                </a>
                                            </h4>
                                            <div class="flex flex-wrap items-center gap-3 mt-1.5 text-xs font-medium text-slate-400">
                                                @if($project->budget)
                                                <span class="flex items-center gap-1"><i class="fa-regular fa-money-bill-1 text-slate-400"></i>Rp {{ number_format($project->budget, 0, ',', '.') }}</span>
                                                @endif
                                                @if($project->deadline)
                                                <span class="flex items-center gap-1"><i class="fa-regular fa-calendar text-slate-400"></i>{{ \Carbon\Carbon::parse($project->deadline)->format('d M Y') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <span class="inline-flex items-center gap-1.5 text-[11px] font-bold px-3 py-1.5 rounded-full shrink-0
                                            @if($project->status == 'Open') bg-emerald-50 text-emerald-600 border border-emerald-200/60
                                            @elseif($project->status == 'Closed') bg-rose-50 text-rose-600 border border-rose-200/60
                                            @else bg-blue-50 text-slate-600 border border-blue-100 @endif
                                        ">
                                            @if($project->status == 'Open')
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                                            @endif
                                            {{ $project->status ?? 'Draft' }}
                                        </span>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="py-12 text-center">
                                    <div class="w-14 h-14 mx-auto mb-3 bg-blue-50 text-slate-400 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                                        <i class="fa-regular fa-folder-open"></i>
                                    </div>
                                    <h3 class="text-sm font-bold text-slate-700">Belum ada proyek</h3>
                                    <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto">Mulai buat proyek pertama Anda dan temukan talenta terbaik.</p>
                                    <a href="{{ route('company.projects.create') }}" class="btn-shimmer inline-flex items-center gap-2 mt-4 px-4 py-2 bg-brand text-white rounded-xl text-xs font-bold shadow-md shadow-brand/20">
                                        <i class="fa-solid fa-plus text-[10px]"></i> Buat Proyek
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>


                    {{-- KARTU PROPOSAL MASUK --}}
                    <div class="reveal reveal-7 bg-white border border-blue-100/85 rounded-3xl p-5 sm:p-6 lg:p-7 shadow-sm flex flex-col justify-between w-full">
                        <div>
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-3">
                                    <span class="section-accent h-7"></span>
                                    <div>
                                        <h2 class="font-extrabold text-slate-900 text-lg">Proposal Masuk</h2>
                                        <p class="text-xs text-slate-400 font-medium">Tawaran & lamaran dari freelancer</p>
                                    </div>
                                </div>
                                <a href="{{ route('company.projects.index') }}" class="text-xs text-brand font-bold hover:text-brand-dark flex items-center gap-1.5 group bg-brand/5 px-3 py-1.5 rounded-xl transition">
                                    <span>Kelola</span> 
                                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                                </a>
                            </div>

                            @if($incomingProposals->count() > 0)
                                <div class="space-y-3">
                                    @foreach($incomingProposals as $proposal)
                                    <div class="modern-row flex items-center justify-between p-4 bg-[#f6f9ff]/70 rounded-2xl border border-blue-50">
                                        <div class="min-w-0 flex-1 pr-4">
                                            <h4 class="text-sm font-bold text-slate-800 truncate">
                                                {{ $proposal->freelancer->name ?? 'Freelancer' }}
                                            </h4>
                                            <div class="flex flex-wrap items-center gap-3 mt-1.5 text-xs font-medium text-slate-400">
                                                <span class="truncate max-w-[140px]"><i class="fa-regular fa-file-lines mr-1"></i>{{ $proposal->project->project_name ?? '-' }}</span>
                                                <span class="text-slate-600 font-semibold"><i class="fa-regular fa-money-bill-1 mr-1 text-slate-400"></i>Rp {{ number_format($proposal->harga_penawaran, 0, ',', '.') }}</span>
                                                <span><i class="fa-regular fa-clock mr-1 text-slate-400"></i>{{ $proposal->estimasi_hari }} hari</span>
                                            </div>
                                        </div>
                                        <span class="inline-flex items-center gap-1.5 text-[11px] font-bold px-3 py-1.5 rounded-full shrink-0
                                            @if($proposal->status == 'Menunggu') bg-amber-50 text-amber-700 border border-amber-200/60
                                            @elseif($proposal->status == 'Diterima') bg-emerald-50 text-emerald-600 border border-emerald-200/60
                                            @else bg-rose-50 text-rose-600 border border-rose-200/60 @endif
                                        ">
                                            {{ $proposal->status }}
                                        </span>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="py-12 text-center">
                                    <div class="w-14 h-14 mx-auto mb-3 bg-blue-50 text-slate-400 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                                        <i class="fa-regular fa-envelope"></i>
                                    </div>
                                    <h3 class="text-sm font-bold text-slate-700">Belum ada proposal</h3>
                                    <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto">Penawaran dan lamaran dari freelancer ahli akan muncul secara instan di sini.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

            </div>

        </main>

        {{-- FOOTER --}}
        @include('navbar.footer')

    </div>

    <script>
        // Smooth count-up animation script for stats
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-count]').forEach(function (el) {
                const target = parseInt(el.getAttribute('data-count'), 10) || 0;
                const duration = 1200; // Durasi animasi dalam milidetik
                const start = performance.now();
                
                function step(now) {
                    const progress = Math.min((now - start) / duration, 1);
                    // Easing cubic out for professional feel
                    const eased = 1 - Math.pow(1 - progress, 3);
                    
                    const currentVal = Math.round(target * eased);
                    el.textContent = currentVal.toLocaleString('id-ID');
                    
                    if (progress < 1) {
                        requestAnimationFrame(step);
                    }
                }
                requestAnimationFrame(step);
            });
        });
    </script>

</body>
</html>
