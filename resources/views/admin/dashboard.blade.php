@extends('admin.layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="flash-message mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium">
            <i class="fa-regular fa-circle-check mr-2"></i> {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="flash-message mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium">
            <i class="fa-regular fa-circle-xmark mr-2"></i> {{ session('error') }}
        </div>
    @endif

    {{-- HEADER --}}
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold text-slate-800">Dashboard Admin</h1>
        <p class="text-sm text-slate-500 mt-1">Ringkasan sistem The Archipelago Nexus</p>
    </div>

    {{-- STATISTICS CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Total Users --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i class="fa-solid fa-users text-xl"></i>
                </div>
                <span class="text-3xl font-extrabold text-slate-800">{{ number_format($totalUsers) }}</span>
            </div>
            <h3 class="text-sm font-semibold text-slate-600">Total Pengguna</h3>
        </div>

        {{-- Total Freelancers --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i class="fa-solid fa-user-tie text-xl"></i>
                </div>
                <span class="text-3xl font-extrabold text-slate-800">{{ number_format($totalFreelancers) }}</span>
            </div>
            <h3 class="text-sm font-semibold text-slate-600">Total Freelancer</h3>
        </div>

        {{-- Total Companies --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                    <i class="fa-solid fa-building text-xl"></i>
                </div>
                <span class="text-3xl font-extrabold text-slate-800">{{ number_format($totalCompanies) }}</span>
            </div>
            <h3 class="text-sm font-semibold text-slate-600">Total Company</h3>
        </div>

        {{-- Total Projects --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center">
                    <i class="fa-solid fa-folder-open text-xl"></i>
                </div>
                <span class="text-3xl font-extrabold text-slate-800">{{ number_format($totalProjects) }}</span>
            </div>
            <h3 class="text-sm font-semibold text-slate-600">Total Proyek</h3>
        </div>

        {{-- Total Penawarans --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i class="fa-solid fa-file-invoice text-xl"></i>
                </div>
                <span class="text-3xl font-extrabold text-slate-800">{{ number_format($totalPenawarans) }}</span>
            </div>
            <h3 class="text-sm font-semibold text-slate-600">Total Penawaran</h3>
        </div>

        {{-- Completed Projects --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center">
                    <i class="fa-solid fa-circle-check text-xl"></i>
                </div>
                <span class="text-3xl font-extrabold text-slate-800">{{ number_format($totalCompletedProjects) }}</span>
            </div>
            <h3 class="text-sm font-semibold text-slate-600">Proyek Selesai</h3>
        </div>

        {{-- Total Reports --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center">
                    <i class="fa-solid fa-flag text-xl"></i>
                </div>
                <span class="text-3xl font-extrabold text-slate-800">{{ number_format($totalReports) }}</span>
            </div>
            <h3 class="text-sm font-semibold text-slate-600">Total Laporan</h3>
        </div>

        {{-- Pending Company Requests --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                    <i class="fa-solid fa-clock text-xl"></i>
                </div>
                <span class="text-3xl font-extrabold text-slate-800">{{ number_format($pendingCompanyRequests) }}</span>
            </div>
            <h3 class="text-sm font-semibold text-slate-600">Permintaan Akun Menunggu</h3>
        </div>
    </div>

    {{-- RECENT DATA SECTION --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent Projects --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="font-bold text-slate-800">Proyek Terbaru</h2>
                <a href="{{ route('admin.projects.index') }}" class="text-xs text-cyan-600 hover:text-cyan-700 font-semibold">Lihat Semua →</a>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($recentProjects as $project)
                    <div class="px-5 py-3 hover:bg-slate-50 transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-slate-800">{{ $project->project_name }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    {{ $project->owner->name ?? '—' }} • {{ $project->created_at->format('d M Y') }}
                                </p>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full font-semibold
                                @if($project->status == 'Open') bg-emerald-50 text-emerald-600
                                @else bg-slate-100 text-slate-600 @endif">
                                {{ $project->status }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center text-sm text-slate-400">Belum ada proyek.</div>
                @endforelse
            </div>
        </div>

        {{-- Recent Users --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="font-bold text-slate-800">Pengguna Terbaru</h2>
                <a href="{{ route('admin.users.index') }}" class="text-xs text-cyan-600 hover:text-cyan-700 font-semibold">Lihat Semua →</a>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($recentUsers as $user)
                    <div class="px-5 py-3 hover:bg-slate-50 transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center text-xs font-bold">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">{{ $user->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $user->email }}</p>
                                </div>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full font-semibold
                                @if($user->role == 'admin') bg-red-50 text-red-600
                                @elseif($user->role == 'company') bg-purple-50 text-purple-600
                                @else bg-emerald-50 text-emerald-600 @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center text-sm text-slate-400">Belum ada pengguna.</div>
                @endforelse
            </div>
        </div>

        {{-- Pending Company Requests --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden lg:col-span-2">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="font-bold text-slate-800">Permintaan Akun Perusahaan Menunggu</h2>
                <a href="{{ route('admin.company-account-requests.index') }}" class="text-xs text-cyan-600 hover:text-cyan-700 font-semibold">Lihat Semua →</a>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($recentRequests as $request)
                    <div class="px-5 py-3 hover:bg-slate-50 transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-slate-800">{{ $request->company_name }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    {{ $request->contact_person }} • {{ $request->company_email }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs px-2 py-1 rounded-full bg-amber-50 text-amber-600 font-semibold">Menunggu</span>
                                <a href="{{ route('admin.company-account-requests.show', $request) }}" class="text-xs text-cyan-600 hover:text-cyan-700 font-semibold">Detail →</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center text-sm text-slate-400">Tidak ada permintaan menunggu.</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

