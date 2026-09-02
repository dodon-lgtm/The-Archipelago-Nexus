<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.theme-boot')
    <title>Workspace - {{ $workspace->project->project_name }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = tailwind.config || {};
        tailwind.config.darkMode = 'class';
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* High-Tech Scrollbar (Blue/White Theme) */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(59, 130, 246, 0.2);
            border-radius: 9999px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(59, 130, 246, 0.5);
        }

        /* Animations */
        @keyframes fadeInBackdrop {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes modalPop {
            from {
                opacity: 0;
                transform: scale(.92) translateY(12px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .modal-backdrop {
            animation: fadeInBackdrop .25s ease-out forwards;
        }

        .modal-panel {
            animation: modalPop .35s cubic-bezier(.34, 1.56, .64, 1) forwards;
        }

        @keyframes iconPulseBlue {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.45);
            }

            50% {
                box-shadow: 0 0 0 9px rgba(255, 255, 255, 0);
            }
        }

        .icon-badge {
            animation: iconPulseBlue 2.4s ease-in-out infinite;
        }

        @keyframes starPop {
            0% {
                transform: scale(1) rotate(0);
            }

            45% {
                transform: scale(1.4) rotate(-10deg);
            }

            100% {
                transform: scale(1.05) rotate(0);
            }
        }

        .star-btn.pop {
            animation: starPop .38s ease;
        }

        @keyframes floatSlow {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-6px) rotate(8deg);
            }
        }

        .deco-star {
            position: absolute;
            color: rgba(255, 255, 255, 0.25);
            animation: floatSlow 3.5s ease-in-out infinite;
        }

        /* Pure Blue Styling */
        .modal-header-pattern {
            background-image: radial-gradient(rgba(255, 255, 255, .16) 1.5px, transparent 1.5px);
            background-size: 16px 16px;
        }

        .hologram-grid-blue {
            background-image:
                linear-gradient(to right, rgba(59, 130, 246, 0.04) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(59, 130, 246, 0.04) 1px, transparent 1px);
            background-size: 32px 32px;
        }

        .star-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.85rem;
            color: #dbeafe;
            /* blue-100 */
            filter: drop-shadow(0 0 0 rgba(59, 130, 246, 0));
            transition: transform .18s ease, color .18s ease, filter .18s ease;
        }

        .star-btn:hover {
            transform: scale(1.18);
        }

        .star-btn.active {
            color: #3b82f6;
            /* blue-500 */
            transform: scale(1.05);
            filter: drop-shadow(0 2px 8px rgba(59, 130, 246, 0.6));
        }

        .btn-shimmer {
            position: relative;
            overflow: hidden;
            isolation: isolate;
        }

        .btn-shimmer::after {
            content: '';
            position: absolute;
            top: 0;
            left: -75%;
            width: 50%;
            height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255, 255, 255, .4), transparent);
            transform: skewX(-20deg);
            transition: left .65s ease;
        }

        .btn-shimmer:hover::after {
            left: 125%;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(59, 130, 246, 0.1);
            box-shadow: 0 10px 30px -15px rgba(59, 130, 246, 0.1);
        }

        .chat-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .chat-scroll::-webkit-scrollbar-thumb {
            background: rgba(59, 130, 246, 0.15);
            border-radius: 9999px;
        }
    </style>
    <style>
        /* ApexForge Labs â€” Unified UI System */
        :root {
            --af-primary: #2563eb;
            --af-primary-dark: #1d4ed8;
            --af-primary-soft: #eff6ff;
            --af-sky: #38bdf8;
            --af-ink: #0f172a;
            --af-muted: #64748b;
            --af-border: #dbeafe;
            --af-surface: #ffffff;
            --af-page: #f6f9ff;
        }

        /* Perbaikan CSS Dark Mode */
        .dark {
            --af-page: #0f172a;
            --af-surface: #0f172a;
            --af-border: #334155;
        }

        .dark input, 
        .dark select, 
        .dark textarea {
            background: rgba(15, 23, 42, 0.9) !important;
            border-color: rgba(51, 65, 85, 0.8) !important;
            color: #ffffff !important;
        }

        .dark .glass-card,
        .dark .glass-panel,
        .dark .glass-surface {
            background: rgba(15, 23, 42, 0.85) !important;
            border-color: rgba(51, 65, 85, 0.6) !important;
            box-shadow: 0 18px 50px -32px rgba(0, 0, 0, 0.6) !important;
        }

        html {
            scroll-behavior: smooth
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background:
                radial-gradient(circle at 10% -10%, rgba(56, 189, 248, .10), transparent 30%),
                radial-gradient(circle at 100% 0%, rgba(37, 99, 235, .08), transparent 28%),
                var(--af-page);
        }

        ::selection {
            background: rgba(37, 99, 235, .18);
            color: #0f172a
        }

        ::-webkit-scrollbar {
            width: 7px;
            height: 7px
        }

        ::-webkit-scrollbar-track {
            background: rgba(241, 245, 249, .7)
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(37, 99, 235, .22);
            border-radius: 999px
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(37, 99, 235, .38)
        }

        input,
        select,
        textarea {
            border-color: var(--af-border) !important;
            background: rgba(255, 255, 255, .92);
            transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: rgba(37, 99, 235, .55) !important;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, .09) !important;
            outline: none !important;
        }

        button,a,[role="button"] {
            transition: all .2s ease
        }

        button:focus-visible,a:focus-visible,[role="button"]:focus-visible {
            outline: 2px solid rgba(37, 99, 235, .55);
            outline-offset: 2px;
        }

        table {
            border-collapse: separate;
            border-spacing: 0
        }

        thead th {
            background: rgba(239, 246, 255, .72) !important;
            color: #334155;
            font-weight: 700;
        }

        tbody tr {
            transition: background .18s ease
        }

        tbody tr:hover {
            background: rgba(239, 246, 255, .48)
        }

        [class*="bg-blue-600"] {
            box-shadow: 0 8px 22px -12px rgba(37, 99, 235, .72);
        }

        [class*="bg-blue-600"]:hover {
            box-shadow: 0 12px 28px -12px rgba(37, 99, 235, .78);
            transform: translateY(-1px);
        }

        .glass-panel,
        .glass-card,
        .glass-surface {
            background: rgba(255, 255, 255, .72);
            border: 1px solid rgba(219, 234, 254, .85);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            box-shadow: 0 18px 50px -32px rgba(30, 64, 175, .32);
        }

        .apex-page-glow {
            position: fixed;
            inset: auto -10rem -12rem auto;
            width: 28rem;
            height: 28rem;
            background: rgba(56, 189, 248, .09);
            filter: blur(70px);
            border-radius: 999px;
            pointer-events: none;
            z-index: -1;
        }

        @media (max-width:767px) {
            main {
                padding-left: 1rem !important;
                padding-right: 1rem !important
            }

            table {
                min-width: 680px
            }

            .overflow-x-auto {
                -webkit-overflow-scrolling: touch
            }
        }

        @media (prefers-reduced-motion:reduce) {

            *,
            *::before,
            *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
                scroll-behavior: auto !important
            }
        }
    </style>
</head>

<body class="bg-white dark:bg-slate-900 text-blue-950 dark:text-white min-h-screen flex antialiased relative font-sans transition-colors duration-300">
    
    {{-- Ambient Background Glows --}}
    <div class="fixed inset-0 pointer-events-none hologram-grid-blue z-0"></div>
    <div
        class="fixed top-[-20%] left-[-10%] w-[50rem] h-[50rem] bg-gradient-to-br from-blue-100/40 dark:from-blue-900/20 to-transparent rounded-full blur-[100px] pointer-events-none z-0">
    </div>

    @include('navbar.navigasi')

    <div class="flex-1 min-w-0 flex flex-col min-h-screen relative z-10">
        @include('navbar.nav')

        <main class="flex-1 min-w-0 overflow-y-auto">
            <div class="max-w-7xl mx-auto px-6 py-10">

                {{-- Breadcrumb --}}
                <div class="flex items-center gap-3 text-xs font-bold text-blue-300 dark:text-slate-400 uppercase tracking-widest mb-8">
                    @if (auth()->user()->role === 'company')
                        <a href="{{ route('company.workspaces.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Workspace</a>
                    @else
                        <a href="{{ route('freelancer.workspaces.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Workspace Saya</a>
                    @endif
                    <i class="fa-solid fa-chevron-right text-[9px] text-blue-200 dark:text-slate-600"></i>
                    <span class="text-blue-600 dark:text-blue-400 font-medium">{{ $workspace->project->project_name }}</span>
                </div>

                {{-- Pure Blue System Alerts --}}
                @if (session('success'))
                    <div class="mb-8 overflow-hidden relative bg-blue-50 dark:bg-slate-800 border border-blue-200 dark:border-slate-700 p-4 rounded-2xl flex items-start gap-4">
                        <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center shrink-0 shadow-[0_0_15px_rgba(59,130,246,0.4)]">
                            <i class="fa-solid fa-check text-white text-sm"></i>
                        </div>
                        <div class="pt-1.5 font-bold text-blue-900 dark:text-white text-sm">{{ session('success') }}</div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-8 overflow-hidden relative bg-white dark:bg-slate-900 border-2 border-blue-600 p-4 rounded-2xl shadow-[0_0_20px_rgba(59,130,246,0.15)] flex items-start gap-4">
                        <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-slate-800 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-slate-700 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </div>
                        <div class="pt-1.5 font-bold text-blue-950 dark:text-white text-sm">{{ session('error') }}</div>
                    </div>
                @endif

                {{-- Layout Single Column --}}
                <div class="space-y-4">

