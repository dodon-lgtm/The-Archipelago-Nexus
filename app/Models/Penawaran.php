<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penawaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'freelancer_id',
        'harga_penawaran',
        'estimasi_hari',
        'pesan',
        'proposal',
        'status',
        'selected_at',
    ];

    protected function casts(): array
    {
        return [
            'selected_at' => 'datetime',
        ];
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function freelancer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    /**
     * Apakah negosiasi untuk penawaran ini sudah terkunci (deal)?
     *
     * Terkunci jika:
     * - status penawaran bukan 'Menunggu' (sudah Diterima/Ditolak), ATAU
     * - project sudah memiliki workspace (freelancer sudah dipilih).
     * Data negosiasi tetap ada, hanya composer yang dikunci → read-only.
     */
    public function isNegotiationLocked(): bool
    {
        if ($this->status !== 'Menunggu') {
            return true;
        }

        // Jika relasi project sudah di-load, gunakan collection check tanpa query tambahan
        if ($this->relationLoaded('project') && $this->project) {
            // Cek via exists query tetap aman meski workspace belum di-eager-load
            return (bool) $this->project->workspace()->exists();
        }

        // Fallback: cek langsung via query project_id
        return (bool) \App\Models\Workspace::where('project_id', $this->project_id)->exists();
    }

    /**
     * Ringkasan deal untuk ditampilkan saat negosiasi terkunci.
     */
    public function dealSummary(): ?array
    {
        if (!$this->isNegotiationLocked()) {
            return null;
        }

        $workspace = \App\Models\Workspace::where('project_id', $this->project_id)->first();

        return [
            'status'         => $this->status,
            'is_winner'      => $this->status === 'Diterima',
            'harga_deal'     => (float) $this->harga_penawaran,
            'estimasi_hari'  => (int) $this->estimasi_hari,
            'selected_at'    => $this->selected_at?->format('d M Y H:i'),
            'freelancer_id'  => (int) $this->freelancer_id,
            'workspace_id'   => $workspace?->id,
        ];
    }
}