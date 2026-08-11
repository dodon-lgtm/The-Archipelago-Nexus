<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Proyek - Freelancer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
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
<div class="container py-4" style="max-width: 1100px;">
    
    <div class="row">
        <!-- Kolom Kiri: Daftar Proyek -->
        <div class="col-lg-8">
            <h1 class="h4 mb-3">Daftar Proyek</h1>
            
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <form method="GET" action="{{ route('freelancer.projects.index') }}">
                        <div class="row g-3">
                            <div class="col-md-7">
                                <label class="form-label" for="search">Pencarian judul</label>
                                <input type="text" id="search" name="search" value="{{ $search }}" class="form-control" placeholder="Cari proyek...">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="category_id">Kategori</label>
                                <select id="category_id" name="category_id" class="form-select">
                                    <option value="" {{ empty($categoryId) ? 'selected' : '' }}>(Semua kategori)</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ (string)$categoryId === (string)$category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">Cari</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="list-group">
                @forelse ($projects as $project)
                    <a class="list-group-item list-group-item-action" href="{{ route('freelancer.projects.show', $project) }}">
                        <div class="fw-semibold">{{ $project->project_name }}</div>
                        <div class="text-muted small">
                            {{ $project->category->name ?? 'Tanpa kategori' }}
                        </div>
                        @if(!empty($project->project_description))
                            <div class="text-muted mt-1" style="font-size: 0.9rem;">{{ \Illuminate\Support\Str::limit($project->project_description, 120) }}</div>
                        @endif
                    </a>
                @empty
                    <div class="list-group-item text-muted">Tidak ada proyek ditemukan.</div>
                @endforelse
            </div>

            <div class="mt-3">
                {{ $projects->links() }}
            </div>
        </div>

        <!-- Kolom Kanan: Lamaran Saya Terbaru -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title h6 mb-3">Lamaran saya terbaru</h5>
                    <div class="list-group list-group-flush">
                        @forelse($latestApplications as $app)
                            <div class="list-group-item px-0">
                                <div class="fw-bold small">{{ $app->project->project_name ?? 'Proyek Dihapus' }}</div>
                                <div class="text-muted small">
                                    Status: <span class="badge bg-light text-dark border">{{ $app->status }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted small">Belum ada lamaran.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>