@extends('layouts.admin')

@section('title', 'Detail Pengguna')
@section('breadcrumb', 'Detail Pengguna')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.users.index') }}" class="text-sm text-cyan-600 hover:text-cyan-700 font-semibold inline-flex items-center gap-1">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Pengguna
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Profile Card --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <div class="text-center mb-4">
                <div class="w-20 h-20 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center text-3xl font-bold mx-auto mb-3">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h2 class="text-lg font-bold text-slate-800">{{ $user->name }}</h2>
                <p class="text-sm text-slate-500">{{ $user->email }}</p>
                <span class="inline-block mt-2 text-xs px-3 py-1.5 rounded-full font-semibold
                    @if($user->role == 'admin') bg-red-50 text-red-600
                    @elseif($user->role == 'company') bg-purple-50 text-purple-600
                    @else bg-emerald-50 text-emerald-600 @endif">
                    {{ ucfirst($user->role) }}
                </span>
            </div>

            <div class="border-t border-slate-100 pt-4 mt-4 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-500">Bergabung</span>
                    <span class="font-semibold">{{ $user->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Terakhir Update</span>
                    <span class="font-semibold">{{ $user->updated_at->format('d M Y') }}</span>
                </div>
            </div>

            @if($user->id !== auth()->id())
                <div class="border-t border-slate-100 pt-4 mt-4">
                    <h3 class="text-sm font-bold text-slate-700 mb-2">Ubah Role</h3>
                    <form method="POST" action="{{ route('admin.users.update-role', $user) }}">
                        @csrf
                        <select name="role" class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 outline-none mb-2">
                            <option value="admin" @selected($user->role == 'admin')>Admin</option>
                            <option value="company" @selected($user->role == 'company')>Company</option>
                            <option value="freelancer" @selected($user->role == 'freelancer')>Freelancer</option>
                        </select>
                        <button type="submit" class="w-full px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-white rounded-xl text-sm font-semibold transition">
                            Simpan Perubahan
                        </button>
                    </form>
                </div>
            @endif
        </div>

        {{-- Stats --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                    <div class="text-2xl font-extrabold text-slate-800">{{ $user->penawarans_count }}</div>
                    <p class="text-xs text-slate-500 font-semibold mt-1">Total Penawaran</p>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                    <div class="text-2xl font-extrabold text-slate-800">{{ $projectsCount }}</div>
                    <p class="text-xs text-slate-500 font-semibold mt-1">Total Proyek (Company)</p>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                    <div class="text-2xl font-extrabold text-slate-800">{{ $acceptedOffers }}</div>
                    <p class="text-xs text-slate-500 font-semibold mt-1">Penawaran Diterima</p>
                </div>
            </div>

            {{-- Saved Projects --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100">
                    <h2 class="font-bold text-slate-800">Proyek Tersimpan</h2>
                </div>
                <div class="divide-y divide-slate-50">
                    @forelse($user->savedProjects()->with('project')->latest()->take(5)->get() as $saved)
                        <div class="px-5 py-3 text-sm">
                            <span class="font-semibold text-slate-700">{{ $saved->project->project_name ?? '—' }}</span>
                            <span class="text-slate-400 text-xs ml-2">{{ $saved->created_at->format('d M Y') }}</span>
                        </div>
                    @empty
                        <div class="px-5 py-6 text-center text-sm text-slate-400">Tidak ada proyek tersimpan.</div>
                    @endforelse
                </div>
            </div>

            @if($user->id !== auth()->id() && $user->role !== 'admin')
                <div class="bg-white rounded-2xl border border-red-200 shadow-sm p-5">
                    <h3 class="font-bold text-red-600 text-sm mb-1">Zona Berbahaya</h3>
                    <p class="text-xs text-slate-500 mb-3">Hapus akun pengguna ini secara permanen.</p>
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                          onsubmit="return confirm('Yakin ingin menghapus pengguna {{ $user->name }}? Semua data terkait akan ikut terhapus.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-xl text-sm font-semibold transition">
                            <i class="fa-solid fa-trash-can mr-1"></i> Hapus Pengguna
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
