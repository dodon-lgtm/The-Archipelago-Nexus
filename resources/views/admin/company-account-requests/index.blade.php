@extends('layouts.admin')

@section('title', 'Permintaan Akun Perusahaan')
@section('breadcrumb', 'Permintaan Akun Perusahaan')

@section('content')
    <div class="bg-white rounded-2xl border border-slate-200 p-4 mb-4 shadow-sm">
        <form method="GET" action="{{ url()->current() }}" class="flex flex-wrap gap-3 items-end">
            <div class="w-48">
                <label class="text-xs font-semibold text-slate-600 mb-1 block">Filter Status</label>
                <select class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 outline-none" name="status">
                    <option value="menunggu" @selected($status==='menunggu')>Menunggu</option>
                    <option value="disetujui" @selected($status==='disetujui')>Disetujui</option>
                    <option value="ditolak" @selected($status==='ditolak')>Ditolak</option>
                </select>
            </div>
            <button class="px-5 py-2.5 bg-cyan-500 hover:bg-cyan-600 text-white rounded-xl text-sm font-semibold transition" type="submit">Terapkan</button>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="text-left px-5 py-3 font-bold text-slate-600 text-xs uppercase">Nama Perusahaan</th>
                        <th class="text-left px-5 py-3 font-bold text-slate-600 text-xs uppercase">Contact Person</th>
                        <th class="text-left px-5 py-3 font-bold text-slate-600 text-xs uppercase">Email</th>
                        <th class="text-left px-5 py-3 font-bold text-slate-600 text-xs uppercase">No. Telepon</th>
                        <th class="text-center px-5 py-3 font-bold text-slate-600 text-xs uppercase">Status</th>
                        <th class="text-center px-5 py-3 font-bold text-slate-600 text-xs uppercase">Tanggal</th>
                        <th class="text-right px-5 py-3 font-bold text-slate-600 text-xs uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($companyRequests as $r)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-5 py-4 font-semibold text-slate-800">{{ $r->company_name }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $r->contact_person }}</td>
                            <td class="px-5 py-4">{{ $r->company_email }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $r->company_phone }}</td>
                            <td class="px-5 py-4 text-center">
                                @if($r->request_status==='menunggu')
                                    <span class="text-xs px-2.5 py-1 rounded-full bg-amber-50 text-amber-600 font-semibold">Menunggu</span>
                                @elseif($r->request_status==='disetujui')
                                    <span class="text-xs px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 font-semibold">Disetujui</span>
                                @else
                                    <span class="text-xs px-2.5 py-1 rounded-full bg-red-50 text-red-600 font-semibold">Ditolak</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center text-xs text-slate-500">{{ $r->created_at?->format('Y-m-d') }}</td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2 flex-wrap">
                                    <a class="px-3 py-1.5 text-xs font-semibold bg-cyan-50 text-cyan-600 hover:bg-cyan-100 rounded-lg transition" href="{{ route('admin.company-account-requests.show', $r) }}">Lihat Detail</a>

                                    @if($r->request_status==='menunggu')
                                        <form method="POST" action="{{ route('admin.company-account-requests.approve', $r) }}" class="inline">
                                            @csrf
                                            <button class="px-3 py-1.5 text-xs font-semibold bg-emerald-50 text-emerald-600 hover:bg-emerald-100 rounded-lg transition" type="submit">Setujui</button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.company-account-requests.reject', $r) }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="note" value="Tolak oleh admin">
                                            <button class="px-3 py-1.5 text-xs font-semibold bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition" type="submit">Tolak</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-sm text-slate-400">Tidak ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $companyRequests->links() }}
    </div>
@endsection
