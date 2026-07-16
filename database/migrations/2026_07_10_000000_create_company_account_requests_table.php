<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('company_account_requests', function (Blueprint $table) {
            $table->id();

            $table->string('company_name');
            $table->string('contact_person');
            $table->string('company_email');
            $table->string('company_phone');
            $table->text('company_address');
            $table->text('company_description')->nullable();

            $table->string('request_status')->default('menunggu');
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->text('note')->nullable();

            $table->timestamps();

            $table->index('company_email');
            $table->foreign('reviewed_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_account_requests');
    }
};

