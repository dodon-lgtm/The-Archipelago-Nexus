<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Masuk ke Akun - The Archipelago Nexus</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Font -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-100/80 text-slate-800 antialiased min-h-screen flex items-center justify-center p-3 md:p-6">

    <!-- Container Utama -->
    <div class="max-w-6xl w-full bg-white rounded-3xl shadow-2xl shadow-slate-200/80 border border-slate-100 overflow-hidden grid grid-cols-1 lg:grid-cols-12">

        <!-- ================= SISI KIRI ================= -->
        <div class="lg:col-span-7 p-6 md:p-10 flex flex-col justify-between space-y-6 relative overflow-hidden bg-white">

            <!-- Background Gedung dengan Gradient Fade Overlay -->
            <div class="absolute inset-0 z-0 pointer-events-none">
                <img src="{{ asset('images/gedung.jpg') }}" alt="Background Gedung" class="w-full h-full object-cover opacity-70">
                <div class="absolute inset-0 bg-gradient-to-tr from-white via-white/80 to-transparent"></div>
            </div>

            <!-- Konten Sisi Kiri -->
            <div class="relative z-10 flex flex-col justify-between space-y-6 h-full">

                <!-- Header Brand -->
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-slate-900 text-white rounded-xl flex items-center justify-center font-bold text-xs shadow-md shadow-slate-900/20 overflow-hidden ring-2 ring-slate-900/10">
                        <img src="{{ asset('images/nexus.jpg') }}" alt="Logo Nexus" class="w-7 h-7 rounded-full object-cover">
                    </div>
                    <span class="font-extrabold text-base tracking-tight text-slate-900">
                        The Archipelago Nexus
                    </span>
                </div>

                <!-- Teks Utama -->
                <div class="space-y-3.5 my-auto">
                    <!-- Mini Badge Aksen -->
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 border border-blue-100 text-blue-600 text-[11px] font-bold">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                        Platform Talenta Nusantara
                    </div>

                    <h1 class="text-2xl md:text-3xl font-black text-slate-900 leading-tight">
                        Temukan Bakat Terampil 
                        <br>
                        atau Proyek Impian Anda 
                        <br>
                        di 
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">
                            The Archipelago Nexus
                        </span>
                    </h1>

                    <p class="text-xs md:text-sm text-slate-600 max-w-md leading-relaxed font-medium">
                        Hubungkan dengan freelancer terbaik dan wujudkan proyek Anda dengan mudah, cepat, dan aman.
                    </p>
                </div>

                <!-- Fitur (Glassmorphism Styling) -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

                    <!-- Fitur 1 -->
                    <div class="bg-white/70 backdrop-blur-md border border-slate-200/60 p-3 rounded-2xl flex items-start gap-2.5 shadow-sm hover:border-blue-200 transition-colors">
                        <div class="w-7 h-7 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center text-xs shrink-0">
                            <i class="fa-solid fa-user-shield"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900">Freelancer Terpercaya</h4>
                            <p class="text-[10px] text-slate-500 mt-0.5 leading-tight">Banyak freelancer berkualitas.</p>
                        </div>
                    </div>

                    <!-- Fitur 2 -->
                    <div class="bg-white/70 backdrop-blur-md border border-slate-200/60 p-3 rounded-2xl flex items-start gap-2.5 shadow-sm hover:border-emerald-200 transition-colors">
                        <div class="w-7 h-7 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center text-xs shrink-0">
                            <i class="fa-solid fa-wallet"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900">Proses Aman</h4>
                            <p class="text-[10px] text-slate-500 mt-0.5 leading-tight">Transaksi aman & terjamin.</p>
                        </div>
                    </div>

                    <!-- Fitur 3 -->
                    <div class="bg-white/70 backdrop-blur-md border border-slate-200/60 p-3 rounded-2xl flex items-start gap-2.5 shadow-sm hover:border-amber-200 transition-colors">
                        <div class="w-7 h-7 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center text-xs shrink-0">
                            <i class="fa-solid fa-business-time"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900">Proyek Tepat Waktu</h4>
                            <p class="text-[10px] text-slate-500 mt-0.5 leading-tight">Selesai sesuai deadline.</p>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <!-- ================= SISI KANAN ================= -->
        <div class="lg:col-span-5 bg-gradient-to-b from-slate-900 via-slate-800 to-slate-950 p-6 md:p-10 flex flex-col justify-between text-white relative overflow-hidden">

            <!-- Dekorasi Light Effect -->
            <div class="absolute -top-20 -right-20 w-48 h-48 bg-blue-500/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -left-20 w-48 h-48 bg-indigo-500/15 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Logo dan Judul -->
            <div class="text-center space-y-2 relative z-10">
                <div class="w-14 h-14 bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl mx-auto flex items-center justify-center shadow-inner overflow-hidden">
                    <div class="w-9 h-9 bg-gradient-to-br from-slate-800 to-black rounded-xl shadow-md flex items-center justify-center overflow-hidden ring-1 ring-white/10">
                        <img src="{{ asset('images/nexus.jpg') }}" alt="Logo Nexus" class="w-6 h-6 rounded-full object-cover">
                    </div>
                </div>

                <div>
                    <h2 class="font-extrabold text-base tracking-wide text-white">
                        The Archipelago<span class="text-blue-400">Nexus</span>
                    </h2>
                    <p class="text-[11px] text-slate-400 font-medium mt-0.5">
                        Masuk ke akun untuk melanjutkan
                    </p>
                </div>
            </div>

            <!-- FORM LOGIN -->
            <form action="{{ route('login') }}" method="POST" class="space-y-3.5 my-auto py-4 relative z-10">
                @csrf

                <!-- Pesan Success -->
                @if (session('success'))
                    <div class="p-2.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 text-xs flex items-center gap-2">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Pesan Error Umum -->
                @if ($errors->any())
                    <div class="p-2.5 rounded-xl bg-red-500/10 border border-red-500/20 text-red-300 text-xs flex items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>Email atau password yang dimasukkan tidak sesuai.</span>
                    </div>
                @endif

                <!-- EMAIL -->
                <div class="space-y-1">
                    <label for="email" class="text-[10px] font-bold tracking-wider text-slate-400 uppercase block">
                        Email
                    </label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-500 text-xs group-focus-within:text-blue-400 transition-colors">
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
                            class="w-full text-xs pl-10 pr-4 py-2.5 bg-slate-800/80 border {{ $errors->has('email') ? 'border-red-500' : 'border-slate-700/60' }} rounded-xl text-white placeholder-slate-500 focus:bg-slate-800 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition shadow-inner"
                        >
                    </div>
                    @error('email')
                        <p class="text-[10px] text-red-400 mt-0.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- PASSWORD -->
                <div class="space-y-1">
                    <div class="flex justify-between items-center">
                        <label for="password" class="text-[10px] font-bold tracking-wider text-slate-400 uppercase block">
                            Password
                        </label>
                        <a href="#" class="text-[10px] text-blue-400 hover:text-blue-300 hover:underline transition-colors">
                            Lupa?
                        </a>
                    </div>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-500 text-xs group-focus-within:text-blue-400 transition-colors">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            placeholder="Masukkan password Anda" 
                            autocomplete="current-password" 
                            required 
                            class="w-full text-xs pl-10 pr-10 py-2.5 bg-slate-800/80 border {{ $errors->has('password') ? 'border-red-500' : 'border-slate-700/60' }} rounded-xl text-white placeholder-slate-500 focus:bg-slate-800 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition shadow-inner"
                        >
                    </div>
                    @error('password')
                        <p class="text-[10px] text-red-400 mt-0.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- REMEMBER ME -->
                <div class="flex items-center gap-2 pt-0.5">
                    <input 
                        id="remember" 
                        type="checkbox" 
                        name="remember" 
                        value="1" 
                        {{ old('remember') ? 'checked' : '' }} 
                        class="w-4 h-4 rounded border-slate-600 bg-slate-800 text-blue-600 focus:ring-blue-500 focus:ring-offset-0 cursor-pointer"
                    >
                    <label for="remember" class="text-xs text-slate-400 cursor-pointer select-none hover:text-slate-300 transition-colors">
                        Ingat Saya
                    </label>
                </div>

                <!-- TOMBOL LOGIN -->
                <button type="submit" class="w-full py-2.5 mt-1 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white text-xs font-bold rounded-xl shadow-lg shadow-blue-600/20 transition transform active:scale-[0.98] flex items-center justify-center gap-2 group">
                    <i class="fa-solid fa-right-to-bracket group-hover:translate-x-0.5 transition-transform"></i>
                    Masuk
                </button>

                <!-- OR DIVIDER -->
                <div class="relative flex py-1.5 items-center">
                    <div class="flex-grow border-t border-slate-700/60"></div>
                    <span class="flex-shrink mx-3 text-[10px] text-slate-500 uppercase tracking-widest font-semibold">
                        atau masuk dengan
                    </span>
                    <div class="flex-grow border-t border-slate-700/60"></div>
                </div>

                <!-- GOOGLE -->
                <a href="#" class="w-full py-2.5 bg-white hover:bg-slate-100 text-slate-900 text-xs font-bold rounded-xl flex items-center justify-center gap-2 transition shadow-sm">
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
            <div class="text-center text-xs text-slate-400 pt-2 relative z-10">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-blue-400 font-bold hover:text-blue-300 hover:underline ml-1 transition-colors">
                    Daftar di sini
                </a>
            </div>

        </div>

    </div>

</body>
</html>