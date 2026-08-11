<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
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