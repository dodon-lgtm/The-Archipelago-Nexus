@extends('layouts.admin')

@section('title', 'Pembayaran')
@section('breadcrumb', 'Pembayaran')

@section('content')

<div class="bg-white border border-blue-100 rounded-2xl shadow-sm relative print:border-none print:shadow-none">


{{-- ============================================================= --}}
{{-- HEADER DAFTAR PEMBAYARAN --}}
{{-- ============================================================= --}}
<div class="px-6 py-5 border-b border-blue-50 flex items-center justify-between print:border-b-2 print:border-slate-800">

    {{-- BAGIAN KIRI --}}
    <div class="flex items-center gap-3">

        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center print:hidden">
            <i class="fa-solid fa-credit-card"></i>
        </div>

        <div>
            <h2 class="font-bold text-slate-800 text-lg">
                Daftar Pembayaran
            </h2>

            <p class="text-xs text-slate-500">
                Kelola verifikasi pembayaran proyek
            </p>
        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- BAGIAN KANAN HEADER --}}
    {{-- ========================================================= --}}
    <div class="flex items-center gap-3 print:hidden">

        {{-- ===================================================== --}}
        {{-- FILTER STATUS --}}
        {{-- ===================================================== --}}
        <div class="relative">

            <form
                method="GET"
                action="{{ route('admin.payments.index') }}"
            >

                <select
                    name="status"
                    onchange="this.form.submit()"
                    class="appearance-none bg-white border border-slate-200 text-slate-700 text-xs font-semibold rounded-xl px-4 py-2 pr-9 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 cursor-pointer"
                >

                    <option value="">
                        Semua Status
                    </option>

                    <option
                        value="paid"
                        {{ request('status') === 'paid' ? 'selected' : '' }}
                    >
                        Dibayar
                    </option>

                    {{-- <option
                        value="waiting_verification"
                        {{ request('status') === 'waiting_verification' ? 'selected' : '' }}
                    >
                        Pending
                    </option> --}}

                    <option
                        value="rejected"
                        {{ request('status') === 'rejected' ? 'selected' : '' }}
                    >
                        Ditolak
                    </option>

                </select>

                <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[9px] text-slate-400 pointer-events-none"></i>

            </form>

        </div>


        {{-- ===================================================== --}}
        {{-- DROPDOWN CETAK LAPORAN --}}
        {{-- ===================================================== --}}
        <details class="relative inline-block text-left">

            {{-- BUTTON DROPDOWN --}}
            <summary
                class="list-none inline-flex items-center gap-2 px-4 py-2
                       bg-blue-600 hover:bg-blue-700
                       text-white font-semibold text-xs rounded-xl
                       shadow-sm transition-all cursor-pointer
                       focus:outline-none select-none"
            >

                <i class="fa-solid fa-print text-sm"></i>

                <span>
                    Cetak Laporan
                </span>

                <i
                    class="fa-solid fa-chevron-down text-[10px]
                           transition-transform duration-200
                           details-chevron"
                ></i>

            </summary>


            {{-- ================================================= --}}
            {{-- MENU DROPDOWN --}}
            {{-- ================================================= --}}
            <div
                class="absolute right-0 top-full mt-2
                       w-52
                       bg-white
                       border border-slate-200
                       rounded-xl
                       shadow-xl
                       py-2
                       z-[9999]
                       text-xs
                       font-medium
                       text-slate-700"
            >

                {{-- CETAK SEMUA --}}
                <a
                    href="{{ route('admin.payments.pdf.all') }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="flex items-center gap-2 px-4 py-2.5
                           hover:bg-slate-50
                           transition
                           text-slate-700"
                >

                    <span class="w-2 h-2 rounded-full bg-slate-400"></span>

                    <span>
                        Cetak Semua
                    </span>

                </a>


                {{-- STATUS DIBAYAR --}}
                <a
                    href="{{ route('admin.payments.pdf.all', ['status' => 'paid']) }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="flex items-center gap-2 px-4 py-2.5
                           hover:bg-emerald-50
                           hover:text-emerald-600
                           transition
                           text-slate-700"
                >

                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>

                    <span>
                        Status Dibayar / Lunas
                    </span>

                </a>


                {{-- STATUS PENDING --}}
                <a
                    href="{{ route('admin.payments.pdf.all', ['status' => 'pending']) }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="flex items-center gap-2 px-4 py-2.5
                           hover:bg-amber-50
                           hover:text-amber-600
                           transition
                           text-slate-700"
                >

                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>

                    <span>
                        Status Pending
                    </span>

                </a>


                {{-- STATUS DITOLAK --}}
                <a
                    href="{{ route('admin.payments.pdf.all', ['status' => 'rejected']) }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="flex items-center gap-2 px-4 py-2.5
                           hover:bg-red-50
                           hover:text-red-600
                           transition
                           text-slate-700"
                >

                    <span class="w-2 h-2 rounded-full bg-red-500"></span>

                    <span>
                        Status Ditolak
                    </span>

                </a>

            </div>

        </details>


        {{-- ===================================================== --}}
        {{-- TOTAL --}}
        {{-- ===================================================== --}}
        <span
            class="text-xs px-3 py-2 rounded-full
                   bg-blue-50 text-slate-600 font-semibold"
        >
            Total: {{ $payments->total() }}
        </span>

    </div>

