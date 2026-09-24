<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add slug column if not exists (for SQLite test database)
        if (!Schema::hasColumn('policies', 'slug')) {
            Schema::table('policies', function (Blueprint $table) {
                $table->string('slug', 50)->nullable()->after('key');
            });
        }

        // Update existing records with slug values based on key
        DB::table('policies')->where('key', 'privacy')->update(['slug' => 'privacy-policy']);
        DB::table('policies')->where('key', 'usage')->update(['slug' => 'usage-policy']);
        DB::table('policies')->where('key', 'terms')->update(['slug' => 'terms-conditions']);

        // Add unique constraint - use try/catch for SQLite compatibility
        try {
            Schema::table('policies', function (Blueprint $table) {
                $table->unique('slug');
            });
        } catch (\Exception $e) {
            // Unique constraint might already exist
        }

        // Make slug non-nullable for SQLite (if needed)
        if (config('database.default') === 'sqlite') {
            try {
                Schema::table('policies', function (Blueprint $table) {
                    $table->string('slug', 50)->nullable(false)->change();
                });
            } catch (\Exception $e) {
                // Ignore if not supported
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('policies', 'slug')) {
            Schema::table('policies', function (Blueprint $table) {
                $table->dropUnique(['slug']);
                $table->dropColumn('slug');
            });
        }
    }
};
