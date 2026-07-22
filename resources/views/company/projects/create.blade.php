<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buat Proyek Baru - The Archipelago Nexus</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: { brand: '#2563EB', surface: '#F8FAFC' }
                }
            }
        }
    </script>
</head>
<body class="bg-surface text-slate-800 min-h-screen flex font-sans">

    {{-- SIDEBAR --}}
    @include('navbar.navigasi')

    {{-- AREA KANAN --}}
    <div class="flex-1 flex flex-col min-h-screen overflow-hidden">

        {{-- NAVBAR --}}
        @include('navbar.nav')

        {{-- KONTEN --}}
        <main class="flex-1 overflow-y-auto p-6">
            <div class="max-w-4xl mx-auto space-y-6">

                {{-- BREADCRUMB --}}
                <nav class="flex items-center gap-2 text-sm text-slate-400">
                    <a href="{{ route('company.dashboard') }}" class="hover:text-brand transition">Dashboard</a>
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    <a href="{{ route('company.projects.index') }}" class="hover:text-brand transition">Proyek</a>
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    <span class="text-slate-700 font-semibold">Buat Proyek</span>
                </nav>

                {{-- HEADER --}}
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Buat Proyek Baru</h1>
                    <p class="text-sm text-slate-500 mt-1">Jelaskan kebutuhan proyek Anda dan temukan freelancer terbaik untuk membantu mewujudkannya.</p>
                </div>

                {{-- SUCCESS MESSAGE --}}
                @if(session('success'))
                    <div class="px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium rounded-lg flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-500"></i>
                        {{ session('success') }}
                    </div>
                @endif

                {{-- FORM CARD --}}
                <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
                    <form method="POST" action="{{ route('company.projects.store') }}" enctype="multipart/form-data" class="p-6 lg:p-8">
                        @csrf

                        {{-- VALIDATION ERRORS (Global) --}}
                        @if ($errors->any())
                            <div class="mb-6 px-4 py-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg">
                                <div class="font-semibold mb-1"><i class="fa-solid fa-circle-exclamation mr-1"></i> Mohon perbaiki kesalahan berikut:</div>
                                <ul class="list-disc list-inside space-y-0.5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- SECTION 1: INFORMASI PROYEK --}}
                        <div class="mb-8">
                            <div class="flex items-center gap-2 mb-5">
                                <div class="w-8 h-8 rounded-lg bg-brand/10 text-brand flex items-center justify-center text-sm">
                                    <i class="fa-solid fa-clipboard-list"></i>
                                </div>
                                <div>
                                    <h2 class="text-base font-bold text-slate-800">Informasi Proyek</h2>
                                    <p class="text-[11px] text-slate-400">Lengkapi detail dasar proyek Anda</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                                {{-- Nama Proyek --}}
                                <div class="lg:col-span-2">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Proyek <span class="text-red-500">*</span></label>
                                    <input type="text" name="project_name" value="{{ old('project_name') }}"
                                        class="w-full px-4 py-2.5 border @error('project_name') border-red-400 ring-2 ring-red-100 @else border-slate-300 @enderror rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand transition placeholder:text-slate-400"
                                        placeholder="Contoh: Pengembangan Website E-commerce" required>
                                    <p class="text-[11px] text-slate-400 mt-1">Berikan nama yang singkat dan jelas untuk proyek Anda.</p>
                                    @error('project_name')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Kategori --}}
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kategori</label>
                                    <select name="category_id"
                                        class="w-full px-4 py-2.5 border @error('category_id') border-red-400 ring-2 ring-red-100 @else border-slate-300 @enderror rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand transition bg-white">
                                        <option value="">Pilih Kategori</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Skills --}}
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Skill yang Dibutuhkan <span class="text-red-500">*</span></label>
                                    <input type="text" name="skills" value="{{ old('skills') }}"
                                        class="w-full px-4 py-2.5 border @error('skills') border-red-400 ring-2 ring-red-100 @else border-slate-300 @enderror rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand transition placeholder:text-slate-400"
                                        placeholder="Laravel, Bootstrap, MySQL">
                                    <p class="text-[11px] text-slate-400 mt-1">Pisahkan dengan koma.</p>
                                    @error('skills')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Deskripsi --}}
                                <div class="lg:col-span-2">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi Proyek <span class="text-red-500">*</span></label>
                                    <textarea name="project_description" rows="6"
                                        class="w-full px-4 py-2.5 border @error('project_description') border-red-400 ring-2 ring-red-100 @else border-slate-300 @enderror rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand transition placeholder:text-slate-400 resize-y"
                                        placeholder="Jelaskan kebutuhan, tujuan, dan hasil yang Anda harapkan dari freelancer.">{{ old('project_description') }}</textarea>
                                    @error('project_description')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- DIVIDER --}}
                        <hr class="border-slate-200 mb-8">

                        {{-- SECTION 2: ANGGARAN & WAKTU --}}
                        <div class="mb-8">
                            <div class="flex items-center gap-2 mb-5">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                                    <i class="fa-solid fa-coins"></i>
                                </div>
                                <div>
                                    <h2 class="text-base font-bold text-slate-800">Anggaran & Waktu</h2>
                                    <p class="text-[11px] text-slate-400">Tentukan budget dan batas waktu pengerjaan</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                                {{-- Budget --}}
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Budget (Rp) <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-medium">Rp</span>
                                        <input type="number" name="budget" value="{{ old('budget') }}"
                                            class="w-full pl-9 pr-4 py-2.5 border @error('budget') border-red-400 ring-2 ring-red-100 @else border-slate-300 @enderror rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand transition placeholder:text-slate-400"
                                            placeholder="5000000" required>
                                    </div>
                                    @error('budget')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Deadline --}}
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deadline <span class="text-red-500">*</span></label>
                                    <input type="date" name="deadline" value="{{ old('deadline') }}"
                                        class="w-full px-4 py-2.5 border @error('deadline') border-red-400 ring-2 ring-red-100 @else border-slate-300 @enderror rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand transition" required>
                                    @error('deadline')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- DIVIDER --}}
                        <hr class="border-slate-200 mb-8">

                        {{-- SECTION 3: DETAIL TAMBAHAN --}}
                        <div class="mb-8">
                            <div class="flex items-center gap-2 mb-5">
                                <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-sm">
                                    <i class="fa-solid fa-paperclip"></i>
                                </div>
                                <div>
                                    <h2 class="text-base font-bold text-slate-800">Detail Tambahan</h2>
                                    <p class="text-[11px] text-slate-400">Lampirkan file pendukung jika diperlukan</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                                {{-- Gambar --}}
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Gambar Proyek</label>
                                    <div class="relative">
                                        <input type="file" name="image" accept="image/*" id="imageInput"
                                            class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-brand/10 file:text-brand hover:file:bg-brand/20 transition cursor-pointer @error('image') border border-red-400 ring-2 ring-red-100 rounded-lg @enderror">
                                    </div>
                                    <p class="text-[11px] text-slate-400 mt-1">Format: JPG, PNG, WebP. Max 2MB.</p>
                                    @error('image')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Lampiran --}}
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Lampiran (PDF/DOC)</label>
                                    <div class="relative">
                                        <input type="file" name="attachment" accept=".pdf,.doc,.docx" id="attachmentInput"
                                            class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-brand/10 file:text-brand hover:file:bg-brand/20 transition cursor-pointer @error('attachment') border border-red-400 ring-2 ring-red-100 rounded-lg @enderror">
                                    </div>
                                    <p class="text-[11px] text-slate-400 mt-1">Format: PDF, DOC, DOCX. Max 10MB.</p>
                                    @error('attachment')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- DIVIDER --}}
                        <hr class="border-slate-200 mb-8">

                        {{-- SECTION 4: STATUS & SUBMIT --}}
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            {{-- Status --}}
                            <div class="flex items-center gap-4">
                                <label class="text-sm font-semibold text-slate-700">Status:</label>
                                <select name="status"
                                    class="px-4 py-2 border border-slate-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand transition">
                                    <option value="Open" {{ old('status') == 'Open' ? 'selected' : '' }}>Open</option>
                                    <option value="Closed" {{ old('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </div>

                            {{-- Buttons --}}
                            <div class="flex items-center gap-3">
                                <a href="{{ route('company.projects.index') }}"
                                    class="px-5 py-2.5 text-sm font-semibold text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-50 transition text-center">
                                    <i class="fa-solid fa-arrow-left mr-1.5"></i>Batal
                                </a>
                                <button type="submit"
                                    class="px-6 py-2.5 text-sm font-semibold text-white bg-brand hover:bg-blue-700 rounded-lg shadow-sm transition inline-flex items-center gap-2">
                                    <i class="fa-solid fa-rocket"></i>
                                    Publikasikan Proyek
                                </button>
                            </div>
                        </div>

                    </form>
                </div>

            </div>
        </main>

        {{-- FOOTER --}}
        @include('navbar.footer')

    </div>

</body>
</html>
