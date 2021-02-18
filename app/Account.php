<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    //
    protected $fillable = [
        'filial_code',
        'acc_type',
        'acc_name',
        'acc_inn',
        'owner_fname',
        'owner_lname',
        'owner_sname',
        'region_code',
        'district_code',
        'acc_address',
        'type_activity',
        'owner_phone',
        'acc_gen'
    ];

    // for files
    public function files() {

        return $this->hasMany(AccountFiles::class,'account_id');
    }

    // for acc accType
    public function accType()
    {
        return $this->hasOne(AccType::class, 'id', 'acc_type');
    }
    // for acc filial
    public function accFilial()
    {
        return $this->hasOne(Department::class, 'id', 'filial_code');
    }
}
