<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EdoProtocolsEditReport extends Model
{
    //
    protected $fillable =[
        'user_id',
        'protocol_id',
        'comment',
    ];

}
