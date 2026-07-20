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
    ];

   // app/Models/Penawaran.php
public function project()
{
    return $this->belongsTo(Project::class, 'project_id');
}

    public function freelancer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }
}