<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * FinancialSettings — pengaturan keuangan platform (single-row, dikelola Admin).
 *
 * Semua nilai adalah DEFAULT / source-of-truth untuk AKSI BARU (transaksi & kuota).
 * Transaksi lama TIDAK dihitung ulang dari sini karena nilai fee sudah di-snapshot
 * ke baris payment/withdrawal masing-masing.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_settings', function (Blueprint $table) {
            $table->id();

            $table->decimal('project_fee_rate', 5, 2)->default(5.00)
                ->comment('Fee platform proyek (%). Dipakai sebagai snapshot saat Payment proyek dibuat.');
            $table->decimal('withdrawal_fee_rate', 5, 2)->default(5.00)
                ->comment('Fee withdrawal freelancer (%). Disnapshot ke withdrawals saat dibuat.');
            $table->unsignedInteger('free_project_uploads_per_month')->default(3)
                ->comment('Jumlah upload proyek GRATIS per company per bulan kalender.');
            $table->decimal('paid_project_upload_price', 15, 2)->default(10000.00)
                ->comment('Harga upload proyek setelah kuota gratis habis (Rp).');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_settings');
    }
};
