@extends('layouts.admin')

@section('title', 'Workspace Resolution')
@section('breadcrumb', 'Workspace Resolution')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.hasil-pekerjaan.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-semibold inline-flex items-center gap-1">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Workspace
        </a>
    </div>

    @if($canRelease || $canRefund)
        <div class="bg-white rounded-2xl border border-green-100 shadow-sm p-6 mb-6">
            <h2 class="font-bold text-slate-800 mb-4">Keputusan Dana Escrow</h2>

            @if($payment && $payment->isFundsHeld())
                <p class="text-sm text-slate-500 mb-4">
                    Dana sedang tertahan. Admin dapat memutuskan merilis ke Freelancer atau merefund ke Company.
                </p>

                {{-- Release to Freelancer --}}
                @if($canRelease)
                    <form method="POST" action="{{ route('admin.workspace.resolution.decide', $workspace) }}"
                      onsubmit="return adminConfirm('Yakin merilis dana ke Freelancer? Aksi tercatat di ledger dan tidak dapat dibatalkan.', this)">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="action" value="release_to_freelancer">
                        <textarea name="reason" rows="3" required placeholder="Alasan keputusan (wajib)..."
                          class="w-full rounded-xl border-blue-100 bg-[#f6f9ff] px-4 py-2.5 text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none"></textarea>
                        <div class="mt-3">
                            <button type="submit"
                                    class="w-full px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-sm font-semibold transition">
                                <i class="fa-solid fa-hand-holding-dollar mr-1"></i> Release ke Freelancer
                            </button>
                        </div>
                    </form>
                @endif

                {{-- Refund to Company --}}
                @if($canRefund)
                    <form method="POST" action="{{ route('admin.workspace.resolution.decide', $workspace) }}"
                      onsubmit="return adminConfirm('Yakin merefund dana ke Company? Aksi tercatat di ledger dan tidak dapat dibatalkan.', this)">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="action" value="refund_to_company">
                        <textarea name="reason" rows="3" required placeholder="Alasan keputusan (wajib)..."
                          class="w-full rounded-xl border-blue-100 bg-[#f6f9ff] px-4 py-2.5 text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none"></textarea>
                        <div class="mt-3">
                            <button type="submit"
                                    class="w-full px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-xl text-sm font-semibold transition">
                                <i class="fa-solid fa-rotate-left mr-1"></i> Refund ke Company
                            </button>
                        </div>
                    </form>
                @endif
                @endif
            </div>
        @endif
    @endif

    {{-- Workspace Info --}}
    <div class="bg-white rounded-2xl border border-blue-100 shadow-sm p-6 mb-6">
        <h2 class="font-bold text-slate-800 mb-3">Informasi Workspace</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-xs text-slate-500 font-semibold">Project</p>
                <p class="font-semibold">{{ $workspace->project->project_name ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold">Company</p>
                <p class="font-semibold">{{ $workspace->company->name ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold">Freelancer</p>
                <p class="font-semibold">{{ $workspace->freelancer->name ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold">Workspace ID</p>
                <p class="font-semibold">#{{ $workspace->id }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold">Status Workspace</p>
                <span class="text-xs px-2.5 py-1 rounded-full font-semibold bg-blue-50 text-blue-600">
                    {{ ucfirst($workspace->status ?? '—') }}
                </span>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold">Created at</p>
                <p class="font-semibold">{{ $workspace->created_at->format('d M Y H:i') }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold">Updated at</p>
                <p class="font-semibold">{{ $workspace->updated_at->format('d M Y H:i') }}</p>
            </div>
        </div>
    </div>

    {{-- Payment Info --}}
    <div class="bg-white rounded-2xl border border-blue-100 shadow-sm p-6 mb-6">
        <h2 class="font-bold text-slate-800 mb-3">Informasi Payment</h2>

        @if($payment)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-xs text-slate-500 font-semibold">Invoice</p>
                    <p class="font-semibold">{{ $payment->invoice_number }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-semibold">Amount</p>
                    <p class="font-semibold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-semibold">Platform Fee</p>
                    <p class="font-semibold">Rp {{ number_format($payment->platform_fee, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-semibold">Freelancer Receive</p>
                    <p class="font-semibold">Rp {{ number_format($payment->freelancer_receive, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-semibold">Payment Status</p>
                    <span class="text-xs px-2.5 py-1 rounded-full font-semibold bg-emerald-50 text-emerald-600 border-emerald-200">
                        {{ $payment->getStatusLabelAttribute() }}
                    </span>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-semibold">Funds Status</p>
                    <span class="text-xs px-2.5 py-1 rounded-full font-semibold {{ $payment->getFundsStatusColorAttribute() }}">
                        {{ $payment->getFundsStatusLabelAttribute() }}
                    </span>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-semibold">Paid at</p>
                    <p class="font-semibold">{{ $payment->paid_at ? $payment->paid_at->format('d M Y H:i') : '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-semibold">Held at</p>
                    <p class="font-semibold">{{ $payment->held_at ? $payment->held_at->format('d M Y H:i') : '—' }}</p>
                </div>
                @if($payment->released_at)
                    <div>
                        <p class="text-xs text-slate-500 font-semibold">Released at</p>
                        <p class="font-semibold">Rp {{ number_format($payment->released_amount, 0, ',', '.') }} @{{ $payment->released_at->format('d M Y H:i') }}</p>
                    </div>
                @endif
                @if($payment->refunded_at)
                    <div>
                        <p class="text-xs text-slate-500 font-semibold">Refunded at</p>
                        <p class="font-semibold text-red-600">Rp {{ number_format($payment->refunded_amount, 0, ',', '.') }} @{{ $payment->refunded_at->format('d M Y H:i') }}</p>
                    </div>
                @endif
            </div>
        @endif
    </div>

    {{-- Submission Info --}}
    <div class="bg-white rounded-2xl border border-blue-100 shadow-sm p-6 mb-6">
        <h2 class="font-bold text-slate-800 mb-3">Informasi Submission</h2>

        @if($submissions->isNotEmpty())
            <div class="space-y-3">
                @foreach($submissions as $submission)
                    <div class="p-3 bg-[#f6f9ff] rounded-xl border border-blue-100 mb-2">
                        <div class="flex items-center justify-between mb-1">
                            <p class="font-semibold text-sm">{{ $submission->title }}</p>
                            <span class="text-xs text-slate-500">{{ $submission->status ?? '—' }}</span>
                        </div>
                        <p class="text-xs text-slate-500 mb-1">Diterima oleh: {{ $submission->submitter->name ?? '—' }}</p>
                        <p class="text-xs text-slate-500 mb-1">Tanggal: {{ $submission->created_at->format('d M Y H:i') }}</p>
                        @if($submission->files->isNotEmpty())
                            <p class="text-xs text-slate-400">Files:</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($submission->files as $file)
                                    <a href="{{ $file->file_url }}" target="_blank"
                                       class="text-[10px] px-2 py-1 rounded bg-blue-50 text-blue-600 hover:bg-blue-100">
                                        {{ basename($file->file_name) }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-slate-400 text-center py-4">Belum ada submission.</p>
        @endif
    </div>

    {{-- Progress Info --}}
    <div class="bg-white rounded-2xl border border-blue-100 shadow-sm p-6 mb-6">
        <h2 class="font-bold text-slate-800 mb-3">Informasi Progress</h2>

        @if($latestProgress)
            <div class="text-center mb-3">
                <div class="text-4xl font-extrabold @if($latestProgress->progress >= 100) text-emerald-500 @elseif($latestProgress->progress >= 50) text-blue-500 @else text-amber-500 @endif">
                    {{ $latestProgress->progress }}%
                </div>
                <p class="text-xs text-slate-500 mt-1">Tahap: {{ $latestProgress->stage }}</p>
            </div>
            <div class="w-full bg-blue-50 rounded-full h-3 overflow-hidden">
                <div class="h-full rounded-full transition-all duration-500 @if($latestProgress->progress >= 100) bg-emerald-500 @elseif($latestProgress->progress >= 50) bg-blue-500 @else bg-amber-500 @endif"
                     style="width: {{ $latestProgress->progress }}%"></div>
            </div>
            <p class="text-xs text-slate-500 mt-1">Deskripsi: {{ $latestProgress->description ?? '—' }}</p>
            <p class="text-xs text-slate-400">Diterima: {{ $latestProgress->created_at->format('d M Y H:i') }}oleh {{ $latestProgress->updater->name ?? '—' }}</p>
        @else
            <p class="text-sm text-slate-400 text-center">Belum ada progress.</p>
        @endif
    </div>

    {{-- Conversation / Messages --}}
    <div class="bg-white rounded-2xl border border-blue-100 shadow-sm p-6 mb-6">
        <h2 class="font-bold text-slate-800 mb-3">Riwayat Percakapan</h2>

        <div class="space-y-3 max-h-96 overflow-y-auto">
            @foreach($workspace->messages as $message)
                <div class="p-3 rounded-xl @if($message->sender->role === 'admin') bg-gray-100 @elseif($message->sender_id == (int) $workspace->company_id) bg-blue-50 @else bg-amber-50 @endif border-l-4 @if($message->sender->role === 'admin') border-blue-500 @elseif($message->sender_id == (int) $workspace->company_id) border-blue-500 @else border-amber-500 @endif">
                    <div class="flex items-start gap-2 mb-1">
                        <div class="w-7 h-7 rounded-full bg-gray-200 text-slate-600 flex items-center justify-center text-xs font-bold shrink-0">
                            {{ strtoupper(substr($message->sender->name ?? '?', 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-bold text-slate-700 {{ $message->sender->role === 'admin' ? 'text-slate-600' : '' }}">
                                    {{ $message->sender->name ?? '—' }}
                                </span>
                                @if($message->type == 'system')<span class="text-[10px] px-1.5 py-0.5 rounded bg-blue-50 text-slate-500 font-semibold">System</span>@endif
                                <span class="text-[10px] text-slate-400 ml-auto">{{ $message->created_at->format('d M H:i') }}</span>
                            </div>
                            <p class="text-sm text-slate-600 {{ $message->sender->role === 'admin' ? 'text-slate-700' : '' }}">{{ $message->message }}</p>
                        </div>
                    </div>
                </div>
            @endforeach

            @if($workspace->messages->isEmpty())
                <p class="text-sm text-slate-400 text-center">Belum ada pesan.</p>
            @endif
        </div>
    </div>

    {{-- Admin Actions --}}
    <div class="bg-white rounded-2xl border border-blue-100 shadow-sm p-6">
        <h2 class="font-bold text-slate-800 mb-3">Aksi Admin</h2>

        @if($payment && $payment->isFundsHeld() && !in_array($workspace->status ?? '', ['Selesai', 'Menunggu Revisi', 'Menunggu Pembayaran', 'Menunggu Verifikasi Admin'])
        {{-- TOMBOL TERSedia hanya jika dana masih tertahan dan workspace belum selesai */}
            <div class="space-y-3">
                {{-- Minta Respons Company --}}
                <form method="POST" action="{{ route('admin.workspace.resolution.request-company-response', $workspace) }}"
                  onsubmit="return adminConfirm('Yakin ingin meminta respons dari Company?', this)">
                    @csrf
                    <textarea name="message" rows="2" required placeholder="Pesan untuk Company..."
                      class="w-full rounded-xl border-blue-100 bg-[#f6f9ff] px-4 py-2.5 text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none"></textarea>
                    <div class="mt-2">
                        <label class="text-xs text-slate-500 mb-1 block">Deadline (opsional)</label>
                        <input type="text" name="deadline" placeholder="misal: 3 hari" class="w-full rounded-xl border-gray-300 px-3 py-2 text-sm">
                    </div>
                    <button type="submit"
                            class="w-full px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-sm font-semibold transition">
                        <i class="fa-solid fa-user-tie mr-1"></i> Minta Respons Company
                    </button>
                </form>

                {{-- Minta Respons Freelancer --}}
                <form method="POST" action="{{ route('admin.workspace.resolution.request-freelancer-response', $workspace) }}"
                  onsubmit="return adminConfirm('Yakin ingin meminta respons dari Freelancer?', this)">
                    @csrf
                    <textarea name="message" rows="2" required placeholder="Pesan untuk Freelancer..."
                      class="w-full rounded-xl border-blue-100 bg-[#f6f9ff] px-4 py-2.5 text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none"></textarea>
                    <div class="mt-2">
                        <label class="text-xs text-slate-500 mb-1 block">Deadline (opsional)</label>
                        <input type="text" name="deadline" placeholder="misal: 3 hari" class="w-full rounded-xl border-gray-300 px-3 py-2 text-sm">
                    </div>
                    <button type="submit"
                            class="w-full px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-sm font-semibold transition">
                        <i class="fa-solid fa-user-tie mr-1"></i> Minta Respons Freelancer
                    </button>
                </form>

                {{-- Mulai Review --}}
                <form method="POST" action="{{ route('admin.workspace.resolution.start-review', $workspace) }}"
                  onsubmit="return adminConfirm('Yakin ingin memulai peninjauan resolusi?', this)">
                    @csrf
                    <button type="submit"
                            class="w-full px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-sm font-semibold transition">
                        <i class="fa-solid fa-gavel mr-1"></i> Mulai Review
                    </button>
                </form>
            </div>
        @else
            <p class="text-sm text-slate-400 text-center">Tidak dapat mengambil action karena dana sudah diselesaikan atau status workspace tidak memungkinkan.</p>
        @endif
    </div>
@endsection