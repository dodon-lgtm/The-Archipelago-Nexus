@extends('layouts.admin')

@section('title', 'Laporan')
@section('breadcrumb', 'Laporan')

@section('content')
<x-admin.page-header icon="fa-flag" title="Laporan" description="Kelola laporan pengguna platform"
        :count="$reports->total()" countLabel="laporan" countIcon="fa-flag" />
    <div class="bg-white rounded-2xl border border-blue-100 p-4 mb-4 shadow-sm">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="text-[11px] font-bold uppercase tracking-wide text-slate-500 mb-1.5 block">
                    <i class="fa-solid fa-magnifying-glass mr-1 text-blue-400"></i> Cari
                </label>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full rounded-xl border-blue-100 bg-[#f6f9ff] px-4 py-2.5 text-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:bg-white"
                       placeholder="Cari subjek, deskripsi, atau pelapor...">
            </div>
            <div class="w-full sm:w-44">
                <label class="text-[11px] font-bold uppercase tracking-wide text-slate-500 mb-1.5 block">
                    <i class="fa-solid fa-tag mr-1 text-blue-400"></i> Filter Kategori
                </label>
                <select name="category" class="w-full rounded-xl border-blue-100 bg-[#f6f9ff] px-4 py-2.5 text-sm outline-none transition cursor-pointer focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:bg-white">
                    <option value="">Semua Kategori</option>
                    @foreach(\App\Models\Report::categories() as $cat)
                        <option value="{{ $cat }}" @selected(request('category') == $cat)>{{ \App\Models\Report::categoryLabel($cat) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full sm:w-40">
                <label class="text-[11px] font-bold uppercase tracking-wide text-slate-500 mb-1.5 block">
                    <i class="fa-solid fa-circle-dot mr-1 text-blue-400"></i> Filter Status
                </label>
                <select name="status" class="w-full rounded-xl border-blue-100 bg-[#f6f9ff] px-4 py-2.5 text-sm outline-none transition cursor-pointer focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:bg-white">
                    <option value="">Semua Status</option>
                    <option value="menunggu" @selected(request('status') == 'menunggu')>Menunggu</option>
                    <option value="ditinjau" @selected(request('status') == 'ditinjau')>Sedang Ditinjau</option>
                    <option value="menunggu-bukti" @selected(request('status') == 'menunggu-bukti')>Menunggu Bukti</option>
                    <option value="ditangani" @selected(request('status') == 'ditangani')>Ditangani</option>
                    <option value="selesai" @selected(request('status') == 'selesai')>Selesai</option>
                    <option value="ditolak" @selected(request('status') == 'ditolak')>Ditolak</option>
                </select>
            </div>
            <div class="w-full sm:w-40">
                <label class="text-[11px] font-bold uppercase tracking-wide text-slate-500 mb-1.5 block">
                    <i class="fa-solid fa-bullseye mr-1 text-blue-400"></i> Filter Target
                </label>
                <select name="target" class="w-full rounded-xl border-blue-100 bg-[#f6f9ff] px-4 py-2.5 text-sm outline-none transition cursor-pointer focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:bg-white">
                    <option value="">Semua Target</option>
                    @foreach(\App\Models\Report::TARGETS as $tgt)
                        <option value="{{ $tgt }}" @selected(request('target') == $tgt)>{{ \App\Models\Report::targetLabel($tgt) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 px-5 py-2.5 bg-blue-500 hover:bg-blue-600 active:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow-sm transition"><i class="fa-solid fa-search"></i> Cari</button>
                <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-sm font-semibold transition"><i class="fa-solid fa-rotate-left text-xs"></i> Reset</a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[860px]">
                <thead class="bg-[#f6f9ff] border-b border-blue-100">
                    <tr>
                        <th class="text-left px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Subjek</th>
                        <th class="text-left px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Pelapor</th>
                        <th class="text-left px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Kategori</th>
                        <th class="text-left px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Proyek Terkait</th>
                        <th class="text-center px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Status</th>
                        <th class="text-center px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Tanggal</th>
                        <th class="text-right px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($reports as $report)
                        <tr class="hover:bg-[#f6f9ff]/70 transition-colors">
                            <td class="px-5 py-4 font-semibold text-slate-800 max-w-[200px] truncate">{{ $report->subject }}</td>
                            <td class="px-5 py-4">
                                <x-admin.user-cell :user="$report->reporter" avatarClass="bg-red-100 text-red-600" :sub="strtoupper($report->reporter->role ?? '')" />
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-block text-xs px-2.5 py-1 rounded-full border border-slate-200 bg-slate-50 text-slate-600 font-semibold whitespace-nowrap">{{ \App\Models\Report::categoryLabel($report->category) }}</span>
                            </td>
                            <td class="px-5 py-4 text-slate-600">
                                @if($report->workspace && $report->workspace->project)
                                    <a href="{{ route('admin.projects.show', $report->workspace->project) }}"
                                        class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline transition">
                                        <i class="fa-solid fa-layer-group"></i> {{ $report->workspace->project->project_name }}
                                    </a>
                                @elseif($report->project)
                                    <a href="{{ route('admin.projects.show', $report->project) }}"
                                        class="text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline transition">{{ $report->project->project_name }}</a>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-block text-xs px-2.5 py-1 rounded-full border font-semibold whitespace-nowrap
                                    @if($report->status == 'menunggu') bg-amber-50 text-amber-600 border-amber-100
                                    @elseif($report->status == 'ditinjau') bg-blue-50 text-blue-600 border-blue-100
                                    @elseif($report->status == 'menunggu-bukti') bg-violet-50 text-violet-600 border-violet-100
                                    @elseif($report->status == 'ditangani') bg-teal-50 text-teal-600 border-teal-100
                                    @elseif($report->status == 'selesai') bg-emerald-50 text-emerald-600 border-emerald-100
                                    @else bg-red-50 text-red-600 border-red-100 @endif">{{ \App\Models\Report::statusLabel($report->status) }}</span>
                                <span class="block text-[10px] mt-1 text-slate-400 font-medium">{{ \App\Models\Report::targetLabel($report->target) }}</span>
                            </td>
                            <td class="px-5 py-4 text-center text-xs text-slate-500 whitespace-nowrap">{{ $report->created_at->format('d M Y') }}</td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('admin.reports.show', $report) }}" class="inline-block px-3 py-1.5 text-xs font-semibold bg-blue-50 text-blue-600 hover:bg-blue-100 active:bg-blue-200 rounded-lg transition">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-14 h-14 rounded-2xl bg-blue-50 border border-blue-100 text-blue-400 flex items-center justify-center text-xl">
                                        <i class="fa-solid fa-flag"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-600">Belum ada laporan</p>
                                        <p class="text-xs text-slate-400 mt-1">Coba ubah kata kunci pencarian atau filter.</p>
                                    </div>
                                    <a href="{{ route('admin.reports.index') }}" class="mt-1 inline-flex items-center gap-1.5 px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-xl text-xs font-semibold transition">
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

    <x-admin.pagination :paginator="$reports" />
@endsection
