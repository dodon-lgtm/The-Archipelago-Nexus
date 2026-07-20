<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buat Proyek</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{ background:#f5f7fb; }
        .card{ border:none; border-radius:18px; }
        .form-control, .form-select{ border-radius:12px; }
        textarea{ resize:none; }
        .btn{ border-radius:12px; }
    </style>
</head>

<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-body p-4">
                    <h2 class="fw-bold mb-4">Buat Proyek Baru</h2>

                    {{-- PESAN ERROR JIKA VALIDASI GAGAL --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('company.projects.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Proyek</label>
                            <input type="text" name="project_name" class="form-control" value="{{ old('project_name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <textarea name="project_description" rows="5" class="form-control">{{ old('project_description') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kategori</label>
                            <select name="category_id" class="form-select">
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Budget (Rp)</label>
                                    <input type="number" name="budget" class="form-control" value="{{ old('budget') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Deadline</label>
                                    <input type="date" name="deadline" class="form-control" value="{{ old('deadline') }}">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Skill yang Dibutuhkan</label>
                            <input type="text" name="skills" class="form-control" value="{{ old('skills') }}" placeholder="Laravel, Bootstrap, MySQL">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Gambar Proyek</label>
                            <input type="file" name="image" accept="image/*" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Lampiran (PDF/DOC)</label>
                            <input type="file" name="attachment" accept=".pdf,.doc,.docx" class="form-control">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="Open">Open</option>
                                <option value="Closed">Closed</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('company.projects.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan Proyek</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>