<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - The Archipelago Nexus</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

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
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

    <div class="min-h-screen bg-slate-50 flex">

        {{-- =============== SIDEBAR =============== --}}
        <aside class="w-64 bg-white border-r border-slate-200 flex flex-col h-screen sticky top-0 shrink-0 z-30">

            {{-- Logo --}}
            <div class="p-6 flex items-center gap-3 border-b border-slate-100 shrink-0">
                <div class="w-10 h-10 rounded-full overflow-hidden shrink-0">
                    <img src="{{ asset('images/nexus.jpg') }}" alt="Nexus Logo" class="w-full h-full object-cover">
                </div>
                <div>
                    <h2 class="font-extrabold text-sm leading-tight text-slate-800">The Archipelago<br>Nexus</h2>
                </div>
            </div>

            {{-- Menu --}}
            <nav class="mt-5 px-3 space-y-1 flex-1 overflow-y-auto sidebar-scroll">

                {{-- Dashboard --}}
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
                   {{ request()->routeIs('admin.dashboard')
                        ? 'bg-cyan-50 text-cyan-700 font-bold shadow-sm border border-cyan-100'
                        : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800' }}">
                    <i class="fa-solid fa-chart-line w-5 text-center"></i>
                    <span class="text-sm">Dashboard</span>
                </a>

                {{-- Pengguna --}}
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
                   {{ request()->routeIs('admin.users.*')
                        ? 'bg-cyan-50 text-cyan-700 font-bold shadow-sm border border-cyan-100'
                        : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800' }}">
                    <i class="fa-solid fa-users w-5 text-center"></i>
                    <span class="text-sm">Pengguna</span>
                </a>

                {{-- Permintaan Akun Perusahaan --}}
                <a href="{{ route('admin.company-account-requests.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
                   {{ request()->routeIs('admin.company-account-requests.*')
                        ? 'bg-cyan-50 text-cyan-700 font-bold shadow-sm border border-cyan-100'
                        : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800' }}">
                    <i class="fa-solid fa-building w-5 text-center"></i>
                    <span class="text-sm">Permintaan Akun Perusahaan</span>
                </a>

                {{-- Kategori --}}
                <a href="{{ route('admin.categories.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
                   {{ request()->routeIs('admin.categories.*')
                        ? 'bg-cyan-50 text-cyan-700 font-bold shadow-sm border border-cyan-100'
                        : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800' }}">
                    <i class="fa-solid fa-tags w-5 text-center"></i>
                    <span class="text-sm">Kategori</span>
                </a>

                {{-- Proyek --}}
                <a href="{{ route('admin.projects.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
                   {{ request()->routeIs('admin.projects.*')
                        ? 'bg-cyan-50 text-cyan-700 font-bold shadow-sm border border-cyan-100'
                        : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800' }}">
                    <i class="fa-solid fa-folder-open w-5 text-center"></i>
                    <span class="text-sm">Proyek</span>
                </a>

                {{-- Penawaran --}}
                <a href="{{ route('admin.penawarans.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
                   {{ request()->routeIs('admin.penawarans.*')
                        ? 'bg-cyan-50 text-cyan-700 font-bold shadow-sm border border-cyan-100'
                        : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800' }}">
                    <i class="fa-solid fa-file-invoice w-5 text-center"></i>
                    <span class="text-sm">Penawaran</span>
                </a>

                {{-- Hasil Pekerjaan --}}
                <a href="{{ route('admin.hasil-pekerjaan.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
                   {{ request()->routeIs('admin.hasil-pekerjaan.*')
                        ? 'bg-cyan-50 text-cyan-700 font-bold shadow-sm border border-cyan-100'
                        : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800' }}">
                    <i class="fa-solid fa-layer-group w-5 text-center"></i>
                    <span class="text-sm">Hasil Pekerjaan</span>
                </a>

                {{-- Pembayaran --}}
                <a href="{{ route('admin.payments.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
                   {{ request()->routeIs('admin.payments.*')
                        ? 'bg-cyan-50 text-cyan-700 font-bold shadow-sm border border-cyan-100'
                        : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800' }}">
                    <i class="fa-solid fa-credit-card w-5 text-center"></i>
                    <span class="text-sm">Pembayaran</span>
                </a>

                {{-- Laporan --}}
                <a href="{{ route('admin.reports.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
                   {{ request()->routeIs('admin.reports.*')
                        ? 'bg-cyan-50 text-cyan-700 font-bold shadow-sm border border-cyan-100'
                        : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800' }}">
                    <i class="fa-solid fa-flag w-5 text-center"></i>
                    <span class="text-sm">Laporan</span>
                </a>

                {{-- Separator --}}
                <div class="pt-4 mt-4 border-t border-slate-100"></div>

                {{-- Back to Home --}}
                <a href="{{ url('/') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all text-slate-500 hover:bg-slate-100 hover:text-slate-700">
                    <i class="fa-solid fa-globe w-5 text-center"></i>
                    <span class="text-sm">Kembali ke Website</span>
                </a>

            </nav>

            {{-- Sidebar Footer --}}
            <div class="p-4 shrink-0 border-t border-slate-100">
                <div class="rounded-2xl bg-gradient-to-r from-cyan-500 to-teal-500 p-4 text-white">
                    <h3 class="font-bold text-sm">The Archipelago Nexus</h3>
                    <p class="text-xs mt-1 opacity-90">Admin Panel</p>
                    <div class="mt-3 text-[10px] opacity-80">© 2026</div>
                </div>
            </div>

        </aside>

        {{-- =============== MAIN CONTENT =============== --}}
        <div class="flex-1 min-w-0 flex flex-col">

            {{-- Top Navbar --}}
            <header class="h-16 bg-white border-b border-slate-200 px-6 flex items-center justify-between sticky top-0 z-20">
                {{-- Left: Title + Breadcrumb --}}
                <div>
                    <h1 class="text-lg font-extrabold text-slate-800">@yield('title', 'Admin Panel')</h1>
                    <nav class="flex items-center gap-1 text-xs text-slate-400 mt-0.5">
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-cyan-600 transition">Admin</a>
                        <i class="fa-solid fa-chevron-right text-[9px] mx-1"></i>
                        <span class="text-slate-600 font-medium">@yield('breadcrumb', 'Dashboard')</span>
                    </nav>
                </div>

                {{-- Right: Notifications + Profile --}}
                <div class="flex items-center gap-4">
                    {{-- Notifications --}}
                    <div class="relative">
                        <button id="adminNotificationButton" class="relative w-10 h-10 rounded-full border border-slate-200 hover:bg-slate-100 flex items-center justify-center transition">
                            <i class="fa-regular fa-bell text-slate-500"></i>
                            <span id="adminNotificationBadge" class="absolute -top-0.5 -right-0.5 min-w-[16px] h-4 rounded-full bg-red-500 text-white text-[8px] font-bold flex items-center justify-center px-1 hidden"></span>
                        </button>

                        {{-- Dropdown Notifikasi Admin --}}
                        <div id="adminNotificationDropdown" class="hidden absolute right-0 mt-3 w-[380px] bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden z-[100]">
                            <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                                <h3 class="font-bold text-sm text-slate-800">Notifikasi</h3>
                                <button id="adminMarkAllReadBtn" class="text-[11px] text-cyan-600 font-semibold hover:underline">Tandai semua sudah dibaca</button>
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
                        <button onclick="toggleProfileDropdown()" class="flex items-center gap-3 hover:bg-slate-50 rounded-xl px-3 py-2 transition border border-transparent hover:border-slate-100">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-cyan-400 to-teal-500 flex items-center justify-center text-white text-sm font-bold shadow-sm">
                                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                            </div>
                            <div class="text-left hidden sm:block">
                                <p class="text-sm font-bold text-slate-800 leading-tight">{{ auth()->user()->name ?? 'Admin' }}</p>
                                <p class="text-[10px] text-cyan-600 font-semibold uppercase tracking-wider">Administrator</p>
                            </div>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                        </button>

                        {{-- Dropdown --}}
                        <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden z-50">
                            <div class="p-4 border-b border-slate-100">
                                <p class="font-bold text-sm text-slate-800">{{ auth()->user()->name ?? 'Admin' }}</p>
                                <p class="text-xs text-slate-500">{{ auth()->user()->email ?? '' }}</p>
                            </div>
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-600 hover:bg-slate-50 transition">
                                <i class="fa-solid fa-chart-line w-4 text-cyan-500"></i> Dashboard
                            </a>
                            <div class="border-t border-slate-100"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center gap-3 px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition">
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
                    <div class="flash-message mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
                        <i class="fa-regular fa-circle-check"></i> {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="flash-message mb-4 bg-red-50 border border-red-200 text-red-700 px-5 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
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
        document.addEventListener('DOMContentLoaded', function () {
            const alerts = document.querySelectorAll('.flash-message');
            alerts.forEach(function (alert) {
                setTimeout(function () {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(function () {
                        alert.remove();
                    }, 500);
                }, 4000);
            });
        });

        // ============= NOTIFIKASI ADMIN =============
        document.addEventListener('DOMContentLoaded', function () {
            const notifButton = document.getElementById('adminNotificationButton');
            const notifDropdown = document.getElementById('adminNotificationDropdown');
            const notifList = document.getElementById('adminNotificationList');
            const notifBadge = document.getElementById('adminNotificationBadge');
            const markAllBtn = document.getElementById('adminMarkAllReadBtn');

            if (!notifButton) return;

            // Toggle dropdown
            notifButton.addEventListener('click', function (e) {
                e.stopPropagation();
                notifDropdown.classList.toggle('hidden');
                if (!notifDropdown.classList.contains('hidden')) {
                    fetchNotifications();
                }
            });

            // Close dropdown when clicking outside
            window.addEventListener('click', function () {
                if (notifDropdown && !notifDropdown.classList.contains('hidden')) {
                    notifDropdown.classList.add('hidden');
                }
            });

            function fetchNotifications() {
                fetch('{{ route("notifications.index") }}')
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
                        <div class="notification-item p-4 border-b border-slate-50 cursor-pointer hover:bg-slate-50 transition ${isUnread ? 'bg-blue-50/40' : ''}" data-url="${redirectUrl}">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center shrink-0 text-sm">
                                    <i class="${icon}"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-xs font-bold text-slate-800 ${isUnread ? '' : 'text-slate-500'}">${notif.title}</h4>
                                        ${isUnread ? '<span class="w-1.5 h-1.5 rounded-full bg-cyan-500 shrink-0"></span>' : ''}
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
                    item.addEventListener('click', function () {
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
                markAllBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    fetch('{{ route("notifications.mark-all-read") }}', {
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
            fetch('{{ route("notifications.index") }}')
                .then(res => res.json())
                .then(data => updateBadge(data.unread_count))
                .catch(err => console.error('Notif init error:', err));

            // Polling every 60 seconds
            setInterval(function () {
                fetch('{{ route("notifications.index") }}')
                    .then(res => res.json())
                    .then(data => updateBadge(data.unread_count))
                    .catch(() => {});
            }, 60000);
        });
    </script>

    @stack('scripts')
</body>
</html>

