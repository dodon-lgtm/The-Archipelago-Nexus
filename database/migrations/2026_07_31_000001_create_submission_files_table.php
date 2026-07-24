<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submission_files', function (Blueprint $table) {
            $table->id();

            $table->foreignId('submission_id')
                ->constrained('project_submissions')
                ->cascadeOnDelete();

            $table->string('file_name');
            $table->string('file_path');
            $table->bigInteger('file_size')->comment('Size in bytes');
            $table->string('mime_type');

            $table->timestamps();

            $table->index('submission_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submission_files');
    }
};

