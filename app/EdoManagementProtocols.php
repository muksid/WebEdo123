<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EdoManagementProtocols extends Model
{
    //
    protected $fillable = [
        'user_id',
        'to_user_id',
        'user_view',
        'depart_id',
        'protocol_type',
        'stf_number',
        'stf_date',
        'title',
        'text',
        'protocol_hash',
        'status'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'depart_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function userEmp()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    // members
    public function members()
    {
        return $this->hasMany(EdoManagementProtocolMembers::class, 'protocol_id')->orderBy('user_sort');
    }

    // view members
    public function viewMembers()
    {
        return $this->hasMany(EdoManagementProtocolMembers::class, 'protocol_id')
            ->where('user_role', 2)
            ->orderBy('user_sort');
    }
}
