<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ADDITIF — snapshot platform_fee_rate pada payments.
 *
 * Menyimpan rate fee platform yang berlaku SAAT Payment dibuat, sehingga
 * perubahan financial_settings di kemudian hari TIDAK mengubah transaksi lama.
 * Non-destruktif: kolom nullable; baris lama tetap valid (platform_fee &
 * freelancer_receive yang sudah ada tetap apa adanya).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('platform_fee_rate', 5, 2)->nullable()->after('platform_fee')
                ->comment('Snapshot rate fee platform (%) saat payment dibuat.');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('platform_fee_rate');
        });
    }
};
