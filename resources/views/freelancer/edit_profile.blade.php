<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil Freelancer</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

body{
    background:#f4f7fc;
    font-family:'Segoe UI',sans-serif;
}

.container{
    max-width:1100px;
    margin:40px auto;
}

.page-title{
    font-size:38px;
    font-weight:700;
    color:#1e293b;
}

.page-subtitle{
    color:#64748b;
    margin-bottom:30px;
}

.card-custom{

    background:#fff;

    border:none;

    border-radius:20px;

    box-shadow:0 8px 25px rgba(0,0,0,.08);

    margin-bottom:25px;

}

.card-header-custom{

    padding:20px 30px;

    border-bottom:1px solid #eee;

    font-size:22px;

    font-weight:700;

}

.card-body-custom{

    padding:30px;

}

.profile-preview{

    width:180px;

    height:180px;

    border-radius:50%;

    object-fit:cover;

    border:6px solid #fff;

    box-shadow:0 10px 25px rgba(0,0,0,.15);

}

.form-control{

    border-radius:12px;

    padding:12px;

}

.form-label{

    font-weight:600;

}

.btn{

    border-radius:50px;

    padding:10px 28px;

}

textarea{

    resize:none;

}

</style>

</head>

<body>

<div class="container">

<h1 class="page-title">

Edit Profil

</h1>

<p class="page-subtitle">

Lengkapi informasi profil agar lebih dipercaya oleh klien.

</p>

@if(session('success'))

<div class="alert alert-success">

{{ session('success') }}

</div>

@endif

@if ($errors->any())

<div class="alert alert-danger">

<ul class="mb-0">

@foreach($errors->all() as $error)

<li>{{ $error }}</li>

@endforeach

</ul>

</div>

@endif

<form action="{{ route('freelancer.profile.update') }}"
method="POST"
enctype="multipart/form-data">

@csrf

<div class="card-custom">

<div class="card-header-custom">

<i class="fa-solid fa-camera text-primary me-2"></i>

Foto Profil

</div>

<div class="card-body-custom text-center">

@if($profile->photo)

<img id="preview"

src="{{ asset('storage/'.$profile->photo) }}"

class="profile-preview mb-4">

@else

<img id="preview"

src="{{ asset('images/default-profile.png') }}"

class="profile-preview mb-4">

@endif

<div class="mb-3">

<input

type="file"

name="photo"

id="photo"

class="form-control">

</div>

<small class="text-muted">

Format JPG, JPEG, PNG. Maksimal 2MB.

</small>

</div>

</div>
{{-- ===========================
    INFORMASI DASAR
=========================== --}}

<div class="card-custom">

    <div class="card-header-custom">

        <i class="fa-solid fa-user text-primary me-2"></i>

        Informasi Dasar

    </div>

    <div class="card-body-custom">

        <div class="row">

            {{-- Nama --}}
            <div class="col-md-6 mb-4">

                <label class="form-label">

                    Nama Lengkap

                </label>

                <input
                type="text"
                class="form-control"
                value="{{ Auth::user()->name }}"
                readonly>

            </div>

            {{-- Email --}}
            <div class="col-md-6 mb-4">

                <label class="form-label">

                    Email

                </label>

                <input
                type="email"
                class="form-control"
                value="{{ Auth::user()->email }}"
                readonly>

            </div>

            {{-- Lokasi --}}
            <div class="col-md-6 mb-4">

                <label class="form-label">

                    Lokasi

                </label>

                <input
                type="text"
                name="location"
                class="form-control"
                placeholder="Contoh : Sukabumi, Jawa Barat"
                value="{{ old('location',$profile->location) }}">

            </div>

            {{-- Tarif --}}
            <div class="col-md-6 mb-4">

                <label class="form-label">

                    Tarif per Jam (Rp)

                </label>

                <input
                type="number"
                name="hourly_rate"
                class="form-control"
                placeholder="100000"
                value="{{ old('hourly_rate',$profile->hourly_rate) }}">

            </div>

        </div>

    </div>

</div>



{{-- ===========================
    TENTANG SAYA
=========================== --}}

