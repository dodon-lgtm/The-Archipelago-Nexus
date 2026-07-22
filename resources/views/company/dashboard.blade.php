<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Perusahaan</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif']
                    },
                    colors: {
                        brand: '#2563EB',
                        surface: '#F8FAFC'
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-surface text-slate-800 min-h-screen flex font-sans">

    {{-- SIDEBAR --}}
    @include('navbar.navigasi')


    {{-- AREA KANAN --}}
    <div class="flex-1 flex flex-col min-h-screen overflow-hidden">

        {{-- NAVBAR --}}
        @include('navbar.nav')


        {{-- KONTEN --}}
        <main class="flex-1 overflow-y-auto p-6">

            <div class="max-w-7xl mx-auto space-y-6">

                {{-- WELCOME --}}
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                    <div>
                        @if(session('success'))
                            <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium rounded-lg">
                                <i class="fa-solid fa-circle-check mr-1"></i> {{ session('success') }}
                            </div>
                        @endif
                        <h1 class="text-2xl font-bold text-slate-800">
                            Selamat datang kembali 👋
                        </h1>

                        <p class="text-sm text-slate-500 mt-1">
                            Kelola proyek Anda dan temukan freelancer terbaik.
                        </p>
                    </div>

                    <a href="{{ route('company.projects.create') }}"
                       class="inline-flex items-center justify-center gap-2
                              bg-brand text-white px-5 py-3 rounded-lg
                              text-sm font-semibold
                              hover:bg-blue-700 transition">

                        <i class="fa-solid fa-plus"></i>

                        Buat Proyek Baru
                    </a>

                </div>


                {{-- STATISTIK --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                    {{-- TOTAL PROYEK --}}
                    <div class="bg-white border border-slate-200 rounded-xl p-5">

                        <div class="flex items-center gap-4">

                            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600
                                        flex items-center justify-center text-xl">

                                <i class="fa-regular fa-folder-open"></i>

                            </div>

                            <div>
                                <p class="text-xs text-slate-500">
                                    Total Proyek
                                </p>

                                <h3 class="text-2xl font-bold text-slate-800">
                                    {{ $totalProjects }}
                                </h3>
                            </div>

                        </div>

                    </div>


                    {{-- PROYEK AKTIF --}}
                    <div class="bg-white border border-slate-200 rounded-xl p-5">

                        <div class="flex items-center gap-4">

                            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600
                                        flex items-center justify-center text-xl">

                                <i class="fa-solid fa-briefcase"></i>

                            </div>

                            <div>
                                <p class="text-xs text-slate-500">
                                    Proyek Aktif
                                </p>

                                <h3 class="text-2xl font-bold text-slate-800">
                                    {{ $activeProjects }}
                                </h3>
                            </div>

                        </div>

                    </div>


                    {{-- FREELANCER --}}
                    <div class="bg-white border border-slate-200 rounded-xl p-5">

                        <div class="flex items-center gap-4">

                            <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600
                                        flex items-center justify-center text-xl">

                                <i class="fa-solid fa-user-group"></i>

                            </div>

                            <div>
                                <p class="text-xs text-slate-500">
                                    Freelancer Bekerja
                                </p>

                                <h3 class="text-2xl font-bold text-slate-800">
                                    0
                                </h3>
                            </div>

                        </div>

                    </div>

                </div>


                {{-- PROYEK ANDA (FULL WIDTH) --}}
                <div class="bg-white border border-slate-200 rounded-xl p-6">

                    <div class="flex items-center justify-between mb-6">

                        <div>
                            <h2 class="font-bold text-slate-800">
                                Proyek Anda
                            </h2>

                            <p class="text-xs text-slate-500 mt-1">
                                Daftar proyek yang Anda buat
                            </p>
                        </div>

                        <a href="{{ route('company.projects.index') }}"
                           class="text-sm text-brand font-semibold hover:underline">

                            Lihat Semua

                        </a>

                    </div>


                    @if($recentProjects->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentProjects as $project)
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-lg border border-slate-100">
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-sm font-semibold text-slate-800 truncate">
                                        <a href="{{ route('company.projects.show', $project) }}" class="hover:text-brand transition">
                                            {{ $project->project_name }}
                                        </a>
                                    </h4>
                                    <div class="flex items-center gap-3 mt-1 text-xs text-slate-400">
                                        @if($project->budget)
                                        <span><i class="fa-regular fa-money-bill-1 mr-1"></i>Rp {{ number_format($project->budget, 0, ',', '.') }}</span>
                                        @endif
                                        @if($project->deadline)
                                        <span><i class="fa-regular fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($project->deadline)->format('d M Y') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <span class="text-[10px] font-semibold px-2.5 py-1 rounded-full shrink-0 ml-3
                                    @if($project->status == 'Open') bg-emerald-50 text-emerald-600
                                    @elseif($project->status == 'Closed') bg-red-50 text-red-600
                                    @else bg-slate-100 text-slate-500 @endif
                                ">
                                    {{ $project->status ?? 'Draft' }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-16 text-center">

                            <div class="w-16 h-16 mx-auto mb-4
                                        bg-slate-100 rounded-full
                                        flex items-center justify-center">

                                <i class="fa-regular fa-folder-open
                                          text-2xl text-slate-400"></i>

                            </div>

                            <h3 class="font-semibold text-slate-700">
                                Belum ada proyek
                            </h3>

                            <p class="text-sm text-slate-500 mt-2">
                                Anda belum membuat proyek.
                            </p>

                            <a href="{{ route('company.projects.create') }}"
                               class="inline-flex items-center gap-2
                                      mt-5 px-4 py-2
                                      bg-brand text-white
                                      rounded-lg text-sm font-semibold
                                      hover:bg-blue-700 transition">

                                <i class="fa-solid fa-plus"></i>

                                Buat Proyek

                            </a>

                        </div>
                    @endif

                </div>


                {{-- AKTIVITAS TERBARU --}}
                <div class="bg-white border border-slate-200 rounded-xl p-6">

                    <div class="mb-6">

                        <h2 class="font-bold text-slate-800">
                            Aktivitas Terbaru
                        </h2>

                        <p class="text-xs text-slate-500 mt-1">
                            Aktivitas terbaru akun Anda
                        </p>

                    </div>


                    {{-- EMPTY STATE --}}
                    <div class="py-12 text-center">

                        <div class="w-14 h-14 mx-auto mb-4
                                    bg-slate-100 rounded-full
                                    flex items-center justify-center">

                            <i class="fa-regular fa-bell
                                      text-xl text-slate-400"></i>

                        </div>

                        <h3 class="text-sm font-semibold text-slate-700">
                            Belum ada aktivitas
                        </h3>

                        <p class="text-xs text-slate-500 mt-2">
                            Aktivitas terbaru akan muncul di sini.
                        </p>

                    </div>

                </div>

            </div>

        </main>


        {{-- FOOTER --}}
        @include('navbar.footer')

    </div>

</body>
</html>