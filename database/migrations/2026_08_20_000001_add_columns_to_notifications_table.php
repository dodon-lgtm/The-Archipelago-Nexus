<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom baru ke tabel notifications
     * untuk mendukung sistem notifikasi yang lebih modern dan fleksibel.
     *
     * Kolom baru:
     * - sender_id              : Pengirim notifikasi (FK ke users)
     * - type                   : Kategori notifikasi (contoh: offer.sent, submission.uploaded)
     * - workspace_id           : Relasi ke workspace (nullable)
     * - project_id             : Relasi ke project (nullable)
     * - payment_id             : Relasi ke payment (nullable)
     * - company_account_request_id : Relasi ke company request (nullable)
     * - data                   : JSON untuk menyimpan konteks tambahan (redirect URL, dll)
     * - read_at                : Timestamp kapan notifikasi dibaca
     *
     * Indeks:
     * - index [user_id, is_read] : Optimasi query notifikasi user & unread count
     * - index type               : Optimasi filter berdasarkan type
     * - index created_at         : Optimasi pengurutan berdasarkan waktu
     */
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // ─── KOLOM BARU ───────────────────────────────────────
            $table->foreignId('sender_id')
                ->nullable()
                ->after('user_id')
                ->constrained('users')
                ->nullOnDelete();

            $table->string('type')
                ->nullable()
                ->after('penawaran_id')
                ->comment('Kategori notifikasi: offer.sent, offer.accepted, workspace.message, dll');

            $table->foreignId('workspace_id')
                ->nullable()
                ->after('type')
                ->constrained('project_workspaces')
                ->nullOnDelete();

            $table->foreignId('project_id')
                ->nullable()
                ->after('workspace_id')
                ->constrained('projects')
                ->nullOnDelete();

            $table->foreignId('payment_id')
                ->nullable()
                ->after('project_id')
                ->constrained('payments')
                ->nullOnDelete();

            $table->foreignId('company_account_request_id')
                ->nullable()
                ->after('payment_id')
                ->constrained('company_account_requests')
                ->nullOnDelete();

            $table->json('data')
                ->nullable()
                ->after('message')
                ->comment('Konteks tambahan seperti redirect URL, metadata, dll');

            $table->timestamp('read_at')
                ->nullable()
                ->after('is_read')
                ->comment('Waktu notifikasi dibaca');

            // ─── INDEKS ───────────────────────────────────────────
            $table->index(['user_id', 'is_read'], 'notifications_user_read_index');
            $table->index('type', 'notifications_type_index');
            $table->index('created_at', 'notifications_created_at_index');
        });
    }

    /**
     * Reverse migration: hapus semua kolom dan indeks yang ditambahkan.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Hapus indeks
            $table->dropIndex('notifications_user_read_index');
            $table->dropIndex('notifications_type_index');
            $table->dropIndex('notifications_created_at_index');

            // Hapus foreign keys
            $table->dropForeign(['sender_id']);
            $table->dropForeign(['workspace_id']);
            $table->dropForeign(['project_id']);
            $table->dropForeign(['payment_id']);
            $table->dropForeign(['company_account_request_id']);

            // Hapus kolom
            $table->dropColumn([
                'sender_id',
                'type',
                'workspace_id',
                'project_id',
                'payment_id',
                'company_account_request_id',
                'data',
                'read_at',
            ]);
        });
    }
};

