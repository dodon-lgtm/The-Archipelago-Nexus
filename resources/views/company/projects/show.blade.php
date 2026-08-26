<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.theme-boot')

    

    <title>{{ $project->project_name }} - Detail Proyek</title>

    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    {{-- Alpine.js (Untuk Filter, Search, & Toggle tanpa reload) --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8FAFC;
        }

        .font-display { font-family: 'Lexend', sans-serif; }

        /* Animations (kept minimal) */
        @keyframes slideUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes scalePop { from { transform: scale(0.97); } to { transform: scale(1); } }
        @keyframes pulseRing {
            0% { box-shadow: 0 0 0 0 rgba(255,255,255,0.5); }
            70% { box-shadow: 0 0 0 8px rgba(255,255,255,0); }
            100% { box-shadow: 0 0 0 0 rgba(255,255,255,0); }
        }
        @keyframes modalBoxIn {
            from { opacity: 0; transform: translateY(16px) scale(0.97); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes iconBounce {
            0% { transform: scale(0.7); opacity: 0; }
            60% { transform: scale(1.06); opacity: 1; }
            100% { transform: scale(1); }
        }

        .animate-slide { animation: slideUp 0.4s ease both; }
        .animate-pop { animation: scalePop 0.3s ease both; }
        .delay-1 { animation-delay: 0.04s; }
        .delay-2 { animation-delay: 0.08s; }
        .delay-3 { animation-delay: 0.12s; }
        .status-dot-live { animation: pulseRing 2s infinite; }
        .status-dot-live-amber { animation: pulseRing 2s infinite; }

        /* Card interactions */
        .lift-card, .lift-card-strong {
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }
        .lift-card:hover { border-color: #cbd5e1; box-shadow: 0 6px 18px -10px rgba(15,23,42,0.15); }
        .lift-card-strong:hover { transform: translateY(-2px); }
        .icon-pop { transition: transform 0.2s ease; }
        .icon-pop:hover { transform: scale(1.06); }

        /* Scroll reveal (subtle, fast) */
        .reveal-on-scroll { opacity: 0.001; transform: translateY(10px); transition: opacity 0.35s ease, transform 0.35s ease; }
        .reveal-on-scroll.revealed { opacity: 1; transform: translateY(0); }
        .reveal-fallback { animation: forceShow 0.01s 0.8s forwards; }
        @keyframes forceShow { to { opacity: 1; transform: none; } }

        /* Modal */
        .custom-modal {
            position: fixed; inset: 0; z-index: 99999;
            display: flex; align-items: center; justify-content: center; padding: 20px;
            background: rgba(15,23,42,0.55);
            backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);
            opacity: 0; visibility: hidden; pointer-events: none;
            transition: opacity 0.2s ease, visibility 0.2s ease;
        }
        .custom-modal.active { opacity: 1; visibility: visible; pointer-events: auto; }
        .modal-box {
            position: relative; width: 100%; max-width: 420px;
            background: white; border-radius: 18px; padding: 28px;
            box-shadow: 0 20px 50px rgba(15,23,42,0.22);
            transform: translateY(16px) scale(0.97); opacity: 0;
        }
        .dark .modal-box {
            background: #0f172a;
            border: 1px solid #1e293b;
        }
        .custom-modal.active .modal-box { animation: modalBoxIn 0.25s ease forwards; }
        .modal-close {
            position: absolute; top: 14px; right: 14px; width: 32px; height: 32px;
            border-radius: 9px; display: flex; align-items: center; justify-content: center;
            border: none; background: #f1f5f9; color: #64748b; cursor: pointer;
            transition: background 0.15s ease, color 0.15s ease;
        }
        .dark .modal-close {
            background: #1e293b;
            color: #94a3b8;
        }
        .modal-close:hover { background: #e2e8f0; color: #0f172a; }
        .dark .modal-close:hover { background: #334155; color: #f8fafc; }
        .modal-icon {
            width: 60px; height: 60px; margin: 0 auto 18px; border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            background: #ecfdf5; color: #059669; font-size: 24px;
        }
        .dark .modal-icon {
            background: rgba(5, 150, 105, 0.15);
            color: #34d399;
        }
        .custom-modal.active .modal-icon { animation: iconBounce 0.35s ease 0.05s both; }
        .modal-title { text-align: center; font-family: 'Inter', sans-serif; font-size: 19px; font-weight: 700; color: #0f172a; }
        .dark .modal-title { color: #f8fafc; }
        .modal-description { text-align: center; font-size: 13.5px; line-height: 1.65; color: #64748b; margin-top: 8px; }
        .dark .modal-description { color: #94a3b8; }
        .modal-description strong { color: #2563eb; font-weight: 700; }
        .dark .modal-description strong { color: #60a5fa; }
        .modal-warning {
            display: flex; align-items: flex-start; gap: 10px; margin-top: 18px; padding: 12px;
            border-radius: 12px; background: #fffbeb; border: 1px solid #fde68a;
            color: #92400e; font-size: 12px; line-height: 1.6;
        }
        .dark .modal-warning {
            background: rgba(245, 158, 11, 0.1);
            border-color: rgba(245, 158, 11, 0.25);
            color: #fcd34d;
        }
        .modal-warning i { color: #f59e0b; font-size: 13px; }
        .modal-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 20px; }
        .modal-btn { height: 44px; border-radius: 11px; font-size: 13px; font-weight: 600; cursor: pointer; transition: background 0.15s ease, opacity 0.15s ease; }
        .modal-cancel { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
        .dark .modal-cancel { background: #1e293b; color: #cbd5e1; border-color: #334155; }
        .modal-cancel:hover { background: #e2e8f0; }
        .dark .modal-cancel:hover { background: #334155; }
        .modal-confirm { border: none; color: white; background: #10b981; }
        .modal-confirm:hover { background: #0d9c6e; }
        .modal-confirm:disabled { opacity: 0.7; cursor: not-allowed; }

        @media (max-width: 480px) {
            .modal-box { padding: 22px 18px; border-radius: 18px; }
            .modal-actions { grid-template-columns: 1fr; }
        }

        @media (prefers-reduced-motion: reduce) {
            .animate-slide, .animate-pop, .status-dot-live, .status-dot-live-amber,
            .reveal-on-scroll, .modal-box, .modal-icon {
                animation: none !important; transition: none !important; opacity: 1 !important; transform: none !important;
            }
        }
    </style>

</head>

<body class="text-slate-800 bg-slate-50 min-h-screen flex dark:bg-slate-950 dark:text-slate-100 transition-colors duration-200">

    {{-- =====================================================
        SIDEBAR
    ====================================================== --}}

    @include('navbar.navigasi')

    {{-- =====================================================
        AREA UTAMA
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
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">


                {{-- =================================================
                    HERO BANNER
                ================================================== --}}

                <div class="animate-slide relative rounded-2xl p-6 md:p-8 mb-6 bg-blue-700">

                    {{-- BREADCRUMB --}}
                    <div class="flex items-center gap-2 text-xs text-blue-100 mb-4">
                        <a href="{{ route('company.dashboard') }}" class="hover:text-white transition-colors">Dashboard</a>
                        <i class="fa-solid fa-chevron-right text-[9px]"></i>
                        <a href="{{ route('company.projects.index') }}" class="hover:text-white transition-colors">Proyek</a>
                        <i class="fa-solid fa-chevron-right text-[9px]"></i>
                        <span class="text-white font-medium">Detail</span>
                    </div>

                    {{-- HEADER --}}
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-12 h-12 shrink-0 rounded-xl bg-white/15 border border-white/20 text-white flex items-center justify-center text-xl icon-pop">
                                <i class="fa-solid fa-folder-open"></i>
                            </div>
                            <div class="min-w-0">
                                <h1 class="font-display text-xl md:text-2xl font-bold text-white tracking-tight truncate">
                                    {{ $project->project_name }}
                                </h1>
                                <p class="text-sm text-blue-100 mt-1">Kelola detail proyek dan penawaran freelancer.</p>
                            </div>
                        </div>

                        <a href="{{ route('company.projects.index') }}"
                           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white rounded-lg text-sm font-semibold text-blue-700 hover:bg-blue-50 transition-colors w-fit shrink-0">
                            <i class="fa-solid fa-arrow-left text-xs"></i>
                            Kembali ke Proyek
                        </a>
                    </div>

                    {{-- RINGKASAN --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-6">
                        @php $status = $project->status ?? 'Draft'; @endphp

                        <div class="bg-white/10 border border-white/15 rounded-xl px-4 py-3">
                            <p class="text-[11px] text-blue-100 uppercase tracking-wider">Status</p>
                            <p class="text-sm font-bold text-white mt-1 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-white status-dot-live"></span>
                                {{ $status }}
                            </p>
                        </div>

                        <div class="bg-white/10 border border-white/15 rounded-xl px-4 py-3">
                            <p class="text-[11px] text-blue-100 uppercase tracking-wider">Budget</p>
                            <p class="text-sm font-bold text-white mt-1">
                                {{ $project->budget ? 'Rp ' . number_format($project->budget, 0, ',', '.') : 'Belum ditentukan' }}
                            </p>
                        </div>

                        <div class="bg-white/10 border border-white/15 rounded-xl px-4 py-3">
                            <p class="text-[11px] text-blue-100 uppercase tracking-wider">Deadline</p>
                            <p class="text-sm font-bold text-white mt-1">
                                {{ $project->deadline ? \Carbon\Carbon::parse($project->deadline)->format('d M Y') : 'Belum ditentukan' }}
                            </p>
                        </div>

                        <div class="bg-white/10 border border-white/15 rounded-xl px-4 py-3">
                            <p class="text-[11px] text-blue-100 uppercase tracking-wider">Penawaran</p>
                            <p class="text-sm font-bold text-white mt-1">{{ $project->penawarans->count() }} Freelancer</p>
                        </div>
                    </div>
                </div>


                {{-- =================================================
                    FLASH MESSAGE
                ================================================== --}}

                @if(session('success'))

                    <div
                        class="
                            mb-6
                            flex
                            items-center
                            gap-3
                            px-4
                            py-3
                            bg-gradient-to-r
                            from-emerald-50
                            to-teal-50
                            dark:from-emerald-950/40
                            dark:to-teal-950/40
                            border
                            border-emerald-200
                            dark:border-emerald-800
                            text-emerald-700
                            dark:text-emerald-300
                            rounded-xl
                            text-sm
                            font-medium
                            shadow-sm
                            animate-pop
                        "
                    >

                        <div
                            class="
                                w-8
                                h-8
                                rounded-full
                                bg-emerald-100
                                dark:bg-emerald-900/60
                                flex
                                items-center
                                justify-center
                                shrink-0
                            "
                        >
                            <i class="fa-solid fa-check text-emerald-600 dark:text-emerald-400"></i>
                        </div>

                        <span>
                            {{ session('success') }}
                        </span>

                    </div>
                @endif

                @if(session('error'))

                    <div
                        class="
                            mb-6
                            flex
                            items-center
                            gap-3
                            px-4
                            py-3
                            bg-gradient-to-r
                            from-red-50
                            to-rose-50
                            dark:from-red-950/40
                            dark:to-rose-950/40
                            border
                            border-red-200
                            dark:border-red-800
                            text-red-700
                            dark:text-red-300
                            rounded-xl
                            text-sm
                            font-medium
                            shadow-sm
                            animate-pop
                        "
                    >

                        <div
                            class="
                                w-8
                                h-8
                                rounded-full
                                bg-red-100
                                dark:bg-red-900/60
                                flex
                                items-center
                                justify-center
                                shrink-0
                            "
                        >
                            <i class="fa-solid fa-xmark text-red-600 dark:text-red-400"></i>
                        </div>

                        <span>
                            {{ session('error') }}
                        </span>

                    </div>
                @endif


                {{-- =================================================
                    INFORMASI PROYEK
                ================================================== --}}

                <div class="bg-white border border-slate-200 dark:border-slate-800 dark:bg-slate-900 rounded-2xl overflow-hidden shadow-sm mb-6 animate-slide delay-1">

                    {{-- HEADER --}}
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 px-6 py-5 border-b border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-lg">
                                <i class="fa-solid fa-folder-open"></i>
                            </div>
                            <div>
                                <h2 class="font-bold text-base text-slate-800 dark:text-white">Informasi Proyek</h2>
                                <p class="text-xs text-slate-400 mt-0.5">Detail informasi proyek yang Anda buat</p>
                            </div>
                        </div>

                        @php $status = $project->status ?? 'Draft'; @endphp
                        @if($status === 'Open')
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 text-xs font-bold w-fit">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Open
                            </span>
                        @elseif($status === 'Closed')
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 text-xs font-bold w-fit">
                                <span class="w-2 h-2 rounded-full bg-red-500"></span> Closed
                            </span>
                        @else
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold w-fit">
                                <span class="w-2 h-2 rounded-full bg-slate-400"></span> {{ $status }}
                            </span>
                        @endif
                    </div>

                    {{-- ISI --}}
                    <div class="p-6 space-y-6">

                        {{-- STATISTIK --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="p-3.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 rounded-xl flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 flex items-center justify-center text-sm shrink-0">
                                    <i class="fa-solid fa-wallet"></i>
                                </div>
                                <div>
                                    <p class="text-[11px] font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Budget Proyek</p>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white mt-0.5">
                                        {{ $project->budget ? 'Rp ' . number_format($project->budget, 0, ',', '.') : 'Belum Ditentukan' }}
                                    </p>
                                </div>
                            </div>

                            <div class="p-3.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 rounded-xl flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 flex items-center justify-center text-sm shrink-0">
                                    <i class="fa-regular fa-calendar-check"></i>
                                </div>
                                <div>
                                    <p class="text-[11px] font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tenggat Waktu</p>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white mt-0.5">
                                        {{ $project->deadline ? \Carbon\Carbon::parse($project->deadline)->format('d M Y') : 'Belum Ditentukan' }}
                                    </p>
                                </div>
                            </div>

                            <div class="p-3.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 rounded-xl flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 flex items-center justify-center text-sm shrink-0">
                                    <i class="fa-solid fa-users"></i>
                                </div>
                                <div>
                                    <p class="text-[11px] font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Penawaran</p>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white mt-0.5">
                                        {{ $project->penawarans->count() }} Freelancer
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- DESKRIPSI --}}
                        <div x-data="{ expanded: false }">
                            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                <i class="fa-solid fa-align-left text-blue-500"></i> Deskripsi Proyek
                            </p>
                            <div class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-800/30 p-4 rounded-xl border border-slate-100 dark:border-slate-800 leading-relaxed">
                                <div :class="expanded ? '' : 'line-clamp-3'">
                                    {!! nl2br(e($project->project_description ?? 'Tidak ada deskripsi proyek.')) !!}
                                </div>
                                @if(strlen($project->project_description ?? '') > 200)
                                    <button @click="expanded = !expanded" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline mt-2 inline-block">
                                        <span x-text="expanded ? 'Sembunyikan' : 'Baca Selengkapnya...'"></span>
                                    </button>
                                @endif
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-3">
                                Kategori: <span class="font-medium text-slate-700 dark:text-slate-300">{{ $project->category->name ?? 'Umum' }}</span>
                            </p>
                        </div>

                        {{-- ACTION BUTTON --}}
                        <div class="flex flex-wrap gap-3 pt-5 border-t border-slate-100 dark:border-slate-800">
                            <a href="{{ route('company.projects.edit', $project) }}"
                               class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors">
                                <i class="fa-solid fa-pen-to-square"></i>
                                Edit Proyek
                            </a>

                            <form method="POST" action="{{ route('company.projects.destroy', $project) }}"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus proyek ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-900/50 rounded-lg text-sm font-semibold hover:bg-red-100 dark:hover:bg-red-900/60 transition-colors">
                                    <i class="fa-solid fa-trash"></i>
                                    Hapus Proyek
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- =========================================================
                    SECTION PENAWARAN FREELANCER
                ========================================================== --}}
                @php
                    $hasAccepted = $project->penawarans->contains(fn($p) => $p->status === 'Diterima');

                    // Statistik harga dari database (bukan disimpan sebagai penawaran baru).
                    $rataRataHarga = $project->penawarans->avg('harga_penawaran');

                    // Siapkan array JSON untuk Alpine.js
                    $penawaranData = $project->penawarans->map(function($p) {
                        $foto = optional($p->freelancer->freelanceProfile)->photo;
                        $photoUrl = $foto ? (Str::startsWith($foto, ['http://', 'https://']) ? $foto : asset('storage/' . $foto)) : null;

                        return [
                            'id' => $p->id,
                            'freelancer_id' => $p->freelancer_id,
                            'nama' => optional($p->freelancer)->name,
                            'foto' => $photoUrl,
                            'harga' => (float) $p->harga_penawaran,
                            'pesan' => $p->pesan ?? '',
                            'status' => $p->status,
                        ];
                    });
                @endphp

                <div
                    x-data="{
                        search: '',
                        statusFilter: 'all',
                        items: @js($penawaranData),
                        get filteredItems() {
                            return this.items.filter(item => {
                                const q = this.search.toLowerCase();
                                const matchSearch = q === '' || (item.nama || '').toLowerCase().includes(q);
                                const matchStatus = this.statusFilter === 'all' || item.status === this.statusFilter;
                                return matchSearch && matchStatus;
                            });
                        }
                    }"
                    class="bg-white border border-slate-200 dark:border-slate-800 dark:bg-slate-900 rounded-2xl overflow-hidden shadow-sm animate-slide delay-2"
                >

                    {{-- HEADER --}}
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 px-6 py-5 border-b border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-lg">
                                <i class="fa-solid fa-users"></i>
                            </div>
                            <div>
                                <h2 class="font-bold text-base text-slate-800 dark:text-white">Penawaran Freelancer</h2>
                                <p class="text-xs text-slate-400 mt-0.5">Lihat dan pilih freelancer yang mengajukan penawaran untuk proyek ini.</p>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full md:w-auto">
                            <div class="inline-flex items-center gap-2 px-3 py-2 bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 rounded-lg text-xs font-semibold w-fit">
                                <i class="fa-solid fa-users"></i>
                                <span x-text="filteredItems.length"></span> / {{ $project->penawarans->count() }} Penawaran
                            </div>
                            @if($project->penawarans->isNotEmpty())
                                <div class="inline-flex items-center gap-2 px-3 py-2 bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 rounded-lg text-xs font-semibold w-fit">
                                    <i class="fa-solid fa-chart-simple"></i>
                                    Rata-rata: Rp {{ number_format($rataRataHarga, 0, ',', '.') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($project->penawarans->isNotEmpty())
                        {{-- FILTER & PENCARIAN --}}
                        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/30 flex flex-col sm:flex-row gap-3">

                            <div class="relative flex-1">
                                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                               {{-- Dummy Input untuk Menjebak Autofill Chrome --}}
<input type="text" name="fake_username" style="display:none !important;" tabindex="-1" aria-hidden="true">
<input type="password" name="fake_password" style="display:none !important;" tabindex="-1" aria-hidden="true">

{{-- Kolom Pencarian Asli --}}
<div class="relative flex-1">
    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
    <input
        type="search"
        name="freelancer_search_field"
        id="freelancer_search_field"
        autocomplete="new-password"
        x-model="search"
        placeholder="Cari nama freelancer..."
        class="w-full pl-9 pr-3 py-2.5 text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 text-slate-700 dark:text-slate-200 placeholder:text-slate-400"
    >
</div>
                            </div>

                            <div class="flex items-center gap-2 overflow-x-auto">
                                <button type="button" @click="statusFilter = 'all'"
                                        :class="statusFilter === 'all' ? 'bg-blue-600 text-white' : 'bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700'"
                                        class="px-3 py-2 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors">
                                    Semua
                                </button>
                                <button type="button" @click="statusFilter = 'Menunggu'"
                                        :class="statusFilter === 'Menunggu' ? 'bg-blue-600 text-white' : 'bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700'"
                                        class="px-3 py-2 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors">
                                    Menunggu
                                </button>
                                <button type="button" @click="statusFilter = 'Diterima'"
                                        :class="statusFilter === 'Diterima' ? 'bg-blue-600 text-white' : 'bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700'"
                                        class="px-3 py-2 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors">
                                    Diterima
                                </button>
                                <button type="button" @click="statusFilter = 'Ditolak'"
                                        :class="statusFilter === 'Ditolak' ? 'bg-blue-600 text-white' : 'bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700'"
                                        class="px-3 py-2 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors">
                                    Ditolak
                                </button>

                                <span class="w-px h-6 bg-slate-200 dark:bg-slate-700 mx-1 shrink-0"></span>

                                <a href="{{ route('company.projects.show', $project) }}"
                                   :class="priceSort === 'default' ? 'bg-blue-600 text-white' : 'bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700'"
                                   class="px-3 py-2 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors">
                                    Terbaru
                                </a>
                                <a href="{{ route('company.projects.show', array_merge([$project], ['sort' => 'harga_tertinggi'])) }}"
                                   :class="'{{ request('sort') }}' === 'harga_tertinggi' ? 'bg-blue-600 text-white' : 'bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700'"
                                   class="px-3 py-2 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors">
                                    Harga Tertinggi
                                </a>
                                <a href="{{ route('company.projects.show', array_merge([$project], ['sort' => 'harga_terendah'])) }}"
                                   :class="'{{ request('sort') }}' === 'harga_terendah' ? 'bg-blue-600 text-white' : 'bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700'"
                                   class="px-3 py-2 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors">
                                    Harga Terendah
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- ISI --}}
                    <div class="p-6">

                        @if($project->penawarans->isEmpty())

                            {{-- EMPTY STATE --}}
                            <div
                                class="
                                    relative
                                    overflow-hidden
                                    py-16
                                    text-center
                                    rounded-2xl
                                    bg-gradient-to-br
                                    from-blue-50
                                    via-sky-50
                                    to-indigo-50
                                    dark:from-slate-800/60
                                    dark:via-slate-800/40
                                    dark:to-slate-800/20
                                "
                            >
                                <div
                                    class="
                                        w-16
                                        h-16
                                        mx-auto
                                        mb-5
                                        bg-white
                                        dark:bg-slate-800
                                        shadow-sm
                                        rounded-2xl
                                        flex
                                        items-center
                                        justify-center
                                    "
                                >
                                    <i
                                        class="
                                            fa-regular
                                            fa-file-lines
                                            text-2xl
                                            text-blue-600
                                            dark:text-blue-400
                                        "
                                    ></i>
                                </div>

                                <h3 class="text-base font-bold text-slate-700 dark:text-slate-200">
                                    Belum Ada Penawaran
                                </h3>

                                <p class="text-sm text-slate-400 mt-2">
                                    Penawaran dari freelancer akan muncul di sini.
                                </p>
                            </div>

                        @else
                            {{-- Empty state jika hasil filter kosong --}}
                            <template x-if="filteredItems.length === 0">
                                <div class="py-12 text-center">
                                    <p class="text-xs font-semibold text-slate-400">Tidak ada penawaran yang cocok dengan filter atau pencarian Anda.</p>
                                </div>
                            </template>

                            <div class="space-y-4">

                                @foreach($project->penawarans as $penawaran)

                                    @php
                                        $accentClass = match($penawaran->status) {
                                            'Diterima' => 'border-l-emerald-500',
                                            'Menunggu' => 'border-l-amber-400',
                                            default => 'border-l-red-400',
                                        };
                                    @endphp

                                    <div
                                        x-data="{ open: false }"
                                        x-show="filteredItems.some(i => i.id === {{ $penawaran->id }})"
                                        class="
                                            reveal-on-scroll
                                            reveal-fallback
                                            border
                                            border-slate-200
                                            dark:border-slate-800
                                            border-l-4
                                            {{ $accentClass }}
                                            rounded-xl
                                            bg-white
                                            dark:bg-slate-900
                                            lift-card
                                            overflow-hidden
                                        "
                                    >

                                        {{-- BARIS RINGKAS (SELALU TAMPIL) --}}
                                        {{-- Klik pada tombol chat negosiasi tidak men-toggle dropdown --}}
                                        <div
                                            @click="if (! $event.target.closest('[data-negosiasi-row-chat]')) open = !open"
                                            @keydown.enter="open = !open"
                                            role="button"
                                            tabindex="0"
                                            class="w-full flex flex-wrap items-center gap-4 p-4 text-left cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors"
                                        >

                                            @php
                                                $fotoFreelancer = optional($penawaran->freelancer->freelanceProfile)->photo;
                                                $profileUrl = $penawaran->freelancer ? route('company.freelancer.profile', $penawaran->freelancer->id) : '#';
                                            @endphp

                                            <a href="{{ $profileUrl }}" @click.stop class="flex items-center gap-3 min-w-0 mr-auto hover:opacity-80 transition-opacity">

                                                {{-- AVATAR --}}
                                                @if($penawaran->freelancer && $fotoFreelancer)
                                                    <img
                                                        src="{{ Str::startsWith($fotoFreelancer, ['http://', 'https://']) ? $fotoFreelancer : asset('storage/' . $fotoFreelancer) }}"
                                                        alt="{{ $penawaran->freelancer->name }}"
                                                        class="w-10 h-10 rounded-full object-cover shrink-0"
                                                    >
                                                @else
                                                    <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm shrink-0">
                                                        {{ strtoupper(substr($penawaran->freelancer->name ?? 'F', 0, 1)) }}
                                                    </div>
                                                @endif

                                                {{-- NAMA --}}
                                                <div class="min-w-0">
                                                    <p data-freelancer-name class="font-bold text-sm text-slate-800 dark:text-slate-100 truncate">
                                                        {{ $penawaran->freelancer->name ?? 'Tidak diketahui' }}
                                                    </p>
                                                    <p class="text-xs text-slate-400">Freelancer</p>
                                                </div>
                                            </a>

                                            {{-- HARGA --}}
                                            <div class="text-right shrink-0">
                                                <p class="text-[11px] text-slate-400">Harga Penawaran</p>
                                                <p class="text-sm font-bold text-slate-800 dark:text-slate-100">
                                                    Rp {{ number_format($penawaran->harga_penawaran, 0, ',', '.') }}
                                                </p>
                                            </div>

                                            {{-- CHAT NEGOSIASI + BADGE UNREAD (terlihat tanpa membuka dropdown) --}}
                                            @php
                                                $negoCount = (int) ($negoUnread[$penawaran->id] ?? 0);
                                            @endphp
                                            <button type="button"
                                                data-negosiasi-row-chat="1"
                                                data-negosiasi-open="{{ $penawaran->id }}"
                                                data-project-title="{{ $project->project_name }}"
                                                data-peer-name="{{ $penawaran->freelancer->name ?? 'Freelancer' }}"
                                                data-peer-type="freelancer"
                                                class="relative shrink-0 w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/40 border border-blue-100 dark:border-blue-900/50 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/60 flex items-center justify-center transition-colors"
                                                aria-label="Chat negosiasi dengan {{ $penawaran->freelancer->name ?? 'Freelancer' }}">
                                                <i class="fa-regular fa-comment-dots"></i>
                                                @if($negoCount > 0)
                                                    <span data-nego-unread="{{ $penawaran->id }}"
                                                        class="absolute -top-1.5 -right-1.5 inline-flex items-center justify-center min-w-[1.15rem] h-[1.15rem] px-1 rounded-full bg-red-500 text-white text-[10px] font-extrabold leading-none border-2 border-white dark:border-slate-900 shadow-sm">
                                                        {{ $negoCount > 9 ? '9+' : $negoCount }}
                                                    </span>
                                                @endif
                                            </button>

                                            {{-- STATUS --}}
                                            <div class="shrink-0">
                                                @if($penawaran->status === 'Menunggu')
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 border border-amber-100 dark:border-amber-900/50 text-[11px] font-bold">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 status-dot-live-amber"></span>
                                                        Menunggu
                                                    </span>
                                                @elseif($penawaran->status === 'Diterima')
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/50 text-[11px] font-bold">
                                                        <i class="fa-solid fa-check"></i> Terpilih
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-900/50 text-[11px] font-bold">
                                                        <i class="fa-solid fa-xmark"></i> Ditolak
                                                    </span>
                                                @endif
                                            </div>

                                            {{-- CHEVRON --}}
                                            <i class="fa-solid fa-chevron-down text-slate-400 text-xs shrink-0 transition-transform" :class="open ? 'rotate-180' : ''"></i>

                                        </div>

                                        {{-- DETAIL (EXPAND) --}}
                                        <div x-show="open" x-transition class="px-5 pb-5 pt-1 border-t border-slate-100 dark:border-slate-800">

                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-4">
                                                <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 rounded-lg p-3.5">
                                                    <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold">Harga Penawaran</p>
                                                    <p class="text-sm font-bold text-slate-800 dark:text-slate-100 mt-1">
                                                        Rp {{ number_format($penawaran->harga_penawaran, 0, ',', '.') }}
                                                    </p>
                                                </div>
                                                <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 rounded-lg p-3.5">
                                                    <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold">Estimasi Pengerjaan</p>
                                                    <p class="text-sm font-bold text-slate-800 dark:text-slate-100 mt-1">{{ $penawaran->estimasi_hari }} Hari</p>
                                                </div>
                                                <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 rounded-lg p-3.5">
                                                    <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold">Waktu Dipilih</p>
                                                    <p class="text-sm font-bold text-slate-800 dark:text-slate-100 mt-1">
                                                        @if($penawaran->selected_at)
                                                            {{ $penawaran->selected_at->format('d M Y H:i') }}
                                                        @else
                                                            Belum dipilih
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>

                                            @if($penawaran->pesan)
                                                <div class="mt-3">
                                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Pesan Freelancer</p>
                                                    <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 border-l-4 border-l-blue-300 dark:border-l-blue-500 rounded-lg p-3.5">
                                                        <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">{{ $penawaran->pesan }}</p>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mt-4 pt-4 border-t border-slate-100 dark:border-slate-800">

                                                <div>
                                                    @if($penawaran->proposal)
                                                        <a href="{{ asset('storage/' . $penawaran->proposal) }}" target="_blank"
                                                           class="inline-flex items-center gap-2 px-3 py-2 border border-blue-200 dark:border-blue-900/50 text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/40 rounded-lg text-xs font-semibold hover:bg-blue-100 dark:hover:bg-blue-900/60 transition-colors">
                                                            <i class="fa-regular fa-file-lines"></i> Lihat Proposal
                                                        </a>
                                                    @else
                                                        <span class="text-xs text-slate-400">Tidak ada file proposal</span>
                                                    @endif
                                                </div>

                                                <div class="flex flex-wrap items-center gap-2">

                                                    <button type="button"
                                                        data-negosiasi-open="{{ $penawaran->id }}"
                                                        data-project-title="{{ $project->project_name }}"
                                                        data-peer-name="{{ $penawaran->freelancer->name ?? 'Freelancer' }}"
                                                        data-peer-type="freelancer"
                                                        class="inline-flex items-center gap-2 px-4 py-2 border border-blue-200 dark:border-blue-900/50 text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/40 rounded-lg text-xs font-bold hover:bg-blue-100 dark:hover:bg-blue-900/60 transition-colors">
                                                        <i class="fa-regular fa-comments"></i> Negosiasi
                                                        @if(($negoUnread[$penawaran->id] ?? 0) > 0)
                                                            <span data-nego-unread="{{ $penawaran->id }}"
                                                                class="inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1.5 rounded-full bg-red-500 text-white text-[10px] font-extrabold leading-none shadow-sm">
                                                                {{ ($negoUnread[$penawaran->id] > 9) ? '9+' : $negoUnread[$penawaran->id] }}
                                                            </span>
                                                        @endif
                                                    </button>

                                                    <a href="{{ route('company.reports.create', ['penawaran_id' => $penawaran->id]) }}"
                                                       class="inline-flex items-center gap-2 px-3 py-2 border border-red-200 dark:border-red-900/50 text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-950/40 rounded-lg text-xs font-semibold hover:bg-red-100 dark:hover:bg-red-900/60 transition-colors"
                                                       title="Laporkan penawaran dari {{ $penawaran->freelancer->name ?? 'freelancer ini' }}">
                                                        <i class="fa-solid fa-flag"></i> Lapor
                                                    </a>

                                                    @if($penawaran->status === 'Menunggu' && !$hasAccepted)
                                                        <form method="POST" action="{{ route('company.projects.penawaran.select', [$project, $penawaran]) }}" class="select-freelancer-form">
                                                            @csrf
                                                            <button type="button" onclick="openSelectModal(this)"
                                                                    class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 text-white rounded-lg text-xs font-bold hover:bg-emerald-600 transition-colors">
                                                                <i class="fa-solid fa-check"></i> Pilih Freelancer
                                                            </button>
                                                        </form>
                                                    @elseif($penawaran->status === 'Diterima')
                                                        @if($project->workspace)
                                                            <a href="{{ route('company.workspaces.show', $project->workspace) }}"
                                                               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg text-xs font-bold hover:bg-blue-700 transition-colors">
                                                                <i class="fa-solid fa-external-link-alt"></i> Buka Workspace
                                                            </a>
                                                        @endif
                                                    @else
                                                        <span class="text-xs text-slate-400">Penawaran tidak tersedia</span>
                                                    @endif

                                                </div>
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


    {{-- ==========================================================
        MODAL KONFIRMASI PILIH FREELANCER
    ========================================================== --}}

    <div
        id="selectFreelancerModal"
        class="custom-modal"
        aria-hidden="true"
    >

        <div
            class="modal-box"
            role="dialog"
            aria-modal="true"
            aria-labelledby="modalTitle"
        >

            {{-- CLOSE --}}
            <button
                type="button"
                onclick="closeSelectModal()"
                class="modal-close"
                aria-label="Tutup"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>

            {{-- ICON --}}
            <div class="modal-icon">
                <i class="fa-solid fa-user-check"></i>
            </div>

            {{-- TITLE --}}
            <h3
                id="modalTitle"
                class="modal-title"
            >
                Pilih Freelancer?
            </h3>

            {{-- DESCRIPTION --}}
            <p class="modal-description">
                Apakah kamu yakin ingin memilih
                <strong id="selectedFreelancerName">
                    freelancer ini
                </strong>
                sebagai freelancer untuk proyek ini?
            </p>

            {{-- WARNING --}}
            <div class="modal-warning">
                <i class="fa-solid fa-triangle-exclamation mt-0.5"></i>
                <span>
                    Setelah freelancer dipilih,
                    penawaran freelancer lain akan
                    otomatis ditolak.
                </span>
            </div>

            {{-- ACTION --}}
            <div class="modal-actions">

                {{-- BATAL --}}
                <button
                    type="button"
                    onclick="closeSelectModal()"
                    class="
                        modal-btn
                        modal-cancel
                    "
                >
                    Batal
                </button>

                {{-- KONFIRMASI --}}
                <button
                    type="button"
                    onclick="confirmSelectFreelancer()"
                    class="
                        modal-btn
                        modal-confirm
                    "
                >
                    <i class="fa-solid fa-check mr-1"></i>
                    Ya, Pilih
                </button>

            </div>

        </div>

    </div>


    {{-- ==========================================================
        SCRIPT
    ========================================================== --}}

    <script>

        /* ==========================================================
           SCROLL REVEAL
        ========================================================== */

        document.addEventListener(
            'DOMContentLoaded',
            function () {

                var items =
                    document.querySelectorAll(
                        '.reveal-on-scroll'
                    );

                if (!items.length) {
                    return;
                }

                if (!('IntersectionObserver' in window)) {
                    items.forEach(
                        function (el) {
                            el.classList.add(
                                'revealed'
                            );
                        }
                    );
                    return;
                }

                var observer =
                    new IntersectionObserver(
                        function (entries) {
                            entries.forEach(
                                function (entry) {
                                    if (
                                        entry.isIntersecting
                                    ) {
                                        entry.target.classList.add(
                                            'revealed'
                                        );
                                        observer.unobserve(
                                            entry.target
                                        );
                                    }
                                }
                            );
                        },
                        {
                            threshold: 0.1,
                            rootMargin:
                                '0px 0px -40px 0px'
                        }
                    );

                items.forEach(
                    function (el, index) {
                        el.style.transitionDelay =
                            Math.min(
                                index * 60,
                                300
                            ) + 'ms';
                        observer.observe(el);
                    }
                );

            }
        );


        /* ==========================================================
           CUSTOM SELECT FREELANCER MODAL
        ========================================================== */

        let selectedFreelancerForm = null;

        function openSelectModal(button) {

            selectedFreelancerForm =
                button.closest(
                    '.select-freelancer-form'
                );

            const card =
                button.closest(
                    '.reveal-on-scroll'
                );

            let freelancerName =
                'freelancer ini';

            if (card) {
                const nameElement =
                    card.querySelector(
                        '[data-freelancer-name]'
                    );

                if (nameElement) {
                    freelancerName =
                        nameElement
                            .innerText
                            .trim();
                }
            }

            document
                .getElementById(
                    'selectedFreelancerName'
                )
                .textContent =
                freelancerName;

            const modal =
                document.getElementById(
                    'selectFreelancerModal'
                );

            modal.classList.add(
                'active'
            );

            modal.setAttribute(
                'aria-hidden',
                'false'
            );

            document.body.style.overflow =
                'hidden';

        }

        function closeSelectModal() {

            const modal =
                document.getElementById(
                    'selectFreelancerModal'
                );

            modal.classList.remove(
                'active'
            );

            modal.setAttribute(
                'aria-hidden',
                'true'
            );

            document.body.style.overflow =
                '';

            selectedFreelancerForm =
                null;

            const confirmButton =
                document.querySelector(
                    '.modal-confirm'
                );

            if (confirmButton) {
                confirmButton.disabled =
                    false;

                confirmButton.innerHTML = `
                    <i class="fa-solid fa-check mr-1"></i>
                    Ya, Pilih
                `;
            }

        }

        function confirmSelectFreelancer() {

            if (!selectedFreelancerForm) {
                return;
            }

            const confirmButton =
                document.querySelector(
                    '.modal-confirm'
                );

            confirmButton.innerHTML = `
                <i class="fa-solid fa-spinner fa-spin mr-1"></i>
                Memproses...
            `;

            confirmButton.disabled =
                true;

            selectedFreelancerForm.submit();

        }


        /* ==========================================================
           KLIK BACKDROP
        ========================================================== */

        document
            .getElementById(
                'selectFreelancerModal'
            )
            .addEventListener(
                'click',
                function (e) {
                    if (
                        e.target === this
                    ) {
                        closeSelectModal();
                    }
                }
            );


        /* ==========================================================
           ESC KEY
        ========================================================== */

        document.addEventListener(
            'keydown',
            function (e) {
                if (
                    e.key === 'Escape'
                ) {
                    const modal =
                        document.getElementById(
                            'selectFreelancerModal'
                        );

                    if (
                        modal.classList.contains(
                            'active'
                        )
                    ) {
                        closeSelectModal();
                    }
                }
            }
        );

    </script>

    {{-- Modal Negosiasi Chat --}}
    @include('negotiations.modal')

</body>
</html>