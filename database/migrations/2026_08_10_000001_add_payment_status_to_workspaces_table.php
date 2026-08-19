<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
        public function up(): void
    {
        // ENUM modification via RAW "ALTER TABLE ... MODIFY COLUMN" hanya didukung
        // olehMySQL. Pada driver SQLite (lingkungan test in-memory) statement ini
        // akan gagal; kolom 'status' yang diciptakan migration create tetap dipakai
        // sebagai string, sehingga perilaku aplikasi tidak berubah.
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE project_workspaces MODIFY COLUMN status ENUM(
                'Sedang Dikerjakan',
                'Menunggu Revisi',
                'Menunggu Pembayaran',
                'Menunggu Verifikasi Admin',
                'Selesai'
            ) DEFAULT 'Sedang Dikerjakan'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE project_workspaces MODIFY COLUMN status ENUM(
                'Sedang Dikerjakan',
                'Menunggu Revisi',
                'Selesai'
            ) DEFAULT 'Sedang Dikerjakan'");
        }
    }
};

