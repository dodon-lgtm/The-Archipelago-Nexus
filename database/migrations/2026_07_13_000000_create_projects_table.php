<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->foreignId('category_id')
                  ->nullable()
                  ->constrained('categories')
                  ->nullOnDelete();

            $table->string('project_name');

            $table->text('project_description')->nullable();

            // Tambahan
            $table->bigInteger('budget')->nullable();

            $table->date('deadline')->nullable();

            $table->string('skills')->nullable();

            $table->string('image')->nullable();

            $table->string('attachment')->nullable();

            $table->enum('status', ['Open', 'Closed'])
                  ->default('Open');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};