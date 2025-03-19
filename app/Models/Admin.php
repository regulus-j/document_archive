<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

/**
 * @deprecated This model is deprecated in favor of Spatie's role-permission system.
 * Use User::isSuperAdmin() and User::isCompanyAdmin() methods instead.
 * This class is maintained for backward compatibility only.
 */
class Admin extends Model
{
    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
