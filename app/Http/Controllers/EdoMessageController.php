<?php

namespace App\Http\Controllers;

use App\EdoCancelMessage;
use App\EdoDepInboxJournals;
use App\EdoHelperMessage;
use App\EdoJournals;
use App\EdoMessage;
use App\EdoMessageFile;
use App\EdoMessageJournal;
use App\EdoMessageLogFile;
use App\EdoMessageUsers;
use App\EdoMessageSubUsers;
use App\EdoRedirectMessage;
use App\EdoReplyMessage;
use App\EdoReplyMessageFile;
use App\EdoTypeMessages;
use App\EdoUserRoles;
use App\EdoUsers;
use App\User;
use App\HelperTask;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EdoMessageController extends Controller
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

    public function index()
    {
        //
        return view('edo.edo-message.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $edoUsers = EdoUsers::where('user_id', Auth::id())->where('status', 1)->firstOrFail();
        # If user kanc or helper
        switch($edoUsers->role_id){

            case 1:
            case 2:
            case 3:
            case 4:
            case 6:
            case 17:
            case 18:
            case 19:
            $users = EdoUsers::select('edo_users.*', DB::raw('CONCAT(branch_code, " - ", lname, " ", fname) AS full_name'), 'r.title as roleName')
                ->join('users as u', 'edo_users.user_id', '=', 'u.id')
                ->join('edo_user_roles as r', 'edo_users.role_id', '=', 'r.id')
                ->where('edo_users.status', 1)
                ->whereIn('r.role_code', ['guide', 'director_department', 'guide_manager'])
                ->orderBy('edo_users.sort', 'ASC')
                ->get();

            $role = EdoUserRoles::where('id', $edoUsers->role_id)->firstOrFail();

            $messageTypes = EdoTypeMessages::where('type_code', $role->role_code)->get();

            $journals = EdoJournals::where('depart_id', $edoUsers->department_id)
                ->where('journal_type', 'inbox')
                ->where('status', 1)
                ->get();

            return view('edo.edo-message.create',compact('users','messageTypes', 'journals'));
            break;

            default:
                return response()->view('errors.' . '404', [], 404);
                break;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        //
        $to_user = $request->input('to_user_id');
        $edo_user = EdoUsers::where('user_id', $to_user)->first();
        $this->validate($request, [
            'message_type' => 'required',
            'from_name' => 'required',
            'text' => 'required'
        ]);

        $lastInsertId = EdoMessage::orderBy('id', 'DESC')->firstOrFail();
        $lastInsertId = $lastInsertId->id+1;

        $edoUsers = EdoUsers::where('user_id', Auth::id())->where('status', 1)->firstOrFail();
        // model Message
        $model = new EdoMessage();

        $model->from_name = $request->input('from_name');

        $model->title = $request->input('title');

        $model->text = $request->input('text');

        $model->in_number = $request->input('office_number').$request->input('office_number_a');

        $model->in_date = $request->input('in_date');

        $model->out_number = $request->input('out_number');

        $model->out_date = $request->input('out_date');

        $model->message_hash = sha1($lastInsertId).date('dmYHis');

        $model->urgent = $request->input('urgent');

        $model->save();

        // model Journal
        $modelJournal = new EdoMessageJournal();

        $modelJournal->user_id = Auth::id();

        $modelJournal->depart_id = $edoUsers->department_id;

        $modelJournal->to_user_id = $request->input('to_user_id');

        $modelJournal->message_view = $request->input('message_view');

        $modelJournal->message_type = $request->input('message_type');

        $modelJournal->edo_journal_id = $request->input('edo_journal_id');

        $modelJournal->in_number = $request->input('office_number');

        $modelJournal->in_number_a = $request->input('office_number_a');

        $modelJournal->edo_message_id = $model->id;

        $modelJournal->status = 0;

        $modelJournal->save();

        // model File
        if ($request->file('message_file') != null) {

            foreach ($request->file('message_file') as $file) {
                if ($file != 0) {
                    $today = Carbon::today();
                    $year = $today->year;
                    $month = $today->month;
                    $day = $today->day;
                    $path = 'edo/'.$year.'/'.$month.'/'.$day.'/';

                    $modelFile = new EdoMessageFile();

                    $modelFile->edo_message_id = $model->id;

                    $modelFile->file_path = $path;

                    $modelFile->file_hash = $model->id . '_' . Auth::id() . '_' . date('dmYHis') . uniqid() . '.' . $file->getClientOriginalExtension();

                    $modelFile->file_size = $file->getSize();

                    Storage::disk('ftp_edo')->put($path.$modelFile->file_hash, file_get_contents($file->getRealPath()));

                    $modelFile->file_name = $file->getClientOriginalName();

                    $modelFile->file_extension = $file->getClientOriginalExtension();

                    $modelFile->save();

                }

            }
        }

        if ($edo_user->userRole->role_code == 'director_department' || $edo_user->userRole->role_code == 'guide_manager') {
            $modelMU = new EdoMessageUsers();

            $modelMU->edo_message_id = $model->id;

            $modelMU->edo_mes_jrls_id = $modelJournal->id;

            $modelMU->from_user_id = Auth::id();

            $modelMU->to_user_id = $to_user;

            $modelMU->depart_id = $edo_user->department_id;

            $modelMU->performer_user = 14;

            $modelMU->status = 1;

            $modelMU->save();

            // helper message
            $modelHelperMessage = new EdoHelperMessage();

            $modelHelperMessage->edo_message_id = $model->id;

            $modelHelperMessage->edo_user_id = Auth::id();

            $modelHelperMessage->edo_type_message_id = 17;

            $modelHelperMessage->edo_message_journals_id = $modelJournal->id;

            $modelHelperMessage->text = 'text';

            $modelHelperMessage->save();

            // helper message status update
            $updateStatus = EdoMessageJournal::find($modelJournal->id);

            $updateStatus->update(['status' => 2]);

        }

        return back()->with('success', 'Sizning hujjatingiz muvaffaqiyatli jo`natildi');

    }

    // view guide task
    public function viewGuideTask($id)
    {
        //
        $model = EdoMessage::where('message_hash', $id)->firstOrFail();

        $users = EdoUsers::select('edo_users.*', 'r.title as roleName')
            ->join('edo_user_roles as r', 'edo_users.role_id', '=', 'r.id')
            ->where('edo_users.status', 1)
            ->whereIn('r.role_code', ['guide', 'guide_manager','director_department', 'all_dep_director'])
            ->orderBy('edo_users.sort', 'ASC')
            ->get();


        $filial_users = DB::table('edo_users as a')
            ->join('users as u', 'a.user_id', '=', 'u.id')
            ->join('edo_user_roles as r', 'a.role_id', '=', 'r.id')
            ->join('departments as d', 'u.branch_code', '=', 'd.branch_code')
            ->select('a.*', 'u.id as u_id', 'u.branch_code', 'u.job_title', 'd.title as branch_name',
                DB::raw('CONCAT(lname, " ", fname) AS full_name'), 'r.title as roleName','d.depart_id')
            ->where('a.status', 1)
            ->where('d.parent_id', 0)
            ->whereIn('r.role_code', ['filial_manager','all_filial_manager'])
            ->orderBy('a.sort', 'ASC')
            ->get();

        $redirectGuideUsers = DB::table('edo_users as a')
            ->join('users as u', 'a.user_id', '=', 'u.id')
            ->join('edo_user_roles as r', 'a.role_id', '=', 'r.id')
            ->select('a.*', 'u.id as u_id', 'u.branch_code', 'u.job_title',
                DB::raw('CONCAT(branch_code, " - ", lname, " ", fname) AS full_name'), 'r.title as roleName')
            ->where('a.status', 1)
            ->whereIn('r.role_code', ['guide'])
            ->orderBy('a.sort', 'ASC')
            ->get();

        $redirectTasks = EdoRedirectMessage::where('edo_message_id', $model->id)->where('status', 1)->get();

        $edoUsers = EdoUsers::where('user_id', Auth::id())->where('status', 1)->first();

        $role = EdoUserRoles::where('id', $edoUsers->role_id)->first();

        $messageTypes = EdoTypeMessages::where('type_code', $role->role_code)->orderBy('sort', 'ASC')->get();

        $perfUsers = EdoMessageUsers::select('edo_message_users.*','t.type_code','t.title_ru','t.sort as mu_sort',
            'u.user_sort', DB::raw('CONCAT(lname, " ", fname) AS full_name'))
            ->join('edo_type_messages as t', 't.id', '=', 'edo_message_users.performer_user')
            ->join('users as u', 'edo_message_users.to_user_id', '=', 'u.id')
            ->where('edo_message_users.edo_message_id', $model->id)
            ->orderBy('t.sort', 'ASC')
            ->get();

        $perfUserTypes = EdoTypeMessages::where('type_code', 'performers_in_helper')->get();

        $tasks = HelperTask::where('user_id', Auth::id())->get();

        $cancelModel = EdoCancelMessage::where('edo_message_id', $model->id)->get();

        return view('edo.edo-message.viewGuideTask',
            compact('model', 'users','filial_users','redirectGuideUsers', 'redirectTasks',
                'tasks', 'role', 'messageTypes', 'perfUsers', 'perfUserTypes','cancelModel'));
    }

    public function fileUpload(Request $request)
    {
        $validation = $this->validate($request, [
            'model_id' => 'required'
        ]);

        if($validation)
        {
            $files = $request->file('message_file');

            $model_id = $request->input('model_id');
            foreach ($files as $file) {
                if ($file != 0) {
                    $today = Carbon::today();
                    $year = $today->year;
                    $month = $today->month;
                    $day = $today->day;
                    $path = 'edo/'.$year.'/'.$month.'/'.$day.'/';

                    $modelFile = new EdoMessageFile();

                    $modelFile->edo_message_id = $model_id;

                    $modelFile->file_path = $path;

                    $modelFile->file_hash = $model_id . '_' . Auth::id() . '_' . date('dmYHis') . uniqid() . '.' . $file->getClientOriginalExtension();

                    $modelFile->file_size = $file->getSize();

                    Storage::disk('ftp_edo')->put($path.$modelFile->file_hash, file_get_contents($file->getRealPath()));

                    $modelFile->file_name = $file->getClientOriginalName();

                    $modelFile->file_extension = $file->getClientOriginalExtension();

                    $modelFile->save();

                    ($request->input('comments')) ? $comment = $request->input('comments') : $comment = null;
                    $modelLogFile = new EdoMessageLogFile();

                    $modelLogFile->edo_message_id = $model_id;

                    $modelLogFile->user_id = Auth::id();

                    $modelLogFile->file_type = 1;

                    $modelLogFile->file_name = $file->getClientOriginalName();

                    $modelLogFile->comments = $comment;

                    $modelLogFile->save();

                }

            }

           return response()->json(array(
                    'success' => true,
                    'message'   => 'File Successfully upload',
                    'class_name'  => 'alert-success',
                    'comment'   => $comment
                )
            );
        }
        else
        {
            return response()->json([
                'message'   => $validation->errors()->all(),
                'uploaded_image' => '',
                'class_name'  => 'alert-danger'
            ]);
        }

    }

    // edit guide task
    public function editGuideTask($id)
    {
        //
        $model = EdoMessage::where('message_hash', $id)->firstOrFail();

        $perfUsers = DB::table('edo_type_messages as a')
            ->join('edo_message_users as m', 'a.id', '=', 'm.performer_user')
            ->join('users as u', 'm.to_user_id', '=', 'u.id')
            ->join('departments as d', 'u.depart_id', '=', 'd.id')
            ->select('a.title_ru','a.id as mes_type_id','u.id as user_id','u.branch_code','u.job_title','m.to_user_id',
                'u.fname','u.lname', 'u.sname', 'm.depart_id','d.title as depart_name')
            ->where('m.edo_message_id', $model->id)
            ->where('m.sort', null)
            ->orderBy('a.sort', 'ASC')
            ->get();

        $all_dep_exists = EdoMessageUsers::where('edo_message_id', $model->id)->where('sort', 1)->get();
        $all_dep_name   = User::where('roles', '["all_dep_director"]')->first();

        $all_filial_exists = EdoMessageUsers::where('edo_message_id', $model->id)->where('sort', 2)->get();
        $all_filial_name   = User::where('roles', '["all_filial_manager"]')->first();


        $selectedUsers = $perfUsers->implode('to_user_id', ',');

        $explodeUsers = explode(',', $selectedUsers);


        $users = EdoUsers::select('edo_users.*', 'r.title as roleName', 'u.fname','u.lname')
            ->join('edo_user_roles as r', 'edo_users.role_id', '=', 'r.id')
            ->join('users as u', 'u.id', 'edo_users.user_id')
            ->where('edo_users.status', 1)
            ->where('edo_users.role_id','!=', 9)
            ->whereNotIn('edo_users.user_id', $explodeUsers)
            ->whereIn('r.role_code', ['guide', 'guide_manager','director_department', 'all_dep_director'])
            ->orderBy('edo_users.sort', 'ASC')
            ->get();


        $filial_users = DB::table('edo_users as a')
            ->join('users as u', 'a.user_id', '=', 'u.id')
            ->join('edo_user_roles as r', 'a.role_id', '=', 'r.id')
            ->join('departments as d', 'u.branch_code', '=', 'd.branch_code')
            ->select('a.*', 'u.id as u_id', 'u.branch_code', 'u.job_title', 'd.title as branch_name',
                DB::raw('CONCAT(lname, " ", fname) AS full_name'), 'r.title as roleName')
            ->where('a.status', 1)
            ->where('d.parent_id', 0)
            ->where('a.role_id','!=', 10)
            ->whereIn('r.role_code', ['filial_manager','all_filial_manager'])
            ->orderBy('a.sort', 'ASC')
            ->get();

        $edoUsers = EdoUsers::where('user_id', Auth::id())->where('status', 1)->first();

        $role = EdoUserRoles::where('id', $edoUsers->role_id)->first();

        $messageTypes = EdoTypeMessages::where('type_code', $role->role_code)->orderBy('sort', 'ASC')->get();

        $perfUserTypes = EdoTypeMessages::where('type_code', 'performers_in_helper')->get();

        $tasks = HelperTask::where('user_id', Auth::id())->get();

        if (empty($model)) {
            return response()->view('errors.' . '404', [], 404);
        }

        return view('edo.edo-message.editGuideTask',
            compact('model', 'users', 'filial_users', 'tasks', 'messageTypes', 'perfUsers', 'perfUserTypes',
            'all_dep_exists','all_dep_name', 'all_filial_exists', 'all_filial_name'
        ));
    }

    # Jamshid edit guide task to change
    public function editGuideTaskChange($id){
    //
            $model = EdoMessage::where('message_hash', $id)->firstOrFail();

        $perfUsers = DB::table('edo_type_messages as a')
            ->join('edo_message_users as m', 'a.id', '=', 'm.performer_user')
            ->join('users as u', 'm.to_user_id', '=', 'u.id')
            ->join('departments as d', 'u.depart_id', '=', 'd.id')
            ->select('a.title_ru','a.id as mes_type_id','u.id as user_id','u.branch_code','u.job_title','m.to_user_id',
                'u.fname','u.lname', 'u.sname', 'm.depart_id', 'm.is_read','m.sub_status' ,'d.title as depart_name')
            ->where('m.edo_message_id', $model->id)
            ->where('m.sort', null)
            ->orderBy('a.sort', 'ASC')
            ->get();

        $all_dep_exists = EdoMessageUsers::where('edo_message_id', $model->id)->where('sort', 1)->get();
        $all_dep_name   = User::where('roles', '["all_dep_director"]')->first();

        $all_filial_exists = EdoMessageUsers::where('edo_message_id', $model->id)->where('sort', 2)->get();
        $all_filial_name   = User::where('roles', '["all_filial_manager"]')->first();


        $selectedUsers = $perfUsers->implode('to_user_id', ',');

        $explodeUsers = explode(',', $selectedUsers);


        $users = EdoUsers::select('edo_users.*', 'r.title as roleName', 'u.fname','u.lname')
            ->join('edo_user_roles as r', 'edo_users.role_id', '=', 'r.id')
            ->join('users as u', 'u.id', 'edo_users.user_id')
            ->where('edo_users.status', 1)
            ->where('edo_users.role_id','!=', 9)
            ->whereNotIn('edo_users.user_id', $explodeUsers)
            ->whereIn('r.role_code', ['guide', 'guide_manager','director_department', 'all_dep_director'])
            ->orderBy('edo_users.sort', 'ASC')
            ->get();


        $filial_users = DB::table('edo_users as a')
            ->join('users as u', 'a.user_id', '=', 'u.id')
            ->join('edo_user_roles as r', 'a.role_id', '=', 'r.id')
            ->join('departments as d', 'u.branch_code', '=', 'd.branch_code')
            ->select('a.*', 'u.id as u_id', 'u.branch_code', 'u.job_title', 'd.title as branch_name',
                DB::raw('CONCAT(lname, " ", fname) AS full_name'), 'r.title as roleName')
            ->where('a.status', 1)
            ->where('d.parent_id', 0)
            ->where('a.role_id','!=', 10)
            ->whereIn('r.role_code', ['filial_manager','all_filial_manager'])
            ->orderBy('a.sort', 'ASC')
            ->get();

        $edoUsers = EdoUsers::where('user_id', Auth::id())->where('status', 1)->first();

        $role = EdoUserRoles::where('id', $edoUsers->role_id)->first();

        $messageTypes = EdoTypeMessages::where('type_code', $role->role_code)->orderBy('sort', 'ASC')->get();

        $perfUserTypes = EdoTypeMessages::where('type_code', 'performers_in_helper')->get();

        $tasks = HelperTask::where('user_id', Auth::id())->get();

        if (empty($model)) {
            return response()->view('errors.' . '404', [], 404);
        }

        return view('edo.edo-message.editGuideTaskChange',
            compact('model', 'users', 'filial_users', 'tasks', 'messageTypes', 'perfUsers', 'perfUserTypes',
            'all_dep_exists','all_dep_name', 'all_filial_exists', 'all_filial_name'
        ));
    }

    public function viewDirectorResolution($id)
    {
        //
        $model = EdoMessage::where('message_hash', $id)->firstOrFail();
        $edoUsers = EdoUsers::where('user_id', Auth::id())->where('status', 1)->firstOrFail();

        switch($edoUsers->role_id){
            case(1):
            case(2):
            case(3):
            case(4):
            case(6):
            case(17):
            case(18):
            case(19):
                $users = DB::table('edo_users as a')
                    ->join('users as u', 'a.user_id', '=', 'u.id')
                    ->join('edo_user_roles as r', 'a.role_id', '=', 'r.id')
                    ->select('a.*', 'u.id as u_id', 'u.branch_code', 'u.job_title',
                        DB::raw('CONCAT(branch_code, " - ", lname, " ", fname) AS full_name'), 'r.title as roleName')
                    ->where('a.status', 1)
                    ->where('a.department_id', Auth::user()->depart_id)
                    ->whereIn('r.role_code', ['department_emp'])
                    ->orderBy('a.sort', 'ASC')
                    ->get();

                $redirectDepartUsers = DB::table('edo_users as a')
                    ->join('users as u', 'a.user_id', '=', 'u.id')
                    ->join('edo_user_roles as r', 'a.role_id', '=', 'r.id')
                    ->select('a.*', 'u.id as u_id', 'u.branch_code', 'u.job_title',
                        DB::raw('CONCAT(branch_code, " - ", lname, " ", fname) AS full_name'), 'r.title as roleName')
                    ->where('a.status', 1)
                    ->whereIn('r.role_code', ['director_department'])
                    ->orderBy('a.sort', 'ASC')
                    ->get();

                $redirectTasks = EdoRedirectMessage::where('edo_message_id', $model->id)->where('status', 1)->get();

                $role = EdoUserRoles::where('id', $edoUsers->role_id)->first();

                $messageTypes = EdoTypeMessages::where('type_code', $role->role_code)->orderBy('sort', 'ASC')->get();

                $perfUsers = DB::table('edo_type_messages as a')
                    ->join('edo_message_users as m', 'a.id', '=', 'm.performer_user')
                    ->join('users as u', 'm.to_user_id', '=', 'u.id')
                    ->select('a.title_ru',
                        DB::raw('CONCAT(lname, " ", fname) AS full_name'))
                    ->where('m.edo_message_id', $model->id)
                    ->orderBy('a.sort', 'ASC')
                    ->get();

                $perfUserTypes = EdoTypeMessages::where('type_code', 'performers_in_helper')->get();

                $tasks = HelperTask::where('user_id', Auth::id())->get();

                // reg journals
                $department = EdoUsers::where('user_id', $edoUsers->user_id)->firstOrFail();
                $regJournals = EdoDepInboxJournals::where('depart_id', $department->department_id)->orderBy('id', 'DESC')->first();
                if (!empty($regJournals->depart_id)){
                    $inboxNum = $regJournals->in_number + 1;
                } else{
                    $inboxNum = 1;
                }

                if (empty($model)) {
                    return response()->view('errors.' . '404', [], 404);
                }

                return view('edo.edo-message.viewDirectorResolution',
                    compact('model', 'users','redirectDepartUsers', 'redirectTasks', 'department', 'inboxNum',
                        'tasks', 'role', 'messageTypes', 'perfUsers', 'perfUserTypes'));
            break;

            default:
                return response()->view('errors.' . '404', [], 404);
                break;
        }

    }

    // create guide task
    public function storeCreateGuideTask(Request $request)
    {
        //
        $this->validate($request, [
            'to_user_id' => 'required',
            'edo_type_message_id' => 'required',
            'text' => 'required'
        ]);

        // model Message
        $journal_id = $request->input('jrnl_id');

        $id = $request->input('model_id');


        $all_filial_managers = EdoUsers::where('role_id', 11)->get();
        $all_dir_dep = EdoUsers::where('role_id', 4)->orWhere('role_id', 19)->get();


        foreach ($request->input('to_user_id') as $key => $value) {

            if($value != 1134 ){
                foreach ($all_dir_dep as $k => $val1) {

                    if($val1->user_id == $value ){
                        unset($all_dir_dep[$k]);
                        break;
                    }
                }
            }

            if($value != 1135){
                foreach ($all_filial_managers as $k => $val1) {

                    if($val1->user_id == $value ){
                        unset($all_filial_managers[$k]);
                        break;
                    }
                }
            }

        }


        if ($request->input('to_user_id') != null) {

            foreach ($request->input('to_user_id') as $key => $user) {
                if ($user != 0) {
                    switch ($request->input('to_user_id')[$key]) {
                        case 1134:
                            foreach ($all_dir_dep as $key1 => $valDir) {
                                $model = new EdoMessageUsers();

                                $model->edo_message_id = $id;

                                $model->edo_mes_jrls_id = $journal_id;

                                $model->from_user_id = Auth::id();

                                $model->to_user_id = $valDir->user_id;

                                $model->depart_id = $valDir->department_id;

                                $model->performer_user = $request->input('performer_user')[$key];

                                $model->sort = 1;

                                $model->status = 0;

                                $model->save();
                            }

                            break;
                        case 1135:
                            foreach ($all_filial_managers as $key2 => $valFil) {
                                $model = new EdoMessageUsers();

                                $model->edo_message_id = $id;

                                $model->edo_mes_jrls_id = $journal_id;

                                $model->from_user_id = Auth::id();

                                $model->to_user_id = $valFil->user_id;

                                $model->depart_id = $valFil->department_id;

                                $model->performer_user = $request->input('performer_user')[$key];

                                $model->sort = 2;

                                $model->status = 0;

                                $model->save();
                            }

                            break;

                        default:
                            $model = new EdoMessageUsers();

                            $model->edo_message_id = $id;

                            $model->edo_mes_jrls_id = $journal_id;

                            $model->from_user_id = Auth::id();

                            $model->to_user_id = $user;

                            $model->depart_id = $request->input('depart_id')[$key];

                            $model->performer_user = $request->input('performer_user')[$key];

                            $model->status = 0;

                            $model->save();
                            break;
                    }


                }
            }
        }

        // helper message
        $modelHelperMessage = new EdoHelperMessage();

        $modelHelperMessage->edo_message_id = $id;

        $modelHelperMessage->edo_user_id = Auth::id();

        $modelHelperMessage->edo_type_message_id = $request->input('edo_type_message_id');

        $modelHelperMessage->edo_message_journals_id = $journal_id;

        $modelHelperMessage->term_date = $request->input('term_date');

        $modelHelperMessage->text = $request->input('text');

        $modelHelperMessage->save();

        // helper message status update

        $updateStatus = EdoMessageJournal::find($journal_id);

        $updateStatus->update(['status' => 1]);

        $message = EdoMessage::find($id);

        return redirect()->route('view-guide-task', $message->message_hash)
            ->with('success', 'Vazifa muvaffaqiyatli saqlandi');

    }

    // edit guide task
    public function storeEditGuideTask(Request $request)
    {
        //
        $this->validate($request, [
            'performer_user' => 'required',
            'to_user_id' => 'required'
        ]);

        // model Message
        $journal_id = $request->input('jrnl_id');

        $id = $request->input('model_id');

        $message_users = EdoMessageUsers::where('edo_mes_jrls_id', $journal_id);

        $message_users->delete();

        #############################################################
        $all_filial_managers = EdoUsers::where('role_id', 11)->get();
        $all_dir_dep = EdoUsers::where('role_id', 4)->orWhere('role_id', 19)->get();

        foreach ($request->input('to_user_id') as $key => $value) {

            if($value != 1134 ){
                foreach ($all_dir_dep as $k => $val1) {

                    if($val1->user_id == $value ){
                        unset($all_dir_dep[$k]);
                        break;
                    }
                }
            }

            if($value != 1135){
                foreach ($all_filial_managers as $k => $val1) {

                    if($val1->user_id == $value ){
                        unset($all_filial_managers[$k]);
                        break;
                    }
                }
            }

        }
        #############################################################
        if ($request->input('to_user_id') != null) {

            foreach ($request->input('to_user_id') as $key => $user) {
                if ($user != 0) {

                    switch ($request->input('to_user_id')[$key]) {
                        case 1134:
                            foreach ($all_dir_dep as $key1 => $valDir) {
                                $model = new EdoMessageUsers();

                                $model->edo_message_id = $id;

                                $model->edo_mes_jrls_id = $journal_id;

                                $model->from_user_id = Auth::id();

                                $model->to_user_id = $valDir->user_id;

                                $model->depart_id = $valDir->department_id;

                                $model->performer_user = $request->input('performer_user')[$key];

                                $model->sort = 1;

                                $model->status = 0;

                                $model->save();
                            }

                            break;
                        case 1135:
                            foreach ($all_filial_managers as $key2 => $valFil) {
                                $model = new EdoMessageUsers();

                                $model->edo_message_id = $id;

                                $model->edo_mes_jrls_id = $journal_id;

                                $model->from_user_id = Auth::id();

                                $model->to_user_id = $valFil->user_id;

                                $model->depart_id = $valFil->department_id;

                                $model->performer_user = $request->input('performer_user')[$key];

                                $model->sort = 2;

                                $model->status = 0;

                                $model->save();
                            }

                            break;

                        default:
                            $model = new EdoMessageUsers();

                            $model->edo_message_id = $id;

                            $model->edo_mes_jrls_id = $journal_id;

                            $model->from_user_id = Auth::id();

                            $model->to_user_id = $user;

                            $model->depart_id = $request->input('depart_id')[$key];

                            $model->performer_user = $request->input('performer_user')[$key];

                            $model->status = 0;

                            $model->save();
                            break;
                    }

                }
            }
        }

        // update helper message
        $helperInput = $request->only('edo_type_message_id', 'term_date', 'text');

        $helperMessage = EdoHelperMessage::where('edo_message_journals_id', $journal_id);

        $helperMessage->update($helperInput);

        $message = EdoMessage::find($id);

        return redirect()->route('view-guide-task', $message->message_hash)
            ->with('success', 'Vazifa muvaffaqiyatli yangilandi');
    }


    # Jamshid edit guide task to change Receivers
    public function storeEditGuideTaskChange(Request $request)
    {
            //
            $this->validate($request, [
                'performer_user' => 'required',
                'to_user_id' => 'required'
            ]);

            // model Message
            $journal_id = $request->input('jrnl_id');

            $id = $request->input('model_id');

            $message_users = EdoMessageUsers::where('edo_mes_jrls_id', $journal_id);

            $message_users->delete();

            #############################################################
            $all_filial_managers = EdoUsers::where('role_id', 11)->get();
            $all_dir_dep = EdoUsers::where('role_id', 4)->orWhere('role_id', 19)->get();

            foreach ($request->input('to_user_id') as $key => $value) {

                if($value != 1134 ){
                    foreach ($all_dir_dep as $k => $val1) {

                        if($val1->user_id == $value ){
                            unset($all_dir_dep[$k]);
                            break;
                        }
                    }
                }

                if($value != 1135){
                    foreach ($all_filial_managers as $k => $val1) {

                        if($val1->user_id == $value ){
                            unset($all_filial_managers[$k]);
                            break;
                        }
                    }
                }

            }
            #############################################################
            if ($request->input('to_user_id') != null) {

                foreach ($request->input('to_user_id') as $key => $user) {
                    if ($user != 0) {

                        switch ($request->input('to_user_id')[$key]) {
                            case 1134:
                                foreach ($all_dir_dep as $key1 => $valDir) {
                                    $model = new EdoMessageUsers();

                                    $model->edo_message_id = $id;

                                    $model->edo_mes_jrls_id = $journal_id;

                                    $model->from_user_id = Auth::id();

                                    $model->to_user_id = $valDir->user_id;

                                    $model->depart_id = $valDir->department_id;

                                    $model->performer_user = $request->input('performer_user')[$key];

                                    $model->sort = 1;

                                    $model->status = 0;

                                    $model->save();
                                }

                                break;
                            case 1135:
                                foreach ($all_filial_managers as $key2 => $valFil) {
                                    $model = new EdoMessageUsers();

                                    $model->edo_message_id = $id;

                                    $model->edo_mes_jrls_id = $journal_id;

                                    $model->from_user_id = Auth::id();

                                    $model->to_user_id = $valFil->user_id;

                                    $model->depart_id = $valFil->department_id;

                                    $model->performer_user = $request->input('performer_user')[$key];

                                    $model->sort = 2;

                                    $model->status = 0;

                                    $model->save();
                                }

                                break;

                            default:
                                $model = new EdoMessageUsers();

                                $model->edo_message_id = $id;

                                $model->edo_mes_jrls_id = $journal_id;

                                $model->from_user_id = Auth::id();

                                $model->to_user_id = $user;

                                $model->depart_id = $request->input('depart_id')[$key];

                                $model->performer_user = $request->input('performer_user')[$key];

                                $model->status = 0;

                                $model->save();
                                break;
                        }

                    }
                }
            }

            // update helper message
            $helperInput = $request->only('edo_type_message_id', 'term_date', 'text');

            $helperMessage = EdoHelperMessage::where('edo_message_journals_id', $journal_id);

            $helperMessage->update($helperInput);


        ################################## Last Added to change ##########################################

        $message = EdoMessage::find($id);
        EdoDepInboxJournals::where('edo_message_id', $message->id)->delete();
        EdoMessageSubUsers::where('edo_message_id', $message->id)->delete();
        $edoReplyMessage = EdoReplyMessage::where('edo_message_id', $message->id)->get();
        if(count($edoReplyMessage)){
            foreach ($edoReplyMessage as $key => $value) {
                EdoReplyMessageFile::where('edo_reply_message_id', $value->id)->delete();
            }
            EdoReplyMessage::where('edo_message_id', $message->id)->delete();
        }

        EdoMessageJournal::where('edo_message_id', $message->id)
              ->update(['status' => 1]);

        ################################## Last Added to change ##########################################


        return redirect()->route('view-guide-task', $message->message_hash)
            ->with('success', 'Vazifa muvaffaqiyatli yangilandi');
    }

    // guide confirm
    public function guideTaskConfirm($id)
    {
        // message journals
        $messageJournals = EdoMessageJournal::where('edo_message_id',$id);

        $messageJournals->update(['status' => 2]);

        // message users
        $messageUsers = EdoMessageUsers::where('edo_message_id', $id);

        $messageUsers->update(['status' => 1, 'from_user_id' => Auth::id()]);

        return redirect()->route('g-tasks-sent')
            ->with('success', 'Vazifa muvaffaqiyatli tasdiqlandi va ijrochilarga yuborildi!');
    }

    // edit helper
    public function editPerformer($id, $slug)
    {
        //
        // is read performer
        $isRead = EdoMessageUsers::find($id);

        $model = EdoMessage::where('message_hash', $slug)->firstOrFail();

        // performer sub users
        $perfSubUsers = DB::table('edo_type_messages as a')
            ->join('edo_message_sub_users as m', 'a.id', '=', 'm.performer_user')
            ->join('users as u', 'm.to_user_id', '=', 'u.id')
            ->select('a.title_ru','a.id as mes_type_id','u.id as user_id','u.branch_code','u.job_title','m.to_user_id',
                DB::raw('CONCAT(lname, " ", fname) AS full_name'))
            ->where('m.from_user_id', Auth::id())
            ->where('m.edo_message_id', $model->id)
            ->orderBy('a.sort', 'ASC')
            ->get();

        $selectedUsers = $perfSubUsers->implode('to_user_id', ',');

        $explodeUsers = explode(',', $selectedUsers);

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
            ->whereNotIn('a.user_id', $explodeUsers)
            ->where('a.status', 1)
            ->orderBy('a.sort', 'ASC')
            ->get();

        $edoUsers = EdoUsers::where('user_id', Auth::id())->where('status', 1)->first();

        $role = EdoUserRoles::where('id', $edoUsers->role_id)->first();

        $messageTypes = EdoTypeMessages::where('type_code', $role->role_code)->get();

        $perfUserTypes = EdoTypeMessages::where('type_code', 'performers_in_helper')->get();

        $tasks = HelperTask::all();

        if (empty($model)) {
            return response()->view('errors.' . '404', [], 404);
        }

        return view('edo.edo-message.editPerformer',
            compact('model', 'users', 'isRead', 'role', 'tasks', 'messageTypes', 'perfSubUsers', 'perfUserTypes'));
    }


    // view helper
    public function viewTaskProcess($id, $slug)
    {
        $edoUsers = EdoUsers::where('user_id', Auth::id())->where('status', 1)->firstOrFail();
        # If user kanc or helper
        switch(Auth::user()->edoUsers()){
            case 'guide':
            case 'office':
            case 'helper':
            case 'director_department':
            case 'admin':
            case 'deputy_of_director':
            case 'dep_helper':
            case 'guide_manager':

                $model = EdoMessage::where('message_hash', $slug)->firstOrFail();

                $users = DB::table('edo_users as a')
                    ->join('users as u', 'a.user_id', '=', 'u.id')
                    ->join('edo_user_roles as r', 'a.role_id', '=', 'r.id')
                    ->select('a.*', 'u.id as u_id', 'u.branch_code', 'u.job_title',
                        DB::raw('CONCAT(branch_code, " - ", lname, " ", fname) AS full_name'), 'r.title as roleName')
                    ->where('a.status', 1)
                    ->whereIn('r.role_code', ['guide', 'director_department'])
                    ->orderBy('a.sort', 'ASC')
                    ->get();

                $role = EdoUserRoles::where('id', $edoUsers->role_id)->first();

                $messageTypes = EdoTypeMessages::where('type_code', $role->role_code)->orderBy('sort', 'ASC')->get();

                $perfUsers = EdoMessageUsers::select('edo_message_users.*','t.type_code','t.title_ru','t.sort as mu_sort',
                    'u.user_sort', DB::raw('CONCAT(lname, " ", fname) AS full_name'))
                    ->join('edo_type_messages as t', 't.id', '=', 'edo_message_users.performer_user')
                    ->join('users as u', 'edo_message_users.to_user_id', '=', 'u.id')
                    ->where('edo_message_users.edo_message_id', $model->id)
                    ->orderBy('t.sort', 'ASC')
                    ->get();

                $perfUserTypes = EdoTypeMessages::where('type_code', 'performers_in_helper')->get();

                $tasks = HelperTask::where('user_id', Auth::id())->get();

                return view('edo.edo-message.viewTaskProcess',
                    compact('model', 'users', 'tasks', 'role', 'messageTypes', 'perfUsers', 'perfUserTypes'
                ));
            break;

            default:
                return response()->view('errors.' . '404', [], 404);
            break;
        }
    }

    // download edo file
    public function fileDownload($id)
    {
        //
        $model = EdoMessageFile::find($id);

        $path = '/'.$model->file_path.$model->file_hash;

        if (Storage::disk('ftp_edo')->exists($path)){

            return Storage::disk('ftp_edo')->download($path, $model->file_name);
        }

        return back()->with('errors', 'Ilova (lar) Serverdan topilmadi!');
    }

    public function fileView($id)
    {
        //
        $model = EdoMessageFile::find($id);

        $path = '/'.$model->file_path.$model->file_hash;

        if (Storage::disk('ftp_edo')->exists($path)){

            $res = Storage::disk('ftp_edo')->get($path);

            if (strtoupper($model->file_extension) == 'PDF'){

                return Response::make($res, 200, [
                    'Content-Type'        => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="'.$model->file_name.'"'
                ]);

            } elseif (strtoupper($model->file_extension) == 'PNG' || strtoupper($model->file_extension) == 'JPG' ||
                strtoupper($model->file_extension) == 'JPEG'){

                return Response::make($res, 200, [
                    'Content-Type'        => 'image/png',
                    'Content-Disposition' => 'inline; filename="'.$model->file_name.'"'
                ]);
            } else {

                return Storage::disk('ftp_edo')->download($path, $model->file_name);
            }
        }

        return response()->json('Ilova (lar) Serverdan topilmadi!');

    }

    // download edo reply file
    public function fileReplyDownload($id){

        $model = EdoReplyMessageFile::find($id);

        $path = '/'.$model->file_path.$model->file_hash;

        if (Storage::disk('ftp_edo')->exists($path)){

            return Storage::disk('ftp_edo')->download($path, $model->file_name);
        }

        return back()->with('errors', 'Ilova (lar) Serverdan topilmadi!');
    }

    public function fileReplyView($id)
    {
        //
        $model = EdoReplyMessageFile::find($id);

        $path = '/'.$model->file_path.$model->file_hash;

        if (Storage::disk('ftp_edo')->exists($path)){

            $res = Storage::disk('ftp_edo')->get($path);

            if (strtoupper($model->file_extension) == 'PDF'){

                return Response::make($res, 200, [
                    'Content-Type'        => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="'.$model->file_name.'"'
                ]);

            } elseif (strtoupper($model->file_extension) == 'PNG' || strtoupper($model->file_extension) == 'JPG' ||
                strtoupper($model->file_extension) == 'JPEG'){

                return Response::make($res, 200, [
                    'Content-Type'        => 'image/png',
                    'Content-Disposition' => 'inline; filename="'.$model->file_name.'"'
                ]);
            } else {

                return Storage::disk('ftp_edo')->download($path, $model->file_name);
            }
        }

        return response()->json('Ilova (lar) Serverdan topilmadi!');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EDOMessage  $eDOMessage
     * @return \Illuminate\Http\Response
     */
    public function show(EDOMessage $eDOMessage)
    {
        //
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        (isset($_GET['comments'])) ?  $comment = $_GET['comments'] : $comment = null;

        //
        $model = EdoMessageFile::find($id);

        // log file
        $modelLogFile = new EdoMessageLogFile();

        $modelLogFile->edo_message_id = $model->edo_message_id;

        $modelLogFile->user_id = Auth::id();

        $modelLogFile->file_type = 0;

        $modelLogFile->file_name = $model->file_name;

        $modelLogFile->comments = $comment;

        $modelLogFile->save();

        $path = '/'.$model->file_path.$model->file_hash;

        if (Storage::disk('ftp_edo')->exists($path)){

            Storage::disk('ftp_edo')->delete($path);
        }

        $model->delete();

        return response()->json(array(
                'success' => true,
                'message' => 'File Successfully deleted',
                'comment' => $comment
            )
        );
    }
}
