<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'user_id',
        'channel_id',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function channel()
    {
        return $this->belongsTo(\App\Models\Channel::class);
    }

    public function getTextAttribute($val)
    {
        $locale = config('app.locale');

        return $val . ($locale !== 'en' ? " ($locale)" : '');
    }
}
