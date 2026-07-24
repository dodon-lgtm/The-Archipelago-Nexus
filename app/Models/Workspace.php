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
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
}

