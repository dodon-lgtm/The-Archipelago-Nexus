<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pusat Bantuan - ApexForge Labs</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>


    {{-- Font Awesome --}}
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    {{-- Google Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet"
    >

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        html {
            scroll-behavior: smooth;
        }

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

        .faq-content {
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            transition:
                max-height 0.35s ease,
                opacity 0.25s ease,
                padding 0.35s ease;
        }

        .faq-item.active .faq-content {
            max-height: 500px;
            opacity: 1;
        }

        .faq-item.active .faq-icon {
            transform: rotate(180deg);
        }

        .faq-icon {
            transition: transform 0.3s ease;
        }
    </style>
</head>

<body>
    {{-- Anti-Flicker Script: Apply saved mode before render (Synchronized with Landing Page keys) --}}
    <script>
        (function() {
            const htmlElement = document.documentElement;
            const savedTheme = localStorage.getItem('apexforge_theme') || localStorage.getItem('theme') || localStorage.getItem('color-theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                htmlElement.classList.add('dark');
            } else {
                htmlElement.classList.remove('dark');
            }
        })();
    </script>

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <header class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8">
            <div class="h-20 flex items-center justify-between">

                {{-- BRAND --}}
                <a
                    href="{{ url('/') }}"
                    class="flex items-center gap-3 group"
                >
                  <div 
    class="w-12 h-12 rounded-2xl overflow-hidden flex items-center justify-center shadow-lg shadow-blue-500/20 group-hover:scale-105 transition-transform duration-300"
>
    <img 
        src="{{ asset('images/nexus.jpg') }}" 
        alt="Nexus"
        class="w-full h-full object-cover"
    >
</div>

                    <div class="leading-tight">
                        <h1 class="text-base sm:text-lg font-black text-slate-900 dark:text-slate-100">
                            ApexForge Labs
                        </h1>

                        <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-300">
                            Pusat Bantuan
                        </p>
                    </div>
                </a>


                {{-- KEMBALI KE BERANDA --}}
                <a
                    href="{{ url('/') }}"
                    class="inline-flex items-center gap-2 px-4 sm:px-5 py-2.5 rounded-xl border border-blue-100 bg-white text-slate-700 font-bold text-sm hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition-all duration-300 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-700 dark:hover:text-blue-300 shadow-sm"
                >
                    <i class="fa-solid fa-arrow-left text-blue-500 dark:text-slate-300"></i>

                    <span class="hidden sm:inline">
                        Kembali ke Beranda
                    </span>

                    <span class="sm:hidden">
                        Beranda
                    </span>
                </a>

            </div>
        </div>
    </header>


    {{-- =========================================================
        HERO
    ========================================================== --}}
    <section
        class="relative overflow-hidden bg-white dark:bg-slate-900 dark:text-slate-100 bg-gradient-to-br from-blue-50 via-white to-sky-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900"
    >

        {{-- Decorative background --}}
        <div
            class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-blue-200/30 blur-3xl dark:bg-slate-800/30"
        ></div>

        <div
            class="absolute -bottom-40 -left-32 w-96 h-96 rounded-full bg-indigo-200/20 blur-3xl dark:bg-slate-800/20"
        ></div>


        <div
            class="relative max-w-5xl mx-auto px-5 sm:px-6 py-20 sm:py-24 text-center dark:text-slate-100"
        >

            {{-- Badge --}}
            <div
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-blue-100 bg-blue-50 text-blue-600 text-sm font-bold mb-7 dark:border-slate-700 dark:bg-slate-800 dark:text-blue-400"
            >
                <i class="fa-solid fa-circle-question"></i>
                Pusat Bantuan
            </div>


            {{-- Heading --}}
            <h2
                class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-slate-950 dark:text-white leading-tight"
            >
                Ada yang bisa kami bantu?
            </h2>


            {{-- Description --}}
            <p
                class="mt-5 max-w-3xl mx-auto text-base sm:text-lg leading-8 text-slate-500 dark:text-slate-300"
            >
                Temukan informasi lengkap mengenai penggunaan ApexForge Labs,
                mulai dari akun, proyek, proses pengerjaan, pembayaran,
                keamanan, hingga berbagai hal yang berkaitan dengan layanan
                platform.
            </p>

        </div>
    </section>


    {{-- =========================================================
        CATEGORY OVERVIEW
    ========================================================== --}}
    <section class="bg-white dark:bg-slate-900 border-b border-slate-100 dark:border-slate-800 transition-colors">
        <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8 py-10">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

                {{-- AKUN --}}
                <a
                    href="#akun"
                    class="group rounded-2xl border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-800 p-6 hover:border-blue-200 hover:shadow-lg hover:shadow-blue-100/40 dark:hover:border-blue-500/50 dark:hover:shadow-blue-900/30 transition-all duration-300"
                >
                    <div
                        class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-slate-700 text-blue-600 dark:text-blue-400 flex items-center justify-center mb-5 group-hover:scale-105 transition-transform"
                    >
                        <i class="fa-solid fa-user text-lg"></i>
                    </div>

                    <h3 class="font-extrabold text-lg text-slate-900 dark:text-slate-100">
                        Akun
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-300">
                        Informasi mengenai registrasi, login, profil,
                        dan pengaturan akun.
                    </p>
                </a>


                {{-- PROYEK --}}
                <a
                    href="#proyek"
                    class="group rounded-2xl border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-800 p-6 hover:border-indigo-200 hover:shadow-lg hover:shadow-indigo-100/40 dark:hover:border-indigo-500/50 dark:hover:shadow-indigo-900/30 transition-all duration-300"
                >
                    <div
                        class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-5 group-hover:scale-105 transition-transform"
                    >
                        <i class="fa-solid fa-briefcase text-lg"></i>
                    </div>

                    <h3 class="font-extrabold text-lg text-slate-900 dark:text-slate-100">
                        Proyek
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-300">
                        Informasi mencari proyek, mengajukan penawaran,
                        dan proses pengerjaan.
                    </p>
                </a>


                {{-- PEMBAYARAN --}}
                <a
                    href="#pembayaran"
                    class="group rounded-2xl border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-800 p-6 hover:border-sky-200 hover:shadow-lg hover:shadow-sky-100/40 dark:hover:border-sky-500/50 dark:hover:shadow-sky-900/30 transition-all duration-300"
                >
                    <div
                        class="w-12 h-12 rounded-xl bg-sky-50 dark:bg-slate-700 text-sky-600 dark:text-sky-400 flex items-center justify-center mb-5 group-hover:scale-105 transition-transform"
                    >
                        <i class="fa-solid fa-wallet text-lg"></i>
                    </div>

                    <h3 class="font-extrabold text-lg text-slate-900 dark:text-slate-100">
                        Pembayaran
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-300">
                        Informasi pembayaran, transaksi, dan proses
                        penyelesaian proyek.
                    </p>
                </a>


                {{-- KEAMANAN --}}
                <a
                    href="#keamanan"
                    class="group rounded-2xl border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-800 p-6 hover:border-emerald-200 hover:shadow-lg hover:shadow-emerald-100/40 dark:hover:border-emerald-500/50 dark:hover:shadow-emerald-900/30 transition-all duration-300"
                >
                    <div
                        class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-slate-700 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-5 group-hover:scale-105 transition-transform"
                    >
                        <i class="fa-solid fa-shield-halved text-lg"></i>
                    </div>

                    <h3 class="font-extrabold text-lg text-slate-900 dark:text-slate-100">
                        Keamanan
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-300">
                        Informasi mengenai keamanan akun, password,
                        dan data pengguna.
                    </p>
                </a>

            </div>

        </div>
    </section>


    {{-- =========================================================
        FAQ
    ========================================================== --}}
    <main class="max-w-5xl mx-auto px-5 sm:px-6 py-16 sm:py-20">


        {{-- =====================================================
            AKUN
        ====================================================== --}}
        <section id="akun" class="mb-16 scroll-mt-28">

            <div class="mb-7">
                <div class="flex items-center gap-3 mb-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-slate-700 text-blue-600 dark:text-blue-400 flex items-center justify-center"
                    >
                        <i class="fa-solid fa-user"></i>
                    </div>

                    <h2 class="text-2xl sm:text-3xl font-black text-slate-950 dark:text-slate-100">
                        Akun
                    </h2>
                </div>

                <p class="text-slate-500 dark:text-slate-300 leading-7">
                    Berikut beberapa pertanyaan yang berkaitan dengan akun
                    pengguna ApexForge Labs.
                </p>
            </div>


            <div class="space-y-4">

                {{-- FAQ 1 --}}
                <div class="faq-item rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-sm dark:border-slate-700 dark:bg-slate-800">

                    <button
                        type="button"
                        class="faq-button w-full flex items-center justify-between gap-5 text-left px-6 py-5 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors"
                    >
                        <span class="font-bold text-slate-900 dark:text-slate-100 leading-7">
                            Bagaimana cara membuat akun di ApexForge Labs?
                        </span>

                        <span
                            class="faq-icon shrink-0 w-9 h-9 rounded-xl bg-blue-50 dark:bg-slate-700 text-blue-600 dark:text-blue-400 flex items-center justify-center"
                        >
                            <i class="fa-solid fa-chevron-down text-sm"></i>
                        </span>
                    </button>

                    <div class="faq-content">
                        <div class="px-6 pb-6 text-slate-600 dark:text-slate-300 leading-8">
                            Untuk membuat akun, buka halaman pendaftaran melalui
                            tombol daftar yang tersedia pada halaman utama.
                            Lengkapi seluruh informasi yang diminta dengan data
                            yang benar dan masih aktif. Pastikan alamat email
                            yang digunakan dapat diakses karena informasi
                            tertentu mengenai akun dapat dikirimkan melalui
                            email tersebut. Setelah proses pendaftaran selesai,
                            kamu dapat menggunakan akun untuk masuk dan
                            mengakses fitur yang tersedia sesuai dengan peran
                            akunmu di platform.
                        </div>
                    </div>

                </div>


                {{-- FAQ 2 --}}
                <div class="faq-item rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-sm dark:border-slate-700 dark:bg-slate-800">

                    <button
                        type="button"
                        class="faq-button w-full flex items-center justify-between gap-5 text-left px-6 py-5 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors"
                    >
                        <span class="font-bold text-slate-900 dark:text-slate-100 leading-7">
                            Apa yang harus dilakukan jika tidak bisa login?
                        </span>

                        <span class="faq-icon shrink-0 w-9 h-9 rounded-xl bg-blue-50 dark:bg-slate-700 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                            <i class="fa-solid fa-chevron-down text-sm"></i>
                        </span>
                    </button>

                    <div class="faq-content">
                        <div class="px-6 pb-6 text-slate-600 dark:text-slate-300 leading-8">
                            Pastikan email dan password yang dimasukkan sudah
                            sesuai dengan akun yang terdaftar. Periksa kembali
                            penulisan email, penggunaan huruf besar dan kecil,
                            serta pastikan tidak terdapat spasi yang tidak
                            diperlukan. Jika password terlupa, gunakan fitur
                            pemulihan password apabila tersedia. Apabila masalah
                            login tetap terjadi meskipun data yang dimasukkan
                            sudah benar, kamu dapat menghubungi tim bantuan
                            untuk mendapatkan pemeriksaan lebih lanjut terhadap
                            akunmu.
                        </div>
                    </div>

                </div>


                {{-- FAQ 3 --}}
                <div class="faq-item rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-sm dark:border-slate-700 dark:bg-slate-800">

                    <button
                        type="button"
                        class="faq-button w-full flex items-center justify-between gap-5 text-left px-6 py-5 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors"
                    >
                        <span class="font-bold text-slate-900 dark:text-slate-100 leading-7">
                            Apakah informasi profil dapat diubah setelah akun dibuat?
                        </span>

                        <span class="faq-icon shrink-0 w-9 h-9 rounded-xl bg-blue-50 dark:bg-slate-700 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                            <i class="fa-solid fa-chevron-down text-sm"></i>
                        </span>
                    </button>

                    <div class="faq-content">
                        <div class="px-6 pb-6 text-slate-600 dark:text-slate-300 leading-8">
                            Informasi profil yang tersedia untuk diubah dapat
                            disesuaikan melalui halaman profil akun. Pastikan
                            informasi yang ditampilkan tetap relevan dan benar,
                            terutama apabila profil digunakan untuk kebutuhan
                            pekerjaan atau komunikasi dengan pengguna lain.
                            Jika terdapat data tertentu yang tidak dapat diubah
                            melalui halaman profil, hubungi tim bantuan untuk
                            mengetahui prosedur yang sesuai.
                        </div>
                    </div>

                </div>

            </div>
        </section>



        {{-- =====================================================
            PROYEK
        ====================================================== --}}
        <section id="proyek" class="mb-16 scroll-mt-28">

            <div class="mb-7">
                <div class="flex items-center gap-3 mb-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 flex items-center justify-center"
                    >
                        <i class="fa-solid fa-briefcase"></i>
                    </div>

                    <h2 class="text-2xl sm:text-3xl font-black text-slate-950 dark:text-slate-100">
                        Proyek
                    </h2>
                </div>

                <p class="text-slate-500 dark:text-slate-300 leading-7">
                    Informasi mengenai pencarian proyek, penawaran,
                    pemilihan freelancer, dan proses pengerjaan.
                </p>
            </div>


            <div class="space-y-4">

                {{-- FAQ 1 --}}
                <div class="faq-item rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-sm dark:border-slate-700 dark:bg-slate-800">

                    <button
                        type="button"
                        class="faq-button w-full flex items-center justify-between gap-5 text-left px-6 py-5 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors"
                    >
                        <span class="font-bold text-slate-900 dark:text-slate-100 leading-7">
                            Bagaimana freelancer dapat menemukan proyek yang tersedia?
                        </span>

                        <span class="faq-icon shrink-0 w-9 h-9 rounded-xl bg-indigo-50 dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                            <i class="fa-solid fa-chevron-down text-sm"></i>
                        </span>
                    </button>

                    <div class="faq-content">
                        <div class="px-6 pb-6 text-slate-600 dark:text-slate-300 leading-8">
                            Freelancer dapat melihat proyek yang tersedia
                            melalui halaman pencarian proyek. Gunakan informasi
                            yang tersedia pada setiap proyek untuk memahami
                            kebutuhan perusahaan, deskripsi pekerjaan,
                            persyaratan, serta informasi lain yang diberikan
                            oleh pemilik proyek. Sebelum mengajukan penawaran,
                            baca deskripsi proyek secara keseluruhan agar
                            penawaran yang diberikan sesuai dengan kebutuhan
                            perusahaan.
                        </div>
                    </div>

                </div>


                {{-- FAQ 2 --}}
                <div class="faq-item rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-sm dark:border-slate-700 dark:bg-slate-800">

                    <button
                        type="button"
                        class="faq-button w-full flex items-center justify-between gap-5 text-left px-6 py-5 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors"
                    >
                        <span class="font-bold text-slate-900 dark:text-slate-100 leading-7">
                            Bagaimana cara freelancer mengajukan penawaran pada sebuah proyek?
                        </span>

                        <span class="faq-icon shrink-0 w-9 h-9 rounded-xl bg-indigo-50 dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                            <i class="fa-solid fa-chevron-down text-sm"></i>
                        </span>
                    </button>

                    <div class="faq-content">
                        <div class="px-6 pb-6 text-slate-600 dark:text-slate-300 leading-8">
                            Pilih proyek yang sesuai dengan kemampuan dan
                            pengalamanmu, kemudian baca seluruh detail pekerjaan
                            sebelum membuat penawaran. Jika proyek tersebut
                            masih terbuka untuk menerima freelancer, ikuti
                            proses pengajuan penawaran yang tersedia pada
                            halaman proyek. Jelaskan kemampuan, pengalaman,
                            pendekatan pengerjaan, serta informasi lain yang
                            relevan secara jelas agar perusahaan dapat memahami
                            alasan mengapa kamu sesuai dengan proyek tersebut.
                        </div>
                    </div>

                </div>


                {{-- FAQ 3 --}}
                <div class="faq-item rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-sm dark:border-slate-700 dark:bg-slate-800">

                    <button
                        type="button"
                        class="faq-button w-full flex items-center justify-between gap-5 text-left px-6 py-5 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors"
                    >
                        <span class="font-bold text-slate-900 dark:text-slate-100 leading-7">
                            Mengapa proyek yang sudah dipilih perusahaan tidak muncul lagi di rekomendasi?
                        </span>

                        <span class="faq-icon shrink-0 w-9 h-9 rounded-xl bg-indigo-50 dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                            <i class="fa-solid fa-chevron-down text-sm"></i>
                        </span>
                    </button>

                    <div class="faq-content">
                        <div class="px-6 pb-6 text-slate-600 dark:text-slate-300 leading-8">
                            Proyek yang sudah masuk ke proses pengerjaan atau
                            sudah dipilih oleh perusahaan tidak seharusnya
                            dianggap sebagai proyek baru yang masih tersedia
                            untuk dicari atau direkomendasikan kepada freelancer.
                            Karena itu, proyek dengan status yang sudah tidak
                            terbuka dapat dikeluarkan dari daftar rekomendasi
                            maupun pencarian proyek agar freelancer hanya melihat
                            peluang pekerjaan yang masih relevan dan dapat
                            diikuti.
                        </div>
                    </div>

                </div>


                {{-- FAQ 4 --}}
                <div class="faq-item rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-sm dark:border-slate-700 dark:bg-slate-800">

                    <button
                        type="button"
                        class="faq-button w-full flex items-center justify-between gap-5 text-left px-6 py-5 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors"
                    >
                        <span class="font-bold text-slate-900 dark:text-slate-100 leading-7">
                            Apa yang terjadi setelah freelancer dipilih untuk mengerjakan proyek?
                        </span>

                        <span class="faq-icon shrink-0 w-9 h-9 rounded-xl bg-indigo-50 dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                            <i class="fa-solid fa-chevron-down text-sm"></i>
                        </span>
                    </button>

                    <div class="faq-content">
                        <div class="px-6 pb-6 text-slate-600 dark:text-slate-300 leading-8">
                            Setelah perusahaan memilih freelancer, proyek
                            memasuki proses berikutnya sesuai dengan mekanisme
                            pengerjaan yang tersedia di platform. Freelancer
                            sebaiknya memperhatikan detail pekerjaan,
                            komunikasi dengan perusahaan, batas waktu,
                            serta informasi pengerjaan lainnya. Proyek yang
                            sudah dipilih atau sedang dikerjakan tidak lagi
                            diperlakukan sebagai proyek terbuka untuk
                            mendapatkan penawaran baru.
                        </div>
                    </div>

                </div>

            </div>
        </section>



        {{-- =====================================================
            PEMBAYARAN
        ====================================================== --}}
        <section id="pembayaran" class="mb-16 scroll-mt-28">

            <div class="mb-7">
                <div class="flex items-center gap-3 mb-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-sky-50 dark:bg-slate-700 text-sky-600 dark:text-sky-400 flex items-center justify-center"
                    >
                        <i class="fa-solid fa-wallet"></i>
                    </div>

                    <h2 class="text-2xl sm:text-3xl font-black text-slate-950 dark:text-slate-100">
                        Pembayaran
                    </h2>
                </div>

                <p class="text-slate-500 dark:text-slate-300 leading-7">
                    Informasi mengenai proses pembayaran dan transaksi proyek.
                </p>
            </div>


            <div class="space-y-4">

                {{-- FAQ 1 --}}
                <div class="faq-item rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-sm dark:border-slate-700 dark:bg-slate-800">

                    <button
                        type="button"
                        class="faq-button w-full flex items-center justify-between gap-5 text-left px-6 py-5 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors"
                    >
                        <span class="font-bold text-slate-900 dark:text-slate-100 leading-7">
                            Bagaimana proses pembayaran dalam sebuah proyek?
                        </span>

                        <span class="faq-icon shrink-0 w-9 h-9 rounded-xl bg-sky-50 dark:bg-slate-700 text-sky-600 dark:text-sky-400 flex items-center justify-center">
                            <i class="fa-solid fa-chevron-down text-sm"></i>
                        </span>
                    </button>

                    <div class="faq-content">
                        <div class="px-6 pb-6 text-slate-600 dark:text-slate-300 leading-8">
                            Proses pembayaran mengikuti alur transaksi yang
                            disediakan oleh platform dan dapat bergantung pada
                            status proyek serta tahap pengerjaan yang sedang
                            berlangsung. Pastikan seluruh informasi pembayaran
                            diperiksa sebelum melakukan transaksi. Simpan
                            informasi transaksi yang tersedia apabila nantinya
                            diperlukan untuk pemeriksaan atau bantuan dari tim
                            platform.
                        </div>
                    </div>

                </div>


                {{-- FAQ 2 --}}
                <div class="faq-item rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-sm dark:border-slate-700 dark:bg-slate-800">

                    <button
                        type="button"
                        class="faq-button w-full flex items-center justify-between gap-5 text-left px-6 py-5 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors"
                    >
                        <span class="font-bold text-slate-900 dark:text-slate-100 leading-7">
                            Apa yang harus dilakukan jika terdapat masalah pada transaksi?
                        </span>

                        <span class="faq-icon shrink-0 w-9 h-9 rounded-xl bg-sky-50 dark:bg-slate-700 text-sky-600 dark:text-sky-400 flex items-center justify-center">
                            <i class="fa-solid fa-chevron-down text-sm"></i>
                        </span>
                    </button>

                    <div class="faq-content">
                        <div class="px-6 pb-6 text-slate-600 dark:text-slate-300 leading-8">
                            Jika terdapat masalah pada transaksi, jangan
                            melakukan tindakan berulang yang dapat menyebabkan
                            transaksi ganda. Periksa terlebih dahulu status
                            transaksi pada akunmu dan simpan informasi atau
                            bukti transaksi yang tersedia. Jika status belum
                            berubah atau terdapat perbedaan antara transaksi
                            dan informasi yang tampil pada platform, hubungi
                            tim bantuan dengan menjelaskan masalah secara
                            lengkap agar dapat diperiksa lebih lanjut.
                        </div>
                    </div>

                </div>


                {{-- FAQ 3 --}}
                <div class="faq-item rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-sm dark:border-slate-700 dark:bg-slate-800">

                    <button
                        type="button"
                        class="faq-button w-full flex items-center justify-between gap-5 text-left px-6 py-5 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors"
                    >
                        <span class="font-bold text-slate-900 dark:text-slate-100 leading-7">
                            Apakah informasi pembayaran perlu disimpan?
                        </span>

                        <span class="faq-icon shrink-0 w-9 h-9 rounded-xl bg-sky-50 dark:bg-slate-700 text-sky-600 dark:text-sky-400 flex items-center justify-center">
                            <i class="fa-solid fa-chevron-down text-sm"></i>
                        </span>
                    </button>

                    <div class="faq-content">
                        <div class="px-6 pb-6 text-slate-600 dark:text-slate-300 leading-8">
                            Sebaiknya simpan informasi transaksi yang tersedia,
                            terutama ketika transaksi berkaitan dengan proyek
                            yang sedang dikerjakan. Informasi tersebut dapat
                            membantu ketika terjadi kendala dan diperlukan
                            pemeriksaan terhadap riwayat pembayaran. Jangan
                            membagikan informasi sensitif seperti password atau
                            kode keamanan akun kepada pihak lain.
                        </div>
                    </div>

                </div>

            </div>
        </section>



        {{-- =====================================================
            KEAMANAN
        ====================================================== --}}
        <section id="keamanan" class="mb-20 scroll-mt-28">

            <div class="mb-7">
                <div class="flex items-center gap-3 mb-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-slate-700 text-emerald-600 dark:text-emerald-400 flex items-center justify-center"
                    >
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>

                    <h2 class="text-2xl sm:text-3xl font-black text-slate-950 dark:text-slate-100">
                        Keamanan
                    </h2>
                </div>

                <p class="text-slate-500 dark:text-slate-300 leading-7">
                    Panduan menjaga keamanan akun dan informasi pribadi.
                </p>
            </div>


            <div class="space-y-4">

                {{-- FAQ 1 --}}
                <div class="faq-item rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-sm dark:border-slate-700 dark:bg-slate-800">

                    <button
                        type="button"
                        class="faq-button w-full flex items-center justify-between gap-5 text-left px-6 py-5 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors"
                    >
                        <span class="font-bold text-slate-900 dark:text-slate-100 leading-7">
                            Bagaimana cara menjaga keamanan akun?
                        </span>

                        <span class="faq-icon shrink-0 w-9 h-9 rounded-xl bg-emerald-50 dark:bg-slate-700 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                            <i class="fa-solid fa-chevron-down text-sm"></i>
                        </span>
                    </button>

                    <div class="faq-content">
                        <div class="px-6 pb-6 text-slate-600 dark:text-slate-300 leading-8">
                            Gunakan password yang kuat dan jangan membagikannya
                            kepada orang lain. Hindari menggunakan password yang
                            sama untuk banyak layanan. Pastikan kamu selalu
                            mengakses platform melalui alamat website yang benar
                            dan jangan memberikan informasi login kepada pihak
                            yang mengaku sebagai tim bantuan tanpa verifikasi.
                            Jika merasa akun tidak aman atau terdapat aktivitas
                            yang tidak dikenali, segera lakukan tindakan
                            pengamanan dan hubungi tim bantuan.
                        </div>
                    </div>

                </div>


                {{-- FAQ 2 --}}
                <div class="faq-item rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-sm dark:border-slate-700 dark:bg-slate-800">

                    <button
                        type="button"
                        class="faq-button w-full flex items-center justify-between gap-5 text-left px-6 py-5 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors"
                    >
                        <span class="font-bold text-slate-900 dark:text-slate-100 leading-7">
                            Apakah saya boleh memberikan password kepada tim bantuan?
                        </span>

                        <span class="faq-icon shrink-0 w-9 h-9 rounded-xl bg-emerald-50 dark:bg-slate-700 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                            <i class="fa-solid fa-chevron-down text-sm"></i>
                        </span>
                    </button>

                    <div class="faq-content">
                        <div class="px-6 pb-6 text-slate-600 dark:text-slate-300 leading-8">
                            Jangan pernah memberikan password akun kepada pihak
                            lain. Password merupakan informasi pribadi yang harus
                            dijaga oleh pemilik akun. Ketika meminta bantuan,
                            cukup jelaskan masalah yang dialami dan berikan
                            informasi yang memang diperlukan untuk proses
                            pemeriksaan. Informasi rahasia seperti password,
                            kode keamanan, atau kredensial login tidak perlu
                            diberikan kepada pihak lain.
                        </div>
                    </div>

                </div>


                {{-- FAQ 3 --}}
                <div class="faq-item rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-sm dark:border-slate-700 dark:bg-slate-800">

                    <button
                        type="button"
                        class="faq-button w-full flex items-center justify-between gap-5 text-left px-6 py-5 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors"
                    >
                        <span class="font-bold text-slate-900 dark:text-slate-100 leading-7">
                            Apa yang harus dilakukan jika menemukan aktivitas akun yang mencurigakan?
                        </span>

                        <span class="faq-icon shrink-0 w-9 h-9 rounded-xl bg-emerald-50 dark:bg-slate-700 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                            <i class="fa-solid fa-chevron-down text-sm"></i>
                        </span>
                    </button>

                    <div class="faq-content">
                        <div class="px-6 pb-6 text-slate-600 dark:text-slate-300 leading-8">
                            Jika menemukan aktivitas yang tidak dikenali,
                            segera periksa akun dan ubah password apabila
                            diperlukan. Jangan berikan informasi login kepada
                            pihak yang tidak dapat diverifikasi. Catat informasi
                            yang berkaitan dengan aktivitas tersebut, seperti
                            waktu atau kejadian yang terlihat pada akun, lalu
                            hubungi tim bantuan agar masalah dapat ditinjau
                            lebih lanjut.
                        </div>
                    </div>

                </div>

            </div>
        </section>



        {{-- =====================================================
            MASIH MEMBUTUHKAN BANTUAN
        ====================================================== --}}
        <section class="mb-20">

            <div
                class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-blue-600 via-blue-600 to-indigo-700 dark:from-blue-800 dark:via-blue-900 dark:to-indigo-950 px-7 py-10 sm:px-12 sm:py-12 text-white shadow-xl shadow-blue-200/40 dark:shadow-none"
            >

                {{-- Decorative --}}
                <div
                    class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-white/10 blur-3xl"
                ></div>

                <div
                    class="absolute -bottom-28 -left-20 w-72 h-72 rounded-full bg-indigo-400/20 blur-3xl"
                ></div>


                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">

                    {{-- TEXT --}}
                    <div class="max-w-2xl">

                        <div
                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/15 border border-white/20 text-sm font-semibold mb-5"
                        >
                            <i class="fa-solid fa-headset"></i>
                            Bantuan Langsung
                        </div>


                        <h2 class="text-3xl sm:text-4xl font-black tracking-tight">
                            Masih membutuhkan bantuan?
                        </h2>


                        <p class="mt-4 text-blue-100 leading-8 text-sm sm:text-base">
                            Jika kamu sudah membaca informasi di Pusat Bantuan
                            tetapi masih belum menemukan jawaban yang sesuai,
                            kamu dapat menghubungi tim kami secara langsung.
                            Jelaskan kendala atau pertanyaanmu dengan lengkap
                            agar kami dapat membantu memberikan informasi yang
                            lebih sesuai dengan kebutuhanmu.
                        </p>

                    </div>


                    {{-- WHATSAPP --}}
                    <div class="shrink-0">

                        {{-- =================================================
                            GANTI NOMOR WHATSAPP DI SINI
                            Contoh:
                            081234567890
                            menjadi:
                            6281234567890
                        ================================================== --}}
                        <a
                            href="https://wa.me/628XXXXXXXXXX"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="w-full lg:w-auto inline-flex items-center justify-center gap-3 px-6 py-4 rounded-2xl bg-white dark:bg-slate-800 dark:text-blue-300 font-extrabold shadow-lg hover:-translate-y-1 hover:shadow-xl transition-all duration-300"
                        >
                            <i class="fa-brands fa-whatsapp text-2xl"></i>

                            <span>
                                Hubungi Kami via WhatsApp
                            </span>
                        </a>


                        <p class="text-xs text-blue-100 text-center mt-3">
                            Kami siap membantu menjawab pertanyaanmu
                        </p>

                    </div>

                </div>

            </div>

        </section>

    </main>



    {{-- =========================================================
        FOOTER
    ========================================================== --}}
    <footer class="bg-slate-950 text-white">

        <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8 py-12">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

                {{-- BRAND --}}
                <div>

                    <div class="flex items-center gap-3 mb-5">

                        <div
                            class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center"
                        >
                            <span class="font-black text-lg">
                                A
                            </span>
                        </div>

                        <div>
                            <h3 class="font-black text-lg">
                                ApexForge Labs
                            </h3>

                            <p class="text-xs text-slate-400">
                                Pusat Bantuan
                            </p>
                        </div>

                    </div>


                    <p class="text-sm text-slate-400 leading-7 max-w-md">
                        Pusat Bantuan ApexForge Labs menyediakan informasi dan
                        panduan untuk membantu pengguna memahami berbagai fitur
                        serta proses yang tersedia di platform.
                    </p>

                </div>


                {{-- NAVIGASI --}}
                <div>

                    <h3 class="font-bold text-white mb-5">
                        Navigasi
                    </h3>

                    <div class="space-y-3 text-sm">

                        <a
                            href="{{ url('/') }}"
                            class="block text-slate-400 hover:text-white transition-colors"
                        >
                            Beranda
                        </a>

                        <a
                            href="#akun"
                            class="block text-slate-400 hover:text-white transition-colors"
                        >
                            Akun
                        </a>

                        <a
                            href="#proyek"
                            class="block text-slate-400 hover:text-white transition-colors"
                        >
                            Proyek
                        </a>

                        <a
                            href="#pembayaran"
                            class="block text-slate-400 hover:text-white transition-colors"
                        >
                            Pembayaran
                        </a>

                        <a
                            href="#keamanan"
                            class="block text-slate-400 hover:text-white transition-colors"
                        >
                            Keamanan
                        </a>

                    </div>

                </div>


                {{-- BANTUAN --}}
                <div>

                    <h3 class="font-bold text-white mb-5">
                        Butuh Bantuan?
                    </h3>

                    <p class="text-sm text-slate-400 leading-7 mb-5">
                        Belum menemukan jawaban yang kamu cari?
                        Hubungi tim kami untuk mendapatkan bantuan lebih lanjut.
                    </p>


                    {{-- GANTI NOMOR WHATSAPP --}}
                    <a
                        href="https://wa.me/628XXXXXXXXXX"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 text-sm font-bold text-white hover:text-blue-400 dark:hover:text-blue-300 transition-colors"
                    >
                        <i class="fa-brands fa-whatsapp text-lg"></i>
                        Hubungi via WhatsApp
                    </a>

                </div>

            </div>


            {{-- COPYRIGHT --}}
            <div
                class="mt-10 pt-7 border-t border-white/10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3"
            >

                <p class="text-xs text-slate-500 dark:text-slate-400">
                    © {{ date('Y') }} ApexForge Labs. All rights reserved.
                </p>

                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Pusat Bantuan
                </p>

            </div>

        </div>

    </footer>



    {{-- =========================================================
        FAQ JAVASCRIPT
    ========================================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const faqButtons = document.querySelectorAll('.faq-button');

            faqButtons.forEach(function (button) {

                button.addEventListener('click', function () {

                    const currentItem = this.closest('.faq-item');

                    // Tutup FAQ lain
                    document.querySelectorAll('.faq-item.active').forEach(function (item) {

                        if (item !== currentItem) {
                            item.classList.remove('active');
                        }

                    });

                    // Toggle FAQ yang diklik
                    currentItem.classList.toggle('active');

                });

            });

        });
    </script>

</body>
</html>