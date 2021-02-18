<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EdoQrMessageFiles extends Model
{
    //
    protected $fillable = [
        'edo_qr_message_id',
        'file_hash',
        'file_name',
        'file_extension',
        'file_size'
    ];
}
