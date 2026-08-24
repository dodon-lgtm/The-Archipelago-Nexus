<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.theme-boot')
    <title>Arsip Proyek | ApexForge Labs</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
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

        /* =========================================
           ENTRANCE ANIMATION
        ========================================= */

        @keyframes fadeInUp {

            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }

        }

        .reveal {
            opacity: 0;
            animation:
                fadeInUp .7s cubic-bezier(.16,1,.3,1)
                forwards;
        }

        .reveal-1 {
            animation-delay: .05s;
        }

        .reveal-2 {
            animation-delay: .1s;
        }

        .reveal-3 {
            animation-delay: .15s;
        }


        /* =========================================
           MESH GRADIENT
        ========================================= */

        @keyframes meshGradient {

            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }

        }

        .animate-mesh {

            background-size: 200% 200%;

            animation:
                meshGradient 12s ease infinite;

        }


        /* =========================================
           FLOATING BLOB
        ========================================= */

        @keyframes floatBlob {

            0%,
            100% {

                transform:
                    translate(0px, 0px)
                    scale(1);

            }

            50% {

                transform:
                    translate(15px, -20px)
                    scale(1.05);

            }

        }

        .blob {

            animation:
                floatBlob 9s ease-in-out infinite;

        }


        /* =========================================
           INTERACTIVE ROW
        ========================================= */

        .modern-row {

            transition:
                all .3s
                cubic-bezier(.16,1,.3,1);

        }

        .modern-row:hover {

            transform:
                translateY(-2px);

            box-shadow:
                0 10px 30px -10px
                rgba(37, 99, 235, 0.08);

            border-color:
                rgba(37, 99, 235, 0.25);

        }

        html:not(.dark) .modern-row:hover {
            background-color: #ffffff;
        }

        html.dark .modern-row:hover {
            background-color: #0f172a;
        }


        /* =========================================
           SHIMMER BUTTON
        ========================================= */

        .btn-shimmer {

            position: relative;

            overflow: hidden;

            isolation: isolate;

        }

        .btn-shimmer::after {

            content: '';

            position: absolute;

            top: 0;

            left: -100%;

            width: 60%;

            height: 100%;

            background:
                linear-gradient(
                    90deg,
                    transparent,
                    rgba(255,255,255,.3),
                    transparent
                );

            transform:
                skewX(-20deg);

            transition:
                left .7s ease;

        }

        .btn-shimmer:hover::after {

            left: 150%;

        }


        /* =========================================
           SCROLLBAR
        ========================================= */

        ::-webkit-scrollbar {

            width: 6px;
            height: 6px;

        }

        ::-webkit-scrollbar-track {

            background:
                #f1f5f9;

        }

        html.dark ::-webkit-scrollbar-track {
            background: #0f172a;
        }

        ::-webkit-scrollbar-thumb {

            background:
                #cbd5e1;

            border-radius:
                9999px;

        }

        html.dark ::-webkit-scrollbar-thumb {
            background: #334155;
        }

        ::-webkit-scrollbar-thumb:hover {

            background:
                #94a3b8;

        }

        html.dark ::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }


        /* =========================================
           TOAST NOTIFICATION
        ========================================= */

        @keyframes toastIn {

            from {

                opacity: 0;

                transform:
                    translateY(-15px)
                    scale(.97);

            }

            to {

                opacity: 1;

                transform:
                    translateY(0)
                    scale(1);

            }

        }

        @keyframes toastOut {

            from {

                opacity: 1;

                transform:
                    translateY(0)
                    scale(1);

            }

            to {

                opacity: 0;

                transform:
                    translateY(-15px)
                    scale(.97);

            }

        }

        .toast-animation {

            animation:
                toastIn .35s
                cubic-bezier(.16,1,.3,1)
                forwards;

        }

        .toast-hide {

            animation:
                toastOut .3s
                ease forwards;

        }


        /* =========================================
           DELETE MODAL
        ========================================= */

        #deleteModal {

            transition:
                opacity .2s ease;

        }

        #deleteModal .modal-box {

            transform:
                scale(.95)
                translateY(10px);

            opacity: 0;

            transition:
                all .25s
                cubic-bezier(.16,1,.3,1);

        }

        #deleteModal.show .modal-box {

            transform:
                scale(1)
                translateY(0);

            opacity: 1;

        }

    </style>

