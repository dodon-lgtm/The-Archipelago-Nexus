@extends('layouts.admin')

@section('title', 'Detail Penawaran')
@section('breadcrumb', 'Detail Penawaran')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.penawarans.index') }}" class="text-sm text-cyan-600 hover:text-cyan-700 font-semibold inline-flex items-center gap-1">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">Penawaran dari {{ $penawaran->freelancer->name ?? '—' }}</h2>
                        <p class="text-sm text-slate-500 mt-1">Untuk proyek: <span class="font-semibold">{{ $penawaran->project->project_name ?? '—' }}</span></p>
                    </div>
                    <span class="text-xs px-3 py-1.5 rounded-full font-semibold
                        @if($penawaran->status == 'Diterima') bg-emerald-50 text-emerald-600
                        @elseif($penawaran->status == 'Ditolak') bg-red-50 text-red-600
                        @else bg-amber-50 text-amber-600 @endif">{{ $penawaran->status }}</span>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-4 text-sm">
                    <div><p class="text-xs text-slate-500 font-semibold">Harga Penawaran</p><p class="font-bold text-lg text-slate-800">Rp {{ number_format($penawaran->harga_penawaran) }}</p></div>
                    <div><p class="text-xs text-slate-500 font-semibold">Estimasi</p><p class="font-semibold">{{ $penawaran->estimasi_hari }} hari</p></div>
                    <div><p class="text-xs text-slate-500 font-semibold">Tanggal</p><p class="font-semibold">{{ $penawaran->created_at->format('d M Y H:i') }}</p></div>
                </div>
                <div class="mb-4">
                    <p class="text-xs text-slate-500 font-semibold mb-1">Pesan dari Freelancer</p>
                    <div class="bg-slate-50 rounded-xl p-4 text-sm text-slate-700 leading-relaxed">{{ $penawaran->pesan ?? 'Tidak ada pesan.' }}</div>
                </div>
                @if($penawaran->proposal)
                    <div>
                        <p class="text-xs text-slate-500 font-semibold mb-1">File Proposal</p>
                        <a href="{{ asset('storage/' . $penawaran->proposal) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-cyan-50 text-cyan-600 hover:bg-cyan-100 rounded-xl text-sm font-semibold transition">
                            <i class="fa-solid fa-file-pdf"></i> Lihat Proposal
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <h3 class="font-bold text-slate-800 mb-3">Freelancer</h3>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg font-bold">{{ strtoupper(substr($penawaran->freelancer->name ?? '?', 0, 1)) }}</div>
                    <div><p class="font-bold text-slate-800">{{ $penawaran->freelancer->name ?? '—' }}</p><p class="text-xs text-slate-500">{{ $penawaran->freelancer->email ?? '—' }}</p></div>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <h3 class="font-bold text-slate-800 mb-3">Proyek</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-slate-500">Nama</span><span class="font-semibold">{{ $penawaran->project->project_name ?? '—' }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">Company</span><span class="font-semibold">{{ $penawaran->project->owner->name ?? '—' }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">Kategori</span><span class="font-semibold">{{ $penawaran->project->category->name ?? '—' }}</span></div>
                </div>
                <a href="{{ route('admin.projects.show', $penawaran->project) }}" class="mt-3 inline-block text-xs text-cyan-600 hover:text-cyan-700 font-semibold">Lihat Detail Proyek →</a>
            </div>
            @if($penawaran->selected_at)
                <div class="bg-white rounded-2xl border border-emerald-200 shadow-sm p-5">
                    <h3 class="font-bold text-emerald-600 text-sm"><i class="fa-solid fa-circle-check mr-1"></i> Dipilih</h3>
                    <p class="text-xs text-slate-500 mt-1">Pada {{ $penawaran->selected_at->format('d M Y H:i') }}</p>
                </div>
            @endif
        </div>
    </div>
@endsection
