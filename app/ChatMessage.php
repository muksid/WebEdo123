<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    //
    protected $table = 'chat_messages';

    public $fillable = [
        'from_user_id',
        'message',
        'to_user_id',
        'is_deleted',
        'deleted_date',
        'is_readed'
    ];

    public function users() {

        return $this->hasMany('App\User','id', 'to_user_id') ;

    }
}
