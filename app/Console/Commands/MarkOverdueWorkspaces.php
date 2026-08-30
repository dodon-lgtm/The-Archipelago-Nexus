<?php

namespace App\Console\Commands;

use App\Services\OverdueWorkspaceService;
use Illuminate\Console\Command;

/**
 * Menandai workspace sebagai 'Melewati Batas Waktu' saat deadline lewat &
 * pekerjaan belum selesai, dan mengembalikan status asalnya masing-masing
 * saat deadline belum lewat lagi.
 *
 * Seluruh aturan & logika pengecekan berada di
 * App\Services\OverdueWorkspaceService (sumber TUNGGAL) demi menghindari
 * duplikasi. Command ini hanyalah pembungkus Artisan; mekanisme utama TIDAK
 * lagi bergantung pada scheduler / `php artisan schedule:work` karena setiap
 * kali halaman Workspace dibuka atau di-refresh (WorkspaceController index &
 * show) service yang sama ikut dijalankan.
 */
class MarkOverdueWorkspaces extends Command
{
    protected $signature = 'workspaces:mark-overdue';

    protected $description = 'Tandai workspace sebagai Melewati Batas Waktu saat deadline lewat & pekerjaan belum selesai, dan kembalikan status asal saat deadline belum lewat lagi.';

    public function handle(): int
    {
        OverdueWorkspaceService::process();

        $this->info('Workspace yang melewati batas waktu & pemulihannya selesai diproses.');

        return self::SUCCESS;
    }
}