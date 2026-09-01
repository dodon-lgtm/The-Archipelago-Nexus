@extends('layouts.admin')

@section('title', 'Detail Pembayaran')
@section('breadcrumb', 'Detail Pembayaran')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm text-slate-400">
            <a href="{{ route('admin.payments.index') }}" class="hover:text-blue-600 transition">Pembayaran</a>
            <i class="fa-solid fa-chevron-right text-[10px]"></i>
            <span class="text-slate-600 dark:text-slate-300 font-medium">{{ $payment->invoice_number }}</span>
        </div>

        {{-- Card: Invoice Info --}}
        <div class="bg-white border border-blue-100 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-blue-50">
                <div class="flex items-center justify-between">
                    <h2 class="font-bold text-slate-800">Informasi Pembayaran</h2>
                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-full border {{ $payment->status_color }}">
                        {{ $payment->status_label }}
                    </span>
                    @if($payment->funds_status !== 'not_applicable')
                        <span class="ml-2 text-[10px] font-bold px-2.5 py-1 rounded-full border {{ $payment->funds_status_color }}">
                            {{ $payment->funds_status_label }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Nomor Invoice</p>
                            <p class="text-sm font-bold text-slate-800 mt-0.5">{{ $payment->invoice_number }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Project</p>
                            <p class="text-sm font-semibold text-slate-700 mt-0.5">                            {{ $payment->workspace?->project?->project_name ?? ($payment->isQuotaPayment() ? 'Kuota Proyek' : '-') }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Perusahaan</p>
                            @if ($payment->company)
                                <a href="{{ route('admin.users.show', $payment->company) }}"
                                    class="text-sm font-semibold text-slate-700 dark:text-slate-200 hover:text-blue-600 dark:hover:text-blue-400 transition mt-0.5 inline-block">{{ $payment->company->name ?? '-' }}</a>
                            @else
                                <p class="text-sm font-semibold text-slate-700 dark:text-slate-200 mt-0.5">-</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Freelancer</p>
                            @if ($payment->freelancer)
                                <a href="{{ route('admin.users.show', $payment->freelancer) }}"
                                    class="text-sm font-semibold text-slate-700 dark:text-slate-200 hover:text-blue-600 dark:hover:text-blue-400 transition mt-0.5 inline-block">{{ $payment->freelancer->name ?? '-' }}</a>
                            @else
                                <p class="text-sm font-semibold text-slate-700 dark:text-slate-200 mt-0.5">-</p>
                            @endif
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Total</p>
                            <p class="text-sm font-bold text-slate-800 mt-0.5">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Biaya Platform (5%)</p>
                            <p class="text-sm font-semibold text-slate-700 mt-0.5">Rp {{ number_format($payment->platform_fee, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Diterima Freelancer</p>
                            <p class="text-sm font-bold text-emerald-600 mt-0.5">Rp {{ number_format($payment->freelancer_receive, 0, ',', '.') }}</p>
                        </div>
                        @if($payment->payment_method)
                            <div>
                                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Metode Pembayaran</p>
                                <p class="text-sm font-semibold text-slate-700 mt-0.5">{{ $payment->payment_method }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Card: Bukti Pembayaran --}}
        @if($payment->payment_proof)
            <div class="bg-white border border-blue-100 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-blue-50">
                    <h2 class="font-bold text-slate-800">Bukti Pembayaran</h2>
                </div>
                <div class="p-6">
                    <div class="bg-[#f6f9ff] rounded-xl p-4">
                        @php
                            $ext = strtolower(pathinfo($payment->payment_proof, PATHINFO_EXTENSION));
                        @endphp
                        @if(in_array($ext, ['jpg', 'jpeg', 'png']))
                            <img src="{{ asset('storage/' . $payment->payment_proof) }}"
                                 alt="Bukti Pembayaran"
                                 class="max-w-full max-h-96 rounded-lg mx-auto object-contain">
                        @elseif($ext === 'pdf')
                            <div class="flex items-center gap-4 p-4">
                                <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center">
                                    <i class="fa-solid fa-file-pdf text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-700">File PDF</p>
                                    <a href="{{ asset('storage/' . $payment->payment_proof) }}" target="_blank"
                                       class="text-xs text-brand font-semibold hover:underline">
                                        <i class="fa-solid fa-external-link-alt mr-1"></i> Buka File
                                    </a>
                                </div>
                            </div>
                        @else
                            <a href="{{ asset('storage/' . $payment->payment_proof) }}" target="_blank"
                               class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
                                <i class="fa-solid fa-download"></i> Download Bukti Pembayaran
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- Card: Informasi Pembayaran Manual --}}
        @if($payment->sender_name || $payment->sender_bank || $payment->sender_account_number || $payment->payment_date || $payment->paid_amount || $payment->destination_info)
            <div class="bg-white border border-blue-100 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-blue-50">
                    <h2 class="font-bold text-slate-800">Informasi Pembayaran Manual</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                        <div>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Nama Pengirim</p>
                            <p class="font-semibold text-slate-700 mt-0.5">{{ $payment->sender_name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Bank/Wallet Pengirim</p>
                            <p class="font-semibold text-slate-700 mt-0.5">{{ $payment->sender_bank ?? '-' }}</p>
                        </div>
                        @if($payment->sender_account_number)
                            <div>
                                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Nomor Rekening/Wallet Pengirim</p>
                                <p class="font-semibold text-slate-700 mt-0.5">{{ $payment->sender_account_number }}</p>
                            </div>
                        @endif
                        <div>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Tanggal Pembayaran</p>
                            <p class="font-semibold text-slate-700 mt-0.5">{{ $payment->payment_date ? $payment->payment_date->format('d M Y') : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Nominal Dibayar</p>
                            <p class="font-bold text-emerald-600 mt-0.5">Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}</p>
                        </div>
                        @if($payment->company_note)
                            <div class="md:col-span-2">
                                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Catatan</p>
                                <p class="text-xs text-slate-600 mt-0.5">{{ $payment->company_note }}</p>
                            </div>
                        @endif
                        @if(!empty($payment->destination_info))
                            @php $destInfo = $payment->destination_info; @endphp
                            <div class="md:col-span-2">
                                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Rekening/Wallet Tujuan yang Dipakai</p>
                                <div class="mt-2 bg-[#f6f9ff] rounded-xl p-4 border border-blue-100 space-y-2">
                                    <p class="text-xs font-bold text-slate-800 mb-1">
                                        <i class="fa-solid fa-building-columns mr-1.5 text-brand"></i>{{ $destInfo['title'] ?? 'ApexForge Labs' }} — {{ $destInfo['label'] ?? '' }}
                                    </p>
                                    @foreach(($destInfo['rows'] ?? []) as $label => $value)
                                        <div class="flex items-center justify-between gap-4">
                                            <p class="text-xs text-slate-400">{{ $label }}</p>
                                            <p class="text-xs font-bold text-slate-700 text-right break-words">{{ $value }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

{{-- Card: Catatan --}}
        @if($payment->company_note)
            <div class="bg-white border border-blue-100 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-blue-50">
                    <h2 class="font-bold text-slate-800">Catatan Perusahaan</h2>
                </div>
                <div class="p-6">
                    <p class="text-sm text-slate-700">{{ $payment->company_note }}</p>
                </div>
            </div>
        @endif

        @if($payment->admin_note)
            <div class="bg-white border border-blue-100 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-blue-50">
                    <h2 class="font-bold text-slate-800">Catatan Admin</h2>
                </div>
                <div class="p-6">
                    <p class="text-sm text-slate-700">{{ $payment->admin_note }}</p>
                </div>
            </div>
        @endif

        {{-- Actions (Mencakup status pending, waiting_verification, & menunggu_verifikasi) --}}
        @if(in_array(strtolower($payment->status), ['pending', 'waiting_verification', 'menunggu_verifikasi']))
            <div class="bg-white border border-blue-100 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-blue-50">
                    <h2 class="font-bold text-slate-800">Aksi Verifikasi</h2>
                </div>
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                        {{-- Verify --}}
                        <form method="POST" action="{{ route('admin.payments.verify', $payment) }}"
                              onsubmit="return adminConfirm('Yakin ingin memverifikasi pembayaran ini? Dana otomatis ditahan (escrow) dan workspace menjadi Sedang Dikerjakan.', this)">
                            @csrf
                            <button type="submit"
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-emerald-500 text-white rounded-xl text-sm font-semibold hover:bg-emerald-600 transition">
                                <i class="fa-solid fa-check-circle"></i> Verifikasi Pembayaran
                            </button>
                        </form>

                        {{-- Reject --}}
                        <button type="button"
                                onclick="document.getElementById('rejectModal').classList.remove('hidden')"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-red-500 text-white rounded-xl text-sm font-semibold hover:bg-red-600 transition">
                            <i class="fa-solid fa-times-circle"></i> Tolak Pembayaran
                        </button>
                    </div>

                    <div class="mt-4 flex items-center gap-2 px-4 py-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-700">
                        <i class="fa-solid fa-info-circle"></i>
                        <span>Verifikasi akan menahan dana (escrow) dan mengubah status workspace menjadi <strong>Sedang Dikerjakan</strong>. Menolak akan mengembalikan status workspace menjadi <strong>Menunggu Pembayaran</strong>.</span>
                    </div>
                </div>
            </div>

            {{-- Modal Reject --}}
            <div id="rejectModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
                    <div class="px-6 py-5 border-b border-blue-50 flex items-center justify-between">
                        <h3 class="font-bold text-slate-800">Tolak Pembayaran</h3>
                        <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')"
                                class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center hover:bg-slate-200 transition">
                            <i class="fa-solid fa-xmark text-slate-500"></i>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('admin.payments.reject', $payment->id) }}" class="p-6 space-y-4">
                        @csrf
                        <div class="flex items-center gap-3 px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                            <i class="fa-solid fa-info-circle"></i>
                            <p class="text-xs font-medium">Pembayaran akan ditolak dan perusahaan dapat mengupload ulang bukti pembayaran.</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Alasan Penolakan (opsional)</label>
                            <textarea name="admin_note" rows="4" maxlength="2000"
                                      placeholder="Jelaskan alasan penolakan..."
                                      class="w-full px-4 py-2.5 bg-[#f6f9ff] border border-blue-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 resize-none"></textarea>
                        </div>
                        <button type="submit"
                                class="w-full py-2.5 bg-red-500 text-white rounded-xl text-sm font-semibold hover:bg-red-600 transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-times-circle"></i> Ya, Tolak Pembayaran
                        </button>
                    </form>
                </div>
            </div>
        @endif

        {{-- Info jika sudah diverifikasi --}}
        @if(in_array(strtolower($payment->status), ['paid', 'dibayar', 'selesai']) && $payment->verifier)
            <div class="bg-white border border-blue-100 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-blue-50">
                    <h2 class="font-bold text-slate-800">Informasi Verifikasi</h2>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <i class="fa-solid fa-check-circle text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-700">Diverifikasi oleh {{ $payment->verifier->name ?? 'Admin' }}</p>
                            <p class="text-xs text-slate-400">{{ $payment->verified_at ? $payment->verified_at->format('d M Y H:i') : '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Info Dana Tertahan (escrow) --}}
        @if($payment->funds_status !== 'not_applicable')
            <div class="bg-white border border-blue-100 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-blue-50 flex items-center justify-between">
                    <h2 class="font-bold text-slate-800">Informasi Dana (Escrow)</h2>
                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-full border {{ $payment->funds_status_color }}">
                        {{ $payment->funds_status_label }}
                    </span>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Dana Ditahan Sejak</p>
                            <p class="font-semibold text-slate-700 mt-0.5">{{ $payment->held_at ? $payment->held_at->format('d M Y H:i') : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Dirilis Pada</p>
                            <p class="font-semibold text-slate-700 mt-0.5">{{ $payment->released_at ? $payment->released_at->format('d M Y H:i') : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Dirilis ke Freelancer</p>
                            <p class="font-bold text-emerald-600 mt-0.5">Rp {{ number_format($payment->released_amount, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Direfund ke Company</p>
                            <p class="font-bold text-red-600 mt-0.5">Rp {{ number_format($payment->refunded_amount, 0, ',', '.') }}</p>
                        </div>
                        @if($payment->dispute_reference)
                            <div class="md:col-span-2">
                                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Referensi Dispute</p>
                                <p class="font-semibold text-slate-700 mt-0.5">{{ $payment->dispute_reference }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- Info jika ditolak --}}
        @if(in_array(strtolower($payment->status), ['rejected', 'ditolak']) && $payment->verifier)
            <div class="bg-white border border-blue-100 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-blue-50">
                    <h2 class="font-bold text-slate-800">Informasi Penolakan</h2>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-red-50 text-red-600 flex items-center justify-center">
                            <i class="fa-solid fa-times-circle text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-700">Ditolak oleh {{ $payment->verifier->name ?? 'Admin' }}</p>
                            <p class="text-xs text-slate-400">{{ $payment->verified_at ? $payment->verified_at->format('d M Y H:i') : '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Button Kembali --}}
        <div class="flex justify-center">
            <a href="{{ route('admin.payments.index') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-blue-100 rounded-xl text-sm font-semibold text-slate-600 hover:bg-[#f6f9ff] transition">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Pembayaran
            </a>
        </div>
    </div>
@endsection