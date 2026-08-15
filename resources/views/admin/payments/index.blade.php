@extends('layouts.admin')

@section('title', 'Pembayaran')
@section('breadcrumb', 'Pembayaran')

@section('content')
    <div class="bg-white border border-blue-100 rounded-2xl shadow-sm overflow-hidden print:border-none print:shadow-none">
        
        {{-- HEADER DAFTAR PEMBAYARAN --}}
        <div class="px-6 py-5 border-b border-blue-50 flex items-center justify-between print:border-b-2 print:border-slate-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center print:hidden">
                    <i class="fa-solid fa-credit-card"></i>
                </div>
                <div>
                    <h2 class="font-bold text-slate-800 text-lg">Daftar Pembayaran</h2>
                    <p class="text-xs text-slate-500">Kelola verifikasi pembayaran proyek</p>
                </div>
            </div>

            {{-- TOMBOL CETAK SEMUA & TOTAL --}}
            <div class="flex items-center gap-2.5 print:hidden">
              <a href="{{ route('admin.payments.pdf.all') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
    </svg>
    Cetak Semua
</a>

                <span class="text-xs px-3 py-2 rounded-full bg-blue-50 text-slate-600 font-semibold">
                    Total: {{ $payments->total() }}
                </span>
            </div>
        </div>

        {{-- ALERT NOTIFIKASI --}}
        @if(session('success'))
            <div class="mx-6 mt-4 flex items-center gap-3 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium print:hidden">
                <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mx-6 mt-4 flex items-center gap-3 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm font-medium print:hidden">
                <i class="fa-solid fa-xmark-circle"></i> {{ session('error') }}
            </div>
        @endif

        {{-- TABEL PEMBAYARAN --}}
        <div class="p-6">
            @if($payments->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm print:text-xs">
                        <thead>
                            <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-blue-50 print:border-slate-300">
                                <th class="pb-3 pr-4">Invoice</th>
                                <th class="pb-3 pr-4">Perusahaan</th>
                                <th class="pb-3 pr-4">Freelancer</th>
                                <th class="pb-3 pr-4">Project</th>
                                <th class="pb-3 pr-4">Nominal</th>
                                <th class="pb-3 pr-4">Status</th>
                                <th class="pb-3 pr-4">Tanggal</th>
                                <th class="pb-3 print:hidden">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                                <tr class="border-b border-slate-50 hover:bg-[#f6f9ff]/50 transition print:border-slate-200">
                                    <td class="py-3 pr-4">
                                        <span class="font-bold text-xs text-slate-700">{{ $payment->invoice_number }}</span>
                                    </td>
                                    <td class="py-3 pr-4">
                                        <span class="text-xs text-slate-600">{{ $payment->company->name ?? '-' }}</span>
                                    </td>
                                    <td class="py-3 pr-4">
                                        <span class="text-xs text-slate-600">{{ $payment->freelancer->name ?? '-' }}</span>
                                    </td>
                                    <td class="py-3 pr-4">
                                        <span class="text-xs text-slate-600">{{ $payment->workspace->project->project_name ?? '-' }}</span>
                                    </td>
                                    <td class="py-3 pr-4">
                                        <span class="text-xs font-semibold text-slate-700">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="py-3 pr-4">
                                        <span class="text-[10px] font-bold px-2.5 py-1 rounded-full border {{ $payment->status_color }}">
                                            {{ $payment->status_label }}
                                        </span>
                                    </td>
                                    <td class="py-3 pr-4">
                                        <span class="text-xs text-slate-400 print:text-slate-700">{{ $payment->created_at->format('d M Y') }}</span>
                                    </td>
                                    
                                    {{-- AKSI SATUAN --}}
                                  <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
    <div class="flex items-center gap-2">
        {{-- Tombol Lihat Detail --}}
        <a href="{{ route('admin.payments.show', $payment->id) }}" 
           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-xs font-semibold">
            👁 Lihat
        </a>

        {{-- Tombol Cetak Struk PDF --}}
        <a href="{{ route('admin.payments.pdf.single', $payment->id) }}" 
           target="_blank" 
           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition text-xs font-semibold">
            🖨 Cetak
        </a>
    </div>
</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if(method_exists($payments, 'links'))
                    <div class="mt-6 print:hidden">{{ $payments->links() }}</div>
                @endif
            @else
                <div class="py-16 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-blue-50 rounded-2xl flex items-center justify-center">
                        <i class="fa-solid fa-credit-card text-2xl text-slate-400"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-600">Belum Ada Pembayaran</h3>
                    <p class="text-xs text-slate-400 mt-1">Belum ada data pembayaran yang masuk.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- CSS STYLE KHUSUS PRINT --}}
    <style>
        @media print {
            /* Sembunyikan sidebar admin, navbar, dan tombol aksi saat mencetak halaman */
            aside, header, nav, .print\:hidden {
                display: none !important;
            }

            body, main {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
            }

            .shadow-sm, .rounded-2xl {
                box-shadow: none !important;
                border-radius: 0 !important;
            }
        }
    </style>
@endsection