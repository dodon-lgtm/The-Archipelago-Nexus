<?php

namespace Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel kebijakan (privacy + terms) yang dapat dikelola dari admin.
     * Isi ditampilkan secara dinamis pada modal login.
     */
    public function up(): void
    {
        Schema::create('policies', function (Blueprint $table) {
            $table->id();
            $table->string('key', 50)->unique();   // privacy | usage
            $table->string('title', 191);         // Judul dokumen
            $table->text('content');               // Isi dokumen (HTML-safety di Blade via nl2br+e)
            $table->boolean('is_active')->default(true); // Tampilkan di login?
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('policies');
    }
};
