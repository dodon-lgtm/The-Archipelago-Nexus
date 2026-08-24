<!DOCTYPE html>
<html lang="id" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.theme-boot')
    <title>Buat Laporan - ApexForge Labs</title>
    <script>
        document.documentElement.classList.add('dark');
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = tailwind.config || {};
    tailwind.config.darkMode = 'class';
        tailwind.config.darkMode = 'class';
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #030712;
        }

        /* High-Tech Scrollbar (Dark Blue Theme) */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.6);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(59, 130, 246, 0.3);
            border-radius: 9999px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(59, 130, 246, 0.6);
        }

        /* Shader Glowing Effects */
        .shader-glow-blue {
            box-shadow: 0 0 40px rgba(59, 130, 246, 0.25);
        }

        .shader-glow-dark {
            box-shadow: 0 0 30px rgba(15, 23, 42, 0.8);
        }

        /* Advanced Pristine Glassmorphism (Dark Variant) */
        .glass-panel-pristine {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(32px);
            -webkit-backdrop-filter: blur(32px);
            border: 1px solid rgba(59, 130, 246, 0.2);
            box-shadow:
                0 30px 60px -15px rgba(0, 0, 0, 0.5),
                inset 0 1px 0 rgba(255, 255, 255, 0.1),
                inset 0 0 20px rgba(59, 130, 246, 0.05);
        }

        /* Cyber Grid Background (Dark Blue) */
        .hologram-grid-blue {
            background-color: #030712;
            background-image:
                linear-gradient(to right, rgba(59, 130, 246, 0.08) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(59, 130, 246, 0.08) 1px, transparent 1px);
            background-size: 48px 48px;
        }

        /* Floating Ambient Orbs */
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

        /* Custom Input Autofill styling for dark transparency */
        input:-webkit-autofill,
        textarea:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 30px #0f172a inset !important;
            -webkit-text-fill-color: #f8fafc !important;
        }
    </style>
    <style>
        /* ApexForge Labs — Unified UI System */
        :root {
            --af-primary: #2563eb;
            --af-primary-dark: #1d4ed8;
            --af-primary-soft: rgba(30, 41, 59, 0.8);
            --af-sky: #38bdf8;
            --af-ink: #f8fafc;
            --af-muted: #94a3b8;
            --af-border: #1e293b;
            --af-surface: #0f172a;
            --af-page: #030712;
        }

        html {
            scroll-behavior: smooth
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background:
                radial-gradient(circle at 10% -10%, rgba(56, 189, 248, .15), transparent 30%),
                radial-gradient(circle at 100% 0%, rgba(37, 99, 235, .12), transparent 28%),
                var(--af-page);
        }

        ::selection {
            background: rgba(37, 99, 235, .35);
            color: #ffffff
        }

        input,
        select,
        textarea {
            border-color: var(--af-border) !important;
            background: rgba(15, 23, 42, 0.8) !important;
            color: #f8fafc !important;
            transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: rgba(59, 130, 246, .6) !important;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, .2) !important;
            outline: none !important;
        }

        button,
        a,
        [role="button"] {
            transition: all .2s ease
        }

        button:focus-visible,
        a:focus-visible,
        [role="button"]:focus-visible {
            outline: 2px solid rgba(59, 130, 246, .6);
            outline-offset: 2px;
        }

        table {
            border-collapse: separate;
            border-spacing: 0
        }

        thead th {
            background: rgba(15, 23, 42, .85) !important;
            color: #cbd5e1;
            font-weight: 700;
        }

        tbody tr {
            transition: background .18s ease
        }

        tbody tr:hover {
            background: rgba(30, 41, 59, .5)
        }

        [class*="bg-blue-600"] {
            box-shadow: 0 8px 22px -12px rgba(37, 99, 235, .72);
        }

        [class*="bg-blue-600"]:hover {
            box-shadow: 0 12px 28px -12px rgba(37, 99, 235, .78);
            transform: translateY(-1px);
        }

        .glass-panel,
        .glass-card,
        .glass-surface {
            background: rgba(15, 23, 42, .75);
            border: 1px solid rgba(59, 130, 246, .2);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            box-shadow: 0 18px 50px -32px rgba(0, 0, 0, .5);
        }

        .apex-page-glow {
            position: fixed;
            inset: auto -10rem -12rem auto;
            width: 28rem;
            height: 28rem;
            background: rgba(56, 189, 248, .12);
            filter: blur(70px);
            border-radius: 999px;
            pointer-events: none;
            z-index: -1;
        }

        @media (max-width:767px) {
            main {
                padding-left: 1rem !important;
                padding-right: 1rem !important
            }

            table {
                min-width: 680px
            }

            .overflow-x-auto {
                -webkit-overflow-scrolling: touch
            }
        }

        @media (prefers-reduced-motion:reduce) {

            *,
            *::before,
            *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
                scroll-behavior: auto !important
            }
        }
    </style>
