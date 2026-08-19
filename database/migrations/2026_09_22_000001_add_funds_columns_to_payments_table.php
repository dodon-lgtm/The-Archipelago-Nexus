<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * ADDITIF — State dana tertahan (escrow) untuk tabel payments.
     *
     * TIDAK mengubah makna payments.status (pending/waiting_verification/paid/rejected).
     * funds_status hanya melacak posisi dana SETELAH pembayaran sukses:
     *   not_applicable / held / disputed / released / refunded / released_partial / refunded_partial
     *
     * Payment paid ≠ dana diterima freelancer. Dana baru direlease setelah proyek
     * Selesai/disetujui, atau setelah Admin memutuskan dispute (release/refund/split).
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('funds_status', 32)
                ->default('not_applicable')
                ->after('status')
                ->comment('Posisi dana: not_applicable|held|disputed|released|refunded|released_partial|refunded_partial');

            $table->timestamp('held_at')->nullable()->after('funds_status');
            $table->timestamp('released_at')->nullable()->after('held_at');
            $table->timestamp('refunded_at')->nullable()->after('released_at');

            $table->decimal('released_amount', 15, 2)->default(0)->after('refunded_at')
                ->comment('Nominal dana yang sudah dirilis ke freelancer');
            $table->decimal('refunded_amount', 15, 2)->default(0)->after('released_amount')
                ->comment('Nominal dana yang dikembalikan ke company');

            $table->string('dispute_reference')->nullable()->after('refunded_amount')
                ->comment('Referensi laporan/dispute yang menahan atau meresolve dana');

            $table->index('funds_status', 'payments_funds_status_index');
        });

        // ─── BACKFILL data lama (tanpa menghapus / mengubah nominal) ───
        // Payment lama berstatus paid diklasifikasikan aman:
        //   - Workspace belum Selesai  -> funds_status = held
        //   - Workspace sudah Selesai  -> funds_status = released
        $paidPending = DB::table('payments')
            ->join('project_workspaces', 'project_workspaces.id', '=', 'payments.workspace_id')
            ->where('payments.status', 'paid')
            ->where('project_workspaces.status', '!=', 'Selesai')
            ->select('payments.id', 'payments.verified_at')
            ->get();

        foreach ($paidPending as $row) {
            DB::table('payments')
                ->where('id', $row->id)
                ->update([
                    'funds_status' => 'held',
                    'held_at' => $row->verified_at ?? now(),
                ]);
        }

        $paidDone = DB::table('payments')
            ->join('project_workspaces', 'project_workspaces.id', '=', 'payments.workspace_id')
            ->where('payments.status', 'paid')
            ->where('project_workspaces.status', 'Selesai')
            ->select('payments.id', 'payments.verified_at', 'payments.freelancer_receive')
            ->get();

        foreach ($paidDone as $row) {
            DB::table('payments')
                ->where('id', $row->id)
                ->update([
                    'funds_status' => 'released',
                    'released_at' => $row->verified_at ?? now(),
                    'released_amount' => $row->freelancer_receive,
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex('payments_funds_status_index');
            $table->dropColumn([
                'funds_status',
                'held_at',
                'released_at',
                'refunded_at',
                'released_amount',
                'refunded_amount',
                'dispute_reference',
            ]);
        });
    }
};
