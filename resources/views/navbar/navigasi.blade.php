<aside class="w-64 bg-white border-r border-slate-200 flex flex-col h-screen sticky top-0">

    {{-- LOGO --}}
    <div class="p-6 flex items-center gap-3 border-b border-slate-100">
        <div class="w-10 h-10 rounded-full overflow-hidden">
            <img src="{{ asset('images/projects/nexus.jpg') }}" class="w-full h-full object-cover">
        </div>
        <div>
            <h2 class="font-extrabold text-sm leading-tight">The Archipelago<br>Nexus</h2>
        </div>
    </div>

    {{-- MENU --}}
    <nav class="mt-5 px-3 space-y-2 flex-1">
        @auth
            {{-- ================= FREELANCER ================= --}}
            @if(Auth::user()->role == 'freelancer')
                <a href="{{ route('freelancer.projects.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('freelancer.projects.*') ? 'bg-cyan-50 text-cyan-700 font-bold' : 'text-slate-600 hover:bg-slate-100' }}">
                    <i class="fa-solid fa-briefcase"></i> Proyek
                </a>
                <a href=""
                    class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('proyek') ? 'bg-cyan-50 text-cyan-700 font-bold' : 'text-slate-600 hover:bg-slate-100' }}">
                    <i class="fa-solid fa-briefcase"></i> Cari Proyek
                </a>
                <a href=""
                    class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('tersimpans') ? 'bg-cyan-50 text-cyan-700 font-bold' : 'text-slate-600 hover:bg-slate-100' }}">
                    <i class="fa-regular fa-bookmark w-5"></i> Tersimpan
                </a>
                <a href=""
                    class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('nontifikasi') ? 'bg-cyan-50 text-cyan-700 font-bold' : 'text-slate-600 hover:bg-slate-100' }}">
                    <i class="fa-solid fa-bell"></i> Notifikasi
                </a>

            {{-- ================= COMPANY ================= --}}
            @elseif(Auth::user()->role == 'company')
                <a href=""
                    class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('perusahaan') ? 'bg-cyan-50 text-cyan-700 font-bold' : 'text-slate-600 hover:bg-slate-100' }}">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>
                <a href=""
                    class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('tambahproyek') ? 'bg-cyan-50 text-cyan-700 font-bold' : 'text-slate-600 hover:bg-slate-100' }}">
                    <i class="fa-solid fa-plus"></i> Tambah Proyek
                </a>
                <a href=""
                    class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('nontifikasi') ? 'bg-cyan-50 text-cyan-700 font-bold' : 'text-slate-600 hover:bg-slate-100' }}">
                    <i class="fa-solid fa-bell"></i> Notifikasi
                </a>

            {{-- ================= ADMIN ================= --}}
            @elseif(Auth::user()->role == 'admin')
                <a href=""
                    class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin') ? 'bg-cyan-50 text-cyan-700 font-bold' : 'text-slate-600 hover:bg-slate-100' }}">
                    <i class="fa-solid fa-chart-line"></i> Dashboard
                </a>
                <a href=""
                    class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('proyek') ? 'bg-cyan-50 text-cyan-700 font-bold' : 'text-slate-600 hover:bg-slate-100' }}">
                    <i class="fa-solid fa-folder-open"></i> Semua Proyek
                </a>
            @endif
        @endauth
    </nav>

    {{-- FOOTER --}}
    <div class="p-4">
        <div class="rounded-2xl bg-gradient-to-r from-cyan-500 to-teal-500 p-4 text-white">
            <h3 class="font-bold">The Archipelago Nexus</h3>
            <p class="text-xs mt-1 opacity-90">Marketplace Freelance Indonesia</p>
            <div class="mt-4 text-xs opacity-80">© 2026</div>
        </div>
    </div>
</aside>