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
                    </div>
                    <span class="text-xs px-3 py-1.5 rounded-full font-semibold
                        @if($report->status == 'menunggu') bg-amber-50 text-amber-600
                        @elseif($report->status == 'diproses') bg-blue-50 text-blue-600
                        @elseif($report->status == 'selesai') bg-emerald-50 text-emerald-600
                        @else bg-red-50 text-red-600 @endif">{{ ucfirst($report->status) }}</span>
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
                                <option value="diproses" @selected($report->status == 'diproses')>Diproses</option>
                                <option value="selesai">Selesai / Ditutup</option>
                                <option value="ditolak">Ditolak</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="text-xs font-semibold text-slate-600 mb-1 block">Catatan Admin (opsional)</label>
                            <textarea name="admin_note" rows="3" class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 outline-none" placeholder="Tambahkan catatan...">{{ old('admin_note', $report->admin_note) }}</textarea>
                        </div>
                        <button type="submit" class="px-6 py-2.5 bg-cyan-500 hover:bg-cyan-600 text-white rounded-xl text-sm font-semibold transition"><i class="fa-solid fa-check mr-1"></i> Update Status</button>
                    </form>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <h3 class="font-bold text-slate-800 mb-3">Pelapor</h3>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-lg font-bold">{{ strtoupper(substr($report->reporter->name ?? '?', 0, 1)) }}</div>
                    <div><p class="font-bold text-slate-800">{{ $report->reporter->name ?? '—' }}</p><p class="text-xs text-slate-500">{{ $report->reporter->email ?? '—' }}</p></div>
                </div>
            </div>

            @if($report->project)
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
                        <div><p class="font-bold text-slate-800">{{ $report->reportedUser->name }}</p><p class="text-xs text-slate-500">{{ $report->reportedUser->email }}</p></div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
