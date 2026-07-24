@extends('layouts.admin')

@section('title', 'Pengguna')
@section('breadcrumb', 'Pengguna')

@section('content')
    <div class="bg-white rounded-2xl border border-slate-200 p-4 mb-4 shadow-sm">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="text-xs font-semibold text-slate-600 mb-1 block">Cari Nama/Email</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 outline-none"
                       placeholder="Ketik nama atau email...">
            </div>
            <div class="w-40">
                <label class="text-xs font-semibold text-slate-600 mb-1 block">Filter Role</label>
                <select name="role" class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 outline-none">
                    <option value="">Semua Role</option>
                    <option value="admin" @selected(request('role') == 'admin')>Admin</option>
                    <option value="company" @selected(request('role') == 'company')>Company</option>
                    <option value="freelancer" @selected(request('role') == 'freelancer')>Freelancer</option>
                </select>
            </div>
            <button type="submit" class="px-5 py-2.5 bg-cyan-500 hover:bg-cyan-600 text-white rounded-xl text-sm font-semibold transition">
                <i class="fa-solid fa-search mr-1"></i> Cari
            </button>
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-sm font-semibold transition">
                Reset
            </a>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="text-left px-5 py-3 font-bold text-slate-600 text-xs uppercase tracking-wider">Nama</th>
                        <th class="text-left px-5 py-3 font-bold text-slate-600 text-xs uppercase tracking-wider">Email</th>
                        <th class="text-left px-5 py-3 font-bold text-slate-600 text-xs uppercase tracking-wider">Role</th>
                        <th class="text-left px-5 py-3 font-bold text-slate-600 text-xs uppercase tracking-wider">Bergabung</th>
                        <th class="text-right px-5 py-3 font-bold text-slate-600 text-xs uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center text-sm font-bold">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="font-semibold text-slate-800">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-slate-600">{{ $user->email }}</td>
                            <td class="px-5 py-4">
                                <span class="text-xs px-2.5 py-1 rounded-full font-semibold
                                    @if($user->role == 'admin') bg-red-50 text-red-600
                                    @elseif($user->role == 'company') bg-purple-50 text-purple-600
                                    @else bg-emerald-50 text-emerald-600 @endif">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-slate-500 text-xs">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.users.show', $user) }}"
                                       class="px-3 py-1.5 text-xs font-semibold bg-cyan-50 text-cyan-600 hover:bg-cyan-100 rounded-lg transition">
                                        Detail
                                    </a>

                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.update-role', $user) }}" class="inline">
                                            @csrf
                                            <select name="role" onchange="this.form.submit()"
                                                    class="text-xs rounded-lg border-slate-200 bg-slate-50 px-2 py-1.5 outline-none focus:border-cyan-400">
                                                <option value="admin" @selected($user->role == 'admin')>Admin</option>
                                                <option value="company" @selected($user->role == 'company')>Company</option>
                                                <option value="freelancer" @selected($user->role == 'freelancer')>Freelancer</option>
                                            </select>
                                        </form>

                                        @if($user->role !== 'admin')
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                                  onsubmit="return confirm('Hapus pengguna {{ $user->name }}?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="px-3 py-1.5 text-xs font-semibold bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <span class="text-xs text-slate-400 italic">Anda</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-sm text-slate-400">Tidak ada pengguna ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
@endsection
