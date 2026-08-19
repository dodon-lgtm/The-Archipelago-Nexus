 
 <!DOCTYPE html>
 <html lang="id">

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>@yield('title', 'Admin Panel') - ApexForge Labs</title>

     {{-- Tailwind CSS --}}
     <script>
         // Dark mode (class-based) — applies to elements with `dark:` variants.
         // Existing light-only admin pages tanpa `dark:` tetap tampil terang (tidak berubah).
         (function () {
             var dark = localStorage.getItem('theme') === 'dark'
                 || (!localStorage.getItem('theme')
                     && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);
             if (dark) {
                 document.documentElement.classList.add('dark');
             }
         })();
     </script>
     <script src="https://cdn.tailwindcss.com"></script>
     <script>
         tailwind.config = tailwind.config || {};
         tailwind.config.darkMode = 'class';
     </script>

     {{-- FontAwesome --}}
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

     {{-- Google Font --}}
     <style>
         @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

         body {
             font-family: 'Plus Jakarta Sans', sans-serif;
         }

         /* Sidebar scroll */
         .sidebar-scroll::-webkit-scrollbar {
             width: 4px;
         }

         .sidebar-scroll::-webkit-scrollbar-thumb {
             background: #cbd5e1;
             border-radius: 2px;
         }

         .sidebar-scroll::-webkit-scrollbar-track {
             background: transparent;
         }
     </style>

     @stack('styles')
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

 <body class="bg-[#f6f9ff] text-slate-800 antialiased">

     <div class="min-h-screen bg-[#f6f9ff] flex">

         {{-- =============== SIDEBAR =============== --}}
         <aside id="sidebar"
             class="w-64 bg-white border-r border-blue-100 flex flex-col h-screen sticky top-0 shrink-0 z-30">

             {{-- Logo --}}
             <div
                 class="sidebar-logo-wrapper p-6 flex items-center gap-3 border-b border-blue-50 shrink-0 transition-all duration-300">

                 {{-- Mobile hamburger --}}
                 <button id="mobileSidebarToggle"
                     class="sidebar-hamburger-mobile w-10 h-10 rounded-xl hover:bg-blue-50 flex items-center justify-center shrink-0 transition mr-1">
                     <i class="fa-solid fa-bars text-slate-600 text-lg"></i>
                 </button>

                 <div class="w-10 h-10 rounded-full overflow-hidden shrink-0">
                     <img src="{{ asset('images/nexus.jpg') }}" alt="ApexForge Labs Logo" class="w-full h-full object-cover">
                 </div>
                 <div class="sidebar-logo-text transition-all duration-300 overflow-hidden">
                     <h2 class="font-extrabold text-sm leading-tight text-slate-800 whitespace-nowrap">ApexForge<br><span class="text-blue-600">Labs</span></h2>
                 </div>

                 {{-- Desktop toggle button --}}
                 <button id="sidebarToggle"
                     class="sidebar-toggle-desktop w-8 h-8 rounded-lg hover:bg-blue-50 flex items-center justify-center shrink-0 ml-auto transition">
                     <i
                         class="sidebar-toggle-icon fa-solid fa-chevron-left text-slate-400 text-sm transition-transform duration-300"></i>
                 </button>

                 {{-- Collapsed toggle --}}
                 <button id="sidebarToggleCollapsed"
                     class="sidebar-toggle-collapsed w-8 h-8 rounded-lg hover:bg-blue-50 flex items-center justify-center shrink-0 mx-auto transition">
                     <i class="sidebar-toggle-icon fa-solid fa-chevron-right text-slate-400 text-sm"></i>
                 </button>
             </div>

             {{-- Menu --}}
             <nav class="mt-5 px-3 space-y-1 flex-1 overflow-y-auto sidebar-scroll">

                 {{-- Dashboard --}}
                 <a href="{{ route('admin.dashboard') }}"
                     class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
                   {{ request()->routeIs('admin.dashboard')
                       ? 'bg-blue-50 text-blue-700 font-bold shadow-sm border border-blue-100'
                       : 'text-slate-600 hover:bg-blue-50 hover:text-slate-800' }}"
                     data-tooltip="Dashboard">
                     <i class="fa-solid fa-chart-line w-5 text-center"></i>
                     <span class="text-sm">Dashboard</span>
                 </a>

                 {{-- Pengguna --}}
                 <a href="{{ route('admin.users.index') }}"
                     class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
                   {{ request()->routeIs('admin.users.*')
                       ? 'bg-blue-50 text-blue-700 font-bold shadow-sm border border-blue-100'
                       : 'text-slate-600 hover:bg-blue-50 hover:text-slate-800' }}"
                     data-tooltip="Pengguna">
                     <i class="fa-solid fa-users w-5 text-center"></i>
                     <span class="text-sm">Pengguna</span>
                 </a>

                 {{-- Permintaan Akun Perusahaan --}}
                 <a href="{{ route('admin.company-account-requests.index') }}"
                     class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
                   {{ request()->routeIs('admin.company-account-requests.*')
                       ? 'bg-blue-50 text-blue-700 font-bold shadow-sm border border-blue-100'
                       : 'text-slate-600 hover:bg-blue-50 hover:text-slate-800' }}"
                     data-tooltip="Permintaan Akun Perusahaan">
                     <i class="fa-solid fa-building w-5 text-center"></i>
                     <span class="text-sm">Permintaan Akun Perusahaan</span>
                 </a>

                 {{-- Kategori --}}
                 <a href="{{ route('admin.categories.index') }}"
                     class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
                   {{ request()->routeIs('admin.categories.*')
                       ? 'bg-blue-50 text-blue-700 font-bold shadow-sm border border-blue-100'
                       : 'text-slate-600 hover:bg-blue-50 hover:text-slate-800' }}"
                     data-tooltip="Kategori">
                     <i class="fa-solid fa-tags w-5 text-center"></i>
                     <span class="text-sm">Kategori</span>
                 </a>

                 {{-- Proyek --}}
                 <a href="{{ route('admin.projects.index') }}"
                     class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
                   {{ request()->routeIs('admin.projects.*')
                       ? 'bg-blue-50 text-blue-700 font-bold shadow-sm border border-blue-100'
                       : 'text-slate-600 hover:bg-blue-50 hover:text-slate-800' }}"
                     data-tooltip="Proyek">
                     <i class="fa-solid fa-folder-open w-5 text-center"></i>
                     <span class="text-sm">Proyek</span>
                 </a>

                 {{-- Penawaran --}}
                 <a href="{{ route('admin.penawarans.index') }}"
                     class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
                   {{ request()->routeIs('admin.penawarans.*')
                       ? 'bg-blue-50 text-blue-700 font-bold shadow-sm border border-blue-100'
                       : 'text-slate-600 hover:bg-blue-50 hover:text-slate-800' }}"
                     data-tooltip="Penawaran">
                     <i class="fa-solid fa-file-invoice w-5 text-center"></i>
                     <span class="text-sm">Penawaran</span>
                 </a>

                 {{-- Hasil Pekerjaan --}}
                 <a href="{{ route('admin.hasil-pekerjaan.index') }}"
                     class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
                   {{ request()->routeIs('admin.hasil-pekerjaan.*')
                       ? 'bg-blue-50 text-blue-700 font-bold shadow-sm border border-blue-100'
                       : 'text-slate-600 hover:bg-blue-50 hover:text-slate-800' }}"
                     data-tooltip="Hasil Pekerjaan">
                     <i class="fa-solid fa-layer-group w-5 text-center"></i>
                     <span class="text-sm">Hasil Pekerjaan</span>
                 </a>

                 {{-- Pembayaran --}}
                 <a href="{{ route('admin.payments.index') }}"
                     class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
                   {{ request()->routeIs('admin.payments.*')
                       ? 'bg-blue-50 text-blue-700 font-bold shadow-sm border border-blue-100'
                       : 'text-slate-600 hover:bg-blue-50 hover:text-slate-800' }}"
                     data-tooltip="Pembayaran">
                     <i class="fa-solid fa-credit-card w-5 text-center"></i>
                     <span class="text-sm">Pembayaran</span>
                 </a>

                 {{-- Laporan --}}
                 <a href="{{ route('admin.reports.index') }}"
                     class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
                   {{ request()->routeIs('admin.reports.*')
                       ? 'bg-blue-50 text-blue-700 font-bold shadow-sm border border-blue-100'
                       : 'text-slate-600 hover:bg-blue-50 hover:text-slate-800' }}"
                     data-tooltip="Laporan">
                     <i class="fa-solid fa-flag w-5 text-center"></i>
                     <span class="text-sm">Laporan</span>
                 </a>

                 {{-- Separator --}}
                 <div class="pt-4 mt-4 border-t border-blue-50"></div>

                 {{-- Back to Home --}}
                 <a href="{{ url('/') }}"
                     class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all text-slate-500 hover:bg-blue-50 hover:text-slate-700"
                     data-tooltip="Kembali ke Website">
                     <i class="fa-solid fa-globe w-5 text-center"></i>
                     <span class="text-sm">Kembali ke Website</span>
                 </a>

             </nav>

             {{-- Sidebar Footer --}}
             <div class="p-4 shrink-0 border-t border-blue-50 sidebar-footer-wrapper">
                 <div
                     class="sidebar-footer-card rounded-2xl bg-gradient-to-r from-blue-500 to-teal-500 p-4 text-white overflow-hidden transition-all duration-300">
                     <h3 class="font-bold text-sm whitespace-nowrap">ApexForge Labs</h3>
                     <p class="text-xs mt-1 opacity-90 whitespace-nowrap">Admin Panel</p>
                     <div class="mt-3 text-[10px] opacity-80 whitespace-nowrap">© 2026</div>
                 </div>
                 {{-- Collapsed footer minimal --}}
                 <div class="sidebar-footer-mini hidden">
                     <div
                         class="w-8 h-8 rounded-lg bg-gradient-to-r from-blue-500 to-teal-500 flex items-center justify-center mx-auto">
                         <i class="fa-solid fa-globe text-white text-xs"></i>
                     </div>
                 </div>
             </div>

         </aside>

         {{-- MOBILE OVERLAY --}}
         <div id="sidebarOverlay"
             class="hidden fixed inset-0 bg-black/40 z-20 lg:hidden transition-opacity duration-300"></div>

         {{-- =============== MAIN CONTENT =============== --}}
         <div class="flex-1 min-w-0 flex flex-col">

             {{-- Top Navbar --}}
             <header
                 class="h-16 bg-white border-b border-blue-100 px-6 flex items-center justify-between sticky top-0 z-20">
                 {{-- Left: Title + Breadcrumb --}}
                 <div>
                     <h1 class="text-lg font-extrabold text-slate-800">@yield('title', 'Admin Panel')</h1>
                     <nav class="flex items-center gap-1 text-xs text-slate-400 mt-0.5">
                         <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition">Admin</a>
                         <i class="fa-solid fa-chevron-right text-[9px] mx-1"></i>
                         <span class="text-slate-600 font-medium">@yield('breadcrumb', 'Dashboard')</span>
                     </nav>
                 </div>

                 {{-- Right: Notifications + Profile --}}
                 <div class="flex items-center gap-4">
                     {{-- Notifications --}}
                     <div class="relative">
                         <button id="adminNotificationButton" aria-label="Notifikasi"
                             class="relative w-10 h-10 rounded-full border border-blue-100 hover:bg-blue-50 flex items-center justify-center">
                             <i class="fa-regular fa-bell text-slate-600"></i>
                             <span id="adminNotificationBadge"
                                 class="absolute -top-1 -right-1 min-w-[18px] h-[18px] rounded-full bg-red-500 text-white text-[9px] font-bold flex items-center justify-center px-1"></span>
                         </button>

                         {{-- Dropdown Notifikasi Admin --}}
                         <div id="adminNotificationDropdown"
                             class="hidden absolute right-0 mt-3 w-[380px] bg-white rounded-2xl border border-blue-100 shadow-xl overflow-hidden z-[100]">
                             <div class="p-4 border-b border-blue-50 flex items-center justify-between">
                                 <h3 class="font-bold text-sm text-slate-800">Notifikasi</h3>
                                 <button id="adminMarkAllReadBtn"
                                     class="text-[11px] text-blue-600 font-semibold hover:underline">Tandai semua sudah
                                     dibaca</button>
                             </div>
                             <div id="adminNotificationList" class="max-h-[360px] overflow-y-auto">
                                 <div class="p-6 text-center text-sm text-slate-400">
                                     <i class="fa-regular fa-bell-slash text-xl mb-2 block"></i>
                                     Tidak ada notifikasi
                                 </div>
                             </div>
                         </div>
                     </div>

                     {{-- Profile Dropdown --}}
                     <div class="relative" x-data="{ open: false }">
                         <button onclick="toggleProfileDropdown()"
                             class="flex items-center gap-3 hover:bg-[#f6f9ff] rounded-xl px-3 py-2 transition border border-transparent hover:border-blue-50">
                             <div
                                 class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-400 to-teal-500 flex items-center justify-center text-white text-sm font-bold shadow-sm">
                                 {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                             </div>
                             <div class="text-left hidden sm:block">
                                 <p class="text-sm font-bold text-slate-800 leading-tight">
                                     {{ auth()->user()->name ?? 'Admin' }}</p>
                                 <p class="text-[10px] text-blue-600 font-semibold uppercase tracking-wider">
                                     Administrator</p>
                             </div>
                             <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                         </button>

                         {{-- Dropdown --}}
                         <div id="profileDropdown"
                             class="hidden absolute right-0 mt-2 w-56 bg-white rounded-2xl border border-blue-100 shadow-xl overflow-hidden z-50">
                             <div class="p-4 border-b border-blue-50">
                                 <p class="font-bold text-sm text-slate-800">{{ auth()->user()->name ?? 'Admin' }}</p>
                                 <p class="text-xs text-slate-500">{{ auth()->user()->email ?? '' }}</p>
                             </div>
                             <a href="{{ route('admin.dashboard') }}"
                                 class="flex items-center gap-3 px-4 py-3 text-sm text-slate-600 hover:bg-[#f6f9ff] transition">
                                 <i class="fa-solid fa-chart-line w-4 text-blue-500"></i> Dashboard
                             </a>
                             <div class="border-t border-blue-50"></div>
                             <form action="{{ route('logout') }}" method="POST">
                                 @csrf
                                 <button type="submit"
                                     class="w-full text-left flex items-center gap-3 px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition">
                                     <i class="fa-solid fa-right-from-bracket w-4"></i> Logout
                                 </button>
                             </form>
                         </div>
                     </div>
                 </div>
             </header>

             {{-- Page Content --}}
             <main class="flex-1 overflow-y-auto p-6">
                 {{-- Flash Messages --}}
                 @if (session('success'))
                     <div
                         class="flash-message mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
                         <i class="fa-regular fa-circle-check"></i> {{ session('success') }}
                     </div>
                 @endif
                 @if (session('error'))
                     <div
                         class="flash-message mb-4 bg-red-50 border border-red-200 text-red-700 px-5 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
                         <i class="fa-regular fa-circle-xmark"></i> {{ session('error') }}
                     </div>
                 @endif

                 @yield('content')
             </main>

         </div>

     </div>

     {{-- Profile Dropdown Script --}}
     <script>
         function toggleProfileDropdown() {
             const dropdown = document.getElementById('profileDropdown');
             if (dropdown) {
                 dropdown.classList.toggle('hidden');
             }
         }

         // Close dropdown when clicking outside
         document.addEventListener('click', function(e) {
             const dropdown = document.getElementById('profileDropdown');
             if (dropdown && !dropdown.classList.contains('hidden')) {
                 const button = e.target.closest('[onclick="toggleProfileDropdown()"]');
                 if (!button && !dropdown.contains(e.target)) {
                     dropdown.classList.add('hidden');
                 }
             }
         });

         // Auto-hide flash messages
         document.addEventListener('DOMContentLoaded', function() {
             const alerts = document.querySelectorAll('.flash-message');
             alerts.forEach(function(alert) {
                 setTimeout(function() {
                     alert.style.transition = 'opacity 0.5s ease';
                     alert.style.opacity = '0';
                     setTimeout(function() {
                         alert.remove();
                     }, 500);
                 }, 4000);
             });
         });

         // ============= NOTIFIKASI ADMIN =============
         document.addEventListener('DOMContentLoaded', function() {
             const notifButton = document.getElementById('adminNotificationButton');
             const notifDropdown = document.getElementById('adminNotificationDropdown');
             const notifList = document.getElementById('adminNotificationList');
             const notifBadge = document.getElementById('adminNotificationBadge');
             const markAllBtn = document.getElementById('adminMarkAllReadBtn');

             if (!notifButton) return;

             // Toggle dropdown
             notifButton.addEventListener('click', function(e) {
                 e.stopPropagation();
                 notifDropdown.classList.toggle('hidden');
                 if (!notifDropdown.classList.contains('hidden')) {
                     fetchNotifications();
                 }
             });

             // Close dropdown when clicking outside
             window.addEventListener('click', function() {
                 if (notifDropdown && !notifDropdown.classList.contains('hidden')) {
                     notifDropdown.classList.add('hidden');
                 }
             });

             function fetchNotifications() {
                 fetch('{{ route('notifications.index') }}')
                     .then(res => res.json())
                     .then(data => {
                         updateBadge(data.unread_count);
                         renderNotifications(data.notifications);
                     })
                     .catch(err => console.error('Notif fetch error:', err));
             }

             function updateBadge(count) {
                 if (count > 0) {
                     notifBadge.textContent = count > 99 ? '99+' : count;
                     notifBadge.classList.remove('hidden');
                 } else {
                     notifBadge.classList.add('hidden');
                 }
             }

             function renderNotifications(notifications) {
                 if (!notifications || notifications.length === 0) {
                     notifList.innerHTML = `
                        <div class="p-6 text-center text-sm text-slate-400">
                            <i class="fa-regular fa-bell-slash text-xl mb-2 block"></i>
                            Tidak ada notifikasi
                        </div>
                    `;
                     return;
                 }

                 let html = '';
                 notifications.forEach(notif => {
                     const isUnread = !notif.is_read;
                     const timeAgo = getTimeAgo(notif.created_at);
                     const redirectUrl = notif.data?.redirect || '#';
                     const icon = getNotifIcon(notif.type);

                     html += `
                        <div class="notification-item p-4 border-b border-slate-50 cursor-pointer hover:bg-[#f6f9ff] transition ${isUnread ? 'bg-blue-50/40' : ''}" data-url="${redirectUrl}">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center shrink-0 text-sm">
                                    <i class="${icon}"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-xs font-bold text-slate-800 ${isUnread ? '' : 'text-slate-500'}">${notif.title}</h4>
                                        ${isUnread ? '<span class="w-1.5 h-1.5 rounded-full bg-blue-500 shrink-0"></span>' : ''}
                                    </div>
                                    <p class="text-[11px] text-slate-500 mt-0.5 line-clamp-2">${notif.message}</p>
                                    <p class="text-[10px] text-slate-400 mt-1">${timeAgo}</p>
                                </div>
                            </div>
                        </div>
                    `;
                 });

                 notifList.innerHTML = html;

                 document.querySelectorAll('.notification-item').forEach(item => {
                     item.addEventListener('click', function() {
                         const url = this.dataset.url;
                         if (url && url !== '#') {
                             window.location.href = url;
                         }
                     });
                 });
             }

             function getNotifIcon(type) {
                 const iconMap = {
                     'company_request.created': 'fa-solid fa-building',
                     'payment.waiting': 'fa-solid fa-credit-card',
                     'payment.verified': 'fa-solid fa-check-circle',
                     'payment.rejected': 'fa-solid fa-times-circle',
                     'offer.sent': 'fa-solid fa-paper-plane',
                     'offer.accepted': 'fa-solid fa-check',
                     'offer.rejected': 'fa-solid fa-ban',
                     'workspace.message': 'fa-regular fa-comment-dots',
                    'submission.uploaded': 'fa-solid fa-upload',
                    'submission.accepted': 'fa-solid fa-check-double',
                    'submission.revision_requested': 'fa-solid fa-pen',
                    'report.created': 'fa-solid fa-flag',
                };
                 return iconMap[type] || 'fa-regular fa-bell';
             }

             function getTimeAgo(dateString) {
                 const now = new Date();
                 const date = new Date(dateString);
                 const diffMs = now - date;
                 const diffSec = Math.floor(diffMs / 1000);
                 const diffMin = Math.floor(diffSec / 60);
                 const diffHour = Math.floor(diffMin / 60);
                 const diffDay = Math.floor(diffHour / 24);

                 if (diffSec < 60) return 'Baru saja';
                 if (diffMin < 60) return diffMin + ' menit yang lalu';
                 if (diffHour < 24) return diffHour + ' jam yang lalu';
                 if (diffDay < 7) return diffDay + ' hari yang lalu';
                 return date.toLocaleDateString('id-ID');
             }

             // Mark all as read
             if (markAllBtn) {
                 markAllBtn.addEventListener('click', function(e) {
                     e.stopPropagation();
                     fetch('{{ route('notifications.mark-all-read') }}', {
                             method: 'POST',
                             headers: {
                                 'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                 'Content-Type': 'application/json',
                             }
                         })
                         .then(res => res.json())
                         .then(data => {
                             if (data.success) {
                                 updateBadge(0);
                                 fetchNotifications();
                             }
                         })
                         .catch(err => console.error('Mark all read error:', err));
                 });
             }

             // Initial fetch for badge count
             fetch('{{ route('notifications.index') }}')
                 .then(res => res.json())
                 .then(data => updateBadge(data.unread_count))
                 .catch(err => console.error('Notif init error:', err));

             // Polling every 60 seconds
             setInterval(function() {
                 fetch('{{ route('notifications.index') }}')
                     .then(res => res.json())
                     .then(data => updateBadge(data.unread_count))
                     .catch(() => {});
             }, 60000);
         });
     </script>

     @stack('scripts')

     {{-- Sidebar CSS & JS (same as navigasi) --}}
     <style>
         /* Sidebar base transition */
         #sidebar {
             transition: width 0.3s ease, transform 0.3s ease;
         }

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

         #sidebar.collapsed .sidebar-toggle-icon {
             transform: rotate(180deg);
         }

         #sidebar.collapsed .sidebar-logo-wrapper {
             justify-content: center;
             padding: 12px 4px;
         }

         #sidebar.collapsed .sidebar-logo-wrapper>button,
         #sidebar.collapsed .sidebar-logo-wrapper>.sidebar-logo-text {
             display: none;
         }

         #sidebar.collapsed .sidebar-logo-wrapper>.sidebar-toggle-collapsed {
             display: flex !important;
         }

         #sidebar:not(.collapsed) .sidebar-logo-wrapper>.sidebar-toggle-collapsed {
             display: none;
         }

         /* Tooltip on collapsed hover */
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
             box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
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
             from {
                 opacity: 0;
             }

             to {
                 opacity: 1;
             }
         }

         /* Mobile drawer */
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

             #sidebar.collapsed .sidebar-logo-wrapper>button,
             #sidebar.collapsed .sidebar-logo-wrapper>.sidebar-logo-text {
                 display: flex;
             }

             #sidebar.collapsed .sidebar-logo-wrapper>.sidebar-toggle-collapsed {
                 display: none !important;
             }

             #sidebar.collapsed nav a:hover::after,
             #sidebar.collapsed nav a:hover::before {
                 display: none !important;
                 content: none !important;
             }

             .sidebar-toggle-desktop {
                 display: none !important;
             }

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

     <script>
         document.addEventListener('DOMContentLoaded', function() {
             const sidebar = document.getElementById('sidebar');
             const toggleDesktop = document.getElementById('sidebarToggle');
             const toggleMobile = document.getElementById('mobileSidebarToggle');
             const overlay = document.getElementById('sidebarOverlay');

             function updateTooltipPositions() {
                 if (window.innerWidth >= 1024 && sidebar.classList.contains('collapsed')) {
                     const links = sidebar.querySelectorAll('nav a');
                     links.forEach(link => {
                         const rect = link.getBoundingClientRect();
                         link.style.setProperty('--tooltip-top', (rect.top + rect.height / 2) + 'px');
                     });
                 }
             }

             if (toggleDesktop) {
                 toggleDesktop.addEventListener('click', function(e) {
                     e.stopPropagation();
                     if (window.innerWidth >= 1024) {
                         sidebar.classList.toggle('collapsed');
                         localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed') ?
                             'true' : 'false');
                         window.dispatchEvent(new CustomEvent('sidebar-toggle', {
                             detail: {
                                 collapsed: sidebar.classList.contains('collapsed')
                             }
                         }));
                         setTimeout(updateTooltipPositions, 50);
                     }
                 });
             }

             if (toggleMobile) {
                 toggleMobile.addEventListener('click', function(e) {
                     e.stopPropagation();
                     sidebar.classList.toggle('mobile-open');
                     if (overlay) overlay.classList.toggle('hidden');
                     document.body.classList.toggle('overflow-hidden');
                 });
             }

             if (overlay) {
                 overlay.addEventListener('click', function() {
                     sidebar.classList.remove('mobile-open');
                     overlay.classList.add('hidden');
                     document.body.classList.remove('overflow-hidden');
                 });
             }

             if (window.innerWidth >= 1024) {
                 const savedState = localStorage.getItem('sidebarCollapsed');
                 if (savedState === 'true') {
                     sidebar.classList.add('collapsed');
                 }
             }

             let resizeTimer;
             window.addEventListener('resize', function() {
                 clearTimeout(resizeTimer);
                 resizeTimer = setTimeout(function() {
                     if (window.innerWidth >= 1024) {
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
                         sidebar.style.width = '';
                         sidebar.classList.remove('collapsed');
                     }
                     window.dispatchEvent(new CustomEvent('sidebar-toggle', {
                         detail: {
                             collapsed: sidebar.classList.contains('collapsed')
                         }
                     }));
                 }, 250);
             });

             document.addEventListener('keydown', function(e) {
                 if (e.key === 'Escape' && window.innerWidth < 1024) {
                     sidebar.classList.remove('mobile-open');
                     if (overlay) overlay.classList.add('hidden');
                     document.body.classList.remove('overflow-hidden');
                 }
             });

             window.addEventListener('scroll', updateTooltipPositions, {
                 passive: true
             });
             window.addEventListener('resize', updateTooltipPositions);

             if (sidebar) {
                 const observer = new MutationObserver(function() {
                     if (window.innerWidth >= 1024 && sidebar.classList.contains('collapsed')) {
                         updateTooltipPositions();
                     }
                 });
                 observer.observe(sidebar, {
                     attributes: true,
                     attributeFilter: ['class']
                 });
             }

             setTimeout(function() {
                 window.dispatchEvent(new CustomEvent('sidebar-toggle', {
                     detail: {
                         collapsed: sidebar.classList.contains('collapsed')
                     }
                 }));
             }, 100);
         });
     </script>
 </body>

 </html>
