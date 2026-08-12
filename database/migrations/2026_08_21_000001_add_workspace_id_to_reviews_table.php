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
        Schema::table('reviews', function (Blueprint $table) {
            // Cek terlebih dahulu apakah kolom workspace_id belum ada di tabel reviews
            if (!Schema::hasColumn('reviews', 'workspace_id')) {
                $table->unsignedBigInteger('workspace_id')->after('id');
            }
        });
    }

    /** 
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Cek apakah kolom workspace_id ada sebelum menghapusnya
            if (Schema::hasColumn('reviews', 'workspace_id')) {
                $table->dropColumn('workspace_id');
            }
        });
    }
};