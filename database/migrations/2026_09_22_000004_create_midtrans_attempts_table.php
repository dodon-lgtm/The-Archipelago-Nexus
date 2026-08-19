<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * ADITIF — Tracking percobaan pembayaran Midtrans (order_id unik per attempt).
     *
     * MidtransService::buildOrderId() menghasilkan order_id unik per attempt
     * (mis. "INV-20260816-0002_a1b2c3"). Menyimpan attempt memungkinkan webhook
     * memvalidasi bahwa order_id yang masuk benar-benar milik Payment ini dan
     * memperkuat idempotensi + audit trail keamanan webhook.
     */
    public function up(): void
    {
        Schema::create('midtrans_attempts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('payment_id')
                ->constrained('payments')
                ->cascadeOnDelete();

            $table->string('order_id')->unique()
                ->comment('Order ID unik per attempt (invoice_number + suffix)');

            $table->string('status', 32)
                ->default('created')
                ->comment('created|pending|settlement|capture|deny|cancel|expire|failure');

            $table->json('raw_response')->nullable()
                ->comment('Respons webhook terakhir untuk attempt ini');

            $table->timestamps();

            $table->index('payment_id', 'midtrans_attempts_payment_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('midtrans_attempts');
    }
};
