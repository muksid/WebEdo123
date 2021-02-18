<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EdoUsers extends Model
{
    //
    protected $fillable = [
        'user_id',
        'role_id',
        'user_manager',
        'user_child',
        'sort',
        'department_id',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function userRole()
    {
        return $this->belongsTo(EdoUserRoles::class, 'role_id');
    }

    public function role()
    {
        return $this->belongsTo('App\EdoUserRoles', 'role_id');
    }

    public function manager()
    {
        return $this->hasOne( 'App\User', 'id', 'user_manager');
    }

    public function child()
    {
        return $this->hasOne( 'App\User', 'id', 'user_child');
    }

    public function department()
    {
        return $this->hasOne( Department::class, 'id', 'department_id');
    }

}
