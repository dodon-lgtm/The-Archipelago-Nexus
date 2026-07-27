<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendapatan - The Archipelago Nexus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800">

<div class="flex h-screen overflow-hidden">
    @include('navbar.navigasi')

    <div class="flex-1 flex flex-col overflow-hidden">
        <div class="sticky top-0 z-40 bg-white border-b">
            @include('navbar.nav')
        </div>

        <main class="flex-1 overflow-y-auto p-8">
            <div class="max-w-7xl mx-auto space-y-6">

                {{-- Header --}}
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-extrabold text-slate-800">Pendapatan Saya</h1>
                        <p class="text-sm text-slate-500 mt-1">Riwayat pendapatan dari proyek yang telah dikerjakan.</p>
                    </div>
                </div>

                {{-- Stat Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-xl bg-emerald-100 flex items-center justify-center">
                                <i class="fa-solid fa-wallet text-emerald-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Total Diterima</p>
                                <h3 class="text-2xl font-black text-emerald-600">Rp {{ number_format($totalEarned, 0, ',', '.') }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-xl bg-amber-100 flex items-center justify-center">
                                <i class="fa-solid fa-clock text-amber-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Menunggu</p>
                                <h3 class="text-2xl font-black text-amber-600">Rp {{ number_format($totalPending, 0, ',', '.') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="flex items-center gap-3 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium">
                        <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="flex items-center gap-3 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm font-medium">
                        <i class="fa-solid fa-xmark-circle"></i> {{ session('error') }}
                    </div>
                @endif

                {{-- Daftar Pendapatan --}}
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100">
                        <h2 class="font-bold text-slate-800">Riwayat Pendapatan</h2>
                    </div>

                    @if($payments->count() > 0)
                        <div class="divide-y divide-slate-50">
                            @foreach($payments as $payment)
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-slate-50 text-slate-600 border-slate-200',
                                        'waiting_verification' => 'bg-amber-50 text-amber-600 border-amber-200',
                                        'paid' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                        'rejected' => 'bg-red-50 text-red-600 border-red-200',
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
                                <div class="px-6 py-4 hover:bg-slate-50/50 transition">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs font-bold text-slate-700">{{ $payment->invoice_number }}</span>
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full border {{ $sc }}">
                                                    {{ $sl }}
                                                </span>
                                            </div>
                                            <p class="text-sm font-semibold text-slate-800 mt-1 truncate">
                                                {{ $payment->workspace->project->project_name ?? '-' }}
                                            </p>
                                            <p class="text-xs text-slate-500 mt-0.5">
                                                <i class="fa-solid fa-building mr-1"></i>{{ $payment->company->name ?? '-' }}
                                            </p>
                                            <p class="text-[10px] text-slate-400 mt-1">
                                                <i class="fa-regular fa-clock mr-1"></i>{{ $payment->created_at->format('d M Y H:i') }}
                                            </p>
                                        </div>
                                        <div class="text-right shrink-0">
                                            <p class="text-sm font-bold text-slate-800">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                            @if($payment->status === 'paid')
                                                <p class="text-xs font-semibold text-emerald-600 mt-1">
                                                    <i class="fa-solid fa-check-circle"></i> Diterima: Rp {{ number_format($payment->freelancer_receive, 0, ',', '.') }}
                                                </p>
                                            @elseif($payment->status === 'rejected')
                                                <p class="text-xs text-red-500 mt-1">Pembayaran ditolak</p>
                                            @else
                                                <p class="text-xs text-amber-500 mt-1">Menunggu pembayaran</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if(method_exists($payments, 'links'))
                            <div class="px-6 py-4 border-t border-slate-100">
                                {{ $payments->links() }}
                            </div>
                        @endif
                    @else
                        <div class="py-16 text-center">
                            <div class="w-16 h-16 mx-auto mb-4 bg-slate-100 rounded-2xl flex items-center justify-center">
                                <i class="fa-solid fa-wallet text-2xl text-slate-400"></i>
                            </div>
                            <h3 class="text-sm font-bold text-slate-600">Belum Ada Pendapatan</h3>
                            <p class="text-xs text-slate-400 mt-1">Pendapatan akan muncul setelah proyek selesai dan pembayaran diverifikasi.</p>
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

</body>
</html>

