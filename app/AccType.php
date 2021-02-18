<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccType extends Model
{
    //
    protected $fillable = [
        'acc_code',
        'title',
        'title_ru'
    ];
}
