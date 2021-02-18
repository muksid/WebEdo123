<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EdoTypeMessages extends Model
{
    //
    protected $fillable = [
        'title',
        'title_ru',
        'sort',
        'type_code',
        'type_message_code'
    ];

    public function roles()
    {
        return $this->belongsTo('App\EdoUserRoles', 'type_code', 'role_code');
    }

}
