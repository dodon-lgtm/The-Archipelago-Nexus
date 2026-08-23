<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - {{ $appName }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="antialiased min-h-screen flex items-center justify-center p-3 md:p-6 bg-slate-50">

<div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-blue-50 overflow-hidden mx-auto">
    <div class="p-8 pt-6">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-12 h-12 bg-blue-500 text-white rounded-xl flex items-center justify-center mx-auto mb-3">
                <i class="fa-solid fa-lock-open text-2xl"></i>
            </div>
            <h2 class="text-2xl font-black text-slate-900 mb-2">Lupa Password?</h2>
            <p class="text-slate-600">Kami menerima permintaan untuk mengatur ulang password akun Anda.</p>
        </div>

        <!-- Main Content -->
        <div class="space-y-4">
            <p class="text-slate-600 font-medium">Halo <strong>{{ $name }}</strong>,</p>
            <p class="text-slate-600">Kami menerima permintaan dari akun Anda untuk mengatur ulang password.</p>

            <p class="text-slate-500 text-sm mt-4">Kode OTP Anda:</p>

            <!-- Menampilkan Angka OTP -->
            <div class="flex justify-center space-x-2 my-4">
                @foreach(str_split((string) $otp) as $digit)
                    <span class="w-10 h-12 flex items-center justify-center text-2xl font-black bg-blue-50 border-2 border-blue-500 text-blue-600 rounded-xl shadow-sm">
                        {{ $digit }}
                    </span>
                @endforeach
            </div>

            <p class="text-slate-500 text-sm mt-4">Kode ini berlaku selama 5 menit.</p>
            <p class="text-slate-500 text-sm">Jika Anda tidak meminta reset password, abaikan email ini.</p>

            <!-- Footer -->
            <div class="mt-8 pt-8 border-t border-blue-100/30 text-slate-500 text-xs text-center">
                <p>{{ $appName }}</p>
                <p>Platform Talenta Nusantara</p>
            </div>
        </div>
    </div>
</div>

</body>
</html>