<div class="card-custom">

    <div class="card-header-custom">

        <i class="fa-solid fa-address-card text-primary me-2"></i>

        Tentang Saya

    </div>

    <div class="card-body-custom">

        <label class="form-label">

            Ceritakan tentang diri Anda

        </label>

        <textarea

        name="bio"

        rows="6"

        class="form-control"

        placeholder="Contoh : Saya adalah seorang Web Developer yang berpengalaman menggunakan Laravel, PHP, dan MySQL...">{{ old('bio',$profile->bio) }}</textarea>

    </div>

</div>



{{-- ===========================
    KEAHLIAN
=========================== --}}

<div class="card-custom">

    <div class="card-header-custom">

        <i class="fa-solid fa-code text-primary me-2"></i>

        Keahlian

    </div>

    <div class="card-body-custom">

        <label class="form-label">

            Pisahkan dengan tanda koma (,)

        </label>

        <input

        type="text"

        name="skills"

        class="form-control"

        placeholder="Laravel, PHP, MySQL, Bootstrap"

        value="{{ old('skills',$profile->skills) }}">

        <small class="text-muted">

            Contoh:
            Laravel, PHP, JavaScript, Bootstrap, Figma

        </small>

    </div>

</div>
{{-- ===========================
    PENGALAMAN
=========================== --}}

<div class="card-custom">

    <div class="card-header-custom">

        <i class="fa-solid fa-briefcase text-primary me-2"></i>

        Pengalaman

    </div>

    <div class="card-body-custom">

        <label class="form-label">

            Pengalaman Kerja / Freelance

        </label>

        <textarea
            name="experience"
            rows="6"
            class="form-control"
            placeholder="Contoh : Freelance Web Developer selama 2 tahun...">{{ old('experience',$profile->experience) }}</textarea>

    </div>

</div>



{{-- ===========================
    PORTOFOLIO
=========================== --}}

<div class="card-custom">

    <div class="card-header-custom">

        <i class="fa-solid fa-globe text-primary me-2"></i>

        Link Portofolio

    </div>

    <div class="card-body-custom">

        <label class="form-label">

            URL Website / Github / Behance

        </label>

        <input
            type="url"
            name="portfolio_link"
            class="form-control"
            placeholder="https://github.com/username"
            value="{{ old('portfolio_link',$profile->portfolio_link) }}">

        @if($profile->portfolio_link)

            <div class="mt-3">

                <a href="{{ $profile->portfolio_link }}"
                   target="_blank"
                   class="btn btn-outline-primary">

                    <i class="fa-solid fa-arrow-up-right-from-square"></i>

                    Lihat Portofolio

                </a>

            </div>

        @endif

    </div>

</div>



{{-- ===========================
    CV
=========================== --}}

<div class="card-custom">

    <div class="card-header-custom">

        <i class="fa-solid fa-file-pdf text-danger me-2"></i>

        Curriculum Vitae

    </div>

    <div class="card-body-custom">

        <label class="form-label">

            Upload CV (PDF)

        </label>

        <input
            type="file"
            name="cv"
            id="cv"
            class="form-control">

        <small class="text-muted">

            Format PDF. Maksimal 2MB.

        </small>

        <div id="cvName" class="mt-3 text-success"></div>

        @if($profile->cv)

            <div class="mt-3">

                <a href="{{ asset('storage/'.$profile->cv) }}"
                   target="_blank"
                   class="btn btn-outline-success">

                    <i class="fa-solid fa-download"></i>

                    Download CV

                </a>

            </div>

        @endif

    </div>

</div>



{{-- ===========================
    BUTTON
=========================== --}}

<div class="d-flex justify-content-end gap-3 mb-5">

    <a href="{{ route('freelancer.profile') }}"
       class="btn btn-secondary">

        <i class="fa-solid fa-arrow-left"></i>

        Kembali

    </a>

    <button
        type="submit"
        class="btn btn-primary">

        <i class="fa-solid fa-floppy-disk"></i>

        Simpan Perubahan

    </button>

</div>

</form>
<script>

document.getElementById('photo').addEventListener('change',function(e){

    const reader = new FileReader();

    reader.onload = function(){

        document.getElementById('preview').src = reader.result;

    }

    reader.readAsDataURL(e.target.files[0]);

});


document.getElementById('cv').addEventListener('change',function(){

    if(this.files.length){

        document.getElementById('cvName').innerHTML =
        "<i class='fa-solid fa-file-pdf text-danger'></i> " +
        this.files[0].name;

    }

});

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>