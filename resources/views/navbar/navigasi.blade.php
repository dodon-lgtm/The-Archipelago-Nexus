<aside id="sidebar" class="w-64 bg-white border-r border-slate-200 flex flex-col h-screen sticky top-0 shrink-0 z-30">

    {{-- LOGO --}}
    <div class="sidebar-logo-wrapper p-6 flex items-center gap-3 border-b border-slate-100 shrink-0 transition-all duration-300">

        {{-- Mobile hamburger (visible only on mobile) --}}
        <button id="mobileSidebarToggle" class="sidebar-hamburger-mobile w-10 h-10 rounded-xl hover:bg-slate-100 flex items-center justify-center shrink-0 transition mr-1">
            <i class="fa-solid fa-bars text-slate-600 text-lg"></i>
        </button>

        {{-- Logo image --}}
        <div class="w-10 h-10 rounded-full overflow-hidden shrink-0">
            <img
                src="{{ asset('images/nexus.jpg') }}"
                alt="Nexus Logo"
                class="w-full h-full object-cover"
            >
        </div>

        {{-- Logo text --}}
        <div class="sidebar-logo-text transition-all duration-300 overflow-hidden">
            <h2 class="font-extrabold text-sm leading-tight text-slate-800 whitespace-nowrap">
                The Archipelago<br>Nexus
            </h2>
        </div>
        
        {{-- Desktop toggle button (visible only on desktop) --}}
        <button id="sidebarToggle" class="sidebar-toggle-desktop w-8 h-8 rounded-lg hover:bg-slate-100 flex items-center justify-center shrink-0 ml-auto transition">
            <i class="sidebar-toggle-icon fa-solid fa-chevron-left text-slate-400 text-sm transition-transform duration-300"></i>
        </button>

        {{-- Collapsed toggle button (visible only when collapsed on desktop) --}}
        <button id="sidebarToggleCollapsed" class="sidebar-toggle-collapsed w-8 h-8 rounded-lg hover:bg-slate-100 flex items-center justify-center shrink-0 mx-auto transition">
            <i class="sidebar-toggle-icon fa-solid fa-chevron-right text-slate-400 text-sm"></i>
        </button>
    </div>

    {{-- MENU --}}
    <nav class="mt-5 px-3 space-y-2 flex-1 overflow-y-auto">

        @auth

            @if(Auth::user()->role == 'freelancer')

<a href="{{ route('freelancer.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('freelancer.dashboard')
                        ? 'bg-cyan-50 text-cyan-700 font-bold'
                        : 'text-slate-600 hover:bg-slate-100' }}"
                   data-tooltip="Dashboard">
                    <i class="fa-solid fa-house w-5"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('freelancer.proyek') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('freelancer.proyek')
                        ? 'bg-cyan-50 text-cyan-700 font-bold'
                        : 'text-slate-600 hover:bg-slate-100' }}"
                   data-tooltip="Cari Proyek">
                    <i class="fa-solid fa-magnifying-glass w-5"></i>
                    <span>Cari Proyek</span>
                </a>

                <a href="{{ route('freelancer.workspaces.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('freelancer.workspaces.*')
                        ? 'bg-cyan-50 text-cyan-700 font-bold'
                        : 'text-slate-600 hover:bg-slate-100' }}"
                   data-tooltip="Workspace Saya">
                    <i class="fa-solid fa-layer-group w-5"></i>
                    <span>Workspace Saya</span>
                </a>

                <a href="{{ route('freelancer.pendapatan.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('freelancer.pendapatan.*')
                        ? 'bg-cyan-50 text-cyan-700 font-bold'
                        : 'text-slate-600 hover:bg-slate-100' }}"
                   data-tooltip="Pendapatan">
                    <i class="fa-solid fa-wallet w-5"></i>
                    <span>Pendapatan</span>
                </a>

                <a href="{{ route('freelancer.reports.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('freelancer.reports.*')
                        ? 'bg-cyan-50 text-cyan-700 font-bold'
                        : 'text-slate-600 hover:bg-slate-100' }}"
                   data-tooltip="Laporan">
                    <i class="fa-solid fa-flag w-5"></i>
                    <span>Laporan</span>
                </a>

            @elseif(Auth::user()->role == 'company')

