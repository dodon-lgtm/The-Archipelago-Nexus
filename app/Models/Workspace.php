<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workspace extends Model
{
    protected $table = 'project_workspaces';

    protected $fillable = [
        'project_id',
        'company_id',
        'freelancer_id',
        'status',
        'stages',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'stages' => 'array',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    public function freelancer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'workspace_id');
    }

    public function progressHistories(): HasMany
    {
        return $this->hasMany(ProgressHistory::class, 'workspace_id');
    }

    public function latestProgress()
    {
        return $this->hasOne(ProgressHistory::class, 'workspace_id')
            ->latestOfMany();
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(ProjectSubmission::class, 'workspace_id')
            ->latest();
    }

    /**
     * Daftar stage custom terurut untuk workspace ini (source of truth).
     * Dikembalikan selalu sebagai array string, dengan fallback aman bila kosong/null.
     */
    public function stageList(): array
    {
        $stages = $this->stages;
        if (!is_array($stages) || count($stages) === 0) {
            return ['Analisis Kebutuhan'];
        }

        return array_values(array_map('strval', $stages));
    }

    /**
     * Total jumlah stage di workspace ini.
     */
    public function totalStages(): int
    {
        return count($this->stageList());
    }

    /**
     * Hitung persentase progres dari urutan stage (1-based) secara server-side.
     * Formula: round(current_order / total_stages * 100). Stage terakhir = 100%.
     */
    public function calculateProgressForStage(int $stageOrder): int
    {
        $total = $this->totalStages();
        if ($total <= 0) {
            return 0;
        }

        $clampedOrder = max(1, min($stageOrder, $total));

        // Stage terakhir selalu 100%
        if ($clampedOrder >= $total) {
            return 100;
        }

        return (int) round(($clampedOrder / $total) * 100);
    }

    /**
     * Persentase progres saat ini berdasarkan stage aktif terakhir (server-side).
     */
    public function currentProgress(): int
    {
        $latest = $this->latestProgress;
        $order = $latest?->stage_order ? (int) $latest->stage_order : 0;
        if ($order <= 0) {
            return 0;
        }
        return $this->calculateProgressForStage($order);
    }

    /**
     * Nama stage aktif saat ini.
     */
    public function currentStage(): ?string
    {
        return $this->latestProgress?->stage;
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'workspace_id');
    }

    public function walletLedgers(): HasMany
    {
        return $this->hasMany(WalletLedger::class, 'workspace_id');
    }

    public function rating()
    {
        return $this->hasOne(Review::class, 'workspace_id');
    }
}

