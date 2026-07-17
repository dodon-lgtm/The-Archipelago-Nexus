<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin - The Archipelago Nexus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
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
        .chart-placeholder {
            height: 220px;
            border-radius: 12px;
            border: 1px dashed rgba(0,0,0,.2);
            background: linear-gradient(180deg, rgba(13,110,253,.05), rgba(13,110,253,.00));
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 16px;
        }
    </style>
</head>
<body class="bg-light">
<div class="d-flex">
    <!-- Sidebar -->
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
                    <span class="me-2">👥</span>Kelola Pengguna
                </a>
                <a class="nav-link fw-semibold py-2 px-2 rounded" href="#">
                    <span class="me-2">🏢</span>Kelola Perusahaan
                </a>
                <a class="nav-link fw-semibold py-2 px-2 rounded" href="#">
                    <span class="me-2">🏷️</span>Kelola Kategori
                </a>
                <a class="nav-link fw-semibold py-2 px-2 rounded" href="#">
                    <span class="me-2">📁</span>Kelola Proyek
                </a>
                <a class="nav-link fw-semibold py-2 px-2 rounded" href="#">
                    <span class="me-2">📊</span>Laporan
                </a>
                <div class="mt-2">
                    <a class="nav-link fw-semibold py-2 px-2 rounded text-danger" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form-admin').submit();">
                        <span class="me-2">🚪</span>Logout
                    </a>
                    <form id="logout-form-admin" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </nav>
        </div>
    </aside>

    <!-- Main -->
    <div class="flex-grow-1">
        <header class="topbar">
            <div class="container-fluid px-3 px-lg-4 py-2">
                <div class="row align-items-center g-2">
                    <div class="col-12 col-lg-7">
                        <div class="input-group">
                            <span class="input-group-text" style="background:#fff;">🔎</span>
                            <input type="text" class="form-control" placeholder="Cari...">
                        </div>
                    </div>
                    <div class="col-12 col-lg-5 d-flex justify-content-end align-items-center gap-2">
                        <button class="btn btn-light border" type="button" aria-label="Notifikasi">🔔</button>
                        <div class="d-flex align-items-center gap-2">
                            <div class="profile-avatar">{{ auth()->user()->name ? strtoupper(substr(auth()->user()->name,0,1)) : 'A' }}</div>
                            <div class="d-none d-md-block">
                                <div class="fw-semibold" style="line-height:1.1;">{{ auth()->user()->name ?? 'Admin' }}</div>
                                <div class="text-muted" style="font-size:.85rem;">admin</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="container-fluid py-4 px-3 px-lg-4">
            <!-- Ringkasan Sistem -->
            <div class="mb-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h1 class="h4 mb-0">Ringkasan Sistem</h1>
                        <p class="text-muted mb-0">Dummy data placeholder untuk visual dashboard.</p>
                    </div>
                    <div class="badge text-bg-primary">Admin</div>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="text-muted">Total User</div>
                            <div class="fs-4 fw-bold">420</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="text-muted">Total Freelancer</div>
                            <div class="fs-4 fw-bold">260</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="text-muted">Total Company</div>
                            <div class="fs-4 fw-bold">95</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="text-muted">Total Project</div>
                            <div class="fs-4 fw-bold">58</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <!-- Placeholder chart + aktivitas -->
                <div class="col-12 col-lg-7">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <h2 class="h6 mb-0">Grafik Statistik</h2>
                                <span class="badge text-bg-secondary">Placeholder</span>
                            </div>
                            <div class="chart-placeholder mt-3">
                                <div>
                                    <div class="fw-semibold mb-1">Chart akan di-render dari backend</div>
                                    <div class="text-muted" style="font-size:.95rem;">Gunakan chart library jika diperlukan di tahap berikutnya.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <h2 class="h6 mb-0">Aktivitas Terbaru Admin</h2>
                                <a href="#" class="text-decoration-none">Lihat semua →</a>
                            </div>

                            <div class="table-responsive mt-3">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                    <tr>
                                        <th>Waktu</th>
                                        <th>Aksi</th>
                                        <th>Entitas</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach(range(1,5) as $n)
                                        <tr>
                                            <td class="text-muted">{{ now()->subMinutes($n*12)->format('d M Y H:i') }}</td>
                                            <td><span class="fw-semibold">Update</span> Pengajuan</td>
                                            <td class="text-muted">CompanyAccountRequest #{{ 100+$n }}</td>
                                            <td>
                                                <span class="badge text-bg-success">Berhasil</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3 d-flex justify-content-end">
                                <nav aria-label="Pagination">
                                    <ul class="pagination pagination-sm mb-0">
                                        <li class="page-item disabled"><span class="page-link">Previous</span></li>
                                        <li class="page-item active"><span class="page-link">1</span></li>
                                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        <li class="page-item"><a class="page-link" href="#">Next</a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Requests terbaru -->
                <div class="col-12 col-lg-5">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <h2 class="h6 mb-0">Permintaan Akun Perusahaan Terbaru</h2>
                                <span class="badge text-bg-warning">Menunggu</span>
                            </div>

                            <div class="list-group list-group-flush mt-3">
                                @foreach(range(1,4) as $n)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between gap-3">
                                            <div>
                                                <div class="fw-semibold">PT Nusantara {{ $n }}</div>
                                                <div class="text-muted" style="font-size:.9rem;">contact{{ $n }}@company.test</div>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge text-bg-warning">menunggu</span>
                                                <div class="text-muted" style="font-size:.85rem; margin-top:4px;">{{ now()->subDays($n)->format('d M') }}</div>
                                            </div>
                                        </div>
                                        <div class="mt-2 d-flex gap-2">
                                            <a href="#" class="btn btn-outline-primary btn-sm">Detail</a>
                                            <button class="btn btn-success btn-sm" type="button">Setujui</button>
                                            <button class="btn btn-danger btn-sm" type="button">Tolak</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-3 d-flex justify-content-between align-items-center">
                                <a href="{{ route('admin.company-account-requests.index') }}" class="text-decoration-none">Lihat semua →</a>
                                <span class="text-muted" style="font-size:.9rem;">Dummy actions</span>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm mt-3">
                        <div class="card-body">
                            <h2 class="h6">Catatan</h2>
                            <p class="text-muted mb-0" style="font-size:.95rem;">
                                Halaman ini hanya tampilan. Nanti data & aksi akan dihubungkan ke backend.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

