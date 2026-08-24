<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.theme-boot')
    <title>{{ config('app.name', 'ApexForge Labs') }}</title>
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


.container{max-width:1040px;margin:0 auto;padding:28px 18px}
.card{
    background:rgba(255,255,255,.78);border:1px solid #dbeafe;border-radius:28px;
    padding:34px 24px;box-shadow:0 24px 70px -38px rgba(30,64,175,.32);
    backdrop-filter:blur(18px);-webkit-backdrop-filter:blur(18px)
}
h1{margin:0 0 10px;font-size:clamp(28px,5vw,46px);letter-spacing:-.04em;color:#0f172a}
p{margin:0 0 22px;color:#475569;line-height:1.7}
.btns{display:flex;flex-direction:column;gap:12px;max-width:420px}
@media(min-width:640px){.btns{flex-direction:row;flex-wrap:wrap;max-width:none}}
a.button{
    display:inline-block;text-decoration:none;padding:13px 18px;border-radius:14px;
    background:linear-gradient(135deg,#2563eb,#3b82f6);color:#fff;font-weight:700;text-align:center;
    flex:1 1 170px;box-shadow:0 12px 24px -14px rgba(37,99,235,.75)
}
a.button.secondary{background:#fff;color:#1e3a8a;border:1px solid #dbeafe}
a.button:hover{transform:translateY(-1px);filter:brightness(1.02)}
.muted{font-size:14px;color:#64748b;margin-top:18px}
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


.container{max-width:1040px;margin:0 auto;padding:28px 18px}
.card{
    background:rgba(255,255,255,.78);border:1px solid #dbeafe;border-radius:28px;
    padding:34px 24px;box-shadow:0 24px 70px -38px rgba(30,64,175,.32);
    backdrop-filter:blur(18px);-webkit-backdrop-filter:blur(18px)
}
h1{margin:0 0 10px;font-size:clamp(28px,5vw,46px);letter-spacing:-.04em;color:#0f172a}
p{margin:0 0 22px;color:#475569;line-height:1.7}
.btns{display:flex;flex-direction:column;gap:12px;max-width:420px}
@media(min-width:640px){.btns{flex-direction:row;flex-wrap:wrap;max-width:none}}
a.button{
    display:inline-block;text-decoration:none;padding:13px 18px;border-radius:14px;
    background:linear-gradient(135deg,#2563eb,#3b82f6);color:#fff;font-weight:700;text-align:center;
    flex:1 1 170px;box-shadow:0 12px 24px -14px rgba(37,99,235,.75)
}
a.button.secondary{background:#fff;color:#1e3a8a;border:1px solid #dbeafe}
a.button:hover{transform:translateY(-1px);filter:brightness(1.02)}
.muted{font-size:14px;color:#64748b;margin-top:18px}
</style>
</head>
<body>
<div class="container">
    <div class="card" style="text-align: left;">
        <h1>ApexForge Labs</h1>
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

