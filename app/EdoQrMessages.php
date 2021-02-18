<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EdoQrMessages extends Model
{
    //
    protected $fillable = [
        'message_date',
        'message_number',
        'title',
        'message_hash',
        'text',
        'performer_user_id',
        'guide_user_id',
        'signature_date',
        'status',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'performer_user_id');
    }

    public function guide()
    {
        return $this->belongsTo(User::class, 'guide_user_id');
    }
}