<a href="{{ route('company.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('company.dashboard')
                        ? 'bg-cyan-50 text-cyan-700 font-bold'
                        : 'text-slate-600 hover:bg-slate-100' }}"
                   data-tooltip="Dashboard">
                    <i class="fa-solid fa-house w-5"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('company.projects.create') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('company.projects.create')
                        ? 'bg-cyan-50 text-cyan-700 font-bold'
                        : 'text-slate-600 hover:bg-slate-100' }}"
                   data-tooltip="Tambah Proyek">
                    <i class="fa-solid fa-plus w-5"></i>
                    <span>Tambah Proyek</span>
                </a>

                <a href="{{ route('company.projects.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('company.projects.*')
                        && !request()->routeIs('company.projects.create')
                        ? 'bg-cyan-50 text-cyan-700 font-bold'
                        : 'text-slate-600 hover:bg-slate-100' }}"
                   data-tooltip="Proyek Saya">
                    <i class="fa-solid fa-folder-open w-5"></i>
                    <span>Proyek Saya</span>
                </a>

<a href="{{ route('company.workspaces.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('company.workspaces.*')
                        ? 'bg-cyan-50 text-cyan-700 font-bold'
                        : 'text-slate-600 hover:bg-slate-100' }}"
                   data-tooltip="Workspace">
                    <i class="fa-solid fa-layer-group w-5"></i>
                    <span>Workspace</span>
                </a>

                <a href="{{ route('company.reports.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('company.reports.*')
                        ? 'bg-cyan-50 text-cyan-700 font-bold'
                        : 'text-slate-600 hover:bg-slate-100' }}"
                   data-tooltip="Laporan">
                    <i class="fa-solid fa-flag w-5"></i>
                    <span>Laporan</span>
                </a>

            @elseif(Auth::user()->role == 'admin')

