<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'The Archipelago Nexus') }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f7f7f7; color: #111; }
        .container { max-width: 980px; margin: 0 auto; padding: 24px 16px; }
        .card { background: #fff; border: 1px solid #eaeaea; border-radius: 12px; padding: 28px 20px; }
        h1 { margin: 0 0 8px; font-size: 28px; }
        p { margin: 0 0 20px; color: #444; line-height: 1.5; }
        .btns { display: flex; flex-direction: column; gap: 12px; max-width: 360px; }
        @media (min-width: 640px) {
            .btns { flex-direction: row; flex-wrap: wrap; max-width: none; }
        }
        a.button {
            display: inline-block;
            text-decoration: none;
            padding: 12px 16px;
            border-radius: 10px;
            background: #111;
            color: #fff;
            font-weight: 600;
            text-align: center;
            flex: 1 1 160px;
        }
        a.button.secondary { background: #fff; color: #111; border: 1px solid #d9d9d9; font-weight: 600; }
        a.button:hover { filter: brightness(0.95); }
        .muted { font-size: 14px; color: #666; margin-top: 16px; }
    </style>
</head>
<body>
<div class="container">
    <div class="card" style="text-align: left;">
        <h1>The Archipelago Nexus</h1>
        <p>
            Platform pengelolaan proyek untuk perusahaan dan individu.
        </p>

        <div class="btns">
            <a class="button secondary" href="{{ route('login') }}">Login</a>
            <a class="button secondary" href="{{ route('register') }}">Register</a>
            {{-- <a class="button" href="{{ route('company-account-requests.create') }}">Ajukan Akun Perusahaan</a> --}}
        </div>

        <div class="muted">
            Halaman ini berfungsi sebagai gerbang masuk selama proses pengembangan.
        </div>
    </div>
</div>
</body>
</html>

