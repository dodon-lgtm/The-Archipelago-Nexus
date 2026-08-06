<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    use HasFactory;

    // ─── STATUS REPORT V3 (5 status) ────────────────────────────
    public const STATUS_MENUNGGU      = 'menunggu';
    public const STATUS_DITINJAU      = 'ditinjau';
    public const STATUS_MENUNGGU_BUKTI = 'menunggu-bukti';
    public const STATUS_SELESAI       = 'selesai';
    public const STATUS_DITOLAK       = 'ditolak';

    // Alias backward-compat untuk data V2 ('diproses').
    public const STATUS_DIPROSES = 'diproses';

    public const STATUSES = [
        self::STATUS_MENUNGGU,
        self::STATUS_DITINJAU,
        self::STATUS_MENUNGGU_BUKTI,
        self::STATUS_SELESAI,
        self::STATUS_DITOLAK,
    ];

    // Status yang masih "aktif" (memblokir laporan duplikat).
    public const ACTIVE_STATUSES = [
        self::STATUS_MENUNGGU,
        self::STATUS_DITINJAU,
        self::STATUS_MENUNGGU_BUKTI,
    ];

    // Status yang dianggap "selesai" (mengizinkan laporan baru untuk kasus sama).
    public const RESOLVED_STATUSES = [
        self::STATUS_SELESAI,
        self::STATUS_DITOLAK,
    ];

    // ─── TARGET REPORT V3 (backend source of truth, bukan dari form) ──
    public const TARGET_PROJECT    = 'project';
    public const TARGET_COMPANY    = 'company';
    public const TARGET_FREELANCER = 'freelancer';
    public const TARGET_WEBSITE    = 'website';

    public const TARGETS = [
        self::TARGET_PROJECT,
        self::TARGET_COMPANY,
        self::TARGET_FREELANCER,
        self::TARGET_WEBSITE,
    ];

    // ─── KATEGORI (V2) ───────────────────────────────────────────
    public const CATEGORY_UMUM                 = 'umum';
    public const CATEGORY_PENIPUAN             = 'penipuan';
    public const CATEGORY_KONTEN_TIDAK_PANTAS  = 'konten-tidak-pantas';
    public const CATEGORY_PERILAKU_TIDAK_PROFESIONAL = 'perilaku-tidak-profesional';
    public const CATEGORY_TIDAK_MEMBAYAR       = 'tidak-membayar';
    public const CATEGORY_HASIL_TIDAK_SESUAI   = 'hasil-tidak-sesuai';
    public const CATEGORY_TRANSAKSI_LUAR_PLATFORM = 'transaksi-luar-platform';
    public const CATEGORY_SPAM                 = 'spam';
    public const CATEGORY_BUG_SISTEM           = 'bug-sistem';
    public const CATEGORY_LAINNYA              = 'lainnya';

    public const CATEGORIES = [
        self::CATEGORY_UMUM,
        self::CATEGORY_PENIPUAN,
        self::CATEGORY_KONTEN_TIDAK_PANTAS,
        self::CATEGORY_PERILAKU_TIDAK_PROFESIONAL,
        self::CATEGORY_TIDAK_MEMBAYAR,
        self::CATEGORY_HASIL_TIDAK_SESUAI,
        self::CATEGORY_TRANSAKSI_LUAR_PLATFORM,
        self::CATEGORY_SPAM,
        self::CATEGORY_BUG_SISTEM,
        self::CATEGORY_LAINNYA,
    ];

    // ─── KATEGORI PER TARGET (V3) ────────────────────────────────
    public const CATEGORIES_PROJECT = [
        self::CATEGORY_PENIPUAN,
        self::CATEGORY_SPAM,
        self::CATEGORY_KONTEN_TIDAK_PANTAS,
        self::CATEGORY_PERILAKU_TIDAK_PROFESIONAL,
        self::CATEGORY_HASIL_TIDAK_SESUAI,
        self::CATEGORY_TRANSAKSI_LUAR_PLATFORM,
        self::CATEGORY_LAINNYA,
    ];

    public const CATEGORIES_COMPANY = [
        self::CATEGORY_PENIPUAN,
        self::CATEGORY_TIDAK_MEMBAYAR,
        self::CATEGORY_PERILAKU_TIDAK_PROFESIONAL,
        self::CATEGORY_TRANSAKSI_LUAR_PLATFORM,
        self::CATEGORY_SPAM,
        self::CATEGORY_KONTEN_TIDAK_PANTAS,
        self::CATEGORY_LAINNYA,
    ];

    public const CATEGORIES_FREELANCER = [
        self::CATEGORY_HASIL_TIDAK_SESUAI,
        self::CATEGORY_PERILAKU_TIDAK_PROFESIONAL,
        self::CATEGORY_TRANSAKSI_LUAR_PLATFORM,
        self::CATEGORY_SPAM,
        self::CATEGORY_PENIPUAN,
        self::CATEGORY_KONTEN_TIDAK_PANTAS,
        self::CATEGORY_LAINNYA,
    ];

    public const CATEGORIES_WEBSITE = [
        self::CATEGORY_BUG_SISTEM,
        self::CATEGORY_UMUM,
        self::CATEGORY_LAINNYA,
    ];

    protected $fillable = [
        'reporter_id',
        'reported_user_id',
        'project_id',
        'penawaran_id',
        'workspace_id',
        'target',
        'subject',
        'description',
        'category',
        'status',
        'admin_note',
        'handled_by',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    // ─── RELATIONS ───────────────────────────────────────────────
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function reportedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_user_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function penawaran(): BelongsTo
    {
        return $this->belongsTo(Penawaran::class, 'penawaran_id');
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class, 'workspace_id');
    }

    /**
     * Admin yang menangani laporan.
     */
    public function handledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    /**
     * Lampiran / bukti laporan (V3).
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(ReportAttachment::class, 'report_id');
    }

    // ─── HELPERS ─────────────────────────────────────────────────
    public static function categories(): array
    {
        return self::CATEGORIES;
    }

    /**
     * Daftar kategori yang valid untuk sebuah target (V3).
     */
    public static function categoriesForTarget(?string $target): array
    {
        return match ($target) {
            self::TARGET_PROJECT    => self::CATEGORIES_PROJECT,
            self::TARGET_COMPANY    => self::CATEGORIES_COMPANY,
            self::TARGET_FREELANCER => self::CATEGORIES_FREELANCER,
            self::TARGET_WEBSITE    => self::CATEGORIES_WEBSITE,
            default                 => self::CATEGORIES,
        };
    }

    public static function categoryLabel(?string $category): string
    {
        return match ($category) {
            self::CATEGORY_PENIPUAN                 => 'Penipuan',
            self::CATEGORY_KONTEN_TIDAK_PANTAS      => 'Konten Tidak Pantas',
            self::CATEGORY_PERILAKU_TIDAK_PROFESIONAL => 'Perilaku Tidak Profesional',
            self::CATEGORY_TIDAK_MEMBAYAR           => 'Tidak Membayar',
            self::CATEGORY_HASIL_TIDAK_SESUAI       => 'Hasil Pekerjaan Tidak Sesuai',
            self::CATEGORY_TRANSAKSI_LUAR_PLATFORM  => 'Transaksi di Luar Platform',
            self::CATEGORY_SPAM                     => 'Spam',
            self::CATEGORY_BUG_SISTEM               => 'Bug Sistem',
            self::CATEGORY_LAINNYA                  => 'Lainnya',
            default                                 => 'Umum',
        };
    }

    public static function statusLabel(?string $status): string
    {
        return match ($status) {
            self::STATUS_MENUNGGU       => 'Menunggu',
            self::STATUS_DITINJAU       => 'Sedang Ditinjau',
            self::STATUS_MENUNGGU_BUKTI => 'Menunggu Bukti Tambahan',
            self::STATUS_SELESAI        => 'Selesai',
            self::STATUS_DITOLAK        => 'Ditolak',
            self::STATUS_DIPROSES       => 'Diproses',
            default                     => ucfirst((string) $status),
        };
    }

    public static function targetLabel(?string $target): string
    {
        return match ($target) {
            self::TARGET_PROJECT    => 'Proyek',
            self::TARGET_COMPANY    => 'Perusahaan',
            self::TARGET_FREELANCER => 'Freelancer',
            self::TARGET_WEBSITE    => 'Website / Sistem',
            default                 => 'Umum',
        };
    }
}
