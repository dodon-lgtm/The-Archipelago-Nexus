@extends('admin.layouts.admin')

@section('title', 'Detail Laporan')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.reports.index') }}" class="text-sm text-cyan-600 hover:text-cyan-700 font-semibold mb-2 inline-block">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
        </a>
        <h1 class="text-2xl font-extrabold text-slate-800">Detail Laporan</h1>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="flash-message mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="flash-message mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Info --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">Laporan #{{ $report->id }}</h2>
                        <p class="text-sm text-slate-500 mt-1">oleh {{ $report->reporter->name ?? '—' }}</p>
                    </div>
                    <span class="text-xs px-3 py-1.5 rounded-full font-semibold
                        @if($report->status == 'menunggu') bg-amber-50 text-amber-600
                        @elseif($report->status == 'diproses') bg-blue-50 text-blue-600
                        @elseif($report->status == 'selesai') bg-emerald-50 text-emerald-600
                        @else bg-red-50 text-red-600 @endif">
                        {{ ucfirst($report->status) }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                    <div>
                        <p class="text-xs text-slate-500 font-semibold">Kategori Laporan</p>
                        <p class="font-semibold mt-1">
                            <span class="px-3 py-1.5 rounded-full bg-slate-100 text-slate-700 text-xs">
                                {{ $report->category }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-semibold">Tanggal Dilaporkan</p>
                        <p class="font-semibold mt-1">{{ $report->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>

                <div class="mb-4">
                    <p class="text-xs text-slate-500 font-semibold mb-1">Deskripsi Laporan</p>
                    <div class="bg-slate-50 rounded-xl p-4 text-sm text-slate-700 leading-relaxed">
                        {{ $report->description }}
                    </div>
                </div>

                @if($report->admin_note)
                    <div>
                        <p class="text-xs text-slate-500 font-semibold mb-1">Catatan Admin</p>
                        <div class="bg-cyan-50 rounded-xl p-4 text-sm text-cyan-700 leading-relaxed">
                            {{ $report->admin_note }}
                        </div>
                    </div>
                @endif
            </div>

            {{-- Update Status Form --}}
            @if($report->status !== 'selesai' && $report->status !== 'ditolak')
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <h2 class="font-bold text-slate-800 mb-4">Update Status Laporan</h2>
                    <form method="POST" action="{{ route('admin.reports.update-status', $report) }}">
                        @csrf
                        <div class="mb-4">
                            <label class="text-xs font-semibold text-slate-600 mb-1 block">Status</label>
                            <select name="status" class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 outline-none">
                                <option value="diproses" @selected($report->status == 'diproses')>Diproses</option>
                                <option value="selesai">Selesai / Ditutup</option>
                                <option value="ditolak">Ditolak</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="text-xs font-semibold text-slate-600 mb-1 block">Catatan Admin (opsional)</label>
                            <textarea name="admin_note" rows="3"
                                      class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 outline-none"
                                      placeholder="Tambahkan catatan...">{{ old('admin_note', $report->admin_note) }}</textarea>
                        </div>
                        <button type="submit" class="px-6 py-2.5 bg-cyan-500 hover:bg-cyan-600 text-white rounded-xl text-sm font-semibold transition">
                            <i class="fa-solid fa-check mr-1"></i> Update Status
                        </button>
                    </form>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Reporter Info --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <h3 class="font-bold text-slate-800 mb-3">Informasi Pelapor</h3>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-lg font-bold">
                        {{ strtoupper(substr($report->reporter->name ?? '?', 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-bold text-slate-800">{{ $report->reporter->name ?? '—' }}</p>
                        <p class="text-xs text-slate-500">{{ $report->reporter->email ?? '—' }}</p>
                    </div>
                </div>
                <div class="text-xs text-slate-500">
                    Role: <span class="font-semibold">{{ ucfirst($report->reporter->role ?? '—') }}</span>
                </div>
            </div>

            {{-- Project Info --}}
            @if($report->project)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                    <h3 class="font-bold text-slate-800 mb-3">Proyek Terkait</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Nama</span>
                            <span class="font-semibold">{{ $report->project->project_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Company</span>
                            <span class="font-semibold">{{ $report->project->owner->name ?? '—' }}</span>
                        </div>
                    </div>
                    <a href="{{ route('admin.projects.show', $report->project) }}" class="mt-3 inline-block text-xs text-cyan-600 hover:text-cyan-700 font-semibold">
                        Lihat Detail Proyek →
                    </a>
                </div>
            @endif

            {{-- Handler Info --}}
            @if($report->handler)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                    <h3 class="font-bold text-slate-800 mb-3">Ditangani Oleh</h3>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center font-bold">
                            {{ strtoupper(substr($report->handler->name ?? '?', 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-800">{{ $report->handler->name }}</p>
                            @if($report->handled_at)
                                <p class="text-xs text-slate-500">{{ $report->handled_at->format('d M Y H:i') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

