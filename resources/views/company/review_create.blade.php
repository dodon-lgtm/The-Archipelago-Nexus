<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.theme-boot')



<title>Beri Rating & Ulasan - FreelanceID</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
<style>
body{
    background:#f4f7fc;
    font-family:'Segoe UI',sans-serif;
}
.container{
    max-width: 700px;
    margin-top: 50px;
    margin-bottom: 50px;
}
.review-card{
    background: white;
    border-radius: 22px;
    box-shadow: 0 15px 40px rgba(0,0,0,.08);
    padding: 40px;
}
.freelancer-photo{
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #eef5ff;
    box-shadow: 0 8px 20px rgba(0,0,0,.1);
}
.star-rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: center;
    gap: 5px;
}
.star-rating input {
    display: none;
}
.star-rating label {
    font-size: 35px;
    color: #ddd;
    cursor: pointer;
    transition: color 0.2s;
}
.star-rating input:checked ~ label,
.star-rating label:hover,
.star-rating label:hover ~ label {
    color: #ffc107;
}
.btn-submit {
    border-radius: 50px;
    padding: 12px 30px;
    font-weight: 600;
}


    html.dark body {
        --bg-color: #0f172a;
        --card-bg: rgba(15, 23, 42, 0.88);
        --text-main: #f8fafc;
        --text-muted: #94a3b8;
        --border-color: rgba(51, 65, 85, 0.9);
        background-color: #0f172a !important;
        color: #f8fafc;
    }
    html.dark .card,
    html.dark .review-card {
        background: #1e293b !important;
        color: #f8fafc;
        border-color: #334155 !important;
    }
    html.dark .card-header {
        background: linear-gradient(135deg, rgba(30, 41, 59, .96) 0%, rgba(15, 23, 42, .96) 100%) !important;
        color: #f8fafc;
        border-color: #334155;
    }
    html.dark .form-control,
    html.dark .form-select,
    html.dark textarea,
    html.dark input,
    html.dark select {
        background-color: #0f172a !important;
        color: #f8fafc !important;
        border-color: #334155 !important;
    }
    html.dark .text-muted { color: #94a3b8 !important; }
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
    <div class="review-card">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-dark">Beri Rating Freelancer</h2>
            <p class="text-muted">Bagaimana pengalaman Anda bekerja sama dalam project ini?</p>
        </div>

        {{-- Info Project & Freelancer --}}
        <div class="card bg-light border-0 rounded-4 p-3 mb-4 text-center">
<div class="mb-2">
                @php
                    $profile = $freelancer->freelancerProfile ?? null;
                @endphp
                @if($profile && $profile->photo)
                    <img src="{{ asset('storage/' . $profile->photo) }}" class="freelancer-photo" alt="Foto Freelancer">
                @else
                    <img src="{{ asset('images/default-profile.png') }}" class="freelancer-photo" alt="Default Foto">
                @endif
            </div>
            <h5 class="fw-bold mb-1">{{ $freelancer->name ?? 'Freelancer' }}</h5>
            <small class="text-muted">Project: <b>{{ $project->project_name ?? 'Judul Project' }}</b></small>
        </div>

        {{-- Form Review --}}
        <form action="{{ route('client.review.store', $project->id) }}" method="POST">
            @csrf

            <div class="mb-4 text-center">
                <label class="form-label d-block fw-semibold mb-2">Pilih Rating Bintang</label>
                <div class="star-rating">
                    <input type="radio" id="star5" name="rating" value="5" required /><label for="star5" title="5 bintang"><i class="fa-solid fa-star"></i></label>
                    <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="4 bintang"><i class="fa-solid fa-star"></i></label>
                    <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="3 bintang"><i class="fa-solid fa-star"></i></label>
                    <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="2 bintang"><i class="fa-solid fa-star"></i></label>
                    <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="1 bintang"><i class="fa-solid fa-star"></i></label>
                </div>
            </div>

            <div class="mb-4">
                <label for="review" class="form-label fw-semibold">Tulis ulasan Anda:</label>
                <textarea name="review" id="review" rows="4" class="form-control rounded-3" placeholder="Tuliskan detail kepuasan kerja sama, ketepatan waktu, dan kualitas hasil kerja..."></textarea>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-submit shadow-sm">
                    <i class="fa-solid fa-paper-plane me-2"></i> Kirim Rating & Ulasan
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>