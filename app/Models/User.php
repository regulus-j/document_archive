<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{

    use Notifiable;
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'uploader');
    }

    public function offices()
    {
        return $this->belongsToMany(Office::class);
    }

    public function office()
{
    return $this->belongsTo(Office::class, 'office_id'); // Assuming office_id exists in users table
}


    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->middle_name} {$this->last_name}";
    }

    

    public function company()
{
    return $this->belongsTo(Company::class, 'company_id');
}


    public function companySubscriptions()
    {
        return $this->hasMany(CompanySubscription::class, 'company_id');
    }

    public function companies()
    {
        return $this->belongsToMany(CompanyAccount::class, 'company_users', 'user_id', 'company_id');
    }

    public function isAdmin(): bool
    {
        return Admin::where('user_id', $this->id)->exists();
    }

    public function plans()
    {
        return $this->belongsToMany(Plan::class);
    }

}