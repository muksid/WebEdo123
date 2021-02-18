<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EdoDepInboxJournals extends Model
{
    //
    protected $fillable = [
        'edo_message_id',
        'depart_id',
        'director_id',
        'user_id',
        'in_number',
        'in_number_a',
        'from_name',
        'title'
    ];

    // message
    public function message()
    {
        return $this->belongsTo(EdoMessage::class, 'edo_message_id');
    }

    // department director
    public function director()
    {
        return $this->hasOne(User::class, 'id','director_id');
    }

    // department director
    public function user()
    {
        return $this->hasOne(User::class, 'id','user_id');
    }

    // message journal
    public function journal()
    {
        return $this->hasOne(EdoMessageJournal::class, 'edo_message_id','edo_message_id');
    }

    // message journal
    public function subUsers()
    {
        $department = EdoUsers::where('user_id', Auth::id())->firstOrFail();

        return $this->hasMany(EdoMessageSubUsers::class, 'edo_message_id','edo_message_id')
            ->where('depart_id', $department->department_id);
    }

    //
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id', 'id');
    }

    #JAmshid
    public function edoMessageUsers()
    {
        return $this->hasMany(EdoMessageUsers::class, 'edo_message_id', 'edo_message_id')->where('depart_id', Auth::user()->department->depart_id);
    }

    public function edoMessageJournal()
    {
        return $this->hasOne(EdoMessageJournal::class, 'edo_message_id', 'edo_message_id');
    }

    // for director process perf sub users
    public function perfSubUsers()
    {
        return $this->hasMany(EdoMessageSubUsers::class,'edo_message_id','edo_message_id')
            ->where('edo_message_sub_users.from_user_id', Auth::id());
    }

    // for get dep reg Date
    public function depRegDate($edo_message_id, $depart_id)
    {
        $dep_reg_journal = EdoDepInboxJournals::where('edo_message_id',$edo_message_id)->where('depart_id',$depart_id)->first();
    
        $reg_date = $dep_reg_journal->created_at;

        return $reg_date;
    }

}
