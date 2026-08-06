<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Saya - The Archipelago Nexus</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
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
                        surface: '#F8FAFC'
                    }
                }
            }
        }
    </script>
    <style>
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-surface text-slate-800 min-h-screen flex font-sans antialiased selection:bg-brand selection:text-white">

    {{-- SIDEBAR --}}
    @include('navbar.navigasi')

    {{-- AREA KANAN (MAIN CONTENT) --}}
    <div class="flex-1 flex flex-col min-h-screen w-full overflow-hidden">

        @include('navbar.nav')

        <main class="flex-1 w-full overflow-y-auto p-4 sm:p-6 lg:p-8">
            <div class="w-full mx-auto space-y-6">

                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="flex items-center gap-3 px-5 py-4 bg-emerald-50/80 backdrop-blur-md border border-emerald-200/60 text-emerald-800 text-sm font-medium rounded-2xl shadow-sm">
                        <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-sm shadow-emerald-500/30">
                            <i class="fa-solid fa-check text-xs"></i>
                        </div>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="flex items-center gap-3 px-5 py-4 bg-red-50/80 backdrop-blur-md border border-red-200/60 text-red-800 text-sm font-medium rounded-2xl shadow-sm">
                        <div class="w-8 h-8 rounded-xl bg-red-500 text-white flex items-center justify-center shrink-0 shadow-sm shadow-red-500/30">
                            <i class="fa-solid fa-xmark text-xs"></i>
                        </div>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                {{-- Header --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Laporan Saya</h1>
                        <p class="text-slate-500 mt-2 text-sm sm:text-base">Pantau laporan yang telah Anda buat ke admin.</p>
                    </div>
                    <a href="{{ route('company.reports.create') }}"
                       class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-sm shadow-red-500/20">
                        <i class="fa-solid fa-plus text-xs"></i>
                        Buat Laporan
                    </a>
                </div>

                {{-- Daftar Laporan --}}
                @if($reports->count() > 0)
                    <div class="space-y-4">
                        @foreach($reports as $report)
                            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-sm hover:shadow-md transition-all duration-300">
                                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                    {{-- Left --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-2">
                                            <h3 class="text-base sm:text-lg font-bold text-slate-900 truncate">
                                                {{ $report->subject }}
                                            </h3>
<span class="text-xs px-2.5 py-1 rounded-full font-semibold shrink-0
                                                @if($report->status == 'menunggu') bg-amber-50 text-amber-600
                                                @elseif($report->status == 'ditinjau') bg-blue-50 text-blue-600
                                                @elseif($report->status == 'menunggu-bukti') bg-violet-50 text-violet-600
                                                @elseif($report->status == 'selesai') bg-emerald-50 text-emerald-600
                                                @else bg-red-50 text-red-600 @endif">{{ \App\Models\Report::statusLabel($report->status) }} <span class="ml-1 text-[10px] opacity-70">{{ \App\Models\Report::targetLabel($report->target) }}</span></span>
                                        </div>

                                        <p class="text-sm text-slate-500 leading-relaxed line-clamp-2 mb-2">
                                            {{ \Illuminate\Support\Str::limit($report->description, 150) }}
                                        </p>

<div class="flex flex-wrap items-center gap-2 text-xs text-slate-400">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-slate-100 text-slate-600 rounded-lg">
                                                <i class="fa-solid fa-tag"></i> {{ \App\Models\Report::categoryLabel($report->category) }}
                                            </span>
                                            <span><i class="fa-regular fa-calendar mr-1"></i>{{ $report->created_at->format('d M Y') }}</span>
                                            @if($report->reportedUser)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-orange-50 text-orange-600 rounded-lg">
                                                    <i class="fa-solid fa-user"></i> {{ $report->reportedUser->name }}
                                                </span>
                                            @endif
                                            @if($report->workspace)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-cyan-50 text-cyan-600 rounded-lg">
                                                    <i class="fa-solid fa-layer-group"></i> {{ \Illuminate\Support\Str::limit($report->workspace->project->project_name ?? 'Workspace', 30) }}
                                                </span>
                                            @elseif($report->project)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-50 text-blue-600 rounded-lg">
                                                    <i class="fa-solid fa-folder"></i> {{ \Illuminate\Support\Str::limit($report->project->project_name, 30) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Right --}}
                                    <div class="flex flex-row sm:flex-col items-center sm:items-end gap-2 shrink-0">
                                        <a href="{{ route('company.reports.show', $report) }}"
                                           class="px-4 py-2 text-xs font-semibold bg-brand/10 text-brand hover:bg-brand hover:text-white rounded-xl transition-all duration-200 inline-flex items-center gap-1">
                                            Lihat Detail
                                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if(method_exists($reports, 'links'))
                        <div class="mt-8">
                            {{ $reports->links() }}
                        </div>
                    @endif
                @else
                    {{-- Empty State --}}
                    <div class="flex flex-col items-center justify-center py-20 px-4 bg-white border border-slate-200/80 rounded-3xl">
                        <div class="w-24 h-24 rounded-full bg-slate-100 flex items-center justify-center mb-6">
                            <i class="fa-solid fa-flag text-4xl text-slate-300"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-700 mb-2">Belum Ada Laporan</h3>
                        <p class="text-sm text-slate-400 text-center max-w-md">
                            Anda belum membuat laporan apa pun. Jika Anda menemukan masalah, silakan buat laporan sekarang.
                        </p>
                        <a href="{{ route('company.reports.create') }}"
                           class="mt-6 inline-flex items-center gap-2 px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-sm shadow-red-500/20">
                            <i class="fa-solid fa-plus text-xs"></i>
                            Buat Laporan
                        </a>
                    </div>
                @endif

            </div>
        </main>

        {{-- FOOTER --}}
        @include('navbar.footer')

    </div>
</body>
</html>
