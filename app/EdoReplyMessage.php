<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EdoReplyMessage extends Model
{
    //
    protected $fillable = [
        'edo_message_id',
        'depart_id',
        'director_id',
        'user_id',
        'text',
        'status'
    ];


    public function files()
    {
        return $this->hasMany(EdoReplyMessageFile::class, 'edo_reply_message_id');
    }

    public function replyUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function replyEdoUser()
    {
        return $this->belongsTo(EdoUsers::class, 'user_id', 'user_id');
    }

    // for guide director name
    public function replyDirector()
    {
        return $this->belongsTo(User::class, 'director_id');
    }
    public function replyDirectorDepartment()
    {
        return $this->belongsTo(Department::class, 'depart_id');
    }
    
}
