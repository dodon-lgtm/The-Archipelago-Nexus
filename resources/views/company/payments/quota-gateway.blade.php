<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.theme-boot')
    <title>Pembayaran Kuota Proyek - {{ $payment->invoice_number }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] }, colors: { brand: '#2563EB', surface: '#F8FAFC' } } }
        }
    </script>
    <style>
        .stepper-dot{width:34px;height:34px;border-radius:9999px;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700}
        .stepper-line{height:2px;flex:1}
        .spinner{width:38px;height:38px;border:4px solid rgba(37,99,235,.15);border-top-color:#2563EB;border-radius:50%;animation:spin .8s linear infinite}
        @keyframes spin{to{transform:rotate(360deg)}}
        .dest-card{cursor:pointer;transition:all .2s ease}
        .dest-card:hover{border-color:rgba(37,99,235,.45)!important;box-shadow:0 8px 22px -12px rgba(37,99,235,.35)}
        .dest-card.active{border-color:#2563eb!important;box-shadow:0 0 0 4px rgba(37,99,235,.12)}
        html.dark input,html.dark select,html.dark textarea{background:rgba(30,41,59,.6);color:#e2e8f0;border-color:rgba(100,116,139,.35)!important}
        html.dark .dest-card{background:rgba(30,41,59,.55)}
    </style>
</head>

<body class="bg-surface dark:bg-slate-950 text-slate-800 dark:text-slate-100 min-h-screen flex font-sans transition-colors duration-300">

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
                        <div class="stepper-dot bg-white dark:bg-slate-900 text-slate-400 border border-blue-100 dark:border-slate-800">
                            <i class="fa-solid fa-shield-halved text-xs"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-slate-400 mt-1.5">Verification</span>
                    </div>
                    <div class="stepper-line bg-slate-200"></div>
                    <div class="flex flex-col items-center">
                        <div class="stepper-dot bg-white dark:bg-slate-900 text-slate-400 border border-blue-100 dark:border-slate-800">
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

                        <div class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                            <div class="px-6 py-5 border-b border-blue-50 flex items-center justify-between">
                                <div>
                                    <h2 class="font-bold text-base text-slate-800 dark:text-slate-100">Pembayaran Kuota Proyek</h2>
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

                                    <div id="quotaPayBox" class="rounded-2xl p-5 bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800">
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

                                        @if ((bool) config('services.midtrans.temporary_confirmation', false))
                                            {{-- MANUAL / DEMO PAYMENT (KUOTA) — hanya saat mode temporary confirmation aktif.
                                                 Nominal TIDAK dikirim dari sini; server memakai amount dari database. --}}
                                            <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 p-3.5">
                                                <p class="text-[11px] font-bold text-amber-800 mb-0.5">
                                                    <i class="fa-solid fa-flask"></i> Bayar Manual (Mode Demo)
                                                </p>
                                                <p class="text-[10px] text-amber-700 leading-relaxed mb-2">
                                                    Konfirmasi tanpa Midtrans untuk demo/testing. Nominal tetap dari database.
                                                </p>
                                                <button type="button" id="manualQuotaConfirmBtn"
                                                    class="w-full px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-xs font-bold transition disabled:opacity-60">
                                                    <i class="fa-solid fa-circle-check"></i>
                                                    Konfirmasi Manual — Rp {{ number_format($price, 0, ',', '.') }}
                                                </button>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- BAYAR MANUAL (KUOTA) — detail pembayaran manual,
                                         konsisten dengan pembayaran proyek sebelum Workspace. --}}
                                    <div class="rounded-2xl p-5 bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="inline-flex items-center justify-center w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600">
                                                <i class="fa-solid fa-money-bill-transfer text-lg"></i>
                                            </div>
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Manual</span>
                                        </div>
                                        <h3 class="font-bold text-slate-800 dark:text-slate-100 text-sm">Bayar Manual</h3>
                                        <p class="text-[11px] text-slate-400 mt-1 leading-relaxed">
                                            Transfer ke rekening/wallet ApexForge Labs, lalu kirim bukti pembayaran untuk diverifikasi Admin.
                                        </p>

                                        <form method="POST" action="{{ route('company.quota.payment.manual', $payment) }}" enctype="multipart/form-data" id="quotaManualForm" class="mt-4 space-y-4">
                                            @csrf

                                            <p class="text-xs font-semibold text-slate-600 dark:text-slate-300">Rekening / Wallet Tujuan Pembayaran</p>
                                            @foreach($destinations as $key => $destination)
                                                @php
                                                    $rows = $destination['rows'] ?? [];
                                                    $copyField = $destination['copy_field'] ?? null;
                                                    $copyValue = ($copyField && isset($rows[$copyField])) ? $rows[$copyField] : '';
                                                @endphp
                                                <div class="dest-card rounded-xl border border-blue-100 dark:border-slate-800 bg-[#f6f9ff] dark:bg-slate-800/80 p-4 {{ $loop->first ? 'active' : '' }}" data-dest="{{ $key }}">
                                                    <div class="flex items-center justify-between gap-3">
                                                        <div class="flex items-center gap-2.5">
                                                            <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-brand flex items-center justify-center">
                                                                <i class="fa-solid {{ $destination['icon'] ?? 'fa-money-bill-transfer' }}"></i>
                                                            </div>
                                                            <div>
                                                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $destination['title'] ?? 'ApexForge Labs' }}</p>
                                                                <h4 class="font-bold text-slate-800 dark:text-white text-xs">{{ $destination['label'] ?? 'Manual' }}</h4>
                                                            </div>
                                                        </div>
                                                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                                            <input type="radio" name="destination_source" value="{{ $key }}" class="w-4 h-4 accent-brand" @checked(old('destination_source') === $key || ($loop->first && !old('destination_source')))>
                                                            <span class="text-[10px] font-semibold text-slate-500 dark:text-slate-400">Pakai ini</span>
                                                        </label>
                                                    </div>
                                                    <div class="mt-3 grid grid-cols-1 sm:grid-cols-3 gap-2">
                                                        @foreach($rows as $label => $value)
                                                            <div class="bg-white dark:bg-slate-900 border border-blue-50 dark:border-slate-700 rounded-lg p-2.5 relative">
                                                                <p class="text-[9px] font-semibold text-slate-400 uppercase tracking-wider">{{ $label }}</p>
                                                                <p class="text-xs font-bold text-slate-800 dark:text-slate-100 mt-0.5 break-words pr-6">{{ $value }}</p>
                                                                @if($label === $copyField && $copyValue !== '')
                                                                    <button type="button" class="copy-btn absolute top-1.5 right-1.5 w-6 h-6 rounded-md bg-brand/10 text-brand hover:bg-brand hover:text-white transition flex items-center justify-center" data-copy="{{ $copyValue }}" title="Salin {{ $label }}">
                                                                        <i class="fa-regular fa-copy text-[10px]"></i>
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    @if(!empty($destination['instruction']))
                                                        <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-2 flex items-start gap-1.5">
                                                            <i class="fa-solid fa-circle-info mt-0.5"></i> {{ $destination['instruction'] }}
                                                        </p>
                                                    @endif
                                                </div>
                                            @endforeach
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5">Metode Pembayaran</label>
                                                    <select name="payment_method" required class="w-full px-4 py-2.5 bg-[#f6f9ff] border border-blue-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand/30">
                                                        <option value="">Pilih Metode</option>
                                                        <option value="Transfer Bank" @selected(old('payment_method') === 'Transfer Bank')>Transfer Bank</option>
                                                        <option value="QRIS" @selected(old('payment_method') === 'QRIS')>QRIS</option>
                                                        <option value="E-Wallet" @selected(old('payment_method') === 'E-Wallet')>E-Wallet</option>
                                                    </select>
                                                    @error('payment_method')<p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>@enderror
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5">Nama Pengirim</label>
                                                    <input type="text" name="sender_name" value="{{ old('sender_name') }}" maxlength="191" required placeholder="Nama sesuai rekening/wallet pengirim" class="w-full px-4 py-2.5 bg-[#f6f9ff] border border-blue-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand/30">
                                                    @error('sender_name')<p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>@enderror
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5">Bank/Wallet Pengirim</label>
                                                    <input type="text" name="sender_bank" value="{{ old('sender_bank') }}" maxlength="191" required placeholder="cth: BCA / DANA / OVO" class="w-full px-4 py-2.5 bg-[#f6f9ff] border border-blue-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand/30">
                                                    @error('sender_bank')<p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>@enderror
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5">Nomor Rekening/Wallet Pengirim (opsional)</label>
                                                    <input type="text" name="sender_account_number" value="{{ old('sender_account_number') }}" maxlength="191" placeholder="Nomor rekening/wallet Anda" class="w-full px-4 py-2.5 bg-[#f6f9ff] border border-blue-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand/30">
                                                    @error('sender_account_number')<p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>@enderror
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5">Tanggal Pembayaran</label>
                                                    <input type="date" name="payment_date" value="{{ old('payment_date', now()->toDateString()) }}" max="{{ now()->toDateString() }}" required class="w-full px-4 py-2.5 bg-[#f6f9ff] border border-blue-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand/30">
                                                    @error('payment_date')<p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>@enderror
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5">Jumlah yang Dibayar</label>
                                                    <div class="relative">
                                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">Rp</span>
                                                        <input type="number" name="paid_amount" value="{{ old('paid_amount', number_format((float) $payment->amount, 2, '.', '')) }}" step="0.01" min="0" required class="w-full pl-8 pr-4 py-2.5 bg-[#f6f9ff] border border-blue-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand/30">
                                                    </div>
                                                    <p class="text-[10px] text-slate-400 mt-1">Harus sesuai total tagihan (Rp {{ number_format($payment->amount, 0, ',', '.') }}). Nominal ditetapkan sistem dan tidak dapat diubah.</p>
                                                    @error('paid_amount')<p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>@enderror
                                                </div>
                                            </div>
