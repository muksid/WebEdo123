<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EdoMessageJournal extends Model
{
    //
    protected $fillable = [
        'user_id',
        'depart_id',
        'to_user_id',
        'message_view',
        'message_type',
        'edo_journal_id',
        'edo_message_id',
        'in_number',
        'in_number_a',
        'status'
    ];


    // for view helper
    public function officeUser()
    {
        return $this->belongsTo(User::class, 'user_id','id')
            ->select(DB::raw('CONCAT(lname, " ", fname) AS full_name'),'users.id as user_id','users.job_title');
    }

    // for ve helper
    public function userJob()
    {
        return $this->belongsTo(EdoUsers::class, 'user_id','user_id');
    }
    // for office sent
    public function toUser()
    {
        return $this->belongsTo('App\User', 'to_user_id')
            ->select(DB::raw('CONCAT(lname, " ", fname) AS full_name'));
    }

    // for depart d task
    public function director()
    {
        return $this->hasOne(User::class, 'id','to_user_id')
            ->join('departments as d', 'users.depart_id', 'd.id')
            ->select(DB::raw('CONCAT(lname, " ", fname) AS full_name'),'users.id as user_id','users.job_title','d.title');
            //->where('d.parent_id', 1)
            //->where('users.status', 1);
    }

    // for view helper
    public function guideUser()
    {
        return $this->hasOne(User::class, 'id','to_user_id')
            ->join('departments as d', 'users.depart_id', 'd.id')
            ->select(DB::raw('CONCAT(lname, " ", fname) AS full_name'),'users.id as user_id','users.job_title','d.title')
            ->where('d.parent_id', 1)
            ->where('users.status', 1);
    }

    // redirect tasks
    public function redirectTasks()
    {
        return $this->hasMany(EdoRedirectMessage::class, 'edo_message_id','edo_message_id');
    }

    // change files by KANC
    public function messageLogFile()
    {
        return $this->hasMany(EdoMessageLogFile::class, 'edo_message_id','edo_message_id')->groupBy('comments');
    }

    public function messageType()
    {
        return $this->belongsTo(EdoTypeMessages::class, 'message_type');
    }

    // for guide task sent
    public function perfUsers()
    {
        return $this->hasMany(EdoMessageUsers::class, 'edo_mes_jrls_id');
    }

    // journal name
    public function journalName()
    {
        return $this->hasOne(EdoJournals::class, 'id','edo_journal_id');
    }

    // journal name
    public function signatureUser()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    // journal name 
    # Jamshid added
    public function signatureUser2()
    {
        return $this->hasOne(User::class, 'id', 'to_user_id');
    }


    public function taskText()
    {
        return $this->hasOne(EdoHelperMessage::class, 'edo_message_journals_id');
    }

    public function message()
    {
        return $this->belongsTo(EdoMessage::class, 'edo_message_id');
    }

    public function messageHelper()
    {
        return $this->hasOne('App\EdoHelperMessage', 'edo_message_journals_id');
    }

}
