<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EdoMessageLogFile extends Model
{
    //
    protected $fillable = [
        'edo_message_id',
        'user_id',
        'file_type',
        'file_name',
        'comments'
    ];

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
