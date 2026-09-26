<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.theme-boot')
    <title>{{ $project->project_name }} - ApexForge Labs</title>

    {{-- =========================================================
        TAILWIND
    ========================================================== --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class'
        };
    </script>

    {{-- =========================================================
        FONT AWESOME
    ========================================================== --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- =========================================================
        FONT + GLOBAL STYLE
    ========================================================== --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background:
                radial-gradient(circle at 15% -10%, rgba(56, 189, 248, 0.12), transparent 35%),
                radial-gradient(circle at 85% 10%, rgba(99, 102, 241, 0.08), transparent 30%),
                #f8fafc;
        }

        html.dark body {
            background:
                radial-gradient(circle at 15% -10%, rgba(37, 99, 235, 0.15), transparent 35%),
                radial-gradient(circle at 85% 10%, rgba(99, 102, 241, 0.10), transparent 30%),
                #020617;
        }

        ::selection {
            background: rgba(37, 99, 235, 0.2);
            color: #0f172a;
        }

        .dark ::selection {
            background: rgba(59, 130, 246, 0.35);
            color: #f8fafc;
        }
    </style>
</head>

<body class="min-h-full text-slate-800 dark:text-slate-200 antialiased transition-colors duration-300">

    {{-- =========================================================
        NAVBAR PUBLIC
    ========================================================== --}}
    <header class="sticky top-0 z-50 bg-white/80 dark:bg-slate-950/80 backdrop-blur-md border-b border-slate-200/80 dark:border-slate-800/80 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="h-16 flex items-center justify-between">

                {{-- LOGO --}}
                <a href="{{ route('landing') }}" class="flex items-center gap-3 group">
                    <div class="relative w-10 h-10 rounded-xl overflow-hidden shadow-md shadow-blue-500/10 group-hover:scale-105 transition-transform duration-200">
                        <img src="{{ asset('images/nexus.jpg') }}" alt="ApexForge Labs" class="w-full h-full object-cover">
                    </div>
                    <div class="leading-tight">
                        <div class="font-black text-base tracking-tight text-slate-900 dark:text-white">ApexForge</div>
                        <div class="font-bold text-xs text-blue-600 dark:text-blue-400">Labs</div>
                    </div>
                </a>

                {{-- NAVBAR RIGHT --}}
                <div class="flex items-center gap-2 sm:gap-3">
                    {{-- THEME BUTTON --}}
                    <button type="button" onclick="toggleTheme()" class="w-10 h-10 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition shadow-sm flex items-center justify-center" title="Ganti tema">
                        <i id="themeIcon" class="fa-solid fa-moon"></i>
                    </button>

                    {{-- AUTH NAVIGATION --}}
                    @auth
                        @if(Auth::user()->role === 'freelancer')
                            <a href="{{ route('freelancer.dashboard') }}" class="hidden sm:inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold shadow-md shadow-blue-500/20 transition">
                                <i class="fa-solid fa-gauge-high"></i> Dashboard
                            </a>
                        @elseif(Auth::user()->role === 'company')
                            <a href="{{ route('company.dashboard') }}" class="hidden sm:inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold transition">
                                <i class="fa-solid fa-gauge-high"></i> Dashboard
                            </a>
                        @elseif(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="hidden sm:inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold transition">
                                <i class="fa-solid fa-shield-halved"></i> Admin
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 text-sm font-bold transition">
                            <i class="fa-solid fa-right-to-bracket"></i> Masuk
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold shadow-md shadow-blue-500/20 transition">
                            <i class="fa-solid fa-user-plus"></i> Daftar
                        </a>
                    @endauth
                </div>

            </div>
        </div>
    </header>

    {{-- =========================================================
        MAIN CONTENT
    ========================================================== --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">

        {{-- BACK BUTTON --}}
        <div class="mb-6">
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('landing') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-400 transition group">
                <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali
            </a>
        </div>

        {{-- MAIN CONTENT GRID --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- LEFT SECTION (HERO IMAGE & DETAIL) --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- PROJECT HERO IMAGE & BADGE --}}
                <div class="relative w-full rounded-3xl overflow-hidden border border-slate-200/80 dark:border-slate-800/80 bg-slate-900 shadow-xl shadow-slate-200/50 dark:shadow-none group">
                    <div class="relative h-64 sm:h-80 lg:h-96 w-full overflow-hidden">
                        @if($project->image)
                            <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->project_name }}" class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent"></div>
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center bg-slate-100 dark:bg-slate-900 text-slate-400 dark:text-slate-600">
                                <i class="fa-solid fa-image text-5xl mb-3"></i>
                                <span class="text-sm font-semibold">Tidak ada gambar proyek</span>
                            </div>
                        @endif

                        {{-- STATUS BADGE (FLOATING ON HERO) --}}
                        <div class="absolute top-4 right-4 z-10">
                            @php
                                $status = strtolower((string) ($project->status ?? 'open'));
                                $statusClasses = match ($status) {
                                    'open' => 'bg-emerald-500/90 text-white backdrop-blur-md border border-emerald-400/30 shadow-lg shadow-emerald-500/20',
                                    'close', 'closed' => 'bg-amber-500/90 text-white backdrop-blur-md border border-amber-400/30 shadow-lg shadow-amber-500/20',
                                    'archive', 'archived' => 'bg-slate-700/90 text-white backdrop-blur-md border border-slate-600/30 shadow-lg',
                                    default => 'bg-slate-500/90 text-white backdrop-blur-md border border-slate-400/30 shadow-lg',
                                };
                                $statusLabel = match ($status) {
                                    'open' => 'Open',
                                    'close', 'closed' => 'Closed',
                                    'archive', 'archived' => 'Archived',
                                    default => ucfirst($status),
                                };
                            @endphp

                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-extrabold tracking-wide uppercase {{ $statusClasses }}">
                                <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                                {{ $statusLabel }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- DETAILS CARD --}}
                <div class="p-6 sm:p-8 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800/80 shadow-xl shadow-slate-200/50 dark:shadow-none space-y-8">
                    
                    {{-- HEADER DETAILS --}}
                    <div>
                        @if($project->category)
                            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-blue-50 dark:bg-blue-950/50 border border-blue-200/60 dark:border-blue-800/60 text-blue-600 dark:text-blue-400 text-xs font-extrabold uppercase tracking-wider">
                                <i class="fa-solid fa-tag text-[10px]"></i> {{ $project->category->name }}
                            </span>
                        @endif

                        <h1 class="mt-4 text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight text-slate-900 dark:text-white leading-snug">
                            {{ $project->project_name }}
                        </h1>

                        @if($project->owner)
                            <div class="mt-6 flex items-center gap-3.5 p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                                <div class="w-11 h-11 rounded-xl bg-blue-600/10 dark:bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0">
                                    <i class="fa-solid fa-building text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] uppercase tracking-widest font-extrabold text-slate-400 dark:text-slate-400">Dipublikasikan oleh</p>
                                    <p class="text-sm font-extrabold text-slate-800 dark:text-slate-100">{{ $project->owner->name }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <hr class="border-slate-100 dark:border-slate-800">

                    {{-- DESCRIPTION SECTION --}}
                    <div>
                        <h2 class="text-base sm:text-lg font-black text-slate-900 dark:text-white mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-align-left text-blue-600 dark:text-blue-400"></i> Tentang Proyek
                        </h2>
                        <div class="text-sm sm:text-base text-slate-600 dark:text-slate-300 leading-relaxed whitespace-pre-line bg-slate-50/50 dark:bg-slate-950/30 p-4 sm:p-5 rounded-2xl border border-slate-100 dark:border-slate-800/50">
                            {{ $project->project_description ?: 'Tidak ada deskripsi proyek.' }}
                        </div>
                    </div>

                    {{-- SKILLS SECTION --}}
                    @if(isset($project->skills) && $project->skills)
                        <div>
                            <h2 class="text-base sm:text-lg font-black text-slate-900 dark:text-white mb-3 flex items-center gap-2">
                                <i class="fa-solid fa-wand-magic-sparkles text-blue-600 dark:text-blue-400"></i> Keahlian yang Dibutuhkan
                            </h2>
                            <div class="flex flex-wrap gap-2">
                                @foreach(is_array($project->skills) ? $project->skills : preg_split('/[,|]/', $project->skills) as $skill)
                                    @if(trim($skill))
                                        <span class="px-3.5 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-300 hover:border-blue-500 dark:hover:border-blue-400 transition-colors">
                                            {{ trim($skill) }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            {{-- RIGHT SIDEBAR --}}
            <aside class="space-y-6">
                <div class="lg:sticky lg:top-24 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 p-6 sm:p-7 shadow-xl shadow-slate-200/50 dark:shadow-none space-y-6 transition-colors">
                    
                    {{-- BUDGET SECTION --}}
                    <div>
                        <p class="text-[10px] uppercase tracking-widest font-extrabold text-slate-400 dark:text-slate-400">Anggaran Proyek</p>
                        <p class="mt-1.5 text-3xl sm:text-4xl font-black text-blue-600 dark:text-blue-400 tracking-tight">
                            Rp {{ number_format($project->budget ?? 0, 0, ',', '.') }}
                        </p>
                    </div>

                    <hr class="border-slate-100 dark:border-slate-800">

                    {{-- METADATA INFO --}}
                    <div class="space-y-4">
                        <div class="flex items-center gap-3.5 p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800">
                            <div class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-blue-600 dark:text-blue-400 shadow-sm shrink-0">
                                <i class="fa-regular fa-calendar text-base"></i>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase tracking-widest font-extrabold text-slate-400 dark:text-slate-400">Deadline</p>
                                <p class="text-sm font-extrabold text-slate-800 dark:text-slate-100">
                                    @if($project->deadline)
                                        {{ \Carbon\Carbon::parse($project->deadline)->isoFormat('D MMMM YYYY') }}
                                    @else
                                        Tidak ditentukan
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3.5 p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800">
                            <div class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-blue-600 dark:text-blue-400 shadow-sm shrink-0">
                                <i class="fa-regular fa-clock text-base"></i>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase tracking-widest font-extrabold text-slate-400 dark:text-slate-400">Dipublikasikan</p>
                                <p class="text-sm font-extrabold text-slate-800 dark:text-slate-100">
                                    {{ $project->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- ACTION BUTTON / BANNER --}}
                    <div class="pt-2">
                        @if(($acceptsOffers ?? true) && $status === 'open')
                            @auth
                                @if(Auth::user()->role === 'freelancer')
                                    @if($hasOffered ?? false)
                                        <div class="w-full px-5 py-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 text-center">
                                            <div class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center mx-auto mb-2 shadow-md shadow-emerald-500/20">
                                                <i class="fa-solid fa-check"></i>
                                            </div>
                                            <p class="font-black text-emerald-800 dark:text-emerald-300 text-sm">Penawaran Sudah Dikirim</p>
                                            <p class="text-xs text-emerald-600 dark:text-emerald-400/80 mt-0.5">Kamu sudah mengirim penawaran untuk proyek ini.</p>
                                        </div>
                                    @else
                                        <a href="{{ route('freelancer.penawaran.create', $project) }}" class="w-full inline-flex items-center justify-center gap-2 px-5 py-4 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold shadow-lg shadow-blue-500/25 hover:-translate-y-0.5 transition-all">
                                            <i class="fa-solid fa-paper-plane"></i> Kirim Penawaran
                                        </a>
                                    @endif
                                @else
                                    <div class="rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-800 p-4 text-center">
                                        <div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center mx-auto mb-2">
                                            <i class="fa-solid fa-user-lock"></i>
                                        </div>
                                        <p class="text-xs font-bold text-slate-600 dark:text-slate-400">Hanya freelancer yang dapat mengirim penawaran.</p>
                                    </div>
                                @endif
                            @else
                                @php
                                    $offerUrl = route('freelancer.penawaran.create', $project);
                                @endphp
                                <a href="{{ route('login', ['redirect' => $offerUrl]) }}" class="w-full inline-flex items-center justify-center gap-2 px-5 py-4 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold shadow-lg shadow-blue-500/25 hover:-translate-y-0.5 transition-all">
                                    <i class="fa-solid fa-paper-plane"></i> Kirim Penawaran
                                </a>
                                <p class="text-[11px] text-center text-slate-400 dark:text-slate-400 mt-2.5 leading-relaxed">
                                    Kamu perlu masuk atau membuat akun freelancer terlebih dahulu untuk mengirim penawaran.
                                </p>
                            @endauth
                        @else
                            <div class="w-full px-5 py-5 rounded-2xl bg-slate-50 dark:bg-slate-950/50 border border-slate-200/80 dark:border-slate-800 text-center">
                                <div class="w-10 h-10 rounded-full bg-slate-200/80 dark:bg-slate-800 text-slate-500 dark:text-slate-400 flex items-center justify-center mx-auto mb-2">
                                    <i class="fa-solid fa-lock"></i>
                                </div>
                                <p class="font-black text-slate-800 dark:text-slate-200 text-sm">Penawaran Ditutup</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Proyek ini sudah tidak menerima penawaran baru.</p>
                            </div>
                        @endif
                    </div>

                </div>
            </aside>

        </div>
    </main>

    {{-- =========================================================
        FOOTER
    ========================================================== --}}
    <footer class="border-t border-slate-200/80 dark:border-slate-800/80 bg-white/50 dark:bg-slate-950/50 backdrop-blur-md mt-12 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl overflow-hidden shadow-sm">
                        <img src="{{ asset('images/nexus.jpg') }}" alt="ApexForge Labs" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <p class="text-xs font-black text-slate-900 dark:text-white">ApexForge Labs</p>
                        <p class="text-[10px] text-slate-400 dark:text-slate-400">Marketplace Freelance Indonesia</p>
                    </div>
                </div>
                <p class="text-xs text-slate-400 dark:text-slate-400">© {{ date('Y') }} ApexForge Labs. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

    {{-- =========================================================
        JAVASCRIPT
    ========================================================== --}}
    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const icon = document.getElementById('themeIcon');

            html.classList.toggle('dark');

            const isDark = html.classList.contains('dark');

            localStorage.setItem('theme_user_', isDark ? 'dark' : 'light');

            if (icon) {
                icon.className = isDark ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const icon = document.getElementById('themeIcon');
            if (icon) {
                icon.className = document.documentElement.classList.contains('dark') ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
            }
        });
    </script>

</body>
</html>