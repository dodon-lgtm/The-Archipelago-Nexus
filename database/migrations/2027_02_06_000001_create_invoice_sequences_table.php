<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * M-2B — Sumber sequence nomor invoice (ADDITIF, tabel baru).
 *
 * Latar belakang (audit M-2B / F-03):
 *   - Generator lama menurunkan nomor dari data transaksional `payments`
 *     (baris terakhir hari itu + substr(-4) untuk workspace; MAX(id)+1 untuk quota)
 *     sehingga: race condition, duplicate invoice_number, sequence workspace
 *     tercampur invoice quota, sequence mundur ketika baris payment dihapus,
 *     dan tidak bisa melewati 9999 tanpa terpotong.
 *
 * Solusi: tabel sequence terpisah per scope + tanggal.
 *   scope      : workspace | quota
 *   seq_date   : tanggal sequence (per hari, mengikuti tanggal invoice)
 *   last_number: nomor terakhir yang sudah dialokasikan
 *
 * Semua akses DILAKUKAN oleh App\Services\InvoiceNumberService
 * (INSERT ... IGNORE untuk first-of-day + SELECT ... FOR UPDATE lalu increment,
 * di dalam transaksi yang sama dengan Payment::create()).
 *
 * TIDAK ada data existing yang diubah/dihapus: invoice lama tetap apa adanya.
 * Row sequence untuk tanggal yang sudah memiliki invoice akan di-seed otomatis
 * (lazy) dari invoice VALID (regex format + scope + tanggal) saat pertama kali
 * dialokasikan — sehingga tidak perlu data migration dan tidak mungkin
 * "meminum" invoice legacy/non-conforming seperti INV-QOT-RENDER-0001.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('invoice_sequences')) {
            return; // idempotent
        }

        Schema::create('invoice_sequences', function (Blueprint $table) {
            $table->id();

            $table->string('scope', 32)
                ->comment('Jenis invoice: workspace | quota.');

            $table->date('seq_date')
                ->comment('Tanggal sequence (per hari) — dipakai sebagai bagian YYYYMMDD invoice.');

            $table->unsignedInteger('last_number')
                ->default(0)
                ->comment('Nomor terakhir yang sudah dialokasikan pada scope+tanggal ini.');

            $table->timestamps();

            // Satu row per (scope, tanggal) — kunci anti-duplikasi & target row lock.
            $table->unique(['scope', 'seq_date'], 'invoice_sequences_scope_date_unique');
        });
    }

    public function down(): void
    {
        // Data di tabel ini adalah state turunan yang dapat di-seed ulang secara
        // otomatis dari invoice existing, sehingga aman di-drop saat rollback.
        Schema::dropIfExists('invoice_sequences');
    }
};
