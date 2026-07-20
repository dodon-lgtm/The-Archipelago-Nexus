<header class="h-16 bg-white border-b border-slate-200 px-6 flex items-center justify-between">

    <!-- ================= LEFT ================= -->
    <div class="flex items-center gap-8">

        <!-- Logo -->
        <a href="/" class="text-lg font-black text-cyan-600">
            TAN
        </a>

        <!-- Menu -->
        <nav class="hidden lg:flex items-center gap-6">

            @auth

                {{-- ================= USER / FREELANCER ================= --}}
                @if(Auth::user()->role == 'user')

                    <a href="/beranda"
                        class="text-sm font-semibold hover:text-cyan-600 transition">
                        Home
                    </a>

                    <a href="/lamaran"
                        class="text-sm text-slate-600 hover:text-cyan-600 transition">
                        Lamaran
                    </a>

                    <a href="/tersimpans"
                        class="text-sm text-slate-600 hover:text-cyan-600 transition">
                        Tersimpan
                    </a>

                @endif


                {{-- ================= PERUSAHAAN ================= --}}
                @if(Auth::user()->role == 'perusahaan')

                    <a href="/perusahaan"
                        class="text-sm font-semibold hover:text-cyan-600 transition">
                        Dashboard
                    </a>

                    <a href="/tambahproyek"
                        class="text-sm text-slate-600 hover:text-cyan-600 transition">
                        Tambah Proyek
                    </a>

                    <a href="/proyek-saya"
                        class="text-sm text-slate-600 hover:text-cyan-600 transition">
                        Proyek Saya
                    </a>

                @endif


                {{-- ================= ADMIN ================= --}}
                @if(Auth::user()->role == 'admin')

                    <a href="/admin"
                        class="text-sm font-semibold hover:text-cyan-600 transition">
                        Dashboard
                    </a>

                    <a href="/users"
                        class="text-sm text-slate-600 hover:text-cyan-600 transition">
                        User
                    </a>

                    <a href="/proyek"
                        class="text-sm text-slate-600 hover:text-cyan-600 transition">
                        Proyek
                    </a>

                    <a href="/ulasan"
                        class="text-sm text-slate-600 hover:text-cyan-600 transition">
                        Ulasan
                    </a>

                @endif

            @endauth

        </nav>

    </div>

    <!-- ================= RIGHT ================= -->
    <div class="flex items-center gap-4">

        <!-- SEARCH -->
        {{-- <div class="relative hidden md:block">

            <input
                type="text"
                placeholder="Cari proyek..."
                class="w-72 h-10 rounded-xl border border-slate-200 bg-slate-50 pl-4 pr-10 text-sm focus:ring-2 focus:ring-cyan-500 focus:outline-none">

            <i class="fa-solid fa-magnifying-glass absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

        </div> --}}

        <!-- NOTIF -->
        <a href="
        "
           class="relative w-10 h-10 rounded-full border border-slate-200 hover:bg-slate-100 flex items-center justify-center">

            <i class="fa-regular fa-bell"></i>

            <span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-red-500"></span>

        </a>

        <!-- USER -->
        <div class="relative">

            <button
                id="userButton"
                class="flex items-center gap-3 hover:bg-slate-100 rounded-xl px-2 py-2 transition">

                <!-- Avatar -->
                <div class="w-10 h-10 rounded-full overflow-hidden">

                    @auth

                        @if(Auth::user()->foto)

                            <img
                                src="{{ asset(Auth::user()->foto) }}"
                                class="w-full h-full object-cover">

                        @else

                            <div class="w-full h-full bg-cyan-500 flex items-center justify-center text-white">

                                <i class="fa-solid fa-user"></i>

                            </div>

                        @endif

                    @endauth

                </div>

                <!-- Nama -->
                <div class="text-left">

                    <h2 class="text-sm font-semibold">

                        {{ Auth::user()->nama }}

                    </h2>

                    <p class="text-xs text-slate-500">

                        {{ ucfirst(Auth::user()->role) }}

                    </p>

                </div>

                <i class="fa-solid fa-chevron-down text-xs text-slate-500"></i>

            </button>

            <!-- Dropdown -->
            <div
                id="userDropdown"
                class="hidden absolute right-0 mt-3 w-64 bg-white rounded-2xl border shadow-xl overflow-hidden z-50">

                <div class="p-5 border-b">

                    <h2 class="font-bold">

                        {{ Auth::user()->nama }}

                    </h2>

                    <p class="text-sm text-slate-500">

                        {{ Auth::user()->email }}

                    </p>

                </div>

                <a href="/profil"
                    class="flex items-center gap-3 px-5 py-3 hover:bg-slate-50">

                    <i class="fa-regular fa-user"></i>

                    Profil

                </a>

                @if(Auth::user()->role=='user')

                <a href="/lamaran"
                    class="flex items-center gap-3 px-5 py-3 hover:bg-slate-50">

                    <i class="fa-regular fa-file-lines"></i>

                    Lamaran Saya

                </a>

                @endif

                @if(Auth::user()->role=='perusahaan')

                <a href="/tambahproyek"
                    class="flex items-center gap-3 px-5 py-3 hover:bg-slate-50">

                    <i class="fa-solid fa-plus"></i>

                    Tambah Proyek

                </a>

                @endif

                @if(Auth::user()->role=='admin')

                <a href="/admin"
                    class="flex items-center gap-3 px-5 py-3 hover:bg-slate-50">

                    <i class="fa-solid fa-chart-line"></i>

                    Dashboard Admin

                </a>

                @endif

                <a href="/pengaturan"
                    class="flex items-center gap-3 px-5 py-3 hover:bg-slate-50">

                    <i class="fa-solid fa-gear"></i>

                    Pengaturan

                </a>

                <div class="border-t"></div>

                <form action="{{ url('/logout') }}" method="POST">

                    @csrf

                    <button
                        class="w-full text-left px-5 py-3 flex items-center gap-3 text-red-600 hover:bg-red-50">

                        <i class="fa-solid fa-right-from-bracket"></i>

                        Logout

                    </button>

                </form>

            </div>

        </div>

    </div>

</header>

<script>

const userButton=document.getElementById('userButton');
const userDropdown=document.getElementById('userDropdown');

userButton.onclick=function(e){

e.stopPropagation();

userDropdown.classList.toggle('hidden');

}

document.onclick=function(){

userDropdown.classList.add('hidden');

}

</script>