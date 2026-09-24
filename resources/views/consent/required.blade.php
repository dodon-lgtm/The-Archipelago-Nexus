<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persetujuan Kebijakan Diperlukan - ApexForge Labs</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Google Font --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="antialiased min-h-screen flex items-center justify-center p-3 md:p-6 bg-cover bg-center bg-no-repeat bg-fixed" style="background-image: url('{{ asset('images/backgroundlogin.png') }}');">

    <div class="max-w-2xl w-full bg-white rounded-3xl shadow-2xl shadow-slate-200/80 border border-blue-50 overflow-hidden" data-aos="zoom-in" data-aos-duration="800">
        <div class="p-6 md:p-10 flex flex-col justify-between space-y-6 relative overflow-hidden bg-white">

            <!-- Header Brand -->
            <div class="flex items-center gap-3 justify-center">
                <div class="w-9 h-9 bg-slate-900 text-white rounded-xl flex items-center justify-center font-bold text-xs shadow-md shadow-slate-900/20 overflow-hidden ring-2 ring-slate-900/10">
                    <img src="{{ asset('images/nexus.jpg') }}" alt="ApexForge Labs Logo" class="w-7 h-7 rounded-full object-cover">
                </div>
                <span class="font-extrabold text-base tracking-tight text-slate-900">
                    ApexForge Labs
                </span>
            </div>

            <div class="space-y-4 text-center">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-500 text-2xl mb-4">
                    <i class="fa-solid fa-file-contract"></i>
                </div>
                <h1 class="text-2xl font-black text-slate-900 leading-tight">
                    Persetujuan Kebijakan Diperlukan
                </h1>
                <p class="text-sm text-slate-600 max-w-md leading-relaxed font-medium mx-auto">
                    Beberapa kebijakan telah diperbarui. Anda perlu menyetujui versi terbaru sebelum melanjutkan menggunakan platform.
                </p>
            </div>

            @if (session('success'))
                <div class="p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-700 text-sm flex items-center gap-2 justify-center">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('consent.required.store') }}" class="space-y-4">
                @csrf

                @foreach ($pendingPolicies as $policy)
                    <div class="bg-slate-50 dark:bg-slate-800/50 border border-blue-100 dark:border-slate-700 rounded-xl p-4 space-y-3">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-blue-500/10 rounded-lg flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-file-contract text-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 dark:text-white">{{ $policy->title }}</h3>
                                <span class="text-xs text-slate-500">Versi baru: v{{ $policy->version }} (berlaku sejak {{ $policy->updated_at?->translatedFormat('d M Y') }})</span>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <div class="flex items-start gap-2.5">
                                <input
                                    type="checkbox"
                                    name="policy_{{ $policy->id }}"
                                    id="policy_{{ $policy->id }}"
                                    value="1"
                                    required
                                    class="mt-0.5 w-4 h-4 rounded border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-blue-600 focus:ring-blue-500 cursor-pointer"
                                >
                                <label for="policy_{{ $policy->id }}" class="text-sm text-slate-700 dark:text-slate-300 cursor-pointer leading-relaxed">
                                    Saya telah membaca dan menyetujui
                                    <a href="{{ $policy->key === 'terms' ? route('syarat-ketentuan') : ($policy->key === 'privacy' ? route('kebijakan-privasi') : route('kebijakan-penggunaan')) }}" target="_blank" class="text-blue-600 hover:text-blue-500 underline font-medium">
                                        {{ $policy->title }} v{{ $policy->version }}
                                    </a>
                                    <span class="text-red-500">*</span>
                                </label>
                            </div>
                            @error("policy_{$policy->id}")
                                <p class="text-xs text-red-500 ml-6.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <a href="{{ $policy->key === 'terms' ? route('syarat-ketentuan') : ($policy->key === 'privacy' ? route('kebijakan-privasi') : route('kebijakan-penggunaan')) }}" target="_blank"
                            class="text-xs text-blue-600 hover:text-blue-500 underline flex items-center gap-1">
                            <i class="fa-solid fa-eye"></i> Baca dokumen lengkap
                        </a>
                    </div>
                @endforeach

                <button type="submit" class="w-full py-2.5 mt-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-600/20 transition transform active:scale-[0.98] flex items-center justify-center gap-2 group">
                    <i class="fa-solid fa-check"></i>
                    Setujui & Lanjutkan
                    <i class="fa-solid fa-arrow-right group-hover:translate-x-0.5 transition-transform"></i>
                </button>
            </form>

            <div class="text-center text-xs text-slate-400 pt-4">
                <p>Dengan melanjutkan, Anda menyetujui kebijakan terbaru ApexForge Labs.</p>
            </div>
        </div>
    </div>

    {{-- AOS Animation Library JS --}}
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ once: true });
    </script>
</body>
</html>