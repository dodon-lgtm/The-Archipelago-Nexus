@extends('layouts.admin')

@section('title', 'Detail Pengguna')
@section('breadcrumb', 'Detail Pengguna')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.users.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-semibold inline-flex items-center gap-1.5 transition">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Pengguna
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Profile Card --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-blue-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-300">
            <div class="bg-gradient-to-b from-[#f6f9ff] to-white dark:from-slate-800 dark:to-slate-900 p-6 text-center border-b border-blue-50 dark:border-slate-800">
                <div class="relative w-20 h-20 mx-auto mb-3">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-100 to-blue-50 dark:from-blue-900/60 dark:to-slate-800 ring-4 ring-white dark:ring-slate-800 shadow-md text-blue-600 dark:text-blue-400 flex items-center justify-center text-3xl font-bold">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <span class="absolute bottom-0 right-0 w-5 h-5 rounded-full bg-emerald-500 ring-2 ring-white dark:ring-slate-900 flex items-center justify-center">
                        <i class="fa-solid fa-check text-[9px] text-white"></i>
                    </span>
                </div>
                <h2 class="text-lg font-bold text-slate-800 dark:text-white leading-tight">{{ $user->name }}</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5 break-all">{{ $user->email }}</p>
                <span class="inline-flex items-center gap-1.5 mt-3 text-xs px-3 py-1.5 rounded-full border font-semibold
                    @if($user->role == 'admin') bg-red-50 dark:bg-red-900/40 text-red-600 dark:text-red-300 border-red-100 dark:border-red-900
                    @elseif($user->role == 'company') bg-purple-50 dark:bg-purple-900/40 text-purple-600 dark:text-purple-300 border-purple-100 dark:border-purple-900
                    @else bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-300 border-emerald-100 dark:border-emerald-900 @endif">
                    <span class="w-1.5 h-1.5 rounded-full
                        @if($user->role == 'admin') bg-red-500
                        @elseif($user->role == 'company') bg-purple-500
                        @else bg-emerald-500 @endif"></span>
                    {{ ucfirst($user->role) }}
                </span>
            </div>

            <div class="p-5 space-y-2 text-sm">
                <div class="flex items-center justify-between rounded-xl bg-[#f6f9ff] dark:bg-slate-800/60 px-3.5 py-2.5">
                    <span class="text-slate-500 dark:text-slate-400 inline-flex items-center gap-2"><i class="fa-regular fa-calendar-plus text-slate-400 text-xs"></i> Bergabung</span>
                    <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $user->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex items-center justify-between rounded-xl bg-[#f6f9ff] dark:bg-slate-800/60 px-3.5 py-2.5">
                    <span class="text-slate-500 dark:text-slate-400 inline-flex items-center gap-2"><i class="fa-regular fa-clock text-slate-400 text-xs"></i> Terakhir Update</span>
                    <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $user->updated_at->format('d M Y') }}</span>
                </div>
            </div>

            @if($user->id !== auth()->id())
                <div class="border-t border-blue-50 dark:border-slate-800 p-5">
                    <h3 class="text-xs font-bold uppercase tracking-wide text-slate-400 dark:text-slate-500 mb-2.5">Ubah Role</h3>
                    <form method="POST" action="{{ route('admin.users.update-role', $user) }}">
                        @csrf
                        <select name="role" class="w-full rounded-xl border-blue-100 dark:border-slate-700 bg-[#f6f9ff] dark:bg-slate-800 dark:text-white px-4 py-2.5 text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none mb-2 cursor-pointer transition">
                            {{-- <option value="admin" @selected($user->role == 'admin')>Admin</option> --}}
                            <option value="company" @selected($user->role == 'company')>Company</option>
                            <option value="freelancer" @selected($user->role == 'freelancer')>Freelancer</option>
                        </select>
                        <button type="submit" class="w-full px-4 py-2.5 bg-blue-500 hover:bg-blue-600 active:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow-sm transition">
                            <i class="fa-solid fa-floppy-disk mr-1.5"></i> Simpan Perubahan
                        </button>
                    </form>
                </div>
            @endif
        </div>

        {{-- Stats --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-slate-900 dark:border-slate-800 rounded-2xl border border-blue-100 p-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                            <i class="fa-solid fa-paper-plane"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-extrabold text-slate-800 dark:text-white tracking-tight">{{ $user->penawarans_count }}</div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold mt-0.5">Total Penawaran</p>
                </div>
                <div class="bg-white dark:bg-slate-900 dark:border-slate-800 rounded-2xl border border-blue-100 p-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 rounded-xl bg-violet-50 dark:bg-violet-900/40 text-violet-600 dark:text-violet-400 flex items-center justify-center">
                            <i class="fa-solid fa-briefcase"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-extrabold text-slate-800 dark:text-white tracking-tight">{{ $projectsCount }}</div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold mt-0.5">Total Proyek (Company)</p>
                </div>
                <div class="bg-white dark:bg-slate-900 dark:border-slate-800 rounded-2xl border border-blue-100 p-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-extrabold text-slate-800 dark:text-white tracking-tight">{{ $acceptedOffers }}</div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold mt-0.5">Penawaran Diterima</p>
                </div>
            </div>

            {{-- Saved Projects --}}
            <div class="bg-white dark:bg-slate-900 dark:border-slate-800 rounded-2xl border border-blue-100 shadow-sm overflow-hidden transition-colors duration-300">
                <div class="px-5 py-4 border-b border-blue-50 dark:border-slate-800 flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-slate-800 text-blue-500 dark:text-blue-400 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-bookmark text-xs"></i>
                    </div>
                    <h2 class="font-bold text-slate-800 dark:text-white">Proyek Tersimpan</h2>
                </div>
                <div class="divide-y divide-slate-50 dark:divide-slate-800">
                    @forelse($user->savedProjects()->with('project')->latest()->take(5)->get() as $saved)
                        <div class="px-5 py-3.5 flex items-center justify-between text-sm hover:bg-[#f6f9ff]/70 dark:hover:bg-slate-800/50 transition-colors">
                            <span class="font-semibold text-slate-700 dark:text-slate-200 inline-flex items-center gap-2 min-w-0 truncate">
                                <i class="fa-regular fa-folder text-slate-300 shrink-0"></i> {{ $saved->project->project_name ?? '—' }}
                            </span>
                            <span class="text-slate-400 dark:text-slate-500 text-xs ml-3 whitespace-nowrap">{{ $saved->created_at->format('d M Y') }}</span>
                        </div>
                    @empty
                        <div class="px-5 py-12 text-center">
                            <div class="flex flex-col items-center gap-2.5">
                                <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-slate-800 border border-blue-100 dark:border-slate-700 text-blue-400 dark:text-slate-500 flex items-center justify-center text-lg">
                                    <i class="fa-regular fa-bookmark"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-600 dark:text-slate-300">Tidak ada proyek tersimpan</p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Pengguna ini belum menyimpan proyek apa pun.</p>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            @if($user->id !== auth()->id() && $user->role !== 'admin')
                {{-- Zona Berbahaya --}}
                <div class="rounded-2xl border-2 border-dashed border-red-200 dark:border-red-900/60 bg-red-50/40 dark:bg-red-950/30 p-5">
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-7 h-7 rounded-lg bg-red-100 dark:bg-red-900/60 text-red-500 dark:text-red-400 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                        </div>
                        <h3 class="font-bold text-red-600 dark:text-red-400 text-sm">Zona Berbahaya</h3>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-4 ml-9">Hapus akun pengguna ini secara permanen.</p>
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                          onsubmit="return adminConfirm('Yakin ingin menghapus pengguna {{ $user->name }}? Semua data terkait akan ikut terhapus.', this)">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-4 py-2.5 bg-red-500 hover:bg-red-600 active:bg-red-700 text-white rounded-xl text-sm font-semibold shadow-sm transition">
                            <i class="fa-solid fa-trash-can mr-1.5"></i> Hapus Pengguna
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    @if(in_array($user->role, ['company', 'freelancer']))
        {{-- ============================================================= --}}
        {{-- KEUANGAN — otomatis menyesuaikan role user                     --}}
        {{-- Company → Pengeluaran | Freelancer → Pemasukan                 --}}
        {{-- ============================================================= --}}
        @php
            $payStatusColors = [
                'pending' => 'bg-amber-50 dark:bg-amber-900/40 text-amber-600 dark:text-amber-300 border-amber-200 dark:border-amber-900',
                'waiting_verification' => 'bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-300 border-blue-200 dark:border-blue-900',
                'paid' => 'bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-300 border-emerald-200 dark:border-emerald-900',
                'rejected' => 'bg-red-50 dark:bg-red-900/40 text-red-600 dark:text-red-300 border-red-200 dark:border-red-900',
            ];
            $payStatusLabels = [
                'pending' => 'Pending',
                'waiting_verification' => 'Menunggu Verifikasi',
                'paid' => 'Dibayar',
                'rejected' => 'Ditolak',
            ];
        @endphp

        <div class="mt-6 bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden transition-colors duration-300">
            {{-- Header KEUANGAN --}}
            <div class="px-6 py-5 border-b border-blue-50 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-slate-800 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-slate-800 dark:text-white">KEUANGAN</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            @if($user->role === 'company')
                                Riwayat pengeluaran dari transaksi/proyek yang dilakukan
                            @else
                                Riwayat pemasukan dari proyek yang dikerjakan
                            @endif
                        </p>
                    </div>
                </div>

                @if($user->role === 'company')
                    <div class="text-left sm:text-right">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Total Pengeluaran</span>
                        <p class="text-lg font-black text-red-600 dark:text-red-400">Rp {{ number_format($companyExpensesTotal, 0, ',', '.') }}</p>
                    </div>
                @else
                    <div class="text-left sm:text-right">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Total Pemasukan</span>
                        <p class="text-lg font-black text-emerald-600 dark:text-emerald-400">Rp {{ number_format($freelancerIncomesTotal, 0, ',', '.') }}</p>
                    </div>
                @endif
            </div>

            @if($user->role === 'company')
                {{-- ── Tabel Pengeluaran (Company) ─────────────────── --}}
                @if($companyExpenses->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="text-[11px] text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                    <th class="px-6 py-3">Penerima / Freelancer</th>
                                    <th class="px-6 py-3">Proyek</th>
                                    <th class="px-6 py-3 text-right">Nominal</th>
                                    <th class="px-6 py-3">Tanggal</th>
                                    <th class="px-6 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @foreach($companyExpenses as $payment)
                                    @php
                                        $sc = $payStatusColors[$payment->status] ?? $payStatusColors['pending'];
                                        $sl = $payStatusLabels[$payment->status] ?? $payment->status;
                                    @endphp
                                    <tr class="hover:bg-[#f6f9ff]/50 dark:hover:bg-slate-800/50 transition">
                                        <td class="px-6 py-4 font-semibold text-slate-800 dark:text-white">
                                            {{ $payment->freelancer->name ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4 text-slate-700 dark:text-slate-300">
                                            {{ $payment->workspace?->project?->project_name ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4 text-right font-bold text-slate-800 dark:text-white">
                                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                            {{ optional($payment->created_at)->format('d M Y') ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-[11px] font-bold px-2.5 py-1 rounded-full border {{ $sc }}">{{ $sl }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="py-14 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-blue-50 dark:bg-slate-800 rounded-2xl flex items-center justify-center">
                            <i class="fa-solid fa-receipt text-2xl text-slate-400"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-600 dark:text-slate-300">Belum Ada Pengeluaran</h3>
                        <p class="text-xs text-slate-400 dark:text-slate-400 mt-1">Belum ada transaksi/proyek yang dilakukan oleh perusahaan ini.</p>
                    </div>
                @endif
            @else
                {{-- ══ Ringkasan Saldo (Freelancer) ══════════════════ --}}
                <div class="px-6 py-5 border-b border-blue-50 dark:border-slate-800">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        {{-- Total Pendapatan --}}
                        <div class="bg-white dark:bg-slate-800/60 border border-emerald-100 dark:border-slate-700 rounded-2xl p-4 sm:p-5 shadow-sm transition-colors duration-300">
                            <div class="flex items-center justify-between">
                                <div class="space-y-1">
                                    <p class="text-[11px] font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wider">Total Pendapatan</p>
                                    <h3 class="text-xl sm:text-2xl font-extrabold text-emerald-600 dark:text-emerald-400 tracking-tight">
                                        Rp {{ number_format($freelancerIncomesTotal, 0, ',', '.') }}
                                    </h3>
                                </div>
                                <div class="w-11 h-11 rounded-xl bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-300 flex items-center justify-center text-lg shrink-0">
                                    <i class="fa-solid fa-arrow-trend-up"></i>
                                </div>
                            </div>
                            <p class="mt-3 text-[10px] font-semibold text-slate-400 dark:text-slate-400">Hanya pembayaran proyek berstatus Dibayar</p>
                        </div>

                        {{-- Total Ditarik --}}
                        <div class="bg-white dark:bg-slate-800/60 border border-amber-100 dark:border-slate-700 rounded-2xl p-4 sm:p-5 shadow-sm transition-colors duration-300">
                            <div class="flex items-center justify-between">
                                <div class="space-y-1">
                                    <p class="text-[11px] font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wider">Total Ditarik</p>
                                    <h3 class="text-xl sm:text-2xl font-extrabold text-amber-600 dark:text-amber-400 tracking-tight">
                                        Rp {{ number_format($freelancerWithdrawalsTotal, 0, ',', '.') }}
                                    </h3>
                                </div>
                                <div class="w-11 h-11 rounded-xl bg-amber-50 dark:bg-amber-900/40 text-amber-600 dark:text-amber-300 flex items-center justify-center text-lg shrink-0">
                                    <i class="fa-solid fa-money-bill-transfer"></i>
                                </div>
                            </div>
                            <p class="mt-3 text-[10px] font-semibold text-slate-400 dark:text-slate-400">Hanya penarikan berstatus Berhasil</p>
                        </div>

                        {{-- Sisa Saldo --}}
                        <div class="bg-white dark:bg-slate-800/60 border border-blue-200 dark:border-blue-900/60 rounded-2xl p-4 sm:p-5 shadow-sm shadow-blue-100/40 dark:shadow-black/20 transition-colors duration-300">
                            <div class="flex items-center justify-between">
                                <div class="space-y-1">
                                    <p class="text-[11px] font-bold text-blue-500 dark:text-blue-400 uppercase tracking-wider">Sisa Saldo</p>
                                    <h3 class="text-xl sm:text-2xl font-extrabold text-blue-600 dark:text-blue-300 tracking-tight">
                                        Rp {{ number_format($remainingBalance, 0, ',', '.') }}
                                    </h3>
                                </div>
                                <div class="w-11 h-11 rounded-xl bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-300 flex items-center justify-center text-lg shrink-0">
                                    <i class="fa-solid fa-wallet"></i>
                                </div>
                            </div>
                            <p class="mt-3 text-[10px] font-semibold text-slate-400 dark:text-slate-400">Total Pendapatan − Total Ditarik</p>
                        </div>
                    </div>
                </div>

                {{-- ── Tabel Pemasukan (Freelancer) ────────────────── --}}
                @if($freelancerIncomes->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="text-[11px] text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                    <th class="px-6 py-3">Proyek</th>
                                    <th class="px-6 py-3">Perusahaan</th>
                                    <th class="px-6 py-3 text-right">Nominal Pemasukan</th>
                                    <th class="px-6 py-3">Tanggal</th>
                                    <th class="px-6 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @foreach($freelancerIncomes as $payment)
                                    @php
                                        $sc = $payStatusColors[$payment->status] ?? $payStatusColors['pending'];
                                        $sl = $payStatusLabels[$payment->status] ?? $payment->status;
                                    @endphp
                                    <tr class="hover:bg-[#f6f9ff]/50 dark:hover:bg-slate-800/50 transition">
                                        <td class="px-6 py-4 font-semibold text-slate-800 dark:text-white">
                                            {{ $payment->workspace?->project?->project_name ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4 text-slate-700 dark:text-slate-300">
                                            {{ $payment->company->name ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4 text-right font-bold text-emerald-600 dark:text-emerald-400">
                                            Rp {{ number_format($payment->freelancer_receive, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                            {{ optional($payment->created_at)->format('d M Y') ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-[11px] font-bold px-2.5 py-1 rounded-full border {{ $sc }}">{{ $sl }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="py-14 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-blue-50 dark:bg-slate-800 rounded-2xl flex items-center justify-center">
                            <i class="fa-solid fa-sack-dollar text-2xl text-slate-400"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-600 dark:text-slate-300">Belum Ada Pemasukan</h3>
                        <p class="text-xs text-slate-400 dark:text-slate-400 mt-1">Belum ada pembayaran dari proyek yang dikerjakan oleh freelancer ini.</p>
                    </div>
                @endif
                {{-- ── Riwayat Penarikan (Freelancer) ──────────────── --}}
                <div class="border-t border-blue-50 dark:border-slate-800">
                    <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-900/40 text-amber-600 dark:text-amber-300 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 dark:text-white text-sm">Riwayat Penarikan</h3>
                                <p class="text-[11px] text-slate-400 dark:text-slate-400">Semua pengajuan penarikan dana freelancer</p>
                            </div>
                        </div>
                        <span class="text-[11px] font-semibold text-slate-400 dark:text-slate-400 sm:text-right">
                            {{ $freelancerWithdrawals->count() }} penarikan
                        </span>
                    </div>

                    @if($freelancerWithdrawals->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead>
                                    <tr class="text-[11px] text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                        <th class="px-6 py-3">Metode</th>
                                        <th class="px-6 py-3">Nama Tujuan</th>
                                        <th class="px-6 py-3">Nomor Rekening / E-Wallet</th>
                                        <th class="px-6 py-3 text-right">Nominal Penarikan</th>
                                        <th class="px-6 py-3 text-right">Pajak 5%</th>
                                        <th class="px-6 py-3 text-right">Nominal Bersih</th>
                                        <th class="px-6 py-3">Tanggal</th>
                                        <th class="px-6 py-3">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                    @foreach($freelancerWithdrawals as $wd)
                                        <tr class="hover:bg-[#f6f9ff]/50 dark:hover:bg-slate-800/50 transition">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    @include('partials.withdrawal-method-icon', ['wd' => $wd])
                                                    <div>
                                                        <p class="font-semibold text-slate-800 dark:text-white">{{ $wd->bank_name ?? '—' }}</p>
                                                        <p class="text-[10px] text-slate-400">{{ $wd->method_label }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-slate-700 dark:text-slate-300">{{ $wd->account_name ?? '—' }}</td>
                                            <td class="px-6 py-4 text-slate-700 dark:text-slate-300 whitespace-nowrap">{{ $wd->account_number ?? '—' }}</td>
                                            <td class="px-6 py-4 text-right font-bold text-slate-800 dark:text-white">
                                                Rp {{ number_format($wd->amount, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 text-right font-semibold text-red-600 dark:text-red-400">
                                                -Rp {{ number_format($wd->fee, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 text-right font-bold text-emerald-600 dark:text-emerald-400">
                                                Rp {{ number_format($wd->net_amount, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                                {{ optional($wd->paid_at ?? $wd->created_at)->format('d M Y') ?? '—' }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full border {{ $wd->status_color }}">{{ $wd->status_label }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="py-10 text-center">
                            <div class="w-14 h-14 mx-auto mb-3 bg-amber-50 dark:bg-slate-800 rounded-2xl flex items-center justify-center">
                                <i class="fa-solid fa-arrow-right-from-bracket text-xl text-slate-400"></i>
                            </div>
                            <h3 class="text-sm font-bold text-slate-600 dark:text-slate-300">Belum Ada Penarikan</h3>
                            <p class="text-xs text-slate-400 dark:text-slate-400 mt-1">Freelancer ini belum melakukan penarikan dana.</p>
                        </div>
                    @endif
                </div>

            @endif
        </div>
    @endif
@endsection