<a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('admin.dashboard')
                        ? 'bg-cyan-50 text-cyan-700 font-bold'
                        : 'text-slate-600 hover:bg-slate-100' }}"
                   data-tooltip="Dashboard">
                    <i class="fa-solid fa-chart-line w-5"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('admin.users.*')
                        ? 'bg-cyan-50 text-cyan-700 font-bold'
                        : 'text-slate-600 hover:bg-slate-100' }}"
                   data-tooltip="Pengguna">
                    <i class="fa-solid fa-users w-5"></i>
                    <span>Pengguna</span>
                </a>

                <a href="{{ route('admin.categories.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('admin.categories.*')
                        ? 'bg-cyan-50 text-cyan-700 font-bold'
                        : 'text-slate-600 hover:bg-slate-100' }}"
                   data-tooltip="Kategori">
                    <i class="fa-solid fa-tags w-5"></i>
                    <span>Kategori</span>
                </a>

                <a href="{{ route('admin.projects.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('admin.projects.*')
                        ? 'bg-cyan-50 text-cyan-700 font-bold'
                        : 'text-slate-600 hover:bg-slate-100' }}"
                   data-tooltip="Proyek">
                    <i class="fa-solid fa-folder-open w-5"></i>
                    <span>Proyek</span>
                </a>

                <a href="{{ route('admin.penawarans.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('admin.penawarans.*')
                        ? 'bg-cyan-50 text-cyan-700 font-bold'
                        : 'text-slate-600 hover:bg-slate-100' }}"
                   data-tooltip="Penawaran">
                    <i class="fa-solid fa-file-invoice w-5"></i>
                    <span>Penawaran</span>
                </a>

                <a href="{{ route('admin.hasil-pekerjaan.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('admin.hasil-pekerjaan.*')
                        ? 'bg-cyan-50 text-cyan-700 font-bold'
                        : 'text-slate-600 hover:bg-slate-100' }}"
                   data-tooltip="Hasil Pekerjaan">
                    <i class="fa-solid fa-layer-group w-5"></i>
                    <span>Hasil Pekerjaan</span>
                </a>

                <a href="{{ route('admin.reports.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('admin.reports.*')
                        ? 'bg-cyan-50 text-cyan-700 font-bold'
                        : 'text-slate-600 hover:bg-slate-100' }}"
                   data-tooltip="Laporan">
                    <i class="fa-solid fa-flag w-5"></i>
                    <span>Laporan</span>
                </a>

                <a href="{{ route('admin.company-account-requests.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('admin.company-account-requests.*')
                        ? 'bg-cyan-50 text-cyan-700 font-bold'
                        : 'text-slate-600 hover:bg-slate-100' }}"
                   data-tooltip="Permintaan Akun Company">
                    <i class="fa-solid fa-building w-5"></i>
                    <span>Permintaan Akun Company</span>
                </a>

            @endif

        @endauth

    </nav>

    {{-- SIDEBAR FOOTER --}}
    <div class="p-4 shrink-0 sidebar-footer-wrapper">
        <div class="sidebar-footer-card rounded-2xl bg-gradient-to-r from-cyan-500 to-teal-500 p-4 text-white overflow-hidden transition-all duration-300">
            <h3 class="font-bold text-sm whitespace-nowrap">
                The Archipelago Nexus
            </h3>

            <p class="text-xs mt-1 opacity-90 whitespace-nowrap">
                Marketplace Freelance Indonesia
            </p>

            <div class="mt-4 text-xs opacity-80 whitespace-nowrap">
                © 2026
            </div>
        </div>
        {{-- Collapsed footer minimal --}}
        <div class="sidebar-footer-mini hidden">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-r from-cyan-500 to-teal-500 flex items-center justify-center mx-auto">
                <i class="fa-solid fa-globe text-white text-xs"></i>
            </div>
        </div>
    </div>

</aside>

{{-- MOBILE OVERLAY --}}
<div id="sidebarOverlay" class="hidden fixed inset-0 bg-black/40 z-20 lg:hidden transition-opacity duration-300"></div>

{{-- CUSTOM CSS FOR SIDEBAR --}}
<style>
    /* Sidebar base transition */
    #sidebar {
        transition: width 0.3s ease, transform 0.3s ease;
    }

    /* ===== COLLAPSED STATE ===== */
    #sidebar.collapsed {
        width: 72px !important;
    }
    #sidebar.collapsed .sidebar-logo-text {
        opacity: 0;
        visibility: hidden;
        width: 0;
        margin: 0;
        overflow: hidden;
        transition: opacity 0.2s ease, visibility 0.2s ease, width 0.3s ease;
    }
    #sidebar.collapsed .sidebar-logo-text * {
        white-space: nowrap;
    }
    /* Menu items in collapsed state */
    #sidebar.collapsed nav a {
        justify-content: center;
        padding: 0;
        width: 44px;
        height: 44px;
        margin-left: auto;
        margin-right: auto;
        gap: 0;
        border-radius: 12px;
    }
    #sidebar.collapsed nav a i {
        margin: 0;
        width: auto;
    }
    #sidebar.collapsed nav a span {
        display: none;
    }
    /* Footer card in collapsed state */
    #sidebar.collapsed .sidebar-footer-card {
        opacity: 0;
        visibility: hidden;
        height: 0;
        padding: 0;
        margin: 0;
        overflow: hidden;
        transition: opacity 0.2s ease, visibility 0.2s ease, height 0.3s ease, padding 0.3s ease;
    }
    #sidebar.collapsed .sidebar-footer-mini {
        display: block !important;
    }
    /* Toggle icon rotation */
    #sidebar.collapsed .sidebar-toggle-icon {
        transform: rotate(180deg);
    }
    /* Logo section adjustments in collapsed */
    #sidebar.collapsed .sidebar-logo-wrapper {
        justify-content: center;
        padding: 12px 4px;
    }
    #sidebar.collapsed .sidebar-logo-wrapper > button,
    #sidebar.collapsed .sidebar-logo-wrapper > .sidebar-logo-text {
        display: none;
    }
    /* Keep toggle visible in collapsed */
    #sidebar.collapsed .sidebar-logo-wrapper > .sidebar-toggle-collapsed {
        display: flex !important;
    }
    #sidebar:not(.collapsed) .sidebar-logo-wrapper > .sidebar-toggle-collapsed {
        display: none;
    }

