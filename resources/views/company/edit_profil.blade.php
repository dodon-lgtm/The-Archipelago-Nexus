<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>

    <title>Edit Profil Perusahaan - Ultra Modern</title>

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
        --bg-color: #f0f9ff;
        --card-bg: rgba(255, 255, 255, 0.85);
        --text-main: #0f172a;
        --text-muted: #64748b;
        --border-color: rgba(186, 230, 253, 0.6);
    }

    body {
        background-color: var(--bg-color);
        background-image: 
            radial-gradient(at 0% 0%, rgba(2, 132, 199, 0.08) 0px, transparent 50%),
            radial-gradient(at 100% 100%, rgba(56, 189, 248, 0.06) 0px, transparent 50%);
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--text-main);
        overflow-x: hidden;
        min-height: 100vh;
    }

    .container {
        max-width: 950px;
        margin-top: 50px;
        margin-bottom: 80px;
    }

    /* Glassmorphism Main Card dengan Efek Floating */
    .card {
        background: var(--card-bg);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--border-color);
        border-radius: 32px;
        box-shadow: 0 25px 50px -12px rgba(2, 132, 199, 0.1);
        overflow: hidden;
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .card-header {
        background: linear-gradient(135deg, rgba(224, 242, 254, 0.9) 0%, rgba(186, 230, 253, 0.4) 100%);
        color: var(--text-main);
        padding: 30px 40px;
        border-bottom: 1px solid var(--border-color);
    }

    .card-header h3 {
        margin: 0;
        font-weight: 800;
        font-size: 24px;
        letter-spacing: -0.5px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .card-header h3 i {
        color: var(--primary-color);
        background: rgba(2, 132, 199, 0.1);
        padding: 10px;
        border-radius: 14px;
    }

    .card-body {
        padding: 40px;
    }

    /* Logo Preview Interaktif */
    .logo-preview-wrapper {
        position: relative;
        display: inline-block;
    }

    .logo-preview {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid white;
        box-shadow: 0 15px 35px rgba(2, 132, 199, 0.2);
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .logo-preview:hover {
        transform: scale(1.08) rotate(3deg);
        box-shadow: 0 20px 40px rgba(2, 132, 199, 0.3);
    }

    /* Modern Floating-Style Form Controls */
    .form-label {
        font-weight: 700;
        color: var(--text-main);
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .form-control, .form-select {
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 14px 20px;
        font-size: 14px;
        color: var(--text-main);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.02);
    }

    .form-control:focus, .form-select:focus {
        background: #ffffff;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 5px rgba(2, 132, 199, 0.15);
        transform: translateY(-2px);
    }

    textarea {
        resize: none;
    }

    /* Tombol Simpan Modern Bergradasi */
    .btn-save {
        background: var(--primary-gradient);
        border: none;
        color: white;
        padding: 14px 35px;
        border-radius: 16px;
        font-weight: 700;
        font-size: 14px;
        letter-spacing: 0.3px;
        box-shadow: 0 10px 25px rgba(2, 132, 199, 0.35);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .btn-save:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 30px rgba(2, 132, 199, 0.5);
        color: white;
    }

    .btn-save:active {
        transform: translateY(-1px);
    }

    /* Tombol Kembali dengan Efek Blur Glass */
    .btn-back {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        border: 1px solid var(--border-color);
        color: var(--text-muted);
        padding: 14px 28px;
        border-radius: 16px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-back:hover {
        background: #ffffff;
        color: var(--text-main);
        border-color: #7dd3fc;
        transform: translateX(-5px);
        box-shadow: 0 8px 20px rgba(2, 132, 199, 0.08);
    }

    /* Custom File Input Upload Styling */
    input[type="file"]::file-selector-button {
        background: #e0f2fe;
        color: var(--primary-color);
        border: none;
        padding: 8px 16px;
        border-radius: 10px;
        font-weight: 700;
        cursor: pointer;
        margin-right: 12px;
        transition: all 0.3s ease;
    }

    input[type="file"]::file-selector-button:hover {
        background: #bae6fd;
        transform: scale(1.02);
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

<div class="container" data-aos="fade-up" data-aos-duration="1000" data-aos-easing="ease-out-cubic">
    <div class="card">
        <div class="card-header">
            <h3>
                <i class="bi bi-building-gear"></i>
                Edit Profil Perusahaan
            </h3>
        </div>

        <div class="card-body">
            <!-- Alert Error Validasi -->
            @if($errors->any())
                <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4" data-aos="shake" data-aos-duration="500">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li><i class="bi bi-exclamation-circle-fill me-2"></i>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('company.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row align-items-center mb-4">
                    <!-- LOGO UPLOAD SECTION -->
                    <div class="col-md-4 text-center mb-4 mb-md-0" data-aos="zoom-in" data-aos-delay="200">
                        <div class="logo-preview-wrapper mb-3">
                            @if($profile->company_logo)
                                <img src="{{ asset('storage/'.$profile->company_logo) }}" id="preview" alt="Logo {{ $profile->company_name ?? 'Perusahaan' }}" class="logo-preview">
                            @else
                                <img src="{{ asset('images/company.png') }}" id="preview" alt="Logo {{ $profile->company_name ?? 'Perusahaan' }}" class="logo-preview" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($profile->company_name ?? 'Company') }}&background=0284c7&color=fff&size=140'">
                            @endif
                        </div>
                        <div>
                            <label class="form-label d-block text-muted small fw-bold mb-2">Ganti Logo Perusahaan</label>
                            <input type="file" name="company_logo" class="form-control form-control-sm shadow-sm" accept="image/*" onchange="previewImage(event)">
                        </div>
                    </div>

                    <!-- INPUT UTAMA -->
                    <div class="col-md-8" data-aos="fade-left" data-aos-delay="300">
                        <div class="mb-3">
                            <label class="form-label">Nama Perusahaan</label>
                            <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $profile->company_name) }}" placeholder="PT. Contoh Indonesia">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Bidang Usaha</label>
                            <select name="industry" class="form-select">
                                <option value="">-- Pilih Bidang Usaha --</option>
                                <option value="Teknologi" {{ $profile->industry=='Teknologi'?'selected':'' }}>Teknologi</option>
                                <option value="Pendidikan" {{ $profile->industry=='Pendidikan'?'selected':'' }}>Pendidikan</option>
                                <option value="Kesehatan" {{ $profile->industry=='Kesehatan'?'selected':'' }}>Kesehatan</option>
                                <option value="Keuangan" {{ $profile->industry=='Keuangan'?'selected':'' }}>Keuangan</option>
                                <option value="Manufaktur" {{ $profile->industry=='Manufaktur'?'selected':'' }}>Manufaktur</option>
                                <option value="Media" {{ $profile->industry=='Media'?'selected':'' }}>Media</option>
                                <option value="Lainnya" {{ $profile->industry=='Lainnya'?'selected':'' }}>Lainnya</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Website Resmi</label>
                            <input type="url" name="website" class="form-control" value="{{ old('website', $profile->website) }}" placeholder="https://company.com">
                        </div>
                    </div>
                </div>

                <div class="row" data-aos="fade-up" data-aos-delay="400">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Lokasi</label>
                            <input type="text" name="location" class="form-control" value="{{ old('location', $profile->location) }}" placeholder="Jakarta, Indonesia">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $profile->phone) }}" placeholder="08123456789">
                        </div>
                    </div>
                </div>

                <div class="mb-4" data-aos="fade-up" data-aos-delay="500">
                    <label class="form-label">Deskripsi Perusahaan</label>
                    <textarea name="description" rows="5" class="form-control" placeholder="Ceritakan mengenai latar belakang, budaya, dan visi perusahaan Anda...">{{ old('description', $profile->description) }}</textarea>
                </div>

                <!-- BUTTON ACTIONS -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top" style="border-color: var(--border-color) !important;" data-aos="fade-up" data-aos-delay="600">
                    <a href="{{ route('company.profile') }}" class="btn btn-back">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-save">
                        <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- AOS Animation Library JS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  // Inisialisasi AOS Animation
  AOS.init({
    once: true,
    offset: 50,
    easing: 'ease-out-cubic'
  });

  // Fungsi Preview Gambar Logo dengan transisi halus
  function previewImage(event){
      const image = document.getElementById('preview');
      image.style.opacity = '0.3';
      setTimeout(() => {
          image.src = URL.createObjectURL(event.target.files[0]);
          image.style.opacity = '1';
      }, 150);
  }
</script>

</body>
</html> 