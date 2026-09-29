<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Vexus — Manual Payment Destinations
    |--------------------------------------------------------------------------
    |
    | Rekening / wallet TUJUAN pembayaran manual yang dimiliki oleh platform
    | Vexus (BUKAN milik freelancer). Data ini ditampilkan kepada
    | Company saat memilih metode "Bayar Manual" dan di-snapshot ke tabel
    | `payments.destination_info` agar Admin dapat melihat tujuan yang dipakai.
    |
    */

    'manual_payment_destinations' => [

        'bank' => [
            'title' => 'BANK',
            'label' => 'Transfer Bank',
            'icon'  => 'fa-building-columns',
            'rows'  => [
                'Nama Bank'      => env('VEXUS_BANK_NAME', 'Bank Central Asia'),
                'Nomor Rekening' => env('VEXUS_BANK_ACCOUNT_NUMBER', '1234567890'),
                'Atas Nama'      => env('VEXUS_BANK_ACCOUNT_NAME', 'PT Vexus'),
            ],
            'copy_field'  => 'Nomor Rekening',
            'instruction'  => 'Lakukan transfer melalui ATM / Mobile Banking / Internet Banking ke rekening di atas, lalu isi form konfirmasi di bawah.',
        ],

        'wallet' => [
            'title' => 'WALLET',
            'label' => 'E-Wallet',
            'icon'  => 'fa-wallet',
            'rows'  => [
                'Platform'      => env('VEXUS_WALLET_PLATFORM', 'DANA'),
                'Nomor'        => env('VEXUS_WALLET_NUMBER', '081234567890'),
                'Atas Nama'  => env('VEXUS_WALLET_NAME', 'Vexus'),
            ],
            'copy_field'  => 'Nomor',
            'instruction'  => 'Lakukan pembayaran melalui aplikasi e-wallet ke nomor di atas, lalu isi form konfirmasi di bawah.',
        ],

    ],

];