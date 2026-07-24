@extends('layouts.admin')

@section('title', 'Proyek')
@section('breadcrumb', 'Proyek')

@section('content')
    {{-- Search & Filter --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-4 mb-4 shadow-sm">
        <form method="GET" action="{{ route('admin.projects.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="text-xs font-semibold text-slate-600 mb-1 block">Cari Proyek</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 outline-none"
                       placeholder="Nama proyek...">
            </div>
            <div class="w-40">
                <label class="text-xs font-semibold text-slate-600 mb-1 block">Filter Status</label>
                <select name="status" class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 outline-none">
                    <option value="">Semua Status</option>
                    <option value="Open" @selected(request('status') == 'Open')>Open</option>
                    <option value="Closed" @selected(request('status') == 'Closed')>Closed</option>
                </select>
            </div>
            <div class="w-48">
                <label class="text-xs font-semibold text-slate-600 mb-1 block">Filter Company</label>
                <select name="company_id" class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 outline-none">
                    <option value="">Semua Company</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" @selected(request('company_id') == $company->id)>{{ $company->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-5 py-2.5 bg-cyan-500 hover:bg-cyan-600 text-white rounded-xl text-sm font-semibold transition">
                <i class="fa-solid fa-search mr-1"></i> Cari
            </button>
            <a href="{{ route('admin.projects.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-sm font-semibold transition">
                Reset
            </a>
        </form>
    </div>

    {{-- Projects Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="text-left px-5 py-3 font-bold text-slate-600 text-xs uppercase">Nama Proyek</th>
                        <th class="text-left px-5 py-3 font-bold text-slate-600 text-xs uppercase">Company</th>
                        <th class="text-left px-5 py-3 font-bold text-slate-600 text-xs uppercase">Kategori</th>
                        <th class="text-right px-5 py-3 font-bold text-slate-600 text-xs uppercase">Budget</th>
                        <th class="text-center px-5 py-3 font-bold text-slate-600 text-xs uppercase">Status</th>
                        <th class="text-center px-5 py-3 font-bold text-slate-600 text-xs uppercase">Dibuat</th>
                        <th class="text-right px-5 py-3 font-bold text-slate-600 text-xs uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($projects as $project)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-5 py-4 font-semibold text-slate-800">{{ $project->project_name }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $project->owner->name ?? '—' }}</td>
                            <td class="px-5 py-4">
                                <span class="text-xs px-2 py-1 rounded-full bg-slate-100 text-slate-600">{{ $project->category->name ?? 'Tanpa Kategori' }}</span>
                            </td>
                            <td class="px-5 py-4 text-right font-semibold text-slate-700">{{ $project->budget ? 'Rp ' . number_format($project->budget) : '—' }}</td>
                            <td class="px-5 py-4 text-center">
                                <span class="text-xs px-2.5 py-1 rounded-full font-semibold
                                    @if($project->status == 'Open') bg-emerald-50 text-emerald-600
                                    @else bg-slate-100 text-slate-600 @endif">{{ $project->status }}</span>
                            </td>
                            <td class="px-5 py-4 text-center text-xs text-slate-500">{{ $project->created_at->format('d M Y') }}</td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.projects.show', $project) }}" class="px-3 py-1.5 text-xs font-semibold bg-cyan-50 text-cyan-600 hover:bg-cyan-100 rounded-lg transition">Detail</a>
                                    <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" onsubmit="return confirm('Hapus proyek {{ $project->project_name }}?')" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 text-xs font-semibold bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-12 text-center text-sm text-slate-400">Belum ada proyek.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $projects->links() }}</div>
@endsection
