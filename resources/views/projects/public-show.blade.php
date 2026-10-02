<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.theme-boot')
    <title>{{ $project->project_name }} - ApexForge Labs</title>

    {{-- =========================================================
        DARK MODE
    ========================================================== --}}
    

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
                radial-gradient(circle at 10% -10%, rgba(56, 189, 248, 0.10), transparent 30%),
                radial-gradient(circle at 100% 0%, rgba(37, 99, 235, 0.08), transparent 28%),
                #f6f9ff;
        }

        html.dark body {
            background:
                radial-gradient(circle at 10% -10%, rgba(37, 99, 235, 0.08), transparent 30%),
                #020617;
        }

        ::selection {
            background: rgba(37, 99, 235, 0.18);
            color: #0f172a;
        }

        .dark ::selection {
            background: rgba(59, 130, 246, 0.3);
            color: #f8fafc;
        }
    </style>
</head>

<body class="min-h-full text-slate-800 dark:text-slate-200 antialiased bg-slate-50 dark:bg-slate-950 transition-colors">

    {{-- =========================================================
        NAVBAR PUBLIC
    ========================================================== --}}
    <header class="sticky top-0 z-50 bg-white/85 dark:bg-slate-950/85 backdrop-blur-xl border-b border-blue-100/80 dark:border-slate-800 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="h-16 flex items-center justify-between">

                {{-- LOGO --}}
                <a href="{{ route('landing') }}" class="flex items-center gap-3 group">
                    <div class="relative w-10 h-10 rounded-xl overflow-hidden shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform">
                        <img src="{{ asset('images/nexus.jpg') }}" alt="ApexForge Labs" class="w-full h-full object-cover">
                    </div>
                    <div class="leading-tight">
                        <div class="font-black text-sm text-blue-950 dark:text-white">ApexForge</div>
                        <div class="font-bold text-xs text-blue-600 dark:text-blue-400">Labs</div>
                    </div>
                </a>

                {{-- NAVBAR RIGHT --}}
                <div class="flex items-center gap-2 sm:gap-3">
                    {{-- THEME BUTTON --}}
                    <button type="button" onclick="toggleTheme()" class="w-10 h-10 rounded-xl border border-blue-100 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-slate-800 transition shadow-sm" title="Ganti tema">
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
                        <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-blue-100 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-slate-800 text-sm font-bold transition">
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
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('landing') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>

        {{-- PROJECT CARD --}}
        <section class="bg-white dark:bg-slate-900 rounded-3xl border border-blue-100/80 dark:border-slate-800 shadow-xl shadow-blue-900/5 dark:shadow-none overflow-hidden transition-colors">

            {{-- PROJECT IMAGE --}}
            <div class="relative w-full h-64 sm:h-80 lg:h-[420px] bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-slate-800 dark:to-slate-900">
                @if($project->image)
                    <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->project_name }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/60 via-transparent to-transparent"></div>
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 dark:text-slate-600">
                        <i class="fa-solid fa-image text-6xl mb-4"></i>
                        <span class="text-sm font-semibold">Tidak ada gambar proyek</span>
                    </div>
                @endif

                {{-- PROJECT STATUS --}}
                <div class="absolute top-5 right-5">
                    @php
                        $status = strtolower((string) ($project->status ?? 'open'));
                        $statusClasses = match ($status) {
                            'open' => 'bg-emerald-500 text-white',
                            'close', 'closed' => 'bg-amber-500 text-white',
                            'archive', 'archived' => 'bg-slate-700 text-white dark:bg-slate-600',
                            default => 'bg-slate-500 text-white',
                        };
                        $statusLabel = match ($status) {
                            'open' => 'Open',
                            'close', 'closed' => 'Closed',
                            'archive', 'archived' => 'Archived',
                            default => ucfirst($status),
                        };
                    @endphp

                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-extrabold shadow-lg {{ $statusClasses }}">
                        <span class="w-2 h-2 rounded-full bg-white"></span>
                        {{ $statusLabel }}
                    </span>
                </div>
            </div>

            {{-- PROJECT DETAILS --}}
            <div class="p-6 sm:p-8 lg:p-10">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    {{-- LEFT CONTENT --}}
                    <div class="lg:col-span-2">
                        @if($project->category)
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-50 dark:bg-slate-800/80 border border-blue-100 dark:border-slate-700 text-blue-600 dark:text-blue-400 text-xs font-extrabold uppercase tracking-wide">
                                <i class="fa-solid fa-tag"></i> {{ $project->category->name }}
                            </span>
                        @endif

                        <h1 class="mt-4 text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight text-slate-900 dark:text-white">
                            {{ $project->project_name }}
                        </h1>

                        @if($project->owner)
                            <div class="mt-5 flex items-center gap-3">
                                <div class="w-11 h-11 rounded-xl bg-blue-50 dark:bg-slate-800 border border-transparent dark:border-slate-700 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                    <i class="fa-solid fa-building"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] uppercase tracking-widest font-bold text-slate-400 dark:text-slate-400">Dipublikasikan oleh</p>
                                    <p class="text-sm font-extrabold text-slate-800 dark:text-slate-100">{{ $project->owner->name }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="mt-10">
                            <h2 class="text-lg sm:text-xl font-black text-slate-900 dark:text-white mb-4">Tentang Proyek</h2>
                            <div class="text-sm sm:text-base text-slate-600 dark:text-slate-300 leading-8 whitespace-pre-line">
                                {{ $project->project_description ?: 'Tidak ada deskripsi proyek.' }}
                            </div>
                        </div>

                        @if(isset($project->skills) && $project->skills)
                            <div class="mt-10">
                                <h2 class="text-lg font-black text-slate-900 dark:text-white mb-4">Keahlian yang Dibutuhkan</h2>
                                <div class="flex flex-wrap gap-2">
                                    @foreach(is_array($project->skills) ? $project->skills : preg_split('/[,|]/', $project->skills) as $skill)
                                        @if(trim($skill))
                                            <span class="px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-600 dark:text-slate-300">
                                                {{ trim($skill) }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- RIGHT SIDEBAR --}}
                    <aside>
                        <div class="lg:sticky lg:top-24 rounded-3xl bg-slate-50 dark:bg-slate-800/70 border border-blue-100 dark:border-slate-700 p-6 transition-colors">
                            <p class="text-[10px] uppercase tracking-widest font-extrabold text-slate-400 dark:text-slate-400">Anggaran Proyek</p>
                            <p class="mt-2 text-2xl sm:text-3xl font-black text-blue-600 dark:text-blue-400">
                                Rp {{ number_format($project->budget ?? 0, 0, ',', '.') }}
                            </p>

                            <div class="my-6 h-px bg-slate-200 dark:bg-slate-700"></div>

                            <div class="flex items-start gap-3 mb-5">
                                <div class="w-10 h-10 rounded-xl bg-white dark:bg-slate-900 border border-transparent dark:border-slate-700/60 flex items-center justify-center text-blue-600 dark:text-blue-400 shadow-sm dark:shadow-none">
                                    <i class="fa-regular fa-calendar"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] uppercase tracking-widest font-bold text-slate-400 dark:text-slate-400">Deadline</p>
                                    <p class="mt-1 text-sm font-extrabold text-slate-800 dark:text-slate-100">
                                        @if($project->deadline)
                                            {{ \Carbon\Carbon::parse($project->deadline)->isoFormat('D MMMM YYYY') }}
                                        @else
                                            Tidak ditentukan
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-white dark:bg-slate-900 border border-transparent dark:border-slate-700/60 flex items-center justify-center text-blue-600 dark:text-blue-400 shadow-sm dark:shadow-none">
                                    <i class="fa-regular fa-clock"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] uppercase tracking-widest font-bold text-slate-400 dark:text-slate-400">Dipublikasikan</p>
                                    <p class="mt-1 text-sm font-extrabold text-slate-800 dark:text-slate-100">
                                        {{ $project->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>

                            {{-- ACTION KIRIM PENAWARAN --}}
                            @if(($acceptsOffers ?? true) && $status === 'open')
                                @auth
                                    @if(Auth::user()->role === 'freelancer')
                                        @if($hasOffered ?? false)
                                            <div class="w-full px-5 py-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900 text-center">
                                                <div class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center mx-auto mb-3">
                                                    <i class="fa-solid fa-check"></i>
                                                </div>
                                                <p class="font-black text-emerald-700 dark:text-emerald-400">Penawaran Sudah Dikirim</p>
                                                <p class="text-xs text-emerald-600/80 dark:text-emerald-400/70 mt-1">Kamu sudah mengirim penawaran untuk proyek ini.</p>
                                            </div>
                                        @else
                                            <a href="{{ route('freelancer.penawaran.create', $project) }}" class="w-full inline-flex items-center justify-center gap-2 px-5 py-4 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold shadow-lg shadow-blue-500/20 hover:-translate-y-0.5 transition-all">
                                                <i class="fa-solid fa-paper-plane"></i> Kirim Penawaran
                                            </a>
                                        @endif
                                    @else
                                        <div class="rounded-2xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 p-4 text-center">
                                            <div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-800 text-slate-500 dark:text-slate-400 flex items-center justify-center mx-auto mb-3">
                                                <i class="fa-solid fa-user-lock"></i>
                                            </div>
                                            <p class="text-xs font-bold text-slate-500 dark:text-slate-400">Hanya freelancer yang dapat mengirim penawaran.</p>
                                        </div>
                                    @endif
                                @else
                                    {{-- UNTUK USER YANG BELUM LOGIN (GUEST) --}}
                                    @php
                                        $offerUrl = route('freelancer.penawaran.create', $project);
                                    @endphp
                                    <a href="{{ route('login', ['redirect' => $offerUrl]) }}" class="w-full inline-flex items-center justify-center gap-2 px-5 py-4 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold shadow-lg shadow-blue-500/20 hover:-translate-y-0.5 transition-all">
                                        <i class="fa-solid fa-paper-plane"></i> Kirim Penawaran
                                    </a>
                                    <p class="text-[11px] text-center text-slate-400 dark:text-slate-400 mt-3 leading-relaxed">
                                        Kamu perlu masuk atau membuat akun freelancer terlebih dahulu untuk mengirim penawaran.
                                    </p>
                                @endauth
                            @else
                                <div class="w-full px-5 py-4 rounded-2xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-center">
                                    <div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-800 text-slate-500 dark:text-slate-400 flex items-center justify-center mx-auto mb-3">
                                        <i class="fa-solid fa-lock"></i>
                                    </div>
                                    <p class="font-black text-slate-700 dark:text-slate-300">Penawaran Ditutup</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Proyek ini sudah tidak menerima penawaran baru.</p>
                                </div>
                            @endif

                        </div>
                    </aside>

                </div>
            </div>
        </section>
    </main>

    {{-- =========================================================
        FOOTER
    ========================================================== --}}
    <footer class="border-t border-blue-100 dark:border-slate-800 bg-white/70 dark:bg-slate-950/70 mt-8 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl overflow-hidden">
                        <img src="{{ asset('images/nexus.jpg') }}" alt="ApexForge Labs" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <p class="text-sm font-black text-blue-950 dark:text-white">ApexForge Labs</p>
                        <p class="text-[11px] text-slate-400 dark:text-slate-400">Marketplace Freelance Indonesia</p>
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