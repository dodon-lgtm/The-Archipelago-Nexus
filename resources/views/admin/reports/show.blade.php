@extends('layouts.admin')

@section('title', 'Detail Laporan')
@section('breadcrumb', 'Detail Laporan')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.reports.index') }}" class="text-sm text-cyan-600 hover:text-cyan-700 font-semibold inline-flex items-center gap-1">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-start justify-between mb-4">
<div>
                        <h2 class="text-xl font-bold text-slate-800">{{ $report->subject }}</h2>
                        <p class="text-sm text-slate-500 mt-1">Laporan #{{ $report->id }} oleh {{ $report->reporter->name ?? '—' }}</p>
                        <div class="flex items-center gap-2 mt-2 flex-wrap">
                            <span class="text-xs px-2.5 py-1 rounded-full font-semibold bg-slate-100 text-slate-600">{{ \App\Models\Report::categoryLabel($report->category) }}</span>
                            <span class="text-xs px-2.5 py-1 rounded-full font-semibold bg-cyan-50 text-cyan-700 border border-cyan-100">
                                Target: {{ \App\Models\Report::targetLabel($report->target) }}
                            </span>
                        </div>
                    </div>
                    <span class="text-xs px-3 py-1.5 rounded-full font-semibold
                        @if($report->status == 'menunggu') bg-amber-50 text-amber-600
                        @elseif($report->status == 'ditinjau') bg-blue-50 text-blue-600
                        @elseif($report->status == 'menunggu-bukti') bg-violet-50 text-violet-600
                        @elseif($report->status == 'selesai') bg-emerald-50 text-emerald-600
                        @else bg-red-50 text-red-600 @endif">{{ \App\Models\Report::statusLabel($report->status) }}</span>
                </div>

                <div class="mb-4">
                    <p class="text-xs text-slate-500 font-semibold mb-1">Deskripsi Laporan</p>
                    <div class="bg-slate-50 rounded-xl p-4 text-sm text-slate-700 leading-relaxed">{{ $report->description }}</div>
                </div>

                @if($report->admin_note)
                    <div>
                        <p class="text-xs text-slate-500 font-semibold mb-1">Catatan Admin</p>
                        <div class="bg-cyan-50 rounded-xl p-4 text-sm text-cyan-700 leading-relaxed">{{ $report->admin_note }}</div>
                    </div>
                @endif
            </div>