/* ===== TOOLTIP ON COLLAPSED HOVER ===== */
    #sidebar.collapsed nav a {
        position: relative;
    }
    #sidebar.collapsed nav a:hover::after {
        content: attr(data-tooltip);
        position: fixed;
        left: 84px;
        top: var(--tooltip-top, 50%);
        transform: translateY(-50%);
        background: #1e293b;
        color: white;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
        z-index: 100;
        pointer-events: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        animation: tooltipFadeIn 0.2s ease forwards;
    }
    #sidebar.collapsed nav a:hover::before {
        content: '';
        position: fixed;
        left: 78px;
        top: var(--tooltip-top, 50%);
        transform: translateY(-50%);
        border: 6px solid transparent;
        border-right-color: #1e293b;
        z-index: 100;
        pointer-events: none;
        animation: tooltipFadeIn 0.2s ease forwards;
    }
    @keyframes tooltipFadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* ===== MOBILE DRAWER ===== */
    @media (max-width: 1023px) {
        #sidebar {
            position: fixed !important;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 40 !important;
            transform: translateX(-100%);
            width: 280px !important;
            transition: transform 0.3s ease;
            border-right: 1px solid #e2e8f0 !important;
        }
        #sidebar.mobile-open {
            transform: translateX(0);
        }
        #sidebar.collapsed {
            width: 280px !important;
        }
        #sidebar.collapsed .sidebar-logo-text {
            opacity: 1;
            visibility: visible;
            width: auto;
            margin: 0;
        }
        #sidebar.collapsed nav a {
            justify-content: flex-start;
            padding: 12px 16px;
            width: auto;
            height: auto;
            margin: 0;
            gap: 12px;
        }
        #sidebar.collapsed nav a i {
            margin: 0;
            width: 20px;
        }
        #sidebar.collapsed nav a span {
            display: inline;
        }
        #sidebar.collapsed .sidebar-footer-card {
            opacity: 1;
            visibility: visible;
            height: auto;
            padding: 16px;
            margin: 0;
        }
        #sidebar.collapsed .sidebar-footer-mini {
            display: none !important;
        }
        #sidebar.collapsed .sidebar-toggle-icon {
            transform: rotate(0deg);
        }
        #sidebar.collapsed .sidebar-logo-wrapper {
            justify-content: flex-start;
            padding: 16px 24px;
        }
        #sidebar.collapsed .sidebar-logo-wrapper > button,
        #sidebar.collapsed .sidebar-logo-wrapper > .sidebar-logo-text {
            display: flex;
        }
        #sidebar.collapsed .sidebar-logo-wrapper > .sidebar-toggle-collapsed {
            display: none !important;
        }
        /* Tooltip disabled on mobile */
        #sidebar.collapsed nav a:hover::after,
        #sidebar.collapsed nav a:hover::before {
            display: none !important;
            content: none !important;
        }
        /* Hide desktop-only toggle on mobile */
        .sidebar-toggle-desktop {
            display: none !important;
        }
        /* Show mobile hamburger */
        .sidebar-hamburger-mobile {
            display: flex !important;
        }
    }
    @media (min-width: 1024px) {
        .sidebar-hamburger-mobile {
            display: none !important;
        }
        .sidebar-toggle-desktop {
            display: flex !important;
        }
    }
</style>

