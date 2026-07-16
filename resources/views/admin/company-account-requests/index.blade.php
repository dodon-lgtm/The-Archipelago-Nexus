<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Permintaan Akun Perusahaan - The Archipelago Nexus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 m-0">Permintaan Akun Perusahaan - The Archipelago Nexus</h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ url()->current() }}" class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label">Filter status</label>
                    <select class="form-select" name="status">
                        <option value="menunggu" @selected($status==='menunggu')>Menunggu</option>
                        <option value="disetujui" @selected($status==='disetujui')>Disetujui</option>
                        <option value="ditolak" @selected($status==='ditolak')>Ditolak</option>
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <button class="btn btn-primary w-100" type="submit">Terapkan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>Nama Perusahaan</th>
                        <th>Contact Person</th>
                        <th>Email</th>
                        <th>No. Telepon</th>
                        <th>Status</th>
                        <th>Tanggal Permintaan</th>
                        <th style="min-width: 260px;">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($companyRequests as $r)
                        <tr>
                            <td>{{ $r->company_name }}</td>
                            <td>{{ $r->contact_person }}</td>
                            <td>{{ $r->company_email }}</td>
                            <td>{{ $r->company_phone }}</td>
                            <td>
                                @if($r->request_status==='menunggu')
                                    <span class="badge text-bg-warning">menunggu</span>
                                @elseif($r->request_status==='disetujui')
                                    <span class="badge text-bg-success">disetujui</span>
                                @else
                                    <span class="badge text-bg-danger">ditolak</span>
                                @endif
                            </td>
                            <td>{{ $r->created_at?->format('Y-m-d') }}</td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <a class="btn btn-outline-primary btn-sm" href="{{ route('admin.company-account-requests.show', $r) }}">Lihat Detail</a>

                                    @if($r->request_status==='menunggu')
                                        <form method="POST" action="{{ route('admin.company-account-requests.approve', $r) }}">
                                            @csrf
                                            <input type="hidden" name="_method" value="POST">
                                            <button class="btn btn-success btn-sm" type="submit">Setujui</button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.company-account-requests.reject', $r) }}">
                                            @csrf
                                            <input type="hidden" name="note" value="Tolak oleh admin">
                                            <button class="btn btn-danger btn-sm" type="submit">Tolak</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Tidak ada data.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $companyRequests->links() }}
    </div>
</div>
</body>
</html>

