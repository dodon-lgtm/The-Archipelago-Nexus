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
            $table->string('paypal_order_id')->nullable()->unique()->after('payment_proof');
            $table->string('paypal_capture_id')->nullable()->unique()->after('paypal_order_id');
            $table->string('paypal_payer_id')->nullable()->after('paypal_capture_id');
            $table->string('paypal_payer_email')->nullable()->after('paypal_payer_id');
            $table->string('currency', 3)->nullable()->after('freelancer_receive');
            $table->json('raw_response')->nullable()->after('admin_note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'paypal_order_id',
                'paypal_capture_id',
                'paypal_payer_id',
                'paypal_payer_email',
                'currency',
                'raw_response',
            ]);
        });
    }
};