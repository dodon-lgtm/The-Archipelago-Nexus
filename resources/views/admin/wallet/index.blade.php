@extends('layouts.admin')

@section('title', 'Admin Wallet')
@section('breadcrumb', 'Admin Wallet')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 pb-10">

    {{-- ===== Stat Cards (Modern Grid) ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-5">
        
        {{-- Hero Card: Saldo Utama --}}
        <div class="col-span-1 sm:col-span-2 lg:col-span-2 bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 rounded-[2rem] p-7 text-white shadow-xl shadow-blue-900/20 relative overflow-hidden flex flex-col justify-between">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-blue-400/20 rounded-full blur-2xl"></div>
            
            <div class="relative z-10 flex items-center justify-between mb-8">
                <p class="text-blue-100 font-medium text-sm tracking-wide">Total Saldo Wallet</p>
                <div class="bg-white/20 p-2 rounded-xl backdrop-blur-sm">
                    <i class="fa-solid fa-wallet text-white text-lg"></i>
                </div>
            </div>
            
            <div class="relative z-10">
                <h2 class="text-4xl sm:text-5xl font-black tracking-tight flex items-start gap-1">
                    <span class="text-xl mt-1.5 opacity-80">Rp</span> 
                    {{ number_format($balance, 0, ',', '.') }}
                </h2>
                <div class="mt-3 flex items-center gap-2 text-xs font-medium text-blue-100 bg-white/10 inline-flex px-3 py-1.5 rounded-full backdrop-blur-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Saldo aktif & siap ditarik
                </div>
            </div>
        </div>

        {{-- Secondary Cards --}}
        <div class="col-span-1 lg:col-span-1 bg-white border border-slate-100 rounded-[2rem] shadow-sm hover:shadow-md transition-shadow p-6 flex flex-col justify-center relative overflow-hidden group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-arrow-trend-up"></i>
                </div>
            </div>
            <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-1">Total Pendapatan</p>
            <h3 class="text-xl font-bold text-slate-800">Rp {{ number_format(\App\Services\AdminWalletService::totalIncome(), 0, ',', '.') }}</h3>
        </div>

        <div class="col-span-1 lg:col-span-1 bg-white border border-slate-100 rounded-[2rem] shadow-sm hover:shadow-md transition-shadow p-6 flex flex-col justify-center group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-arrow-trend-down"></i>
                </div>
            </div>
            <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-1">Total Pengeluaran</p>
            <h3 class="text-xl font-bold text-slate-800">Rp {{ number_format(\App\Services\AdminWalletService::totalExpense(), 0, ',', '.') }}</h3>
        </div>

        <div class="col-span-1 lg:col-span-1 bg-white border border-slate-100 rounded-[2rem] shadow-sm hover:shadow-md transition-shadow p-6 flex flex-col justify-center group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
            </div>
            <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-1">In ({{ now()->translatedFormat('M y') }})</p>
            <h3 class="text-xl font-bold text-slate-800">Rp {{ number_format(\App\Services\AdminWalletService::monthlyIncome(), 0, ',', '.') }}</h3>
        </div>

        <div class="col-span-1 lg:col-span-1 bg-white border border-slate-100 rounded-[2rem] shadow-sm hover:shadow-md transition-shadow p-6 flex flex-col justify-center group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-calendar-minus"></i>
                </div>
            </div>
            <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-1">Out ({{ now()->translatedFormat('M y') }})</p>
            <h3 class="text-xl font-bold text-slate-800">Rp {{ number_format(\App\Services\AdminWalletService::monthlyExpense(), 0, ',', '.') }}</h3>
        </div>
    </div>

    {{-- ===== Alerts ===== --}}
    @if(session('success') || session('error'))
        <div class="flex gap-4">
            @if(session('success'))
                <div class="flex-1 flex items-center gap-3 px-5 py-4 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-700 animate-fade-in">
                    <i class="fa-solid fa-circle-check text-xl"></i>
                    <p class="font-medium text-sm">{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="flex-1 flex items-center gap-3 px-5 py-4 rounded-2xl bg-red-50 border border-red-100 text-red-700 animate-fade-in">
                    <i class="fa-solid fa-circle-exclamation text-xl"></i>
                    <p class="font-medium text-sm">{{ session('error') }}</p>
                </div>
            @endif
        </div>
    @endif

    {{-- ===== Riwayat Wallet Platform ===== --}}
    <div class="bg-white border border-slate-200/60 rounded-3xl shadow-sm overflow-hidden">
        {{-- Header & Toolbar --}}
        <div class="px-7 py-6 border-b border-slate-100 flex flex-wrap lg:flex-nowrap items-center justify-between gap-5 bg-slate-50/50">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-white shadow-sm border border-slate-100 text-blue-600 flex items-center justify-center">
                    <i class="fa-solid fa-book-journal-whills text-xl"></i>
                </div>
                <div>
                    <h2 class="font-extrabold text-slate-800 text-lg">Buku Kas Platform</h2>
                    <p class="text-xs text-slate-500 font-medium">Monitoring arus kas pendapatan & pengeluaran</p>
                </div>
            </div>
            
            <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                <div class="flex bg-slate-100 p-1 rounded-xl w-full sm:w-auto">
                    <form method="GET" action="{{ route('admin.wallet.index') }}" class="flex">
                        @if($search !== '') <input type="hidden" name="q" value="{{ $search }}"> @endif
                        <select name="filter" onchange="this.form.submit()"
                            class="appearance-none bg-transparent text-slate-600 text-xs font-bold px-4 py-2 pr-8 focus:outline-none cursor-pointer">
                            <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>Semua Kas</option>
                            <option value="income" {{ $filter === 'income' ? 'selected' : '' }}>Pendapatan</option>
                            <option value="expense" {{ $filter === 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                            <option value="withdrawal_fee" {{ $filter === 'withdrawal_fee' ? 'selected' : '' }}>Fee Withdrawal</option>
                            <option value="quota_fee" {{ $filter === 'quota_fee' ? 'selected' : '' }}>Biaya Upload</option>
                            <option value="platform_fee" {{ $filter === 'platform_fee' ? 'selected' : '' }}>Platform Fee</option>
                        </select>
                    </form>
                </div>

                <form method="GET" action="{{ route('admin.wallet.index') }}" class="relative w-full sm:w-auto flex-1 sm:flex-none">
                    @if($filter !== 'all') <input type="hidden" name="filter" value="{{ $filter }}"> @endif
                    <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" name="q" value="{{ $search }}" placeholder="Cari ID / Ref..."
                        class="w-full sm:w-56 bg-slate-50 border border-slate-200 text-slate-700 text-xs rounded-xl pl-9 pr-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                </form>

                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <button type="button" onclick="openWithdrawModal()" @if($balance <= 0) disabled @endif
                        class="flex-1 sm:flex-none justify-center inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-50 hover:text-blue-600 hover:border-blue-200 transition-all shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fa-solid fa-arrow-down text-blue-500"></i> Tarik Saldo
                    </button>

                    <button type="button" onclick="openExpenseModal()"
                        class="flex-1 sm:flex-none justify-center inline-flex items-center gap-2 px-5 py-2.5 bg-slate-800 text-white rounded-xl text-xs font-bold hover:bg-slate-900 transition-all shadow-sm shadow-slate-800/20">
                        <i class="fa-solid fa-plus text-slate-300"></i> Catat Beban
                    </button>
                </div>
            </div>
        </div>

        {{-- Table Ledger --}}
        <div class="overflow-x-auto">
            @if($ledgers->count())
                <table class="w-full text-left min-w-[960px] border-collapse">
                    <thead class="bg-slate-50/80 border-b border-slate-100">
                        <tr>
                            <th class="px-7 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Waktu Transaksi</th>
                            <th class="px-7 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Ref ID</th>
                            <th class="px-7 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kategori</th>
                            <th class="px-7 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">Nominal</th>
                            <th class="px-7 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">Saldo Akhir</th>
                            <th class="px-7 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100/70">
                        @foreach($ledgers as $ledger)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-7 py-4">
                                    <div class="text-xs font-semibold text-slate-700">{{ $ledger->created_at?->format('d M Y') ?? '-' }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $ledger->created_at?->format('H:i') ?? '-' }}</div>
                                </td>
                                <td class="px-7 py-4">
                                    <span class="text-xs font-mono font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-md border border-blue-100">
                                        {{ $ledger->display_code }}
                                    </span>
                                </td>
                                <td class="px-7 py-4">
                                    <span class="text-xs font-bold text-slate-700 block">{{ $ledger->type_label }}</span>
                                    <span class="text-[10px] text-slate-400 font-medium">{{ $ledger->source ?? 'Sistem' }}</span>
                                </td>
                                <td class="px-7 py-4 text-right whitespace-nowrap">
                                    @if($ledger->direction === \App\Models\WalletLedger::DIRECTION_CREDIT)
                                        <div class="text-sm font-black text-emerald-500 bg-emerald-50 inline-block px-3 py-1 rounded-lg">
                                            + Rp {{ number_format($ledger->amount, 0, ',', '.') }}
                                        </div>
                                    @else
                                        <div class="text-sm font-black text-red-500 bg-red-50 inline-block px-3 py-1 rounded-lg">
                                            - Rp {{ number_format($ledger->amount, 0, ',', '.') }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-7 py-4 text-right whitespace-nowrap">
                                    <span class="text-xs font-bold text-slate-600">
                                        {{ $ledger->balance_after !== null ? 'Rp ' . number_format($ledger->balance_after, 0, ',', '.') : '-' }}
                                    </span>
                                </td>
                                <td class="px-7 py-4">
                                    <span class="text-xs font-medium text-slate-500 leading-snug block max-w-xs truncate group-hover:whitespace-normal group-hover:max-w-md transition-all">
                                        {{ $ledger->description ?? '-' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="py-20 flex flex-col items-center justify-center opacity-70">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fa-solid fa-box-open text-3xl text-slate-300"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-700">Belum Ada Transaksi</h3>
                    <p class="text-xs text-slate-400 mt-1">Data riwayat arus kas akan muncul di sini.</p>
                </div>
            @endif
        </div>

        @if($ledgers->count())
            <div class="px-7 py-5 border-t border-slate-100 bg-slate-50/30">
                {{ $ledgers->links() }}
            </div>
        @endif
    </div>

    {{-- ===== Riwayat Penarikan Saldo Admin ===== --}}
    <div class="bg-white border border-slate-200/60 rounded-3xl shadow-sm overflow-hidden">
        <div class="px-7 py-6 border-b border-slate-100 flex items-center gap-4 bg-slate-50/50">
            <div class="w-12 h-12 rounded-2xl bg-white shadow-sm border border-slate-100 text-indigo-600 flex items-center justify-center">
                <i class="fa-solid fa-money-bill-transfer text-xl"></i>
            </div>
            <div>
                <h2 class="font-extrabold text-slate-800 text-lg">History Penarikan Admin</h2>
                <p class="text-xs text-slate-500 font-medium">Log penarikan saldo tanpa potongan platform (hanya fee provider)</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            @if($adminWithdrawals->count())
                <table class="w-full text-left min-w-[1000px]">
                    <thead class="bg-slate-50/80 border-b border-slate-100">
                        <tr>
                            <th class="px-7 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tanggal</th>
                            <th class="px-7 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kode WD</th>
                            <th class="px-7 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tujuan</th>
                            <th class="px-7 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">Nominal Asli</th>
                            <th class="px-7 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">Fee</th>
                            <th class="px-7 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">Diterima Bersih</th>
                            <th class="px-7 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100/70">
                        @foreach($adminWithdrawals as $wd)
                            @php
                                $methodLabel = \App\Services\WithdrawalService::providerLabel($wd->method);
                                $fee = (float) $wd->fee;
                                $received = (float) ($wd->net_amount ?: max(0, (float)$wd->amount - $fee));
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-7 py-4">
                                    <div class="text-xs font-semibold text-slate-700">{{ $wd->created_at?->format('d M Y') ?? '-' }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $wd->created_at?->format('H:i') ?? '-' }}</div>
                                </td>
                                <td class="px-7 py-4">
                                    <span class="text-xs font-mono font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded border border-indigo-100">
                                        {{ $wd->withdrawal_code }}
                                    </span>
                                </td>
                                <td class="px-7 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-500 text-xs font-bold">
                                            {{ substr($wd->bank_name, 0, 2) }}
                                        </div>
                                        <div>
                                            <span class="text-xs font-bold text-slate-800 block">{{ $wd->bank_name ?: '-' }}</span>
                                            <span class="text-[10px] text-slate-500 font-mono">{{ $wd->masked_account_number }} a.n {{ $wd->account_name }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-7 py-4 text-right"><span class="text-xs font-bold text-slate-600">Rp {{ number_format($wd->amount, 0, ',', '.') }}</span></td>
                                <td class="px-7 py-4 text-right"><span class="text-xs font-bold text-red-500 border border-red-100 bg-red-50 px-2 py-0.5 rounded-md">Rp {{ number_format($fee, 0, ',', '.') }}</span></td>
                                <td class="px-7 py-4 text-right"><span class="text-sm font-black text-emerald-600">Rp {{ number_format($received, 0, ',', '.') }}</span></td>
                                <td class="px-7 py-4">
                                    <span class="text-[10px] font-bold px-3 py-1.5 rounded-full border shadow-sm tracking-wide uppercase
                                        {{ $wd->status === 'success' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 
                                        ($wd->status === 'pending' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-red-50 text-red-700 border-red-200') }}">
                                        {{ $wd->status_label }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="py-20 flex flex-col items-center justify-center opacity-70">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fa-solid fa-clock-rotate-left text-3xl text-slate-300"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-700">Belum Ada Penarikan</h3>
                    <p class="text-xs text-slate-400 mt-1">Data penarikan saldo akan direkam di sini.</p>
                </div>
            @endif
        </div>
        
        @if($adminWithdrawals->count())
            <div class="px-7 py-5 border-t border-slate-100 bg-slate-50/30">
                {{ $adminWithdrawals->links() }}
            </div>
        @endif
    </div>
</div>

{{-- ===== Modal: Tambah Pengeluaran ===== --}}
<div id="expenseModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/40 backdrop-blur-sm hidden transition-all">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md mx-4 overflow-hidden border border-slate-100 transform transition-transform scale-100">
        <div class="px-7 py-5 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
            <div>
                <h3 class="font-extrabold text-slate-800 text-lg">Catat Pengeluaran</h3>
                <p class="text-xs text-slate-500 mt-1 font-medium">Beban operasional platform</p>
            </div>
            <button onclick="closeExpenseModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-200 text-slate-500 hover:bg-slate-300 transition-colors">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <form id="expenseForm" method="POST" action="{{ route('admin.wallet.expense') }}">
            @csrf
            <input type="hidden" name="_tx" value="{{ $txExpense }}">
            <div class="p-7 space-y-5">
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Kategori</label>
                    <div class="relative">
                        <select name="category" required class="w-full text-sm border-2 border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-slate-800 bg-slate-50 focus:bg-white transition-all appearance-none">
                            @foreach($categories as $key => $label)
                                <option value="{{ $key }}" @checked(old('category') === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="3" placeholder="Contoh: Perpanjang server AWS..." required
                        class="w-full text-sm border-2 border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-slate-800 bg-slate-50 focus:bg-white transition-all">{{ old('description') }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nominal (Rp)</label>
                        <input type="number" name="amount" placeholder="100000" required min="100" value="{{ old('amount') }}"
                            class="w-full text-sm font-bold border-2 border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-slate-800 bg-slate-50 focus:bg-white transition-all">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Tanggal</label>
                        <input type="date" name="expense_date" value="{{ old('expense_date', now()->toDateString()) }}" max="{{ now()->toDateString() }}"
                            class="w-full text-sm border-2 border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-slate-800 bg-slate-50 focus:bg-white transition-all">
                    </div>
                </div>
            </div>
            <div class="p-5 border-t border-slate-100 flex items-center justify-end gap-3 bg-slate-50">
                <button type="button" onclick="closeExpenseModal()"
                        class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-100 transition-colors">
                    Batal
                </button>
                <button type="submit" data-submit-label="Simpan Pengeluaran"
                        class="px-6 py-2.5 text-sm font-bold text-white bg-slate-800 rounded-xl hover:bg-slate-900 shadow-lg shadow-slate-800/30 transition-all">
                    Simpan Kas Keluar
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ===== Modal: Tarik Saldo Admin ===== --}}
<div id="withdrawModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/40 backdrop-blur-sm hidden transition-all">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md mx-4 overflow-hidden border border-slate-100">
        <div class="px-7 py-5 border-b border-slate-100 bg-blue-50 flex justify-between items-center">
            <div>
                <h3 class="font-extrabold text-blue-900 text-lg">Tarik Saldo Admin</h3>
                <p class="text-[11px] text-blue-600/70 mt-1 font-bold tracking-wide uppercase">0% Platform Fee</p>
            </div>
            <button onclick="closeWithdrawModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-blue-200/50 text-blue-700 hover:bg-blue-300 transition-colors">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <form id="withdrawForm" method="POST" action="{{ route('admin.wallet.withdraw') }}">
            @csrf
            <input type="hidden" name="_tx" value="{{ $txWithdraw }}">
            
            <div class="p-7 space-y-5">
                {{-- Info Saldo --}}
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl px-5 py-4 flex items-center justify-between text-white shadow-lg shadow-blue-500/20">
                    <span class="text-xs font-semibold text-blue-100 tracking-wider">SALDO BISA DITARIK</span>
                    <span class="text-lg font-black tracking-tight">Rp {{ number_format($balance, 0, ',', '.') }}</span>
                </div>
                
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nominal Tarik</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-slate-400">Rp</span>
                        <input type="text" name="amount" id="withdrawAmount" inputmode="numeric" placeholder="500.000"
                            required data-max="{{ (int) $balance }}"
                            class="w-full text-lg font-black border-2 border-slate-200 rounded-xl pl-10 pr-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-50 transition-all text-slate-800">
                    </div>
                    <p id="withdrawAmountError" class="text-[11px] font-bold text-red-500 mt-1.5 hidden"></p>
                </div>

                <div id="withdrawFeeBox" class="hidden bg-slate-50 border border-slate-100 rounded-2xl p-4 space-y-2">
                    <div class="flex items-center justify-between text-xs font-medium">
                        <span class="text-slate-500">Nominal request</span>
                        <span id="feeNominal" class="text-slate-700">Rp 0</span>
                    </div>
                    <div class="flex items-center justify-between text-xs font-medium">
                        <span class="text-slate-500">Biaya Transfer Bank/PG</span>
                        <span id="feeProvider" class="text-red-500 bg-red-50 px-1.5 rounded font-bold">Rp 0</span>
                    </div>
                    <div class="flex items-center justify-between text-sm pt-2 border-t border-slate-200/60 mt-1">
                        <span class="text-slate-800 font-extrabold">Total Bersih Diterima</span>
                        <span id="feeReceived" class="font-black text-emerald-600 text-base">Rp 0</span>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Metode</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer group">
                            <input type="radio" name="method" value="bank" class="peer sr-only" @checked(old('method', 'bank') === 'bank')>
                            <div class="border-2 border-slate-200 rounded-xl px-4 py-3 text-center text-xs font-bold text-slate-500 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-700 transition-all">
                                <i class="fa-solid fa-building-columns block text-lg mb-1 group-hover:scale-110 transition-transform"></i> Bank
                            </div>
                        </label>
                        <label class="cursor-pointer group">
                            <input type="radio" name="method" value="ewallet" class="peer sr-only" @checked(old('method') === 'ewallet')>
                            <div class="border-2 border-slate-200 rounded-xl px-4 py-3 text-center text-xs font-bold text-slate-500 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-700 transition-all">
                                <i class="fa-solid fa-mobile-screen block text-lg mb-1 group-hover:scale-110 transition-transform"></i> E-Wallet
                            </div>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nama Bank / E-Wallet</label>
                        <input type="text" name="bank_name" placeholder="BCA / DANA / OVO..." required maxlength="100" value="{{ old('bank_name') }}"
                            class="w-full text-sm font-bold border-2 border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 transition-all">
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nomor Rekening</label>
                        <input type="text" name="account_number" placeholder="1234567890" required maxlength="30" value="{{ old('account_number') }}"
                            class="w-full text-sm font-bold border-2 border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 transition-all">
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nama Pemilik</label>
                        <input type="text" name="account_name" placeholder="A/n rekening..." required maxlength="255" value="{{ old('account_name') }}"
                            class="w-full text-sm font-bold border-2 border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 transition-all">
                    </div>
                </div>
            </div>
            
            <div class="p-5 border-t border-slate-100 flex items-center justify-end gap-3 bg-slate-50">
                <button type="button" onclick="closeWithdrawModal()"
                        class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-100 transition-colors">
                    Batal
                </button>
                <button type="submit" id="withdrawSubmitBtn" data-submit-label="Proses Penarikan"
                        class="px-6 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-600/30 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-paper-plane text-blue-200"></i> Proses
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // ── Modal helpers ────────────────────────────────────────────
    function openExpenseModal()  { 
        const el = document.getElementById('expenseModal');
        el.classList.remove('hidden'); 
        setTimeout(() => el.querySelector('div').classList.add('scale-100'), 10);
    }
    function closeExpenseModal() { document.getElementById('expenseModal').classList.add('hidden'); }
    
    function openWithdrawModal() {
        @if($balance <= 0) return; @endif
        const el = document.getElementById('withdrawModal');
        el.classList.remove('hidden');
    }
    function closeWithdrawModal(){ document.getElementById('withdrawModal').classList.add('hidden'); }

    ['expenseModal', 'withdrawModal'].forEach(function (id) {
        var el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('click', function (e) { if (e.target === this) this.classList.add('hidden'); });
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeExpenseModal();
            closeWithdrawModal();
        }
    });

    // ── Format Rupiah untuk nominal penarikan ────────────────────
    var amountInput = document.getElementById('withdrawAmount');
    var withdrawProviders = @json(collect(config('withdrawal.providers'))->map(fn ($p) => $p['fee'] ?? []));

    function calcProviderFee(method, nominal) {
        var fee = withdrawProviders[method];
        if (!fee || !nominal || nominal <= 0) return 0;
        if ((fee.type || '') === 'fixed') return Math.round(parseFloat(fee.amount || 0));
        if ((fee.type || '') === 'percent') return Math.round(nominal * (parseFloat(fee.amount || 0)) / 100);
        return 0;
    }

    function updateFeePreview() {
        var box = document.getElementById('withdrawFeeBox');
        if (!box || !amountInput) return;
        var raw = amountInput.value.replace(/[^\d]/g, '');
        var nominal = parseInt(raw || '0', 10);
        var checked = document.querySelector('input[name="method"]:checked');
        var method = checked ? checked.value : 'bank';
        var fee = calcProviderFee(method, nominal);
        var received = Math.max(0, nominal - fee);
        
        box.classList.toggle('hidden', !(nominal > 0));
        if(nominal > 0) {
            document.getElementById('feeNominal').textContent = 'Rp ' + nominal.toLocaleString('id-ID');
            document.getElementById('feeProvider').textContent = 'Rp ' + fee.toLocaleString('id-ID');
            document.getElementById('feeReceived').textContent = 'Rp ' + received.toLocaleString('id-ID');
        }
    }

    if (amountInput) {
        amountInput.addEventListener('input', function () {
            var digits = this.value.replace(/[^\d]/g, '');
            this.value = digits ? parseInt(digits, 10).toLocaleString('id-ID') : '';
            hideAmountError();
            updateFeePreview();
        });
    }
    
    document.querySelectorAll('input[name="method"]').forEach(function (radio) {
        radio.addEventListener('change', updateFeePreview);
    });

    function showAmountError(msg) {
        var el = document.getElementById('withdrawAmountError');
        el.textContent = msg;
        el.classList.remove('hidden');
    }
    function hideAmountError() {
        document.getElementById('withdrawAmountError').classList.add('hidden');
    }

    // ── Submit Handling ──────────────────────────────────────────
    ['expenseForm', 'withdrawForm'].forEach(function (fid) {
        var form = document.getElementById(fid);
        if (!form) return;
        form.addEventListener('submit', function (e) {
            var btn = form.querySelector('button[type="submit"]');
            if (!btn || btn.disabled) return;

            if (form.id === 'withdrawForm' && !validateWithdraw()) {
                e.preventDefault();
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Memproses...';
        });
    });

    function validateWithdraw() {
        var digits = amountInput.value.replace(/[^\d]/g, '');
        var max = parseInt(amountInput.getAttribute('data-max') || '0', 10);

        if (!digits || parseInt(digits, 10) <= 0) {
            showAmountError('Nominal penarikan wajib diisi minimal Rp 1.');
            return false;
        }
        if (max > 0 && parseInt(digits, 10) > max) {
            showAmountError('Gagal: Saldo maksimal yang dapat ditarik adalah Rp ' + max.toLocaleString('id-ID'));
            return false;
        }
        hideAmountError();
        return true;
    }
</script>
@endsection