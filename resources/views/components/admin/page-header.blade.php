@props([
    'icon' => 'fa-circle',
    'title' => '',
    'description' => null,
    'count' => null,
    'countLabel' => 'data',
    'countIcon' => 'fa-layer-group',
])

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
    <div class="flex items-center gap-3 min-w-0">
        <div
            class="w-11 h-11 shrink-0 rounded-xl bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-900 flex items-center justify-center shadow-sm">
            <i class="fa-solid {{ $icon }}"></i>
        </div>
        <div class="min-w-0">
            <h1 class="text-lg font-bold text-slate-800 dark:text-white leading-tight truncate">{{ $title }}</h1>
            @if ($description)
                <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $description }}</p>
            @endif
        </div>
    </div>

    <div class="flex flex-wrap items-center gap-2 shrink-0">
        @if ($count !== null)
            <span
                class="inline-flex items-center gap-1.5 self-start sm:self-auto px-3 py-1.5 rounded-full bg-blue-50 dark:bg-blue-900/40 border border-blue-100 dark:border-blue-900 text-xs font-semibold text-blue-600 dark:text-blue-300">
                <i class="fa-solid {{ $countIcon }} text-[10px]"></i> Total {{ $count }} {{ $countLabel }}
            </span>
        @endif
        {{ $slot }}
    </div>
</div>