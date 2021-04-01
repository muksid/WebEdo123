<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailAppeals extends Model
{
    //
    protected $fillable = [
        'user_id',
        'to_email_name',
        'subject',
        'text',
        'sort',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
