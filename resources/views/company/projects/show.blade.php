<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $project->project_name }} - Detail Proyek</title>

    {{-- Google Font --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    {{-- Font Awesome --}}
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    >

    {{-- Tailwind --}}
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


<body class="bg-slate-50 text-slate-800 min-h-screen flex font-sans">


    {{-- =====================================================
        SIDEBAR
    ====================================================== --}}
    @include('navbar.navigasi')


    {{-- =====================================================
        AREA KANAN
    ====================================================== --}}
    <div class="flex-1 min-w-0 flex flex-col min-h-screen">


        {{-- =================================================
            NAVBAR ATAS
        ================================================== --}}
        @include('navbar.nav')


        {{-- =================================================
            KONTEN UTAMA
        ================================================== --}}
        <main class="flex-1 min-w-0 overflow-y-auto">

            <div class="max-w-7xl mx-auto px-6 py-8">


                {{-- =========================================
                    BREADCRUMB
                ========================================== --}}
                <div class="flex items-center gap-2 text-sm text-slate-400 mb-5">

                    <a
                        href="{{ route('company.dashboard') }}"
                        class="hover:text-brand transition"
                    >
                        Dashboard
                    </a>

                    <i class="fa-solid fa-chevron-right text-[10px]"></i>

                    <a
                        href="{{ route('company.projects.index') }}"
                        class="hover:text-brand transition"
                    >
                        Proyek
                    </a>

                    <i class="fa-solid fa-chevron-right text-[10px]"></i>

                    <span class="text-slate-600 font-medium">
                        Detail
                    </span>

                </div>


                {{-- =========================================
                    HEADER HALAMAN
                ========================================== --}}
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5 mb-8">

                    <div>

                        <div class="flex items-center gap-3 mb-2">

                            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">

                                <i class="fa-solid fa-folder-open"></i>

                            </div>

                            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800">
                                {{ $project->project_name }}
                            </h1>

                        </div>

                        <p class="text-sm text-slate-500">
                            Kelola detail proyek dan penawaran freelancer.
                        </p>

                    </div>


                    {{-- TOMBOL KEMBALI --}}
                    <a
                        href="{{ route('company.projects.index') }}"
                        class="inline-flex items-center justify-center gap-2
                               px-4 py-2.5
                               bg-white
                               border border-slate-200
                               rounded-xl
                               text-sm font-semibold
                               text-slate-600
                               hover:bg-slate-50
                               hover:border-slate-300
                               transition"
                    >

                        <i class="fa-solid fa-arrow-left text-xs"></i>

                        Kembali ke Proyek

                    </a>

                </div>


                {{-- =========================================
                    FLASH MESSAGE
                ========================================== --}}
                @if(session('success'))

                    <div class="mb-6 flex items-center gap-3
                                px-4 py-3
                                bg-emerald-50
                                border border-emerald-200
                                text-emerald-700
                                rounded-xl
                                text-sm font-medium">

                        <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">

                            <i class="fa-solid fa-check text-emerald-600"></i>

                        </div>

                        <span>
                            {{ session('success') }}
                        </span>

                    </div>

                @endif


                @if(session('error'))

                    <div class="mb-6 flex items-center gap-3
                                px-4 py-3
                                bg-red-50
                                border border-red-200
                                text-red-700
                                rounded-xl
                                text-sm font-medium">

                        <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center shrink-0">

                            <i class="fa-solid fa-xmark text-red-600"></i>

                        </div>

                        <span>
                            {{ session('error') }}
                        </span>

                    </div>

                @endif



                {{-- =================================================
                    INFORMASI PROYEK
                ================================================== --}}
                <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm mb-8">


                    {{-- HEADER CARD --}}
                    <div class="px-6 py-5 border-b border-slate-100
                                flex flex-col md:flex-row
                                md:items-center
                                md:justify-between
                                gap-4">

                        <div class="flex items-center gap-4">

                            <div class="w-12 h-12 rounded-xl
                                        bg-blue-100
                                        text-blue-600
                                        flex items-center justify-center
                                        text-xl">

                                <i class="fa-solid fa-folder-open"></i>

                            </div>

                            <div>

                                <h2 class="font-bold text-lg text-slate-800">
                                    Informasi Proyek
                                </h2>

                                <p class="text-xs text-slate-500 mt-1">
                                    Detail informasi proyek yang Anda buat
                                </p>

                            </div>

                        </div>


                        {{-- STATUS --}}
                        @php
                            $status = $project->status ?? 'Draft';
                        @endphp

                        @if($status === 'Open')

                            <span class="inline-flex items-center gap-2
                                         px-3 py-1.5
                                         rounded-full
                                         bg-emerald-50
                                         text-emerald-600
                                         border border-emerald-100
                                         text-xs font-bold">

                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>

                                Open

                            </span>

                        @elseif($status === 'Closed')

                            <span class="inline-flex items-center gap-2
                                         px-3 py-1.5
                                         rounded-full
                                         bg-red-50
                                         text-red-600
                                         border border-red-100
                                         text-xs font-bold">

                                <span class="w-2 h-2 rounded-full bg-red-500"></span>

                                Closed

                            </span>

                        @else

                            <span class="inline-flex items-center gap-2
                                         px-3 py-1.5
                                         rounded-full
                                         bg-slate-100
                                         text-slate-600
                                         border border-slate-200
                                         text-xs font-bold">

                                <span class="w-2 h-2 rounded-full bg-slate-400"></span>

                                {{ $status }}

                            </span>

                        @endif

                    </div>



                    {{-- ISI INFORMASI --}}
                    <div class="p-6">


                        {{-- DESKRIPSI --}}
                        <div class="mb-6">

                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">
                                Deskripsi Proyek
                            </p>

                            <div class="bg-slate-50
                                        border border-slate-100
                                        rounded-xl
                                        p-5">

                                @if($project->project_description)

                                    <p class="text-sm text-slate-600 leading-relaxed whitespace-pre-line">
                                        {{ $project->project_description }}
                                    </p>

                                @else

                                    <p class="text-sm text-slate-400">
                                        Tidak ada deskripsi proyek.
                                    </p>

                                @endif

                            </div>

                        </div>



                        {{-- INFORMASI TAMBAHAN --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">


                            {{-- BUDGET --}}
                            <div class="border border-slate-100 rounded-xl p-4">

                                <div class="flex items-center gap-3">

                                    <div class="w-10 h-10 rounded-lg
                                                bg-emerald-50
                                                text-emerald-600
                                                flex items-center justify-center">

                                        <i class="fa-solid fa-wallet"></i>

                                    </div>

                                    <div>

                                        <p class="text-xs text-slate-400">
                                            Budget
                                        </p>

                                        <p class="text-sm font-bold text-slate-700 mt-1">

                                            @if($project->budget)

                                                Rp {{ number_format($project->budget, 0, ',', '.') }}

                                            @else

                                                Belum ditentukan

                                            @endif

                                        </p>

                                    </div>

                                </div>

                            </div>



                            {{-- DEADLINE --}}
                            <div class="border border-slate-100 rounded-xl p-4">

                                <div class="flex items-center gap-3">

                                    <div class="w-10 h-10 rounded-lg
                                                bg-orange-50
                                                text-orange-600
                                                flex items-center justify-center">

                                        <i class="fa-regular fa-calendar"></i>

                                    </div>

                                    <div>

                                        <p class="text-xs text-slate-400">
                                            Deadline
                                        </p>

                                        <p class="text-sm font-bold text-slate-700 mt-1">

                                            @if($project->deadline)

                                                {{ \Carbon\Carbon::parse($project->deadline)->format('d M Y') }}

                                            @else

                                                Belum ditentukan

                                            @endif

                                        </p>

                                    </div>

                                </div>

                            </div>



                            {{-- JUMLAH PENAWARAN --}}
                            <div class="border border-slate-100 rounded-xl p-4">

                                <div class="flex items-center gap-3">

                                    <div class="w-10 h-10 rounded-lg
                                                bg-purple-50
                                                text-purple-600
                                                flex items-center justify-center">

                                        <i class="fa-solid fa-users"></i>

                                    </div>

                                    <div>

                                        <p class="text-xs text-slate-400">
                                            Penawaran Masuk
                                        </p>

                                        <p class="text-sm font-bold text-slate-700 mt-1">
                                            {{ $project->penawarans->count() }} Freelancer
                                        </p>

                                    </div>

                                </div>

                            </div>

                        </div>



                        {{-- ACTION BUTTON --}}
                        <div class="flex flex-wrap gap-3 pt-5 border-t border-slate-100">

                            <a
                                href="{{ route('company.projects.edit', $project) }}"
                                class="inline-flex items-center gap-2
                                       px-4 py-2.5
                                       bg-brand
                                       text-white
                                       rounded-lg
                                       text-sm font-semibold
                                       hover:bg-blue-700
                                       transition"
                            >

                                <i class="fa-solid fa-pen-to-square"></i>

                                Edit Proyek

                            </a>


                            <form
                                method="POST"
                                action="{{ route('company.projects.destroy', $project) }}"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus proyek ini?');"
                            >

                                @csrf

                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="inline-flex items-center gap-2
                                           px-4 py-2.5
                                           bg-red-50
                                           text-red-600
                                           border border-red-100
                                           rounded-lg
                                           text-sm font-semibold
                                           hover:bg-red-100
                                           transition"
                                >

                                    <i class="fa-solid fa-trash"></i>

                                    Hapus Proyek

                                </button>

                            </form>

                        </div>

                    </div>

                </div>



                {{-- =================================================
                    PENAWARAN FREELANCER
                ================================================== --}}
                <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">


                    {{-- HEADER --}}
                    <div class="px-6 py-5 border-b border-slate-100">

                        <div class="flex flex-col md:flex-row
                                    md:items-center
                                    md:justify-between
                                    gap-4">

                            <div>

                                <h2 class="text-lg font-bold text-slate-800">
                                    Penawaran Freelancer
                                </h2>

                                <p class="text-xs text-slate-500 mt-1">
                                    Lihat dan pilih freelancer yang mengajukan penawaran untuk proyek ini.
                                </p>

                            </div>


                            <div class="inline-flex items-center gap-2
                                        px-3 py-2
                                        bg-blue-50
                                        text-blue-600
                                        rounded-lg
                                        text-xs font-semibold">

                                <i class="fa-solid fa-users"></i>

                                {{ $project->penawarans->count() }} Penawaran

                            </div>

                        </div>

                    </div>



                    {{-- ISI PENAWARAN --}}
                    <div class="p-6">

                        @if ($project->penawarans->isEmpty())


                            {{-- EMPTY STATE --}}
                            <div class="py-16 text-center">

                                <div class="w-16 h-16 mx-auto mb-5
                                            bg-slate-100
                                            rounded-2xl
                                            flex items-center justify-center">

                                    <i class="fa-regular fa-file-lines
                                              text-2xl
                                              text-slate-400"></i>

                                </div>

                                <h3 class="text-base font-bold text-slate-700">
                                    Belum Ada Penawaran
                                </h3>

                                <p class="text-sm text-slate-400 mt-2">
                                    Penawaran dari freelancer akan muncul di sini.
                                </p>

                            </div>


                        @else


                            {{-- CEK APAKAH SUDAH ADA YANG DITERIMA --}}
                            @php
                                $hasAccepted = $project->penawarans->contains(
                                    fn($p) => $p->status === 'Diterima'
                                );
                            @endphp


                            <div class="space-y-4">


                                @foreach ($project->penawarans as $penawaran)

                                    <div class="border border-slate-200
                                                rounded-xl
                                                p-5
                                                hover:border-slate-300
                                                transition">


                                        {{-- BAGIAN ATAS --}}
                                        <div class="flex flex-col lg:flex-row
                                                    lg:items-start
                                                    lg:justify-between
                                                    gap-5">


                                            {{-- FREELANCER --}}
                                            <div class="flex items-center gap-4">

                                                <div class="w-12 h-12
                                                            rounded-full
                                                            bg-blue-100
                                                            text-blue-600
                                                            flex items-center justify-center
                                                            font-bold">

                                                    {{ strtoupper(substr($penawaran->freelancer->name ?? 'F', 0, 1)) }}

                                                </div>


                                                <div>

                                                    <h3 class="font-bold text-slate-800">
                                                        {{ $penawaran->freelancer->name ?? 'Tidak diketahui' }}
                                                    </h3>

                                                    <p class="text-xs text-slate-400 mt-1">
                                                        Freelancer
                                                    </p>

                                                </div>

                                            </div>



                                            {{-- STATUS --}}
                                            <div>

                                                @if ($penawaran->status === 'Menunggu')

                                                    <span class="inline-flex items-center gap-2
                                                                 px-3 py-1.5
                                                                 rounded-full
                                                                 bg-amber-50
                                                                 text-amber-600
                                                                 border border-amber-100
                                                                 text-xs font-bold">

                                                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>

                                                        Menunggu

                                                    </span>

                                                @elseif ($penawaran->status === 'Diterima')

                                                    <span class="inline-flex items-center gap-2
                                                                 px-3 py-1.5
                                                                 rounded-full
                                                                 bg-emerald-50
                                                                 text-emerald-600
                                                                 border border-emerald-100
                                                                 text-xs font-bold">

                                                        <i class="fa-solid fa-check"></i>

                                                        Freelancer Terpilih

                                                    </span>

                                                @else

                                                    <span class="inline-flex items-center gap-2
                                                                 px-3 py-1.5
                                                                 rounded-full
                                                                 bg-red-50
                                                                 text-red-600
                                                                 border border-red-100
                                                                 text-xs font-bold">

                                                        <i class="fa-solid fa-xmark"></i>

                                                        Ditolak

                                                    </span>

                                                @endif

                                            </div>

                                        </div>



                                        {{-- DETAIL PENAWARAN --}}
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-5">


                                            {{-- HARGA --}}
                                            <div class="bg-slate-50 rounded-lg p-4">

                                                <p class="text-xs text-slate-400">
                                                    Harga Penawaran
                                                </p>

                                                <p class="text-base font-bold text-slate-800 mt-1">

                                                    Rp {{ number_format($penawaran->harga_penawaran, 0, ',', '.') }}

                                                </p>

                                            </div>


                                            {{-- ESTIMASI --}}
                                            <div class="bg-slate-50 rounded-lg p-4">

                                                <p class="text-xs text-slate-400">
                                                    Estimasi Pengerjaan
                                                </p>

                                                <p class="text-base font-bold text-slate-800 mt-1">

                                                    {{ $penawaran->estimasi_hari }} Hari

                                                </p>

                                            </div>


                                            {{-- WAKTU --}}
                                            <div class="bg-slate-50 rounded-lg p-4">

                                                <p class="text-xs text-slate-400">
                                                    Waktu Dipilih
                                                </p>

                                                <p class="text-sm font-bold text-slate-800 mt-1">

                                                    @if ($penawaran->selected_at)

                                                        {{ $penawaran->selected_at->format('d M Y H:i') }}

                                                    @else

                                                        Belum dipilih

                                                    @endif

                                                </p>

                                            </div>

                                        </div>



                                        {{-- PESAN --}}
                                        @if($penawaran->pesan)

                                            <div class="mt-4">

                                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">
                                                    Pesan Freelancer
                                                </p>

                                                <div class="bg-slate-50
                                                            border border-slate-100
                                                            rounded-lg
                                                            p-4">

                                                    <p class="text-sm text-slate-600 leading-relaxed">
                                                        {{ $penawaran->pesan }}
                                                    </p>

                                                </div>

                                            </div>

                                        @endif



                                        {{-- BAGIAN BAWAH --}}
                                        <div class="flex flex-col sm:flex-row
                                                    sm:items-center
                                                    sm:justify-between
                                                    gap-4
                                                    mt-5
                                                    pt-5
                                                    border-t border-slate-100">


                                            {{-- PROPOSAL --}}
                                            <div>

                                                @if ($penawaran->proposal)

                                                    <a
                                                        href="{{ asset('storage/' . $penawaran->proposal) }}"
                                                        target="_blank"
                                                        class="inline-flex items-center gap-2
                                                               px-3 py-2
                                                               border border-blue-200
                                                               text-blue-600
                                                               bg-blue-50
                                                               rounded-lg
                                                               text-xs font-semibold
                                                               hover:bg-blue-100
                                                               transition"
                                                    >

                                                        <i class="fa-regular fa-file-lines"></i>

                                                        Lihat Proposal

                                                    </a>

                                                @else

                                                    <span class="text-xs text-slate-400">
                                                        Tidak ada file proposal
                                                    </span>

                                                @endif

                                            </div>



                                            {{-- ACTION --}}
                                            <div>

                                                @if ($penawaran->status === 'Menunggu' && !$hasAccepted)

                                                    <form
                                                        method="POST"
                                                        action="{{ route('company.projects.penawaran.select', [$project, $penawaran]) }}"
                                                        onsubmit="return confirm('Pilih freelancer ini? Penawaran lain akan otomatis ditolak.');"
                                                    >

                                                        @csrf

                                                        <button
                                                            type="submit"
                                                            class="inline-flex items-center gap-2
                                                                   px-4 py-2.5
                                                                   bg-emerald-500
                                                                   text-white
                                                                   rounded-lg
                                                                   text-xs font-bold
                                                                   hover:bg-emerald-600
                                                                   transition"
                                                        >

                                                            <i class="fa-solid fa-check"></i>

                                                            Pilih Freelancer

                                                        </button>

                                                    </form>


                                                @elseif ($penawaran->status === 'Diterima')

                                                    <span class="inline-flex items-center gap-2
                                                                 px-4 py-2.5
                                                                 bg-emerald-50
                                                                 text-emerald-600
                                                                 rounded-lg
                                                                 text-xs font-bold">

                                                        <i class="fa-solid fa-circle-check"></i>

                                                        Freelancer Terpilih

                                                    </span>


                                                @else

                                                    <span class="text-xs text-slate-400">
                                                        Penawaran tidak tersedia
                                                    </span>

                                                @endif

                                            </div>

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        @endif

                    </div>

                </div>


            </div>

        </main>


        {{-- =================================================
            FOOTER
        ================================================== --}}
        @include('navbar.footer')


    </div>

</body>

</html>