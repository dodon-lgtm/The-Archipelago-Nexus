<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Proyek</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Daftar Proyek</h1>
        <a class="btn btn-primary" href="{{ route('company.projects.create') }}">Buat Proyek</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="list-group">
        @forelse ($projects as $project)
            <a class="list-group-item list-group-item-action" href="{{ route('company.projects.show', $project) }}">
                <div class="fw-semibold">{{ $project->project_name }}</div>
                <div class="text-muted" style="font-size: 0.9rem;">{{ Str::limit($project->project_description ?? '-', 80) }}</div>
            </a>
        @empty
            <div class="list-group-item text-muted">Belum ada proyek.</div>
        @endforelse
    </div>

    <div class="mt-3">
        {{ $projects->links() }}
    </div>
</div>
</body>
</html>

