<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saved_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('freelancer_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('project_id')
                ->constrained('projects')
                ->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['freelancer_id', 'project_id'], 'saved_projects_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saved_projects');
    }
};

