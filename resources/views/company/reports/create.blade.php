<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.theme-boot')
    <title>Buat Laporan - ApexForge Labs</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif']
                    },
                    colors: {
                        brand: {
                            DEFAULT: '#2563EB',
                            dark: '#1D4ED8',
                            light: '#EFF6FF',
                        },
                        surface: {
                            light: '#F8FAFC',
                            dark: '#0F172A'
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        :root {
            --af-primary: #2563eb;
            --af-primary-dark: #1d4ed8;
            --af-primary-soft: #eff6ff;
            --af-sky: #38bdf8;
            --af-page-light: #f8fafc;
            --af-page-dark: #090d16;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--af-page-light);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .dark body {
            background-color: var(--af-page-dark);
        }

        /* Custom Ambient Background Elements */
        .ambient-glow-1 {
            position: fixed;
            top: -10%;
            right: -5%;
            width: 35rem;
            height: 35rem;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.12) 0%, rgba(0, 0, 0, 0) 70%);
            pointer-events: none;
            z-index: 0;
        }

        .dark .ambient-glow-1 {
            background: radial-gradient(circle, rgba(37, 99, 235, 0.18) 0%, rgba(0, 0, 0, 0) 70%);
        }

        .ambient-glow-2 {
            position: fixed;
            bottom: -10%;
            left: -5%;
            width: 30rem;
            height: 30rem;
            background: radial-gradient(circle, rgba(239, 68, 68, 0.08) 0%, rgba(0, 0, 0, 0) 70%);
            pointer-events: none;
            z-index: 0;
        }

        .dark .ambient-glow-2 {
            background: radial-gradient(circle, rgba(239, 68, 68, 0.12) 0%, rgba(0, 0, 0, 0) 70%);
        }

        ::selection { background: rgba(37, 99, 235, 0.25); color: #2563eb; }
        .dark ::selection { background: rgba(56, 189, 248, 0.3); color: #38bdf8; }

        /* Scrollbar Styling */
        ::-webkit-scrollbar { width: 7px; height: 7px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(148, 163, 184, 0.3); border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(148, 163, 184, 0.5); }
        .dark ::-webkit-scrollbar-thumb { background: rgba(51, 65, 85, 0.5); }
        .dark ::-webkit-scrollbar-thumb:hover { background: rgba(71, 85, 105, 0.7); }

        /* Form Inputs Optimized */
        input[type="text"], select, textarea {
            transition: all 0.2s ease-in-out;
        }

        /* Glassmorphism Panel */
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        .dark .glass-card {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(30, 41, 59, 0.8);
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-[#090d16] text-slate-800 dark:text-slate-100 min-h-screen flex font-sans antialiased selection:bg-brand/20 relative">

    {{-- Ambient Decorative Glows --}}
    <div class="ambient-glow-1"></div>
    <div class="ambient-glow-2"></div>

    {{-- SIDEBAR --}}
    @include('navbar.navigasi')

    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col min-h-screen w-full overflow-hidden z-10 relative">
        @include('navbar.nav')

        <main class="flex-1 w-full overflow-y-auto p-4 sm:p-6 lg:p-8">
            <div class="max-w-2xl mx-auto">

                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="mb-5 flex items-center gap-3 px-5 py-4 bg-emerald-500/10 dark:bg-emerald-500/15 backdrop-blur-md border border-emerald-500/30 text-emerald-700 dark:text-emerald-400 text-sm font-medium rounded-2xl shadow-sm transition-all duration-300">
                        <i class="fa-regular fa-circle-check text-base text-emerald-500"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-5 flex items-center gap-3 px-5 py-4 bg-rose-500/10 dark:bg-rose-500/15 backdrop-blur-md border border-rose-500/30 text-rose-700 dark:text-rose-400 text-sm font-medium rounded-2xl shadow-sm transition-all duration-300">
                        <i class="fa-regular fa-circle-xmark text-base text-rose-500"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-5 flex items-start gap-3 px-5 py-4 bg-rose-500/10 dark:bg-rose-500/15 backdrop-blur-md border border-rose-500/30 text-rose-700 dark:text-rose-400 text-sm font-medium rounded-2xl shadow-sm transition-all duration-300">
                        <i class="fa-regular fa-circle-xmark text-base text-rose-500 mt-0.5"></i>
                        <div class="space-y-1">
                            @foreach($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Back Button --}}
                <div class="mb-5">
                    <a href="{{ route('company.reports.index') }}" class="group text-xs sm:text-sm text-slate-500 dark:text-slate-400 hover:text-brand dark:hover:text-blue-400 font-semibold inline-flex items-center gap-2 transition-all duration-200">
                        <span class="w-8 h-8 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 flex items-center justify-center group-hover:border-brand/40 dark:group-hover:border-blue-500/40 shadow-xs group-hover:-translate-x-0.5 transition-transform">
                            <i class="fa-solid fa-arrow-left text-xs"></i>
                        </span>
                        <span>Kembali ke Laporan</span>
                    </a>
                </div>

                {{-- Form Card --}}
                <div class="glass-card rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-none p-6 sm:p-8 relative overflow-hidden">
                    
                    {{-- Decorative Card Accent --}}
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-red-500 via-rose-500 to-amber-500"></div>

                    <div class="flex items-center gap-4 mb-8 pb-6 border-b border-slate-100 dark:border-slate-800/80">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-red-500/10 to-rose-500/20 dark:from-red-500/20 dark:to-rose-500/30 text-red-600 dark:text-red-400 border border-red-500/20 flex items-center justify-center text-xl shrink-0 shadow-sm">
                            <i class="fa-solid fa-flag"></i>
                        </div>
                        <div>
                            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Buat Laporan</h2>
                            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">Laporkan masalah, pengguna, atau proyek yang melanggar aturan</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('company.reports.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        {{-- Hidden inputs for contextual reporting --}}
                        @if($workspace)
                            <input type="hidden" name="workspace_id" value="{{ $workspace->id }}">
                            <input type="hidden" name="project_id" value="{{ $project ? $project->id : '' }}">
                            <input type="hidden" name="reported_user_id" value="{{ $reportedUser ? $reportedUser->id : '' }}">
                        @elseif($penawaran)
                            <input type="hidden" name="penawaran_id" value="{{ $penawaran->id }}">
                            <input type="hidden" name="project_id" value="{{ $project ? $project->id : '' }}">
                            <input type="hidden" name="reported_user_id" value="{{ $reportedUser ? $reportedUser->id : '' }}">
                        @elseif($reportedUser)
                            {{-- Konteks murni: Company melaporkan Freelancer --}}
                            <input type="hidden" name="reported_user_id" value="{{ $reportedUser->id }}">
                        @endif

                        {{-- Context Info: Workspace (if reporting from workspace) --}}
                        @if($workspace)
                            <div class="bg-slate-50/80 dark:bg-slate-800/40 border border-slate-200/80 dark:border-slate-700/60 rounded-2xl p-4.5 space-y-3.5 transition-colors">
                                <div class="flex items-center gap-2 text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider">
                                    <i class="fa-solid fa-layer-group text-brand dark:text-blue-400"></i>
                                    <span>Workspace yang Dilaporkan</span>
                                </div>
                                @if($reportedUser)
                                    <div class="flex items-center gap-3.5 pb-3 border-b border-slate-200/60 dark:border-slate-700/50">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-amber-500 to-orange-400 text-white flex items-center justify-center font-bold text-sm shadow-md shadow-orange-500/20 shrink-0">
                                            {{ strtoupper(substr($reportedUser->name ?? '?', 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-slate-800 dark:text-slate-100 text-sm truncate">{{ $reportedUser->name }}</p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">Freelancer yang Dilaporkan</p>
                                        </div>
                                    </div>
                                @endif
                                @if($project)
                                    <div class="flex items-center gap-3.5 pt-0.5">
                                        <div class="w-10 h-10 rounded-xl bg-brand/10 dark:bg-blue-500/20 text-brand dark:text-blue-400 flex items-center justify-center shrink-0">
                                            <i class="fa-solid fa-briefcase text-sm"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-slate-800 dark:text-slate-100 text-sm truncate">{{ $project->project_name }}</p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">Proyek Terkait</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @elseif($penawaran)
                            {{-- Context Info: Penawaran & Freelancer Details (if reporting from penawaran) --}}
                            <div class="bg-slate-50/80 dark:bg-slate-800/40 border border-slate-200/80 dark:border-slate-700/60 rounded-2xl p-4.5 space-y-3.5 transition-colors">
                                <div class="flex items-center gap-2 text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider">
                                    <i class="fa-solid fa-flag text-brand dark:text-blue-400"></i>
                                    <span>Detail yang Dilaporkan</span>
                                </div>
                                @if($reportedUser)
                                    <div class="flex items-center gap-3.5 pb-3 border-b border-slate-200/60 dark:border-slate-700/50">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-amber-500 to-orange-400 text-white flex items-center justify-center font-bold text-sm shadow-md shadow-orange-500/20 shrink-0">
                                            {{ strtoupper(substr($reportedUser->name ?? '?', 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-slate-800 dark:text-slate-100 text-sm truncate">{{ $reportedUser->name }}</p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">Freelancer yang Dilaporkan</p>
                                        </div>
                                    </div>
                                @endif
                                @if($project)
                                    <div class="flex items-center gap-3.5 pt-0.5">
                                        <div class="w-10 h-10 rounded-xl bg-brand/10 dark:bg-blue-500/20 text-brand dark:text-blue-400 flex items-center justify-center shrink-0">
                                            <i class="fa-solid fa-briefcase text-sm"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-slate-800 dark:text-slate-100 text-sm truncate">{{ $project->project_name }}</p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">Proyek Terkait</p>
                                        </div>
                                    </div>
                                @endif
                                @if($penawaran->harga_penawaran)
                                    <div class="text-xs text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-900/80 border border-slate-200/60 dark:border-slate-700/60 rounded-xl px-3.5 py-2.5 font-medium flex items-center gap-2">
                                        <i class="fa-solid fa-tag text-emerald-500"></i>
                                        <span>Penawaran: <strong class="text-slate-800 dark:text-slate-100">Rp {{ number_format($penawaran->harga_penawaran, 0, ',', '.') }}</strong></span>
                                        <span class="text-slate-300 dark:text-slate-600">|</span>
                                        <span>Estimasi: <strong class="text-slate-800 dark:text-slate-100">{{ $penawaran->estimasi_hari }} hari</strong></span>
                                    </div>
                                @endif
                            </div>
                        @elseif($reportedUser)
                            {{-- Context Info: Freelancer yang Dilaporkan (Company melaporkan Freelancer) --}}
                            <div class="bg-slate-50/80 dark:bg-slate-800/40 border border-slate-200/80 dark:border-slate-700/60 rounded-2xl p-4.5 space-y-3 transition-colors">
                                <div class="flex items-center gap-2 text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider">
                                    <i class="fa-solid fa-user-tie text-brand dark:text-blue-400"></i>
                                    <span>Freelancer yang Dilaporkan</span>
                                </div>
                                <div class="flex items-center gap-3.5">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-amber-500 to-orange-400 text-white flex items-center justify-center font-bold text-sm shadow-md shadow-orange-500/20 shrink-0">
                                        {{ strtoupper(substr($reportedUser->name ?? '?', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-800 dark:text-slate-100 text-sm truncate">{{ $reportedUser->name }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $reportedUser->email }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Category --}}
                        @php
                            // Frontend hanya mengikuti target. Backend tetap source of truth.
                            $reportTarget = ($workspace || $penawaran || $reportedUser)
                                ? \App\Models\Report::TARGET_FREELANCER
                                : \App\Models\Report::TARGET_WEBSITE;
                            $targetCategories = \App\Models\Report::categoriesForTarget($reportTarget);
                        @endphp
                        <div>
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2 block">
                                Kategori Laporan <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="category"
                                    class="w-full appearance-none rounded-xl border border-slate-200 dark:border-slate-700/80 bg-slate-50/80 dark:bg-slate-800/60 dark:text-slate-100 px-4 py-3 text-sm focus:border-brand dark:focus:border-blue-500 focus:bg-white dark:focus:bg-slate-800 focus:ring-4 focus:ring-brand/10 dark:focus:ring-blue-500/20 outline-none pr-10 cursor-pointer @error('category') border-rose-400 dark:border-rose-500/80 @enderror">
                                    @foreach($targetCategories as $cat)
                                        <option value="{{ $cat }}" class="bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100" @selected(old('category') == $cat)>
                                            {{ \App\Models\Report::categoryLabel($cat) }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            @error('category') <p class="text-xs text-rose-500 dark:text-rose-400 mt-1.5 font-medium">{{ $message }}</p> @enderror
                        </div>

                        {{-- Subject --}}
                        <div>
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2 block">
                                Subjek Laporan <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="subject" value="{{ old('subject') }}"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-700/80 bg-slate-50/80 dark:bg-slate-800/60 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 px-4 py-3 text-sm focus:border-brand dark:focus:border-blue-500 focus:bg-white dark:focus:bg-slate-800 focus:ring-4 focus:ring-brand/10 dark:focus:ring-blue-500/20 outline-none @error('subject') border-rose-400 dark:border-rose-500/80 @enderror"
                                placeholder="Contoh: Freelancer tidak profesional, Proyek tidak sesuai, dll.">
                            @error('subject') <p class="text-xs text-rose-500 dark:text-rose-400 mt-1.5 font-medium">{{ $message }}</p> @enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2 block">
                                Deskripsi <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="description" rows="5"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-700/80 bg-slate-50/80 dark:bg-slate-800/60 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 px-4 py-3 text-sm focus:border-brand dark:focus:border-blue-500 focus:bg-white dark:focus:bg-slate-800 focus:ring-4 focus:ring-brand/10 dark:focus:ring-blue-500/20 outline-none @error('description') border-rose-400 dark:border-rose-500/80 @enderror"
                                placeholder="Jelaskan secara detail masalah yang Anda temui...">{{ old('description') }}</textarea>
                            @error('description') <p class="text-xs text-rose-500 dark:text-rose-400 mt-1.5 font-medium">{{ $message }}</p> @enderror
                        </div>

                        {{-- Attachment / Bukti --}}
                        <div>
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2 block">
                                Lampiran / Bukti <span class="text-slate-400 font-normal lowercase">(opsional)</span>
                            </label>
                            <div class="relative group">
                                <input type="file" name="attachments[]" multiple accept=".jpg,.jpeg,.png,.pdf"
                                    class="w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand/10 dark:file:bg-blue-500/20 file:text-brand dark:file:text-blue-400 hover:file:bg-brand/20 dark:hover:file:bg-blue-500/30 file:transition-all cursor-pointer bg-slate-50/80 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/80 rounded-xl p-2 focus:outline-none">
                            </div>
                            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1.5 flex items-center gap-1">
                                <i class="fa-solid fa-circle-info text-[10px]"></i>
                                Maks 5 file. Format: JPG, JPEG, PNG, atau PDF. Maks 5 MB per file.
                            </p>
                            @error('attachments') <p class="text-xs text-rose-500 dark:text-rose-400 mt-1 font-medium">{{ $message }}</p> @enderror
                            @error('attachments.*') <p class="text-xs text-rose-500 dark:text-rose-400 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        {{-- Info Alert --}}
                        <div class="bg-amber-500/10 dark:bg-amber-500/15 border border-amber-500/30 rounded-2xl p-4 text-sm text-amber-800 dark:text-amber-300 flex items-start gap-3.5">
                            <div class="w-7 h-7 rounded-lg bg-amber-500/20 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="fa-solid fa-shield-halved text-sm"></i>
                            </div>
                            <p class="text-xs sm:text-sm leading-relaxed">
                                Laporan Anda akan ditinjau secara rahasia oleh tim admin. Pastikan Anda memberikan informasi yang benar dan jelas agar dapat diproses dengan baik.
                            </p>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-3 pt-3 border-t border-slate-100 dark:border-slate-800/80">
                            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white rounded-xl text-sm font-bold transition-all duration-200 flex items-center justify-center gap-2 shadow-lg shadow-red-500/25 hover:shadow-red-500/35 active:scale-[0.99]">
                                <i class="fa-solid fa-paper-plane text-xs"></i>
                                <span>Kirim Laporan</span>
                            </button>
                            <a href="{{ route('company.reports.index') }}" class="px-6 py-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700/80 text-slate-700 dark:text-slate-300 rounded-xl text-sm font-bold transition-all duration-200 text-center">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>

            </div>
        </main>
    </div>

</body>
</html>