@extends('layouts.admin')

@section('title', 'Detail Permintaan Akun Perusahaan')
@section('breadcrumb', 'Detail Permintaan')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.company-account-requests.index') }}" class="text-sm text-cyan-600 hover:text-cyan-700 font-semibold inline-flex items-center gap-1">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h2 class="text-lg font-bold text-slate-800 mb-4">Data Permintaan</h2>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div class="md:col-span-2">
                        <dt class="text-xs text-slate-500 font-semibold">Nama Perusahaan</dt>
                        <dd class="font-semibold text-slate-800 mt-1">{{ $companyRequest->company_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-500 font-semibold">Nama Penanggung Jawab</dt>
                        <dd class="font-semibold text-slate-800 mt-1">{{ $companyRequest->contact_person }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-500 font-semibold">Email Perusahaan</dt>
                        <dd class="font-semibold text-slate-800 mt-1">{{ $companyRequest->company_email }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-500 font-semibold">Nomor Telepon</dt>
                        <dd class="font-semibold text-slate-800 mt-1">{{ $companyRequest->company_phone }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-500 font-semibold">Tanggal Permintaan</dt>
                        <dd class="font-semibold text-slate-800 mt-1">{{ $companyRequest->created_at?->format('Y-m-d H:i') }}</dd>
                    </div>
                    <div class="md:col-span-2">
                        <dt class="text-xs text-slate-500 font-semibold">Alamat Perusahaan</dt>
                        <dd class="font-semibold text-slate-800 mt-1">{{ $companyRequest->company_address }}</dd>
                    </div>
                    <div class="md:col-span-2">
                        <dt class="text-xs text-slate-500 font-semibold">Deskripsi Singkat</dt>
                        <dd class="text-slate-700 mt-1">{{ $companyRequest->company_description ?? 'Tidak ada deskripsi.' }}</dd>
                    </div>
                    <div class="md:col-span-2">
                        <dt class="text-xs text-slate-500 font-semibold">Catatan Admin</dt>
                        <dd class="text-slate-700 mt-1">{{ $companyRequest->note ?? '—' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <h3 class="font-bold text-slate-800 mb-3">Status Permintaan</h3>
                <div class="text-center">
                    @if($companyRequest->request_status==='menunggu')
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-50 text-amber-600 font-semibold text-sm">
                            <i class="fa-solid fa-clock"></i> Menunggu
                        </span>
                    @elseif($companyRequest->request_status==='disetujui')
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-50 text-emerald-600 font-semibold text-sm">
                            <i class="fa-solid fa-circle-check"></i> Disetujui
                        </span>
                    @else
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-red-50 text-red-600 font-semibold text-sm">
                            <i class="fa-solid fa-circle-xmark"></i> Ditolak
                        </span>
                    @endif
                </div>
            </div>

            @if($companyRequest->request_status==='menunggu')
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                    <h3 class="font-bold text-emerald-600 text-sm mb-3"><i class="fa-solid fa-check mr-1"></i> Setujui Permintaan</h3>
                    <form method="POST" action="{{ route('admin.company-account-requests.approve', ['companyRequest' => $companyRequest->id]) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="text-xs font-semibold text-slate-600 mb-1 block">Catatan (opsional)</label>
                            <textarea class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 outline-none" id="note" name="note" rows="2">{{ old('note') }}</textarea>
                        </div>
                        <button class="w-full px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-sm font-semibold transition" type="submit">Konfirmasi Setujui</button>
                    </form>
                </div>

                <div class="bg-white rounded-2xl border border-red-200 shadow-sm p-5">
                    <h3 class="font-bold text-red-600 text-sm mb-3"><i class="fa-solid fa-xmark mr-1"></i> Tolak Permintaan</h3>
                    <form method="POST" action="{{ route('admin.company-account-requests.reject', ['companyRequest' => $companyRequest->id]) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="text-xs font-semibold text-slate-600 mb-1 block">Alasan Penolakan <span class="text-red-500">*</span></label>
                            <textarea class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 outline-none" name="note" rows="2" required>{{ old('note') }}</textarea>
                        </div>
                        <button class="w-full px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-xl text-sm font-semibold transition" type="submit">Konfirmasi Tolak</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
