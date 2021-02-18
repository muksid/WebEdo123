<?php

namespace App\Http\Controllers;

use App\EdoDepInboxJournals;
use App\EdoMessage;
use App\EdoMessageJournal;
use App\EdoMessageUsers;
use App\EdoMessageSubUsers;
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
use Illuminate\Support\Facades\Input;

class EdoMessageUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * 1. Kanselariya
     *      EdoMessageJournals status = 0
     *
     * 2. Rahbariyat (Yordamchi)
     *      EdoMessageJournals status = 1
     *      EdoMessageUsers status = 0, sub_status = null
     *
     * 3. Rahbariyat
     *      EdoMessageJournals status = 2
     *      EdoMessageUsers status = 1, sub_status = null
     *
     * 4. Department Kanselariya
     *      EdoMessageUsers status = 2, sub_status = null
     *
     * 5. Department Director
     *      EdoMessageUsers status = 3, sub_status = 1
     *      EdoMessageSubUsers status = 0
     *
     * 6. Department emp javob xatini kiritish
     *      EdoMessageSubUsers status = 1
     *      EdoReplyMessages status = 0
     *
     * 7. Department director javob xatini qabul qilish
     *      EdoReplyMessages status = 1
     *
     * 8. Department director javob xatini tasdiqlashga junatish
     *      EdoReplyMessages status = 2
     *      EdoMessageUsers sub_status = 2
     *      EdoMessageSubUsers status = 2
     *
     * 9. Rahbariyat javob xatini tasdiqlash
     *      EdoMessageJournals status = 3
     *      EdoMessageUsers sub_status = 3
     *      EdoMessageSubUsers status = 3
     */


    public function guideTaskGInbox()
    {
        //
        // search filter
        $r = Input::get ( 'r' );
        $t = Input::get ( 't' );

        if($r != '' || $t != ''){

            $models = EdoMessageUsers::where('to_user_id', Auth::id())
                ->whereHas('message', function ($query) use ($r, $t) {
                    $query->where('in_number', 'like', '%'.$r.'%');
                    $query->where('from_name', 'like', '%'.$t.'%');
                })
                ->where('is_read' , null)
                ->where('status' ,1)
                ->orderBy('id', 'DESC')
                ->paginate(25);

            $models->appends ( array (
                'r' => Input::get ( 'r' ),
                't' => Input::get ( 't' )
            ) );
            if (count ( $models ) > 0)
                return view ( 'edo.edo-message-users.directorTaskInbox',
                    compact('models','r','t'))
                    ->withDetails ( $models )->withQuery ($r, $t);

        }

        $models = EdoMessageUsers::where('to_user_id', Auth::id())
            ->where('is_read' , null)
            ->where('status' ,1)
            ->orderBy('id', 'DESC')
            ->paginate(25);

        return view('edo.edo-message-users.directorTaskInbox',compact('models','r','t'));
    }


    public function guideTaskGProcess()
    {
        //
        // search filter
        $r = Input::get ( 'r' );
        $t = Input::get ( 't' );

        if($r != '' || $t != ''){

            $models = EdoMessageUsers::where('to_user_id', Auth::id())
                ->whereHas('message', function ($query) use ($r, $t) {
                    $query->where('in_number', 'like', '%'.$r.'%');
                    $query->where('from_name', 'like', '%'.$t.'%');
                })
                ->where('is_read', 1)
                ->where('status' , 1)
                ->orderBy('id', 'DESC')
                ->paginate(25);

            $models->appends ( array (
                'r' => Input::get ( 'r' ),
                't' => Input::get ( 't' )
            ) );
            if (count ( $models ) > 0)
                return view ( 'edo.edo-message-users.directorTaskProcess',
                    compact('models','r','t'))
                    ->withDetails ( $models )->withQuery ($r, $t);

        }

        $models = EdoMessageUsers::where('to_user_id', Auth::id())
            ->where('is_read' , 1)
            ->where('status' ,1)
            ->orderBy('id', 'DESC')
            ->paginate(25);

        return view('edo.edo-message-users.directorTaskProcess',compact('models','r','t'));
    }


    public function directorTasksReg()
    {
        //
        $edoUsers  = EdoUsers::where('user_id', Auth::id())->where('status',1)->firstOrFail();

        switch ($edoUsers->userRole->role_code){
          case 'director_department':
          case 'guide_manager':
          case 'deputy_of_director':
          case 'dep_helper':
          case 'filial_office':
        //   deputy_of_director
            $allDepart = EdoMessageUsers::where('status', 1)
                ->where('depart_id', 1)
                ->orderBy('created_at', 'DESC')
                ->get();

            $models = EdoMessageUsers::where('status', 1)
                ->whereIn('depart_id', [$edoUsers->department_id,1])
                ->orderBy('created_at', 'DESC')
                ->paginate(100);

            break;
            default;
                return $edoUsers->userRole->role_code;
                break;

        }

        return view('edo.edo-message-users.directorTaskReg',compact('models', 'allDepart'))
            ->with('i', (request()->input('page', 1) - 1) * 25);
    }

    public function directorTasksInbox()
    {
        //
        $edoUsers  = EdoUsers::where('user_id', Auth::id())->where('status',1)->firstOrFail();
        $department = EdoUsers::where('user_id', Auth::id())->where('status',1)->firstOrfail();


        if ($edoUsers->userRole->role_code == 'director_department' ||
            $edoUsers->userRole->role_code == 'guide_manager' ||
            $edoUsers->userRole->role_code == 'deputy_of_director' ||
            $edoUsers->userRole->role_code == 'dep_helper' ||
            $edoUsers->userRole->role_code == 'filial_manager' ||
            $edoUsers->userRole->role_code == 'filial_office'){
            $models = EdoMessageUsers::where('status' ,2)
                //->join('edo_dep_inbox_journals as b', 'edo_message_users.edo_message_id', '=', 'b.edo_message_id')
                //->where('status' ,2)
                ->where('edo_message_users.depart_id' ,$department->department_id)
                ->orderBy('created_at', 'DESC')
                ->get();
        } else {
            return response()->view('errors.' . '404', [], 404);
        }

        return view('edo.edo-message-users.directorTaskInbox',compact('models'));
    }

    public function directorTasksProcess()
    {
        //
        $edoUsers  = EdoUsers::where('user_id', Auth::id())->where('status',1)->firstOrFail();
        $department = EdoUsers::where('user_id', Auth::id())->where('status',1)->firstOrfail();
        // dd($edoUsers->department_id);
        switch ($edoUsers->userRole->role_code){
            case ('director_department');
            case ('guide_manager');
            case ('deputy_of_director');
            case ('dep_helper');
            case ('filial_manager');
            case ('filial_office');

            $search = EdoMessageUsers::where('depart_id', $department->department_id)
                ->where('status' ,3)
                ->where('sub_status' ,'!=', 3);

            $ofc = Input::get ( 'ofc' );
            $f = Input::get ( 'f' );
            $t = Input::get ( 't' );
            $r = Input::get ( 'r' );

            if($ofc) {
                $search->whereHas('journal', function ($query) use ($ofc) {

                    $query->where('in_number', 'like', '%'.$ofc.'%');

                });
            }
            if($f) {
                $search->whereHas('message', function ($query) use ($f) {

                    $query->where('from_name', 'like', '%'.$f.'%');

                });
            }
            if($t) {
                $search->whereHas('message', function ($query) use ($t) {

                    $query->where('title', 'like', '%'.$t.'%');

                });
            }
            if($r) {
                $search->whereHas('message', function ($query) use ($r) {

                    $query->where('in_number', 'like', '%'.$r.'%');

                });
            }

            $models = $search->orderBy('id', 'DESC')
                ->paginate(25);

            $models->appends ( array (
                'ofc' => Input::get ( 'ofc' ),
                'f' => Input::get ( 'f' ),
                't' => Input::get ( 't' ),
                'd' => Input::get ( 'd' )
            ) );

            break;
            default;
                return response()->view('errors.' . '404', [], 404);
                break;

        }
        return view('edo.edo-message-users.directorTaskProcess',compact('models','ofc','f','t','r'));

    }

    public function directorTasksClosed()
    {
        //
        $edoUsers  = EdoUsers::where('user_id', Auth::id())->where('status',1)->firstOrFail();
        $department = EdoUsers::where('user_id', Auth::id())->where('status',1)->firstOrfail();


        if ($edoUsers->userRole->role_code == 'director_department' ||
            $edoUsers->userRole->role_code == 'guide_manager' ||
            $edoUsers->userRole->role_code == 'deputy_of_director' ||
            $edoUsers->userRole->role_code == 'dep_helper' ||
            $edoUsers->userRole->role_code == 'filial_manager' ||
            $edoUsers->userRole->role_code == 'filial_office'){
            $models = EdoMessageUsers::where('depart_id', $department->department_id)
                ->where('sub_status' ,3)
                ->orderBy('id', 'DESC')
                ->get();
        } else {
            return response()->view('errors.' . '404', [], 404);
        }

        return view('edo.edo-message-users.directorTaskClosed',compact('models'));

    }

    public function directorTasksJournal()
    {
        //
        $edoUsers  = EdoUsers::where('user_id', Auth::id())->firstOrFail();

        $department = EdoUsers::where('user_id', Auth::id())->firstOrfail();

        if ($edoUsers->userRole->role_code == 'dep_helper' || $edoUsers->userRole->role_code == 'filial_office'){

            $search = EdoDepInboxJournals::where('depart_id', $department->department_id);

            $ofc = Input::get ( 'ofc' );
            $f = Input::get ( 'f' );
            $t = Input::get ( 't' );
            $r = Input::get ( 'r' );
            $d = Input::get ( 'd' );

            if($ofc) {
                $search->where('in_number', $ofc);
            }
            if($f) {
                $search->where('from_name', 'LIKE', '%'.$f.'%');
            }
            if($t) {
                $search->where('title', 'LIKE', '%'.$t.'%');
            }
            if($r) {
                $search->whereHas('message', function ($query) use ($r) {

                    $query->where('in_number', 'LIKE', '%'.$r.'%');

                });
            }
            if($d) {
                $search->whereHas('message', function ($query) use ($d) {

                    $query->where('out_number', 'LIKE', '%'.$d.'%');

                });
            }

            $models = $search->orderBy('created_at', 'DESC')->paginate(25);

        } else {
            return response()->view('errors.' . '404', [], 404);
        }

        return view('edo.edo-message-users.directorTaskJournal',compact('models','ofc','f','t','r','d'));

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

    public function viewRegDirectorTask($id, $slug)
    {
        // is read performer
        $messageUser = EdoMessageUsers::findOrFail($id);

        $messageUser->update(['is_read' => 1, 'read_date' => Carbon::now()]);

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

        // reg journals
        $department = EdoUsers::where('user_id', $messageUser->to_user_id)->firstOrFail();

        $regJournals = EdoDepInboxJournals::where('depart_id', $department->department_id)->orderBy('id', 'DESC')->first();

        if (!empty($regJournals->depart_id)){
            $inboxNum = $regJournals->in_number + 1;
        } else{
            $inboxNum = 1;
        }

        return view('edo.edo-message-users.viewRegDirectorTask',
            compact('model', 'messageUser', 'perfUsers', 'department', 'inboxNum'));
    }

    public function viewDirectorTask($id, $slug)
    {
        // is read performer
        $messageUser = EdoMessageUsers::findOrFail($id);

        $messageUser->update(['is_read' => 1, 'read_date' => Carbon::now()]);

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

        $edoUsers = EdoUsers::where('user_id', Auth::id())->first();

        // users
        $users = DB::table('edo_users as a')
            ->join('users as u', 'a.user_id', '=', 'u.id')
            ->join('edo_user_roles as r', 'a.role_id', '=', 'r.id')
            ->join('departments as d', 'u.depart_id', '=', 'd.id')
            ->select('a.*', 'u.id as u_id', 'u.branch_code', 'u.job_title',
                DB::raw('CONCAT(u.branch_code, " - ", lname, " ", fname) AS full_name'), 'r.title as roleName')
            ->where('d.depart_id', Auth::user()->department->depart_id)
            ->where('a.status', 1)
            ->orderBy('u.depart_id', 'ASC')
            ->get();
        
        $role = EdoUserRoles::where('id', $edoUsers->role_id)->first();

        $messageTypes = EdoTypeMessages::where('type_code', $role->role_code)->get();

        $tasks = HelperTask::where('user_id', Auth::id())->get();

        // performer sub users
        $perfSubUsers = DB::table('edo_type_messages as a')
            ->join('edo_message_sub_users as m', 'a.id', '=', 'm.performer_user')
            ->join('users as u', 'm.to_user_id', '=', 'u.id')
            ->select('a.title_ru','a.id as mes_type_id','u.id as user_id','u.branch_code','u.job_title',
                DB::raw('CONCAT(lname, " ", fname) AS full_name'))
            ->where('m.from_user_id', Auth::id())
            ->where('m.edo_message_id', $model->id)
            ->orderBy('a.sort', 'ASC')
            ->get();

        return view('edo.edo-message-users.viewDirectorTask',
            compact('model', 'users','messageTypes', 'messageUser', 'tasks', 'perfUsers', 'perfUserTypes',
                'perfSubUsers','role'));
    }

    public function viewDirectorTaskProcess($id, $slug)
    {
        // is read performer
        $messageUsers = EdoMessageUsers::find($id);

        // model message
        $model = EdoMessage::where('message_hash', $slug)->first();

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

        $tasks = HelperTask::all();

        $dep_id = Auth::user()->department->depart_id;

        // performer sub users
        $perfSubUsers = EdoMessageSubUsers::where('edo_message_id', $model->id)
        ->orderby('performer_user', 'desc')
        ->get();
        // dd($perfSubUsers);

        // reply message
        $replyMessage = EdoReplyMessage::where('edo_message_id', $model->id)->orderBy('director_id')->get();
        
        $replyMessageGroupBy = EdoReplyMessage::where('edo_message_id', $model->id)->groupBy('director_id')->get();

        $modelDepart = EdoDepInboxJournals::where('edo_message_id', $model->id)->where('depart_id', Auth::user()->department->depart_id)->first();
        
        return view('edo.edo-message-users.viewDirectorTaskProcess',
            compact('model', 'users','messageTypes', 'messageUsers', 'tasks', 'perfUsers', 'perfUserTypes',
                'perfSubUsers','role','replyMessage','replyMessageGroupBy','modelDepart'));

    }


    /**
     * Display the specified resource.
     *
     * @param  \App\EDOMessage  $eDOMessage
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        //
        $model = EdoMessageUsers::find($id);

        $model->update(['is_read' => 1, 'read_date' => Carbon::now()]);


        $perfUsers = DB::table('edo_message_users as a')
            ->join('users as u', 'a.to_user_id', '=', 'u.id')
            ->join('edo_type_messages as t', 'a.performer_user', '=', 't.id')
            ->select('a.*', 'u.id as u_id', 'u.branch_code', 'u.job_title', 't.title_ru',
                DB::raw('CONCAT(lname, " ", fname) AS full_name'))
            ->where('a.edo_mes_jrls_id', $model->edo_mes_jrls_id)
            ->orderBy('a.performer_user', 'DESC')
            ->get();

        $perfUserTypes = EdoTypeMessages::where('type_code', 'performers_in_helper')->get();

        if (empty($model)) {
            return response()->view('errors.' . '404', [], 404);
        }

        return view('edo.edo-message-users.show',compact('model', 'perfUsers', 'perfUserTypes'));
    }

    // reg task
    public function DepRegTask(Request $request)
    {
        //
        $this->validate($request, [
            'edo_message_id' => 'required',
            'depart_id' => 'required',
            'director_id' => 'required',
            'user_id' => 'required'
        ]);

        $input = $request->all();

        $model = new EdoDepInboxJournals($input);

        $model->save();

        // update status
        $message_id = $request->input('edo_message_id');

        $user_id = $request->input('director_id');

        $modelStatus = EdoMessageUsers::where('edo_message_id', $message_id)->where('to_user_id', $user_id);

        $modelStatus->update(['status' => 2]);

        return redirect()->route('d-tasks-reg')->with('success', 'Vazifa muvaffaqiyatli ro`yhatdan o`tdi');

    }

    // reg department task
    public function DepRegTask1(Request $request)
    {
        //
        $this->validate($request, [
            'edo_message_id' => 'required',
            'depart_id' => 'required',
            'director_id' => 'required',
            'user_id' => 'required'
        ]);

        $input = $request->all();

        $model = new EdoDepInboxJournals($input);

        $model->save();

        // update status
        $message_id = $request->input('edo_message_id');

        $user_id = $request->input('director_id');

        $modelStatus = EdoMessageJournal::where('edo_message_id', $message_id);

        $modelStatus->update(['status' => 1]);

        return back()->with('success', 'Vazifa muvaffaqiyatli ro`yhatdan o`tdi');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EDOMessage  $eDOMessage
     * @return \Illuminate\Http\Response
     */
    public function edit(EDOMessage $eDOMessage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EDOMessage  $eDOMessage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EDOMessage $eDOMessage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EDOMessage  $eDOMessage
     * @return \Illuminate\Http\Response
     */
    public function destroy(EDOMessage $eDOMessage)
    {
        //
    }
}
