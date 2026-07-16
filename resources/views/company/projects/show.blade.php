<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $project->project_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4" style="max-width: 820px;">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">{{ $project->project_name }}</h1>
        <a class="btn btn-outline-secondary" href="{{ route('company.projects.index') }}">Kembali</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
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

</div>
</body>
</html>

