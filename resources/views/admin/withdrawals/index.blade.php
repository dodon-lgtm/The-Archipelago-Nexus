@extends('layouts.admin')

@section('title', 'Permintaan Penarikan Dana')
@section('breadcrumb', 'Permintaan Penarikan Dana')

@push('styles')
    
    <script>
        tailwind.config = tailwind.config || {};
    tailwind.config.darkMode = 'class';
        tailwind.config.darkMode = 'class';
    </script>
    <style>
        html.dark body { background: #020617; color: #f1f5f9; }
    </style>
@endpush

@section('content')
    @if(session('success'))
        <div class="flex items-center gap-3 px-4 py-3 mb-4 bg-emerald-50 dark:bg-emerald-900/40 border border-emerald-200 dark:border-emerald-900 text-emerald-700 dark:text-emerald-300 rounded-xl text-sm font-medium">
            <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="flex items-center gap-3 px-4 py-3 mb-4 bg-red-50 dark:bg-red-900/40 border border-red-200 dark:border-red-900 text-red-700 dark:text-red-300 rounded-xl text-sm font-medium">
            <i class="fa-solid fa-xmark-circle"></i> {{ session('error') }}
        </div>
    @endif

    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-blue-100 dark:border-slate-800 p-4 mb-4 shadow-sm">
        <form method="GET" action="{{ url()->current() }}" class="flex flex-wrap gap-3 items-end">
            <div class="w-48">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1 block">Filter Status</label>
                <select class="w-full rounded-xl border-blue-100 dark:border-slate-700 bg-[#f6f9ff] dark:bg-slate-800 dark:text-white px-4 py-2.5 text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none" name="status">
                    <option value="semua" @selected($status==='semua')>Semua</option>
                    <option value="menunggu" @selected($status==='menunggu')>Menunggu</option>
                    <option value="diproses" @selected($status==='diproses')>Diproses</option>
                    <option value="berhasil" @selected($status==='berhasil')>Berhasil</option>
                    <option value="ditolak" @selected($status==='ditolak')>Ditolak</option>
                </select>
            </div>
            <button class="px-5 py-2.5 bg-blue-500 hover:bg-blue-600 text-white rounded-xl text-sm font-semibold transition" type="submit">Terapkan</button>
        </form>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-blue-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-[#f6f9ff] dark:bg-slate-800 border-b border-blue-100 dark:border-slate-700">
                    <tr>
                        <th class="text-left px-5 py-3 font-bold text-slate-600 dark:text-slate-300 text-xs uppercase">Kode</th>
                        <th class="text-left px-5 py-3 font-bold text-slate-600 dark:text-slate-300 text-xs uppercase">Freelancer</th>
                        <th class="text-left px-5 py-3 font-bold text-slate-600 dark:text-slate-300 text-xs uppercase">Metode</th>
                        <th class="text-left px-5 py-3 font-bold text-slate-600 dark:text-slate-300 text-xs uppercase">Rekening</th>
                        <th class="text-right px-5 py-3 font-bold text-slate-600 dark:text-slate-300 text-xs uppercase">Nominal</th>
                        <th class="text-right px-5 py-3 font-bold text-slate-600 dark:text-slate-300 text-xs uppercase">Pajak 5%</th>
                        <th class="text-right px-5 py-3 font-bold text-slate-600 dark:text-slate-300 text-xs uppercase">Nominal Bersih</th>
                        <th class="text-center px-5 py-3 font-bold text-slate-600 dark:text-slate-300 text-xs uppercase">Status</th>
                        <th class="text-center px-5 py-3 font-bold text-slate-600 dark:text-slate-300 text-xs uppercase">Tanggal</th>
                        <th class="text-right px-5 py-3 font-bold text-slate-600 dark:text-slate-300 text-xs uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($withdrawals as $wd)
                        <tr class="hover:bg-[#f6f9ff] dark:hover:bg-slate-800/50 transition">
                            <td class="px-5 py-4 font-semibold text-slate-800 dark:text-white">{{ $wd->withdrawal_code }}</td>
                            <td class="px-5 py-4">
                                <p class="font-semibold text-slate-800 dark:text-white">{{ $wd->user->name ?? '-' }}</p>
                                <p class="text-[10px] text-slate-400">{{ $wd->user->email ?? '-' }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    @include('partials.withdrawal-method-icon', ['wd' => $wd])
                                    <span class="text-slate-600 dark:text-slate-300">{{ $wd->method_label }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-semibold text-slate-700 dark:text-slate-200">{{ $wd->bank_name }}</p>
                                <p class="text-[10px] text-slate-400">{{ $wd->account_name }} • {{ $wd->account_number }}</p>
                            </td>
                            <td class="px-5 py-4 text-right font-bold text-slate-800 dark:text-white">Rp {{ number_format($wd->amount, 0, ',', '.') }}</td>
                            <td class="px-5 py-4 text-right font-semibold text-red-500 dark:text-red-400">Rp {{ number_format($wd->fee, 0, ',', '.') }}</td>
                            <td class="px-5 py-4 text-right font-bold text-emerald-600 dark:text-emerald-300">Rp {{ number_format($wd->net_amount, 0, ',', '.') }}</td>
                            <td class="px-5 py-4 text-center">
                                <span class="text-xs px-2.5 py-1 rounded-full border font-semibold {{ $wd->status_color }}">{{ $wd->status_label }}</span>
                            </td>
                            <td class="px-5 py-4 text-center text-xs text-slate-500 dark:text-slate-400">{{ $wd->created_at?->format('d M Y H:i') }}</td>
                            <td class="px-5 py-4 text-right">
                                <a class="px-3 py-1.5 text-xs font-semibold bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/60 rounded-lg transition" href="{{ route('admin.withdrawals.show', $wd) }}">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-5 py-12 text-center text-sm text-slate-400">Tidak ada data penarikan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $withdrawals->links() }}
    </div>
@endsection