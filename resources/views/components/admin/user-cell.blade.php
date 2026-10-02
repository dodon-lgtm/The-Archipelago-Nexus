@props([
    'user' => null,
    'name' => null,
    'email' => null,
    'sub' => null,
    'initials' => null,
    'photo' => null,
    'withPhoto' => false,
    'size' => 'md',
    'avatarClass' => 'bg-gradient-to-br from-blue-100 to-blue-50 ring-1 ring-blue-100 text-blue-600',
])

@php
    $label = $name ?? ($user->name ?? null);
    $letter = $initials ?? strtoupper(mb_substr((string) ($label ?? '?'), 0, 1));
    $href = (($user ?? null) && $user->id) ? route('admin.users.show', $user) : null;
    $subText = $sub ?? (($email ?? ($user->email ?? null)) ?: null);

    // Ukuran avatar/teks konsisten: sm | md (default) | lg
    $sizes = [
        'sm' => ['box' => 'w-8 h-8', 'text' => 'text-xs', 'name' => 'text-sm', 'sub' => 'text-[10px]'],
        'md' => ['box' => 'w-9 h-9', 'text' => 'text-sm', 'name' => 'text-sm', 'sub' => 'text-[10px]'],
        'lg' => ['box' => 'w-14 h-14', 'text' => 'text-xl', 'name' => 'text-base', 'sub' => 'text-xs'],
    ];
    $s = $sizes[$size] ?? $sizes['md'];

    // Foto profil: hanya diaktifkan secara eksplisit (withPhoto) agar list tidak melakukan query tambahan.
    // Sumber data yang SUDAH tersedia di project:
    //   freelancer -> freelancer_profiles.photo | company -> company_profiles.company_logo
    $photoPath = $photo;
    if ($withPhoto && $user && !$photoPath) {
        if ($user->role === 'freelancer') {
            $photoPath = optional($user->freelanceProfile)->photo;
        } elseif ($user->role === 'company') {
            $photoPath = optional($user->companyProfile)->company_logo;
        }
    }
    $photoUrl = $photoPath ? asset('storage/' . $photoPath) : null;
@endphp

@if ($href)
    <div class="flex items-center gap-3 group min-w-0">
        <a href="{{ $href }}" class="shrink-0" aria-label="Lihat profil {{ $label }}" title="{{ $label }}">
            @if ($photoUrl)
                <img src="{{ $photoUrl }}" alt="{{ $label }}" loading="lazy"
                    class="{{ $s['box'] }} rounded-full object-cover ring-1 ring-blue-100 dark:ring-slate-700 shadow-sm transition group-hover:ring-2 group-hover:ring-blue-200 dark:group-hover:ring-blue-800"
                    onerror="this.style.display='none'; if (this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                <div
                    class="hidden {{ $s['box'] }} rounded-full {{ $avatarClass }} dark:text-blue-300 items-center justify-center {{ $s['text'] }} font-bold">
                    {{ $letter }}
                </div>
            @else
                <div
                    class="{{ $s['box'] }} rounded-full {{ $avatarClass }} dark:text-blue-300 flex items-center justify-center {{ $s['text'] }} font-bold transition group-hover:ring-2 group-hover:ring-blue-200 dark:group-hover:ring-blue-800">
                    {{ $letter }}
                </div>
            @endif
        </a>
        <div class="min-w-0">
            <a href="{{ $href }}"
                class="block font-semibold text-slate-800 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 truncate {{ $s['name'] }} transition"
                title="{{ $label }}">{{ $label }}</a>
            @if ($subText)
                <p class="{{ $s['sub'] }} text-slate-400 dark:text-slate-500 truncate">{{ $subText }}</p>
            @endif
        </div>
    </div>
@else
    <div class="flex items-center gap-3 min-w-0">
        @if ($photoUrl)
            <img src="{{ $photoUrl }}" alt="{{ $label ?? 'pengguna' }}" loading="lazy"
                class="{{ $s['box'] }} rounded-full object-cover ring-1 ring-blue-100 dark:ring-slate-700 shadow-sm shrink-0"
                onerror="this.style.display='none'; if (this.nextElementSibling) this.nextElementSibling.style.display='flex';">
            <div
                class="hidden {{ $s['box'] }} rounded-full {{ $avatarClass }} items-center justify-center {{ $s['text'] }} font-bold shrink-0">
                {{ $letter }}
            </div>
        @else
            <div
                class="{{ $s['box'] }} rounded-full {{ $avatarClass }} flex items-center justify-center {{ $s['text'] }} font-bold shrink-0">
                {{ $letter }}
            </div>
        @endif
        <div class="min-w-0">
            <p class="font-semibold text-slate-800 dark:text-white truncate {{ $s['name'] }}">{{ $label ?? '—' }}</p>
            @if ($subText)
                <p class="{{ $s['sub'] }} text-slate-400 dark:text-slate-500 truncate">{{ $subText }}</p>
            @endif
        </div>
    </div>
@endif