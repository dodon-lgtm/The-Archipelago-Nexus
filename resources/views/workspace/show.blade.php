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
        /* ApexForge Labs — Unified UI System */
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
                <div class="space-y-6">

                    {{-- ROW 1: INFO PROJECT + PROGRESS --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        {{-- Card: Info Project --}}
                        <div class="md:col-span-1 glass-card dark:bg-slate-900 rounded-3xl overflow-hidden">
                            <div class="px-6 py-5 border-b border-blue-50/50 dark:border-slate-800 bg-gradient-to-b from-blue-50/50 dark:from-slate-800/50 to-transparent">
                                <h2 class="font-black text-sm text-blue-950 dark:text-white tracking-tight">{{ $workspace->project->project_name }}</h2>
                            </div>
                            <div class="p-6 space-y-5">
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
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-slate-800 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-slate-800 flex items-center justify-center shrink-0">
                                            <i class="fa-regular fa-calendar-days text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="text-[9px] font-black tracking-widest uppercase text-blue-400 dark:text-slate-400 mb-0.5">Deadline</p>
                                            <p class="text-xs font-bold text-blue-900 dark:text-white">
                                                {{ \Carbon\Carbon::parse($workspace->project->deadline)->format('d M Y') }}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Card: Progress Bar --}}
                        <div class="md:col-span-1 glass-card dark:bg-slate-900 rounded-3xl overflow-hidden flex flex-col justify-between">
                            <div>
                                <div class="px-6 py-5 border-b border-blue-50/50 dark:border-slate-800 bg-gradient-to-b from-blue-50/50 dark:from-slate-800/50 to-transparent">
                                    <h2 class="font-black text-sm text-blue-950 dark:text-white tracking-tight">Progress Pengerjaan</h2>
                                </div>
                                <div class="p-6">
                                    <div class="text-center mb-5">
                                        <span
                                            class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-700 to-blue-400 dark:from-blue-400 dark:to-blue-200 tracking-tighter">{{ $progressValue }}%</span>
                                        @if ($activeStage)
                                            <p class="text-xs font-bold text-blue-500 dark:text-blue-400 mt-2">{{ $activeStage }}</p>
                                        @endif
                                        @if ($totalStages > 0 && $activeStageOrder > 0)
                                            <p class="text-[9px] font-bold uppercase tracking-widest text-blue-300 dark:text-slate-400 mt-1">Tahap {{ $activeStageOrder }} dari {{ $totalStages }}</p>
                                        @endif
                                    </div>
                                    <div class="w-full bg-blue-50 dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded-full h-3 overflow-hidden shadow-inner">
                                        <div class="h-full rounded-full bg-gradient-to-r from-blue-600 to-blue-400 transition-all duration-700 shadow-[0_0_10px_rgba(59,130,246,0.5)] relative overflow-hidden"
                                            style="width: {{ $progressValue }}%">
                                            <div
                                                class="absolute inset-0 bg-[linear-gradient(45deg,transparent_25%,rgba(255,255,255,0.2)_50%,transparent_75%,transparent_100%)] bg-[length:20px_20px] animate-[slide_1s_linear_infinite]">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if (auth()->user()->role === 'freelancer')
                                <div class="px-6 pb-6 mt-6">
                                    @if (in_array($workspace->status, ['Menunggu Pembayaran', 'Menunggu Verifikasi Admin', 'Selesai']))
                                        <button type="button" disabled
                                            class="w-full flex items-center justify-center gap-2 px-4 py-3.5 bg-gray-200 dark:bg-slate-800 text-gray-500 dark:text-slate-400 rounded-xl text-sm font-bold cursor-not-allowed border border-gray-300 dark:border-slate-700">
                                            <i class="fa-solid fa-lock"></i> Update Progress Dikunci
                                        </button>
                                    @else
                                        <button type="button"
                                            onclick="document.getElementById('progressModal').classList.remove('hidden')"
                                            class="btn-shimmer w-full flex items-center justify-center gap-2 px-4 py-3.5 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition shadow-[0_5px_15px_rgba(37,99,235,0.3)]">
                                            <i class="fa-solid fa-chart-line"></i> Update Progress
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>

                        {{-- Card: Stage --}}
                        <div class="md:col-span-1 glass-card dark:bg-slate-900 rounded-3xl overflow-hidden flex flex-col h-full">
                            <div class="px-6 py-5 border-b border-blue-50/50 dark:border-slate-800 bg-gradient-to-b from-blue-50/50 dark:from-slate-800/50 to-transparent">
                                <h2 class="font-black text-sm text-blue-950 dark:text-white tracking-tight">Tahap Pengerjaan</h2>
                            </div>

                            @php
                                $stageActionRoute = auth()->user()->role === 'company'
                                    ? 'company.workspaces.progress'
                                    : 'freelancer.workspaces.progress';
                                // Se aktif stage tehap dari daftar (poshapul), peila urutam ditampil (aman).
                                $displayActiveOrder = max(1, min((int) $activeStageOrder, (int) $totalStages));
                            @endphp

                            <div class="p-6 flex-1 overflow-y-auto custom-sidebar-scroll max-h-[300px]">
                                <div class="grid grid-cols-1 gap-3">
                                    @foreach ($stageItems as $index => $stageItem)
                                        @php
                                            $stage = $stageItem['name'];
                                            $order = $index + 1;
                                            $isCompleted = $order < $displayActiveOrder;
                                            $isActive = $order === $displayActiveOrder;
                                            $isOwner = (int) ($stageItem['created_by'] ?? 0) === (int) auth()->id();

                                            // PURE BLUE LOGIC
                                            if ($isCompleted) {
                                                $icon = 'fa-solid fa-check-circle';
                                                $color = 'text-blue-500 dark:text-blue-400';
                                                $bg = 'bg-blue-50/80 border-blue-200 dark:bg-slate-800/80 dark:border-slate-700';
                                                $label = 'Selesai';
                                                $labelColor = 'text-blue-600 bg-white border border-blue-200 dark:text-blue-400 dark:bg-slate-900 dark:border-slate-700';
                                            } elseif ($isActive) {
                                                $icon = 'fa-solid fa-play-circle animate-pulse';
                                                $color = 'text-white';
                                                $bg =
                                                    'bg-blue-600 border-blue-500 shadow-[0_5px_15px_rgba(37,99,235,0.3)]';
                                                $label = 'Aktif';
                                                $labelColor = 'text-blue-600 bg-white dark:text-blue-400 dark:bg-slate-900';
                                            } else {
                                                $icon = 'fa-regular fa-circle';
                                                $color = 'text-blue-200 dark:text-slate-500';
                                                $bg = 'bg-transparent border-blue-50/50 dark:border-slate-800 opacity-60';
                                                $label = 'Belum Dimulai';
                                                $labelColor = 'text-blue-400 dark:text-slate-400 bg-white dark:bg-slate-900 border border-blue-50 dark:border-slate-800';
                                            }
                                        @endphp
                                        <div class="border rounded-xl p-3 transition-all duration-300 {{ $bg }}">
                                            <div class="flex items-center gap-3">
                                                <div class="w-6 flex justify-center shrink-0">
                                                    <i class="{{ $icon }} {{ $color }} text-sm"></i>
                                                </div>
                                                <div class="min-w-0 flex-1 flex items-center justify-between">
                                                    <p class="text-xs font-bold truncate {{ $isActive ? 'text-white' : 'text-blue-900 dark:text-white' }}">
                                                    {{ $stage }}
                                                </p>
                                                @if ($label)
                                                    <span
                                                        class="text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded-md {{ $labelColor }} ml-2 shrink-0">{{ $label }}</span>
                                                @endif
                                            </div>
                                            </div>

                                            @if ($stageItem['description'] ?? null)
                                                <p class="text-[11px] font-medium text-blue-800/70 dark:text-blue-300 leading-relaxed mt-1">{{ $stageItem['description'] }}</p>
                                            @endif

                                            @if ($stageItem['creator'])
                                                <p class="text-[10px] font-bold text-blue-400 dark:text-slate-400 mt-1.5 flex items-center gap-1">
                                                    <i class="fa-regular fa-user"></i> Dibuat oleh: {{ $stageItem['creator']->name }} <span class="uppercase">({{ ucfirst($stageItem['creator']->role) }})</span>
                                                </p>
                                            @endif

                                            @if ($isOwner)
                                                <div class="flex flex-wrap items-center gap-2 mt-2">
                                                    <button type="button" onclick="document.getElementById('editItem-{{ $order }}').classList.toggle('hidden')"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1 text-[9px] font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-slate-800 border border-blue-200 dark:border-slate-700 rounded-md transition">
                                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                                    </button>
                                                    <form method="POST" action="{{ route($stageActionRoute, $workspace) }}" onsubmit="return confirm('Hapus tahap &quot;{{ $stage }}&quot;?');" class="inline">
                                                        @csrf
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="old_stage" value="{{ $stage }}">
                                                        <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 text-[9px] font-bold text-red-500 dark:text-red-400 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/40 rounded-md transition">
                                                            <i class="fa-solid fa-trash"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                                <div id="editItem-{{ $order }}" class="hidden mt-1.5">
                                                    <form method="POST" action="{{ route($stageActionRoute, $workspace) }}" class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-xl p-3 space-y-2">
                                                        @csrf
                                                        <input type="hidden" name="action" value="rename">
                                                        <input type="hidden" name="old_stage" value="{{ $stage }}">
                                                        <input type="text" name="new_stage" value="{{ $stage }}" maxlength="255" placeholder="Nama tahap"
                                                            class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded-lg text-xs font-semibold dark:text-white focus:outline-none focus:border-blue-400">
                                                        <textarea name="description" rows="2" maxlength="2000" placeholder="Deskripsi (option)"
                                                            class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded-lg text-xs font-medium dark:text-white dark:placeholder:text-slate-500 resize-none focus:outline-none">{{ $stageItem['description'] ?? '' }}</textarea>
                                                        <div class="flex justify-end gap-2 pt-1">
                                                            <button type="button" onclick="document.getElementById('editItem-{{ $order }}').classList.add('hidden')"
                                                                class="px-3 py-1 text-[10px] font-bold text-slate-500 bg-white dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded-lg">Batal</button>
                                                            <button type="submit" class="px-3 py-1 text-[10px] font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg">Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Footer: Company tambah tahap / Freelancer quick-add --}}
                            @if ((int) $workspace->company_id === (int) auth()->id() && auth()->user()->role === 'company')
                                <div class="px-5 py-4 border-t border-blue-50/60 dark:border-slate-800 bg-blue-50/30 dark:bg-slate-800/30">
                                    <button type="button" id="companyAddStageToggle"
                                        onclick="document.getElementById('companyAddStageForm').classList.toggle('hidden'); document.getElementById('companyAddStageToggle').classList.add('hidden')"
                                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-900 border border-blue-300 dark:border-slate-700 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-slate-800 rounded-xl text-sm font-bold transition shadow-sm">
                                        <i class="fa-solid fa-plus"></i> Tambah Tahap
                                    </button>
                                    <div id="companyAddStageForm" class="hidden">
                                        <form method="POST" action="{{ route('company.workspaces.progress', $workspace) }}" class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl p-4 space-y-2.5 shadow-sm">
                                            @csrf
                                            <input type="hidden" name="action" value="add">
                                            <div>
                                                <label class="block text-[9px] font-black text-blue-500 dark:text-blue-400 uppercase tracking-widest mb-1">Nama Tahap</label>
                                                <input type="text" name="new_stage" maxlength="255" required placeholder="Nama tahap..."
                                                    class="w-full px-3 py-2.5 bg-white dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded-lg text-xs font-semibold dark:text-white dark:placeholder:text-slate-500 focus:outline-none focus:border-blue-400">
                                            </div>
                                            <div>
                                                <label class="block text-[9px] font-black text-blue-500 dark:text-blue-400 uppercase tracking-widest mb-1">Deskripsi</label>
                                                <textarea name="description" rows="3" maxlength="2000" placeholder="Deskripsi (option)"
                                                    class="w-full px-3 py-2.5 bg-white dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded-lg text-xs font-medium dark:text-white dark:placeholder:text-slate-500 resize-none focus:outline-none"></textarea>
                                            </div>
                                            <div class="flex justify-end gap-2 pt-1">
                                                <button type="button"
                                                    onclick="document.getElementById('companyAddStageToggle').classList.remove('hidden'); document.getElementById('companyAddStageForm').classList.add('hidden')"
                                                    class="px-3 py-1.5 text-[10px] font-bold text-slate-500 bg-white dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded-lg">Batal</button>
                                                <button type="submit" class="px-4 py-1.5 text-[10px] font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg flex items-center gap-1">
                                                    <i class="fa-solid fa-plus"></i> Tambah Tahap
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @elseif ((int) $workspace->freelancer_id === (int) auth()->id() && auth()->user()->role === 'freelancer')
                                <div class="px-5 py-4 border-t border-blue-50/60 dark:border-slate-800 bg-blue-50/30 dark:bg-slate-800/30">
                                    <form method="POST" action="{{ route('freelancer.workspaces.progress', $workspace) }}" class="flex gap-2">
                                        @csrf
                                        <input type="hidden" name="action" value="add">
                                        <input type="text" name="new_stage" maxlength="255" placeholder="Nama tahap baru..."
                                            class="flex-1 px-4 py-2 bg-white dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded-lg text-xs font-semibold dark:text-white dark:placeholder:text-slate-500 focus:outline-none focus:border-blue-400">
                                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition">Tambah</button>
                                    </form>
                                </div>
                            @endif
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
                                    <div class="space-y-6">
                                        @foreach ($workspace->progressHistories as $history)
                                            <div class="relative pl-8 border-l border-blue-100/70 dark:border-slate-800 ml-2">
                                                <div class="absolute -left-[13px] top-0 w-6 h-6 rounded-full bg-white dark:bg-slate-900 border border-blue-200 dark:border-slate-700 shadow-[0_0_10px_rgba(59,130,246,0.2)] flex items-center justify-center z-10">
                                                    <span class="text-[8px] text-blue-600 dark:text-blue-400 font-black">{{ $history->progress }}%</span>
                                                </div>
                                                <div class="bg-blue-50/40 dark:bg-slate-800/40 rounded-2xl p-4 border border-blue-50 dark:border-slate-800 ml-1 hover:bg-blue-50 dark:hover:bg-slate-800 hover:border-blue-100 dark:hover:border-slate-700 transition-colors">
                                                    <div class="flex flex-wrap items-center justify-between gap-2 mb-2 border-b border-blue-100/50 dark:border-slate-800 pb-2">
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

                                    @if ($workspace->status === 'Selesai')
                                        <div class="pt-2">
                                            @if ($workspace->rating)
                                                <div class="bg-blue-50 dark:bg-slate-800 border border-blue-100 dark:border-slate-800 rounded-2xl p-5 text-center shadow-inner">
                                                    <p class="text-[10px] font-black text-blue-400 dark:text-slate-400 uppercase tracking-widest mb-3">Rating Telah Diberikan</p>
                                                    <div class="flex justify-center gap-1.5 text-blue-500 dark:text-blue-400 text-lg mb-3 drop-shadow-[0_0_8px_rgba(59,130,246,0.4)]">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <i
                                                                class="fa-{{ $i <= $workspace->rating->score ? 'solid' : 'regular' }} fa-star"></i>
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

    {{-- MODAL UPDATE PROGRESS --}}
