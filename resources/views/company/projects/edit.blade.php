<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Proyek - ApexForge Labs</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(59, 130, 246, 0.2); border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(59, 130, 246, 0.5); }

        .hologram-grid-blue {
            background-image:
                linear-gradient(to right, rgba(59, 130, 246, 0.04) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(59, 130, 246, 0.04) 1px, transparent 1px);
            background-size: 32px 32px;
        }

        .btn-shimmer { position: relative; overflow: hidden; isolation: isolate; }
        .btn-shimmer::after {
            content: ''; position: absolute; top: 0; left: -75%;
            width: 50%; height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255,255,255,.4), transparent);
            transform: skewX(-20deg); transition: left .65s ease;
        }
        .btn-shimmer:hover::after { left: 125%; }

        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(59, 130, 246, 0.1);
            box-shadow: 0 20px 50px -10px rgba(30, 58, 138, 0.1);
        }
    </style>
</head>
<body class="bg-white text-blue-950 min-h-screen flex relative antialiased">

    <div class="fixed inset-0 pointer-events-none hologram-grid-blue z-0"></div>
    <div class="fixed top-[-20%] right-[-10%] w-[50rem] h-[50rem] bg-gradient-to-bl from-blue-100/40 to-transparent rounded-full blur-[100px] pointer-events-none z-0"></div>

    {{-- SIDEBAR --}}
    <div class="relative z-10 flex">
        @include('navbar.navigasi')
    </div>

    {{-- AREA KANAN --}}
    <div class="flex-1 flex flex-col min-h-screen overflow-hidden relative z-10">

        @include('navbar.nav')

        <main class="flex-1 overflow-y-auto p-6">
            <div class="max-w-4xl mx-auto space-y-6">

                {{-- BREADCRUMB --}}
                <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-blue-300">
                    <a href="{{ route('company.dashboard') }}" class="hover:text-blue-600 transition-colors">Dashboard</a>
                    <i class="fa-solid fa-chevron-right text-[9px] text-blue-200"></i>
                    <a href="{{ route('company.projects.index') }}" class="hover:text-blue-600 transition-colors">Proyek</a>
                    <i class="fa-solid fa-chevron-right text-[9px] text-blue-200"></i>
                    <a href="{{ route('company.projects.show', $project) }}" class="hover:text-blue-600 transition-colors">Detail</a>
                    <i class="fa-solid fa-chevron-right text-[9px] text-blue-200"></i>
                    <span class="text-blue-600">Edit Proyek</span>
                </nav>

                {{-- HEADER --}}
                <div>
                    <h1 class="text-3xl font-black text-blue-950 tracking-tight">Edit Proyek</h1>
                    <p class="text-sm font-semibold text-blue-400 mt-1">Perbarui informasi proyek Anda. Status proyek tidak dapat diubah dari sini.</p>
                </div>

                {{-- WORKFLOW LOCK NOTICE --}}
                @if(!empty($lock['note']) && count($lock['locked']) > 1)
                    <div class="overflow-hidden relative bg-amber-50 border border-amber-200 p-4 rounded-2xl flex items-start gap-3 shadow-sm">
                        <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 border border-amber-200 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-lock text-sm"></i>
                        </div>
                        <div class="text-xs font-semibold text-amber-800">
                            {{ $lock['note'] }}
                        </div>
                    </div>
                @endif

                {{-- FLASH --}}
                @if(session('success'))
                    <div class="overflow-hidden relative bg-blue-50 border border-blue-200 p-4 rounded-2xl flex items-center gap-4 shadow-sm">
                        <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center shrink-0 shadow-[0_0_15px_rgba(59,130,246,0.4)]">
                            <i class="fa-solid fa-check text-white text-sm"></i>
                        </div>
                        <div class="font-bold text-blue-900 text-sm">{{ session('success') }}</div>
                    </div>
                @endif
                @if(session('error'))
                    <div class="overflow-hidden relative bg-rose-50 border border-rose-200 p-4 rounded-2xl flex items-center gap-4 shadow-sm">
                        <div class="w-8 h-8 rounded-full bg-rose-100 text-rose-600 border border-rose-200 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-circle-exclamation text-sm"></i>
                        </div>
                        <div class="font-bold text-rose-800 text-sm">{{ session('error') }}</div>
                    </div>
                @endif

                @php
                    $locked = $lock['locked'] ?? [];
                    $isLocked = fn($f) => in_array($f, $locked, true);
                @endphp

                {{-- FORM CARD --}}
                <div class="glass-card rounded-3xl relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 via-blue-600 to-blue-400"></div>

                    <form method="POST" action="{{ route('company.projects.update', $project) }}" enctype="multipart/form-data" class="p-6 lg:p-8">
                        @csrf
                        @method('PUT')

                        {{-- VALIDATION ERRORS --}}
                        @if ($errors->any())
                            <div class="mb-8 overflow-hidden relative bg-white border-2 border-blue-600 p-5 rounded-2xl shadow-[0_0_20px_rgba(59,130,246,0.15)] flex items-start gap-4">
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 border border-blue-200 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-exclamation text-sm"></i>
                                </div>
                                <div>
                                    <div class="font-black text-blue-950 text-sm mb-1">Mohon perbaiki kesalahan berikut:</div>
                                    <ul class="list-none space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li class="text-xs font-semibold text-blue-700 flex items-center gap-2">
                                                <i class="fa-solid fa-angle-right text-[10px] text-blue-400"></i> {{ $error }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        {{-- SECTION 1: INFORMASI PROYEK --}}
                        <div class="mb-8">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center shrink-0 shadow-sm">
                                    <i class="fa-solid fa-clipboard-list"></i>
                                </div>
                                <div>
                                    <h2 class="text-sm font-black text-blue-950 tracking-tight">Informasi Proyek</h2>
                                    <p class="text-[10px] font-bold text-blue-400 uppercase tracking-widest mt-0.5">Lengkapi detail dasar proyek Anda</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                {{-- Nama Proyek --}}
                                <div class="lg:col-span-2">
                                    <label class="block text-[10px] font-black text-blue-500 uppercase tracking-widest mb-2">Nama Proyek <span class="text-blue-500">*</span></label>
                                    <input type="text" name="project_name" value="{{ old('project_name', $project->project_name) }}"
                                        {{ $isLocked('project_name') ? 'disabled' : '' }}
                                        class="w-full px-5 py-3.5 bg-blue-50/50 border @error('project_name') border-blue-500 ring-2 ring-blue-500/20 @else border-blue-100 @enderror rounded-xl text-sm font-bold text-blue-950 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 focus:bg-white transition-all placeholder:text-blue-300 placeholder:font-medium {{ $isLocked('project_name') ? 'opacity-60 cursor-not-allowed' : '' }}"
                                        placeholder="Contoh: Pengembangan Website E-commerce" required>
                                    @error('project_name')
                                        <p class="text-[10px] font-bold tracking-wide text-blue-600 mt-1.5">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Kategori --}}
                                <div>
                                    <label class="block text-[10px] font-black text-blue-500 uppercase tracking-widest mb-2">Kategori</label>
                                    <select name="category_id" {{ $isLocked('category_id') ? 'disabled' : '' }}
                                        class="w-full px-5 py-3.5 bg-blue-50/50 border @error('category_id') border-blue-500 ring-2 ring-blue-500/20 @else border-blue-100 @enderror rounded-xl text-sm font-bold text-blue-950 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 focus:bg-white transition-all cursor-pointer {{ $isLocked('category_id') ? 'opacity-60 cursor-not-allowed' : '' }}">
                                        <option value="">Pilih Kategori</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ (string) old('category_id', $project->category_id) === (string) $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <p class="text-[10px] font-bold tracking-wide text-blue-600 mt-1.5">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Skills --}}
                                <div>
                                    <label class="block text-[10px] font-black text-blue-500 uppercase tracking-widest mb-2">Skill yang Dibutuhkan</label>
                                    <input type="text" name="skills" value="{{ old('skills', $project->skills) }}"
                                        {{ $isLocked('skills') ? 'disabled' : '' }}
                                        class="w-full px-5 py-3.5 bg-blue-50/50 border @error('skills') border-blue-500 ring-2 ring-blue-500/20 @else border-blue-100 @enderror rounded-xl text-sm font-bold text-blue-950 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 focus:bg-white transition-all placeholder:text-blue-300 placeholder:font-medium {{ $isLocked('skills') ? 'opacity-60 cursor-not-allowed' : '' }}"
                                        placeholder="Laravel, Bootstrap, MySQL">
                                    <p class="text-[10px] font-bold text-blue-400 mt-2">Pisahkan dengan koma.</p>
                                    @error('skills')
                                        <p class="text-[10px] font-bold tracking-wide text-blue-600 mt-1.5">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Deskripsi --}}
                                <div class="lg:col-span-2">
                                    <label class="block text-[10px] font-black text-blue-500 uppercase tracking-widest mb-2">Deskripsi Proyek</label>
                                    <textarea name="project_description" rows="6" {{ $isLocked('project_description') ? 'disabled' : '' }}
                                        class="w-full px-5 py-4 bg-blue-50/50 border @error('project_description') border-blue-500 ring-2 ring-blue-500/20 @else border-blue-100 @enderror rounded-xl text-sm font-medium text-blue-950 leading-relaxed focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 focus:bg-white transition-all placeholder:text-blue-300 resize-y {{ $isLocked('project_description') ? 'opacity-60 cursor-not-allowed' : '' }}"
                                        placeholder="Jelaskan kebutuhan, tujuan, dan hasil yang Anda harapkan dari freelancer.">{{ old('project_description', $project->project_description) }}</textarea>
                                    @error('project_description')
                                        <p class="text-[10px] font-bold tracking-wide text-blue-600 mt-1.5">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- DIVIDER --}}
                        <div class="h-px w-full bg-gradient-to-r from-transparent via-blue-100 to-transparent mb-8"></div>

                        {{-- SECTION 2: ANGGARAN & WAKTU --}}
                        <div class="mb-8">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center shrink-0 shadow-sm">
                                    <i class="fa-solid fa-coins"></i>
                                </div>
                                <div>
                                    <h2 class="text-sm font-black text-blue-950 tracking-tight">Anggaran & Waktu</h2>
                                    <p class="text-[10px] font-bold text-blue-400 uppercase tracking-widest mt-0.5">Tentukan budget dan batas waktu pengerjaan</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                {{-- Budget --}}
                                <div>
                                    <label class="block text-[10px] font-black text-blue-500 uppercase tracking-widest mb-2">Budget (Rp) <span class="text-blue-500">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-5 pointer-events-none">
                                            <span class="text-blue-400 font-bold text-sm">Rp</span>
                                        </div>
                                        <input type="hidden" name="budget" id="real_budget" value="{{ old('budget', $project->budget) }}">
                                        <input type="text" id="display_budget" {{ $isLocked('budget') ? 'disabled' : '' }}
                                            class="w-full pl-12 pr-5 py-3.5 bg-blue-50/50 border @error('budget') border-blue-500 ring-2 ring-blue-500/20 @else border-blue-100 @enderror rounded-xl text-sm font-bold text-blue-950 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 focus:bg-white transition-all placeholder:text-blue-300 placeholder:font-medium {{ $isLocked('budget') ? 'opacity-60 cursor-not-allowed' : '' }}"
                                            placeholder="5000000">
                                    </div>
                                    @error('budget')
                                        <p class="text-[10px] font-bold tracking-wide text-blue-600 mt-1.5">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Deadline --}}
                                <div>
                                    <label class="block text-[10px] font-black text-blue-500 uppercase tracking-widest mb-2">Deadline <span class="text-blue-500">*</span></label>
                                    <input type="date" name="deadline" value="{{ old('deadline', $project->deadline ? \Carbon\Carbon::parse($project->deadline)->format('Y-m-d') : '') }}"
                                        {{ $isLocked('deadline') ? 'disabled' : '' }}
                                        class="w-full px-5 py-3.5 bg-blue-50/50 border @error('deadline') border-blue-500 ring-2 ring-blue-500/20 @else border-blue-100 @enderror rounded-xl text-sm font-bold text-blue-950 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 focus:bg-white transition-all cursor-pointer {{ $isLocked('deadline') ? 'opacity-60 cursor-not-allowed' : '' }}">
                                    @error('deadline')
                                        <p class="text-[10px] font-bold tracking-wide text-blue-600 mt-1.5">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- DIVIDER --}}
                        <div class="h-px w-full bg-gradient-to-r from-transparent via-blue-100 to-transparent mb-8"></div>

                        {{-- SECTION 3: DETAIL TAMBAHAN --}}
                        <div class="mb-8">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center shrink-0 shadow-sm">
                                    <i class="fa-solid fa-paperclip"></i>
                                </div>
                                <div>
                                    <h2 class="text-sm font-black text-blue-950 tracking-tight">Detail Tambahan</h2>
                                    <p class="text-[10px] font-bold text-blue-400 uppercase tracking-widest mt-0.5">Lampirkan file pendukung jika diperlukan</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                {{-- Gambar --}}
                                <div>
                                    <label class="block text-[10px] font-black text-blue-500 uppercase tracking-widest mb-2">Gambar Proyek</label>
                                    @if($project->image)
                                        <div class="mb-2 rounded-xl overflow-hidden border border-blue-200">
                                            <img src="{{ asset('storage/' . $project->image) }}" class="w-full h-32 object-cover" alt="Gambar proyek">
                                        </div>
                                    @endif
                                    <div class="relative w-full border-2 border-dashed @error('image') border-blue-400 @else border-blue-200 @enderror rounded-xl bg-blue-50/30 hover:bg-blue-50/80 hover:border-blue-400 transition-colors duration-300">
                                        <input type="file" name="image" accept="image/*" {{ $isLocked('image') ? 'disabled' : '' }}
                                            class="w-full px-5 py-4 text-sm text-blue-900 cursor-pointer file:cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-wider file:bg-blue-600 file:text-white hover:file:bg-blue-700 file:transition-colors focus:outline-none {{ $isLocked('image') ? 'opacity-50 cursor-not-allowed' : '' }}">
                                    </div>
                                    <p class="text-[10px] font-bold text-blue-400 mt-2">Format: JPG, PNG, WebP. Max 2MB.</p>
                                    @error('image')
                                        <p class="text-[10px] font-bold tracking-wide text-blue-600 mt-1.5">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Lampiran --}}
                                <div>
                                    <label class="block text-[10px] font-black text-blue-500 uppercase tracking-widest mb-2">Lampiran (PDF/DOC)</label>
                                    @if($project->attachment)
                                        <a href="{{ asset('storage/' . $project->attachment) }}" target="_blank" class="mb-2 inline-flex items-center gap-2 text-[10px] font-bold text-blue-600 bg-blue-50 border border-blue-200 px-3 py-1.5 rounded-lg hover:bg-blue-100 transition">
                                            <i class="fa-solid fa-file-pdf"></i> Lihat lampiran saat ini
                                        </a>
                                    @endif
                                    <div class="relative w-full border-2 border-dashed @error('attachment') border-blue-400 @else border-blue-200 @enderror rounded-xl bg-blue-50/30 hover:bg-blue-50/80 hover:border-blue-400 transition-colors duration-300">
                                        <input type="file" name="attachment" accept=".pdf,.doc,.docx" {{ $isLocked('attachment') ? 'disabled' : '' }}
                                            class="w-full px-5 py-4 text-sm text-blue-900 cursor-pointer file:cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-wider file:bg-blue-600 file:text-white hover:file:bg-blue-700 file:transition-colors focus:outline-none {{ $isLocked('attachment') ? 'opacity-50 cursor-not-allowed' : '' }}">
                                    </div>
                                    <p class="text-[10px] font-bold text-blue-400 mt-2">Format: PDF, DOC, DOCX. Max 10MB.</p>
                                    @error('attachment')
                                        <p class="text-[10px] font-bold tracking-wide text-blue-600 mt-1.5">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- DIVIDER --}}
                        <div class="h-px w-full bg-gradient-to-r from-transparent via-blue-100 to-transparent mb-8"></div>

                        {{-- SECTION 4: STATUS & SUBMIT --}}
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 bg-blue-50/50 p-5 rounded-2xl border border-blue-100">
                            {{-- Status (read-only) --}}
                            <div class="flex items-center gap-4">
                                <label class="block text-[10px] font-black text-blue-500 uppercase tracking-widest">Status:</label>
                                <span class="px-5 py-2.5 bg-white border border-blue-200 rounded-xl text-sm font-bold {{ $project->status === 'Open' ? 'text-emerald-600' : 'text-slate-500' }} shadow-sm">
                                    {{ $project->status }}
                                </span>
                                <p class="text-[10px] font-semibold text-blue-400">Status proyek hanya diubah lewat aksi "Tutup Proyek".</p>
                            </div>

                            {{-- Buttons --}}
                            <div class="flex items-center gap-3 w-full sm:w-auto">
                                <a href="{{ route('company.projects.show', $project) }}"
                                    class="w-full sm:w-auto px-6 py-3.5 text-[11px] font-black uppercase tracking-widest text-blue-600 bg-white border border-blue-200 rounded-xl hover:bg-blue-50 hover:border-blue-300 transition-colors text-center shadow-sm">
                                    <i class="fa-solid fa-arrow-left mr-1.5"></i>Batal
                                </a>
                                <button type="submit"
                                    class="btn-shimmer w-full sm:w-auto px-8 py-3.5 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition shadow-[0_5px_15px_rgba(37,99,235,0.3)] inline-flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-floppy-disk"></i>
                                    Perbarui Proyek
                                </button>
                            </div>
                        </div>

                    </form>
                </div>

            </div>
        </main>

        @include('navbar.footer')

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const displayInput = document.getElementById('display_budget');
            const realInput = document.getElementById('real_budget');

            if (displayInput && realInput) {
                if (realInput.value) {
                    let initialValue = realInput.value.replace(/[^0-9]/g, '');
                    if (initialValue !== '') {
                        displayInput.value = parseInt(initialValue, 10).toLocaleString('id-ID');
                    }
                }

                displayInput.addEventListener('input', function(e) {
                    let rawValue = this.value.replace(/[^0-9]/g, '');
                    realInput.value = rawValue;

                    if (rawValue !== '') {
                        let formatted = parseInt(rawValue, 10).toLocaleString('id-ID');
                        this.value = formatted;
                    } else {
                        this.value = '';
                    }
                });
            }
        });
    </script>
</body>
</html>
