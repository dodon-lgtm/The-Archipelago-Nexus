@extends('layouts.admin')

@section('title', 'Detail Penarikan Dana')
@section('breadcrumb', 'Detail Penarikan Dana')

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
    <div class="max-w-4xl mx-auto space-y-6">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm text-slate-400">
            <a href="{{ route('admin.withdrawals.index') }}" class="hover:text-brand transition">Penarikan Dana</a>
            <i class="fa-solid fa-chevron-right text-[10px]"></i>
            <span class="text-slate-600 dark:text-slate-300 font-medium">{{ $withdrawal->withdrawal_code }}</span>
        </div>

        {{-- Alert Notifikasi --}}
        @if(session('success'))
            <div class="flex items-center gap-3 px-4 py-3 bg-emerald-50 dark:bg-emerald-900/40 border border-emerald-200 dark:border-emerald-900 text-emerald-700 dark:text-emerald-300 rounded-xl text-sm font-medium">
                <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="flex items-center gap-3 px-4 py-3 bg-red-50 dark:bg-red-900/40 border border-red-200 dark:border-red-900 text-red-700 dark:text-red-300 rounded-xl text-sm font-medium">
                <i class="fa-solid fa-xmark-circle"></i> {{ session('error') }}
            </div>
        @endif

        {{-- Card: Info Penarikan --}}
        <div class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-blue-50 dark:border-slate-800">
                <div class="flex items-center justify-between flex-wrap gap-3">
                    <h2 class="font-bold text-slate-800 dark:text-white">Informasi Penarikan</h2>
                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-full border {{ $withdrawal->status_color }}">
                        {{ $withdrawal->status_label }}
                    </span>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Kode Penarikan</p>
                            <p class="text-sm font-bold text-slate-800 dark:text-white mt-0.5">{{ $withdrawal->withdrawal_code }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Freelancer</p>
                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-200 mt-0.5">{{ $withdrawal->user->name ?? '-' }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $withdrawal->user->email ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Metode Pencairan</p>
                            <div class="flex items-center gap-2 mt-0.5">
                                @include('partials.withdrawal-method-icon', ['wd' => $withdrawal])
                                <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ $withdrawal->method_label }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Tanggal Pengajuan</p>
                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-200 mt-0.5">{{ $withdrawal->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Nominal Penarikan</p>
                            <p class="text-sm font-black text-slate-800 dark:text-white mt-0.5">Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</p>
                        </div>
                        <div class="rounded-xl border border-blue-100 dark:border-slate-700 bg-[#f6f9ff] dark:bg-slate-800/60 p-3 space-y-2">
                            <p class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Rincian Biaya</p>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-500 dark:text-slate-400">Nominal Penarikan</span>
                                <span class="font-semibold text-slate-700 dark:text-white">Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-500 dark:text-slate-400">Pajak Admin 5%</span>
                                <span class="font-semibold text-red-500 dark:text-red-400">-Rp {{ number_format($withdrawal->fee, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between text-xs border-t border-blue-100 dark:border-slate-700 pt-2">
                                <span class="font-semibold text-slate-600 dark:text-slate-300">Nominal Bersih Diterima</span>
                                <span class="font-black text-emerald-600 dark:text-emerald-300">Rp {{ number_format($withdrawal->net_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Bank / E-Wallet</p>
                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-200 mt-0.5">{{ $withdrawal->bank_name }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Nama Pemilik</p>
                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-200 mt-0.5">{{ $withdrawal->account_name }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Nomor Rekening / E-Wallet</p>
                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-200 mt-0.5">{{ $withdrawal->account_number }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Aksi: Menunggu --}}
        @if(in_array($withdrawal->status, ['menunggu', 'diproses']))
            <div class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-blue-50 dark:border-slate-800">
                    <h2 class="font-bold text-slate-800 dark:text-white">Aksi Penarikan</h2>
                </div>
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                        @if($withdrawal->status === 'menunggu')
                            <form method="POST" action="{{ route('admin.withdrawals.process', $withdrawal) }}"
                                  onsubmit="return adminConfirm('Proses penarikan ini? Status berubah menjadi Diproses.', this)">
                                @csrf
                                <button type="submit"
                                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 bg-blue-500 text-white rounded-xl text-sm font-semibold hover:bg-blue-600 transition">
                                    <i class="fa-solid fa-rotate"></i> Proses Penarikan
                                </button>
                            </form>
                        @endif

                        <form method="POST" action="{{ route('admin.withdrawals.approve', $withdrawal) }}"
                              onsubmit="return adminConfirm('Setujui dan cairkan dana ini? Ini hanya simulasi payout, tidak ada uang sungguhan yang dikirim.', this)">
                            @csrf
                            <button type="submit"
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 bg-emerald-500 text-white rounded-xl text-sm font-semibold hover:bg-emerald-600 transition">
                                <i class="fa-solid fa-check-circle"></i> Setujui & Cairkan
                            </button>
                        </form>

                        <button type="button"
                                onclick="document.getElementById('rejectModal').classList.remove('hidden')"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 bg-red-500 text-white rounded-xl text-sm font-semibold hover:bg-red-600 transition">
                            <i class="fa-solid fa-times-circle"></i> Tolak Penarikan
                        </button>
                    </div>

                    <div class="mt-4 flex items-center gap-2 px-4 py-3 bg-amber-50 dark:bg-amber-900/40 border border-amber-200 dark:border-amber-900 rounded-xl text-xs text-amber-700 dark:text-amber-300">
                        <i class="fa-solid fa-info-circle"></i>
                        <span>Menyetujui penarikan akan menandai dana berhasil dicairkan (simulasi). Menolak penarikan akan mengembalikan saldo freelancer ke saldo tersedia.</span>
                    </div>
                </div>
            </div>

            {{-- Modal Tolak --}}
            <div id="rejectModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
                    <div class="px-6 py-5 border-b border-blue-50 dark:border-slate-800 flex items-center justify-between">
                        <h3 class="font-bold text-slate-800 dark:text-white">Tolak Penarikan</h3>
                        <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')"
                                class="w-8 h-8 rounded-full bg-blue-50 dark:bg-slate-800 flex items-center justify-center hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                            <i class="fa-solid fa-xmark text-slate-500"></i>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('admin.withdrawals.reject', $withdrawal) }}" class="p-6 space-y-4">
                        @csrf
                        <div class="flex items-center gap-3 px-4 py-3 bg-red-50 dark:bg-red-900/40 border border-red-200 dark:border-red-900 rounded-xl text-sm text-red-700 dark:text-red-300">
                            <i class="fa-solid fa-info-circle"></i>
                            <p class="text-xs font-medium">Penarikan akan ditolak dan saldo freelancer otomatis dikembalikan ke saldo tersedia.</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5">Alasan Penolakan <span class="text-red-500">*</span></label>
                            <textarea name="rejection_reason" rows="4" maxlength="2000"
                                      placeholder="Jelaskan alasan penolakan (wajib diisi)..."
                                      class="w-full px-4 py-2.5 bg-[#f6f9ff] dark:bg-slate-800 dark:text-white border border-blue-100 dark:border-slate-700 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 resize-none"></textarea>
                        </div>
                        <button type="submit"
                                class="w-full py-2.5 bg-red-500 text-white rounded-xl text-sm font-semibold hover:bg-red-600 transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-times-circle"></i> Ya, Tolak Penarikan
                        </button>
                    </form>
                </div>
            </div>
        @endif

        {{-- Info jika sudah dicairkan --}}
        @if($withdrawal->status === 'berhasil')
            <div class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-blue-50 dark:border-slate-800">
                    <h2 class="font-bold text-slate-800 dark:text-white">Informasi Pencairan</h2>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-300 flex items-center justify-center">
                            <i class="fa-solid fa-check-circle text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">
                                Dana berhasil dicairkan (simulasi payout) oleh {{ $withdrawal->processedBy->name ?? 'Admin' }}
                            </p>
                            <p class="text-xs text-slate-400 mt-0.5">
                                {{ $withdrawal->paid_at ? 'Dicairkan ' . $withdrawal->paid_at->format('d M Y H:i') : '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Info jika ditolak --}}
        @if($withdrawal->status === 'ditolak')
            <div class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-blue-50 dark:border-slate-800">
                    <h2 class="font-bold text-slate-800 dark:text-white">Informasi Penolakan</h2>
                </div>
                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-red-50 dark:bg-red-900/40 text-red-600 dark:text-red-300 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-times-circle text-xl"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">
                                Ditolak oleh {{ $withdrawal->processedBy->name ?? 'Admin' }}
                            </p>
                            <p class="text-xs text-slate-400 mt-0.5">
                                {{ $withdrawal->processed_at ? 'Ditolak ' . $withdrawal->processed_at->format('d M Y H:i') : '-' }}
                            </p>
                            @if($withdrawal->rejection_reason)
                                <div class="mt-3 px-4 py-3 bg-red-50 dark:bg-red-900/30 border border-red-100 dark:border-red-900 rounded-xl">
                                    <p class="text-[10px] font-semibold text-red-600 dark:text-red-300 uppercase tracking-wider">Alasan Penolakan</p>
                                    <p class="text-sm text-red-700 dark:text-red-200 mt-1">{{ $withdrawal->rejection_reason }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Button Kembali --}}
        <div class="flex justify-center">
            <a href="{{ route('admin.withdrawals.index') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-300 hover:bg-[#f6f9ff] dark:hover:bg-slate-800 transition">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Penarikan
            </a>
        </div>
    </div>
@endsection