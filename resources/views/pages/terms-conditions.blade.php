<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.theme-boot')
    <title>Syarat &amp; Ketentuan - ApexForge Labs</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    {{-- Google Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        * { font-family: 'Inter', sans-serif; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            background: #f8faff;
            color: #0f172a;
            transition: background-color .3s ease, color .3s ease;
        }
        .dark body {
            background: #0f172a;
            color: #f1f5f9;
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <header class="sticky top-0 z-40 bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl border-b border-blue-100 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 h-16 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2.5">
                <div class="w-9 h-9 bg-gradient-to-br from-blue-600 to-blue-500 rounded-xl flex items-center justify-center text-white shadow-[0_5px_15px_rgba(59,130,246,0.3)]">
                    <i class="fa-solid fa-satellite-dish text-sm"></i>
                </div>
                <span class="font-black text-lg text-blue-950 dark:text-white tracking-tight">
                    ApexForge<span class="text-blue-600 dark:text-blue-400">Labs</span>
                </span>
            </a>
            <a href="{{ url('/') }}"
                class="px-4 py-2 text-sm font-bold text-slate-700 dark:text-slate-300 border border-blue-100 dark:border-slate-800 rounded-xl hover:bg-blue-50 dark:hover:bg-slate-800 transition">
                <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali ke Beranda
            </a>
        </div>
    </header>

    {{-- Hero --}}
    <section class="bg-gradient-to-b from-blue-50/60 dark:from-slate-800/40 to-transparent py-14">
        <div class="max-w-3xl mx-auto px-6 text-center">
            <div class="w-14 h-14 mx-auto rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center text-white text-xl mb-4 shadow-[0_10px_25px_-5px_rgba(16,185,129,0.4)]">
                <i class="fa-solid fa-file-contract"></i>
            </div>
            <h1 class="text-3xl sm:text-4xl font-black text-blue-950 dark:text-white tracking-tight">Syarat &amp; Ketentuan</h1>
            <p class="mt-3 text-sm text-blue-900/50 dark:text-slate-400 font-medium">Terakhir diperbarui:
                {{ $setting->updated_at ? $setting->updated_at->translatedFormat('d M Y') : '-' }}</p>
        </div>
    </section>

    {{-- Konten --}}
    <section class="py-12 pb-24">
        <div class="max-w-3xl mx-auto px-6">
            <article class="bg-white dark:bg-slate-900 rounded-2xl border border-blue-100 dark:border-slate-800 shadow-sm p-6 sm:p-10">
                @if (trim((string) $setting->terms_conditions_content) !== '')
                    <div class="space-y-4 text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                        @foreach (preg_split('/\r\n|\r|\n/', $setting->terms_conditions_content) as $paragraph)
                            @php($text = trim($paragraph))
                            @if ($text !== '')
                                <p>{!! nl2br(e($text)) !!}</p>
                            @endif
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-slate-400">Konten Syarat &amp; Ketentuan belum tersedia.</p>
                @endif
            </article>
        </div>
    </section>

    {{-- Footer Dinamis --}}
    @include('navbar.footer')
</body>
</html>