@php
    $title = 'Laporan Saya';
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Saya - The Archipelago Nexus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex">

    @include('navbar.navigasi')

    <div class="flex-1 flex flex-col min-h-screen overflow-hidden">
        @include('navbar.nav')

        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-4 flex items-center gap-3 px-5 py-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium rounded-xl">
                    <i class="fa-regular fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 flex items-center gap-3 px-5 py-4 bg-red-50 border border-red-200 text-red-700 text-sm font-medium rounded-xl">
                    <i class="fa-regular fa-circle-xmark"></i> {{ session('error') }}
                </div>
            @endif

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-black text-slate-900">Laporan Saya</h1>
                    <p class="text-slate-500 mt-2 text-sm sm:text-base">Kelola dan pantau laporan yang telah Anda buat.</p>
                </div>
                <a href="{{ route('freelancer.reports.create') }}"
                   class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-xl transition-colors duration-200 shadow-sm">
                    <i class="fa-solid fa-plus"></i>
                    Buat Laporan
                </a>
            </div>

            {{-- Daftar Laporan --}}
            @if($reports->count() > 0)
                <div class="space-y-4">
                    @foreach($reports as $report)
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all duration-300 p-5 sm:p-6">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                {{-- Left: Report Info --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-2">
                                        <h3 class="text-base sm:text-lg font-bold text-slate-900 truncate">
                                            {{ $report->subject }}
                                        </h3>
                                        <span class="text-xs px-2.5 py-1 rounded-full font-semibold shrink-0
                                            @if($report->status == 'menunggu') bg-amber-50 text-amber-600
                                            @elseif($report->status == 'diproses') bg-blue-50 text-blue-600
                                            @elseif($report->status == 'selesai') bg-emerald-50 text-emerald-600
                                            @else bg-red-50 text-red-600 @endif">{{ ucfirst($report->status) }}</span>
                                    </div>

                                    {{-- Description summary --}}
                                    <p class="text-sm text-slate-500 leading-relaxed line-clamp-2 mb-2">
                                        {{ Str::limit($report->description, 150) }}
                                    </p>

                                    {{-- Tags --}}
                                    <div class="flex flex-wrap items-center gap-2 text-xs text-slate-400">
                                        <span><i class="fa-regular fa-calendar mr-1"></i>{{ $report->created_at->format('d M Y') }}</span>
                                        @if($report->reportedUser)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-orange-50 text-orange-600 rounded-lg">
                                                <i class="fa-solid fa-user"></i> {{ $report->reportedUser->name }}
                                            </span>
                                        @endif
                                        @if($report->project)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-50 text-blue-600 rounded-lg">
                                                <i class="fa-solid fa-folder"></i> {{ Str::limit($report->project->project_name, 30) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Right: Action --}}
                                <div class="flex flex-row sm:flex-col items-center sm:items-end gap-2 shrink-0">
                                    <a href="{{ route('freelancer.reports.show', $report) }}"
                                       class="px-4 py-2 text-xs font-semibold bg-cyan-50 text-cyan-600 hover:bg-cyan-100 rounded-xl transition-colors duration-200 inline-flex items-center gap-1">
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
                <div class="flex flex-col items-center justify-center py-20 px-4">
                    <div class="w-24 h-24 rounded-full bg-slate-100 flex items-center justify-center mb-6">
                        <i class="fa-solid fa-flag text-4xl text-slate-300"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-700 mb-2">Belum Ada Laporan</h3>
                    <p class="text-sm text-slate-400 text-center max-w-md">
                        Anda belum membuat laporan apa pun. Jika Anda menemukan masalah, pengguna mencurigakan, atau proyek yang melanggar aturan, silakan buat laporan sekarang.
                    </p>
                    <a href="{{ route('freelancer.reports.create') }}"
                       class="mt-6 inline-flex items-center gap-2 px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-xl transition-colors duration-200">
                        <i class="fa-solid fa-plus"></i>
                        Buat Laporan
                    </a>
                </div>
            @endif

            @include('navbar.footer')
        </main>
    </div>

</body>
</html>
