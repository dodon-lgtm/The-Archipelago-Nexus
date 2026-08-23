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
     * Daftar stage custom terurut untuk workspace ini (source of truth),
     * dinormalisasi menjadi item bertipe object:
     *
     *     [
     *       'name'        => string,
     *       'description' => ?string,
     *       'created_by'  => ?int (users.id pembuat tahap),
     *     ]
     *
     * Entry lama yang masih berbentuk string polos otomatis dianggap
     * dibuat oleh freelancer workspace ini (backward-compatible, data aman).
     */
    public function stageItems(): array
    {
        $stages = $this->stages;

        if (!is_array($stages) || count($stages) === 0) {
            return [[
                'name' => 'Analisis Kebutuhan',
                'description' => null,
                'created_by' => $this->freelancer_id ? (int) $this->freelancer_id : null,
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
            ];
        }

        if (count($items) === 0) {
            return [[
                'name' => 'Analisis Kebutuhan',
                'description' => null,
                'created_by' => $defaultCreator,
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
     * Hitung persentase progres dari urutan stage (1-based) secara server-side.
     * Formula: round(current_order / total_stages * 100). Stage terakhir = 100%.
     */
    public function calculateProgressForStage(int $stageOrder): int
    {
        $total = $this->totalStages();
        if ($total <= 0 || $stageOrder <= 0) {
            // stage_order <= 0 artinya pekerjaan belum dimulai → 0% (bukan 100%).
            return 0;
        }

        $clampedOrder = min($stageOrder, $total);

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