</head>


<body
    class="bg-surface dark:bg-slate-950 text-slate-800 dark:text-slate-100 min-h-screen flex font-sans antialiased selection:bg-brand selection:text-white transition-colors duration-300">


    {{-- =====================================================
         SIDEBAR
    ====================================================== --}}

    @include('navbar.navigasi')


    {{-- =====================================================
         AREA KANAN
    ====================================================== --}}

    <div
        class="flex-1 flex flex-col min-h-screen w-full overflow-hidden">


        {{-- NAVBAR --}}

        @include('navbar.nav')


        {{-- =================================================
             MAIN CONTENT
        ================================================== --}}

        <main
            class="flex-1 w-full overflow-y-auto p-4 sm:p-6 lg:p-8">


            <div
                class="w-full mx-auto space-y-6">


                {{-- =================================================
                     HERO HEADER
                ================================================== --}}

                <div
                    class="reveal reveal-1 relative overflow-hidden rounded-3xl shadow-xl shadow-blue-600/10 border border-blue-500/20 w-full">


                    {{-- Gradient --}}

                    <div
                        class="absolute inset-0 animate-mesh bg-gradient-to-r from-blue-700 via-brand to-blue-600">
                    </div>


                    {{-- Decorative Blob --}}

                    <div
                        class="blob absolute -top-20 -right-20 w-72 h-72 bg-white/10 rounded-full blur-2xl">
                    </div>


                    <div
                        class="blob absolute -bottom-24 -left-20 w-80 h-80 bg-blue-400/20 rounded-full blur-2xl"
                        style="animation-delay: 2s;">
                    </div>


                    {{-- Dot Pattern --}}

                    <div
                        class="absolute inset-0 opacity-[0.07]"
                        style="
                            background-image:
                                radial-gradient(#fff 1px, transparent 1px);
                            background-size: 20px 20px;
                        ">
                    </div>


                    <div
                        class="relative p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">


                        <div class="space-y-2">


                            <div
                                class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md px-3.5 py-1.5 rounded-full text-white text-xs font-semibold ring-1 ring-white/20 shadow-inner">

                                <i
                                    class="fa-solid fa-box-archive text-xs text-blue-200">
                                </i>

                                Histori Pekerjaan

                            </div>


                            <h1
                                class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">

                                Arsip Proyek

                            </h1>


                            <p
                                class="text-blue-100/90 text-sm max-w-xl font-medium leading-relaxed">

                                Proyek yang diarsipkan atau dinonaktifkan —
                                histori pekerjaan tetap tersimpan rapi.

                            </p>


                        </div>


                        <div
                            class="flex flex-wrap items-center gap-3 shrink-0">

                            {{-- TOMBOL TOGGLE DARK MODE --}}
                            <button
                                type="button"
                                onclick="toggleDarkMode()"
                                class="inline-flex items-center justify-center w-11 h-11 bg-white/10 hover:bg-white/20 backdrop-blur-md rounded-2xl text-white transition ring-1 ring-white/20 shadow-lg"
                                title="Ganti Mode Tampilan">
                                <i class="fa-solid fa-moon text-sm dark:hidden"></i>
                                <i class="fa-solid fa-sun text-sm hidden dark:inline text-amber-300"></i>
                            </button>


                            <a
                                href="{{ route('company.projects.index') }}"
                                class="btn-shimmer inline-flex items-center gap-2 bg-white text-brand hover:bg-[#f6f9ff] px-5 py-3 rounded-2xl text-sm font-bold shadow-lg shadow-black/5 transition">


                                <i
                                    class="fa-solid fa-folder-open text-xs">
                                </i>


                                <span>
                                    Proyek Aktif
                                </span>


                            </a>


                        </div>


                    </div>

                </div>



                {{-- =================================================
                     SUCCESS NOTIFICATION
                ================================================== --}}

                @if (session('success'))

                    <div
                        id="successToast"
                        class="toast-animation flex items-center justify-between gap-3 px-5 py-4 bg-emerald-50/95 dark:bg-emerald-950/90 backdrop-blur-md border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-sm font-medium rounded-2xl shadow-lg">


                        <div
                            class="flex items-center gap-3 min-w-0">


                            <div
                                class="w-9 h-9 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-sm shadow-emerald-500/30">

                                <i
                                    class="fa-solid fa-check">
                                </i>

                            </div>


                            <div class="min-w-0">

                                <p
                                    class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wide">

                                    Berhasil

                                </p>

                                <span
                                    class="block truncate">

                                    {{ session('success') }}

                                </span>

                            </div>


                        </div>


                        <button
                            type="button"
                            onclick="closeToast('successToast')"
                            class="text-emerald-500 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-200 p-2 rounded-lg hover:bg-emerald-100 dark:hover:bg-emerald-900/50 transition">

                            <i
                                class="fa-solid fa-xmark">
                            </i>

                        </button>


                    </div>

                @endif



                {{-- =================================================
                     ERROR NOTIFICATION
                ================================================== --}}

                @if (session('error'))

                    <div
                        id="errorToast"
                        class="toast-animation flex items-center justify-between gap-3 px-5 py-4 bg-rose-50/95 dark:bg-rose-950/90 backdrop-blur-md border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 text-sm font-medium rounded-2xl shadow-lg">


                        <div
                            class="flex items-center gap-3 min-w-0">


                            <div
                                class="w-9 h-9 rounded-xl bg-rose-500 text-white flex items-center justify-center shrink-0 shadow-sm shadow-rose-500/30">

                                <i
                                    class="fa-solid fa-xmark">
                                </i>

                            </div>


                            <div class="min-w-0">

                                <p
                                    class="text-xs font-bold text-rose-600 dark:text-rose-400 uppercase tracking-wide">

                                    Terjadi Kesalahan

                                </p>

                                <span
                                    class="block truncate">

                                    {{ session('error') }}

                                </span>

                            </div>


                        </div>


                        <button
                            type="button"
                            onclick="closeToast('errorToast')"
                            class="text-rose-500 dark:text-rose-400 hover:text-rose-700 dark:hover:text-rose-200 p-2 rounded-lg hover:bg-rose-100 dark:hover:bg-rose-900/50 transition">

                            <i
                                class="fa-solid fa-xmark">
                            </i>

                        </button>


                    </div>

                @endif



                {{-- =================================================
                     SUB HEADER
                ================================================== --}}

                <div
                    class="reveal reveal-2 flex items-center justify-between">


                    <div>

                        <h2
                            class="text-lg font-extrabold text-slate-900 dark:text-white tracking-tight">

                            Daftar Arsip

                        </h2>


                        <p
                            class="text-xs text-slate-400 dark:text-slate-500 font-medium">

                            Koleksi proyek nonaktif dan yang telah selesai dilakukan

                        </p>

                    </div>


                </div>



                {{-- =================================================
                     PROJECT LIST
                ================================================== --}}

                <div
                    class="reveal reveal-3 space-y-3">


                    @forelse ($archivedProjects as $project)


                        @php
    $workspace = $project->workspace;
    $workStatus = $workspace?->status;
    $isCompleted = $project->isCompleted();
    $isInactive = $project->status === 'inactive';
@endphp


                        <div
                            class="modern-row block bg-white dark:bg-slate-900 border border-blue-100/80 dark:border-slate-800 rounded-2xl p-5 shadow-sm relative overflow-hidden group">


                            {{-- STATUS BAR --}}

                            <div
                                class="absolute left-0 top-0 bottom-0 w-1.5
                                {{ $isCompleted
                                    ? 'bg-emerald-500'
                                    : ($isInactive
                                        ? 'bg-amber-500'
                                        : 'bg-indigo-400') }}">
                            </div>



                            <div
                                class="flex flex-col md:flex-row md:items-center justify-between gap-4 pl-2">


                                {{-- PROJECT INFO --}}

                                <div
                                    class="flex items-start gap-4 min-w-0 flex-1">


                                    <div
                                        class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-slate-800 text-brand dark:text-blue-400 border border-blue-100 dark:border-slate-700 flex items-center justify-center shrink-0 text-lg shadow-inner group-hover:bg-brand group-hover:text-white dark:group-hover:bg-brand dark:group-hover:text-white transition-colors duration-300">

                                        <i
                                            class="fa-solid fa-box-archive">
                                        </i>

                                    </div>



                                    <div
                                        class="min-w-0 flex-1">


                                        <div
                                            class="flex flex-wrap items-center gap-2">


                                            <h3
                                                class="text-base font-bold text-slate-800 dark:text-slate-100 group-hover:text-brand dark:group-hover:text-blue-400 transition-colors truncate">

                                                {{ $project->project_name }}

                                            </h3>


                                            @if($project->category)

                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-blue-50 dark:bg-blue-950/50 border border-blue-100 dark:border-blue-900/50 text-brand dark:text-blue-300 text-[11px] font-bold">

                                                    {{ $project->category->name }}

                                                </span>

                                            @endif


                                        </div>



                                        @if($project->project_description)

                                            <p
                                                class="mt-1 text-xs text-slate-500 dark:text-slate-400 line-clamp-1 leading-relaxed">

                                                {{ $project->project_description }}

                                            </p>

                                        @endif



                                        <div
                                            class="mt-3 flex flex-wrap items-center gap-2 text-xs font-semibold">


                                            @if($project->budget)

                                                <span
                                                    class="inline-flex items-center gap-1.5 text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-100 dark:border-emerald-900/50 px-2.5 py-1 rounded-lg">

                                                    <i
                                                        class="fa-solid fa-wallet text-emerald-600 dark:text-emerald-400 text-[10px]">
                                                    </i>

                                                    Rp
                                                    {{ number_format($project->budget, 0, ',', '.') }}

                                                </span>

                                            @endif



                                            @if($project->deadline)

                                                <span
                                                    class="inline-flex items-center gap-1.5 text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/40 border border-amber-100 dark:border-amber-900/50 px-2.5 py-1 rounded-lg">

                                                    <i
                                                        class="fa-regular fa-calendar text-[10px]">
                                                    </i>

                                                    {{ \Carbon\Carbon::parse($project->deadline)->format('d M Y') }}

                                                </span>

                                            @endif



                                            @if($project->penawarans)

                                                <span
                                                    class="inline-flex items-center gap-1.5 text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/40 border border-blue-100 dark:border-blue-900/50 px-2.5 py-1 rounded-lg">

                                                    <i
                                                        class="fa-solid fa-handshake text-[10px]">
                                                    </i>

                                                    {{ $project->penawarans->count() }}
                                                    Penawaran

                                                </span>

                                            @endif


                                        </div>


                                    </div>


                                </div>



                                {{-- STATUS + ACTION --}}

                                <div
                                    class="flex flex-col sm:flex-row items-start sm:items-center justify-between md:justify-end gap-3 pt-3 md:pt-0 border-t md:border-t-0 border-slate-100 dark:border-slate-800">


                                    <div
                                        class="flex flex-wrap items-center gap-1.5">


                                        @if($workspace)

                                            @php

                                                $workBadge = match($workStatus) {

                                                    'Selesai'
                                                        => 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-900/50',

                                                    'Menunggu Review'
                                                        => 'bg-sky-50 dark:bg-sky-950/40 text-sky-700 dark:text-sky-400 border-sky-200 dark:border-sky-900/50',

                                                    'Menunggu Revisi'
                                                        => 'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-900/50',

                                                    'Menunggu Pembayaran'
                                                        => 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-400 border-indigo-200 dark:border-indigo-900/50',

                                                    'Menunggu Verifikasi Admin'
                                                        => 'bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-400 border-purple-200 dark:border-purple-900/50',

                                                    default
                                                        => 'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 border-blue-200 dark:border-blue-900/50',

                                                };

                                            @endphp


                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-xl border {{ $workBadge }}">

                                                <i
                                                    class="fa-solid
                                                    {{ $isCompleted
                                                        ? 'fa-circle-check'
                                                        : 'fa-circle-half-stroke' }}
                                                    text-[10px]">
                                                </i>

                                                {{ $workStatus }}

                                            </span>

                                        @endif



                                        @if($isInactive)

                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-xl border bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-900/50">

                                                <i
                                                    class="fa-solid fa-pause text-[10px]">
                                                </i>

                                                Nonaktif

                                            </span>

                                        @else

                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-xl border bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-400 border-indigo-200 dark:border-indigo-900/50">

                                                <i
                                                    class="fa-solid fa-box-archive text-[10px]">
                                                </i>

                                                Arsip

                                            </span>

                                        @endif


                                    </div>



                                    <div
                                        class="flex items-center gap-2 w-full sm:w-auto">


                                        {{-- DETAIL --}}

                                        <a
                                            href="{{ route('company.projects.show', $project) }}"
                                            class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold transition">

                                            <i
                                                class="fa-solid fa-eye text-[10px]">
                                            </i>

                                            Detail

                                        </a>



                                        {{-- AKTIFKAN --}}

                                        @if(!$isCompleted)

                                            <form
                                                method="POST"
                                                action="{{ route('company.projects.activate', $project) }}"
                                                class="inline">

                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 hover:bg-emerald-100 dark:hover:bg-emerald-900/60 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 text-xs font-bold transition">

                                                    <i
                                                        class="fa-solid fa-rotate-left text-[10px]">
                                                    </i>

                                                    Aktifkan

                                                </button>

                                            </form>

                                        @else

                                            <span
                                                class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-400 dark:text-slate-500 text-xs font-bold cursor-not-allowed">

                                                <i
                                                    class="fa-solid fa-lock text-[10px]">
                                                </i>

                                                Selesai

                                            </span>

                                        @endif


                                    </div>


                                </div>


                            </div>


                        </div>


                    @empty


                        {{-- EMPTY STATE --}}

                        <div
                            class="bg-white dark:bg-slate-900 border border-blue-100/80 dark:border-slate-800 rounded-3xl p-12 text-center shadow-sm">


                            <div
                                class="w-14 h-14 mx-auto mb-3 bg-blue-50 dark:bg-slate-800 text-slate-400 dark:text-slate-500 rounded-2xl flex items-center justify-center text-xl shadow-inner">

                                <i
                                    class="fa-solid fa-box-archive">
                                </i>

                            </div>


                            <h3
                                class="text-sm font-bold text-slate-700 dark:text-slate-200">

                                Belum ada proyek diarsipkan

                            </h3>


                            <p
                                class="text-xs text-slate-400 dark:text-slate-500 mt-1 max-w-xs mx-auto">

                                Proyek yang Anda nonaktifkan atau selesaikan akan tersimpan otomatis di sini.

                            </p>


                            <a
                                href="{{ route('company.projects.index') }}"
                                class="btn-shimmer inline-flex items-center gap-2 mt-4 px-4 py-2.5 bg-brand text-white rounded-xl text-xs font-bold shadow-md shadow-brand/20">

                                <i
                                    class="fa-solid fa-arrow-left text-[10px]">
                                </i>

                                Kembali ke Proyek Aktif

                            </a>


                        </div>


                    @endforelse


                </div>



                {{-- =================================================
                     PAGINATION
                ================================================== --}}

                @if ($archivedProjects->hasPages())

                    <div
                        class="pt-4 flex justify-center">

                        <div
                            class="bg-white dark:bg-slate-900 border border-blue-100/80 dark:border-slate-800 rounded-2xl shadow-sm px-4 py-2">

                            {{ $archivedProjects->links() }}

                        </div>

                    </div>

                @endif


            </div>


        </main>



        {{-- =================================================
             FOOTER
        ================================================== --}}

        @include('navbar.footer')


    </div>



    {{-- =====================================================
         JAVASCRIPT
    ====================================================== --}}

    <script>

        /*
        |--------------------------------------------------------------------------
        | TOGGLE DARK MODE
        |--------------------------------------------------------------------------
        */

        function toggleDarkMode() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }


        /*
        |--------------------------------------------------------------------------
        | CLOSE TOAST
        |--------------------------------------------------------------------------
        */

        function closeToast(id)
        {

            const toast =
                document.getElementById(id);

            if (!toast) {
                return;
            }

            toast.classList.add('toast-hide');

            setTimeout(function()
            {

                toast.remove();

            }, 300);

        }



        /*
        |--------------------------------------------------------------------------
        | AUTO CLOSE SUCCESS
        |--------------------------------------------------------------------------
        */

        document.addEventListener(
            'DOMContentLoaded',
            function()
            {

                const successToast =
                    document.getElementById('successToast');

                const errorToast =
                    document.getElementById('errorToast');


                if (successToast)
                {

                    setTimeout(function()
                    {

                        closeToast('successToast');

                    }, 4000);

                }


                if (errorToast)
                {

                    setTimeout(function()
                    {

                        closeToast('errorToast');

                    }, 5000);

                }

            }
        );

    </script>


</body>

</html>