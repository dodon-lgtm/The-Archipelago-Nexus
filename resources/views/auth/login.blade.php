<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Masuk ke Akun - The Archipelago Nexus</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Font -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex items-center justify-center p-4 md:p-6">

    <!-- Container Utama -->
    <div class="max-w-6xl w-full bg-white rounded-3xl shadow-2xl shadow-slate-200/80 border border-slate-100 overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[640px]">

        <!-- ================= SISI KIRI ================= -->
        <div class="lg:col-span-7 p-8 md:p-12 flex flex-col justify-between space-y-8">

            <!-- Header Brand -->
            <div class="flex items-center gap-3">

                <div class="w-10 h-10 bg-slate-900 text-white rounded-xl flex items-center justify-center font-bold text-sm shadow-md shadow-slate-900/20">
                    <i class="fa-solid fa-layer-group"></i>
                </div>

                <span class="font-extrabold text-lg tracking-tight text-slate-900">
                    The Archipelago Nexus
                </span>

            </div>


            <!-- Teks Utama -->
            <div class="space-y-4">

                <h1 class="text-3xl md:text-4xl font-black text-slate-900 leading-tight">
                    Temukan Bakat Terampil
                    <br>
                    atau Proyek Impian Anda
                    <br>
                    di
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">
                        The Archipelago Nexus
                    </span>
                </h1>

                <p class="text-sm text-slate-500 max-w-md leading-relaxed">
                    Hubungkan dengan freelancer terbaik dan wujudkan proyek Anda
                    dengan mudah, cepat, dan aman.
                </p>

            </div>


            <!-- Fitur -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                <!-- Fitur 1 -->
                <div class="bg-slate-50 border border-slate-100 p-4 rounded-xl flex items-start gap-3">

                    <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center text-xs shrink-0">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>

                    <div>
                        <h4 class="text-xs font-bold text-slate-900">
                            Freelancer Terpercaya
                        </h4>

                        <p class="text-[10px] text-slate-400 mt-0.5">
                            Banyak freelancer berkualitas.
                        </p>
                    </div>

                </div>


                <!-- Fitur 2 -->
                <div class="bg-slate-50 border border-slate-100 p-4 rounded-xl flex items-start gap-3">

                    <div class="w-8 h-8 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center text-xs shrink-0">
                        <i class="fa-solid fa-wallet"></i>
                    </div>

                    <div>
                        <h4 class="text-xs font-bold text-slate-900">
                            Proses Aman
                        </h4>

                        <p class="text-[10px] text-slate-400 mt-0.5">
                            Transaksi aman dan terjamin.
                        </p>
                    </div>

                </div>


                <!-- Fitur 3 -->
                <div class="bg-slate-50 border border-slate-100 p-4 rounded-xl flex items-start gap-3">

                    <div class="w-8 h-8 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center text-xs shrink-0">
                        <i class="fa-solid fa-business-time"></i>
                    </div>

                    <div>
                        <h4 class="text-xs font-bold text-slate-900">
                            Proyek Tepat Waktu
                        </h4>

                        <p class="text-[10px] text-slate-400 mt-0.5">
                            Selesai sesuai deadline.
                        </p>
                    </div>

                </div>

            </div>


            <!-- Gambar -->
            <div class="w-full h-48 bg-gradient-to-br from-blue-50 to-indigo-50/50 rounded-2xl border border-blue-100/30 overflow-hidden flex items-center justify-center p-2 relative group">

                <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-blue-200/30 rounded-full blur-xl"></div>

                <img
                    src="{{ asset('images/beranda.png') }}"
                    alt="Ilustrasi Kerja"
                    class="max-h-full max-w-full object-contain rounded-xl transition duration-500 group-hover:scale-105"
                >

            </div>

        </div>


        <!-- ================= SISI KANAN ================= -->
        <div class="lg:col-span-5 bg-gradient-to-b from-slate-900 via-slate-800 to-slate-950 p-8 md:p-12 flex flex-col justify-between text-white relative overflow-hidden">

            <!-- Dekorasi -->
            <div class="absolute -top-20 -right-20 w-44 h-44 bg-blue-500/10 rounded-full blur-3xl"></div>

            <div class="absolute -bottom-20 -left-20 w-44 h-44 bg-indigo-500/10 rounded-full blur-3xl"></div>


            <!-- Logo dan Judul -->
            <div class="text-center space-y-3 relative z-10 mt-4">

                <div class="w-16 h-16 bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl mx-auto flex items-center justify-center shadow-inner overflow-hidden">

                    <div class="w-10 h-10 bg-gradient-to-br from-slate-800 to-black rounded-xl shadow-md flex items-center justify-center">

                        <i class="fa-solid fa-fingerprint text-white text-base"></i>

                    </div>

                </div>

                <div>

                    <h2 class="font-extrabold text-lg tracking-wide text-white">
                        The Archipelago<span class="text-blue-400">Nexus</span>
                    </h2>

                    <p class="text-xs text-slate-400 font-medium mt-1">
                        Masuk ke akun untuk melanjutkan
                    </p>

                </div>

            </div>


            <!-- ================= FORM LOGIN ================= -->

            <form
                action="{{ route('login') }}"
                method="POST"
                class="space-y-4 my-auto py-6 relative z-10"
            >

                @csrf


                <!-- Pesan Success -->
                @if (session('success'))

                    <div class="p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 text-xs">
                        <i class="fa-solid fa-circle-check mr-1"></i>
                        {{ session('success') }}
                    </div>

                @endif


                <!-- Pesan Error Umum -->
                @if ($errors->any())

                    <div class="p-3 rounded-xl bg-red-500/10 border border-red-500/20 text-red-300 text-xs">

                        <i class="fa-solid fa-circle-exclamation mr-1"></i>

                        Email atau password yang dimasukkan tidak sesuai.

                    </div>

                @endif


                <!-- EMAIL -->
                <div class="space-y-1.5">

                    <label
                        for="email"
                        class="text-[11px] font-bold tracking-wider text-slate-400 uppercase block"
                    >
                        Email
                    </label>

                    <div class="relative">

                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-500 text-xs">
                            <i class="fa-regular fa-envelope"></i>
                        </span>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Masukkan email Anda"
                            autocomplete="email"
                            autofocus
                            required
                            class="w-full text-xs pl-10 pr-4 py-3 bg-slate-800/80 border
                            {{ $errors->has('email') ? 'border-red-500' : 'border-slate-700/60' }}
                            rounded-xl text-white placeholder-slate-500
                            focus:bg-slate-800 focus:border-blue-500
                            focus:ring-1 focus:ring-blue-500 outline-none transition"
                        >

                    </div>

                    @error('email')

                        <p class="text-[10px] text-red-400 mt-1">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                <!-- PASSWORD -->
                <div class="space-y-1.5">

                    <div class="flex justify-between items-center">

                        <label
                            for="password"
                            class="text-[11px] font-bold tracking-wider text-slate-400 uppercase block"
                        >
                            Password
                        </label>

                        <a
                            href="#"
                            class="text-[10px] text-blue-400 hover:underline"
                        >
                            Lupa?
                        </a>

                    </div>


                    <div class="relative">

                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-500 text-xs">
                            <i class="fa-solid fa-lock"></i>
                        </span>

                        <input
                            id="password"
                            type="password"
                            name="password"
                            placeholder="Masukkan password Anda"
                            autocomplete="current-password"
                            required
                            class="w-full text-xs pl-10 pr-4 py-3 bg-slate-800/80 border
                            {{ $errors->has('password') ? 'border-red-500' : 'border-slate-700/60' }}
                            rounded-xl text-white placeholder-slate-500
                            focus:bg-slate-800 focus:border-blue-500
                            focus:ring-1 focus:ring-blue-500 outline-none transition"
                        >

                    </div>

                    @error('password')

                        <p class="text-[10px] text-red-400 mt-1">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                <!-- TOMBOL LOGIN -->
                <button
                    type="submit"
                    class="w-full py-3 mt-2
                    bg-gradient-to-r from-blue-600 to-indigo-600
                    hover:from-blue-700 hover:to-indigo-700
                    text-white text-xs font-bold rounded-xl
                    shadow-lg shadow-blue-600/20
                    transition transform active:scale-[0.98]"
                >

                    <i class="fa-solid fa-right-to-bracket mr-2"></i>

                    Masuk

                </button>


                <!-- OR -->
                <div class="relative flex py-2 items-center">

                    <div class="flex-grow border-t border-slate-700/50"></div>

                    <span class="flex-shrink mx-3 text-[10px] text-slate-500 uppercase tracking-widest font-medium">
                        atau masuk dengan
                    </span>

                    <div class="flex-grow border-t border-slate-700/50"></div>

                </div>


                <!-- GOOGLE -->
                <a
                    href="#"
                    class="w-full py-2.5 bg-white hover:bg-slate-100
                    text-slate-900 text-xs font-bold rounded-xl
                    flex items-center justify-center gap-2 transition shadow-sm"
                >

                    <svg class="w-4 h-4" viewBox="0 0 24 24">

                        <path fill="#EA4335" d="M5.266 9.765A7.077 7.077 0 0 1 12 4.909c1.69 0 3.218.6 4.418 1.582l3.51-3.51C17.642 1.09 14.974 0 12 0 7.354 0 3.307 2.673 1.295 6.57l3.971 3.195z"/>

                        <path fill="#4285F4" d="M23.49 12.275c0-.818-.073-1.609-.21-2.373H12v4.51h6.44c-.277 1.463-1.096 2.704-2.33 3.533l3.63 2.815c2.123-1.955 3.35-4.832 3.35-8.485z"/>

                        <path fill="#FBBC05" d="M5.266 14.235L1.295 17.43A11.96 11.96 0 0 0 12 24c3.045 0 5.89-.964 8.11-2.618l-3.63-2.815c-1.214.814-2.768 1.309-4.48 1.309-3.455 0-6.382-2.336-7.423-5.472l-3.971 3.196z"/>

                        <path fill="#34A853" d="M12 19.091c-1.714 0-3.268-.495-4.48-1.31l-3.631 2.816A11.966 11.966 0 0 0 12 24c4.646 0 8.693-2.673 10.705-6.57l-3.97-3.195c-1.042 3.136-3.969 5.472-7.424 5.472z"/>

                    </svg>

                    Google

                </a>

            </form>


            <!-- REGISTER -->
            <div class="text-center text-xs text-slate-400 pt-4 relative z-10">

                Belum punya akun?

                <a
                    href="{{ route('register') }}"
                    class="text-blue-400 font-bold hover:underline ml-1"
                >
                    Daftar di sini
                </a>

            </div>

        </div>

    </div>

</body>
</html>