<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Report V3 - Tabel lampiran laporan (report_attachments).
 *
 * Desain future-proof & scalable:
 *   - Mendukung BANYAK lampiran per laporan (multi-file).
 *   - Kolom type disiapkan agar nanti mudah menambah video/bukti lain.
 *   - Metadata per file (name, path, disk, mime_type, size, type).
 *   - Mendukung alur "admin meminta bukti tambahan" -> reporter upload lagi
 *     pada report yang sama, tanpa membuat report baru.
 *
 * Format yang diizinkan V3 awal: jpg, jpeg, png, pdf (maks 5 file).
 * Struktur tetap fleksibel untuk video di masa depan.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_attachments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('report_id')
                ->constrained('reports')
                ->cascadeOnDelete()
                ->comment('Laporan pemilik lampiran');

            $table->string('file_name')
                ->comment('Nama file asli');

            $table->string('file_path')
                ->comment('Path file di storage');

            $table->string('disk')
                ->default('public')
                ->comment('Disk penyimpanan');

            $table->string('mime_type')
                ->nullable()
                ->comment('MIME type file (image/png, image/jpeg, application/pdf, dll)');

            $table->unsignedBigInteger('file_size')
                ->default(0)
                ->comment('Ukuran file dalam byte');

            $table->string('type')
                ->default('image')
                ->comment('Jenis lampiran: image | pdf | video (disiapkan untuk future)');

            $table->string('caption')
                ->nullable()
                ->comment('Keterangan opsional dari pengunggah');

            $table->timestamps();

            $table->index('report_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_attachments');
    }
};
