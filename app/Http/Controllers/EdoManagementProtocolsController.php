<?php

namespace App\Http\Controllers;

use App\EdoManagementMembers;
use App\EdoManagementProtocolMembers;
use App\EdoManagementProtocols;
use App\EdoProtocolFiles;
use App\EdoUsers;
use App\User;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class EdoManagementProtocolsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $admin = EdoUsers::where('user_id', Auth::id())->firstOrFail();

        if ($admin->role->role_code == 'admin')
        {
            $models = EdoManagementMembers::all();

        } else {
            return response()->view('errors.' . '404', [], 404);
        }

        return view('edo.edo-management-protocols.index',compact('models'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $admin = EdoUsers::where('user_id', Auth::id())->firstOrFail();
        if ($admin->role->role_code == 'admin')
        {
            $users = User::select('id', DB::raw('CONCAT(branch_code, " - ", lname, " ", fname) AS full_name'))->where('status', 1)->get();

            return view('edo.edo-management-protocols.create',compact('users'));

        } else {

            return response()->view('errors.' . '404', [], 404);

        }
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
        $this->validate($request, [
            'user_id'   => 'required',
            'qr_name'   => 'required',
            'title'     => 'required',
            'user_sort' => 'required',
            'status'    => 'required'
        ]);

        $model              = new EdoManagementMembers();
        $model->user_id     = $request->input('user_id');
        $model->qr_name     = $request->input('qr_name');
        $model->title       = $request->input('title');
        $model->user_sort   = $request->input('user_sort');
        $model->status      = $request->input('status');
        $model->save();

        $model_hash = EdoManagementMembers::find($model->id);
        $model_hash->update(['qr_hash' => strtolower(substr(sha1($model->id), -8))]);


        return back()->with('success', 'Management user muvaffaqiyatli yaratildi');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        //
        $model = EdoManagementProtocolMembers::find($id);

        $model->update(['status' => 2]);

        if($model->user_role == 1){

            $model_protocol = EdoManagementProtocols::find($model->protocol_id);
            $model_protocol->update(['status' => 3]);
        }

        return response()->json(array(
                'success' => true,
                'msg' => 'Vazifa muvaffaqiyatli tasdiqlandi')
        );
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
        $admin = EdoUsers::where('user_id', Auth::id())->firstOrFail();

        if ($admin->role->role_code == 'admin')
        {

            $users = User::select('id', DB::raw('CONCAT(branch_code, " - ", lname, " ", fname) AS full_name'))->where('status', 1)->get();

            $model = EdoManagementMembers::find($id);

        }

        if (empty($model)) {
            return response()->view('errors.' . '404', [], 404);
        }

        return view('edo.edo-management-protocols.edit',
            compact('model','users'));
    }

    public function qrAccount($name, $hash)
    {
        $model = EdoManagementMembers::where('qr_name', '=', $name)
            ->where('qr_hash', '=', $hash)
            ->where('status', 1)
            ->first();

        if (empty($model)) {
            print_r('Not found user'); die;
        }
        // count() //
        @include('count_message.php');

        return view('edo.edo-management-protocols.qrAccount',
            compact('model','inbox_count','sent_count','term_inbox_count','all_inbox_count'));
    }

    public function indexProtocol()
    {
        //
        $admin = EdoUsers::where('user_id', Auth::id())->firstOrFail();

        if ($admin->role->role_code == 'admin' || $admin->role->role_code == 'helper' || $admin->role->role_code == 'secretary_management' || strpos( Auth::user()->roles, 'secretary'))
        {
            $department = Auth::user()->department->depart_id ?? 0;

            $models = EdoManagementProtocols::where('depart_id', $department)->orderBy('created_at', 'DESC')->get();

        } else {

            return response()->view('errors.' . '404', [], 404);

        }

        return view('edo.edo-management-protocols.indexProtocol',compact('models', 'admin'));
    }

    public function memberProtocol()
    {
        //
        $user = EdoManagementMembers::where('user_id', Auth::id())->first();

        if ($user != null || Auth::id() == 199 )
        {

            $models = EdoManagementProtocolMembers::where('user_id', Auth::id())
                ->whereHas('protocol', function($query){
                    $query->where('protocol_type',null);
                })
                ->whereIn('status', [-1,1,2,3])
                ->orderBy('created_at', 'DESC')->get();
            $unsigned_count = $models->count();
            $signed_count = EdoManagementProtocolMembers::whereHas('protocol', function($query){
                    $query->where('protocol_type', null);
                })
                ->where('status', '!=', 1)
                ->orderBy('created_at', 'DESC')
                ->count();

            return view('edo.edo-management-protocols.memberProtocol',compact('models','unsigned_count', 'signed_count'));

        }
        else
        {
            return response()->view('errors.' . '404', [], 404);

        }
    }

    public function hrMemberProtocol(Request $request, $protocol_dep)
    {
        //
        // dd($protocol_dep);
        $user = EdoManagementMembers::where('user_id', Auth::id())->first();
        $edo_user = EdoUsers::where('user_id', Auth::id())->firstOrFail();
        
        $current_user_id = Auth::id();

        $type = ($request->input('type')) ? $request->input('type') : 'new';

        if($edo_user->role_id == 3){
            $current_user_id = Auth::user()->edoHelperParent->user_id;
            $user = EdoManagementMembers::where('user_id', Auth::user()->edoHelperParent->user_id)->first();
        }

        if ($user != null || Auth::user()->isAdmin() || $edo_user->role_id == 3 )
        {

            $reg_num    = $request->input('reg_num');
            $title      = $request->input('title');
            $date       = $request->input('date');
    

            $search = EdoManagementProtocolMembers::where('user_id', $current_user_id)
                ->whereHas('protocol', function($query) use($protocol_dep){
                    $query->where('depart_id', $protocol_dep);
                });
            
            if($reg_num){
                $search->whereHas('protocol', function($query) use($reg_num){

                    $query->where('stf_number', 'like', '%'.$reg_num.'%');
                
                });
            }  
            if($title){
                $search->whereHas('protocol', function($query) use($title){

                    $query->where('title', 'like', '%'.$title.'%');
                
                });
            }  
            if($date){
                $search->whereHas('protocol', function($query) use($date){

                    $query->where('stf_date', $date);
                
                });
            }

            if($type == 'new')
            {
                $search->where('status', 1);
                $new_count          = EdoManagementProtocolMembers::whereHas('protocol', function($query) use($protocol_dep){
                        $query->where('depart_id', $protocol_dep);
                    })
                    ->where('user_id', $user->user_id)
                    ->where('status', 1)
                    ->count();

                $on_process_count   = EdoManagementProtocolMembers::whereHas('protocol', function($query) use($protocol_dep){
                        $query->where('depart_id', $protocol_dep);
                        $query->where('status', 2);
                    })
                    ->where('user_id', $user->user_id)
                    ->whereIn('status', [2,-1])
                    ->count();

                $archive_count      = EdoManagementProtocolMembers::whereHas('protocol', function($query) use($protocol_dep){
                        $query->where('depart_id', $protocol_dep);
                        $query->where('status', 3);
                    })
                    ->where('user_id', $user->user_id)
                    ->where('status', 2)
                    ->count();
                
                $type = 'new';
                
            }
            elseif($type == 'on_process')
            {
                $search->whereIn('status', [2,-1]);
                $search->whereHas('protocol', function($query){
                    $query->where('status', 2);
                });
                $on_process_count   = EdoManagementProtocolMembers::whereHas('protocol', function($query) use($protocol_dep){
                        $query->where('depart_id', $protocol_dep);
                        $query->where('status', 2);
                    })
                    ->where('user_id', $user->user_id)
                    ->whereIn('status', [2,-1])
                    ->orderBy('created_at', 'DESC')
                    ->count();
                
                $new_count          = EdoManagementProtocolMembers::whereHas('protocol', function($query) use($protocol_dep){
                        $query->where('depart_id', $protocol_dep);
                    })
                    ->where('user_id', $user->user_id)
                    ->where('status', 1)
                    ->count();
                $archive_count      = EdoManagementProtocolMembers::whereHas('protocol', function($query) use($protocol_dep){
                        $query->where('depart_id', $protocol_dep);
                        $query->where('status', 3);
                    })
                    ->where('user_id', $user->user_id)
                    ->where('status', 2)
                    ->count();
                $type = 'on_process';
                
            }
            else
            {
                $search->where('status', 2);
                $search->whereHas('protocol', function($query){
                    $query->where('status', 3);
                });
                $archive_count      = EdoManagementProtocolMembers::whereHas('protocol', function($query) use($protocol_dep){
                        $query->where('depart_id', $protocol_dep);
                        $query->where('status', 3);
                    })
                    ->where('user_id', $user->user_id)
                    ->where('status', 2)
                    ->count();

                $on_process_count   = EdoManagementProtocolMembers::whereHas('protocol', function($query) use($protocol_dep){
                        $query->where('depart_id', $protocol_dep);
                        $query->where('status', 2);
                    })
                    ->where('user_id', $user->user_id)
                    ->whereIn('status', [2,-1])
                    ->orderBy('created_at', 'DESC')
                    ->count();
                $new_count          = EdoManagementProtocolMembers::whereHas('protocol', function($query) use($protocol_dep){
                        $query->where('depart_id', $protocol_dep);
                    })
                    ->where('user_id', $user->user_id)
                    ->where('status', 1)
                    ->count();
                $type = 'archive';
                
            }
            $models = $search->orderBy('created_at', 'DESC')->paginate(15);

            $models->appends ( array (
                'type'      => $type,
                'reg_num'   => $reg_num,
                'title'     => $title,                
                'date'      => $date,
                'protocol_dep' => $protocol_dep
            ) );


            return view('edo.edo-management-protocols.hrMemberProtocol',compact('models','protocol_dep', 'type','new_count', 'on_process_count','archive_count',
            'type','reg_num','title','date'));
        
        }
        else
        {
            return response()->view('errors.' . '404', [], 404);

        }
    }

    public function viewProtocol($id, $hash)
    {
        //
        $admin = EdoUsers::where('user_id', Auth::id())->first();

        if ($admin->role->role_code == 'admin' || $admin->role->role_code == 'helper' || $admin->role->role_code == 'secretary_management')
        {

            $model = EdoManagementProtocols::where('id', $id)->where('protocol_hash', $hash)->first();

            $guide = EdoManagementProtocolMembers::where('protocol_id', $id)->where('user_role', 1)->first();

            return view('edo.edo-management-protocols.viewProtocol',compact('model', 'guide'));

        }
        else
        {
            return response()->view('errors.' . '404', [], 404);

        }

    }

    // member view
    public function viewMProtocol($id, $hash)
    {
        //
        $user = EdoManagementMembers::where('user_id', Auth::id())->first();
        $edo_role = Auth::user()->edoUsers();
        if ($user != null || $edo_role == 'helper')
        {

            $model = EdoManagementProtocols::where('id', $id)->where('protocol_hash', $hash)->first();

            $guide = EdoManagementProtocolMembers::where('protocol_id', $id)->where('user_role', 1)->first();

            $model_files = EdoProtocolFiles::where('protocol_id', $id)->get();

            return view('edo.edo-management-protocols.viewMProtocol',compact('model', 'guide', 'model_files'));

        }
        else
        {
            return response()->view('errors.' . '404', [], 404);
        }

    }

    // member confirm
    public function confirmProtocol(Request $request)
    {
        //
        $id = $request->input('member_id');

        $user = EdoManagementMembers::where('user_id', Auth::id())->first();

        if ($user != null)
        {

            // update member
            $model = EdoManagementProtocolMembers::find($id);

            $model->update(['status' => 2]);

            // update protocol
            $protocol = EdoManagementProtocols::find($model->protocol_id);

            $protocol->update(['status' => 2]);

            return response()->json(array(
                    'success' => true,
                    'msg' => 'Boshqaruv qarori muvaffaqiyatli tasdiqlandi')
            );

        }
        else
        {
            return response()->view('errors.' . '404', [], 404);

        }

    }

    // protocol create
    public function createProtocol()
    {
        //
        $users = EdoManagementMembers::where('status', 1)->orderBy('user_sort', 'ASC')->get();

        return view('edo.edo-management-protocols.createProtocol',
            compact('users'));
    }

    public function storeProtocol(Request $request)
    {
        //
        $this->validate($request, [
            'title'      => 'required',
            'to_user_id' => 'required',
            'text'       => 'required'
        ]);

        $depart = User::find(Auth::id());

        $depart = $depart->department->depart_id ?? 0;

        // model management protocol
        $model                  = new EdoManagementProtocols();
        $model->user_id         = Auth::id();
        $model->to_user_id      = $request->input('emp_user_id');
        $model->user_view       = $request->input('user_view')??'0';
        $model->depart_id       = $depart;
        $model->protocol_type   = $depart;
        $model->title           = $request->input('title');
        $model->text            = $request->input('text');
        $model->protocol_hash   = sha1(date('dmYHis')).date('dmYHis');
        $model->status          = 1;
        $model->save();

        $memberStatus = 1;
        if (!empty($request->input('emp_user_id'))) {

            $memberStatus = 0;
        }

        // model management members
        if ($request->input('to_user_id') != null) {

            foreach ($request->input('to_user_id') as $key => $user) {
                if ($user != 0) {

                    $member                 = new EdoManagementProtocolMembers();
                    $member->protocol_id    = $model->id;
                    $member->user_id        = $user;
                    $member->user_role      = $request->input('user_role')[$key];
                    $member->user_sort      = $request->input('user_sort')[$key];
                    $member->status         = 0;
                    $member->save();

                }
            }
        }

        if ($request->hasFile('protocol_file')){
            foreach ($request->file('protocol_file') as $file) {

                $file->store('protocol_files', 'public');

                $newFile                    = new EdoProtocolFiles();
                $newFile->protocol_id       = $model->id;
                $newFile->file_name         = $file->getClientOriginalName();
                $newFile->file_hash         = $file->hashName();
                $newFile->file_extension    = $file->extension();
                $newFile->file_size         = $file->getSize();
                $newFile->save();

            }

        }

        return back()->with('success', 'Sizning hujjatingiz muvaffaqiyatli jo`natildi');
    }

    // update protocol
    public function storeEditProtocol(Request $request)
    {
        //
        $this->validate($request, [
            'to_user_id' => 'required',
            'title' => 'required',
            'text' => 'required'
        ]);

        // model edit
        $id = $request->input('model_id');

        $model = EdoManagementProtocols::find($id);

        $model->user_id = Auth::id();

        $model->title = $request->input('title');

        $model->text = $request->input('text');

        $model->update();

        // delete members
        $deleteMember = EdoManagementProtocolMembers::where('protocol_id', $id);

        $deleteMember->delete();

        // model management members

        if ($request->input('to_user_id') != null) {

            foreach ($request->input('to_user_id') as $key => $user) {
                if ($user != 0) {
                    $member = new EdoManagementProtocolMembers();

                    $member->protocol_id = $id;

                    $member->user_id = $user;

                    $member->user_role = $request->input('user_role')[$key];

                    $member->user_sort = $request->input('user_sort')[$key];

                    $member->status = 1;

                    $member->save();

                }
            }
        }

        return back()->with('success', 'Boshqaruv qarori muvaffaqiyatli yangilandi');
    }

    // update protocol
    public function cancelProtocol(Request $request)
    {
        //
        $this->validate($request, [
            'model_id' => 'required',
            'cancel_desc' => 'required'
        ]);

        // model edit
        $id = $request->input('model_id');

        $model = EdoManagementProtocolMembers::where('protocol_id', $id)->where('user_id', Auth::id())->first();

        $model->user_id = Auth::id();

        $model->status = '-1';

        $model->descr = $request->input('cancel_desc');

        $model->update();

        return back()->with('success', 'Sizning e`tirozingiz muvaffaqiyatli jo`natildi');

    }

    public function editProtocol($id, $hash)
    {
        //
        $admin = EdoUsers::where('user_id', Auth::id())->first();

        if ($admin->role->role_code == 'admin' || $admin->role->role_code == 'secretary_management')
        {

            $model = EdoManagementProtocols::where('id', $id)->where('protocol_hash', $hash)->first();

            $selectedUsers = EdoManagementProtocolMembers::where('protocol_id', $id)->orderBy('user_sort', 'ASC')->get();

            $selected = $selectedUsers->implode('user_id', ',');
            $explode = explode(',', $selected);

            $users = EdoManagementMembers::whereNotIn('user_id', $explode)->where('status', 1)->orderBy('user_sort', 'ASC')->get();

            return view('edo.edo-management-protocols.editProtocol',compact('model', 'selectedUsers', 'users'));

        }
        else
        {
            return response()->view('errors.' . '404', [], 404);

        }
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
        $request->validate([
            'user_id'   => 'required',
            'qr_name'   => 'required',
            'title'     => 'required',
            'user_sort' => 'required',
            'status'    => 'required'
        ]);

        $data = $request->all();

        $model = EdoManagementMembers::find($id);

        $model->update($data);

        return back()->with('success', 'Management user muvaffaqiyatli yangilandi');
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
        EdoManagementMembers::find($id)->delete();

        return back()->with('success', 'Management user muvaffaqiyatli tizimdan o`chirildi');
    }


    // staff

    // my
    public function myProtocol()
    {
        //
        $models = EdoManagementProtocols::where('to_user_id', Auth::id())
            ->whereIn('status', [-1,0, 2, 3])
            ->where('user_view', 1)
            ->orderBy('created_at', 'DESC')->get();

        return view('edo.edo-staff-protocols.myProtocol',compact('models'));
    }

    public function staffProtocol(Request $request)
    {
        //
        EdoUsers::where('user_id', Auth::id())->firstOrFail();

        $type       = ($request->input('type')) ? $request->input('type') : 'new';
        $user_roles = json_decode(Auth::user()->roles);

        $access_roles = [
            0 => 'admin',
            1 => 'main_staff',
            2 => 'main_staff_emp',
            3 => 'bank_apparat',
            4 => 'bank_apparat_emp',
            5 => 'strategy',
            6 => 'strategy_emp',
            7 => 'kazna',
            8 => 'kazna_emp',
        ];

        $director_roles = [
            0 => 'main_staff',
            1 => 'bank_apparat',
            2 => 'strategy',
            3 => 'kazna'
        ];


        if(array_intersect($access_roles, $user_roles)){

            $memberStatus = 0;
            $department = Auth::user()->department->depart_id ?? 0;

            if(array_intersect($director_roles, $user_roles)){
                $memberStatus = 1;
            }

            $search = EdoManagementProtocols::where('depart_id', $department);

            $reg_num    = $request->input('reg_num');
            $title      = $request->input('title');
            $date       = $request->input('date');

            if($reg_num)    $search->where('stf_number', 'like', '%'.$reg_num.'%');

            if($title)      $search->where('title', '%'.$title.'%');

            if($date)       $search->where('stf_date', $date);


            if($type == 'new')
            {
                $search->where('status', 1);
                $new_count          = EdoManagementProtocols::where('depart_id', $department)->where('status', 1)->orderBy('created_at', 'DESC')->count();
                $on_process_count   = EdoManagementProtocols::where('depart_id', $department)->whereIn('status', [2,-1])->orderBy('created_at', 'DESC')->count();
                $archive_count      = EdoManagementProtocols::where('depart_id', $department)->where('status', 3)->orderBy('created_at', 'DESC')->count();
                
            }
            elseif($type == 'on_process')
            {
                $search->whereIn('status', [2,-1]);
                $on_process_count   = EdoManagementProtocols::where('depart_id', $department)->whereIn('status', [2,-1])->orderBy('created_at', 'DESC')->count();
                $new_count          = EdoManagementProtocols::where('depart_id', $department)->where('status', 1)->orderBy('created_at', 'DESC')->count();
                $archive_count      = EdoManagementProtocols::where('depart_id', $department)->where('status', 3)->orderBy('created_at', 'DESC')->count();
                
            }
            else
            {
                $search->where('status', 3);
                $archive_count      = EdoManagementProtocols::where('depart_id', $department)->where('status', 3)->orderBy('created_at', 'DESC')->count();
                $on_process_count   = EdoManagementProtocols::where('depart_id', $department)->whereIn('status', [2,-1])->orderBy('created_at', 'DESC')->count();
                $new_count          = EdoManagementProtocols::where('depart_id', $department)->where('status', 1)->orderBy('created_at', 'DESC')->count();
                
            }
            $models = $search->orderBy('created_at', 'DESC')->paginate(15);

            $models->appends ( array (
                'type'      => $type,
                'reg_num'   => $reg_num,
                'title'     => $title,                
                'date'      => $date                
            ) );

        }else {

            return response()->view('errors.' . '404', [], 404);

        }

        return view('edo.edo-staff-protocols.staffProtocol',compact('models','memberStatus', 'type', 'new_count', 'on_process_count','archive_count',
            'type','reg_num','title','date'));

    }


    public function editStfProtocol($id, $hash)
    {
        //
        $model = EdoManagementProtocols::where('id', $id)->where('protocol_hash', $hash)->first();

        $model_files = EdoProtocolFiles::where('protocol_id', $model->id)->get();

        $selectedUsers = EdoManagementProtocolMembers::where('protocol_id', $id)->orderBy('user_sort', 'ASC')->get();

        $selected = $selectedUsers->implode('user_id', ',');
        $explode = explode(',', $selected);

        $users = EdoManagementMembers::whereNotIn('user_id', $explode)->where('status', 1)->orderBy('user_sort', 'ASC')->get();

        $userEmp = User::select('id', 'depart_id', DB::raw('CONCAT(branch_code, " - ", lname, " ", fname) AS full_name'))->where('status', 1)->get();

        return view('edo.edo-staff-protocols.editStfProtocol',compact('model','model_files', 'selectedUsers', 'userEmp', 'users'));

    }

    // Delete Protocol
    public function deleteStfProtocol($id)
    {
        $model = EdoManagementProtocols::find($id);
        if($model){
            $model->delete();
        }else{
            return back()->with('error', 'Protocol jadvaldan topilmadi!');
        }
        $molel_members = EdoManagementProtocolMembers::where('protocol_id', $model->id)->get();
        if($model) EdoManagementProtocolMembers::where('protocol_id', $model->id)->delete();
        
        return back()->with('success', 'Protocol muvaffaqiyatli o`chirildi');
    }
    
    // update stf protocol
    public function storeEditStfProtocol(Request $request)
    {
        //

        $this->validate($request, [
            'title' => 'required',
            'text' => 'required'
        ]);

        // model edit
        $depart = User::find(Auth::id());
        $depart = $depart->department->depart_id ?? 0;

        $id = $request->input('model_id');

        $empUser = $request->input('emp_user_id');

        $model = EdoManagementProtocols::find($id);

        $model->user_id         = Auth::id();
        $model->user_view       = $request->input('user_view')??'0';
        $model->to_user_id      = $empUser;
        $model->depart_id       = $depart;
        $model->protocol_type   = $depart;
        $model->title           = $request->input('title');
        $model->text            = $request->input('text');
        $model->status          = 1;
        $model->update();

        // delete members
        $deleteMember = EdoManagementProtocolMembers::where('protocol_id', $id);

        $deleteMember->delete();

        // model management members
        if ($request->input('to_user_id') != null) {

            foreach ($request->input('to_user_id') as $key => $user) {
                if ($user != 0) {
                    $member = new EdoManagementProtocolMembers();

                    $member->protocol_id = $id;

                    $member->user_id = $user;

                    $member->user_role = $request->input('user_role')[$key];

                    $member->user_sort = $request->input('user_sort')[$key];

                    $member->status = 0;

                    $member->save();

                }
            }
        }

        if ($request->hasFile('protocol_file')){
            foreach ($request->file('protocol_file') as $file) {

                $file->store('protocol_files', 'public');

                $newFile                    = new EdoProtocolFiles();
                $newFile->protocol_id       = $model->id;
                $newFile->file_name         = $file->getClientOriginalName();
                $newFile->file_hash         = $file->hashName();
                $newFile->file_extension    = $file->extension();
                $newFile->file_size         = $file->getSize();
                $newFile->save();

            }

        }

        return redirect(route('edo-staff-protocols'))
            ->with('success', 'Xodimlar buyrug`i muvaffaqiyatli yangilandi');
    }

    // staff protocol create
    public function createStaffProtocol()
    {
        //
        $users = EdoManagementMembers::where('status', 1)->orderBy('user_sort', 'ASC')->get();

        $user = User::select('id', 'depart_id', DB::raw('CONCAT(branch_code, " - ", lname, " ", fname) AS full_name'))
            ->where('status', 1)->get();

        return view('edo.edo-staff-protocols.createStaffProtocol',
            compact('users', 'user'));
    }

    // main staff view
    public function viewStfProtocol($id, $hash)
    {
        //
        $model = EdoManagementProtocols::where('id', $id)->where('protocol_hash', $hash)->firstOrFail();

        $guide = EdoManagementProtocolMembers::where('protocol_id', $id)->where('user_role', 1)->first();


        $model_files = EdoProtocolFiles::where('protocol_id', $model->id)->get();

        return view('edo.edo-staff-protocols.viewStfProtocol',compact('model', 'guide', 'model_files'));

    }

    public function downloadProtocolFile($id)
    {
        $model = EdoProtocolFiles::findOrFail($id);

        $url = storage_path('app/public/protocol_files/'.$model->file_hash);

        return response()->download($url, $model->file_name);
    }

    public function previewProtocolFile($id)
    {

        $model = EdoProtocolFiles::findOrFail($id);
        $url = storage_path('app/public/protocol_files/'.$model->file_hash);

        //
        if ($url) {

            if($model->file_extension == 'pdf'){
                return Response::make(file_get_contents($url), 200, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; name ="'.$model->file_name.'"'
                ]);
            }

            return response()->file($url);

        } else {

            return response()->json('Serverdan fayl topilmadi!');

        }

    }

    public function removeSingleProtocolFile($id)
    {
        $model = EdoProtocolFiles::findOrFail($id);

        Storage::delete('public/protocol_files/'.$model->file_hash);

        $model->delete();

        $msg = "Successfully deleted";
        
        return back()->with('success', $msg);
    }

    // my staff view
    public function viewMyStfProtocol($id, $hash)
    {
        //
        $model = EdoManagementProtocols::where('id', $id)->where('protocol_hash', $hash)->firstOrFail();

        $guide = EdoManagementProtocolMembers::where('protocol_id', $id)->where('user_role', 1)->first();

        $members = EdoManagementProtocolMembers::where('protocol_id', $id)->where('user_role', 2)->first();

        return view('edo.edo-staff-protocols.viewMyStfProtocol',compact('model', 'guide', 'members'));

    }

    public function stfNumberEdit(Request $request, $id){

        $model = EdoManagementProtocols::findOrFail($id);

        return response()->json(array(
            'success' => true,
            'msg' => $id,
            'stf_number' => $model->stf_number,
            'stf_date' => $model->stf_date
        ));
    }

    public function stfNumberPost(Request $request)
    {
        //
        $this->validate($request, [
            'model_id' => 'required',
            'stf_number' => 'required'
        ]);


        $id = $request->input('model_id');

        $stf_number = $request->input('stf_number');

        $stf_date = $request->input('stf_date');

        $model = EdoManagementProtocols::find($id);

        $model->update(['stf_number' => $stf_number, 'stf_date' => $stf_date]);

        return response()->json(array(
                'success' => true,
                'id' => $id,
                'stf_number' => $stf_number,
                'stf_date'   => $stf_date)
        );
    }

    public function stfMainConfirm(Request $request)
    {
        //
        $this->validate($request, [
            'model_id' => 'required'
        ]);

        $id = $request->input('model_id');

        $modelHR = EdoManagementProtocolMembers::where('protocol_id', $id)->where('user_id', Auth::id())->first();
        
        if($modelHR){
            $model = EdoManagementProtocols::find($id);

            $model->update(['status' => 2]);
    
            $modelMem = EdoManagementProtocolMembers::where('protocol_id', $id);
    
            $modelMem->update(['status' => 1]);

            $modelHR = EdoManagementProtocolMembers::where('protocol_id', $id)->where('user_id', Auth::id())->first()->update(['status' => 2]);

        }else{
            return response()->json(array(
                'success' => false,
                'msg'   => 'Hujjatda siz ishtirok etmagansiz!')
            ); 
        }

        return response()->json(array(
                'success' => true,
                'msg'   => 'Xodim buyrug`i muvaffaqiyatli tasdiqlandi')
        );
    }

    public function stfMainCancel(Request $request)
    {
        $this->validate($request, [
            'protocol_id' => 'required',
            'cancel_text' => 'required'
        ]);

        $id     = $request->input('protocol_id');
        $text   = $request->input('cancel_text');

        $model = EdoManagementProtocols::find($id);
        if($model){
            $model->update([
                'comments'  => $text,
                'status'    => -1
            ]);
            return redirect(route('edo-staff-protocols'))->with('success', 'Protocol muvoffaqiyatli bekor qilindi!');
        }else{
            return back()->with('error', 'Protocol Not Found');
        }

    }

}