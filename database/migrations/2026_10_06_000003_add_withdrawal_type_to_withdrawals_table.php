<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ADDITIF — withdrawal_type pada tabel withdrawals.
 *
 * Menjadikan tabel withdrawals reusable untuk DUA jenis penarikan:
 *   - 'freelancer' : flow existing (fee = platform tax 5%, income Admin Wallet).
 *   - 'admin'      : penarikan saldo platform (fee = biaya PROVIDER dari
 *                    config/withdrawal.php; TIDAK ada platform fee 5%;
 *                    debit wallet = amount penuh; diterima = amount - fee).
 *
 * Non-destruktif: default 'freelancer' menjaga seluruh baris lama tetap
 * berperilaku persis seperti sebelumnya.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->string('withdrawal_type', 20)
                ->default('freelancer')
                ->after('withdrawal_code')
                ->comment('freelancer|admin');

            $table->index('withdrawal_type', 'withdrawals_withdrawal_type_index');
        });
    }

    public function down(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->dropIndex('withdrawals_withdrawal_type_index');
            $table->dropColumn('withdrawal_type');
        });
    }
};
