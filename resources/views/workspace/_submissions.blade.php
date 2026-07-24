{{-- Card: Hasil Pekerjaan (Submissions) --}}
<div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100">
        <h2 class="font-bold text-sm text-slate-800">Hasil Pekerjaan</h2>
    </div>
    <div class="p-5 space-y-4">

        @if($workspace->submissions->isNotEmpty())
            {{-- Timeline histori submission (terbaru di atas) --}}
            <div class="space-y-4">
                @foreach($workspace->submissions as $submission)
                    @php
                        $statusColors = [
                            'pending' => ['bg' => 'bg-amber-50', 'border' => 'border-amber-200', 'text' => 'text-amber-700', 'badge' => 'bg-amber-100 text-amber-700', 'label' => 'Pending'],
                            'accepted' => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'text' => 'text-emerald-700', 'badge' => 'bg-emerald-100 text-emerald-700', 'label' => 'Diterima'],
                            'revision' => ['bg' => 'bg-red-50', 'border' => 'border-red-200', 'text' => 'text-red-700', 'badge' => 'bg-red-100 text-red-700', 'label' => 'Revisi'],
                        ];
                        $sc = $statusColors[$submission->status] ?? $statusColors['pending'];
                    @endphp
                    <div class="relative pl-6 border-l-2 {{ $loop->first ? 'border-brand' : 'border-slate-200' }}">
                        {{-- Timeline dot --}}
                        <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full {{ $loop->first ? 'bg-brand' : 'bg-slate-300' }} flex items-center justify-center">
                            <i class="text-[8px] text-white fa-solid fa-check"></i>
                        </div>

                        <div class="ml-2 {{ $sc['bg'] }} border {{ $sc['border'] }} rounded-xl p-4 space-y-3">
                            {{-- Header: Title + Status Badge --}}
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-sm font-bold {{ $sc['text'] }} truncate">{{ $submission->title }}</h4>
                                    <p class="text-[10px] text-slate-400 mt-0.5">
                                        <i class="fa-regular fa-clock mr-1"></i>
                                        {{ $submission->created_at->format('d M Y H:i') }}
                                        @if($submission->submitter)
                                            &middot; oleh {{ $submission->submitter->name }}
                                        @endif
                                    </p>
                                </div>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $sc['badge'] }} shrink-0">
                                    {{ $sc['label'] }}
                                </span>
                            </div>

                            {{-- Description --}}
                            @if($submission->description)
                                <p class="text-xs text-slate-600 leading-relaxed">{{ $submission->description }}</p>
                            @endif

                            {{-- Daftar File --}}
                            @if($submission->files->isNotEmpty())
                                <div class="bg-white/60 border border-slate-200 rounded-lg overflow-hidden">
                                    <div class="px-3 py-2 bg-slate-50 border-b border-slate-200">
                                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider flex items-center gap-1.5">
                                            <i class="fa-solid fa-folder-open"></i>
                                            Daftar File ({{ $submission->files->count() }})
                                        </p>
                                    </div>
                                    <div class="divide-y divide-slate-100">
                                        @foreach($submission->files as $file)
                                            <div class="flex items-center gap-3 px-3 py-2.5 hover:bg-white transition">
                                                {{-- Icon --}}
                                                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center {{ $file->file_color }} shrink-0">
                                                    <i class="{{ $file->file_icon }} text-sm"></i>
                                                </div>

                                                {{-- File Info --}}
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-xs font-semibold text-slate-700 truncate">{{ $file->file_name }}</p>
                                                    <p class="text-[10px] text-slate-400">{{ $file->formatted_size }}</p>
                                                </div>

                                                {{-- Download --}}
                                                <a href="{{ $file->file_url }}" target="_blank"
                                                   class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-[10px] font-semibold text-slate-600 hover:bg-slate-50 hover:border-brand/30 transition shrink-0">
                                                    <i class="fa-solid fa-download"></i>
                                                    Download
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Company Note --}}
                            @if($submission->company_note)
                                <div class="bg-white/60 border border-slate-200 rounded-lg p-3">
                                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Catatan Perusahaan</p>
                                    <p class="text-xs text-slate-700">{{ $submission->company_note }}</p>
                                </div>
                            @endif

                            {{-- Actions --}}
                            <div class="flex items-center gap-2 pt-1">
                                {{-- Company Actions (hanya untuk submission pending dan milik company) --}}
                                @if(auth()->user()->role === 'company' && $submission->status === 'pending')
                                    {{-- Terima --}}
                                    <button type="button"
                                            onclick="openAcceptModal({{ $submission->id }})"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-500 text-white rounded-lg text-[10px] font-semibold hover:bg-emerald-600 transition">
                                        <i class="fa-solid fa-check-circle"></i> Terima
                                    </button>

                                    {{-- Minta Revisi --}}
                                    <button type="button"
                                            onclick="openRevisionModal({{ $submission->id }})"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-500 text-white rounded-lg text-[10px] font-semibold hover:bg-amber-600 transition">
                                        <i class="fa-solid fa-pen"></i> Minta Revisi
                                    </button>
                                @endif
                            </div>

                            {{-- Review time --}}
                            @if($submission->reviewed_at)
                                <p class="text-[9px] text-slate-400">
                                    <i class="fa-regular fa-clock mr-1"></i>
                                    Direview: {{ $submission->reviewed_at->format('d M Y H:i') }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="py-8 text-center">
                <div class="w-12 h-12 mx-auto mb-3 bg-slate-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-folder-open text-slate-400"></i>
                </div>
                <p class="text-xs text-slate-500">Belum ada hasil pekerjaan yang dikirim.</p>
            </div>
        @endif

        {{-- Upload Button (Freelancer Only) --}}
        @if(auth()->user()->role === 'freelancer')
            @php
                $hasAccepted = $workspace->submissions->contains('status', 'accepted');
            @endphp
            @if(!$hasAccepted)
                <button type="button" onclick="document.getElementById('uploadModal').classList.remove('hidden')"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-brand text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
                    <i class="fa-solid fa-upload"></i> Upload Hasil Pekerjaan
                </button>
            @else
                <div class="flex items-center gap-2 px-4 py-2.5 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-700 font-medium">
                    <i class="fa-solid fa-check-circle"></i> Pekerjaan sudah diterima. Tidak dapat mengirim submission baru.
                </div>
            @endif
        @endif
    </div>
</div>

{{-- ============================================================
     MODAL UPLOAD HASIL PEKERJAAN (Freelancer) - Multiple Files
============================================================ --}}
<div id="uploadModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800">Upload Hasil Pekerjaan</h3>
            <button type="button" onclick="document.getElementById('uploadModal').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center hover:bg-slate-200 transition">
                <i class="fa-solid fa-xmark text-slate-500"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('freelancer.workspaces.submissions.store', $workspace) }}" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Judul Pekerjaan <span class="text-red-500">*</span></label>
                <input type="text" name="title" required maxlength="255" placeholder="Contoh: Final Design Dashboard"
                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand/30">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Deskripsi</label>
                <textarea name="description" rows="3" maxlength="2000" placeholder="Jelaskan hasil pekerjaan yang dikirim..."
                          class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 resize-none"></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">File <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="file" name="files[]" multiple required
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-semibold file:bg-brand file:text-white hover:file:bg-blue-700 transition">
                </div>
                <p class="text-[10px] text-slate-400 mt-1.5">
                    <i class="fa-solid fa-info-circle mr-1"></i>
                    Format: gambar (png, jpg, jpeg, webp, gif), video (mp4, mov, avi, mkv), dokumen (pdf, doc, docx, xls, xlsx, ppt, pptx, txt), database (sql), arsip (zip, rar, 7z), kode (json, xml, fig, apk).
                </p>
                <p class="text-[10px] text-amber-600 font-medium mt-1">
                    <i class="fa-solid fa-circle-exclamation mr-1"></i>
                    Maksimal total upload 100 MB. Semua file akan disimpan sebagai satu submission.
                </p>
            </div>

            {{-- File list preview (JavaScript) --}}
            <div id="filePreview" class="hidden">
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 space-y-1.5">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">File Terpilih</p>
                    <ul id="fileList" class="space-y-1"></ul>
                </div>
            </div>

            <button type="submit"
                    class="w-full py-2.5 bg-brand text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-upload"></i> Kirim Hasil Pekerjaan
            </button>
        </form>
    </div>
