<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Laporan - ApexForge Labs</title>
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = tailwind.config || {};
        tailwind.config.darkMode = 'class';
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
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
<body class="bg-[#f6f9ff] dark:bg-slate-950 text-slate-800 dark:text-white antialiased min-h-screen flex transition-colors duration-300">

    @include('navbar.navigasi')

    <div class="flex-1 flex flex-col min-h-screen overflow-hidden">
        @include('navbar.nav')

        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            <div class="max-w-2xl mx-auto">
{{-- Flash Messages --}}
                @if(session('success'))
                    <div class="mb-4 flex items-center gap-3 px-5 py-4 bg-emerald-50 dark:bg-emerald-900/40 border border-emerald-200 dark:border-emerald-900 text-emerald-700 dark:text-emerald-300 text-sm font-medium rounded-xl">
                        <i class="fa-regular fa-circle-check"></i> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 flex items-center gap-3 px-5 py-4 bg-red-50 dark:bg-red-900/40 border border-red-200 dark:border-red-900 text-red-700 dark:text-red-300 text-sm font-medium rounded-xl">
                        <i class="fa-regular fa-circle-xmark"></i> {{ session('error') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="mb-4 flex items-start gap-3 px-5 py-4 bg-red-50 dark:bg-red-900/40 border border-red-200 dark:border-red-900 text-red-700 dark:text-red-300 text-sm font-medium rounded-xl">
                        <i class="fa-regular fa-circle-xmark mt-0.5"></i>
                        <div>
                            @foreach($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Back Button --}}
                <div class="mb-4">
                    <a href="{{ route('freelancer.reports.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-400 font-semibold inline-flex items-center gap-1">
                        <i class="fa-solid fa-arrow-left"></i> Kembali ke Laporan
                    </a>
                </div>

                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-blue-100 dark:border-slate-800 shadow-sm p-6 transition-colors duration-300">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-xl bg-red-50 dark:bg-red-900/40 text-red-500 dark:text-red-400 flex items-center justify-center text-xl">
                            <i class="fa-solid fa-flag"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-slate-800 dark:text-white">Buat Laporan</h2>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Laporkan masalah, pengguna, atau proyek yang melanggar aturan</p>
                        </div>
                    </div>

<form method="POST" action="{{ route('freelancer.reports.store') }}" enctype="multipart/form-data" class="space-y-5">
                        @csrf

{{-- Hidden inputs for contextual reporting --}}
@if($workspace)
                            <input type="hidden" name="workspace_id" value="{{ $workspace->id }}">
                            <input type="hidden" name="project_id" value="{{ $project ? $project->id : '' }}">
                            <input type="hidden" name="reported_user_id" value="{{ $reportedUser ? $reportedUser->id : '' }}">
                        @elseif($project)
                            <input type="hidden" name="project_id" value="{{ $project->id }}">
                            <input type="hidden" name="reported_user_id" value="{{ $reportedUser ? $reportedUser->id : '' }}">
                        @elseif($reportedUser)
                            {{-- Konteks murni: Freelancer melaporkan Company --}}
                            <input type="hidden" name="reported_user_id" value="{{ $reportedUser->id }}">
                        @endif

                        {{-- Context Info: Workspace (if reporting from workspace) --}}
                        @if($workspace)
                            <div class="bg-[#f6f9ff] dark:bg-slate-950 border border-blue-100 dark:border-slate-800 rounded-xl p-4 space-y-3">
                                <div class="flex items-center gap-2 text-slate-500 dark:text-slate-400 text-xs font-semibold uppercase tracking-wider">
                                    <i class="fa-solid fa-layer-group"></i>
                                    <span>Workspace yang Dilaporkan</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-slate-800 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                                        <i class="fa-solid fa-briefcase"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 dark:text-white">{{ $project->project_name ?? 'Workspace' }}</p>
                                        @if($reportedUser)
                                            <p class="text-xs text-slate-500 dark:text-slate-400">Perusahaan: {{ $reportedUser->name }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
@elseif($project)
                            {{-- Context Info: Project Details (if reporting from project detail page) --}}
                            <div class="bg-[#f6f9ff] dark:bg-slate-950 border border-blue-100 dark:border-slate-800 rounded-xl p-4 space-y-3">
                                <div class="flex items-center gap-2 text-slate-500 dark:text-slate-400 text-xs font-semibold uppercase tracking-wider">
                                    <i class="fa-solid fa-folder-open"></i>
                                    <span>Proyek yang Dilaporkan</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-slate-800 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                                        <i class="fa-solid fa-briefcase"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 dark:text-white">{{ $project->project_name }}</p>
                                        @if($reportedUser)
                                            <p class="text-xs text-slate-500 dark:text-slate-400">Perusahaan: {{ $reportedUser->name }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @elseif($reportedUser)
                            {{-- Context Info: Company yang Dilaporkan (Freelancer melaporkan Company) --}}
                            <div class="bg-[#f6f9ff] dark:bg-slate-950 border border-blue-100 dark:border-slate-800 rounded-xl p-4 space-y-3">
                                <div class="flex items-center gap-2 text-slate-500 dark:text-slate-400 text-xs font-semibold uppercase tracking-wider">
                                    <i class="fa-solid fa-building"></i>
                                    <span>Perusahaan yang Dilaporkan</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-slate-800 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold">
                                        {{ strtoupper(substr($reportedUser->name ?? '?', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 dark:text-white">{{ $reportedUser->name }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $reportedUser->email }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Category --}}
                        @php
                            // Frontend hanya mengikuti target. Backend tetap source of truth.
                            // - Workspace   -> melaporkan Company => TARGET_COMPANY
                            // - Project     -> melaporkan Project => TARGET_PROJECT
                            // - reportedUser (murni Company)      => TARGET_COMPANY
                            // - selain itu (Bantuan -> Laporkan Bug) => TARGET_WEBSITE
                            $reportTarget = $workspace || ($reportedUser && !$project)
                                ? \App\Models\Report::TARGET_COMPANY
                                : ($project
                                    ? \App\Models\Report::TARGET_PROJECT
                                    : \App\Models\Report::TARGET_WEBSITE);
                            $targetCategories = \App\Models\Report::categoriesForTarget($reportTarget);
                        @endphp
                        <div>
                            <label class="text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5 block">Kategori Laporan <span class="text-red-500 dark:text-red-400">*</span></label>
                            <select name="category"
                                class="w-full rounded-xl border-blue-100 dark:border-slate-700 bg-[#f6f9ff] dark:bg-slate-800 px-4 py-2.5 text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none dark:text-white @error('category') border-red-300 @enderror">
                                @foreach($targetCategories as $cat)
                                    <option value="{{ $cat }}" @selected(old('category') == $cat)>{{ \App\Models\Report::categoryLabel($cat) }}</option>
                                @endforeach
                            </select>
                            @error('category') <p class="text-xs text-red-500 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Subject --}}
                        <div>
                            <label class="text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5 block">Subjek Laporan <span class="text-red-500 dark:text-red-400">*</span></label>
                            <input type="text" name="subject" value="{{ old('subject') }}"
                                class="w-full rounded-xl border-blue-100 dark:border-slate-700 bg-[#f6f9ff] dark:bg-slate-800 px-4 py-2.5 text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none dark:text-white dark:placeholder:text-slate-500 @error('subject') border-red-300 @enderror"
                                placeholder="Contoh: Pengguna mencurigakan, Proyek tidak sesuai, dll.">
                            @error('subject') <p class="text-xs text-red-500 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label class="text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5 block">Deskripsi <span class="text-red-500 dark:text-red-400">*</span></label>
                            <textarea name="description" rows="5"
                                class="w-full rounded-xl border-blue-100 dark:border-slate-700 bg-[#f6f9ff] dark:bg-slate-800 px-4 py-2.5 text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none dark:text-white dark:placeholder:text-slate-500 @error('description') border-red-300 @enderror"
                                placeholder="Jelaskan secara detail masalah yang Anda temui...">{{ old('description') }}</textarea>
                            @error('description') <p class="text-xs text-red-500 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                        </div>

{{-- Attachment / Bukti --}}
                        <div>
                            <label class="text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5 block">Lampiran / Bukti (opsional)</label>
                            <input type="file" name="attachments[]" multiple accept=".jpg,.jpeg,.png,.pdf"
                                   class="w-full text-sm text-slate-500 dark:text-slate-400 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 dark:file:bg-slate-800 file:text-blue-600 dark:file:text-blue-400 hover:file:bg-blue-100 dark:hover:file:bg-slate-800">
                            <p class="text-[11px] text-slate-400 mt-1">Maks 5 file. Format: JPG, JPEG, PNG, atau PDF. Maks 5 MB per file.</p>
                            @error('attachments') <p class="text-xs text-red-500 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                            @error('attachments.*') <p class="text-xs text-red-500 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Info --}}
                        <div class="bg-amber-50 dark:bg-amber-900/40 border border-amber-200 dark:border-amber-900 rounded-xl p-4 text-sm text-amber-700 dark:text-amber-300 flex items-start gap-3">
                            <i class="fa-solid fa-shield-halved mt-0.5"></i>
                            <p>Laporan Anda akan ditinjau oleh tim admin. Pastikan Anda memberikan informasi yang benar dan jelas agar dapat diproses dengan baik.</p>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-3 pt-2">
                            <button type="submit" class="px-6 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-xl text-sm font-semibold transition flex items-center gap-2">
                                <i class="fa-solid fa-paper-plane"></i> Kirim Laporan
                            </button>
                            <a href="{{ route('freelancer.reports.index') }}" class="px-6 py-2.5 bg-blue-50 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl text-sm font-semibold transition">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

</body>
</html>
