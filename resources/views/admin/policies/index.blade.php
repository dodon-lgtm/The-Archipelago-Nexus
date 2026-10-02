@php
    /** @var \App\Models\Policy[] $policies */
@endphp
@extends('layouts.admin')

@section('title', 'Kebijakan & Privasi')
@section('breadcrumb', 'Kebijakan & Privasi')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <h2 class="text-xl font-extrabold text-slate-800 dark:text-white">
            <i class="fa-solid fa-shield-halved text-blue-600 dark:text-blue-400 mr-2"></i>
            Kebijakan &amp; Privasi
        </h2>
        @unless ($policies->isEmpty())
            <a href="{{ route('admin.policies.edit', $policies->firstWhere('key', \App\Models\Policy::KEY_PRIVACY) ?? $policies->first()) }}"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold transition flex items-center gap-1.5">
                <i class="fa-solid fa-pen"></i> Kelola Kebijakan
            </a>
        @endunless
    </div>

    @if ($policies->isEmpty())
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-blue-100 dark:border-slate-800 shadow-sm p-8 text-center text-slate-400">
            Belum ada dokumen kebijakan tersedia. Pastikan migrasi &amp; seeder sudah dijalankan:
            <code class="block mt-2 px-2 py-1 text-xs bg-slate-100 dark:bg-slate-800 rounded">php artisan migrate:fresh --seed</code>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach ($policies as $policy)
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-blue-100 dark:border-slate-800 shadow-sm p-5 flex flex-col">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-slate-800 flex items-center justify-center shrink-0">
                            @if ($policy->key === \App\Models\Policy::KEY_PRIVACY)
                                <i class="fa-solid fa-shield-halved text-blue-600 dark:text-blue-400"></i>
                            @elseif ($policy->key === \App\Models\Policy::KEY_USAGE)
                                <i class="fa-solid fa-list-check text-emerald-600 dark:text-emerald-400"></i>
                            @else
                                <i class="fa-solid fa-file-text text-slate-500"></i>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-black text-slate-800 dark:text-white truncate">{{ $policy->title }}</h3>
                            <span class="text-[10px] font-semibold uppercase text-slate-400">key: {{ $policy->key }}</span>
                        </div>
                    </div>

                    <div class="text-xs text-slate-500 dark:text-slate-400 mb-3">
                        @php($dot = $policy->is_active ? 'bg-emerald-500' : 'bg-slate-400')
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold
                            {{ $policy->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $dot }}"></span>
                            {{ $policy->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>

                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-4 flex-1 line-clamp-3">{{ $policy->excerpt(160) }}</p>

                    <p class="text-[10px] text-slate-400 mb-4">
                        <i class="fa-solid fa-clock-rotate-left mr-1"></i>
                        Terakhir diperbarui: {{ $policy->updated_at ? $policy->updated_at->translatedFormat('d M Y H:i') : '-' }}
                    </p>

                    <a href="{{ route('admin.policies.edit', $policy) }}"
                        class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold transition flex items-center justify-center gap-1">
                        <i class="fa-solid fa-pen"></i> Edit
                    </a>
                </div>
            @endforeach
        </div>
    @endif
@endsection
