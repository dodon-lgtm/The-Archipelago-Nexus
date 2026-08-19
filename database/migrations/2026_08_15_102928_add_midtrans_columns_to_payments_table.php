<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('midtrans_transaction_id')
                ->nullable()
                ->unique()
                ->after('status');

            $table->string('midtrans_payment_type')
                ->nullable()
                ->after('midtrans_transaction_id');

            $table->json('midtrans_response')
                ->nullable()
                ->after('midtrans_payment_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'midtrans_transaction_id',
                'midtrans_payment_type',
                'midtrans_response',
            ]);
        });
    }
};

