<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f7f7f7; margin: 0; padding: 0; }
        .container { max-width: 520px; margin: 40px auto; padding: 0 16px; }
        .card { background: #fff; border-radius: 8px; padding: 20px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); }
        .field { margin-bottom: 14px; }
        label { display: block; font-size: 14px; margin-bottom: 6px; }
        input, textarea { width: 100%; padding: 10px; border: 1px solid #d9d9d9; border-radius: 6px; }
        input.is-invalid { border-color: #dc3545; }
        textarea.is-invalid { border-color: #dc3545; }
        .error { color: #dc3545; font-size: 13px; margin-top: 6px; }
        .btn { width: 100%; padding: 10px; border: 0; border-radius: 6px; background: #111; color: #fff; cursor: pointer; }
        .flash { margin-bottom: 14px; padding: 10px 12px; border-radius: 6px; background: #fff3cd; color: #664d03; border: 1px solid #ffeeba; }
        .links { margin-top: 12px; font-size: 14px; }
        a { color: #111; }
        .row { display: flex; gap: 12px; }
        .row > div { flex: 1; }
        @media (max-width: 520px) { .row { flex-direction: column; } }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <h1 style="margin:0 0 16px; font-size:20px;">Register</h1>

        @if (session('success'))
            <div class="flash">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="field">
                <label for="name">Nama</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" class="{{ $errors->has('name') ? 'is-invalid' : '' }}" required autofocus>
                @error('name')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" class="{{ $errors->has('email') ? 'is-invalid' : '' }}" required>
                @error('email')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" class="{{ $errors->has('password') ? 'is-invalid' : '' }}" required>
                @error('password')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="" required>
            </div>

            <div class="field">
                <label style="display:flex; align-items:center; gap:10px; margin: 0;">
                    <input type="checkbox" name="is_company" value="1" {{ old('is_company') ? 'checked' : '' }} onchange="toggleCompanyFields()">
                    <span>Daftar sebagai Perusahaan</span>
                </label>
            </div>

            <div id="companyFields" style="display: {{ old('is_company') ? 'block' : 'none' }};">
                <div class="field">
                    <label for="company_name">Nama Perusahaan</label>
                    <input id="company_name" name="company_name" type="text" value="{{ old('company_name') }}" class="{{ $errors->has('company_name') ? 'is-invalid' : '' }}">
                    @error('company_name')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label for="company_phone">Nomor Telepon</label>
                    <input id="company_phone" name="company_phone" type="text" value="{{ old('company_phone') }}" class="{{ $errors->has('company_phone') ? 'is-invalid' : '' }}">
                    @error('company_phone')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label for="company_address">Alamat</label>
                    <textarea id="company_address" name="company_address" rows="3" class="{{ $errors->has('company_address') ? 'is-invalid' : '' }}">{{ old('company_address') }}</textarea>
                    @error('company_address')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label for="company_description">Deskripsi Perusahaan (opsional)</label>
                    <textarea id="company_description" name="company_description" rows="3">{{ old('company_description') }}</textarea>
                </div>
            </div>

            <button class="btn" type="submit">Daftar</button>
        </form>

        <div class="links">
            Sudah punya akun? <a href="{{ route('login') }}">Login</a>
        </div>
    </div>
</div>

<script>
    function toggleCompanyFields() {
        const companyFields = document.getElementById('companyFields');
        const checked = document.querySelector('input[name="is_company"]').checked;
        companyFields.style.display = checked ? 'block' : 'none';
    }
</script>
</body>
</html>

