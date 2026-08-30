<?php

use App\Console\Commands\MarkOverdueWorkspaces;
use App\Console\Commands\SendProjectDeadlineNotifications;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Kirim notifikasi pengingat deadline proyek (H-3 dan H-1)
// ke company dan freelancer setiap hari pukul 09:00.
Schedule::command(SendProjectDeadlineNotifications::class)
    ->dailyAt('09:00')
    ->withoutOverlapping();

// Tandai workspace "Melewati Batas Waktu" + kirim notifikasi saat deadline lewat
// dan pekerjaan belum selesai. Dijalankan setelah pengingat deadline.
Schedule::command(MarkOverdueWorkspaces::class)
    ->dailyAt('09:05')
    ->withoutOverlapping();
