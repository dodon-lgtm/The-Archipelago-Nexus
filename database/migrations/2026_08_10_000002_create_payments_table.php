<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('workspace_id')
                ->constrained('project_workspaces')
                ->cascadeOnDelete()
                ->unique();

            $table->foreignId('company_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('freelancer_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('invoice_number')->unique();

            $table->decimal('amount', 15, 2);
            $table->decimal('platform_fee', 15, 2)->default(0);
            $table->decimal('freelancer_receive', 15, 2)->default(0);

            $table->string('payment_method')->nullable();
            $table->string('payment_proof')->nullable();
            $table->text('company_note')->nullable();
            $table->text('admin_note')->nullable();

            $table->enum('status', [
                'pending',
                'waiting_verification',
                'paid',
                'rejected',
            ])->default('pending');

            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('verified_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

