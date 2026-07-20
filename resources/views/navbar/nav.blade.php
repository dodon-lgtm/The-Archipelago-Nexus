<header class="h-16 bg-white border-b border-slate-200 px-6 flex items-center justify-between">

    <!-- ================= LEFT ================= -->
    <div class="flex items-center gap-8">
        <a href="/" class="text-lg font-black text-cyan-600">TAN</a>

        <!-- Menu -->
        <nav class="hidden lg:flex items-center gap-6">
            @auth
                {{-- ================= FREELANCER ================= --}}
                @if(Auth::user()->role == 'freelancer')
                    <a href="{{ route('freelancer.dashboard') }}" class="text-sm font-semibold hover:text-cyan-600 transition">Home</a>
                    <a href="#" class="text-sm text-slate-600 hover:text-cyan-600 transition">Lamaran</a>
                    <a href="#" class="text-sm text-slate-600 hover:text-cyan-600 transition">Tersimpan</a>
                
                {{-- ================= COMPANY ================= --}}
                @elseif(Auth::user()->role == 'company')
                    <a href="#" class="text-sm font-semibold hover:text-cyan-600 transition">Dashboard</a>
                    <a href="#" class="text-sm text-slate-600 hover:text-cyan-600 transition">Tambah Proyek</a>
                    <a href="#" class="text-sm text-slate-600 hover:text-cyan-600 transition">Proyek Saya</a>
                
                {{-- ================= ADMIN ================= --}}
                @elseif(Auth::user()->role == 'admin')
                    <a href="#" class="text-sm font-semibold hover:text-cyan-600 transition">Dashboard</a>
                    <a href="#" class="text-sm text-slate-600 hover:text-cyan-600 transition">User</a>
                    <a href="#" class="text-sm text-slate-600 hover:text-cyan-600 transition">Proyek</a>
                    <a href="#" class="text-sm text-slate-600 hover:text-cyan-600 transition">Ulasan</a>
                @endif
            @endauth
        </nav>
    </div>

    <!-- ================= RIGHT ================= -->
    <div class="flex items-center gap-4">
        <!-- NOTIF -->
        <a href="#" class="relative w-10 h-10 rounded-full border border-slate-200 hover:bg-slate-100 flex items-center justify-center">
            <i class="fa-regular fa-bell"></i>
            <span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-red-500"></span>
        </a>

        <!-- USER -->
        <div class="relative">
            <button id="userButton" class="flex items-center gap-3 hover:bg-slate-100 rounded-xl px-2 py-2 transition">
                <div class="w-10 h-10 rounded-full overflow-hidden bg-cyan-500 flex items-center justify-center text-white">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div class="text-left">
                    <h2 class="text-sm font-semibold">{{ Auth::user()->name }}</h2>
                    <p class="text-xs text-slate-500">{{ ucfirst(Auth::user()->role) }}</p>
                </div>
                <i class="fa-solid fa-chevron-down text-xs text-slate-500"></i>
            </button>

            <!-- Dropdown -->
            <div id="userDropdown" class="hidden absolute right-0 mt-3 w-64 bg-white rounded-2xl border shadow-xl overflow-hidden z-[100]">
                <div class="p-5 border-b">
                    <h2 class="font-bold">{{ Auth::user()->name }}</h2>
                    <p class="text-sm text-slate-500">{{ Auth::user()->email }}</p>
                </div>
                
                <a href="#" class="flex items-center gap-3 px-5 py-3 hover:bg-slate-50"><i class="fa-regular fa-user"></i> Profil</a>
                
                @if(Auth::user()->role == 'freelancer')
                    <a href="#" class="flex items-center gap-3 px-5 py-3 hover:bg-slate-50"><i class="fa-regular fa-file-lines"></i> Lamaran Saya</a>
                @elseif(Auth::user()->role == 'company')
                    <a href="#" class="flex items-center gap-3 px-5 py-3 hover:bg-slate-50"><i class="fa-solid fa-plus"></i> Tambah Proyek</a>
                @elseif(Auth::user()->role == 'admin')
                    <a href="#" class="flex items-center gap-3 px-5 py-3 hover:bg-slate-50"><i class="fa-solid fa-chart-line"></i> Dashboard Admin</a>
                @endif

                <a href="#" class="flex items-center gap-3 px-5 py-3 hover:bg-slate-50"><i class="fa-solid fa-gear"></i> Pengaturan</a>
                
                <div class="border-t"></div>
                
                <form action="{{ url('/logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left px-5 py-3 flex items-center gap-3 text-red-600 hover:bg-red-50">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

{{-- Script untuk mengontrol Dropdown --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const userButton = document.getElementById('userButton');
        const userDropdown = document.getElementById('userDropdown');

        userButton.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('hidden');
        });

        // Menutup dropdown saat klik di luar area
        window.addEventListener('click', (e) => {
            if (!userDropdown.classList.contains('hidden')) {
                userDropdown.classList.add('hidden');
            }
        });
    });
</script>