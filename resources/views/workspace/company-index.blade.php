<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workspace Perusahaan - The Archipelago Nexus</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: { brand: '#2563EB', surface: '#F8FAFC' }
                }
            }
        }
    </script>
</head>
<body class="bg-surface text-slate-800 min-h-screen flex font-sans">

    @include('navbar.navigasi')

    <div class="flex-1 min-w-0 flex flex-col min-h-screen">
        @include('navbar.nav')

        <main class="flex-1 min-w-0 overflow-y-auto">
            <div class="max-w-7xl mx-auto px-6 py-8 space-y-6">

                {{-- Header --}}
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-extrabold text-slate-800">Workspace</h1>
                        <p class="text-sm text-slate-500 mt-1">Pantau progress proyek yang sedang dikerjakan freelancer.</p>
                    </div>
                </div>

                @if(session('success'))
                    <div class="flex items-center gap-3 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium">
                        <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="flex items-center gap-3 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm font-medium">
                        <i class="fa-solid fa-xmark-circle"></i> {{ session('error') }}
                    </div>
                @endif

                {{-- Daftar Workspace --}}
                @if($workspaces->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        @foreach($workspaces as $ws)
                            @php
                                $progress = $ws->latestProgress?->progress ?? 0;
                                $stageColors = [
                                    'Sedang Dikerjakan' => 'bg-blue-50 text-blue-600 border-blue-200',
                                    'Menunggu Revisi' => 'bg-amber-50 text-amber-600 border-amber-200',
                                    'Selesai' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                ];
                                $wsStatusColor = $stageColors[$ws->status] ?? 'bg-slate-50 text-slate-600 border-slate-200';
                            @endphp
                            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm hover:shadow-md transition overflow-hidden">
                                <div class="p-5">
                                    <div class="flex items-start justify-between gap-3 mb-4">
                                        <div class="min-w-0 flex-1">
                                            <h3 class="font-bold text-slate-800 truncate">{{ $ws->project->project_name }}</h3>
                                            <p class="text-xs text-slate-400 mt-1">
                                                <i class="fa-solid fa-user-tie mr-1"></i>{{ $ws->freelancer->name }}
                                            </p>
                                        </div>
                                        <span class="text-[10px] font-bold px-2.5 py-1 rounded-full border {{ $wsStatusColor }} shrink-0">
                                            {{ $ws->status }}
                                        </span>
                                    </div>

                                    {{-- Progress Bar --}}
                                    <div class="mb-3">
                                        <div class="flex items-center justify-between text-xs mb-1">
                                            <span class="text-slate-500">Progress</span>
                                            <span class="font-bold text-brand">{{ $progress }}%</span>
                                        </div>
                                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                            <div class="h-full rounded-full bg-gradient-to-r from-brand to-cyan-400 transition-all" style="width: {{ $progress }}%"></div>
                                        </div>
                                    </div>

                                    @if($ws->latestProgress)
                                        <p class="text-xs text-slate-500">
                                            <span class="font-semibold">Stage:</span> {{ $ws->latestProgress->stage }}
                                        </p>
                                    @endif
                                </div>

                                <div class="px-5 py-3 bg-slate-50 border-t border-slate-100">
                                    <a href="{{ route('company.workspaces.show', $ws) }}"
                                       class="block text-center text-sm font-semibold text-brand hover:text-white bg-brand/10 hover:bg-brand px-4 py-2 rounded-xl transition">
                                        <i class="fa-solid fa-external-link-alt mr-1"></i> Buka Workspace
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if(method_exists($workspaces, 'links'))
                        <div class="mt-8">{{ $workspaces->links() }}</div>
                    @endif
                @else
                    <div class="bg-white border border-slate-200 rounded-2xl p-16 text-center">
                        <div class="w-20 h-20 mx-auto mb-5 bg-slate-100 rounded-2xl flex items-center justify-center">
                            <i class="fa-solid fa-layer-group text-3xl text-slate-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-700">Belum Ada Workspace</h3>
                        <p class="text-sm text-slate-400 mt-2">Workspace akan muncul setelah Anda memilih freelancer untuk proyek.</p>
                        <a href="{{ route('company.projects.index') }}"
                           class="inline-flex items-center gap-2 mt-6 px-5 py-2.5 bg-brand text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
                            <i class="fa-solid fa-folder-open"></i> Lihat Proyek
                        </a>
                    </div>
                @endif

            </div>
        </main>

        @include('navbar.footer')
    </div>

</body>
</html>

