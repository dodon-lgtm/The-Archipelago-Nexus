<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
