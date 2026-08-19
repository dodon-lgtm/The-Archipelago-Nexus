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
    Schema::table('projects', function (Blueprint $table) {
        $table->string('archive_status')->default('active')->after('status');
    });
}

public function down(): void
{
    
}
};
