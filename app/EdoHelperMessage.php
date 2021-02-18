<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EdoHelperMessage extends Model
{
    //
    protected $fillable = [
        'edo_message_id',
        'edo_user_id',
        'edo_message_journals_id',
        'edo_type_message_id',
        'text'
    ];

    // for view task
    public function controlType()
    {
        return $this->hasOne(EdoTypeMessages::class, 'id','edo_type_message_id');
    }
}
