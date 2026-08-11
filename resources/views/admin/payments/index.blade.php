@extends('layouts.admin')

@section('title', 'Pembayaran')
@section('breadcrumb', 'Pembayaran')

@section('content')
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i class="fa-solid fa-credit-card"></i>
                </div>
                <div>
                    <h2 class="font-bold text-slate-800">Daftar Pembayaran</h2>
                    <p class="text-xs text-slate-500">Kelola verifikasi pembayaran proyek</p>
                </div>
            </div>
            <span class="text-xs px-3 py-1.5 rounded-full bg-slate-100 text-slate-600 font-semibold">
                Total: {{ $payments->total() }}
            </span>
        </div>

        @if(session('success'))
            <div class="mx-6 mt-4 flex items-center gap-3 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium">
                <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mx-6 mt-4 flex items-center gap-3 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm font-medium">
                <i class="fa-solid fa-xmark-circle"></i> {{ session('error') }}
            </div>
        @endif

        <div class="p-6">
            @if($payments->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                                <th class="pb-3 pr-4">Invoice</th>
                                <th class="pb-3 pr-4">Perusahaan</th>
                                <th class="pb-3 pr-4">Freelancer</th>
                                <th class="pb-3 pr-4">Project</th>
                                <th class="pb-3 pr-4">Nominal</th>
                                <th class="pb-3 pr-4">Status</th>
                                <th class="pb-3 pr-4">Tanggal</th>
                                <th class="pb-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                                <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition">
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
                                        <span class="text-xs text-slate-400">{{ $payment->created_at->format('d M Y') }}</span>
                                    </td>
                                    <td class="py-3">
                                        <a href="{{ route('admin.payments.show', $payment) }}"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-brand/10 text-brand rounded-lg text-[10px] font-semibold hover:bg-brand hover:text-white transition">
                                            <i class="fa-solid fa-eye"></i> Lihat
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if(method_exists($payments, 'links'))
                    <div class="mt-6">{{ $payments->links() }}</div>
                @endif
            @else
                <div class="py-16 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-slate-100 rounded-2xl flex items-center justify-center">
                        <i class="fa-solid fa-credit-card text-2xl text-slate-400"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-600">Belum Ada Pembayaran</h3>
                    <p class="text-xs text-slate-400 mt-1">Belum ada data pembayaran yang masuk.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
