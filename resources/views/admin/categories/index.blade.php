@extends('layouts.admin')

@section('title', 'Kategori')
@section('breadcrumb', 'Kategori')

@section('content')
<x-admin.page-header icon="fa-tags" title="Kategori" description="Kelola kategori proyek platform"
        :count="$categories->total()" countLabel="kategori" countIcon="fa-tags" />
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Add Category Form --}}
        <div class="bg-white rounded-2xl border border-blue-100 shadow-sm p-5 self-start">
            <h2 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center"><i class="fa-solid fa-plus text-xs"></i></span>
                Tambah Kategori
            </h2>
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="text-[11px] font-bold uppercase tracking-wide text-slate-500 mb-1.5 block">Nama Kategori</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full rounded-xl border-blue-100 bg-[#f6f9ff] px-4 py-2.5 text-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:bg-white"
                           placeholder="Contoh: Web Development">
                    @error('name')
                        <p class="text-xs text-red-500 mt-1 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="w-full px-4 py-2.5 bg-blue-500 hover:bg-blue-600 active:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow-sm transition">
                    <i class="fa-solid fa-plus mr-1"></i> Tambah Kategori
                </button>
            </form>
        </div>

        {{-- Categories List --}}
        <div class="lg:col-span-2">
            {{-- Search --}}
            <div class="bg-white rounded-2xl border border-blue-100 p-4 mb-4 shadow-sm">
                <form method="GET" action="{{ route('admin.categories.index') }}" class="flex flex-col sm:flex-row gap-3 sm:items-end">
                    <div class="flex-1">
                        <label class="text-[11px] font-bold uppercase tracking-wide text-slate-500 mb-1.5 block">
                            <i class="fa-solid fa-magnifying-glass mr-1 text-blue-400"></i> Cari Kategori
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="w-full rounded-xl border-blue-100 bg-[#f6f9ff] px-4 py-2.5 text-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:bg-white"
                               placeholder="Ketik nama kategori...">
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="submit" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 px-5 py-2.5 bg-blue-500 hover:bg-blue-600 active:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow-sm transition">
                            <i class="fa-solid fa-search"></i> Cari
                        </button>
                        <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-sm font-semibold transition">
                            <i class="fa-solid fa-rotate-left text-xs"></i> Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm min-w-[520px]">
                        <thead class="bg-[#f6f9ff] border-b border-blue-100">
                            <tr>
                                <th class="text-left px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Nama Kategori</th>
                                <th class="text-center px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Jumlah Proyek</th>
                                <th class="text-center px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Dibuat</th>
                                <th class="text-right px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($categories as $category)
                                <tr class="hover:bg-[#f6f9ff]/70 transition-colors">
                                    <td class="px-5 py-4">
                                        <span class="font-semibold text-slate-800 inline-flex items-center gap-2.5">
                                            <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-100 to-blue-50 ring-1 ring-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                                                <i class="fa-solid fa-tag text-xs"></i>
                                            </span>
                                            {{ $category->name }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-blue-600 text-xs font-bold">
                                            {{ $category->projects_count }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-center text-slate-500 text-xs whitespace-nowrap">{{ $category->created_at->format('d M Y') }}</td>
                                    <td class="px-5 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button type="button" onclick="openEditModal({{ $category->id }}, '{{ $category->name }}')"
                                                    class="px-3 py-1.5 text-xs font-semibold bg-blue-50 text-blue-600 hover:bg-blue-100 active:bg-blue-200 rounded-lg transition">
                                                <i class="fa-solid fa-pen mr-1"></i> Edit
                                            </button>
                                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                                  onsubmit="return adminConfirm('Hapus kategori {{ $category->name }}?', this)" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1.5 text-xs font-semibold bg-red-50 text-red-600 hover:bg-red-100 active:bg-red-200 rounded-lg transition disabled:opacity-40 disabled:cursor-not-allowed"
                                                    @if($category->projects_count > 0) disabled title="Kategori memiliki proyek" @endif>
                                                    <i class="fa-solid fa-trash-can mr-1"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-16 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-14 h-14 rounded-2xl bg-blue-50 border border-blue-100 text-blue-400 flex items-center justify-center text-xl">
                                                <i class="fa-solid fa-tags"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-slate-600">Belum ada kategori</p>
                                                <p class="text-xs text-slate-400 mt-1">Tambahkan kategori pertama melalui formulir di samping.</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <x-admin.pagination :paginator="$categories" />
        </div>
    </div>

    {{-- Edit Modal --}}
    <div id="editModal" class="fixed inset-0 bg-black/40 backdrop-blur-[2px] flex items-center justify-center z-50 hidden p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-auto overflow-hidden">
            <div class="px-6 py-4 border-b border-blue-50 flex items-center justify-between">
                <h2 class="font-bold text-slate-800 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center"><i class="fa-solid fa-pen text-xs"></i></span>
                    Edit Kategori
                </h2>
                <button type="button" onclick="closeEditModal()" class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 flex items-center justify-center transition" aria-label="Tutup modal">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form id="editForm" method="POST" class="p-6">
                @csrf
                <div class="mb-5">
                    <label class="text-[11px] font-bold uppercase tracking-wide text-slate-500 mb-1.5 block">Nama Kategori</label>
                    <input type="text" name="name" id="editName" required
                           class="w-full rounded-xl border-blue-100 bg-[#f6f9ff] px-4 py-2.5 text-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:bg-white">
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-blue-500 hover:bg-blue-600 active:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow-sm transition">
                        <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan Perubahan
                    </button>
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-sm font-semibold transition">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openEditModal(id, name) {
        document.getElementById('editName').value = name;
        document.getElementById('editForm').action = '{{ url("/admin/categories") }}/' + id;
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    document.getElementById('editModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeEditModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeEditModal();
    });
</script>
@endpush
