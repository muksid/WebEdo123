<?php

namespace App\Http\Controllers;

use App\EdoManagementMembers;
use App\EdoManagementProtocolMembers;
use App\EdoManagementProtocols;
use App\EdoUsers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


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
        $model->qr_hash     = Hash::make($request->input('qr_name'));
        $model->title       = $request->input('title');
        $model->user_sort   = $request->input('user_sort');
        $model->status      = $request->input('status');
        $model->save();

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

        if ($admin->role->role_code == 'admin' || $admin->role->role_code == 'helper' || $admin->role->role_code == 'secretary_management')
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

            return view('edo.edo-management-protocols.memberProtocol',compact('models'));

        }
        else
        {
            return response()->view('errors.' . '404', [], 404);

        }
    }

    public function hrMemberProtocol()
    {
        //
        $user = EdoManagementMembers::where('user_id', Auth::id())->first();
        if ($user != null || Auth::id() == 199 )
        {
            if($user->user_id == 145){

                $models = EdoManagementProtocolMembers::where('user_id',1)
                ->whereHas('protocol', function($query){
                    $query->where('protocol_type', 11);
                })
                // ->where('protocol_type', 11)
                ->whereIn('status', [-1,1,2,3])
                ->orderBy('created_at', 'DESC')->get();
                return view('edo.edo-management-protocols.memberProtocol',compact('models'));

            }else{
                $models = EdoManagementProtocolMembers::where('user_id', Auth::id())
                ->whereHas('protocol', function($query){
                    $query->where('protocol_type', 11);
                })
                // ->where('protocol_type', 11)
                ->whereIn('status', [-1,1,2,3])
                ->orderBy('created_at', 'DESC')->get();

            return view('edo.edo-management-protocols.memberProtocol',compact('models'));
            }

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

        if ($user != null)
        {

            $model = EdoManagementProtocols::where('id', $id)->where('protocol_hash', $hash)->first();

            $guide = EdoManagementProtocolMembers::where('protocol_id', $id)->where('user_role', 1)->first();

            return view('edo.edo-management-protocols.viewMProtocol',compact('model', 'guide'));

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
        //print_r($request->all()); die;
        $this->validate($request, [
            'title' => 'required',
            'to_user_id' => 'required',
            'text' => 'required'
        ]);

        $depart = User::find(Auth::id());

        $depart = $depart->department->depart_id ?? 0;

        // model management protocol
        $model = new EdoManagementProtocols();

        $model->user_id = Auth::id();

        $model->to_user_id = $request->input('emp_user_id');

        $model->user_view = $request->input('user_view')??'0';

        $model->depart_id = $depart;

        $model->protocol_type = $depart;

        $model->title = $request->input('title');

        $model->text = $request->input('text');

        $model->protocol_hash = sha1(date('dmYHis')).date('dmYHis');

        $model->status = 1;

        $model->save();

        $memberStatus = 1;
        if (!empty($request->input('emp_user_id'))) {

            $memberStatus = 0;
        }

        // model management members
        if ($request->input('to_user_id') != null) {

            foreach ($request->input('to_user_id') as $key => $user) {
                if ($user != 0) {
                    $member = new EdoManagementProtocolMembers();

                    $member->protocol_id = $model->id;

                    $member->user_id = $user;

                    $member->user_role = $request->input('user_role')[$key];

                    $member->user_sort = $request->input('user_sort')[$key];

                    $member->status = 0;

                    $member->save();

                }
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

    public function staffProtocol()
    {
        //
        foreach (json_decode(Auth::user()->roles) as $user) {

            if ($user == 'main_staff' || $user == 'main_staff_emp' || $user == 'user') {

                $department = Auth::user()->department->depart_id ?? 0;

                $memberStatus = 0;

                if ($user == 'main_staff') {

                    $memberStatus = 1;
                }

                $models = EdoManagementProtocols::where('depart_id', $department)->orderBy('created_at', 'DESC')->get();

            } else {

                return response()->view('errors.' . '404', [], 404);

            }
        }

        return view('edo.edo-staff-protocols.staffProtocol',compact('models', 'memberStatus'));
    }

    public function editStfProtocol($id, $hash)
    {
        //
        $model = EdoManagementProtocols::where('id', $id)->where('protocol_hash', $hash)->first();

        $selectedUsers = EdoManagementProtocolMembers::where('protocol_id', $id)->orderBy('user_sort', 'ASC')->get();

        $selected = $selectedUsers->implode('user_id', ',');
        $explode = explode(',', $selected);

        $users = EdoManagementMembers::whereNotIn('user_id', $explode)->where('status', 1)->orderBy('user_sort', 'ASC')->get();

        $userEmp = User::select('id', 'depart_id', DB::raw('CONCAT(branch_code, " - ", lname, " ", fname) AS full_name'))->where('status', 1)->get();

        return view('edo.edo-staff-protocols.editStfProtocol',compact('model', 'selectedUsers', 'userEmp', 'users'));

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
        //print_r($request->all()); die;
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

        $model->user_id = Auth::id();

        $model->user_view = $request->input('user_view')??'0';

        $model->to_user_id = $empUser;

        $model->depart_id = $depart;

        $model->protocol_type = $depart;

        $model->title = $request->input('title');

        $model->text = $request->input('text');

        $model->status = 1;

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

        return view('edo.edo-staff-protocols.viewStfProtocol',compact('model', 'guide'));

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