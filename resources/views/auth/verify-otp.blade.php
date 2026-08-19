<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - ApexForge Labs</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Font -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
    <style>
        /* ApexForge Labs — Unified UI System */
        :root {
            --af-primary: #2563eb;
            --af-primary-dark: #1d4ed8;
            --af-primary-soft: #eff6ff;
            --af-sky: #38bdf8;
            --af-ink: #0f172a;
            --af-muted: #64748b;
            --af-border: #dbeafe;
            --af-surface: #ffffff;
            --af-page: #f6f9ff;
        }

        html {
            scroll-behavior: smooth
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background:
                radial-gradient(circle at 10% -10%, rgba(56, 189, 248, .10), transparent 30%),
                radial-gradient(circle at 100% 0%, rgba(37, 99, 235, .08), transparent 28%),
                var(--af-page);
        }

        ::selection {
            background: rgba(37, 99, 235, .18);
            color: #0f172a
        }

        ::-webkit-scrollbar {
            width: 7px;
            height: 7px
        }

        ::-webkit-scrollbar-track {
            background: rgba(241, 245, 249, .7)
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(37, 99, 235, .22);
            border-radius: 999px
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(37, 99, 235, .38)
        }

        input,
        select,
        textarea {
            border-color: var(--af-border) !important;
            background: rgba(255, 255, 255, .92);
            transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: rgba(37, 99, 235, .55) !important;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, .09) !important;
            outline: none !important;
        }

        button,a,[role="button"] {
            transition: all .2s ease
        }

        button:focus-visible,a:focus-visible,[role="button"]:focus-visible {
            outline: 2px solid rgba(37, 99, 235, .55);
            outline-offset: 2px;
        }

        table {
            border-collapse: separate;
            border-spacing: 0
        }

        thead th {
            background: rgba(239, 246, 255, .72) !important;
            color: #334155;
            font-weight: 700;
        }

        tbody tr {
            transition: background .18s ease
        }

        tbody tr:hover {
            background: rgba(239, 246, 255, .48)
        }

        [class*="bg-blue-600"] {
            box-shadow: 0 8px 22px -12px rgba(37, 99, 235, .72);
        }

        [class*="bg-blue-600"]:hover {
            box-shadow: 0 12px 28px -12px rgba(37, 99, 235, .78);
            transform: translateY(-1px);
        }

        .glass-panel,
        .glass-card,
        .glass-surface {
            background: rgba(255, 255, 255, .72);
            border: 1px solid rgba(219, 234, 254, .85);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            box-shadow: 0 18px 50px -32px rgba(30, 64, 175, .32);
        }

        .apex-page-glow {
            position: fixed;
            inset: auto -10rem -12rem auto;
            width: 28rem;
            height: 28rem;
            background: rgba(56, 189, 248, .09);
            filter: blur(70px);
            border-radius: 999px;
            pointer-events: none;
            z-index: -1;
        }

        @media (max-width:767px) {
            main {
                padding-left: 1rem !important;
                padding-right: 1rem !important
            }

            table {
                min-width: 680px
            }

            .overflow-x-auto {
                -webkit-overflow-scrolling: touch
            }
        }

        @media (prefers-reduced-motion:reduce) {

            *,
            *::before,
            *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
                scroll-behavior: auto !important
            }
        }
    </style>
</head>

