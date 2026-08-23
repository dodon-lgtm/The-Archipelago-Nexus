@extends('layouts.admin')

@section('title', 'Admin Wallet')
@section('breadcrumb', 'Admin Wallet')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    {{-- ===== Stat Cards ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white border border-blue-100 rounded-2xl shadow-sm p-5 text-center">
            <div class="text-3xl font-extrabold text-blue-600">Rp {{ number_format($balance, 0, ',', '.') }}</div>
            <p class="text-xs text-slate-500 mt-1">Saldo Wallet</p>
        </div>

        <div class="bg-white border border-blue-100 rounded-2xl shadow-sm p-5 text-center">
            <div class="text-3xl font-extrabold text-emerald-600">Rp {{ number_format(\App\Services\AdminWalletService::totalIncome(), 0, ',', '.') }}</div>
            <p class="text-xs text-slate-500 mt-1">Total Pendapatan</p>
        </div>

        <div class="bg-white border border-blue-100 rounded-2xl shadow-sm p-5 text-center">
            <div class="text-3xl font-extrabold text-red-600">Rp {{ number_format(\App\Services\AdminWalletService::totalExpense(), 0, ',', '.') }}</div>
            <p class="text-xs text-slate-500 mt-1">Total Pengeluaran</p>
        </div>

        <div class="bg-white border border-blue-100 rounded-2xl shadow-sm p-5 text-center">
            <div class="text-3xl font-extrabold text-emerald-600">Rp {{ number_format(\App\Services\AdminWalletService::monthlyIncome(), 0, ',', '.') }}</div>
            <p class="text-xs text-slate-500 mt-1">Pendapatan {{ now()->translatedFormat('F Y') }}</p>
        </div>

        <div class="bg-white border border-blue-100 rounded-2xl shadow-sm p-5 text-center">
            <div class="text-3xl font-extrabold text-red-600">Rp {{ number_format(\App\Services\AdminWalletService::monthlyExpense(), 0, ',', '.') }}</div>
            <p class="text-xs text-slate-500 mt-1">Pengeluaran {{ now()->translatedFormat('F Y') }}</p>
        </div>
    </div>

    {{-- ===== Riwayat Wallet Platform ===== --}}
    <div class="bg-white border border-blue-100 rounded-2xl shadow-sm">
        <div class="px-6 py-5 border-b border-blue-50 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i class="fa-solid fa-wallet text-lg"></i>
                </div>
                <div>
                    <h2 class="font-bold text-slate-800 text-lg">Riwayat Wallet Platform</h2>
                    <p class="text-xs text-slate-500">Catatan semua pendapatan &amp; pengeluaran platform</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                @if(session('success'))
                    <div class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-lg">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="text-xs font-semibold text-red-600 bg-red-50 px-3 py-1.5 rounded-lg">{{ session('error') }}</div>
                @endif

                {{-- Filter jenis transaksi --}}
                <form method="GET" action="{{ route('admin.wallet.index') }}" class="flex items-center">
                    @if($search !== '')
                        <input type="hidden" name="q" value="{{ $search }}">
                    @endif
                    <select name="filter" onchange="this.form.submit()"
                        class="appearance-none bg-white border border-slate-200 text-slate-700 text-xs font-semibold rounded-xl px-4 py-2 pr-9 focus:outline-none cursor-pointer">
                        <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>Semua</option>
                        <option value="income" {{ $filter === 'income' ? 'selected' : '' }}>Pendapatan</option>
                        <option value="expense" {{ $filter === 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                        <option value="withdrawal_fee" {{ $filter === 'withdrawal_fee' ? 'selected' : '' }}>Fee Withdrawal</option>
                        <option value="quota_fee" {{ $filter === 'quota_fee' ? 'selected' : '' }}>Biaya Upload Project</option>
                        <option value="platform_fee" {{ $filter === 'platform_fee' ? 'selected' : '' }}>Platform Fee</option>
                        <option value="admin_expense" {{ $filter === 'admin_expense' ? 'selected' : '' }}>Pengeluaran Admin</option>
                        <option value="admin_withdrawal" {{ $filter === 'admin_withdrawal' ? 'selected' : '' }}>Tarik Saldo Admin</option>
                    </select>
                </form>

                {{-- Pencarian --}}
                <form method="GET" action="{{ route('admin.wallet.index') }}" class="flex items-center">
                    @if($filter !== 'all')
                        <input type="hidden" name="filter" value="{{ $filter }}">
                    @endif
                    <div class="relative">
                        <input type="text" name="q" value="{{ $search }}" placeholder="Cari kode / deskripsi..."
                            class="w-48 bg-white border border-slate-200 text-slate-700 text-xs rounded-xl px-4 py-2 pr-8 focus:outline-none">
                        <button type="submit" class="absolute right-2.5 top-2 text-slate-400 hover:text-blue-600">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </button>
                    </div>
                </form>

                <button type="button" onclick="openWithdrawModal()" @if($balance <= 0) disabled title="Saldo belum tersedia" @endif
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-blue-200 text-blue-600 rounded-xl text-xs font-bold hover:bg-blue-50 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fa-solid fa-arrow-down"></i> Tarik Saldo
                </button>

                <button type="button" onclick="openExpenseModal()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-bold hover:bg-blue-700">
                    <i class="fa-solid fa-plus"></i> Tambah Pengeluaran
                </button>
            </div>
        </div>
        {{-- Ledger Table --}}
        <div class="overflow-x-auto">
            @if($ledgers->count())
                <table class="w-full text-left min-w-[960px]">
                    <thead class="bg-blue-50">
                        <tr>
                            <th class="px-6 py-3 text-[10px] font-semibold text-slate-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-[10px] font-semibold text-slate-500 uppercase">Kode/ID</th>
                            <th class="px-6 py-3 text-[10px] font-semibold text-slate-500 uppercase">Jenis</th>
                            <th class="px-6 py-3 text-[10px] font-semibold text-slate-500 uppercase">Arah</th>
                            <th class="px-6 py-3 text-[10px] font-semibold text-slate-500 uppercase">Sumber</th>
                            <th class="px-6 py-3 text-[10px] font-semibold text-slate-500 uppercase text-right">Nominal</th>
                            <th class="px-6 py-3 text-[10px] font-semibold text-slate-500 uppercase text-right">Saldo Setelah</th>
                            <th class="px-6 py-3 text-[10px] font-semibold text-slate-500 uppercase">Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-blue-50">
                        @foreach($ledgers as $ledger)
                            <tr class="hover:bg-blue-50/30">
                                <td class="px-6 py-3 text-xs text-slate-600 whitespace-nowrap">{{ $ledger->created_at?->format('d M Y H:i') ?? '-' }}</td>
                                <td class="px-6 py-3"><span class="text-xs font-mono font-semibold text-slate-600">{{ $ledger->display_code }}</span></td>
                                <td class="px-6 py-3"><span class="text-xs font-semibold text-slate-700">{{ $ledger->type_label }}</span></td>
                                <td class="px-6 py-3">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $ledger->direction === \App\Models\WalletLedger::DIRECTION_CREDIT ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' }}">
                                        {{ $ledger->direction_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-3"><span class="text-xs text-slate-500">{{ $ledger->source ?? '-' }}</span></td>
                                <td class="px-6 py-3 text-right whitespace-nowrap">
                                    @if($ledger->direction === \App\Models\WalletLedger::DIRECTION_CREDIT)
                                        <span class="text-xs font-bold text-emerald-600">+Rp {{ number_format($ledger->amount, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-xs font-bold text-red-600">-Rp {{ number_format($ledger->amount, 0, ',', '.') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-right whitespace-nowrap">
                                    <span class="text-xs font-semibold text-slate-600" @if($ledger->balance_after !== null) title="Saldo sebelum: Rp {{ number_format($ledger->balance_before, 0, ',', '.') }}" @endif>
                                        @if($ledger->balance_after !== null)
                                            Rp {{ number_format($ledger->balance_after, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-3"><span class="text-xs text-slate-500">{{ \Illuminate\Support\Str::limit($ledger->description ?? '-', 60) }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="py-12 text-center">
                    <i class="fa-solid fa-wallet text-xl text-slate-400"></i>
                    <h3 class="text-sm font-bold text-slate-600">Belum Ada Transaksi</h3>
                </div>
            @endif
        </div>

        @if($ledgers->count())
            <div class="px-6 py-4 border-t border-blue-50">{{ $ledgers->links() }}</div>
        @endif
    </div>

    {{-- ===== Riwayat Penarikan Saldo Admin (tabel withdrawals, type=admin) ===== --}}
    <div class="bg-white border border-blue-100 rounded-2xl shadow-sm">
        <div class="px-6 py-5 border-b border-blue-50 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <i class="fa-solid fa-building-columns text-lg"></i>
                </div>
                <div>
                    <h2 class="font-bold text-slate-800 text-lg">Riwayat Penarikan Saldo Admin</h2>
                    <p class="text-xs text-slate-500">Tanpa platform fee 5% — hanya fee provider</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            @if($adminWithdrawals->count())
                <table class="w-full text-left min-w-[1000px]">
                    <thead class="bg-blue-50">
                        <tr>
                            <th class="px-6 py-3 text-[10px] font-semibold text-slate-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-[10px] font-semibold text-slate-500 uppercase">Kode</th>
                            <th class="px-6 py-3 text-[10px] font-semibold text-slate-500 uppercase">Provider</th>
                            <th class="px-6 py-3 text-[10px] font-semibold text-slate-500 uppercase">Metode</th>
                            <th class="px-6 py-3 text-[10px] font-semibold text-slate-500 uppercase">Tujuan</th>
                            <th class="px-6 py-3 text-[10px] font-semibold text-slate-500 uppercase text-right">Nominal</th>
                            <th class="px-6 py-3 text-[10px] font-semibold text-slate-500 uppercase text-right">Fee Provider</th>
                            <th class="px-6 py-3 text-[10px] font-semibold text-slate-500 uppercase text-right">Total Debit</th>
                            <th class="px-6 py-3 text-[10px] font-semibold text-slate-500 uppercase text-right">Diterima</th>
                            <th class="px-6 py-3 text-[10px] font-semibold text-slate-500 uppercase text-right">Saldo Sebelum</th>
                            <th class="px-6 py-3 text-[10px] font-semibold text-slate-500 uppercase text-right">Saldo Sesudah</th>
                            <th class="px-6 py-3 text-[10px] font-semibold text-slate-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-blue-50">
                        @foreach($adminWithdrawals as $wd)
                            @php
                                $methodLabel = \App\Services\WithdrawalService::providerLabel($wd->method);
                                $fee = (float) $wd->fee;
                                $received = (float) ($wd->net_amount ?: max(0, (float)$wd->amount - $fee));
                                // Ledger debit admin_withdrawal terkait — sumber saldo
                                // sebelum/sesudah (computed, bukan kolom manual).
                                $wdLedger = $withdrawalLedgers->get($wd->id);
                            @endphp
                            <tr class="hover:bg-blue-50/30">
                                <td class="px-6 py-3 text-xs text-slate-600 whitespace-nowrap">{{ $wd->created_at?->format('d M Y H:i') ?? '-' }}</td>
                                <td class="px-6 py-3"><span class="text-xs font-mono font-semibold text-slate-700">{{ $wd->withdrawal_code }}</span></td>
                                <td class="px-6 py-3"><span class="text-xs text-slate-600">{{ $wd->bank_name ?: '-' }}</span></td>
                                <td class="px-6 py-3"><span class="text-xs text-slate-600">{{ $methodLabel }}</span></td>
                                <td class="px-6 py-3">
                                    <span class="text-xs text-slate-600 block">{{ $wd->account_name ?: '-' }}</span>
                                    <span class="text-[11px] text-slate-400 font-mono" title="{{ $wd->account_number }}">{{ $wd->masked_account_number }}</span>
                                </td>
                                <td class="px-6 py-3 text-right whitespace-nowrap"><span class="text-xs font-bold text-slate-700">Rp {{ number_format($wd->amount, 0, ',', '.') }}</span></td>
                                <td class="px-6 py-3 text-right whitespace-nowrap"><span class="text-xs font-semibold text-red-500">Rp {{ number_format($fee, 0, ',', '.') }}</span></td>
                                <td class="px-6 py-3 text-right whitespace-nowrap"><span class="text-xs font-bold text-red-600">Rp {{ number_format($wd->amount, 0, ',', '.') }}</span></td>
                                <td class="px-6 py-3 text-right whitespace-nowrap"><span class="text-xs font-bold text-emerald-600">Rp {{ number_format($received, 0, ',', '.') }}</span></td>
                                <td class="px-6 py-3 text-right whitespace-nowrap" title="Saldo sebelum penarikan">
                                    <span class="text-xs font-semibold text-slate-600">
                                        @if($wdLedger)
                                            Rp {{ number_format($wdLedger->balance_before, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-right whitespace-nowrap" title="Saldo setelah penarikan">
                                    <span class="text-xs font-semibold text-slate-600">
                                        @if($wdLedger)
                                            Rp {{ number_format($wdLedger->balance_after, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-3"><span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $wd->status_color }}">{{ $wd->status_label }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="py-12 text-center">
                    <i class="fa-solid fa-building-columns text-xl text-slate-400"></i>
                    <h3 class="text-sm font-bold text-slate-600">Belum Ada Penarikan Saldo Admin</h3>
                </div>
            @endif
        </div>

        @if($adminWithdrawals->count())
            <div class="px-6 py-4 border-t border-blue-50">{{ $adminWithdrawals->links() }}</div>
        @endif
    </div>
</div>

{{-- ===== Modal: Tambah Pengeluaran ===== --}}
<div id="expenseModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 hidden">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4">
        <div class="p-6 border-b border-blue-100">
            <h3 class="font-bold text-slate-800 text-lg">Tambah Pengeluaran Platform</h3>
            <p class="text-xs text-slate-500 mt-1">Catat pengeluaran biaya operasional platform.</p>
        </div>
        <form id="expenseForm" method="POST" action="{{ route('admin.wallet.expense') }}">
            @csrf
            <input type="hidden" name="_tx" value="{{ $txExpense }}">
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 uppercase">Kategori</label>
                    <select name="category" required
                        class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-100 bg-white">
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}" @checked(old('category') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 uppercase">Deskripsi</label>
                    <textarea name="description" rows="3" placeholder="Contoh: Pembayaran hosting..." required
                        class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('description') }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 uppercase">Nominal (Rp)</label>
                        <input type="number" name="amount" placeholder="100000" required min="100" value="{{ old('amount') }}"
                            class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 uppercase">Tanggal</label>
                        <input type="date" name="expense_date" value="{{ old('expense_date', now()->toDateString()) }}" max="{{ now()->toDateString() }}"
                            class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>
                </div>
            </div>
            <div class="p-4 border-t border-blue-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeExpenseModal()"
                        class="px-4 py-2 text-sm font-semibold text-slate-600 bg-slate-50 rounded-xl hover:bg-slate-100">
                    Batal
                </button>
                <button type="submit" data-submit-label="Simpan Pengeluaran"
                        class="px-5 py-2 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700">
                    Simpan Pengeluaran
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ===== Modal: Tarik Saldo Admin (TANPA biaya platform) ===== --}}
<div id="withdrawModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 hidden">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4">
        <div class="p-6 border-b border-blue-100">
            <h3 class="font-bold text-slate-800 text-lg">Tarik Saldo Admin</h3>
            <p class="text-xs text-slate-500 mt-1">Tanpa platform fee 5% — hanya fee provider yang berlaku.</p>
        </div>
        <form id="withdrawForm" method="POST" action="{{ route('admin.wallet.withdraw') }}">
            @csrf
            <input type="hidden" name="_tx" value="{{ $txWithdraw }}">
            <div class="p-6 space-y-4">
                <div class="bg-blue-50 rounded-xl px-4 py-3 flex items-center justify-between">
                    <span class="text-[10px] font-semibold text-slate-500 uppercase">Saldo Tersedia</span>
                    <span class="text-sm font-extrabold text-blue-600">Rp {{ number_format($balance, 0, ',', '.') }}</span>
                </div>
                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 uppercase">Nominal Penarikan (Rp)</label>
                    <input type="text" name="amount" id="withdrawAmount" inputmode="numeric" placeholder="500.000"
                        required data-max="{{ (int) $balance }}"
                        class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    <p id="withdrawAmountError" class="text-[11px] text-red-500 mt-1 hidden"></p>
                </div>

                {{-- Estimasi biaya provider (display-only; server tetap sumber kebenaran) --}}
                <div id="withdrawFeeBox" class="hidden bg-[#f6f9ff] border border-blue-100 rounded-xl px-4 py-3 space-y-1.5">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500">Nominal penarikan</span>
                        <span id="feeNominal" class="font-semibold text-slate-700">Rp 0</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500">Biaya provider</span>
                        <span id="feeProvider" class="font-semibold text-red-500">Rp 0</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500">Total dipotong dari wallet</span>
                        <span id="feeDebit" class="font-semibold text-slate-700">Rp 0</span>
                    </div>
                    <div class="flex items-center justify-between text-xs pt-1.5 border-t border-blue-100">
                        <span class="text-slate-600 font-semibold">Estimasi diterima</span>
                        <span id="feeReceived" class="font-extrabold text-emerald-600">Rp 0</span>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 uppercase">Metode Penarikan</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="method" value="bank" class="peer sr-only" @checked(old('method', 'bank') === 'bank')>
                            <div class="border border-slate-200 rounded-xl px-4 py-3 text-center text-xs font-semibold text-slate-600 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-600">
                                <i class="fa-solid fa-building-columns mr-1"></i> Bank
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="method" value="ewallet" class="peer sr-only" @checked(old('method') === 'ewallet')>
                            <div class="border border-slate-200 rounded-xl px-4 py-3 text-center text-xs font-semibold text-slate-600 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-600">
                                <i class="fa-solid fa-mobile-screen mr-1"></i> E-Wallet
                            </div>
                        </label>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 uppercase">Nama Bank / E-Wallet</label>
                    <input type="text" name="bank_name" placeholder="Contoh: BCA / DANA" required maxlength="100" value="{{ old('bank_name') }}"
                        class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>
                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 uppercase">Nama Pemilik Rekening</label>
                    <input type="text" name="account_name" placeholder="Nama sesuai rekening" required maxlength="255" value="{{ old('account_name') }}"
                        class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>
                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 uppercase">Nomor Rekening / E-Wallet</label>
                    <input type="text" name="account_number" placeholder="1234567890" required maxlength="30" value="{{ old('account_number') }}"
                        class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>
                <p class="text-[11px] text-slate-400 leading-relaxed">
                    Biaya provider (jika ada) merupakan biaya eksternal dan bukan potongan aplikasi.
                    Saldo diverifikasi ulang di server saat diproses.
                </p>
            </div>
            <div class="p-4 border-t border-blue-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeWithdrawModal()"
                        class="px-4 py-2 text-sm font-semibold text-slate-600 bg-slate-50 rounded-xl hover:bg-slate-100">
                    Batal
                </button>
                <button type="submit" id="withdrawSubmitBtn" data-submit-label="Tarik Sekarang"
                        class="px-5 py-2 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700">
                    Tarik Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // ── Modal helpers ────────────────────────────────────────────
    function openExpenseModal()  { document.getElementById('expenseModal').classList.remove('hidden'); }
    function closeExpenseModal() { document.getElementById('expenseModal').classList.add('hidden'); }
    function openWithdrawModal() {
        @if($balance <= 0)
            return; // pengaman: saldo nol → modal tidak dibuka
        @endif
        document.getElementById('withdrawModal').classList.remove('hidden');
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
        var checked = document.querySelector('input[name=' + JSON.stringify('method') + ']:checked');
        var method = checked ? checked.value : 'bank';
        var fee = calcProviderFee(method, nominal);
        var received = Math.max(0, nominal - fee);
        box.classList.toggle('hidden', !(nominal > 0));
        document.getElementById('feeNominal').textContent = 'Rp ' + nominal.toLocaleString('id-ID');
        document.getElementById('feeProvider').textContent = 'Rp ' + fee.toLocaleString('id-ID');
        document.getElementById('feeDebit').textContent = 'Rp ' + nominal.toLocaleString('id-ID');
        document.getElementById('feeReceived').textContent = 'Rp ' + received.toLocaleString('id-ID');
    }
    if (amountInput) {
        amountInput.addEventListener('input', function () {
            var digits = this.value.replace(/[^\d]/g, '');
            this.value = digits ? parseInt(digits, 10).toLocaleString('id-ID') : '';
            hideAmountError();
            updateFeePreview();
        });
    }
    document.querySelectorAll('input[' + 'name=' + JSON.stringify('method') + ']').forEach(function (radio) {
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

    // ── Anti double-submit untuk semua form di halaman ini ───────
    // Tombol dinonaktifkan saat submit; server juga punya proteksi
    // one-time token (_tx) sehingga resubmit/refresh ditolak.
    ['expenseForm', 'withdrawForm'].forEach(function (fid) {
        var form = document.getElementById(fid);
        if (!form) return;
        form.addEventListener('submit', function () {
            var btn = form.querySelector('button[type="submit"]');
            if (!btn || btn.disabled) return;

            if (form.id === 'withdrawForm' && !validateWithdraw()) {
                return;
            }

            btn.disabled = true;
            var label = btn.getAttribute('data-submit-label') || btn.textContent;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Memproses...';
        });
    });

    function validateWithdraw() {
        var digits = amountInput.value.replace(/[^\d]/g, '');
        var max = parseInt(amountInput.getAttribute('data-max') || '0', 10);

        if (!digits || parseInt(digits, 10) <= 0) {
            showAmountError('Nominal penarikan wajib diisi.');
            return false;
        }
        if (max > 0 && parseInt(digits, 10) > max) {
            showAmountError('Nominal tidak boleh melebihi saldo tersedia (Rp ' + max.toLocaleString('id-ID') + ').');
            return false;
        }
        hideAmountError();
        return true;
    }
</script>

@endsection
