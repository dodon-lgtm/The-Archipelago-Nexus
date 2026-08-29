@extends('layouts.admin')

@section('title', 'Detail Hasil Pekerjaan')
@section('breadcrumb', 'Detail Hasil Pekerjaan')

@section('content')
    <div class="mb-4 flex flex-col gap-2">
        <nav class="flex items-center gap-1.5 text-xs text-slate-400 min-w-0" aria-label="Breadcrumb">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition shrink-0">Admin</a>
            <i class="fa-solid fa-chevron-right text-[9px] shrink-0"></i>
            <a href="{{ route('admin.hasil-pekerjaan.index') }}" class="hover:text-blue-600 transition shrink-0">Hasil Pekerjaan</a>
            <i class="fa-solid fa-chevron-right text-[9px] shrink-0"></i>
            <span class="text-slate-600 dark:text-slate-300 font-medium truncate">Workspace #{{ $workspace->id }}</span>
        </nav>
        <a href="{{ route('admin.hasil-pekerjaan.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-semibold inline-flex items-center gap-1.5 transition w-fit">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Workspace
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-blue-100 shadow-sm p-6">
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
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 pt-4 border-t border-blue-50 dark:border-slate-800">
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400 mb-1.5">Company</p>
                        @if ($workspace->company)
                            <x-admin.user-cell :user="$workspace->company" with-photo size="md"
                                :sub="$workspace->company->role ? ucfirst($workspace->company->role) : null" />
                        @else
                            <p class="text-sm text-slate-400">—</p>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400 mb-1.5">Freelancer</p>
                        @if ($workspace->freelancer)
                            <x-admin.user-cell :user="$workspace->freelancer" with-photo size="md"
                                :sub="$workspace->freelancer->role ? ucfirst($workspace->freelancer->role) : null"
                                avatar-class="bg-gradient-to-br from-emerald-100 to-emerald-50 ring-1 ring-emerald-100 text-emerald-600" />
                        @else
                            <p class="text-sm text-slate-400">—</p>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400 mb-1.5">Kategori</p>
                        <p class="text-sm font-semibold text-slate-800 dark:text-white truncate">{{ $workspace->project->category->name ?? '—' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-blue-50"><h2 class="font-bold text-slate-800">Riwayat Progress</h2></div>
                <div class="divide-y divide-slate-50">
                    @forelse($workspace->progressHistories as $history)
                        <div class="px-5 py-3 hover:bg-[#f6f9ff] transition">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs px-2 py-0.5 rounded-full bg-blue-50 text-blue-600 font-semibold">{{ $history->stage }}</span>
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

            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-blue-100 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-blue-50 dark:border-slate-800"><h2 class="font-bold text-slate-800 dark:text-white">Pesan ({{ $workspace->messages->count() }})</h2></div>
                <div class="divide-y divide-slate-50 dark:divide-slate-800 max-h-80 overflow-y-auto">
                    @forelse($workspace->messages as $message)
                        <div class="px-5 py-3 hover:bg-[#f6f9ff] dark:hover:bg-slate-800/50 transition">
                            <div class="flex items-start gap-3">
                                <div class="w-7 h-7 rounded-full bg-blue-50 dark:bg-blue-900/40 text-slate-600 dark:text-slate-300 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">{{ strtoupper(substr($message->sender->name ?? '?', 0, 1)) }}</div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        @if ($message->sender)
                                            <a href="{{ route('admin.users.show', $message->sender) }}"
                                                class="text-xs font-bold text-slate-700 dark:text-slate-200 hover:text-blue-600 dark:hover:text-blue-400 transition">{{ $message->sender->name ?? '—' }}</a>
                                        @else
                                            <span class="text-xs font-bold text-slate-700 dark:text-slate-200">—</span>
                                        @endif
                                        @if($message->type == 'system')<span class="text-[10px] px-1.5 py-0.5 rounded bg-blue-50 dark:bg-blue-900/40 text-slate-500 dark:text-slate-400 font-semibold">System</span>@endif
                                        <span class="text-[10px] text-slate-400 dark:text-slate-500 ml-auto">{{ $message->created_at->format('d M H:i') }}</span>
                                    </div>
                                    <p class="text-sm text-slate-600 dark:text-slate-300 mt-1 break-words">{{ $message->message }}</p>
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
            <div class="bg-white rounded-2xl border border-blue-100 shadow-sm p-5">
                <h3 class="font-bold text-slate-800 mb-3">Progress Saat Ini</h3>
                @if($workspace->latestProgress)
                    <div class="text-center mb-3">
                        <div class="text-4xl font-extrabold @if($workspace->latestProgress->progress >= 100) text-emerald-500 @elseif($workspace->latestProgress->progress >= 50) text-blue-500 @else text-amber-500 @endif">{{ $workspace->latestProgress->progress }}%</div>
                        <p class="text-xs text-slate-500 mt-1">Tahap: {{ $workspace->latestProgress->stage }}</p>
                    </div>
                    <div class="w-full bg-blue-50 rounded-full h-3 overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-500 @if($workspace->latestProgress->progress >= 100) bg-emerald-500 @elseif($workspace->latestProgress->progress >= 50) bg-blue-500 @else bg-amber-500 @endif" style="width: {{ $workspace->latestProgress->progress }}%"></div>
                    </div>
                @else
                    <p class="text-sm text-slate-400 text-center">Belum ada progress.</p>
                @endif
            </div>
            @if ($workspace->payment)
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-blue-100 dark:border-slate-800 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-blue-50 dark:border-slate-800 flex items-center justify-between gap-3">
                        <h3 class="font-bold text-slate-800 dark:text-white text-sm flex items-center gap-2">
                            <i class="fa-solid fa-credit-card text-blue-400 text-xs"></i> Pembayaran
                        </h3>
                        <span class="text-[10px] font-bold px-2.5 py-1 rounded-full border shrink-0 {{ $workspace->payment->status_color }}">{{ $workspace->payment->status_label }}</span>
                    </div>
                    <div class="p-5 space-y-2.5 text-sm">
                        <div class="flex justify-between gap-3">
                            <span class="text-slate-500 dark:text-slate-400 shrink-0">Invoice</span>
                            <span class="font-semibold text-slate-800 dark:text-white text-right">{{ $workspace->payment->invoice_number }}</span>
                        </div>
                        <div class="flex justify-between gap-3">
                            <span class="text-slate-500 dark:text-slate-400 shrink-0">Nominal</span>
                            <span class="font-bold text-slate-800 dark:text-white">Rp {{ number_format($workspace->payment->amount, 0, ',', '.') }}</span>
                        </div>
                        @if ($workspace->payment->funds_status !== 'not_applicable')
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-slate-500 dark:text-slate-400 shrink-0">Status Dana</span>
                                <span class="text-[10px] font-bold px-2.5 py-1 rounded-full border {{ $workspace->payment->funds_status_color }}">{{ $workspace->payment->funds_status_label }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between gap-3">
                            <span class="text-slate-500 dark:text-slate-400 shrink-0">Diverifikasi</span>
                            <span class="font-semibold text-slate-800 dark:text-white">{{ optional($workspace->payment->verified_at)->format('d M Y H:i') ?? '—' }}</span>
                        </div>
                        <a href="{{ route('admin.payments.show', $workspace->payment) }}"
                            class="mt-1 inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-700 transition">
                            Lihat Detail Pembayaran <i class="fa-solid fa-arrow-right text-[9px]"></i>
                        </a>
                    </div>
                </div>
            @endif

            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-blue-100 dark:border-slate-800 shadow-sm p-5">
                <h3 class="font-bold text-slate-800 dark:text-white mb-3">Detail Proyek</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between gap-3"><span class="text-slate-500 dark:text-slate-400 shrink-0">Budget</span><span class="font-semibold text-slate-800 dark:text-white">{{ $workspace->project->budget ? 'Rp ' . number_format($workspace->project->budget) : '—' }}</span></div>
                    <div class="flex justify-between gap-3"><span class="text-slate-500 dark:text-slate-400 shrink-0">Deadline</span><span class="font-semibold text-slate-800 dark:text-white text-right">{{ $workspace->project->deadline ? \Illuminate\Support\Carbon::parse($workspace->project->deadline)->translatedFormat('d M Y') : '—' }}</span></div>
                </div>
                <a href="{{ route('admin.projects.show', $workspace->project) }}" class="mt-3 inline-block text-xs text-blue-600 hover:text-blue-700 font-semibold">Lihat Detail →</a>
            </div>
        </div>
    </div>
@endsection
