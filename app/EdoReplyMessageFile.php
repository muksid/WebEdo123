<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EdoReplyMessageFile extends Model
{
    //
    protected $fillable = [
        'edo_reply_message_id',
        'file_hash',
        'file_name',
        'file_extension',
        'file_size'
    ];
}
