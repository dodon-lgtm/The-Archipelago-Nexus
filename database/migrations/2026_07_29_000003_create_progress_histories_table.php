<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progress_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('workspace_id')
                ->constrained('project_workspaces')
                ->cascadeOnDelete();

            $table->enum('stage', [
                'Dipilih',
                'Analisis',
                'Desain',
                'Backend',
                'Frontend',
                'Testing',
                'Revisi',
                'Selesai',
            ]);

            $table->integer('progress')
                ->default(0)
                ->comment('Persentase progress 0-100');

            $table->text('description')->nullable();

            $table->foreignId('updated_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progress_histories');
    }
};

