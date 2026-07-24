@extends('layouts.admin')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
    {{-- STATISTICS CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Pengguna</p>
                    <p class="text-3xl font-extrabold text-slate-800 mt-1">{{ number_format($totalUsers) }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i class="fa-solid fa-users text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Freelancer</p>
                    <p class="text-3xl font-extrabold text-slate-800 mt-1">{{ number_format($totalFreelancers) }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i class="fa-solid fa-user-tie text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Company</p>
                    <p class="text-3xl font-extrabold text-slate-800 mt-1">{{ number_format($totalCompanies) }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                    <i class="fa-solid fa-building text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Proyek</p>
                    <p class="text-3xl font-extrabold text-slate-800 mt-1">{{ number_format($totalProjects) }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center">
                    <i class="fa-solid fa-folder-open text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Penawaran</p>
                    <p class="text-3xl font-extrabold text-slate-800 mt-1">{{ number_format($totalPenawarans) }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i class="fa-solid fa-file-invoice text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Laporan</p>
                    <p class="text-3xl font-extrabold text-slate-800 mt-1">{{ number_format($totalReports) }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center">
                    <i class="fa-solid fa-flag text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- RECENT DATA SECTION --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent Company Requests --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-building text-amber-500"></i>
                    <h2 class="font-bold text-slate-800">Permintaan Akun Perusahaan Terbaru</h2>
                </div>
                @if($pendingCompanyRequests > 0)
                    <span class="text-xs px-2 py-1 rounded-full bg-amber-50 text-amber-600 font-semibold">{{ $pendingCompanyRequests }} menunggu</span>
                @endif
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($recentRequests as $req)
                    <div class="px-5 py-3 hover:bg-slate-50 transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-slate-800">{{ $req->company_name }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $req->contact_person }} • {{ $req->company_email }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs px-2 py-1 rounded-full bg-amber-50 text-amber-600 font-semibold">Menunggu</span>
                                <a href="{{ route('admin.company-account-requests.show', $req) }}" class="text-xs text-cyan-600 hover:text-cyan-700 font-semibold">Detail →</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center text-sm text-slate-400">Tidak ada permintaan menunggu.</div>
                @endforelse
                <div class="px-5 py-3 bg-slate-50/50">
                    <a href="{{ route('admin.company-account-requests.index') }}" class="text-xs text-cyan-600 hover:text-cyan-700 font-semibold">Lihat Semua Permintaan →</a>
                </div>
            </div>
        </div>

        {{-- Recent Projects --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-folder-open text-cyan-500"></i>
                    <h2 class="font-bold text-slate-800">Proyek Terbaru</h2>
                </div>
                <a href="{{ route('admin.projects.index') }}" class="text-xs text-cyan-600 hover:text-cyan-700 font-semibold">Lihat Semua →</a>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($recentProjects as $project)
                    <div class="px-5 py-3 hover:bg-slate-50 transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-slate-800">{{ $project->project_name }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $project->owner->name ?? '—' }} • {{ $project->created_at->format('d M Y') }}</p>
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
                <div class="px-5 py-3 bg-slate-50/50">
                    <a href="{{ route('admin.projects.index') }}" class="text-xs text-cyan-600 hover:text-cyan-700 font-semibold">Lihat Semua Proyek →</a>
                </div>
            </div>
        </div>

        {{-- Recent Penawarans --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-file-invoice text-amber-500"></i>
                    <h2 class="font-bold text-slate-800">Penawaran Terbaru</h2>
                </div>
                <a href="{{ route('admin.penawarans.index') }}" class="text-xs text-cyan-600 hover:text-cyan-700 font-semibold">Lihat Semua →</a>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($recentPenawarans as $penawaran)
                    <div class="px-5 py-3 hover:bg-slate-50 transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-slate-800">{{ $penawaran->freelancer->name ?? '—' }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $penawaran->project->project_name ?? '—' }} • Rp {{ number_format($penawaran->harga_penawaran) }}</p>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full font-semibold
                                @if($penawaran->status == 'Diterima') bg-emerald-50 text-emerald-600
                                @elseif($penawaran->status == 'Ditolak') bg-red-50 text-red-600
                                @else bg-amber-50 text-amber-600 @endif">
                                {{ $penawaran->status }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center text-sm text-slate-400">Belum ada penawaran.</div>
                @endforelse
                <div class="px-5 py-3 bg-slate-50/50">
                    <a href="{{ route('admin.penawarans.index') }}" class="text-xs text-cyan-600 hover:text-cyan-700 font-semibold">Lihat Semua Penawaran →</a>
                </div>
            </div>
        </div>

        {{-- Recent Reports --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-flag text-red-500"></i>
                    <h2 class="font-bold text-slate-800">Laporan Terbaru</h2>
                </div>
                <a href="{{ route('admin.reports.index') }}" class="text-xs text-cyan-600 hover:text-cyan-700 font-semibold">Lihat Semua →</a>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($recentReports as $report)
                    <div class="px-5 py-3 hover:bg-slate-50 transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-slate-800">{{ $report->subject }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">oleh {{ $report->reporter->name ?? '—' }} • {{ $report->created_at->format('d M Y') }}</p>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full font-semibold
                                @if($report->status == 'menunggu') bg-amber-50 text-amber-600
                                @elseif($report->status == 'diproses') bg-blue-50 text-blue-600
                                @elseif($report->status == 'selesai') bg-emerald-50 text-emerald-600
                                @else bg-red-50 text-red-600 @endif">
                                {{ ucfirst($report->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center text-sm text-slate-400">Belum ada laporan.</div>
                @endforelse
                <div class="px-5 py-3 bg-slate-50/50">
                    <a href="{{ route('admin.reports.index') }}" class="text-xs text-cyan-600 hover:text-cyan-700 font-semibold">Lihat Semua Laporan →</a>
                </div>
            </div>
        </div>
    </div>
@endsection
