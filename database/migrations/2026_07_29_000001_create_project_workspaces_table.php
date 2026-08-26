<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_workspaces', function (Blueprint $table) {
            $table->id();

            $table->foreignId('project_id')
                ->constrained('projects')
                ->cascadeOnDelete()
                ->unique();

            $table->foreignId('company_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('freelancer_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Superset final dari seluruh migrasi ALTER ENUM berikutnya
            // (2026_08_10 & 2026_09_11, MySQL-only) agar fresh install di
            // driver lain (mis. SQLite untuk test in-memory) memiliki
            // CHECK constraint yang sama lengkapnya.
            $table->enum('status', [
                'Sedang Dikerjakan',
                'Menunggu Review',
                'Menunggu Revisi',
                'Menunggu Pembayaran',
                'Menunggu Verifikasi Admin',
                'Selesai',
            ])->default('Sedang Dikerjakan');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_workspaces');
    }
};

