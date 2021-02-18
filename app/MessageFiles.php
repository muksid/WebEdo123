<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 06.01.2019
 * Time: 17:11
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageFiles extends Model
{
    //
    protected $fillable = [
        'message_id',
        'file_hash',
        'file_name',
        'file_extension',
        'file_size'
    ];

    public function message()
    {
        return $this->belongsTo(Message::class,'message_id');
    }

}