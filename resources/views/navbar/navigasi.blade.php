<aside class="w-64 bg-white border-r border-slate-200 flex flex-col h-screen sticky top-0 shrink-0">

    {{-- LOGO --}}
    <div class="p-6 flex items-center gap-3 border-b border-slate-100 shrink-0">
        <div class="w-10 h-10 rounded-full overflow-hidden shrink-0">
            <img
                src="{{ asset('images/nexus.jpg') }}"
                alt="Nexus Logo"
                class="w-full h-full object-cover"
            >
        </div>

        <div>
            <h2 class="font-extrabold text-sm leading-tight text-slate-800">
                The Archipelago<br>Nexus
            </h2>
        </div>
    </div>

    {{-- MENU --}}
    <nav class="mt-5 px-3 space-y-2 flex-1 overflow-y-auto">

        @auth

            @if(Auth::user()->role == 'freelancer')

                <a href="{{ route('freelancer.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('freelancer.dashboard')
                        ? 'bg-cyan-50 text-cyan-700 font-bold'
                        : 'text-slate-600 hover:bg-slate-100' }}">
                    <i class="fa-solid fa-house w-5"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('freelancer.proyek') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('freelancer.proyek')
                        ? 'bg-cyan-50 text-cyan-700 font-bold'
                        : 'text-slate-600 hover:bg-slate-100' }}">
                    <i class="fa-solid fa-magnifying-glass w-5"></i>
                    <span>Cari Proyek</span>
                </a>

                <a href="{{ route('freelancer.workspaces.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('freelancer.workspaces.*')
                        ? 'bg-cyan-50 text-cyan-700 font-bold'
                        : 'text-slate-600 hover:bg-slate-100' }}">
                    <i class="fa-solid fa-layer-group w-5"></i>
                    <span>Workspace Saya</span>
                </a>

            @elseif(Auth::user()->role == 'company')

                <a href="{{ route('company.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('company.dashboard')
                        ? 'bg-cyan-50 text-cyan-700 font-bold'
                        : 'text-slate-600 hover:bg-slate-100' }}">
                    <i class="fa-solid fa-house w-5"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('company.projects.create') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('company.projects.create')
                        ? 'bg-cyan-50 text-cyan-700 font-bold'
                        : 'text-slate-600 hover:bg-slate-100' }}">
                    <i class="fa-solid fa-plus w-5"></i>
                    <span>Tambah Proyek</span>
                </a>

                <a href="{{ route('company.projects.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('company.projects.*')
                        && !request()->routeIs('company.projects.create')
                        ? 'bg-cyan-50 text-cyan-700 font-bold'
                        : 'text-slate-600 hover:bg-slate-100' }}">
                    <i class="fa-solid fa-folder-open w-5"></i>
                    <span>Proyek Saya</span>
                </a>

                <a href="{{ route('company.workspaces.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('company.workspaces.*')
                        ? 'bg-cyan-50 text-cyan-700 font-bold'
                        : 'text-slate-600 hover:bg-slate-100' }}">
                    <i class="fa-solid fa-layer-group w-5"></i>
                    <span>Workspace</span>
                </a>

            @elseif(Auth::user()->role == 'admin')

                <a href="#"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition text-slate-600 hover:bg-slate-100">
                    <i class="fa-solid fa-chart-line w-5"></i>
                    <span>Dashboard</span>
                </a>

                <a href="#"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition text-slate-600 hover:bg-slate-100">
                    <i class="fa-solid fa-folder-open w-5"></i>
                    <span>Semua Proyek</span>
                </a>

            @endif

        @endauth

    </nav>

    {{-- SIDEBAR FOOTER --}}
    <div class="p-4 shrink-0">
        <div class="rounded-2xl bg-gradient-to-r from-cyan-500 to-teal-500 p-4 text-white">
            <h3 class="font-bold text-sm">
                The Archipelago Nexus
            </h3>

            <p class="text-xs mt-1 opacity-90">
                Marketplace Freelance Indonesia
            </p>

            <div class="mt-4 text-xs opacity-80">
                © 2026
            </div>
        </div>
    </div>

</aside>