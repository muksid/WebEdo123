<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QrMessages extends Model
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
}