</div>

{{-- ============================================================
     MODAL TERIMA (Company)
============================================================ --}}
<div id="acceptModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800">Terima Hasil Pekerjaan</h3>
            <button type="button" onclick="document.getElementById('acceptModal').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center hover:bg-slate-200 transition">
                <i class="fa-solid fa-xmark text-slate-500"></i>
            </button>
        </div>
        <form method="POST" action="" id="acceptForm" class="p-6 space-y-4">
            @csrf

            <div class="flex items-center gap-3 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl text-sm text-emerald-700">
                <i class="fa-solid fa-check-circle"></i>
                <p class="text-xs font-medium">Dengan menerima, status workspace akan menjadi <strong>Selesai</strong>.</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Catatan (opsional)</label>
                <textarea name="company_note" rows="3" maxlength="2000" placeholder="Tambahkan catatan untuk freelancer..."
                          class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 resize-none"></textarea>
            </div>

            <button type="submit"
                    class="w-full py-2.5 bg-emerald-500 text-white rounded-xl text-sm font-semibold hover:bg-emerald-600 transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-check-circle"></i> Ya, Terima
            </button>
        </form>
    </div>
</div>

{{-- ============================================================
     MODAL MINTA REVISI (Company)
============================================================ --}}
<div id="revisionModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800">Minta Revisi</h3>
            <button type="button" onclick="document.getElementById('revisionModal').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center hover:bg-slate-200 transition">
                <i class="fa-solid fa-xmark text-slate-500"></i>
            </button>
        </div>
        <form method="POST" action="" id="revisionForm" class="p-6 space-y-4">
            @csrf

            <div class="flex items-center gap-3 px-4 py-3 bg-amber-50 border border-amber-200 rounded-xl text-sm text-amber-700">
                <i class="fa-solid fa-pen"></i>
                <p class="text-xs font-medium">Jelaskan apa yang perlu direvisi agar freelancer dapat memperbaikinya.</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Catatan Revisi <span class="text-red-500">*</span></label>
                <textarea name="company_note" rows="4" maxlength="2000" required placeholder="Contoh: Mohon perbaiki halaman login dan tampilan responsif navbar..."
                          class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 resize-none"></textarea>
            </div>

            <button type="submit"
                    class="w-full py-2.5 bg-amber-500 text-white rounded-xl text-sm font-semibold hover:bg-amber-600 transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-pen"></i> Kirim Permintaan Revisi
            </button>
        </form>
    </div>
