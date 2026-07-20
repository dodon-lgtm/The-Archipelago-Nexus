<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buat Proyek</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background:#f5f7fb;
        }

        .card{
            border:none;
            border-radius:18px;
        }

        .form-control,
        .form-select{
            border-radius:12px;
        }

        textarea{
            resize:none;
        }

        .btn{
            border-radius:12px;
        }
    </style>

</head>

<body>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="card shadow">

                <div class="card-body p-4">

                    <h2 class="fw-bold mb-4">
                        Buat Proyek Baru
                    </h2>

                    @if(session('success'))

                        <div class="alert alert-success">

                            {{ session('success') }}

                        </div>

                    @endif

                    <form method="POST"
                          action="{{ route('company.projects.store') }}"
                          enctype="multipart/form-data">

                        @csrf

                        {{-- Nama Proyek --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Nama Proyek
                            </label>

                            <input
                                type="text"
                                name="project_name"
                                class="form-control @error('project_name') is-invalid @enderror"
                                value="{{ old('project_name') }}"
                                required>

                            @error('project_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- Deskripsi --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Deskripsi
                            </label>

                            <textarea
                                name="project_description"
                                rows="5"
                                class="form-control @error('project_description') is-invalid @enderror">{{ old('project_description') }}</textarea>

                            @error('project_description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- Kategori --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Kategori
                            </label>

                            <select
                                name="category_id"
                                class="form-select @error('category_id') is-invalid @enderror">

                                <option value="">Pilih Kategori</option>

                                @foreach($categories as $category)

                                    <option
                                        value="{{ $category->id }}"
                                        {{ old('category_id')==$category->id?'selected':'' }}>

                                        {{ $category->name }}

                                    </option>

                                @endforeach

                            </select>

                            @error('category_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="row">

                            {{-- Budget --}}
                            <div class="col-md-6">

                                <div class="mb-3">

                                    <label class="form-label fw-semibold">
                                        Budget (Rp)
                                    </label>

                                    <input
                                        type="number"
                                        name="budget"
                                        class="form-control @error('budget') is-invalid @enderror"
                                        value="{{ old('budget') }}">

                                    @error('budget')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                            </div>

                            {{-- Deadline --}}
                            <div class="col-md-6">

                                <div class="mb-3">

                                    <label class="form-label fw-semibold">
                                        Deadline
                                    </label>

                                    <input
                                        type="date"
                                        name="deadline"
                                        class="form-control @error('deadline') is-invalid @enderror"
                                        value="{{ old('deadline') }}">

                                    @error('deadline')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                            </div>

                        </div>

                        {{-- Skills --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Skill yang Dibutuhkan
                            </label>

                            <input
                                type="text"
                                name="skills"
                                class="form-control @error('skills') is-invalid @enderror"
                                value="{{ old('skills') }}"
                                placeholder="Laravel, Flutter, UI/UX">

                            @error('skills')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- Upload Gambar --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Gambar Proyek
                            </label>

                            <input
                                type="file"
                                name="image"
                                class="form-control @error('image') is-invalid @enderror">

                            @error('image')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- Upload Lampiran --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Lampiran
                            </label>

                            <input
                                type="file"
                                name="attachment"
                                class="form-control @error('attachment') is-invalid @enderror">

                            @error('attachment')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- Status --}}
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Status
                            </label>

                            <select
                                name="status"
                                class="form-select">

                                <option value="Open">
                                    Open
                                </option>

                                <option value="Closed">
                                    Closed
                                </option>

                            </select>

                        </div>

                        <div class="d-flex justify-content-between">

                            <a
                                href="{{ route('company.projects.index') }}"
                                class="btn btn-secondary">

                                Kembali

                            </a>

                            <button
                                class="btn btn-primary">

                                Simpan Proyek

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>