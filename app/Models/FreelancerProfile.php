<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FreelancerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'bio',
        'photo',
        'skills',
        'experience',
        'portfolio_link',
        'location',
        'cv',
        'hourly_rate',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
