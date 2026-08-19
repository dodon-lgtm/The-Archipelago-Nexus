<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendapatan - ApexForge Labs</title>
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = tailwind.config || {};
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

                    {{-- Tombol Tarik Saldo (hanya jika total diterima > 0) --}}
                    @if((float) $totalEarned > 0)
                        <button type="button" onclick="document.getElementById('withdrawModal').classList.remove('hidden')"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-xl text-sm font-semibold hover:shadow-lg hover:shadow-emerald-500/30 hover:-translate-y-0.5 active:translate-y-0 transition-all">
                            <i class="fa-solid fa-money-bill-transfer"></i>
                            Tarik Saldo
                        </button>
                    @endif
                </div>

                {{-- Stat Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl shadow-sm p-5 transition-colors duration-300">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center">
                                <i class="fa-solid fa-wallet text-emerald-600 dark:text-emerald-300 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 dark:text-slate-400 font-bold uppercase tracking-wider">Total Diterima</p>
                                <h3 class="text-2xl font-black text-emerald-600 dark:text-emerald-300">Rp {{ number_format($totalEarned, 0, ',', '.') }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl shadow-sm p-5 transition-colors duration-300">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-xl bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center">
                                <i class="fa-solid fa-clock text-amber-600 dark:text-amber-300 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 dark:text-slate-400 font-bold uppercase tracking-wider">Menunggu</p>
                                <h3 class="text-2xl font-black text-amber-600 dark:text-amber-300">Rp {{ number_format($totalPending, 0, ',', '.') }}</h3>
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

                    @if($payments->count() > 0)
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
                                @endphp
                                <div class="px-6 py-4 hover:bg-[#f6f9ff]/50 dark:hover:bg-slate-800/50 transition">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs font-bold text-slate-700 dark:text-white">{{ $payment->invoice_number }}</span>
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full border {{ $sc }}">
                                                    {{ $sl }}
                                                </span>
                                            </div>
                                            <p class="text-sm font-semibold text-slate-800 dark:text-white mt-1 truncate">
                                                {{ $payment->workspace->project->project_name ?? '-' }}
                                            </p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                                <i class="fa-solid fa-building mr-1"></i>{{ $payment->company->name ?? '-' }}
                                            </p>
                                            <p class="text-[10px] text-slate-400 dark:text-slate-400 mt-1">
                                                <i class="fa-regular fa-clock mr-1"></i>{{ $payment->created_at->format('d M Y H:i') }}
                                            </p>
                                        </div>
                                        <div class="text-right shrink-0">
                                            <p class="text-sm font-bold text-slate-800 dark:text-white">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                            @if($payment->status === 'paid')
                                                <p class="text-xs font-semibold text-emerald-600 dark:text-emerald-300 mt-1">
                                                    <i class="fa-solid fa-check-circle"></i> Diterima: Rp {{ number_format($payment->freelancer_receive, 0, ',', '.') }}
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

</div>
        </main>
    </div>
</div>

{{-- ============================================================
     MODAL TARIK SALDO (Coming Soon)
============================================================ --}}
<div id="withdrawModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
    <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl shadow-slate-900/10 w-full max-w-md overflow-hidden ring-1 ring-black/[.03] transition-colors duration-300">

        {{-- Gradient header --}}
        <div class="relative px-6 py-7 bg-gradient-to-br from-emerald-500 via-teal-500 to-blue-500 overflow-hidden">
            <div class="absolute inset-0" style="background-image: radial-gradient(rgba(255,255,255,.16) 1.5px, transparent 1.5px); background-size: 16px 16px;"></div>
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full"></div>
            <div class="absolute -bottom-12 -left-8 w-28 h-28 bg-white/10 rounded-full"></div>
            <div class="relative flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center ring-1 ring-white/30">
                        <i class="fa-solid fa-money-bill-transfer text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-white text-base tracking-tight">🚧 Tarik Saldo</h3>
                        <p class="text-[11px] text-white/75">Fitur penarikan saldo</p>
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('withdrawModal').classList.add('hidden')"
                    class="w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 hover:rotate-90 flex items-center justify-center transition-all duration-300">
                    <i class="fa-solid fa-xmark text-white text-sm"></i>
                </button>
            </div>
        </div>

        <div class="p-6 space-y-5 bg-gradient-to-b from-emerald-50/40 dark:from-slate-800/40 to-white dark:to-slate-900">

            {{-- Deskripsi --}}
            <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                Fitur penarikan saldo sedang dalam tahap pengembangan.
                Pada versi berikutnya freelancer dapat mencairkan saldo langsung dari aplikasi.
            </p>

            {{-- Badge Coming Soon --}}
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300 text-[11px] font-bold ring-1 ring-amber-200 dark:ring-amber-900">
                    <i class="fa-solid fa-hourglass-half text-[10px]"></i>
                    Coming Soon
                </span>
            </div>

            {{-- Roadmap --}}
            <div class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl divide-y divide-slate-100 dark:divide-slate-800 overflow-hidden">
                <div class="px-4 py-3 flex items-center gap-3">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-300 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-check text-xs"></i>
                    </span>
                    <p class="text-sm font-semibold text-slate-700 dark:text-white">Transfer ke Rekening Bank</p>
                </div>
                <div class="px-4 py-3 flex items-center gap-3">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-300 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-check text-xs"></i>
                    </span>
                    <p class="text-sm font-semibold text-slate-700 dark:text-white">Transfer ke E-Wallet</p>
                </div>
                <div class="px-4 py-3 flex items-center gap-3">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-300 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-check text-xs"></i>
                    </span>
                    <p class="text-sm font-semibold text-slate-700 dark:text-white">Virtual Account</p>
                </div>
                <div class="px-4 py-3 flex items-center gap-3">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-300 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-check text-xs"></i>
                    </span>
                    <p class="text-sm font-semibold text-slate-700 dark:text-white">Riwayat Penarikan</p>
                </div>
            </div>

            {{-- Tombol Mengerti --}}
            <button type="button" onclick="document.getElementById('withdrawModal').classList.add('hidden')"
                class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-xl text-sm font-semibold hover:shadow-lg hover:shadow-emerald-500/30 hover:-translate-y-0.5 active:translate-y-0 transition-all">
                <i class="fa-solid fa-check"></i>
                Mengerti
            </button>
        </div>
    </div>
</div>

</body>
</html>

