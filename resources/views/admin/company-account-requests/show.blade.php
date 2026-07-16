<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Permintaan Akun Perusahaan - The Archipelago Nexus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="mb-3">
        <a href="{{ route('admin.company-account-requests.index') }}" class="btn btn-outline-secondary btn-sm">&larr; Kembali</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h2 class="h5 mb-3">Data Permintaan</h2>
            <dl class="row mb-0">
                <dt class="col-12 col-md-4">Nama perusahaan</dt>
                <dd class="col-12 col-md-8">{{ $companyRequest->company_name }}</dd>

                <dt class="col-12 col-md-4">Nama penanggung jawab</dt>
                <dd class="col-12 col-md-8">{{ $companyRequest->contact_person }}</dd>

                <dt class="col-12 col-md-4">Email perusahaan</dt>
                <dd class="col-12 col-md-8">{{ $companyRequest->company_email }}</dd>

                <dt class="col-12 col-md-4">Nomor telepon</dt>
                <dd class="col-12 col-md-8">{{ $companyRequest->company_phone }}</dd>

                <dt class="col-12 col-md-4">Alamat perusahaan</dt>
                <dd class="col-12 col-md-8">{{ $companyRequest->company_address }}</dd>

                <dt class="col-12 col-md-4">Deskripsi singkat</dt>
                <dd class="col-12 col-md-8">{{ $companyRequest->company_description }}</dd>

                <dt class="col-12 col-md-4">Status permintaan</dt>
                <dd class="col-12 col-md-8">
                    @if($companyRequest->request_status==='menunggu')
                        <span class="badge text-bg-warning">menunggu</span>
                    @elseif($companyRequest->request_status==='disetujui')
                        <span class="badge text-bg-success">disetujui</span>
                    @else
                        <span class="badge text-bg-danger">ditolak</span>
                    @endif
                </dd>

                <dt class="col-12 col-md-4">Tanggal permintaan</dt>
                <dd class="col-12 col-md-8">{{ $companyRequest->created_at?->format('Y-m-d H:i') }}</dd>

                <dt class="col-12 col-md-4">Catatan admin</dt>
                <dd class="col-12 col-md-8">{{ $companyRequest->note }}</dd>
            </dl>
        </div>
    </div>

    @if($companyRequest->request_status==='menunggu')
        <div class="row g-3">
            <div class="col-12 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="h6">Setujui permintaan</h3>
                        <form method="POST" action="{{ route('admin.company-account-requests.approve', ['companyRequest' => $companyRequest->id]) }}">

                            @csrf

                            <div class="mb-3">
                                <label class="form-label" for="note">Catatan admin (opsional)</label>
                                <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note" rows="3">{{ old('note') }}</textarea>
                                @error('note')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button class="btn btn-success w-100" type="submit">Konfirmasi Setujui</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="h6">Tolak permintaan</h3>
                        <form method="POST" action="{{ route('admin.company-account-requests.reject', ['companyRequest' => $companyRequest->id]) }}">


                            @csrf

                            <div class="mb-3">
                                <label class="form-label" for="note_reject">Alasan penolakan (note)</label>
                                <textarea class="form-control @error('note') is-invalid @enderror" id="note_reject" name="note" rows="4" required>{{ old('note') }}</textarea>
                                @error('note')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button class="btn btn-danger w-100" type="submit">Konfirmasi Tolak</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
</body>
</html>

