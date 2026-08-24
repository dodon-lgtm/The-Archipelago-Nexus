<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.theme-boot')
    <title>Detail Laporan - ApexForge Labs</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = tailwind.config || {};
    tailwind.config.darkMode = 'class';
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
            <div class="max-w-5xl mx-auto space-y-6">

                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="flex items-center gap-3 px-5 py-4 bg-emerald-50 dark:bg-emerald-900/40 border border-emerald-200 dark:border-emerald-900 text-emerald-800 dark:text-emerald-300 text-sm font-medium rounded-2xl shadow-sm">
                        <i class="fa-solid fa-circle-check text-emerald-500 dark:text-emerald-400 text-lg"></i> 
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                {{-- Back Link & Header --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <a href="{{ route('freelancer.reports.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 font-semibold transition group w-fit">
                        <i class="fa-solid fa-arrow-left text-xs transition-transform group-hover:-translate-x-1"></i> Kembali ke Laporan
                    </a>
                    <span class="text-xs text-slate-400 font-medium">
                        <i class="fa-regular fa-clock mr-1"></i> Dibuat: {{ $report->created_at->format('d M Y, H:i') }}
                    </span>
                </div>

                {{-- Main Grid Layout --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
                    
                    {{-- Main Content Column --}}
                    <div class="lg:col-span-2 space-y-6">
                        
                        {{-- Report Card --}}
                        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-blue-100/80 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-300">
                            <div class="p-6">
                                <div class="flex items-start justify-between gap-4 mb-5 pb-5 border-b border-blue-50 dark:border-slate-800">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="text-xs font-bold px-2.5 py-1 rounded-md bg-blue-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 tracking-wide">
                                                #{{ $report->id }}
                                            </span>
                                            @if(class_exists('\App\Models\Report') && method_exists('\App\Models\Report', 'categoryLabel'))
                                                <span class="text-xs font-semibold px-2.5 py-1 rounded-md bg-blue-50 dark:bg-slate-800 text-blue-700 dark:text-blue-400 border border-blue-100 dark:border-slate-800">
                                                    {{ \App\Models\Report::categoryLabel($report->category) }}
                                                </span>
                                            @endif
                                        </div>
                                        <h1 class="text-xl sm:text-2xl font-bold text-slate-800 dark:text-white leading-snug pt-1">
                                            {{ $report->subject }}
                                        </h1>
                                    </div>
                                    <span class="shrink-0 text-xs px-3.5 py-1.5 rounded-full font-bold uppercase tracking-wider
                                        @if($report->status == 'menunggu') bg-amber-50 dark:bg-amber-900/40 text-amber-600 dark:text-amber-300 border border-amber-200/60 dark:border-amber-900
                                        @elseif($report->status == 'diproses') bg-blue-50 dark:bg-slate-800 text-blue-600 dark:text-blue-400 border border-blue-200/60 dark:border-slate-800
                                        @elseif($report->status == 'selesai') bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-900
                                        @else bg-red-50 dark:bg-red-900/40 text-red-600 dark:text-red-300 border border-red-200/60 dark:border-red-900 @endif">
                                        {{ ucfirst($report->status) }}
                                    </span>
                                </div>

                                {{-- Description Block --}}
                                <div class="space-y-2 mb-6">
                                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400">Deskripsi Laporan</h2>
                                    <div class="bg-[#f6f9ff] dark:bg-slate-950 border border-blue-50 dark:border-slate-800 rounded-xl p-4 text-sm text-slate-700 dark:text-white leading-relaxed whitespace-pre-line">
                                        {{ $report->description }}
                                    </div>
                                </div>

{{-- Admin Note Block --}}
                                <div class="space-y-2">
                                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400">Catatan Admin</h2>
                                    @if($report->admin_note)
                                        <div class="bg-blue-50/70 dark:bg-slate-800/70 border border-blue-100 dark:border-slate-800 rounded-xl p-4 text-sm text-blue-900 dark:text-white leading-relaxed flex items-start gap-3">
                                            <i class="fa-solid fa-comment-dots text-blue-600 dark:text-blue-400 text-base mt-0.5 shrink-0"></i>
                                            <div>
                                                <p class="font-semibold text-xs text-blue-700 dark:text-blue-400 mb-1">Tanggapan dari Tim Modérasi:</p>
                                                <p class="whitespace-pre-line">{{ $report->admin_note }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="bg-[#f6f9ff] dark:bg-slate-950 border border-dashed border-blue-100 dark:border-slate-700 rounded-xl p-4 text-sm text-slate-400 dark:text-slate-400 italic flex items-center gap-2">
                                            <i class="fa-regular fa-clock text-slate-300 dark:text-slate-600"></i> Belum ada catatan atau tanggapan dari admin.
                                        </div>
                                    @endif
                                </div>

{{-- Lampiran / Bukti --}}
                                @if($report->attachments->count() > 0)
                                    <div class="space-y-2">
                                        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400">Lampiran / Bukti ({{ $report->attachments->count() }})</h2>
                                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                            @foreach($report->attachments as $attachment)
                                                <div class="group rounded-xl border border-blue-100 dark:border-slate-800 overflow-hidden bg-[#f6f9ff] dark:bg-slate-950 hover:border-blue-300 dark:hover:border-slate-700 transition">
                                                    @if($attachment->is_image)
                                                        {{-- Thumbnail - klik untuk preview gambar --}}
                                                        <a href="{{ $attachment->file_url }}" target="_blank" title="{{ $attachment->file_name }}">
                                                            <img src="{{ $attachment->file_url }}" alt="{{ $attachment->file_name }}"
                                                                 class="w-full h-24 object-cover group-hover:scale-105 transition">
                                                        </a>
                                                        <div class="p-2">
                                                            <p class="text-[11px] font-semibold text-slate-700 dark:text-white truncate">🖼 {{ $attachment->file_name }}</p>
                                                            <p class="text-[10px] text-slate-400">{{ $attachment->formatted_size }}</p>
                                                        </div>
                                                    @else
                                                        {{-- PDF / File - icon + Buka/Download --}}
                                                        <div class="h-24 flex items-center justify-center bg-blue-50 dark:bg-slate-800 text-blue-500 dark:text-blue-400">
                                                            <i class="fa-solid fa-file-pdf text-3xl"></i>
                                                        </div>
                                                        <div class="p-2">
                                                            <p class="text-[11px] font-semibold text-slate-700 dark:text-white truncate">📄 {{ $attachment->file_name }}</p>
                                                            <p class="text-[10px] text-slate-400">{{ $attachment->formatted_size }}</p>
                                                            <div class="flex gap-2 mt-2">
                                                                <a href="{{ $attachment->file_url }}" target="_blank"
                                                                   class="flex-1 text-center px-2 py-1.5 rounded-lg bg-blue-50 dark:bg-slate-800 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-slate-800 text-[10px] font-bold transition">
                                                                    <i class="fa-solid fa-eye mr-1"></i> Buka
                                                                </a>
                                                                <a href="{{ $attachment->file_url }}" download
                                                                   class="flex-1 text-center px-2 py-1.5 rounded-lg bg-blue-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 text-[10px] font-bold transition">
                                                                    <i class="fa-solid fa-download mr-1"></i> Download
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                {{-- Form Unggah Bukti Tambahan (menunggu-bukti) --}}
                                @if($report->status == 'menunggu-bukti')
                                    <div class="space-y-2 bg-violet-50 dark:bg-violet-900/40 border border-violet-200 dark:border-violet-900 rounded-xl p-4">
                                        <h2 class="text-xs font-bold uppercase tracking-wider text-violet-600 dark:text-violet-300 flex items-center gap-2">
                                            <i class="fa-solid fa-upload"></i> Unggah Bukti Tambahan
                                        </h2>
                                        <p class="text-xs text-violet-700 dark:text-violet-300">Admin meminta bukti tambahan untuk laporan ini. Silakan unggah screenshot/bukti pendukung.</p>
                                        <form method="POST" action="{{ route('freelancer.reports.evidence', $report) }}" enctype="multipart/form-data" class="space-y-3 mt-2">
                                            @csrf
                                            <input type="file" name="attachments[]" multiple accept=".jpg,.jpeg,.png,.pdf"
                                                   class="w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-violet-100 dark:file:bg-violet-900/40 file:text-violet-700 dark:file:text-violet-300 hover:file:bg-violet-200 dark:hover:file:bg-violet-900/60">
                                            @error('attachments') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                                            @error('attachments.*') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                                            <button type="submit" class="px-4 py-2 bg-violet-500 hover:bg-violet-600 text-white rounded-lg text-xs font-bold transition">
                                                <i class="fa-solid fa-paper-plane mr-1"></i> Kirim Bukti
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>

                    {{-- Sidebar Column --}}
                    <div class="space-y-6">

                        {{-- Pelapor --}}
                        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-blue-100/80 dark:border-slate-800 shadow-sm p-5 transition-colors duration-300">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400 mb-3">Pelapor</h3>
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-blue-500 to-blue-500 text-white flex items-center justify-center font-bold text-base shadow-sm">
                                    {{ strtoupper(substr($report->reporter->name ?? '?', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-800 dark:text-white text-sm truncate">{{ $report->reporter->name ?? '—' }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $report->reporter->email ?? '—' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Proyek Terkait --}}
                        @if($report->project)
                            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-blue-100/80 dark:border-slate-800 shadow-sm p-5 transition-colors duration-300">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400 mb-3">Proyek Terkait</h3>
                                <div class="p-3 bg-[#f6f9ff] dark:bg-slate-950 rounded-xl border border-blue-50 dark:border-slate-800 space-y-2 text-xs">
                                    <div>
                                        <span class="text-slate-400 block mb-0.5">Judul Proyek</span>
                                        <p class="font-semibold text-slate-800 dark:text-white text-sm leading-snug">{{ $report->project->project_name }}</p>
                                    </div>
                                    @if($report->project->owner)
                                        <div class="pt-2 border-t border-blue-100/60 dark:border-slate-800 flex justify-between items-center">
                                            <span class="text-slate-400">Klien / Owner</span>
                                            <span class="font-semibold text-slate-700 dark:text-white truncate max-w-[150px]">{{ $report->project->owner->name ?? '—' }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- User Dilaporkan --}}
                        @if($report->reportedUser)
                            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-blue-100/80 dark:border-slate-800 shadow-sm p-5 transition-colors duration-300">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400 mb-3">Pihak Dilaporkan</h3>
                                <div class="flex items-center gap-3 p-3 bg-rose-50/50 dark:bg-rose-900/40 border border-rose-100 dark:border-rose-900 rounded-xl">
                                    <div class="w-10 h-10 rounded-xl bg-rose-100 dark:bg-rose-900/40 text-rose-600 dark:text-rose-300 flex items-center justify-center font-bold text-sm shrink-0">
                                        {{ strtoupper(substr($report->reportedUser->name ?? '?', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-800 dark:text-white text-sm truncate">{{ $report->reportedUser->name }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $report->reportedUser->email }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Penawaran Terkait --}}
                        @if($report->penawaran)
                            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-blue-100/80 dark:border-slate-800 shadow-sm p-5 transition-colors duration-300">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400 mb-3">Penawaran Terkait</h3>
                                <div class="p-3 bg-[#f6f9ff] dark:bg-slate-950 rounded-xl border border-blue-50 dark:border-slate-800 space-y-2 text-xs">
                                    <div class="flex justify-between items-center">
                                        <span class="text-slate-400">Nilai Penawaran</span>
                                        <span class="font-bold text-slate-800 dark:text-white text-sm">Rp {{ number_format($report->penawaran->harga_penawaran, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between items-center pt-2 border-t border-blue-100/60 dark:border-slate-800">
                                        <span class="text-slate-400">Status Penawaran</span>
                                        <span class="font-semibold text-slate-700 dark:text-white capitalize">{{ $report->penawaran->status }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Info Penanganan --}}
                        @if($report->handledBy || $report->resolved_at)
                            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-blue-100/80 dark:border-slate-800 shadow-sm p-5 space-y-3 transition-colors duration-300">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400">Penanganan</h3>
                                <div class="space-y-2 text-xs">
                                    @if($report->handledBy)
                                        <div class="flex justify-between items-center">
                                            <span class="text-slate-400">Ditangani Oleh</span>
                                            <span class="font-semibold text-slate-700 dark:text-white">{{ $report->handledBy->name }}</span>
                                        </div>
                                    @endif
                                    @if($report->resolved_at)
                                        <div class="flex justify-between items-center pt-2 border-t border-blue-50 dark:border-slate-800">
                                            <span class="text-slate-400">Waktu Selesai</span>
                                            <span class="font-semibold text-slate-700 dark:text-white">{{ $report->resolved_at->format('d M Y H:i') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                    </div>

                </div>

            </div>
        </main>
    </div>

</body>
</html>