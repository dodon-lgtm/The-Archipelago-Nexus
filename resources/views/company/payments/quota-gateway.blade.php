<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Kuota Proyek - {{ $payment->invoice_number }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] }, colors: { brand: '#2563EB', surface: '#F8FAFC' } } }
        }
    </script>
    <style>
        .stepper-dot{width:34px;height:34px;border-radius:9999px;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700}
        .stepper-line{height:2px;flex:1}
        .spinner{width:38px;height:38px;border:4px solid rgba(37,99,235,.15);border-top-color:#2563EB;border-radius:50%;animation:spin .8s linear infinite}
        @keyframes spin{to{transform:rotate(360deg)}}
    </style>
</head>

<body class="bg-surface text-slate-800 min-h-screen flex font-sans">

    @include('navbar.navigasi')

    <div class="flex-1 min-w-0 flex flex-col min-h-screen">
        @include('navbar.nav')

        <main class="flex-1 min-w-0 overflow-y-auto">
            <div class="max-w-5xl mx-auto px-6 py-8">
                {{-- Breadcrumb --}}
                <div class="flex items-center gap-2 text-sm text-slate-400 mb-6">
                    <a href="{{ route('company.projects.index') }}" class="hover:text-brand transition">Proyek</a>
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    <span class="text-slate-600 font-medium">Pembayaran Kuota Proyek</span>
                </div>

                {{-- Stepper --}}
                <div class="flex items-center mb-8">
                    <div class="flex flex-col items-center">
                        <div class="stepper-dot {{ $payment->status === 'paid' ? 'bg-emerald-500' : 'bg-brand' }} text-white shadow-lg shadow-brand/30">
                            <i class="fa-solid fa-credit-card text-xs"></i>
                        </div>
                        <span class="text-[10px] font-bold mt-1.5 {{ $payment->status === 'paid' ? 'text-emerald-600' : 'text-brand' }}">Payment</span>
                    </div>
                    <div class="stepper-line {{ $payment->status === 'paid' ? 'bg-emerald-300' : 'bg-brand/30' }}"></div>
                    <div class="flex flex-col items-center">
                        <div class="stepper-dot bg-white text-slate-400 border border-blue-100">
                            <i class="fa-solid fa-shield-halved text-xs"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-slate-400 mt-1.5">Verification</span>
                    </div>
                    <div class="stepper-line bg-slate-200"></div>
                    <div class="flex flex-col items-center">
                        <div class="stepper-dot bg-white text-slate-400 border border-blue-100">
                            <i class="fa-solid fa-plus text-xs"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-slate-400 mt-1.5">+1 Slot Aktif</span>
                    </div>
                </div>

                @if (session('success'))
                    <div class="mb-6 flex items-center gap-3 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium">
                        <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-5 gap-6">

                    {{-- LEFT: STATUS / METODE --}}
                    <div class="md:col-span-3 space-y-6">

                        @php $canPay = in_array($payment->status, ['pending', 'rejected'], true); @endphp

                        <div class="bg-white border border-blue-100 rounded-2xl shadow-sm overflow-hidden">
                            <div class="px-6 py-5 border-b border-blue-50 flex items-center justify-between">
                                <div>
                                    <h2 class="font-bold text-base text-slate-800">Pembayaran Kuota Proyek</h2>
                                    <p class="text-xs text-slate-400 mt-0.5">
                                        @if ($canPay)
                                            Pilih metode pembayaran untuk menambah slot proyek
                                        @elseif ($payment->status === 'waiting_verification')
                                            Pembayaran sedang diproses
                                        @elseif ($payment->status === 'paid')
                                            Pembayaran telah selesai
                                        @else
                                            Status: {{ $payment->status_label }}
                                        @endif
                                    </p>
                                </div>
                                <div class="w-10 h-10 rounded-xl bg-blue-50 text-brand flex items-center justify-center">
                                    <i class="fa-solid fa-lock"></i>
                                </div>
                            </div>
                            <div class="p-6 space-y-5">
                                @if ($canPay)
                                    <p class="text-xs font-semibold text-slate-600 mb-2.5">Metode Pembayaran</p>

                                    @if ($payment->status === 'rejected')
                                        <div class="mb-4 flex items-center gap-3 px-4 py-3 bg-red-50 border border-red-200 text-red-600 rounded-xl text-xs font-medium">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            Pembayaran sebelumnya ditolak/gagal. Silakan bayar kembali.
                                        </div>
                                    @endif

                                    <div id="quotaPayBox" class="rounded-2xl p-5 bg-white border border-blue-100">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="inline-flex items-center justify-center w-11 h-11 rounded-xl bg-blue-50 text-brand">
                                                <i class="fa-solid fa-bolt text-lg"></i>
                                            </div>
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Otomatis via Midtrans</span>
                                        </div>
                                        <h3 class="font-bold text-slate-800 text-sm">Bayar dengan Midtrans</h3>
                                        <p class="text-[11px] text-slate-400 mt-1 leading-relaxed">
                                            QRIS, Virtual Account, E-Wallet, dan Kartu Kredit.
                                        </p>
                                        <button type="button" id="payQuotaBtn"
                                                data-payment-id="{{ $payment->id }}"
                                                data-status-url="{{ route('company.quota.payment.status', $payment) }}"
                                                data-midtrans-url="{{ route('company.quota.payment.midtrans', $payment) }}"
                                                data-invoice="{{ $payment->invoice_number }}"
                                                class="w-full flex items-center justify-center gap-2 mt-4 px-4 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl text-sm font-bold hover:from-blue-700 hover:to-indigo-700 transition shadow-lg shadow-brand/25 disabled:opacity-60 disabled:cursor-not-allowed">
                                            <i class="fa-solid fa-bolt text-xs"></i>
                                            <span>Bayar Sekarang — Rp {{ number_format($price, 0, ',', '.') }}</span>
                                        </button>
                                        <div id="quotaPayProcessing" class="hidden mt-5 text-center">
                                            <div class="spinner mx-auto"></div>
                                            <p class="text-xs font-semibold text-slate-600 mt-3">Menunggu konfirmasi pembayaran…</p>
                                            <p class="text-[11px] text-slate-400 mt-1">Jangan tutup halaman — status diperiksa otomatis.</p>
                                        </div>
                                        <button type="button" id="recheckQuotaBtn"
                                                class="hidden w-full flex items-center justify-center gap-2 mt-3 px-4 py-2.5 bg-blue-50 text-blue-600 border border-blue-200 rounded-xl text-xs font-bold hover:bg-blue-100 transition">
                                            <i class="fa-solid fa-rotate-right text-xs"></i>
                                            <span>Cek Status Lagi</span>
                                        </button>
                                        <p id="midtransError" class="text-[11px] text-slate-600 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 leading-relaxed mt-3 hidden"></p>
                                    </div>

                                    <a href="{{ route('company.projects.create') }}"
                                       class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-brand transition">
                                        <i class="fa-solid fa-arrow-left text-[10px]"></i> Kembali ke Buat Proyek
                                    </a>
                                @elseif ($payment->status === 'waiting_verification')
                                    <div class="flex items-start gap-4 px-5 py-4 bg-amber-50 border border-amber-200 rounded-2xl">
                                        <div class="w-10 h-10 shrink-0 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
                                            <i class="fa-solid fa-hourglass-half"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-amber-700">Pembayaran sedang diproses.</p>
                                            <p class="text-xs text-amber-600/90 mt-1">Slot akan aktif setelah konfirmasi selesai.</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('company.projects.create') }}"
                                       class="inline-flex items-center gap-2 text-xs font-semibold text-brand hover:underline">
                                        <i class="fa-solid fa-arrow-left text-[10px]"></i> Kembali ke Buat Proyek
                                    </a>
                                @elseif ($payment->status === 'paid')
                                    <div class="flex items-start gap-4 px-5 py-4 bg-emerald-50 border border-emerald-200 rounded-2xl">
                                        <div class="w-10 h-10 shrink-0 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                            <i class="fa-solid fa-check"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-emerald-700">Pembayaran Berhasil</p>
                                            <p class="text-xs text-emerald-600/90 mt-1 leading-relaxed">
                                                Kuota tambahan <strong>+1 Proyek</strong> telah aktif.
                                            </p>
                                            @if($payment->verified_at)
                                                <p class="text-[11px] text-emerald-500 mt-1">
                                                    Diverifikasi: {{ $payment->verified_at->translatedFormat('d M Y H:i') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <a href="{{ route('company.projects.create') }}"
                                       class="inline-flex items-center justify-center gap-2 w-full px-4 py-3 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700">
                                        <i class="fa-solid fa-plus text-xs"></i> Kembali ke Buat Proyek
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- RIGHT: RINGKASAN --}}
                    <div class="md:col-span-2 space-y-6">
                        <div class="bg-white border border-blue-100 rounded-2xl shadow-sm overflow-hidden">
                            <div class="px-5 py-4 border-b border-blue-50">
                                <h3 class="font-bold text-sm text-slate-800">Ringkasan Pembayaran</h3>
                            </div>
                            <div class="p-5 space-y-4">
                                <div class="flex items-center justify-between">
                                    <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Nomor Invoice</p>
                                    <span class="text-xs font-bold text-slate-700">{{ $payment->invoice_number }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Jenis</p>
                                    <span class="text-xs font-bold text-slate-700">Penambahan Kuota Proyek</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Tanggal</p>
                                    <span class="text-xs font-bold text-slate-700">{{ $payment->created_at->translatedFormat('d M Y H:i') }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Metode Pembayaran</p>
                                    <span class="text-xs font-bold text-slate-700">{{ $payment->payment_method ?? 'Midtrans' }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Kuota</p>
                                    <span class="text-xs font-bold text-emerald-600">+1 Proyek</span>
                                </div>

                                <div class="border-t border-blue-50 pt-4 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Harga</p>
                                        <span class="text-lg font-extrabold text-emerald-600">Rp {{ number_format($price, 0, ',', '.') }}</span>
                                    </div>
                                    <p class="text-[10px] text-slate-400">Nominal ditentukan oleh sistem.</p>
                                </div>

                                <div class="flex items-center justify-between bg-[#f6f9ff] rounded-xl px-4 py-3">
                                    <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Status</p>
                                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-full {{ $payment->status_color }}">
                                        {{ $payment->status === 'pending' ? 'Menunggu Pembayaran' : $payment->status_label }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white border border-blue-100 rounded-2xl shadow-sm p-5">
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-3">Informasi Kuota Bulan Ini</p>
                            <div class="space-y-2 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-500">Kuota Gratis Bulan Ini</span>
                                    <span class="font-bold text-slate-700">{{ $quota['free_quota'] }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-500">Kuota Terpakai</span>
                                    <span class="font-bold {{ $quota['used_slots'] >= $quota['free_quota'] ? 'text-red-500' : 'text-slate-700' }}">{{ $quota['used_slots'] }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-500">Kuota Berbayar</span>
                                    <span class="font-bold text-slate-700">{{ $quota['paid_slots'] ?? 0 }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-500">Kuota Tersedia</span>
                                    <span class="font-bold {{ max(0, $quota['available_slots'] - $quota['used_slots']) > 0 ? 'text-emerald-600' : 'text-red-500' }}">{{ max(0, $quota['available_slots'] - $quota['used_slots']) }}</span>
                                </div>
                            </div>
                            <div class="mt-4 rounded-xl bg-[#f6f9ff] px-4 py-3 flex items-center justify-between">
                                <span class="text-[11px] font-semibold text-slate-600">Tambahan Kuota</span>
                                <span class="text-xs font-extrabold text-brand">Rp {{ number_format($price, 0, ',', '.') }} / 1 proyek</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    @if(in_array($payment->status, ['pending','rejected','waiting_verification'], true))
        {{-- Midtrans Snap SDK + handler khusus kuota (polling status server-side) --}}
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
        <script src="{{ asset('js/payments/quota-midtrans.js') }}"></script>
    @endif
</body>

</html>