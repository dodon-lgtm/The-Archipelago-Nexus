{{-- ApexForge Labs — Shared UI polish --}}
<header class="h-16 bg-white/90 backdrop-blur-3xl border-b border-blue-100 px-6 flex items-center justify-between sticky top-0 z-40 shadow-[0_10px_30px_-15px_rgba(59,130,246,0.1)]">

    <!-- ================= LEFT ================= -->
    <div class="flex items-center gap-8">

        <!-- Menu -->
        <nav class="hidden lg:flex items-center gap-6">
            @auth
                {{-- ================= FREELANCER ================= --}}
                @if (Auth::user()->role == 'freelancer')
                    <a href="/freelancer/dashboard" class="text-lg font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-700 to-blue-500 tracking-tight drop-shadow-[0_2px_10px_rgba(59,130,246,0.2)]">LABS</a>

                {{-- ================= COMPANY ================= --}}
                @elseif(Auth::user()->role == 'company')
                    <a href="/company/dashboard" class="text-lg font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-700 to-blue-500 tracking-tight drop-shadow-[0_2px_10px_rgba(59,130,246,0.2)]">LABS</a>

                {{-- ================= ADMIN ================= --}}
                @elseif(Auth::user()->role == 'admin')
                    <a href="{{ route('admin.dashboard') }}"
                        class="text-sm font-bold transition-all duration-300 relative group
                        {{ request()->routeIs('admin.dashboard') ? 'text-blue-700' : 'text-blue-900/50 hover:text-blue-600' }}">
                        Dashboard
                        @if(request()->routeIs('admin.dashboard')) <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-blue-500 rounded-full shadow-[0_0_8px_rgba(59,130,246,0.6)]"></span> @endif
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                        class="text-sm font-bold transition-all duration-300 relative group
                        {{ request()->routeIs('admin.users.*') ? 'text-blue-700' : 'text-blue-900/50 hover:text-blue-600' }}">
                        Pengguna
                        @if(request()->routeIs('admin.users.*')) <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-blue-500 rounded-full shadow-[0_0_8px_rgba(59,130,246,0.6)]"></span> @endif
                    </a>
                    <a href="{{ route('admin.projects.index') }}"
                        class="text-sm font-bold transition-all duration-300 relative group
                        {{ request()->routeIs('admin.projects.*') ? 'text-blue-700' : 'text-blue-900/50 hover:text-blue-600' }}">
                        Proyek
                        @if(request()->routeIs('admin.projects.*')) <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-blue-500 rounded-full shadow-[0_0_8px_rgba(59,130,246,0.6)]"></span> @endif
                    </a>
                    <a href="{{ route('admin.reports.index') }}"
                        class="text-sm font-bold transition-all duration-300 relative group
                        {{ request()->routeIs('admin.reports.*') ? 'text-blue-700' : 'text-blue-900/50 hover:text-blue-600' }}">
                        Laporan
                        @if(request()->routeIs('admin.reports.*')) <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-blue-500 rounded-full shadow-[0_0_8px_rgba(59,130,246,0.6)]"></span> @endif
                    </a>
                @endif
            @endauth
        </nav>
    </div>

    <!-- ================= RIGHT ================= -->
    <div class="flex items-center gap-5">
        <!-- NOTIF -->
        <div class="relative">
            <button id="notificationButton" aria-label="Notifikasi"
                class="relative w-10 h-10 rounded-2xl border border-blue-100 hover:bg-blue-50 hover:border-blue-200 hover:shadow-[0_0_15px_rgba(59,130,246,0.15)] flex items-center justify-center transition-all duration-300 group">
                <i class="fa-regular fa-bell text-blue-600 group-hover:animate-swing"></i>
                <span id="notificationBadge"
                    class="absolute -top-1.5 -right-1.5 min-w-[20px] h-[20px] rounded-full bg-gradient-to-r from-blue-600 to-blue-400 shadow-[0_0_10px_rgba(59,130,246,0.5)] text-white text-[10px] font-black flex items-center justify-center px-1 border-2 border-white transition-transform transform scale-0"></span>
            </button>

            <!-- Dropdown Notifikasi -->
            <div id="notificationDropdown"
                class="hidden absolute right-0 mt-4 w-[380px] bg-white/95 backdrop-blur-2xl rounded-[1.5rem] border border-blue-100 shadow-[0_20px_50px_-10px_rgba(30,58,138,0.15)] overflow-hidden z-[100] transform transition-all">
                <div class="p-5 border-b border-blue-50/50 flex items-center justify-between bg-gradient-to-b from-blue-50/30 to-transparent">
                    <h3 class="font-black text-sm text-blue-950 tracking-wide">Notifikasi Sistem</h3>
                    <button id="markAllReadBtn" class="text-[11px] text-blue-500 font-bold hover:text-blue-700 transition-colors">Tandai semua dibaca</button>
                </div>
                <div id="notificationList" class="max-h-[360px] overflow-y-auto custom-sidebar-scroll">
                    <div class="p-8 text-center text-sm text-blue-300 font-semibold">
                        <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center mx-auto mb-3 text-blue-400">
                            <i class="fa-regular fa-bell-slash text-xl"></i>
                        </div>
                        Tidak ada notifikasi aktif
                    </div>
                </div>
            </div>
        </div>

        <!-- USER -->
        <div class="relative">
            <button id="userButton" class="flex items-center gap-3 border border-transparent hover:border-blue-100 hover:bg-blue-50/50 rounded-2xl px-2 py-1.5 transition-all duration-300 group">
                <div class="text-right hidden sm:block pr-1">
                    <h2 class="text-[13px] font-black text-blue-950 leading-tight">{{ Auth::user()->name }}</h2>
                    <p class="text-[10px] font-bold tracking-widest uppercase text-blue-400">{{ ucfirst(Auth::user()->role) }}</p>
                </div>
                <div class="w-10 h-10 rounded-[0.9rem] overflow-hidden bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white shrink-0 shadow-[0_4px_15px_rgba(59,130,246,0.3)] group-hover:scale-105 transition-transform">
                    @if (Auth::user()->role == 'company' && Auth::user()->companyProfile && Auth::user()->companyProfile->company_logo)
                        <img src="{{ asset('storage/' . Auth::user()->companyProfile->company_logo) }}" alt="Logo" class="w-full h-full object-cover">
                    @elseif(Auth::user()->freelanceProfile && Auth::user()->freelanceProfile->photo)
                        <img src="{{ asset('storage/' . Auth::user()->freelanceProfile->photo) }}" alt="Profil" class="w-full h-full object-cover">
                    @else
                        <i class="fa-solid fa-user text-sm"></i>
                    @endif
                </div>
                <i class="fa-solid fa-chevron-down text-[10px] text-blue-300 group-hover:text-blue-500 transition-colors"></i>
            </button>

            <!-- Dropdown User -->
            <div id="userDropdown"
                class="hidden absolute right-0 mt-4 w-72 bg-white/95 backdrop-blur-2xl rounded-[1.5rem] border border-blue-100 shadow-[0_20px_50px_-10px_rgba(30,58,138,0.15)] overflow-hidden z-[100]">
                
                <div class="p-6 border-b border-blue-50/50 bg-gradient-to-b from-blue-50/50 to-transparent">
                    <h2 class="font-black text-blue-950 tracking-tight">{{ Auth::user()->name }}</h2>
                    <p class="text-xs font-semibold text-blue-500/70 truncate">{{ Auth::user()->email }}</p>
                </div>

                <div class="p-2 space-y-1">
                    @if (Auth::user()->role == 'freelancer')
                        <a href="{{ route('freelancer.profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-blue-900/70 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                            <i class="fa-regular fa-user text-blue-500 w-5 text-center"></i> Profil Utama
                        </a>
                        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-blue-900/70 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                            <i class="fa-regular fa-file-lines text-blue-500 w-5 text-center"></i> Lamaran Saya
                        </a>
                    @elseif(Auth::user()->role == 'company')
                        <a href="{{ route('company.profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-blue-900/70 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                            <i class="fa-regular fa-building text-blue-500 w-5 text-center"></i> Profil Perusahaan
                        </a>
                        <a href="{{ route('company.projects.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-blue-900/70 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                            <i class="fa-solid fa-plus text-blue-500 w-5 text-center"></i> Tambah Proyek
                        </a>
                    @elseif(Auth::user()->role == 'admin')
                        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-blue-900/70 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                            <i class="fa-solid fa-shield-halved text-blue-500 w-5 text-center"></i> Profil Admin
                        </a>
                        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-blue-900/70 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                            <i class="fa-solid fa-chart-pie text-blue-500 w-5 text-center"></i> Analitik Sistem
                        </a>
                    @endif

                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-blue-900/70 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                        <i class="fa-solid fa-gear text-blue-500 w-5 text-center"></i> Pengaturan
                    </a>
                </div>

                <div class="p-2 border-t border-blue-50">
                    <form action="{{ url('/logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-3 flex items-center gap-3 rounded-xl text-sm font-bold text-blue-600 hover:bg-blue-600 hover:text-white transition-colors group">
                            <i class="fa-solid fa-power-off w-5 text-center transition-transform group-hover:scale-110"></i> Logut
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<style>
    @keyframes swing {
        0% { transform: rotate(0deg); }
        25% { transform: rotate(15deg); }
        50% { transform: rotate(-10deg); }
        75% { transform: rotate(5deg); }
        100% { transform: rotate(0deg); }
    }
    .animate-swing {
        animation: swing 0.5s ease-in-out;
    }
