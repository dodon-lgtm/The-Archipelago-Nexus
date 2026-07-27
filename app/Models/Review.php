<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'project_id',
        'client_id',
        'freelancer_id',
        'rating',
        'review'
    ];

    // Relasi ke Project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Relasi ke Client (User)
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    // Relasi ke Freelancer (User)
    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }
}
