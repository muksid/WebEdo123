<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 30.12.2019
 * Time: 17:11
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    //
    protected $fillable = [
        'user_id',
        'from_branch',
        'subject',
        'text',
        'mes_term',
        'mes_type',
        'is_deleted',
        'mes_gen'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function messageUsers()
    {
        return $this->hasMany(MessageUsers::class,'message_id');
    }

    public function messageUser()
    {
        return $this->hasOne(MessageUsers::class,'message_id');
    }

    public function files()
    {
        return $this->hasMany(MessageFiles::class,'message_id');
    }

    public static function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

}
