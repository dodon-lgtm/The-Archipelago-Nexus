<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Laporan - ApexForge Labs</title>
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = tailwind.config || {};
        tailwind.config.darkMode = 'class';
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* High-Tech Scrollbar (Blue/White Theme) */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(59, 130, 246, 0.2);
            border-radius: 9999px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(59, 130, 246, 0.5);
        }

        /* Shader Glowing Effects */
        .shader-glow-blue {
            box-shadow: 0 0 40px rgba(59, 130, 246, 0.15);
        }

        .shader-glow-white {
            box-shadow: 0 0 30px rgba(255, 255, 255, 1);
        }

        /* Advanced Pristine Glassmorphism */
        .glass-panel-pristine {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(32px);
            -webkit-backdrop-filter: blur(32px);
            border: 1px solid rgba(255, 255, 255, 1);
            box-shadow:
                0 30px 60px -15px rgba(30, 58, 138, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 1),
                inset 0 0 20px rgba(59, 130, 246, 0.05);
        }

        /* Cyber Grid Background (Light/Blue) */
        .hologram-grid-blue {
            background-color: #ffffff;
            background-image:
                linear-gradient(to right, rgba(59, 130, 246, 0.06) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(59, 130, 246, 0.06) 1px, transparent 1px);
            background-size: 48px 48px;
        }

        /* Floating Ambient Orbs (Strictly Blue & White) */
        @keyframes drift {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            33% {
                transform: translate(50px, -40px) scale(1.1);
            }

            66% {
                transform: translate(-30px, 40px) scale(0.9);
            }
        }

        .animate-orb-1 {
            animation: drift 18s ease-in-out infinite;
        }

        .animate-orb-2 {
            animation: drift 22s ease-in-out infinite reverse;
        }

        /* Custom Input Autofill styling for transparency */
        input:-webkit-autofill,
        textarea:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 30px white inset !important;
            -webkit-text-fill-color: #1e3a8a !important;
        }
    </style>
<style>

