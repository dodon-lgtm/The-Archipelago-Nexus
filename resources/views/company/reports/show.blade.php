<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan - ApexForge Labs</title>
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
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-surface text-slate-800 min-h-screen flex font-sans antialiased">

    {{-- SIDEBAR --}}
    @include('navbar.navigasi')

    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col min-h-screen w-full overflow-hidden">
        @include('navbar.nav')

        <main class="flex-1 w-full overflow-y-auto p-4 sm:p-6 lg:p-8">
            <div class="max-w-3xl mx-auto">

                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="mb-4 flex items-center gap-3 px-5 py-4 bg-emerald-50/80 backdrop-blur-md border border-emerald-200/60 text-emerald-800 text-sm font-medium rounded-2xl shadow-sm">
                        <i class="fa-regular fa-circle-check"></i> {{ session('success') }}
                    </div>
                @endif

                {{-- Back Button --}}
                <div class="mb-4">
                    <a href="{{ route('company.reports.index') }}" class="text-sm text-brand hover:text-brand-dark font-semibold inline-flex items-center gap-1">
                        <i class="fa-solid fa-arrow-left"></i> Kembali ke Laporan
                    </a>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Main Content --}}
                    <div class="lg:col-span-2 space-y-6">
                        {{-- Report Detail --}}
                        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6">