</div>

<script>
    // File preview on select
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.querySelector('input[name="files[]"]');
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                const preview = document.getElementById('filePreview');
                const list = document.getElementById('fileList');
                list.innerHTML = '';

                if (this.files.length > 0) {
                    preview.classList.remove('hidden');
                    for (let i = 0; i < this.files.length; i++) {
                        const file = this.files[i];
                        const size = file.size >= 1048576
                            ? (file.size / 1048576).toFixed(1) + ' MB'
                            : (file.size / 1024).toFixed(1) + ' KB';
                        const li = document.createElement('li');
                        li.className = 'flex items-center gap-2 text-xs text-slate-600';
                        li.innerHTML = '<i class="fa-solid fa-file text-slate-400"></i> ' +
                            file.name + ' <span class="text-slate-400">(' + size + ')</span>';
                        list.appendChild(li);
                    }
                } else {
                    preview.classList.add('hidden');
                }
            });
        }
    });

    // Modal Accept
    function openAcceptModal(submissionId) {
        const form = document.getElementById('acceptForm');
        form.action = '/company/workspaces/{{ $workspace->id }}/submissions/' + submissionId + '/accept';
        document.getElementById('acceptModal').classList.remove('hidden');
    }

    // Modal Revision
    function openRevisionModal(submissionId) {
        const form = document.getElementById('revisionForm');
        form.action = '/company/workspaces/{{ $workspace->id }}/submissions/' + submissionId + '/revision';
        document.getElementById('revisionModal').classList.remove('hidden');
    }
</script>

