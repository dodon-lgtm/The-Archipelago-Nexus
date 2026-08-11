@extends('layouts.admin')

@section('title', 'Laporan')
@section('breadcrumb', 'Laporan')

@section('content')
    <div class="bg-white rounded-2xl border border-blue-100 p-4 mb-4 shadow-sm">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="text-xs font-semibold text-slate-600 mb-1 block">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full rounded-xl border-blue-100 bg-[#f6f9ff] px-4 py-2.5 text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none"
                       placeholder="Cari subjek, deskripsi, atau pelapor...">
            </div>
<div class="w-44">
                <label class="text-xs font-semibold text-slate-600 mb-1 block">Filter Kategori</label>
                <select name="category" class="w-full rounded-xl border-blue-100 bg-[#f6f9ff] px-4 py-2.5 text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none">
                    <option value="">Semua Kategori</option>
                    @foreach(\App\Models\Report::categories() as $cat)
                        <option value="{{ $cat }}" @selected(request('category') == $cat)>{{ \App\Models\Report::categoryLabel($cat) }}</option>
                    @endforeach
                </select>
            </div>
<div class="w-40">
                <label class="text-xs font-semibold text-slate-600 mb-1 block">Filter Status</label>
                <select name="status" class="w-full rounded-xl border-blue-100 bg-[#f6f9ff] px-4 py-2.5 text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none">
                    <option value="">Semua Status</option>
                    <option value="menunggu" @selected(request('status') == 'menunggu')>Menunggu</option>
                    <option value="ditinjau" @selected(request('status') == 'ditinjau')>Sedang Ditinjau</option>
                    <option value="menunggu-bukti" @selected(request('status') == 'menunggu-bukti')>Menunggu Bukti</option>
                    <option value="selesai" @selected(request('status') == 'selesai')>Selesai</option>
                    <option value="ditolak" @selected(request('status') == 'ditolak')>Ditolak</option>
                </select>
            </div>
            <div class="w-40">
                <label class="text-xs font-semibold text-slate-600 mb-1 block">Filter Target</label>
                <select name="target" class="w-full rounded-xl border-blue-100 bg-[#f6f9ff] px-4 py-2.5 text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none">
                    <option value="">Semua Target</option>
                    @foreach(\App\Models\Report::TARGETS as $tgt)
                        <option value="{{ $tgt }}" @selected(request('target') == $tgt)>{{ \App\Models\Report::targetLabel($tgt) }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-5 py-2.5 bg-blue-500 hover:bg-blue-600 text-white rounded-xl text-sm font-semibold transition"><i class="fa-solid fa-search mr-1"></i> Cari</button>
            <a href="{{ route('admin.reports.index') }}" class="px-4 py-2.5 bg-blue-50 hover:bg-slate-200 text-slate-600 rounded-xl text-sm font-semibold transition">Reset</a>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-[#f6f9ff] border-b border-blue-100">
<tr>
                        <th class="text-left px-5 py-3 font-bold text-slate-600 text-xs uppercase">Subjek</th>
                        <th class="text-left px-5 py-3 font-bold text-slate-600 text-xs uppercase">Pelapor</th>
                        <th class="text-left px-5 py-3 font-bold text-slate-600 text-xs uppercase">Kategori</th>
                        <th class="text-left px-5 py-3 font-bold text-slate-600 text-xs uppercase">Proyek Terkait</th>
                        <th class="text-center px-5 py-3 font-bold text-slate-600 text-xs uppercase">Status</th>
                        <th class="text-center px-5 py-3 font-bold text-slate-600 text-xs uppercase">Tanggal</th>
                        <th class="text-right px-5 py-3 font-bold text-slate-600 text-xs uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($reports as $report)
                        <tr class="hover:bg-[#f6f9ff] transition">
                            <td class="px-5 py-4 font-semibold text-slate-800 max-w-[200px] truncate">{{ $report->subject }}</td>
<td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-xs font-bold">{{ strtoupper(substr($report->reporter->name ?? '?', 0, 1)) }}</div>
                                    <div>
                                        <span class="block font-semibold text-slate-800">{{ $report->reporter->name ?? '—' }}</span>
                                        <span class="block text-[10px] font-bold uppercase tracking-wide text-slate-400">{{ $report->reporter->role ?? '' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="text-xs px-2.5 py-1 rounded-full font-semibold bg-blue-50 text-slate-600">{{ \App\Models\Report::categoryLabel($report->category) }}</span>
                            </td>
                            <td class="px-5 py-4 text-slate-600">
                                @if($report->workspace)
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600">
                                        <i class="fa-solid fa-layer-group"></i> {{ $report->workspace->project->project_name ?? 'Workspace' }}
                                    </span>
                                @else
                                    {{ $report->project->project_name ?? '—' }}
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="text-xs px-2.5 py-1 rounded-full font-semibold
                                    @if($report->status == 'menunggu') bg-amber-50 text-amber-600
                                    @elseif($report->status == 'ditinjau') bg-blue-50 text-blue-600
                                    @elseif($report->status == 'menunggu-bukti') bg-violet-50 text-violet-600
                                    @elseif($report->status == 'selesai') bg-emerald-50 text-emerald-600
                                    @else bg-red-50 text-red-600 @endif">{{ \App\Models\Report::statusLabel($report->status) }}</span>
                                <span class="block text-[10px] mt-1 text-slate-400 font-medium">{{ \App\Models\Report::targetLabel($report->target) }}</span>
                            </td>
                            <td class="px-5 py-4 text-center text-xs text-slate-500">{{ $report->created_at->format('d M Y') }}</td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('admin.reports.show', $report) }}" class="px-3 py-1.5 text-xs font-semibold bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-12 text-center text-sm text-slate-400">Belum ada laporan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $reports->links() }}</div>
@endsection
