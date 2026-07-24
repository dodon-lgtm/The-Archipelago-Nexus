@extends('admin.layouts.admin')

@section('title', 'Kelola Penawaran')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold text-slate-800">Kelola Penawaran</h1>
        <p class="text-sm text-slate-500 mt-1">Semua penawaran dari freelancer</p>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="flash-message mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium">{{ session('success') }}</div>
    @endif

    {{-- Search & Filter --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-4 mb-4 shadow-sm">
        <form method="GET" action="{{ route('admin.penawarans.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="text-xs font-semibold text-slate-600 mb-1 block">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 outline-none"
                       placeholder="Cari freelancer, proyek, atau company...">
            </div>
            <div class="w-40">
                <label class="text-xs font-semibold text-slate-600 mb-1 block">Filter Status</label>
                <select name="status" class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 outline-none">
                    <option value="">Semua Status</option>
                    <option value="Menunggu" @selected(request('status') == 'Menunggu')>Menunggu</option>
                    <option value="Diterima" @selected(request('status') == 'Diterima')>Diterima</option>
                    <option value="Ditolak" @selected(request('status') == 'Ditolak')>Ditolak</option>
                </select>
            </div>
            <button type="submit" class="px-5 py-2.5 bg-cyan-500 hover:bg-cyan-600 text-white rounded-xl text-sm font-semibold transition">
                <i class="fa-solid fa-search mr-1"></i> Cari
            </button>
            <a href="{{ route('admin.penawarans.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-sm font-semibold transition">
                Reset
            </a>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="text-left px-5 py-3 font-bold text-slate-600 text-xs uppercase">Freelancer</th>
                        <th class="text-left px-5 py-3 font-bold text-slate-600 text-xs uppercase">Proyek</th>
                        <th class="text-left px-5 py-3 font-bold text-slate-600 text-xs uppercase">Company</th>
                        <th class="text-right px-5 py-3 font-bold text-slate-600 text-xs uppercase">Harga</th>
                        <th class="text-center px-5 py-3 font-bold text-slate-600 text-xs uppercase">Estimasi</th>
                        <th class="text-center px-5 py-3 font-bold text-slate-600 text-xs uppercase">Status</th>
                        <th class="text-center px-5 py-3 font-bold text-slate-600 text-xs uppercase">Tanggal</th>
                        <th class="text-right px-5 py-3 font-bold text-slate-600 text-xs uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($penawarans as $penawaran)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs font-bold">
                                        {{ strtoupper(substr($penawaran->freelancer->name ?? '?', 0, 1)) }}
                                    </div>
                                    <span class="font-semibold text-slate-800">{{ $penawaran->freelancer->name ?? '—' }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 font-semibold text-slate-700">{{ $penawaran->project->project_name ?? '—' }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $penawaran->project->owner->name ?? '—' }}</td>
                            <td class="px-5 py-4 text-right font-semibold text-slate-700">Rp {{ number_format($penawaran->harga_penawaran) }}</td>
                            <td class="px-5 py-4 text-center text-slate-600">{{ $penawaran->estimasi_hari }} hari</td>
                            <td class="px-5 py-4 text-center">
                                <span class="text-xs px-2.5 py-1 rounded-full font-semibold
                                    @if($penawaran->status == 'Diterima') bg-emerald-50 text-emerald-600
                                    @elseif($penawaran->status == 'Ditolak') bg-red-50 text-red-600
                                    @else bg-amber-50 text-amber-600 @endif">
                                    {{ $penawaran->status }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center text-xs text-slate-500">{{ $penawaran->created_at->format('d M Y') }}</td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('admin.penawarans.show', $penawaran) }}"
                                   class="px-3 py-1.5 text-xs font-semibold bg-cyan-50 text-cyan-600 hover:bg-cyan-100 rounded-lg transition">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center text-sm text-slate-400">Belum ada penawaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $penawarans->links() }}
    </div>
@endsection

