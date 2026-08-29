<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.theme-boot')
    <title>Pendapatan - ApexForge Labs</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = tailwind.config || {};
    tailwind.config.darkMode = 'class';
        tailwind.config.darkMode = 'class';
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    <style>
        /* ApexForge Labs — Unified UI System */
        :root{
            --af-primary:#2563eb;
            --af-primary-dark:#1d4ed8;
            --af-primary-soft:#eff6ff;
            --af-sky:#38bdf8;
            --af-ink:#0f172a;
            --af-muted:#64748b;
            --af-border:#dbeafe;
            --af-surface:#ffffff;
            --af-page:#f6f9ff;
        }
        html{scroll-behavior:smooth}
        body{
            font-family:'Plus Jakarta Sans',sans-serif;
            background:
                radial-gradient(circle at 10% -10%,rgba(56,189,248,.10),transparent 30%),
                radial-gradient(circle at 100% 0%,rgba(37,99,235,.08),transparent 28%),
                var(--af-page);
        }
        ::selection{background:rgba(37,99,235,.18);color:#0f172a}
        ::-webkit-scrollbar{width:7px;height:7px}
        ::-webkit-scrollbar-track{background:rgba(241,245,249,.7)}
        ::-webkit-scrollbar-thumb{background:rgba(37,99,235,.22);border-radius:999px}
        ::-webkit-scrollbar-thumb:hover{background:rgba(37,99,235,.38)}

        input,select,textarea{
            border-color:var(--af-border)!important;
            background:rgba(255,255,255,.92);
            transition:border-color .2s ease,box-shadow .2s ease,background .2s ease;
        }
        input:focus,select:focus,textarea:focus{
            border-color:rgba(37,99,235,.55)!important;
            box-shadow:0 0 0 4px rgba(37,99,235,.09)!important;
            outline:none!important;
        }
        button,a,[role="button"]{transition:all .2s ease}
        button:focus-visible,a:focus-visible,[role="button"]:focus-visible{
            outline:2px solid rgba(37,99,235,.55);
            outline-offset:2px;
        }
        table{border-collapse:separate;border-spacing:0}
        thead th{
            background:rgba(239,246,255,.72)!important;
            color:#334155;
            font-weight:700;
        }
        tbody tr{transition:background .18s ease}
        tbody tr:hover{background:rgba(239,246,255,.48)}
        [class*="bg-blue-600"]{
            box-shadow:0 8px 22px -12px rgba(37,99,235,.72);
        }
        [class*="bg-blue-600"]:hover{
            box-shadow:0 12px 28px -12px rgba(37,99,235,.78);
            transform:translateY(-1px);
        }
        .glass-panel,.glass-card,.glass-surface{
            background:rgba(255,255,255,.72);
            border:1px solid rgba(219,234,254,.85);
            backdrop-filter:blur(18px);
            -webkit-backdrop-filter:blur(18px);
            box-shadow:0 18px 50px -32px rgba(30,64,175,.32);
        }
        .apex-page-glow{
            position:fixed;inset:auto -10rem -12rem auto;width:28rem;height:28rem;
            background:rgba(56,189,248,.09);filter:blur(70px);border-radius:999px;
            pointer-events:none;z-index:-1;
        }
        @media (max-width:767px){
            main{padding-left:1rem!important;padding-right:1rem!important}
            table{min-width:680px}
            .overflow-x-auto{-webkit-overflow-scrolling:touch}
        }
        @media (prefers-reduced-motion:reduce){
            *,*::before,*::after{animation-duration:.01ms!important;animation-iteration-count:1!important;transition-duration:.01ms!important;scroll-behavior:auto!important}
        }
    </style>
</head>
<body class="bg-[#f6f9ff] dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-300">

@php
    if (!function_exists('formatRupiahShort')) {
        function formatRupiahShort($amount) {
            $amount = (float) ($amount ?? 0);
            if ($amount >= 1000000000000) {
                $val = round($amount / 1000000000000, 2);
                return 'Rp ' . (fmod($val, 1) == 0 ? number_format($val, 0) : $val) . ' T';
            }
            if ($amount > 999000000) {
                $val = round($amount / 1000000000, 2);
                return 'Rp ' . (fmod($val, 1) == 0 ? number_format($val, 0) : $val) . ' M';
            }
            return 'Rp ' . number_format($amount, 0, ',', '.');
        }
    }
@endphp

<div class="flex h-screen overflow-hidden">
    @include('navbar.navigasi')

    <div class="flex-1 flex flex-col overflow-hidden">
        <div class="sticky top-0 z-40 bg-white dark:bg-slate-900 border-b dark:border-slate-800 transition-colors duration-300">
            @include('navbar.nav')
        </div>

        <main class="flex-1 overflow-y-auto p-8">
            <div class="max-w-7xl mx-auto space-y-6">

                {{-- Header --}}
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-extrabold text-slate-800 dark:text-white">Pendapatan Saya</h1>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Riwayat pendapatan dari proyek yang telah dikerjakan.</p>
                    </div>

                    {{-- Tombol Tarik Dana (hanya jika saldo tersedia > 0) --}}
                    @if((float) ($availableBalance ?? 0) > 0)
                        <button type="button" onclick="openWithdrawModal()"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-xl text-sm font-semibold hover:shadow-lg hover:shadow-emerald-500/30 hover:-translate-y-0.5 active:translate-y-0 transition-all">
                            <i class="fa-solid fa-money-bill-transfer"></i>
                            Tarik Dana
                        </button>
                    @endif
                </div>

                {{-- Stat Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">

                    {{-- Kartu 1: Saldo Tersedia --}}
                    <div class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl shadow-sm p-5 transition-colors duration-300">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-wallet text-emerald-600 dark:text-emerald-300 text-xl"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs text-slate-400 dark:text-slate-400 font-bold uppercase tracking-wider">Saldo Tersedia</p>
                                <h3 class="text-2xl font-black text-emerald-600 dark:text-emerald-300" title="Rp {{ number_format($availableBalance ?? 0, 0, ',', '.') }}">
                                    {{ formatRupiahShort($availableBalance ?? 0) }}
                                </h3>
                            </div>
                        </div>
                    </div>

                    {{-- Kartu 2: Saldo Tertahan (Escrow) --}}
                    <div class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl shadow-sm p-5 transition-colors duration-300">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-xl bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-clock text-amber-600 dark:text-amber-300 text-xl"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs text-slate-400 dark:text-slate-400 font-bold uppercase tracking-wider">Saldo Tertahan (Escrow)</p>
                                <h3 class="text-2xl font-black text-amber-600 dark:text-amber-300" title="Rp {{ number_format($totalHeld ?? 0, 0, ',', '.') }}">
                                    {{ formatRupiahShort($totalHeld ?? 0) }}
                                </h3>
                                @if((float) ($totalPending ?? 0) > 0)
                                    <p class="text-[10px] text-slate-400 dark:text-slate-400 mt-1" title="Rp {{ number_format($totalPending, 0, ',', '.') }}">
                                        + {{ formatRupiahShort($totalPending) }} menunggu pembayaran
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Kartu 3: Total Pendapatan --}}
                    <div class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl shadow-sm p-5 transition-colors duration-300">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-xl bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-sack-dollar text-blue-600 dark:text-blue-300 text-xl"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs text-slate-400 dark:text-slate-400 font-bold uppercase tracking-wider">Total Pendapatan</p>
                                <h3 class="text-2xl font-black text-blue-600 dark:text-blue-300" title="Rp {{ number_format($totalEarned ?? 0, 0, ',', '.') }}">
                                    {{ formatRupiahShort($totalEarned ?? 0) }}
                                </h3>
                            </div>
                        </div>
                    </div>

                    {{-- Kartu 4: Direfund ke Company --}}
                    <div class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl shadow-sm p-5 transition-colors duration-300">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-xl bg-red-100 dark:bg-red-900/40 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-rotate-left text-red-600 dark:text-red-300 text-xl"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs text-slate-400 dark:text-slate-400 font-bold uppercase tracking-wider">Direfund ke Company</p>
                                <h3 class="text-2xl font-black text-red-600 dark:text-red-300" title="Rp {{ number_format($totalRefunded ?? 0, 0, ',', '.') }}">
                                    {{ formatRupiahShort($totalRefunded ?? 0) }}
                                </h3>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Flash Messages --}}
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

                {{-- Daftar Pendapatan --}}
                <div class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden transition-colors duration-300">
                    <div class="px-6 py-5 border-b border-blue-50 dark:border-slate-800">
                        <h2 class="font-bold text-slate-800 dark:text-white">Riwayat Pendapatan</h2>
                    </div>

                    @if(isset($payments) && $payments->count() > 0)
                        <div class="divide-y divide-slate-50 dark:divide-slate-800">
                            @foreach($payments as $payment)
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-[#f6f9ff] dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-blue-100 dark:border-slate-700',
                                        'waiting_verification' => 'bg-amber-50 dark:bg-amber-900/40 text-amber-600 dark:text-amber-300 border-amber-200 dark:border-amber-900',
                                        'paid' => 'bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-300 border-emerald-200 dark:border-emerald-900',
                                        'rejected' => 'bg-red-50 dark:bg-red-900/40 text-red-600 dark:text-red-300 border-red-200 dark:border-red-900',
                                    ];
                                    $statusLabels = [
                                        'pending' => 'Pending',
                                        'waiting_verification' => 'Menunggu Verifikasi',
                                        'paid' => 'Dibayar',
                                        'rejected' => 'Ditolak',
                                    ];
                                    $sc = $statusColors[$payment->status] ?? $statusColors['pending'];
                                    $sl = $statusLabels[$payment->status] ?? $payment->status;
                                    
                                    // Deteksi nomor pembayaran / VA / Bank tempat dana ditahan
                                    $paymentNo = $payment->account_number ?? $payment->va_number ?? $payment->payment_code ?? null;
                                    $paymentMethod = $payment->payment_method ?? $payment->bank_name ?? $payment->payment_channel ?? 'Rekening System/Escrow';
                                @endphp
                                <div class="px-6 py-4 hover:bg-[#f6f9ff]/50 dark:hover:bg-slate-800/50 transition">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <span class="text-xs font-bold text-slate-700 dark:text-white">{{ $payment->invoice_number }}</span>
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full border {{ $sc }}">
                                                    {{ $sl }}
                                                </span>
                                                @if($payment->funds_status !== 'not_applicable')
                                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full border {{ $payment->funds_status_color }}">
                                                        {{ $payment->funds_status_label }}
                                                    </span>
                                                @endif
                                            </div>

                                            <p class="text-sm font-semibold text-slate-800 dark:text-white mt-1 truncate">
                                                {{ $payment->workspace->project->project_name ?? '-' }}
                                            </p>
                                            
                                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                                <i class="fa-solid fa-building mr-1"></i>{{ $payment->company->name ?? '-' }}
                                            </p>

                                            {{-- Detail Metode & Nomor Pembayaran --}}
                                            <p class="text-xs font-medium text-slate-600 dark:text-slate-300 mt-1 flex items-center gap-1.5">
                                                <i class="fa-solid fa-credit-card text-blue-500"></i>
                                                <span>{{ $paymentMethod }}</span>
                                                @if($paymentNo)
                                                    <span class="font-bold text-slate-800 dark:text-slate-200">({{ $paymentNo }})</span>
                                                @endif
                                            </p>

                                            <p class="text-[10px] text-slate-400 dark:text-slate-400 mt-1">
                                                <i class="fa-regular fa-clock mr-1"></i>{{ $payment->created_at->format('d M Y H:i') }}
                                            </p>
                                        </div>

                                        <div class="text-right shrink-0">
                                            <p class="text-sm font-bold text-slate-800 dark:text-white" title="Rp {{ number_format($payment->amount, 0, ',', '.') }}">
                                                {{ formatRupiahShort($payment->amount) }}
                                            </p>

                                            @if($payment->funds_status === 'released' || $payment->funds_status === 'released_partial')
                                                <p class="text-xs font-semibold text-emerald-600 dark:text-emerald-300 mt-1" title="Rp {{ number_format($payment->released_amount, 0, ',', '.') }}">
                                                    <i class="fa-solid fa-check-circle"></i> Dirilis: {{ formatRupiahShort($payment->released_amount) }}
                                                </p>
                                            @elseif($payment->isFundsHeld())
                                                <p class="text-xs font-semibold text-amber-600 dark:text-amber-300 mt-1" title="Rp {{ number_format($payment->freelancer_receive, 0, ',', '.') }}">
                                                    <i class="fa-solid fa-lock"></i> Ditahan: {{ formatRupiahShort($payment->freelancer_receive) }}
                                                </p>
                                            @elseif($payment->funds_status === 'refunded' || $payment->funds_status === 'refunded_partial')
                                                <p class="text-xs text-red-500 dark:text-red-300 mt-1" title="Rp {{ number_format($payment->refunded_amount, 0, ',', '.') }}">
                                                    Direfund: {{ formatRupiahShort($payment->refunded_amount) }}
                                                </p>
                                            @elseif($payment->status === 'rejected')
                                                <p class="text-xs text-red-500 dark:text-red-300 mt-1">Pembayaran ditolak</p>
                                            @else
                                                <p class="text-xs text-amber-500 dark:text-amber-300 mt-1">Menunggu pembayaran</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if(method_exists($payments, 'links'))
                            <div class="px-6 py-4 border-t border-blue-50 dark:border-slate-800">
                                {{ $payments->links() }}
                            </div>
                        @endif
                    @else
                        <div class="py-16 text-center">
                            <div class="w-16 h-16 mx-auto mb-4 bg-blue-50 dark:bg-slate-800 rounded-2xl flex items-center justify-center">
                                <i class="fa-solid fa-wallet text-2xl text-slate-400 dark:text-slate-400"></i>
                            </div>
                            <h3 class="text-sm font-bold text-slate-600 dark:text-slate-300">Belum Ada Pendapatan</h3>
                            <p class="text-xs text-slate-400 dark:text-slate-400 mt-1">Pendapatan akan muncul setelah proyek selesai dan pembayaran diverifikasi.</p>
                            <a href="{{ route('freelancer.workspaces.index') }}"
                               class="inline-flex items-center gap-2 mt-6 px-5 py-2.5 bg-brand text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
                                <i class="fa-solid fa-layer-group"></i> Lihat Workspace
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Riwayat Penarikan --}}
                <div class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden transition-colors duration-300">
                    <div class="px-6 py-5 border-b border-blue-50 dark:border-slate-800 flex items-center justify-between">
                        <h2 class="font-bold text-slate-800 dark:text-white">Riwayat Penarikan</h2>
                        @if((float) ($withdrawnBalance ?? 0) > 0)
                            <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">
                                Total ditarik: <span class="text-emerald-600 dark:text-emerald-300 font-bold" title="Rp {{ number_format($withdrawnBalance, 0, ',', '.') }}">{{ formatRupiahShort($withdrawnBalance) }}</span>
                            </span>
                        @endif
                    </div>

                    @if(isset($withdrawals) && $withdrawals->count() > 0)
                        <div class="divide-y divide-slate-50 dark:divide-slate-800">
                            @foreach($withdrawals as $wd)
                                <div class="px-4 sm:px-6 py-4 hover:bg-[#f6f9ff]/50 dark:hover:bg-slate-800/50 transition">
                                    <div class="flex items-start gap-3 sm:gap-4">
                                        {{-- Icon metode pembayaran --}}
                                        @include('partials.withdrawal-method-icon', ['wd' => $wd])

                                        {{-- Info utama --}}
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center flex-wrap gap-x-2 gap-y-1">
                                                <p class="text-sm font-bold text-slate-800 dark:text-white truncate">
                                                    {{ $wd->bank_name }}
                                                </p>
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full border {{ $wd->status_color }}">
                                                    {{ $wd->status === 'berhasil' ? 'Dana Dicairkan' : $wd->status_label }}
                                                </span>
                                            </div>

                                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 truncate">
                                                <i class="fa-solid fa-hashtag mr-1 text-slate-300 dark:text-slate-600"></i>{{ $wd->account_number }}
                                                <span class="mx-1 text-slate-300 dark:text-slate-600">•</span>
                                                {{ $wd->account_name }}
                                            </p>

                                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1.5">
                                                <p class="text-[10px] text-slate-400 dark:text-slate-400">
                                                    <i class="fa-regular fa-clock mr-1"></i>{{ $wd->paid_at?->format('d M Y H:i') ?? $wd->created_at->format('d M Y H:i') }}
                                                </p>
                                                <p class="text-[10px] text-slate-400 dark:text-slate-400">
                                                    <i class="fa-solid fa-tag mr-1"></i>{{ $wd->withdrawal_code }}
                                                </p>
                                            </div>

                                            @if($wd->status === 'ditolak' && $wd->rejection_reason)
                                                <div class="mt-2 px-3 py-2 bg-red-50 dark:bg-red-900/30 border border-red-100 dark:border-red-900 rounded-lg">
                                                    <p class="text-[10px] font-semibold text-red-600 dark:text-red-300">
                                                        <i class="fa-solid fa-circle-info mr-1"></i>Alasan penolakan: {{ $wd->rejection_reason }}
                                                    </p>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Nominal --}}
                                        <div class="text-right shrink-0">
                                            <p class="text-sm font-black text-slate-800 dark:text-white" title="Rp {{ number_format($wd->amount, 0, ',', '.') }}">
                                                {{ formatRupiahShort($wd->amount) }}
                                            </p>
                                            @if($wd->fee > 0)
                                                <p class="text-[10px] text-red-400 dark:text-red-400 mt-0.5">Fee Admin: -{{ formatRupiahShort($wd->fee) }}</p>
                                                <p class="text-[10px] font-bold text-emerald-600 dark:text-emerald-300 mt-0.5" title="Rp {{ number_format($wd->net_amount, 0, ',', '.') }}">
                                                    Diterima: {{ formatRupiahShort($wd->net_amount) }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if(method_exists($withdrawals, 'links'))
                            <div class="px-6 py-4 border-t border-blue-50 dark:border-slate-800">
                                {{ $withdrawals->links() }}
                            </div>
                        @endif
                    @else
                        <div class="py-16 text-center">
                            <div class="w-16 h-16 mx-auto mb-4 bg-amber-50 dark:bg-slate-800 rounded-2xl flex items-center justify-center">
                                <i class="fa-solid fa-money-bill-transfer text-2xl text-slate-400 dark:text-slate-400"></i>
                            </div>
                            <h3 class="text-sm font-bold text-slate-600 dark:text-slate-300">Belum Ada Penarikan</h3>
                            <p class="text-xs text-slate-400 dark:text-slate-400 mt-1">Anda belum pernah mengajukan penarikan dana.</p>
                        </div>
                    @endif
                </div>

            </div>
        </main>
    </div>
</div>

{{-- MODAL TARIK DANA --}}
<div id="withdrawModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-md p-4">
    <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl shadow-emerald-900/20 w-full max-w-lg overflow-hidden ring-1 ring-black/[.04] dark:ring-white/5 transition-colors duration-300 max-h-[92vh] overflow-y-auto">

        {{-- Header premium --}}
        <div class="relative px-6 pt-6 pb-5 overflow-hidden bg-gradient-to-br from-emerald-500 via-teal-500 to-emerald-600">
            <div class="absolute inset-0" style="background-image: radial-gradient(rgba(255,255,255,.14) 1.5px, transparent 1.5px); background-size: 18px 18px;"></div>
            <div class="absolute -top-12 -right-12 w-40 h-40 bg-white/10 rounded-full blur-xl"></div>
            <div class="absolute -bottom-14 -left-10 w-32 h-32 bg-emerald-300/20 rounded-full blur-xl"></div>
            <div class="relative flex items-center justify-between">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center ring-1 ring-white/30 shadow-lg shadow-emerald-900/20">
                        <i class="fa-solid fa-money-bill-transfer text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-white text-lg tracking-tight leading-none">Tarik Dana</h3>
                        <p class="text-[11px] text-white/80 mt-1">Cairkan saldo ke rekening pilihan</p>
                    </div>
                </div>
                <button type="button" onclick="closeWithdrawModal()"
                    class="w-9 h-9 rounded-full bg-white/20 hover:bg-white/30 hover:rotate-90 flex items-center justify-center transition-all duration-300 ring-1 ring-white/20">
                    <i class="fa-solid fa-xmark text-white text-sm"></i>
                </button>
            </div>
        </div>

        <div class="p-5 sm:p-6 space-y-5 bg-gradient-to-b from-emerald-50/50 dark:from-slate-800/40 to-white dark:to-slate-900">

            {{-- Saldo tersedia compact --}}
            <div class="flex items-center justify-between px-4 py-3 rounded-2xl bg-white dark:bg-slate-800/70 border border-emerald-100 dark:border-slate-700 shadow-sm shadow-emerald-900/5">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center">
                        <i class="fa-solid fa-wallet text-emerald-600 dark:text-emerald-300 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Saldo Tersedia</p>
                        <p class="text-base font-black text-emerald-600 dark:text-emerald-300 leading-none mt-0.5" id="modalAvailableBalance">
                            {{ formatRupiahShort($availableBalance ?? 0) }}
                        </p>
                    </div>
                </div>
                <span class="text-[9px] font-bold px-2.5 py-1 bg-amber-50 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 rounded-full border border-amber-200 dark:border-amber-900 shrink-0">Simulasi</span>
            </div>

            @if($errors->any())
                <div class="flex items-start gap-3 px-4 py-3 bg-red-50 dark:bg-red-900/40 border border-red-200 dark:border-red-900 text-red-700 dark:text-red-300 rounded-xl text-sm font-medium">
                    <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                    <div class="space-y-1">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('freelancer.withdrawals.store') }}" id="withdrawForm" class="space-y-5">
                @csrf

                {{-- Step 1: Metode pencairan --}}
                <div>
                    <div class="flex items-center justify-between mb-2.5">
                        <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">1. Metode Pencairan</p>
                    </div>

                    <div class="grid grid-cols-2 gap-2.5 mb-3">
                        <label for="method_bank" class="cursor-pointer">
                            <input type="radio" name="method" id="method_bank" value="bank"
                                   class="peer sr-only" @checked(old('method', 'bank') === 'bank')>
                            <div class="flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl border-2 border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-bold text-slate-500 dark:text-slate-400 transition-all peer-checked:border-emerald-500 peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-900/40 peer-checked:text-emerald-600 dark:peer-checked:text-emerald-300 hover:border-emerald-300">
                                <i class="fa-solid fa-building-columns"></i> Bank
                            </div>
                        </label>
                        <label for="method_ewallet" class="cursor-pointer">
                            <input type="radio" name="method" id="method_ewallet" value="ewallet"
                                   class="peer sr-only" @checked(old('method') === 'ewallet')>
                            <div class="flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl border-2 border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-bold text-slate-500 dark:text-slate-400 transition-all peer-checked:border-emerald-500 peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-900/40 peer-checked:text-emerald-600 dark:peer-checked:text-emerald-300 hover:border-emerald-300">
                                <i class="fa-solid fa-mobile-screen"></i> E-Wallet
                            </div>
                        </label>
                    </div>

                    {{-- Pilih bank / e-wallet --}}
                    <div id="bankCardsGroup" class="grid grid-cols-3 sm:grid-cols-4 gap-2">
                        @php
                            $bankOptions = ['BCA', 'BRI', 'BNI', 'Mandiri', 'BTN', 'CIMB Niaga', 'BSI', 'Danamon', 'Permata', 'Mayapada', 'Jago'];
                        @endphp
                        @foreach($bankOptions as $bank)
                            <label class="cursor-pointer">
                                <input type="radio" name="bank_name" value="{{ $bank }}" data-group="bank"
                                       class="peer sr-only" @checked(old('bank_name') === $bank)>
                                <div class="flex flex-col items-center gap-1.5 px-2 py-3 rounded-xl border-2 border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-900 transition-all peer-checked:border-emerald-500 peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-900/40 peer-checked:shadow-md peer-checked:shadow-emerald-500/10 hover:border-emerald-300">
                                    @include('partials.withdrawal-method-logo', ['name' => $bank])
                                    <span class="text-[9px] font-bold text-slate-500 dark:text-slate-400 leading-none">{{ $bank }}</span>
                                </div>
                            </label>
                        @endforeach
                        <label class="cursor-pointer">
                            <input type="radio" name="bank_name" value="Bank lainnya" data-group="bank"
                                   class="peer sr-only" @checked(old('bank_name') === 'Bank lainnya')>
                            <div class="flex flex-col items-center justify-center gap-1.5 px-2 py-3 rounded-xl border-2 border-dashed border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/40 text-slate-400 dark:text-slate-500 transition-all peer-checked:border-emerald-500 peer-checked:text-emerald-600 dark:peer-checked:text-emerald-300 peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-900/40 hover:border-emerald-300">
                                <i class="fa-solid fa-plus text-sm"></i>
                                <span class="text-[9px] font-bold leading-none">Lainnya</span>
                            </div>
                        </label>
                    </div>

                    <div id="ewalletCardsGroup" class="grid grid-cols-3 sm:grid-cols-4 gap-2 hidden">
                        @php
                            $ewalletOptions = ['OVO', 'GoPay', 'DANA', 'ShopeePay', 'LinkAja', 'iSaku', 'PayPal'];
                        @endphp
                        @foreach($ewalletOptions as $wallet)
                            <label class="cursor-pointer">
                                <input type="radio" name="bank_name" value="{{ $wallet }}" data-group="ewallet"
                                       class="peer sr-only" @checked(old('bank_name') === $wallet)>
                                <div class="flex flex-col items-center gap-1.5 px-2 py-3 rounded-xl border-2 border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-900 transition-all peer-checked:border-emerald-500 peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-900/40 peer-checked:shadow-md peer-checked:shadow-emerald-500/10 hover:border-emerald-300">
                                    @include('partials.withdrawal-method-logo', ['name' => $wallet])
                                    <span class="text-[9px] font-bold text-slate-500 dark:text-slate-400 leading-none">{{ $wallet }}</span>
                                </div>
                            </label>
                        @endforeach
                        <label class="cursor-pointer">
                            <input type="radio" name="bank_name" value="E-Wallet lainnya" data-group="ewallet"
                                   class="peer sr-only" @checked(old('bank_name') === 'E-Wallet lainnya')>
                            <div class="flex flex-col items-center justify-center gap-1.5 px-2 py-3 rounded-xl border-2 border-dashed border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/40 text-slate-400 dark:text-slate-500 transition-all peer-checked:border-emerald-500 peer-checked:text-emerald-600 dark:peer-checked:text-emerald-300 peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-900/40 hover:border-emerald-300">
                                <i class="fa-solid fa-plus text-sm"></i>
                                <span class="text-[9px] font-bold leading-none">Lainnya</span>
                            </div>
                        </label>
                    </div>
                    @error('method')
                        <p class="text-xs text-red-500 dark:text-red-400 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                    @enderror
                    @error('bank_name')
                        <p class="text-xs text-red-500 dark:text-red-400 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                {{-- Step 2: Detail rekening --}}
                <div>
                    <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2.5">2. Detail Rekening</p>
                    <div class="space-y-3">
                        <div>
                            <label for="account_name" class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 mb-1.5">Nama Pemilik Rekening</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"><i class="fa-solid fa-user text-xs"></i></span>
                                <input type="text" name="account_name" id="account_name" value="{{ old('account_name', Auth::user()->name ?? '') }}" maxlength="255"
                                       placeholder="Nama sesuai rekening/e-wallet"
                                       class="w-full pl-10 pr-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-700 rounded-xl text-sm text-slate-700 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/40 focus:border-emerald-400">
                            </div>
                            @error('account_name')
                                <p class="text-xs text-red-500 dark:text-red-400 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="account_number" class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 mb-1.5">Nomor Rekening / E-Wallet</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"><i class="fa-solid fa-hashtag text-xs"></i></span>
                                <input type="text" name="account_number" id="account_number" value="{{ old('account_number') }}" inputmode="numeric"
                                       placeholder="Masukkan nomor rekening / e-wallet"
                                       class="w-full pl-10 pr-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-700 rounded-xl text-sm text-slate-700 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/40 focus:border-emerald-400">
                            </div>
                            @error('account_number')
                                <p class="text-xs text-red-500 dark:text-red-400 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Step 3: Nominal penarikan --}}
                <div>
                    <div class="flex items-center justify-between mb-2.5">
                        <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">3. Nominal Penarikan</p>
                    </div>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xl font-black text-slate-700 dark:text-slate-300">Rp</span>
                        <input type="text" name="amount" id="amount" value="{{ old('amount') }}" inputmode="numeric"
                               placeholder="0" maxlength="20"
                               class="w-full pl-14 pr-4 py-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-700 rounded-2xl text-2xl font-black tracking-tight text-slate-800 dark:text-white placeholder-slate-300 dark:placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-400/40 focus:border-emerald-400">
                    </div>

                    {{-- Quick chips --}}
                    <div class="flex flex-wrap gap-2 mt-3">
                        <button type="button" data-amount="50000" class="quick-amount text-[11px] font-bold px-3.5 py-2 rounded-xl border border-emerald-200 dark:border-emerald-900 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-300 hover:bg-emerald-100 dark:hover:bg-emerald-900/50 active:scale-95 transition">Rp 50.000</button>
                        <button type="button" data-amount="100000" class="quick-amount text-[11px] font-bold px-3.5 py-2 rounded-xl border border-emerald-200 dark:border-emerald-900 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-300 hover:bg-emerald-100 dark:hover:bg-emerald-900/50 active:scale-95 transition">Rp 100.000</button>
                        <button type="button" data-amount="250000" class="quick-amount text-[11px] font-bold px-3.5 py-2 rounded-xl border border-emerald-200 dark:border-emerald-900 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-300 hover:bg-emerald-100 dark:hover:bg-emerald-900/50 active:scale-95 transition">Rp 250.000</button>
                        <button type="button" id="amountMax" class="text-[11px] font-bold px-3.5 py-2 rounded-xl border border-blue-200 dark:border-blue-900 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/50 active:scale-95 transition">Maksimal</button>
                    </div>

                    <p id="amountHint" class="text-[10px] text-slate-400 dark:text-slate-500 mt-2.5">
                        Minimal penarikan {{ formatRupiahShort($minWithdraw ?? 0) }} dan tidak boleh melebihi saldo tersedia.
                    </p>

                    {{-- Progress bar --}}
                    <div class="mt-3">
                        <div class="h-1.5 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                            <div id="amountProgress" class="h-full bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full transition-all duration-300" style="width:0%"></div>
                        </div>
                        <div class="flex justify-between text-[9px] text-slate-400 dark:text-slate-500 mt-1">
                            <span>0%</span>
                            <span>100% saldo</span>
                        </div>
                    </div>

                    @error('amount')
                        <p class="text-xs text-red-500 dark:text-red-400 mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                {{-- Ringkasan --}}
                <div class="px-4 py-4 bg-white dark:bg-slate-800/60 border border-slate-100 dark:border-slate-700 rounded-2xl space-y-2.5">
                    <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Ringkasan Penarikan</p>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500 dark:text-slate-400">Metode</span>
                        <span class="font-semibold text-slate-700 dark:text-white" id="summaryMethod">Bank</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500 dark:text-slate-400">Tujuan</span>
                        <span class="font-semibold text-slate-700 dark:text-white truncate max-w-[180px]" id="summaryAccount">-</span>
                    </div>
                    <div class="h-px bg-slate-100 dark:bg-slate-700"></div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500 dark:text-slate-400">Nominal penarikan</span>
                        <span class="font-black text-slate-800 dark:text-white text-sm" id="summaryAmount">Rp 0</span>
                    </div>
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="text-slate-500 dark:text-slate-400">Fee withdrawal admin ({{ rtrim(rtrim(number_format($withdrawalFeeRate, 2, '.', ''), '0'), '.') }}%)</span>
                        <span class="font-bold text-amber-600 dark:text-amber-400" id="summaryTax">-Rp 0</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500 dark:text-slate-400">Nominal diterima</span>
                        <span class="font-black text-emerald-600 dark:text-emerald-300 text-sm" id="summaryReceived">Rp 0</span>
                    </div>
                    <div class="flex items-center justify-between text-[11px] border-t border-slate-100 dark:border-slate-700 pt-2.5">
                        <span class="text-slate-500 dark:text-slate-400">Saldo setelah penarikan</span>
                        <span class="font-bold text-slate-700 dark:text-white" id="summaryRemaining">-</span>
                    </div>
                </div>

                {{-- Simulasi --}}
                <div class="flex items-center gap-3 px-4 py-3 bg-amber-50 dark:bg-amber-900/40 border border-amber-200 dark:border-amber-900 rounded-xl text-[11px] text-amber-700 dark:text-amber-300">
                    <i class="fa-solid fa-circle-info shrink-0"></i>
                    <p>Ini adalah simulasi penarikan dana. Tidak ada uang sungguhan yang ditransfer pada tahap ini.</p>
                </div>

                {{-- Tombol Ajukan --}}
                <button type="submit" id="withdrawSubmitBtn" disabled
                        class="w-full flex items-center justify-center gap-2 px-4 py-3.5 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-2xl text-sm font-bold shadow-lg shadow-emerald-500/30 hover:shadow-xl hover:shadow-emerald-500/40 hover:-translate-y-0.5 active:translate-y-0 transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0 disabled:shadow-none">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span>Ajukan Penarikan</span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    const availableBalance = {{ (float) ($availableBalance ?? 0) }};
    const minWithdraw = {{ (int) ($minWithdraw ?? 0) }};
    const withdrawModal = document.getElementById('withdrawModal');

    function formatShortRupiahJS(num) {
        num = Number(num || 0);
        if (num >= 1000000000000) {
            let val = (num / 1000000000000).toFixed(2).replace(/\.00$/, '');
            return 'Rp ' + val + ' T';
        }
        if (num > 999000000) {
            let val = (num / 1000000000).toFixed(2).replace(/\.00$/, '');
            return 'Rp ' + val + ' M';
        }
        return 'Rp ' + num.toLocaleString('id-ID');
    }

    function openWithdrawModal() {
        withdrawModal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeWithdrawModal() {
        withdrawModal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // Buka modal otomatis jika ada error validasi dari form penarikan.
    @if($errors->any())
        document.addEventListener('DOMContentLoaded', openWithdrawModal);
    @endif

    // Tutup modal saat klik backdrop.
    withdrawModal.addEventListener('click', function (e) {
        if (e.target === withdrawModal) closeWithdrawModal();
    });

    // Tombol Esc menutup modal.
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !withdrawModal.classList.contains('hidden')) closeWithdrawModal();
    });

    // Toggle opsi bank/e-wallet berdasarkan metode.
    const methodInputs = document.querySelectorAll('input[name="method"]');
    const bankCardsGroup = document.getElementById('bankCardsGroup');
    const ewalletCardsGroup = document.getElementById('ewalletCardsGroup');
    const accountNumberLabel = document.querySelector('label[for="account_number"]');
    const accountNumberPlaceholder = document.getElementById('account_number');
    const summaryMethod = document.getElementById('summaryMethod');
    const summaryAccount = document.getElementById('summaryAccount');

    function getDigits(value) {
        return (value || '').replace(/[^\d]/g, '').slice(0, 15);
    }

    function formatRupiah(digits) {
        return digits ? Number(digits).toLocaleString('id-ID') : '';
    }

    function syncMethod() {
        const checkedMethod = document.querySelector('input[name="method"]:checked');
        const method = checkedMethod ? checkedMethod.value : 'bank';
        const isEwallet = method === 'ewallet';

        bankCardsGroup.classList.toggle('hidden', isEwallet);
        ewalletCardsGroup.classList.toggle('hidden', !isEwallet);

        // Reset pilihan bank bila optgroup disembunyikan.
        const checkedBank = document.querySelector('input[name="bank_name"]:checked');
        if (checkedBank && checkedBank.dataset.group !== method) {
            checkedBank.checked = false;
        }

        accountNumberLabel.textContent = isEwallet ? 'Nomor E-Wallet' : 'Nomor Rekening';
        accountNumberPlaceholder.placeholder = isEwallet
            ? 'Contoh: 081234567890'
            : 'Contoh: 1234567890';

        summaryMethod.textContent = isEwallet ? 'E-Wallet' : 'Bank';
        updateSummaryAccount();
    }

    function updateSummaryAccount() {
        const name = document.getElementById('account_name').value.trim();
        const checkedBank = document.querySelector('input[name="bank_name"]:checked');
        const dest = checkedBank ? checkedBank.value : '';
        summaryAccount.textContent = dest ? (dest + (name ? ' — ' + name : '')) : (name || '-');
    }

    // Nominal & ringkasan.
    const amountInput = document.getElementById('amount');
    const amountHint = document.getElementById('amountHint');
    const amountProgress = document.getElementById('amountProgress');
    const summaryAmount = document.getElementById('summaryAmount');
    const summaryTax = document.getElementById('summaryTax');
    const summaryReceived = document.getElementById('summaryReceived');
    const summaryRemaining = document.getElementById('summaryRemaining');
    const withdrawSubmitBtn = document.getElementById('withdrawSubmitBtn');
    const withdrawForm = document.getElementById('withdrawForm');

    // Rate fee withdrawal dari Financial Settings (server-side truth).
    window.__withdrawalFeeRate = {{ (float) ($withdrawalFeeRate ?? 5) }};

    let amountState = 'empty'; // empty | ok | error

    function updateAmountState() {
        const digits = getDigits(amountInput.value);
        const value = digits ? Number(digits) : 0;
        const pct = availableBalance > 0 ? Math.min(100, Math.round((value / availableBalance) * 100)) : 0;
        amountProgress.style.width = pct + '%';

        let state = 'empty';
        let hint = 'Minimal penarikan ' + formatShortRupiahJS(minWithdraw) +
            ' dan tidak boleh melebihi saldo tersedia.';

        if (value > 0) {
            if (value < minWithdraw) {
                state = 'error';
                hint = 'Minimal penarikan adalah ' + formatShortRupiahJS(minWithdraw) + '.';
            } else if (value > availableBalance) {
                state = 'error';
                hint = 'Nominal melebihi saldo tersedia (' + formatShortRupiahJS(availableBalance) + ').';
            } else {
                state = 'ok';
                hint = 'Nominal valid.';
            }
        }

        amountState = state;
        amountHint.textContent = hint;
        amountHint.className = 'text-[10px] mt-2.5 ' +
            (state === 'error' ? 'text-red-500 dark:text-red-400' : 'text-slate-400 dark:text-slate-500');

        const fee = value > 0 ? Math.round(value * (window.__withdrawalFeeRate ?? 5) / 100) : 0;
        const received = value - fee;

        summaryAmount.textContent = formatShortRupiahJS(value);
        summaryTax.textContent = '-' + formatShortRupiahJS(fee);
        summaryReceived.textContent = value > 0
            ? formatShortRupiahJS(received)
            : 'Rp 0';
        summaryRemaining.textContent = value > 0 && state === 'ok'
            ? formatShortRupiahJS(availableBalance - value)
            : '-';

        withdrawSubmitBtn.disabled = state !== 'ok';
    }

    amountInput.addEventListener('input', function () {
        this.value = formatRupiah(getDigits(this.value));
        updateAmountState();
    });

    function setAmount(digits) {
        amountInput.value = formatRupiah(String(digits));
        updateAmountState();
    }

    document.querySelectorAll('.quick-amount').forEach(function (btn) {
        btn.addEventListener('click', function () {
            setAmount(this.dataset.amount);
        });
    });

    document.getElementById('amountMax').addEventListener('click', function () {
        setAmount(Math.floor(availableBalance));
    });

    // Update ringkasan tujuan saat isi form.
    document.getElementById('account_name').addEventListener('input', updateSummaryAccount);
    document.querySelectorAll('input[name="bank_name"]').forEach(function (input) {
        input.addEventListener('change', updateSummaryAccount);
    });

    methodInputs.forEach(input => input.addEventListener('change', syncMethod));
    syncMethod();
    updateAmountState();

    // Normalisasi nominal ke angka bulat (hapus format Rupiah) SEBELUM dikirim.
    withdrawForm.addEventListener('submit', function () {
        amountInput.value = getDigits(amountInput.value);
        const btn = withdrawSubmitBtn;
        btn.disabled = true;
        btn.querySelector('span').textContent = 'Mengirim...';
        btn.querySelector('i').className = 'fa-solid fa-spinner fa-spin';
    });
</script>

</body>
</html>