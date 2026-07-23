<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $project->project_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4" style="max-width: 960px;">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">{{ $project->project_name }}</h1>
        <a class="btn btn-outline-secondary" href="{{ route('company.projects.index') }}">Kembali</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Detail Project --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="mb-3">
                <div class="text-muted">Deskripsi</div>
                <div>{{ $project->project_description ? nl2br(e($project->project_description)) : '-' }}</div>
            </div>

            <div class="d-flex gap-2">
                <a class="btn btn-primary" href="{{ route('company.projects.edit', $project) }}">Edit</a>

                <form method="POST" action="{{ route('company.projects.destroy', $project) }}" onsubmit="return confirm('Hapus proyek ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Daftar Penawaran Freelancer --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3">Daftar Penawaran Freelancer</h5>

            @if ($project->penawarans->isEmpty())
                <p class="text-muted mb-0">Belum ada penawaran untuk proyek ini.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Freelancer</th>
                                <th>Harga Penawaran</th>
                                <th>Estimasi (Hari)</th>
                                <th>Pesan</th>
                                <th>Proposal</th>
                                <th>Status</th>
                                <th>Waktu Dipilih</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($project->penawarans as $penawaran)
                                <tr>
                                    <td>{{ $penawaran->freelancer->name ?? 'Tidak diketahui' }}</td>
                                    <td>Rp {{ number_format($penawaran->harga_penawaran, 0, ',', '.') }}</td>
                                    <td>{{ $penawaran->estimasi_hari }} hari</td>
                                    <td style="max-width: 200px;">
                                        <span class="d-inline-block text-truncate" style="max-width: 180px;" title="{{ e($penawaran->pesan) }}">
                                            {{ $penawaran->pesan }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($penawaran->proposal)
                                            <a href="{{ asset('storage/' . $penawaran->proposal) }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat</a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($penawaran->status === 'Menunggu')
                                            <span class="badge bg-warning text-dark">Menunggu</span>
                                        @elseif ($penawaran->status === 'Diterima')
                                            <span class="badge bg-success">Diterima</span>
                                        @else
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($penawaran->selected_at)
{{ $penawaran->selected_at->format('d M Y H:i') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
<td>
                                        @php
                                            $hasAccepted = $project->penawarans->contains(fn($p) => $p->status === 'Diterima');
                                        @endphp
                                        @if ($penawaran->status === 'Menunggu' && !$hasAccepted)
                                            <form method="POST"
                                                action="{{ route('company.projects.penawaran.select', [$project, $penawaran]) }}"
                                                onsubmit="return confirm('Pilih freelancer ini? Penawaran lain akan otomatis ditolak.');">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">Pilih Freelancer</button>
                                            </form>
                                        @elseif ($penawaran->status === 'Diterima')
                                            <span class="badge bg-success">Freelancer Terpilih</span>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

</div>
</body>
</html>

