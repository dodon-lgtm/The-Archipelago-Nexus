<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Daftar Akun - ApexForge Labs</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- AOS Animation Library CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Google Font -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Segmented control Freelancer / Perusahaan — dikelola oleh JS setRole() */
        .role-btn {
            transition: all 0.2s ease;
        }
        .role-btn:hover {
            color: #ffffff;
        }
        .role-btn.active {
            background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
        }

        /* Scrollbar halus untuk panel form kanan */
        .form-scroll::-webkit-scrollbar {
            width: 6px;
        }
        .form-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .form-scroll::-webkit-scrollbar-thumb {
            background: rgba(100, 116, 139, 0.4);
            border-radius: 10px;
        }
        .form-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(100, 116, 139, 0.6);
        }
    </style>
</head>

<body class="antialiased min-h-screen flex items-center justify-center p-3 md:p-6 bg-cover bg-center bg-no-repeat bg-fixed" style="background-image: url('{{ asset('images/backgroundlogin.png') }}');">

    <!-- Container Utama -->
    <div class="max-w-6xl w-full bg-white rounded-3xl shadow-2xl shadow-slate-200/80 border border-slate-100 overflow-hidden grid grid-cols-1 lg:grid-cols-12" data-aos="zoom-in" data-aos-duration="800">

        <!-- ================= SISI KIRI ================= -->
        <div class="lg:col-span-7 p-6 md:p-10 flex flex-col justify-between space-y-6 relative overflow-hidden bg-white">

            <!-- Background Gedung dengan Gradient Fade Overlay -->
            <div class="absolute inset-0 z-0 pointer-events-none">
                <img src="{{ asset('images/gedung.jpg') }}" alt="Background Gedung" class="w-full h-full object-cover opacity-100">
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
                        ApexForge Labs
                    </span>
                </div>

                <!-- Teks Utama -->
                <div class="space-y-3.5 my-auto">
                    <!-- Mini Badge Aksen -->
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 border border-blue-100 text-blue-600 text-[11px] font-bold">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                        Gabung Platform Nusantara
                    </div>

                    <h1 class="text-2xl md:text-3xl font-black text-slate-900 leading-tight">
                        Gabung Sekarang & Temukan
                        <br>
                        Bakat Terampil atau
                        <br>
                        Proyek Impian Anda di
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">
                            ApexForge Labs
                        </span>
                    </h1>

                    <p class="text-xs md:text-sm text-slate-600 max-w-md leading-relaxed font-medium">
                        Daftarkan akun Anda secara gratis dan mulailah berkolaborasi dengan freelancer maupun perusahaan terbaik di Nusantara.
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
        <div id="rightFormContainer" class="lg:col-span-5 bg-gradient-to-b from-slate-900 via-slate-800 to-slate-950 p-6 md:p-10 flex flex-col text-white relative overflow-hidden form-scroll lg:max-h-[calc(100vh-3rem)] lg:overflow-y-auto">

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
                        ApexForge<span class="text-blue-400">Labs</span>
                    </h2>
                    <p class="text-[11px] text-slate-400 font-medium mt-0.5">
                        Buat akun baru untuk melanjutkan
                    </p>
                </div>
            </div>

            <!-- FORM REGISTER -->
            <form method="POST" action="{{ route('register') }}" class="space-y-3.5 my-auto py-4 relative z-10">
                @csrf

                <input type="hidden" name="is_company" id="is_company_input" value="{{ old('is_company', 0) }}">

                <!-- Pesan Success -->
                @if (session('success'))
                    <div class="p-2.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 text-xs flex items-center gap-2">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Role Switcher Freelancer / Perusahaan -->
                <div class="flex gap-1 p-1 bg-slate-800/80 border border-slate-700/60 rounded-xl">
                    <button type="button" class="role-btn flex-1 py-2 text-[11px] font-bold rounded-lg text-slate-400 {{ old('is_company') ? '' : 'active' }}" id="btnFreelancer" onclick="setRole(false)">
                        <i class="fa-solid fa-user-tie me-1.5"></i>Freelancer
                    </button>
                    <button type="button" class="role-btn flex-1 py-2 text-[11px] font-bold rounded-lg text-slate-400 {{ old('is_company') ? 'active' : '' }}" id="btnCompany" onclick="setRole(true)">
                        <i class="fa-solid fa-building me-1.5"></i>Perusahaan / Client
                    </button>
                </div>

                <!-- Nama Lengkap PIC -->
                <div class="space-y-1">
                    <label for="name" class="text-[10px] font-bold tracking-wider text-slate-400 uppercase block">
                        Nama Lengkap
                    </label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-500 text-xs group-focus-within:text-blue-400 transition-colors">
                            <i class="fa-solid fa-user"></i>
                        </span>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name') }}"
                            placeholder="Nama lengkap Anda"
                            autofocus
                            required
                            class="w-full text-xs pl-10 pr-4 py-2.5 bg-slate-800/80 border {{ $errors->has('name') ? 'border-red-500' : 'border-slate-700/60' }} rounded-xl text-white placeholder-slate-500 focus:bg-slate-800 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition shadow-inner"
                        >
                    </div>
                    @error('name')
                        <p class="text-[10px] text-red-400 mt-0.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
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
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            placeholder="nama@email.com"
                            required
                            class="w-full text-xs pl-10 pr-4 py-2.5 bg-slate-800/80 border {{ $errors->has('email') ? 'border-red-500' : 'border-slate-700/60' }} rounded-xl text-white placeholder-slate-500 focus:bg-slate-800 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition shadow-inner"
                        >
                    </div>
                    @error('email')
                        <p class="text-[10px] text-red-400 mt-0.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nomor HP / WhatsApp -->
                <div class="space-y-1">
                    <label for="phone" class="text-[10px] font-bold tracking-wider text-slate-400 uppercase block">
                        Nomor HP / WhatsApp
                    </label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-500 text-xs group-focus-within:text-blue-400 transition-colors">
                            <i class="fa-solid fa-phone"></i>
                        </span>
                        <input
                            id="phone"
                            name="phone"
                            type="text"
                            value="{{ old('phone') }}"
                            placeholder="08xxxxxxxxxx"
                            required
                            class="w-full text-xs pl-10 pr-4 py-2.5 bg-slate-800/80 border {{ $errors->has('phone') ? 'border-red-500' : 'border-slate-700/60' }} rounded-xl text-white placeholder-slate-500 focus:bg-slate-800 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition shadow-inner"
                        >
                    </div>
                    @error('phone')
                        <p class="text-[10px] text-red-400 mt-0.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="space-y-1">
                    <label for="password" class="text-[10px] font-bold tracking-wider text-slate-400 uppercase block">
                        Password
                    </label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-500 text-xs group-focus-within:text-blue-400 transition-colors">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            placeholder="Minimal 8 karakter"
                            required
                            class="w-full text-xs pl-10 pr-10 py-2.5 bg-slate-800/80 border {{ $errors->has('password') ? 'border-red-500' : 'border-slate-700/60' }} rounded-xl text-white placeholder-slate-500 focus:bg-slate-800 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition shadow-inner"
                        >
                        <button type="button" onclick="togglePassword('password', this)" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-500 hover:text-blue-400 transition-colors">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-[10px] text-red-400 mt-0.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Konfirmasi Password -->
                <div class="space-y-1">
                    <label for="password_confirmation" class="text-[10px] font-bold tracking-wider text-slate-400 uppercase block">
                        Konfirmasi Password
                    </label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-500 text-xs group-focus-within:text-blue-400 transition-colors">
                            <i class="fa-solid fa-shield-halved"></i>
                        </span>
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            placeholder="Ulangi password"
                            required
                            class="w-full text-xs pl-10 pr-10 py-2.5 bg-slate-800/80 border border-slate-700/60 rounded-xl text-white placeholder-slate-500 focus:bg-slate-800 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition shadow-inner"
                        >
                        <button type="button" onclick="togglePassword('password_confirmation', this)" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-500 hover:text-blue-400 transition-colors">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Bagian Form Perusahaan -->
                <div id="companyFields" class="bg-slate-800/40 border border-slate-700/60 rounded-xl p-4 space-y-3" style="display: {{ old('is_company') ? 'block' : 'none' }};">
                    <div class="flex items-center gap-1.5 text-[10px] font-bold tracking-widest text-blue-400 uppercase">
                        <i class="fa-solid fa-building-circle-check"></i>
                        Informasi Detail Perusahaan
                    </div>

                    <div class="space-y-1">
                        <label for="company_name" class="text-[10px] font-bold tracking-wider text-slate-400 uppercase block">
                            Nama Perusahaan
                        </label>
                        <input
                            id="company_name"
                            name="company_name"
                            type="text"
                            value="{{ old('company_name') }}"
                            placeholder="PT / CV / Nama Usaha"
                            class="w-full text-xs px-3.5 py-2.5 bg-slate-800/80 border {{ $errors->has('company_name') ? 'border-red-500' : 'border-slate-700/60' }} rounded-xl text-white placeholder-slate-500 focus:bg-slate-800 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition shadow-inner"
                        >
                        @error('company_name')
                            <p class="text-[10px] text-red-400 mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1">
                        <label for="company_phone" class="text-[10px] font-bold tracking-wider text-slate-400 uppercase block">
                            Nomor Telepon Perusahaan
                        </label>
                        <input
                            id="company_phone"
                            name="company_phone"
                            type="text"
                            value="{{ old('company_phone') }}"
                            placeholder="08xxxxxxxxxx"
                            class="w-full text-xs px-3.5 py-2.5 bg-slate-800/80 border {{ $errors->has('company_phone') ? 'border-red-500' : 'border-slate-700/60' }} rounded-xl text-white placeholder-slate-500 focus:bg-slate-800 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition shadow-inner"
                        >
                        @error('company_phone')
                            <p class="text-[10px] text-red-400 mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1">
                        <label for="company_address" class="text-[10px] font-bold tracking-wider text-slate-400 uppercase block">
                            Alamat Perusahaan
                        </label>
                        <textarea
                            id="company_address"
                            name="company_address"
                            rows="2"
                            placeholder="Alamat lengkap lokasi"
                            class="w-full text-xs px-3.5 py-2.5 bg-slate-800/80 border {{ $errors->has('company_address') ? 'border-red-500' : 'border-slate-700/60' }} rounded-xl text-white placeholder-slate-500 focus:bg-slate-800 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition shadow-inner"
                            style="resize: none;"
                        >{{ old('company_address') }}</textarea>
                        @error('company_address')
                            <p class="text-[10px] text-red-400 mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1">
                        <label for="company_description" class="text-[10px] font-bold tracking-wider text-slate-400 uppercase block">
                            Deskripsi Perusahaan (Opsional)
                        </label>
                        <textarea
                            id="company_description"
                            name="company_description"
                            rows="2"
                            placeholder="Ceritakan sedikit..."
                            class="w-full text-xs px-3.5 py-2.5 bg-slate-800/80 border border-slate-700/60 rounded-xl text-white placeholder-slate-500 focus:bg-slate-800 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition shadow-inner"
                            style="resize: none;"
                        >{{ old('company_description') }}</textarea>
                    </div>
                </div>

                <!-- TOMBOL DAFTAR -->
                <button type="submit" class="w-full py-2.5 mt-1 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white text-xs font-bold rounded-xl shadow-lg shadow-blue-600/20 transition transform active:scale-[0.98] flex items-center justify-center gap-2 group">
                    <i class="fa-solid fa-user-plus"></i>
                    Daftar Sekarang
                    <i class="fa-solid fa-arrow-right group-hover:translate-x-0.5 transition-transform"></i>
                </button>

            </form>

            <!-- LOGIN -->
            <div class="text-center text-xs text-slate-400 pt-2 relative z-10">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-blue-400 font-bold hover:text-blue-300 hover:underline ml-1 transition-colors">
                    Masuk di sini
                </a>
            </div>

        </div>

    </div>

    <!-- AOS Animation Library JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ once: true });

        // Memastikan saat halaman dimuat, posisi scroll form kanan berada di paling atas
        window.addEventListener('DOMContentLoaded', () => {
            const formContainer = document.getElementById('rightFormContainer');
            if (formContainer) {
                formContainer.scrollTop = 0;
            }
        });

        function setRole(isCompany) {
            const btnFreelancer = document.getElementById('btnFreelancer');
            const btnCompany = document.getElementById('btnCompany');
            const companyFields = document.getElementById('companyFields');
            const hiddenInput = document.getElementById('is_company_input');

            if (isCompany) {
                btnCompany.classList.add('active');
                btnFreelancer.classList.remove('active');
                companyFields.style.display = 'block';
                hiddenInput.value = '1';
            } else {
                btnFreelancer.classList.add('active');
                btnCompany.classList.remove('active');
                companyFields.style.display = 'none';
                hiddenInput.value = '0';
            }
        }

        function togglePassword(fieldId, button) {
            const input = document.getElementById(fieldId);
            const icon = button.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
