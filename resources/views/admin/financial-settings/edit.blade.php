@extends('layouts.admin')

@section('title', 'Pengaturan Keuangan')

@section('breadcrumb', 'Pengaturan Keuangan')

@section('content')

    <div class="max-w-4xl mx-auto">

        <div class="flex items-center gap-3 mb-6">

            <a href="{{ route('admin.dashboard') }}"
                class="text-slate-400 hover:text-blue-600">
                <i class="fa-solid fa-chevron-left"></i>
            </a>

            <h2 class="text-xl font-extrabold text-slate-800 dark:text-white">
                Pengaturan Keuangan
            </h2>

        </div>


        {{-- Success Message --}}
        @if (session('success'))

            <div class="p-3.5 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 mb-4 text-xs">
                {{ session('success') }}
            </div>

        @endif


        {{-- Validation Errors --}}
        @if ($errors->any())

            <div class="p-3.5 rounded-xl bg-red-50 text-red-700 border border-red-200 mb-4 text-xs">

                <ul class="list-disc pl-4 space-y-0.5">

                    @foreach ($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- Ringkasan --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-blue-100 dark:border-slate-800 shadow-sm p-5 mb-5">

            <h3 class="text-sm font-black text-slate-800 dark:text-white mb-4 flex items-center gap-2">

                <i class="fa-solid fa-chart-pie text-blue-600 dark:text-blue-400"></i>

                Ringkasan Pengaturan

            </h3>


            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-center">


                {{-- Fee Platform --}}
                <div class="bg-[#f6f9ff] dark:bg-slate-800 rounded-xl p-3">

                    <p class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        Fee Platform Proyek
                    </p>

                    <p class="text-lg font-black text-slate-800 dark:text-white mt-1">

                        {{
                            rtrim(
                                rtrim(
                                    number_format(
                                        (float) $setting->project_fee_rate,
                                        2,
                                        ',',
                                        '.'
                                    ),
                                    '0'
                                ),
                                ','
                            )
                        }}%

                    </p>

                </div>


                {{-- Fee Withdrawal --}}
                <div class="bg-[#f6f9ff] dark:bg-slate-800 rounded-xl p-3">

                    <p class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        Fee Withdrawal Freelancer
                    </p>

                    <p class="text-lg font-black text-slate-800 dark:text-white mt-1">

                        {{
                            rtrim(
                                rtrim(
                                    number_format(
                                        (float) $setting->withdrawal_fee_rate,
                                        2,
                                        ',',
                                        '.'
                                    ),
                                    '0'
                                ),
                                ','
                            )
                        }}%

                    </p>

                </div>


                {{-- Upload Gratis --}}
                <div class="bg-[#f6f9ff] dark:bg-slate-800 rounded-xl p-3">

                    <p class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        Upload Gratis / Bulan
                    </p>

                    <p class="text-lg font-black text-slate-800 dark:text-white mt-1">

                        {{ (int) $setting->free_project_uploads_per_month }}

                        proyek

                    </p>

                </div>


                {{-- Harga Upload --}}
                <div class="bg-[#f6f9ff] dark:bg-slate-800 rounded-xl p-3">

                    <p class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        Harga Upload Berikutnya
                    </p>

                    <p class="text-lg font-black text-slate-800 dark:text-white mt-1">

                        Rp
                        {{
                            number_format(
                                (float) $setting->paid_project_upload_price,
                                0,
                                ',',
                                '.'
                            )
                        }}

                    </p>

                </div>


            </div>

        </div>


        {{-- Form --}}
        <form method="POST"
            action="{{ route('admin.financial-settings.update') }}"
            id="financial-settings-form">

            @csrf

            @method('PUT')


            {{-- =========================================================
                FEE PLATFORM PROYEK
            ========================================================== --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-blue-100 dark:border-slate-800 shadow-sm p-5 mb-5">

                <h3 class="text-sm font-black text-slate-800 dark:text-white mb-1 flex items-center gap-2">

                    <i class="fa-solid fa-percent text-blue-600 dark:text-blue-400"></i>

                    Fee Platform Proyek

                </h3>


                <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">

                    Persentase fee platform yang dipotong dari harga proyek
                    (pendapatan platform). Nilai ini di-snapshot saat Payment
                    proyek dibuat.

                </p>


                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>

                        <label
                            class="text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1 block">

                            Persentase fee platform

                        </label>


                        <div class="relative">

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                max="100"
                                name="project_fee_rate"

                                value="{{ old('project_fee_rate', $setting->project_fee_rate) }}"

                                class="w-full rounded-xl border-blue-100 dark:border-slate-700 bg-[#f6f9ff] dark:bg-slate-800 px-4 py-2.5 pr-12 text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none"
                            >


                            <span
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">

                                %

                            </span>

                        </div>


                        @error('project_fee_rate')

                            <p class="text-xs text-red-500 mt-1">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-6">

                        Contoh:

                        <code class="text-slate-700 dark:text-slate-300">
                            5.00
                        </code>

                        = 5%,

                        <code class="text-slate-700 dark:text-slate-300">
                            12.50
                        </code>

                        = 12,5%.

                    </div>

                </div>

            </div>



            {{-- =========================================================
                FEE WITHDRAWAL FREELANCER
            ========================================================== --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-blue-100 dark:border-slate-800 shadow-sm p-5 mb-5">

                <h3 class="text-sm font-black text-slate-800 dark:text-white mb-1 flex items-center gap-2">

                    <i class="fa-solid fa-money-bill-transfer text-blue-600 dark:text-blue-400"></i>

                    Fee Withdrawal Freelancer

                </h3>


                <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">

                    Persentase fee yang dipotong dari setiap penarikan dana
                    Freelancer. Nilai ini dari platform (komisi), bukan pajak
                    legal (PPN/PPh).

                </p>


                <div>

                    <label
                        class="text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1 block">

                        Persentase fee withdrawal

                    </label>


                    <div class="relative max-w-xs">

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            max="100"
                            name="withdrawal_fee_rate"

                            value="{{ old('withdrawal_fee_rate', $setting->withdrawal_fee_rate) }}"

                            class="w-full rounded-xl border-blue-100 dark:border-slate-700 bg-[#f6f9ff] dark:bg-slate-800 px-4 py-2.5 pr-12 text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none"
                        >


                        <span
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">

                            %

                        </span>

                    </div>


                    @error('withdrawal_fee_rate')

                        <p class="text-xs text-red-500 mt-1">
                            {{ $message }}
                        </p>

                    @enderror

                </div>

            </div>



            {{-- =========================================================
                GRATIS UPLOAD PROYEK
            ========================================================== --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-blue-100 dark:border-slate-800 shadow-sm p-5 mb-5">

                <h3 class="text-sm font-black text-slate-800 dark:text-white mb-1 flex items-center gap-2">

                    <i class="fa-solid fa-box-open text-blue-600 dark:text-blue-400"></i>

                    Batas Upload Gratis Proyek

                </h3>


                <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">

                    Batas upload gratis adalah jumlah proyek yang dapat dibuat
                    secara gratis oleh setiap Company dalam satu bulan kalender.

                </p>


                <div>

                    <label
                        class="text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1 block">

                        Batas upload gratis per bulan

                    </label>


                    <div class="relative max-w-xs">

                        <input
                            type="number"
                            step="1"
                            min="0"
                            name="free_project_uploads_per_month"

                            value="{{ old('free_project_uploads_per_month', $setting->free_project_uploads_per_month) }}"

                            class="w-full rounded-xl border-blue-100 dark:border-slate-700 bg-[#f6f9ff] dark:bg-slate-800 px-4 py-2.5 pr-20 text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none"
                        >


                        <span
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">

                            proyek

                        </span>

                    </div>


                    @error('free_project_uploads_per_month')

                        <p class="text-xs text-red-500 mt-1">
                            {{ $message }}
                        </p>

                    @enderror

                </div>

            </div>



            {{-- =========================================================
                HARGA UPLOAD BERIKUTNYA
            ========================================================== --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-blue-100 dark:border-slate-800 shadow-sm p-5 mb-5">

                <h3 class="text-sm font-black text-slate-800 dark:text-white mb-1 flex items-center gap-2">

                    <i class="fa-solid fa-coins text-blue-600 dark:text-blue-400"></i>

                    Harga Upload Berikutnya

                </h3>


                <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">

                    Harga upload berikutnya digunakan setelah kuota gratis
                    bulan berjalan habis.

                </p>


                <div>

                    <label
                        for="paid_project_upload_price_display"
                        class="text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1 block">

                        Harga upload setelah kuota habis

                    </label>


                    <div class="relative max-w-xs">

                        {{-- Prefix Rupiah --}}
                        <span
                            class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400 pointer-events-none">

                            Rp

                        </span>


                        {{-- =================================================
                            VISIBLE INPUT

                            Hanya untuk tampilan.
                            Contoh:
                            Database 100000
                            Tampilan 100.000
                        ================================================== --}}
                        <input
                            type="text"

                            id="paid_project_upload_price_display"

                            inputmode="numeric"

                            autocomplete="off"

                            spellcheck="false"

                            value="{{ old('paid_project_upload_price', $setting->paid_project_upload_price) }}"

                            class="w-full rounded-xl border-blue-100 dark:border-slate-700 bg-[#f6f9ff] dark:bg-slate-800 px-10 py-2.5 text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none"

                            aria-label="Harga upload setelah kuota habis"
                        >


                        {{-- =================================================
                            HIDDEN RAW INPUT

                            Inilah yang dikirim ke backend.

                            Tampilan:
                            100.000

                            Backend:
                            100000
                        ================================================== --}}
                        <input
                            type="hidden"

                            name="paid_project_upload_price"

                            id="paid_project_upload_price"

                            value="{{ old('paid_project_upload_price', $setting->paid_project_upload_price) }}"
                        >

                    </div>


                    @error('paid_project_upload_price')

                        <p class="text-xs text-red-500 mt-1">
                            {{ $message }}
                        </p>

                    @enderror

                </div>

            </div>



            {{-- =========================================================
                BUTTON
            ========================================================== --}}
            <div class="flex items-center justify-end gap-3">

                <a
                    href="{{ route('admin.dashboard') }}"

                    class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 rounded-xl text-sm font-semibold hover:bg-slate-200 dark:hover:bg-slate-700 transition">

                    Batal

                </a>


                <button
                    type="submit"

                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-600/20 transition flex items-center gap-2">

                    <i class="fa-solid fa-save"></i>

                    Simpan Pengaturan

                </button>

            </div>

        </form>

    </div>

@endsection



@push('scripts')

<script>
(function () {

    'use strict';


    /*
    |--------------------------------------------------------------------------
    | ELEMENT
    |--------------------------------------------------------------------------
    */

    const form =
        document.getElementById('financial-settings-form');

    const displayInput =
        document.getElementById('paid_project_upload_price_display');

    const rawInput =
        document.getElementById('paid_project_upload_price');


    /*
    |--------------------------------------------------------------------------
    | SAFETY CHECK
    |--------------------------------------------------------------------------
    */

    if (!displayInput || !rawInput) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | AMBIL DIGIT SAJA
    |--------------------------------------------------------------------------
    |
    | Semua huruf, simbol, spasi, titik, koma, dll dibuang.
    |
    | "100000"      -> "100000"
    | "100.000"     -> "100000"
    | "Rp 100.000"  -> "100000"
    | "abc100000"   -> "100000"
    |
    */

    function onlyDigits(value) {

        return String(value || '')
            .replace(/[^0-9]/g, '');

    }


    /*
    |--------------------------------------------------------------------------
    | FORMAT RIBUAN INDONESIA
    |--------------------------------------------------------------------------
    |
    | 1       -> 1
    | 10      -> 10
    | 100     -> 100
    | 1000    -> 1.000
    | 10000   -> 10.000
    | 100000  -> 100.000
    | 1000000 -> 1.000.000
    |
    */

    function formatThousands(value) {

        const digits =
            onlyDigits(value);

        if (digits === '') {
            return '';
        }

        return digits.replace(
            /\B(?=(\d{3})+(?!\d))/g,
            '.'
        );

    }


    /*
    |--------------------------------------------------------------------------
    | HITUNG DIGIT SEBELUM CURSOR
    |--------------------------------------------------------------------------
    |
    | Supaya cursor tidak lompat ketika titik otomatis
    | ditambahkan.
    |
    */

    function getDigitsBeforeCursor(
        value,
        cursorPosition
    ) {

        return onlyDigits(
            value.substring(
                0,
                cursorPosition
            )
        ).length;

    }


    /*
    |--------------------------------------------------------------------------
    | HITUNG POSISI CURSOR BARU
    |--------------------------------------------------------------------------
    */

    function getCursorPosition(
        formattedValue,
        digitCount
    ) {

        if (digitCount <= 0) {
            return 0;
        }

        let count = 0;

        for (
            let i = 0;
            i < formattedValue.length;
            i++
        ) {

            if (/[0-9]/.test(formattedValue[i])) {

                count++;

            }

            if (count === digitCount) {

                return i + 1;

            }

        }

        return formattedValue.length;

    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE RAW VALUE
    |--------------------------------------------------------------------------
    */

    function updateRawValue() {

        const rawValue =
            onlyDigits(displayInput.value);

        rawInput.value =
            rawValue;

    }


    /*
    |--------------------------------------------------------------------------
    | FORMAT NILAI AWAL DARI DATABASE
    |--------------------------------------------------------------------------
    |
    | Misalnya database:
    |
    | 100000
    |
    | Maka ketika halaman dibuka:
    |
    | 100.000
    |
    */

    const initialValue =
        onlyDigits(displayInput.value);

    displayInput.value =
        formatThousands(initialValue);

    rawInput.value =
        initialValue;


    /*
    |--------------------------------------------------------------------------
    | REAL-TIME FORMAT SAAT MENGETIK
    |--------------------------------------------------------------------------
    */

    displayInput.addEventListener(
        'input',
        function () {

            const oldValue =
                this.value;

            const oldCursor =
                this.selectionStart || 0;


            /*
             * Hitung jumlah digit sebelum cursor.
             */
            const digitsBeforeCursor =
                getDigitsBeforeCursor(
                    oldValue,
                    oldCursor
                );


            /*
             * Ambil angka saja.
             */
            const rawValue =
                onlyDigits(oldValue);


            /*
             * Format menjadi ribuan.
             */
            const formattedValue =
                formatThousands(rawValue);


            /*
             * Tampilkan kembali.
             */
            this.value =
                formattedValue;


            /*
             * Simpan angka mentah.
             */
            rawInput.value =
                rawValue;


            /*
             * Kembalikan cursor.
             */
            const newCursor =
                getCursorPosition(
                    formattedValue,
                    digitsBeforeCursor
                );


            try {

                this.setSelectionRange(
                    newCursor,
                    newCursor
                );

            } catch (error) {

                // Ignore cursor errors on unsupported browsers.

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | KEYBOARD FILTER
    |--------------------------------------------------------------------------
    |
    | Hanya angka yang boleh diketik.
    |
    | Ditolak:
    | - a-z
    | - e
    | - +
    | - -
    | - ,
    | | .
    | - simbol lainnya
    |
    */

    displayInput.addEventListener(
        'keydown',
        function (event) {

            /*
             * Tombol kontrol yang tetap diizinkan.
             */
            const allowedKeys = [

                'Backspace',
                'Delete',
                'Tab',

                'ArrowLeft',
                'ArrowRight',
                'ArrowUp',
                'ArrowDown',

                'Home',
                'End'

            ];


            if (allowedKeys.includes(event.key)) {

                return;

            }


            /*
             * Shortcut Ctrl / Cmd.
             */
            if (
                event.ctrlKey ||
                event.metaKey
            ) {

                const shortcuts = [

                    'a',
                    'c',
                    'v',
                    'x'

                ];


                if (
                    shortcuts.includes(
                        event.key.toLowerCase()
                    )
                ) {

                    return;

                }

            }


            /*
             * Hanya angka 0-9.
             */
            if (!/^[0-9]$/.test(event.key)) {

                event.preventDefault();

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | PASTE FILTER
    |--------------------------------------------------------------------------
    |
    | User bisa paste:
    |
    | 100000
    | Rp 100.000
    | 100.000
    | abc100000xyz
    |
    | Semuanya akan menjadi:
    |
    | 100.000
    |
    */

    displayInput.addEventListener(
        'paste',
        function (event) {

            event.preventDefault();


            const clipboard =
                event.clipboardData ||
                window.clipboardData;


            const pastedText =
                clipboard
                    ? clipboard.getData('text')
                    : '';


            const pastedDigits =
                onlyDigits(pastedText);


            const currentValue =
                this.value;


            const start =
                this.selectionStart || 0;


            const end =
                this.selectionEnd || 0;


            /*
             * Ambil bagian kiri.
             */
            const leftDigits =
                onlyDigits(
                    currentValue.substring(
                        0,
                        start
                    )
                );


            /*
             * Ambil bagian kanan.
             */
            const rightDigits =
                onlyDigits(
                    currentValue.substring(
                        end
                    )
                );


            /*
             * Gabungkan.
             */
            const combinedDigits =
                leftDigits +
                pastedDigits +
                rightDigits;


            /*
             * Format.
             */
            const formattedValue =
                formatThousands(
                    combinedDigits
                );


            /*
             * Tampilkan.
             */
            this.value =
                formattedValue;


            /*
             * Simpan raw.
             */
            rawInput.value =
                combinedDigits;


            /*
             * Cursor setelah hasil paste.
             */
            const newCursor =
                getCursorPosition(
                    formattedValue,
                    leftDigits.length +
                    pastedDigits.length
                );


            try {

                this.setSelectionRange(
                    newCursor,
                    newCursor
                );

            } catch (error) {

                // Ignore cursor errors.

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | BLUR
    |--------------------------------------------------------------------------
    |
    | Ketika keluar dari input, pastikan format tetap benar.
    |
    */

    displayInput.addEventListener(
        'blur',
        function () {

            const rawValue =
                onlyDigits(this.value);


            this.value =
                formatThousands(rawValue);


            rawInput.value =
                rawValue;

        }
    );


    /*
    |--------------------------------------------------------------------------
    | SUBMIT
    |--------------------------------------------------------------------------
    |
    | Yang dikirim ke backend adalah:
    |
    | 100000
    |
    | BUKAN:
    |
    | 100.000
    |
    */

    if (form) {

        form.addEventListener(
            'submit',
            function () {

                const rawValue =
                    onlyDigits(
                        displayInput.value
                    );


                rawInput.value =
                    rawValue;

            }
        );

    }


})();
</script>
@endpush