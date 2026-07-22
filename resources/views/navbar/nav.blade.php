<header class="h-16 bg-white border-b border-slate-200 px-6 flex items-center justify-between">

    <!-- ================= LEFT ================= -->
    <div class="flex items-center gap-8">
        <a href="/freelancer/dashboard" class="text-lg font-black text-cyan-600">NEXUS</a>

        <!-- Menu -->
        <nav class="hidden lg:flex items-center gap-6">
            @auth
                {{-- ================= FREELANCER ================= --}}
                @if(Auth::user()->role == 'freelancer')
                    {{-- <a href="{{ route('freelancer.dashboard') }}" class="text-sm font-semibold hover:text-cyan-600 transition">Home</a> --}}
                    {{-- <a href="#" class="text-sm text-slate-600 hover:text-cyan-600 transition">Lamaran</a>
                    <a href="#" class="text-sm text-slate-600 hover:text-cyan-600 transition">Tersimpan</a> --}}
                
                {{-- ================= COMPANY ================= --}}
                @elseif(Auth::user()->role == 'company')
                    <a href="{{ route('company.dashboard') }}" class="text-sm font-semibold hover:text-cyan-600 transition">Dashboard</a>
                    <a href="{{ route('company.projects.create') }}" class="text-sm text-slate-600 hover:text-cyan-600 transition">Tambah Proyek</a>
                    <a href="{{ route('company.projects.index') }}" class="text-sm text-slate-600 hover:text-cyan-600 transition">Proyek Saya</a>
                
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
        <div class="relative">
            <button id="notificationButton" class="relative w-10 h-10 rounded-full border border-slate-200 hover:bg-slate-100 flex items-center justify-center">
                <i class="fa-regular fa-bell text-slate-600"></i>
                <span id="notificationBadge" class="absolute -top-1 -right-1 min-w-[18px] h-[18px] rounded-full bg-red-500 text-white text-[9px] font-bold flex items-center justify-center px-1 hidden"></span>
            </button>

            <!-- Dropdown Notifikasi -->
            <div id="notificationDropdown" class="hidden absolute right-0 mt-3 w-[380px] bg-white rounded-2xl border shadow-xl overflow-hidden z-[100]">
                <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-bold text-sm text-slate-800">Notifikasi</h3>
                    <button id="markAllReadBtn" class="text-[11px] text-brand font-semibold hover:underline">Tandai semua sudah dibaca</button>
                </div>
                <div id="notificationList" class="max-h-[360px] overflow-y-auto">
                    <div class="p-6 text-center text-sm text-slate-400">
                        <i class="fa-regular fa-bell-slash text-xl mb-2 block"></i>
                        Tidak ada notifikasi
                    </div>
                </div>
            </div>
        </div>

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
                    <a href="{{ route('company.projects.create') }}" class="flex items-center gap-3 px-5 py-3 hover:bg-slate-50"><i class="fa-solid fa-plus"></i> Tambah Proyek</a>
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

    // ============= NOTIFIKASI SYSTEM =============
    document.addEventListener('DOMContentLoaded', () => {
        const notifButton = document.getElementById('notificationButton');
        const notifDropdown = document.getElementById('notificationDropdown');
        const notifList = document.getElementById('notificationList');
        const notifBadge = document.getElementById('notificationBadge');
        const markAllBtn = document.getElementById('markAllReadBtn');

        if (!notifButton) return;

        // Toggle dropdown
        notifButton.addEventListener('click', (e) => {
            e.stopPropagation();
            notifDropdown.classList.toggle('hidden');
            if (!notifDropdown.classList.contains('hidden')) {
                fetchNotifications();
            }
        });

        // Close dropdown when clicking outside
        window.addEventListener('click', (e) => {
            if (!notifDropdown.classList.contains('hidden')) {
                notifDropdown.classList.add('hidden');
            }
        });

        // Fetch notifications from API
        function fetchNotifications() {
            fetch('{{ route("notifications.index") }}')
                .then(res => res.json())
                .then(data => {
                    updateBadge(data.unread_count);
                    renderNotifications(data.notifications);
                })
                .catch(err => console.error('Notif fetch error:', err));
        }

        // Update badge count
        function updateBadge(count) {
            if (count > 0) {
                notifBadge.textContent = count;
                notifBadge.classList.remove('hidden');
            } else {
                notifBadge.classList.add('hidden');
            }
        }

        // Render notification items
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
                html += `
                    <div class="notification-item p-4 border-b border-slate-50 cursor-pointer hover:bg-slate-50 transition ${isUnread ? 'bg-blue-50/40' : ''}" data-id="${notif.id}" data-url="{{ url('/company/projects') }}/${notif.penawaran?.project_id || ''}">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-brand/10 text-brand flex items-center justify-center shrink-0 text-sm">
                                <i class="fa-solid fa-paper-plane"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <h4 class="text-xs font-bold text-slate-800 ${isUnread ? '' : 'text-slate-500'}">${notif.title}</h4>
                                    ${isUnread ? '<span class="w-1.5 h-1.5 rounded-full bg-brand shrink-0"></span>' : ''}
                                </div>
                                <p class="text-[11px] text-slate-500 mt-0.5 line-clamp-2">${notif.message}</p>
                                <p class="text-[10px] text-slate-400 mt-1">${timeAgo}</p>
                            </div>
                        </div>
                    </div>
                `;
            });

            notifList.innerHTML = html;

            // Add click event to each notification
            document.querySelectorAll('.notification-item').forEach(item => {
                item.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const url = this.dataset.url;
                    markAsRead(id, url);
                });
            });
        }

        // Mark single notification as read
        function markAsRead(id, redirectUrl) {
            fetch('{{ url("/notifications") }}/' + id + '/read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else if (redirectUrl) {
                    window.location.href = redirectUrl;
                }
            })
            .catch(err => console.error('Mark read error:', err));
        }

        // Mark all as read
        if (markAllBtn) {
            markAllBtn.addEventListener('click', function(e) {
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

        // Helper: time ago
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

        // Auto fetch badge count on page load
        fetch('{{ route("notifications.index") }}')
            .then(res => res.json())
            .then(data => updateBadge(data.unread_count))
            .catch(err => console.error('Notif init error:', err));

        // Refresh badge every 30 seconds
        setInterval(() => {
            fetch('{{ route("notifications.index") }}')
                .then(res => res.json())
                .then(data => updateBadge(data.unread_count))
                .catch(() => {});
        }, 30000);
    });
</script>
