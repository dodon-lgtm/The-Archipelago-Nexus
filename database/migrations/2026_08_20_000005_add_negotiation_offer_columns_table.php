<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Sempurnakan skema negosiasi:
     * - offered_price -> proposed_price (harga baru dari Perusahaan)
     * - tambah proposed_days (estimasi pengerjaan baru dari Perusahaan)
     * - tambah status (pending | accepted | rejected)
     */
    public function up(): void
    {
        Schema::table('negotiation_messages', function (Blueprint $table) {
            $table->renameColumn('offered_price', 'proposed_price');
            $table->integer('proposed_days')->nullable()->after('proposed_price');
            $table->enum('status', ['pending', 'accepted', 'rejected'])
                ->default('pending')
                ->after('proposed_days');
        });
    }

    public function down(): void
    {
        Schema::table('negotiation_messages', function (Blueprint $table) {
            $table->dropColumn(['status', 'proposed_days']);
            $table->renameColumn('proposed_price', 'offered_price');
        });
    }
};