<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.theme-boot')
    
    <title>Buat Proyek Baru - ApexForge Labs</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = tailwind.config || {};
        tailwind.config.darkMode = 'class';
    </script>

    {{-- Midtrans Snap SDK --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* High-Tech Scrollbar (Blue/White Theme) */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(59, 130, 246, 0.2); border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(59, 130, 246, 0.5); }

        /* Pure Blue Styling */
        .hologram-grid-blue {
            background-image: 
                linear-gradient(to right, rgba(59, 130, 246, 0.04) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(59, 130, 246, 0.04) 1px, transparent 1px);
            background-size: 32px 32px;
        }

        .btn-shimmer { position: relative; overflow: hidden; isolation: isolate; }
        .btn-shimmer::after {
            content: ''; position: absolute; top: 0; left: -75%;
            width: 50%; height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255,255,255,.4), transparent);
            transform: skewX(-20deg); transition: left .65s ease;
        }
        .btn-shimmer:hover::after { left: 125%; }

        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(59, 130, 246, 0.1);
            box-shadow: 0 20px 50px -10px rgba(30, 58, 138, 0.1);
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

/* FIX DARK MODE OVERRIDES FOR INPUTS & FILE PICKER */
.dark input, .dark select, .dark textarea {
    background: rgba(30, 41, 59, 0.7) !important;
    border-color: rgba(51, 65, 85, 0.8) !important;
    color: #ffffff !important;
}
.dark input[type="file"] {
    background: transparent !important;
    color: #94a3b8 !important;
}
.dark select option {
    background-color: #0f172a !important;
    color: #ffffff !important;
}
.dark input[type="date"] {
    color-scheme: dark;
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
<body class="bg-white text-blue-950 min-h-screen flex relative antialiased dark:bg-slate-900 dark:text-white transition-colors duration-300">

    {{-- Ambient Background Glows --}}
    <div class="fixed inset-0 pointer-events-none hologram-grid-blue z-0"></div>
    <div class="fixed top-[-20%] right-[-10%] w-[50rem] h-[50rem] bg-gradient-to-bl from-blue-100/40 to-transparent rounded-full blur-[100px] pointer-events-none z-0"></div>

    {{-- TOAST NOTIFICATION CONTAINER (Modern Floating Notifications) --}}
    <div id="toast-container" class="fixed top-5 right-5 z-50 flex flex-col gap-3 pointer-events-none"></div>

    {{-- SIDEBAR --}}
    <div class="relative z-10 flex">
        @include('navbar.navigasi')
    </div>

    {{-- AREA KANAN --}}
    <div class="flex-1 flex flex-col min-h-screen overflow-hidden relative z-10">

        {{-- NAVBAR --}}
        @include('navbar.nav')

        {{-- KONTEN --}}
        <main class="flex-1 overflow-y-auto p-6">
            <div class="max-w-4xl mx-auto space-y-6">

                {{-- BREADCRUMB --}}
                <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-blue-300 dark:text-slate-400">
                    <a href="{{ route('company.dashboard') }}" class="hover:text-blue-600 transition-colors dark:hover:text-blue-400">Dashboard</a>
                    <i class="fa-solid fa-chevron-right text-[9px] text-blue-200"></i>
                    <a href="{{ route('company.projects.index') }}" class="hover:text-blue-600 transition-colors dark:hover:text-blue-400">Proyek</a>
                    <i class="fa-solid fa-chevron-right text-[9px] text-blue-200"></i>
                    <span class="text-blue-600 dark:text-blue-400">Buat Proyek</span>
                </nav>

                {{-- HEADER --}}
                <div>
                    <h1 class="text-3xl font-black text-blue-950 tracking-tight dark:text-white">Buat Proyek Baru</h1>
                    <p class="text-sm font-semibold text-blue-400 mt-1 dark:text-slate-400">Jelaskan kebutuhan proyek Anda dan temukan freelancer terbaik untuk membantu mewujudkannya.</p>
                </div>

                {{-- FORM CARD --}}
                <div class="glass-card rounded-3xl relative overflow-hidden dark:bg-slate-900/90 dark:border-slate-800">
                    
                    {{-- Decorative top line --}}
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 via-blue-600 to-blue-400"></div>

                    <form method="POST" action="{{ route('company.projects.store') }}" enctype="multipart/form-data" class="p-6 lg:p-8">
                        @csrf

                        {{-- VALIDATION ERRORS (Global) --}}
                        @if ($errors->any())
                            <div class="mb-8 overflow-hidden relative bg-white border-2 border-blue-600 p-5 rounded-2xl shadow-[0_0_20px_rgba(59,130,246,0.15)] flex items-start gap-4 dark:bg-slate-900 dark:border-blue-500">
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 border border-blue-200 flex items-center justify-center shrink-0 dark:bg-slate-800 dark:text-blue-400 dark:border-slate-700">
                                    <i class="fa-solid fa-exclamation text-sm"></i>
                                </div>
                                <div>
                                    <div class="font-black text-blue-950 text-sm mb-1 dark:text-white">Mohon perbaiki kesalahan berikut:</div>
                                    <ul class="list-none space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li class="text-xs font-semibold text-blue-700 flex items-center gap-2 dark:text-blue-400">
                                                <i class="fa-solid fa-angle-right text-[10px] text-blue-400 dark:text-slate-400"></i> {{ $error }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        {{-- SECTION 1: INFORMASI PROYEK --}}
                        <div class="mb-8">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center shrink-0 shadow-sm dark:bg-slate-800 dark:text-blue-400 dark:border-slate-800">
                                    <i class="fa-solid fa-clipboard-list"></i>
                                </div>
                                <div>
                                    <h2 class="text-sm font-black text-blue-950 tracking-tight dark:text-white">Informasi Proyek</h2>
                                    <p class="text-[10px] font-bold text-blue-400 uppercase tracking-widest mt-0.5 dark:text-slate-400">Lengkapi detail dasar proyek Anda</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                {{-- Nama Proyek --}}
                                <div class="lg:col-span-2">
                                    <label class="block text-[10px] font-black text-blue-500 uppercase tracking-widest mb-2 dark:text-blue-400">Nama Proyek <span class="text-blue-500">*</span></label>
                                    <input type="text" name="project_name" value="{{ old('project_name') }}"
                                        class="w-full px-5 py-3.5 bg-blue-50/50 border @error('project_name') border-blue-500 ring-2 ring-blue-500/20 @else border-blue-100 @enderror rounded-xl text-sm font-bold text-blue-950 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 focus:bg-white transition-all placeholder:text-blue-300 placeholder:font-medium dark:bg-slate-800 dark:border-slate-700 dark:text-white dark:focus:bg-slate-800 dark:placeholder:text-slate-500"
                                        placeholder="Contoh: Pengembangan Website E-commerce" required>
                                    <p class="text-[10px] font-bold text-blue-400 mt-2 dark:text-slate-400">Berikan nama yang singkat dan jelas untuk proyek Anda.</p>
                                    @error('project_name')
                                        <p class="text-[10px] font-bold tracking-wide text-blue-600 mt-1.5 dark:text-blue-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Kategori --}}
                                <div>
                                    <label class="block text-[10px] font-black text-blue-500 uppercase tracking-widest mb-2 dark:text-blue-400">Kategori</label>
                                    <select name="category_id"
                                        class="w-full px-5 py-3.5 bg-blue-50/50 border @error('category_id') border-blue-500 ring-2 ring-blue-500/20 @else border-blue-100 @enderror rounded-xl text-sm font-bold text-blue-950 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 focus:bg-white transition-all cursor-pointer dark:bg-slate-800 dark:border-slate-700 dark:text-white dark:focus:bg-slate-800">
                                        <option value="">Pilih Kategori</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <p class="text-[10px] font-bold tracking-wide text-blue-600 mt-1.5 dark:text-blue-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Skills --}}
                                <div>
                                    <label class="block text-[10px] font-black text-blue-500 uppercase tracking-widest mb-2 dark:text-blue-400">Skill yang Dibutuhkan <span class="text-blue-500">*</span></label>
                                    <input type="text" name="skills" value="{{ old('skills') }}"
                                        class="w-full px-5 py-3.5 bg-blue-50/50 border @error('skills') border-blue-500 ring-2 ring-blue-500/20 @else border-blue-100 @enderror rounded-xl text-sm font-bold text-blue-950 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 focus:bg-white transition-all placeholder:text-blue-300 placeholder:font-medium dark:bg-slate-800 dark:border-slate-700 dark:text-white dark:focus:bg-slate-800 dark:placeholder:text-slate-500"
                                        placeholder="Laravel, Bootstrap, MySQL">
                                    <p class="text-[10px] font-bold text-blue-400 mt-2 dark:text-slate-400">Pisahkan dengan koma.</p>
                                    @error('skills')
                                        <p class="text-[10px] font-bold tracking-wide text-blue-600 mt-1.5 dark:text-blue-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Deskripsi --}}
                                <div class="lg:col-span-2">
                                    <label class="block text-[10px] font-black text-blue-500 uppercase tracking-widest mb-2 dark:text-blue-400">Deskripsi Proyek <span class="text-blue-500">*</span></label>
                                    <textarea name="project_description" rows="6"
                                        class="w-full px-5 py-4 bg-blue-50/50 border @error('project_description') border-blue-500 ring-2 ring-blue-500/20 @else border-blue-100 @enderror rounded-xl text-sm font-medium text-blue-950 leading-relaxed focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 focus:bg-white transition-all placeholder:text-blue-300 resize-y dark:bg-slate-800 dark:border-slate-700 dark:text-white dark:focus:bg-slate-800 dark:placeholder:text-slate-500"
                                        placeholder="Jelaskan kebutuhan, tujuan, dan hasil yang Anda harapkan dari freelancer.">{{ old('project_description') }}</textarea>
                                    @error('project_description')
                                        <p class="text-[10px] font-bold tracking-wide text-blue-600 mt-1.5 dark:text-blue-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- DIVIDER --}}
                        <div class="h-px w-full bg-gradient-to-r from-transparent via-blue-100 dark:via-slate-800 to-transparent mb-8"></div>

                        {{-- SECTION 2: ANGGARAN & WAKTU --}}
                        <div class="mb-8">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center shrink-0 shadow-sm dark:bg-slate-800 dark:text-blue-400 dark:border-slate-800">
                                    <i class="fa-solid fa-coins"></i>
                                </div>
                                <div>
                                    <h2 class="text-sm font-black text-blue-950 tracking-tight dark:text-white">Anggaran & Waktu</h2>
                                    <p class="text-[10px] font-bold text-blue-400 uppercase tracking-widest mt-0.5 dark:text-slate-400">Tentukan budget dan batas waktu pengerjaan</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                {{-- Budget --}}
                                <div>
                                    <label class="block text-[10px] font-black text-blue-500 uppercase tracking-widest mb-2 dark:text-blue-400">Budget (Rp) <span class="text-blue-500">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-5 pointer-events-none">
                                            <span class="text-blue-400 font-bold text-sm dark:text-slate-400">Rp</span>
                                        </div>
                                        <input type="hidden" name="budget" id="real_budget" value="{{ old('budget') }}">
                                        <input type="text" id="display_budget"
                                            class="w-full pl-12 pr-5 py-3.5 bg-blue-50/50 border @error('budget') border-blue-500 ring-2 ring-blue-500/20 @else border-blue-100 @enderror rounded-xl text-sm font-bold text-blue-950 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 focus:bg-white transition-all placeholder:text-blue-300 placeholder:font-medium dark:bg-slate-800 dark:border-slate-700 dark:text-white dark:focus:bg-slate-800 dark:placeholder:text-slate-500"
                                            placeholder="5000000" required>
                                    </div>
                                    @error('budget')
                                        <p class="text-[10px] font-bold tracking-wide text-blue-600 mt-1.5 dark:text-blue-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Deadline --}}
                                <div>
                                    <label class="block text-[10px] font-black text-blue-500 uppercase tracking-widest mb-2 dark:text-blue-400">Deadline <span class="text-blue-500">*</span></label>
                                    <input type="date" name="deadline" value="{{ old('deadline') }}" min="{{ date('Y-m-d') }}"
                                        class="w-full px-5 py-3.5 bg-blue-50/50 border @error('deadline') border-blue-500 ring-2 ring-blue-500/20 @else border-blue-100 @enderror rounded-xl text-sm font-bold text-blue-950 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 focus:bg-white transition-all cursor-pointer dark:bg-slate-800 dark:border-slate-700 dark:text-white dark:focus:bg-slate-800" required>
                                    @error('deadline')
                                        <p class="text-[10px] font-bold tracking-wide text-blue-600 mt-1.5 dark:text-blue-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- DIVIDER --}}
                        <div class="h-px w-full bg-gradient-to-r from-transparent via-blue-100 dark:via-slate-800 to-transparent mb-8"></div>

                        {{-- SECTION 3: DETAIL TAMBAHAN --}}
                        <div class="mb-8">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center shrink-0 shadow-sm dark:bg-slate-800 dark:text-blue-400 dark:border-slate-800">
                                    <i class="fa-solid fa-paperclip"></i>
                                </div>
                                <div>
                                    <h2 class="text-sm font-black text-blue-950 tracking-tight dark:text-white">Detail Tambahan</h2>
                                    <p class="text-[10px] font-bold text-blue-400 uppercase tracking-widest mt-0.5 dark:text-slate-400">Lampirkan file pendukung jika diperlukan</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                {{-- Gambar --}}
                                <div>
                                    <label class="block text-[10px] font-black text-blue-500 uppercase tracking-widest mb-2 dark:text-blue-400">Gambar Proyek</label>
                                    <div class="relative w-full border-2 border-dashed @error('image') border-blue-400 @else border-blue-200 dark:border-slate-700 @enderror rounded-xl bg-blue-50/30 hover:bg-blue-50/80 hover:border-blue-400 transition-colors duration-300 dark:bg-slate-800/40 dark:hover:bg-slate-800/70 dark:hover:border-slate-600">
                                        <input type="file" name="image" accept="image/*" id="imageInput"
                                            class="w-full px-5 py-4 text-sm cursor-pointer text-slate-600 dark:text-slate-300 file:cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-wider file:bg-blue-600 file:text-white hover:file:bg-blue-700 file:transition-colors focus:outline-none">
                                    </div>
                                    <p class="text-[10px] font-bold text-blue-400 mt-2 dark:text-slate-400">Format: JPG, PNG, WebP. Max 2MB.</p>
                                    @error('image')
                                        <p class="text-[10px] font-bold tracking-wide text-blue-600 mt-1.5 dark:text-blue-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Lampiran --}}
                                <div>
                                    <label class="block text-[10px] font-black text-blue-500 uppercase tracking-widest mb-2 dark:text-blue-400">Lampiran (PDF/DOC)</label>
                                    <div class="relative w-full border-2 border-dashed @error('attachment') border-blue-400 @else border-blue-200 dark:border-slate-700 @enderror rounded-xl bg-blue-50/30 hover:bg-blue-50/80 hover:border-blue-400 transition-colors duration-300 dark:bg-slate-800/40 dark:hover:bg-slate-800/70 dark:hover:border-slate-600">
                                        <input type="file" name="attachment" accept=".pdf,.doc,.docx" id="attachmentInput"
                                            class="w-full px-5 py-4 text-sm cursor-pointer text-slate-600 dark:text-slate-300 file:cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-wider file:bg-blue-600 file:text-white hover:file:bg-blue-700 file:transition-colors focus:outline-none">
                                    </div>
                                    <p class="text-[10px] font-bold text-blue-400 mt-2 dark:text-slate-400">Format: PDF, DOC, DOCX. Max 10MB.</p>
                                    @error('attachment')
                                        <p class="text-[10px] font-bold tracking-wide text-blue-600 mt-1.5 dark:text-blue-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- SECTION 3.5: TAHAP PENGERJAAN (REVISI) --}}
                        <div class="mb-8">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center shrink-0 shadow-sm dark:bg-slate-800 dark:text-blue-400 dark:border-slate-800">
                                    <i class="fa-solid fa-list-check"></i>
                                </div>
                                <div>
                                    <h2 class="text-sm font-black text-blue-950 tracking-tight dark:text-white">Tahap Pengerjaan</h2>
                                    <p class="text-[10px] font-bold text-blue-400 uppercase tracking-widest mt-0.5 dark:text-slate-400">Susun alur pengerjaan proyek Anda sebelum dipublikasikan</p>
                                </div>
                            </div>

                            <div class="bg-blue-50/40 border border-blue-100 dark:bg-slate-800/40 dark:border-slate-800 rounded-2xl p-4">
                                <div id="projectStageList" class="space-y-2.5">
                                    @php
                                        $defaultStages = ['Brief & Analisis', 'Pengerjaan', 'Revisi', 'Finalisasi'];
                                        $seedStages = old('stage_name') ?? $defaultStages;
                                    @endphp
                                    @foreach ($seedStages as $seedIndex => $seedStage)
                                        <div class="stage-row flex items-start gap-2 bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-xl p-3 shadow-sm">
                                            <div class="stage-order-badge flex flex-col gap-1 mt-1 shrink-0">
                                                <button type="button" onclick="moveStageRow(this, -1)" title="Naik"
                                                    class="w-6 h-5 flex items-center justify-center text-[9px] text-blue-500 dark:text-blue-400 bg-blue-50 dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded hover:bg-blue-100">&#9650;</button>
                                                <button type="button" onclick="moveStageRow(this, 1)" title="Turun"
                                                    class="w-6 h-5 flex items-center justify-center text-[9px] text-blue-500 dark:text-blue-400 bg-blue-50 dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded hover:bg-blue-100">&#9660;</button>
                                            </div>
                                            <div class="flex-1 space-y-2">
                                                <input type="text" name="stage_name[]" maxlength="255"
                                                    placeholder="Nama tahap..." value="{{ is_array($seedStage) ? ($seedStage['name'] ?? '') : $seedStage }}"
                                                    class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded-lg text-sm font-bold text-blue-950 dark:text-white placeholder:text-blue-300 dark:placeholder:text-slate-500 focus:outline-none focus:border-blue-400">
                                                <textarea name="stage_desc[]" rows="1" maxlength="2000" placeholder="Deskripsi (opsional)"
                                                    class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded-lg text-xs font-medium text-blue-900 dark:text-white placeholder:text-blue-300 dark:placeholder:text-slate-500 focus:outline-none resize-none">{{ is_array($seedStage) ? ($seedStage['description'] ?? '') : '' }}</textarea>
                                            </div>
                                            <button type="button" onclick="removeStageRow(this)" title="Hapus tahap"
                                                class="mt-1 w-7 h-7 flex items-center justify-center shrink-0 text-blue-400 dark:text-slate-500 bg-blue-50 dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded-lg hover:text-red-500">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>

                                <button type="button" onclick="addStageRow()"
                                    class="mt-3 w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-900 border border-dashed border-blue-300 dark:border-slate-700 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-slate-800 rounded-xl text-sm font-bold transition">
                                    <i class="fa-solid fa-plus"></i> Tambah Tahap
                                </button>
                                <p class="text-[10px] font-bold text-blue-400 dark:text-slate-500 mt-2">
                                    Urutan mengikuti daftar di atas. Tahap terakhir dianggap sebagai finalisasi berproduk (100%).
                                </p>
                            </div>
                        </div>

                        {{-- DIVIDER --}}
                        <div class="h-px w-full bg-gradient-to-r from-transparent via-blue-100 dark:via-slate-800 to-transparent mb-8"></div>

                        {{-- QUOTA INDICATOR --}}
                        <div class="mb-6">
                            <div class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl p-5 shadow-sm">
                                <div class="flex items-start justify-between gap-4 mb-4">
                                    <div>
                                        <h3 class="font-bold text-slate-800 dark:text-white text-sm">Kuota Proyek Bulan Ini</h3>
                                        <p class="text-[10px] font-bold text-blue-400 uppercase tracking-widest mt-0.5 dark:text-slate-400">
                                            {{ $quotaData['used_slots'] ?? 0 }} / {{ $quotaData['free_quota'] ?? 3 }} tersisa
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0">
                                        <span class="text-lg font-extrabold {{ ($quotaData['remaining'] ?? ($quotaData['free_quota'] ?? 3) - ($quotaData['used_slots'] ?? 0)) > 0 ? 'text-emerald-600' : 'text-red-500' }}">
                                            {{ max(0, ($quotaData['available_slots'] ?? ($quotaData['free_quota'] ?? 3)) - ($quotaData['used_slots'] ?? 0)) }}
                                        </span>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Slot</span>
                                    </div>
                                </div>
                                
                                {{-- Progress Bar --}}
                                <div class="mb-3">
                                    <div class="w-full h-2.5 bg-blue-50 dark:bg-slate-800 rounded-full overflow-hidden">
                                        @php
                                            $used = $quotaData['used_slots'] ?? 0;
                                            $limit = $quotaData['free_quota'] ?? 3;
                                            $available = $quotaData['available_slots'] ?? $limit;
                                            $percentage = $available > 0 ? min(100, round(($used / $available) * 100)) : 100;
                                            $isExhausted = ($available - $used) <= 0;
                                        @endphp
                                        <div class="h-full {{ $isExhausted ? 'bg-red-500' : 'bg-gradient-to-r from-blue-500 to-blue-600' }} rounded-full transition-all duration-500"
                                             style="width: {{ $percentage }}%;"
                                             role="progressbar"
                                             aria-valuenow="{{ $percentage }}"
                                             aria-valuemin="0"
                                             aria-valuemax="100"></div>
                                    </div>
                                </div>
                                
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 text-xs">
                                    <p class="text-slate-500 dark:text-slate-400">
                                        {{ $used }} proyek sudah digunakan
                                    </p>
                                    <p class="text-slate-500 dark:text-slate-400">
                                        Kuota gratis direset setiap awal bulan.
                                    </p>
                                    @if($isExhausted)
                                    <p class="text-red-500 font-semibold">
                                        Kuota gratis habis. Proyek tambahan: Rp {{ number_format($quotaData['quota_price'], 0, ',', '.') }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- SECTION 4: STATUS & SUBMIT --}}
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 bg-blue-50/50 p-5 rounded-2xl border border-blue-100 dark:bg-slate-800/50 dark:border-slate-800">
                            {{-- Status --}}
                            <div class="flex items-center gap-4">
                                <label class="block text-[10px] font-black text-blue-500 uppercase tracking-widest dark:text-blue-400">Status:</label>
                                <select name="status"
                                    class="px-5 py-2.5 bg-white border border-blue-200 rounded-xl text-sm font-bold text-blue-950 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition-all cursor-pointer shadow-sm dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                                    <option value="open" {{ old('status', 'open') == 'open' ? 'selected' : '' }}>Open</option>
                                    {{-- <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Tutup</option> --}}
                                    <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Arsip</option>
                                </select>
                            </div>

                            {{-- Buttons --}}
                            <div class="flex items-center gap-3 w-full sm:w-auto">
                                <a href="{{ route('company.projects.index') }}"
                                    class="w-full sm:w-auto px-6 py-3.5 text-[11px] font-black uppercase tracking-widest text-blue-600 bg-white border border-blue-200 rounded-xl hover:bg-blue-50 hover:border-blue-300 transition-colors text-center shadow-sm dark:text-blue-400 dark:bg-slate-900 dark:border-slate-700 dark:hover:bg-slate-800">
                                    <i class="fa-solid fa-arrow-left mr-1.5"></i>Batal
                                </a>
                                <button type="submit"
                                    class="btn-shimmer w-full sm:w-auto px-8 py-3.5 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition shadow-[0_5px_15px_rgba(37,99,235,0.3)] inline-flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-rocket"></i>
                                    Publikasikan Proyek
                                </button>
                            </div>
                        </div>

                    </form>
                </div>

            </div>
        </main>

        {{-- FOOTER --}}

    </div>

    {{-- Quota Modal --}}
    <div id="quotaModal" class="fixed inset-0 z-[200] flex items-center justify-center bg-black/60 hidden">
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl w-full max-w-md mx-4">
            <div class="p-6 border-b border-blue-100 dark:border-slate-700">
                <h3 class="font-bold text-slate-800 text-lg dark:text-white">Batas Kuota Proyek Bulan Ini</h3>
                <p class="text-xs text-slate-500 mt-1 dark:text-slate-400">Anda telah mencapai batas kuota gratis ({{ $quotaData['free_quota'] ?? 3 }} proyek/bulan).</p>
            </div>
            <div class="p-6 text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-blue-50 rounded-full flex items-center justify-center dark:bg-slate-800">
                    <i class="fa-solid fa-rocket text-2xl text-blue-600 dark:text-blue-400"></i>
                </div>
                <p class="text-sm font-semibold text-slate-700 dark:text-white mb-3">
                    Kuota proyek bulan ini sudah habis. Anda telah menggunakan
                    <span class="font-bold text-blue-600 dark:text-blue-400" id="quotaUsedInfo">{{ session('quota_used', $quotaData['used_slots'] ?? '-') }}</span>
                    dari
                    <span class="font-bold text-slate-900 dark:text-white" id="quotaFreeInfo">{{ session('quota_free', $quotaData['free_quota'] ?? 3) }}</span>
                    proyek gratis.
                </p>
                <p class="text-sm text-slate-600 dark:text-slate-300 mb-6">
                    Untuk membuat proyek tambahan, diperlukan pembayaran
                    <span class="font-bold text-blue-600" id="quotaPriceDisplay">Rp {{ number_format($quotaData['quota_price'], 0, ',', '.') }}</span>.
                </p>
            </div>
            <div class="p-4 border-t border-blue-100 dark:border-slate-700 flex justify-end gap-3">
                <button type="button" onclick="closeQuotaModal()"
                        class="px-4 py-2 text-sm font-semibold text-slate-600 bg-slate-50 rounded-xl hover:bg-slate-100 transition">
                    Nanti Saja
                </button>
<a href="{{ session('quota_payment_id') ? route('company.quota.payment.show', session('quota_payment_id')) : route('company.quota.payment.start') }}"
                        id="btnPayQuota"
                        class="px-6 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 inline-flex items-center gap-2 transition shadow-[0_5px_15px_rgba(37,99,235,0.3)]">
                        <i class="fa-solid fa-credit-card"></i> <span id="btnPayQuotaText">Bayar Rp{{ number_format($quotaData['quota_price'], 0, ',', '.') }}</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Delete Stage Confirmation Modal --}}
    <div id="deleteStageModal" class="fixed inset-0 z-[250] flex items-center justify-center bg-black/60 hidden opacity-0 transition-opacity duration-300 backdrop-blur-sm">
        <div class="glass-card rounded-3xl w-full max-w-sm mx-4 transform scale-95 transition-transform duration-300 bg-white/95 dark:bg-slate-900/95 border border-blue-100 dark:border-slate-800 overflow-hidden">
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

    {{-- Script untuk auto-format input angka & Sistem Modern Toast Notification --}}
    <script>
        // Memastikan batasan deadline tanggal minimal hari ini berdasarkan waktu lokal perangkat user
        document.addEventListener('DOMContentLoaded', function() {
            const deadlineInput = document.querySelector('input[name="deadline"]');
            if (deadlineInput) {
                const today = new Date().toLocaleDateString('en-CA');
                deadlineInput.setAttribute('min', today);
            }
        });

        // Fungsi untuk memunculkan Toast Notification secara modern
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            if (!container) return;

            const toast = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-blue-950/95 text-white' : 'bg-red-600/95 text-white';
            const icon = type === 'success' ? 'fa-check-circle text-blue-400' : 'fa-exclamation-circle text-red-200';

            toast.className = `pointer-events-auto flex items-center gap-3 px-5 py-4 rounded-2xl shadow-2xl backdrop-blur-xl border border-white/10 transform translate-x-10 opacity-0 transition-all duration-500 ${bgColor}`;
            toast.innerHTML = `
                <i class="fa-solid ${icon} text-lg"></i>
                <span class="text-xs font-bold tracking-tight">${message}</span>
            `;

            container.appendChild(toast);

            // Efek masuk
            setTimeout(() => {
                toast.classList.remove('translate-x-10', 'opacity-0');
            }, 10);

            // Efek keluar otomatis setelah 4 detik
            setTimeout(() => {
                toast.classList.add('translate-x-10', 'opacity-0');
                setTimeout(() => toast.remove(), 500);
            }, 4000);
        }

        // Panggil otomatis jika ada session success dari Laravel
        @if(session('success'))
            document.addEventListener('DOMContentLoaded', function() {
                showToast("{{ session('success') }}", 'success');
            });
        @endif

        // Format angka budget secara real-time
        document.addEventListener('DOMContentLoaded', function() {
            const displayInput = document.getElementById('display_budget');
            const realInput = document.getElementById('real_budget');

            if (displayInput && realInput) {
                if (realInput.value) {
                    let initialValue = realInput.value.replace(/[^0-9]/g, '');
                    if (initialValue !== '') {
                        displayInput.value = parseInt(initialValue, 10).toLocaleString('id-ID');
                    }
                }

                displayInput.addEventListener('input', function(e) {
                    let rawValue = this.value.replace(/[^0-9]/g, '');
                    realInput.value = rawValue;

                    if (rawValue !== '') {
                        let formatted = parseInt(rawValue, 10).toLocaleString('id-ID');
                        this.value = formatted;
                    } else {
                        this.value = '';
                    }
                });
            }
        });

        // ── Tahap Pengerjaan (REVISI): kelola daftar tahap di form create ──
        function stageRowTemplate() {
            const list = document.getElementById('projectStageList');
            const div = document.createElement('div');
            div.className = 'stage-row flex items-start gap-2 bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-xl p-3 shadow-sm';
            div.innerHTML =
                '<div class="stage-order-badge flex flex-col gap-1 mt-1 shrink-0">' +
                '<button type="button" onclick="moveStageRow(this, -1)" title="Naik" class="w-6 h-5 flex items-center justify-center text-[9px] text-blue-500 dark:text-blue-400 bg-blue-50 dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded hover:bg-blue-100">&#9650;</button>' +
                '<button type="button" onclick="moveStageRow(this, 1)" title="Turun" class="w-6 h-5 flex items-center justify-center text-[9px] text-blue-500 dark:text-blue-400 bg-blue-50 dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded hover:bg-blue-100">&#9660;</button>' +
                '</div>' +
                '<div class="flex-1 space-y-2">' +
                '<input type="text" name="stage_name[]" maxlength="255" placeholder="Nama tahap..." class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded-lg text-sm font-bold text-blue-950 dark:text-white placeholder:text-blue-300 dark:placeholder:text-slate-500 focus:outline-none focus:border-blue-400">' +
                '<textarea name="stage_desc[]" rows="1" maxlength="2000" placeholder="Deskripsi (opsional)" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded-lg text-xs font-medium text-blue-900 dark:text-white placeholder:text-blue-300 dark:placeholder:text-slate-500 focus:outline-none resize-none"></textarea>' +
                '</div>' +
                '<button type="button" onclick="removeStageRow(this)" title="Hapus tahap" class="mt-1 w-7 h-7 flex items-center justify-center shrink-0 text-blue-400 dark:text-slate-500 bg-blue-50 dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded-lg hover:text-red-500"><i class="fa-solid fa-xmark"></i></button>';
            if (list) list.appendChild(div);
            return div;
        }

        function addStageRow() { stageRowTemplate(); }

        function moveStageRow(btn, dir) {
            const row = btn.closest('.stage-row');
            if (!row) return;
            const target = dir < 0 ? row.previousElementSibling : row.nextElementSibling;
            if (target && target.matches('.stage-row')) {
                row.parentNode.insertBefore(row, dir < 0 ? target : target.nextSibling);
            }
        }

        // Variabel penampung baris yang akan dihapus
        let stageRowToDelete = null;

        function openDeleteStageModal(row, stageName) {
            stageRowToDelete = row;
            document.getElementById('deleteStageName').textContent = `"${stageName}"`;
            
            const modal = document.getElementById('deleteStageModal');
            const modalInner = modal.querySelector('div.glass-card');
            
            modal.classList.remove('hidden');
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
                stageRowToDelete = null;
            }, 300);
        }

        // Menimpa fungsi removeStageRow() bawaan
        function removeStageRow(btn) {
            const row = btn.closest('.stage-row');
            if (!row) return;
            const nameInput = row.querySelector('input[name="stage_name[]"]');
            
            // Logic original: jika input kosong, langsung hapus tanpa konfirmasi
            if (nameInput && nameInput.value.trim() !== '') {
                openDeleteStageModal(row, nameInput.value.trim());
            } else {
                row.remove();
            }
        }

        // Quota data dari server (embed via PHP)
        const quotaData = @json($quotaData ?? null);

        function formatPrice(price) {
            return 'Rp ' + parseInt(price, 10).toLocaleString('id-ID');
        }

        function openQuotaModal() {
            document.getElementById('quotaModal').classList.remove('hidden');
            if (quotaData) {
                document.getElementById('quotaUsedInfo').textContent = quotaData.used_slots;
                const free = document.getElementById('quotaFreeInfo'); 
                if(free && quotaData.free_quota !== undefined) free.textContent = quotaData.free_quota;
                // Update price displays - use quota_price from embedded server data
                const price = quotaData.quota_price ?? 10000;
                const priceDisplay = document.getElementById('quotaPriceDisplay');
                if (priceDisplay) priceDisplay.textContent = formatPrice(price);
                const btnText = document.getElementById('btnPayQuotaText');
                if (btnText) btnText.textContent = 'Bayar ' + formatPrice(price);
            }
        }
        function closeQuotaModal() { document.getElementById('quotaModal').classList.add('hidden'); }

        document.addEventListener('DOMContentLoaded', function() {
            // Action tombol hapus di dalam modal Delete Stage
            const btnConfirmDelete = document.getElementById('btnConfirmDeleteStage');
            if (btnConfirmDelete) {
                btnConfirmDelete.addEventListener('click', function() {
                    if (stageRowToDelete) {
                        stageRowToDelete.remove();
                        closeDeleteStageModal();
                    }
                });
            }

            // Close modal Delete Stage jika overlay hitam diklik
            const deleteStageModal = document.getElementById('deleteStageModal');
            if (deleteStageModal) {
                deleteStageModal.addEventListener('click', function(e) {
                    if (e.target === this) closeDeleteStageModal();
                });
            }

            // Intercept form submit — block jika kuota penuh
            const form = document.querySelector('form[action="{{ route('company.projects.store') }}"]');
            if (form && quotaData && !quotaData.can_create) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    openQuotaModal();
                });
            }

            // Open modal otomatis jika redirect dari store() karena kuota habis
            @if(session('quota_payment_id'))
            openQuotaModal();
            @endif
        });

        // Close modal on backdrop click & Escape
        document.getElementById('quotaModal').addEventListener('click', function(e) {
            if (e.target === this) closeQuotaModal();
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeQuotaModal();
                
                const deleteModal = document.getElementById('deleteStageModal');
                if (deleteModal && !deleteModal.classList.contains('hidden')) {
                    closeDeleteStageModal();
                }
            }
        });
    </script>
</body>
</html>