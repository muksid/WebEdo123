<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageForward extends Model
{
    //
    protected $fillable = [
        'message_id',
        'new_message_id',
        'from_user_id',
        'to_user_id',
        'title',
        'text'
    ];

    public function message()
    {
        return $this->belongsTo(Message::class,'message_id', 'id');
    }
}
