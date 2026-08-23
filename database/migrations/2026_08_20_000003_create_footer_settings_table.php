<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel pengaturan footer (single-row) yang dapat dikelola dari admin.
     */
    public function up(): void
    {
        Schema::create('footer_settings', function (Blueprint $table) {
            $table->id();
            $table->longText('privacy_policy_content')->nullable();   // Isi Kebijakan Privasi
            $table->longText('terms_conditions_content')->nullable(); // Isi Syarat & Ketentuan
            $table->string('support_email', 191)->nullable();          // Email dukungan
            $table->text('about_text')->nullable();                    // Deskripsi singkat perusahaan
            $table->string('copyright_text', 191)->nullable();         // Teks hak cipta footer
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('footer_settings');
    }
};