@extends('layouts.admin')

@section('title', 'Hasil Pekerjaan')
@section('breadcrumb', 'Hasil Pekerjaan')

@section('content')
<x-admin.page-header icon="fa-layer-group" title="Hasil Pekerjaan" description="Pantau progress dan status workspace"
        :count="$workspaces->total()" countLabel="workspace" countIcon="fa-diagram-project" />
    <div class="bg-white rounded-2xl border border-blue-100 p-4 mb-4 shadow-sm">
        <form method="GET" action="{{ route('admin.hasil-pekerjaan.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="text-[11px] font-bold uppercase tracking-wide text-slate-500 mb-1.5 block">
                    <i class="fa-solid fa-magnifying-glass mr-1 text-blue-400"></i> Cari
                </label>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full rounded-xl border-blue-100 bg-[#f6f9ff] px-4 py-2.5 text-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:bg-white"
                       placeholder="Cari proyek, company, atau freelancer...">
            </div>
            <div class="w-full sm:w-44">
                <label class="text-[11px] font-bold uppercase tracking-wide text-slate-500 mb-1.5 block">
                    <i class="fa-solid fa-circle-dot mr-1 text-blue-400"></i> Filter Status
                </label>
                <select name="status" class="w-full rounded-xl border-blue-100 bg-[#f6f9ff] px-4 py-2.5 text-sm outline-none transition cursor-pointer focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:bg-white">
                    <option value="">Semua Status</option>
                    <option value="Sedang Dikerjakan" @selected(request('status') == 'Sedang Dikerjakan')>Sedang Dikerjakan</option>
                    <option value="Menunggu Revisi" @selected(request('status') == 'Menunggu Revisi')>Menunggu Revisi</option>
                    <option value="Selesai" @selected(request('status') == 'Selesai')>Selesai</option>
                </select>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 px-5 py-2.5 bg-blue-500 hover:bg-blue-600 active:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow-sm transition"><i class="fa-solid fa-search"></i> Cari</button>
                <a href="{{ route('admin.hasil-pekerjaan.index') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-sm font-semibold transition"><i class="fa-solid fa-rotate-left text-xs"></i> Reset</a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[780px]">
                <thead class="bg-[#f6f9ff] border-b border-blue-100">
                    <tr>
                        <th class="text-left px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Proyek</th>
                        <th class="text-left px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Company</th>
                        <th class="text-left px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Freelancer</th>
                        <th class="text-center px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Progress</th>
                        <th class="text-center px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Status</th>
                        <th class="text-center px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Mulai</th>
                        <th class="text-right px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($workspaces as $workspace)
                        <tr class="hover:bg-[#f6f9ff]/70 transition-colors">
                            <td class="px-5 py-4 max-w-[220px]">
                                @if ($workspace->project)
                                    <a href="{{ route('admin.projects.show', $workspace->project) }}"
                                        class="font-semibold text-slate-800 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 truncate block transition"
                                        title="{{ $workspace->project->project_name }}">{{ $workspace->project->project_name }}</a>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <x-admin.user-cell :user="$workspace->company" :name="$workspace->company->name ?? '—'" />
                            </td>
                            <td class="px-5 py-4">
                                <x-admin.user-cell :user="$workspace->freelancer" avatarClass="bg-gradient-to-br from-emerald-100 to-emerald-50 ring-1 ring-emerald-100 text-emerald-600" />
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2 justify-center">
                                    <div class="w-20 bg-blue-50 rounded-full h-2 overflow-hidden shrink-0">
                                        <div class="h-full rounded-full transition-all duration-500
                                            @if(($workspace->latestProgress->progress ?? 0) >= 100) bg-emerald-500
                                            @elseif(($workspace->latestProgress->progress ?? 0) >= 50) bg-blue-500
                                            @else bg-amber-500 @endif"
                                            style="width: {{ $workspace->latestProgress->progress ?? 0 }}%"></div>
                                    </div>
                                    <span class="text-xs font-bold text-slate-600 w-9 text-left">{{ $workspace->latestProgress->progress ?? 0 }}%</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-block text-xs px-2.5 py-1 rounded-full border font-semibold whitespace-nowrap
                                    @if($workspace->status == 'Selesai') bg-emerald-50 text-emerald-600 border-emerald-100
                                    @elseif($workspace->status == 'Menunggu Revisi') bg-amber-50 text-amber-600 border-amber-100
                                    @else bg-blue-50 text-blue-600 border-blue-100 @endif">{{ $workspace->status }}</span>
                            </td>
                            <td class="px-5 py-4 text-center text-xs text-slate-500 whitespace-nowrap">{{ $workspace->created_at->format('d M Y') }}</td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('admin.hasil-pekerjaan.show', $workspace) }}" class="inline-block px-3 py-1.5 text-xs font-semibold bg-blue-50 text-blue-600 hover:bg-blue-100 active:bg-blue-200 rounded-lg transition">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-14 h-14 rounded-2xl bg-blue-50 border border-blue-100 text-blue-400 flex items-center justify-center text-xl">
                                        <i class="fa-solid fa-diagram-project"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-600">Belum ada hasil pekerjaan</p>
                                        <p class="text-xs text-slate-400 mt-1">Coba ubah kata kunci pencarian atau filter status.</p>
                                    </div>
                                    <a href="{{ route('admin.hasil-pekerjaan.index') }}" class="mt-1 inline-flex items-center gap-1.5 px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-xl text-xs font-semibold transition">
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

    <x-admin.pagination :paginator="$workspaces" />
@endsection
