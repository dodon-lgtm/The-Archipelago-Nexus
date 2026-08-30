<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.theme-boot')
    <title>Payment Gateway - {{ $workspace->project->project_name }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
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

<body class="bg-surface dark:bg-slate-950 text-slate-800 dark:text-slate-100 min-h-screen flex font-sans transition-colors duration-300">

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
                        <div class="stepper-dot bg-white dark:bg-slate-900 text-slate-400 border border-blue-100 dark:border-slate-800">
                            <i class="fa-solid fa-upload text-xs"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-slate-400 mt-1.5">Upload Proof</span>
                    </div>
                    <div class="stepper-line bg-slate-200"></div>
                    <div class="flex flex-col items-center">
                        <div class="stepper-dot bg-white dark:bg-slate-900 text-slate-400 border border-blue-100 dark:border-slate-800">
                            <i class="fa-solid fa-shield-halved text-xs"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-slate-400 mt-1.5">Admin Verification</span>
                    </div>
                    <div class="stepper-line bg-slate-200"></div>
                    <div class="flex flex-col items-center">
                        <div class="stepper-dot bg-white dark:bg-slate-900 text-slate-400 border border-blue-100 dark:border-slate-800">
                            <i class="fa-solid fa-check text-xs"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-slate-400 mt-1.5">Completed</span>
                    </div>
                </div>

                {{-- Alert Messages --}}
                @if (session('success'))
                    <div class="mb-6 flex items-center gap-3 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium">
                        <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 flex items-center gap-3 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm font-medium">
                        <i class="fa-solid fa-xmark-circle"></i> {{ session('error') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-5 gap-6">

                    {{-- LEFT: PAYMENT METHOD SELECTION --}}
                    <div class="md:col-span-3 space-y-6">

                        {{-- Header --}}
                        <div class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                            <div class="px-6 py-5 border-b border-blue-50 flex items-center justify-between">
                                <div>
                                    <h2 class="font-bold text-base text-slate-800 dark:text-slate-100">Payment Gateway</h2>
                                    <p class="text-xs text-slate-400 mt-0.5">
                                        @if (in_array($payment->status, ['pending', 'rejected'], true))
                                            Pilih metode pembayaran untuk melanjutkan
                                        @elseif ($payment->status === 'waiting_verification')
                                            Bukti pembayaran sedang diverifikasi admin
                                        @elseif ($payment->status === 'paid')
                                            Pembayaran telah selesai
                                        @else
                                            Status pembayaran: {{ $payment->status_label }}
                                        @endif
                                    </p>
                                </div>
                                <div class="w-10 h-10 rounded-xl bg-blue-50 text-brand flex items-center justify-center">
                                    <i class="fa-solid fa-lock"></i>
                                </div>
                            </div>

                            <div class="p-6 space-y-5">
                                @if (in_array($payment->status, ['pending', 'rejected'], true))
                                    {{-- Payment Methods --}}
                                    <div>
                                        <p class="text-xs font-semibold text-slate-600 mb-2.5">Metode Pembayaran</p>

                                        @if ($payment->status === 'rejected')
                                            <div class="mb-4 flex items-center gap-3 px-4 py-3 bg-red-50 border border-red-200 text-red-600 rounded-xl text-xs font-medium">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                Pembayaran sebelumnya gagal/dibatalkan. Silakan coba bayar kembali.
                                            </div>
                                        @endif

                                        {{-- Include options partial --}}
                                        @include('company.payments.options', ['workspace' => $workspace, 'payment' => $payment])
                                    </div>
                                @elseif ($payment->status === 'waiting_verification')
                                    <div class="flex items-start gap-4 px-5 py-4 bg-amber-50 border border-amber-200 rounded-2xl">
                                        <div class="w-10 h-10 shrink-0 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
                                            <i class="fa-solid fa-hourglass-half"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-amber-700">Menunggu Verifikasi Admin</p>
                                            <p class="text-xs text-amber-600/90 mt-1 leading-relaxed">
                                                Bukti pembayaran Anda telah diterima dan sedang diperiksa oleh admin.
                                                Anda akan menerima notifikasi setelah verifikasi selesai.
                                            </p>
                                        </div>
                                    </div>
                                    <a href="{{ route('company.workspaces.show', $workspace) }}"
                                       class="inline-flex items-center gap-2 text-xs font-semibold text-brand hover:underline">
                                        <i class="fa-solid fa-arrow-left text-[10px]"></i> Kembali ke Workspace
                                    </a>
                                @elseif ($payment->status === 'paid')
                                    <div class="flex items-start gap-4 px-5 py-4 bg-emerald-50 border border-emerald-200 rounded-2xl">
                                        <div class="w-10 h-10 shrink-0 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                            <i class="fa-solid fa-check"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-emerald-700">Pembayaran Berhasil</p>
                                            <p class="text-xs text-emerald-600/90 mt-1 leading-relaxed">
                                                Dana tertahan di escrow platform dan akan dirilis ke freelancer
                                                sesuai kesepakatan pekerjaan.
                                            </p>
                                            @if($payment->verified_at)
                                                <p class="text-[11px] text-emerald-500 mt-1">
                                                    Diverifikasi: {{ $payment->verified_at->translatedFormat('d M Y H:i') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <a href="{{ route('company.workspaces.show', $workspace) }}"
                                       class="inline-flex items-center justify-center gap-2 w-full px-4 py-3 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700">
                                        <i class="fa-solid fa-folder-open text-xs"></i> Buka Workspace Proyek
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- Detail Pekerjaan --}}
                        <div class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-blue-50 flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-blue-50 text-brand flex items-center justify-center">
                                    <i class="fa-solid fa-briefcase text-sm"></i>
                                </div>
                                <h3 class="font-bold text-sm text-slate-800 dark:text-slate-100">Detail Pekerjaan</h3>
                            </div>
                            <div class="p-5 space-y-3">
                                <div class="flex items-center justify-between gap-4">
                                    <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Freelancer</p>
                                    <span class="text-xs font-bold text-slate-700 text-right">{{ $workspace->freelancer->name ?? '-' }}</span>
                                </div>
                                <div class="flex items-center justify-between gap-4">
                                    <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Workspace</p>
                                    <span class="text-xs font-bold text-slate-700">#{{ $workspace->id }}</span>
                                </div>
                                <div class="flex items-center justify-between gap-4">
                                    <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Status Pekerjaan</p>
                                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-slate-50 text-slate-600 border border-slate-200">
                                        {{ $workspace->status }}
                                    </span>
                                </div>
                                @if(!empty($workspace->project->description))
                                    <div class="pt-2 border-t border-blue-50">
                                        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Deskripsi Singkat</p>
                                        <p class="text-xs text-slate-500 leading-relaxed">
                                            {{ \Illuminate\Support\Str::limit(strip_tags($workspace->project->description), 180) }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT: INVOICE SUMMARY --}}
                    <div class="md:col-span-2 space-y-6">

                        {{-- Invoice Summary --}}
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
                                    <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Nama Project</p>
                                    <span class="text-xs font-bold text-slate-700 text-right max-w-[150px]">{{ $workspace->project->project_name }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Nama Freelancer</p>
                                    <span class="text-xs font-bold text-slate-700">{{ $workspace->freelancer->name }}</span>
                                </div>

                                <div class="border-t border-blue-50 dark:border-slate-800 pt-4 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Biaya Proyek (dari penawaran)</p>
                                        <span class="text-xs font-semibold text-slate-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Fee Platform{{ ($payment->platform_fee_rate !== null && $payment->platform_fee_rate !== '') ? ' (' . rtrim(rtrim(number_format((float) $payment->platform_fee_rate, 2, '.', ''), '0'), '.') . '%)' : '' }}</p>
                                        <span class="text-xs font-semibold text-slate-600">Rp {{ number_format($payment->platform_fee, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Freelancer Menerima</p>
                                        <span class="text-xs font-semibold text-slate-600">Rp {{ number_format($payment->freelancer_receive, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex items-center justify-between pt-2 border-t border-blue-100">
                                        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Total Dibayar Company</p>
                                        <span class="text-lg font-extrabold text-emerald-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between bg-[#f6f9ff] rounded-xl px-4 py-3">
                                    <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Status Pembayaran</p>
                                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-full {{ $payment->status_color }}">
                                        {{ $payment->status === 'pending' ? 'Menunggu Pembayaran' : $payment->status_label }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

    </div>

    {{-- Midtrans Snap SDK (Sandbox) + External JS --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <script src="{{ asset('js/toast.js') }}"></script>
    <script src="{{ asset('js/payments/midtrans.js') }}"></script>

    @if ((bool) config('services.midtrans.temporary_confirmation', false) && $payment->status !== 'paid')
        {{-- MANUAL / DEMO PAYMENT — hanya saat mode temporary confirmation aktif.
             Nominal TIDAK dikirim dari sini; server memakai amount dari database. --}}
        <div class="max-w-4xl mx-auto px-4 pb-10">
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
                <div class="flex items-center gap-2 mb-1">
                    <i class="fa-solid fa-flask text-amber-600"></i>
                    <h3 class="font-bold text-sm text-amber-800">Bayar Manual (Mode Demo)</h3>
                </div>
                <p class="text-[11px] text-amber-700 leading-relaxed mb-3">
                    Konfirmasi pembayaran tanpa Midtrans untuk kebutuhan demo/testing. Nominal tetap dibaca dari
                    database (Rp {{ number_format($payment->amount, 0, ',', '.') }}).
                </p>
                <button type="button" id="manualConfirmBtn"
                    class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition disabled:opacity-60">
                    <i class="fa-solid fa-circle-check"></i> Konfirmasi Manual
                </button>
            </div>
        </div>
        <script>
            (function () {
                var btn = document.getElementById('manualConfirmBtn');
                if (!btn) return;
                btn.addEventListener('click', function () {
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memproses…';
                    fetch("{{ route('company.payments.confirm', $workspace) }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({})
                    }).then(function (r) { return r.json(); }).then(function (data) {
                        if (data.success && data.redirect_url) {
                            window.location.href = data.redirect_url;
                            return;
                        }
                        alert((data && data.message) || 'Gagal mengonfirmasi pembayaran.');
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fa-solid fa-circle-check"></i> Konfirmasi Manual';
                    }).catch(function () {
                        alert('Gagal menghubungi server.');
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fa-solid fa-circle-check"></i> Konfirmasi Manual';
                    });
                });
            })();
        </script>
    @endif
</body>

</html>