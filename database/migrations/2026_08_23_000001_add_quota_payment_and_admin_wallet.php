<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * ADDITIF - Company Project Upload Quota (pay-per-additional-project)
 * dan Admin Wallet, TANPA tabel payment/wallet baru.
 * 1) payments: workspace_id/freelancer_id nullable + payment_type.
 * 2) wallet_ledger: workspace/payment nullable + withdrawal_id + source
 *    + UNIQUE (payment_id,type) & (withdrawal_id,type) untuk idempotensi.
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->preparePayments();
        $this->prepareWalletLedger();
    }

    public function down(): void
    {
        $this->restoreWalletLedger();
        $this->restorePayments();
    }

    private function preparePayments(): void
    {
        $this->dropForeignOnColumn('payments', 'workspace_id');
        $this->dropForeignOnColumn('payments', 'freelancer_id');

        if ($this->tableHasIndex('payments', 'payments_workspace_id_unique')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->dropUnique('payments_workspace_id_unique');
            });
        }

        Schema::table('payments', function (Blueprint $table) {
            $table->unsignedBigInteger('workspace_id')->nullable()->change();
            $table->unsignedBigInteger('freelancer_id')->nullable()->change();
            $table->string('payment_type', 32)
                ->default('workspace')
                ->after('workspace_id')
                ->comment('Jenis payment: workspace atau quota.');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->index('payment_type', 'payments_payment_type_index');
            $table->foreign('workspace_id')->references('id')->on('project_workspaces')->cascadeOnDelete();
            $table->foreign('freelancer_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    private function prepareWalletLedger(): void
    {
        $this->dropForeignOnColumn('wallet_ledger', 'workspace_id');
        $this->dropForeignOnColumn('wallet_ledger', 'payment_id');

        Schema::table('wallet_ledger', function (Blueprint $table) {
            $table->unsignedBigInteger('workspace_id')->nullable()->change();
            $table->unsignedBigInteger('payment_id')->nullable()->change();
            $table->foreignId('withdrawal_id')
                ->nullable()
                ->after('payment_id')
                ->constrained('withdrawals', 'id')
                ->nullOnDelete();
            $table->string('source', 40)
                ->nullable()
                ->after('type')
                ->comment('Sumber transaksi: quota_payment|withdrawal_fee|admin_expense|escrow_*|legacy');
        });

        DB::statement('
            DELETE wl FROM wallet_ledger wl
            INNER JOIN wallet_ledger wl2
                ON wl.payment_id = wl2.payment_id
               AND wl.type = wl2.type
               AND wl.id < wl2.id
            WHERE wl.payment_id IS NOT NULL
        ');

        Schema::table('wallet_ledger', function (Blueprint $table) {
            $table->foreign('workspace_id')->references('id')->on('project_workspaces')->cascadeOnDelete();
            $table->foreign('payment_id')->references('id')->on('payments')->cascadeOnDelete();
            $table->unique(['payment_id', 'type'], 'wallet_ledger_payment_type_unique');
            $table->unique(['withdrawal_id', 'type'], 'wallet_ledger_withdrawal_type_unique');
            $table->index('withdrawal_id', 'wallet_ledger_withdrawal_index');
            $table->index('source', 'wallet_ledger_source_index');
        });

        DB::table('wallet_ledger')->whereNull('source')->update(['source' => 'legacy']);
    }

    private function restoreWalletLedger(): void
    {
        Schema::table('wallet_ledger', function (Blueprint $table) {
            if ($this->tableHasIndex('wallet_ledger', 'wallet_ledger_source_index')) {
                $table->dropIndex('wallet_ledger_source_index');
            }
            if ($this->tableHasIndex('wallet_ledger', 'wallet_ledger_withdrawal_index')) {
                $table->dropIndex('wallet_ledger_withdrawal_index');
            }
            if ($this->tableHasIndex('wallet_ledger', 'wallet_ledger_withdrawal_type_unique')) {
                $table->dropUnique('wallet_ledger_withdrawal_type_unique');
            }
            if ($this->tableHasIndex('wallet_ledger', 'wallet_ledger_payment_type_unique')) {
                $table->dropUnique('wallet_ledger_payment_type_unique');
            }
            if ($this->tableHasIndex('wallet_ledger', 'wallet_ledger_withdrawal_id_foreign')) {
                $table->dropForeign(['withdrawal_id']);
            }
            $table->dropColumn(['withdrawal_id', 'source']);
        });

        $this->dropForeignOnColumn('wallet_ledger', 'workspace_id');
        $this->dropForeignOnColumn('wallet_ledger', 'payment_id');

        Schema::table('wallet_ledger', function (Blueprint $table) {
            $table->unsignedBigInteger('workspace_id')->nullable(false)->change();
            $table->unsignedBigInteger('payment_id')->nullable(false)->change();
        });

        Schema::table('wallet_ledger', function (Blueprint $table) {
            $table->foreign('workspace_id')->references('id')->on('project_workspaces')->cascadeOnDelete();
            $table->foreign('payment_id')->references('id')->on('payments')->cascadeOnDelete();
        });
    }

    private function restorePayments(): void
    {
        $this->dropForeignOnColumn('payments', 'workspace_id');
        $this->dropForeignOnColumn('payments', 'freelancer_id');

        Schema::table('payments', function (Blueprint $table) {
            if ($this->tableHasIndex('payments', 'payments_payment_type_index')) {
                $table->dropIndex('payments_payment_type_index');
            }
            $table->unsignedBigInteger('workspace_id')->nullable(false)->change();
            $table->unsignedBigInteger('freelancer_id')->nullable(false)->change();
            $table->dropColumn('payment_type');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->foreign('workspace_id')->references('id')->on('project_workspaces')->cascadeOnDelete();
            $table->foreign('freelancer_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    private function tableHasIndex(string $table, string $index): bool
    {
        foreach (Schema::getIndexes($table) as $idx) {
            if (($idx['name'] ?? '') === $index) {
                return true;
            }
        }

        return false;
    }

    private function dropForeignOnColumn(string $table, string $column): void
    {
        foreach (Schema::getForeignKeys($table) as $fk) {
            if (in_array($column, $fk['columns'] ?? [], true)) {
                Schema::table($table, function (Blueprint $table) use ($fk) {
                    $table->dropForeign($fk['name']);
                });

                break;
            }
        }
    }
};
