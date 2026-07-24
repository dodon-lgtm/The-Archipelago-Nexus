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

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->comment('Pelapor');

            $table->foreignId('project_id')
                ->nullable()
                ->constrained('projects')
                ->nullOnDelete()
                ->comment('Proyek terkait laporan (opsional)');

            $table->string('category')
                ->comment('Kategori laporan: penipuan, pelanggaran, spam, lainnya');

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

            $table->foreignId('handled_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('Admin yang menangani');

            $table->timestamp('handled_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};

