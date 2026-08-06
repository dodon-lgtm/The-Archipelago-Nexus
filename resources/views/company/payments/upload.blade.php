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
                        <div class="w-9 h-9 rounded-full bg-white text-slate-400 border border-slate-200 flex items-center justify-center">
                            <i class="fa-solid fa-shield-halved text-xs"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-slate-400 mt-1.5">Admin Verification</span>
                    </div>
                    <div class="h-0.5 bg-slate-200 flex-1"></div>
                    <div class="flex flex-col items-center">
                        <div class="w-9 h-9 rounded-full bg-white text-slate-400 border border-slate-200 flex items-center justify-center">
                            <i class="fa-solid fa-check text-xs"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-slate-400 mt-1.5">Completed</span>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
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
                        <div class="bg-slate-50 rounded-xl p-4 space-y-2">
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
                            <div class="flex items-center justify-between pt-2 border-t border-slate-200">
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
                                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand/30">
                                    <option value="">Pilih Metode</option>
                                    <option value="Transfer Bank">Transfer Bank</option>
                                    <option value="QRIS">QRIS</option>
                                    <option value="E-Wallet">E-Wallet</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Upload Bukti Pembayaran</label>
                                <input type="file" name="payment_proof" required accept=".jpg,.jpeg,.png,.pdf"
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-semibold file:bg-brand file:text-white hover:file:bg-blue-700 transition">
                                <p class="text-[10px] text-slate-400 mt-1">Format: jpg, jpeg, png, pdf. Maksimal 10 MB.</p>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Catatan (opsional)</label>
                                <textarea name="company_note" rows="2" maxlength="2000"
                                          placeholder="Tambahkan catatan..."
                                          class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 resize-none"></textarea>
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

        @include('navbar.footer')
    </div>

</body>

</html>
