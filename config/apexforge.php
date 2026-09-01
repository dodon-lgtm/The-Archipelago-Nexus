<?php

return [

    /*
    |--------------------------------------------------------------------------
    | ApexForge Labs — Manual Payment Destinations
    |--------------------------------------------------------------------------
    |
    | Rekening / wallet TUJUAN pembayaran manual yang dimiliki oleh platform
    | ApexForge Labs (BUKAN milik freelancer). Data ini ditampilkan kepada
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
                'Nama Bank'      => env('APEXFORGE_BANK_NAME', 'Bank Central Asia'),
                'Nomor Rekening' => env('APEXFORGE_BANK_ACCOUNT_NUMBER', '1234567890'),
                'Atas Nama'      => env('APEXFORGE_BANK_ACCOUNT_NAME', 'PT ApexForge Labs'),
            ],
            'copy_field'  => 'Nomor Rekening',
            'instruction'  => 'Lakukan transfer melalui ATM / Mobile Banking / Internet Banking ke rekening di atas, lalu isi form konfirmasi di bawah.',
        ],

        'wallet' => [
            'title' => 'WALLET',
            'label' => 'E-Wallet',
            'icon'  => 'fa-wallet',
            'rows'  => [
                'Platform'      => env('APEXFORGE_WALLET_PLATFORM', 'DANA'),
                'Nomor'        => env('APEXFORGE_WALLET_NUMBER', '081234567890'),
                'Atas Nama'  => env('APEXFORGE_WALLET_NAME', 'ApexForge Labs'),
            ],
            'copy_field'  => 'Nomor',
            'instruction'  => 'Lakukan pembayaran melalui aplikasi e-wallet ke nomor di atas, lalu isi form konfirmasi di bawah.',
        ],

    ],

];