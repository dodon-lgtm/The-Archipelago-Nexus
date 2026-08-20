<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Riwayat negosiasi (chat) antara perusahaan dan freelancer
     * untuk sebuah penawaran/lamaran tertentu.
     */
    public function up(): void
    {
        Schema::create('negotiation_messages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('penawaran_id')
                ->constrained('penawarans')
                ->cascadeOnDelete();

            $table->foreignId('sender_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->enum('sender_type', ['company', 'freelancer']);

            $table->text('message');

            $table->decimal('offered_price', 15, 2)->nullable();

            $table->timestamps();

            $table->index(['penawaran_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('negotiation_messages');
    }
};