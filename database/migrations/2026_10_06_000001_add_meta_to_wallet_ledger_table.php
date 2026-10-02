<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ADDITIF — Kolom meta (JSON) pada wallet_ledger.
 *
 * Dipakai untuk data terstruktur opsional tanpa menambah tabel baru:
 *   - admin_withdrawal : {method, bank_name, account_name, account_number}
 *   - admin_expense    : {category, category_label}
 *
 * Non-destruktif: kolom nullable, baris lama tetap valid (meta = NULL).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wallet_ledger', function (Blueprint $table) {
            $table->json('meta')
                ->nullable()
                ->after('description')
                ->comment('Data tambahan terstruktur (metode/tujuan penarikan admin, kategori expense).');
        });
    }

    public function down(): void
    {
        Schema::table('wallet_ledger', function (Blueprint $table) {
            if (Schema::hasColumn('wallet_ledger', 'meta')) {
                $table->dropColumn('meta');
            }
        });
    }
};
