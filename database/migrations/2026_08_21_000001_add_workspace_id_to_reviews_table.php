<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Menambahkan kolom workspace_id ke tabel reviews.
     * Kolom ini sudah ada di file migration create_reviews_table,
     * tetapi migration tersebut sudah dijalankan sebelum kolom ini ditambahkan,
     * sehingga kolom tidak pernah terbuat di database.
     *
     * Relasi Workspace->rating() membutuhkan kolom ini.
     */
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->unsignedBigInteger('workspace_id')->after('id');
        });
    }

    /** 
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('workspace_id');
        });
    }
};