{{-- ROW 1: INFO PROJECT + PROGRESS + TAHAP PENGERJAAN --}}
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-stretch mb-8">

                        @php
                            $stageActionRoute = auth()->user()->role === 'company'
                                ? 'company.workspaces.progress'
                                : 'freelancer.workspaces.progress';
                            // NON-LINEAR: default detail = tahap pertama yang belum selesai, atau tahap pertama jika semua selesai
                            $firstIncompleteOrder = null;
                            foreach ($stageItems as $idx => $si) {
                                if (empty($si['is_completed'])) { $firstIncompleteOrder = $idx + 1; break; }
                            }
                            $displayActiveOrder = $firstIncompleteOrder ?? max(1, (int) $totalStages);
                            if ((int) $totalStages === 0) $displayActiveOrder = 1;
                            $completedCountVal = $completedCount ?? 0;
                            $isAllCompleted = $totalStages > 0 && $completedCountVal >= $totalStages;
                            $isAtLastStage = $isAllCompleted;
                            $progressLocked = in_array($workspace->status, ['Menunggu Pembayaran', 'Menunggu Verifikasi Admin', 'Selesai']);
                        @endphp

                        {{-- Card: Info Project --}}
                        <div class="lg:col-span-1 glass-card dark:bg-slate-900 rounded-3xl overflow-hidden flex flex-col h-full">
                            <div class="px-6 py-5 border-b border-blue-50/50 dark:border-slate-800 bg-gradient-to-b from-blue-50/50 dark:from-slate-800/50 to-transparent">
                                <h2 class="font-black text-sm text-blue-950 dark:text-white tracking-tight">{{ $workspace->project->project_name }}</h2>
                            </div>
                            <div class="flex flex-col justify-start items-start gap-4 p-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-slate-800 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-slate-800 flex items-center justify-center shrink-0">
                                        <i class="fa-regular fa-building text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black tracking-widest uppercase text-blue-400 dark:text-slate-400 mb-0.5">Perusahaan</p>
                                        <p class="text-xs font-bold text-blue-900 dark:text-white">{{ $workspace->company->name }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-slate-800 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-slate-800 flex items-center justify-center shrink-0">
                                        <i class="fa-regular fa-user text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black tracking-widest uppercase text-blue-400 dark:text-slate-400 mb-0.5">Freelancer</p>
                                        <p class="text-xs font-bold text-blue-900 dark:text-white">{{ $workspace->freelancer->name }}</p>
                                    </div>
                                </div>
                                @if ($workspace->project->deadline)
                                    @php
                                        // Deadline tersimpan sebagai tanggal (tanpa jam). Batas deadline = akhir hari
                                        // tersebut (23:59:59) sesuai timezone aplikasi (UTC). Dihitung sekali di server
                                        // agar target konsisten, lalu JS menghitung mundur realtime tiap detik.
                                        $deadlineEnd   = \Carbon\Carbon::parse($workspace->project->deadline)->endOfDay();
                                        $deadlineMs    = $deadlineEnd->getTimestamp() * 1000;
                                        $remainingSec  = $deadlineEnd->getTimestamp() - \Carbon\Carbon::now()->getTimestamp();
                                        $initialText   = 'Deadline telah lewat';
                                        if ($remainingSec > 0) {
                                            $initialText = 'â³ ' . intdiv($remainingSec, 86400) . ' Hari '
                                                . intdiv($remainingSec % 86400, 3600) . ' Jam '
                                                . intdiv($remainingSec % 3600, 60) . ' Menit '
                                                . ($remainingSec % 60) . ' Detik lagi';
                                        }
                                    @endphp
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-slate-800 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-slate-800 flex items-center justify-center shrink-0">
                                            <i class="fa-regular fa-calendar-days text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="text-[9px] font-black tracking-widest uppercase text-blue-400 dark:text-slate-400 mb-0.5">Deadline</p>
                                            <p class="text-xs font-bold text-blue-900 dark:text-white">
                                                {{ \Carbon\Carbon::parse($workspace->project->deadline)->format('d M Y') }}
                                            </p>
                                            <p id="deadlineCountdown" class="text-[11px] font-semibold mt-1 {{ $remainingSec > 0 ? 'text-blue-600 dark:text-blue-300' : 'text-red-500 dark:text-red-400' }}">
                                                {{ $initialText }}
                                            </p>
                                        </div>
                                    </div>
                                    <script>
                                        (function () {
                                            var el = document.getElementById('deadlineCountdown');
                                            if (!el) return;
                                            var targetMs = {{ $deadlineMs }};
                                            function tick() {
                                                var diff = targetMs - Date.now();
                                                if (diff <= 0) {
                                                    el.innerHTML = 'Deadline telah lewat';
                                                    el.classList.remove('text-blue-600', 'dark:text-blue-300');
                                                    el.classList.add('text-red-500', 'dark:text-red-400');
                                                    return;
                                                }
                                                var s  = Math.floor(diff / 1000);
                                                var d  = Math.floor(s / 86400);
                                                var h  = Math.floor((s % 86400) / 3600);
                                                var m  = Math.floor((s % 3600) / 60);
                                                var sc = s % 60;
                                                el.innerHTML = 'â³ ' + d + ' Hari ' + h + ' Jam ' + m + ' Menit ' + sc + ' Detik lagi';
                                            }
                                            tick();
                                            setInterval(tick, 1000);
                                        })();
                                    </script>
                                @endif

                                {{-- Aksi Company: Laporkan Keterlambatan (hanya saat Melewati Batas Waktu) --}}
                                @if (auth()->user()->role === 'company' && $workspace->status === 'Melewati Batas Waktu')
                                    <div class="w-full rounded-2xl bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 p-4">
                                        <p class="text-xs font-extrabold text-red-700 dark:text-red-300 flex items-center gap-1.5">
                                            <i class="fa-solid fa-triangle-exclamation text-[11px]"></i> Deadline Terlewat
                                        </p>
                                        <p class="text-[11px] font-medium text-red-500 dark:text-red-400 mt-1 leading-relaxed">
                                            Freelancer belum menyelesaikan pekerjaan hingga batas akhir. Anda dapat melaporkan keterlambatan ini ke Admin.
                                        </p>
                                        <a href="{{ route('company.reports.create', ['workspace_id' => $workspace->id, 'reason' => 'late']) }}"
                                            class="mt-3 inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-bold transition shadow-md shadow-red-600/20">
                                            <i class="fa-solid fa-flag text-[11px]"></i> Laporkan Keterlambatan
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Kolom Tengah: Progress + Detail MENYATU (SATU CARD, 2 SECTION) --}}
                        <div class="lg:col-span-1 min-w-0 flex flex-col h-full">
                            <div class="glass-card dark:bg-slate-900 rounded-2xl overflow-hidden flex flex-col h-full">
                                {{-- SECTION ATAS: Progress Pengerjaan --}}
                                <div class="p-5">
                                    <div class="flex items-center justify-between gap-3 mb-3">
                                        <h2 class="font-bold text-[13px] text-blue-950 dark:text-white tracking-tight flex items-center gap-2">
                                            <span class="w-6 h-6 rounded-lg bg-blue-600 text-white flex items-center justify-center text-[10px] leading-none"><i class="fa-solid fa-arrow-trend-up"></i></span> Progress Pengerjaan
                                        </h2>
                                        <span class="text-[9px] font-extrabold uppercase tracking-widest text-blue-500 dark:text-slate-400 bg-blue-50 dark:bg-slate-800 border border-blue-100 dark:border-slate-700 px-2 py-1 rounded-md shrink-0">{{ $completedCountVal }}/{{ $totalStages }} Selesai</span>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div class="shrink-0 text-center min-w-[64px]">
                                            <span class="block text-[26px] font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-700 to-blue-400 dark:from-blue-400 dark:to-blue-200 tracking-tighter leading-none">{{ $progressValue }}%</span>
                                            <span class="block text-[8px] font-extrabold uppercase tracking-widest text-blue-300 dark:text-slate-500 mt-1">Keseluruhan</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="w-full bg-blue-50 dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded-full h-2 overflow-hidden">
                                                <div class="h-full rounded-full bg-gradient-to-r from-blue-600 to-blue-400 transition-all duration-700" style="width: {{ $progressValue }}%"></div>
                                            </div>
                                            @if ($completedCountVal > 0)
                                                <p class="mt-2 flex items-center gap-1.5 text-[11px] font-bold text-emerald-600 dark:text-emerald-400 truncate">
                                                    <i class="fa-solid fa-check-circle text-[10px]"></i> {{ $completedCountVal }} dari {{ $totalStages }} tahap selesai
                                                </p>
                                            @else
                                                <p class="mt-2 text-[11px] font-bold text-blue-300 dark:text-slate-500 truncate">Belum ada tahap selesai • Fleksibel, pilih mana saja</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Divider tanpa gap --}}
                                <div class="h-px bg-blue-100/70 dark:bg-slate-800"></div>

                                {{-- SECTION BAWAH: Detail Tahap Terpilih --}}
                                @if (count($stageItems) > 0)
                                    @php
                                        $latestNoteByStage = [];
                                        foreach ($workspace->progressHistories as $noteHistory) {
                                            $noteStage = (string) ($noteHistory->stage ?? '');
                                            if ($noteStage !== '' && $noteHistory->description && !array_key_exists($noteStage, $latestNoteByStage)) {
                                                $latestNoteByStage[$noteStage] = $noteHistory;
                                            }
                                        }
                                    @endphp
                                    <div class="flex-1 flex flex-col justify-center items-center text-center px-5 py-6 bg-blue-50/30 dark:bg-slate-800/20">
                                        <div class="flex items-center justify-center gap-2 mb-4">
                                            <h3 class="font-bold text-[12px] text-blue-950 dark:text-white tracking-tight flex items-center gap-2">
                                                <span class="w-5 h-5 rounded-md bg-white dark:bg-slate-800 border border-blue-100 dark:border-slate-700 text-blue-600 dark:text-blue-400 flex items-center justify-center text-[9px] leading-none"><i class="fa-regular fa-circle-dot"></i></span> Detail Tahap Terpilih
                                            </h3>
                                            <span class="hidden sm:inline text-[8px] font-extrabold uppercase tracking-widest text-blue-300 dark:text-slate-500">Klik tahap â–¶ detail</span>
                                        </div>
                                        <div class="w-full flex flex-col items-center justify-center text-center">
                                            @foreach ($stageItems as $detailIndex => $detailItem)
                                                @php
                                                    $detailStage = $detailItem['name'];
                                                    $detailOrder = $detailIndex + 1;
                                                    $detailIsCompleted = !empty($detailItem['is_completed']);
                                                    $detailIsActive = $detailOrder === (int) $displayActiveOrder;
                                                    if ($detailIsCompleted) {
                                                        $detailLabel = 'Selesai';
                                                        $detailLabelColor = 'text-emerald-600 bg-emerald-50 border border-emerald-200 dark:text-emerald-400 dark:bg-emerald-900/20 dark:border-emerald-800/40';
                                                    } else {
                                                        $detailLabel = 'Belum Selesai';
                                                        $detailLabelColor = 'text-slate-500 bg-slate-100 border border-slate-200 dark:text-slate-400 dark:bg-slate-800 dark:border-slate-700';
                                                    }
                                                @endphp
                                                <div id="stageDetail-{{ $detailOrder }}" class="stage-detail w-full flex flex-col items-center justify-center {{ $detailOrder === (int) $displayActiveOrder ? '' : 'hidden' }}">
                                                    <div class="w-full rounded-xl border border-blue-100 dark:border-slate-700 bg-white dark:bg-slate-900 p-4 flex flex-col items-center justify-center text-center space-y-4">
                                                        <div class="flex items-center justify-center gap-2">
                                                        <span class="shrink-0 w-6 h-6 rounded-full bg-blue-600 text-white text-[10px] font-black flex items-center justify-center">{{ $detailOrder }}</span>
                                                        <h4 class="text-[13px] font-bold text-blue-950 dark:text-white">{{ $detailStage }}</h4>
                                                    </div>
                                                    @if ($detailLabel)
                                                        <div class="flex items-center justify-center">
                                                            <span class="text-[8px] font-black uppercase tracking-widest px-2 py-1 rounded-md {{ $detailLabelColor }} shrink-0">{{ $detailLabel }}</span>
                                                        </div>
                                                    @endif
                                                        @if (($detailItem['description'] ?? null) !== null && $detailItem['description'] !== '')
                                                            <p class="w-full text-[12px] font-medium text-slate-600 dark:text-slate-300 leading-relaxed">{{ $detailItem['description'] }}</p>
                                                        @else
                                                            <p class="w-full text-[11px] italic text-slate-400 dark:text-slate-500">Tidak ada deskripsi untuk tahap ini.</p>
                                                        @endif
                                                        {{-- Catatan pengerjaan fleksibel: prioritas note di stages JSON, fallback ke history terakhir --}}
                                                        @php $flexNote = $detailItem['note'] ?? null; @endphp
                                                        @if ($flexNote)
                                                            <div class="w-full rounded-lg bg-emerald-50/60 dark:bg-emerald-900/10 border border-emerald-200 dark:border-emerald-800/30 p-2.5">
                                                                <p class="text-[8px] font-black uppercase tracking-widest text-emerald-600 dark:text-emerald-400 mb-1 flex items-center justify-center gap-1">
                                                                    <i class="fa-solid fa-note-sticky text-[10px]"></i> Catatan pengerjaan terakhir
                                                                </p>
                                                                <p class="text-[11px] font-medium text-slate-700 dark:text-slate-300 leading-relaxed">{{ $flexNote }}</p>
                                                            </div>
                                                        @elseif ($latestNoteByStage[$detailStage] ?? null)
                                                            <div class="w-full rounded-lg bg-blue-50/70 dark:bg-slate-800 border border-blue-100 dark:border-slate-700 p-2.5">
                                                                <p class="text-[8px] font-black uppercase tracking-widest text-blue-400 dark:text-slate-400 mb-1 flex items-center justify-center gap-1">
                                                                    <i class="fa-solid fa-note-sticky text-[10px]"></i> Catatan pengerjaan terakhir
                                                                </p>
                                                                <p class="text-[11px] font-medium text-slate-600 dark:text-slate-300 leading-relaxed">{{ $latestNoteByStage[$detailStage]->description }}</p>
                                                            </div>
                                                        @endif
                                                        @if ($detailItem['creator'] ?? null)
                                                            <p class="w-full text-[10px] font-semibold text-blue-400 dark:text-slate-400 flex items-center justify-center gap-1">
                                                                <i class="fa-regular fa-user"></i> Dibuat oleh: {{ $detailItem['creator']->name }} <span class="uppercase">({{ ucfirst($detailItem['creator']->role) }})</span>
                                                            </p>
                                                        @endif
                                                    </div>
                                                    @if (auth()->user()->role === 'freelancer')
                                                        <div class="w-full pt-2">
                                                            @if ($progressLocked)
                                                                <button type="button" disabled
                                                                    class="w-full flex items-center justify-center gap-2 px-3 py-2.5 bg-gray-100 dark:bg-slate-800 text-gray-400 dark:text-slate-500 rounded-lg text-[11px] font-bold cursor-not-allowed border border-gray-200 dark:border-slate-700">
                                                                    <i class="fa-solid fa-lock text-[11px]"></i> Update Progress Dikunci
                                                                </button>
                                                            @else
                                                                <button type="button" onclick="openStageProgressModal('{{ addslashes($detailStage) }}', {{ $detailOrder }}, {{ $detailIsCompleted ? 'true' : 'false' }}, '{{ addslashes($flexNote ?? '') }}')"
                                                                    class="w-full flex items-center justify-center gap-2 px-3 py-2.5 {{ $detailIsCompleted ? 'bg-white dark:bg-slate-800 border border-emerald-200 dark:border-emerald-800 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50' : 'bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white' }} rounded-xl text-[11px] font-bold transition shadow-sm">
                                                                    <i class="fa-solid {{ $detailIsCompleted ? 'fa-pen-to-square' : 'fa-check' }} text-[11px]"></i> {{ $detailIsCompleted ? 'Edit Progress Tahap Ini' : 'Selesaikan Tahap Ini' }}
                                                                </button>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <div class="flex-1 flex items-center justify-center p-6 text-center border-t border-blue-50/50 dark:border-slate-800">
                                        <p class="text-[11px] font-semibold text-slate-400 dark:text-slate-500">Belum ada tahap untuk ditampilkan</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Card: Tahap Pengerjaan | COMPACT WORKFLOW TIMELINE --}}
                        <div class="lg:col-span-1 glass-card dark:bg-slate-900 rounded-2xl overflow-hidden flex flex-col h-full">
                            <div class="px-4 py-3 border-b border-blue-50/50 dark:border-slate-800 bg-gradient-to-b from-blue-50/50 dark:from-slate-800/50 to-transparent flex items-center justify-between gap-3">
                                <h2 class="font-bold text-[13px] text-blue-950 dark:text-white tracking-tight flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-lg bg-blue-600 text-white flex items-center justify-center text-[10px] leading-none"><i class="fa-solid fa-list-check"></i></span> Tahap Pengerjaan
                                </h2>
                                <span class="text-[9px] font-extrabold uppercase tracking-widest bg-blue-600 text-white px-2 py-1 rounded-md">{{ count($stageItems) }} Tahap</span>
                            </div>

                            <div class="flex-1 min-h-0 px-3 py-2 max-h-[380px] overflow-y-auto pr-1 custom-scrollbar">
                                @if (count($stageItems) > 0)
                                    <div class="relative">
                                        <div class="absolute left-[15px] top-2 bottom-2 w-px bg-blue-100 dark:bg-slate-700"></div>
                                        <div class="space-y-2.5">
                                            @foreach ($stageItems as $index => $stageItem)
                                                @php
                                                    $stage = $stageItem['name'];
                                                    $order = $index + 1;
                                                    $isCompleted = !empty($stageItem['is_completed']);
                                                    $isSelected = $order === (int) $displayActiveOrder;
                                                    $isOwner = (int) ($stageItem['created_by'] ?? 0) === (int) auth()->id();
                                                    $isCompanyWorkspaceOwner = auth()->user() && (int) $workspace->company_id === (int) auth()->id();
                                                    $canManageStage = $isCompanyWorkspaceOwner || $isOwner;

                                                    if ($isCompleted) {
                                                        $bg = 'bg-white dark:bg-slate-900 border-emerald-200 dark:border-emerald-800/30';
                                                        $label = 'Selesai';
                                                        $labelColor = 'text-emerald-600 bg-emerald-50 border-emerald-200 dark:text-emerald-400 dark:bg-emerald-900/20 dark:border-emerald-800/40';
                                                    } else {
                                                        $bg = 'bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 opacity-95';
                                                        $label = 'Belum Selesai';
                                                        $labelColor = 'text-slate-500 bg-slate-100 border-slate-200 dark:text-slate-400 dark:bg-slate-800 dark:border-slate-700';
                                                    }
                                                    $isActive = $isSelected; // untuk styling edit/delete konteks
                                                @endphp
                                                <div class="relative pl-8">
                                                    <button type="button" data-stage-target="stageDetail-{{ $order }}" data-stage-order="{{ $order }}"
                                                        class="stage-circle absolute left-0 top-1 w-8 h-8 rounded-full flex items-center justify-center text-[11px] font-black border-2 transition-all focus:outline-none focus:ring-2 focus:ring-blue-400/40 {{ $order === (int) $displayActiveOrder ? 'stage-circle-selected' : '' }} {{ $isCompleted ? 'bg-emerald-500 border-emerald-500 text-white shadow-sm' : 'bg-white dark:bg-slate-800 border-slate-300 dark:border-slate-600 text-slate-500 dark:text-slate-400 hover:border-blue-300' }}">
                                                        @if ($isCompleted)
                                                            <i class="fa-solid fa-check text-[10px]"></i>
                                                        @else
                                                            <i class="fa-regular fa-clock text-[10px]"></i>
                                                        @endif
                                                    </button>

                                                    <div class="border rounded-xl transition-all {{ $bg }}">
                                                        <div class="flex items-center gap-1.5">
                                                            <div onclick="selectStage({{ $order }})" class="flex-1 min-w-0 flex items-center gap-2 cursor-pointer px-2.5 py-2">
                                                                <p class="flex-1 min-w-0 text-[12px] font-bold truncate text-blue-950 dark:text-white">{{ $stage }}</p>
                                                                <span class="text-[8px] font-black uppercase tracking-widest px-2 py-1 rounded-md {{ $labelColor }} shrink-0 leading-none flex items-center gap-1">
                                                                    @if($isCompleted)
                                                                        <i class="fa-solid fa-check text-[9px]"></i>
                                                                    @else
                                                                        <i class="fa-regular fa-clock text-[9px]"></i>
                                                                    @endif
                                                                    {{ $label }}
                                                                </span>
                                                            </div>
                                                            @if ($canManageStage)
                                                                <div class="flex items-center gap-1 shrink-0 pr-1.5">
                                                                    <button type="button" onclick="document.getElementById('editItem-{{ $order }}').classList.toggle('hidden')"
                                                                        class="inline-flex items-center justify-center w-6 h-6 rounded-md text-[10px] font-bold transition {{ $isActive ? 'bg-blue-600 text-white border border-blue-600 hover:bg-blue-700' : 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-slate-800 border border-blue-200 dark:border-slate-700 hover:bg-blue-100' }}">
                                                                        <i class="fa-solid fa-pen text-[9px]"></i>
                                                                    </button>
                                                                    <form method="POST" action="{{ route($stageActionRoute, $workspace) }}" class="inline delete-stage-form shrink-0">
                                                                        @csrf
                                                                        <input type="hidden" name="action" value="delete">
                                                                        <input type="hidden" name="old_stage" value="{{ $stage }}">
                                                                        <button type="button" onclick="openDeleteStageModal(this.closest('form'), '{{ addslashes($stage) }}')"
                                                                            class="inline-flex items-center justify-center w-6 h-6 rounded-md text-[10px] font-bold transition {{ $isActive ? 'bg-red-600 text-white border border-red-600 hover:bg-red-700' : 'text-red-500 dark:text-red-400 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/40 hover:bg-red-100' }}">
                                                                            <i class="fa-solid fa-trash text-[9px]"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        @if ($canManageStage)
                                                            <div id="editItem-{{ $order }}" class="hidden mx-2.5 mb-2.5">
                                                                <form method="POST" action="{{ route($stageActionRoute, $workspace) }}" class="bg-white dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded-lg p-2.5 space-y-2">
                                                                    @csrf
                                                                    <input type="hidden" name="action" value="rename">
                                                                    <input type="hidden" name="old_stage" value="{{ $stage }}">
                                                                    <input type="text" name="new_stage" value="{{ $stage }}" maxlength="255" placeholder="Nama tahap"
                                                                        class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-700 rounded-md text-xs font-semibold dark:text-white focus:outline-none focus:border-blue-400">
                                                                    <textarea name="description" rows="2" maxlength="2000" placeholder="Deskripsi (opsional)"
                                                                        class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-700 rounded-md text-xs font-medium dark:text-white resize-none focus:outline-none">{{ $stageItem['description'] ?? '' }}</textarea>
                                                                    <div class="flex justify-end gap-2">
                                                                        <button type="button" onclick="document.getElementById('editItem-{{ $order }}').classList.add('hidden')"
                                                                            class="px-3 py-1.5 text-[10px] font-bold text-slate-500 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-md">Batal</button>
                                                                        <button type="submit" class="px-3 py-1.5 text-[10px] font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-md">Simpan</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <div class="py-10 text-center">
                                        <div class="w-10 h-10 mx-auto mb-2 rounded-xl bg-blue-50 dark:bg-slate-800 border border-blue-100 dark:border-slate-700 flex items-center justify-center text-blue-300 dark:text-slate-500">
                                            <i class="fa-solid fa-layer-group text-sm"></i>
                                        </div>
                                        <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500">Belum ada tahap</p>
                                    </div>
                                @endif
                            </div>

                            <div class="px-3 py-3 border-t border-blue-50/60 dark:border-slate-800 bg-blue-50/30 dark:bg-slate-800/30">
                                @if ((int) $workspace->company_id === (int) auth()->id() && auth()->user()->role === 'company')
                                    <button type="button" id="companyAddStageToggle"
                                        onclick="document.getElementById('companyAddStageForm').classList.toggle('hidden'); document.getElementById('companyAddStageToggle').classList.add('hidden')"
                                        class="w-full flex items-center justify-center gap-2 px-3 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition shadow-sm">
                                        <i class="fa-solid fa-plus text-[11px]"></i> Tambah Tahap
                                    </button>
                                    <div id="companyAddStageForm" class="hidden">
                                        <form method="POST" action="{{ route('company.workspaces.progress', $workspace) }}" class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-xl p-3 space-y-2.5 shadow-sm">
                                            @csrf
                                            <input type="hidden" name="action" value="add">
                                            <div>
                                                <label class="block text-[8px] font-black text-blue-500 dark:text-blue-400 uppercase tracking-widest mb-1">Nama Tahap</label>
                                                <input type="text" name="new_stage" maxlength="255" required placeholder="Nama tahap..."
                                                    class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded-md text-xs font-semibold dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-blue-400">
                                            </div>
                                            <div>
                                                <label class="block text-[8px] font-black text-blue-500 dark:text-blue-400 uppercase tracking-widest mb-1">Deskripsi</label>
                                                <textarea name="description" rows="2" maxlength="2000" placeholder="Deskripsi (opsional)"
                                                    class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded-md text-xs font-medium dark:text-white placeholder:text-slate-400 resize-none focus:outline-none"></textarea>
                                            </div>
                                            <div class="flex justify-end gap-2 pt-1">
                                                <button type="button"
                                                    onclick="document.getElementById('companyAddStageToggle').classList.remove('hidden'); document.getElementById('companyAddStageForm').classList.add('hidden')"
                                                    class="px-3 py-1.5 text-[10px] font-bold text-slate-500 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-md">Batal</button>
                                                <button type="submit" class="px-4 py-1.5 text-[10px] font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-md flex items-center gap-1">
                                                    <i class="fa-solid fa-plus text-[10px]"></i> Tambah Tahap
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                @elseif ((int) $workspace->freelancer_id === (int) auth()->id() && auth()->user()->role === 'freelancer')
                                    <form method="POST" action="{{ route('freelancer.workspaces.progress', $workspace) }}" class="flex gap-2">
                                        @csrf
                                        <input type="hidden" name="action" value="add">
                                        <input type="text" name="new_stage" maxlength="255" placeholder="Nama tahap baru..."
                                            class="flex-1 px-3 py-2 bg-white dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded-lg text-xs font-semibold dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-blue-400">
                                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-[11px] font-bold transition">Tambah</button>
                                    </form>
                                    @if ($progressLocked)
                                        <div class="w-full px-3 py-2 mt-2 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-500 dark:text-slate-400 rounded-lg text-[11px] font-bold text-center">
                                            <i class="fa-solid fa-lock mr-1"></i> Progress dikunci
                                        </div>
                                    @elseif ($isAllCompleted)
                                        <div class="w-full px-3 py-2 mt-2 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800/30 text-emerald-600 dark:text-emerald-400 rounded-lg text-[11px] font-bold text-center">
                                            <i class="fa-solid fa-check-circle mr-1"></i> Semua tahap selesai ({{ $progressValue }}%)
                                        </div>
                                    @else
                                        <p class="mt-2 text-[10px] font-semibold text-blue-400 dark:text-slate-400 text-center leading-relaxed">
                                            <i class="fa-solid fa-circle-info mr-1"></i> Fleksibel: pilih tahap mana saja untuk melihat detail & memperbarui progres
                                        </p>
                                    @endif
                                @endif
                            </div>
                        </div>
                        </div>

                    {{-- ROW 2: CHAT --}}
                    <div class="glass-card dark:bg-slate-900 rounded-3xl overflow-hidden flex flex-col h-[500px]">
                        {{-- Chat Header --}}
                        <div class="px-6 py-4 border-b border-blue-50/50 dark:border-slate-800 bg-gradient-to-b from-blue-50/80 dark:from-slate-800/80 to-transparent flex items-center justify-between shrink-0">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 rounded-[1rem] bg-gradient-to-br from-blue-500 to-blue-700 text-white flex items-center justify-center font-black text-lg shadow-[0_4px_10px_rgba(59,130,246,0.3)]">
                                    {{ strtoupper(substr(auth()->user()->role === 'company' ? $workspace->freelancer->name : $workspace->company->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h3 class="font-black text-sm text-blue-950 dark:text-white tracking-tight">
                                        {{ auth()->user()->role === 'company' ? $workspace->freelancer->name : $workspace->company->name }}
                                    </h3>
                                    <p class="text-[10px] font-bold text-blue-400 dark:text-slate-400 uppercase tracking-widest mt-0.5">{{ $workspace->project->project_name }}</p>
                                </div>
                            </div>

                            @php
                                // Pure Blue Status Indicators using ORIGINAL labels
                                $chatStatusMap = [
                                    'Sedang Dikerjakan' => ['label' => 'Sedang Dikerjakan', 'dot' => 'bg-blue-400 shadow-[0_0_8px_rgba(96,165,250,0.8)]', 'box' => 'bg-blue-50 border-blue-100 text-blue-600 dark:bg-slate-800 dark:border-slate-800 dark:text-blue-400'],
                                    'Menunggu Review' => ['label' => 'Menunggu Review', 'dot' => 'bg-amber-400 shadow-[0_0_8px_rgba(251,191,36,0.8)]', 'box' => 'bg-amber-50 border-amber-200 text-amber-700 dark:bg-amber-900/40 dark:border-amber-900 dark:text-amber-300'],
                                    'Menunggu Revisi' => ['label' => 'Menunggu Revisi', 'dot' => 'bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.8)]', 'box' => 'bg-white border-blue-200 text-blue-700 dark:bg-slate-900 dark:border-slate-700 dark:text-blue-400'],
                                    'Menunggu Pembayaran' => ['label' => 'Menunggu Pembayaran', 'dot' => 'bg-blue-700 shadow-[0_0_8px_rgba(29,78,216,0.8)]', 'box' => 'bg-blue-50 border-blue-200 text-blue-800 dark:bg-slate-800 dark:border-slate-700 dark:text-blue-400'],
                                    'Menunggu Verifikasi Admin' => ['label' => 'Menunggu Verifikasi Admin', 'dot' => 'bg-blue-300 shadow-[0_0_8px_rgba(147,197,253,0.8)]', 'box' => 'bg-white border-blue-100 text-blue-500 dark:bg-slate-900 dark:border-slate-800 dark:text-blue-400'],
                                    'Selesai' => ['label' => 'Selesai', 'dot' => 'bg-white', 'box' => 'bg-blue-600 border-blue-600 text-white shadow-md'],
                                    'Melewati Batas Waktu' => ['label' => 'Melewati Batas Waktu', 'dot' => 'bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.9)]', 'box' => 'bg-red-50 border-red-200 text-red-600 dark:bg-red-900/40 dark:border-red-900 dark:text-red-300'],
                                ];
                                $statusStyle = $chatStatusMap[$workspace->status] ?? ['label' => $workspace->status, 'dot' => 'bg-blue-300', 'box' => 'bg-white text-blue-400 border-blue-100 dark:bg-slate-900 dark:text-slate-400 dark:border-slate-800'];
                            @endphp

                            <div class="flex items-center gap-4">
                                <span
                                    class="flex items-center gap-2 px-3 py-1.5 rounded-lg border text-[10px] font-black uppercase tracking-wider {{ $statusStyle['box'] }}">
                                    <span class="w-2 h-2 rounded-full {{ $statusStyle['dot'] }}"></span>
                                    {{ $statusStyle['label'] }}
                                </span>

                                @php
                                    $reportedTarget =
                                        auth()->user()->role === 'company'
                                            ? $workspace->freelancer
                                            : $workspace->company;
                                @endphp
                                @if ($reportedTarget && (int) $reportedTarget->id !== (int) auth()->id())
                                    <a href="{{ route(auth()->user()->role === 'company' ? 'company.reports.create' : 'freelancer.reports.create', ['workspace_id' => $workspace->id, 'reported_user_id' => $reportedTarget->id]) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-black uppercase tracking-widest text-blue-500 dark:text-blue-400 bg-white dark:bg-slate-900 border border-blue-200 dark:border-slate-700 hover:bg-blue-50 dark:hover:bg-slate-800 hover:border-blue-300 dark:hover:border-slate-600 rounded-lg transition-colors">
                                        <i class="fa-solid fa-flag"></i> Laporkan
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- Chat Body --}}
                        <div id="chatBody" class="flex-1 overflow-y-auto chat-scroll p-6 space-y-5 bg-white/40 dark:bg-slate-900/40 hologram-grid-blue relative">
                            @if ($workspace->messages->isNotEmpty())
                                @foreach ($workspace->messages as $message)
                                    @if ($message->type === 'system')
                                        <div class="flex justify-center my-6 relative z-10">
                                            <div class="bg-blue-50/80 dark:bg-slate-800/80 backdrop-blur-sm text-blue-600 dark:text-blue-400 text-[10px] font-bold tracking-wide px-5 py-2 rounded-full border border-blue-100/50 dark:border-slate-800 inline-flex items-center gap-2 shadow-sm uppercase">
                                                <i class="fa-solid fa-gear opacity-60"></i>
                                                {{ $message->message }}
                                            </div>
                                        </div>
                                    @else
                                        @php $isMine = (int) $message->sender_id === (int) auth()->id(); @endphp
                                        <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }} relative z-10">
                                            <div class="max-w-[75%] {{ $isMine ? 'bg-gradient-to-br from-blue-600 to-blue-500 text-white rounded-tl-2xl rounded-tr-2xl rounded-bl-2xl rounded-br-sm shadow-[0_5px_15px_rgba(37,99,235,0.2)]' : 'bg-white dark:bg-slate-800 text-blue-900 dark:text-white border border-blue-100 dark:border-slate-700 rounded-tl-2xl rounded-tr-2xl rounded-br-2xl rounded-bl-sm shadow-sm' }} px-5 py-3.5">
                                                @if (!$isMine)
                                                    <p class="text-[9px] font-black uppercase tracking-widest text-blue-400 dark:text-slate-400 mb-1.5">
                                                        {{ $message->sender->name }}
                                                    </p>
                                                @endif
                                                <p class="text-[13px] leading-relaxed font-medium">{{ $message->message }}</p>
                                                <p class="text-[9px] font-bold mt-2 {{ $isMine ? 'text-blue-200' : 'text-blue-300 dark:text-slate-500' }} text-right tracking-wider">
                                                    {{ $message->created_at->format('H:i, d M') }}
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="absolute inset-0 flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 mx-auto mb-4 bg-blue-50 dark:bg-slate-800 rounded-2xl border border-blue-100 dark:border-slate-800 flex items-center justify-center shadow-inner">
                                        <i class="fa-regular fa-message text-2xl text-blue-300 dark:text-slate-400"></i>
                                    </div>
                                    <h3 class="text-sm font-black text-blue-900 dark:text-white tracking-tight">Belum Ada Pesan</h3>
                                    <p class="text-xs font-semibold text-blue-400 dark:text-slate-400 mt-1">Mulai percakapan dengan mengirim pesan.</p>
                                </div>
                            @endif
                        </div>

                        {{-- Chat Input --}}
                        <div class="px-6 py-5 border-t border-blue-50/50 dark:border-slate-800 bg-white dark:bg-slate-900 shrink-0 relative z-10">
                            <form method="POST" action="{{ route(auth()->user()->role === 'company' ? 'company.workspaces.message' : 'freelancer.workspaces.message', $workspace) }}" class="flex items-center gap-3">
                                @csrf
                                <input type="text" name="message" placeholder="Ketik pesan..." required maxlength="1000"
                                    class="flex-1 px-5 py-3.5 bg-blue-50/50 dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded-xl text-sm text-blue-950 dark:text-white font-medium placeholder-blue-300 dark:placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 focus:bg-white dark:focus:bg-slate-800 transition-all">
                                <button type="submit"
                                    class="w-12 h-12 sm:w-auto sm:px-6 sm:py-3.5 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition flex items-center justify-center gap-2 shadow-[0_5px_15px_rgba(37,99,235,0.25)] shrink-0">
                                    <i class="fa-solid fa-paper-plane"></i>
                                    <span class="hidden sm:inline tracking-wide">Kirim</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- ROW 3: HASIL PEKERJAAN / SUBMISSIONS --}}
                    @include('workspace._submissions')

                    {{-- ROW 4: INVOICE (company Menunggu Pembayaran / Verifikasi Admin) --}}
                    @if(auth()->user()->role === 'company' && in_array($workspace->status, ['Menunggu Pembayaran', 'Menunggu Verifikasi Admin']) && $payment)
                        <div class="glass-card dark:bg-slate-900 rounded-3xl overflow-hidden">
                            <div class="px-6 py-5 border-b border-blue-50/50 dark:border-slate-800 bg-gradient-to-b from-blue-50/50 dark:from-slate-800/50 to-transparent">
                                <h2 class="font-black text-sm text-blue-950 dark:text-white tracking-tight">Invoice Pembayaran</h2>
                            </div>
                            <div class="p-6 space-y-6">
                                {{-- Invoice Info --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl p-5 shadow-sm space-y-3">
                                        <div class="flex items-center justify-between border-b border-blue-50 dark:border-slate-800 pb-2">
                                            <p class="text-[10px] font-black text-blue-400 dark:text-slate-400 uppercase tracking-widest">Nomor Invoice</p>
                                            <span class="text-xs font-bold text-blue-950 dark:text-white bg-blue-50 dark:bg-slate-800 px-2 py-0.5 rounded">{{ $payment->invoice_number }}</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <p class="text-[10px] font-black text-blue-400 dark:text-slate-400 uppercase tracking-widest">Total</p>
                                            <span class="text-sm font-bold text-blue-900 dark:text-white">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <p class="text-[10px] font-black text-blue-400 dark:text-slate-400 uppercase tracking-widest">Biaya Platform (5%)</p>
                                            <span class="text-xs font-bold text-blue-500 dark:text-blue-400">Rp {{ number_format($payment->platform_fee, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex items-center justify-between pt-3 border-t border-blue-100/50 dark:border-slate-800">
                                            <p class="text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest">Total Dibayar</p>
                                            <span class="text-base font-black text-blue-700 dark:text-blue-400">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                        </div>
                                    </div>

                                    <div class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl p-5 shadow-sm space-y-3">
                                        <p class="text-[10px] font-black text-blue-400 dark:text-slate-400 uppercase tracking-widest mb-1">Status Pembayaran</p>
                                        @php
                                            $psDesign = $payment->status === 'pending' 
                                                ? 'bg-white border-blue-400 text-blue-600 dark:bg-slate-900 dark:border-blue-400 dark:text-blue-400' 
                                                : 'bg-blue-600 border-blue-600 text-white shadow-[0_5px_15px_rgba(37,99,235,0.3)]';
                                            $psLabel = $payment->status === 'pending' ? 'Belum Dibayar' : 'Menunggu Verifikasi';
                                        @endphp
                                        <div
                                            class="inline-block px-3 py-1.5 rounded-lg border text-[11px] font-bold tracking-wide {{ $psDesign }}">
                                            {{ $psLabel }}
                                        </div>

                                        @if($payment->status === 'rejected')
                                            <div class="mt-4 p-3 bg-white dark:bg-slate-900 border border-blue-400 dark:border-slate-700 rounded-xl relative overflow-hidden">
                                                <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>
                                                <p class="text-xs font-bold text-blue-900 dark:text-white ml-2">Pembayaran ditolak. Silakan upload ulang.</p>
                                                @if($payment->admin_note)
                                                    <p class="text-[10px] text-blue-600 dark:text-blue-400 mt-2 ml-2 p-2 bg-blue-50 dark:bg-slate-800 rounded-lg border border-blue-100 dark:border-slate-800">Alasan: {{ $payment->admin_note }}</p>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Payment Gateway (hanya pending) --}}
                                @if($payment->status === 'pending')
                                    <div class="flex flex-col gap-4 bg-gradient-to-r from-blue-50 dark:from-slate-800/50 to-transparent p-5 rounded-2xl border border-blue-100 dark:border-slate-800">
                                        <p class="text-xs font-semibold text-blue-800 dark:text-blue-300 leading-relaxed max-w-2xl">
                                            Silakan lanjutkan ke <strong>Payment Gateway</strong> untuk melakukan pembayaran, kemudian upload bukti pembayaran pada halaman berikutnya.
                                        </p>
                                        <div class="flex flex-col sm:flex-row items-center gap-4">
                                            <a href="{{ route('company.payments.gateway', $workspace) }}"
                                                class="w-full sm:w-auto px-6 py-3 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition shadow-[0_5px_15px_rgba(37,99,235,0.3)] flex items-center justify-center gap-2">
                                                <i class="fa-solid fa-credit-card"></i> Bayar Sekarang
                                            </a>
                                            <p class="text-[10px] font-bold text-blue-400 dark:text-slate-400 flex items-center gap-1.5">
                                                <i class="fa-solid fa-circle-info"></i> Mode Simulasi
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                {{-- Payment Upload Form (rejected / re-upload) --}}
                                @if($payment->status === 'rejected')
                                    <form method="POST" action="{{ route('company.payments.upload', $workspace) }}" enctype="multipart/form-data" class="space-y-5 bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 p-5 rounded-2xl shadow-sm">
                                        @csrf
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                            <div>
                                                <label class="block text-[10px] font-black text-blue-500 dark:text-blue-400 uppercase tracking-widest mb-2">Metode Pembayaran</label>
                                                <select name="payment_method" required class="w-full px-4 py-3 bg-blue-50/50 dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded-xl text-sm font-bold text-blue-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:bg-white dark:focus:bg-slate-800 transition-all">
                                                    <option value="">Pilih Metode</option>
                                                    <option value="Transfer Bank">Transfer Bank</option>
                                                    <option value="QRIS">QRIS</option>
                                                    <option value="E-Wallet">E-Wallet</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-black text-blue-500 dark:text-blue-400 uppercase tracking-widest mb-2">Upload Bukti Pembayaran</label>
                                                <input type="file" name="payment_proof" required accept=".jpg,.jpeg,.png,.pdf"
                                                       class="w-full px-4 py-2 bg-blue-50/50 dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded-xl text-sm font-semibold text-blue-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-wider file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition-all cursor-pointer">
                                                <p class="text-[9px] font-bold text-blue-400 dark:text-slate-400 mt-1.5 uppercase tracking-wide">Format: jpg, jpeg, png, pdf. Maksimal 10 MB.</p>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-black text-blue-500 dark:text-blue-400 uppercase tracking-widest mb-2">Catatan (opsional)</label>
                                            <textarea name="company_note" rows="2" maxlength="2000" placeholder="Tambahkan catatan..."
                                                      class="w-full px-4 py-3 bg-blue-50/50 dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded-xl text-sm font-medium text-blue-900 dark:text-white dark:placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:bg-white dark:focus:bg-slate-800 transition-all resize-none"></textarea>
                                        </div>
                                        <button type="submit"
                                            class="w-full sm:w-auto px-6 py-3 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition flex items-center justify-center gap-2 shadow-[0_5px_15px_rgba(37,99,235,0.3)]">
                                            <i class="fa-solid fa-cloud-arrow-up"></i> Kirim Pembayaran
                                        </button>
                                    </form>
                                @endif

                                @if($payment->status === 'waiting_verification')
                                    <div class="flex items-center gap-3 px-5 py-4 bg-white dark:bg-slate-900 border border-blue-200 dark:border-slate-700 rounded-2xl shadow-[0_5px_20px_rgba(59,130,246,0.1)]">
                                        <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-slate-800 text-blue-500 dark:text-blue-400 flex items-center justify-center shrink-0 border border-blue-100 dark:border-slate-800">
                                            <i class="fa-solid fa-clock animate-pulse"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-blue-900 dark:text-white">Bukti pembayaran telah dikirim. Menunggu verifikasi admin.</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- ROW 5: TIMELINE + ACTIONS --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        {{-- Card: Timeline --}}
                        <div class="md:col-span-2 glass-card dark:bg-slate-900 rounded-3xl overflow-hidden">
                            <div class="px-6 py-5 border-b border-blue-50/50 dark:border-slate-800 bg-gradient-to-b from-blue-50/50 dark:from-slate-800/50 to-transparent">
                                <h2 class="font-black text-sm text-blue-950 dark:text-white tracking-tight">Timeline Progress</h2>
                            </div>
                            <div class="p-6">
                                @if ($workspace->progressHistories->isNotEmpty())
                                    <div class="space-y-3 max-h-[340px] overflow-y-auto pr-2 custom-scrollbar">
                                        @foreach ($workspace->progressHistories as $history)
                                            <div class="relative pl-8 border-l border-blue-100/70 dark:border-slate-800 ml-2">
                                                <div class="absolute -left-[13px] top-0 w-6 h-6 rounded-full bg-white dark:bg-slate-900 border border-blue-200 dark:border-slate-700 shadow-[0_0_10px_rgba(59,130,246,0.2)] flex items-center justify-center z-10">
                                                    <span class="text-[8px] text-blue-600 dark:text-blue-400 font-black">{{ $history->progress }}%</span>
                                                </div>
                                                <div class="bg-blue-50/40 dark:bg-slate-800/40 rounded-2xl p-3 border border-blue-50 dark:border-slate-800 ml-1 hover:bg-blue-50 dark:hover:bg-slate-800 hover:border-blue-100 dark:hover:border-slate-700 transition-colors">
                                                    <div class="flex flex-wrap items-center justify-between gap-2 mb-1.5 border-b border-blue-100/50 dark:border-slate-800 pb-1.5">
                                                        <span class="text-xs font-black text-blue-900 dark:text-white">{{ $history->stage }}</span>
                                                        <span class="text-[9px] font-bold tracking-widest uppercase text-blue-400 dark:text-slate-400 bg-white dark:bg-slate-800 px-2 py-0.5 rounded shadow-sm border border-blue-50 dark:border-slate-800">{{ $history->created_at->format('d M Y') }}</span>
                                                    </div>
                                                    @if ($history->description)
                                                        <p class="text-[11px] font-medium text-blue-800/70 dark:text-blue-300 leading-relaxed">{{ $history->description }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="py-12 flex flex-col items-center justify-center">
                                        <i class="fa-solid fa-clock-rotate-left text-3xl text-blue-100 dark:text-slate-600 mb-3"></i>
                                        <p class="text-xs font-bold text-blue-300 dark:text-slate-400 uppercase tracking-widest">Belum ada riwayat progress.</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Card: Actions & Rating --}}
                        <div class="md:col-span-1 glass-card dark:bg-slate-900 rounded-3xl overflow-hidden flex flex-col justify-between">
                            <div>
                                <div class="px-6 py-5 border-b border-blue-50/50 dark:border-slate-800 bg-gradient-to-b from-blue-50/50 dark:from-slate-800/50 to-transparent">
                                    <h2 class="font-black text-sm text-blue-950 dark:text-white tracking-tight">Aksi & Ulasan</h2>
                                </div>
                                <div class="p-6 space-y-4">
                                    @if (auth()->user()->role === 'company' && $workspace->status === 'Menunggu Review')
                                        <div class="flex items-center gap-3 px-4 py-3.5 bg-amber-50 dark:bg-amber-900/40 border border-amber-200 dark:border-amber-900 rounded-xl text-amber-700 dark:text-amber-300">
                                            <i class="fa-solid fa-clock"></i>
                                            <p class="text-xs font-bold">Perusahaan sedang meninjau hasil pekerjaan Anda.</p>
                                        </div>
                                    @endif

                                    @if (strtolower($workspace->status) === 'selesai')
                                        <div class="pt-2">
                                            @if ($workspace->rating)
                                                <div class="bg-blue-50 dark:bg-slate-800 border border-blue-100 dark:border-slate-800 rounded-2xl p-5 text-center shadow-inner">
                                                    <p class="text-[10px] font-black text-blue-400 dark:text-slate-400 uppercase tracking-widest mb-3">Rating Telah Diberikan</p>
                                                    <div class="flex justify-center gap-1.5 text-blue-500 dark:text-blue-400 text-lg mb-3 drop-shadow-[0_0_8px_rgba(59,130,246,0.4)]">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <i
                                                                class="fa-{{ $i <= $workspace->rating->rating ? 'solid' : 'regular' }} fa-star"></i>
                                                        @endfor
                                                    </div>
                                                    @if ($workspace->rating->review)
                                                        <div class="relative">
                                                            <i class="fa-solid fa-quote-left absolute -top-1 -left-1 text-blue-200 dark:text-slate-600 text-xl opacity-50"></i>
                                                            <p class="text-xs font-semibold text-blue-900 dark:text-white italic px-4 leading-relaxed relative z-10">"{{ $workspace->rating->review }}"</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                @if (auth()->user()->role === 'company')
                                                    <button type="button"
                                                        onclick="document.getElementById('ratingModal').classList.remove('hidden')"
                                                        class="w-full flex items-center justify-center gap-2 px-4 py-3.5 bg-white dark:bg-slate-900 border border-blue-300 dark:border-slate-700 text-blue-600 dark:text-blue-400 rounded-xl text-sm font-bold hover:bg-blue-50 dark:hover:bg-slate-800 transition shadow-sm">
                                                        <i class="fa-solid fa-star"></i> Beri Rating & Ulasan
                                                    </button>
                                                @else
                                                    <div class="text-center py-5 bg-white dark:bg-slate-900 rounded-2xl border border-blue-50 dark:border-slate-800 border-dashed">
                                                        <p class="text-[10px] font-bold text-blue-300 dark:text-slate-400 uppercase tracking-widest">Belum ada rating dari perusahaan.</p>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </main>

    </div>

    {{-- MODAL RATING & ULASAN (Company Only) --}}
@if (auth()->user()->role === 'company' && strtolower($workspace->status) === 'selesai')
    <style>
        .modal-star-btn {
            color: #cbd5e1; /* slate-300 */
            font-size: 1.75rem;
            cursor: pointer;
            background: none;
            border: none;
            transition: color 0.2s ease, transform 0.15s ease;
        }
        .modal-star-btn:hover,
        .modal-star-btn.active {
            color: #f59e0b !important; /* amber-500 */
        }
        .modal-star-btn.pop {
            animation: starPop 0.25s ease-out;
        }
        @keyframes starPop {
            0% { transform: scale(1); }
            50% { transform: scale(1.3); }
            100% { transform: scale(1); }
        }
    </style>

    <div id="ratingModal" class="hidden modal-backdrop fixed inset-0 z-50 flex items-center justify-center bg-blue-950/40 backdrop-blur-md p-4">
        <div class="modal-panel bg-white dark:bg-slate-900 rounded-3xl shadow-[0_20px_50px_rgba(30,58,138,0.2)] w-full max-w-md overflow-hidden border border-blue-100 dark:border-slate-800">
            <div class="relative px-6 py-7 bg-gradient-to-br from-blue-700 via-blue-600 to-blue-500 overflow-hidden">
                <div class="absolute inset-0 modal-header-pattern opacity-50"></div>
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-xl"></div>
                <div class="absolute -bottom-12 -left-8 w-28 h-28 bg-white/10 rounded-full blur-xl"></div>
                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/20 shadow-inner">
                            <i class="fa-solid fa-ranking-star text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-white text-base tracking-tight">Beri Rating & Ulasan</h3>
                            <p class="text-[10px] font-bold tracking-widest uppercase text-blue-200 mt-0.5">Bagikan pengalaman Anda</p>
                        </div>
                    </div>
                    <button type="button"
                        onclick="document.getElementById('ratingModal').classList.add('hidden')"
                        class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors">
                        <i class="fa-solid fa-xmark text-white text-sm"></i>
                    </button>
                </div>
            </div>

            {{-- Pastikan nama route & parameter sesuai dengan web.php Anda --}}
            <form method="POST" action="{{ route('company.workspaces.review.store', $workspace) }}" class="p-6 space-y-6 bg-white dark:bg-slate-900">
                @csrf
                <div class="bg-blue-50 dark:bg-slate-800 border border-blue-100 dark:border-slate-800 rounded-2xl p-5 text-center shadow-inner">
                    <label class="block text-[10px] font-black text-blue-500 dark:text-blue-400 uppercase tracking-widest mb-3">Pilih Rating</label>
                    <input type="hidden" name="rating" id="ratingInput" value="5">
                    
                    <div class="flex justify-center gap-2 mb-3" id="starRating">
                        @for ($i = 1; $i <= 5; $i++)
                            <button type="button" class="modal-star-btn active" data-value="{{ $i }}"
                                onclick="setRating({{ $i }})">
                                <i class="fa-solid fa-star pointer-events-none"></i>
                            </button>
                        @endfor
                    </div>
                    
                    <span class="inline-block px-3 py-1 bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest shadow-sm" id="ratingLabel">5 - Sempurna</span>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-blue-500 dark:text-blue-400 uppercase tracking-widest mb-2">Ulasan / Testimoni</label>
                    <textarea name="review" rows="3" maxlength="500" id="reviewText"
                        oninput="document.getElementById('reviewCount').textContent = this.value.length"
                        class="w-full px-4 py-3 bg-blue-50/50 dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded-xl text-sm font-medium text-blue-900 dark:text-white dark:placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:bg-white dark:focus:bg-slate-800 transition-all resize-none"
                        placeholder="Tulis ulasan kinerja freelancer ini..."></textarea>
                    <p class="text-[9px] font-black text-blue-300 dark:text-slate-400 mt-1.5 text-right"><span id="reviewCount">0</span>/500</p>
                </div>

                <button type="submit"
                    class="w-full py-3.5 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition shadow-[0_5px_15px_rgba(37,99,235,0.3)]">
                    Kirim Ulasan
                </button>
            </form>
        </div>
    </div>

    <script>
        (function () {
            'use strict';

            var ratingLabels = {
                1: '1 - Buruk',
                2: '2 - Kurang',
                3: '3 - Cukup',
                4: '4 - Sangat Baik',
                5: '5 - Sempurna'
            };

            // amber-500 â€” warna bintang aktif. Dipasang via style inline +
            // !important agar SELALU menang atas konflik CSS lain
            // (.star-btn.active, dark mode, utility Tailwind, dsb).
            var ACTIVE_COLOR = '#f59e0b';

            function starButtons() {
                return document.querySelectorAll('#starRating .modal-star-btn');
            }

            function setRating(value) {
                var v = parseInt(value, 10);
                if (!ratingLabels[v]) return;

                var input = document.getElementById('ratingInput');
                var label = document.getElementById('ratingLabel');
                if (input) input.value = v;
                if (label) label.textContent = ratingLabels[v];

                starButtons().forEach(function (btn, idx) {
                    var on = idx < v;
                    btn.classList.toggle('active', on);

                    if (on) {
                        // Bintang aktif: warna dipaku inline (!important).
                        btn.style.setProperty('color', ACTIVE_COLOR, 'important');
                    } else {
                        // Bintang non-aktif: bersihkan inline style agar warna
                        // default (#cbd5e1) dan efek :hover CSS kembali bekerja.
                        btn.style.removeProperty('color');
                    }
                });

                var clicked = document.querySelector('#starRating .modal-star-btn[data-value="' + v + '"]');
                if (clicked) {
                    clicked.classList.remove('pop');
                    void clicked.offsetWidth; // paksa reflow agar animasi pop restart
                    clicked.classList.add('pop');
                }
            }

            // WAJIB: inline onclick="setRating(n)" membutuhkan fungsi GLOBAL.
            // Bind eksplisit ke window supaya tidak kena Uncaught ReferenceError
            // akibat scope/closure/wrapper script mana pun.
            window.setRating = setRating;

            // Cadangan (belt & suspenders): delegasi klik di container bintang.
            // Klik tetap terproses walau inline handler tidak tereksekusi.
            var starContainer = document.getElementById('starRating');
            if (starContainer) {
                starContainer.addEventListener('click', function (e) {
                    var btn = e.target.closest ? e.target.closest('.modal-star-btn') : null;
                    if (btn && starContainer.contains(btn)) {
                        setRating(btn.getAttribute('data-value'));
                    }
                });
            }
        })();
    </script>
@endif

    {{-- Delete Stage Confirmation Modal --}}
    {{-- Modal Popup Form Deskripsi Pengerjaan (NON-LINEAR) --}}
    <div id="stageProgressModal" class="fixed inset-0 z-[260] flex items-center justify-center bg-black/60 hidden opacity-0 transition-opacity duration-300 backdrop-blur-sm p-4">
        <div class="bg-white dark:bg-slate-900 rounded-3xl w-full max-w-md overflow-hidden border border-blue-100 dark:border-slate-800 shadow-2xl transform scale-95 transition-transform duration-300 max-h-[90vh] overflow-y-auto">
            <div class="relative px-6 py-5 bg-gradient-to-br from-blue-600 to-blue-500 overflow-hidden">
                <div class="absolute inset-0 modal-header-pattern opacity-20"></div>
                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center border border-white/20">
                            <i class="fa-solid fa-list-check text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-white text-sm">Progress Tahap</h3>
                            <p id="stageModalStageName" class="text-[11px] font-bold text-blue-100 truncate max-w-[180px]">Nama Tahap</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeStageProgressModal()" class="w-8 h-8 rounded-full bg-white/15 hover:bg-white/25 flex items-center justify-center transition">
                        <i class="fa-solid fa-xmark text-white text-sm"></i>
                    </button>
                </div>
            </div>
            <form id="stageProgressForm" method="POST" action="{{ auth()->user()->role === 'freelancer' ? route('freelancer.workspaces.progress', $workspace) : route('company.workspaces.progress', $workspace) }}" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="action" value="update_stage">
                <input type="hidden" name="stage" id="stageModalStageInput" value="">
                <div class="flex items-center justify-between p-3 bg-blue-50/70 dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-700 flex items-center justify-center text-blue-600 dark:text-blue-400">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div>
                            <p class="text-xs font-black text-blue-950 dark:text-white">Status Tahap</p>
                            <p class="text-[10px] font-semibold text-slate-500 dark:text-slate-400">Tandai selesai / belum</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="stageModalCompleted" name="is_completed" value="1" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                        <span id="stageModalStatusLabel" class="ml-2 text-[11px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Belum</span>
                    </label>
                </div>
                <div>
                    <label for="stageModalDescription" class="block text-[10px] font-black uppercase tracking-widest text-blue-500 dark:text-blue-400 mb-2">Deskripsi / Catatan Pengerjaan Tahap Ini <span class="text-red-500">*</span></label>
                    <textarea id="stageModalDescription" name="description" rows="4" maxlength="2000" required placeholder="Jelaskan progres / hasil pengerjaan tahap ini..."
                        class="w-full px-4 py-3 bg-blue-50/50 dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded-xl text-sm font-medium text-slate-700 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-500/10 resize-none"></textarea>
                    <p class="text-[9px] font-semibold text-slate-400 mt-1.5"><span id="stageModalCharCount">0</span>/2000 • wajib diisi saat menandai selesai</p>
                    <p id="stageModalError" class="hidden mt-2 text-xs font-bold text-red-500 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/40 rounded-lg px-3 py-2"></p>
                </div>
                <div class="flex items-center gap-3 pt-1">
                    <button type="button" onclick="closeStageProgressModal()" class="flex-1 px-4 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-black hover:bg-slate-50 dark:hover:bg-slate-700 transition">Batal</button>
                    <button type="submit" id="stageModalSubmit" class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-black transition shadow-md flex items-center justify-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Progress Tahap
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteStageModal" class="fixed inset-0 z-[250] flex items-center justify-center bg-black/60 hidden opacity-0 transition-opacity duration-300 backdrop-blur-sm">
        <div class="glass-card rounded-3xl w-full max-w-sm mx-4 transform scale-95 transition-transform duration-300 bg-white/95 dark:bg-slate-900/95 border border-blue-100 dark:border-slate-800 overflow-hidden shadow-2xl">
            <div class="p-6">
                <div class="w-12 h-12 mx-auto mb-4 bg-red-50 dark:bg-red-500/10 border border-red-100 dark:border-red-500/20 rounded-full flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-trash-can text-xl text-red-500"></i>
                </div>
                <h3 class="font-black text-blue-950 dark:text-white text-lg text-center tracking-tight mb-2">Hapus tahap?</h3>
                <p class="text-sm font-medium text-slate-600 dark:text-slate-400 text-center leading-relaxed">
                    Apakah kamu yakin ingin menghapus tahap ini?<br>
                    <span id="deleteStageName" class="font-black text-blue-600 dark:text-blue-400 mt-1.5 block"></span>
                </p>
            </div>
            <div class="p-4 border-t border-blue-50 dark:border-slate-800 flex justify-end gap-3 bg-blue-50/30 dark:bg-slate-800/30">
                <button type="button" onclick="closeDeleteStageModal()" 
                    class="px-5 py-2.5 text-sm font-bold text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-xl transition-colors shadow-sm">
                    Batal
                </button>
                <button type="button" id="btnConfirmDeleteStage" 
                    class="px-5 py-2.5 bg-red-600 text-white rounded-xl text-sm font-bold hover:bg-red-700 transition shadow-[0_5px_15px_rgba(220,38,38,0.3)] inline-flex items-center gap-2 border border-transparent">
                    <i class="fa-solid fa-trash-can"></i> Hapus
                </button>
            </div>
        </div>
    </div>

    <script>
        // ── Modal Progress Tahap (NON-LINEAR) ──
        function openStageProgressModal(stageName, order, isCompleted, currentNote) {
            const modal = document.getElementById('stageProgressModal');
            const inner = modal.querySelector('div.transform');
            document.getElementById('stageModalStageName').textContent = stageName;
            document.getElementById('stageModalStageInput').value = stageName;
            const chk = document.getElementById('stageModalCompleted');
            chk.checked = !!isCompleted;
            updateStageModalLabel();
            const ta = document.getElementById('stageModalDescription');
            ta.value = currentNote || '';
            document.getElementById('stageModalCharCount').textContent = ta.value.length;
            document.getElementById('stageModalError').classList.add('hidden');
            modal.classList.remove('hidden');
            setTimeout(() => { modal.classList.remove('opacity-0'); if(inner) inner.classList.remove('scale-95'); }, 10);
            // fokus ke textarea
            setTimeout(() => ta.focus(), 120);
        }
        function closeStageProgressModal() {
            const modal = document.getElementById('stageProgressModal');
            const inner = modal.querySelector('div.transform');
            modal.classList.add('opacity-0');
            if(inner) inner.classList.add('scale-95');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }
        function updateStageModalLabel() {
            const chk = document.getElementById('stageModalCompleted');
            const lbl = document.getElementById('stageModalStatusLabel');
            if (!chk || !lbl) return;
            if (chk.checked) { lbl.textContent = 'Selesai'; lbl.className = 'ml-2 text-[11px] font-black uppercase tracking-widest text-emerald-600 dark:text-emerald-400'; }
            else { lbl.textContent = 'Belum'; lbl.className = 'ml-2 text-[11px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400'; }
        }
        document.getElementById('stageModalCompleted')?.addEventListener('change', updateStageModalLabel);
        document.getElementById('stageModalDescription')?.addEventListener('input', function() {
            document.getElementById('stageModalCharCount').textContent = this.value.length;
        });
        document.getElementById('stageProgressForm')?.addEventListener('submit', function(e) {
            const chk = document.getElementById('stageModalCompleted');
            const ta = document.getElementById('stageModalDescription');
            const err = document.getElementById('stageModalError');
            if (chk.checked && ta.value.trim() === '') {
                e.preventDefault();
                err.textContent = 'Deskripsi / catatan pengerjaan wajib diisi saat menandai tahap selesai.';
                err.classList.remove('hidden');
                ta.focus();
                return false;
            }
        });
        // klik backdrop
        document.getElementById('stageProgressModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeStageProgressModal();
        });

        // Logika Modal Konfirmasi Hapus Tahap
        let formToSubmit = null;

        function openDeleteStageModal(formElement, stageName) {
            formToSubmit = formElement;
            document.getElementById('deleteStageName').textContent = `"${stageName}"`;
            
            const modal = document.getElementById('deleteStageModal');
            const modalInner = modal.querySelector('div.glass-card');
            
            modal.classList.remove('hidden');
            // Sedikit delay untuk memicu animasi Tailwind
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modalInner.classList.remove('scale-95');
            }, 10);
        }

        function closeDeleteStageModal() {
            const modal = document.getElementById('deleteStageModal');
            const modalInner = modal.querySelector('div.glass-card');
            
            modal.classList.add('opacity-0');
            modalInner.classList.add('scale-95');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                formToSubmit = null;
            }, 300);
        }

        // Action Klik Confirm Hapus
        document.getElementById('btnConfirmDeleteStage')?.addEventListener('click', function() {
            if (formToSubmit) {
                formToSubmit.submit();
            }
        });

        // Menutup Modal dengan Klik Backdrop atau tombol Escape
        document.getElementById('deleteStageModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeDeleteStageModal();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const deleteModal = document.getElementById('deleteStageModal');
                if (deleteModal && !deleteModal.classList.contains('hidden')) {
                    closeDeleteStageModal();
                }
                const stageModal = document.getElementById('stageProgressModal');
                if (stageModal && !stageModal.classList.contains('hidden')) {
                    closeStageProgressModal();
                }
            }
        });

        // Logika Auto-Scroll Obrolan
        document.addEventListener('DOMContentLoaded', function() {
            const chatBody = document.getElementById('chatBody');
            if (chatBody) {
                chatBody.scrollTop = chatBody.scrollHeight;
            }
        });
    </script>

    <style>
        .stage-circle-selected {
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.25), 0 0 0 7px rgba(37, 99, 235, 0.12);
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const circles = document.querySelectorAll('.stage-circle');
            const details = document.querySelectorAll('.stage-detail');

            function selectStage(order) {
                circles.forEach(function (c) {
                    if (parseInt(c.dataset.stageOrder, 10) === order) {
                        c.classList.add('stage-circle-selected');
                    } else {
                        c.classList.remove('stage-circle-selected');
                    }
                });
                details.forEach(function (d) {
                    if (d.id === 'stageDetail-' + order) {
                        d.classList.remove('hidden');
                    } else {
                        d.classList.add('hidden');
                    }
                });
            }
            window.selectStage = selectStage;

            circles.forEach(function (c) {
                c.addEventListener('click', function () {
                    selectStage(parseInt(c.dataset.stageOrder, 10));
                });
            });
        });
    </script>
</body>

</html>
