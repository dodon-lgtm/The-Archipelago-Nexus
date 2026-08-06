<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workspace - {{ $workspace->project->project_name }}</title>

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

        .modal-backdrop { animation: fadeInBackdrop .25s ease-out; }
        .modal-panel { animation: modalPop .35s cubic-bezier(.34, 1.56, .64, 1); }

        @keyframes iconPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(255,255,255,.45); }
            50% { box-shadow: 0 0 0 9px rgba(255,255,255,0); }
        }
        .icon-badge { animation: iconPulse 2.4s ease-in-out infinite; }

        @keyframes starPop {
            0% { transform: scale(1) rotate(0); }
            45% { transform: scale(1.4) rotate(-10deg); }
            100% { transform: scale(1.05) rotate(0); }
        }
        .star-btn.pop { animation: starPop .38s ease; }

        @keyframes floatSlow {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-6px) rotate(8deg); }
        }
        .deco-star {
            position: absolute;
            color: rgba(255,255,255,.35);
            animation: floatSlow 3.5s ease-in-out infinite;
        }

        .modal-header-pattern {
            background-image: radial-gradient(rgba(255,255,255,.16) 1.5px, transparent 1.5px);
            background-size: 16px 16px;
        }

        .star-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.85rem;
            color: #cbd5e1;
            filter: drop-shadow(0 0 0 rgba(251,191,36,0));
            transition: transform .18s ease, color .18s ease, filter .18s ease;
        }
        .star-btn:hover { transform: scale(1.18); }
        .star-btn.active {
            color: #fbbf24;
            transform: scale(1.05);
            filter: drop-shadow(0 2px 6px rgba(251,191,36,.55));
        }

        .btn-shimmer { position: relative; overflow: hidden; isolation: isolate; }
        .btn-shimmer::after {
            content: '';
            position: absolute;
            top: 0; left: -75%;
            width: 50%; height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255,255,255,.4), transparent);
            transform: skewX(-20deg);
            transition: left .65s ease;
        }
        .btn-shimmer:hover::after { left: 125%; }

        .field-shell:focus-within {
            box-shadow: 0 0 0 4px rgba(37,99,235,.12);
        }
    </style>
</head>

