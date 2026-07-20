<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penawarans', function (Blueprint $table) {

            $table->id();

            $table->foreignId('project_id')
                ->constrained('projects')
                ->cascadeOnDelete();

            $table->foreignId('freelancer_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->decimal('harga_penawaran',15,2);

            $table->integer('estimasi_hari');

            $table->text('pesan');

            $table->string('proposal')->nullable();

            $table->enum('status',[
                'Menunggu',
                'Diterima',
                'Ditolak'
            ])->default('Menunggu');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penawarans');
    }
};