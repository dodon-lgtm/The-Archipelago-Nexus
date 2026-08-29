@extends('layouts.admin')

@section('title', 'Penawaran')
@section('breadcrumb', 'Penawaran')

@section('content')
<x-admin.page-header icon="fa-file-invoice" title="Penawaran" description="Daftar penawaran freelancer pada proyek"
        :count="$penawarans->total()" countLabel="penawaran" countIcon="fa-file-invoice" />
    {{-- Search & Filter --}}
    <div class="bg-white rounded-2xl border border-blue-100 p-4 mb-4 shadow-sm">
        <form method="GET" action="{{ route('admin.penawarans.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="text-[11px] font-bold uppercase tracking-wide text-slate-500 mb-1.5 block">
                    <i class="fa-solid fa-magnifying-glass mr-1 text-blue-400"></i> Cari
                </label>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full rounded-xl border-blue-100 bg-[#f6f9ff] px-4 py-2.5 text-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:bg-white"
                       placeholder="Cari freelancer, proyek, atau company...">
            </div>
            <div class="w-full sm:w-40">
                <label class="text-[11px] font-bold uppercase tracking-wide text-slate-500 mb-1.5 block">
                    <i class="fa-solid fa-circle-dot mr-1 text-blue-400"></i> Filter Status
                </label>
                <select name="status" class="w-full rounded-xl border-blue-100 bg-[#f6f9ff] px-4 py-2.5 text-sm outline-none transition cursor-pointer focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:bg-white">
                    <option value="">Semua Status</option>
                    <option value="Menunggu" @selected(request('status') == 'Menunggu')>Menunggu</option>
                    <option value="Diterima" @selected(request('status') == 'Diterima')>Diterima</option>
                    <option value="Ditolak" @selected(request('status') == 'Ditolak')>Ditolak</option>
                </select>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 px-5 py-2.5 bg-blue-500 hover:bg-blue-600 active:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow-sm transition"><i class="fa-solid fa-search"></i> Cari</button>
                <a href="{{ route('admin.penawarans.index') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-sm font-semibold transition"><i class="fa-solid fa-rotate-left text-xs"></i> Reset</a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[820px]">
                <thead class="bg-[#f6f9ff] border-b border-blue-100">
                    <tr>
                        <th class="text-left px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Freelancer</th>
                        <th class="text-left px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Proyek</th>
                        <th class="text-left px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Company</th>
                        <th class="text-right px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Harga</th>
                        <th class="text-center px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Estimasi</th>
                        <th class="text-center px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Status</th>
                        <th class="text-center px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Tanggal</th>
                        <th class="text-right px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($penawarans as $penawaran)
                        <tr class="hover:bg-[#f6f9ff]/70 transition-colors">
                            <td class="px-5 py-4">
                                <x-admin.user-cell :user="$penawaran->freelancer" avatarClass="bg-gradient-to-br from-emerald-100 to-emerald-50 ring-1 ring-emerald-100 text-emerald-600" />
                            </td>
                            <td class="px-5 py-4 max-w-[200px]">
                                @if ($penawaran->project)
                                    <a href="{{ route('admin.projects.show', $penawaran->project) }}"
                                        class="font-semibold text-slate-700 dark:text-slate-200 hover:text-blue-600 dark:hover:text-blue-400 truncate block transition"
                                        title="{{ $penawaran->project->project_name }}">{{ $penawaran->project->project_name }}</a>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <x-admin.user-cell :user="$penawaran->project?->owner" :name="$penawaran->project?->owner?->name ?? '—'" />
                            </td>
                            <td class="px-5 py-4 text-right font-bold text-slate-700 whitespace-nowrap">Rp {{ number_format($penawaran->harga_penawaran) }}</td>
                            <td class="px-5 py-4 text-center text-slate-600 whitespace-nowrap">{{ $penawaran->estimasi_hari }} hari</td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-block text-xs px-2.5 py-1 rounded-full border font-semibold whitespace-nowrap
                                    @if($penawaran->status == 'Diterima') bg-emerald-50 text-emerald-600 border-emerald-100
                                    @elseif($penawaran->status == 'Ditolak') bg-red-50 text-red-600 border-red-100
                                    @else bg-amber-50 text-amber-600 border-amber-100 @endif">{{ $penawaran->status }}</span>
                            </td>
                            <td class="px-5 py-4 text-center text-xs text-slate-500 whitespace-nowrap">{{ $penawaran->created_at->format('d M Y') }}</td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('admin.penawarans.show', $penawaran) }}" class="inline-block px-3 py-1.5 text-xs font-semibold bg-blue-50 text-blue-600 hover:bg-blue-100 active:bg-blue-200 rounded-lg transition">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-14 h-14 rounded-2xl bg-blue-50 border border-blue-100 text-blue-400 flex items-center justify-center text-xl">
                                        <i class="fa-solid fa-file-signature"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-600">Belum ada penawaran</p>
                                        <p class="text-xs text-slate-400 mt-1">Coba ubah kata kunci pencarian atau filter status.</p>
                                    </div>
                                    <a href="{{ route('admin.penawarans.index') }}" class="mt-1 inline-flex items-center gap-1.5 px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-xl text-xs font-semibold transition">
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

    <x-admin.pagination :paginator="$penawarans" />
@endsection
