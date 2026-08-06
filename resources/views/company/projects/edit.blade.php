<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Proyek - The Archipelago Nexus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="h-full bg-slate-50 text-slate-800 antialiased selection:bg-blue-600 selection:text-white flex">

    @include('navbar.navigasi')

    <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
        
        <div class="sticky top-0 z-40 bg-white/80 backdrop-blur-md border-b border-slate-200/80 shadow-xs">
            @include('navbar.nav')
        </div>

        <main class="flex-1 overflow-y-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="max-w-3xl mx-auto">

                {{-- Header Section --}}
                <div class="mb-8 flex items-center justify-between">
                    <div>
                        <a href="{{ route('company.projects.show', $project) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:text-blue-700 mb-2 transition">
                            <i class="fa-solid fa-arrow-left"></i>
                            Kembali ke Detail Proyek
                        </a>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Edit Proyek</h1>
                        <p class="text-sm text-slate-500 mt-1">Perbarui informasi dan rincian proyek Anda.</p>
                    </div>
                </div>

                {{-- Form Card --}}
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-8">
                    <form method="POST" action="{{ route('company.projects.update', $project) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- Nama Proyek --}}
                        <div>
                            <label for="project_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Nama Proyek <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <input
                                    type="text"
                                    id="project_name"
                                    name="project_name"
                                    value="{{ old('project_name', $project->project_name) }}"
                                    required
                                    placeholder="Masukkan nama proyek..."
                                    class="w-full px-4 py-3 rounded-xl border text-sm transition focus:outline-hidden focus:ring-2 focus:ring-blue-500/20 
                                    @error('project_name') border-rose-400 bg-rose-50/30 text-rose-900 focus:border-rose-500 @else border-slate-200 bg-slate-50/50 text-slate-900 focus:border-blue-500 focus:bg-white @enderror"
                                >
                            </div>
                            @error('project_name')
                                <p class="mt-1.5 text-xs text-rose-500 font-semibold flex items-center gap-1">
                                    <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Kategori --}}
                        <div>
                            <label for="category_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Kategori Proyek
                            </label>
                            <div class="relative">
                                <select
                                    id="category_id"
                                    name="category_id"
                                    class="w-full px-4 py-3 rounded-xl border text-sm transition appearance-none bg-no-repeat focus:outline-hidden focus:ring-2 focus:ring-blue-500/20
                                    @error('category_id') border-rose-400 bg-rose-50/30 text-rose-900 focus:border-rose-500 @else border-slate-200 bg-slate-50/50 text-slate-900 focus:border-blue-500 focus:bg-white @enderror"
                                >
                                    <option value="" {{ old('category_id', $project->category_id) ? '' : 'selected' }}>(Tanpa kategori)</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ (string) old('category_id', $project->category_id) === (string) $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            @error('category_id')
                                <p class="mt-1.5 text-xs text-rose-500 font-semibold flex items-center gap-1">
                                    <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Deskripsi Proyek --}}
                        <div>
                            <label for="project_description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Deskripsi Proyek
                            </label>
                            <textarea
                                id="project_description"
                                name="project_description"
                                rows="5"
                                placeholder="Jelaskan kebutuhan, ruang lingkup, dan ekspektasi proyek..."
                                class="w-full px-4 py-3 rounded-xl border text-sm transition focus:outline-hidden focus:ring-2 focus:ring-blue-500/20
                                @error('project_description') border-rose-400 bg-rose-50/30 text-rose-900 focus:border-rose-500 @else border-slate-200 bg-slate-50/50 text-slate-900 focus:border-blue-500 focus:bg-white @enderror"
                            >{{ old('project_description', $project->project_description) }}</textarea>
                            @error('project_description')
                                <p class="mt-1.5 text-xs text-rose-500 font-semibold flex items-center gap-1">
                                    <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Action Buttons --}}
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                            <a href="{{ route('company.projects.show', $project) }}" 
                               class="px-5 py-2.5 text-xs font-bold text-slate-600 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center gap-2 px-6 py-2.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 active:bg-blue-800 rounded-xl shadow-xs shadow-blue-500/20 transition">
                                <i class="fa-solid fa-floppy-disk"></i>
                                Perbarui Proyek
                            </button>
                        </div>
                    </form>
                </div>

                <div class="mt-12">
                    @include('navbar.footer')
                </div>

            </div>
        </main>
    </div>

</body>
</html>