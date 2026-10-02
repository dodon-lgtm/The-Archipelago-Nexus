<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ADDITIF & IDEMPOTENT — Persiapan skema fitur platform wallet:
 *   1) payments      : workspace_id/freelancer_id nullable + payment_type.
 *   2) wallet_ledger : workspace/payment nullable + withdrawal_id + source
 *                      + UNIQUE (payment_id,type) & (withdrawal_id,type).
 *
 * Mengambil alih peran 2026_08_23_000001 pada fresh install karena tabel
 * wallet_ledger (2026_09_22) dan withdrawals (2026_09_30) baru tersedia
 * SETELAH tanggal tersebut. Semua langkah ber-guard hasColumn/hasIndex,
 * sehingga AMAN dijalankan pada dev MySQL yang kolomnya sudah lengkap
 * (semua langkah akan skip).
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
        // NO-OP: kolom/index aditif dipakai data existing.
    }

    private function preparePayments(): void
    {
        if (!Schema::hasTable('payments')) {
            return;
        }

        $columns = collect(Schema::getColumns('payments'))->keyBy('name');
        $isSqlite = $this->isSqlite();

        if (!$isSqlite) {
            $this->dropForeignKeyIfExists('payments', 'workspace_id');
            $this->dropForeignKeyIfExists('payments', 'freelancer_id');

            if ($this->tableHasIndex('payments', 'payments_workspace_id_unique')) {
                Schema::table('payments', fn (Blueprint $t) => $t->dropUnique('payments_workspace_id_unique'));
            }
        }

        Schema::table('payments', function (Blueprint $t) use ($columns): void {
            if (($columns['workspace_id']['nullable'] ?? false) === false) {
                $t->unsignedBigInteger('workspace_id')->nullable()->change();
            }
            if (($columns['freelancer_id']['nullable'] ?? false) === false) {
                $t->unsignedBigInteger('freelancer_id')->nullable()->change();
            }

            if (!Schema::hasColumn('payments', 'payment_type')) {
                $t->string('payment_type', 32)->default('workspace')->after('workspace_id')
                    ->comment('Jenis payment: workspace atau quota.');
            }
        });

        if (!$this->tableHasIndex('payments', 'payments_payment_type_index')) {
            Schema::table('payments', fn (Blueprint $t) => $t->index('payment_type', 'payments_payment_type_index'));
        }

        if (!$isSqlite) {
            $this->addForeignKeyIfMissing('payments', 'workspace_id', 'project_workspaces');
            $this->addForeignKeyIfMissing('payments', 'freelancer_id', 'users');
        }
    }

    private function prepareWalletLedger(): void
    {
        if (!Schema::hasTable('wallet_ledger')) {
            return;
        }

        $columns = collect(Schema::getColumns('wallet_ledger'))->keyBy('name');
        $isSqlite = $this->isSqlite();

        if (!$isSqlite) {
            $this->dropForeignKeyIfExists('wallet_ledger', 'workspace_id');
            $this->dropForeignKeyIfExists('wallet_ledger', 'payment_id');
        }

        Schema::table('wallet_ledger', function (Blueprint $t) use ($columns): void {
            if (($columns['workspace_id']['nullable'] ?? false) === false) {
                $t->unsignedBigInteger('workspace_id')->nullable()->change();
            }
            if (($columns['payment_id']['nullable'] ?? false) === false) {
                $t->unsignedBigInteger('payment_id')->nullable()->change();
            }

            if (!Schema::hasColumn('wallet_ledger', 'withdrawal_id')) {
                $t->foreignId('withdrawal_id')->nullable()->after('payment_id')
                    ->constrained('withdrawals', 'id')->nullOnDelete();
            }

            if (!Schema::hasColumn('wallet_ledger', 'source')) {
                $t->string('source', 40)->nullable()->after('type')
                    ->comment('Sumber transaksi: quota_payment|withdrawal_fee|admin_expense|admin_withdrawal|legacy');
            }
        });

        if (!$isSqlite) {
            if (!$this->tableHasIndex('wallet_ledger', 'wallet_ledger_payment_type_unique')) {
                \Illuminate\Support\Facades\DB::statement("
                    DELETE wl FROM wallet_ledger wl
                    INNER JOIN wallet_ledger wl2
                        ON wl.payment_id = wl2.payment_id
                       AND wl.type = wl2.type
                       AND wl.id < wl2.id
                    WHERE wl.payment_id IS NOT NULL
                ");
            }

            \Illuminate\Support\Facades\DB::table('wallet_ledger')
                ->whereNull('source')->update(['source' => 'legacy']);

            $this->addForeignKeyIfMissing('wallet_ledger', 'workspace_id', 'project_workspaces');
            $this->addForeignKeyIfMissing('wallet_ledger', 'payment_id', 'payments');
        }

        if (!$this->tableHasIndex('wallet_ledger', 'wallet_ledger_payment_type_unique')) {
            Schema::table('wallet_ledger', fn (Blueprint $t) => $t->unique(['payment_id', 'type'], 'wallet_ledger_payment_type_unique'));
        }

        if (!$this->tableHasIndex('wallet_ledger', 'wallet_ledger_withdrawal_type_unique')) {
            Schema::table('wallet_ledger', fn (Blueprint $t) => $t->unique(['withdrawal_id', 'type'], 'wallet_ledger_withdrawal_type_unique'));
        }

        if (!$this->tableHasIndex('wallet_ledger', 'wallet_ledger_withdrawal_index')) {
            Schema::table('wallet_ledger', fn (Blueprint $t) => $t->index('withdrawal_id', 'wallet_ledger_withdrawal_index'));
        }

        if (!$this->tableHasIndex('wallet_ledger', 'wallet_ledger_source_index')) {
            Schema::table('wallet_ledger', fn (Blueprint $t) => $t->index('source', 'wallet_ledger_source_index'));
        }
    }

    private function isSqlite(): bool
    {
        return Schema::getConnection()->getDriverName() === 'sqlite';
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

    private function dropForeignKeyIfExists(string $table, string $column): void
    {
        foreach (Schema::getForeignKeys($table) as $fk) {
            if (in_array($column, $fk['columns'] ?? [], true)) {
                Schema::table($table, fn (Blueprint $t) => $t->dropForeign($fk['name']));
                break;
            }
        }
    }

    private function addForeignKeyIfMissing(string $table, string $column, string $referenced): void
    {
        foreach (Schema::getForeignKeys($table) as $fk) {
            if (in_array($column, $fk['columns'] ?? [], true)) {
                return; // FK sudah ada
            }
        }

        Schema::table($table, function (Blueprint $t) use ($column, $referenced): void {
            $t->foreign($column)->references('id')->on($referenced)->cascadeOnDelete();
        });
    }
};
