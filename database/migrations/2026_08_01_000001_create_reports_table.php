<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('reporter_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->comment('Pelapor');

            $table->foreignId('reported_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('User yang dilaporkan (opsional)');

            $table->foreignId('project_id')
                ->nullable()
                ->constrained('projects')
                ->nullOnDelete()
                ->comment('Proyek terkait laporan (opsional)');

            $table->foreignId('penawaran_id')
                ->nullable()
                ->constrained('penawarans')
                ->nullOnDelete()
                ->comment('Penawaran terkait laporan (opsional)');

            $table->string('subject')
                ->comment('Judul/subjek laporan');

            $table->text('description')
                ->comment('Deskripsi laporan');

            $table->enum('status', [
                'menunggu',
                'diproses',
                'selesai',
                'ditolak',
            ])->default('menunggu')
                ->comment('Status laporan');

            $table->text('admin_note')->nullable()
                ->comment('Catatan admin');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};

