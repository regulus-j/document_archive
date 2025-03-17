<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserManaged extends Model
{
    use HasFactory;

    protected $table = 'companies'; // This maps to the companies table

    protected $fillable = [
        'name', 'address', 'logo', 'site_name', 'color_theme',
    ];

    // Relationship with users
    public function users()
    {
        return $this->hasMany(User::class, 'company_id');
    }

    // Relationship with offices
    public function offices()
    {
        return $this->hasMany(Office::class, 'company_id');
    }
}
