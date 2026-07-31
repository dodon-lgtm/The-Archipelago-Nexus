<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Profil Freelancer - Modern Dashboard</title>

<!-- Bootstrap 5.3 & FontAwesome -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
<!-- Google Fonts: Plus Jakarta Sans -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<!-- AOS Animation Library CSS -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<style>
    :root {
        --primary-color: #0284c7;
        --primary-gradient: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%);
        --bg-color: #f0f9ff;
        --card-bg: rgba(255, 255, 255, 0.85);
        --text-main: #0f172a;
        --text-muted: #64748b;
        --border-color: rgba(186, 230, 253, 0.6);
    }

    body {
        background-color: var(--bg-color);
        background-image: 
            radial-gradient(at 0% 0%, rgba(2, 132, 199, 0.06) 0px, transparent 50%),
            radial-gradient(at 100% 100%, rgba(56, 189, 248, 0.05) 0px, transparent 50%);
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--text-main);
        overflow-x: hidden;
    }

    .container {
        max-width: 1200px;
        margin-top: 40px;
        margin-bottom: 80px;
    }

    /* Page Header */
    .page-title {
        font-size: 34px;
        font-weight: 800;
        color: var(--text-main);
        letter-spacing: -1px;
    }

    .page-subtitle {
        color: var(--text-muted);
        font-size: 15px;
        margin-bottom: 30px;
    }

    /* Ultra Modern Glassmorphism Cards */
    .content-card {
        background: var(--card-bg);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-radius: 28px;
        border: 1px solid var(--border-color);
        box-shadow: 0 10px 30px -5px rgba(2, 132, 199, 0.03);
        padding: 32px;
        height: 100%;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .content-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px -10px rgba(2, 132, 199, 0.12);
        border-color: rgba(2, 132, 199, 0.3);
    }

    /* Profile Top Banner Card */
    .profile-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(12px);
        border-radius: 32px;
        border: 1px solid var(--border-color);
        overflow: hidden;
        box-shadow: 0 15px 35px -5px rgba(2, 132, 199, 0.04);
        margin-bottom: 28px;
        transition: all 0.4s ease;
    }

    .profile-header {
        background: linear-gradient(135deg, rgba(224, 242, 254, 0.8) 0%, rgba(186, 230, 253, 0.4) 100%);
        padding: 45px;
        position: relative;
    }

    .profile-photo-wrapper {
        position: relative;
        display: inline-block;
    }

    .profile-photo {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid white;
        box-shadow: 0 12px 30px rgba(2, 132, 199, 0.2);
        transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .profile-photo:hover {
        transform: scale(1.05) rotate(2deg);
    }

    /* Pulsing Online Status Indicator */
    .status-badge {
        position: absolute;
        bottom: 8px;
        right: 8px;
        width: 22px;
        height: 22px;
        background: #0ea5e9;
        border: 3px solid white;
        border-radius: 50%;
        animation: pulse-ring 2s infinite;
    }

    @keyframes pulse-ring {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(14, 165, 233, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(14, 165, 233, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(14, 165, 233, 0); }
    }

    .profile-name {
        font-size: 32px;
        font-weight: 800;
        color: var(--text-main);
        letter-spacing: -0.5px;
        margin-bottom: 6px;
    }

    .badge-freelancer {
        background: var(--primary-gradient);
        color: white;
        padding: 7px 18px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        display: inline-block;
        box-shadow: 0 6px 15px rgba(2, 132, 199, 0.3);
        margin-bottom: 14px;
    }

    .profile-info {
        color: var(--text-muted);
        font-size: 14px;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
    }

    .profile-info i {
        color: var(--primary-color);
        width: 18px;
        text-align: center;
    }

    /* Animated Buttons */
    .btn-custom-primary {
        background: var(--primary-gradient);
        border: none;
        color: white;
        border-radius: 16px;
        padding: 12px 28px;
        font-weight: 700;
        font-size: 14px;
        box-shadow: 0 8px 20px rgba(2, 132, 199, 0.3);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .btn-custom-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 25px rgba(2, 132, 199, 0.45);
        color: white;
    }

    .btn-back {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(8px);
        border: 1px solid var(--border-color);
        color: var(--text-muted);
        border-radius: 14px;
        padding: 9px 22px;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-back:hover {
        background: #ffffff;
        color: var(--text-main);
        transform: translateX(-4px);
        border-color: #7dd3fc;
    }

    /* Enhanced Interactive Stat Box (Blue Theme) */
    .stat-box {
        text-align: center;
        padding: 24px 15px;
        border-right: 1px solid var(--border-color);
        background: rgba(255, 255, 255, 0.6);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        text-decoration: none;
        display: block;
    }

    .stat-box:hover {
        background: rgba(255, 255, 255, 1);
        transform: translateY(-3px);
        box-shadow: inset 0 4px 0 0 #0284c7;
    }

    .stat-box:last-child {
        border-right: none;
    }

    .stat-icon {
        font-size: 20px;
        color: #0284c7;
        margin-bottom: 6px;
        background: rgba(2, 132, 199, 0.1);
        width: 42px;
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        transition: transform 0.3s ease;
    }

    .stat-box:hover .stat-icon {
        transform: scale(1.1) rotate(5deg);
        background: var(--primary-gradient);
        color: white;
    }

    .stat-title {
        color: var(--text-muted);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .stat-value {
        margin-top: 4px;
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
    }

    /* Section Titles */
    .content-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Smooth Animated Progress Bar */
    .progress {
        height: 14px;
        border-radius: 20px;
        background-color: rgba(224, 242, 254, 0.8);
        overflow: hidden;
        padding: 2px;
    }

    .progress-bar {
        border-radius: 20px;
        background: var(--primary-gradient);
        transition: width 1.5s cubic-bezier(0.1, 1, 0.1, 1);
    }

    /* Interactive Skill Badges */
    .skill-badge {
        background: rgba(2, 132, 199, 0.08);
        color: #0284c7;
        border: 1px solid rgba(2, 132, 199, 0.2);
        padding: 8px 18px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: inline-block;
    }

    .skill-badge:hover {
        background: var(--primary-gradient);
        color: white;
        transform: translateY(-4px) scale(1.05);
        box-shadow: 0 6px 15px rgba(2, 132, 199, 0.3);
    }

    /* Review Card Styling */
    .review-item {
        background: rgba(240, 249, 255, 0.8);
        border-radius: 20px;
        border: 1px solid var(--border-color);
        padding: 24px;
        transition: all 0.3s ease;
    }
    
    .review-item:hover {
        background: #ffffff;
        border-color: rgba(2, 132, 199, 0.3);
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(2, 132, 199, 0.05);
    }
</style>
</head>

<body>

@php
$total = 7;
$isi = 0;
if($profile->photo) $isi++;
if($profile->bio) $isi++;
if($profile->skills) $isi++;
if($profile->experience) $isi++;
if($profile->portfolio_link) $isi++;
if($profile->cv) $isi++;
if($profile->location) $isi++;
$progress = round(($isi / $total) * 100);
@endphp

<div class="container">

    <!-- Tombol Back Dinamis -->
    <div class="mb-4" data-aos="fade-down" data-aos-duration="600">
        @if(isset($isViewOnly) && $isViewOnly)
            <a href="{{ url()->previous() }}" class="btn btn-back shadow-sm">
                <i class="fa-solid fa-arrow-left me-2"></i> Kembali
            </a>
        @else
            <a href="{{ route('freelancer.dashboard') }}" class="btn btn-back shadow-sm">
                <i class="fa-solid fa-arrow-left me-2"></i> Kembali ke Dashboard
            </a>
        @endif
    </div>

    <!-- Header Title -->
    <div class="mb-4" data-aos="fade-up" data-aos-duration="800">
        <h1 class="page-title">{{ isset($isViewOnly) && $isViewOnly ? 'Profil Freelancer' : 'Profil Saya' }}</h1>
        <p class="page-subtitle">
            {{ isset($isViewOnly) && $isViewOnly ? 'Detail informasi dan portofolio freelancer.' : 'Kelola informasi data diri, keahlian, dan pantau performa Anda secara real-time.' }}
        </p>
    </div>

    <!-- MAIN PROFILE CARD -->
    <div class="profile-card" data-aos="fade-up" data-aos-duration="1000">
        <div class="profile-header">
            <div class="row align-items-center">
                <!-- FOTO DENGAN PULSE ANIMATION -->
                <div class="col-lg-2 text-center mb-4 mb-lg-0">
                    <div class="profile-photo-wrapper">
                        @if($profile->photo)
                            <img src="{{ asset('storage/'.$profile->photo) }}" class="profile-photo">
                        @else
                            <img src="{{ asset('images/default-profile.png') }}" class="profile-photo" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0284c7&color=fff&size=140'">
                        @endif
                        <div class="status-badge" title="Aktif / Online"></div>
                    </div>
                </div>

                <!-- BIODATA -->
                <div class="col-lg-7 text-center text-lg-start">
                    <h2 class="profile-name">{{ $user->name }}</h2>
                    <div>
                        <span class="badge-freelancer"><i class="fa-solid fa-circle-check me-1"></i> Verified Freelancer</span>
                    </div>

                    <div class="profile-info justify-content-center justify-content-lg-start">
                        <i class="fa-solid fa-location-dot"></i>
                        <span>{{ $profile->location ?: 'Belum mengisi lokasi' }}</span>
                    </div>
                    <div class="profile-info justify-content-center justify-content-lg-start">
                        <i class="fa-solid fa-envelope"></i>
                        <span>{{ $user->email }}</span>
                    </div>
                    <div class="profile-info justify-content-center justify-content-lg-start">
                        <i class="fa-solid fa-phone"></i>
                        <span>{{ $user->phone ?: 'Belum mengisi nomor HP' }}</span>
                    </div>
                    <div class="profile-info justify-content-center justify-content-lg-start">
                        <i class="fa-solid fa-calendar-days"></i>
                        <span>Bergabung sejak {{ $user->created_at->translatedFormat('d F Y') }}</span>
                    </div>
                </div>

                <!-- TOMBOL EDIT / VIEW MODE -->
                <div class="col-lg-3 text-center text-lg-end mt-4 mt-lg-0">
                    @if(isset($isViewOnly) && $isViewOnly)
                        <span class="badge bg-light text-primary border border-primary px-3 py-2 rounded-pill shadow-sm" style="font-size: 13px;">
                            <i class="fa-solid fa-eye me-1"></i> Mode Lihat Profil
                        </span>
                    @else
                        <a href="{{ route('freelancer.profile.edit') }}" class="btn btn-custom-primary">
                            <i class="fa-solid fa-pen-to-square me-2"></i> Edit Profil
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- STATISTIK / METRIK CARD -->
        <div class="row g-0">
            <div class="col stat-box">
                <div class="stat-icon"><i class="fa-solid fa-star"></i></div>
                <div class="stat-title">Rating Performa</div>
                <div class="stat-value text-warning">{{ number_format($averageRating ?? 0, 1) }} <small class="text-muted fs-6">/5.0</small></div>
            </div>
            <div class="col stat-box">
                <div class="stat-icon"><i class="fa-solid fa-comments"></i></div>
                <div class="stat-title">Total Ulasan</div>
                <div class="stat-value">{{ $totalReview ?? 0 }} <small class="text-muted fs-6">Klien</small></div>
            </div>
            <div class="col stat-box">
                <div class="stat-icon"><i class="fa-solid fa-code"></i></div>
                <div class="stat-title">Keahlian</div>
                <div class="stat-value">{{ $profile->skills ? count(explode(',',$profile->skills)) : 0 }} <small class="text-muted fs-6">Skill</small></div>
            </div>
            <div class="col stat-box">
                <div class="stat-icon"><i class="fa-solid fa-circle-check"></i></div>
                <div class="stat-title">Status Akun</div>
                <div class="stat-value text-success fs-5 fw-bold mt-1">Aktif</div>
            </div>
        </div>
    </div>

    <!-- TENTANG & KEAHLIAN -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8" data-aos="fade-right" data-aos-duration="1000">
            <div class="content-card">
                <h4 class="content-title">
                    <i class="fa-solid fa-user-pen" style="color: #0284c7;"></i> Tentang Saya
                </h4>

                @if($profile->bio)
                    <p class="text-secondary" style="font-size: 15px; line-height: 1.8;">{{ $profile->bio }}</p>
                @else
                    <p class="text-muted fst-italic">Belum ada deskripsi profil yang ditambahkan.</p>
                @endif

                <hr class="my-4" style="border-color: rgba(186, 230, 253, 0.6);">

                <div class="row g-3">
                    <div class="col-md-6">
                        <span class="d-block text-muted small mb-1 fw-semibold">Nama Lengkap</span>
                        <strong class="text-dark">{{ $user->name }}</strong>
                    </div>
                    <div class="col-md-6">
                        <span class="d-block text-muted small mb-1 fw-semibold">Email Utama</span>
                        <strong class="text-dark">{{ $user->email }}</strong>
                    </div>
                    <div class="col-md-6">
                        <span class="d-block text-muted small mb-1 fw-semibold">Nomor HP / WhatsApp</span>
                        <strong class="text-dark">{{ $user->phone ?: '-' }}</strong>
                    </div>
                    <div class="col-md-6">
                        <span class="d-block text-muted small mb-1 fw-semibold">Lokasi Domisili</span>
                        <strong class="text-dark">{{ $profile->location ?: '-' }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4" data-aos="fade-left" data-aos-duration="1000">
            <div class="content-card">
                <h4 class="content-title">
                    <i class="fa-solid fa-code" style="color: #0284c7;"></i> Keahlian
                </h4>

                @if($profile->skills)
                    <div class="d-flex flex-wrap gap-2 mt-3">
                        @foreach(explode(',', $profile->skills) as $skill)
                            <span class="skill-badge">
                                {{ trim($skill) }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted fst-italic">Belum menambahkan skill/keahlian.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- PORTOFOLIO & CV -->
    <div class="row g-4 mb-4" data-aos="fade-up" data-aos-duration="1000">
        <div class="col-lg-6">
            <div class="content-card d-flex flex-column justify-content-between">
                <div>
                    <h4 class="content-title">
                        <i class="fa-solid fa-folder-open text-info"></i> Portofolio
                    </h4>
                    <p class="text-muted small">Tampilkan hasil karya terbaik Anda kepada klien.</p>
                </div>
                <div class="mt-3">
                    @if($profile->portfolio_link)
                        <a href="{{ $profile->portfolio_link }}" target="_blank" class="btn btn-outline-info text-dark rounded-pill px-4 fw-bold w-100 py-2 border-info transition-all">
                            <i class="fa-solid fa-arrow-up-right-from-square me-2"></i> Buka Link Portofolio
                        </a>
                    @else
                        <div class="p-3 bg-light rounded-3 text-muted small text-center fst-italic">Tautan portofolio belum diatur.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="content-card d-flex flex-column justify-content-between">
                <div>
                    <h4 class="content-title">
                        <i class="fa-solid fa-file-arrow-down text-primary"></i> Curriculum Vitae (CV)
                    </h4>
                    <p class="text-muted small">Unduh dokumen CV terbaru Anda untuk keperluan review.</p>
                </div>
                <div class="mt-3">
                    @if($profile->cv)
                        <a href="{{ asset('storage/'.$profile->cv) }}" target="_blank" class="btn btn-primary rounded-pill px-4 fw-bold w-100 py-2 text-white shadow-sm" style="background: var(--primary-gradient); border:none;">
                            <i class="fa-solid fa-download me-2"></i> Unduh Berkas CV
                        </a>
                    @else
                        <div class="p-3 bg-light rounded-3 text-muted small text-center fst-italic">Belum ada file CV yang diunggah.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ULASAN & RATING -->
    <div class="row g-4 mb-4" data-aos="fade-up" data-aos-duration="1000">
        <div class="col-lg-12">
            <div class="content-card">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-3 border-bottom">
                    <h4 class="content-title mb-2 mb-md-0">
                        <i class="fa-solid fa-star text-warning"></i> Ulasan & Rating dari Perusahaan
                    </h4>
                    <div class="d-flex align-items-center gap-3 bg-light px-4 py-2 rounded-pill border">
                        <span class="fw-extrabold text-dark" style="font-size: 24px;">{{ number_format($averageRating ?? 0, 1) }}</span>
                        <div class="text-warning small">
                            @for($i=1; $i<=5; $i++)
                                <i class="fa-{{ $i <= round($averageRating ?? 0) ? 'solid' : 'regular' }} fa-star"></i>
                            @endfor
                            <span class="text-muted ms-2 fw-semibold">({{ $totalReview ?? 0 }} Ulasan)</span>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    @forelse($reviews ?? [] as $review)
                        <div class="col-md-6">
                            <div class="review-item h-100">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fw-bold text-dark mb-0">{{ $review->project->project_name ?? 'Project Selesai' }}</h6>
                                    <small class="text-muted fw-semibold" style="font-size: 11px;">{{ $review->created_at->translatedFormat('d M Y') }}</small>
                                </div>
                                <div class="text-warning small mb-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star"></i>
                                    @endfor
                                    <span class="text-muted ms-2 small">oleh <strong class="text-dark">{{ $review->client->name ?? 'Perusahaan' }}</strong></span>
                                </div>
                                @if ($review->review)
                                    <p class="text-secondary fst-italic mb-0 small bg-white p-3 rounded-3 border-0 shadow-sm">"{{ $review->review }}"</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5 text-muted">
                                <i class="fa-regular fa-face-smile fa-3x mb-3 text-slate-300"></i>
                                <p class="mb-0 fw-semibold">Belum ada ulasan atau rating yang diterima dari perusahaan.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- PROGRESS PROFIL (Hanya tampil untuk pemilik akun / freelancer sendiri) -->
    @if(!($isViewOnly ?? false))
    <div class="row" data-aos="fade-up" data-aos-duration="1000">
        <div class="col-lg-12">
            <div class="content-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="content-title mb-0">
                        <i class="fa-solid fa-chart-line" style="color: #0284c7;"></i> Progress Kelengkapan Profil
                    </h4>
                    <h3 class="fw-extrabold mb-0" style="color: #0284c7 !important;">{{ $progress }}%</h3>
                </div>

                <div class="progress mb-4 shadow-inner">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" style="width:{{ $progress }}%"></div>
                </div>

                <div class="row g-3 text-sm">
                    <div class="col-6 col-md-3">
                        @if($profile->photo) <span class="text-success fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Foto Profil</span> @else <span class="text-muted"><i class="fa-regular fa-circle me-1"></i> Foto Profil</span> @endif
                    </div>
                    <div class="col-6 col-md-3">
                        @if($profile->bio) <span class="text-success fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Tentang Saya</span> @else <span class="text-muted"><i class="fa-regular fa-circle me-1"></i> Tentang Saya</span> @endif
                    </div>
                    <div class="col-6 col-md-3">
                        @if($profile->skills) <span class="text-success fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Keahlian</span> @else <span class="text-muted"><i class="fa-regular fa-circle me-1"></i> Keahlian</span> @endif
                    </div>
                    <div class="col-6 col-md-3">
                        @if($profile->experience) <span class="text-success fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Pengalaman</span> @else <span class="text-muted"><i class="fa-regular fa-circle me-1"></i> Pengalaman</span> @endif
                    </div>
                    <div class="col-6 col-md-3">
                        @if($profile->portfolio_link) <span class="text-success fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Portofolio</span> @else <span class="text-muted"><i class="fa-regular fa-circle me-1"></i> Portofolio</span> @endif
                    </div>
                    <div class="col-6 col-md-3">
                        @if($profile->cv) <span class="text-success fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Dokumen CV</span> @else <span class="text-muted"><i class="fa-regular fa-circle me-1"></i> Dokumen CV</span> @endif
                    </div>
                    <div class="col-6 col-md-3">
                        @if($profile->location) <span class="text-success fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Lokasi</span> @else <span class="text-muted"><i class="fa-regular fa-circle me-1"></i> Lokasi</span> @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- AOS Animation Library JS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  // Inisialisasi library AOS untuk animasi scroll yang smooth
  AOS.init({
    once: true, 
    offset: 50,  
    easing: 'ease-out-cubic'
  });
</script>
</body>
</html>