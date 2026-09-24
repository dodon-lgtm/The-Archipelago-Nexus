<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.theme-boot')
    <title>Edit Profil Freelancer - Modern Dashboard</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = tailwind.config || {};
        tailwind.config.darkMode = 'class';
    </script>

    <!--
        URUTAN CSS (PENTING):
        Tailwind Play CDN menyuntikkan <style> hasil generate-nya di AKHIR <head>
        (document.head.append(...)), sehingga stylesheet Tailwind SELALU dievaluasi
        paling akhir. Karena itu Bootstrap 5 TIDAK dimuat di halaman ini:
        utility Bootstrap memakai !important (mis. .p-5 { padding: 3rem !important })
        sehingga selalu menang atas utility Tailwind dengan nama sama
        (.p-5 { padding: 1.25rem }) dan merusak layout sidebar
        (card banner "ApexForge Labs" terpotong di sisi kanan karena padding
        sidebar menjadi 48px, bukan 20px).
        Halaman ini memakai Tailwind + custom CSS (di-scope .edit-profile-page),
        sama seperti halaman freelancer lain (dashboard, profil, dst).
    -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- AOS Animation Library CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<style>
    /* =====================================================
       GLOBAL (aman untuk sidebar) - hanya hal yang netral
       ===================================================== */
    html { scroll-behavior: smooth; }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        overflow-x: hidden;
        background-color: #f6f9ff;
        background-image:
            radial-gradient(circle at 10% -10%, rgba(56,189,248,.10), transparent 30%),
            radial-gradient(circle at 100% 0%, rgba(37,99,235,.08), transparent 28%);
    }
    .dark body { background-color: #020617; background-image: none; }

    ::selection { background: rgba(37,99,235,.18); color: #0f172a; }
    ::-webkit-scrollbar { width: 7px; height: 7px; }
    ::-webkit-scrollbar-track { background: rgba(241,245,249,.7); }
    ::-webkit-scrollbar-thumb { background: rgba(37,99,235,.22); border-radius: 999px; }
    ::-webkit-scrollbar-thumb:hover { background: rgba(37,99,235,.38); }
    .dark ::-webkit-scrollbar-track { background: rgba(15,23,42,.7); }

    /* =====================================================
       LAYOUT: kunci sidebar agar tidak menyusut / terpotong
       ===================================================== */
    .layout-shell { display: flex; align-items: stretch; min-height: 100vh; width: 100%; }

    /* Sidebar dari navbar.navigasi: lebar tetap (w-64) & jangan boleh menyusut,
       supaya konten kanan yang lebar tidak "memeras" sidebar. */
    .layout-shell > aside,
    .layout-shell > nav,
    .layout-shell > .sidebar,
    .layout-shell > [id*="sidebar"] {
        flex: 0 0 auto;
        flex-shrink: 0;
    }

    /* Area kanan: mengisi sisa ruang, min-width:0 agar bisa menyusut tanpa
       mendorong/menciutkan sidebar. */
    .layout-content { flex: 1 1 0%; min-width: 0; display: flex; flex-direction: column; }

    /* =====================================================
       SCOPE HALAMAN EDIT PROFIL
       Semua gaya di bawah HANYA berlaku di dalam .edit-profile-page
       sehingga tidak merusak sidebar / navbar.
       ===================================================== */
    .edit-profile-page {
        --primary-color: #0284c7;
        --primary-gradient: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%);
        --card-bg: rgba(255, 255, 255, 0.85);
        --text-main: #0f172a;
        --text-muted: #64748b;
        --border-color: rgba(186, 230, 253, 0.6);

        max-width: 1100px;
        margin: 0 auto;
        padding-bottom: 60px;
        color: var(--text-main);
    }

    .dark .edit-profile-page {
        --card-bg: rgba(15, 23, 42, .9);
        --text-main: #f1f5f9;
        --text-muted: #94a3b8;
        --border-color: rgba(51, 65, 85, .6);
    }

    /* Header */
    .edit-profile-page .page-title {
        font-size: 34px;
        font-weight: 800;
        color: var(--text-main);
        letter-spacing: -1px;
        margin-bottom: 4px;
    }
    .edit-profile-page .page-subtitle {
        color: var(--text-muted);
        font-size: 15px;
        margin-bottom: 30px;
    }

    /* Cards */
    .edit-profile-page .card-custom {
        background: var(--card-bg);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--border-color);
        border-radius: 28px;
        box-shadow: 0 10px 30px -5px rgba(2, 132, 199, 0.05);
        margin-bottom: 28px;
        transition: box-shadow .4s cubic-bezier(0.16, 1, 0.3, 1), border-color .4s ease;
        overflow: hidden;
    }
    .edit-profile-page .card-custom:hover {
        box-shadow: 0 20px 40px -10px rgba(2, 132, 199, 0.12);
        border-color: rgba(2, 132, 199, 0.3);
    }
    .dark .edit-profile-page .card-custom { box-shadow: 0 10px 30px -5px rgba(0,0,0,.4); }

    .edit-profile-page .card-header-custom {
        background: linear-gradient(135deg, rgba(224,242,254,.6) 0%, rgba(186,230,253,.2) 100%);
        padding: 22px 32px;
        border-bottom: 1px solid var(--border-color);
        font-size: 18px;
        font-weight: 700;
        color: var(--text-main);
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .dark .edit-profile-page .card-header-custom {
        background: linear-gradient(135deg, rgba(30,41,59,.8) 0%, rgba(15,23,42,.5) 100%);
    }
    .edit-profile-page .card-header-custom i { color: var(--primary-color); font-size: 20px; }
    .edit-profile-page .card-body-custom { padding: 32px; }

    /* Foto profil */
    .edit-profile-page .profile-preview {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid #fff;
        box-shadow: 0 12px 30px rgba(2,132,199,.2);
        transition: transform .4s ease;
    }
    .edit-profile-page .profile-preview:hover { transform: scale(1.05); }
    .dark .edit-profile-page .profile-preview { border-color: #1e293b; }

    /* Form (base style dulu disuplai Bootstrap .form-control/.form-select) */
    .edit-profile-page .form-control,
    .edit-profile-page .form-select {
        display: block;
        width: 100%;
        line-height: 1.5;
        background-color: rgba(255,255,255,.9);
        background-clip: padding-box;
        border: 1px solid var(--border-color);
        border-radius: 14px;
        padding: 13px 18px;
        font-family: inherit;
        font-size: 14px;
        color: var(--text-main);
        transition: all .3s ease;
    }
    .edit-profile-page input[type="file"].form-control { padding-top: 9px; padding-bottom: 9px; }
    .edit-profile-page input[type="file"]::file-selector-button {
        background: #eff6ff;
        color: var(--primary-color);
        border: 0;
        border-radius: 10px;
        padding: 8px 14px;
        margin-right: 12px;
        font-family: inherit;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: background .3s ease;
    }
    .edit-profile-page input[type="file"]::file-selector-button:hover { background: #dbeafe; }
    .edit-profile-page .form-control:focus,
    .edit-profile-page .form-select:focus {
        background-color: #fff;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(2,132,199,.15);
        outline: none;
    }
    .edit-profile-page .form-label {
        font-weight: 700;
        font-size: 13px;
        color: var(--text-main);
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: .5px;
    }
    .edit-profile-page textarea.form-control { resize: none; }
    .edit-profile-page .text-muted { color: var(--text-muted) !important; }

    .dark .edit-profile-page .form-control,
    .dark .edit-profile-page .form-select {
        background-color: #0f172a !important;
        color: #e2e8f0 !important;
        border-color: rgba(51,65,85,.7) !important;
    }
    .dark .edit-profile-page .form-control:focus,
    .dark .edit-profile-page .form-select:focus {
        background-color: #1e293b !important;
        color: #f1f5f9 !important;
        border-color: #38bdf8 !important;
        box-shadow: 0 0 0 4px rgba(56,189,248,.15) !important;
    }
    .dark .edit-profile-page .form-label { color: #e2e8f0; }
    .dark .edit-profile-page input::placeholder,
    .dark .edit-profile-page textarea::placeholder { color: #64748b; }
    .dark .edit-profile-page input[type="file"]::file-selector-button {
        background: #1e293b;
        color: #38bdf8;
        border: 0;
    }
    .dark .edit-profile-page input[type="file"]::file-selector-button:hover { background: #334155; }

    /* Buttons
       Base style di bawah ini sebelumnya datang dari Bootstrap .btn
       (display, line-height, text-decoration, cursor). */
    .edit-profile-page .btn-custom-primary,
    .edit-profile-page .btn-secondary-custom,
    .edit-profile-page .btn-outline-custom {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        line-height: 1.5;
        text-align: center;
        text-decoration: none;
        vertical-align: middle;
        cursor: pointer;
        user-select: none;
        border: 0;
        font-family: inherit;
    }

    .edit-profile-page .btn-custom-primary {
        background: var(--primary-gradient);
        border: none;
        color: #fff;
        border-radius: 16px;
        padding: 12px 28px;
        font-weight: 700;
        font-size: 14px;
        box-shadow: 0 8px 20px rgba(2,132,199,.3);
        transition: all .3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .edit-profile-page .btn-custom-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 25px rgba(2,132,199,.45);
        color: #fff;
    }
    .edit-profile-page .btn-secondary-custom {
        background: rgba(255,255,255,.8);
        backdrop-filter: blur(8px);
        border: 1px solid var(--border-color);
        color: var(--text-muted);
        border-radius: 16px;
        padding: 12px 28px;
        font-weight: 600;
        font-size: 14px;
        transition: all .3s ease;
    }
    .edit-profile-page .btn-secondary-custom:hover {
        background: #fff;
        color: var(--text-main);
        border-color: #7dd3fc;
        transform: translateY(-2px);
    }
    .dark .edit-profile-page .btn-secondary-custom {
        background: rgba(15,23,42,.85);
        border-color: rgba(51,65,85,.7);
        color: #cbd5e1;
    }
    .dark .edit-profile-page .btn-secondary-custom:hover { background: #1e293b; color: #fff; }

    .edit-profile-page .btn-outline-custom {
        background: transparent;
        border: 1px solid var(--primary-color);
        color: var(--primary-color);
        border-radius: 12px;
        padding: 8px 18px;
        font-weight: 600;
        font-size: 13px;
        transition: all .3s ease;
    }
    .edit-profile-page .btn-outline-custom:hover {
        background: var(--primary-gradient);
        color: #fff;
        border-color: transparent;
    }
    .dark .edit-profile-page .btn-outline-custom { border-color: rgba(56,189,248,.5); color: #38bdf8; }
    .dark .edit-profile-page .btn-outline-custom:hover { color: #fff; border-color: transparent; }

    /* Alert */
    .edit-profile-page .alert {
        border-radius: 16px;
        border: none;
        padding: 16px 20px;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(0,0,0,.03);
    }
    .edit-profile-page .alert-success { background-color: #d1fae5; color: #065f46; }
    .edit-profile-page .alert-danger  { background-color: #fee2e2; color: #991b1b; }
    .dark .edit-profile-page .alert-success { background-color: rgba(6,78,59,.55); color: #a7f3d0; }
    .dark .edit-profile-page .alert-danger  { background-color: rgba(127,29,29,.55); color: #fecaca; }

    /* Mobile */
    @media (max-width: 767px) {
        .edit-profile-page .card-body-custom { padding: 22px; }
        .edit-profile-page .card-header-custom { padding: 18px 22px; }
        .edit-profile-page .page-title { font-size: 28px; }
    }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: .01ms !important;
            scroll-behavior: auto !important;
        }
    }
</style>
</head>

<body class="bg-[#f6f9ff] dark:bg-slate-950 text-slate-800 dark:text-white antialiased transition-colors duration-300">

<div class="layout-shell">

    {{-- SIDEBAR FREELANCER (sama dengan halaman Freelancer lain) --}}
    @include('navbar.navigasi')

    {{-- AREA KANAN: flex-1 + min-w-0 supaya responsif mengisi sisa ruang
         tanpa menekan/menciutkan sidebar --}}
    <div class="layout-content flex-1 min-w-0 flex flex-col">

        {{-- TOP NAVBAR FREELANCER --}}
        <div class="sticky top-0 z-40 bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border-b border-blue-100/80 dark:border-slate-800 shadow-xs">
            @include('navbar.nav')
        </div>

        <main class="flex-1 px-4 sm:px-6 lg:px-8 py-8">

            {{-- SEMUA STYLE HALAMAN DI-SCOPE DI SINI --}}
            <div class="edit-profile-page">

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
                        <ul class="mb-0 p-0 list-none">
                            @foreach($errors->all() as $error)
                                <li><i class="fa-solid fa-triangle-exclamation me-2"></i> {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('freelancer.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- FOTO PROFIL -->
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
                            <div class="mb-2 w-full md:w-1/2 mx-auto">
                                <input type="file" name="photo" id="photo" accept="image/png,image/jpeg" class="form-control">
                            </div>
                            <small class="text-muted block">Format yang didukung: JPG, JPEG, PNG. Ukuran maksimal 2MB.</small>
                        </div>
                    </div>

                    <!-- INFORMASI DASAR -->
                    <div class="card-custom" data-aos="fade-up" data-aos-duration="900">
                        <div class="card-header-custom">
                            <i class="fa-solid fa-user"></i> Informasi Dasar
                        </div>
                        <div class="card-body-custom">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" name="name" class="form-control"
                                           value="{{ old('name', $user->name) }}"
                                           placeholder="Masukkan nama lengkap">
                                </div>

                                <div>
                                    <label class="form-label">Email Utama</label>
                                    <input type="email" name="email" class="form-control"
                                           value="{{ old('email', $user->email) }}"
                                           placeholder="Masukkan email">
                                </div>

                                <div>
                                    <label class="form-label">Nomor Telepon</label>
                                    <input type="tel" name="phone" class="form-control" maxlength="20"
                                           value="{{ old('phone', $user->phone) }}"
                                           placeholder="Contoh : 08123456789">
                                </div>

                                <div>
                                    <label class="form-label">Lokasi Domisili</label>
                                    <input type="text" name="location" class="form-control"
                                           placeholder="Contoh : Sukabumi, Jawa Barat"
                                           value="{{ old('location', $profile->location) }}">
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
                            <textarea name="bio" rows="5" class="form-control"
                                      placeholder="Contoh: Saya adalah seorang Web Developer profesional yang berpengalaman menggunakan Laravel, PHP, dan MySQL selama lebih dari 3 tahun...">{{ old('bio', $profile->bio) }}</textarea>
                        </div>
                    </div>

                    <!-- KEAHLIAN -->
                    <div class="card-custom" data-aos="fade-up" data-aos-duration="1000">
                        <div class="card-header-custom">
                            <i class="fa-solid fa-code"></i> Keahlian (Skills)
                        </div>
                        <div class="card-body-custom">
                            <label class="form-label">Daftar Keahlian (Pisahkan dengan tanda koma)</label>
                            <input type="text" name="skills" class="form-control"
                                   placeholder="Laravel, PHP, JavaScript, Bootstrap, Figma"
                                   value="{{ old('skills', $profile->skills) }}">
                            <small class="text-muted mt-2 block">Contoh: Laravel, PHP, JavaScript, Bootstrap, UI/UX Design</small>
                        </div>
                    </div>

                    <!-- PENGALAMAN -->
                    <div class="card-custom" data-aos="fade-up" data-aos-duration="1000">
                        <div class="card-header-custom">
                            <i class="fa-solid fa-briefcase"></i> Pengalaman Kerja
                        </div>
                        <div class="card-body-custom">
                            <label class="form-label">Riwayat Pengalaman Kerja / Freelance</label>
                            <textarea name="experience" rows="5" class="form-control"
                                      placeholder="Contoh: Freelance Web Developer di berbagai agensi lokal selama 2 tahun...">{{ old('experience', $profile->experience) }}</textarea>
                        </div>
                    </div>

                    <!-- PORTOFOLIO -->
                    <div class="card-custom" data-aos="fade-up" data-aos-duration="1000">
                        <div class="card-header-custom">
                            <i class="fa-solid fa-globe"></i> Tautan Portofolio
                        </div>
                        <div class="card-body-custom">
                            <label class="form-label">URL Website / GitHub / Behance / Dribbble</label>
                            <input type="url" name="portfolio_link" class="form-control"
                                   placeholder="https://github.com/username"
                                   value="{{ old('portfolio_link', $profile->portfolio_link) }}">

                            @if($profile->portfolio_link)
                                <div class="mt-3">
                                    <a href="{{ $profile->portfolio_link }}" target="_blank" rel="noopener" class="btn-outline-custom">
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
                            <input type="file" name="cv" id="cv" accept="application/pdf" class="form-control">
                            <small class="text-muted mt-2 block">Format file wajib PDF dengan ukuran maksimal 2MB.</small>

                            <div id="cvName" class="mt-3 text-sky-600 dark:text-sky-400 font-semibold"></div>

                            @if($profile->cv)
                                <div class="mt-3">
                                    <a href="{{ asset('storage/'.$profile->cv) }}" target="_blank" rel="noopener" class="btn-outline-custom">
                                        <i class="fa-solid fa-download me-2"></i> Unduh CV yang Tersimpan
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- TOMBOL AKSI -->
                    <div class="flex justify-end gap-3 mb-5" data-aos="fade-up" data-aos-duration="1000">
                        <a href="{{ route('freelancer.profile') }}" class="btn-secondary-custom">
                            <i class="fa-solid fa-arrow-left me-2"></i> Batal / Kembali
                        </a>
                        <button type="submit" class="btn-custom-primary">
                            <i class="fa-solid fa-floppy-disk me-2"></i> Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>{{-- /.edit-profile-page --}}

        </main>
    </div>{{-- /.layout-content --}}
</div>{{-- /.layout-shell --}}

<!-- JavaScript: Live Preview Foto dan Nama File CV -->
<script>
    document.getElementById('photo').addEventListener('change', function (e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function (event) {
                document.getElementById('preview').src = event.target.result;
            };
            reader.readAsDataURL(e.target.files[0]);
        }
    });

    document.getElementById('cv').addEventListener('change', function () {
        if (this.files.length) {
            document.getElementById('cvName').innerHTML =
                "<i class='fa-solid fa-file-pdf text-red-500 me-1'></i> File terpilih: " + this.files[0].name;
        }
    });
</script>

<!-- AOS Animation (Bootstrap JS tidak dipakai: modal/navbar di navbar.nav
     di-toggle dengan JavaScript sendiri + class Tailwind) -->
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