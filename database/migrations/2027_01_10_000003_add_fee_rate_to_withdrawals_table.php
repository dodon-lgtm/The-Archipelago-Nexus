<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ADDITIF — snapshot fee_rate pada withdrawals.
 *
 * Menyimpan rate fee withdrawal yang berlaku SAAT penarikan dibuat, sehingga
 * perubahan financial_settings di kemudian hari TIDAK mengubah withdrawal lama
 * (fee & net_amount yang sudah ada tetap apa adanya).
 * Non-destruktif: kolom nullable untuk backward compatibility.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->decimal('fee_rate', 5, 2)->nullable()->after('fee')
                ->comment('Snapshot rate fee withdrawal (%) saat dibuat.');
        });
    }

    public function down(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->dropColumn('fee_rate');
        });
    }
};
