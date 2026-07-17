<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Proyek - Freelancer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4" style="max-width: 980px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Daftar Proyek</h1>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('freelancer.projects.index') }}">
                <div class="row g-3">
                    <div class="col-12 col-md-7">
                        <label class="form-label" for="search">Pencarian judul</label>
                        <input
                            type="text"
                            id="search"
                            name="search"
                            value="{{ $search }}"
                            class="form-control"
                            placeholder="Cari proyek..."
                        >
                    </div>

                    <div class="col-12 col-md-4">
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

                    <div class="col-12 col-md-1 d-flex align-items-end">
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
                <div class="text-muted" style="font-size: 0.9rem;">
                    @if($project->category)
                        {{ $project->category->name }}
                    @else
                        (Tanpa kategori)
                    @endif
                </div>
                @if(!empty($project->project_description))
                    <div class="text-muted" style="font-size: 0.9rem;">{{ 
                        \Illuminate\Support\Str::limit($project->project_description, 120)
                    }}</div>
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
</body>
</html>

