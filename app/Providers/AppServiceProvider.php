<?php

namespace App\Providers;

use App\Models\FooterSetting;
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
    }
}