<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First, modify the enum to add new statuses
        DB::statement("ALTER TABLE project_workspaces MODIFY COLUMN status ENUM(
            'Sedang Dikerjakan',
            'Menunggu Revisi',
            'Menunggu Pembayaran',
            'Menunggu Verifikasi Admin',
            'Selesai'
        ) DEFAULT 'Sedang Dikerjakan'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE project_workspaces MODIFY COLUMN status ENUM(
            'Sedang Dikerjakan',
            'Menunggu Revisi',
            'Selesai'
        ) DEFAULT 'Sedang Dikerjakan'");
    }
};

