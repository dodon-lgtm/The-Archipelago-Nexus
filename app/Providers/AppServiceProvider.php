<?php

namespace App\Providers;

use App\Models\FooterSetting;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // 
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Bagikan pengaturan footer (single-row) ke partial footer di seluruh halaman publik.
        View::composer(['navbar.footer', 'landingpage'], function ($view) {
            try {
                $footerSettings = FooterSetting::getSettings();
            } catch (\Throwable $e) {
                $footerSettings = new FooterSetting();
            }

            $view->with('footerSettings', $footerSettings);
        });

        // Catat setiap email yang berhasil dikirim beserta Message-ID-nya.
        // Berguna untuk verifikasi pengiriman (mis. fitur "Hubungi Kami via Email"):
        //  - Message-ID yang dicatat dapat dicari lewat pencarian Gmail.
        //  - Tidak ada detail kredensial/SMTP yang ikut tercatat.
        \Illuminate\Support\Facades\Event::listen(function (MessageSent $event) {
            $recipients = implode(', ', array_map(
                fn ($addr) => $addr->getAddress(),
                $event->sent->getEnvelope()->getRecipients()
            ));

            Log::info('[Mail] Email terkirim ke ' . $recipients
                . ' | Message-ID: ' . $event->sent->getMessageId(), [
                    'sent_at' => now()->toDateTimeString(),
                ]);
        });
    }
}