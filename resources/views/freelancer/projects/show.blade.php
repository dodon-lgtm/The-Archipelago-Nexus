<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Proyek - Freelancer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4" style="max-width: 900px;">
    <div class="mb-3">
        <a href="{{ route('freelancer.projects.index') }}" class="btn btn-outline-secondary btn-sm">&larr; Kembali</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h1 class="h4 mb-2">{{ $project->project_name }}</h1>
            <div class="text-muted mb-3">
                @if($project->category)
                    Kategori: <strong>{{ $project->category->name }}</strong>
                @else
                    Kategori: <strong>(Tanpa kategori)</strong>
                @endif
            </div>

            @if($project->project_description)
                <h2 class="h6">Deskripsi</h2>
                <p class="mb-0">{{ $project->project_description }}</p>
            @else
                <p class="text-muted mb-0">Tidak ada deskripsi.</p>
            @endif

            <hr>
            <div class="text-muted" style="font-size: 0.9rem;">
                Pemilik: {{ $project->owner?->name ?? '-' }}
            </div>
        </div>
    </div>
</div>
</body>
</html>

