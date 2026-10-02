@extends('layouts.admin')

@section('title', 'Permintaan Akun Perusahaan')
@section('breadcrumb', 'Permintaan Akun Perusahaan')

@section('content')
    <x-admin.page-header icon="fa-building" title="Permintaan Akun Perusahaan"
        description="Tinjau dan kelola permintaan akun perusahaan"
        :count="$companyRequests->total()" countLabel="permintaan" countIcon="fa-building" />

    {{-- Search & Filter --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-blue-100 dark:border-slate-800 p-4 mb-4 shadow-sm">
        <form method="GET" action="{{ url()->current() }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="text-[11px] font-bold uppercase tracking-wide text-slate-500 dark:text-slate-300 mb-1.5 block">
                    <i class="fa-solid fa-magnifying-glass mr-1 text-blue-400"></i> Cari Perusahaan
                </label>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="w-full rounded-xl border-blue-100 dark:border-slate-700 bg-[#f6f9ff] dark:bg-slate-800 dark:text-white px-4 py-2.5 text-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:bg-white dark:focus:bg-slate-800"
                    placeholder="Nama, email, atau contact person...">
            </div>
            <div class="w-full sm:w-44">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1 block">Filter Status</label>
                <select class="w-full rounded-xl border-blue-100 dark:border-slate-700 bg-[#f6f9ff] dark:bg-slate-800 dark:text-white px-4 py-2.5 text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none" name="status">
                    <option value="menunggu" @selected($status==='menunggu')>Menunggu</option>
                    <option value="disetujui" @selected($status==='disetujui')>Disetujui</option>
                    <option value="ditolak" @selected($status==='ditolak')>Ditolak</option>
                </select>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-blue-500 hover:bg-blue-600 active:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow-sm transition" type="submit">
                    <i class="fa-solid fa-search text-xs"></i> Cari
                </button>
                <a href="{{ url()->current() }}"
                    class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl text-sm font-semibold transition hover:bg-slate-200 dark:hover:bg-slate-700">
                    <i class="fa-solid fa-rotate-left text-xs"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-[#f6f9ff] border-b border-blue-100">
                    <tr>
                        <th class="text-left px-5 py-3 font-bold text-slate-600 text-xs uppercase">Nama Perusahaan</th>
                        <th class="text-left px-5 py-3 font-bold text-slate-600 text-xs uppercase">Contact Person</th>
                        <th class="text-left px-5 py-3 font-bold text-slate-600 text-xs uppercase">Email</th>
                        <th class="text-left px-5 py-3 font-bold text-slate-600 text-xs uppercase">No. Telepon</th>
                        <th class="text-center px-5 py-3 font-bold text-slate-600 text-xs uppercase">Status</th>
                        <th class="text-center px-5 py-3 font-bold text-slate-600 text-xs uppercase">Tanggal</th>
                        <th class="text-right px-5 py-3 font-bold text-slate-600 text-xs uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($companyRequests as $r)
                        <tr class="hover:bg-[#f6f9ff] dark:hover:bg-slate-800/50 transition">
                            <td class="px-5 py-4">
                                <a href="{{ route('admin.users.index', ['search' => $r->company_email]) }}"
                                    class="flex items-center gap-3 group" title="Lihat pengguna {{ $r->company_email }}">
                                    <div
                                        class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-100 to-blue-50 ring-1 ring-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold shrink-0 group-hover:ring-blue-200 transition">
                                        <i class="fa-solid fa-building text-xs"></i>
                                    </div>
                                    <span
                                        class="font-semibold text-slate-800 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 truncate transition">{{ $r->company_name }}</span>
                                </a>
                            </td>
                            <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ $r->contact_person }}</td>
                            <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ $r->company_email }}</td>
                            <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ $r->company_phone }}</td>
                            <td class="px-5 py-4 text-center">
                                @if($r->request_status==='menunggu')
                                    <span class="inline-flex items-center gap-1.5 text-xs px-2.5 py-1 rounded-full border font-semibold bg-amber-50 text-amber-600 border-amber-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Menunggu
                                    </span>
                                @elseif($r->request_status==='disetujui')
                                    <span class="inline-flex items-center gap-1.5 text-xs px-2.5 py-1 rounded-full border font-semibold bg-emerald-50 text-emerald-600 border-emerald-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Disetujui
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-xs px-2.5 py-1 rounded-full border font-semibold bg-red-50 text-red-600 border-red-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center text-xs text-slate-500">{{ $r->created_at?->format('Y-m-d') }}</td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2 flex-wrap">
                                    <a class="px-3 py-1.5 text-xs font-semibold bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition" href="{{ route('admin.company-account-requests.show', $r) }}">Lihat Detail</a>

                                    @if($r->request_status==='menunggu')
                                        <form method="POST" action="{{ route('admin.company-account-requests.approve', $r) }}"
                                              onsubmit="return adminConfirm('Setujui permintaan akun perusahaan {{ $r->company_name }}?', this, { confirmText: 'Ya, Setujui' })" class="inline">
                                            @csrf
                                            <button class="px-3 py-1.5 text-xs font-semibold bg-emerald-50 text-emerald-600 hover:bg-emerald-100 rounded-lg transition" type="submit">Setujui</button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.company-account-requests.reject', $r) }}"
                                              onsubmit="return adminConfirm('Tolak permintaan akun perusahaan {{ $r->company_name }}? Aksi ini tidak dapat dibatalkan.', this, { danger: true, confirmText: 'Ya, Tolak' })" class="inline">
                                            @csrf
                                            <input type="hidden" name="note" value="Tolak oleh admin">
                                            <button class="px-3 py-1.5 text-xs font-semibold bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition" type="submit">Tolak</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <x-admin.empty-state icon="fa-building" title="Tidak ada permintaan"
                                    message="Tidak ditemukan permintaan akun perusahaan untuk filter ini."
                                    :reset-url="url()->current()" resetLabel="Reset Filter" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <x-admin.pagination :paginator="$companyRequests" />
@endsection
