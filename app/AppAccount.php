<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppAccount extends Model
{
    //
    protected $fillable = [
        'account_id',
        'account_code',
        'account_inn',
        'status'
    ];

    // for ve helper
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
