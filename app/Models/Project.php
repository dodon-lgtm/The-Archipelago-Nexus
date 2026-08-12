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
        'archive_status',
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

/**
     * Apakah project masih menerima penawaran baru?
     *
     * Source of truth (backend):
     * - archive_status harus 'active' (belum diarsip/nonaktif).
     * - Status harus 'Open' (belum ditutup oleh Company).
     * - Belum memiliki Workspace (freelancer belum dipilih / pekerjaan belum berjalan).
     *
     * Project 'Closed' TIDAK otomatis dianggap selesai.
     * 'Selesai' hanya ditentukan oleh Workspace.status === 'Selesai'.
     */
    public function acceptsOffers(): bool
    {
        return $this->archive_status === 'active'
            && $this->status === 'Open'
            && !$this->workspace()->exists();
    }

    /**
     * Apakah project sudah benar-benar selesai?
     * Sumber kebenaran: Workspace dengan status 'Selesai'.
     * Project 'Closed' saja BUKAN berarti selesai.
     */
    public function isCompleted(): bool
    {
        return $this->workspace()
            ->where('status', 'Selesai')
            ->exists();
    }

    /**
     * Apakah project sedang aktif (belum diarsip/nonaktif)?
     */
    public function isArchived(): bool
    {
        return $this->archive_status === 'archived';
    }

    /**
     * Apakah project dalam keadaan nonaktif?
     */
    public function isInactive(): bool
    {
        return $this->archive_status === 'inactive';
    }

    /**
     * Arsipkan project (hanya mengubah archive_status, TIDAK mengubah Workspace).
     */
    public function archive(): bool
    {
        return $this->update(['archive_status' => 'archived']);
    }

    /**
     * Aktifkan kembali project secara administratif.
     *
     * HANYA mengubah archive_status menjadi 'active'.
     * TIDAK menghidupkan kembali Workspace/kontrak lama.
     */
    public function activate(): bool
    {
        return $this->update(['archive_status' => 'active']);
    }

    /**
     * Nonaktifkan project (tidak menerima penawaran baru).
     */
    public function deactivate(): bool
    {
        return $this->update(['archive_status' => 'inactive']);
    }
}
