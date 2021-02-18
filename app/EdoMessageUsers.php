<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EdoMessageUsers extends Model
{
    //
    protected $fillable = [
        'edo_message_id',
        'edo_mes_jrls_id',
        'from_user_id',
        'to_user_id',
        'depart_id',
        'performer_user',
        'sort',
        'is_read',
        'read_date',
        'status',
        'sub_status'
    ];

    // change files by KANC
    public function messageLogFile()
    {
        return $this->hasMany(EdoMessageLogFile::class, 'edo_message_id','edo_message_id')->groupBy('comments');
    }

    public function signatureUser()
    {
        return $this->hasOne(User::class, 'id', 'from_user_id');
    }
    // edo message users index
    public function helper()
    {
        return $this->belongsTo(EdoHelperMessage::class,'edo_message_id','edo_message_id');
    }

    // director message process
    public function subHelper()
    {
        return $this->belongsTo(EdoHelperSubMessage::class,'edo_message_id','edo_message_id');
    }

    // for director process perf sub users
    public function perfSubUsers()
    {
        $department = EdoUsers::where('user_id', Auth::id())->where('status',1)->firstOrFail();

        return $this->hasMany(EdoMessageSubUsers::class, 'edo_message_id','edo_message_id')
            ->where('depart_id', $department->department_id);
    }

    // for view performer Department Director name
    public function director()
    {
        return $this->hasOne(User::class, 'id','to_user_id')
            ->join('departments as d', 'users.depart_id', 'd.id')
            ->select(DB::raw('CONCAT(lname, " ", fname) AS full_name'),'users.job_title','d.title');
    }

    // for view performer Department Director name
    public function filial()
    {
        return $this->hasOne(EdoUsers::class, 'user_id','to_user_id')
            ->join('departments as d', 'edo_users.department_id', 'd.depart_id')
            ->select('d.title as filial_name')
            ->where('d.parent_id', 0);
            //->where('users.status', 1);
    }

    // message journals
    public function messageJournal()
    {
        return $this->belongsTo(EdoMessageJournal::class, 'edo_mes_jrls_id');
    }

    public function journal()
    {
        $department = EdoUsers::where('user_id', Auth::id())->where('status', 1)->firstOrFail();

        return $this->hasOne(EdoDepInboxJournals::class, 'edo_message_id', 'edo_message_id')
            ->where('depart_id', $department->department_id);
    }

    public function performerUser()
    {
        return $this->hasOne(User::class, 'id', 'to_user_id');
    }

    public function performerType()
    {
        return $this->hasOne(EdoTypeMessages::class, 'id', 'performer_user');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'id', 'to_user_id');
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id', 'id');
    }

    public function files()
    {
        return $this->hasMany(EdoMessageFile::class, 'edo_message_id', 'edo_message_id');
    }

    public function message()
    {
        return $this->belongsTo(EdoMessage::class, 'edo_message_id');
    }


    public function edoUsers()
    {
        return $this->hasOne(EdoUsers::class, 'user_id', 'to_user_id');
    }
    
}
