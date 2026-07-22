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
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

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
                                    0
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
                                    0
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


                    {{-- PENGELUARAN --}}
                    <div class="bg-white border border-slate-200 rounded-xl p-5">

                        <div class="flex items-center gap-4">

                            <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600
                                        flex items-center justify-center text-xl">

                                <i class="fa-solid fa-wallet"></i>

                            </div>

                            <div>
                                <p class="text-xs text-slate-500">
                                    Total Pengeluaran
                                </p>

                                <h3 class="text-xl font-bold text-slate-800">
                                    Rp 0
                                </h3>
                            </div>

                        </div>

                    </div>

                </div>


                {{-- GRID UTAMA --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">


                    {{-- PROYEK --}}
                    <div class="lg:col-span-2">

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

                                <a href="{{ url('/company/projects') }}"
                                   class="text-sm text-brand font-semibold hover:underline">

                                    Lihat Semua

                                </a>

                            </div>


                            {{-- EMPTY STATE --}}
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

                        </div>

                    </div>


                    {{-- AKTIVITAS --}}
                    <div>

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

                </div>


                {{-- BAGIAN PROPOSAL --}}
                <div class="bg-white border border-slate-200 rounded-xl p-6">

                    <div class="flex items-center justify-between mb-6">

                        <div>
                            <h2 class="font-bold text-slate-800">
                                Proposal Masuk
                            </h2>

                            <p class="text-xs text-slate-500 mt-1">
                                Proposal dari freelancer untuk proyek Anda
                            </p>
                        </div>

                    </div>


                    {{-- EMPTY STATE --}}
                    <div class="py-12 text-center">

                        <div class="w-14 h-14 mx-auto mb-4
                                    bg-slate-100 rounded-full
                                    flex items-center justify-center">

                            <i class="fa-regular fa-file-lines
                                      text-xl text-slate-400"></i>

                        </div>

                        <h3 class="text-sm font-semibold text-slate-700">
                            Belum ada proposal
                        </h3>

                        <p class="text-xs text-slate-500 mt-2">
                            Proposal freelancer akan muncul di sini.
                        </p>

                    </div>

                </div>


                {{-- PENGELUARAN --}}
                <div class="bg-white border border-slate-200 rounded-xl p-6">

                    <div class="flex items-center justify-between">

                        <div>
                            <h2 class="font-bold text-slate-800">
                                Pengeluaran
                            </h2>

                            <p class="text-xs text-slate-500 mt-1">
                                Ringkasan pengeluaran proyek Anda
                            </p>
                        </div>

                        <span class="text-lg font-bold text-slate-800">
                            Rp 0
                        </span>

                    </div>


                    <div class="mt-6 h-32 flex items-center justify-center
                                border border-dashed border-slate-200
                                rounded-lg">

                        <p class="text-sm text-slate-400">
                            Belum ada data pengeluaran
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