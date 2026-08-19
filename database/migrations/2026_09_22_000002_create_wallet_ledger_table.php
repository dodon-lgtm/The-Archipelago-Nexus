<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * ADITIF — Tabel audit trail seluruh pergerakan dana (wallet_ledger).
     *
     * Setiap mutasi uang WAJIB mencatat baris di sini:
     *   escrow_held / escrow_released / refund_issued / fee_earned / admin_adjustment
     *
     * Kolom report_id menghubungkan resolusi dana dengan laporan dispute (jika ada).
     * user_id nullable dipakai untuk transaksi pihak platform (mis. fee_earned).
     */
    public function up(): void
    {
        Schema::create('wallet_ledger', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('Pihak pemilik dana (company/freelancer/platform)');

            $table->foreignId('workspace_id')
                ->constrained('project_workspaces')
                ->cascadeOnDelete();

            $table->foreignId('payment_id')
                ->constrained('payments')
                ->cascadeOnDelete();

            $table->foreignId('report_id')
                ->nullable()
                ->constrained('reports')
                ->nullOnDelete()
                ->comment('Laporan/dispute yang memicu mutasi (jika ada)');

            $table->string('type', 40)
                ->comment('escrow_held|escrow_released|refund_issued|fee_earned|admin_adjustment');

            $table->decimal('amount', 15, 2)
                ->comment('Nominal mutasi (selalu positif)');

            $table->string('direction', 8)
                ->default('credit')
                ->comment('credit = saldo bertambah, debit = saldo berkurang');

            $table->decimal('balance_after', 15, 2)
                ->nullable()
                ->comment('Saldo user_id setelah mutasi ini (informasi audit)');

            $table->text('description')->nullable()
                ->comment('Alasan / keterangan mutasi');

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('User/aktor yang melakukan mutasi (sistem = null)');

            $table->timestamps();

            $table->index(['user_id', 'created_at'], 'wallet_ledger_user_created_index');
            $table->index('payment_id', 'wallet_ledger_payment_index');
            $table->index('workspace_id', 'wallet_ledger_workspace_index');
            $table->index('report_id', 'wallet_ledger_report_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_ledger');
    }
};
