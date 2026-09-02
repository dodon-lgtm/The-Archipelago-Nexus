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
     * dinormalisasi menjadi item bertipe object:
     *
     *     [
     *       'name'         => string,
     *       'description'  => ?string,
     *       'created_by'   => ?int (users.id pembuat tahap),
     *       'is_completed' => bool (status ceklis; count-based progress),
     *     ]
     *
     * Entry lama yang masih berbentuk string polos otomatis dianggap
     * dibuat oleh freelancer workspace ini (backward-compatible, data aman),
     * dan `is_completed` di-default ke false bila flag belum pernah disimpan.
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
                    'is_completed' => !empty($entry['is_completed']),
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
            ];
        }

        if (count($items) === 0) {
            return [[
                'name' => 'Analisis Kebutuhan',
                'description' => null,
                'created_by' => $defaultCreator,
                'is_completed' => false,
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
     * Jumlah tahap yang sudah ditandai selesai (is_completed = true).
     * Ini adalah dasar perhitungan progress COUNTS-BASED (bukan nomor urut).
     */
    public function completedStageCount(): int
    {
        $completed = 0;
        foreach ($this->stageItems() as $item) {
            if (!empty($item['is_completed'])) {
                $completed++;
            }
        }

        return $completed;
    }

    /**
     * Hitung persentase progres murni dari JUMLAH CEKLIS yang selesai.
     *
     * Formula: round(COUNT(tahap_selesai) / COUNT(semua_tahap) * 100)
     *
     * Contoh: 7 tahap total, freelance mencentang tahap 1, 2, 3, dan 7
     * (4 tahap selesai) → (4 / 7) * 100 = 57%.
     *
     * Tahap lain yang belum dicentang TIDAK ikut dianggap selesai dan
     * TIDAK memengaruhi nilai ini — progres HANYA memperhitungkan jumlah
     * item yang berstatus selesai.
     */
    public function calculateProgressCountBased(): int
    {
        $total = $this->totalStages();
        if ($total <= 0) {
            return 0;
        }

        return (int) round(($this->completedStageCount() / $total) * 100);
    }

    /**
     * Hitung persentase progres.
     *
     * @deprecated Pakai `currentProgress()` / `calculateProgressCountBased()`.
     *             Param `$stageOrder` TIDAK dipakai lagi — progres murni
     *             dihitung dari jumlah tahap selesai, bukan nomor urut.
     */
    public function calculateProgressForStage(int $stageOrder): int
    {
        return $this->calculateProgressCountBased();
    }

    /**
     * Persentase progres saat ini (server-side, murni count-based).
     */
    public function currentProgress(): int
    {
        return $this->calculateProgressCountBased();
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