@extends('layouts.admin')

@section('title', 'Proyek')
@section('breadcrumb', 'Proyek')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center shadow-sm">
                <i class="fa-solid fa-folder-open"></i>
            </div>
            <div>
                <h1 class="text-lg font-bold text-slate-800 leading-tight">Daftar Proyek</h1>
                <p class="text-xs text-slate-500">Kelola seluruh proyek di platform</p>
            </div>
        </div>
        <span class="inline-flex items-center gap-1.5 self-start sm:self-auto px-3 py-1.5 rounded-full bg-blue-50 border border-blue-100 text-xs font-semibold text-blue-600">
            <i class="fa-solid fa-layer-group text-[10px]"></i> Total {{ $projects->total() }} proyek
        </span>
    </div>

    {{-- Search & Filter --}}
    <div class="bg-white rounded-2xl border border-blue-100 p-4 mb-4 shadow-sm">
        <form method="GET" action="{{ route('admin.projects.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="text-[11px] font-bold uppercase tracking-wide text-slate-500 mb-1.5 block">
                    <i class="fa-solid fa-magnifying-glass mr-1 text-blue-400"></i> Cari Proyek
                </label>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full rounded-xl border-blue-100 bg-[#f6f9ff] px-4 py-2.5 text-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:bg-white"
                       placeholder="Nama proyek...">
            </div>
            <div class="w-full sm:w-40">
                <label class="text-[11px] font-bold uppercase tracking-wide text-slate-500 mb-1.5 block">
                    <i class="fa-solid fa-circle-dot mr-1 text-blue-400"></i> Filter Status
                </label>
                <select name="status" class="w-full rounded-xl border-blue-100 bg-[#f6f9ff] px-4 py-2.5 text-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:bg-white cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="open" @selected(request('status') == 'open')>Open</option>
                    <option value="closed" @selected(request('status') == 'closed')>Tutup</option>
                    <option value="archived" @selected(request('status') == 'archived')>Arsip</option>
                </select>
            </div>
            <div class="w-full sm:w-48">
                <label class="text-[11px] font-bold uppercase tracking-wide text-slate-500 mb-1.5 block">
                    <i class="fa-solid fa-building mr-1 text-blue-400"></i> Filter Company
                </label>
                <select name="company_id" class="w-full rounded-xl border-blue-100 bg-[#f6f9ff] px-4 py-2.5 text-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:bg-white cursor-pointer">
                    <option value="">Semua Company</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" @selected(request('company_id') == $company->id)>{{ $company->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 px-5 py-2.5 bg-blue-500 hover:bg-blue-600 active:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow-sm hover:shadow transition">
                    <i class="fa-solid fa-search"></i> Cari
                </button>
                <a href="{{ route('admin.projects.index') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-sm font-semibold transition">
                    <i class="fa-solid fa-rotate-left text-xs"></i> Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Projects Table --}}
    <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[820px]">
                <thead class="bg-[#f6f9ff] border-b border-blue-100">
                    <tr>
                        <th class="text-left px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Nama Proyek</th>
                        <th class="text-left px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Company</th>
                        <th class="text-left px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Kategori</th>
                        <th class="text-right px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Budget</th>
                        <th class="text-center px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Status</th>
                        <th class="text-center px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Dibuat</th>
                        <th class="text-right px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($projects as $project)
                        <tr class="hover:bg-[#f6f9ff]/70 transition-colors">
                            <td class="px-5 py-4 max-w-[240px]">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-100 to-blue-50 ring-1 ring-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-briefcase text-xs"></i>
                                    </div>
                                    <span class="font-semibold text-slate-800 truncate">{{ $project->project_name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-slate-600">{{ $project->owner->name ?? '—' }}</td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1.5 text-xs px-2.5 py-1 rounded-full border bg-slate-50 text-slate-600 border-slate-200 font-semibold whitespace-nowrap">
                                    <i class="fa-solid fa-tag text-[10px] text-slate-400"></i> {{ $project->category->name ?? 'Tanpa Kategori' }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right font-bold text-slate-700 whitespace-nowrap">{{ $project->budget ? 'Rp ' . number_format($project->budget) : '—' }}</td>
                            <td class="px-5 py-4 text-center">
                                @php($status = $project->status ?? 'open')
                                <span class="inline-flex items-center gap-1.5 text-xs px-2.5 py-1 rounded-full border font-semibold whitespace-nowrap
                                    @if($status == 'open') bg-emerald-50 text-emerald-600 border-emerald-100
                                    @elseif($status == 'closed') bg-red-50 text-red-600 border-red-100
                                    @elseif($status == 'archived') bg-amber-50 text-amber-600 border-amber-100
                                    @else bg-blue-50 text-slate-600 border-blue-100 @endif">
                                    <span class="w-1.5 h-1.5 rounded-full
                                        @if($status == 'open') bg-emerald-500
                                        @elseif($status == 'closed') bg-red-500
                                        @elseif($status == 'archived') bg-amber-500
                                        @else bg-blue-400 @endif"></span>
                                    {{ \App\Models\Project::statusLabel($status) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center text-xs text-slate-500 whitespace-nowrap">
                                <i class="fa-regular fa-calendar mr-1 text-slate-300"></i>{{ $project->created_at->format('d M Y') }}
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex flex-wrap items-center justify-end gap-2">
                                    <a href="{{ route('admin.projects.show', $project) }}" class="px-3 py-1.5 text-xs font-semibold bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition">Detail</a>
                                    <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" onsubmit="return adminConfirm('Hapus proyek {{ $project->project_name }}?', this)" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 text-xs font-semibold bg-red-50 text-red-600 hover:bg-red-100 active:bg-red-200 rounded-lg transition">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-14 h-14 rounded-2xl bg-blue-50 border border-blue-100 text-blue-400 flex items-center justify-center text-xl">
                                        <i class="fa-solid fa-folder-open"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-600">Belum ada proyek</p>
                                        <p class="text-xs text-slate-400 mt-1">Coba ubah kata kunci pencarian atau filter.</p>
                                    </div>
                                    <a href="{{ route('admin.projects.index') }}" class="mt-1 inline-flex items-center gap-1.5 px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-xl text-xs font-semibold transition">
                                        <i class="fa-solid fa-rotate-left text-[10px]"></i> Reset Filter
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5 flex justify-center">
        <div class="[&_nav]:!m-0 [&_nav]:!p-0">
            {{ $projects->links() }}
        </div>
    </div>
@endsection
