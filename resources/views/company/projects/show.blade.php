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
                                        bg-gradient-to-br from-sky-600 to-cyan-500
                                        text-white
                                        shadow-lg shadow-sky-500/30
                                        lift-card-strong">

                                <i class="fa-regular fa-calendar stat-watermark"></i>

                                <div class="relative z-10 flex items-center gap-3">

                                    <div class="w-10 h-10 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                        <i class="fa-regular fa-calendar"></i>
                                    </div>

                                    <div>
                                        <p class="text-xs text-cyan-50">Deadline</p>
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
                                                <div class="bg-slate-50
                                                            border border-slate-100
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
                                                {{-- TOMBOL LAPOR PENAWARAN (terikat ke penawaran_id) --}}
                                                <a
                                                    href="{{ route('company.reports.create', ['penawaran_id' => $penawaran->id]) }}"
                                                    class="inline-flex items-center gap-2
                                                           px-3 py-2
                                                           border border-red-200
                                                           text-red-600
                                                           bg-red-50
                                                           rounded-lg
                                                           text-xs font-semibold
                                                           hover:bg-red-100
                                                           hover:-translate-y-0.5
                                                           transition-all duration-200"
                                                    title="Laporkan penawaran dari {{ $penawaran->freelancer->name ?? 'freelancer ini' }}"
                                                >
                                                    <i class="fa-solid fa-flag"></i>
                                                    Lapor
                                                </a>

                                                @if ($penawaran->status === 'Menunggu' && !$hasAccepted)
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