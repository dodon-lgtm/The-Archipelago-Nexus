<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Ajukan Akun Perusahaan</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-7">
                    <div class="mb-4">
                        <h1 class="h4 mb-1">Ajukan Akun Perusahaan</h1>
                        <p class="text-muted mb-0">Isi formulir di bawah ini untuk mengajukan permintaan pembuatan akun.</p>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="card shadow-sm">
                        <div class="card-body">
                            <form method="POST" action="{{ route('company-account-requests.store') }}">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label" for="company_name">Nama perusahaan</label>
                                    <input
                                        type="text"
                                        class="form-control @error('company_name') is-invalid @enderror"
                                        id="company_name"
                                        name="company_name"
                                        value="{{ old('company_name') }}"
                                        required
                                    >
                                    @error('company_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="contact_person">Nama penanggung jawab / contact person</label>
                                    <input
                                        type="text"
                                        class="form-control @error('contact_person') is-invalid @enderror"
                                        id="contact_person"
                                        name="contact_person"
                                        value="{{ old('contact_person') }}"
                                        required
                                    >
                                    @error('contact_person')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="company_email">Email perusahaan</label>
                                    <input
                                        type="email"
                                        class="form-control @error('company_email') is-invalid @enderror"
                                        id="company_email"
                                        name="company_email"
                                        value="{{ old('company_email') }}"
                                        required
                                    >
                                    @error('company_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="company_phone">Nomor telepon</label>
                                    <input
                                        type="text"
                                        class="form-control @error('company_phone') is-invalid @enderror"
                                        id="company_phone"
                                        name="company_phone"
                                        value="{{ old('company_phone') }}"
                                        required
                                    >
                                    @error('company_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="company_address">Alamat perusahaan</label>
                                    <textarea
                                        class="form-control @error('company_address') is-invalid @enderror"
                                        id="company_address"
                                        name="company_address"
                                        rows="3"
                                        required
                                    >{{ old('company_address') }}</textarea>
                                    @error('company_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="company_description">Deskripsi singkat perusahaan (opsional)</label>
                                    <textarea
                                        class="form-control @error('company_description') is-invalid @enderror"
                                        id="company_description"
                                        name="company_description"
                                        rows="3"
                                    >{{ old('company_description') }}</textarea>
                                    @error('company_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Kirim permintaan</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="text-center text-muted mt-3" style="font-size: 0.9rem;">
                        Setelah dikirim, perusahaan belum bisa login atau membuat proyek sebelum disetujui admin.
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

