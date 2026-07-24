<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_submissions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('workspace_id')
                ->constrained('project_workspaces')
                ->cascadeOnDelete();

            $table->foreignId('submitted_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file');

            $table->text('company_note')->nullable()
                ->comment('Catatan dari perusahaan saat menerima/meminta revisi');

            $table->enum('status', [
                'pending',
                'accepted',
                'revision',
            ])->default('pending');

            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            // Index for faster queries
            $table->index('workspace_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_submissions');
    }
};

