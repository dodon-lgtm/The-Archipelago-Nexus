<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan - The Archipelago Nexus</title>
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
<body class="bg-surface text-slate-800 min-h-screen flex font-sans antialiased">

    {{-- SIDEBAR --}}
    @include('navbar.navigasi')

    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col min-h-screen w-full overflow-hidden">
        @include('navbar.nav')

        <main class="flex-1 w-full overflow-y-auto p-4 sm:p-6 lg:p-8">
            <div class="max-w-3xl mx-auto">

                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="mb-4 flex items-center gap-3 px-5 py-4 bg-emerald-50/80 backdrop-blur-md border border-emerald-200/60 text-emerald-800 text-sm font-medium rounded-2xl shadow-sm">
                        <i class="fa-regular fa-circle-check"></i> {{ session('success') }}
                    </div>
                @endif

                {{-- Back Button --}}
                <div class="mb-4">
                    <a href="{{ route('company.reports.index') }}" class="text-sm text-brand hover:text-brand-dark font-semibold inline-flex items-center gap-1">
                        <i class="fa-solid fa-arrow-left"></i> Kembali ke Laporan
                    </a>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Main Content --}}
                    <div class="lg:col-span-2 space-y-6">
                        {{-- Report Detail --}}
                        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h2 class="text-xl font-bold text-slate-800">{{ $report->subject }}</h2>
                                    <p class="text-sm text-slate-500 mt-1">Laporan #{{ $report->id }}</p>
                                </div>
                                <span class="text-xs px-3 py-1.5 rounded-full font-semibold
                                    @if($report->status == 'menunggu') bg-amber-50 text-amber-600
                                    @elseif($report->status == 'diproses') bg-blue-50 text-blue-600
                                    @elseif($report->status == 'selesai') bg-emerald-50 text-emerald-600
                                    @else bg-red-50 text-red-600 @endif">{{ ucfirst($report->status) }}</span>
                            </div>

                            {{-- Description --}}
                            <div class="mb-4">
                                <p class="text-xs text-slate-500 font-semibold mb-1">Deskripsi Laporan</p>
                                <div class="bg-slate-50 rounded-xl p-4 text-sm text-slate-700 leading-relaxed">{{ $report->description }}</div>
                            </div>

                            {{-- Admin Note --}}
                            @if($report->admin_note)
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold mb-1">Catatan Admin</p>
                                    <div class="bg-blue-50 rounded-xl p-4 text-sm text-blue-700 leading-relaxed">{{ $report->admin_note }}</div>
                                </div>
                            @else
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold mb-1">Catatan Admin</p>
                                    <div class="bg-slate-50 rounded-xl p-4 text-sm text-slate-400 italic">Belum ada catatan dari admin.</div>
                                </div>
                            @endif

                            {{-- Date --}}
                            <div class="mt-4 pt-4 border-t border-slate-100">
                                <p class="text-xs text-slate-400">
                                    <i class="fa-regular fa-calendar mr-1"></i>
                                    Dibuat pada {{ $report->created_at->format('d M Y H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Sidebar --}}
                    <div class="space-y-6">
                        {{-- Reporter Info --}}
                        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-5">
                            <h3 class="font-bold text-slate-800 mb-3">Pelapor</h3>
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-lg font-bold">
                                    {{ strtoupper(substr($report->reporter->name ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800">{{ $report->reporter->name ?? '—' }}</p>
                                    <p class="text-xs text-slate-500">{{ $report->reporter->email ?? '—' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Project Info --}}
                        @if($report->project)
                            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-5">
                                <h3 class="font-bold text-slate-800 mb-3">Proyek Terkait</h3>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-slate-500">Nama</span>
                                        <span class="font-semibold">{{ $report->project->project_name }}</span>
                                    </div>
                                    @if($report->project->owner)
                                        <div class="flex justify-between">
                                            <span class="text-slate-500">Company</span>
                                            <span class="font-semibold">{{ $report->project->owner->name ?? '—' }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- Reported User --}}
                        @if($report->reportedUser)
                            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-5">
                                <h3 class="font-bold text-slate-800 mb-3">User Dilaporkan</h3>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-bold">
                                        {{ strtoupper(substr($report->reportedUser->name ?? '?', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800">{{ $report->reportedUser->name }}</p>
                                        <p class="text-xs text-slate-500">{{ $report->reportedUser->email }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Penawaran Info --}}
                        @if($report->penawaran)
                            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-5">
                                <h3 class="font-bold text-slate-800 mb-3">Penawaran Terkait</h3>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-slate-500">Nilai</span>
                                        <span class="font-semibold">Rp {{ number_format($report->penawaran->harga_penawaran, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-500">Status</span>
                                        <span class="font-semibold">{{ $report->penawaran->status }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
