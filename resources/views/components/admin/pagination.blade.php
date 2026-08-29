@props(['paginator'])

@php
    $isPaginator = ($paginator ?? null) instanceof \Illuminate\Pagination\AbstractPaginator;
    $total = $isPaginator ? $paginator->total() : 0;
@endphp

@if ($isPaginator && $total > 0)
    <div class="mt-5 flex flex-col sm:flex-row items-center justify-between gap-3 px-1">
        <p class="text-xs text-slate-500 dark:text-slate-400" role="status">
            Menampilkan
            <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $paginator->firstItem() ?? 0 }}</span>–
            <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $paginator->lastItem() ?? 0 }}</span>
            dari
            <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $total }}</span> data
        </p>
        <div class="[&_nav]:!m-0 [&_nav]:!p-0">{{ $paginator->links() }}</div>
    </div>
@endif