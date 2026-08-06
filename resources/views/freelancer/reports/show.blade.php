<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan - The Archipelago Nexus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex">

    @include('navbar.navigasi')

    <div class="flex-1 flex flex-col min-h-screen overflow-hidden">
        @include('navbar.nav')

        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            <div class="max-w-5xl mx-auto space-y-6">

                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="flex items-center gap-3 px-5 py-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium rounded-2xl shadow-sm">
                        <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i> 
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                {{-- Back Link & Header --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <a href="{{ route('freelancer.reports.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-cyan-600 font-semibold transition group w-fit">
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
                        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                            <div class="p-6">
                                <div class="flex items-start justify-between gap-4 mb-5 pb-5 border-b border-slate-100">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="text-xs font-bold px-2.5 py-1 rounded-md bg-slate-100 text-slate-600 tracking-wide">
                                                #{{ $report->id }}
                                            </span>
                                            @if(class_exists('\App\Models\Report') && method_exists('\App\Models\Report', 'categoryLabel'))
                                                <span class="text-xs font-semibold px-2.5 py-1 rounded-md bg-cyan-50 text-cyan-700 border border-cyan-100">
                                                    {{ \App\Models\Report::categoryLabel($report->category) }}
                                                </span>
                                            @endif
                                        </div>
                                        <h1 class="text-xl sm:text-2xl font-bold text-slate-800 leading-snug pt-1">
                                            {{ $report->subject }}
                                        </h1>
                                    </div>
                                    <span class="shrink-0 text-xs px-3.5 py-1.5 rounded-full font-bold uppercase tracking-wider
                                        @if($report->status == 'menunggu') bg-amber-50 text-amber-600 border border-amber-200/60
                                        @elseif($report->status == 'diproses') bg-blue-50 text-blue-600 border border-blue-200/60
                                        @elseif($report->status == 'selesai') bg-emerald-50 text-emerald-600 border border-emerald-200/60
                                        @else bg-red-50 text-red-600 border border-red-200/60 @endif">
                                        {{ ucfirst($report->status) }}
                                    </span>
                                </div>

                                {{-- Description Block --}}
                                <div class="space-y-2 mb-6">
                                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">Deskripsi Laporan</h2>
                                    <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                                        {{ $report->description }}
                                    </div>
                                </div>

{{-- Admin Note Block --}}
                                <div class="space-y-2">
                                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">Catatan Admin</h2>
                                    @if($report->admin_note)
                                        <div class="bg-cyan-50/70 border border-cyan-100 rounded-xl p-4 text-sm text-cyan-900 leading-relaxed flex items-start gap-3">
                                            <i class="fa-solid fa-comment-dots text-cyan-600 text-base mt-0.5 shrink-0"></i>
                                            <div>
                                                <p class="font-semibold text-xs text-cyan-700 mb-1">Tanggapan dari Tim Modérasi:</p>
                                                <p class="whitespace-pre-line">{{ $report->admin_note }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="bg-slate-50 border border-dashed border-slate-200 rounded-xl p-4 text-sm text-slate-400 italic flex items-center gap-2">
                                            <i class="fa-regular fa-clock text-slate-300"></i> Belum ada catatan atau tanggapan dari admin.
                                        </div>
                                    @endif
                                </div>

                                {{-- Lampiran / Bukti --}}
                                @if($report->attachments->count() > 0)
                                    <div class="space-y-2">
                                        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">Lampiran / Bukti ({{ $report->attachments->count() }})</h2>
                                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                            @foreach($report->attachments as $attachment)
                                                <a href="{{ $attachment->file_url }}" target="_blank"
                                                   class="group rounded-xl border border-slate-200 overflow-hidden bg-slate-50 hover:border-cyan-300 transition">
                                                    @if($attachment->is_image)
                                                        <img src="{{ $attachment->file_url }}" alt="{{ $attachment->file_name }}"
                                                             class="w-full h-24 object-cover group-hover:scale-105 transition">
                                                    @else
                                                        <div class="h-24 flex items-center justify-center bg-slate-100 text-cyan-500">
                                                            <i class="fa-solid fa-file-lines text-2xl"></i>
                                                        </div>
                                                    @endif
                                                    <div class="p-2">
                                                        <p class="text-[11px] font-semibold text-slate-700 truncate">{{ $attachment->file_name }}</p>
                                                        <p class="text-[10px] text-slate-400">{{ $attachment->formatted_size }}</p>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                {{-- Form Unggah Bukti Tambahan (menunggu-bukti) --}}
                                @if($report->status == 'menunggu-bukti')
                                    <div class="space-y-2 bg-violet-50 border border-violet-200 rounded-xl p-4">
                                        <h2 class="text-xs font-bold uppercase tracking-wider text-violet-600 flex items-center gap-2">
                                            <i class="fa-solid fa-upload"></i> Unggah Bukti Tambahan
                                        </h2>
                                        <p class="text-xs text-violet-700">Admin meminta bukti tambahan untuk laporan ini. Silakan unggah screenshot/bukti pendukung.</p>
                                        <form method="POST" action="{{ route('freelancer.reports.evidence', $report) }}" enctype="multipart/form-data" class="space-y-3 mt-2">
                                            @csrf
                                            <input type="file" name="attachments[]" multiple accept=".jpg,.jpeg,.png,.pdf"
                                                   class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-violet-100 file:text-violet-700 hover:file:bg-violet-200">
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
                        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Pelapor</h3>
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-cyan-500 to-blue-500 text-white flex items-center justify-center font-bold text-base shadow-sm">
                                    {{ strtoupper(substr($report->reporter->name ?? '?', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-800 text-sm truncate">{{ $report->reporter->name ?? '—' }}</p>
                                    <p class="text-xs text-slate-500 truncate">{{ $report->reporter->email ?? '—' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Proyek Terkait --}}
                        @if($report->project)
                            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Proyek Terkait</h3>
                                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 space-y-2 text-xs">
                                    <div>
                                        <span class="text-slate-400 block mb-0.5">Judul Proyek</span>
                                        <p class="font-semibold text-slate-800 text-sm leading-snug">{{ $report->project->project_name }}</p>
                                    </div>
                                    @if($report->project->owner)
                                        <div class="pt-2 border-t border-slate-200/60 flex justify-between items-center">
                                            <span class="text-slate-400">Klien / Owner</span>
                                            <span class="font-semibold text-slate-700 truncate max-w-[150px]">{{ $report->project->owner->name ?? '—' }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- User Dilaporkan --}}
                        @if($report->reportedUser)
                            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Pihak Dilaporkan</h3>
                                <div class="flex items-center gap-3 p-3 bg-rose-50/50 border border-rose-100 rounded-xl">
                                    <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center font-bold text-sm shrink-0">
                                        {{ strtoupper(substr($report->reportedUser->name ?? '?', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-800 text-sm truncate">{{ $report->reportedUser->name }}</p>
                                        <p class="text-xs text-slate-500 truncate">{{ $report->reportedUser->email }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Penawaran Terkait --}}
                        @if($report->penawaran)
                            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Penawaran Terkait</h3>
                                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 space-y-2 text-xs">
                                    <div class="flex justify-between items-center">
                                        <span class="text-slate-400">Nilai Penawaran</span>
                                        <span class="font-bold text-slate-800 text-sm">Rp {{ number_format($report->penawaran->harga_penawaran, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between items-center pt-2 border-t border-slate-200/60">
                                        <span class="text-slate-400">Status Penawaran</span>
                                        <span class="font-semibold text-slate-700 capitalize">{{ $report->penawaran->status }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Info Penanganan --}}
                        @if($report->handledBy || $report->resolved_at)
                            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 space-y-3">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Penanganan</h3>
                                <div class="space-y-2 text-xs">
                                    @if($report->handledBy)
                                        <div class="flex justify-between items-center">
                                            <span class="text-slate-400">Ditangani Oleh</span>
                                            <span class="font-semibold text-slate-700">{{ $report->handledBy->name }}</span>
                                        </div>
                                    @endif
                                    @if($report->resolved_at)
                                        <div class="flex justify-between items-center pt-2 border-t border-slate-100">
                                            <span class="text-slate-400">Waktu Selesai</span>
                                            <span class="font-semibold text-slate-700">{{ $report->resolved_at->format('d M Y H:i') }}</span>
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