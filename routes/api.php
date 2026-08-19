<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MidtransWebhookController;

Route::post('/midtrans/notification', [MidtransWebhookController::class, 'handleNotification'])
    ->name('midtrans.notification');
