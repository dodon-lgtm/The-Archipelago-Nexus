<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Report V2 - Menambahkan kolom workspace_id ke tabel reports.
 *
 * Memungkinkan laporan antar-user yang dilakukan secara kontekstual
 * dari halaman workspace (Company melaporkan Freelancer, atau sebaliknya).
 *
 * Kolom nullable + nullOnDelete agar penghapusan workspace tidak menghapus laporan.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->foreignId('workspace_id')
                ->nullable()
                ->after('project_id')
                ->constrained('project_workspaces')
                ->nullOnDelete()
                ->comment('Workspace terkait laporan (opsional)');
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropConstrainedForeignId('workspace_id');
        });
    }
};
