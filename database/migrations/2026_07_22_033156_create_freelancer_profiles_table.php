<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('freelancer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->text('bio')->nullable();
            $table->string('photo')->nullable();
            $table->string('skills')->nullable();
            $table->text('experience')->nullable();
            $table->string('portfolio_link')->nullable();
            $table->string('location')->nullable();
            $table->string('cv')->nullable();
            $table->decimal('hourly_rate', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('freelancer_profiles');
    }
};
