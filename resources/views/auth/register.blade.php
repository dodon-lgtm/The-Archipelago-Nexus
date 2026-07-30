<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Akun - The Archipelago Nexus</title>
    
    <!-- Bootstrap CSS v5.3.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- AOS Animation Library CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --bg-body: #f8fafc;
            --container-bg: #ffffff;
            --primary-gradient: linear-gradient(135deg, #0284c7 0%, #2563eb 100%);
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-glass: #e2e8f0;
        }

        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
            box-sizing: border-box;
        }

        body {
            background-color: var(--bg-body);
            background-image: 
                radial-gradient(circle at 15% 15%, rgba(37, 99, 235, 0.06) 0%, transparent 45%),
                radial-gradient(circle at 85% 85%, rgba(2, 132, 199, 0.05) 0%, transparent 45%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 20px;
            margin: 0;
            color: var(--text-main);
        }

        .main-container {
            width: 100%;
            max-width: 1200px;
            background: var(--container-bg);
            border: 1px solid var(--border-glass);
            border-radius: 32px;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.06), 0 1px 3px rgba(15, 23, 42, 0.03);
            overflow: hidden;
            display: flex;
            height: 780px;
        }

        /* Kolom Kiri: Banner Branding */
        .left-banner {
            flex: 1.1;
            background: linear-gradient(145deg, #f0f9ff 0%, #e0f2fe 100%);
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border-right: 1px solid var(--border-glass);
            position: relative;
            overflow: hidden;
        }

        .left-banner::before {
            content: '';
            position: absolute;
            top: -60px;
            left: -60px;
            width: 220px;
            height: 220px;
            background: rgba(37, 99, 235, 0.08);
            filter: blur(50px);
            border-radius: 50%;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 14px;
            font-weight: 700;
            color: #0f172a;
            font-size: 18px;
            letter-spacing: -0.3px;
        }

        .brand-logo .icon-box {
            width: 44px;
            height: 44px;
            background: var(--primary-gradient);
            color: #fff;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);
        }

        .banner-title {
            font-size: 34px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.25;
            margin-bottom: 16px;
            letter-spacing: -0.5px;
        }

        .banner-title span {
            background: linear-gradient(135deg, #0284c7 0%, #2563eb 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .banner-desc {
            color: var(--text-muted);
            font-size: 14px;
            margin-bottom: 25px;
            line-height: 1.6;
        }

        .feature-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 25px;
        }

        .feature-item {
            background: #ffffff;
            border: 1px solid rgba(2, 132, 199, 0.12);
            border-radius: 14px;
            padding: 14px;
            text-align: left;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03);
        }

        .feature-item:hover {
            transform: translateY(-4px);
            border-color: #0284c7;
            box-shadow: 0 10px 20px rgba(2, 132, 199, 0.08);
        }

        .feature-item i {
            font-size: 18px;
            color: #0284c7;
            margin-bottom: 8px;
            display: block;
        }

        .feature-item h6 {
            font-size: 12px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 3px;
        }

        .feature-item p {
            font-size: 10px;
            color: var(--text-muted);
            margin: 0;
            line-height: 1.4;
        }

        .illustration-box {
            background: #ffffff;
            border: 1px solid var(--border-glass);
            border-radius: 14px;
            padding: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--text-muted);
            font-size: 12px;
            font-weight: 500;
            box-shadow: 0 4px 10px rgba(15, 23, 42, 0.02);
        }

        .illustration-box i {
            font-size: 20px;
            color: #0284c7;
        }

        /* Kolom Kanan: Form Register */
        .right-form {
            flex: 1.15;
            background: #ffffff;
            padding: 45px 50px;
            display: flex;
            flex-direction: column;
            overflow-y: auto; 
            height: 100%;
        }

        /* Kustomisasi Scrollbar */
        .right-form::-webkit-scrollbar {
            width: 6px;
        }
        .right-form::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        .right-form::-webkit-scrollbar-thumb {
            gap: 10px;
            background: #cbd5e1;
            border-radius: 10px;
        }
        .right-form::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .form-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-header .mini-logo {
            width: 42px;
            height: 42px;
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #0284c7;
            font-size: 20px;
            margin-bottom: 8px;
        }

        .form-header h3 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 2px;
            color: #0f172a;
            letter-spacing: -0.5px;
        }

        .form-header p {
            font-size: 12px;
            color: var(--text-muted);
            margin: 0;
        }

        .role-switcher {
            background: #f8fafc;
            border: 1px solid var(--border-glass);
            border-radius: 14px;
            padding: 5px;
            display: flex;
            gap: 5px;
            margin-bottom: 20px;
        }

        .role-btn {
            flex: 1;
            background: transparent;
            border: none;
            color: var(--text-muted);
            padding: 10px;
            font-size: 12px;
            font-weight: 700;
            border-radius: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
            text-align: center;
        }

        .role-btn.active {
            background: var(--primary-gradient);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }

        .form-label-custom {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--text-muted);
            margin-bottom: 6px;
            display: block;
        }

        .input-group-custom {
            background: #f8fafc;
            border: 1px solid var(--border-glass);
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .input-group-custom:focus-within {
            border-color: #0284c7;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.1);
        }

        .input-group-custom .input-group-text {
            background: transparent;
            border: none;
            color: #64748b;
            padding-left: 14px;
            padding-right: 8px;
            font-size: 15px;
        }

        .input-group-custom .form-control {
            background: transparent;
            border: none;
            color: #0f172a;
            padding: 11px 12px;
            font-size: 13px;
        }

        .input-group-custom .form-control:focus {
            box-shadow: none;
            background: transparent;
            color: #0f172a;
        }

        .input-group-custom .form-control::placeholder {
            color: #94a3b8;
        }

        .error {
            color: #dc2626;
            font-size: 11px;
            margin-top: 4px;
            font-weight: 500;
        }

        .flash {
            background: #fef3c7;
            border: 1px solid #fde68a;
            color: #b45309;
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 12px;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .company-box-luxury {
            background: #f8fafc;
            border: 1px dashed #0284c7;
            border-radius: 14px;
            padding: 16px;
            margin-top: 14px;
            margin-bottom: 14px;
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .btn-submit-luxury {
            width: 100%;
            background: var(--primary-gradient);
            border: none;
            border-radius: 12px;
            color: #ffffff;
            padding: 13px;
            font-weight: 700;
            font-size: 13px;
            letter-spacing: 0.3px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
            margin-top: 10px;
            cursor: pointer;
        }

        .btn-submit-luxury:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(2, 132, 199, 0.4);
            opacity: 0.95;
        }

        .footer-links {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: var(--text-muted);
            padding-bottom: 15px;
        }

        .footer-links a {
            color: #0284c7;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: #2563eb;
            text-decoration: underline;
        }

        @media (max-width: 992px) {
            .main-container {
                flex-direction: column;
                max-width: 550px;
                height: auto;
            }
            .right-form {
                height: auto;
                max-height: 600px;
                padding: 30px;
            }
            .left-banner {
                padding: 30px;
            }
        }
    </style>
</head>
<body>

<div class="main-container" data-aos="zoom-in" data-aos-duration="800">
    
    <!-- Kolom Kiri: Banner Branding -->
    <div class="left-banner">
        <div>
            <div class="brand-logo mb-3">
                <div class="icon-box"><i class="bi bi-layers-fill"></i></div>
                <span>The Archipelago Nexus</span>
            </div>
            <h1 class="banner-title">Temukan Bakat Terampil atau Proyek Impian Anda di <span>The Archipelago Nexus</span></h1>
            <p class="banner-desc">Hubungkan dengan freelancer terbaik dan wujudkan proyek Anda dengan mudah, cepat, dan aman.</p>
            
            <div class="feature-cards">
                <div class="feature-item">
                    <i class="bi bi-person-check"></i>
                    <h6>Terpercaya</h6>
                    <p>Banyak freelancer berkualitas.</p>
                </div>
                <div class="feature-item">
                    <i class="bi bi-shield-check"></i>
                    <h6>Proses Aman</h6>
                    <p>Transaksi terjamin.</p>
                </div>
                <div class="feature-item">
                    <i class="bi bi-clock-history"></i>
                    <h6>Tepat Waktu</h6>
                    <p>Sesuai deadline.</p>
                </div>
            </div>
        </div>

        <div class="illustration-box">
            <i class="bi bi-people-fill"></i>
            <span>Bergabunglah dengan ribuan profesional lainnya</span>
        </div>
    </div>

    <!-- Kolom Kanan: Form Registrasi -->
    <div class="right-form" id="rightFormContainer">
        <div class="form-header">
            <div class="mini-logo">
                <i class="bi bi-layers-fill"></i>
            </div>
            <h3>The Archipelago Nexus</h3>
            <p>Buat akun baru untuk melanjutkan</p>
        </div>

        @if (session('success'))
            <div class="flash"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <input type="hidden" name="is_company" id="is_company_input" value="{{ old('is_company', 0) }}">

            <div class="role-switcher">
                <button type="button" class="role-btn {{ old('is_company') ? '' : 'active' }}" id="btnFreelancer" onclick="setRole(false)">
                    Freelancer
                </button>
                <button type="button" class="role-btn {{ old('is_company') ? 'active' : '' }}" id="btnCompany" onclick="setRole(true)">
                    Perusahaan / Client
                </button>
            </div>

            <!-- Nama Lengkap PIC -->
            <div class="mb-3">
                <label class="form-label-custom">Nama Lengkap</label>
                <div class="input-group-custom {{ $errors->has('name') ? 'border-danger' : '' }}">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input name="name" type="text" value="{{ old('name') }}" class="form-control" placeholder="Nama lengkap Anda" required autofocus>
                </div>
                @error('name')<div class="error">{{ $message }}</div>@enderror
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label-custom">Email</label>
                <div class="input-group-custom {{ $errors->has('email') ? 'border-danger' : '' }}">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input name="email" type="email" value="{{ old('email') }}" class="form-control" placeholder="nama@email.com" required>
                </div>
                @error('email')<div class="error">{{ $message }}</div>@enderror
            </div>

            <!-- Nomor HP / WhatsApp -->
            <div class="mb-3">
                <label class="form-label-custom">Nomor HP / WhatsApp</label>
                <div class="input-group-custom {{ $errors->has('phone') ? 'border-danger' : '' }}">
                    <span class="input-group-text"><i class="bi bi-phone"></i></span>
                    <input name="phone" type="text" value="{{ old('phone') }}" class="form-control" placeholder="08xxxxxxxxxx" required>
                </div>
                @error('phone')<div class="error">{{ $message }}</div>@enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label class="form-label-custom">Password</label>
                <div class="input-group-custom {{ $errors->has('password') ? 'border-danger' : '' }}">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input id="password" name="password" type="password" class="form-control" placeholder="Minimal 8 karakter" required>
                    <button class="btn text-muted border-0 bg-transparent px-3" type="button" onclick="togglePassword('password', this)">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                @error('password')<div class="error">{{ $message }}</div>@enderror
            </div>

            <!-- Konfirmasi Password -->
            <div class="mb-3">
                <label class="form-label-custom">Konfirmasi Password</label>
                <div class="input-group-custom">
                    <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" placeholder="Ulangi password" required>
                    <button class="btn text-muted border-0 bg-transparent px-3" type="button" onclick="togglePassword('password_confirmation', this)">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Bagian Form Perusahaan -->
            <div id="companyFields" class="company-box-luxury" style="display: {{ old('is_company') ? 'block' : 'none' }};">
                <div class="text-primary fw-semibold mb-3" style="font-size: 11px; letter-spacing: 0.5px;">
                    <i class="bi bi-building-fill-gear me-1"></i> INFORMASI DETAIL PERUSAHAAN
                </div>
                
                <div class="mb-3">
                    <label class="form-label-custom">Nama Perusahaan</label>
                    <input name="company_name" type="text" value="{{ old('company_name') }}" class="form-control bg-white text-dark border border-secondary-subtle rounded-3 px-3 py-2" style="font-size: 13px;" placeholder="PT / CV / Nama Usaha">
                    @error('company_name')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label-custom">Nomor Telepon Perusahaan</label>
                    <input name="company_phone" type="text" value="{{ old('company_phone') }}" class="form-control bg-white text-dark border border-secondary-subtle rounded-3 px-3 py-2" style="font-size: 13px;" placeholder="08xxxxxxxxxx">
                    @error('company_phone')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label-custom">Alamat Perusahaan</label>
                    <textarea name="company_address" rows="2" class="form-control bg-white text-dark border border-secondary-subtle rounded-3 px-3 py-2" style="font-size: 13px; resize: none;" placeholder="Alamat lengkap lokasi">{{ old('company_address') }}</textarea>
                    @error('company_address')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="mb-0">
                    <label class="form-label-custom">Deskripsi Perusahaan (Opsional)</label>
                    <textarea name="company_description" rows="2" class="form-control bg-white text-dark border border-secondary-subtle rounded-3 px-3 py-2" style="font-size: 13px; resize: none;" placeholder="Ceritakan sedikit...">{{ old('company_description') }}</textarea>
                </div>
            </div>

            <!-- Tombol Submit -->
            <button class="btn-submit-luxury" type="submit">
                Daftar Sekarang <i class="bi bi-arrow-right ms-1"></i>
            </button>
        </form>

        <div class="footer-links">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
        </div>
    </div>

</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- AOS Animation Library JS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ once: true });

    // Memastikan saat halaman dimuat, posisi scroll form kanan berada di paling atas
    window.addEventListener('DOMContentLoaded', () => {
        const formContainer = document.getElementById('rightFormContainer');
        if (formContainer) {
            formContainer.scrollTop = 0;
        }
    });

    function setRole(isCompany) {
        const btnFreelancer = document.getElementById('btnFreelancer');
        const btnCompany = document.getElementById('btnCompany');
        const companyFields = document.getElementById('companyFields');
        const hiddenInput = document.getElementById('is_company_input');

        if (isCompany) {
            btnCompany.classList.add('active');
            btnFreelancer.classList.remove('active');
            companyFields.style.display = 'block';
            hiddenInput.value = '1';
        } else {
            btnFreelancer.classList.add('active');
            btnCompany.classList.remove('active');
            companyFields.style.display = 'none';
            hiddenInput.value = '0';
        }
    }

    function togglePassword(fieldId, button) {
        const input = document.getElementById(fieldId);
        const icon = button.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>
</body>
</html>