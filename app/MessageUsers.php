<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 06.01.2019
 * Time: 17:11
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageUsers extends Model
{
    //
    protected $fillable = [
        'message_id',
        'from_users_id',
        'to_users_id',
        'is_readed',
        'readed_date',
        'is_deleted'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'from_users_id');
    }

    public function toUsers()
    {
        return $this->belongsTo(User::class, 'to_users_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class,'depart_id');
    }

    public function message()
    {
        return $this->belongsTo(Message::class,'message_id');
    }

}