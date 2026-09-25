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
        'overdue_previous_status',
        'progress',
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
     * Daftar stage custom terurut untuk workspace ini (source of truth),
     * dinormalisasi menjadi item bertipe object (NON-LINEAR / FLEKSIBEL):
     *
     *    [
     *      'name'        => string,
     *      'description' => ?string,
     *      'created_by'  => ?int,
     *      'is_completed'=> bool,
     *      'note'        => ?string  // catatan pengerjaan tahap ini (dari modal)
     *      'completed_at'=> ?string  // ISO datetime
     *      'completed_by'=> ?int
     *    ]
     *
     * Entry lama yang masih berbentuk string polos atau tanpa flag
     * is_completed otomatis dianggap belum selesai (backward-compatible).
     */
    public function stageItems(): array
    {
        $stages = $this->stages;

        if (!is_array($stages) || count($stages) === 0) {
            return [[
                'name' => 'Analisis Kebutuhan',
                'description' => null,
                'created_by' => $this->freelancer_id ? (int) $this->freelancer_id : null,
                'is_completed' => false,
                'note' => null,
                'completed_at' => null,
                'completed_by' => null,
            ]];
        }

        $defaultCreator = $this->freelancer_id ? (int) $this->freelancer_id : null;
        $items = [];

        foreach (array_values($stages) as $entry) {
            if (is_array($entry)) {
                $name = trim((string) ($entry['name'] ?? ''));
                if ($name === '') {
                    continue;
                }
                $items[] = [
                    'name' => $name,
                    'description' => isset($entry['description']) && $entry['description'] !== ''
                        ? (string) $entry['description']
                        : null,
                    'created_by' => isset($entry['created_by']) && $entry['created_by'] !== null
                        ? (int) $entry['created_by']
                        : $defaultCreator,
                    'is_completed' => (bool) ($entry['is_completed'] ?? false),
                    'note' => isset($entry['note']) && $entry['note'] !== '' ? (string) $entry['note'] : null,
                    'completed_at' => $entry['completed_at'] ?? null,
                    'completed_by' => isset($entry['completed_by']) && $entry['completed_by'] !== null ? (int) $entry['completed_by'] : null,
                ];
                continue;
            }

            $name = trim((string) $entry);
            if ($name === '') {
                continue;
            }
            $items[] = [
                'name' => $name,
                'description' => null,
                'created_by' => $defaultCreator,
                'is_completed' => false,
                'note' => null,
                'completed_at' => null,
                'completed_by' => null,
            ];
        }

        if (count($items) === 0) {
            return [[
                'name' => 'Analisis Kebutuhan',
                'description' => null,
                'created_by' => $defaultCreator,
                'is_completed' => false,
                'note' => null,
                'completed_at' => null,
                'completed_by' => null,
            ]];
        }

        return $items;
    }

    /**
     * Daftar nama stage terurut (array of string)
     * — dipakai endpoint progress, JS, dan perhitungan persentase.
     */
    public function stageList(): array
    {
        return array_values(array_map(fn (array $item) => $item['name'], $this->stageItems()));
    }

    /**
     * Total jumlah stage di workspace ini.
     */
    public function totalStages(): int
    {
        return count($this->stageList());
    }

    /**
     * Jumlah tahap yang sudah berstatus Selesai (non-linear).
     */
    public function completedStagesCount(): int
    {
        return collect($this->stageItems())->where('is_completed', true)->count();
    }

    /**
     * Hitung progress fleksibel / non-linear:
     * Progress (%) = (Jumlah Tahap Selesai / Total Semua Tahap) * 100
     */
    public function calculateFlexibleProgress(): int
    {
        $total = $this->totalStages();
        if ($total <= 0) {
            return 0;
        }
        $completed = $this->completedStagesCount();
        return (int) round(($completed / $total) * 100);
    }

    /**
     * Persentase progres saat ini (NON-LINEAR).
     * Jika ada tahap yang sudah ditandai selesai, gunakan rumus fleksibel.
     * Jika belum ada yang selesai tetapi ada riwayat linear lama, fallback ke legacy.
     */
    public function currentProgress(): int
    {
        $total = $this->totalStages();
        if ($total <= 0) {
            return 0;
        }
        $completed = $this->completedStagesCount();
        if ($completed > 0) {
            return $this->calculateFlexibleProgress();
        }

        // Fallback legacy untuk workspace lama yang belum migrasi ke flag is_completed
        $latest = $this->relationLoaded('latestProgress') ? $this->latestProgress : $this->latestProgress()->first();
        $order = $latest?->stage_order ? (int) $latest->stage_order : 0;
        if ($order > 0) {
            return (int) round(($order / $total) * 100);
        }

        // Belum ada progress sama sekali
        return 0;
    }

    /**
     * Nama stage aktif saat ini.
     */
    public function currentStage(): ?string
    {
        return $this->latestProgress?->stage;
    }

    /**
     * Accessor untuk active_stage_name - digunakan di view untuk menampilkan
     * nama tahap yang sedang aktif/terakhir dikerjakan.
     */
    public function getActiveStageNameAttribute()
    {
        return $this->currentStage();
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