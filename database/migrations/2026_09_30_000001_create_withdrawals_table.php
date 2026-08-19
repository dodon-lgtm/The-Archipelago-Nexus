<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();

            $table->string('withdrawal_code')->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->comment('Freelancer yang mengajukan penarikan');

            $table->decimal('amount', 15, 2);

            $table->enum('method', [
                'bank',
                'ewallet',
            ])->default('bank');

            $table->string('bank_name');
            $table->string('account_name');
            $table->string('account_number');

            $table->enum('status', [
                'menunggu',
                'diproses',
                'berhasil',
                'ditolak',
            ])->default('menunggu');

            $table->text('rejection_reason')->nullable();

            $table->foreignId('processed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('Admin yang memproses penarikan');

            $table->timestamp('processed_at')->nullable();
            $table->timestamp('paid_at')->nullable()
                ->comment('Waktu pencairan dana (simulasi payout)');

            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};