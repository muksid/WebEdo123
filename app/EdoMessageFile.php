<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EdoMessageFile extends Model
{
    //
    protected $fillable = [
        'edo_message_id',
        'file_hash',
        'file_name',
        'file_extension',
        'file_size'
    ];
}
