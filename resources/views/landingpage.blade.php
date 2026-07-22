<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Archipelago Nexus </title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome untuk Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

    <!-- NAVBAR -->
    <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <!-- Brand Logo -->
            <div class="flex items-center gap-3">
               <div class="w-10 h-10 rounded-full overflow-hidden flex items-center justify-center">
   <img src="{{ asset('images/snexus.jpg') }}" alt="Logo" class="h-10 w-10 object-cover rounded-full">
</div>
                <span class="font-bold text-lg tracking-tight text-slate-900">The Archipelago Nexus</span>
            </div>
            
            <!-- Auth Buttons -->
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="px-5 py-2 text-sm font-semibold text-slate-700 hover:text-slate-950 border border-gray-300 rounded-lg hover:bg-gray-50 transition">LOGIN</a>
                <a href="{{ route('register') }}" class="px-5 py-2 text-sm font-semibold text-white bg-slate-900 hover:bg-slate-800 rounded-lg transition shadow-sm">REGISTER</a>
            </div>
        </div>
    </header>

    <!-- HERO SECTION -->
    <section class="max-w-7xl mx-auto px-6 py-12 md:py-20 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <!-- Hero Text -->
        <div class="space-y-6">
            <h1 class="text-3xl md:text-5xl font-extrabold text-slate-900 leading-tight">
                Temukan Bakat Terampil <br>
                atau Proyek Impian Anda <br>
                di <span class="text-teal-600">The Archipelago Nexus</span>
            </h1>
            <p class="text-base text-slate-600 max-w-md leading-relaxed">
                Hubungkan dengan freelancer terbaik dan wujudkan proyek Anda dengan mudah dan aman.
            </p>
            <div>
                <a href="{{ route('login') }}" class="inline-block px-8 py-3.5 bg-slate-900 hover:bg-slate-800 text-white font-medium rounded-lg shadow transition transform hover:-translate-y-0.5">
                    Mulai sekarang
                </a>
            </div>
            
            <!-- Slider Dots -->
            <div class="flex gap-2 pt-4">
                <span class="w-6 h-2 bg-slate-800 rounded-full"></span>
                <span class="w-2 h-2 bg-slate-300 rounded-full"></span>
                <span class="w-2 h-2 bg-slate-300 rounded-full"></span>
                <span class="w-2 h-2 bg-slate-300 rounded-full"></span>
            </div>
        </div>

        <!-- Hero Graphics Vector / Mockup Bergaya Premium Baru -->
        <div class="relative flex justify-center items-center h-[360px] md:h-[400px]">
            <!-- Lingkaran Besar Gradasi Warna di Belakang (Biru Kehijauan) -->
            <div class="absolute right-4 w-[280px] h-[280px] md:w-[360px] md:h-[360px] bg-gradient-to-br from-blue-500 via-teal-400 to-emerald-400 rounded-full -z-10 opacity-90 shadow-2xl"></div>
            
            <!-- Main Mockup Card (Frame Browser Putih) -->
            <div class="bg-white border border-slate-200/60 rounded-2xl shadow-2xl p-4 w-full max-w-[280px] md:max-w-[320px] h-[280px] md:h-[320px] relative overflow-hidden flex flex-col justify-between">
                <!-- Mac-style top dots -->
                <div class="flex gap-1.5 mb-2">
                    <span class="w-2 h-2 bg-red-400 rounded-full"></span>
                    <span class="w-2 h-2 bg-yellow-400 rounded-full"></span>
                    <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                </div>
                
                <!-- TEMPAT PEMANGGILAN FOTO KAMU -->
                <div class="w-full h-full bg-gradient-to-t from-blue-50 to-transparent rounded-xl overflow-hidden flex items-end justify-center relative">
                    <img src="{{ asset('images/beranda.png') }}" 
                         alt="Freelancer" 
                         class="w-full h-full object-cover object-center relative z-10" />
                </div>
                
                <!-- Floating Mini Card Kiri (Username) -->
                <div class="absolute bottom-12 -left-8 bg-white/95 backdrop-blur border border-slate-100 rounded-lg shadow-lg py-1.5 px-2.5 flex items-center gap-2 z-20">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                    <span class="text-[9px] font-bold text-slate-700 font-mono">@orremiana</span>
                </div>

                <!-- Floating Mini Card Kanan (Detail Status Ulasan) -->
                <div class="absolute top-12 -right-6 bg-white border border-slate-100 rounded-lg shadow-lg p-2 w-24 z-20">
                    <p class="text-[8px] text-gray-400 font-medium">Rating</p>
                    <div class="w-12 h-1.5 bg-blue-100 rounded-full my-1 overflow-hidden">
                        <div class="w-4/5 h-full bg-teal-500 rounded-full"></div>
                    </div>
                    <p class="text-[8px] font-bold text-slate-700">For Teams</p>
                </div>
            </div>

            <!-- Floating Check Badge Right -->
            <div class="absolute top-1/2 -right-6 w-12 h-12 bg-white border border-gray-100 rounded-full shadow-lg flex items-center justify-center text-emerald-500 text-xl z-20">
                <i class="fa-solid fa-circle-check"></i>
            </div>

            <!-- Emoji Floating Smiley Kiri -->
            <div class="absolute top-24 left-4 bg-white w-8 h-8 rounded-full shadow-md flex items-center justify-center text-sm z-20">
                😍
            </div>
        </div>
    </section>

    <!-- KATEGORI JASA POPULER -->
    <section class="max-w-7xl mx-auto px-6 py-10">
        <h2 class="text-xs font-bold tracking-wider text-slate-400 uppercase mb-4">KATEGORI JASA POPULER</h2>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
            <a href="{{ route('login') }}" class="bg-white border border-gray-200 rounded-xl p-4 flex items-center justify-between shadow-sm hover:border-teal-500 transition group">
                <span class="text-xs font-bold text-slate-800 group-hover:text-teal-600">Web Dev ></span>
                <i class="fa-solid fa-code text-slate-400 group-hover:text-teal-500 text-sm"></i>
            </a>
            <a href="{{ route('login') }}" class="bg-white border border-gray-200 rounded-xl p-4 flex items-center justify-between shadow-sm hover:border-teal-500 transition group">
                <span class="text-xs font-bold text-slate-800 group-hover:text-teal-600">Desain Grafis ></span>
                <i class="fa-solid fa-palette text-slate-400 group-hover:text-teal-500 text-sm"></i>
            </a>
            <a href="{{ route('login') }}" class="bg-white border border-gray-200 rounded-xl p-4 flex items-center justify-between shadow-sm hover:border-teal-500 transition group">
                <span class="text-xs font-bold text-slate-800 group-hover:text-teal-600">Penulisan ></span>
                <i class="fa-solid fa-pen-nib text-slate-400 group-hover:text-teal-500 text-sm"></i>
            </a>
            <a href="{{ route('login') }}" class="bg-white border border-gray-200 rounded-xl p-4 flex items-center justify-between shadow-sm hover:border-teal-500 transition group">
                <span class="text-xs font-bold text-slate-800 group-hover:text-teal-600">Pemasaran Digital ></span>
                <i class="fa-solid fa-bullhorn text-slate-400 group-hover:text-teal-500 text-sm"></i>
            </a>
            <a href="{{ route('login') }}" class="bg-white border border-gray-200 rounded-xl p-4 flex items-center justify-between shadow-sm hover:border-teal-500 transition group col-span-2 md:col-span-1">
                <span class="text-xs font-bold text-slate-800 group-hover:text-teal-600">Video & Animasi ></span>
                <i class="fa-solid fa-video text-slate-400 group-hover:text-teal-500 text-sm"></i>
            </a>
        </div>
    </section>

    <!-- PROYEK TERBARU -->
    <section class="max-w-7xl mx-auto px-6 py-10">
        <h2 class="text-xs font-bold tracking-wider text-slate-400 uppercase mb-4">PROYEK TERBARU</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            
            <!-- Card 1 -->
            <a href="{{ route('login') }}" class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm flex flex-col justify-between hover:shadow-md transition block">
                <div>
                    <span class="text-[9px] bg-emerald-50 text-emerald-600 border border-emerald-100 px-2 py-0.5 rounded font-semibold">Web Development</span>
                    <h3 class="text-xs font-bold text-slate-800 mt-2 line-clamp-1">Pembuatan Website Company Profile</h3>
                    <p class="text-[10px] text-gray-400 mt-0.5">PT Maju Bersama</p>
                    <p class="text-xs font-black text-slate-900 mt-3">Rp5.000.000 - Rp10.000.000</p>
                </div>
                <div class="text-[9px] text-slate-400 mt-4 pt-2 border-t border-gray-100 text-right">
                    2 jam yang lalu
                </div>
            </a>

            <!-- Card 2 -->
            <a href="{{ route('login') }}" class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm flex flex-col justify-between hover:shadow-md transition block">
                <div>
                    <span class="text-[9px] bg-purple-50 text-purple-600 border border-purple-100 px-2 py-0.5 rounded font-semibold">Desain Grafis</span>
                    <h3 class="text-xs font-bold text-slate-800 mt-2 line-clamp-1">Desain Logo untuk Brand Fashion</h3>
                    <p class="text-[10px] text-gray-400 mt-0.5">Retro Fashion</p>
                    <p class="text-xs font-black text-slate-900 mt-3">Rp 500.000 - Rp 1.500.000</p>
                </div>
                <div class="text-[9px] text-slate-400 mt-4 pt-2 border-t border-gray-100 text-right">
                    4 jam yang lalu
                </div>
            </a>

            <!-- Card 3 -->
            <a href="{{ route('login') }}" class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm flex flex-col justify-between hover:shadow-md transition block">
                <div>
                    <span class="text-[9px] bg-blue-50 text-blue-600 border border-blue-100 px-2 py-0.5 rounded font-semibold">Penulisan</span>
                    <h3 class="text-xs font-bold text-slate-800 mt-2 line-clamp-1">Penulisan Artikel SEO 1000 Kata</h3>
                    <p class="text-[10px] text-gray-400 mt-0.5">Digital Marketing ID</p>
                    <p class="text-xs font-black text-slate-900 mt-3">Rp 300.000 - Rp 600.000</p>
                </div>
                <div class="text-[9px] text-slate-400 mt-4 pt-2 border-t border-gray-100 text-right">
                    6 jam yang lalu
                </div>
            </a>

            <!-- Card 4 -->
            <a href="{{ route('login') }}" class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm flex flex-col justify-between hover:shadow-md transition block">
                <div>
                    <span class="text-[9px] bg-amber-50 text-amber-600 border border-amber-100 px-2 py-0.5 rounded font-semibold">Pemasaran Digital</span>
                    <h3 class="text-xs font-bold text-slate-800 mt-2 line-clamp-1">Kelola Iklan Facebook & Instagram</h3>
                    <p class="text-[10px] text-gray-400 mt-0.5">Toko Online Kita</p>
                    <p class="text-xs font-black text-slate-900 mt-3">Rp 1.000.000 - Rp 2.500.000</p>
                </div>
                <div class="text-[9px] text-slate-400 mt-4 pt-2 border-t border-gray-100 text-right">
                    8 jam yang lalu
                </div>
            </a>

        </div>
    </section>

    <!-- FEATURES STRIP (Bawah) -->
    <section class="bg-slate-900 text-white mt-12">
        <div class="max-w-7xl mx-auto px-6 py-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 items-center">
            
            <!-- Fitur 1 -->
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-slate-800 rounded-xl flex items-center justify-center text-teal-400 shrink-0 border border-slate-700">
                    <i class="fa-solid fa-shield-halved text-xl"></i>
                </div>
                <div>
                    <h4 class="text-xs font-bold">Aman & Terpercaya</h4>
                    <p class="text-[10px] text-slate-400 mt-0.5">Sistem pembayaran aman dan freelancer terverifikasi.</p>
                </div>
            </div>

            <!-- Fitur 2 -->
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-slate-800 rounded-xl flex items-center justify-center text-teal-400 shrink-0 border border-slate-700">
                    <i class="fa-solid fa-users text-xl"></i>
                </div>
                <div>
                    <h4 class="text-xs font-bold">Banyak Pilihan</h4>
                    <p class="text-[10px] text-slate-400 mt-0.5">Temukan freelancer sesuai kebutuhan Anda.</p>
                </div>
            </div>

            <!-- Fitur 3 -->
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-slate-800 rounded-xl flex items-center justify-center text-teal-400 shrink-0 border border-slate-700">
                    <i class="fa-solid fa-bolt text-xl"></i>
                </div>
                <div>
                    <h4 class="text-xs font-bold">Pengerjaan Cepat</h4>
                    <p class="text-[10px] text-slate-400 mt-0.5">Proyek diselesaikan tepat waktu dan berkualitas.</p>
                </div>
            </div>

            <!-- Fitur 4 -->
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-slate-800 rounded-xl flex items-center justify-center text-teal-400 shrink-0 border border-slate-700">
                    <i class="fa-solid fa-headset text-xl"></i>
                </div>
                <div>
                    <h4 class="text-xs font-bold">Dukungan 24/7</h4>
                    <p class="text-[10px] text-slate-400 mt-0.5">Tim kami siap membantu kapan pun Anda butuhkan.</p>
                </div>
            </div>
            
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-slate-200 mt-12">
        <div class="max-w-7xl mx-auto px-6 py-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-left">
                
                <!-- Kolom 1: Tentang -->
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-slate-900 rounded-full flex items-center justify-center text-white font-bold text-xs">AN</div>
                        <span class="font-bold text-sm tracking-tight text-slate-900">The Archipelago Nexus</span>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed max-w-sm">
                        Platform marketplace freelance terpercaya untuk menghubungkan talenta berbakat Nusantara dengan berbagai proyek industri kreatif dan teknologi secara aman dan transparan.
                    </p>
                </div>

                <!-- Kolom 2: Navigasi -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Jelajahi Platform</h4>
                    <div class="grid grid-cols-2 gap-2 text-xs text-slate-500 font-medium">
                        <a href="{{ route('login') }}" class="hover:text-teal-600 transition">Cari Lowongan</a>
                        <a href="{{ route('login') }}" class="hover:text-teal-600 transition">Lihat Talent</a>
                        <a href="{{ route('login') }}" class="hover:text-teal-600 transition">Cara Kerja</a>
                        <a href="{{ route('login') }}" class="hover:text-teal-600 transition">Sistem Keamanan</a>
                        <a href="{{ route('login') }}" class="hover:text-teal-600 transition">Hubungi Bantuan</a>
                        <a href="{{ route('login') }}" class="hover:text-teal-600 transition">FAQ</a>
                    </div>
                </div>
                
                <!-- Kolom 3: Kontak -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Kontak & Dukungan</h4>
                    <div class="space-y-2 text-xs text-slate-500 font-medium">
                        <p class="flex items-center gap-2">
                            <i class="fa-regular fa-envelope text-slate-400 w-4"></i> support@archipelagonexus.id
                        </p>
                        <p class="flex items-center gap-2">
                            <i class="fa-solid fa-headset text-slate-400 w-4"></i> Jam Kerja: 09:00 - 17:00 WIB
                        </p>
                    </div>
                    <div class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-600 px-3 py-1.5 rounded-full border border-emerald-200/40 text-[10px] font-bold">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                        <span>Semua Sistem Berjalan Normal</span>
                    </div>
                </div>

            </div>

            <!-- Bagian Bawah Footer -->
            <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500 font-medium">
                <p>&copy; 2026 <span class="text-slate-700 font-bold">The Archipelago Nexus</span>. Hak Cipta Dilindungi.</p>
                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}" class="hover:text-slate-700 transition">Ketentuan Layanan</a>
                    <a href="{{ route('login') }}" class="hover:text-slate-700 transition">Kebijakan Privasi</a>
                </div>
            </div>
        </div>
    </footer>
    
</body>
</html>