<div id="progressModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm sm:p-6 transition-all">
    
    <!-- Modal Panel -->
    <div class="flex flex-col w-full max-w-md bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-h-[90vh] overflow-hidden border border-slate-200 dark:border-slate-800">
        
        <!-- Header (Fixed) -->
        <div class="relative shrink-0 px-5 py-4 bg-gradient-to-r from-blue-700 to-blue-500 overflow-hidden">
            <!-- Background Ornaments -->
            <div class="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-white to-transparent"></div>
            <div class="absolute -top-6 -right-6 w-24 h-24 bg-white/20 rounded-full blur-xl"></div>
            
            <div class="relative flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-white/20 backdrop-blur-md shadow-inner border border-white/20">
                        <i class="fa-solid fa-chart-line text-white text-base"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-white text-base tracking-wide">Update Progress</h3>
                        <p class="text-[10px] font-medium text-blue-100 uppercase tracking-widest mt-0.5">Perbarui status pekerjaan</p>
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('progressModal').classList.add('hidden')"
                    class="flex items-center justify-center w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white transition-colors">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
        </div>

        <!-- Scrollable Body -->
        <div class="flex-1 overflow-y-auto bg-slate-50/30 dark:bg-slate-900 p-5 space-y-5">
            
            <!-- MAIN UPDATE FORM -->
            <form method="POST" action="{{ route('freelancer.workspaces.progress', $workspace) }}" class="space-y-4">
                @csrf
                <input type="hidden" name="action" value="select">

                <!-- Tahap Pengerjaan -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Tahap Pengerjaan</label>
                    <select name="stage" id="stageSelect" required onchange="updateStageProgress()"
                        class="w-full px-3 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all cursor-pointer">
                        @foreach ($stages as $stage)
                            <option value="{{ $stage }}" {{ $activeStage === $stage ? 'selected' : '' }}>{{ $stage }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Progress Preview -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Estimasi Progress</label>
                    <div class="flex items-center gap-4 p-3.5 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl shadow-sm">
                        <span id="progressPreview" class="text-xl font-black text-blue-600 dark:text-blue-400 min-w-[3.5rem] text-right">{{ $progressValue }}%</span>
                        <div class="flex-1 bg-slate-100 dark:bg-slate-700 rounded-full h-2 overflow-hidden shadow-inner">
                            <div id="progressPreviewBar" class="h-full rounded-full bg-blue-500 transition-all duration-500 ease-out shadow-[0_0_8px_rgba(59,130,246,0.5)]" style="width: {{ $progressValue }}%"></div>
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-1.5 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-info text-blue-400"></i> Dihitung otomatis dari urutan tahap.
                    </p>
                </div>

                <!-- Deskripsi -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Deskripsi Tambahan</label>
                        <span class="text-[10px] font-medium text-slate-400"><span id="progressDescCount">0</span>/500</span>
                    </div>
                    <textarea name="description" rows="2" maxlength="500" id="progressDesc"
                        oninput="document.getElementById('progressDescCount').textContent = this.value.length"
                        class="w-full px-3 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all resize-none"
                        placeholder="Jelaskan detail yang dikerjakan..."></textarea>
                </div>

                <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold transition-all shadow-md hover:shadow-lg shadow-blue-500/20 active:scale-[0.98]">
                    Simpan Progress
                </button>
            </form>

            <div class="w-full h-px bg-slate-200 dark:bg-slate-700 my-4"></div>

            <!-- KELOLA TAHAP -->
            <div class="space-y-3">
                <p class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-1.5 mb-2">
                    <i class="fa-solid fa-pen-to-square"></i> Modifikasi Tahap
                </p>

                <!-- Ubah Nama Tahap -->
                <form method="POST" action="{{ route('freelancer.workspaces.progress', $workspace) }}" class="flex gap-2">
                    @csrf
                    <input type="hidden" name="action" value="rename">
                    <select name="old_stage" class="w-[35%] px-2.5 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-semibold text-slate-700 dark:text-white focus:outline-none focus:border-blue-500">
                        @php $myStageNames = collect($stageItems)->where('created_by', (int) auth()->id())->pluck('name')->all(); @endphp
                        @foreach ($myStageNames as $stage)
                            <option value="{{ $stage }}" {{ $activeStage === $stage ? 'selected' : '' }}>{{ $stage }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="new_stage" maxlength="255" placeholder="Ganti nama jadi..." required
                        class="flex-1 px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-700 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-blue-500">
                    <button type="submit" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-bold transition-colors">
                        Ganti
                    </button>
                </form>

                <!-- Pindah Tahap Berikutnya -->
                @php $isAtLastStage = $activeStageOrder >= $totalStages; @endphp
                @if (!$isAtLastStage)
                    <form method="POST" action="{{ route('freelancer.workspaces.progress', $workspace) }}">
                        @csrf
                        <input type="hidden" name="action" value="move_next">
                        <button type="submit" class="w-full px-4 py-2.5 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/30 dark:hover:bg-indigo-900/50 border border-indigo-200 dark:border-indigo-800/50 text-indigo-700 dark:text-indigo-400 rounded-lg text-xs font-bold transition-colors flex items-center justify-center gap-2">
                            Lanjut ke Tahap Berikutnya <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </form>
                @else
                    <div class="w-full px-4 py-2 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800/30 text-emerald-600 dark:text-emerald-400 rounded-lg text-[11px] font-bold text-center">
                        <i class="fa-solid fa-check-circle mr-1"></i> Anda sudah berada di tahap terakhir
                    </div>
                @endif
            </div>

        </div>
        
        <!-- Scripts -->
        <script>
            const STAGE_LIST = @json($stages);

            function calcStageProgress(index) {
                const total = STAGE_LIST.length;
                if (!total) return 0;
                const order = index + 1;
                if (order >= total) return 100;
                return Math.round((order / total) * 100);
            }

            function updateStageProgress() {
                const sel = document.getElementById('stageSelect');
                if (!sel) return;
                const idx = STAGE_LIST.indexOf(sel.value);
                const pct = calcStageProgress(idx);
                const elVal = document.getElementById('progressPreview');
                const elBar = document.getElementById('progressPreviewBar');
                
                if (elVal) {
                    elVal.textContent = pct + '%';
                }
                if (elBar) {
                    elBar.style.width = pct + '%';
                }
            }
        </script>
    </div>
</div>

    {{-- MODAL RATING & ULASAN (Company Only) --}}
    @if (auth()->user()->role === 'company' && $workspace->status === 'Selesai')
        <div id="ratingModal" class="hidden modal-backdrop fixed inset-0 z-50 flex items-center justify-center bg-blue-950/40 backdrop-blur-md p-4">
            <div class="modal-panel bg-white dark:bg-slate-900 rounded-3xl shadow-[0_20px_50px_rgba(30,58,138,0.2)] w-full max-w-md overflow-hidden border border-blue-100 dark:border-slate-800">
                <div class="relative px-6 py-7 bg-gradient-to-br from-blue-700 via-blue-600 to-blue-500 overflow-hidden">
                    <div class="absolute inset-0 modal-header-pattern opacity-50"></div>
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-xl"></div>
                    <div class="absolute -bottom-12 -left-8 w-28 h-28 bg-white/10 rounded-full blur-xl"></div>
                    <div class="relative flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/20 shadow-inner">
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

                <form method="POST" action="{{ route('company.client.review.store', $workspace->project_id) }}" class="p-6 space-y-6 bg-white dark:bg-slate-900">
                    @csrf
                    <div class="bg-blue-50 dark:bg-slate-800 border border-blue-100 dark:border-slate-800 rounded-2xl p-5 text-center shadow-inner">
                        <label class="block text-[10px] font-black text-blue-500 dark:text-blue-400 uppercase tracking-widest mb-3">Pilih Rating</label>
                        <input type="hidden" name="rating" id="ratingInput" value="5">
                        <div class="flex justify-center gap-2 mb-3" id="starRating">
                            @for ($i = 1; $i <= 5; $i++)
                                <button type="button" class="star-btn active" data-value="{{ $i }}"
                                    onclick="setRating({{ $i }})">
                                    <i class="fa-solid fa-star"></i>
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
                        class="btn-shimmer w-full py-3.5 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition shadow-[0_5px_15px_rgba(37,99,235,0.3)]">
                        Kirim Ulasan
                    </button>
                </form>
            </div>

            <script>
                const ratingLabels = {
                    1: '1 - Buruk',
                    2: '2 - Kurang',
                    3: '3 - Cukup',
                    4: '4 - Sangat Baik',
                    5: '5 - Sempurna'
                };

                function setRating(v) {
                    document.getElementById('ratingInput').value = v;
                    document.getElementById('ratingLabel').textContent = ratingLabels[v];
                    document.querySelectorAll('#starRating .star-btn').forEach((btn, idx) => {
                        if (idx < v) {
                            btn.classList.add('active');
                        } else {
                            btn.classList.remove('active');
                        }
                    });
                    const clicked = document.querySelector('#starRating .star-btn[data-value="' + v + '"]');
                    if (clicked) {
                        clicked.classList.remove('pop');
                        void clicked.offsetWidth;
                        clicked.classList.add('pop');
                    }
                }
            </script>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chatBody = document.getElementById('chatBody');
            if (chatBody) {
                chatBody.scrollTop = chatBody.scrollHeight;
            }
        });
    </script>
</body>

</html>