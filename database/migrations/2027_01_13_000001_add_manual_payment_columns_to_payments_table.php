<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom detail pembayaran manual (data pengirim + tujuan).
     *
     * Semua kolom NULLABLE agar data pembayaran lama tidak terdampak.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('sender_name')->nullable();
            $table->string('sender_bank')->nullable();
            $table->string('sender_account_number')->nullable();
            $table->date('payment_date')->nullable();
            $table->decimal('paid_amount', 15, 2)->nullable();
            $table->text('destination_info')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'sender_name',
                'sender_bank',
                'sender_account_number',
                'payment_date',
                'paid_amount',
                'destination_info',
            ]);
        });
    }
};