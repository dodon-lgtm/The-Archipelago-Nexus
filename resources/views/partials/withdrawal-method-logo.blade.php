{{-- Logo/ikon metode pembayaran (tile premium) untuk dipakai di kartu pilihan metode.
     Menerima variabel $name (string) misal "DANA", "BCA", "OVO". Hanya untuk tampilan UI. --}}
@php
    $key = strtolower($name ?? '');
@endphp

@if(str_contains($key, 'dana'))
    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-sky-400 to-blue-600 flex items-center justify-center shadow-md shadow-sky-500/20 shrink-0">
        <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="DANA">
            <path d="M4 8.5C4 6.57 5.57 5 7.5 5H16.5C18.43 5 20 6.57 20 8.5V15.5C20 17.43 18.43 19 16.5 19H7.5C5.57 19 4 17.43 4 15.5V8.5Z" stroke="white" stroke-width="1.6" stroke-linejoin="round"/>
            <path d="M8.5 9.5H15.5" stroke="white" stroke-width="1.6" stroke-linecap="round"/>
            <path d="M8.5 12.5H13" stroke="white" stroke-width="1.6" stroke-linecap="round"/>
            <path d="M15.5 15H17" stroke="white" stroke-width="1.6" stroke-linecap="round"/>
            <circle cx="17.5" cy="13" r="1" fill="white"/>
        </svg>
    </div>
@elseif(str_contains($key, 'ovo'))
    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-purple-500 to-fuchsia-600 flex items-center justify-center shadow-md shadow-purple-500/20 shrink-0">
        <span class="text-white text-[10px] font-black tracking-tight leading-none">OVO</span>
    </div>
@elseif(str_contains($key, 'gopay'))
    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-emerald-400 to-green-600 flex items-center justify-center shadow-md shadow-emerald-500/20 shrink-0">
        <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="GoPay">
            <circle cx="12" cy="12" r="8.2" stroke="white" stroke-width="1.8"/>
            <path d="M8.4 12h7.2M12 8.4v7.2" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
        </svg>
    </div>
@elseif(str_contains($key, 'shopeepay'))
    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center shadow-md shadow-orange-500/20 shrink-0">
        <i class="fa-solid fa-bag-shopping text-white text-sm"></i>
    </div>
@elseif(str_contains($key, 'linkaja'))
    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-red-500 to-rose-600 flex items-center justify-center shadow-md shadow-red-500/20 shrink-0">
        <i class="fa-solid fa-link text-white text-sm"></i>
    </div>
@elseif(str_contains($key, 'isaku'))
    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-teal-500 to-cyan-600 flex items-center justify-center shadow-md shadow-teal-500/20 shrink-0">
        <span class="text-white text-[9px] font-black tracking-tight leading-none">iSaku</span>
    </div>
@elseif(str_contains($key, 'paypal'))
    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-sky-400 to-blue-700 flex items-center justify-center shadow-md shadow-sky-500/20 shrink-0">
        <i class="fa-brands fa-paypal text-white text-base"></i>
    </div>
@elseif(str_contains($key, 'bca'))
    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center shadow-md shadow-blue-500/20 shrink-0">
        <i class="fa-solid fa-building-columns text-white text-sm"></i>
    </div>
@elseif(str_contains($key, 'bri'))
    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-sky-500 to-blue-600 flex items-center justify-center shadow-md shadow-sky-500/20 shrink-0">
        <i class="fa-solid fa-building-columns text-white text-sm"></i>
    </div>
@elseif(str_contains($key, 'bni'))
    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-orange-400 to-amber-600 flex items-center justify-center shadow-md shadow-orange-500/20 shrink-0">
        <i class="fa-solid fa-building-columns text-white text-sm"></i>
    </div>
@elseif(str_contains($key, 'mandiri'))
    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-yellow-400 to-amber-500 flex items-center justify-center shadow-md shadow-amber-500/20 shrink-0">
        <i class="fa-solid fa-building-columns text-white text-sm"></i>
    </div>
@elseif(str_contains($key, 'btn'))
    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center shadow-md shadow-indigo-500/20 shrink-0">
        <i class="fa-solid fa-building-columns text-white text-sm"></i>
    </div>
@elseif(str_contains($key, 'cimb'))
    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-red-500 to-rose-700 flex items-center justify-center shadow-md shadow-red-500/20 shrink-0">
        <i class="fa-solid fa-building-columns text-white text-sm"></i>
    </div>
@elseif(str_contains($key, 'bsi'))
    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-emerald-500 to-teal-700 flex items-center justify-center shadow-md shadow-emerald-500/20 shrink-0">
        <i class="fa-solid fa-building-columns text-white text-sm"></i>
    </div>
@elseif(str_contains($key, 'danamon'))
    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-slate-500 to-slate-700 flex items-center justify-center shadow-md shadow-slate-500/20 shrink-0">
        <i class="fa-solid fa-building-columns text-white text-sm"></i>
    </div>
@elseif(str_contains($key, 'permata'))
    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center shadow-md shadow-red-500/20 shrink-0">
        <i class="fa-solid fa-building-columns text-white text-sm"></i>
    </div>
@elseif(str_contains($key, 'mayapada'))
    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-teal-500 to-emerald-600 flex items-center justify-center shadow-md shadow-teal-500/20 shrink-0">
        <i class="fa-solid fa-building-columns text-white text-sm"></i>
    </div>
@elseif(str_contains($key, 'jago'))
    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center shadow-md shadow-emerald-500/20 shrink-0">
        <i class="fa-solid fa-building-columns text-white text-sm"></i>
    </div>
@else
    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-slate-400 to-slate-600 flex items-center justify-center shadow-md shadow-slate-500/20 shrink-0">
        <i class="fa-solid fa-building-columns text-white text-sm"></i>
    </div>
@endif