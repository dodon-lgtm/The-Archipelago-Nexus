<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    ],

    'midtrans' => [
        'server_key' => env('MIDTRANS_SERVER_KEY'),
        'client_key' => env('MIDTRANS_CLIENT_KEY'),
        'is_production' => env('MIDTRANS_IS_PRODUCTION', false),

        /*
        |--------------------------------------------------------------------------
        | TEMPORARY / TESTING FLOW — Payment Confirmation
        |--------------------------------------------------------------------------
        |
        | PERINGATAN: Ini adalah FLOW SEMENTARA untuk keperluan TESTING.
        | Saat PAYMENT_TEMPORARY_CONFIRMATION=true, Company dapat mengonfirmasi
        | pembayaran dari halaman gateway melalui endpoint backend yang sah,
        | sehingga workspace terbuka TANPA menunggu webhook Midtrans.
        |
        | Setelah webhook Midtrans diperbaiki, set variabel ini menjadi false
        | (atau hapus) agar sistem kembali mempercayai webhook resmi Midtrans
        | sebagai sumber konfirmasi pembayaran.
        |
        | JANGAN aktifkan di production.
        */
        'temporary_confirmation' => env('PAYMENT_TEMPORARY_CONFIRMATION', false),
    ],

];
