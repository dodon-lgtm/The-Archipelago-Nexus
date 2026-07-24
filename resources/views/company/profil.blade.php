<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Profil Client | FreelanceID</title>

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
background:#f5f8ff;
}

.container{
max-width:1250px;
margin-top:40px;
margin-bottom:50px;
}

.page-title{
font-size:40px;
font-weight:700;
color:#1f2937;
}

.page-subtitle{
color:#6b7280;
margin-bottom:30px;
}

.profile-card{

background:#fff;

border-radius:25px;

overflow:hidden;

box-shadow:0 15px 35px rgba(0,0,0,.08);

}

.profile-header{
    background:linear-gradient(135deg,#2563eb,#3b82f6);
    padding:45px;
    color:white;
    position:relative;
    overflow:hidden;
}

.profile-header::before{
    content:"";
    position:absolute;
    right:-100px;
    top:-100px;
    width:350px;
    height:350px;
    border-radius:50%;
    background:rgba(255,255,255,.08);

    /* TAMBAHAN */
    z-index:0;
    pointer-events:none;
}

.profile-header .row{
    position:relative;
    z-index:2;
}

.edit-btn{
    display:inline-block;
    background:white;
    color:#2563eb;
    padding:12px 25px;
    border-radius:12px;
    text-decoration:none;
    font-weight:600;
    transition:.3s;
    position:relative;
    z-index:999;
}

.edit-btn:hover{
    background:#eef4ff;
    color:#2563eb;
    transform:translateY(-2px);
}

.company-logo{

width:140px;

height:140px;

border-radius:50%;

border:5px solid white;

object-fit:cover;

background:white;

box-shadow:0 8px 25px rgba(0,0,0,.15);

}

.company-name{

font-size:34px;

font-weight:700;

}

.company-type{

display:inline-block;

margin-top:10px;

background:#22c55e;

padding:8px 18px;

border-radius:30px;

font-size:14px;

font-weight:600;

}

.info{

margin-top:12px;

font-size:15px;

}

.info i{

width:25px;

}

.edit-btn{

background:white;

color:#2563eb;

padding:12px 25px;

border-radius:12px;

text-decoration:none;

font-weight:600;

display:inline-block;

transition:.3s;

}

.edit-btn:hover{

background:#eef4ff;

transform:translateY(-2px);

}

.rate{

font-size:34px;

font-weight:700;

text-align:right;

}

.rate small{

font-size:18px;

font-weight:400;

}

.stats{

background:white;

}

.stat{

padding:30px;

text-align:center;

border-right:1px solid #ececec;

}

.stat:last-child{

border-right:none;

}

.stat h3{

font-weight:700;

color:#2563eb;

}

.stat span{

color:#6b7280;

font-size:15px;

display:block;

margin-top:5px;

}

.content-card{

background:white;

border-radius:20px;

padding:30px;

box-shadow:0 10px 25px rgba(0,0,0,.06);

height:100%;

transition:.3s;

}

.content-card:hover{

transform:translateY(-4px);

}

.section-title{

font-size:24px;

font-weight:700;

margin-bottom:20px;

color:#1f2937;

}

.table td{

padding:12px;

}

.table th{

padding:12px;

width:180px;

}

.website-btn{

padding:12px 25px;

border-radius:10px;

}

</style>

</head>

<body>

<div class="container">

<!-- Tombol Kembali ke Dashboard -->
<div class="mb-4">
    <a href="{{ route('company.dashboard') }}" class="btn btn-outline-primary px-4 py-2 rounded-pill fw-semibold">
        <i class="bi bi-arrow-left me-2"></i> Kembali ke Dashboard
    </a>
</div>

<h1 class="page-title">

Profil Perusahaan

</h1>

<p class="page-subtitle">

Kelola informasi perusahaan Anda agar freelancer lebih percaya.

</p>

<div class="profile-card">

<div class="profile-header">

<div class="row align-items-center">

<div class="col-lg-2 text-center">

@if($profile->company_logo)

<img
src="{{ asset('storage/'.$profile->company_logo) }}"
class="company-logo">

@else

<img
src="{{ asset('images/company.png') }}"
class="company-logo">

@endif

</div>

<div class="col-lg-7">

<h2 class="company-name">

{{ $profile->company_name ?? 'Nama Perusahaan' }}

</h2>

<div class="company-type">

<i class="bi bi-patch-check-fill"></i>

Client Terverifikasi

</div>

<div class="info">

<i class="bi bi-geo-alt-fill"></i>

{{ $profile->location ?? '-' }}

</div>

<div class="info">

<i class="bi bi-envelope-fill"></i>

{{ Auth::user()->email }}

</div>

<div class="info">

<i class="bi bi-telephone-fill"></i>

{{ $profile->phone ?? '-' }}

</div>

<div class="info">

<i class="bi bi-calendar-event-fill"></i>

Bergabung sejak

{{ Auth::user()->created_at->format('d F Y') }}

</div>

</div>

<div class="col-lg-3 text-end">

<div class="rate">

{{ $profile->industry ?? 'Belum menentukan bidang' }}

</div>

<br>

<a
href="{{ route('company.profile.edit') }}"
class="edit-btn">

<i class="bi bi-pencil-square"></i>

Edit Profil

</a>

</div>

</div>

</div>
<!-- Statistik -->

<div class="row stats">

    <div class="col stat">

        <h3>

            0

        </h3>

        <span>

            Project Dibuka

        </span>

    </div>

    <div class="col stat">

        <h3>

            0

        </h3>

        <span>

            Freelancer Direkrut

        </span>

    </div>

    <div class="col stat">

        <h3>

            0.0

        </h3>

        <span>

            Rating

        </span>

    </div>

    <div class="col stat">

        <h3>

            0%

        </h3>

        <span>

            Keberhasilan

        </span>

    </div>

</div>

</div>


<!-- Tentang Perusahaan -->

<div class="row mt-4">

    <div class="col-lg-12 mb-4">

        <div class="content-card">

            <div class="section-title">

                <i class="bi bi-building text-primary me-2"></i>

                Tentang Perusahaan

            </div>

            @if($profile->description)

                <p class="text-secondary lh-lg fs-6">

                    {{ $profile->description }}

                </p>

            @else

                <div class="alert alert-light border">

                    Belum ada deskripsi perusahaan.

                </div>

            @endif

        </div>

    </div>

</div>


<div class="row">

    <!-- Informasi Perusahaan -->

    <div class="col-lg-6 mb-4">

        <div class="content-card">

            <div class="section-title">

                <i class="bi bi-info-circle-fill text-primary me-2"></i>

                Informasi Perusahaan

            </div>

            <table class="table table-borderless align-middle">

                <tr>

                    <th>Nama</th>

                    <td>

                        {{ $profile->company_name ?? '-' }}

                    </td>

                </tr>

                <tr>

                    <th>Bidang</th>

                    <td>

                        {{ $profile->industry ?? '-' }}

                    </td>

                </tr>

                <tr>

                    <th>Lokasi</th>

                    <td>

                        {{ $profile->location ?? '-' }}

                    </td>

                </tr>

                <tr>

                    <th>Email</th>

                    <td>

                        {{ Auth::user()->email }}

                    </td>

                </tr>

                <tr>

                    <th>Telepon</th>

                    <td>

                        {{ $profile->phone ?? '-' }}

                    </td>

                </tr>

            </table>

        </div>

    </div>
        <!-- Website Perusahaan -->
    <div class="col-lg-6 mb-4">

        <div class="content-card">

            <div class="section-title">

                <i class="bi bi-globe2 text-primary me-2"></i>

                Website Perusahaan

            </div>

            @if($profile->website)

                <div class="mb-3">

                    <a
                        href="{{ $profile->website }}"
                        target="_blank"
                        class="btn btn-primary website-btn">

                        <i class="bi bi-box-arrow-up-right me-2"></i>

                        Kunjungi Website

                    </a>

                </div>

                <div class="alert alert-light border">

                    {{ $profile->website }}

                </div>

            @else

                <div class="alert alert-warning">

                    <i class="bi bi-exclamation-circle me-2"></i>

                    Website perusahaan belum ditambahkan.

                </div>

            @endif

            <hr>

            <h5 class="fw-bold mb-3">

                Bidang Usaha

            </h5>

            @if($profile->industry)

                <span class="badge bg-primary fs-6 px-3 py-2">

                    {{ $profile->industry }}

                </span>

            @else

                <span class="text-muted">

                    Belum diisi

                </span>

            @endif

            <hr>

            <h5 class="fw-bold mb-3">

                Bergabung

            </h5>

            <p class="text-muted mb-0">

                <i class="bi bi-calendar-event me-2"></i>

                {{ Auth::user()->created_at->format('d F Y') }}

            </p>

        </div>

    </div>

</div>

</div>

</body>

</html>