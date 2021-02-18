<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EdoRedirectMessage extends Model
{
    //
    protected $fillable = [
        'user_id',
        'from_guide_id',
        'to_guide_id',
        'edo_message_id',
        'status',
        'redirect_desc'
    ];

    public function message()
    {
        return $this->belongsTo(EdoMessage::class, 'edo_message_id');
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'to_guide_id');
    }
}
