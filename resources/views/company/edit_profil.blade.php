<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Edit Profil Client</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{

margin:0;

padding:0;

box-sizing:border-box;

font-family:'Poppins',sans-serif;

}

body{

background:#f4f8ff;

}

.container{

max-width:950px;

margin-top:40px;

margin-bottom:50px;

}

.card{

border:none;

border-radius:20px;

box-shadow:0 10px 25px rgba(0,0,0,.08);

overflow:hidden;

}

.card-header{

background:linear-gradient(135deg,#2563eb,#3b82f6);

color:white;

padding:25px;

border:none;

}

.card-header h3{

margin:0;

font-weight:700;

}

.card-body{

padding:35px;

}

.logo-preview{

width:150px;

height:150px;

border-radius:50%;

object-fit:cover;

border:5px solid #ddd;

margin-bottom:20px;

}

.form-control{

border-radius:10px;

padding:12px;

}

textarea{

resize:none;

}

.btn-save{

background:#2563eb;

color:white;

padding:12px 30px;

border-radius:10px;

font-weight:600;

}

.btn-save:hover{

background:#1d4ed8;

color:white;

}

.btn-back{

padding:12px 30px;

border-radius:10px;

}

.form-control:focus,
.form-select:focus{

border-color:#2563eb;

box-shadow:0 0 0 .2rem rgba(37,99,235,.15);

}

.logo-preview{

transition:.3s;

cursor:pointer;

}

.logo-preview:hover{

transform:scale(1.05);

}

.btn-save{

transition:.3s;

}

.btn-save:hover{

transform:translateY(-2px);

box-shadow:0 10px 20px rgba(37,99,235,.25);

}

.card{

animation:fadeUp .5s ease;

}

@keyframes fadeUp{

from{

opacity:0;

transform:translateY(20px);

}

to{

opacity:1;

transform:translateY(0);

}

}

</style>

</head>

<body>

<div class="container">

<div class="card">

<div class="card-header">

<h3>

<i class="bi bi-building"></i>

Edit Profil Perusahaan

</h3>

</div>

<div class="card-body">

@if($errors->any())

<div class="alert alert-danger">

<ul class="mb-0">

@foreach($errors->all() as $error)

<li>{{ $error }}</li>

@endforeach

</ul>

</div>

@endif

<form

action="{{ route('company.profile.update') }}"

method="POST"

enctype="multipart/form-data">

@csrf
<div class="row">

<div class="col-md-4 text-center">

@if($profile->company_logo)

<img
src="{{ asset('storage/'.$profile->company_logo) }}"
id="preview"
class="logo-preview">

@else

<img
src="{{ asset('images/company.png') }}"
id="preview"
class="logo-preview">

@endif

<div class="mb-3">

<label class="form-label fw-bold">

Logo Perusahaan

</label>

<input
type="file"
name="company_logo"
class="form-control"
accept="image/*"
onchange="previewImage(event)">

</div>

</div>

<div class="col-md-8">

<div class="mb-3">

<label class="form-label">

Nama Perusahaan

</label>

<input
type="text"
name="company_name"
class="form-control"
value="{{ old('company_name',$profile->company_name) }}"
placeholder="PT. Contoh Indonesia">

</div>

<div class="mb-3">

<label class="form-label">

Bidang Usaha

</label>

<select
name="industry"
class="form-select">

<option value="">-- Pilih Bidang Usaha --</option>

<option value="Teknologi"
{{ $profile->industry=='Teknologi'?'selected':'' }}>
Teknologi
</option>

<option value="Pendidikan"
{{ $profile->industry=='Pendidikan'?'selected':'' }}>
Pendidikan
</option>

<option value="Kesehatan"
{{ $profile->industry=='Kesehatan'?'selected':'' }}>
Kesehatan
</option>

<option value="Keuangan"
{{ $profile->industry=='Keuangan'?'selected':'' }}>
Keuangan
</option>

<option value="Manufaktur"
{{ $profile->industry=='Manufaktur'?'selected':'' }}>
Manufaktur
</option>

<option value="Media"
{{ $profile->industry=='Media'?'selected':'' }}>
Media
</option>

<option value="Lainnya"
{{ $profile->industry=='Lainnya'?'selected':'' }}>
Lainnya
</option>

</select>

</div>

<div class="mb-3">

<label class="form-label">

Website

</label>

<input
type="url"
name="website"
class="form-control"
value="{{ old('website',$profile->website) }}"
placeholder="https://company.com">

</div>

<div class="row">

<div class="col-md-6">

<div class="mb-3">

<label class="form-label">

Lokasi

</label>

<input
type="text"
name="location"
class="form-control"
value="{{ old('location',$profile->location) }}"
placeholder="Jakarta">

</div>

</div>

<div class="col-md-6">

<div class="mb-3">

<label class="form-label">

Nomor Telepon

</label>

<input
type="text"
name="phone"
class="form-control"
value="{{ old('phone',$profile->phone) }}"
placeholder="08123456789">

</div>

</div>

</div>

</div>

</div>

<div class="mb-4">

<label class="form-label">

Deskripsi Perusahaan

</label>

<textarea
name="description"
rows="6"
class="form-control"
placeholder="Ceritakan mengenai perusahaan Anda...">{{ old('description',$profile->description) }}</textarea>

</div>
<div class="d-flex justify-content-between mt-4">

    <a
        href="{{ route('company.profile') }}"
        class="btn btn-secondary btn-back">

        <i class="bi bi-arrow-left"></i>

        Kembali

    </a>

    <button
        type="submit"
        class="btn btn-save">

        <i class="bi bi-check-circle"></i>

        Simpan Perubahan

    </button>

</div>

</form>

</div>

</div>

</div>

<script>

function previewImage(event){

    const image=document.getElementById('preview');

    image.src=URL.createObjectURL(event.target.files[0]);

}

</script>

</body>

</html>