{{-- SIDEBAR JAVASCRIPT --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const toggleDesktop = document.getElementById('sidebarToggle');
        const toggleMobile = document.getElementById('mobileSidebarToggle');
        const overlay = document.getElementById('sidebarOverlay');
        
        // Helper to position tooltips correctly
        function positionTooltips() {
            if (!sidebar) return;
            const links = sidebar.querySelectorAll('nav a');
            const isCollapsed = sidebar.classList.contains('collapsed');
            const isMobile = window.innerWidth < 1024;
            
            links.forEach(link => {
                if (isCollapsed && !isMobile) {
                    const rect = link.getBoundingClientRect();
                    link.style.setProperty('--tooltip-top', (rect.top + rect.height/2) + 'px');
                }
            });
        }
        
        // Update tooltip positions
        function updateTooltipPositions() {
            if (window.innerWidth >= 1024 && sidebar.classList.contains('collapsed')) {
                const links = sidebar.querySelectorAll('nav a');
                links.forEach(link => {
                    const rect = link.getBoundingClientRect();
                    link.style.setProperty('--tooltip-top', (rect.top + rect.height/2) + 'px');
                    // Update pseudo-element positions dynamically
                });
            }
        }
        

        if (toggleDesktop) {
            toggleDesktop.addEventListener('click', function(e) {
                e.stopPropagation();
                if (window.innerWidth >= 1024) {
                    sidebar.classList.toggle('collapsed');
                    localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed') ? 'true' : 'false');
                    // Dispatch event for main content adjustment
                    window.dispatchEvent(new CustomEvent('sidebar-toggle', { 
                        detail: { collapsed: sidebar.classList.contains('collapsed') }
                    }));
                    setTimeout(updateTooltipPositions, 50);
                }
            });
        }
        
        // Mobile toggle: open/close drawer
        if (toggleMobile) {
            toggleMobile.addEventListener('click', function(e) {
                e.stopPropagation();
                sidebar.classList.toggle('mobile-open');
                overlay.classList.toggle('hidden');
                document.body.classList.toggle('overflow-hidden');
            });
        }
        
        // Overlay click to close mobile sidebar
        if (overlay) {
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('mobile-open');
                overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            });
        }
        
        // Restore state from localStorage (desktop only)
        if (window.innerWidth >= 1024) {
            const savedState = localStorage.getItem('sidebarCollapsed');
            if (savedState === 'true') {
                sidebar.classList.add('collapsed');
            }
        }
        
        // Handle resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (window.innerWidth >= 1024) {
                    // Going to desktop
                    sidebar.classList.remove('mobile-open');
                    if (overlay) overlay.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                    
                    const savedState = localStorage.getItem('sidebarCollapsed');
                    if (savedState === 'true') {
                        sidebar.classList.add('collapsed');
                    } else {
                        sidebar.classList.remove('collapsed');
                    }
                } else {
                    // Going to mobile
                    sidebar.style.width = '';
                    sidebar.classList.remove('collapsed');
                }
                window.dispatchEvent(new CustomEvent('sidebar-toggle', { 
                    detail: { collapsed: sidebar.classList.contains('collapsed') }
                }));
            }, 250);
        });
        
        // Close mobile sidebar on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && window.innerWidth < 1024) {
                sidebar.classList.remove('mobile-open');
                if (overlay) overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        });
        
        // Update tooltip positions on scroll and resize
        window.addEventListener('scroll', updateTooltipPositions, { passive: true });
        window.addEventListener('resize', updateTooltipPositions);
        
        // Fix tooltip positioning using CSS custom properties
        if (sidebar) {
            const observer = new MutationObserver(function() {
                if (window.innerWidth >= 1024 && sidebar.classList.contains('collapsed')) {
                    updateTooltipPositions();
                }
            });
            observer.observe(sidebar, { attributes: true, attributeFilter: ['class'] });
        }

        // Initial dispatch
        setTimeout(function() {
            window.dispatchEvent(new CustomEvent('sidebar-toggle', { 
                detail: { collapsed: sidebar.classList.contains('collapsed') }
            }));
        }, 100);
    });
</script>
