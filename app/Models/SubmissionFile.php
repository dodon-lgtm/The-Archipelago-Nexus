<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubmissionFile extends Model
{
    protected $table = 'submission_files';

    protected $fillable = [
        'submission_id',
        'file_name',
        'file_path',
        'file_size',
        'mime_type',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(ProjectSubmission::class, 'submission_id');
    }

    public function getFileUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 1) . ' KB';
        }
        return $bytes . ' B';
    }

    public function getFileIconAttribute(): string
    {
        $ext = strtolower(pathinfo($this->file_name, PATHINFO_EXTENSION));

        $imageExts = ['png', 'jpg', 'jpeg', 'webp', 'gif'];
        $videoExts = ['mp4', 'mov', 'avi', 'mkv'];
        $docExts = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt'];
        $archiveExts = ['zip', 'rar', '7z'];
        $codeExts = ['json', 'xml', 'fig', 'apk', 'sql'];

        if (in_array($ext, $imageExts)) return 'fa-solid fa-image';
        if (in_array($ext, $videoExts)) return 'fa-solid fa-video';
        if (in_array($ext, $docExts)) return 'fa-solid fa-file-lines';
        if (in_array($ext, $archiveExts)) return 'fa-solid fa-file-zipper';
        if (in_array($ext, $codeExts)) return 'fa-solid fa-file-code';
        return 'fa-solid fa-file';
    }

    public function getFileColorAttribute(): string
    {
        $ext = strtolower(pathinfo($this->file_name, PATHINFO_EXTENSION));

        $imageExts = ['png', 'jpg', 'jpeg', 'webp', 'gif'];
        $videoExts = ['mp4', 'mov', 'avi', 'mkv'];
        $docExts = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt'];
        $archiveExts = ['zip', 'rar', '7z'];
        $codeExts = ['json', 'xml', 'fig', 'apk', 'sql'];

        if (in_array($ext, $imageExts)) return 'text-pink-500';
        if (in_array($ext, $videoExts)) return 'text-purple-500';
        if (in_array($ext, $docExts)) return 'text-blue-500';
        if (in_array($ext, $archiveExts)) return 'text-amber-500';
        if (in_array($ext, $codeExts)) return 'text-cyan-500';
        return 'text-slate-400';
    }
}

