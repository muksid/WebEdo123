<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class EdoMessageSubUsers extends Model
{
    //
    protected $fillable = [
        'edo_message_id',
        'edo_mes_jrls_id',
        'from_user_id',
        'to_user_id',
        'depart_id',
        'performer_user',
        'is_read',
        'read_date',
        'status'
    ];

    // message
    public function message()
    {
        return $this->belongsTo(EdoMessage::class, 'edo_message_id');
    }

    // message journal
    public function messageJournal()
    {
        return $this->belongsTo(EdoMessageJournal::class, 'edo_mes_jrls_id');
    }

    // helper message
    public function helper()
    {
        return $this->belongsTo(EdoHelperMessage::class, 'edo_message_id','edo_message_id');
    }

    // sub helper message
    public function subHelper()
    {
        return $this->belongsTo(EdoHelperSubMessage::class, 'edo_mes_jrls_id','edo_message_journals_id')->where('depart_id', Auth::user()->department->depart_id);
    }


    public function signatureUser()
    {
        return $this->hasOne(User::class, 'id', 'from_user_id');
    }
    # Jamshid
    public function signatureUserRole()
    {
        return $this->hasOne(EdoUsers::class, 'user_id', 'from_user_id');
    }

    # Jamshid
    public function toSubUser()
    {
        return $this->hasOne(User::class, 'id', 'to_user_id');
    }

    # Jamshid
    public function edoTypeMessage()
    {
        return $this->hasOne(EdoTypeMessages::class, 'id', 'performer_user');
    }

    // for view performer Department Director name
    public function director()
    {
        return $this->hasOne(User::class, 'id','from_user_id')
            ->join('departments as d', 'users.depart_id', 'd.id')
            ->select(DB::raw('CONCAT(lname, " ", fname) AS full_name'),'users.job_title','d.title');
            //->whereIn('d.parent_id', [0,1]);
            //->where('users.status', 1);
    }

    public function department()
    {
        return $this->hasOne(Department::class, 'parent_id','depart_id');
    }

    public function mesUsers(){
        return $this->hasOne(EdoMessageUsers::class, 'edo_message_id','edo_message_id')->where('depart_id', Auth::user()->department->depart_id);
    }
}
