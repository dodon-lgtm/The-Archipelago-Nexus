<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus kolom file yang sudah tidak digunakan dari project_submissions
        Schema::table('project_submissions', function (Blueprint $table) {
            $table->dropColumn('file');
        });

        // Tambah kolom category ke submission_files
        Schema::table('submission_files', function (Blueprint $table) {
            $table->enum('category', [
                'image',
                'video',
                'document',
                'archive',
            ])->after('mime_type')->nullable()->comment('Kategori file: image, video, document, archive');
        });
    }

    public function down(): void
    {
        Schema::table('project_submissions', function (Blueprint $table) {
            $table->string('file')->after('description');
        });

        Schema::table('submission_files', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};


