<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Perusahaan - Ultra Modern Dashboard</title>

    <!-- Bootstrap 5.3 & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- AOS Animation Library CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<style>
    :root {
        --primary-color: #0284c7;
        --primary-gradient: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%);
        --accent-gradient: linear-gradient(135deg, #059669 100%, #34d399 0%);
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

    /* Back Button */
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
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-back:hover {
        background: #ffffff;
        color: var(--text-main);
        transform: translateX(-4px);
        border-color: #7dd3fc;
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
    }

    .profile-header {
        background: linear-gradient(135deg, rgba(224, 242, 254, 0.8) 0%, rgba(186, 230, 253, 0.4) 100%);
        padding: 45px;
        position: relative;
    }

    .company-logo {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid white;
        box-shadow: 0 12px 30px rgba(2, 132, 199, 0.2);
        transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .company-logo:hover {
        transform: scale(1.05) rotate(2deg);
    }

    .company-name {
        font-size: 32px;
        font-weight: 800;
        color: var(--text-main);
        letter-spacing: -0.5px;
        margin-bottom: 6px;
    }

    .company-type {
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

    .info {
        color: var(--text-muted);
        font-size: 14px;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
    }

    .info i {
        color: var(--primary-color);
        width: 18px;
        text-align: center;
    }

    .edit-btn {
        background: var(--primary-gradient);
        border: none;
        color: white;
        border-radius: 16px;
        padding: 12px 28px;
        font-weight: 700;
        font-size: 14px;
        box-shadow: 0 8px 20px rgba(2, 132, 199, 0.3);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        text-decoration: none;
        display: inline-block;
    }

    .edit-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 25px rgba(2, 132, 199, 0.45);
        color: white;
    }

    .rate-badge-top {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-muted);
        background: rgba(255, 255, 255, 0.7);
        padding: 8px 16px;
        border-radius: 12px;
        display: inline-block;
        border: 1px solid var(--border-color);
        margin-bottom: 15px;
    }

    /* Modern Glassmorphism Stat Cards */
    .stat-card {
        background: var(--card-bg);
        backdrop-filter: blur(12px);
        border-radius: 24px;
        border: 1px solid var(--border-color);
        padding: 26px 20px;
        text-align: center;
        box-shadow: 0 10px 30px -5px rgba(2, 132, 199, 0.04);
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
        height: 100%;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: var(--primary-gradient);
        opacity: 0.8;
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px -10px rgba(2, 132, 199, 0.15);
        border-color: rgba(2, 132, 199, 0.4);
        background: #ffffff;
    }

    .stat-icon {
        width: 52px;
        height: 52px;
        background: rgba(2, 132, 199, 0.08);
        color: var(--primary-color);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin: 0 auto 16px auto;
        transition: transform 0.3s ease;
    }

    .stat-card:hover .stat-icon {
        transform: scale(1.1) rotate(5deg);
        background: var(--primary-gradient);
        color: white;
    }

    .stat-card h3 {
        font-size: 26px;
        font-weight: 800;
        color: var(--text-main);
        margin-bottom: 6px;
        letter-spacing: -0.5px;
    }

    .stat-card span {
        color: var(--text-muted);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: block;
    }

    /* Content Cards */
    .content-card {
        background: var(--card-bg);
        backdrop-filter: blur(12px);
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

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        color: var(--primary-color);
    }

    .table td, .table th {
        padding: 14px 12px;
        background: transparent !important;
        border-color: var(--border-color) !important;
        color: var(--text-main);
    }

    .table th {
        width: 160px;
        font-weight: 600;
        color: var(--text-muted);
    }

    .website-btn {
        background: var(--primary-gradient);
        border: none;
        padding: 12px 25px;
        border-radius: 14px;
        font-weight: 700;
        box-shadow: 0 6px 15px rgba(2, 132, 199, 0.25);
        transition: all 0.3s ease;
    }

    .website-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(2, 132, 199, 0.35);
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
</style>
</head>

<body>

<div class="container">

    <!-- Tombol Kembali ke Dashboard -->
    <div class="mb-4" data-aos="fade-down" data-aos-duration="600">
        <a href="{{ route('company.dashboard') }}" class="btn btn-back">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    <!-- Header Title -->
    <div class="mb-4" data-aos="fade-up" data-aos-duration="800">
        <h1 class="page-title">Profil Perusahaan</h1>
        <p class="page-subtitle">Kelola informasi perusahaan Anda agar freelancer semakin percaya dan tertarik bergabung.</p>
    </div>

    <!-- MAIN PROFILE CARD -->
    <div class="profile-card" data-aos="fade-up" data-aos-duration="1000">
        <div class="profile-header">
            <div class="row align-items-center">
                <!-- LOGO PERUSAHAAN -->
                <div class="col-lg-2 text-center mb-4 mb-lg-0">
                    @if(isset($profile->company_logo) && $profile->company_logo)
                        <img src="{{ asset('storage/'.$profile->company_logo) }}" class="company-logo" alt="Logo Perusahaan">
                    @else
                        <img src="{{ asset('images/company.png') }}" class="company-logo" alt="Logo Perusahaan" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($profile->company_name ?? 'Company') }}&background=0284c7&color=fff&size=140';">
                    @endif
                </div>

                <!-- DETAIL PERUSAHAAN -->
                <div class="col-lg-7 text-center text-lg-start">
                    <h2 class="company-name">{{ $profile->company_name ?? 'Nama Perusahaan' }}</h2>
                    <div>
                        <span class="company-type">
                            <i class="bi bi-patch-check-fill me-1"></i> Client Terverifikasi
                        </span>
                    </div>

                    <div class="info justify-content-center justify-content-lg-start">
                        <i class="bi bi-geo-alt-fill"></i>
                        <span>{{ $profile->location ?? 'Belum mengisi lokasi' }}</span>
                    </div>
                    <div class="info justify-content-center justify-content-lg-start">
                        <i class="bi bi-envelope-fill"></i>
                        <span>{{ Auth::user()->email }}</span>
                    </div>
                    <div class="info justify-content-center justify-content-lg-start">
                        <i class="bi bi-telephone-fill"></i>
                        <span>{{ $profile->phone ?? 'Belum mengisi nomor telepon' }}</span>
                    </div>
                    <div class="info justify-content-center justify-content-lg-start">
                        <i class="bi bi-calendar-event-fill"></i>
                        <span>Bergabung sejak {{ Auth::user()->created_at->translatedFormat('d F Y') }}</span>
                    </div>
                </div>

                <!-- BIDANG & TOMBOL EDIT -->
                <div class="col-lg-3 text-center text-lg-end mt-4 mt-lg-0">
                    <div class="rate-badge-top">
                        <i class="bi bi-briefcase me-1 text-primary"></i> {{ $profile->industry ?? 'Bidang Usaha' }}
                    </div>
                    <br>
                    <a href="{{ route('company.profile.edit') }}" class="edit-btn">
                        <i class="bi bi-pencil-square me-2"></i> Edit Profil
                    </a>
                </div>
            </div> <!-- Close row -->
        </div> <!-- Close profile-header -->
    </div> <!-- Close profile-card -->

    <!-- KARTU STATISTIK SPESIFIK & MODERN -->
    <div class="row g-4 mb-4" data-aos="fade-up" data-aos-duration="900">
        <div class="col-md-3 col-6">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-folder2-open"></i>
                </div>
                <h3>{{ $totalProjects ?? 0 }}</h3>
                <span>Project Dibuka</span>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-check2-circle"></i>
                </div>
                <h3>{{ $completedProjects ?? 0 }}</h3>
                <span>Project Selesai</span>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <h3>{{ $paymentRate ?? '100%' }}</h3>
                <span>Ketepatan Bayar</span>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-shield-check"></i>
                </div>
                <h3>{{ $successRate ?? '0%' }}</h3>
                <span>Keberhasilan</span>
            </div>
        </div>
    </div>

    <!-- TENTANG PERUSAHAAN -->
    <div class="row mt-4" data-aos="fade-up" data-aos-duration="1000">
        <div class="col-lg-12 mb-4">
            <div class="content-card">
                <div class="section-title">
                    <i class="bi bi-building"></i> Tentang Perusahaan
                </div>
                @if(isset($profile->description) && $profile->description)
                    <p class="text-secondary lh-lg fs-6 mb-0">{{ $profile->description }}</p>
                @else
                    <div class="alert alert-light border fst-italic text-muted mb-0">
                        Belum ada deskripsi perusahaan yang ditambahkan.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- INFORMASI DETAIL & WEBSITE -->
    <div class="row g-4" data-aos="fade-up" data-aos-duration="1000">
        <!-- Informasi Perusahaan -->
        <div class="col-lg-6">
            <div class="content-card">
                <div class="section-title">
                    <i class="bi bi-info-circle-fill"></i> Informasi Detail Perusahaan
                </div>
                <table class="table table-borderless align-middle mb-0">
                    <tr>
                        <th>Nama</th>
                        <td><strong>{{ $profile->company_name ?? '-' }}</strong></td>
                    </tr>
                    <tr>
                        <th>Bidang</th>
                        <td><strong>{{ $profile->industry ?? '-' }}</strong></td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td><strong>{{ $profile->location ?? '-' }}</strong></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><strong>{{ Auth::user()->email }}</strong></td>
                    </tr>
                    <tr>
                        <th>Telepon</th>
                        <td><strong>{{ $profile->phone ?? '-' }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Website & Atribut Tambahan -->
        <div class="col-lg-6">
            <div class="content-card">
                <div class="section-title">
                    <i class="bi bi-globe2"></i> Website & Atribut
                </div>

                @if(isset($profile->website) && $profile->website)
                    <div class="mb-3">
                        <a href="{{ \Illuminate\Support\Str::startsWith($profile->website, ['http://', 'https://']) ? $profile->website : 'https://' . $profile->website }}" target="_blank" class="btn btn-primary website-btn w-100 text-white text-center">
                            <i class="bi bi-box-arrow-up-right me-2"></i> Kunjungi Website Resmi
                        </a>
                    </div>
                    <div class="p-3 bg-light rounded-3 text-muted small text-break border">
                        <i class="bi bi-link-45deg me-1"></i> {{ $profile->website }}
                    </div>
                @else
                    <div class="alert alert-warning border-0 shadow-sm mb-4">
                        <i class="bi bi-exclamation-circle me-2"></i> Website perusahaan belum ditambahkan.
                    </div>
                @endif

                <hr class="my-4" style="border-color: var(--border-color);">

                <div class="row g-3">
                    <div class="col-6">
                        <span class="d-block text-muted small mb-1 fw-semibold">Bidang Usaha</span>
                        @if(isset($profile->industry) && $profile->industry)
                            <span class="badge bg-primary px-3 py-2 rounded-pill fw-semibold" style="background: var(--primary-gradient) !important;">
                                {{ $profile->industry }}
                            </span>
                        @else
                            <span class="text-muted fst-italic small">Belum diisi</span>
                        @endif
                    </div>
                    <div class="col-6">
                        <span class="d-block text-muted small mb-1 fw-semibold">Tanggal Bergabung</span>
                        <strong class="text-dark small"><i class="bi bi-calendar-event me-1 text-primary"></i> {{ Auth::user()->created_at->format('d M Y') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PROGRESS KELENGKAPAN PROFIL -->
    <div class="row mt-4" data-aos="fade-up" data-aos-duration="1000">
        <div class="col-lg-12">
            <div class="content-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="section-title mb-0">
                        <i class="bi bi-bar-chart-line"></i> Progress Kelengkapan Profil
                    </h4>
                    <h3 class="fw-extrabold mb-0" style="color: #0284c7 !important;">{{ profile_completion_percentage() }}%</h3>
                </div>

                <div class="progress mb-4 shadow-inner">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" style="width:{{ profile_completion_percentage() }}%"></div>
                </div>

                @php
                    $missing = get_missing_profile_fields();
                    $isComplete = is_profile_complete();
                @endphp

                @if($isComplete)
                    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-0">
                        <i class="bi bi-check-circle-fill me-2"></i> Profil Anda sudah lengkap. Anda dapat menggunakan semua fitur aplikasi.
                    </div>
                @else
                    <div class="alert alert-warning border-0 shadow-sm rounded-4 mb-3">
                        <i class="bi bi-exclamation-circle-fill me-2"></i> Lengkapi minimal 80% profil untuk membuat proyek, memilih freelancer, dan fitur lainnya.
                    </div>
                @endif

                <div class="row g-3 text-sm">
                    @php
                        $fields = [
                            ['key' => 'name', 'label' => 'Nama Lengkap', 'check' => Auth::user()->name],
                            ['key' => 'email', 'label' => 'Email', 'check' => Auth::user()->email],
                            ['key' => 'phone', 'label' => 'Nomor Telepon', 'check' => Auth::user()->phone],
                            ['key' => 'location', 'label' => 'Lokasi', 'check' => $profile->location ?? null],
                            ['key' => 'company_name', 'label' => 'Nama Perusahaan', 'check' => $profile->company_name ?? null],
                        ];
                    @endphp
                    @foreach($fields as $field)
                        <div class="col-6 col-md-2">
                            @if($field['check'])
                                <span class="text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i> {{ $field['label'] }}</span>
                            @else
                                <span class="text-muted"><i class="bi bi-circle me-1"></i> {{ $field['label'] }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

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