<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.theme-boot')
    <title>Bayar Manual - {{ $workspace->project->project_name }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif']
                    },
                    colors: {
                        brand: '#2563EB',
                        surface: '#F8FAFC'
                    }
                }
            }
        }
    </script>
    <style>
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
        .dest-card{cursor:pointer;transition:all .2s ease}
        .dest-card:hover{border-color:rgba(37,99,235,.45)!important;box-shadow:0 8px 22px -12px rgba(37,99,235,.35)}
        .dest-card.active{border-color:#2563eb!important;box-shadow:0 0 0 4px rgba(37,99,235,.12)}
        html.dark input,html.dark select,html.dark textarea{background:rgba(30,41,59,.6);color:#e2e8f0;border-color:rgba(100,116,139,.35)!important}
        html.dark .dest-card{background:rgba(30,41,59,.55)}
        @media (prefers-reduced-motion:reduce){
            *,*::before,*::after{animation-duration:.01ms!important;animation-iteration-count:1!important;transition-duration:.01ms!important;scroll-behavior:auto!important}
        }
    </style>
</head>

<body class="bg-surface dark:bg-slate-950 text-slate-800 dark:text-slate-100 min-h-screen flex font-sans transition-colors duration-300">

    @include('navbar.navigasi')

    <div class="flex-1 min-w-0 flex flex-col min-h-screen">
        @include('navbar.nav')

        <main class="flex-1 min-w-0 overflow-y-auto">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 py-8">

                {{-- Breadcrumb --}}
                <div class="flex items-center gap-2 text-sm text-slate-400 mb-6 overflow-x-auto whitespace-nowrap">
                    <a href="{{ route('company.workspaces.index') }}" class="hover:text-brand transition">Workspace</a>
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    <a href="{{ route('company.workspaces.show', $workspace) }}" class="hover:text-brand transition">{{ $workspace->project->project_name }}</a>
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    <span class="text-slate-600 dark:text-slate-300 font-medium">Bayar Manual</span>
                </div>

                {{-- Header --}}
                <div class="mb-6">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white">Pembayaran Manual</h1>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">Lakukan transfer ke rekening/wallet ApexForge Labs, lalu kirim bukti pembayaran untuk diverifikasi Admin.</p>
                </div>

                {{-- Alerts --}}
                @if (session('success'))
                    <div class="mb-6 flex items-center gap-3 px-4 py-3 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 text-emerald-700 dark:text-emerald-300 rounded-xl text-sm font-medium">
                        <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 flex items-center gap-3 px-4 py-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800/60 text-red-700 dark:text-red-300 rounded-xl text-sm font-medium">
                        <i class="fa-solid fa-xmark-circle"></i> {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 flex items-start gap-3 px-4 py-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800/60 text-red-700 dark:text-red-300 rounded-xl text-sm">
                        <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

{{-- Stepper --}}
                <div class="flex items-center mb-8">
                    <div class="flex flex-col items-center">
                        <div class="w-9 h-9 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-500/30">
                            <i class="fa-solid fa-check text-xs"></i>
                        </div>
                        <span class="text-[10px] font-bold text-emerald-600 mt-1.5">Payment</span>
                    </div>
                    <div class="h-0.5 bg-emerald-400 flex-1"></div>
                    <div class="flex flex-col items-center">
                        <div class="w-9 h-9 rounded-full bg-brand text-white flex items-center justify-center shadow-lg shadow-brand/30">
                            <i class="fa-solid fa-money-bill-transfer text-xs"></i>
                        </div>
                        <span class="text-[10px] font-bold text-brand mt-1.5">Bayar Manual</span>
                    </div>
                    <div class="h-0.5 bg-slate-200 flex-1"></div>
                    <div class="flex flex-col items-center">
                        <div class="w-9 h-9 rounded-full bg-white text-slate-400 border border-blue-100 dark:bg-slate-900 dark:border-slate-700 flex items-center justify-center">
                            <i class="fa-solid fa-shield-halved text-xs"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-slate-400 mt-1.5">Admin Verification</span>
                    </div>
                    <div class="h-0.5 bg-slate-200 flex-1"></div>
                    <div class="flex flex-col items-center">
                        <div class="w-9 h-9 rounded-full bg-white text-slate-400 border border-blue-100 dark:bg-slate-900 dark:border-slate-700 flex items-center justify-center">
                            <i class="fa-solid fa-check text-xs"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-slate-400 mt-1.5">Completed</span>
                    </div>
                </div>

                {{-- Ringkasan Pembayaran --}}
                <div class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-blue-50 dark:border-slate-800 flex items-center justify-between">
                        <div>
                            <h2 class="font-bold text-base text-slate-800 dark:text-white">Ringkasan Pembayaran</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Detail tagihan proyek Anda</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-brand/10 text-brand flex items-center justify-center">
                            <i class="fa-solid fa-receipt"></i>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                            <div>
                                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Nama Proyek</p>
                                <p class="font-bold text-slate-800 dark:text-slate-100 mt-0.5">{{ $workspace->project->project_name }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Nama Freelancer</p>
                                <p class="font-semibold text-slate-700 dark:text-slate-200 mt-0.5">{{ $workspace->freelancer->name ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Referensi / No. Invoice</p>
                                <p class="font-bold text-slate-800 dark:text-slate-100 mt-0.5">{{ $payment->invoice_number }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Metode Pembayaran</p>
                                <span class="inline-flex items-center gap-1.5 mt-1 px-2.5 py-0.5 rounded-full bg-blue-50 dark:bg-blue-950/60 text-brand border border-blue-100 dark:border-blue-800/60 text-[10px] font-bold">
                                    <i class="fa-solid fa-hand-holding-dollar text-[9px]"></i> Manual</span>
                            </div>
                            <div class="md:col-span-2 flex items-center justify-between pt-4 border-t border-blue-100 dark:border-slate-800">
                                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Total Pembayaran</p>
                                <span class="text-xl font-extrabold text-emerald-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

{{-- Info jika ditolak --}}
                @if($payment->status === 'rejected')
                    <div class="flex items-start gap-3 px-4 py-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800/60 rounded-xl text-sm text-red-700 dark:text-red-300 mt-4">
                        <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                        <div>
                            <p class="text-xs font-semibold">Pembayaran sebelumnya ditolak. Silakan upload ulang.</p>
                            @if($payment->admin_note)
                                <p class="text-[10px] text-red-600 dark:text-red-400 mt-0.5">Alasan: {{ $payment->admin_note }}</p>
                            @endif
                        </div>
                    </div>
                @endif

{{-- Tujuan Pembayaran --}}
                <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100 mt-8 mb-1">
                    <i class="fa-solid fa-building-columns text-brand mr-1"></i> Rekening / Wallet Tujuan Pembayaran</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">Transfer ke rekening/wallet milik ApexForge Labs di bawah ini, lalu pilih tujuan yang Anda gunakan.</p>

                @php $firstDest = true; @endphp
                @foreach($destinations as $key => $destination)
                    @php
                        $rows = $destination['rows'] ?? [];
                        $copyField = $destination['copy_field'] ?? null;
                        $copyValue = ($copyField && isset($rows[$copyField])) ? $rows[$copyField] : '';
                    @endphp
                    <div class="dest-card rounded-2xl border border-blue-100 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 mb-4 {{ $firstDest ? 'active' : '' }}" data-dest="{{ $key }}">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-brand flex items-center justify-center">
                                    <i class="fa-solid {{ $destination['icon'] ?? 'fa-money-bill-transfer' }} text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $destination['title'] ?? 'ApexForge Labs' }}</p>
                                    <h3 class="font-bold text-slate-800 dark:text-white text-sm">{{ $destination['label'] ?? 'Manual' }}</h3>
                                </div>
                            </div>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                {{-- form="manualPaymentForm" menghubungkan radio ini ke form konfirmasi
                                     meskipun secara DOM berada di luar <form> (atribut HTML5). --}}
                                <input type="radio" name="destination_source" form="manualPaymentForm" value="{{ $key }}" class="w-4 h-4 accent-brand" @checked(old('destination_source') === $key || ($firstDest && !old('destination_source')))>
                                <span class="text-[10px] font-semibold text-slate-500 dark:text-slate-400">Saya transfer ke sini</span>
                            </label>
                        </div>
                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-3">
                            @foreach($rows as $label => $value)
                                <div class="bg-[#f6f9ff] dark:bg-slate-800/80 border border-blue-50 dark:border-slate-700 rounded-xl p-3 relative">
                                    <p class="text-[9px] font-semibold text-slate-400 uppercase tracking-wider">{{ $label }}</p>
                                    <p class="text-sm font-bold text-slate-800 dark:text-slate-100 mt-1 break-words pr-7">{{ $value }}</p>
                                    @if($label === $copyField && $copyValue !== '')
                                        <button type="button" class="copy-btn absolute top-2 right-2 w-7 h-7 rounded-lg bg-brand/10 text-brand hover:bg-brand hover:text-white transition flex items-center justify-center" data-copy="{{ $copyValue }}" title="Salin {{ $label }}">
                                            <i class="fa-regular fa-copy text-xs"></i>
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        @if(!empty($destination['instruction']))
                            <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-3 flex items-start gap-1.5">
                                <i class="fa-solid fa-circle-info mt-0.5"></i> {{ $destination['instruction'] }}
                            </p>
                        @endif
                    </div>
                    @php $firstDest = false; @endphp
                @endforeach

{{-- Form Konfirmasi Pembayaran --}}
                <div class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden mt-8">
                    <div class="px-6 py-5 border-b border-blue-50 dark:border-slate-800 flex items-center justify-between">
                        <div>
                            <h2 class="font-bold text-base text-slate-800 dark:text-white">Konfirmasi Pembayaran</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Isi data transfer Anda, lalu kirim bukti pembayaran</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 flex items-center justify-center">
                            <i class="fa-solid fa-money-check-pen"></i>
                        </div>
                    </div>
                    <div class="p-6">
                        <form method="POST" action="{{ route('company.payments.upload', $workspace) }}" enctype="multipart/form-data" id="manualPaymentForm" class="space-y-5">
                            @csrf

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5">Metode Pembayaran</label>
                                    <select name="payment_method" required class="w-full px-4 py-2.5 bg-[#f6f9ff] border border-blue-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand/30">
                                        <option value="">Pilih Metode</option>
                                        <option value="Transfer Bank" {{ old('payment_method') === 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                                        <option value="QRIS" {{ old('payment_method') === 'QRIS' ? 'selected' : '' }}>QRIS</option>
                                        <option value="E-Wallet" {{ old('payment_method') === 'E-Wallet' ? 'selected' : '' }}>E-Wallet</option>
                                    </select>
                                    @error('payment_method')
                                        <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5">Nama Pengirim</label>
                                    <input type="text" name="sender_name" value="{{ old('sender_name') }}" maxlength="191" required placeholder="Nama sesuai rekening/wallet pengirim" class="w-full px-4 py-2.5 bg-[#f6f9ff] border border-blue-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand/30">
                                    @error('sender_name')
                                        <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5">Bank/Wallet Pengirim</label>
                                    <input type="text" name="sender_bank" value="{{ old('sender_bank') }}" maxlength="191" required placeholder="cth: BCA / DANA / OVO" class="w-full px-4 py-2.5 bg-[#f6f9ff] border border-blue-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand/30">
                                    @error('sender_bank')
                                        <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

<div>
                                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5">Nomor Rekening/Wallet Pengirim (opsional)</label>
                                    <input type="text" name="sender_account_number" value="{{ old('sender_account_number') }}" maxlength="191" placeholder="Nomor rekening/wallet Anda" class="w-full px-4 py-2.5 bg-[#f6f9ff] border border-blue-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand/30">
                                    @error('sender_account_number')
                                        <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5">Tanggal Pembayaran</label>
                                    <input type="date" name="payment_date" value="{{ old('payment_date', now()->toDateString()) }}" max="{{ now()->toDateString() }}" required class="w-full px-4 py-2.5 bg-[#f6f9ff] border border-blue-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand/30">
                                    @error('payment_date')
                                        <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5">Jumlah yang Dibayar</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">Rp</span>
                                        <input type="number" name="paid_amount" value="{{ old('paid_amount', number_format((float) $payment->amount, 2, '.', '')) }}" step="0.01" min="0" required class="w-full pl-8 pr-4 py-2.5 bg-[#f6f9ff] border border-blue-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand/30">
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-1">Harus sesuai total tagihan(Rp {{ number_format($payment->amount, 0, ',', '.') }}). Nominal ditetapkan sistem dan tidak dapat diubah.</p>
                                    @error('paid_amount')
                                        <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

<div>
                            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5">Catatan Pembayaran (opsional)</label>
                            <textarea name="company_note" rows="2" maxlength="2000" placeholder="cth: sudah transfer via m-banking BCA atas nama ..." class="w-full px-4 py-2.5 bg-[#f6f9ff] border border-blue-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 resize-none">{{ old('company_note') }}</textarea>
                            @error('company_note')
                                <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5">Upload Bukti Pembayaran</label>
                            <input type="file" name="payment_proof" required accept=".jpg,.jpeg,.png,.pdf" class="w-full px-4 py-2.5 bg-[#f6f9ff] border border-blue-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-semibold file:bg-brand file:text-white hover:file:bg-blue-700 transition">
                            <p class="text-[10px] text-slate-400 mt-1">Format: jpg, jpeg, png, pdf. Maksimal 10 MB. Bukti pembayaran wajib diupload.</p>
                            @error('payment_proof')
                                <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

<div class="flex items-start gap-3 px-4 py-3 bg-blue-50 dark:bg-blue-950/40 border border-blue-100 dark:border-blue-800/60 rounded-xl text-xs text-slate-600 dark:text-slate-300">
                                <i class="fa-solid fa-shield-halved mt-0.5 text-brand"></i>
                                <span>Setelah dikirim, pembayaran berstatus <strong>Menunggu Verifikasi</strong>. Admin ApexForge Labs akan memverifikasi bukti Anda, lalu Workspace otomatis terbuka.</span>
                            </div>

                            <button type="submit"
                                    class="w-full flex items-center justify-center gap-2 px-4 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl text-sm font-bold hover:from-blue-700 hover:to-indigo-700 transition shadow-lg shadow-brand/25 disabled:opacity-60 disabled:cursor-not-allowed">
                                <i class="fa-solid fa-circle-check"></i> Saya Sudah Membayar — Kirim Bukti Pembayaran
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

<script>
        (function () {
            // Highlight kartu tujuan sesuai radio yang dipilih.
            // Nilai destination_source disubmit via atribut form="manualPaymentForm"
            // pada radio (native HTML5), sehingga tidak butuh hidden input.
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
                icon.className = 'fa-solid fa-check text-xs';
                btn.classList.add('bg-emerald-500', 'text-white');
                setTimeout(function () {
                    icon.className = oldClass;
                    btn.classList.remove('bg-emerald-500', 'text-white');
                }, 1500);
            }

            // Tombol Salin nomor rekening / wallet
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
                        var promise = navigator.clipboard.writeText(value);
                        promise.then(function () {
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
</body>

</html>