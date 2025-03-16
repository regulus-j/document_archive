<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'company_accounts'; // Ensure correct table

    public function offices()
    {
        return $this->hasMany(Office::class, 'company_id'); // Ensure correct foreign key
    }
  

public function users()
{
    return $this->hasMany(User::class, 'company_id');
/*************  ✨ Codeium Command ⭐  *************/
    /**
     * Get the users that belong to the company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
/******  89096050-0257-4cef-a37b-825046569df1  *******/}


}