<body class="antialiased min-h-screen flex items-center justify-center p-3 md:p-6 bg-cover bg-no-repeat bg-fixed">

    <!-- Background -->
    <div class="apex-page-glow" style="left: 50%; top: 50%; transform: translate(-50%, -50%);"></div>

    <!-- Container Utama -->
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-blue-50 overflow-hidden mx-auto">

        <!-- Flash Messages -->
        @if (session('status'))
            <div class="p-4 bg-green-100 text-green-800 rounded-t-3xl mb-4 animate-fade-in">
                <i class="fa-solid fa-circle-check text-lg mr-2"></i>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="p-4 bg-red-100 text-red-800 rounded-t-3xl mb-4 animate-fade-in">
                <i class="fa-solid fa-circle-exclamation text-lg mr-2"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('password.verify.submit') }}" method="POST" class="p-6 md:p-8 space-y-4">
            @csrf

            @if (session('otp_expired'))
                <div class="p-4 bg-red-100 text-red-800 rounded-b-3xl mb-4 animate-fade-in">
                    <i class="fa-solid fa-circle-exclamation text-lg mr-2"></i>
                    <span>{{ session('otp_expired') }}</span>
                </div>
            @endif

            @if (session('too_many_attempts'))
                <div class="p-4 bg-red-100 text-red-800 rounded-b-3xl mb-4 animate-fade-in">
                    <i class="fa-solid fa-circle-exclamation text-lg mr-2"></i>
                    <span>{{ session('too_many_attempts') }}</span>
                </div>
            @endif

            @if (session('otp_invalid'))
                <div class="p-4 bg-red-100 text-red-800 rounded-b-3xl mb-4 animate-fade-in">
                    <i class="fa-solid fa-circle-exclamation text-lg mr-2"></i>
                    <span>{{ session('otp_invalid') }}</span>
                </div>
            @endif

            <input type="hidden" name="otp_id" value="{{ session('otp_id') }}">

            <div class="space-y-2">
                <p class="text-slate-600 text-sm">Kode OTP telah dikirim ke email <strong>{{ session('otp_email') }}</strong>.</p>
                <p class="text-slate-500 text-xs">Masukkan 6 digit kode OTP yang Anda terima.</p>

                <div class="flex gap-1">
                    {{-- 6 digit input boxes --}}
                    @for ($i = 1; $i <= 6; $i++)
                        <input type="text" name="otp_digit_$i"
                            maxlength="1"
                            pattern="[0-9]"
                            inputmode="numeric"
                            class="w-10 h-10 text-center text-3xl font-bold border-2 border-blue-300 rounded-lg focus:outline-none focus:border-blue-500 transition"
                            {{ $i > 1 ? 'style="border-left: 2px solid transparent;"' : '' }}>
                    @endfor
                </div>
            </div>

            <div class="flex gap-2 pt-4">
                <button type="submit"
                    class="flex-1 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white text-xs font-bold rounded-xl shadow-lg shadow-blue-600/20 transition transform active:scale-[0.98] flex items-center justify-center gap-2 group">
                    <i class="fa-solid fa-check group-hover:translate-x-0.5 transition-transform"></i>
                    Verifikasi OTP
                </button>
            </div>

            <div class="pt-3">
                <button type="button"
                    onclick="window.history.back()"
                    class="text-[10px] text-slate-400 hover:text-blue-400 hover:underline transition-colors">
                    Kembali
                </button>
            </div>

            @if (session('resend_countdown'))
                <div class="mt-3 text-sm text-slate-500">
                    Kirim ulang kode dalam <span id="resend-countdown">{{ session('resend_countdown') }}</span> detik
                </div>
            @endif
        </form>

        <script>
            // OTP digit input handling
            const inputs = document.querySelectorAll('input[name^="otp_digit_"]');

            inputs.forEach((input, index) => {
                input.addEventListener('input', function() {
                    if (input.value.length > 0 && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                });

                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && input.value.length === 0 && index > 0) {
                        e.preventDefault();
                        inputs[index - 1].focus();
                    }
                });
            });

            // Countdown logic
            const countdownEl = document.getElementById('resend-countdown');
            if (countdownEl) {
                let countdown = {{ session('resend_countdown', 60) }};
                const countdownInterval = setInterval(() => {
                    countdown--;
                    countdownEl.textContent = countdown >= 0 ? countdown : '';
                    if (countdown <= 0) {
                        clearInterval(countdownInterval);
                        countdownEl.textContent = '';
                    }
                }, 1000);
            }
        </script>

</body>

</html>