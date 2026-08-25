@extends('layouts.admin')

@section('title', 'Pengguna')
@section('breadcrumb', 'Pengguna')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center shadow-sm">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <h1 class="text-lg font-bold text-slate-800 leading-tight">Daftar Pengguna</h1>
                <p class="text-xs text-slate-500">Kelola pengguna dan role platform</p>
            </div>
        </div>
        <span class="inline-flex items-center gap-1.5 self-start sm:self-auto px-3 py-1.5 rounded-full bg-blue-50 border border-blue-100 text-xs font-semibold text-blue-600">
            <i class="fa-solid fa-user-group text-[10px]"></i> Total {{ $users->total() }} pengguna
        </span>
    </div>

    <div class="bg-white rounded-2xl border border-blue-100 p-4 mb-4 shadow-sm">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="text-[11px] font-bold uppercase tracking-wide text-slate-500 mb-1.5 block">
                    <i class="fa-solid fa-magnifying-glass mr-1 text-blue-400"></i> Cari Nama/Email
                </label>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full rounded-xl border-blue-100 bg-[#f6f9ff] px-4 py-2.5 text-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:bg-white"
                       placeholder="Ketik nama atau email...">
            </div>
            <div class="w-full sm:w-44">
                <label class="text-[11px] font-bold uppercase tracking-wide text-slate-500 mb-1.5 block">
                    <i class="fa-solid fa-user-tag mr-1 text-blue-400"></i> Filter Role
                </label>
                <select name="role" class="w-full rounded-xl border-blue-100 bg-[#f6f9ff] px-4 py-2.5 text-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:bg-white cursor-pointer">
                    <option value="">Semua Role</option>
                    <option value="admin" @selected(request('role') == 'admin')>Admin</option>
                    <option value="company" @selected(request('role') == 'company')>Company</option>
                    <option value="freelancer" @selected(request('role') == 'freelancer')>Freelancer</option>
                </select>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 px-5 py-2.5 bg-blue-500 hover:bg-blue-600 active:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow-sm hover:shadow transition">
                    <i class="fa-solid fa-search"></i> Cari
                </button>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-sm font-semibold transition">
                    <i class="fa-solid fa-rotate-left text-xs"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[760px]">
                <thead class="bg-[#f6f9ff] border-b border-blue-100">
                    <tr>
                        <th class="text-left px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Nama</th>
                        <th class="text-left px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Email</th>
                        <th class="text-left px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Role</th>
                        <th class="text-left px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Bergabung</th>
                        <th class="text-right px-5 py-3.5 font-bold text-slate-500 text-[11px] uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-[#f6f9ff]/70 transition-colors">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-100 to-blue-50 ring-1 ring-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="font-semibold text-slate-800">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-slate-600">{{ $user->email }}</td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1.5 text-xs px-2.5 py-1 rounded-full border font-semibold
                                    @if($user->role == 'admin') bg-red-50 text-red-600 border-red-100
                                    @elseif($user->role == 'company') bg-purple-50 text-purple-600 border-purple-100
                                    @else bg-emerald-50 text-emerald-600 border-emerald-100 @endif">
                                    <span class="w-1.5 h-1.5 rounded-full
                                        @if($user->role == 'admin') bg-red-500
                                        @elseif($user->role == 'company') bg-purple-500
                                        @else bg-emerald-500 @endif"></span>
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-slate-500 text-xs">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex flex-wrap items-center justify-end gap-2">
                                    <a href="{{ route('admin.users.show', $user) }}"
                                       class="px-3 py-1.5 text-xs font-semibold bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition">
                                        Detail
                                    </a>

                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.update-role', $user) }}" class="inline">
                                            @csrf
                                            <select name="role" onchange="this.form.submit()"
                                                    class="text-xs rounded-lg border-blue-100 bg-[#f6f9ff] px-2 py-1.5 outline-none transition cursor-pointer focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                                                <option value="company" @selected($user->role == 'company')>Company</option>
                                                <option value="freelancer" @selected($user->role == 'freelancer')>Freelancer</option>
                                            </select>
                                        </form>

                                        @if($user->role !== 'admin')
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                                  onsubmit="return adminConfirm('Hapus pengguna {{ $user->name }}?', this)" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="px-3 py-1.5 text-xs font-semibold bg-red-50 text-red-600 hover:bg-red-100 active:bg-red-200 rounded-lg transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <span class="text-xs px-2.5 py-1 rounded-full bg-slate-100 border border-slate-200 text-slate-400 italic">Anda</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-14 h-14 rounded-2xl bg-blue-50 border border-blue-100 text-blue-400 flex items-center justify-center text-xl">
                                        <i class="fa-solid fa-user-slash"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-600">Tidak ada pengguna ditemukan</p>
                                        <p class="text-xs text-slate-400 mt-1">Coba ubah kata kunci pencarian atau filter role.</p>
                                    </div>
                                    <a href="{{ route('admin.users.index') }}" class="mt-1 inline-flex items-center gap-1.5 px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-xl text-xs font-semibold transition">
                                        <i class="fa-solid fa-rotate-left text-[10px]"></i> Reset Filter
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5 flex justify-center">
        <div class="[&_nav]:!m-0 [&_nav]:!p-0">
            {{ $users->links() }}
        </div>
    </div>
@endsection
