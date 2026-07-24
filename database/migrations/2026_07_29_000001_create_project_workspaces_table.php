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

            $table->enum('status', [
                'Sedang Dikerjakan',
                'Menunggu Revisi',
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

