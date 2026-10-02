<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menambahkan status baca pada tabel `messages` untuk mendukung
 * indikator "Pesan Baru" (unread) di Dashboard Freelancer.
 *
 * - is_read : boolean, default false — true setelah pesan dilihat penerima.
 * - read_at : timestamp nullable — waktu pesan ditandai terbaca.
 * - index [workspace_id, is_read] : optimasi query unread count per workspace.
 *
 * Pola kolom mengikuti tabel `notifications` yang sudah ada
 * (is_read + read_at) agar konsisten dengan skema proyek.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->boolean('is_read')
                ->default(false)
                ->after('type');

            $table->timestamp('read_at')
                ->nullable()
                ->after('is_read');

            $table->index(['workspace_id', 'is_read'], 'messages_workspace_read_index');
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropIndex('messages_workspace_read_index');
            $table->dropColumn(['is_read', 'read_at']);
        });
    }
};
