@extends('layouts.admin')

@section('title', 'Hasil Pekerjaan')
@section('breadcrumb', 'Hasil Pekerjaan')

@section('content')
    <div class="bg-white rounded-2xl border border-blue-100 p-4 mb-4 shadow-sm">
        <form method="GET" action="{{ route('admin.hasil-pekerjaan.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="text-xs font-semibold text-slate-600 mb-1 block">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full rounded-xl border-blue-100 bg-[#f6f9ff] px-4 py-2.5 text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none"
                       placeholder="Cari proyek, company, atau freelancer...">
            </div>
            <div class="w-44">
                <label class="text-xs font-semibold text-slate-600 mb-1 block">Filter Status</label>
                <select name="status" class="w-full rounded-xl border-blue-100 bg-[#f6f9ff] px-4 py-2.5 text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none">
                    <option value="">Semua Status</option>
                    <option value="Sedang Dikerjakan" @selected(request('status') == 'Sedang Dikerjakan')>Sedang Dikerjakan</option>
                    <option value="Menunggu Revisi" @selected(request('status') == 'Menunggu Revisi')>Menunggu Revisi</option>
                    <option value="Selesai" @selected(request('status') == 'Selesai')>Selesai</option>
                </select>
            </div>
            <button type="submit" class="px-5 py-2.5 bg-blue-500 hover:bg-blue-600 text-white rounded-xl text-sm font-semibold transition"><i class="fa-solid fa-search mr-1"></i> Cari</button>
            <a href="{{ route('admin.hasil-pekerjaan.index') }}" class="px-4 py-2.5 bg-blue-50 hover:bg-slate-200 text-slate-600 rounded-xl text-sm font-semibold transition">Reset</a>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-[#f6f9ff] border-b border-blue-100">
                    <tr>
                        <th class="text-left px-5 py-3 font-bold text-slate-600 text-xs uppercase">Proyek</th>
                        <th class="text-left px-5 py-3 font-bold text-slate-600 text-xs uppercase">Company</th>
                        <th class="text-left px-5 py-3 font-bold text-slate-600 text-xs uppercase">Freelancer</th>
                        <th class="text-center px-5 py-3 font-bold text-slate-600 text-xs uppercase">Progress</th>
                        <th class="text-center px-5 py-3 font-bold text-slate-600 text-xs uppercase">Status</th>
                        <th class="text-center px-5 py-3 font-bold text-slate-600 text-xs uppercase">Mulai</th>
                        <th class="text-right px-5 py-3 font-bold text-slate-600 text-xs uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($workspaces as $workspace)
                        <tr class="hover:bg-[#f6f9ff] transition">
                            <td class="px-5 py-4 font-semibold text-slate-800">{{ $workspace->project->project_name ?? '—' }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $workspace->company->name ?? '—' }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs font-bold">{{ strtoupper(substr($workspace->freelancer->name ?? '?', 0, 1)) }}</div>
                                    <span class="font-semibold text-slate-700">{{ $workspace->freelancer->name ?? '—' }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <div class="flex items-center gap-2 justify-center">
                                    <div class="w-20 bg-blue-50 rounded-full h-2 overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-500
                                            @if(($workspace->latestProgress->progress ?? 0) >= 100) bg-emerald-500
                                            @elseif(($workspace->latestProgress->progress ?? 0) >= 50) bg-blue-500
                                            @else bg-amber-500 @endif"
                                            style="width: {{ $workspace->latestProgress->progress ?? 0 }}%"></div>
                                    </div>
                                    <span class="text-xs font-semibold text-slate-600">{{ $workspace->latestProgress->progress ?? 0 }}%</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="text-xs px-2.5 py-1 rounded-full font-semibold
                                    @if($workspace->status == 'Selesai') bg-emerald-50 text-emerald-600
                                    @elseif($workspace->status == 'Menunggu Revisi') bg-amber-50 text-amber-600
                                    @else bg-blue-50 text-blue-600 @endif">{{ $workspace->status }}</span>
                            </td>
                            <td class="px-5 py-4 text-center text-xs text-slate-500">{{ $workspace->created_at->format('d M Y') }}</td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('admin.hasil-pekerjaan.show', $workspace) }}" class="px-3 py-1.5 text-xs font-semibold bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-12 text-center text-sm text-slate-400">Belum ada hasil pekerjaan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $workspaces->links() }}</div>
@endsection
