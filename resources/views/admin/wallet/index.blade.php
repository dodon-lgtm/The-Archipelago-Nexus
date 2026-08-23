@extends('layouts.admin')

@section('title', 'Admin Wallet')
@section('breadcrumb', 'Admin Wallet')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    {{-- ===== Stat Cards ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white border border-blue-100 rounded-2xl shadow-sm p-5 text-center">
            <div class="text-3xl font-extrabold text-blue-600">Rp {{ number_format(\App\Services\AdminWalletService::balance(), 0, ',', '.') }}</div>
            <p class="text-xs text-slate-500 mt-1">Saldo Platform</p>
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
</div>
    {{-- Header + Filter + Expense Button --}}
    <div class="bg-white border border-blue-100 rounded-2xl shadow-sm">
        <div class="px-6 py-5 border-b border-blue-50 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i class="fa-solid fa-wallet text-lg"></i>
                </div>
                <div>
                    <h2 class="font-bold text-slate-800 text-lg">Riwayat Wallet Platform</h2>
                    <p class="text-xs text-slate-500">Catatan semua pendapatan &amp; pengeluaran platform</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                @if(session('success'))
                    <div class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-lg">{{ session('success') }}</div>
                @endif
                <form method="GET" action="{{ route('admin.wallet.index') }}">
                    <select name="filter" onchange="this.form.submit()"
                        class="appearance-none bg-white border border-slate-200 text-slate-700 text-xs font-semibold rounded-xl px-4 py-2 pr-9 focus:outline-none cursor-pointer">
                        <option value="all" {{ ($filter ?? 'all') === 'all' ? 'selected' : '' }}>Semua</option>
                        <option value="income" {{ ($filter ?? 'all') === 'income' ? 'selected' : '' }}>Pendapatan</option>
                        <option value="expense" {{ ($filter ?? 'all') === 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                    </select>
                </form>
                <button type="button" onclick="openExpenseModal()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-bold hover:bg-blue-700">
                    <i class="fa-solid fa-plus"></i> Tambah Pengeluaran
                </button>
            </div>
        </div>

        {{-- Ledger Table --}}
        <div class="overflow-x-auto">
            @if($ledgers->count())
                <table class="w-full text-left">
                    <thead class="bg-blue-50">
                        <tr>
                            <th class="px-6 py-3 text-[10px] font-semibold text-slate-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-[10px] font-semibold text-slate-500 uppercase">Tipe</th>
                            <th class="px-6 py-3 text-[10px] font-semibold text-slate-500 uppercase">Sumber</th>
                            <th class="px-6 py-3 text-[10px] font-semibold text-slate-500 uppercase text-right">Jumlah</th>
                            <th class="px-6 py-3 text-[10px] font-semibold text-slate-500 uppercase">Deskripsi</th>
                            <th class="px-6 py-3 text-[10px] font-semibold text-slate-500 uppercase">Dicatat Oleh</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-blue-50">
                        @foreach($ledgers as $ledger)
                            <tr class="hover:bg-blue-50/30">
                                <td class="px-6 py-3 text-xs text-slate-600">{{ $ledger->created_at ? $ledger->created_at->format('d M Y H:i') : '-' }}</td>
                                <td class="px-6 py-3">
                                    @php $typeLabel = match($ledger->type) {
                                        \App\Models\WalletLedger::TYPE_PROJECT_QUOTA_FEE => 'Fee Kuota Proyek',
                                        \App\Models\WalletLedger::TYPE_WITHDRAWAL_FEE => 'Fee Withdrawal',
                                                                                \App\Models\WalletLedger::TYPE_ADMIN_EXPENSE => 'Pengeluaran Admin',
                                        \App\Models\WalletLedger::TYPE_ADMIN_WITHDRAWAL => 'Tarik Saldo Admin',
                                        default => $ledger->type,
                                    }; @endphp
                                    <span class="text-xs font-semibold text-slate-700">{{ $typeLabel }}</span>
                                </td>
                                <td class="px-6 py-3"><span class="text-xs text-slate-500">{{ $ledger->source ?? '-' }}</span></td>
                                <td class="px-6 py-3 text-right">
                                    @if($ledger->direction === \App\Models\WalletLedger::DIRECTION_CREDIT)
                                        <span class="text-xs font-bold text-emerald-600">+ Rp {{ number_format($ledger->amount, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-xs font-bold text-red-600">- Rp {{ number_format($ledger->amount, 0, ',', '.') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3"><span class="text-xs text-slate-500">{{ \Illuminate\Support\Str::limit($ledger->description ?? '-', 60) }}</span></td>
                                <td class="px-6 py-3"><span class="text-xs text-slate-500">{{ $ledger->creator->name ?? 'System' }}</span></td>
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
</div>

<div id="expenseModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 hidden">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4">
        <div class="p-6 border-b border-blue-100">
            <h3 class="font-bold text-slate-800 text-lg">Tambah Pengeluaran Platform</h3>
            <p class="text-xs text-slate-500 mt-1">Catat pengeluaran biaya operasional platform.</p>
        </div>
        <form id="expenseForm" method="POST" action="{{ route('admin.wallet.expense') }}">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 uppercase">Nominal (Rp)</label>
                    <input type="number" name="amount" placeholder="100000" required min="100"
                           class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>
                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 uppercase">Deskripsi</label>
                    <textarea name="description" rows="3" placeholder="Contoh: Bayar tagihan server bulanan..." required
                              class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-100"></textarea>
                </div>
            </div>
            <div class="p-4 border-t border-blue-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeExpenseModal()"
                        class="px-4 py-2 text-sm font-semibold text-slate-600 bg-slate-50 rounded-xl hover:bg-slate-100">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700">
                    Simpan Pengeluaran
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openExpenseModal() { document.getElementById('expenseModal').classList.remove('hidden'); }
    function closeExpenseModal() { document.getElementById('expenseModal').classList.add('hidden'); }
    document.getElementById('expenseModal').addEventListener('click', function(e) {
        if (e.target === this) closeExpenseModal();
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeExpenseModal();
    });
</script>

@endsection