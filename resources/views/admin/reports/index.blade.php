@extends('layouts.admin')

@section('title', 'Laporan')
@section('breadcrumb', 'Laporan')

@section('content')
    <div class="bg-white rounded-2xl border border-slate-200 p-4 mb-4 shadow-sm">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="text-xs font-semibold text-slate-600 mb-1 block">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 outline-none"
                       placeholder="Cari subjek, deskripsi, atau pelapor...">
            </div>
            <div class="w-40">
                <label class="text-xs font-semibold text-slate-600 mb-1 block">Filter Status</label>
                <select name="status" class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 outline-none">
                    <option value="">Semua Status</option>
                    <option value="menunggu" @selected(request('status') == 'menunggu')>Menunggu</option>
                    <option value="diproses" @selected(request('status') == 'diproses')>Diproses</option>
                    <option value="selesai" @selected(request('status') == 'selesai')>Selesai</option>
                    <option value="ditolak" @selected(request('status') == 'ditolak')>Ditolak</option>
                </select>
            </div>
            <button type="submit" class="px-5 py-2.5 bg-cyan-500 hover:bg-cyan-600 text-white rounded-xl text-sm font-semibold transition"><i class="fa-solid fa-search mr-1"></i> Cari</button>
            <a href="{{ route('admin.reports.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-sm font-semibold transition">Reset</a>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="text-left px-5 py-3 font-bold text-slate-600 text-xs uppercase">Subjek</th>
                        <th class="text-left px-5 py-3 font-bold text-slate-600 text-xs uppercase">Pelapor</th>
                        <th class="text-left px-5 py-3 font-bold text-slate-600 text-xs uppercase">Proyek Terkait</th>
                        <th class="text-center px-5 py-3 font-bold text-slate-600 text-xs uppercase">Status</th>
                        <th class="text-center px-5 py-3 font-bold text-slate-600 text-xs uppercase">Tanggal</th>
                        <th class="text-right px-5 py-3 font-bold text-slate-600 text-xs uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($reports as $report)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-5 py-4 font-semibold text-slate-800 max-w-[200px] truncate">{{ $report->subject }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-xs font-bold">{{ strtoupper(substr($report->reporter->name ?? '?', 0, 1)) }}</div>
                                    <span class="font-semibold text-slate-800">{{ $report->reporter->name ?? '—' }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-slate-600">{{ $report->project->project_name ?? '—' }}</td>
                            <td class="px-5 py-4 text-center">
                                <span class="text-xs px-2.5 py-1 rounded-full font-semibold
                                    @if($report->status == 'menunggu') bg-amber-50 text-amber-600
                                    @elseif($report->status == 'diproses') bg-blue-50 text-blue-600
                                    @elseif($report->status == 'selesai') bg-emerald-50 text-emerald-600
                                    @else bg-red-50 text-red-600 @endif">{{ ucfirst($report->status) }}</span>
                            </td>
                            <td class="px-5 py-4 text-center text-xs text-slate-500">{{ $report->created_at->format('d M Y') }}</td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('admin.reports.show', $report) }}" class="px-3 py-1.5 text-xs font-semibold bg-cyan-50 text-cyan-600 hover:bg-cyan-100 rounded-lg transition">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-12 text-center text-sm text-slate-400">Belum ada laporan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $reports->links() }}</div>
@endsection
