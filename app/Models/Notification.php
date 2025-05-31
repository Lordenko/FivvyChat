<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['chat_id', 'message'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recipients()
    {
        return $this->belongsToMany(User::class)->withPivot('is_read')->withTimestamps();
    }

}
