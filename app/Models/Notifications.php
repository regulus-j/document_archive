<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    //
    protected $fillable = [
        'user_id',
        'type',
        'data',
        'read_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function markAsRead()
    {
        $this->read_at = now();
        $this->save();
    }
}
