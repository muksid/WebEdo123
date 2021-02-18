<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EdoMessage extends Model
{
    //
    protected $fillable = [
        'from_name',
        'title',
        'text',
        'in_number',
        'in_date',
        'out_number',
        'out_date',
        'message_hash',
        'urgent'
    ];

    ### For Displaying Redirect info in viewTaskProcess.blade.php Jamshid
    public function messageRedirect()
    {
        return $this->hasOne(EdoRedirectMessage::class, 'edo_message_id');
    }
    
    // for view helper
    public function journalUser()
    {
        return $this->belongsTo(EdoMessageJournal::class, 'id','edo_message_id');
    }

    // for ve helper
    public function userJob()
    {
        return $this->belongsTo(EdoUsers::class, 'user_id','user_id');
    }

    // for view helper
    public function perfUsers()
    {
        return $this->hasMany(EdoMessageUsers::class, 'edo_message_id','id');
    }

    // for view helper
    public function edoMessageUsersOrdinary()
    {
        return $this->hasOne(EdoMessageUsers::class, 'edo_message_id','id')->where('depart_id', Auth::user()->department->depart_id);
    }

    // edo message sub users
    public function subUser()
    {
        $department = EdoUsers::where('user_id', Auth::id())->firstOrFail();

        return $this->hasOne(EdoMessageSubUsers::class, 'edo_message_id')
            ->where('depart_id', $department->department_id);
    }

    // edo message sub users
    public function subUserOrdinary()
    {
        return $this->hasOne(EdoMessageSubUsers::class, 'edo_message_id')->where('depart_id', Auth::user()->department->depart_id);
    }

    // for guide view task process
    public function replyTasks()
    {
        return $this->hasMany(EdoReplyMessage::class, 'edo_message_id')->whereIn('status', [2,3]);
    }

    // for edit helper
    public function messageHelper()
    {
        return $this->hasOne('App\EdoHelperMessage', 'edo_message_id');
    }

    // for view performer
    public function messageSubHelper()
    {
        $dep = EdoUsers::where('user_id', Auth::id())->firstOrFail();

        return $this->hasOne(EdoHelperSubMessage::class, 'edo_message_id')
            ->where('depart_id', Auth::user()->department->depart_id);
    }

    public function helperMessageType()
    {
        return $this->hasOne(EdoHelperMessage::class, 'edo_message_id');
    }

    public function files()
    {
        return $this->hasMany('App\EdoMessageFile', 'edo_message_id', 'id');
    }

    public function depInboxJournal()
    {
        return $this->hasOne(EdoDepInboxJournals::class, 'edo_message_id', 'id');
    }


}
