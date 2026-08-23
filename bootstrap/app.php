<?php

require_once __DIR__.'/../app/helpers.php';

use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureCompanyAdminOrAbort;
use App\Http\Middleware\EnsureProfileComplete;
use App\Http\Middleware\EnsureWorkspacePaid;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // Percayai reverse proxy (ngrok tunnel untuk pembayaran Midtrans, load balancer).
        // Tanpa ini, route()/url() menghasilkan URL http:// di halaman HTTPS →
        // Mixed Content memblokir fetch (Snap quota payment, notifikasi) dan
        // notification_url webhook menjadi http://.
        // CATATAN PRODUCTION: ganti '*' dengan IP load balancer/proxy yang dikenal.
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'ensureAdmin' => EnsureAdmin::class,
            'ensureCompanyAdminOrAbort' => EnsureCompanyAdminOrAbort::class,
            'ensureFreelancer' => \App\Http\Middleware\EnsureFreelancerOrAbort::class,
            'ensureProfileComplete' => EnsureProfileComplete::class,
            'ensureWorkspacePaid' => EnsureWorkspacePaid::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'api/midtrans/notification',
            'payments/midtrans/notification',
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();