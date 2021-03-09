<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EdoProtocolFiles extends Model
{
    //
    protected $fillable =[
        'protocol_id',
        'file_hash',
        'file_name',
        'file_extension',
        'file_size'
    ];
}
