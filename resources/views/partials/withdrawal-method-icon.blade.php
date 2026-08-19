{{-- Icon/logo metode pembayaran penarikan (dipakai di halaman freelancer & admin).
     Menerima variabel $wd (Withdrawal). Menampilkan tile bundar ber-gradient
     dengan logo DANA (SVG premium) atau ikon FontAwesome per metode. --}}
@php
    $methodKey = strtolower($wd->bank_name ?? '');
    $isEwallet = $wd->method === \App\Models\Withdrawal::METHOD_EWALLET;
    $isDana = $isEwallet && str_contains($methodKey, 'dana');

    $iconMap = [
        'ovo'      => ['icon' => 'fa-wallet', 'grad' => 'from-purple-500 to-fuchsia-600'],
        'gopay'    => ['icon' => 'fa-wallet', 'grad' => 'from-emerald-500 to-green-600'],
        'shopeepay' => ['icon' => 'fa-bag-shopping', 'grad' => 'from-orange-400 to-orange-600'],
        'linkaja'  => ['icon' => 'fa-link', 'grad' => 'from-red-500 to-rose-600'],
        'isaku'    => ['icon' => 'fa-wallet', 'grad' => 'from-teal-500 to-cyan-600'],
        'paypal'   => ['icon' => 'fa-paypal', 'grad' => 'from-sky-500 to-blue-700'],
        'bca'      => ['icon' => 'fa-building-columns', 'grad' => 'from-blue-500 to-blue-700'],
        'bri'      => ['icon' => 'fa-building-columns', 'grad' => 'from-sky-500 to-blue-600'],
        'bni'      => ['icon' => 'fa-building-columns', 'grad' => 'from-orange-400 to-amber-600'],
        'mandiri'  => ['icon' => 'fa-building-columns', 'grad' => 'from-yellow-400 to-amber-500'],
        'btn'      => ['icon' => 'fa-building-columns', 'grad' => 'from-indigo-500 to-blue-600'],
        'cimb'     => ['icon' => 'fa-building-columns', 'grad' => 'from-red-500 to-rose-700'],
        'bsi'      => ['icon' => 'fa-building-columns', 'grad' => 'from-emerald-500 to-teal-700'],
        'danamon'  => ['icon' => 'fa-building-columns', 'grad' => 'from-slate-500 to-slate-700'],
        'permata'  => ['icon' => 'fa-building-columns', 'grad' => 'from-red-400 to-red-600'],
        'mayapada' => ['icon' => 'fa-building-columns', 'grad' => 'from-teal-500 to-emerald-600'],
        'jago'     => ['icon' => 'fa-building-columns', 'grad' => 'from-emerald-500 to-green-600'],
    ];

    $matched = null;
    foreach ($iconMap as $key => $cfg) {
        if (str_contains($methodKey, $key)) {
            $matched = $cfg;
            break;
        }
    }

    if (!$matched) {
        $matched = $isEwallet
            ? ['icon' => 'fa-wallet', 'grad' => 'from-slate-500 to-slate-700']
            : ['icon' => 'fa-building-columns', 'grad' => 'from-slate-500 to-slate-700'];
    }
@endphp

@if($isDana)
    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-sky-400 to-blue-600 flex items-center justify-center ring-2 ring-sky-200 dark:ring-sky-900/60 shadow-md shadow-sky-500/20 shrink-0">
        <svg viewBox="0 0 24 24" class="w-6 h-6" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="DANA">
            <path d="M4 8.5C4 6.57 5.57 5 7.5 5H16.5C18.43 5 20 6.57 20 8.5V15.5C20 17.43 18.43 19 16.5 19H7.5C5.57 19 4 17.43 4 15.5V8.5Z" stroke="white" stroke-width="1.6" stroke-linejoin="round"/>
            <path d="M8.5 9.5H15.5" stroke="white" stroke-width="1.6" stroke-linecap="round"/>
            <path d="M8.5 12.5H13" stroke="white" stroke-width="1.6" stroke-linecap="round"/>
            <path d="M15.5 15H17" stroke="white" stroke-width="1.6" stroke-linecap="round"/>
            <circle cx="17.5" cy="13" r="1" fill="white"/>
        </svg>
    </div>
@else
    <div class="w-11 h-11 rounded-xl bg-gradient-to-br {{ $matched['grad'] }} flex items-center justify-center ring-2 ring-white/60 dark:ring-white/10 shadow-md shadow-black/10 shrink-0">
        <i class="fa-solid {{ $matched['icon'] }} text-white text-base"></i>
    </div>
@endif
