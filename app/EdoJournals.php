<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EdoJournals extends Model
{
    //
    protected $fillable = [
        'user_id',
        'journal_type',
        'depart_id',
        'title',
        'title_ru',
        'status'
    ];
}
