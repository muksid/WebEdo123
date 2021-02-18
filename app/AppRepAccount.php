<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppRepAccount extends Model
{
    //
    protected $fillable = [
        'bank_user_id',
        'account_id',
        'account_inn',
        'title'
    ];

    // for rep files
    public function repFiles() {

        return $this->hasMany(AppRepAccountFiles::class,'rep_account_id');
    }
}
