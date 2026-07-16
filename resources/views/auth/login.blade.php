<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f7f7f7; margin: 0; padding: 0; }
        .container { max-width: 420px; margin: 60px auto; padding: 0 16px; }
        .card { background: #fff; border-radius: 8px; padding: 20px; box-shadow: 0 2px 12px rgba(0,0,0,0.05); }
        .field { margin-bottom: 14px; }
        label { display: block; font-size: 14px; margin-bottom: 6px; }
        input { width: 100%; padding: 10px; border: 1px solid #d9d9d9; border-radius: 6px; }
        input.is-invalid { border-color: #dc3545; }
        .error { color: #dc3545; font-size: 13px; margin-top: 6px; }
        .btn { width: 100%; padding: 10px; border: 0; border-radius: 6px; background: #111; color: #fff; cursor: pointer; }
        .flash { margin-bottom: 14px; padding: 10px 12px; border-radius: 6px; background: #fff3cd; color: #664d03; border: 1px solid #ffeeba; }
        .links { margin-top: 12px; font-size: 14px; }
        a { color: #111; }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <h1 style="margin:0 0 16px; font-size:20px;">Login</h1>

        @if (session('success'))
            <div class="flash">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="field">
                <label for="email">Email</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                    required
                    autofocus
                >
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                    required
                >
                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <button class="btn" type="submit">Masuk</button>
        </form>

        <div class="links">
            Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
        </div>
    </div>
</div>
</body>
</html>

