@extends('layouts.admin')

@section('title', 'Kategori')
@section('breadcrumb', 'Kategori')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Add Category Form --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <h2 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-plus-circle text-cyan-500"></i> Tambah Kategori
            </h2>
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="text-xs font-semibold text-slate-600 mb-1 block">Nama Kategori</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 outline-none"
                           placeholder="Contoh: Web Development">
                    @error('name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="w-full px-4 py-2.5 bg-cyan-500 hover:bg-cyan-600 text-white rounded-xl text-sm font-semibold transition">
                    <i class="fa-solid fa-plus mr-1"></i> Tambah Kategori
                </button>
            </form>
        </div>

        {{-- Categories List --}}
        <div class="lg:col-span-2">
            {{-- Search --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-4 mb-4 shadow-sm">
                <form method="GET" action="{{ route('admin.categories.index') }}" class="flex gap-3 items-end">
                    <div class="flex-1">
                        <label class="text-xs font-semibold text-slate-600 mb-1 block">Cari Kategori</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 outline-none"
                               placeholder="Ketik nama kategori...">
                    </div>
                    <button type="submit" class="px-5 py-2.5 bg-cyan-500 hover:bg-cyan-600 text-white rounded-xl text-sm font-semibold transition">
                        <i class="fa-solid fa-search mr-1"></i> Cari
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-sm font-semibold transition">
                        Reset
                    </a>
                </form>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="text-left px-5 py-3 font-bold text-slate-600 text-xs uppercase">Nama Kategori</th>
                                <th class="text-center px-5 py-3 font-bold text-slate-600 text-xs uppercase">Jumlah Proyek</th>
                                <th class="text-center px-5 py-3 font-bold text-slate-600 text-xs uppercase">Dibuat</th>
                                <th class="text-right px-5 py-3 font-bold text-slate-600 text-xs uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($categories as $category)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-5 py-4 font-semibold text-slate-800">{{ $category->name }}</td>
                                    <td class="px-5 py-4 text-center">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-cyan-50 text-cyan-600 text-xs font-bold">
                                            {{ $category->projects_count }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-center text-slate-500 text-xs">{{ $category->created_at->format('d M Y') }}</td>
                                    <td class="px-5 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button type="button" onclick="openEditModal({{ $category->id }}, '{{ $category->name }}')"
                                                    class="px-3 py-1.5 text-xs font-semibold bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg transition">
                                                <i class="fa-solid fa-pen mr-1"></i> Edit
                                            </button>
                                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                                  onsubmit="return confirm('Hapus kategori {{ $category->name }}?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1.5 text-xs font-semibold bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition"
                                                    @if($category->projects_count > 0) disabled title="Kategori memiliki proyek" @endif>
                                                    <i class="fa-solid fa-trash-can mr-1"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-12 text-center text-sm text-slate-400">Belum ada kategori.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $categories->links() }}
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div id="editModal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-slate-800">Edit Kategori</h2>
                <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="text-xs font-semibold text-slate-600 mb-1 block">Nama Kategori</label>
                    <input type="text" name="name" id="editName" required
                           class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 outline-none">
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-cyan-500 hover:bg-cyan-600 text-white rounded-xl text-sm font-semibold transition">
                        Simpan Perubahan
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
</script>
@endpush
