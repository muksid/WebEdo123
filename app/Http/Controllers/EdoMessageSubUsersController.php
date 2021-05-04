<?php

namespace App\Http\Controllers;

use App\EdoHelperSubMessage;
use App\EdoMessage;
use App\EdoMessageJournal;
use App\EdoMessageSubUsers;
use App\EdoMessageUsers;
use App\EdoReplyMessage;
use App\EdoTypeMessages;
use App\EdoUserRoles;
use App\EdoUsers;
use App\User;
use App\HelperTask;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EdoMessageSubUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function empTasksInbox(Request $request)
    {
        //
        if($request->all()){
            $dep_num    = $request->input('dep_num');
            $kanc_num   = $request->input('kanc_num');
            $org_name   = $request->input('org_name');
            $doc_name   = $request->input('doc_name');
            $in_num     = $request->input('in_num');

            $search = EdoMessageSubUsers::where('to_user_id','=', Auth::id())
                ->where('depart_id', Auth::user()->department->depart_id)
                ->where('status', 0);

            if($dep_num) {
                $search->whereHas('depInboxJournal', function($query) use($dep_num){
                    $query->where('in_number', 'LIKE', '%'.$dep_num.'%');
                });
            }
            if($org_name) {
                $search->whereHas('message', function($query) use($org_name){
                    $query->where('from_name', 'LIKE', '%'.$org_name.'%');
                });
            }
            if($doc_name) {
                $search->whereHas('message', function($query) use($doc_name){
                    $query->where('title', 'LIKE', '%'.$doc_name.'%');
                });
            }
            if($kanc_num) {
                $search->whereHas('message', function ($query) use ($kanc_num) {

                    $query->where('in_number', 'LIKE', '%'.$kanc_num.'%');

                });
            }
            if($in_num) {
                $search->whereHas('message', function ($query) use ($in_num) {

                    $query->where('out_number', 'LIKE', '%'.$in_num.'%');

                });
            }

            $models = $search->orderBy('created_at', 'DESC')->paginate(25);

            $models->appends ( array (
                'dep_num'   => $dep_num,
                'kanc_num'  => $kanc_num,
                'org_name'  => $org_name,
                'dep_num'   => $dep_num,
                'in_num'    => $in_num
            ));

            return view('edo.edo-message-sub-users.empTasksInbox',compact('models', 'dep_num', 'kanc_num', 'org_name', 'doc_name', 'in_num'));

        }
        $models = EdoMessageSubUsers::where('to_user_id','=', Auth::id())
            ->where('status', 0)
            ->orderBy('created_at', 'DESC')
            ->paginate(25);

        return view('edo.edo-message-sub-users.empTasksInbox',compact('models'));
    }

    public function empTasksProcess(Request $request)
    {
        //
        if($request->all()){

            $dep_num    = $request->input('dep_num');
            $kanc_num   = $request->input('kanc_num');
            $org_name   = $request->input('org_name');
            $doc_name   = $request->input('doc_name');
            $in_num     = $request->input('in_num');

            $search = EdoMessageSubUsers::where('to_user_id','=', Auth::id())
                ->whereIn('status', [0,1,2]);

            if($dep_num) {
                $search->whereHas('depInboxJournal', function($query) use($dep_num){
                    $query->where('in_number', 'LIKE', '%'.$dep_num.'%');
                });
            }
            if($org_name) {
                $search->whereHas('message', function($query) use($org_name){
                    $query->where('from_name', 'LIKE', '%'.$org_name.'%');
                });
            }
            if($doc_name) {
                $search->whereHas('message', function($query) use($doc_name){
                    $query->where('title', 'LIKE', '%'.$doc_name.'%');
                });
            }
            if($kanc_num) {
                $search->whereHas('message', function ($query) use ($kanc_num) {

                    $query->where('in_number', 'LIKE', '%'.$kanc_num.'%');

                });
            }
            if($in_num) {
                $search->whereHas('message', function ($query) use ($in_num) {

                    $query->where('out_number', 'LIKE', '%'.$in_num.'%');

                });
            }

            $models = $search->orderBy('created_at', 'DESC')->paginate(25);

            $models->appends ( array (
                'dep_num'   => $dep_num,
                'kanc_num'  => $kanc_num,
                'org_name'  => $org_name,
                'dep_num'   => $dep_num,
                'in_num'    => $in_num
            ) );

            return view('edo.edo-message-sub-users.empTasksProcess',compact('models', 'dep_num', 'kanc_num', 'org_name', 'doc_name', 'in_num'))
            ->with('i', (request()->input('page', 1) - 1) * 25);

        }
        $models = EdoMessageSubUsers::where('to_user_id','=', Auth::id())
            ->where('status', 1)
            ->orderBy('created_at', 'DESC')
            ->paginate(25);

        return view('edo.edo-message-sub-users.empTasksProcess',compact('models'));
    }

    public function empTasksClosed(Request $request)
    {
        //
        if($request->all()){

            $dep_num    = $request->input('dep_num');
            $kanc_num   = $request->input('kanc_num');
            $org_name   = $request->input('org_name');
            $doc_name   = $request->input('doc_name');
            $in_num     = $request->input('in_num');

            $search = EdoMessageSubUsers::where('to_user_id','=', Auth::id())
                ->where('status', 3);

            if($dep_num) {
                $search->whereHas('depInboxJournal', function($query) use($dep_num){
                    $query->where('in_number', 'LIKE', '%'.$dep_num.'%');
                });
            }
            if($org_name) {
                $search->whereHas('message', function($query) use($org_name){
                    $query->where('from_name', 'LIKE', '%'.$org_name.'%');
                });
            }
            if($doc_name) {
                $search->whereHas('message', function($query) use($doc_name){
                    $query->where('title', 'LIKE', '%'.$doc_name.'%');
                });
            }
            if($kanc_num) {
                $search->whereHas('message', function ($query) use ($kanc_num) {

                    $query->where('in_number', 'LIKE', '%'.$kanc_num.'%');

                });
            }
            if($in_num) {
                $search->whereHas('message', function ($query) use ($in_num) {

                    $query->where('out_number', 'LIKE', '%'.$in_num.'%');

                });
            }

            $models = $search->orderBy('created_at', 'DESC')->paginate(25);

            $models->appends ( array (
                'dep_num'   => $dep_num,
                'kanc_num'  => $kanc_num,
                'org_name'  => $org_name,
                'dep_num'   => $dep_num,
                'in_num'    => $in_num
            ));

            return view('edo.edo-message-sub-users.empTasksClosed',compact('models', 'dep_num', 'kanc_num', 'org_name', 'doc_name', 'in_num'))
            ->with('i', (request()->input('page', 1) - 1) * 25);

        }


        $models = EdoMessageSubUsers::where('to_user_id','=', Auth::id())
            ->where('status', 3)
            ->orderBy('created_at', 'DESC')
            ->paginate(25);

        return view('edo.edo-message-sub-users.empTasksClosed',compact('models'));
    }

    public function viewEmpTask($id, $slug)
    {
        // is read performer
        $messageSubUser = EdoMessageSubUsers::findOrFail($id);

        $messageSubUser->update(['is_read' => 1, 'read_date' => Carbon::now()]);

        $messageJournal = EdoMessageJournal::findOrFail($messageSubUser->edo_mes_jrls_id);

        // model message
        $model = EdoMessage::where('message_hash', $slug)->firstOrFail();

        // performer users
        $perfUsers = EdoMessageUsers::select('edo_message_users.*','t.type_code','t.title_ru','t.sort as mu_sort',
            'u.user_sort', DB::raw('CONCAT(lname, " ", fname) AS full_name'))
            ->join('edo_type_messages as t', 't.id', '=', 'edo_message_users.performer_user')
            ->join('users as u', 'edo_message_users.to_user_id', '=', 'u.id')
            ->where('edo_message_users.edo_message_id', $model->id)
            ->orderBy('t.sort', 'ASC')
            ->get();

        // edo type messages
        $perfUserTypes = EdoTypeMessages::where('type_code', 'performers_in_helper')->get();

        $edoUsers = EdoUsers::where('user_id', Auth::id())->firstOrFail();

        $role = EdoUserRoles::findOrFail($edoUsers->role_id);

        $messageTypes = EdoTypeMessages::where('type_code', $role->role_code)->get();

        // performer sub users
        $perfSubUsers = DB::table('edo_type_messages as a')
            ->join('edo_message_sub_users as m', 'a.id', '=', 'm.performer_user')
            ->join('users as u', 'm.to_user_id', '=', 'u.id')
            ->select('a.title_ru','a.id as mes_type_id','u.id as user_id','u.branch_code','u.job_title','m.from_user_id','m.to_user_id',
                DB::raw('CONCAT(lname, " ", fname) AS full_name'))
            ->where('m.from_user_id', $messageSubUser->from_user_id)
            ->where('m.edo_message_id', $model->id)
            ->orderBy('a.sort', 'ASC')
            ->get();

        $perfEmpUsers = DB::table('edo_type_messages as a')
            ->join('edo_message_sub_users as m', 'a.id', '=', 'm.performer_user')
            ->join('users as u', 'm.to_user_id', '=', 'u.id')
            ->select('a.title_ru','a.id as mes_type_id','u.id as user_id','u.branch_code','u.job_title','m.from_user_id','m.to_user_id',
                DB::raw('CONCAT(lname, " ", fname) AS full_name'))
            ->where('m.edo_message_id', $model->id)
            ->orderBy('a.sort', 'ASC')
            ->groupBy('m.to_user_id')
            ->get();

        $selectedUsers = $perfEmpUsers->implode('to_user_id', ',');

        $explodeUsers = explode(',', $selectedUsers);

        // users
        $users = DB::table('edo_users as a')
            ->join('users as u', 'a.user_id', '=', 'u.id')
            ->join('edo_user_roles as r', 'a.role_id', '=', 'r.id')
            ->join('departments as d', 'u.depart_id', '=', 'd.id')
            ->select('a.*', 'u.id as u_id', 'u.branch_code', 'u.job_title',
                DB::raw('CONCAT(u.branch_code, " - ", lname, " ", fname) AS full_name'), 'r.title as roleName')
            ->where('d.depart_id', Auth::user()->department->depart_id)
            ->where('a.user_id', '!=', Auth::id())
            ->whereNotIn('a.user_id', $explodeUsers)
            ->where('a.status', 1)
            ->orderBy('a.sort', 'ASC')
            ->get();

        $replyMessage = EdoReplyMessage::where('edo_message_id', $model->id)->get();

        $replyMessageGroupBy = EdoReplyMessage::where('edo_message_id', $model->id)->groupBy('director_id')->get();

        return view('edo.edo-message-sub-users.viewEmpTask',
            compact('model', 'users','messageTypes', 'messageSubUser','edoUsers','messageJournal',
                'replyMessage','replyMessageGroupBy', 'perfUsers', 'perfUserTypes', 'perfSubUsers','perfEmpUsers','role'));
    }

    public function viewDepartDTask($id, $slug)
    {
        // is read performer
        $isRead = EdoMessageSubUsers::findOrFail($id);

        $director = EdoMessageJournal::where('edo_message_id',$id)->first();

        $isRead->update(['is_read' => 1, 'read_date' => Carbon::now()]);

        // model message
        $model = EdoMessage::where('message_hash', $slug)->firstOrFail();



        // performer users
        $perfUsers = DB::table('edo_type_messages as a')
            ->join('edo_message_users as m', 'a.id', '=', 'm.performer_user')
            ->join('users as u', 'm.to_user_id', '=', 'u.id')
            ->select('a.title_ru','a.id as mes_type_id','u.id as user_id','u.branch_code','u.job_title',
                DB::raw('CONCAT(lname, " ", fname) AS full_name'))
            ->where('m.edo_message_id', $model->id)
            ->orderBy('a.sort', 'ASC')
            ->get();

        // edo type messages
        $perfUserTypes = EdoTypeMessages::where('type_code', 'performers_in_helper')->get();

        // users
        $users = DB::table('edo_users as a')
            ->join('users as u', 'a.user_id', '=', 'u.id')
            ->join('edo_user_roles as r', 'a.role_id', '=', 'r.id')
            ->join('departments as d', 'u.depart_id', '=', 'd.id')
            ->select('a.*', 'u.id as u_id', 'u.branch_code', 'u.job_title',
                DB::raw('CONCAT(u.branch_code, " - ", lname, " ", fname) AS full_name'), 'r.title as roleName')
            ->where(function($query)
            {
                $query->where('d.parent_id', Auth::user()->depart_id)
                    ->orWhere('u.depart_id', Auth::user()->depart_id);
            })
            ->where('a.user_id', '!=', Auth::id())
            ->where('a.status', 1)
            ->orderBy('a.sort', 'ASC')
            ->get();

        $edoUsers = EdoUsers::where('user_id', Auth::id())->first();

        $role = EdoUserRoles::where('id', $edoUsers->role_id)->first();

        $messageTypes = EdoTypeMessages::where('type_code', $role->role_code)->get();

        // performer sub users
        $perfSubUsers = DB::table('edo_type_messages as a')
            ->join('edo_message_sub_users as m', 'a.id', '=', 'm.performer_user')
            ->join('users as u', 'm.to_user_id', '=', 'u.id')
            ->select('a.title_ru','a.id as mes_type_id','u.id as user_id','u.branch_code','u.job_title',
                DB::raw('CONCAT(lname, " ", fname) AS full_name'))
            ->where('m.from_user_id', $isRead->from_user_id)
            ->where('m.edo_message_id', $model->id)
            ->orderBy('a.sort', 'ASC')
            ->get();

        $replyMessage = EdoReplyMessage::where('edo_message_id', $model->id)->where('director_id', $isRead->from_user_id)->get();

        return view('edo.edo-message-sub-users.viewDirctDepartTaskProcs',
            compact('model', 'users','messageTypes', 'isRead', 'director',
                'replyMessage', 'perfUsers', 'perfUserTypes', 'perfSubUsers','role'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        //print_r($request->all()); die;
        $this->validate($request, [
            'to_user_id' => 'required',
            'text' => 'required'
        ]);

        // model Message
        $message_user_id = $request->input('message_user_id');

        $journal_id = $request->input('jrl_id');

        $id = $request->input('model_id');

        $depart_id = $request->input('depart_id');


        // helper message status update
        $updateSubStatus = EdoMessageUsers::find($message_user_id);

        $status = 0;
        $sub_status = 1;

        if ($updateSubStatus->sub_status == 3){
            $status = 3;
            $sub_status = 3;
        }

        $updateSubStatus->update(['status' => 3, 'sub_status' => $sub_status]);

        // edo message sub users create
        if ($request->input('to_user_id') != null) {

            foreach ($request->input('to_user_id') as $key => $user) {
                if ($user != 0) {
                    $model = new EdoMessageSubUsers();

                    $model->edo_message_id = $id;

                    $model->edo_mes_jrls_id = $journal_id;

                    $model->from_user_id = Auth::id();

                    $model->to_user_id = $user;

                    $model->depart_id = $depart_id;

                    $model->performer_user = $request->input('performer_user')[$key];

                    $model->status = $status;

                    $model->save();

                }
            }
        }

        // helper message
        $modelHelperMessage = new EdoHelperSubMessage();

        $modelHelperMessage->edo_message_id = $id;

        $modelHelperMessage->edo_user_id = Auth::id();

        $modelHelperMessage->depart_id = $depart_id;

        $modelHelperMessage->edo_type_message_id = $request->input('edo_type_message_id');;

        $modelHelperMessage->edo_message_journals_id = $journal_id;

        $modelHelperMessage->term_date = $request->input('term_date');

        $modelHelperMessage->text = $request->input('text');

        $modelHelperMessage->save();

        return redirect()->route('d-tasks-inbox')->with('success', 'Vazifa muvaffaqiyatli saqlandi');
    }

    // add sub users
    public function storeAddSubUsers(Request $request)
    {
        //
        //print_r($request->all()); die;
        $this->validate($request, [
            'to_user_id' => 'required'
        ]);

        // model Message
        $journal_id = $request->input('jrl_id');

        $id = $request->input('model_id');

        // edo message sub users create
        if ($request->input('to_user_id') != null) {

            foreach ($request->input('to_user_id') as $key => $user) {
                if ($user != 0) {
                    $model = new EdoMessageSubUsers();

                    $model->edo_message_id = $id;

                    $model->edo_mes_jrls_id = $journal_id;

                    $model->from_user_id = Auth::id();

                    $model->to_user_id = $user;

                    $model->depart_id = $request->input('depart_id')[$key];

                    $model->performer_user = $request->input('performer_user')[$key];

                    $model->status = 0;

                    $model->save();

                }
            }
        }

        return back()->with('success', 'Xodimlar muvaffaqiyatli qo`shildi');
    }

    // for edit sub performer users
    public function storeEditSubPerformer(Request $request)
    {
        //
        //print_r($request->all()); die;
        $this->validate($request, [
            'to_user_id' => 'required',
            'text' => 'required'
        ]);

        // model Message
        $message_user_id = $request->input('message_user_id');

        $journal_id = $request->input('jrl_id');

        $id = $request->input('model_id');

        // delete old sub users
        $message_users = EdoMessageSubUsers::where('edo_message_id', $id)->where('from_user_id', $message_user_id);

        $message_users->delete();

        if ($request->input('to_user_id') != null) {

            foreach ($request->input('to_user_id') as $key => $user) {
                if ($user != 0) {
                    $model = new EdoMessageSubUsers();

                    $model->edo_message_id = $id;

                    $model->edo_mes_jrls_id = $journal_id;

                    $model->from_user_id = Auth::id();

                    $model->to_user_id = $user;

                    $model->performer_user = $request->input('performer_user')[$key];

                    $model->status = 0;

                    $model->save();

                }
            }
        }

        // update helper message
        $helperInput = $request->only('edo_type_message_id', 'term_date', 'text');

        $helperMessage = EdoHelperSubMessage::where('edo_message_journals_id', $journal_id)->where('edo_user_id', $message_user_id);

        $helperMessage->update($helperInput);

        $message = EdoMessage::find($id);

        /*return redirect()->action(
            'EdoMessageController@viewHelper', ['message_hash' => $message->message_hash]
        )->with('success', 'Vazifa muvaffaqiyatli yangilandi!');*/

        return redirect()->route('edo-message-users')->with('success', 'Vazifa muvaffaqiyatli yangilandi');
    }


    public function storeDirectorEmp(Request $request)
    {
        //
        $this->validate($request, [
            'to_user_id' => 'required',
            'text' => 'required'
        ]);

        $journal_id = $request->input('jrl_id');

        $id = $request->input('model_id');

        $depart_id = $request->input('depart_id');

        if ($request->input('to_user_id') != null) {

            foreach ($request->input('to_user_id') as $key => $user) {
                if ($user != 0) {
                    $model = new EdoMessageSubUsers();

                    $model->edo_message_id = $id;

                    $model->edo_mes_jrls_id = $journal_id;

                    $model->from_user_id = Auth::id();

                    $model->to_user_id = $user;

                    $model->depart_id = $depart_id;

                    $model->performer_user = $request->input('performer_user')[$key];

                    $model->status = 0;

                    $model->save();

                }
            }
        }

        // helper message
        $modelHelperMessage = new EdoHelperSubMessage();

        $modelHelperMessage->edo_message_id = $id;

        $modelHelperMessage->edo_user_id = Auth::id();

        $modelHelperMessage->depart_id = $depart_id;

        $modelHelperMessage->edo_type_message_id = $request->input('edo_type_message_id');

        $modelHelperMessage->edo_message_journals_id = $journal_id;

        $modelHelperMessage->term_date = $request->input('term_date');

        $modelHelperMessage->text = $request->input('text');

        $modelHelperMessage->save();

        // helper message status update
        $updateJrnlStatus = EdoMessageJournal::find($journal_id);

        $updateJrnlStatus->update(['status' => 2]);

        return redirect()->route('department-tasks')->with('success', 'Vazifa muvaffaqiyatli ijrochilarga yuborildi');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
