@extends('layouts.admin')

@section('title', 'Detail Hasil Pekerjaan')
@section('breadcrumb', 'Detail Hasil Pekerjaan')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.hasil-pekerjaan.index') }}" class="text-sm text-cyan-600 hover:text-cyan-700 font-semibold inline-flex items-center gap-1">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">{{ $workspace->project->project_name ?? '—' }}</h2>
                        <p class="text-sm text-slate-500 mt-1">Workspace ID: #{{ $workspace->id }}</p>
                    </div>
                    <span class="text-xs px-3 py-1.5 rounded-full font-semibold
                        @if($workspace->status == 'Selesai') bg-emerald-50 text-emerald-600
                        @elseif($workspace->status == 'Menunggu Revisi') bg-amber-50 text-amber-600
                        @else bg-blue-50 text-blue-600 @endif">{{ $workspace->status }}</span>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                    <div><p class="text-xs text-slate-500 font-semibold">Company</p><p class="font-semibold">{{ $workspace->company->name ?? '—' }}</p></div>
                    <div><p class="text-xs text-slate-500 font-semibold">Freelancer</p><p class="font-semibold">{{ $workspace->freelancer->name ?? '—' }}</p></div>
                    <div><p class="text-xs text-slate-500 font-semibold">Kategori</p><p class="font-semibold">{{ $workspace->project->category->name ?? '—' }}</p></div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100"><h2 class="font-bold text-slate-800">Riwayat Progress</h2></div>
                <div class="divide-y divide-slate-50">
                    @forelse($workspace->progressHistories as $history)
                        <div class="px-5 py-3 hover:bg-slate-50 transition">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs px-2 py-0.5 rounded-full bg-cyan-50 text-cyan-600 font-semibold">{{ $history->stage }}</span>
                                        <span class="text-xs font-bold text-slate-700">{{ $history->progress }}%</span>
                                    </div>
                                    @if($history->description)<p class="text-xs text-slate-500 mt-1">{{ $history->description }}</p>@endif
                                </div>
                                <div class="text-right text-xs text-slate-400">
                                    <p>{{ $history->created_at->format('d M Y H:i') }}</p>
                                    <p>oleh {{ $history->updater->name ?? '—' }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-5 py-6 text-center text-sm text-slate-400">Belum ada riwayat progress.</div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100"><h2 class="font-bold text-slate-800">Pesan ({{ $workspace->messages->count() }})</h2></div>
                <div class="divide-y divide-slate-50 max-h-80 overflow-y-auto">
                    @forelse($workspace->messages as $message)
                        <div class="px-5 py-3 hover:bg-slate-50 transition">
                            <div class="flex items-start gap-3">
                                <div class="w-7 h-7 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">{{ strtoupper(substr($message->sender->name ?? '?', 0, 1)) }}</div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-slate-700">{{ $message->sender->name ?? '—' }}</span>
                                        @if($message->type == 'system')<span class="text-[10px] px-1.5 py-0.5 rounded bg-slate-100 text-slate-500 font-semibold">System</span>@endif
                                        <span class="text-[10px] text-slate-400 ml-auto">{{ $message->created_at->format('d M H:i') }}</span>
                                    </div>
                                    <p class="text-sm text-slate-600 mt-1">{{ $message->message }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-5 py-6 text-center text-sm text-slate-400">Belum ada pesan.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <h3 class="font-bold text-slate-800 mb-3">Progress Saat Ini</h3>
                @if($workspace->latestProgress)
                    <div class="text-center mb-3">
                        <div class="text-4xl font-extrabold @if($workspace->latestProgress->progress >= 100) text-emerald-500 @elseif($workspace->latestProgress->progress >= 50) text-cyan-500 @else text-amber-500 @endif">{{ $workspace->latestProgress->progress }}%</div>
                        <p class="text-xs text-slate-500 mt-1">Tahap: {{ $workspace->latestProgress->stage }}</p>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-500 @if($workspace->latestProgress->progress >= 100) bg-emerald-500 @elseif($workspace->latestProgress->progress >= 50) bg-cyan-500 @else bg-amber-500 @endif" style="width: {{ $workspace->latestProgress->progress }}%"></div>
                    </div>
                @else
                    <p class="text-sm text-slate-400 text-center">Belum ada progress.</p>
                @endif
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <h3 class="font-bold text-slate-800 mb-3">Detail Proyek</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-slate-500">Budget</span><span class="font-semibold">{{ $workspace->project->budget ? 'Rp ' . number_format($workspace->project->budget) : '—' }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">Deadline</span><span class="font-semibold">{{ $workspace->project->deadline ?? '—' }}</span></div>
                </div>
                <a href="{{ route('admin.projects.show', $workspace->project) }}" class="mt-3 inline-block text-xs text-cyan-600 hover:text-cyan-700 font-semibold">Lihat Detail →</a>
            </div>
        </div>
    </div>
@endsection
