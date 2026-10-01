@extends('layouts.admin')

@section('title', 'Detail Laporan')
@section('breadcrumb', 'Detail Laporan')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.reports.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline font-semibold inline-flex items-center gap-1">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Kolom Kiri (Detail & Lampiran) --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Card Utama Laporan --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-blue-100 dark:border-slate-700 shadow-sm p-6">
                <div class="flex items-start justify-between mb-4 gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 dark:text-white">{{ $report->subject }}</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                            Laporan #{{ $report->id }} oleh 
                            @if ($report->reporter)
                                <a href="{{ route('admin.users.show', $report->reporter) }}"
                                   class="font-semibold text-slate-700 dark:text-slate-200 hover:text-blue-600 dark:hover:text-blue-400 transition">
                                   {{ $report->reporter->name ?? '—' }}
                                </a>
                            @else
                                <span>—</span>
                            @endif
                        </p>
                        <div class="flex items-center gap-2 mt-2 flex-wrap">
                            <span class="text-xs px-2.5 py-1 rounded-full font-semibold bg-blue-50 dark:bg-slate-700 text-slate-600 dark:text-slate-300">
                                {{ \App\Models\Report::categoryLabel($report->category) }}
                            </span>
                            <span class="text-xs px-2.5 py-1 rounded-full font-semibold bg-blue-50 dark:bg-slate-700 text-blue-700 dark:text-blue-300 border border-blue-100 dark:border-slate-600">
                                Target: {{ \App\Models\Report::targetLabel($report->target) }}
                            </span>
                        </div>
                    </div>
                    <span class="text-xs px-3 py-1.5 rounded-full font-semibold whitespace-nowrap
                        @if($report->status == 'menunggu') bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400
                        @elseif($report->status == 'ditinjau') bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400
                        @elseif($report->status == 'menunggu-bukti') bg-violet-50 text-violet-600 dark:bg-violet-950/40 dark:text-violet-400
                        @elseif($report->status == 'ditangani') bg-teal-50 text-teal-600 dark:bg-teal-950/40 dark:text-teal-400
                        @elseif($report->status == 'selesai') bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400
                        @else bg-red-50 text-red-600 dark:bg-red-950/40 dark:text-red-400 @endif">
                        {{ \App\Models\Report::statusLabel($report->status) }}
                    </span>
                </div>

                <div class="mb-4">
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold mb-1">Deskripsi Laporan</p>
                    <div class="bg-[#f6f9ff] dark:bg-slate-900 rounded-xl p-4 text-sm text-slate-700 dark:text-slate-300 leading-relaxed border border-slate-100 dark:border-slate-700/50">
                        {{ $report->description }}
                    </div>
                </div>

                @if($report->admin_note)
                    <div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold mb-1">Catatan Admin</p>
                        <div class="bg-blue-50 dark:bg-blue-950/30 rounded-xl p-4 text-sm text-blue-700 dark:text-blue-300 leading-relaxed border border-blue-100 dark:border-blue-900/50">
                            {{ $report->admin_note }}
                        </div>
                    </div>
                @endif
            </div>

            {{-- Form Update Status --}}
            @if($report->status !== 'selesai' && $report->status !== 'ditolak')
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-blue-100 dark:border-slate-700 shadow-sm p-6">
                    <h2 class="font-bold text-slate-800 dark:text-white mb-4">Update Status Laporan</h2>
                    <form method="POST" action="{{ route('admin.reports.update-status', $report) }}">
                        @csrf
                        <div class="mb-4">
                            <label class="text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1 block">Status</label>
                            <select name="status" class="w-full rounded-xl border-blue-100 dark:border-slate-700 bg-[#f6f9ff] dark:bg-slate-900 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none">
                                <option value="ditinjau" @selected($report->status == 'ditinjau')>Sedang Ditinjau</option>
                                <option value="menunggu-bukti" @selected($report->status == 'menunggu-bukti')>Menunggu Bukti Tambahan</option>
                                <option value="selesai" @selected($report->status == 'selesai')>Selesai / Ditutup</option>
                                <option value="ditolak" @selected($report->status == 'ditolak')>Ditolak</option>
                            </select>
                            <p class="text-[11px] text-slate-400 mt-1">Pilih "Menunggu Bukti Tambahan" untuk meminta pelapor mengunggah bukti/screenshot pendukung.</p>
                        </div>
                        <div class="mb-4">
                            <label class="text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1 block">Catatan Admin (opsional)</label>
                            <textarea name="admin_note" rows="3" class="w-full rounded-xl border-blue-100 dark:border-slate-700 bg-[#f6f9ff] dark:bg-slate-900 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Tambahkan catatan...">{{ old('admin_note', $report->admin_note) }}</textarea>
                        </div>
                        <button type="submit" class="px-6 py-2.5 bg-blue-500 hover:bg-blue-600 text-white rounded-xl text-sm font-semibold transition">
                            <i class="fa-solid fa-check mr-1"></i> Update Status
                        </button>
                    </form>
                </div>
            @endif

            {{-- Lampiran / Bukti --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-blue-100 dark:border-slate-700 shadow-sm p-6">
                <h2 class="font-bold text-slate-800 dark:text-white mb-4">Lampiran / Bukti ({{ $report->attachments->count() }})</h2>
                @if($report->attachments->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($report->attachments as $attachment)
                            <div class="group relative rounded-xl border border-blue-100 dark:border-slate-700 overflow-hidden bg-[#f6f9ff] dark:bg-slate-900 hover:border-blue-300 transition">
                                @if($attachment->is_image)
                                    <a href="{{ $attachment->file_url }}" target="_blank" title="{{ $attachment->file_name }}">
                                        <img src="{{ $attachment->file_url }}" alt="{{ $attachment->file_name }}" class="w-full h-24 object-cover group-hover:scale-105 transition">
                                    </a>
                                    <div class="p-2">
                                        <p class="text-[11px] font-semibold text-slate-700 dark:text-slate-300 truncate">🖼 {{ $attachment->file_name }}</p>
                                        <p class="text-[10px] text-slate-400">{{ $attachment->formatted_size }}</p>
                                    </div>
                                @else
                                    <div class="h-24 flex items-center justify-center bg-blue-50 dark:bg-slate-800 text-blue-500">
                                        <i class="fa-solid fa-file-pdf text-3xl"></i>
                                    </div>
                                    <div class="p-2">
                                        <p class="text-[11px] font-semibold text-slate-700 dark:text-slate-300 truncate">📄 {{ $attachment->file_name }}</p>
                                        <p class="text-[10px] text-slate-400">{{ $attachment->formatted_size }}</p>
                                        <div class="flex gap-2 mt-2">
                                            <a href="{{ $attachment->file_url }}" target="_blank" class="flex-1 text-center px-2 py-1.5 rounded-lg bg-blue-50 dark:bg-slate-700 text-blue-600 dark:text-blue-400 text-[10px] font-bold transition">
                                                <i class="fa-solid fa-eye mr-1"></i> Buka
                                            </a>
                                            <a href="{{ $attachment->file_url }}" download class="flex-1 text-center px-2 py-1.5 rounded-lg bg-blue-50 dark:bg-slate-700 text-slate-600 dark:text-slate-300 text-[10px] font-bold transition">
                                                <i class="fa-solid fa-download mr-1"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-slate-400 text-center py-4">Belum ada lampiran/bukti.</p>
                @endif
            </div>
        </div>

        {{-- Kolom Kanan (Informasi Relasi & Aksi) --}}
        <div class="space-y-6">
            {{-- Pelapor --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-blue-100 dark:border-slate-700 shadow-sm p-5">
                <h3 class="font-bold text-slate-800 dark:text-white mb-3">Pelapor</h3>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-lg font-bold">
                        {{ strtoupper(substr($report->reporter->name ?? '?', 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 dark:text-slate-200">{{ $report->reporter->name ?? '—' }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $report->reporter->email ?? '—' }}</p>
                    </div>
                </div>
            </div>

            {{-- Proyek Terkait --}}
            @if($report->project && !$report->penawaran_id)
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-blue-100 dark:border-slate-700 shadow-sm p-5">
                    <h3 class="font-bold text-slate-800 dark:text-white mb-3">Proyek Terkait</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Nama</span><span class="font-semibold text-slate-800 dark:text-slate-200">{{ $report->project->project_name }}</span></div>
                        <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Company</span><span class="font-semibold text-slate-800 dark:text-slate-200">{{ $report->project->owner->name ?? '—' }}</span></div>
                    </div>
                    <a href="{{ route('admin.projects.show', $report->project) }}" class="mt-3 inline-block text-xs text-blue-600 dark:text-blue-400 font-semibold hover:underline">Lihat Detail →</a>
                </div>
            @endif

            {{-- User Dilaporkan --}}
            @if($report->reportedUser)
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-blue-100 dark:border-slate-700 shadow-sm p-5">
                    <h3 class="font-bold text-slate-800 dark:text-white mb-3">User Dilaporkan</h3>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-bold">
                            {{ strtoupper(substr($report->reportedUser->name ?? '?', 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-800 dark:text-slate-200">{{ $report->reportedUser->name }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $report->reportedUser->email }}</p>
                            <span class="mt-1 inline-block text-[10px] font-bold uppercase tracking-wide px-2 py-0.5 rounded-full bg-blue-50 dark:bg-slate-700 text-blue-600 dark:text-blue-400">
                                {{ ucfirst($report->reportedUser->role ?? '—') }}
                            </span>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Workspace Terkait --}}
            @if($report->workspace)
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-blue-100 dark:border-slate-700 shadow-sm p-5">
                    <h3 class="font-bold text-slate-800 dark:text-white mb-3">Workspace Terkait</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500 dark:text-slate-400">Proyek</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200 text-right">{{ $report->workspace->project->project_name ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500 dark:text-slate-400">Status</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $report->workspace->status ?? '—' }}</span>
                        </div>
                    </div>
                </div>
            @endif

            @php
                $payment = $report->payment ?? $report->workspace?->payment;
            @endphp

            {{-- Dana Tertahan / Dispute --}}
            @if($payment && $payment->status === 'paid' && $payment->funds_status !== 'not_applicable')
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-blue-100 dark:border-slate-700 shadow-sm p-5">
                    <h3 class="font-bold text-slate-800 dark:text-white mb-3">Dana Tertahan / Dispute</h3>
                    <div class="space-y-2 text-sm mb-4">
                        <div class="flex justify-between">
                            <span class="text-slate-500 dark:text-slate-400">Invoice</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $payment->invoice_number }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500 dark:text-slate-400">Total Dibayar</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500 dark:text-slate-400">Freelancer Receive</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200">Rp {{ number_format($payment->freelancer_receive, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500 dark:text-slate-400">Status Dana</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $payment->funds_status_label }}</span>
                        </div>
                        @if((float) $payment->released_amount > 0)
                            <div class="flex justify-between">
                                <span class="text-slate-500 dark:text-slate-400">Dirilis</span>
                                <span class="font-semibold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($payment->released_amount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        @if((float) $payment->refunded_amount > 0)
                            <div class="flex justify-between">
                                <span class="text-slate-500 dark:text-slate-400">Direfund</span>
                                <span class="font-semibold text-red-600 dark:text-red-400">Rp {{ number_format($payment->refunded_amount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                    </div>

                    @if($payment->isFundsHeld() && !in_array($report->status, ['selesai', 'ditolak']))
                        <div class="space-y-4">
                            {{-- Release Full --}}
                            <form method="POST" action="{{ route('admin.reports.release-funds', $report) }}"
                                  onsubmit="return adminConfirm('Yakin merilis SELURUH dana ke freelancer?', this)">
                                @csrf
                                <textarea name="admin_note" rows="2" required placeholder="Alasan keputusan (wajib)..."
                                          class="w-full rounded-xl border-blue-100 dark:border-slate-700 bg-[#f6f9ff] dark:bg-slate-900 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 outline-none"></textarea>
                                <button type="submit" class="mt-2 w-full px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-sm font-semibold transition">
                                    <i class="fa-solid fa-hand-holding-dollar mr-1"></i> Release Penuh ke Freelancer
                                </button>
                            </form>

                            {{-- Refund Full --}}
                            <form method="POST" action="{{ route('admin.reports.refund-funds', $report) }}"
                                  onsubmit="return adminConfirm('Yakin merefund SELURUH dana ke company?', this)">
                                @csrf
                                <textarea name="admin_note" rows="2" required placeholder="Alasan keputusan (wajib)..."
                                          class="w-full rounded-xl border-blue-100 dark:border-slate-700 bg-[#f6f9ff] dark:bg-slate-900 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 outline-none"></textarea>
                                <button type="submit" class="mt-2 w-full px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-xl text-sm font-semibold transition">
                                    <i class="fa-solid fa-rotate-left mr-1"></i> Refund Penuh ke Company
                                </button>
                            </form>

                            {{-- Split / Partial --}}
                            <form method="POST" action="{{ route('admin.reports.split-funds', $report) }}"
                                  onsubmit="return adminConfirm('Yakin melakukan pembagian dana (split)?', this)">
                                @csrf
                                <textarea name="admin_note" rows="2" required placeholder="Alasan keputusan (wajib)..."
                                          class="w-full rounded-xl border-blue-100 dark:border-slate-700 bg-[#f6f9ff] dark:bg-slate-900 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 outline-none"></textarea>
                                <div class="mt-2">
                                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1 block">Nominal untuk Freelancer</label>
                                    <input type="number" name="freelancer_amount" min="0" max="{{ $payment->freelancer_receive }}"
                                           step="0.01" required value="{{ $payment->freelancer_receive }}"
                                           class="w-full rounded-xl border-blue-100 dark:border-slate-700 bg-[#f6f9ff] dark:bg-slate-900 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 outline-none">
                                    <p class="text-[11px] text-slate-400 mt-1">Sisa otomatis menjadi refund ke company.</p>
                                </div>
                                <button type="submit" class="mt-2 w-full px-4 py-2.5 bg-blue-500 hover:bg-blue-600 text-white rounded-xl text-sm font-semibold transition">
                                    <i class="fa-solid fa-scale-balanced mr-1"></i> Split / Bagi Dana
                                </button>
                            </form>
                        </div>
                    @else
                        <p class="text-sm text-slate-400 text-center py-2">Dana sudah terselesaikan pada laporan ini.</p>
                    @endif
                </div>
            @endif

            {{-- Penanganan --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-blue-100 dark:border-slate-700 shadow-sm p-5">
                <h3 class="font-bold text-slate-800 dark:text-white mb-3">Penanganan</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500 dark:text-slate-400">Ditangani Oleh</span>
                        <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $report->handledBy->name ?? 'Belum ada' }}</span>
                    </div>
                    @if($report->resolved_at)
                        <div class="flex justify-between">
                            <span class="text-slate-500 dark:text-slate-400">Diselesaikan</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $report->resolved_at->format('d M Y H:i') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Aksi Admin --}}
            @php
                $isKeterlambatan = $report->category === \App\Models\Report::CATEGORY_KETERLAMBATAN;
                $isClosed        = in_array($report->status, [\App\Models\Report::STATUS_SELESAI, \App\Models\Report::STATUS_DITOLAK], true);
            @endphp
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-blue-100 dark:border-slate-700 shadow-sm p-5">
                <h3 class="font-bold text-slate-800 dark:text-white mb-4">Aksi Admin</h3>

                @if($isKeterlambatan && $report->status === \App\Models\Report::STATUS_DITANGANI)
                    <div class="rounded-xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-100 dark:border-emerald-900/50 p-3">
                        <p class="text-sm text-emerald-600 dark:text-emerald-400 font-semibold flex items-center gap-2">
                            <i class="fa-solid fa-circle-check"></i> Laporan telah diterima &amp; sedang ditangani.
                        </p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                            Menunggu tindakan lanjutan dari Admin (perpanjangan deadline / pembatalan project).
                        </p>
                    </div>
                @elseif($isKeterlambatan && $isClosed)
                    <p class="text-sm text-slate-400 text-center py-2">Laporan sudah ditutup.</p>
                @elseif($isKeterlambatan)
                    <p class="text-[11px] font-bold uppercase tracking-wide text-slate-400 mb-2">Terima Laporan (Keterlambatan)</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-3 leading-relaxed">
                        Menyatakan laporan keterlambatan ini <strong>VALID</strong>.
                    </p>
                    <form method="POST" action="{{ route('admin.reports.accept', $report) }}"
                          onsubmit="return adminConfirm('Terima laporan keterlambatan ini?', this)">
                        @csrf
                        <textarea name="admin_note" rows="2" placeholder="Catatan admin (opsional)..."
                                  class="w-full rounded-xl border-blue-100 dark:border-slate-700 bg-[#f6f9ff] dark:bg-slate-900 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 outline-none"></textarea>
                        <button type="submit" class="mt-2 w-full px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-sm font-semibold transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-check"></i> Terima Laporan
                        </button>
                    </form>
                @elseif($report->penawaran_id)
                    <form method="POST" action="{{ route('admin.reports.destroy-penawaran', $report) }}"
                          onsubmit="return adminConfirm('Yakin ingin menghapus penawaran ini?', this)">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-xl text-sm font-semibold transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-trash-can"></i> Hapus Penawaran
                        </button>
                    </form>
                @elseif($report->project_id)
                    <form method="POST" action="{{ route('admin.reports.destroy-project', $report) }}"
                          onsubmit="return adminConfirm('Yakin ingin menghapus project ini?', this)">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-xl text-sm font-semibold transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-trash-can"></i> Hapus Project
                        </button>
                    </form>
                @else
                    <p class="text-sm text-slate-400 text-center py-2">Tidak ada aksi yang tersedia.</p>
                @endif
            </div>
        </div>
    </div>
@endsection