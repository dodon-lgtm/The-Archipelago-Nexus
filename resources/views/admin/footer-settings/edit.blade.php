@extends('layouts.admin')

@section('title', 'Pengaturan Footer')
@section('breadcrumb', 'Pengaturan Footer')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('admin.dashboard') }}" class="text-slate-400 hover:text-blue-600">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
            <h2 class="text-xl font-extrabold text-slate-800 dark:text-white">Edit Pengaturan Footer</h2>
        </div>

        <form method="POST" action="{{ route('admin.footer-settings.update') }}">
            @csrf
            @method('PUT')

            {{-- Informasi Umum --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-blue-100 dark:border-slate-800 shadow-sm p-5 mb-5">
                <h3 class="text-sm font-black text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-blue-600 dark:text-blue-400"></i> Informasi Umum
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1 block">Email Dukungan</label>
                        <input type="email" name="support_email" value="{{ old('support_email', $setting->support_email) }}"
                            class="w-full rounded-xl border-blue-100 dark:border-slate-700 bg-[#f6f9ff] dark:bg-slate-800 px-4 py-2.5 text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none"
                            placeholder="support@example.com">
                        @error('support_email')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1 block">Teks Hak Cipta</label>
                        <input type="text" name="copyright_text" value="{{ old('copyright_text', $setting->copyright_text) }}"
                            class="w-full rounded-xl border-blue-100 dark:border-slate-700 bg-[#f6f9ff] dark:bg-slate-800 px-4 py-2.5 text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none"
                            placeholder="© 2026 ApexForge Labs. Hak Cipta Dilindungi.">
                        @error('copyright_text')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1 block">Tentang Perusahaan (Deskripsi Singkat)</label>
                    <textarea name="about_text" rows="4"
                        class="w-full rounded-xl border-blue-100 dark:border-slate-700 bg-[#f6f9ff] dark:bg-slate-800 px-4 py-3 text-sm leading-relaxed focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none resize-y"
                        placeholder="Deskripsi singkat perusahaan yang ditampilkan di footer...">{{ old('about_text', $setting->about_text) }}</textarea>
                    @error('about_text')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Dokumen Legal --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-blue-100 dark:border-slate-800 shadow-sm p-5 mb-5">
                <h3 class="text-sm font-black text-slate-800 dark:text-white mb-1 flex items-center gap-2">
                    <i class="fa-solid fa-file-shield text-blue-600 dark:text-blue-400"></i> Dokumen Legal
                </h3>
                <p class="text-[11px] text-slate-400 mb-4">Pisahkan tiap paragraf dengan baris baru. Konten tampil di halaman <code class="px-1 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-blue-500">/kebijakan-privasi</code> dan <code class="px-1 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-blue-500">/syarat-ketentuan</code>.</p>

                <div class="mb-4">
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-xs font-semibold text-slate-600 dark:text-slate-300 block">Kebijakan Privasi</label>
                        <button type="button" onclick="openPreview('privacy')"
                            class="text-[11px] px-2.5 py-1 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 rounded-lg font-semibold hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                            <i class="fa-solid fa-eye mr-1"></i> Preview
                        </button>
                    </div>
                    <textarea name="privacy_policy_content" id="privacy_policy_content" rows="10"
                        class="w-full rounded-xl border-blue-100 dark:border-slate-700 bg-[#f6f9ff] dark:bg-slate-800 px-4 py-3 text-sm leading-relaxed font-mono focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none resize-y"
                        placeholder="Isi Kebijakan Privasi...">{{ old('privacy_policy_content', $setting->privacy_policy_content) }}</textarea>
                    @error('privacy_policy_content')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-xs font-semibold text-slate-600 dark:text-slate-300 block">Syarat &amp; Ketentuan</label>
                        <button type="button" onclick="openPreview('terms')"
                            class="text-[11px] px-2.5 py-1 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 rounded-lg font-semibold hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                            <i class="fa-solid fa-eye mr-1"></i> Preview
                        </button>
                    </div>
                    <textarea name="terms_conditions_content" id="terms_conditions_content" rows="10"
                        class="w-full rounded-xl border-blue-100 dark:border-slate-700 bg-[#f6f9ff] dark:bg-slate-800 px-4 py-3 text-sm leading-relaxed font-mono focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none resize-y"
                        placeholder="Isi Syarat & Ketentuan...">{{ old('terms_conditions_content', $setting->terms_conditions_content) }}</textarea>
                    @error('terms_conditions_content')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.dashboard') }}"
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
    function openPreview(type) {
        var id = type === 'privacy' ? 'privacy_policy_content' : 'terms_conditions_content';
        var title = type === 'privacy' ? 'Kebijakan Privasi' : 'Syarat & Ketentuan';
        var content = document.getElementById(id).value;
        var html = content.split(/\r\n|\r|\n/)
            .filter(function (p) { return p.trim() !== ''; })
            .map(function (p) { return '<p>' + escapeHtml(p) + '</p>'; })
            .join('');
        document.getElementById('previewTitle').textContent = title;
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