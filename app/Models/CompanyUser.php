<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyUser extends Model
{
    protected $table = 'company_users';

    protected $fillable = [
        'user_id',
        'company_id',
    ];

    /**
     * Get the user that belongs to the company user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the company that belongs to the company user.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyAccount::class);
    }
}
