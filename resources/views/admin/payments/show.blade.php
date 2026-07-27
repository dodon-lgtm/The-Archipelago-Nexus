@extends('layouts.admin')

@section('title', 'Detail Pembayaran')
@section('breadcrumb', 'Detail Pembayaran')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm text-slate-400">
            <a href="{{ route('admin.payments.index') }}" class="hover:text-brand transition">Pembayaran</a>
            <i class="fa-solid fa-chevron-right text-[10px]"></i>
            <span class="text-slate-600 font-medium">{{ $payment->invoice_number }}</span>
        </div>

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

        {{-- Card: Invoice Info --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100">
                <div class="flex items-center justify-between">
                    <h2 class="font-bold text-slate-800">Informasi Pembayaran</h2>
                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-full border {{ $payment->status_color }}">
                        {{ $payment->status_label }}
                    </span>
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
                            <p class="text-sm font-semibold text-slate-700 mt-0.5">{{ $payment->workspace->project->project_name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Perusahaan</p>
                            <p class="text-sm font-semibold text-slate-700 mt-0.5">{{ $payment->company->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Freelancer</p>
                            <p class="text-sm font-semibold text-slate-700 mt-0.5">{{ $payment->freelancer->name ?? '-' }}</p>
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
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100">
                    <h2 class="font-bold text-slate-800">Bukti Pembayaran</h2>
                </div>
                <div class="p-6">
                    <div class="bg-slate-50 rounded-xl p-4">
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

        {{-- Card: Catatan --}}
        @if($payment->company_note)
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100">
                    <h2 class="font-bold text-slate-800">Catatan Perusahaan</h2>
                </div>
                <div class="p-6">
                    <p class="text-sm text-slate-700">{{ $payment->company_note }}</p>
                </div>
            </div>
        @endif

        @if($payment->admin_note)
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100">
                    <h2 class="font-bold text-slate-800">Catatan Admin</h2>
                </div>
                <div class="p-6">
                    <p class="text-sm text-slate-700">{{ $payment->admin_note }}</p>
                </div>
            </div>
        @endif

        {{-- Actions --}}
        @if($payment->status === 'waiting_verification')
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100">
                    <h2 class="font-bold text-slate-800">Aksi Verifikasi</h2>
                </div>
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                        {{-- Verify --}}
                        <form method="POST" action="{{ route('admin.payments.verify', $payment) }}"
                              onsubmit="return confirm('Yakin ingin memverifikasi pembayaran ini? Status workspace akan menjadi Selesai.')">
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
                        Verifikasi akan mengubah status workspace menjadi <strong>Selesai</strong>.
                        Menolak akan mengembalikan status workspace menjadi <strong>Menunggu Pembayaran</strong>.
                    </div>
                </div>
            </div>

            {{-- Modal Reject --}}
            <div id="rejectModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="font-bold text-slate-800">Tolak Pembayaran</h3>
                        <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')"
                                class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center hover:bg-slate-200 transition">
                            <i class="fa-solid fa-xmark text-slate-500"></i>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('admin.payments.reject', $payment) }}" class="p-6 space-y-4">
                        @csrf
                        <div class="flex items-center gap-3 px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                            <i class="fa-solid fa-info-circle"></i>
                            <p class="text-xs font-medium">Pembayaran akan ditolak dan perusahaan dapat mengupload ulang bukti pembayaran.</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Alasan Penolakan (opsional)</label>
                            <textarea name="admin_note" rows="4" maxlength="2000"
                                      placeholder="Jelaskan alasan penolakan..."
                                      class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 resize-none"></textarea>
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
        @if($payment->status === 'paid' && $payment->verifier)
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100">
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

        @if($payment->status === 'rejected' && $payment->verifier)
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100">
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
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Pembayaran
            </a>
        </div>
    </div>
@endsection

