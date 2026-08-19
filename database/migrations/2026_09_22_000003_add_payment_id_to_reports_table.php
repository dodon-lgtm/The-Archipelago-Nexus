<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * ADITIF — Menghubungkan laporan/dispute ke pembayaran terkait.
     *
     * workspace_id tetap relasi utama. payment_id ditambahkan agar Admin dapat
     * meresolve dana tertahan (release/refund/split) langsung dari konteks laporan.
     * Nullable + nullOnDelete agar penghapusan payment tidak menghapus laporan.
     */
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->foreignId('payment_id')
                ->nullable()
                ->after('workspace_id')
                ->constrained('payments')
                ->nullOnDelete()
                ->comment('Pembayaran terkait laporan (opsional)');
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropConstrainedForeignId('payment_id');
        });
    }
};
