<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Proyek</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4" style="max-width: 720px;">

    <h1 class="h4 mb-3">Edit Proyek</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('company.projects.update', $project) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label" for="project_name">Nama proyek</label>
                    <input
                        type="text"
                        class="form-control @error('project_name') is-invalid @enderror"
                        id="project_name"
                        name="project_name"
                        value="{{ old('project_name', $project->project_name) }}"
                        required
                    >
                    @error('project_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="project_description">Deskripsi proyek</label>
                    <textarea
                        class="form-control @error('project_description') is-invalid @enderror"
                        id="project_description"
                        name="project_description"
                        rows="4"
                    >{{ old('project_description', $project->project_description) }}</textarea>
                    @error('project_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Perbarui</button>
                    <a class="btn btn-outline-secondary" href="{{ route('company.projects.show', $project) }}">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>

