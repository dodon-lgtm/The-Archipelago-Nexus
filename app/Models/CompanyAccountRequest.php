<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyAccountRequest extends Model
{
    protected $table = 'company_account_requests';

    protected $fillable = [
        'company_name',
        'contact_person',
        'company_email',
        'company_phone',
        'company_address',
        'company_description',
        'request_status',
        'reviewed_by',
        'note',
    ];

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}

