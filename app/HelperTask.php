<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HelperTask extends Model
{
    //
    protected $fillable = [
        'user_id',
        'title',
        'title_ru'
    ];
}
