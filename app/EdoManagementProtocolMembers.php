<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EdoManagementProtocolMembers extends Model
{
    //
    protected $fillable = [
        'protocol_id',
        'user_id',
        'user_role',
        'user_sort',
        'descr',
        'status'
    ];

    // users
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    // users
    public function protocol()
    {
        return $this->hasOne(EdoManagementProtocols::class, 'id', 'protocol_id');
    }

    // members
    public function members()
    {
        return $this->hasMany(EdoManagementProtocolMembers::class, 'protocol_id','protocol_id')->orderBy('user_sort');
    }

    // view members
    public function managementMembers()
    {
        return $this->hasOne(EdoManagementMembers::class, 'user_id', 'user_id');
    }
}
