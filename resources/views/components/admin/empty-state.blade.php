@props([
    'icon' => 'fa-inbox',
    'title' => 'Belum Ada Data',
    'message' => null,
    'resetUrl' => null,
    'resetLabel' => 'Reset Filter',
])

<div class="px-5 py-14 text-center">
    <div class="flex flex-col items-center gap-3">
        <div
            class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-blue-900/40 border border-blue-100 dark:border-blue-900 text-blue-400 dark:text-blue-500 flex items-center justify-center text-xl">
            <i class="fa-solid {{ $icon }}"></i>
        </div>
        <div>
            <p class="text-sm font-bold text-slate-600 dark:text-slate-300">{{ $title }}</p>
            @if ($message)
                <p class="text-xs text-slate-400 dark:text-slate-500 mt-1 max-w-sm mx-auto">{{ $message }}</p>
            @endif
        </div>
        @if ($resetUrl)
            <a href="{{ $resetUrl }}"
                class="mt-1 inline-flex items-center gap-1.5 px-4 py-2 bg-blue-50 dark:bg-blue-900/40 hover:bg-blue-100 dark:hover:bg-blue-900/60 text-blue-600 dark:text-blue-300 rounded-xl text-xs font-semibold transition">
                <i class="fa-solid fa-rotate-left text-[10px]"></i> {{ $resetLabel }}
            </a>
        @endif
    </div>
</div>