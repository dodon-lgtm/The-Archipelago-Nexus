<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil Freelancer - Modern Dashboard</title>

    <!-- Bootstrap 5.3 & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
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
        max-width: 1100px;
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
    .card-custom {
        background: var(--card-bg);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--border-color);
        border-radius: 28px;
        box-shadow: 0 10px 30px -5px rgba(2, 132, 199, 0.03);
        margin-bottom: 28px;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        overflow: hidden;
    }

    .card-custom:hover {
        box-shadow: 0 20px 40px -10px rgba(2, 132, 199, 0.1);
        border-color: rgba(2, 132, 199, 0.3);
    }

    .card-header-custom {
        background: linear-gradient(135deg, rgba(224, 242, 254, 0.6) 0%, rgba(186, 230, 253, 0.2) 100%);
        padding: 22px 32px;
        border-bottom: 1px solid var(--border-color);
        font-size: 18px;
        font-weight: 700;
        color: var(--text-main);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .card-header-custom i {
        color: var(--primary-color);
        font-size: 20px;
    }

    .card-body-custom {
        padding: 32px;
    }

    /* Profile Preview */
    .profile-preview {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid white;
        box-shadow: 0 12px 30px rgba(2, 132, 199, 0.2);
        transition: transform 0.4s ease;
    }

    .profile-preview:hover {
        transform: scale(1.05);
    }

    /* Form Controls Styling */
    .form-control, .form-select {
        background-color: rgba(255, 255, 255, 0.9);
        border: 1px solid var(--border-color);
        border-radius: 14px;
        padding: 13px 18px;
        font-size: 14px;
        color: var(--text-main);
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        background-color: #ffffff;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.15);
    }

    .form-label {
        font-weight: 700;
        font-size: 13px;
        color: var(--text-main);
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    textarea.form-control {
        resize: none;
    }

    /* Buttons */
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

    .btn-secondary-custom {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(8px);
        border: 1px solid var(--border-color);
        color: var(--text-muted);
        border-radius: 16px;
        padding: 12px 28px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .btn-secondary-custom:hover {
        background: #ffffff;
        color: var(--text-main);
        border-color: #7dd3fc;
        transform: translateY(-2px);
    }

    .btn-outline-custom {
        background: transparent;
        border: 1px solid var(--primary-color);
        color: var(--primary-color);
        border-radius: 12px;
        padding: 8px 18px;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.3s ease;
    }

    .btn-outline-custom:hover {
        background: var(--primary-gradient);
        color: white;
        border-color: transparent;
    }

    /* Alert Styling */
    .alert {
        border-radius: 16px;
        border: none;
        padding: 16px 20px;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    }

    .alert-success {
        background-color: #d1fae5;
        color: #065f46;
    }

    .alert-danger {
        background-color: #fee2e2;
        color: #991b1b;
    }
</style>
<style>

/* ApexForge Labs — Unified UI System */
:root{
    --af-primary:#2563eb;
    --af-primary-dark:#1d4ed8;
    --af-primary-soft:#eff6ff;
    --af-sky:#38bdf8;
    --af-ink:#0f172a;
    --af-muted:#64748b;
    --af-border:#dbeafe;
    --af-surface:#ffffff;
    --af-page:#f6f9ff;
}
html{scroll-behavior:smooth}
body{
    font-family:'Plus Jakarta Sans',sans-serif;
    background:
        radial-gradient(circle at 10% -10%,rgba(56,189,248,.10),transparent 30%),
        radial-gradient(circle at 100% 0%,rgba(37,99,235,.08),transparent 28%),
        var(--af-page);
}
::selection{background:rgba(37,99,235,.18);color:#0f172a}
::-webkit-scrollbar{width:7px;height:7px}
::-webkit-scrollbar-track{background:rgba(241,245,249,.7)}
::-webkit-scrollbar-thumb{background:rgba(37,99,235,.22);border-radius:999px}
::-webkit-scrollbar-thumb:hover{background:rgba(37,99,235,.38)}

input,select,textarea{
    border-color:var(--af-border)!important;
    background:rgba(255,255,255,.92);
    transition:border-color .2s ease,box-shadow .2s ease,background .2s ease;
}
input:focus,select:focus,textarea:focus{
    border-color:rgba(37,99,235,.55)!important;
    box-shadow:0 0 0 4px rgba(37,99,235,.09)!important;
    outline:none!important;
}
button,a,[role="button"]{transition:all .2s ease}
button:focus-visible,a:focus-visible,[role="button"]:focus-visible{
    outline:2px solid rgba(37,99,235,.55);
    outline-offset:2px;
}
table{border-collapse:separate;border-spacing:0}
thead th{
    background:rgba(239,246,255,.72)!important;
    color:#334155;
    font-weight:700;
}
tbody tr{transition:background .18s ease}
tbody tr:hover{background:rgba(239,246,255,.48)}
[class*="bg-blue-600"]{
    box-shadow:0 8px 22px -12px rgba(37,99,235,.72);
}
[class*="bg-blue-600"]:hover{
    box-shadow:0 12px 28px -12px rgba(37,99,235,.78);
    transform:translateY(-1px);
}
.glass-panel,.glass-card,.glass-surface{
    background:rgba(255,255,255,.72);
    border:1px solid rgba(219,234,254,.85);
    backdrop-filter:blur(18px);
    -webkit-backdrop-filter:blur(18px);
    box-shadow:0 18px 50px -32px rgba(30,64,175,.32);
}
.apex-page-glow{
    position:fixed;inset:auto -10rem -12rem auto;width:28rem;height:28rem;
    background:rgba(56,189,248,.09);filter:blur(70px);border-radius:999px;
    pointer-events:none;z-index:-1;
}
@media (max-width:767px){
    main{padding-left:1rem!important;padding-right:1rem!important}
    table{min-width:680px}
    .overflow-x-auto{-webkit-overflow-scrolling:touch}
}
@media (prefers-reduced-motion:reduce){
    *,*::before,*::after{animation-duration:.01ms!important;animation-iteration-count:1!important;transition-duration:.01ms!important;scroll-behavior:auto!important}
}

</style>
</head>

<body>

<div class="container">

    <!-- Header Title -->
    <div class="mb-4" data-aos="fade-down" data-aos-duration="600">
        <h1 class="page-title">Edit Profil</h1>
        <p class="page-subtitle">Lengkapi informasi profil agar lebih menarik dan dipercaya oleh klien.</p>
    </div>

    <!-- Alert Notifikasi -->
    @if(session('success'))
        <div class="alert alert-success mb-4" data-aos="fade-up">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger mb-4" data-aos="fade-up">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li><i class="fa-solid fa-triangle-exclamation me-2"></i> {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('freelancer.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- FOTO PROFIL CARD -->
        <div class="card-custom" data-aos="fade-up" data-aos-duration="800">
            <div class="card-header-custom">
                <i class="fa-solid fa-camera"></i> Foto Profil
            </div>
            <div class="card-body-custom text-center">
                <div class="mb-4">
                    @if($profile->photo)
                        <img id="preview" src="{{ asset('storage/'.$profile->photo) }}" alt="Foto profil {{ Auth::user()->name }}" class="profile-preview">
                    @else
                        <img id="preview" src="{{ asset('images/default-profile.png') }}" alt="Foto profil {{ Auth::user()->name }}" class="profile-preview" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0284c7&color=fff&size=150'">
                    @endif
                </div>
                <div class="mb-2 col-md-6 mx-auto">
                    <input type="file" name="photo" id="photo" class="form-control">
                </div>
                <small class="text-muted d-block">Format yang didukung: JPG, JPEG, PNG. Ukuran maksimal 2MB.</small>
            </div>
        </div>

        <!-- INFORMASI DASAR -->
        <div class="card-custom" data-aos="fade-up" data-aos-duration="900">
            <div class="card-header-custom">
                <i class="fa-solid fa-user"></i> Informasi Dasar
            </div>
            <div class="card-body-custom">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control bg-light text-muted" value="{{ Auth::user()->name }}" readonly>
                        <small class="text-muted" style="font-size: 11px;">Nama diambil dari akun utama dan tidak dapat diubah di sini.</small>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label">Email Utama</label>
                        <input type="email" class="form-control bg-light text-muted" value="{{ Auth::user()->email }}" readonly>
                        <small class="text-muted" style="font-size: 11px;">Alamat email terdaftar.</small>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label">Lokasi Domisili</label>
                        <input type="text" name="location" class="form-control" placeholder="Contoh : Sukabumi, Jawa Barat" value="{{ old('location', $profile->location) }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- TENTANG SAYA -->
        <div class="card-custom" data-aos="fade-up" data-aos-duration="1000">
            <div class="card-header-custom">
                <i class="fa-solid fa-address-card"></i> Tentang Saya
            </div>
            <div class="card-body-custom">
                <label class="form-label">Ceritakan Singkat Tentang Diri Anda</label>
                <textarea name="bio" rows="5" class="form-control" placeholder="Contoh: Saya adalah seorang Web Developer profesional yang berpengalaman menggunakan Laravel, PHP, dan MySQL selama lebih dari 3 tahun...">{{ old('bio', $profile->bio) }}</textarea>
            </div>
        </div>

        <!-- KEAHLIAN -->
        <div class="card-custom" data-aos="fade-up" data-aos-duration="1000">
            <div class="card-header-custom">
                <i class="fa-solid fa-code"></i> Keahlian (Skills)
            </div>
            <div class="card-body-custom">
                <label class="form-label">Daftar Keahlian (Pisahkan dengan tanda koma)</label>
                <input type="text" name="skills" class="form-control" placeholder="Laravel, PHP, JavaScript, Bootstrap, Figma" value="{{ old('skills', $profile->skills) }}">
                <small class="text-muted mt-2 d-block">Contoh: Laravel, PHP, JavaScript, Bootstrap, UI/UX Design</small>
            </div>
        </div>

        <!-- PENGALAMAN -->
        <div class="card-custom" data-aos="fade-up" data-aos-duration="1000">
            <div class="card-header-custom">
                <i class="fa-solid fa-briefcase"></i> Pengalaman Kerja
            </div>
            <div class="card-body-custom">
                <label class="form-label">Riwayat Pengalaman Kerja / Freelance</label>
                <textarea name="experience" rows="5" class="form-control" placeholder="Contoh: Freelance Web Developer di berbagai agensi lokal selama 2 tahun...">{{ old('experience', $profile->experience) }}</textarea>
            </div>
        </div>

        <!-- PORTOFOLIO -->
        <div class="card-custom" data-aos="fade-up" data-aos-duration="1000">
            <div class="card-header-custom">
                <i class="fa-solid fa-globe"></i> Tautan Portofolio
            </div>
            <div class="card-body-custom">
                <label class="form-label">URL Website / GitHub / Behance / Dribbble</label>
                <input type="url" name="portfolio_link" class="form-control" placeholder="https://github.com/username" value="{{ old('portfolio_link', $profile->portfolio_link) }}">

                @if($profile->portfolio_link)
                    <div class="mt-3">
                        <a href="{{ $profile->portfolio_link }}" target="_blank" class="btn btn-outline-custom">
                            <i class="fa-solid fa-arrow-up-right-from-square me-2"></i> Buka Link Portofolio Saat Ini
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- CURRICULUM VITAE (CV) -->
        <div class="card-custom" data-aos="fade-up" data-aos-duration="1000">
            <div class="card-header-custom">
                <i class="fa-solid fa-file-pdf"></i> Curriculum Vitae (CV)
            </div>
            <div class="card-body-custom">
                <label class="form-label">Unggah Dokumen CV Baru (Format PDF)</label>
                <input type="file" name="cv" id="cv" class="form-control">
                <small class="text-muted mt-2 d-block">Format file wajib PDF dengan ukuran maksimal 2MB.</small>
                
                <div id="cvName" class="mt-3 text-info fw-semibold"></div>

                @if($profile->cv)
                    <div class="mt-3">
                        <a href="{{ asset('storage/'.$profile->cv) }}" target="_blank" class="btn btn-outline-custom">
                            <i class="fa-solid fa-download me-2"></i> Unduh CV yang Tersimpan
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- TOMBOL AKSI -->
        <div class="d-flex justify-content-end gap-3 mb-5" data-aos="fade-up" data-aos-duration="1000">
            <a href="{{ route('freelancer.profile') }}" class="btn btn-secondary-custom">
                <i class="fa-solid fa-arrow-left me-2"></i> Batal / Kembali
            </a>
            <button type="submit" class="btn btn-custom-primary">
                <i class="fa-solid fa-floppy-disk me-2"></i> Simpan Perubahan
            </button>
        </div>

    </form>

</div>

<!-- JavaScript untuk Live Preview Foto dan Nama File CV -->
<script>
    document.getElementById('photo').addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('preview').src = event.target.result;
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });

    document.getElementById('cv').addEventListener('change', function() {
        if(this.files.length) {
            document.getElementById('cvName').innerHTML = 
                "<i class='fa-solid fa-file-pdf text-danger me-1'></i> File terpilih: " + this.files[0].name;
        }
    });
</script>

<!-- Bootstrap JS Bundle & AOS Animation -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({
    once: true, 
    offset: 50,  
    easing: 'ease-out-cubic'
  });
</script>
</body>
</html>