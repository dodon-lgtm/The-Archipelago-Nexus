@extends('layouts.admin')

@section('title', 'Detail Proyek')
@section('breadcrumb', 'Detail Proyek')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.projects.index') }}" class="text-sm text-cyan-600 hover:text-cyan-700 font-semibold inline-flex items-center gap-1">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Proyek
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Info --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">{{ $project->project_name }}</h2>
                        <p class="text-sm text-slate-500 mt-1">oleh {{ $project->owner->name ?? '—' }}</p>
                    </div>
                    <span class="text-xs px-3 py-1.5 rounded-full font-semibold
                        @if($project->status == 'Open') bg-emerald-50 text-emerald-600
                        @else bg-slate-100 text-slate-600 @endif">{{ $project->status }}</span>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4 text-sm">
                    <div><p class="text-xs text-slate-500 font-semibold">Kategori</p><p class="font-semibold">{{ $project->category->name ?? '—' }}</p></div>
                    <div><p class="text-xs text-slate-500 font-semibold">Budget</p><p class="font-semibold">{{ $project->budget ? 'Rp ' . number_format($project->budget) : '—' }}</p></div>
                    <div><p class="text-xs text-slate-500 font-semibold">Deadline</p><p class="font-semibold">{{ $project->deadline ?? '—' }}</p></div>
                    <div><p class="text-xs text-slate-500 font-semibold">Total Penawaran</p><p class="font-semibold">{{ $totalPenawarans }}</p></div>
                </div>
                <div class="mb-4"><p class="text-xs text-slate-500 font-semibold mb-1">Skills Dibutuhkan</p><p class="text-sm">{{ $project->skills ?? '—' }}</p></div>
                <div><p class="text-xs text-slate-500 font-semibold mb-1">Deskripsi</p><p class="text-sm text-slate-700 leading-relaxed">{{ $project->project_description ?? 'Tidak ada deskripsi.' }}</p></div>
            </div>

            {{-- Penawarans --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="font-bold text-slate-800">Penawaran Masuk ({{ $totalPenawarans }})</h2>
                </div>
                <div class="divide-y divide-slate-50">
                    @forelse($project->penawarans as $penawaran)
                        <div class="px-5 py-3 hover:bg-slate-50 transition">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">{{ $penawaran->freelancer->name ?? '—' }}</p>
                                    <p class="text-xs text-slate-500">Rp {{ number_format($penawaran->harga_penawaran) }} • {{ $penawaran->estimasi_hari }} hari</p>
                                </div>
                                <span class="text-xs px-2 py-1 rounded-full font-semibold
                                    @if($penawaran->status == 'Diterima') bg-emerald-50 text-emerald-600
                                    @elseif($penawaran->status == 'Ditolak') bg-red-50 text-red-600
                                    @else bg-amber-50 text-amber-600 @endif">{{ $penawaran->status }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="px-5 py-6 text-center text-sm text-slate-400">Belum ada penawaran.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <h3 class="font-bold text-slate-800 mb-3">Workspace</h3>
                @if($project->workspace)
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between"><span class="text-slate-500">Status</span><span class="font-semibold">{{ $project->workspace->status }}</span></div>
                        <div class="flex justify-between"><span class="text-slate-500">Freelancer</span><span class="font-semibold">{{ $project->workspace->freelancer->name ?? '—' }}</span></div>
                    </div>
                @else
                    <p class="text-sm text-slate-400">Belum ada workspace.</p>
                @endif
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <h3 class="font-bold text-slate-800 mb-3">Lampiran</h3>
                @if($project->image)
                    <div class="mb-2"><p class="text-xs text-slate-500 font-semibold">Gambar</p><a href="{{ asset('storage/' . $project->image) }}" target="_blank" class="text-sm text-cyan-600 hover:underline">Lihat</a></div>
                @endif
                @if($project->attachment)
                    <div><p class="text-xs text-slate-500 font-semibold">File</p><a href="{{ asset('storage/' . $project->attachment) }}" target="_blank" class="text-sm text-cyan-600 hover:underline">Download</a></div>
                @endif
                @if(!$project->image && !$project->attachment)
                    <p class="text-sm text-slate-400">Tidak ada lampiran.</p>
                @endif
            </div>
            <div class="bg-white rounded-2xl border border-red-200 shadow-sm p-5">
                <h3 class="font-bold text-red-600 text-sm mb-1">Hapus Proyek</h3>
                <p class="text-xs text-slate-500 mb-3">Tindakan ini permanen.</p>
                <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" onsubmit="return confirm('Yakin ingin menghapus?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-xl text-sm font-semibold transition"><i class="fa-solid fa-trash-can mr-1"></i> Hapus Proyek</button>
                </form>
            </div>
        </div>
    </div>
@endsection