<div class="flex items-start justify-between mb-4">
                                <div>
                                    <h2 class="text-xl font-bold text-slate-800">{{ $report->subject }}</h2>
                                    <p class="text-sm text-slate-500 mt-1">Laporan #{{ $report->id }}</p>
                                    <div class="flex items-center gap-2 mt-2 flex-wrap">
                                        <span class="text-xs px-2.5 py-1 rounded-full font-semibold bg-slate-100 text-slate-600">{{ \App\Models\Report::categoryLabel($report->category) }}</span>
                                        <span class="text-xs px-2.5 py-1 rounded-full font-semibold bg-brand/10 text-brand border border-brand/10">Target: {{ \App\Models\Report::targetLabel($report->target) }}</span>
                                    </div>
                                </div>
                                <span class="text-xs px-3 py-1.5 rounded-full font-semibold
                                    @if($report->status == 'menunggu') bg-amber-50 text-amber-600
                                    @elseif($report->status == 'ditinjau') bg-blue-50 text-blue-600
                                    @elseif($report->status == 'menunggu-bukti') bg-violet-50 text-violet-600
                                    @elseif($report->status == 'selesai') bg-emerald-50 text-emerald-600
                                    @else bg-red-50 text-red-600 @endif">{{ \App\Models\Report::statusLabel($report->status) }}</span>
                            </div>

                            {{-- Description --}}
                            <div class="mb-4">
                                <p class="text-xs text-slate-500 font-semibold mb-1">Deskripsi Laporan</p>
                                <div class="bg-slate-50 rounded-xl p-4 text-sm text-slate-700 leading-relaxed">{{ $report->description }}</div>
                            </div>

                            {{-- Admin Note --}}
                            @if($report->admin_note)
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold mb-1">Catatan Admin</p>
                                    <div class="bg-blue-50 rounded-xl p-4 text-sm text-blue-700 leading-relaxed">{{ $report->admin_note }}</div>
                                </div>
                            @else
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold mb-1">Catatan Admin</p>
                                    <div class="bg-slate-50 rounded-xl p-4 text-sm text-slate-400 italic">Belum ada catatan dari admin.</div>
                                </div>
                            @endif

                            {{-- Lampiran / Bukti --}}
                            @if($report->attachments->count() > 0)
                                <div class="mt-4">
                                    <p class="text-xs text-slate-500 font-semibold mb-2">Lampiran / Bukti ({{ $report->attachments->count() }})</p>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                        @foreach($report->attachments as $attachment)
                                            <div class="group rounded-xl border border-slate-200 overflow-hidden bg-slate-50 hover:border-brand transition">
                                                @if($attachment->is_image)
                                                    {{-- Thumbnail - klik untuk preview gambar --}}
                                                    <a href="{{ $attachment->file_url }}" target="_blank" title="{{ $attachment->file_name }}">
                                                        <img src="{{ $attachment->file_url }}" alt="{{ $attachment->file_name }}"
                                                             class="w-full h-24 object-cover group-hover:scale-105 transition">
                                                    </a>
                                                    <div class="p-2">
                                                        <p class="text-[11px] font-semibold text-slate-700 truncate">🖼 {{ $attachment->file_name }}</p>
                                                        <p class="text-[10px] text-slate-400">{{ $attachment->formatted_size }}</p>
                                                    </div>
                                                @else
                                                    {{-- PDF / File - icon + Buka/Download --}}
                                                    <div class="h-24 flex items-center justify-center bg-slate-100 text-brand">
                                                        <i class="fa-solid fa-file-pdf text-3xl"></i>
                                                    </div>
                                                    <div class="p-2">
                                                        <p class="text-[11px] font-semibold text-slate-700 truncate">📄 {{ $attachment->file_name }}</p>
                                                        <p class="text-[10px] text-slate-400">{{ $attachment->formatted_size }}</p>
                                                        <div class="flex gap-2 mt-2">
                                                            <a href="{{ $attachment->file_url }}" target="_blank"
                                                               class="flex-1 text-center px-2 py-1.5 rounded-lg bg-brand/10 text-brand hover:bg-brand/20 text-[10px] font-bold transition">
                                                                <i class="fa-solid fa-eye mr-1"></i> Buka
                                                            </a>
                                                            <a href="{{ $attachment->file_url }}" download
                                                               class="flex-1 text-center px-2 py-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 text-[10px] font-bold transition">
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

                            {{-- Form Unggah Bukti Tambahan --}}
                            @if($report->status == 'menunggu-bukti')
                                <div class="mt-4 bg-violet-50 border border-violet-200 rounded-xl p-4">
                                    <p class="text-xs font-bold text-violet-700 mb-1 flex items-center gap-2"><i class="fa-solid fa-upload"></i> Unggah Bukti Tambahan</p>
                                    <p class="text-xs text-violet-600 mb-3">Admin meminta bukti tambahan untuk laporan ini.</p>
                                    <form method="POST" action="{{ route('company.reports.evidence', $report) }}" enctype="multipart/form-data" class="space-y-3">
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

                            {{-- Date --}}
                            <div class="mt-4 pt-4 border-t border-slate-100">
                                <p class="text-xs text-slate-400">
                                    <i class="fa-regular fa-calendar mr-1"></i>
                                    Dibuat pada {{ $report->created_at->format('d M Y H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Sidebar --}}
                    <div class="space-y-6">
                        {{-- Reporter Info --}}
                        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-5">
                            <h3 class="font-bold text-slate-800 mb-3">Pelapor</h3>
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-lg font-bold">
                                    {{ strtoupper(substr($report->reporter->name ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800">{{ $report->reporter->name ?? '—' }}</p>
                                    <p class="text-xs text-slate-500">{{ $report->reporter->email ?? '—' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Project Info --}}
                        @if($report->project)
                            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-5">
                                <h3 class="font-bold text-slate-800 mb-3">Proyek Terkait</h3>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-slate-500">Nama</span>
                                        <span class="font-semibold">{{ $report->project->project_name }}</span>
                                    </div>
                                    @if($report->project->owner)
                                        <div class="flex justify-between">
                                            <span class="text-slate-500">Company</span>
                                            <span class="font-semibold">{{ $report->project->owner->name ?? '—' }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- Reported User --}}
                        @if($report->reportedUser)
                            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-5">
                                <h3 class="font-bold text-slate-800 mb-3">User Dilaporkan</h3>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-bold">
                                        {{ strtoupper(substr($report->reportedUser->name ?? '?', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800">{{ $report->reportedUser->name }}</p>
                                        <p class="text-xs text-slate-500">{{ $report->reportedUser->email }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Penawaran Info --}}
                        @if($report->penawaran)
                            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-5">
                                <h3 class="font-bold text-slate-800 mb-3">Penawaran Terkait</h3>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-slate-500">Nilai</span>
                                        <span class="font-semibold">Rp {{ number_format($report->penawaran->harga_penawaran, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-500">Status</span>
                                        <span class="font-semibold">{{ $report->penawaran->status }}</span>
                                    </div>
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
