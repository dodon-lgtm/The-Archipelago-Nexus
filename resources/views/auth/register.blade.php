<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Akun - The Archipelago Nexus</title>
    <!-- Bootstrap CSS v5.3.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        * {
            font-family: 'Poppins', sans-serif;
            box-sizing: border-box;
        }
        body {
            background: #f1f5f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            margin: 0;
        }
        .main-container {
            width: 100%;
            max-width: 1150px;
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            display: flex;
            min-height: 700px;
        }
        /* Kolom Kiri (Banner / Branding) */
        .left-banner {
            flex: 1.1;
            background: #ffffff;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border-right: 1px solid #f1f5f9;
        }
        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            color: #0f172a;
            font-size: 16px;
        }
        .brand-logo .icon-box {
            width: 38px;
            height: 38px;
            background: #0f172a;
            color: #fff;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }
        .banner-title {
            font-size: 36px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.3;
            margin-bottom: 15px;
        }
        .banner-title span {
            color: #2563eb;
        }
        .banner-desc {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .feature-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 35px;
        }
        .feature-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px;
            text-align: left;
        }
        .feature-item i {
            font-size: 18px;
            color: #2563eb;
            margin-bottom: 8px;
            display: block;
        }
        .feature-item h6 {
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 2px;
        }
        .feature-item p {
            font-size: 11px;
            color: #64748b;
            margin: 0;
            line-height: 1.3;
        }
        .illustration-box {
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            border-radius: 16px;
            height: 140px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            font-size: 13px;
            font-weight: 500;
        }

        /* Kolom Kanan (Form Register) */
        .right-form {
            flex: 1;
            background: #0b1120;
            padding: 45px 50px;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow-y: auto;
            max-height: 85vh;
        }
        .form-header {
            text-align: center;
            margin-bottom: 25px;
        }
        .form-header .mini-logo {
            width: 44px;
            height: 44px;
            background: #1e293b;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 20px;
            margin-bottom: 12px;
            border: 1px solid #334155;
        }
        .form-header h3 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 4px;
            color: #f8fafc;
        }
        .form-header p {
            font-size: 13px;
            color: #94a3b8;
            margin: 0;
        }
        .form-label-custom {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
            margin-bottom: 6px;
            display: block;
        }
        .input-group-custom {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 10px;
            overflow: hidden;
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
        }
        .input-group-custom:focus-within {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        .input-group-custom .input-group-text {
            background: transparent;
            border: none;
            color: #94a3b8;
            padding-left: 14px;
            padding-right: 10px;
        }
        .input-group-custom .form-control {
            background: transparent;
            border: none;
            color: #ffffff;
            padding: 11px 12px;
            font-size: 13px;
        }
        .input-group-custom .form-control:focus {
            box-shadow: none;
            background: transparent;
            color: #ffffff;
        }
        .input-group-custom textarea.form-control {
            resize: none;
        }
        .error {
            color: #f87171;
            font-size: 11px;
            margin-top: 4px;
        }
        .flash {
            background: rgba(245, 158, 11, 0.15);
            border: 1px solid rgba(245, 158, 11, 0.3);
            color: #fbbf24;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 12px;
            margin-bottom: 20px;
        }
        .company-box-dark {
            background: #111827;
            border: 1px dashed #374151;
            border-radius: 12px;
            padding: 16px;
            margin-top: 15px;
            margin-bottom: 15px;
        }
        .btn-submit-dark {
            width: 100%;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border: none;
            border-radius: 10px;
            color: #ffffff;
            padding: 12px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
            margin-top: 5px;
        }
        .btn-submit-dark:hover {
            opacity: 0.95;
            transform: translateY(-1px);
        }
        .form-check-input {
            background-color: #1e293b;
            border-color: #475569;
            cursor: pointer;
        }
        .form-check-input:checked {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }
        .form-check-label {
            color: #cbd5e1;
            font-size: 13px;
            cursor: pointer;
        }
        .footer-links {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #94a3b8;
        }
        .footer-links a {
            color: #60a5fa;
            text-decoration: none;
            font-weight: 500;
        }
        .footer-links a:hover {
            text-decoration: underline;
        }

        /* Responsive Breakpoint */
        @media (max-width: 900px) {
            .main-container {
                flex-direction: column;
                max-width: 550px;
            }
            .left-banner {
                padding: 30px;
            }
            .right-form {
                padding: 30px;
                max-height: none;
            }
        }
    </style>
</head>
<body>

<div class="main-container">
    
    <!-- Kolom Kiri: Banner Branding -->
    <div class="left-banner">
        <div>
            <div class="brand-logo mb-4">
                <div class="icon-box"><i class="bi bi-layers-fill"></i></div>
                <span>The Archipelago Nexus</span>
            </div>
            <h1 class="banner-title">Temukan Bakat Terampil atau Proyek Impian Anda di <span>The Archipelago Nexus</span></h1>
            <p class="banner-desc">Hubungkan dengan freelancer terbaik dan wujudkan proyek Anda dengan mudah, cepat, dan aman.</p>
            
            <div class="feature-cards">
                <div class="feature-item">
                    <i class="bi bi-person-check"></i>
                    <h6>Freelancer Terpercaya</h6>
                    <p>Banyak freelancer berkualitas.</p>
                </div>
                <div class="feature-item">
                    <i class="bi bi-shield-check"></i>
                    <h6>Proses Aman</h6>
                    <p>Transaksi aman dan terjamin.</p>
                </div>
                <div class="feature-item">
                    <i class="bi bi-clock-history"></i>
                    <h6>Proyek Tepat Waktu</h6>
                    <p>Selesai sesuai deadline.</p>
                </div>
            </div>
        </div>

        <div class="illustration-box">
            <span>Bergabunglah dengan ribuan profesional lainnya</span>
        </div>
    </div>

    <!-- Kolom Kanan: Form Registrasi -->
    <div class="right-form">
        <div class="form-header">
            <div class="mini-logo">
                <i class="bi bi-layers-fill"></i>
            </div>
            <h3>The ArchipelagoNexus</h3>
            <p>Buat akun baru untuk melanjutkan</p>
        </div>

        @if (session('success'))
            <div class="flash"><i class="bi bi-check-circle me-1"></i> {{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Nama -->
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

            <!-- Nomor HP Freelancer -->
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
                        <i class="bi bi-eye text-secondary"></i>
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
                        <i class="bi bi-eye text-secondary"></i>
                    </button>
                </div>
            </div>

            <!-- Checkbox Perusahaan -->
            <div class="mb-3 form-check">
                <input class="form-check-input" type="checkbox" name="is_company" value="1" id="is_company" {{ old('is_company') ? 'checked' : '' }} onchange="toggleCompanyFields()">
                <label class="form-check-label" for="is_company">
                    Daftar sebagai Perusahaan / Client
                </label>
            </div>

            <!-- Bagian Form Perusahaan -->
            <div id="companyFields" class="company-box-dark" style="display: {{ old('is_company') ? 'block' : 'none' }};">
                <div class="text-info fw-semibold mb-3" style="font-size: 12px;"><i class="bi bi-building me-1"></i> Informasi Detail Perusahaan</div>
                
                <div class="mb-3">
                    <label class="form-label-custom">Nama Perusahaan</label>
                    <input name="company_name" type="text" value="{{ old('company_name') }}" class="form-control bg-transparent text-white border border-secondary rounded-2 px-3 py-2 fs-7" style="font-size: 13px;" placeholder="PT / CV / Nama Usaha">
                    @error('company_name')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label-custom">Nomor Telepon Perusahaan</label>
                    <input name="company_phone" type="text" value="{{ old('company_phone') }}" class="form-control bg-transparent text-white border border-secondary rounded-2 px-3 py-2" style="font-size: 13px;" placeholder="08xxxxxxxxxx">
                    @error('company_phone')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label-custom">Alamat Perusahaan</label>
                    <textarea name="company_address" rows="2" class="form-control bg-transparent text-white border border-secondary rounded-2 px-3 py-2" style="font-size: 13px;" placeholder="Alamat lengkap lokasi">{{ old('company_address') }}</textarea>
                    @error('company_address')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="mb-0">
                    <label class="form-label-custom">Deskripsi Perusahaan (Opsional)</label>
                    <textarea name="company_description" rows="2" class="form-control bg-transparent text-white border border-secondary rounded-2 px-3 py-2" style="font-size: 13px;" placeholder="Ceritakan sedikit...">{{ old('company_description') }}</textarea>
                </div>
            </div>

            <!-- Tombol Submit -->
            <button class="btn-submit-dark" type="submit">
                Daftar Sekarang
            </button>
        </form>

        <div class="footer-links">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
        </div>
    </div>

</div>

<script>
    function toggleCompanyFields() {
        const companyFields = document.getElementById('companyFields');
        const checked = document.querySelector('input[name="is_company"]').checked;
        companyFields.style.display = checked ? 'block' : 'none';
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