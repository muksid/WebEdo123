<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EdoHelperSubMessage extends Model
{
    //
    protected $fillable = [
        'edo_message_id',
        'edo_user_id',
        'depart_id',
        'edo_message_journals_id',
        'edo_type_message_id',
        'term_date',
        'text'
    ];

    // edo message users index
    public function resolutionType()
    {
        return $this->belongsTo(EdoTypeMessages::class,'edo_type_message_id');
    }

    // for view director task process
    public function controlType()
    {

        return $this->hasOne(EdoTypeMessages::class, 'id','edo_type_message_id');
    }
}
