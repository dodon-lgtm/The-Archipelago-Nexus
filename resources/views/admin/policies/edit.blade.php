@extends('layouts.admin')

@section('title', 'Edit ' . $policy->title)
@section('breadcrumb', 'Kebijakan & Privasi')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('admin.policies.index') }}" class="text-slate-400 hover:text-blue-600">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
            <h2 class="text-xl font-extrabold text-slate-800 dark:text-white">Edit Kebijakan</h2>
        </div>

        <form method="POST" action="{{ route('admin.policies.update', $policy) }}">
            @csrf
            @method('PUT')

            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-blue-100 dark:border-slate-800 shadow-sm p-5 mb-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">

                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1 block">Judul</label>
                        <input type="text" name="title" value="{{ old('title', $policy->title) }}" required
                            class="w-full rounded-xl border-blue-100 dark:border-slate-700 bg-[#f6f9ff] dark:bg-slate-800 px-4 py-2.5 text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none">
                        @error('title')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1 block">Isi Kebijakan (pisahkan paragraf dengan baris baru)</label>
                        <textarea name="content" rows="18" required
                            class="w-full rounded-xl border-blue-100 dark:border-slate-700 bg-[#f6f9ff] dark:bg-slate-800 px-4 py-3 text-sm leading-relaxed font-mono focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none resize-y"
                            placeholder="Ketik isi kebijakan...">{{ old('content', $policy->content) }}</textarea>
                        @error('content')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                                        <div class="flex items-center gap-2 pt-2">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" id="is_active"
                            {{ old('is_active', $policy->is_active) ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-slate-600 bg-slate-800 text-blue-600 focus:ring-blue-500 cursor-pointer">
                        <label for="is_active" class="text-xs text-slate-600 dark:text-slate-300 cursor-pointer select-none">
                            Tampilkan di halaman login / publik
                        </label>
                    </div>
                    <div class="flex items-center gap-2 justify-end pt-2">
                        <span class="text-[10px] text-slate-400">
                            <i class="fa-solid fa-clock-rotate-left mr-1"></i>
                            Diperbarui: {{ $policy->updated_at ? $policy->updated_at->translatedFormat('d M Y H:i') : '-' }}
                        </span>
                        <button type="button" onclick="openPreview()"
                            class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-semibold hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                            <i class="fa-solid fa-eye mr-1"></i> Preview
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.policies.index') }}"
                    class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 rounded-xl text-sm font-semibold hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-600/20 transition flex items-center gap-2">
                    <i class="fa-solid fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

        {{-- Preview Modal --}}
    <div id="previewModal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-[60] hidden">
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-3xl mx-4 p-6 overflow-y-auto max-h-[85vh] border border-blue-100 dark:border-slate-800">
            <div class="flex items-center justify-between mb-4">
                <h3 id="previewTitle" class="font-black text-slate-800 dark:text-white text-sm"></h3>
                <button type="button" onclick="closePreview()"
                    class="w-8 h-8 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-slate-600 flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <div id="previewContent" class="prose prose-sm max-w-none text-slate-600 dark:text-slate-300 leading-relaxed"></div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function escapeHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }
    function openPreview() {
        var title = document.querySelector('input[name="title"]').value;
        var content = document.querySelector('textarea[name="content"]').value;
        var html = content.split(/\r\n|\r|\n/)
            .filter(function (p) { return p.trim() !== ''; })
            .map(function (p) { return '<p>' + escapeHtml(p) + '</p>'; })
            .join('');
        document.getElementById('previewTitle').textContent = title || '(tanpa judul)';
        document.getElementById('previewContent').innerHTML = html;
        var modal = document.getElementById('previewModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
    function closePreview() {
        var modal = document.getElementById('previewModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }
    document.getElementById('previewModal')?.addEventListener('click', function (e) {
        if (e.target === this) closePreview();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closePreview();
    });
</script>
@endpush
