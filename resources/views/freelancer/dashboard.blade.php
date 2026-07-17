<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Freelancer - The Archipelago Nexus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Minimal extra CSS to support layout */
        .sidebar {
            min-height: 100vh;
            border-right: 1px solid rgba(0,0,0,.08);
        }
        .brand-logo {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #111;
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            letter-spacing: .5px;
        }
        .nav-link {
            color: rgba(0,0,0,.7);
        }
        .nav-link:hover {
            color: rgba(0,0,0,.95);
        }
        .topbar {
            position: sticky;
            top: 0;
            z-index: 1020;
            background: rgba(255,255,255,.9);
            backdrop-filter: blur(6px);
            border-bottom: 1px solid rgba(0,0,0,.08);
        }
        .profile-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #f1f1f1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #333;
        }
    </style>
</head>
<body class="bg-light">
<div class="d-flex">
    <!-- Sidebar (left) -->
    <aside class="sidebar bg-white col-12 col-lg-2 d-none d-lg-block">
        <div class="p-3">
            <div class="d-flex align-items-center gap-2 mb-3">
                <div class="brand-logo">A N</div>
                <div>
                    <div class="fw-bold">The Archipelago</div>
                    <div class="text-muted" style="font-size:.85rem;">Nexus</div>
                </div>
            </div>

            <nav class="nav flex-column gap-1">
                <a class="nav-link fw-semibold py-2 px-2 rounded" href="#">
                    <span class="me-2">🏠</span>Dashboard
                </a>
                <a class="nav-link fw-semibold py-2 px-2 rounded" href="#">
                    <span class="me-2">🔎</span>Cari Proyek
                </a>
                <a class="nav-link fw-semibold py-2 px-2 rounded" href="#">
                    <span class="me-2">📁</span>Proyek Saya
                </a>
                <a class="nav-link fw-semibold py-2 px-2 rounded" href="#">
                    <span class="me-2">💼</span>Penawaran Saya
                </a>
                <a class="nav-link fw-semibold py-2 px-2 rounded" href="#">
                    <span class="me-2">👤</span>Profil
                </a>
                <div class="mt-2">
                    <a class="nav-link fw-semibold py-2 px-2 rounded text-danger" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <span class="me-2">🚪</span>Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </nav>
        </div>
    </aside>

    <!-- Main content -->
    <div class="flex-grow-1">
        <!-- Topbar (navbar top) -->
        <header class="topbar">
            <div class="container-fluid px-3 px-lg-4 py-2">
                <div class="row align-items-center g-2">
                    <div class="col-12 col-lg-6">
                        <div class="input-group">
                            <span class="input-group-text" style="background:#fff;">🔎</span>
                            <input type="text" class="form-control" placeholder="Cari proyek...">
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 d-flex justify-content-end align-items-center gap-2">
                        <button class="btn btn-light border" type="button" aria-label="Notifikasi">
                            🔔
                        </button>
                        <div class="d-flex align-items-center gap-2">
                            <div class="profile-avatar">
                                {{ auth()->user()->name ? strtoupper(substr(auth()->user()->name,0,1)) : 'F' }}
                            </div>
                            <div class="d-none d-md-block">
                                <div class="fw-semibold" style="line-height:1.1;">{{ auth()->user()->name ?? 'Freelancer' }}</div>
                                <div class="text-muted" style="font-size:.85rem;">freelancer</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="container-fluid py-4 px-3 px-lg-4">
            <div class="row g-3">
                <!-- Content left -->
                <section class="col-12 col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
                                <div>
                                    <h2 class="h5 mb-1">Selamat datang!</h2>
                                    <p class="text-muted mb-0">Berikut ringkasan aktivitas Anda sebagai freelancer.</p>
                                </div>
                                <div class="badge text-bg-primary">Dummy Data</div>
                            </div>

                            <hr>

                            <div class="row g-3">
                                <div class="col-12 col-md-6 col-xl-3">
                                    <div class="p-3 bg-white border rounded">
                                        <div class="text-muted">Total Penawaran</div>
                                        <div class="fs-4 fw-bold">128</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-xl-3">
                                    <div class="p-3 bg-white border rounded">
                                        <div class="text-muted">Proyek Dilamar</div>
                                        <div class="fs-4 fw-bold">56</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-xl-3">
                                    <div class="p-3 bg-white border rounded">
                                        <div class="text-muted">Proyek Diterima</div>
                                        <div class="fs-4 fw-bold">19</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-xl-3">
                                    <div class="p-3 bg-white border rounded">
                                        <div class="text-muted">Total Pendapatan</div>
                                        <div class="fs-4 fw-bold">Rp 12.450.000</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h3 class="h6 mb-0">Daftar Proyek Terbaru</h3>
                            <a href="#" class="text-decoration-none">Lihat semua →</a>
                        </div>

                        <div class="row g-3">
                            @for($i=1;$i<=3;$i++)
                                <div class="col-12">
                                    <div class="card shadow-sm">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                                <div>
                                                    <div class="fw-bold h6 mb-1">Project {{ $i }}: Desain & Implementasi Sistem</div>
                                                    <div class="text-muted" style="font-size:.95rem;">
                                                        Kategori: <span class="fw-semibold">Software</span>
                                                    </div>
                                                    <div class="text-muted" style="font-size:.95rem;">
                                                        Budget: <span class="fw-semibold">Rp {{ number_format(5000000*$i,0,',','.') }}</span>
                                                    </div>
                                                    <div class="text-muted" style="font-size:.95rem;">
                                                        Deadline: <span class="fw-semibold">{{ now()->addDays($i*7)->format('d M Y') }}</span>
                                                    </div>
                                                </div>
                                                <div>
                                                    <a href="{{ route('freelancer.projects.index') }}" class="btn btn-outline-primary">
                                                        Lihat Detail
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </section>

                <!-- Sidebar kanan -->
                <aside class="col-12 col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <h3 class="h6 mb-0">Notifikasi Terbaru</h3>
                                <span class="badge text-bg-secondary">3</span>
                            </div>
                            <hr>
                            <div class="list-group list-group-flush">
                                @foreach(range(1,3) as $n)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between gap-3">
                                            <div>
                                                <div class="fw-semibold">Penawaran Anda diperbarui</div>
                                                <div class="text-muted" style="font-size:.9rem;">{{ now()->subHours($n*4)->format('d M Y H:i') }}</div>
                                            </div>
                                            <span class="badge text-bg-warning align-self-start">New</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm mt-3">
                        <div class="card-body">
                            <h3 class="h6">Tips Cepat</h3>
                            <ul class="text-muted mb-0" style="font-size:.95rem;">
                                <li>Pastikan profil Anda lengkap.</li>
                                <li>Respons cepat meningkatkan peluang diterima.</li>
                                <li>Gunakan pencarian untuk menemukan proyek sesuai skill.</li>
                            </ul>
                        </div>
                    </div>
                </aside>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

