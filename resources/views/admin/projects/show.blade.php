@extends('layouts.admin')

@section('title', 'Detail Proyek')
@section('breadcrumb', 'Detail Proyek')

@php($status = $project->status ?? 'open')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.projects.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-semibold inline-flex items-center gap-1.5 transition">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Proyek
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Info --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden">
                <div class="bg-gradient-to-r from-[#f6f9ff] to-white border-b border-blue-100 p-6">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-briefcase"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-slate-800 leading-tight">{{ $project->project_name }}</h2>
                                <p class="text-sm text-slate-500 mt-1">
                                    oleh <span class="font-semibold text-slate-700">{{ $project->owner->name ?? '—' }}</span>
                                </p>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1.5 self-start px-3 py-1.5 rounded-full border text-xs font-semibold
                            @if($status == 'open') bg-emerald-50 text-emerald-600 border-emerald-100
                            @elseif($status == 'closed') bg-red-50 text-red-600 border-red-100
                            @elseif($status == 'archived') bg-amber-50 text-amber-600 border-amber-100
                            @else bg-blue-50 text-slate-600 border-blue-100 @endif">
                            <span class="w-1.5 h-1.5 rounded-full
                                @if($status == 'open') bg-emerald-500
                                @elseif($status == 'closed') bg-red-500
                                @elseif($status == 'archived') bg-amber-500
                                @else bg-blue-400 @endif"></span>
                            {{ \App\Models\Project::statusLabel($status) }}
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
                        <div class="rounded-xl border border-slate-100 bg-slate-50/60 p-3">
                            <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400 mb-1"><i class="fa-solid fa-tag mr-1"></i>Kategori</p>
                            <p class="font-semibold text-sm text-slate-800 truncate">{{ $project->category->name ?? '—' }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-100 bg-slate-50/60 p-3">
                            <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400 mb-1"><i class="fa-solid fa-wallet mr-1"></i>Budget</p>
                            <p class="font-semibold text-sm text-slate-800">{{ $project->budget ? 'Rp ' . number_format($project->budget) : '—' }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-100 bg-slate-50/60 p-3">
                            <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400 mb-1"><i class="fa-regular fa-calendar mr-1"></i>Deadline</p>
                            <p class="font-semibold text-sm text-slate-800 whitespace-nowrap">@if($project->deadline){{ \Illuminate\Support\Carbon::parse($project->deadline)->format('d M Y') }}@else — @endif</p>
                        </div>
                        <div class="rounded-xl border border-blue-100 bg-blue-50/60 p-3">
                            <p class="text-[10px] font-bold uppercase tracking-wide text-blue-400 mb-1"><i class="fa-solid fa-inbox mr-1"></i>Total Penawaran</p>
                            <p class="font-bold text-sm text-blue-700">{{ $totalPenawarans }}</p>
                        </div>
                    </div>

                    <div class="mb-5">
                        <p class="text-[11px] font-bold uppercase tracking-wide text-slate-400 mb-2">Skills Dibutuhkan</p>
                        <p class="text-sm text-slate-700">{{ $project->skills ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wide text-slate-400 mb-2">Deskripsi</p>
                        <p class="text-sm text-slate-700 leading-relaxed">{{ $project->project_description ?? 'Tidak ada deskripsi.' }}</p>
                    </div>
                </div>
            </div>

            {{-- Penawarans --}}
            <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-blue-50 flex items-center justify-between">
                    <h2 class="font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-comments text-blue-400 text-sm"></i> Penawaran Masuk
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-blue-50 border border-blue-100 text-blue-600">{{ $totalPenawarans }}</span>
                    </h2>
                </div>
                <div class="divide-y divide-slate-50">
                    @forelse($project->penawarans as $penawaran)
                        <div class="px-5 py-3.5 hover:bg-[#f6f9ff]/70 transition-colors">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-100 to-blue-50 ring-1 ring-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold shrink-0">
                                        {{ strtoupper(substr(($penawaran->freelancer->name ?? '?'), 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-slate-800 truncate">{{ $penawaran->freelancer->name ?? '—' }}</p>
                                        <p class="text-xs text-slate-500 whitespace-nowrap">Rp {{ number_format($penawaran->harga_penawaran) }} • {{ $penawaran->estimasi_hari }} hari</p>
                                    </div>
                                </div>
                                <span class="text-xs px-2.5 py-1 rounded-full border font-semibold shrink-0
                                    @if($penawaran->status == 'Diterima') bg-emerald-50 text-emerald-600 border-emerald-100
                                    @elseif($penawaran->status == 'Ditolak') bg-red-50 text-red-600 border-red-100
                                    @else bg-amber-50 text-amber-600 border-amber-100 @endif">{{ $penawaran->status }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="px-5 py-10 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-11 h-11 rounded-xl bg-blue-50 border border-blue-100 text-blue-400 flex items-center justify-center">
                                    <i class="fa-solid fa-inbox"></i>
                                </div>
                                <p class="text-sm text-slate-400">Belum ada penawaran.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-blue-100 shadow-sm p-5">
                <h3 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-diagram-project text-blue-400 text-sm"></i> Workspace
                </h3>
                @if($project->workspace)
                    <div class="space-y-2.5 text-sm">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500">Status</span>
                            <span class="font-semibold px-2 py-0.5 rounded-full bg-blue-50 border border-blue-100 text-blue-600 text-xs">{{ $project->workspace->status }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500">Freelancer</span>
                            <span class="font-semibold text-right">{{ $project->workspace->freelancer->name ?? '—' }}</span>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-slate-400 italic">Belum ada workspace.</p>
                @endif
            </div>
            <div class="bg-white rounded-2xl border border-blue-100 shadow-sm p-5">
                <h3 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-paperclip text-blue-400 text-sm"></i> Lampiran
                </h3>
                @if($project->image || $project->attachment)
                    <div class="grid grid-cols-1 gap-2.5">
                        @if($project->image)
                            <a href="{{ asset('storage/' . $project->image) }}" target="_blank"
                               class="flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50/60 hover:border-blue-200 hover:bg-blue-50/60 p-3 transition group">
                                <div class="w-9 h-9 rounded-lg bg-blue-50 border border-blue-100 text-blue-500 flex items-center justify-center shrink-0 group-hover:bg-blue-100 transition">
                                    <i class="fa-regular fa-image"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold uppercase tracking-wide text-slate-400">Gambar</p>
                                    <p class="text-sm font-semibold text-blue-600 truncate">Lihat Gambar <i class="fa-solid fa-arrow-up-right-from-square text-[10px] ml-0.5"></i></p>
                                </div>
                            </a>
                        @endif
                        @if($project->attachment)
                            <a href="{{ asset('storage/' . $project->attachment) }}" target="_blank"
                               class="flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50/60 hover:border-blue-200 hover:bg-blue-50/60 p-3 transition group">
                                <div class="w-9 h-9 rounded-lg bg-blue-50 border border-blue-100 text-blue-500 flex items-center justify-center shrink-0 group-hover:bg-blue-100 transition">
                                    <i class="fa-solid fa-file-arrow-down"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold uppercase tracking-wide text-slate-400">File</p>
                                    <p class="text-sm font-semibold text-blue-600 truncate">Download File <i class="fa-solid fa-download text-[10px] ml-0.5"></i></p>
                                </div>
                            </a>
                        @endif
                    </div>
                @else
                    <div class="flex items-center gap-2.5 rounded-xl border border-dashed border-slate-200 bg-slate-50/60 p-3">
                        <i class="fa-regular fa-folder-open text-slate-300"></i>
                        <p class="text-sm text-slate-400 italic">Tidak ada lampiran.</p>
                    </div>
                @endif
            </div>

            {{-- Zona Berbahaya --}}
            <div class="rounded-2xl border-2 border-dashed border-red-200 bg-red-50/40 p-5">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-7 h-7 rounded-lg bg-red-100 text-red-500 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                    </div>
                    <h3 class="font-bold text-red-600 text-sm">Zona Berbahaya</h3>
                </div>
                <p class="text-xs text-slate-500 mb-4 ml-9">Menghapus proyek bersifat permanen dan tidak dapat dibatalkan.</p>
                <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" onsubmit="return adminConfirm('Yakin ingin menghapus?', this)">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2.5 bg-red-500 hover:bg-red-600 active:bg-red-700 text-white rounded-xl text-sm font-semibold shadow-sm transition"><i class="fa-solid fa-trash-can mr-1.5"></i> Hapus Proyek</button>
                </form>
            </div>
        </div>
    </div>
@endsection