<div>
                                                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5">Catatan Pembayaran (opsional)</label>
                                                <textarea name="company_note" rows="2" maxlength="2000" placeholder="cth: sudah transfer via m-banking BCA atas nama ..." class="w-full px-4 py-2.5 bg-[#f6f9ff] border border-blue-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 resize-none">{{ old('company_note') }}</textarea>
                                                @error('company_note')<p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>@enderror
                                            </div>

                                            <div>
                                                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5">Upload Bukti Pembayaran</label>
                                                <input type="file" name="payment_proof" required accept=".jpg,.jpeg,.png,.pdf" class="w-full px-4 py-2.5 bg-[#f6f9ff] border border-blue-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-semibold file:bg-brand file:text-white hover:file:bg-blue-700 transition">
                                                <p class="text-[10px] text-slate-400 mt-1">Format: jpg, jpeg, png, pdf. Maksimal 10 MB. Bukti pembayaran wajib diupload.</p>
                                                @error('payment_proof')<p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>@enderror
                                            </div>

                                            <div class="flex items-start gap-3 px-4 py-3 bg-blue-50 dark:bg-blue-950/40 border border-blue-100 dark:border-blue-800/60 rounded-xl text-xs text-slate-600 dark:text-slate-300">
                                                <i class="fa-solid fa-shield-halved mt-0.5 text-brand"></i>
                                                <span>Setelah dikirim, pembayaran berstatus <strong>Menunggu Verifikasi</strong>. Admin ApexForge Labs akan memverifikasi, lalu slot kuota tambahan aktif otomatis.</span>
                                            </div>

                                            <button type="submit"
                                                    class="w-full flex items-center justify-center gap-2 px-4 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl text-sm font-bold hover:from-blue-700 hover:to-indigo-700 transition shadow-lg shadow-brand/25 disabled:opacity-60 disabled:cursor-not-allowed">
                                                <i class="fa-solid fa-circle-check"></i> Saya Sudah Membayar — Kirim Bukti Pembayaran
                                            </button>
                                        </form>
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
                        <div class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                            <div class="px-5 py-4 border-b border-blue-50">
                                <h3 class="font-bold text-sm text-slate-800 dark:text-slate-100">Ringkasan Pembayaran</h3>
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

                                <div class="border-t border-blue-50 dark:border-slate-800 pt-4 space-y-2">
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

                        <div class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl shadow-sm p-5">
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

    @if ((bool) config('services.midtrans.temporary_confirmation', false) && in_array($payment->status, ['pending','rejected','waiting_verification'], true))
        <script>
            (function () {
                var btn = document.getElementById('manualQuotaConfirmBtn');
                if (!btn) return;
                btn.addEventListener('click', function () {
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memproses…';
                    fetch("{{ route('company.quota.payment.confirm', $payment) }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({})
                    }).then(function (r) { return r.json(); }).then(function (data) {
                        if (data.success && data.status === 'paid') {
                            window.location.href = "{{ route('company.projects.create') }}";
                            return;
                        }
                        alert((data && data.message) || 'Gagal mengonfirmasi pembayaran.');
                        btn.disabled = false;
                    }).catch(function () {
                        alert('Gagal menghubungi server.');
                        btn.disabled = false;
                    });
                });
            })();
        </script>
    @endif
    @if(in_array($payment->status, ['pending','rejected'], true))
        {{-- Salin nomor tujuan + highlight kartu tujuan yang dipilih --}}
        <script>
            (function () {
                var radios = document.querySelectorAll('input[name="destination_source"]');
                var cards = document.querySelectorAll('.dest-card');

                function highlight() {
                    var checked = document.querySelector('input[name="destination_source"]:checked');
                    cards.forEach(function (card) {
                        card.classList.toggle('active', !!checked && card.dataset.dest === checked.value);
                    });
                }

                radios.forEach(function (radio) {
                    radio.addEventListener('change', highlight);
                });
                highlight();

                function showCopied(btn) {
                    var icon = btn.querySelector('i');
                    var oldClass = icon.className;
                    icon.className = 'fa-solid fa-check text-[10px]';
                    btn.classList.add('bg-emerald-500', 'text-white');
                    setTimeout(function () {
                        icon.className = oldClass;
                        btn.classList.remove('bg-emerald-500', 'text-white');
                    }, 1500);
                }

                document.querySelectorAll('.copy-btn').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        var value = btn.getAttribute('data-copy');
                        function fallback() {
                            var ta = document.createElement('textarea');
                            ta.value = value;
                            ta.style.position = 'fixed';
                            ta.style.opacity = '0';
                            document.body.appendChild(ta);
                            ta.select();
                            try { document.execCommand('copy'); } catch (e) {}
                            document.body.removeChild(ta);
                        }
                        if (navigator.clipboard && typeof navigator.clipboard.writeText === 'function') {
                            navigator.clipboard.writeText(value).then(function () {
                                showCopied(btn);
                            }).catch(fallback);
                        } else {
                            fallback();
                            showCopied(btn);
                        }
                    });
                });
            })();
        </script>
    @endif
</body>

</html>