{{-- Card: Hasil Pekerjaan (Submissions) --}}
<div class="bg-white dark:bg-slate-900 border border-blue-100 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden transition-colors duration-300">
    <div class="px-5 py-4 border-b border-blue-50 dark:border-slate-800">
        <h2 class="font-bold text-sm text-slate-800 dark:text-white">Hasil Pekerjaan</h2>
    </div>
    <div class="p-5 space-y-4">

        @if ($workspace->submissions->isNotEmpty())
            {{-- Timeline histori submission (terbaru di atas) --}}
            <div class="space-y-4">
                @foreach ($workspace->submissions as $submission)
                    @php
                        $statusColors = [
                            'pending' => [
                                'bg' => 'bg-amber-50 dark:bg-amber-900/40',
                                'border' => 'border-amber-200 dark:border-amber-900',
                                'text' => 'text-amber-700 dark:text-amber-300',
                                'badge' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
                                'label' => 'Pending',
                            ],
                            'accepted' => [
                                'bg' => 'bg-emerald-50 dark:bg-emerald-900/40',
                                'border' => 'border-emerald-200 dark:border-emerald-900',
                                'text' => 'text-emerald-700 dark:text-emerald-300',
                                'badge' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300',
                                'label' => 'Diterima',
                            ],
                            'revision' => [
                                'bg' => 'bg-red-50 dark:bg-red-900/40',
                                'border' => 'border-red-200 dark:border-red-900',
                                'text' => 'text-red-700 dark:text-red-300',
                                'badge' => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
                                'label' => 'Revisi',
                            ],
                        ];
                        $sc = $statusColors[$submission->status] ?? $statusColors['pending'];

                        $groupedFiles = [
                            'image' => $submission->files->where('category', 'image'),
                            'video' => $submission->files->where('category', 'video'),
                            'document' => $submission->files->where('category', 'document'),
                            'archive' => $submission->files->where('category', 'archive'),
                        ];

                        $categoryMeta = [
                            'image' => [
                                'icon' => 'fa-solid fa-image',
                                'color' => 'text-pink-500 dark:text-pink-400',
                                'bg' => 'bg-pink-50 dark:bg-pink-900/40',
                                'label' => 'Gambar',
                            ],
                            'video' => [
                                'icon' => 'fa-solid fa-video',
                                'color' => 'text-purple-500 dark:text-purple-400',
                                'bg' => 'bg-purple-50 dark:bg-purple-900/40',
                                'label' => 'Video',
                            ],
                            'document' => [
                                'icon' => 'fa-solid fa-file-lines',
                                'color' => 'text-blue-500 dark:text-blue-400',
                                'bg' => 'bg-blue-50 dark:bg-slate-800',
                                'label' => 'Dokumen',
                            ],
                            'archive' => [
                                'icon' => 'fa-solid fa-file-zipper',
                                'color' => 'text-amber-500 dark:text-amber-400',
                                'bg' => 'bg-amber-50 dark:bg-amber-900/40',
                                'label' => 'Arsip',
                            ],
                        ];
                    @endphp
                    <div class="relative pl-6 border-l-2 {{ $loop->first ? 'border-brand' : 'border-blue-100 dark:border-slate-800' }}">
                        <div
                            class="absolute -left-[9px] top-0 w-4 h-4 rounded-full {{ $loop->first ? 'bg-brand' : 'bg-slate-300 dark:bg-slate-600' }} flex items-center justify-center">
                            <i class="text-[8px] text-white fa-solid fa-check"></i>
                        </div>

                        <div class="ml-2 {{ $sc['bg'] }} border {{ $sc['border'] }} rounded-xl p-4 space-y-3">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-sm font-bold {{ $sc['text'] }} truncate">{{ $submission->title }}
                                    </h4>
                                    <p class="text-[10px] text-slate-400 mt-0.5">
                                        <i class="fa-regular fa-clock mr-1"></i>
                                        {{ $submission->created_at->format('d M Y H:i') }}
                                        @if ($submission->submitter)
                                            &middot; oleh {{ $submission->submitter->name }}
                                        @endif
                                    </p>
                                </div>
                                <span
                                    class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $sc['badge'] }} shrink-0">
                                    {{ $sc['label'] }}
                                </span>
                            </div>

                            @if ($submission->description)
                                <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">{{ $submission->description }}</p>
                            @endif

                            @if ($submission->files->isNotEmpty())
                                <div class="space-y-2">
                                    @foreach ($categoryMeta as $catKey => $catMeta)
                                        @php $catFiles = $groupedFiles[$catKey] ?? collect(); @endphp
                                        @if ($catFiles->isNotEmpty())
                                            <div class="bg-white/60 dark:bg-slate-900/60 border border-blue-100 dark:border-slate-800 rounded-lg overflow-hidden">
                                                <div class="px-3 py-2 {{ $catMeta['bg'] }} border-b border-blue-100 dark:border-slate-800">
                                                    <p class="text-[10px] font-bold {{ $catMeta['color'] }} uppercase tracking-wider flex items-center gap-1.5">
                                                        <i class="{{ $catMeta['icon'] }}"></i>
                                                        {{ $catMeta['label'] }} ({{ $catFiles->count() }})
                                                    </p>
                                                </div>
                                                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                                                    @foreach ($catFiles as $file)
                                                        <div class="flex items-center gap-3 px-3 py-2.5 hover:bg-white dark:hover:bg-slate-800 transition">
                                                            @if ($catKey === 'image')
                                                                <div class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-slate-800 overflow-hidden shrink-0 border border-blue-100 dark:border-slate-800">
                                                                    <img src="{{ $file->file_url }}"
                                                                        alt="{{ $file->file_name }}"
                                                                        class="w-full h-full object-cover"
                                                                        onerror="this.style.display='none'; this.parentElement.innerHTML='<div class=\\'w-full h-full flex items-center justify-center\\'><i class=\\'{{ $file->file_icon }} text-sm {{ $file->file_color }}\\'></i></div>'">
                                                                </div>
                                                            @else
                                                                <div class="w-10 h-10 rounded-lg {{ $catMeta['bg'] }} flex items-center justify-center {{ $file->file_color }} shrink-0 border border-blue-100 dark:border-slate-800">
                                                                    <i class="{{ $file->file_icon }} text-sm"></i>
                                                                </div>
                                                            @endif
                                                            <div class="flex-1 min-w-0">
                                                                <p class="text-xs font-semibold text-slate-700 dark:text-white truncate">{{ $file->file_name }}</p>
                                                                <p class="text-[10px] text-slate-400">{{ $file->formatted_size }}</p>
                                                            </div>
                                                            <a href="{{ $file->file_url }}" target="_blank"
                                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-white dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded-lg text-[10px] font-semibold text-slate-600 dark:text-slate-300 hover:bg-[#f6f9ff] dark:hover:bg-slate-700 hover:border-brand/30 transition shrink-0">
                                                                <i class="fa-solid fa-download"></i> Download
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif

                            @if ($submission->company_note)
                                <div class="bg-white/60 dark:bg-slate-900/60 border border-blue-100 dark:border-slate-800 rounded-lg p-3">
                                    <p class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Catatan Perusahaan</p>
                                    <p class="text-xs text-slate-700 dark:text-white">{{ $submission->company_note }}</p>
                                </div>
                            @endif

                            @if (auth()->user()->role === 'company' && $submission->status === 'pending')
                                <div class="flex items-center gap-2 pt-1">
                                    <button type="button" onclick="openAcceptModal(this)"
                                        data-action="{{ route('company.workspaces.submissions.accept', ['workspace' => $workspace->id, 'submission' => $submission->id]) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-500 text-white rounded-lg text-[10px] font-semibold hover:bg-emerald-600 transition cursor-pointer">
                                        <i class="fa-solid fa-check-circle"></i> Terima
                                    </button>

                                    <button type="button" onclick="openRevisionModal(this)"
                                        data-action="{{ route('company.workspaces.submissions.revision', ['workspace' => $workspace->id, 'submission' => $submission->id]) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-500 text-white rounded-lg text-[10px] font-semibold hover:bg-amber-600 transition cursor-pointer">
                                        <i class="fa-solid fa-pen"></i> Minta Revisi
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="py-8 text-center">
                <div class="w-12 h-12 mx-auto mb-3 bg-blue-50 dark:bg-slate-800 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-folder-open text-slate-400 dark:text-slate-400"></i>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400">Belum ada hasil pekerjaan yang dikirim.</p>
            </div>
        @endif

        @if (auth()->user()->role === 'freelancer')
            @php
                $hasAccepted = $workspace->submissions->contains('status', 'accepted');
                $hasPending = $workspace->submissions->contains('status', 'pending');
            @endphp
            @if ($hasAccepted)
                <div class="flex items-center gap-2 px-4 py-2.5 bg-emerald-50 dark:bg-emerald-900/40 border border-emerald-200 dark:border-emerald-900 rounded-xl text-xs text-emerald-700 dark:text-emerald-300 font-medium">
                    <i class="fa-solid fa-check-circle"></i> Hasil pekerjaan telah diterima oleh perusahaan.
                </div>
            @elseif ($hasPending)
                <button type="button" disabled
                    class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-200 dark:bg-slate-800 text-slate-400 rounded-xl text-sm font-semibold cursor-not-allowed">
                    <i class="fa-solid fa-upload"></i> Upload Hasil Pekerjaan
                </button>
                <div class="flex items-center gap-2 px-4 py-2.5 bg-amber-50 dark:bg-amber-900/40 border border-amber-200 dark:border-amber-900 rounded-xl text-xs text-amber-700 dark:text-amber-300 font-medium">
                    <i class="fa-solid fa-clock"></i> Menunggu perusahaan meninjau hasil pekerjaan Anda.
                </div>
            @else
                <button type="button" onclick="document.getElementById('uploadModal').classList.remove('hidden')"
                    class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-400 text-white rounded-xl text-sm font-semibold hover:bg-blue-500 transition">
                    <i class="fa-solid fa-upload"></i> Upload Hasil Pekerjaan
                </button>
            @endif
        @endif
    </div>
</div>

{{-- ============================================================
     MODAL UPLOAD HASIL PEKERJAAN (Freelancer) - 4 Kategori File
============================================================ --}}
<div id="uploadModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm transition-opacity">
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-xl mx-4 overflow-auto max-h-[90vh] border border-slate-100 dark:border-slate-800">
        
        {{-- Header Modal --}}
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between sticky top-0 bg-white/95 dark:bg-slate-900/95 backdrop-blur z-10">
            <h3 class="font-bold text-lg text-slate-800 dark:text-white">Upload Hasil Pekerjaan</h3>
            <button type="button" onclick="document.getElementById('uploadModal').classList.add('hidden')"
                class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors group">
                <i class="fa-solid fa-xmark text-slate-500 dark:text-slate-400 group-hover:text-slate-700 dark:group-hover:text-white transition-colors"></i>
            </button>
        </div>
        
        {{-- Form Content --}}
        <form method="POST" action="{{ route('freelancer.workspaces.submissions.store', $workspace) }}"
            enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf

            {{-- Input Judul --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                    Judul Pekerjaan <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" required maxlength="255"
                    placeholder="Contoh: Final Design Dashboard"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 dark:focus:ring-blue-500/50 transition-all">
            </div>

            {{-- Input Deskripsi --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Deskripsi</label>
                <textarea name="description" rows="3" maxlength="2000" placeholder="Jelaskan hasil pekerjaan yang dikirim..."
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 dark:focus:ring-blue-500/50 resize-none transition-all"></textarea>
            </div>

            {{-- Divider Upload --}}
            <div class="border-t border-slate-100 dark:border-slate-800 pt-4 mt-2">
                <p class="text-sm font-semibold text-slate-800 dark:text-white mb-1">Pilih File <span class="text-red-500">*</span></p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">Minimal upload 1 file dari salah satu kategori di bawah ini.</p>
            </div>

            {{-- SECTION 1: GAMBAR --}}
            <div class="bg-pink-50/50 dark:bg-pink-500/10 border border-pink-100 dark:border-pink-500/20 rounded-xl p-4 space-y-3 transition-colors hover:border-pink-200 dark:hover:border-pink-500/40">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-pink-100 dark:bg-pink-500/20 flex items-center justify-center text-pink-600 dark:text-pink-400 shadow-sm">
                        <i class="fa-solid fa-image text-sm"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">Gambar</h4>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">jpg, jpeg, png, webp &middot; Maks 10 MB/file</p>
                    </div>
                </div>
                <input type="file" name="images[]" multiple accept=".jpg,.jpeg,.png,.webp"
                    class="w-full text-xs bg-white dark:bg-slate-900 border border-pink-100 dark:border-pink-500/30 rounded-lg px-3 py-2 text-slate-700 dark:text-slate-300 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-[11px] file:font-semibold file:bg-pink-500 file:text-white hover:file:bg-pink-600 dark:hover:file:bg-pink-400 transition-all cursor-pointer">
            </div>

            {{-- SECTION 2: VIDEO --}}
            <div class="bg-purple-50/50 dark:bg-purple-500/10 border border-purple-100 dark:border-purple-500/20 rounded-xl p-4 space-y-3 transition-colors hover:border-purple-200 dark:hover:border-purple-500/40">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-purple-100 dark:bg-purple-500/20 flex items-center justify-center text-purple-600 dark:text-purple-400 shadow-sm">
                        <i class="fa-solid fa-video text-sm"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">Video</h4>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">mp4, mov, avi, mkv &middot; Maks 100 MB/file</p>
                    </div>
                </div>
                <input type="file" name="videos[]" multiple accept=".mp4,.mov,.avi,.mkv"
                    class="w-full text-xs bg-white dark:bg-slate-900 border border-purple-100 dark:border-purple-500/30 rounded-lg px-3 py-2 text-slate-700 dark:text-slate-300 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-[11px] file:font-semibold file:bg-purple-500 file:text-white hover:file:bg-purple-600 dark:hover:file:bg-purple-400 transition-all cursor-pointer">
            </div>

            {{-- SECTION 3: DOKUMEN --}}
            <div class="bg-blue-50/50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20 rounded-xl p-4 space-y-3 transition-colors hover:border-blue-200 dark:hover:border-blue-500/40">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center text-blue-600 dark:text-blue-400 shadow-sm">
                        <i class="fa-solid fa-file-lines text-sm"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">Dokumen</h4>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">pdf, doc, docx &middot; Maks 20 MB/file</p>
                    </div>
                </div>
                <input type="file" name="documents[]" multiple accept=".pdf,.doc,.docx"
                    class="w-full text-xs bg-white dark:bg-slate-900 border border-blue-100 dark:border-blue-500/30 rounded-lg px-3 py-2 text-slate-700 dark:text-slate-300 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-[11px] file:font-semibold file:bg-blue-500 file:text-white hover:file:bg-blue-600 dark:hover:file:bg-blue-400 transition-all cursor-pointer">
            </div>

            {{-- SECTION 4: SOURCE CODE / ARSIP --}}
            <div class="bg-amber-50/50 dark:bg-amber-500/10 border border-amber-100 dark:border-amber-500/20 rounded-xl p-4 space-y-3 transition-colors hover:border-amber-200 dark:hover:border-amber-500/40">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400 shadow-sm">
                        <i class="fa-solid fa-file-zipper text-sm"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">Source Code / Arsip</h4>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">zip, rar, 7z &middot; Maks 100 MB/file</p>
                    </div>
                </div>
                <input type="file" name="archives[]" multiple accept=".zip,.rar,.7z"
                    class="w-full text-xs bg-white dark:bg-slate-900 border border-amber-100 dark:border-amber-500/30 rounded-lg px-3 py-2 text-slate-700 dark:text-slate-300 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-[11px] file:font-semibold file:bg-amber-500 file:text-white hover:file:bg-amber-600 dark:hover:file:bg-amber-400 transition-all cursor-pointer">
            </div>

            {{-- TOMBOL SUBMIT YG DIPEROLEH (Lebih Tegas) --}}
            <div class="pt-2">
                <button type="submit"
                    class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-500/30 dark:shadow-blue-900/40 transition-all duration-200 flex items-center justify-center gap-2 focus:ring-4 focus:ring-blue-500/50 focus:outline-none transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-upload"></i> Kirim Hasil Pekerjaan
                </button>
            </div>
        </form>
    </div>
</div>
{{-- ============================================================
     MODAL TERIMA (Company)
============================================================ --}}
<div id="acceptModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
        <div class="px-6 py-5 border-b border-blue-50 dark:border-slate-800 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 dark:text-white">Terima Hasil Pekerjaan</h3>
            <button type="button" onclick="document.getElementById('acceptModal').classList.add('hidden')"
                class="w-8 h-8 rounded-full bg-blue-50 dark:bg-slate-800 flex items-center justify-center hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                <i class="fa-solid fa-xmark text-slate-500 dark:text-slate-400"></i>
            </button>
        </div>
        <form method="POST" action="" id="acceptForm" class="p-6 space-y-4">
            @csrf

            <div class="flex items-center gap-3 px-4 py-3 bg-emerald-50 dark:bg-emerald-900/40 border border-emerald-200 dark:border-emerald-900 rounded-xl text-sm text-emerald-700 dark:text-emerald-300">
                <i class="fa-solid fa-check-circle"></i>
                <p class="text-xs font-medium">Dengan menerima, status workspace akan menjadi <strong>Menunggu
                        Pembayaran</strong>.</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5">Catatan (opsional)</label>
                <textarea name="company_note" rows="3" maxlength="2000" placeholder="Tambahkan catatan untuk freelancer..."
                    class="w-full px-4 py-2.5 bg-[#f6f9ff] dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded-xl text-sm dark:text-white dark:placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-brand/30 resize-none"></textarea>
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
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
        <div class="px-6 py-5 border-b border-blue-50 dark:border-slate-800 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 dark:text-white">Minta Revisi</h3>
            <button type="button" onclick="document.getElementById('revisionModal').classList.add('hidden')"
                class="w-8 h-8 rounded-full bg-blue-50 dark:bg-slate-800 flex items-center justify-center hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                <i class="fa-solid fa-xmark text-slate-500 dark:text-slate-400"></i>
            </button>
        </div>
        <form method="POST" action="" id="revisionForm" class="p-6 space-y-4">
            @csrf

            <div class="flex items-center gap-3 px-4 py-3 bg-amber-50 dark:bg-amber-900/40 border border-amber-200 dark:border-amber-900 rounded-xl text-sm text-amber-700 dark:text-amber-300">
                <i class="fa-solid fa-pen"></i>
                <p class="text-xs font-medium">Jelaskan apa yang perlu direvisi agar freelancer dapat memperbaikinya.
                </p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5">Catatan Revisi <span class="text-red-500">*</span></label>
                <textarea name="company_note" rows="4" maxlength="2000" required
                    placeholder="Contoh: Mohon perbaiki halaman login dan tampilan responsif navbar..."
                    class="w-full px-4 py-2.5 bg-[#f6f9ff] dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded-xl text-sm dark:text-white dark:placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-brand/30 resize-none"></textarea>
            </div>

            <button type="submit"
                class="w-full py-2.5 bg-amber-500 text-white rounded-xl text-sm font-semibold hover:bg-amber-600 transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-pen"></i> Kirim Permintaan Revisi
            </button>
        </form>
    </div>
</div>

<script>
    function openAcceptModal(buttonEl) {
        const form = document.getElementById('acceptForm');
        const actionUrl = buttonEl.getAttribute('data-action');

        if (form && actionUrl) {
            form.action = actionUrl;
            document.getElementById('acceptModal').classList.remove('hidden');
        }
    }

    function openRevisionModal(buttonEl) {
        const form = document.getElementById('revisionForm');
        const actionUrl = buttonEl.getAttribute('data-action');

        if (form && actionUrl) {
            form.action = actionUrl;
            document.getElementById('revisionModal').classList.remove('hidden');
        }
    }
</script>