/* ApexForge Labs — Unified UI System */
:root{
    --af-primary:#2563eb;
    --af-primary-dark:#1d4ed8;
    --af-primary-soft:#eff6ff;
    --af-sky:#38bdf8;
    --af-ink:#0f172a;
    --af-muted:#64748b;
    --af-border:#dbeafe;
    --af-surface:#ffffff;
    --af-page:#f6f9ff;
}
html{scroll-behavior:smooth}
body{
    font-family:'Plus Jakarta Sans',sans-serif;
    background:
        radial-gradient(circle at 10% -10%,rgba(56,189,248,.10),transparent 30%),
        radial-gradient(circle at 100% 0%,rgba(37,99,235,.08),transparent 28%),
        var(--af-page);
}
::selection{background:rgba(37,99,235,.18);color:#0f172a}
::-webkit-scrollbar{width:7px;height:7px}
::-webkit-scrollbar-track{background:rgba(241,245,249,.7)}
::-webkit-scrollbar-thumb{background:rgba(37,99,235,.22);border-radius:999px}
::-webkit-scrollbar-thumb:hover{background:rgba(37,99,235,.38)}

input,select,textarea{
    border-color:var(--af-border)!important;
    background:rgba(255,255,255,.92);
    transition:border-color .2s ease,box-shadow .2s ease,background .2s ease;
}
input:focus,select:focus,textarea:focus{
    border-color:rgba(37,99,235,.55)!important;
    box-shadow:0 0 0 4px rgba(37,99,235,.09)!important;
    outline:none!important;
}
button,a,[role="button"]{transition:all .2s ease}
button:focus-visible,a:focus-visible,[role="button"]:focus-visible{
    outline:2px solid rgba(37,99,235,.55);
    outline-offset:2px;
}
table{border-collapse:separate;border-spacing:0}
thead th{
    background:rgba(239,246,255,.72)!important;
    color:#334155;
    font-weight:700;
}
tbody tr{transition:background .18s ease}
tbody tr:hover{background:rgba(239,246,255,.48)}
[class*="bg-blue-600"]{
    box-shadow:0 8px 22px -12px rgba(37,99,235,.72);
}
[class*="bg-blue-600"]:hover{
    box-shadow:0 12px 28px -12px rgba(37,99,235,.78);
    transform:translateY(-1px);
}
.glass-panel,.glass-card,.glass-surface{
    background:rgba(255,255,255,.72);
    border:1px solid rgba(219,234,254,.85);
    backdrop-filter:blur(18px);
    -webkit-backdrop-filter:blur(18px);
    box-shadow:0 18px 50px -32px rgba(30,64,175,.32);
}
.apex-page-glow{
    position:fixed;inset:auto -10rem -12rem auto;width:28rem;height:28rem;
    background:rgba(56,189,248,.09);filter:blur(70px);border-radius:999px;
    pointer-events:none;z-index:-1;
}
@media (max-width:767px){
    main{padding-left:1rem!important;padding-right:1rem!important}
    table{min-width:680px}
    .overflow-x-auto{-webkit-overflow-scrolling:touch}
}
@media (prefers-reduced-motion:reduce){
    *,*::before,*::after{animation-duration:.01ms!important;animation-iteration-count:1!important;transition-duration:.01ms!important;scroll-behavior:auto!important}
}

</style>
</head>

<body class="bg-white dark:bg-slate-900 text-blue-950 dark:text-white antialiased min-h-screen flex transition-colors duration-300">

    @include('navbar.navigasi')

    <div class="flex-1 flex flex-col min-h-screen overflow-hidden">
        @include('navbar.nav')

        {{-- PURE BLUE & WHITE HOLOGRAPHIC MAIN CONTAINER --}}
        <main class="flex-1 overflow-y-auto relative bg-white dark:bg-slate-900">

            {{-- Ambient Lighting & Hologram Background Layers --}}
            <div class="absolute inset-0 z-0 pointer-events-none hologram-grid-blue"></div>

            {{-- Glowing Shaders (Strictly Blue and White) --}}
            <div
                class="fixed top-[-10%] left-[-10%] w-[50rem] h-[50rem] bg-gradient-to-br from-blue-100/60 to-blue-400/20 rounded-full blur-[120px] animate-orb-1 pointer-events-none">
            </div>
            <div
                class="fixed bottom-[-10%] right-[-5%] w-[45rem] h-[45rem] bg-gradient-to-tl from-blue-500/15 to-blue-200/30 rounded-full blur-[100px] animate-orb-2 pointer-events-none">
            </div>
            <div
                class="fixed top-1/3 left-1/3 w-[30rem] h-[30rem] bg-white rounded-full blur-[100px] pointer-events-none opacity-80 mix-blend-overlay">
            </div>

            <div class="relative z-10 px-4 py-12 sm:p-10 max-w-4xl mx-auto min-h-full flex flex-col justify-center">

                {{-- ALERTS: FUTURISTIC GLASS ALERTS --}}
                @if (session('success'))
                    <div
                        class="mb-8 overflow-hidden relative bg-white dark:bg-slate-900 border border-blue-200 dark:border-slate-700 backdrop-blur-xl p-5 rounded-[1.5rem] shadow-[0_10px_30px_rgba(59,130,246,0.1)] flex items-start gap-4">
                        <div
                            class="w-10 h-10 rounded-full bg-blue-50 dark:bg-slate-800 flex items-center justify-center shrink-0 border border-blue-100 dark:border-slate-800">
                            <i class="fa-solid fa-check text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div class="pt-2 font-bold text-blue-900 dark:text-white">{{ session('success') }}</div>
                    </div>
                @endif

                @if (session('error'))
                    <div
                        class="mb-8 overflow-hidden relative bg-white dark:bg-slate-900 border border-red-200 dark:border-red-900 backdrop-blur-xl p-5 rounded-[1.5rem] shadow-[0_10px_30px_rgba(225,29,72,0.1)] flex items-start gap-4">
                        <div
                            class="w-10 h-10 rounded-full bg-red-50 dark:bg-red-900/40 flex items-center justify-center shrink-0 border border-red-100 dark:border-red-900">
                            <i class="fa-solid fa-xmark text-red-500"></i>
                        </div>
                        <div class="pt-2 font-bold text-red-600 dark:text-red-300">{{ session('error') }}</div>
                    </div>
                @endif

                @if ($errors->any())
                    <div
                        class="mb-8 overflow-hidden relative bg-white dark:bg-slate-900 border border-red-200 dark:border-red-900 backdrop-blur-xl p-5 rounded-[1.5rem] shadow-[0_10px_30px_rgba(225,29,72,0.1)] flex items-start gap-4">
                        <div
                            class="w-10 h-10 rounded-full bg-red-50 dark:bg-red-900/40 flex items-center justify-center shrink-0 border border-red-100 dark:border-red-900">
                            <i class="fa-solid fa-triangle-exclamation text-red-500"></i>
                        </div>
                        <div class="pt-1.5 text-sm font-bold text-red-500 dark:text-red-300 space-y-1">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- FORM CONTAINER: PRISTINE WHITE GLASS --}}
                <div class="glass-panel-pristine dark:bg-slate-900 rounded-[2.5rem] relative overflow-hidden">

                    {{-- Decorative Top Accent Line --}}
                    <div
                        class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-white via-blue-400 to-white opacity-80">
                    </div>

                    <div class="p-8 sm:p-12">
                        {{-- HEADER SECTION --}}
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6 mb-10">
                            <div
                                class="w-16 h-16 rounded-3xl bg-gradient-to-br from-blue-500 to-blue-700 text-white flex items-center justify-center text-2xl shader-glow-blue relative group shadow-[0_10px_20px_rgba(59,130,246,0.3)]">
                                <div
                                    class="absolute inset-0 bg-white/20 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity">
                                </div>
                                <i class="fa-solid fa-flag"></i>
                            </div>
                            <div>
                                <h2 class="text-3xl font-black text-blue-950 dark:text-white tracking-tight mb-1">Buat Laporan</h2>
                                <p class="text-sm font-semibold text-blue-600/70 dark:text-blue-400/70">Laporkan masalah, pengguna, atau
                                    proyek yang melanggar protokol</p>
                            </div>
                        </div>

                        {{-- MAIN FORM --}}
                        <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data"
                            class="space-y-8">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                {{-- Kategori --}}
                                <div class="relative group">
                                    <label
                                        class="text-[10px] font-extrabold text-blue-500 dark:text-blue-400 uppercase tracking-widest mb-2 block">Kategori
                                        Laporan <span class="text-blue-600">*</span></label>
                                    <div class="relative">
                                        <select name="category"
                                            class="w-full appearance-none rounded-2xl border border-blue-100 dark:border-slate-700 bg-white/80 dark:bg-slate-800 px-5 py-4 text-sm font-bold text-blue-950 dark:text-white transition-all duration-300 focus:border-blue-500 focus:bg-white dark:focus:bg-slate-800 focus:ring-4 focus:ring-blue-500/10 outline-none shadow-sm group-hover:border-blue-300 @error('category') border-red-300 focus:border-red-500 focus:ring-red-500/20 @enderror">
                                            <option value="" disabled selected>Pilih Kategori...</option>
                                            @foreach (\App\Models\Report::categoriesForTarget(\App\Models\Report::TARGET_WEBSITE) as $cat)
                                                <option value="{{ $cat }}" @selected(old('category') == $cat)>
                                                    {{ \App\Models\Report::categoryLabel($cat) }}</option>
                                            @endforeach
                                        </select>
                                        <div
                                            class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-blue-400 dark:text-slate-400">
                                            <i class="fa-solid fa-chevron-down text-xs"></i>
                                        </div>
                                    </div>
                                    @error('category')
                                        <p class="text-xs font-bold text-red-500 mt-2 flex items-center gap-1"><i
                                                class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Subjek --}}
                                <div class="relative group">
                                    <label
                                        class="text-[10px] font-extrabold text-blue-500 dark:text-blue-400 uppercase tracking-widest mb-2 block">Subjek
                                        Laporan <span class="text-blue-600">*</span></label>
                                    <input type="text" name="subject" value="{{ old('subject') }}"
                                        class="w-full rounded-2xl border border-blue-100 dark:border-slate-700 bg-white/80 dark:bg-slate-800 px-5 py-4 text-sm font-bold text-blue-950 dark:text-white transition-all duration-300 focus:border-blue-500 focus:bg-white dark:focus:bg-slate-800 focus:ring-4 focus:ring-blue-500/10 outline-none shadow-sm group-hover:border-blue-300 placeholder-blue-300 dark:placeholder:text-slate-500 @error('subject') border-red-300 focus:border-red-500 focus:ring-red-500/20 @enderror"
                                        placeholder="Contoh: Pengguna mencurigakan...">
                                    @error('subject')
                                        <p class="text-xs font-bold text-red-500 mt-2 flex items-center gap-1"><i
                                                class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Deskripsi --}}
                            <div class="relative group">
                                <label
                                    class="text-[10px] font-extrabold text-blue-500 dark:text-blue-400 uppercase tracking-widest mb-2 block">Deskripsi
                                    Detail <span class="text-blue-600">*</span></label>
                                <textarea name="description" rows="5"
                                    class="w-full rounded-2xl border border-blue-100 dark:border-slate-700 bg-white/80 dark:bg-slate-800 px-5 py-4 text-sm font-bold text-blue-950 dark:text-white transition-all duration-300 focus:border-blue-500 focus:bg-white dark:focus:bg-slate-800 focus:ring-4 focus:ring-blue-500/10 outline-none shadow-sm resize-none group-hover:border-blue-300 placeholder-blue-300 dark:placeholder:text-slate-500 @error('description') border-red-300 focus:border-red-500 focus:ring-red-500/20 @enderror"
                                    placeholder="Jelaskan kronologi dan detail masalah yang Anda temui secara spesifik...">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-xs font-bold text-red-500 mt-2 flex items-center gap-1"><i
                                            class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Lampiran / Bukti --}}
                            <div class="relative">
                                <label
                                    class="text-[10px] font-extrabold text-blue-500 dark:text-blue-400 uppercase tracking-widest mb-2 block">Lampiran
                                    / Bukti (Opsional)</label>

                                <div id="dropzone"
                                    class="relative flex flex-col items-center border-2 border-dashed border-blue-200 dark:border-slate-700 rounded-2xl px-6 py-8 bg-blue-50/30 dark:bg-slate-800/30 hover:bg-blue-50/80 dark:hover:bg-slate-800/80 hover:border-blue-400 transition-colors duration-300 group">

                                    <input type="file" id="fileInput" name="attachments[]" multiple
                                        accept=".jpg,.jpeg,.png,.pdf"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                                    <div class="w-full text-center pointer-events-none">
                                        <div id="uploadIcon"
                                            class="w-12 h-12 rounded-full bg-white dark:bg-slate-800 shadow-sm border border-blue-100 dark:border-slate-700 flex items-center justify-center text-blue-500 dark:text-blue-400 mx-auto mb-3 group-hover:bg-blue-500 group-hover:text-white group-hover:scale-110 group-hover:shadow-[0_0_20px_rgba(59,130,246,0.3)] transition-all">
                                            <i class="fa-solid fa-cloud-arrow-up text-xl"></i>
                                        </div>
                                        <p id="uploadText" class="text-sm font-bold text-blue-900 dark:text-white mb-1">Tarik & Lepas
                                            file ke sini, atau <span
                                                class="text-blue-600 dark:text-blue-400 underline decoration-blue-300 dark:decoration-slate-600 underline-offset-2">Jelajahi</span>
                                        </p>
                                        <p class="text-[11px] font-bold text-blue-400 dark:text-slate-400">Maksimal 5 file. Format: JPG,
                                            PNG, atau PDF (Maks 5 MB/file).</p>
                                    </div>

                                    {{-- Area to display selected file names --}}
                                    <div id="fileList" class="w-full mt-4 space-y-2 relative z-20 empty:hidden"></div>
                                </div>

                                @error('attachments')
                                    <p class="text-xs font-bold text-red-500 mt-2 flex items-center gap-1"><i
                                            class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                                @enderror
                                @error('attachments.*')
                                    <p class="text-xs font-bold text-red-500 mt-2 flex items-center gap-1"><i
                                            class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                                @enderror
                            </div>
                            {{-- Info Banner --}}
                            <div
                                class="bg-blue-50/50 dark:bg-slate-800/50 border border-blue-100 dark:border-slate-800 rounded-2xl p-5 text-sm flex items-start gap-4">
                                <div
                                    class="w-8 h-8 rounded-full bg-blue-100 dark:bg-slate-800 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-shield-halved"></i>
                                </div>
                                <div class="pt-1 text-blue-900/80 dark:text-white font-bold leading-relaxed">
                                    Laporan Anda akan diamankan dan ditinjau secara mendalam oleh tim administrator
                                    ApexForge. Pastikan menyertakan bukti valid untuk mempercepat proses investigasi.
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex flex-col sm:flex-row items-center gap-4 pt-4 border-t border-blue-50 dark:border-slate-800">
                                <button type="submit"
                                    class="w-full sm:w-auto px-8 py-3.5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-sm font-bold transition-all duration-300 shadow-[0_8px_20px_-6px_rgba(37,99,235,0.4)] hover:shadow-[0_12px_25px_-6px_rgba(37,99,235,0.6)] hover:-translate-y-0.5 flex items-center justify-center gap-2.5 group">
                                    <i
                                        class="fa-solid fa-paper-plane group-hover:-translate-y-1 group-hover:translate-x-1 transition-transform"></i>
                                    Kirim Laporan
                                </button>

                                <a href="{{ url()->previous() }}"
                                    class="w-full sm:w-auto px-8 py-3.5 bg-white dark:bg-slate-800 hover:bg-blue-50 dark:hover:bg-slate-700 border border-blue-200 dark:border-slate-700 text-blue-600 dark:text-blue-400 rounded-2xl text-sm font-bold transition-all duration-300 text-center hover:-translate-y-0.5">
                                    Batalkan
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('fileInput');
            const dropzone = document.getElementById('dropzone');
            const fileList = document.getElementById('fileList');

            // Add visual cues when dragging files over the input
            fileInput.addEventListener('dragenter', () => {
                dropzone.classList.add('bg-blue-100', 'border-blue-500');
            });

            fileInput.addEventListener('dragleave', () => {
                dropzone.classList.remove('bg-blue-100', 'border-blue-500');
            });

            fileInput.addEventListener('drop', () => {
                dropzone.classList.remove('bg-blue-100', 'border-blue-500');
            });

            // Handle the file selection (both drag & drop and manual click)
            fileInput.addEventListener('change', function() {
                fileList.innerHTML = ''; // Clear previous list

                const files = this.files;

                if (files.length > 0) {
                    // Limit to 5 files visual check (Validation should still be handled in Laravel)
                    const fileCount = Math.min(files.length, 5);

                    for (let i = 0; i < fileCount; i++) {
                        const file = files[i];

                        // Create a beautiful file badge
                        const fileItem = document.createElement('div');
                        fileItem.className =
                            'flex items-center justify-between p-3 bg-white border border-blue-100 rounded-xl shadow-sm';

                        fileItem.innerHTML = `
                        <div class="flex items-center gap-3 overflow-hidden">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
                                <i class="fa-solid ${getFileIcon(file.type)}"></i>
                            </div>
                            <span class="text-sm font-bold text-blue-900 truncate">${file.name}</span>
                        </div>
                        <span class="text-xs font-semibold text-blue-400 shrink-0 ml-4">${formatBytes(file.size)}</span>
                    `;

                        fileList.appendChild(fileItem);
                    }

                    if (files.length > 5) {
                        const warning = document.createElement('p');
                        warning.className = 'text-xs font-bold text-red-500 text-center mt-2';
                        warning.innerText = `*Hanya 5 file pertama yang akan diproses.`;
                        fileList.appendChild(warning);
                    }
                }
            });

            // Helper function to show correct icon based on file type
            function getFileIcon(type) {
                if (type.includes('pdf')) return 'fa-file-pdf';
                if (type.includes('image')) return 'fa-file-image';
                return 'fa-file-lines';
            }

            // Helper function to format file size
            function formatBytes(bytes, decimals = 2) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const dm = decimals < 0 ? 0 : decimals;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
            }
        });
    </script>
</body>

</html>
