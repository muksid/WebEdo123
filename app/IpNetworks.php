<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IpNetworks extends Model
{
    //
    protected $fillable = [
        'filial_code',
        'ip_owner_id',
        'ip_first',
        'ip_second',
        'ip_route',
        'ip_net',
        'ip_status',
        'ip_description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'ip_owner_id');
    }

    public function filial()
    {
        return $this->belongsTo(Department::class,'filial_code','branch_code')->where('parent_id', 0);
    }
}
