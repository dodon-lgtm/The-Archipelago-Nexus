<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Gateway - {{ $workspace->project->project_name }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif']
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
        @keyframes fadeInBackdrop {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes modalPop {
            from { opacity: 0; transform: scale(.92) translateY(12px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        @keyframes spinner {
            to { transform: rotate(360deg); }
        }
        @keyframes checkDraw {
            0% { stroke-dashoffset: 100; }
            100% { stroke-dashoffset: 0; }
        }
        .modal-backdrop { animation: fadeInBackdrop .25s ease-out; }
        .modal-panel { animation: modalPop .35s cubic-bezier(.34, 1.56, .64, 1); }
        .spinner {
            width: 42px;
            height: 42px;
            border: 4px solid rgba(37, 99, 235, .15);
            border-top-color: #2563EB;
            border-radius: 50%;
            animation: spinner .8s linear infinite;
        }
        .checkmark-svg {
            width: 96px;
            height: 96px;
        }
        .checkmark-circle {
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            stroke-width: 3;
            stroke-linecap: round;
            fill: none;
            animation: checkDraw .6s cubic-bezier(.65, 0, .45, 1) forwards;
        }
        .checkmark-check {
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            stroke-width: 4;
            stroke-linecap: round;
            stroke-linejoin: round;
            fill: none;
            animation: checkDraw .35s .5s cubic-bezier(.65, 0, .45, 1) forwards;
        }

        .card-preview {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563EB 55%, #3b82f6 100%);
            border-radius: 20px;
            padding: 24px;
            color: white;
            min-height: 220px;
            box-shadow: 0 20px 40px -12px rgba(37, 99, 235, .45);
            position: relative;
            overflow: hidden;
        }
        .card-preview::before {
            content: '';
            position: absolute;
            top: -40px;
            right: -40px;
            width: 180px;
            height: 180px;
            background: rgba(255, 255, 255, .08);
            border-radius: 50%;
        }
        .card-preview::after {
            content: '';
            position: absolute;
            bottom: -60px;
            left: -30px;
            width: 160px;
            height: 160px;
            background: rgba(255, 255, 255, .06);
            border-radius: 50%;
        }
        .card-chip {
            width: 44px;
            height: 32px;
            background: linear-gradient(135deg, #fcd34d, #f59e0b);
            border-radius: 6px;
            position: relative;
        }
        .card-chip::after {
            content: '';
            position: absolute;
            inset: 6px;
            border: 1px solid rgba(180, 130, 20, .6);
            border-radius: 4px;
        }

        .field-shell:focus-within {
            box-shadow: 0 0 0 4px rgba(37, 99, 235, .12);
        }

        .method-card {
            transition: all .2s ease;
        }
        .method-card.active {
            border-color: #2563EB;
            background: #eff6ff;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .12);
        }
        .method-card.disabled {
            opacity: .55;
            cursor: not-allowed;
        }

        .stepper-dot {
            width: 34px;
            height: 34px;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            transition: all .3s ease;
        }
        .stepper-line {
            height: 2px;
            flex: 1;
            transition: all .3s ease;
        }
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
                    <a href="{{ route('company.workspaces.index') }}" class="hover:text-brand transition">Workspace</a>
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    <a href="{{ route('company.workspaces.show', $workspace) }}" class="hover:text-brand transition">{{ $workspace->project->project_name }}</a>
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    <span class="text-slate-600 font-medium">Payment Gateway</span>
                </div>

                {{-- Stepper --}}
                <div class="flex items-center mb-8">
                    <div class="flex flex-col items-center">
                        <div class="stepper-dot bg-brand text-white shadow-lg shadow-brand/30">
                            <i class="fa-solid fa-credit-card text-xs"></i>
                        </div>
                        <span class="text-[10px] font-bold text-brand mt-1.5">Payment</span>
                    </div>
                    <div class="stepper-line bg-brand/30"></div>
                    <div class="flex flex-col items-center">
                        <div class="stepper-dot bg-white text-slate-400 border border-slate-200">
                            <i class="fa-solid fa-upload text-xs"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-slate-400 mt-1.5">Upload Proof</span>
                    </div>
                    <div class="stepper-line bg-slate-200"></div>
                    <div class="flex flex-col items-center">
                        <div class="stepper-dot bg-white text-slate-400 border border-slate-200">
                            <i class="fa-solid fa-shield-halved text-xs"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-slate-400 mt-1.5">Admin Verification</span>
                    </div>
                    <div class="stepper-line bg-slate-200"></div>
                    <div class="flex flex-col items-center">
                        <div class="stepper-dot bg-white text-slate-400 border border-slate-200">
                            <i class="fa-solid fa-check text-xs"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-slate-400 mt-1.5">Completed</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-5 gap-6">

                    {{-- LEFT: PAYMENT METHODS + CARD FORM --}}
                    <div class="md:col-span-3 space-y-6">

                        {{-- Header --}}
                        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                                <div>
                                    <h2 class="font-bold text-base text-slate-800">Payment Gateway</h2>
                                    <p class="text-xs text-slate-400 mt-0.5">Mode Simulasi &middot; Pembayaran Aman</p>
                                </div>
                                <div class="w-10 h-10 rounded-xl bg-blue-50 text-brand flex items-center justify-center">
                                    <i class="fa-solid fa-lock"></i>
                                </div>
                            </div>

                            <div class="p-6 space-y-5">
                                {{-- Payment Methods --}}
                                <div>
                                    <p class="text-xs font-semibold text-slate-600 mb-2.5">Metode Pembayaran</p>
                                    <div class="space-y-2.5">
                                        {{-- Card (active) --}}
                                        <div class="method-card active flex items-center gap-3 px-4 py-3 border border-slate-200 rounded-xl cursor-pointer">
                                            <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-600 to-indigo-500 text-white flex items-center justify-center">
                                                <i class="fa-solid fa-credit-card text-sm"></i>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-bold text-slate-700">Debit / Credit Card</p>
                                                <p class="text-[10px] text-slate-400">Visa, Mastercard, JCB</p>
                                            </div>
                                            <span class="text-[9px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700">AKTIF</span>
                                        </div>

                                        {{-- Virtual Account (coming soon) --}}
                                        <div class="method-card disabled flex items-center gap-3 px-4 py-3 border border-slate-200 rounded-xl">
                                            <div class="w-9 h-9 rounded-lg bg-slate-100 text-slate-400 flex items-center justify-center">
                                                <i class="fa-solid fa-building-columns text-sm"></i>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-bold text-slate-500">Virtual Account</p>
                                                <p class="text-[10px] text-slate-400">BCA, Mandiri, BRI, BNI</p>
                                            </div>
                                            <span class="text-[9px] font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">Coming Soon</span>
                                        </div>

                                        {{-- QRIS (coming soon) --}}
                                        <div class="method-card disabled flex items-center gap-3 px-4 py-3 border border-slate-200 rounded-xl">
                                            <div class="w-9 h-9 rounded-lg bg-slate-100 text-slate-400 flex items-center justify-center">
                                                <i class="fa-solid fa-qrcode text-sm"></i>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-bold text-slate-500">QRIS</p>
                                                <p class="text-[10px] text-slate-400">Scan &amp; bayar</p>
                                            </div>
                                            <span class="text-[9px] font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">Coming Soon</span>
                                        </div>

                                        {{-- E-Wallet (coming soon) --}}
                                        <div class="method-card disabled flex items-center gap-3 px-4 py-3 border border-slate-200 rounded-xl">
                                            <div class="w-9 h-9 rounded-lg bg-slate-100 text-slate-400 flex items-center justify-center">
                                                <i class="fa-solid fa-wallet text-sm"></i>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-bold text-slate-500">E-Wallet</p>
                                                <p class="text-[10px] text-slate-400">GoPay, OVO, DANA, ShopeePay</p>
                                            </div>
                                            <span class="text-[9px] font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">Coming Soon</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Card Form --}}
                                <div class="border-t border-slate-100 pt-5">
                                    <p class="text-xs font-semibold text-slate-600 mb-4">Data Kartu</p>

                                    <form id="cardForm" class="space-y-4" onsubmit="return handlePay(event)">
                                        @csrf

                                        <div>
                                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Card Number</label>
                                            <div class="field-shell rounded-xl transition">
                                                <input type="text" id="cardNumber" name="card_number"
                                                    placeholder="1234 5678 9012 3456" maxlength="19"
                                                    inputmode="numeric"
                                                    oninput="formatCardNumber(this); updatePreview();"
                                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-brand transition tracking-wider">
                                            </div>
                                            <p class="text-[10px] text-red-500 mt-1 hidden" id="cardNumberError">Nomor kartu tidak valid (minimal 13 digit).</p>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Card Holder Name</label>
                                            <div class="field-shell rounded-xl transition">
                                                <input type="text" id="cardHolder" name="card_holder"
                                                    placeholder="NAMA LENGKAP SESUAI KARTU"
                                                    oninput="updatePreview()"
                                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-brand transition uppercase">
                                            </div>
                                            <p class="text-[10px] text-red-500 mt-1 hidden" id="cardHolderError">Nama pemegang kartu wajib diisi.</p>
                                        </div>

                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Expiry Date</label>
                                                <div class="field-shell rounded-xl transition">
                                                    <input type="text" id="cardExpiry" placeholder="MM/YY"
                                                        maxlength="5" inputmode="numeric"
                                                        oninput="formatExpiry(this); updatePreview();"
                                                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-brand transition">
                                                </div>
                                                <p class="text-[10px] text-red-500 mt-1 hidden" id="cardExpiryError">Expiry tidak boleh kosong (MM/YY).</p>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">CVV</label>
                                                <div class="field-shell rounded-xl transition">
                                                    <input type="password" id="cardCvv" placeholder="•••"
                                                        maxlength="3" inputmode="numeric"
                                                        oninput="this.value=this.value.replace(/[^0-9]/g,''); updatePreview();"
                                                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-brand transition tracking-widest">
                                                </div>
                                                <p class="text-[10px] text-red-500 mt-1 hidden" id="cardCvvError">CVV harus 3 digit.</p>
                                            </div>
                                        </div>

                                        <div class="pt-2">
                                            <button type="submit" id="payButton"
                                                class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-brand text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition shadow-lg shadow-brand/20">
                                                <i class="fa-solid fa-lock text-xs"></i>
                                                Bayar Sekarang
                                            </button>
                                        </div>

                                        {{-- Mode Simulasi note --}}
                                        <p class="text-[10px] text-slate-400 text-center leading-relaxed">
                                            <i class="fa-solid fa-circle-info mr-1"></i>
                                            <strong>Mode Simulasi</strong> &mdash; Pembayaran ini digunakan untuk demonstrasi aplikasi.
                                            Integrasi Midtrans, QRIS, Virtual Account, dan E-Wallet akan tersedia pada versi berikutnya.
                                        </p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT: INVOICE SUMMARY + CARD PREVIEW --}}
                    <div class="md:col-span-2 space-y-6">

                        {{-- Card Preview --}}
                        <div class="card-preview">
                            <div class="relative z-10 flex items-start justify-between">
                                <div class="card-chip"></div>
                                <div class="flex items-center gap-1">
                                    <span class="text-lg font-extrabold italic tracking-tight">VIS</span>
                                    <span class="text-lg font-extrabold italic tracking-tight" style="color:#fbbf24">A</span>
                                </div>
                            </div>
                            <div class="relative z-10 mt-6">
                                <p class="text-[10px] text-white/60 tracking-widest uppercase">Card Number</p>
                                <p id="previewNumber" class="text-lg font-semibold tracking-widest mt-0.5">•••• •••• •••• ••••</p>
                            </div>
                            <div class="relative z-10 mt-5 flex items-end justify-between">
                                <div>
                                    <p class="text-[9px] text-white/60 tracking-widest uppercase">Card Holder</p>
                                    <p id="previewHolder" class="text-sm font-semibold mt-0.5 uppercase truncate max-w-[160px]">YOUR NAME</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[9px] text-white/60 tracking-widest uppercase">Expires</p>
                                    <p id="previewExpiry" class="text-sm font-semibold mt-0.5">MM/YY</p>
                                </div>
                            </div>
                        </div>

                        {{-- Invoice Summary --}}
                        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                            <div class="px-5 py-4 border-b border-slate-100">
                                <h3 class="font-bold text-sm text-slate-800">Ringkasan Pembayaran</h3>
                            </div>
                            <div class="p-5 space-y-4">
                                <div class="flex items-center justify-between">
                                    <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Nomor Invoice</p>
                                    <span class="text-xs font-bold text-slate-700">{{ $payment->invoice_number }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Nama Project</p>
                                    <span class="text-xs font-bold text-slate-700 text-right max-w-[150px]">{{ $workspace->project->project_name }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Nama Freelancer</p>
                                    <span class="text-xs font-bold text-slate-700">{{ $workspace->freelancer->name }}</span>
                                </div>

                                <div class="border-t border-slate-100 pt-4 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Total Pembayaran</p>
                                        <span class="text-sm font-bold text-slate-800">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Biaya Platform (5%)</p>
                                        <span class="text-xs font-semibold text-slate-600">Rp {{ number_format($payment->platform_fee, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex items-center justify-between pt-2 border-t border-slate-200">
                                        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Total Dibayar</p>
                                        <span class="text-lg font-extrabold text-emerald-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between bg-slate-50 rounded-xl px-4 py-3">
                                    <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Status Pembayaran</p>
                                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-amber-50 text-amber-600 border border-amber-200">
                                        Belum Dibayar
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        @include('navbar.footer')
    </div>

    {{-- MODAL LOADING --}}
    <div id="loadingModal" class="hidden modal-backdrop fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
        <div class="modal-panel bg-white rounded-3xl shadow-2xl w-full max-w-xs p-8 text-center">
            <div class="flex justify-center mb-4">
                <div class="spinner"></div>
            </div>
            <h3 class="font-bold text-slate-800 text-sm">Memproses Pembayaran</h3>
            <p class="text-xs text-slate-400 mt-1">Harap tunggu sebentar...</p>
        </div>
    </div>

    {{-- MODAL SUCCESS --}}
    <div id="successModal" class="hidden modal-backdrop fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
        <div class="modal-panel bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden">
            <div class="px-8 pt-8 pb-6 text-center">
                <div class="flex justify-center mb-4">
                    <svg class="checkmark-svg" viewBox="0 0 52 52">
                        <circle class="checkmark-circle" cx="26" cy="26" r="24" fill="none" stroke="#10b981"/>
                        <path class="checkmark-check" fill="none" stroke="#10b981" stroke-linecap="round" stroke-linejoin="round" d="M14 27l8 8 16-16"/>
                    </svg>
                </div>
                <h3 class="font-bold text-lg text-slate-800">Pembayaran Berhasil</h3>
                <p class="text-xs text-slate-400 mt-1">Pembayaran Anda telah diproses (Simulasi).</p>
                <div class="mt-4 bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3">
                    <p class="text-[10px] text-slate-400 uppercase tracking-wider">Nominal Dibayar</p>
                    <p class="text-lg font-extrabold text-emerald-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="px-8 pb-8">
                <a href="{{ route('company.payments.upload-form', $workspace) }}"
                    class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-brand text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
                    <i class="fa-solid fa-upload"></i>
                    Lanjut Upload Bukti
                </a>
            </div>
        </div>
    </div>

    <script>
        // Format card number: group of 4, digits only
        function formatCardNumber(input) {
            let digits = input.value.replace(/\D/g, '').slice(0, 16);
            input.value = digits.replace(/(.{4})/g, '$1 ').trim();
        }

        // Format expiry MM/YY
        function formatExpiry(input) {
            let digits = input.value.replace(/\D/g, '').slice(0, 4);
            if (digits.length >= 3) {
                input.value = digits.slice(0, 2) + '/' + digits.slice(2);
            } else {
                input.value = digits;
            }
        }

        // Real-time preview update
        function updatePreview() {
            const number = document.getElementById('cardNumber').value.replace(/\s/g, '');
            const holder = document.getElementById('cardHolder').value;
            const expiry = document.getElementById('cardExpiry').value;

            document.getElementById('previewNumber').textContent =
                number ? number.replace(/(.{4})/g, '$1 ').trim() : '•••• •••• •••• ••••';
            document.getElementById('previewHolder').textContent =
                holder ? holder.toUpperCase() : 'YOUR NAME';
            document.getElementById('previewExpiry').textContent =
                expiry ? expiry : 'MM/YY';
        }

        // Frontend validation
        function validate() {
            let valid = true;

            const number = document.getElementById('cardNumber').value.replace(/\s/g, '');
            const numberErr = document.getElementById('cardNumberError');
            if (number.length < 13) {
                numberErr.classList.remove('hidden');
                valid = false;
            } else {
                numberErr.classList.add('hidden');
            }

            const holder = document.getElementById('cardHolder').value.trim();
            const holderErr = document.getElementById('cardHolderError');
            if (!holder) {
                holderErr.classList.remove('hidden');
                valid = false;
            } else {
                holderErr.classList.add('hidden');
            }

            const expiry = document.getElementById('cardExpiry').value.trim();
            const expiryErr = document.getElementById('cardExpiryError');
            if (!expiry) {
                expiryErr.classList.remove('hidden');
                valid = false;
            } else {
                expiryErr.classList.add('hidden');
            }

            const cvv = document.getElementById('cardCvv').value;
            const cvvErr = document.getElementById('cardCvvError');
            if (cvv.length !== 3) {
                cvvErr.classList.remove('hidden');
                valid = false;
            } else {
                cvvErr.classList.add('hidden');
            }

            return valid;
        }

        // Handle pay: loading -> success modal
        function handlePay(event) {
            event.preventDefault();
            if (!validate()) {
                return false;
            }

            document.getElementById('payButton').disabled = true;
            document.getElementById('loadingModal').classList.remove('hidden');

            // Simulate 2.5s processing
            setTimeout(function() {
                document.getElementById('loadingModal').classList.add('hidden');
                document.getElementById('successModal').classList.remove('hidden');
                document.getElementById('payButton').disabled = false;
            }, 2500);

            return false;
        }
    </script>

</body>

</html>
