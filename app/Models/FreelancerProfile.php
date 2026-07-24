<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FreelancerProfile extends Model
{
    protected $fillable = [

        'user_id',

        'company_name',

        'company_logo',

        'industry',

        'description',

        'website',

        'location',

        'phone'

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