</head>

<body class="bg-slate-950 text-slate-100 antialiased min-h-screen flex transition-colors duration-300">

    @include('navbar.navigasi')

    <div class="flex-1 flex flex-col min-h-screen overflow-hidden bg-slate-950">
        @include('navbar.nav')

        {{-- PURE DARK BLUE HOLOGRAPHIC MAIN CONTAINER --}}
        <main class="flex-1 overflow-y-auto relative bg-slate-950">

            {{-- Ambient Lighting & Hologram Background Layers --}}
            <div class="absolute inset-0 z-0 pointer-events-none hologram-grid-blue"></div>

            {{-- Glowing Shaders --}}
            <div
                class="fixed top-[-10%] left-[-10%] w-[50rem] h-[50rem] bg-gradient-to-br from-blue-600/20 to-blue-900/10 rounded-full blur-[120px] animate-orb-1 pointer-events-none">
            </div>
            <div
                class="fixed bottom-[-10%] right-[-5%] w-[45rem] h-[45rem] bg-gradient-to-tl from-blue-500/15 to-sky-500/10 rounded-full blur-[100px] animate-orb-2 pointer-events-none">
            </div>
            <div
                class="fixed top-1/3 left-1/3 w-[30rem] h-[30rem] bg-blue-500/10 rounded-full blur-[100px] pointer-events-none opacity-50 mix-blend-screen">
            </div>

            <div class="relative z-10 px-4 py-12 sm:p-10 max-w-4xl mx-auto min-h-full flex flex-col justify-center">

                {{-- ALERTS: FUTURISTIC GLASS ALERTS --}}
                @if (session('success'))
                    <div
                        class="mb-8 overflow-hidden relative bg-slate-900/90 border border-blue-500/30 backdrop-blur-xl p-5 rounded-[1.5rem] shadow-[0_10px_30px_rgba(0,0,0,0.5)] flex items-start gap-4">
                        <div
                            class="w-10 h-10 rounded-full bg-blue-950 flex items-center justify-center shrink-0 border border-blue-800">
                            <i class="fa-solid fa-check text-blue-400"></i>
                        </div>
                        <div class="pt-2 font-bold text-white">{{ session('success') }}</div>
                    </div>
                @endif

                @if (session('error'))
                    <div
                        class="mb-8 overflow-hidden relative bg-slate-900/90 border border-red-500/30 backdrop-blur-xl p-5 rounded-[1.5rem] shadow-[0_10px_30px_rgba(0,0,0,0.5)] flex items-start gap-4">
                        <div
                            class="w-10 h-10 rounded-full bg-red-950/60 flex items-center justify-center shrink-0 border border-red-800">
                            <i class="fa-solid fa-xmark text-red-400"></i>
                        </div>
                        <div class="pt-2 font-bold text-red-300">{{ session('error') }}</div>
                    </div>
                @endif

                @if ($errors->any())
                    <div
                        class="mb-8 overflow-hidden relative bg-slate-900/90 border border-red-500/30 backdrop-blur-xl p-5 rounded-[1.5rem] shadow-[0_10px_30px_rgba(0,0,0,0.5)] flex items-start gap-4">
                        <div
                            class="w-10 h-10 rounded-full bg-red-950/60 flex items-center justify-center shrink-0 border border-red-800">
                            <i class="fa-solid fa-triangle-exclamation text-red-400"></i>
                        </div>
                        <div class="pt-1.5 text-sm font-bold text-red-300 space-y-1">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- FORM CONTAINER: PRISTINE DARK GLASS --}}
                <div class="glass-panel-pristine rounded-[2.5rem] relative overflow-hidden">

                    {{-- Decorative Top Accent Line --}}
                    <div
                        class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-blue-600 via-sky-400 to-blue-600 opacity-90">
                    </div>

                    <div class="p-8 sm:p-12">
                        {{-- HEADER SECTION --}}
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6 mb-10">
                            <div
                                class="w-16 h-16 rounded-3xl bg-gradient-to-br from-blue-500 to-blue-700 text-white flex items-center justify-center text-2xl shader-glow-blue relative group shadow-[0_10px_20px_rgba(37,99,235,0.4)]">
                                <div
                                    class="absolute inset-0 bg-white/10 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity">
                                </div>
                                <i class="fa-solid fa-flag"></i>
                            </div>
                            <div>
                                <h2 class="text-3xl font-black text-white tracking-tight mb-1">Buat Laporan</h2>
                                <p class="text-sm font-semibold text-blue-400/80">Laporkan masalah, pengguna, atau
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
                                        class="text-[10px] font-extrabold text-blue-400 uppercase tracking-widest mb-2 block">Kategori
                                        Laporan <span class="text-blue-500">*</span></label>
                                    <div class="relative">
                                        <select name="category"
                                            class="w-full appearance-none rounded-2xl border border-slate-800 bg-slate-900/90 px-5 py-4 text-sm font-bold text-white transition-all duration-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 outline-none shadow-sm group-hover:border-slate-700 @error('category') border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror">
                                            <option value="" disabled selected class="bg-slate-900 text-slate-400">Pilih Kategori...</option>
                                            @foreach (\App\Models\Report::categoriesForTarget(\App\Models\Report::TARGET_WEBSITE) as $cat)
                                                <option value="{{ $cat }}" class="bg-slate-900 text-white" @selected(old('category') == $cat)>
                                                    {{ \App\Models\Report::categoryLabel($cat) }}</option>
                                            @endforeach
                                        </select>
                                        <div
                                            class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-400">
                                            <i class="fa-solid fa-chevron-down text-xs"></i>
                                        </div>
                                    </div>
                                    @error('category')
                                        <p class="text-xs font-bold text-red-400 mt-2 flex items-center gap-1"><i
                                                class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Subjek --}}
                                <div class="relative group">
                                    <label
                                        class="text-[10px] font-extrabold text-blue-400 uppercase tracking-widest mb-2 block">Subjek
                                        Laporan <span class="text-blue-500">*</span></label>
                                    <input type="text" name="subject" value="{{ old('subject') }}"
                                        class="w-full rounded-2xl border border-slate-800 bg-slate-900/90 px-5 py-4 text-sm font-bold text-white transition-all duration-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 outline-none shadow-sm group-hover:border-slate-700 placeholder-slate-500 @error('subject') border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror"
                                        placeholder="Contoh: Pengguna mencurigakan...">
                                    @error('subject')
                                        <p class="text-xs font-bold text-red-400 mt-2 flex items-center gap-1"><i
                                                class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Deskripsi --}}
                            <div class="relative group">
                                <label
                                    class="text-[10px] font-extrabold text-blue-400 uppercase tracking-widest mb-2 block">Deskripsi
                                    Detail <span class="text-blue-500">*</span></label>
                                <textarea name="description" rows="5"
                                    class="w-full rounded-2xl border border-slate-800 bg-slate-900/90 px-5 py-4 text-sm font-bold text-white transition-all duration-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 outline-none shadow-sm resize-none group-hover:border-slate-700 placeholder-slate-500 @error('description') border-red-500 focus:border-red-500 focus:ring-red-500/20 @enderror"
                                    placeholder="Jelaskan kronologi dan detail masalah yang Anda temui secara spesifik...">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-xs font-bold text-red-400 mt-2 flex items-center gap-1"><i
                                            class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Lampiran / Bukti --}}
                            <div class="relative">
                                <label
                                    class="text-[10px] font-extrabold text-blue-400 uppercase tracking-widest mb-2 block">Lampiran
                                    / Bukti (Opsional)</label>

                                <div id="dropzone"
                                    class="relative flex flex-col items-center border-2 border-dashed border-slate-800 rounded-2xl px-6 py-8 bg-slate-900/40 hover:bg-slate-800/60 hover:border-blue-500/50 transition-colors duration-300 group">

                                    <input type="file" id="fileInput" name="attachments[]" multiple
                                        accept=".jpg,.jpeg,.png,.pdf"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                                    <div class="w-full text-center pointer-events-none">
                                        <div id="uploadIcon"
                                            class="w-12 h-12 rounded-full bg-slate-800 shadow-sm border border-slate-700 flex items-center justify-center text-blue-400 mx-auto mb-3 group-hover:bg-blue-600 group-hover:text-white group-hover:scale-110 group-hover:shadow-[0_0_20px_rgba(59,130,246,0.4)] transition-all">
                                            <i class="fa-solid fa-cloud-arrow-up text-xl"></i>
                                        </div>
                                        <p id="uploadText" class="text-sm font-bold text-white mb-1">Tarik & Lepas
                                            file ke sini, atau <span
                                                class="text-blue-400 underline decoration-slate-600 underline-offset-2">Jelajahi</span>
                                        </p>
                                        <p class="text-[11px] font-bold text-slate-400">Maksimal 5 file. Format: JPG,
                                            PNG, atau PDF (Maks 5 MB/file).</p>
                                    </div>

                                    {{-- Area to display selected file names --}}
                                    <div id="fileList" class="w-full mt-4 space-y-2 relative z-20 empty:hidden"></div>
                                </div>

                                @error('attachments')
                                    <p class="text-xs font-bold text-red-400 mt-2 flex items-center gap-1"><i
                                            class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                                @enderror
                                @error('attachments.*')
                                    <p class="text-xs font-bold text-red-400 mt-2 flex items-center gap-1"><i
                                            class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Info Banner --}}
                            <div
                                class="bg-slate-900/60 border border-slate-800 rounded-2xl p-5 text-sm flex items-start gap-4">
                                <div
                                    class="w-8 h-8 rounded-full bg-slate-800 text-blue-400 flex items-center justify-center shrink-0 border border-slate-700">
                                    <i class="fa-solid fa-shield-halved"></i>
                                </div>
                                <div class="pt-1 text-slate-300 font-bold leading-relaxed">
                                    Laporan Anda akan diamankan dan ditinjau secara mendalam oleh tim administrator
                                    ApexForge. Pastikan menyertakan bukti valid untuk mempercepat proses investigasi.
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex flex-col sm:flex-row items-center gap-4 pt-4 border-t border-slate-800">
                                <button type="submit"
                                    class="w-full sm:w-auto px-8 py-3.5 bg-blue-600 hover:bg-blue-500 text-white rounded-2xl text-sm font-bold transition-all duration-300 shadow-[0_8px_20px_-6px_rgba(37,99,235,0.5)] hover:shadow-[0_12px_25px_-6px_rgba(37,99,235,0.7)] hover:-translate-y-0.5 flex items-center justify-center gap-2.5 group">
                                    <i
                                        class="fa-solid fa-paper-plane group-hover:-translate-y-1 group-hover:translate-x-1 transition-transform"></i>
                                    Kirim Laporan
                                </button>

                                <a href="{{ url()->previous() }}"
                                    class="w-full sm:w-auto px-8 py-3.5 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 rounded-2xl text-sm font-bold transition-all duration-300 text-center hover:-translate-y-0.5">
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
                dropzone.classList.add('bg-slate-800', 'border-blue-500');
            });

            fileInput.addEventListener('dragleave', () => {
                dropzone.classList.remove('bg-slate-800', 'border-blue-500');
            });

            fileInput.addEventListener('drop', () => {
                dropzone.classList.remove('bg-slate-800', 'border-blue-500');
            });

            // Handle the file selection (both drag & drop and manual click)
            fileInput.addEventListener('change', function() {
                fileList.innerHTML = ''; // Clear previous list

                const files = this.files;

                if (files.length > 0) {
                    const fileCount = Math.min(files.length, 5);

                    for (let i = 0; i < fileCount; i++) {
                        const file = files[i];

                        // Create dark themed file badge
                        const fileItem = document.createElement('div');
                        fileItem.className =
                            'flex items-center justify-between p-3 bg-slate-800/90 border border-slate-700 rounded-xl shadow-sm';

                        fileItem.innerHTML = `
                        <div class="flex items-center gap-3 overflow-hidden">
                            <div class="w-8 h-8 rounded-lg bg-slate-700 text-blue-400 flex items-center justify-center shrink-0">
                                <i class="fa-solid ${getFileIcon(file.type)}"></i>
                            </div>
                            <span class="text-sm font-bold text-slate-200 truncate">${file.name}</span>
                        </div>
                        <span class="text-xs font-semibold text-slate-400 shrink-0 ml-4">${formatBytes(file.size)}</span>
                    `;

                        fileList.appendChild(fileItem);
                    }

                    if (files.length > 5) {
                        const warning = document.createElement('p');
                        warning.className = 'text-xs font-bold text-red-400 text-center mt-2';
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