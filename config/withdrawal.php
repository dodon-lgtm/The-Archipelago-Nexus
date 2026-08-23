<?php

/*
|--------------------------------------------------------------------------
| Withdrawal Configuration — FEE PROVIDER TERPUSAT
|--------------------------------------------------------------------------
|
| Konfigurasi BIAYA PROVIDER untuk penarikan dana. Angka di sini adalah
| biaya eksternal dari provider pembayaran (bukan pendapatan platform),
| dipotong dari nominal penarikan ADMIN.
|
| PENTING:
|  - Freelancer TIDAK terpengaruh (tetap platform fee 5% via
|    Withdrawal::TAX_RATE di WithdrawalService — aturan existing).
|  - Admin tidak dikenakan platform fee 5%; yang dipotong hanya fee provider.
|  - Semua nilai fee WAJIB dibaca dari file ini — dilarang hardcode di Blade.
*/

return [

    /*
    |----------------------------------------------------------------------
    | Provider penarikan & struktur fee-nya
    |----------------------------------------------------------------------
    | type: 'fixed'    => amount adalah Rupiah tetap per transaksi.
    |       'percent'  => amount adalah persentase dari nominal penarikan.
    */
    'providers' => [
        'bank' => [
            'label'      => 'Bank Transfer',
            'fee'        => ['type' => 'fixed', 'amount' => 6500],
        ],
        'ewallet' => [
            'label'      => 'E-Wallet',
            'fee'        => ['type' => 'percent', 'amount' => 1], // 1%
        ],
    ],

    /*
    |----------------------------------------------------------------------
    | Minimal & maksimal nominal penarikan admin
    |----------------------------------------------------------------------
    */
    'min_amount' => 10000,
];
