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
            Kebijakan & Privasi
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
            Belum ada dokumen kebijakan tersedia. Pastikan migrasi & seeder sudah dijalankan:
            <code class="block mt-2 px-2 py-1 text-xs bg-slate-100 dark:bg-slate-800 rounded">php artisan migrate:fresh --seed</code>
        </div>
    @else
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-blue-100 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-blue-100 dark:border-slate-700">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Policy</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Version</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Updated</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-blue-100 dark:divide-slate-700">
                        @foreach ($policies as $policy)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0
                                            @if ($policy->key === \App\Models\Policy::KEY_PRIVACY)
                                                bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400
                                            @elseif ($policy->key === \App\Models\Policy::KEY_USAGE)
                                                bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400
                                            @else
                                                bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400
                                            @endif">
                                            @if ($policy->key === \App\Models\Policy::KEY_PRIVACY)
                                                <i class="fa-solid fa-shield-halved"></i>
                                            @elseif ($policy->key === \App\Models\Policy::KEY_USAGE)
                                                <i class="fa-solid fa-list-check"></i>
                                            @else
                                                <i class="fa-solid fa-file-contract"></i>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <h3 class="font-semibold text-slate-800 dark:text-white truncate">{{ $policy->title }}</h3>
                                            <span class="text-[10px] font-medium text-slate-400 uppercase">key: {{ $policy->key }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300">
                                        v{{ $policy->version }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    @php($dot = $policy->is_active ? 'bg-emerald-500' : 'bg-slate-400')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold
                                        {{ $policy->is_active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $dot }}"></span>
                                        {{ $policy->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-sm text-slate-500 dark:text-slate-400">
                                    {{ $policy->updated_at ? $policy->updated_at->translatedFormat('d M Y H:i') : '-' }}
                                </td>
                                <td class="px-5 py-4">
                                    <a href="{{ route('admin.policies.edit', $policy) }}"
                                        class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold transition flex items-center justify-center gap-1">
                                        <i class="fa-solid fa-pen"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
