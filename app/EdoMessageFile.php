<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EdoMessageFile extends Model
{
    //
    protected $fillable = [
        'edo_message_id',
        'file_path',
        'file_hash',
        'file_name',
        'file_extension',
        'file_size'
    ];

    public function size($bytes)
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