</div>


{{-- ============================================================= --}}
{{-- ALERT SUCCESS --}}
{{-- ============================================================= --}}
@if(session('success'))

    <div
        class="mx-6 mt-4 flex items-center gap-3
               px-4 py-3
               bg-emerald-50
               border border-emerald-200
               text-emerald-700
               rounded-xl
               text-sm
               font-medium
               print:hidden"
    >

        <i class="fa-solid fa-check-circle"></i>

        {{ session('success') }}

    </div>

@endif


{{-- ============================================================= --}}
{{-- ALERT ERROR --}}
{{-- ============================================================= --}}
@if(session('error'))

    <div
        class="mx-6 mt-4 flex items-center gap-3
               px-4 py-3
               bg-red-50
               border border-red-200
               text-red-700
               rounded-xl
               text-sm
               font-medium
               print:hidden"
    >

        <i class="fa-solid fa-xmark-circle"></i>

        {{ session('error') }}

    </div>

@endif


{{-- ============================================================= --}}
{{-- TABEL PEMBAYARAN --}}
{{-- ============================================================= --}}
<div class="p-6">

    @if($payments->count() > 0)

        <div class="overflow-x-auto rounded-b-2xl">

            <table class="w-full text-sm print:text-xs">

                {{-- HEADER TABLE --}}
                <thead>

                    <tr
                        class="text-left
                               text-xs
                               font-semibold
                               text-slate-500
                               uppercase
                               tracking-wider
                               border-b
                               border-blue-50
                               print:border-slate-300"
                    >

                        <th class="pb-3 pr-4">
                            Invoice
                        </th>

                        <th class="pb-3 pr-4">
                            Perusahaan
                        </th>

                        <th class="pb-3 pr-4">
                            Freelancer
                        </th>

                        <th class="pb-3 pr-4">
                            Project
                        </th>

                        <th class="pb-3 pr-4">
                            Nominal
                        </th>

                        <th class="pb-3 pr-4">
                            Status
                        </th>

                        <th class="pb-3 pr-4">
                            Tanggal
                        </th>

                        <th class="pb-3 print:hidden">
                            Aksi
                        </th>

                    </tr>

                </thead>


                {{-- BODY TABLE --}}
                <tbody>

                    @foreach($payments as $payment)

                        <tr
                            class="border-b
                                   border-slate-50
                                   hover:bg-[#f6f9ff]/50
                                   transition
                                   print:border-slate-200"
                        >

                            {{-- INVOICE --}}
                            <td class="py-3 pr-4">

                                <span class="font-bold text-xs text-slate-700">
                                    {{ $payment->invoice_number }}
                                </span>

                            </td>


                            {{-- PERUSAHAAN --}}
                            <td class="py-3 pr-4">

                                <span class="text-xs text-slate-600">
                                    {{ $payment->company->name ?? '-' }}
                                </span>

                            </td>


                            {{-- FREELANCER --}}
                            <td class="py-3 pr-4">

                                <span class="text-xs text-slate-600">
                                    {{ $payment->freelancer->name ?? '-' }}
                                </span>

                            </td>


                            {{-- PROJECT --}}
                            <td class="py-3 pr-4">

                                <span class="text-xs text-slate-600">
                                                                        {{ $payment->workspace?->project?->project_name ?? ($payment->isQuotaPayment() ? 'Kuota Proyek' : '-') }}
                                </span>

                            </td>


                            {{-- NOMINAL --}}
                            <td class="py-3 pr-4">

                                <span class="text-xs font-semibold text-slate-700">
                                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                </span>

                            </td>


                            {{-- STATUS --}}
                            <td class="py-3 pr-4">

                                <span
                                    class="text-[10px]
                                           font-bold
                                           px-2.5
                                           py-1
                                           rounded-full
                                           border
                                           {{ $payment->status_color }}"
                                >
                                    {{ $payment->status_label }}
                                </span>

                            </td>


                            {{-- TANGGAL --}}
                            <td class="py-3 pr-4">

                                <span
                                    class="text-xs
                                           text-slate-400
                                           print:text-slate-700"
                                >
                                    {{ $payment->created_at->format('d M Y') }}
                                </span>

                            </td>


                            {{-- AKSI --}}
                            <td
                                class="px-4 py-3
                                       whitespace-nowrap
                                       text-sm
                                       font-medium"
                            >

                                <div class="flex items-center gap-2">

                                    {{-- LIHAT --}}
                                    <a
                                        href="{{ route('admin.payments.show', $payment->id) }}"
                                        class="inline-flex
                                               items-center
                                               gap-1.5
                                               px-3
                                               py-1.5
                                               bg-gray-100
                                               text-gray-700
                                               rounded-lg
                                               hover:bg-gray-200
                                               transition
                                               text-xs
                                               font-semibold"
                                    >
                                        👁 Lihat
                                    </a>


                                    {{-- CETAK STRUK --}}
                                    @if(in_array(strtolower($payment->status), ['paid', 'dibayar', 'selesai']))

                                        <a
                                            href="{{ route('admin.payments.pdf.single', $payment->id) }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="inline-flex
                                                   items-center
                                                   gap-1.5
                                                   px-3
                                                   py-1.5
                                                   bg-blue-50
                                                   text-blue-600
                                                   rounded-lg
                                                   hover:bg-blue-600
                                                   hover:text-white
                                                   transition
                                                   text-xs
                                                   font-semibold"
                                        >
                                            🖨 Cetak
                                        </a>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}
        @if(method_exists($payments, 'links'))

            <div class="mt-6 print:hidden">
                {{ $payments->links() }}
            </div>

        @endif


    @else

        {{-- EMPTY STATE --}}
        <div class="py-16 text-center">

            <div
                class="w-16 h-16
                       mx-auto
                       mb-4
                       bg-blue-50
                       rounded-2xl
                       flex
                       items-center
                       justify-center"
            >

                <i
                    class="fa-solid fa-credit-card
                           text-2xl
                           text-slate-400"
                ></i>

            </div>

            <h3 class="text-sm font-bold text-slate-600">
                Belum Ada Pembayaran
            </h3>

            <p class="text-xs text-slate-400 mt-1">
                Belum ada data pembayaran yang masuk.
            </p>

        </div>

    @endif

</div>


</div>

{{-- ============================================================= --}}
{{-- CSS STYLE --}}
{{-- ============================================================= --}}

<style>

    /*
     * Hilangkan marker bawaan <summary>
     */
    summary {
        list-style: none;
    }

    summary::-webkit-details-marker {
        display: none;
    }


    /*
     * Chevron berputar ketika dropdown terbuka.
     */
    details[open] .details-chevron {
        transform: rotate(180deg);
    }


    /*
     * Dropdown tetap relative terhadap tombol.
     */
    details {
        position: relative;
    }


    /*
     * Print style
     */
    @media print {

        aside,
        header,
        nav,
        .print\:hidden {
            display: none !important;
        }

        body,
        main {
            background: white !important;
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
        }

        .shadow-sm,
        .rounded-2xl {
            box-shadow: none !important;
            border-radius: 0 !important;
        }

    }

</style>

@endsection