@if($report->status !== 'selesai' && $report->status !== 'ditolak')
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <h2 class="font-bold text-slate-800 mb-4">Update Status Laporan</h2>
                    <form method="POST" action="{{ route('admin.reports.update-status', $report) }}">
                        @csrf
                        <div class="mb-4">
                            <label class="text-xs font-semibold text-slate-600 mb-1 block">Status</label>
                            <select name="status" class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 outline-none">
                                <option value="ditinjau" @selected($report->status == 'ditinjau')>Sedang Ditinjau</option>
                                <option value="menunggu-bukti" @selected($report->status == 'menunggu-bukti')>Menunggu Bukti Tambahan</option>
                                <option value="selesai" @selected($report->status == 'selesai')>Selesai / Ditutup</option>
                                <option value="ditolak" @selected($report->status == 'ditolak')>Ditolak</option>
                            </select>
                            <p class="text-[11px] text-slate-400 mt-1">Pilih "Menunggu Bukti Tambahan" untuk meminta pelapor mengunggah bukti/screenshot pendukung.</p>
                        </div>
                        <div class="mb-4">
                            <label class="text-xs font-semibold text-slate-600 mb-1 block">Catatan Admin (opsional)</label>
                            <textarea name="admin_note" rows="3" class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 outline-none" placeholder="Tambahkan catatan...">{{ old('admin_note', $report->admin_note) }}</textarea>
                        </div>
                        <button type="submit" class="px-6 py-2.5 bg-cyan-500 hover:bg-cyan-600 text-white rounded-xl text-sm font-semibold transition"><i class="fa-solid fa-check mr-1"></i> Update Status</button>
                    </form>
                </div>
            @endif

            {{-- Lampiran / Bukti --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h2 class="font-bold text-slate-800 mb-4">Lampiran / Bukti ({{ $report->attachments->count() }})</h2>
                @if($report->attachments->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($report->attachments as $attachment)
                            <a href="{{ $attachment->file_url }}" target="_blank"
                               class="group relative rounded-xl border border-slate-200 overflow-hidden bg-slate-50 hover:border-cyan-300 transition">
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
                @else
                    <p class="text-sm text-slate-400 text-center py-4">Belum ada lampiran/bukti.</p>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <h3 class="font-bold text-slate-800 mb-3">Pelapor</h3>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-lg font-bold">{{ strtoupper(substr($report->reporter->name ?? '?', 0, 1)) }}</div>
                    <div><p class="font-bold text-slate-800">{{ $report->reporter->name ?? '—' }}</p><p class="text-xs text-slate-500">{{ $report->reporter->email ?? '—' }}</p></div>
                </div>
            </div>

            @if($report->project && !$report->penawaran_id)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                    <h3 class="font-bold text-slate-800 mb-3">Proyek Terkait</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between"><span class="text-slate-500">Nama</span><span class="font-semibold">{{ $report->project->project_name }}</span></div>
                        <div class="flex justify-between"><span class="text-slate-500">Company</span><span class="font-semibold">{{ $report->project->owner->name ?? '—' }}</span></div>
                    </div>
                    <a href="{{ route('admin.projects.show', $report->project) }}" class="mt-3 inline-block text-xs text-cyan-600 hover:text-cyan-700 font-semibold">Lihat Detail →</a>
                </div>
            @endif

@if($report->reportedUser)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                    <h3 class="font-bold text-slate-800 mb-3">User Dilaporkan</h3>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-bold">{{ strtoupper(substr($report->reportedUser->name ?? '?', 0, 1)) }}</div>
                        <div>
                            <p class="font-bold text-slate-800">{{ $report->reportedUser->name }}</p>
                            <p class="text-xs text-slate-500">{{ $report->reportedUser->email }}</p>
                            <span class="mt-1 inline-block text-[10px] font-bold uppercase tracking-wide px-2 py-0.5 rounded-full
                                @if($report->reportedUser->role == 'company') bg-blue-50 text-blue-600
                                @elseif($report->reportedUser->role == 'freelancer') bg-cyan-50 text-cyan-600
                                @else bg-slate-100 text-slate-600 @endif">{{ ucfirst($report->reportedUser->role ?? '—') }}</span>
                        </div>
                    </div>
                </div>
            @endif

            @if($report->workspace)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                    <h3 class="font-bold text-slate-800 mb-3">Workspace Terkait</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Proyek</span>
                            <span class="font-semibold text-right">{{ $report->workspace->project->project_name ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Status</span>
                            <span class="font-semibold">{{ $report->workspace->status ?? '—' }}</span>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Penanganan Laporan (V2) --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <h3 class="font-bold text-slate-800 mb-3">Penanganan</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Ditangani Oleh</span>
                        <span class="font-semibold">{{ $report->handledBy->name ?? 'Belum ada' }}</span>
                    </div>
                    @if($report->resolved_at)
                        <div class="flex justify-between">
                            <span class="text-slate-500">Diselesaikan</span>
                            <span class="font-semibold">{{ $report->resolved_at->format('d M Y H:i') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Aksi Admin --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <h3 class="font-bold text-slate-800 mb-4">Aksi Admin</h3>

                @if($report->penawaran_id)
                    {{-- Laporan memiliki penawaran -> tombol Hapus Penawaran --}}
                    <form method="POST" action="{{ route('admin.reports.destroy-penawaran', $report) }}" onsubmit="return confirm('Yakin ingin menghapus penawaran ini? Penawaran yang dihapus tidak dapat dikembalikan.')">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-xl text-sm font-semibold transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-trash-can"></i>
                            Hapus Penawaran
                        </button>
                    </form>
                @elseif($report->project_id)
                    {{-- Laporan memiliki project (tanpa penawaran) -> tombol Hapus Project --}}
                    <form method="POST" action="{{ route('admin.reports.destroy-project', $report) }}" onsubmit="return confirm('Yakin ingin menghapus project ini? Project yang dihapus tidak dapat dikembalikan.')">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-xl text-sm font-semibold transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-trash-can"></i>
                            Hapus Project
                        </button>
                    </form>
                @else
                    <p class="text-sm text-slate-400 text-center py-2">Tidak ada aksi yang tersedia untuk laporan ini.</p>
                @endif
            </div>
        </div>
    </div>
@endsection