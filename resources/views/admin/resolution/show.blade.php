@extends('layouts.admin')

@section('title', 'Workspace Resolution')
@section('breadcrumb', 'Workspace Resolution')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.hasil-pekerjaan.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-semibold inline-flex items-center gap-1.5 transition">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Workspace
        </a>
    </div>

    {{-- Header Ringkas --}}
    <div class="bg-white dark:bg-slate-900 dark:border-slate-800 rounded-2xl border border-blue-100 shadow-sm p-5 mb-6 transition-colors duration-300">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="w-11 h-11 rounded-xl bg-blue-50 dark:bg-slate-800 border border-blue-100 dark:border-slate-700 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-scale-balanced"></i>
                </div>
                <div class="min-w-0">
                    <h1 class="text-lg font-bold text-slate-800 dark:text-white leading-tight truncate">Resolusi Workspace #{{ $workspace->id }}</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $workspace->project->project_name ?? '—' }}</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-full border font-semibold bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-300 border-blue-100 dark:border-blue-900">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> {{ ucfirst($workspace->status ?? '—') }}
                </span>
                @if($payment)
                    <span class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-full border font-semibold {{ $payment->getFundsStatusColorAttribute() }}">
                        <i class="fa-solid fa-vault text-[10px]"></i> {{ $payment->getFundsStatusLabelAttribute() }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    @if($canRelease || $canRefund)
        {{-- Keputusan Dana Escrow --}}
        <div class="rounded-2xl border-2 border-dashed border-emerald-200 dark:border-emerald-900/60 bg-emerald-50/40 dark:bg-emerald-950/30 p-6 mb-6">
            <div class="flex items-center gap-2.5 mb-4">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-hand-holding-dollar text-xs"></i>
                </div>
                <div>
                    <h2 class="font-bold text-slate-800 dark:text-white leading-tight">Keputusan Dana Escrow</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Aksi tercatat di ledger dan tidak dapat dibatalkan.</p>
                </div>
            </div>

            @if($payment && $payment->isFundsHeld())
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
                    Dana sedang tertahan. Admin dapat memutuskan merilis ke Freelancer atau merefund ke Company.
                </p>

                <div class="grid grid-cols-1 @if($canRelease && $canRefund) md:grid-cols-2 @endif gap-4">
                    {{-- Release to Freelancer --}}
                    @if($canRelease)
                        <form method="POST" action="{{ route('admin.workspace.resolution.decide', $workspace) }}"
                          onsubmit="return adminConfirm('Yakin merilis dana ke Freelancer? Aksi tercatat di ledger dan tidak dapat dibatalkan.', this)"
                          class="bg-white dark:bg-slate-900 dark:border-slate-800 rounded-xl border border-emerald-100 dark:border-emerald-900/60 p-4 shadow-sm">
                            @csrf
                            @method('POST')
                            <input type="hidden" name="action" value="release_to_freelancer">
                            <label class="text-xs font-bold uppercase tracking-wide text-emerald-600 dark:text-emerald-400 mb-2 block">Release ke Freelancer</label>
                            <textarea name="reason" rows="3" required placeholder="Alasan keputusan (wajib)..."
                              class="w-full rounded-xl border-blue-100 dark:border-slate-700 bg-[#f6f9ff] dark:bg-slate-800 dark:text-white px-4 py-2.5 text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none transition"></textarea>
                            <div class="mt-3">
                                <button type="submit"
                                        class="w-full px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700 text-white rounded-xl text-sm font-semibold shadow-sm transition">
                                    <i class="fa-solid fa-hand-holding-dollar mr-1"></i> Release ke Freelancer
                                </button>
                            </div>
                        </form>
                    @endif

                    {{-- Refund to Company --}}
                    @if($canRefund)
                        <form method="POST" action="{{ route('admin.workspace.resolution.decide', $workspace) }}"
                          onsubmit="return adminConfirm('Yakin merefund dana ke Company? Aksi tercatat di ledger dan tidak dapat dibatalkan.', this)"
                          class="bg-white dark:bg-slate-900 dark:border-slate-800 rounded-xl border border-red-100 dark:border-red-900/60 p-4 shadow-sm">
                            @csrf
                            @method('POST')
                            <input type="hidden" name="action" value="refund_to_company">
                            <label class="text-xs font-bold uppercase tracking-wide text-red-600 dark:text-red-400 mb-2 block">Refund ke Company</label>
                            <textarea name="reason" rows="3" required placeholder="Alasan keputusan (wajib)..."
                              class="w-full rounded-xl border-blue-100 dark:border-slate-700 bg-[#f6f9ff] dark:bg-slate-800 dark:text-white px-4 py-2.5 text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none transition"></textarea>
                            <div class="mt-3">
                                <button type="submit"
                                        class="w-full px-4 py-2.5 bg-red-500 hover:bg-red-600 active:bg-red-700 text-white rounded-xl text-sm font-semibold shadow-sm transition">
                                    <i class="fa-solid fa-rotate-left mr-1"></i> Refund ke Company
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            @endif
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-6">
        {{-- Workspace Info --}}
        <div class="bg-white dark:bg-slate-900 dark:border-slate-800 rounded-2xl border border-blue-100 shadow-sm overflow-hidden transition-colors duration-300">
            <div class="px-5 py-4 border-b border-blue-50 dark:border-slate-800 flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-slate-800 text-blue-500 dark:text-blue-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-diagram-project text-xs"></i>
                </div>
                <h2 class="font-bold text-slate-800 dark:text-white">Informasi Workspace</h2>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                <div class="rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/40 p-3">
                    <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400 dark:text-slate-500 mb-1">Project</p>
                    <p class="font-semibold text-slate-800 dark:text-white truncate">{{ $workspace->project->project_name ?? '—' }}</p>
                </div>
                <div class="rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/40 p-3">
                    <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400 dark:text-slate-500 mb-1">Company</p>
                    <p class="font-semibold text-slate-800 dark:text-white truncate">{{ $workspace->company->name ?? '—' }}</p>
                </div>
                <div class="rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/40 p-3">
                    <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400 dark:text-slate-500 mb-1">Freelancer</p>
                    <p class="font-semibold text-slate-800 dark:text-white truncate">{{ $workspace->freelancer->name ?? '—' }}</p>
                </div>
                <div class="rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/40 p-3">
                    <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400 dark:text-slate-500 mb-1">Workspace ID</p>
                    <p class="font-semibold text-slate-800 dark:text-white">#{{ $workspace->id }}</p>
                </div>
                <div class="rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/40 p-3">
                    <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400 dark:text-slate-500 mb-1">Created at</p>
                    <p class="font-semibold text-slate-800 dark:text-white whitespace-nowrap">{{ $workspace->created_at->format('d M Y H:i') }}</p>
                </div>
                <div class="rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/40 p-3">
                    <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400 dark:text-slate-500 mb-1">Updated at</p>
                    <p class="font-semibold text-slate-800 dark:text-white whitespace-nowrap">{{ $workspace->updated_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>

        {{-- Payment Info --}}
        <div class="bg-white dark:bg-slate-900 dark:border-slate-800 rounded-2xl border border-blue-100 shadow-sm overflow-hidden transition-colors duration-300">
            <div class="px-5 py-4 border-b border-blue-50 dark:border-slate-800 flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-slate-800 text-emerald-500 dark:text-emerald-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-file-invoice-dollar text-xs"></i>
                </div>
                <h2 class="font-bold text-slate-800 dark:text-white">Informasi Payment</h2>
            </div>
            <div class="p-5">
                @if($payment)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <div class="rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/40 p-3 sm:col-span-2">
                            <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400 dark:text-slate-500 mb-1">Invoice</p>
                            <p class="font-semibold text-slate-800 dark:text-white">{{ $payment->invoice_number }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/40 p-3">
                            <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400 dark:text-slate-500 mb-1">Amount</p>
                            <p class="font-bold text-slate-800 dark:text-white">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/40 p-3">
                            <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400 dark:text-slate-500 mb-1">Platform Fee</p>
                            <p class="font-semibold text-slate-800 dark:text-white">Rp {{ number_format($payment->platform_fee, 0, ',', '.') }}</p>
                        </div>
                        <div class="rounded-xl border border-emerald-100 dark:border-emerald-900/60 bg-emerald-50/60 dark:bg-emerald-900/20 p-3">
                            <p class="text-[10px] font-bold uppercase tracking-wide text-emerald-500 dark:text-emerald-400 mb-1">Freelancer Receive</p>
                            <p class="font-bold text-emerald-600 dark:text-emerald-300">Rp {{ number_format($payment->freelancer_receive, 0, ',', '.') }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/40 p-3 flex flex-col gap-2">
                            <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400 dark:text-slate-500">Payment Status</p>
                            <span class="self-start text-xs px-2.5 py-1 rounded-full font-semibold bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-900">
                                {{ $payment->getStatusLabelAttribute() }}
                            </span>
                        </div>
                        <div class="rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/40 p-3 flex flex-col gap-2">
                            <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400 dark:text-slate-500">Funds Status</p>
                            <span class="self-start text-xs px-2.5 py-1 rounded-full font-semibold {{ $payment->getFundsStatusColorAttribute() }}">
                                {{ $payment->getFundsStatusLabelAttribute() }}
                            </span>
                        </div>
                        <div class="rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/40 p-3">
                            <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400 dark:text-slate-500 mb-1">Paid at</p>
                            <p class="font-semibold text-slate-800 dark:text-white whitespace-nowrap">{{ $payment->paid_at ? $payment->paid_at->format('d M Y H:i') : '—' }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/40 p-3">
                            <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400 dark:text-slate-500 mb-1">Held at</p>
                            <p class="font-semibold text-slate-800 dark:text-white whitespace-nowrap">{{ $payment->held_at ? $payment->held_at->format('d M Y H:i') : '—' }}</p>
                        </div>
                        @if($payment->released_at)
                            <div class="rounded-xl border border-emerald-100 dark:border-emerald-900/60 bg-emerald-50/60 dark:bg-emerald-900/20 p-3">
                                <p class="text-[10px] font-bold uppercase tracking-wide text-emerald-500 dark:text-emerald-400 mb-1">Released at</p>
                                <p class="font-semibold text-emerald-600 dark:text-emerald-300">Rp {{ number_format($payment->released_amount, 0, ',', '.') }} @{{ $payment->released_at->format('d M Y H:i') }}</p>
                            </div>
                        @endif
                        @if($payment->refunded_at)
                            <div class="rounded-xl border border-red-100 dark:border-red-900/60 bg-red-50/60 dark:bg-red-900/20 p-3">
                                <p class="text-[10px] font-bold uppercase tracking-wide text-red-500 dark:text-red-400 mb-1">Refunded at</p>
                                <p class="font-semibold text-red-600 dark:text-red-400">Rp {{ number_format($payment->refunded_amount, 0, ',', '.') }} @{{ $payment->refunded_at->format('d M Y H:i') }}</p>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="py-10 text-center">
                        <div class="w-12 h-12 mx-auto mb-3 bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl flex items-center justify-center text-slate-300 dark:text-slate-500">
                            <i class="fa-solid fa-file-invoice-dollar"></i>
                        </div>
                        <p class="text-sm text-slate-400 dark:text-slate-500">Belum ada data payment.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Submission Info --}}
    <div class="bg-white dark:bg-slate-900 dark:border-slate-800 rounded-2xl border border-blue-100 shadow-sm p-6 mb-6 transition-colors duration-300">
        <h2 class="font-bold text-slate-800 dark:text-white mb-4 flex items-center gap-2.5">
            <span class="w-8 h-8 rounded-lg bg-violet-50 dark:bg-slate-800 text-violet-500 dark:text-violet-400 flex items-center justify-center"><i class="fa-solid fa-upload text-xs"></i></span>
            Informasi Submission
        </h2>

        @if($submissions->isNotEmpty())
            <div class="space-y-3">
                @foreach($submissions as $submission)
                    <div class="p-4 bg-[#f6f9ff] dark:bg-slate-800/60 rounded-xl border border-blue-100 dark:border-slate-700 hover:border-blue-200 dark:hover:border-slate-600 transition-colors">
                        <div class="flex flex-wrap items-center justify-between gap-2 mb-1.5">
                            <p class="font-semibold text-sm text-slate-800 dark:text-white">{{ $submission->title }}</p>
                            <span class="text-xs px-2.5 py-0.5 rounded-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 font-semibold">{{ $submission->status ?? '—' }}</span>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-0.5">Diterima oleh: <span class="font-semibold">{{ $submission->submitter->name ?? '—' }}</span></p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-2">Tanggal: {{ $submission->created_at->format('d M Y H:i') }}</p>
                        @if($submission->files->isNotEmpty())
                            <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400 dark:text-slate-500 mb-1.5">Files</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($submission->files as $file)
                                    <a href="{{ $file->file_url }}" target="_blank"
                                       class="inline-flex items-center gap-1.5 text-[11px] px-2.5 py-1.5 rounded-lg bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-700 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/40 font-semibold transition">
                                        <i class="fa-solid fa-paperclip text-[9px]"></i> {{ basename($file->file_name) }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="py-10 text-center">
                <div class="flex flex-col items-center gap-2.5">
                    <div class="w-12 h-12 rounded-2xl bg-violet-50 dark:bg-slate-800 border border-violet-100 dark:border-slate-700 text-violet-400 dark:text-slate-500 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                    </div>
                    <p class="text-sm text-slate-400 dark:text-slate-500">Belum ada submission.</p>
                </div>
            </div>
        @endif
    </div>

    {{-- Progress Info --}}
    <div class="bg-white dark:bg-slate-900 dark:border-slate-800 rounded-2xl border border-blue-100 shadow-sm p-6 mb-6 transition-colors duration-300">
        <h2 class="font-bold text-slate-800 dark:text-white mb-4 flex items-center gap-2.5">
            <span class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-slate-800 text-amber-500 dark:text-amber-400 flex items-center justify-center"><i class="fa-solid fa-bars-progress text-xs"></i></span>
            Informasi Progress
        </h2>

        @if($latestProgress)
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-2 mb-3">
                <div class="text-4xl font-extrabold tracking-tight @if($latestProgress->progress >= 100) text-emerald-500 dark:text-emerald-400 @elseif($latestProgress->progress >= 50) text-blue-500 dark:text-blue-400 @else text-amber-500 dark:text-amber-400 @endif">
                    {{ $latestProgress->progress }}%
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400">Tahap: <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $latestProgress->stage }}</span></p>
            </div>
            <div class="w-full bg-blue-50 dark:bg-slate-800 rounded-full h-3 overflow-hidden">
                <div class="h-full rounded-full transition-all duration-500 @if($latestProgress->progress >= 100) bg-emerald-500 @elseif($latestProgress->progress >= 50) bg-blue-500 @else bg-amber-500 @endif"
                     style="width: {{ $latestProgress->progress }}%"></div>
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-3">Deskripsi: {{ $latestProgress->description ?? '—' }}</p>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Diterima: {{ $latestProgress->created_at->format('d M Y H:i') }} oleh {{ $latestProgress->updater->name ?? '—' }}</p>
        @else
            <div class="py-10 text-center">
                <div class="flex flex-col items-center gap-2.5">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-slate-800 border border-amber-100 dark:border-slate-700 text-amber-400 dark:text-slate-500 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-chart-simple"></i>
                    </div>
                    <p class="text-sm text-slate-400 dark:text-slate-500">Belum ada progress.</p>
                </div>
            </div>
        @endif
    </div>

    {{-- Conversation / Messages --}}
    <div class="bg-white dark:bg-slate-900 dark:border-slate-800 rounded-2xl border border-blue-100 shadow-sm p-6 mb-6 transition-colors duration-300">
        <h2 class="font-bold text-slate-800 dark:text-white mb-4 flex items-center gap-2.5">
            <span class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-slate-800 text-blue-500 dark:text-blue-400 flex items-center justify-center"><i class="fa-solid fa-comments text-xs"></i></span>
            Riwayat Percakapan
        </h2>

        <div class="space-y-3 max-h-96 overflow-y-auto pr-1">
            @foreach($workspace->messages as $message)
                <div class="p-3.5 rounded-xl @if($message->sender->role === 'admin') bg-gray-50 dark:bg-slate-800/60 @elseif($message->sender_id == (int) $workspace->company_id) bg-blue-50/70 dark:bg-blue-900/20 @else bg-amber-50/70 dark:bg-amber-900/20 @endif border-l-4 @if($message->sender->role === 'admin') border-slate-400 dark:border-slate-500 @elseif($message->sender_id == (int) $workspace->company_id) border-blue-500 @else border-amber-500 @endif">
                    <div class="flex items-start gap-2.5 mb-1">
                        <div class="w-7 h-7 rounded-full bg-white dark:bg-slate-700 ring-1 ring-slate-200 dark:ring-slate-600 text-slate-600 dark:text-slate-300 flex items-center justify-center text-xs font-bold shrink-0">
                            {{ strtoupper(substr($message->sender->name ?? '?', 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-bold text-slate-700 dark:text-slate-200 {{ $message->sender->role === 'admin' ? 'text-slate-600 dark:text-slate-300' : '' }}">
                                    {{ $message->sender->name ?? '—' }}
                                </span>
                                @if($message->type == 'system')<span class="text-[10px] px-1.5 py-0.5 rounded bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 font-semibold">System</span>@endif
                                <span class="text-[10px] text-slate-400 dark:text-slate-500 ml-auto whitespace-nowrap">{{ $message->created_at->format('d M H:i') }}</span>
                            </div>
                            <p class="text-sm text-slate-600 dark:text-slate-300 break-words {{ $message->sender->role === 'admin' ? 'text-slate-700 dark:text-slate-200' : '' }}">{{ $message->message }}</p>
                        </div>
                    </div>
                </div>
            @endforeach

            @if($workspace->messages->isEmpty())
                <div class="py-10 text-center">
                    <div class="flex flex-col items-center gap-2.5">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-slate-800 border border-blue-100 dark:border-slate-700 text-blue-400 dark:text-slate-500 flex items-center justify-center text-lg">
                            <i class="fa-regular fa-comment-dots"></i>
                        </div>
                        <p class="text-sm text-slate-400 dark:text-slate-500">Belum ada pesan.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Admin Actions --}}
    <div class="rounded-2xl border-2 border-dashed border-amber-200 dark:border-amber-900/60 bg-amber-50/40 dark:bg-amber-950/30 p-6 transition-colors duration-300">
        <div class="flex items-center gap-2.5 mb-4">
            <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/60 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-gavel text-xs"></i>
            </div>
            <div>
                <h2 class="font-bold text-slate-800 dark:text-white leading-tight">Aksi Admin</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">Tindakan mediasi untuk resolusi workspace ini.</p>
            </div>
        </div>

        @if($payment && $payment->isFundsHeld() && !in_array($workspace->status ?? '', ['Selesai', 'Menunggu Revisi', 'Menunggu Pembayaran', 'Menunggu Verifikasi Admin']))
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                {{-- Minta Respons Company --}}
                <form method="POST" action="{{ route('admin.workspace.resolution.request-company-response', $workspace) }}"
                  onsubmit="return adminConfirm('Yakin ingin meminta respons dari Company?', this)"
                  class="bg-white dark:bg-slate-900 dark:border-slate-800 rounded-xl border border-amber-100 dark:border-amber-900/60 p-4 shadow-sm">
                    <p class="text-xs font-bold uppercase tracking-wide text-amber-600 dark:text-amber-400 mb-2.5">Minta Respons Company</p>
                    @csrf
                    <textarea name="message" rows="2" required placeholder="Pesan untuk Company..."
                      class="w-full rounded-xl border-blue-100 dark:border-slate-700 bg-[#f6f9ff] dark:bg-slate-800 dark:text-white px-4 py-2.5 text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none transition"></textarea>
                    <div class="mt-2">
                        <label class="text-[10px] font-bold uppercase tracking-wide text-slate-400 dark:text-slate-500 mb-1 block">Deadline (opsional)</label>
                        <input type="text" name="deadline" placeholder="misal: 3 hari" class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white px-3 py-2 text-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition">
                    </div>
                    <button type="submit"
                            class="mt-3 w-full px-4 py-2.5 bg-amber-500 hover:bg-amber-600 active:bg-amber-700 text-white rounded-xl text-sm font-semibold shadow-sm transition">
                        <i class="fa-solid fa-user-tie mr-1"></i> Minta Respons Company
                    </button>
                </form>

                {{-- Minta Respons Freelancer --}}
                <form method="POST" action="{{ route('admin.workspace.resolution.request-freelancer-response', $workspace) }}"
                  onsubmit="return adminConfirm('Yakin ingin meminta respons dari Freelancer?', this)"
                  class="bg-white dark:bg-slate-900 dark:border-slate-800 rounded-xl border border-amber-100 dark:border-amber-900/60 p-4 shadow-sm">
                    <p class="text-xs font-bold uppercase tracking-wide text-amber-600 dark:text-amber-400 mb-2.5">Minta Respons Freelancer</p>
                    @csrf
                    <textarea name="message" rows="2" required placeholder="Pesan untuk Freelancer..."
                      class="w-full rounded-xl border-blue-100 dark:border-slate-700 bg-[#f6f9ff] dark:bg-slate-800 dark:text-white px-4 py-2.5 text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none transition"></textarea>
                    <div class="mt-2">
                        <label class="text-[10px] font-bold uppercase tracking-wide text-slate-400 dark:text-slate-500 mb-1 block">Deadline (opsional)</label>
                        <input type="text" name="deadline" placeholder="misal: 3 hari" class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white px-3 py-2 text-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition">
                    </div>
                    <button type="submit"
                            class="mt-3 w-full px-4 py-2.5 bg-amber-500 hover:bg-amber-600 active:bg-amber-700 text-white rounded-xl text-sm font-semibold shadow-sm transition">
                        <i class="fa-solid fa-user-tie mr-1"></i> Minta Respons Freelancer
                    </button>
                </form>

                {{-- Mulai Review --}}
                <form method="POST" action="{{ route('admin.workspace.resolution.start-review', $workspace) }}"
                  onsubmit="return adminConfirm('Yakin ingin memulai peninjauan resolusi?', this)"
                  class="bg-white dark:bg-slate-900 dark:border-slate-800 rounded-xl border border-amber-100 dark:border-amber-900/60 p-4 shadow-sm self-start w-full">
                    <p class="text-xs font-bold uppercase tracking-wide text-amber-600 dark:text-amber-400 mb-2.5">Peninjauan</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-3">Mulai proses review resolusi oleh admin.</p>
                    <button type="submit"
                            class="w-full px-4 py-2.5 bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 text-white rounded-xl text-sm font-semibold shadow-sm transition">
                        <i class="fa-solid fa-gavel mr-1"></i> Mulai Review
                    </button>
                </form>
            </div>
        @else
            <div class="bg-white dark:bg-slate-900 dark:border-slate-800 rounded-xl border border-amber-100 dark:border-amber-900/60 p-5 text-center">
                <div class="w-12 h-12 mx-auto mb-3 bg-amber-50 dark:bg-slate-800 rounded-2xl flex items-center justify-center text-amber-400 dark:text-slate-500">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400 max-w-md mx-auto">Tidak dapat mengambil action karena dana sudah diselesaikan atau status workspace tidak memungkinkan.</p>
            </div>
        @endif
    </div>
@endsection