</style>

{{-- Script untuk mengontrol Dropdown & Notifikasi --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const userButton = document.getElementById('userButton');
        const userDropdown = document.getElementById('userDropdown');

        if (userButton && userDropdown) {
            userButton.addEventListener('click', (e) => {
                e.stopPropagation();
                userDropdown.classList.toggle('hidden');
            });
        }

        window.addEventListener('click', () => {
            if (userDropdown && !userDropdown.classList.contains('hidden')) {
                userDropdown.classList.add('hidden');
            }
        });
    });

    // ============= PURE BLUE NOTIFIKASI SYSTEM =============
    document.addEventListener('DOMContentLoaded', () => {
        const notifButton = document.getElementById('notificationButton');
        const notifDropdown = document.getElementById('notificationDropdown');
        const notifList = document.getElementById('notificationList');
        const notifBadge = document.getElementById('notificationBadge');
        const markAllBtn = document.getElementById('markAllReadBtn');

        if (!notifButton) return;

        notifButton.addEventListener('click', (e) => {
            e.stopPropagation();
            notifDropdown.classList.toggle('hidden');
            if (!notifDropdown.classList.contains('hidden')) {
                fetchNotifications();
            }
        });

        window.addEventListener('click', () => {
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
                notifBadge.textContent = count;
                notifBadge.classList.remove('scale-0');
                notifBadge.classList.add('scale-100');
            } else {
                notifBadge.classList.remove('scale-100');
                notifBadge.classList.add('scale-0');
            }
        }

        function renderNotifications(notifications) {
            if (!notifications || notifications.length === 0) {
                notifList.innerHTML = `
                    <div class="p-8 text-center text-sm text-blue-300 font-semibold">
                        <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center mx-auto mb-3 text-blue-400">
                            <i class="fa-regular fa-bell-slash text-xl"></i>
                        </div>
                        Sistem bersih. Tidak ada notifikasi.
                    </div>
                `;
                return;
            }

            let html = '';
            notifications.forEach(notif => {
                const isUnread = !notif.is_read;
                const timeAgo = getTimeAgo(notif.created_at);
                const redirectUrl = notif.data?.redirect || '';
                
                // Mapped strictly to Blue/White aesthetic
                let iconClass = 'fa-solid fa-satellite-dish';
                let iconBg = 'bg-white border border-blue-100 text-blue-500 shadow-sm';
                
                if (notif.type) {
                    if (notif.type.startsWith('offer.accepted') || notif.type.startsWith('payment.verified')) {
                        iconClass = 'fa-solid fa-check-double';
                        iconBg = 'bg-blue-600 text-white shadow-[0_0_10px_rgba(37,99,235,0.4)]';
                    } else if (notif.type.startsWith('offer.rejected') || notif.type.startsWith('payment.rejected')) {
                        iconClass = 'fa-solid fa-ban';
                        iconBg = 'bg-white border-2 border-blue-200 text-blue-400';
                    } else if (notif.type.startsWith('workspace.message')) {
                        iconClass = 'fa-solid fa-comment-dots';
                        iconBg = 'bg-blue-50 border border-blue-200 text-blue-600';
                    } else if (notif.type.startsWith('submission.')) {
                        iconClass = 'fa-solid fa-cloud-arrow-up';
                        iconBg = 'bg-blue-100 text-blue-700';
                    } else if (notif.type === 'payment.waiting') {
                        iconClass = 'fa-solid fa-wallet';
                        iconBg = 'bg-white border border-blue-300 text-blue-500';
                    } else if (notif.type.startsWith('company_request') || notif.type === 'report.created') {
                        iconClass = 'fa-solid fa-shield-halved';
                        iconBg = 'bg-blue-50 text-blue-500';
                    }
                }
                
                html += `
                    <div class="notification-item p-4 border-b border-blue-50/50 cursor-pointer hover:bg-blue-50/50 transition-colors ${isUnread ? 'bg-blue-50/30' : ''}" data-id="${notif.id}" data-url="${redirectUrl}">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl ${iconBg} flex items-center justify-center shrink-0 text-sm">
                                <i class="${iconClass}"></i>
                            </div>
                            <div class="flex-1 min-w-0 pt-0.5">
                                <div class="flex items-center gap-2">
                                    <h4 class="text-[13px] font-black tracking-tight ${isUnread ? 'text-blue-950' : 'text-blue-900/60'}">${notif.title}</h4>
                                    ${isUnread ? '<span class="w-1.5 h-1.5 rounded-full bg-blue-500 shadow-[0_0_5px_rgba(59,130,246,0.8)] shrink-0 ml-auto"></span>' : ''}
                                </div>
                                <p class="text-[11px] font-medium ${isUnread ? 'text-blue-700/80' : 'text-blue-400'} mt-1 leading-relaxed line-clamp-2">${notif.message}</p>
                                <p class="text-[9px] font-bold tracking-widest uppercase text-blue-300 mt-2">${timeAgo}</p>
                            </div>
                        </div>
                    </div>
                `;
            });

            notifList.innerHTML = html;

            document.querySelectorAll('.notification-item').forEach(item => {
                item.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const url = this.dataset.url;
                    markAsRead(id, url);
                });
            });
        }

        function markAsRead(id, redirectUrl) {
            fetch('{{ url('/notifications') }}/' + id + '/read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.redirect_url) window.location.href = data.redirect_url;
                    else if (redirectUrl) window.location.href = redirectUrl;
                })
                .catch(err => console.error('Mark read error:', err));
        }

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

        function getTimeAgo(dateString) {
            const now = new Date();
            const date = new Date(dateString);
            const diffMs = now - date;
            const diffSec = Math.floor(diffMs / 1000);
            const diffMin = Math.floor(diffSec / 60);
            const diffHour = Math.floor(diffMin / 60);
            const diffDay = Math.floor(diffHour / 24);

            if (diffSec < 60) return 'TRANSMISI BARU';
            if (diffMin < 60) return diffMin + ' MENIT LALU';
            if (diffHour < 24) return diffHour + ' JAM LALU';
            if (diffDay < 7) return diffDay + ' HARI LALU';
            return date.toLocaleDateString('id-ID');
        }

        fetch('{{ route('notifications.index') }}')
            .then(res => res.json())
            .then(data => updateBadge(data.unread_count))
            .catch(err => console.error('Notif init error:', err));

        setInterval(() => {
            fetch('{{ route('notifications.index') }}')
                .then(res => res.json())
                .then(data => updateBadge(data.unread_count))
                .catch(() => {});
        }, 60000);
    });
</script>