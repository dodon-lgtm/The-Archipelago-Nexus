@extends('layouts.admin')

@section('title', 'Permintaan Penarikan Dana')
@section('breadcrumb', 'Permintaan Penarikan Dana')

@section('content')
    <x-admin.page-header icon="fa-money-bill-transfer" title="Permintaan Penarikan Dana"
        description="Kelola pencairan dana freelancer" :count="$withdrawals->total()" countLabel="penarikan"
        countIcon="fa-money-bill-transfer" />

    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-blue-100 dark:border-slate-800 p-4 mb-4 shadow-sm">
        <form method="GET" action="{{ url()->current() }}" class="flex flex-wrap gap-3 items-end">
            <div class="w-full sm:w-48">
                <label class="text-[11px] font-bold uppercase tracking-wide text-slate-500 dark:text-slate-300 mb-1.5 block">
                    <i class="fa-solid fa-circle-dot mr-1 text-blue-400"></i> Filter Status
                </label>
                <select class="w-full rounded-xl border-blue-100 dark:border-slate-700 bg-[#f6f9ff] dark:bg-slate-800 dark:text-white px-4 py-2.5 text-sm outline-none transition cursor-pointer focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:bg-white dark:focus:bg-slate-800" name="status">
                    <option value="semua" @selected($status==='semua')>Semua</option>
                    <option value="menunggu" @selected($status==='menunggu')>Menunggu</option>
                    <option value="diproses" @selected($status==='diproses')>Diproses</option>
                    <option value="berhasil" @selected($status==='berhasil')>Berhasil</option>
                    <option value="ditolak" @selected($status==='ditolak')>Ditolak</option>
                </select>
            </div>
            <button class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-blue-500 hover:bg-blue-600 active:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow-sm transition" type="submit">
                <i class="fa-solid fa-filter text-xs"></i> Terapkan
            </button>
        </form>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-blue-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[900px]">
                <thead class="bg-[#f6f9ff] dark:bg-slate-800 border-b border-blue-100 dark:border-slate-700">
                    <tr>
                        <th class="text-left px-5 py-3.5 font-bold text-slate-600 dark:text-slate-300 text-[11px] uppercase tracking-wider">Kode</th>
                        <th class="text-left px-5 py-3.5 font-bold text-slate-600 dark:text-slate-300 text-[11px] uppercase tracking-wider">Freelancer</th>
                        <th class="text-left px-5 py-3.5 font-bold text-slate-600 dark:text-slate-300 text-[11px] uppercase tracking-wider">Metode</th>
                        <th class="text-left px-5 py-3.5 font-bold text-slate-600 dark:text-slate-300 text-[11px] uppercase tracking-wider">Rekening</th>
                        <th class="text-right px-5 py-3.5 font-bold text-slate-600 dark:text-slate-300 text-[11px] uppercase tracking-wider">Nominal</th>
                        <th class="text-right px-5 py-3.5 font-bold text-slate-600 dark:text-slate-300 text-[11px] uppercase tracking-wider">Fee Admin</th>
                        <th class="text-right px-5 py-3.5 font-bold text-slate-600 dark:text-slate-300 text-[11px] uppercase tracking-wider">Nominal Bersih</th>
                        <th class="text-center px-5 py-3.5 font-bold text-slate-600 dark:text-slate-300 text-[11px] uppercase tracking-wider">Status</th>
                        <th class="text-center px-5 py-3.5 font-bold text-slate-600 dark:text-slate-300 text-[11px] uppercase tracking-wider">Tanggal</th>
                        <th class="text-right px-5 py-3.5 font-bold text-slate-600 dark:text-slate-300 text-[11px] uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($withdrawals as $wd)
                        <tr class="hover:bg-[#f6f9ff]/70 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-5 py-4 font-semibold text-slate-800 dark:text-white whitespace-nowrap">{{ $wd->withdrawal_code }}</td>
                            <td class="px-5 py-4">
                                <x-admin.user-cell :user="$wd->user" :name="$wd->user->name ?? '-'" :email="$wd->user->email ?? null" />
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    @include('partials.withdrawal-method-icon', ['wd' => $wd])
                                    <span class="text-slate-600 dark:text-slate-300 whitespace-nowrap">{{ $wd->method_label }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-semibold text-slate-700 dark:text-slate-200 whitespace-nowrap">{{ $wd->bank_name }}</p>
                                <p class="text-[10px] text-slate-400 dark:text-slate-500">{{ $wd->account_name }} • {{ $wd->account_number }}</p>
                            </td>
                            <td class="px-5 py-4 text-right font-bold text-slate-800 dark:text-white whitespace-nowrap">Rp {{ number_format($wd->amount, 0, ',', '.') }}</td>
                            <td class="px-5 py-4 text-right font-semibold text-red-500 dark:text-red-400 whitespace-nowrap">Rp {{ number_format($wd->fee, 0, ',', '.') }}</td>
                            <td class="px-5 py-4 text-right font-bold text-emerald-600 dark:text-emerald-300 whitespace-nowrap">Rp {{ number_format($wd->net_amount, 0, ',', '.') }}</td>
                            <td class="px-5 py-4 text-center">
                                <span class="text-xs px-2.5 py-1 rounded-full border font-semibold inline-block whitespace-nowrap {{ $wd->status_color }}">{{ $wd->status_label }}</span>
                            </td>
                            <td class="px-5 py-4 text-center text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">{{ $wd->created_at?->format('d M Y H:i') }}</td>
                            <td class="px-5 py-4 text-right">
                                <a class="inline-block px-3 py-1.5 text-xs font-semibold bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/60 active:bg-blue-200 rounded-lg transition" href="{{ route('admin.withdrawals.show', $wd) }}">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-slate-800 border border-blue-100 dark:border-slate-700 text-blue-400 dark:text-slate-500 flex items-center justify-center text-xl">
                                        <i class="fa-solid fa-money-bill-transfer"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-600 dark:text-slate-300">Tidak ada data penarikan</p>
                                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Belum ada pengajuan penarikan untuk filter ini.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <x-admin.pagination :paginator="$withdrawals" />
@endsection
