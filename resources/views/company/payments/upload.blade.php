<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Bukti Pembayaran - {{ $workspace->project->project_name }}</title>

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

<body class="bg-surface text-slate-800 min-h-screen flex font-sans">

    @include('navbar.navigasi')

    <div class="flex-1 min-w-0 flex flex-col min-h-screen">
        @include('navbar.nav')

        <main class="flex-1 min-w-0 overflow-y-auto">
            <div class="max-w-3xl mx-auto px-6 py-8">

                {{-- Breadcrumb --}}
                <div class="flex items-center gap-2 text-sm text-slate-400 mb-6">
                    <a href="{{ route('company.workspaces.index') }}" class="hover:text-brand transition">Workspace</a>
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    <a href="{{ route('company.workspaces.show', $workspace) }}" class="hover:text-brand transition">{{ $workspace->project->project_name }}</a>
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    <span class="text-slate-600 font-medium">Upload Bukti Pembayaran</span>
                </div>

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

                @if ($errors->any())
                    <div class="mb-6 flex items-start gap-3 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
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
                            <i class="fa-solid fa-upload text-xs"></i>
                        </div>
                        <span class="text-[10px] font-bold text-brand mt-1.5">Upload Proof</span>
                    </div>
                    <div class="h-0.5 bg-slate-200 flex-1"></div>
                    <div class="flex flex-col items-center">
                        <div class="w-9 h-9 rounded-full bg-white text-slate-400 border border-blue-100 flex items-center justify-center">
                            <i class="fa-solid fa-shield-halved text-xs"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-slate-400 mt-1.5">Admin Verification</span>
                    </div>
                    <div class="h-0.5 bg-slate-200 flex-1"></div>
                    <div class="flex flex-col items-center">
                        <div class="w-9 h-9 rounded-full bg-white text-slate-400 border border-blue-100 flex items-center justify-center">
                            <i class="fa-solid fa-check text-xs"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-slate-400 mt-1.5">Completed</span>
                    </div>
                </div>

                <div class="bg-white border border-blue-100 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-blue-50 flex items-center justify-between">
                        <div>
                            <h2 class="font-bold text-base text-slate-800">Upload Bukti Pembayaran</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Melanjutkan ke verifikasi admin</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <i class="fa-solid fa-receipt"></i>
                        </div>
                    </div>

                    <div class="p-6 space-y-5">
                        {{-- Invoice Summary --}}
                        <div class="bg-[#f6f9ff] rounded-xl p-4 space-y-2">
                            <div class="flex items-center justify-between">
                                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Nomor Invoice</p>
                                <span class="text-xs font-bold text-slate-800">{{ $payment->invoice_number }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Nama Project</p>
                                <span class="text-xs font-bold text-slate-800">{{ $workspace->project->project_name }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Nama Freelancer</p>
                                <span class="text-xs font-bold text-slate-800">{{ $workspace->freelancer->name }}</span>
                            </div>
                            <div class="flex items-center justify-between pt-2 border-t border-blue-100">
                                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Total Dibayar</p>
                                <span class="text-base font-extrabold text-emerald-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        @if($payment->status === 'rejected')
                            <div class="flex items-start gap-3 px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                                <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                                <div>
                                    <p class="text-xs font-semibold">Pembayaran sebelumnya ditolak. Silakan upload ulang.</p>
                                    @if($payment->admin_note)
                                        <p class="text-[10px] text-red-600 mt-0.5">Alasan: {{ $payment->admin_note }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('company.payments.upload', $workspace) }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf

                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Metode Pembayaran</label>
                                <select name="payment_method" required
                                        class="w-full px-4 py-2.5 bg-[#f6f9ff] border border-blue-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand/30">
                                    <option value="">Pilih Metode</option>
                                    <option value="Transfer Bank">Transfer Bank</option>
                                    <option value="QRIS">QRIS</option>
                                    <option value="E-Wallet">E-Wallet</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Upload Bukti Pembayaran</label>
                                <input type="file" name="payment_proof" required accept=".jpg,.jpeg,.png,.pdf"
                                       class="w-full px-4 py-2.5 bg-[#f6f9ff] border border-blue-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-semibold file:bg-brand file:text-white hover:file:bg-blue-700 transition">
                                <p class="text-[10px] text-slate-400 mt-1">Format: jpg, jpeg, png, pdf. Maksimal 10 MB.</p>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Catatan (opsional)</label>
                                <textarea name="company_note" rows="2" maxlength="2000"
                                          placeholder="Tambahkan catatan..."
                                          class="w-full px-4 py-2.5 bg-[#f6f9ff] border border-blue-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 resize-none"></textarea>
                            </div>

                            <button type="submit"
                                    class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-brand text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition shadow-lg shadow-brand/20">
                                <i class="fa-solid fa-paper-plane"></i> Kirim Pembayaran
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </main>

     
    </div>

</body>

</html>
