<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Project extends Model
{
    use HasFactory;

    // ─── STATUS PROYEK (satu-satunya standar value di database) ─────
    public const STATUS_OPEN     = 'open';
    public const STATUS_CLOSED   = 'closed';
    public const STATUS_ARCHIVED = 'archived';

    public const STATUSES = [
        self::STATUS_OPEN,
        self::STATUS_CLOSED,
        self::STATUS_ARCHIVED,
    ];

    protected $fillable = [
        'user_id',
        'category_id',
        'project_name',
        'project_description',
        'budget',
        'deadline',
        'skills',
        'image',
        'attachment',
        'status',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function penawarans(): HasMany
    {
        return $this->hasMany(Penawaran::class);
    }

    public function savedByFreelancers(): HasMany
    {
        return $this->hasMany(SavedProject::class, 'project_id');
    }

    public function workspace(): HasOne
    {
        return $this->hasOne(Workspace::class, 'project_id');
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    // ─── STATUS HELPERS ────────────────────────────────────────────

    public function isOpen(): bool
    {
        return $this->status === self::STATUS_OPEN;
    }

    public function isClosed(): bool
    {
        return $this->status === self::STATUS_CLOSED;
    }

    /**
     * Apakah project masuk arsip?
     */
    public function isArchived(): bool
    {
        return $this->status === self::STATUS_ARCHIVED;
    }

    /**
     * Label bahasa Indonesia untuk ditampilkan di UI.
     * Value database TETAP: open / closed / archived.
     */
    public static function statusLabel(?string $status): string
    {
        return match ($status) {
            self::STATUS_OPEN     => 'Open',
            self::STATUS_CLOSED   => 'Tutup',
            self::STATUS_ARCHIVED => 'Arsip',
            default               => 'Open',
        };
    }

    /**
     * Apakah project masih menerima penawaran baru?
     *
     * Source of truth (backend):
     * - Status harus 'open' (belum ditutup oleh Company / belum diarsip).
     * - Belum memiliki Workspace (freelancer belum dipilih / pekerjaan belum berjalan).
     *
     * Project 'closed' TIDAK otomatis dianggap selesai.
     * 'Selesai' hanya ditentukan oleh Workspace.status === 'Selesai'.
     */
    public function acceptsOffers(): bool
    {
        return $this->status === self::STATUS_OPEN
            && !$this->workspace()->exists();
    }

    /**
     * Apakah project sudah benar-benar selesai?
     * Sumber kebenaran: Workspace dengan status 'Selesai'.
     * Project 'closed' saja BUKAN berarti selesai.
     */
    public function isCompleted(): bool
    {
        return $this->workspace()
            ->where('status', 'Selesai')
            ->exists();
    }

    /**
     * Arsipkan project (hanya mengubah status menjadi 'archived', TIDAK mengubah Workspace).
     */
    public function archive(): bool
    {
        return $this->update(['status' => self::STATUS_ARCHIVED]);
    }

    /**
     * Aktifkan kembali project secara administratif (status menjadi 'open').
     *
     * HANYA mengubah status menjadi 'open'.
     * TIDAK menghidupkan kembali Workspace/kontrak lama.
     */
    public function activate(): bool
    {
        return $this->update(['status' => self::STATUS_OPEN]);
    }

    /**
     * Tutup / nonaktifkan project (status menjadi 'closed', tidak menerima penawaran baru).
     */
    public function deactivate(): bool
    {
        return $this->update(['status' => self::STATUS_CLOSED]);
    }
}
