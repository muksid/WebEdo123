<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EdoCancelMessage extends Model
{
    //
    protected $fillable = [
        'user_id',
        'edo_message_id',
        'description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
