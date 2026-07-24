<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Profil Freelancer</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>

<style>

body{
    background:#f4f7fc;
    font-family:'Segoe UI',sans-serif;
}

/* ===================== */

.container{
    max-width:1200px;
    margin-top:40px;
    margin-bottom:50px;
}

/* ===================== */

.page-title{
    font-size:42px;
    font-weight:700;
    color:#1f2937;
}

.page-subtitle{
    color:#6b7280;
    margin-bottom:35px;
}

/* ===================== */

.profile-card{

    background:white;

    border-radius:22px;

    overflow:hidden;

    box-shadow:0 15px 40px rgba(0,0,0,.08);

}

/* ===================== */

.profile-header{

    background:linear-gradient(135deg,#eef5ff,#d8e8ff);

    padding:40px;

}

/* ===================== */

.profile-photo{

    width:140px;

    height:140px;

    border-radius:50%;

    object-fit:cover;

    border:6px solid white;

    box-shadow:0 10px 25px rgba(0,0,0,.15);

}

/* ===================== */

.profile-name{

    font-size:38px;

    font-weight:700;

    color:#1f2937;

}

/* ===================== */

.badge-freelancer{

    background:#0d6efd;

    color:white;

    padding:8px 18px;

    border-radius:50px;

    font-size:14px;

    display:inline-block;

    margin-top:8px;

}

/* ===================== */

.profile-info{

    color:#5f6c7b;

    font-size:17px;

    margin-top:12px;

}

.profile-info i{

    width:22px;

    color:#0d6efd;

}

/* ===================== */

.rate-box{

    text-align:right;

}

.rate-title{

    font-size:18px;

    color:#666;

}

.rate-price{

    font-size:38px;

    color:#198754;

    font-weight:bold;

}

.edit-btn{

    border-radius:50px;

    padding:10px 28px;

}

/* ===================== */

.stat-box{

    text-align:center;

    padding:28px;

    border-right:1px solid #ececec;

}

.stat-box:last-child{

    border-right:none;

}

.stat-title{

    color:#777;

    font-size:14px;

}

.stat-value{

    margin-top:8px;

    font-size:30px;

    font-weight:bold;

    color:#0d6efd;

}

/* ===================== */

.content-card{

    background:white;

    border-radius:18px;

    box-shadow:0 10px 30px rgba(0,0,0,.06);

    padding:28px;

    height:100%;

}

.content-title{

    font-size:25px;

    font-weight:700;

    margin-bottom:25px;

}

</style>

</head>

<body>

@php

$total=7;

$isi=0;

if($profile->photo) $isi++;

if($profile->bio) $isi++;

if($profile->skills) $isi++;

if($profile->portfolio_link) $isi++;

if(Auth::user()->phone) $isi++;

if($profile->cv) $isi++;

if($profile->hourly_rate) $isi++;

$progress=round(($isi/$total)*100);

@endphp


<div class="container">

<h1 class="page-title">
Profil Saya
</h1>

<p class="page-subtitle">
Kelola informasi profil, keahlian dan portofolio Anda.
</p>

<div class="profile-card">

<div class="profile-header">

<div class="row align-items-center">

<!-- FOTO -->

<div class="col-lg-2 text-center">

@if($profile->photo)

<img
src="{{ asset('storage/'.$profile->photo) }}"
class="profile-photo">

@else

<img
src="{{ asset('images/default-profile.png') }}"
class="profile-photo">

@endif

</div>

<!-- BIODATA -->

<div class="col-lg-6">

<h2 class="profile-name">

{{ Auth::user()->name }}

</h2>

<div class="badge-freelancer">

Freelancer

</div>

<div class="profile-info">

<i class="fa-solid fa-location-dot"></i>

{{ $profile->location ?: 'Belum mengisi lokasi' }}

</div>

<div class="profile-info">

<i class="fa-solid fa-envelope"></i>

{{ Auth::user()->email }}

</div>

<div class="profile-info">

<i class="fa-solid fa-phone"></i>

{{ Auth::user()->phone ?: 'Belum mengisi nomor HP' }}

</div>

<div class="profile-info">

<i class="fa-solid fa-calendar"></i>

Bergabung sejak

{{ Auth::user()->created_at->translatedFormat('d F Y') }}

</div>

</div>

<!-- TARIF -->

 <div class="col-lg-4 rate-box">

{{-- <div class="rate-title">

Tarif

</div>

<div class="rate-price">

@if($profile->hourly_rate)

Rp {{ number_format($profile->hourly_rate,0,',','.') }}

@else

-

@endif

</div>

<div class="text-muted">

/ Jam

</div> --}}

<br>

<a
href="{{ route('freelancer.profile.edit') }}"
class="btn btn-primary edit-btn">

<i class="fa-solid fa-pen"></i>

Edit Profil

</a>

</div>

</div>

</div>

<!-- STATISTIK -->

<div class="row g-0">

<div class="col stat-box">

<div class="stat-title">

Skill

</div>

<div class="stat-value">

{{ $profile->skills ? count(explode(',',$profile->skills)) : 0 }}

</div>

</div>

<div class="col stat-box">

<div class="stat-title">

Pengalaman

</div>

<div class="stat-value">

{{ $profile->experience ? '✔' : '-' }}

</div>

</div>

<div class="col stat-box">

<div class="stat-title">

Portofolio

</div>

<div class="stat-value">

{{ $profile->portfolio_link ? '✔' : '-' }}

</div>

</div>

<div class="col stat-box">

<div class="stat-title">

CV

</div>

<div class="stat-value">

{{ $profile->cv ? '✔' : '-' }}

</div>

</div>

<div class="col stat-box">

<div class="stat-title">

Rate

</div>

<div class="stat-value">

{{ $profile->hourly_rate ? '✔' : '-' }}

</div>

</div>

</div>

</div>
{{-- ===========================
    CONTENT
=========================== --}}

<div class="row mt-4">

    <!-- Tentang Saya -->
    <div class="col-lg-8 mb-4">

        <div class="content-card">

            <h4 class="content-title">
                <i class="fa-solid fa-user text-primary me-2"></i>
                Tentang Saya
            </h4>

            @if($profile->bio)

                <p style="font-size:16px;line-height:30px;color:#555;">
                    {{ $profile->bio }}
                </p>

            @else

                <p class="text-muted">
                    Belum ada deskripsi.
                </p>

            @endif

            <hr>

            <div class="row">

                <div class="col-md-6 mb-3">

                    <small class="text-muted">
                        Nama Lengkap
                    </small>

                    <h6>

                        {{ Auth::user()->name }}

                    </h6>

                </div>

                <div class="col-md-6 mb-3">

                    <small class="text-muted">
                        Email
                    </small>

                    <h6>

                        {{ Auth::user()->email }}

                    </h6>

                </div>

                <div class="col-md-6 mb-3">

                    <small class="text-muted">
                        Lokasi
                    </small>

                    <h6>

                        {{ $profile->location ?: '-' }}

                    </h6>

                </div>

                <div class="col-md-6 mb-3">

                    <small class="text-muted">
                        Tarif
                    </small>

                    <h6>

                        @if($profile->hourly_rate)

                            Rp {{ number_format($profile->hourly_rate,0,',','.') }}/Jam

                        @else

                            -

                        @endif

                    </h6>

                </div>

            </div>

        </div>

    </div>



    <!-- Skill -->

    <div class="col-lg-4 mb-4">

        <div class="content-card">

            <h4 class="content-title">

                <i class="fa-solid fa-laptop-code text-primary me-2"></i>

                Keahlian

            </h4>

            @if($profile->skills)

                @foreach(explode(',', $profile->skills) as $skill)

                    <span
                    class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2 m-1">

                        {{ trim($skill) }}

                    </span>

                @endforeach

            @else

                <p class="text-muted">

                    Belum ada keahlian.

                </p>

            @endif

        </div>

    </div>

</div>



{{-- ===========================
    PORTFOLIO
=========================== --}}

<div class="row">

    <div class="col-lg-6 mb-4">

        <div class="content-card">

            <h4 class="content-title">

                <i class="fa-solid fa-folder-open text-warning me-2"></i>

                Portofolio

            </h4>

            @if($profile->portfolio_link)

                <a

                href="{{ $profile->portfolio_link }}"

                target="_blank"

                class="btn btn-outline-primary">

                    <i class="fa-solid fa-arrow-up-right-from-square"></i>

                    Lihat Portofolio

                </a>

            @else

                <p class="text-muted">

                    Belum menambahkan link portofolio.

                </p>

            @endif

        </div>

    </div>
        {{-- ================= CV ================= --}}

    <div class="col-lg-6 mb-4">

        <div class="content-card">

            <h4 class="content-title">

                <i class="fa-solid fa-file-arrow-down text-success me-2"></i>

                Curriculum Vitae

            </h4>

            @if($profile->cv)

                <a href="{{ asset('storage/'.$profile->cv) }}"
                   target="_blank"
                   class="btn btn-success">

                    <i class="fa-solid fa-download"></i>

                    Download CV

                </a>

            @else

                <p class="text-muted">

                    Belum mengunggah CV.

                </p>

            @endif

        </div>

    </div>

</div>


{{-- ================= Progress Profil ================= --}}

<div class="row">

    <div class="col-lg-12">

        <div class="content-card">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h4 class="content-title mb-0">

                    <i class="fa-solid fa-chart-line text-primary me-2"></i>

                    Progress Profil

                </h4>

                <h3 class="text-primary">

                    {{ $progress }}%

                </h3>

            </div>


            <div class="progress mb-4">

                <div class="progress-bar progress-bar-striped progress-bar-animated"

                     style="width:{{ $progress }}%">

                </div>

            </div>


            <div class="row">

                <div class="col-md-3 mb-3">

                    @if($profile->photo)

                        <span class="text-success">✔ Foto Profil</span>

                    @else

                        <span class="text-danger">✖ Foto Profil</span>

                    @endif

                </div>

                <div class="col-md-3 mb-3">

                    @if($profile->bio)

                        <span class="text-success">✔ Tentang Saya</span>

                    @else

                        <span class="text-danger">✖ Tentang Saya</span>

                    @endif

                </div>

                <div class="col-md-3 mb-3">

                    @if($profile->skills)

                        <span class="text-success">✔ Keahlian</span>

                    @else

                        <span class="text-danger">✖ Keahlian</span>

                    @endif

                </div>

                <div class="col-md-3 mb-3">

                    @if($profile->experience)

                        <span class="text-success">✔ Pengalaman</span>

                    @else

                        <span class="text-danger">✖ Pengalaman</span>

                    @endif

                </div>

                <div class="col-md-3 mb-3">

                    @if($profile->portfolio_link)

                        <span class="text-success">✔ Portofolio</span>

                    @else

                        <span class="text-danger">✖ Portofolio</span>

                    @endif

                </div>

                <div class="col-md-3 mb-3">

                    @if($profile->cv)

                        <span class="text-success">✔ CV</span>

                    @else

                        <span class="text-danger">✖ CV</span>

                    @endif

                </div>

                <div class="col-md-3 mb-3">

                    @if($profile->hourly_rate)

                        <span class="text-success">✔ Tarif</span>

                    @else

                        <span class="text-danger">✖ Tarif</span>

                    @endif

                </div>

                <div class="col-md-3 mb-3">

                    @if($profile->location)

                        <span class="text-success">✔ Lokasi</span>

                    @else

                        <span class="text-danger">✖ Lokasi</span>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>