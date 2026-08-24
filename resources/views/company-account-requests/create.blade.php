<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.theme-boot')
        <title>Ajukan Akun Perusahaan</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

