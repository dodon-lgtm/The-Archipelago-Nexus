<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Masuk ke Akun - ApexForge Labs</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Identity Services SDK -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>

    <!-- Google Font -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
<style>

/* ApexForge Labs — Unified UI System */
:root{
    --af-primary:#2563eb;
    --af-primary-dark:#1d4ed8;
    --af-primary-soft:#eff6ff;
    --af-sky:#38bdf8;
    --af-ink:#0f172a;
    --af-muted:#64748b;
    --af-border:#dbeafe;
    --af-surface:#ffffff;
    --af-page:#f6f9ff;
}
html{scroll-behavior:smooth}
body{
    font-family:'Plus Jakarta Sans',sans-serif;
    background:
        radial-gradient(circle at 10% -10%,rgba(56,189,248,.10),transparent 30%),
        radial-gradient(circle at 100% 0%,rgba(37,99,235,.08),transparent 28%),
        var(--af-page);
}
::selection{background:rgba(37,99,235,.18);color:#0f172a}
::-webkit-scrollbar{width:7px;height:7px}
::-webkit-scrollbar-track{background:rgba(241,245,249,.7)}
::-webkit-scrollbar-thumb{background:rgba(37,99,235,.22);border-radius:999px}
::-webkit-scrollbar-thumb:hover{background:rgba(37,99,235,.38)}

input,select,textarea{
    border-color:var(--af-border)!important;
    background:rgba(255,255,255,.92);
    transition:border-color .2s ease,box-shadow .2s ease,background .2s ease;
}
input:focus,select:focus,textarea:focus{
    border-color:rgba(37,99,235,.55)!important;
    box-shadow:0 0 0 4px rgba(37,99,235,.09)!important;
    outline:none!important;
}
button,a,[role="button"]{transition:all .2s ease}
button:focus-visible,a:focus-visible,[role="button"]:focus-visible{
    outline:2px solid rgba(37,99,235,.55);
    outline-offset:2px;
}
table{border-collapse:separate;border-spacing:0}
thead th{
    background:rgba(239,246,255,.72)!important;
    color:#334155;
    font-weight:700;
}
tbody tr{transition:background .18s ease}
tbody tr:hover{background:rgba(239,246,255,.48)}
[class*="bg-blue-600"]{
    box-shadow:0 8px 22px -12px rgba(37,99,235,.72);
}
[class*="bg-blue-600"]:hover{
    box-shadow:0 12px 28px -12px rgba(37,99,235,.78);
    transform:translateY(-1px);
}
.glass-panel,.glass-card,.glass-surface{
    background:rgba(255,255,255,.72);
    border:1px solid rgba(219,234,254,.85);
    backdrop-filter:blur(18px);
    -webkit-backdrop-filter:blur(18px);
    box-shadow:0 18px 50px -32px rgba(30,64,175,.32);
}
.apex-page-glow{
    position:fixed;inset:auto -10rem -12rem auto;width:28rem;height:28rem;
    background:rgba(56,189,248,.09);filter:blur(70px);border-radius:999px;
    pointer-events:none;z-index:-1;
}
@media (max-width:767px){
    main{padding-left:1rem!important;padding-right:1rem!important}
    table{min-width:680px}
    .overflow-x-auto{-webkit-overflow-scrolling:touch}
}
@media (prefers-reduced-motion:reduce){
    *,*::before,*::after{animation-duration:.01ms!important;animation-iteration-count:1!important;transition-duration:.01ms!important;scroll-behavior:auto!important}
}

</style>
</head>

<body class="antialiased min-h-screen flex items-center justify-center p-3 md:p-6 bg-cover bg-center bg-no-repeat bg-fixed" style="background-image: url('{{ asset('images/backgroundlogin.png') }}');">

    <!-- Container Utama -->
    <div class="max-w-6xl w-full bg-white rounded-3xl shadow-2xl shadow-slate-200/80 border border-blue-50 overflow-hidden grid grid-cols-1 lg:grid-cols-12">

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
                        <img src="{{ asset('images/nexus.jpg') }}" alt="ApexForge Labs Logo" class="w-7 h-7 rounded-full object-cover">
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
                        Platform Talenta Nusantara
                    </div>

                    <h1 class="text-2xl md:text-3xl font-black text-slate-900 leading-tight">
                        Temukan Bakat Terampil 
                        <br>
                        atau Proyek Impian Anda 
                        <br>
                        di 
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">
                            ApexForge Labs
                        </span>
                    </h1>

                    <p class="text-xs md:text-sm text-slate-600 max-w-md leading-relaxed font-medium">
                        Hubungkan dengan freelancer terbaik dan wujudkan proyek Anda dengan mudah, cepat, dan aman.
                    </p>
                </div>

                <!-- Fitur (Glassmorphism Styling) -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

                    <!-- Fitur 1 -->
                    <div class="bg-white/70 backdrop-blur-md border border-blue-100/60 p-3 rounded-2xl flex items-start gap-2.5 shadow-sm hover:border-blue-200 transition-colors">
                        <div class="w-7 h-7 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center text-xs shrink-0">
                            <i class="fa-solid fa-user-shield"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900">Freelancer Terpercaya</h4>
                            <p class="text-[10px] text-slate-500 mt-0.5 leading-tight">Banyak freelancer berkualitas.</p>
                        </div>
                    </div>

                    <!-- Fitur 2 -->
                    <div class="bg-white/70 backdrop-blur-md border border-blue-100/60 p-3 rounded-2xl flex items-start gap-2.5 shadow-sm hover:border-emerald-200 transition-colors">
                        <div class="w-7 h-7 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center text-xs shrink-0">
                            <i class="fa-solid fa-wallet"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900">Proses Aman</h4>
                            <p class="text-[10px] text-slate-500 mt-0.5 leading-tight">Transaksi aman & terjamin.</p>
                        </div>
                    </div>

                    <!-- Fitur 3 -->
                    <div class="bg-white/70 backdrop-blur-md border border-blue-100/60 p-3 rounded-2xl flex items-start gap-2.5 shadow-sm hover:border-amber-200 transition-colors">
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
                        <img src="{{ asset('images/nexus.jpg') }}" alt="ApexForge Labs Logo" class="w-6 h-6 rounded-full object-cover">
                    </div>
                </div>

                <div>
                    <h2 class="font-extrabold text-base tracking-wide text-white">
                       ApexForge <span class="text-blue-400">Labs</span>
                    </h2>
                    <p class="text-[11px] text-slate-400 font-medium mt-0.5">
                        Masuk ke akun untuk melanjutkan
                    </p>
                </div>
            </div>

<!-- FORM LOGIN -->
            <form action="{{ route('login') }}" method="POST" class="space-y-3.5 my-auto py-4 relative z-10">
                @csrf

                @if(request()->filled('redirect'))
                    <input 
                        type="hidden" 
                        name="redirect" 
                        value="{{ request('redirect') }}"
                    >
                @endif

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
                        <a href="{{ route('password.request') }}"
                    class="text-[10px] text-blue-400 hover:text-blue-300 hover:underline transition-colors">
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
                        <button type="button" id="togglePassword"
                            class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-500 hover:text-blue-400 transition-colors focus:outline-none"
                            aria-label="Tampilkan atau sembunyikan password">
                            <i id="togglePasswordIcon" class="fa-regular fa-eye"></i>
                        </button>
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
                <button type="submit" id="loginSubmit" disabled class="w-full py-2.5 mt-1 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white text-xs font-bold rounded-xl shadow-lg shadow-blue-600/20 transition transform active:scale-[0.98] flex items-center justify-center gap-2 group disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:from-blue-600 disabled:hover:to-indigo-600 disabled:active:scale-100 disabled:shadow-none">
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

                <!-- GOOGLE LOGIN INTEGRATION -->
                <!-- ⚠️ PENTING: Ganti YOUR_CLIENT_ID dengan Client ID Google Cloud Anda -->
                <div id="g_id_onload"
                     data-client_id="1003806983123-1lj0oba3dn5ptanueebqsrd6n1ees0g3.apps.googleusercontent.com"
                     data-context="signin"
                     data-ux_mode="popup"
                     data-callback="handleGoogleLogin"
                     data-auto_prompt="false">
                </div>

                <div class="g_id_signin flex justify-center"
                     data-type="standard"
                     data-shape="rectangular"
                     data-theme="outline"
                     data-text="signin_with"
                     data-size="large"
                     data-logo_alignment="left"
                     data-width="100%">
                </div>

            </form>

            <!-- FORM TERSEMBUNYI UNTUK MENGIRIM TOKEN GOOGLE KE LARAVEL -->
            <form id="google-login-form" action="{{ route('login.google') }}" method="POST" class="hidden">
                @csrf
                <input type="hidden" name="id_token" id="google_id_token">
            </form>

           <!-- PERSETUJUAN KEBIJAKAN & PRIVASI -->
            <div class="space-y-1 relative z-10 mt-1 text-center">
                <label for="agreePolicy" class="inline-flex items-center justify-center gap-2 cursor-pointer select-none group">
                    <input
                        id="agreePolicy"
                        type="checkbox"
                        name="agree_policy"
                        value="1"
                        class="w-4 h-4 rounded border-slate-600 bg-slate-800 text-blue-600 focus:ring-blue-500 focus:ring-offset-0 cursor-pointer shrink-0"
                    >
                    <span class="text-[11px] text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">
                        Saya menyetujui
                        <button
                            type="button"
                            id="openPolicyModal"
                            class="inline text-blue-400 font-semibold hover:text-blue-300 hover:underline transition-colors"
                        >
                            Kebijakan &amp; Privasi
                        </button>
                    </span>
                </label>
                <p id="agreeHint" class="hidden text-[10px] text-amber-400/90 flex items-center justify-center gap-1">
                    <i class="fa-solid fa-circle-info"></i>
                    <span>Centang persetujuan ini terlebih dahulu untuk masuk.</span>
                </p>
            </div>

            <!-- REGISTER -->
            <div class="text-center text-xs text-slate-400 pt-2 relative z-10">
                Belum punya akun?
                <a href="{{ route('register', request()->filled('redirect') ? ['redirect' => request('redirect')] : []) }}" class="text-blue-400 font-bold hover:text-blue-300 hover:underline ml-1 transition-colors">
                    Daftar di sini
                </a>
            </div>

        </div>

    </div>

<!-- ================= MODAL KEBIJAKAN & PRIVASI ================= -->
<div id="policyModal" class="hidden fixed inset-0 z-[999] items-center justify-center p-3 sm:p-4" role="dialog" aria-modal="true" aria-labelledby="policyModalTitle">
    <div id="policyBackdrop" class="absolute inset-0 bg-slate-900/70 backdrop-blur-sm"></div>

    <div class="relative w-full max-w-2xl max-h-[85vh] bg-white dark:bg-slate-900 rounded-2xl shadow-2xl shadow-slate-900/40 border border-slate-200 dark:border-slate-700 overflow-hidden flex flex-col transition-transform duration-300 scale-95" id="policyModalBox">

        <!-- Header Modal -->
        <div class="px-5 sm:px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-gradient-to-r from-blue-600/10 via-transparent to-transparent">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-9 h-9 rounded-xl bg-blue-600/10 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-file-shield text-sm"></i>
                </div>
                <div class="min-w-0">
                    <h3 id="policyModalTitle" class="text-sm font-black text-slate-900 dark:text-white truncate">Kebijakan &amp; Privasi</h3>
                    <p class="text-[10px] text-slate-400 truncate">Kebijakan Privasi &amp; Kebijakan Penggunaan</p>
                </div>
            </div>
            <button type="button" id="closePolicyModal" aria-label="Tutup modal" class="w-8 h-8 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-slate-900 dark:hover:text-white flex items-center justify-center transition-colors shrink-0">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Body Modal (scrollable) -->
<div class="flex-1 overflow-y-auto px-5 sm:px-6 py-5 space-y-6 text-xs text-slate-600 dark:text-slate-300 leading-relaxed">

    @forelse ($policies as $policy)
        <section>
            <div class="flex items-center gap-2 mb-2.5">
                @if ($policy->key === \App\Models\Policy::KEY_PRIVACY)
                    <i class="fa-solid fa-shield-halved text-blue-600 dark:text-blue-400 text-sm"></i>
                @elseif ($policy->key === \App\Models\Policy::KEY_USAGE)
                    <i class="fa-solid fa-list-check text-emerald-600 dark:text-emerald-400 text-sm"></i>
                @else
                    <i class="fa-solid fa-file-text text-slate-500 dark:text-slate-400 text-sm"></i>
                @endif
                <h4 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-wide">
                    {{ $policy->title }}
                </h4>
            </div>
            <div class="space-y-3">
                @foreach (preg_split('/\r\n|\r|\n/', $policy->content) as $paragraph)
                    @php($text = trim($paragraph))
                    @if ($text !== '')
                        <p>{!! nl2br(e($text)) !!}</p>
                    @endif
                @endforeach
            </div>
        </section>
    @empty
        <p class="text-slate-400">Tidak ada dokumen kebijakan yang tersedia saat ini.</p>
    @endforelse

</div>
<!-- Footer Modal -->
        <div class="px-5 sm:px-6 py-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-2.5">
            <button type="button" id="closePolicyModalBtn" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 shadow-lg shadow-blue-600/20 transition transform active:scale-[0.98]">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var agreeCheckbox = document.getElementById('agreePolicy');
    var loginSubmit   = document.getElementById('loginSubmit');
    var agreeHint     = document.getElementById('agreeHint');

    var policyModal    = document.getElementById('policyModal');
    var policyBackdrop = document.getElementById('policyBackdrop');
    var policyBox      = document.getElementById('policyModalBox');
    var openPolicy     = document.getElementById('openPolicyModal');

    // 1) Ceklis wajib -> aktifkan/nonaktifkan tombol login
    function syncLoginButton() {
        var checked = agreeCheckbox && agreeCheckbox.checked;
        if (loginSubmit) {
            loginSubmit.disabled = !checked;
        }
        if (agreeHint) {
            agreeHint.classList.toggle('hidden', !!checked);
        }
    }
    if (agreeCheckbox) {
        agreeCheckbox.addEventListener('change', syncLoginButton);
    }
    // Mulai dengan tombol login nonaktif (checkbox belum dicentang).
    syncLoginButton();

    // 1b) Toggle tampil/sembunyikan password
    var passwordInput = document.getElementById('password');
    var togglePassword = document.getElementById('togglePassword');
    var togglePasswordIcon = document.getElementById('togglePasswordIcon');
    if (passwordInput && togglePassword && togglePasswordIcon) {
        togglePassword.addEventListener('click', function () {
            var isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';
            togglePasswordIcon.classList.toggle('fa-eye', !isHidden);
            togglePasswordIcon.classList.toggle('fa-eye-slash', isHidden);
        });
    }

    // 2) Buka modal
    function openModal() {
        if (!policyModal) return;
        policyModal.classList.remove('hidden');
        policyModal.classList.add('flex');
        // animasi masuk
        requestAnimationFrame(function () {
            if (policyBox) policyBox.classList.remove('scale-95');
            policyBox && policyBox.classList.add('scale-100');
        });
        document.body.style.overflow = 'hidden';
    }

    // 3) Tutup modal
    function closeModal() {
        if (!policyModal) return;
        if (policyBox) {
            policyBox.classList.remove('scale-100');
            policyBox.classList.add('scale-95');
        }
        policyModal.classList.add('hidden');
        policyModal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    if (openPolicy) openPolicy.addEventListener('click', function (e) {
        // Mencegah klik tombol ikut men-toggle checkbox (tombol ada di dalam <label>).
        e.preventDefault();
        e.stopPropagation();
        openModal();
    });
    var closeBtns = document.querySelectorAll('#closePolicyModal, #closePolicyModalBtn');
    closeBtns.forEach(function (btn) { btn.addEventListener('click', closeModal); });
    if (policyBackdrop) policyBackdrop.addEventListener('click', closeModal);

    // 4) Tutup dengan tombol ESC
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeModal();
    });
});
</script>

<script>
    // Callback fungsi saat user berhasil login Google
    function handleGoogleLogin(response) {
        // Masukkan token JWT dari Google ke input tersembunyi
        document.getElementById('google_id_token').value = response.credential;
        
        // Kirim form ke backend Laravel
        document.getElementById('google-login-form').submit();
    }
</script>

</body>
</html>