<body class="bg-surface text-slate-800 min-h-screen flex font-sans">

    @include('navbar.navigasi')

    <div class="flex-1 min-w-0 flex flex-col min-h-screen">
        @include('navbar.nav')

        <main class="flex-1 min-w-0 overflow-y-auto">
            <div class="max-w-7xl mx-auto px-6 py-8">

                {{-- Breadcrumb --}}
                <div class="flex items-center gap-2 text-sm text-slate-400 mb-6">
                    @if (auth()->user()->role === 'company')
                        <a href="{{ route('company.workspaces.index') }}"
                            class="hover:text-brand transition">Workspace</a>
                    @else
                        <a href="{{ route('freelancer.workspaces.index') }}"
                            class="hover:text-brand transition">Workspace Saya</a>
                    @endif
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    <span class="text-slate-600 font-medium">{{ $workspace->project->project_name }}</span>
                </div>

                @if (session('success'))
                    <div
                        class="mb-6 flex items-center gap-3 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium">
                        <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div
                        class="mb-6 flex items-center gap-3 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm font-medium">
                        <i class="fa-solid fa-xmark-circle"></i> {{ session('error') }}
                    </div>
                @endif

                {{-- Layout Single Column --}}
                <div class="space-y-6">

                    {{-- ROW 1: INFO PROJECT + PROGRESS --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        {{-- Card: Info Project --}}
                        <div
                            class="md:col-span-1 bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                            <div class="px-5 py-4 border-b border-slate-100">
                                <h2 class="font-bold text-sm text-slate-800">{{ $workspace->project->project_name }}
                                </h2>
                            </div>
                            <div class="p-5 space-y-3 text-sm">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-building text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-slate-400">Perusahaan</p>
                                        <p class="text-xs font-semibold text-slate-700">{{ $workspace->company->name }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-cyan-50 text-cyan-600 flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-user-tie text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-slate-400">Freelancer</p>
                                        <p class="text-xs font-semibold text-slate-700">
                                            {{ $workspace->freelancer->name }}</p>
                                    </div>
                                </div>
                                @if ($workspace->project->deadline)
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center shrink-0">
                                            <i class="fa-regular fa-calendar text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="text-[10px] text-slate-400">Deadline</p>
                                            <p class="text-xs font-semibold text-slate-700">
                                                {{ \Carbon\Carbon::parse($workspace->project->deadline)->format('d M Y') }}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Card: Progress Bar --}}
                        <div
                            class="md:col-span-1 bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden flex flex-col justify-between">
                            <div>
                                <div class="px-5 py-4 border-b border-slate-100">
                                    <h2 class="font-bold text-sm text-slate-800">Progress Pengerjaan</h2>
                                </div>
                                <div class="p-5">
                                    <div class="text-center mb-3">
                                        <span class="text-3xl font-extrabold text-brand">{{ $progressValue }}%</span>
                                        @if ($workspace->latestProgress)
                                            <p class="text-xs text-slate-400 mt-1">{{ $workspace->latestProgress->stage }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                                        <div class="h-full rounded-full bg-gradient-to-r from-brand to-cyan-400 transition-all duration-700"
                                            style="width: {{ $progressValue }}%"></div>
                                    </div>
                                </div>
                            </div>
                            @if (auth()->user()->role === 'freelancer')
                                <div class="mt-20 px-5">
                                    <button type="button"
                                        onclick="document.getElementById('progressModal').classList.remove('hidden')"
                                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-brand text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
                                        <i class="fa-solid fa-chart-line"></i>
                                        Update Progress
                                    </button>
                                </div>
                            @endif
                        </div>

                        {{-- Card: Stage --}}
                        <div
                            class="md:col-span-1 bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                            <div class="px-5 py-4 border-b border-slate-100">
                                <h2 class="font-bold text-sm text-slate-800">Tahap Pengerjaan</h2>
                            </div>
                            <div class="p-5">
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach ($allStages as $index => $stage)
                                        @php
                                            $isCompleted = $index < $activeStageIndex;
                                            $isActive = $index === $activeStageIndex;
                                            if ($isCompleted) {
                                                $icon = 'fa-solid fa-check-circle';
                                                $color = 'text-emerald-500';
                                                $bg = 'bg-emerald-50';
                                                $label = 'Selesai';
                                                $labelColor = 'text-emerald-600 bg-emerald-100';
                                            } elseif ($isActive) {
                                                $icon = 'fa-solid fa-play-circle';
                                                $color = 'text-brand';
                                                $bg = 'bg-blue-50';
                                                $label = 'Aktif';
                                                $labelColor = 'text-white bg-brand';
                                            } else {
                                                $icon = 'fa-regular fa-circle';
                                                $color = 'text-slate-300';
                                                $bg = 'bg-slate-50';
                                                $label = '';
                                                $labelColor = '';
                                            }
                                        @endphp
                                        <div
                                            class="{{ $bg }} border border-slate-100 rounded-xl p-2 flex items-center gap-2 {{ $isActive ? 'ring-2 ring-brand/20' : '' }}">
                                            <i class="{{ $icon }} {{ $color }} text-sm"></i>
                                            <div class="min-w-0">
                                                <p
                                                    class="text-[10px] font-semibold {{ $isCompleted ? 'text-emerald-700' : ($isActive ? 'text-brand' : 'text-slate-400') }} truncate">
                                                    {{ $stage }}</p>
                                                @if ($label)
                                                    <span
                                                        class="text-[8px] font-bold px-1.5 py-0.5 rounded-full {{ $labelColor }}">{{ $label }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- ROW 2: CHAT --}}
                    <div
                        class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden flex flex-col h-[450px]">
                        {{-- Chat Header --}}
                        <div
                            class="px-5 py-4 border-b border-slate-100 bg-white flex items-center justify-between shrink-0">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center font-bold text-sm">
                                    {{ strtoupper(substr(auth()->user()->role === 'company' ? $workspace->freelancer->name : $workspace->company->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-sm text-slate-800">
                                        {{ auth()->user()->role === 'company' ? $workspace->freelancer->name : $workspace->company->name }}
                                    </h3>
                                    <p class="text-[10px] text-slate-400">{{ $workspace->project->project_name }}</p>
                                </div>
                            </div>
@php
                                $chatStatusColors = [
                                    'Sedang Dikerjakan' => 'bg-blue-500',
                                    'Menunggu Revisi' => 'bg-amber-500',
                                    'Menunggu Pembayaran' => 'bg-purple-500',
                                    'Menunggu Verifikasi Admin' => 'bg-orange-500',
                                    'Selesai' => 'bg-emerald-500',
                                ];
                            @endphp
                            <div class="flex items-center gap-3">
                                <span class="flex items-center gap-1.5 text-[10px] text-slate-500">
                                    <span
                                        class="w-2 h-2 rounded-full {{ $chatStatusColors[$workspace->status] ?? 'bg-slate-400' }}"></span>
                                    {{ $workspace->status }}
                                </span>
                                {{-- Tombol Laporkan dari workspace (kontekstual) --}}
                                @php
                                    $reportedTarget = auth()->user()->role === 'company'
                                        ? $workspace->freelancer
                                        : $workspace->company;
                                @endphp
                                @if($reportedTarget && (int) $reportedTarget->id !== (int) auth()->id())
                                    <a href="{{ route(auth()->user()->role === 'company' ? 'company.reports.create' : 'freelancer.reports.create', ['workspace_id' => $workspace->id, 'reported_user_id' => $reportedTarget->id]) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition">
                                        <i class="fa-solid fa-flag"></i> Laporkan
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- Chat Body --}}
                        <div id="chatBody" class="flex-1 overflow-y-auto p-5 space-y-4 bg-slate-50/50">
                            @if ($workspace->messages->isNotEmpty())
                                @foreach ($workspace->messages as $message)
                                    @if ($message->type === 'system')
                                        <div class="flex justify-center">
                                            <div
                                                class="bg-white text-slate-400 text-[10px] font-medium px-4 py-2 rounded-full border border-slate-200 inline-flex items-center gap-2 shadow-sm">
                                                <i class="fa-solid fa-gear text-[9px]"></i>
                                                {{ $message->message }}
                                            </div>
                                        </div>
                                    @else
                                        @php $isMine = (int) $message->sender_id === (int) auth()->id(); @endphp
                                        <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}">
                                            <div
                                                class="max-w-[80%] {{ $isMine ? 'bg-brand text-white' : 'bg-white text-slate-700 border border-slate-200' }} rounded-2xl px-4 py-3 shadow-sm">
                                                @if (!$isMine)
                                                    <p class="text-[10px] font-bold text-slate-400 mb-1">
                                                        {{ $message->sender->name }}</p>
                                                @endif
                                                <p class="text-sm leading-relaxed">{{ $message->message }}</p>
                                                <p
                                                    class="text-[9px] mt-1.5 {{ $isMine ? 'text-white/60' : 'text-slate-400' }} text-right">
                                                    {{ $message->created_at->format('H:i, d M') }}</p>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="flex flex-col items-center justify-center h-full py-12">
                                    <div
                                        class="w-16 h-16 mx-auto mb-4 bg-white rounded-2xl border border-slate-200 flex items-center justify-center">
                                        <i class="fa-regular fa-message text-2xl text-slate-300"></i>
                                    </div>
                                    <h3 class="text-sm font-bold text-slate-600">Belum Ada Pesan</h3>
                                    <p class="text-xs text-slate-400 mt-1">Mulai percakapan dengan mengirim pesan.</p>
                                </div>
                            @endif
                        </div>

                        {{-- Chat Input --}}
                        <div class="px-5 py-4 border-t border-slate-200 bg-white shrink-0">
                            <form method="POST"
                                action="{{ route(auth()->user()->role === 'company' ? 'company.workspaces.message' : 'freelancer.workspaces.message', $workspace) }}"
                                class="flex items-center gap-3">
                                @csrf
                                <input type="text" name="message" placeholder="Ketik pesan..." required
                                    maxlength="1000"
                                    class="flex-1 px-4 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand transition">
                                <button type="submit"
                                    class="px-5 py-2.5 bg-brand text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition flex items-center gap-2">
                                    <i class="fa-solid fa-paper-plane"></i>
                                    <span class="hidden sm:inline">Kirim</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- ROW 3: HASIL PEKERJAAN / SUBMISSIONS --}}
                    @include('workspace._submissions')

                    {{-- ROW 4: TIMELINE + ACTIONS --}}
                    {{-- ============================================================
                         ROW 4: INVOICE (untuk company saat Menunggu Pembayaran / Menunggu Verifikasi Admin)
                    ============================================================ --}}
                    @if(auth()->user()->role === 'company' && in_array($workspace->status, ['Menunggu Pembayaran', 'Menunggu Verifikasi Admin']) && $payment)
                        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                            <div class="px-5 py-4 border-b border-slate-100">
                                <h2 class="font-bold text-sm text-slate-800">Invoice Pembayaran</h2>
                            </div>
                            <div class="p-5 space-y-4">
                                {{-- Invoice Info --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-slate-50 rounded-xl p-4 space-y-2">
                                        <div class="flex items-center justify-between">
                                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Nomor Invoice</p>
                                            <span class="text-xs font-bold text-slate-800">{{ $payment->invoice_number }}</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Total</p>
                                            <span class="text-sm font-bold text-slate-800">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Biaya Platform (5%)</p>
                                            <span class="text-xs font-semibold text-slate-600">Rp {{ number_format($payment->platform_fee, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex items-center justify-between pt-2 border-t border-slate-200">
                                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Total Dibayar</p>
                                            <span class="text-sm font-bold text-emerald-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                        </div>
                                    </div>

                                    <div class="bg-slate-50 rounded-xl p-4 space-y-2">
                                        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Status Pembayaran</p>
                                        @php
                                            $psColor = $payment->status === 'pending' ? 'bg-amber-50 text-amber-600 border-amber-200' : 'bg-slate-50 text-slate-600 border-slate-200';
                                            $psLabel = $payment->status === 'pending' ? 'Belum Dibayar' : 'Menunggu Verifikasi';
                                        @endphp
                                        <span class="text-[10px] font-bold px-2.5 py-1 rounded-full border {{ $psColor }}">
                                            {{ $psLabel }}
                                        </span>

                                        @if($payment->status === 'rejected')
                                            <div class="mt-3 px-3 py-2 bg-red-50 border border-red-200 rounded-lg">
                                                <p class="text-[10px] font-semibold text-red-700">Pembayaran ditolak. Silakan upload ulang.</p>
                                                @if($payment->admin_note)
                                                    <p class="text-[9px] text-red-600 mt-1">Alasan: {{ $payment->admin_note }}</p>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>

{{-- Payment Gateway (hanya jika status pending) --}}
                                @if($payment->status === 'pending')
                                    <div class="flex flex-col gap-3">
                                        <p class="text-xs text-slate-500 leading-relaxed">
                                            Silakan lanjutkan ke <strong>Payment Gateway</strong> untuk melakukan pembayaran, kemudian upload bukti pembayaran pada halaman berikutnya.
                                        </p>
                                        <a href="{{ route('company.payments.gateway', $workspace) }}"
                                           class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-brand text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition shadow-lg shadow-brand/20">
                                            <i class="fa-solid fa-credit-card"></i> Bayar Sekarang
                                        </a>
                                        <p class="text-[10px] text-slate-400 text-center">
                                            <i class="fa-solid fa-circle-info mr-1"></i>
                                            <strong>Mode Simulasi</strong> &mdash; Pembayaran ini digunakan untuk demonstrasi aplikasi. Integrasi Midtrans, QRIS, Virtual Account, dan E-Wallet akan tersedia pada versi berikutnya.
                                        </p>
                                    </div>
                                @endif

                                {{-- Payment Upload Form (hanya jika status rejected / re-upload) --}}
                                @if($payment->status === 'rejected')
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
                                                class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-brand text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
                                            <i class="fa-solid fa-paper-plane"></i> Kirim Pembayaran
                                        </button>
                                    </form>
                                @endif

                                {{-- Status waiting verification --}}
                                @if($payment->status === 'waiting_verification')
                                    <div class="flex items-center gap-3 px-4 py-3 bg-amber-50 border border-amber-200 rounded-xl text-sm text-amber-700">
                                        <i class="fa-solid fa-clock"></i>
                                        <p class="text-xs font-medium">Bukti pembayaran telah dikirim. Menunggu verifikasi admin.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- ============================================================
                         ROW 5: TIMELINE + ACTIONS (side by side)
                    ============================================================ --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        {{-- Card: Timeline --}}
                        <div
                            class="md:col-span-2 bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                            <div class="px-5 py-4 border-b border-slate-100">
                                <h2 class="font-bold text-sm text-slate-800">Timeline Progress</h2>
                            </div>
                            <div class="p-5">
                                @if ($workspace->progressHistories->isNotEmpty())
                                    <div class="space-y-4">
                                        @foreach ($workspace->progressHistories as $history)
                                            <div class="relative pl-6 border-l-2 border-slate-200">
                                                <div
                                                    class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-brand flex items-center justify-center">
                                                    <span
                                                        class="text-[8px] text-white font-bold">{{ $history->progress }}%</span>
                                                </div>
                                                <div class="bg-slate-50 rounded-xl p-3 ml-2">
                                                    <div class="flex items-center justify-between gap-2 mb-1">
                                                        <span
                                                            class="text-xs font-bold text-slate-700">{{ $history->stage }}</span>
                                                        <span
                                                            class="text-[10px] text-slate-400">{{ $history->created_at->format('d M Y') }}</span>
                                                    </div>
                                                    @if ($history->description)
                                                        <p class="text-xs text-slate-500 leading-relaxed">
                                                            {{ $history->description }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="py-8 text-center">
                                        <p class="text-xs text-slate-400">Belum ada riwayat progress.</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Card: Actions & Rating --}}
                        <div
                            class="md:col-span-1 bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden flex flex-col justify-between">
                            <div>
                                <div class="px-5 py-4 border-b border-slate-100">
                                    <h2 class="font-bold text-sm text-slate-800">Aksi & Ulasan</h2>
                                </div>
                                <div class="p-5 space-y-3">
                                    @if (auth()->user()->role === 'company' && $progressValue == 100 && $workspace->status !== 'Selesai')
                                        {{-- Confirm Completion Button (Company Only) --}}
                                        <form method="POST"
                                            action="{{ route('company.workspaces.complete', $workspace) }}"
                                            onsubmit="return confirm('Konfirmasi bahwa pekerjaan telah selesai?')">
                                            @csrf
                                            <button type="submit"
                                                class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-500 text-white rounded-xl text-sm font-semibold hover:bg-emerald-600 transition">
                                                <i class="fa-solid fa-check-circle"></i> Konfirmasi Pekerjaan Selesai
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Bagian Rating / Ulasan --}}
                                    @if ($workspace->status === 'Selesai')
                                        <div class="pt-2 border-t border-slate-100">
                                            @if ($workspace->rating)
                                                {{-- Tampilan saat rating sudah diberikan --}}
                                                <div class="bg-blue-50 border border-blue-100 rounded-xl p-3 text-center">
                                                    <p class="text-xs font-bold text-brand mb-1">Rating Telah Diberikan</p>
                                                    <div class="flex justify-center gap-1 text-amber-400 text-sm mb-1">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <i class="fa-{{ $i <= $workspace->rating->score ? 'solid' : 'regular' }} fa-star"></i>
                                                        @endfor
                                                    </div>
                                                    @if ($workspace->rating->review)
                                                        <p class="text-[11px] text-slate-600 italic">"{{ $workspace->rating->review }}"</p>
                                                    @endif
                                                </div>
                                            @else
                                                {{-- Tampilan saat belum pernah diberi rating --}}
                                                @if (auth()->user()->role === 'company')
                                                    <button type="button"
                                                        onclick="document.getElementById('ratingModal').classList.remove('hidden')"
                                                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-brand to-cyan-500 text-white rounded-xl text-sm font-semibold hover:shadow-lg hover:shadow-brand/30 hover:-translate-y-0.5 active:translate-y-0 transition-all">
                                                        <i class="fa-solid fa-star"></i> Beri Rating & Ulasan
                                                    </button>
                                                @else
                                                    <div class="text-center py-2 bg-slate-50 rounded-xl border border-slate-100">
                                                        <p class="text-xs text-slate-400">Belum ada rating dari perusahaan.</p>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </main>

        @include('navbar.footer')
    </div>

    {{-- MODAL UPDATE PROGRESS --}}
    <div id="progressModal"
        class="hidden modal-backdrop fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
        <div class="modal-panel bg-white rounded-3xl shadow-2xl shadow-brand/10 w-full max-w-md overflow-hidden border border-slate-100 ring-1 ring-black/[.03]">
            {{-- Gradient header with pattern + floating decor --}}
            <div class="relative px-6 py-7 bg-gradient-to-br from-blue-600 via-brand to-cyan-400 overflow-hidden">
                <div class="absolute inset-0 modal-header-pattern"></div>
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full"></div>
                <div class="absolute -bottom-12 -left-8 w-28 h-28 bg-white/10 rounded-full"></div>
                <i class="fa-solid fa-chart-simple deco-star text-xl" style="top:14px; right:56px; animation-delay:.2s;"></i>
                <i class="fa-solid fa-bolt deco-star text-sm" style="bottom:16px; left:70px; animation-delay:.8s;"></i>
                <div class="relative flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="icon-badge w-12 h-12 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center ring-1 ring-white/30">
                            <i class="fa-solid fa-chart-line text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-white text-base tracking-tight">Update Progress</h3>
                            <p class="text-[11px] text-white/75">Perbarui status pekerjaan Anda</p>
                        </div>
                    </div>
                    <button type="button" onclick="document.getElementById('progressModal').classList.add('hidden')"
                        class="w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 hover:rotate-90 flex items-center justify-center transition-all duration-300">
                        <i class="fa-solid fa-xmark text-white text-sm"></i>
                    </button>
                </div>
            </div>

            <form method="POST" action="{{ route('freelancer.workspaces.progress', $workspace) }}"
                class="p-6 space-y-4 bg-gradient-to-b from-blue-50/40 to-white">
                @csrf

                <div>
                    <label class="flex items-center gap-1.5 text-xs font-semibold text-slate-600 mb-1.5">
                        <i class="fa-solid fa-layer-group text-brand text-[11px]"></i> Stage
                    </label>
                    <div class="field-shell rounded-2xl transition">
                        <select name="stage" required
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:border-brand transition">
                            @foreach ($allStages as $stage)
                                <option value="{{ $stage }}" {{ $activeStage === $stage ? 'selected' : '' }}>
                                    {{ $stage }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="flex items-center gap-1.5 text-xs font-semibold text-slate-600 mb-1.5">
                        <i class="fa-solid fa-gauge-high text-brand text-[11px]"></i> Progress (0-100%)
                    </label>
                    <div class="field-shell rounded-2xl transition">
                        <input type="number" name="progress" min="0" max="100"
                            value="{{ $progressValue }}" required
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:border-brand transition">
                    </div>
                    <p class="text-[10px] text-slate-400 mt-1.5 flex items-center gap-1">
                        <i class="fa-solid fa-circle-info"></i> Progress minimal: {{ $progressValue }}% (tidak boleh turun)
                    </p>
                </div>

                <div>
                    <label class="flex items-center gap-1.5 text-xs font-semibold text-slate-600 mb-1.5">
                        <i class="fa-solid fa-pen text-brand text-[11px]"></i> Deskripsi
                    </label>
                    <div class="field-shell rounded-2xl transition">
                        <textarea name="description" rows="3" maxlength="500" id="progressDesc"
                            oninput="document.getElementById('progressDescCount').textContent = this.value.length"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:border-brand transition resize-none"
                            placeholder="Jelaskan update progress..."></textarea>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-1 text-right"><span id="progressDescCount">0</span>/500</p>
                </div>

                <button type="submit"
                    class="btn-shimmer w-full py-3 bg-gradient-to-r from-brand via-blue-600 to-cyan-500 text-white rounded-2xl text-sm font-semibold shadow-lg shadow-brand/30 hover:shadow-xl hover:shadow-brand/40 hover:-translate-y-0.5 active:translate-y-0 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Progress
                </button>
            </form>
        </div>
    </div>

    {{-- MODAL RATING & ULASAN (Untuk Company) --}}
    @if (auth()->user()->role === 'company' && $workspace->status === 'Selesai')
        <div id="ratingModal"
            class="hidden modal-backdrop fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
            <div class="modal-panel bg-white rounded-3xl shadow-2xl shadow-brand/10 w-full max-w-md overflow-hidden border border-slate-100 ring-1 ring-black/[.03]">
                {{-- Gradient header with pattern + floating decor --}}
                <div class="relative px-6 py-7 bg-gradient-to-br from-blue-600 via-brand to-cyan-400 overflow-hidden">
                    <div class="absolute inset-0 modal-header-pattern"></div>
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full"></div>
                    <div class="absolute -bottom-12 -left-8 w-28 h-28 bg-white/10 rounded-full"></div>
                    <i class="fa-solid fa-star deco-star text-lg" style="top:16px; right:60px; animation-delay:.1s;"></i>
                    <i class="fa-solid fa-star deco-star text-xs" style="bottom:14px; left:78px; animation-delay:.6s;"></i>
                    <i class="fa-regular fa-star deco-star text-sm" style="top:40px; right:100px; animation-delay:1.1s;"></i>
                    <div class="relative flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="icon-badge w-12 h-12 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center ring-1 ring-white/30">
                                <i class="fa-solid fa-star text-white text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-white text-base tracking-tight">Beri Rating & Ulasan</h3>
                                <p class="text-[11px] text-white/75">Bagikan pengalaman Anda</p>
                            </div>
                        </div>
                        <button type="button" onclick="document.getElementById('ratingModal').classList.add('hidden')"
                            class="w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 hover:rotate-90 flex items-center justify-center transition-all duration-300">
                            <i class="fa-solid fa-xmark text-white text-sm"></i>
                        </button>
                    </div>
                </div>

                {{-- Sesuaikan route endpoint aksi penyimpanan rating di backend Anda --}}
                <form method="POST" action="{{ route('company.client.review.store', $workspace->project_id) }}"
                    class="p-6 space-y-5 bg-gradient-to-b from-blue-50/40 to-white">
                    @csrf

                    <div class="text-center bg-gradient-to-b from-blue-50 to-white border border-blue-100 rounded-2xl py-5 px-4">
                        <label class="block text-xs font-semibold text-slate-500 mb-3 uppercase tracking-wide">Pilih Rating</label>
                        <input type="hidden" name="rating" id="ratingInput" value="5">
                        <div class="flex justify-center gap-1.5" id="starRating">
                            @for ($i = 1; $i <= 5; $i++)
                                <button type="button" class="star-btn active" data-value="{{ $i }}"
                                    onclick="setRating({{ $i }})">
                                    <i class="fa-solid fa-star"></i>
                                </button>
                            @endfor
                        </div>
                        <p class="text-xs font-bold text-brand mt-2.5 bg-white inline-block px-3 py-1 rounded-full shadow-sm border border-blue-100" id="ratingLabel">5 - Sempurna</p>
                    </div>

                    <div>
                        <label class="flex items-center gap-1.5 text-xs font-semibold text-slate-600 mb-1.5">
                            <i class="fa-solid fa-comment-dots text-brand text-[11px]"></i> Ulasan / Testimoni
                        </label>
                        <div class="field-shell rounded-2xl transition">
                            <textarea name="review" rows="3" maxlength="500" id="reviewText"
                                oninput="document.getElementById('reviewCount').textContent = this.value.length"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:border-brand transition resize-none"
                                placeholder="Tulis ulasan kinerja freelancer ini..."></textarea>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1 text-right"><span id="reviewCount">0</span>/500</p>
                    </div>

                    <button type="submit"
                        class="btn-shimmer w-full py-3 bg-gradient-to-r from-brand via-blue-600 to-cyan-500 text-white rounded-2xl text-sm font-semibold shadow-lg shadow-brand/30 hover:shadow-xl hover:shadow-brand/40 hover:-translate-y-0.5 active:translate-y-0 transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i> Kirim Ulasan
                    </button>
                </form>
            </div>
        </div>

        <script>
            const ratingLabels = { 1: '1 - Buruk', 2: '2 - Kurang', 3: '3 - Cukup', 4: '4 - Sangat Baik', 5: '5 - Sempurna' };
            function setRating(v) {
                document.getElementById('ratingInput').value = v;
                document.getElementById('ratingLabel').textContent = ratingLabels[v];
                document.querySelectorAll('#starRating .star-btn').forEach((btn, idx) => {
                    if (idx < v) {
                        btn.classList.add('active');
                    } else {
                        btn.classList.remove('active');
                    }
                });
                const clicked = document.querySelector('#starRating .star-btn[data-value="' + v + '"]');
                if (clicked) {
                    clicked.classList.remove('pop');
                    void clicked.offsetWidth;
                    clicked.classList.add('pop');
                }
            }
        </script>
    @endif

    <script>
        // Auto scroll chat ke bawah
        document.addEventListener('DOMContentLoaded', function() {
            const chatBody = document.getElementById('chatBody');
            if (chatBody) {
                chatBody.scrollTop = chatBody.scrollHeight;
            }
        });
    </script>

</body>

</html>