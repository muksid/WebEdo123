<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EdoManagementMembers extends Model
{
    //
    protected $fillable = [
        'user_id',
        'qr_name',
        'qr_hash',
        'qr_file',
        'title',
        'user_sort',
        'status'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
