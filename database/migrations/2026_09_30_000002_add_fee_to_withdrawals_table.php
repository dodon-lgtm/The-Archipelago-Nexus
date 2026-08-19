<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->decimal('fee', 15, 2)->default(0)->after('amount')
                ->comment('Pajak admin 5% dari nominal penarikan');
            $table->decimal('net_amount', 15, 2)->default(0)->after('fee')
                ->comment('Nominal bersih yang diterima freelancer (amount - fee)');
        });

        // Backfill data lama: hitung pajak 5% dari nominal penarikan.
        $rows = DB::table('withdrawals')->where('amount', '>', 0)->get(['id', 'amount']);
        foreach ($rows as $row) {
            $fee = round((float) $row->amount * 0.05, 2);
            DB::table('withdrawals')
                ->where('id', $row->id)
                ->update([
                    'fee'        => $fee,
                    'net_amount' => round((float) $row->amount - $fee, 2),
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->dropColumn(['fee', 'net_amount']);
        });
    }
};