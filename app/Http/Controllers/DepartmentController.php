<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Department;
use Auth;
use DB;


class DepartmentController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $user = Auth::user();
        if(in_array("admin", json_decode(Auth::user()->roles))){
            
            $departments = Department::orderBy('id','ASC')->get();            
            
            $allDepartments = Department::all();
            
        }else{
            
            $user_department = Department::where('id', $user->depart_id)->orderBy('id','ASC')->first();
            $departments = Department::where('depart_id', $user_department->depart_id)->orderBy('id','ASC')->get();
        
        }
        // count() //
        @include('count_message.php');

        return view('departments.index',compact('departments','inbox_count','sent_count','all_inbox_count'));
    }

    public function create()
    {
        $user = Auth::user();

        $user_department = Department::where('id', $user->depart_id)->orderBy('id','ASC')->first();
        $departments = Department::where('depart_id', $user_department->depart_id)->orderBy('id','ASC')->get();

        $filial = Department::where('parent_id', 0)
            ->where('status', '=', 1)
            ->orderBy('id', 'ASC')->get();

        // count() //
        @include('count_message.php');

        return view('departments.create',compact('filial','departments',
            'inbox_count','sent_count','all_inbox_count'));

    }
    public function userDepartment(Request $request){

        $id = $request->input('id');

        $model = Department::where('branch_code', $id)->where('status', 1)->get();

        return response()->json(array('success' => true, 'msg' => $model, 'branch' => $id));
    }

    // Jamshid 2020-03-25 18:07:03 changed $req and added $branch
    public function department(Request $request){

        $branch = $request->input('branch_code');

        $req = DB::table('departments')
            ->select('id')
            ->where('branch_code', $branch)
            ->where('parent_id', 0)
            ->first();

        $model = Department::where('parent_id', $req->id)->where('status', 1)->get();

        return response()->json(array('success' => true, 'msg' => $model, 'branch' => $branch, 'req' => $req->id));
    }

    // Jamshid Sub departments
    public function subdep(Request $request){

        $req = $request->input('name');

        $sub_depart = Department::where('parent_id', $req)->get();

        return response()->json(array('success' => true, 'msg' => $sub_depart, 'req' => $req));
    }


    public function branch(Request $request){
        // Jamshid call only departments
        $input = $request->input('name');
        // $depart = Department::where('branch_code', '=', $input)              //call all info of departments

        $depart = Department::where('branch_code', '=', $input)
            ->whereIn('parent_id',function($query){
            $query->select('id')
                ->from('departments as d')
                ->where('parent_id',0);
        })
            ->get();

        return response()->json(array('success' => true, 'msg' => $depart, 'branch' => $input));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'title_ru' => 'required',
            'branch_code' => 'required',
        ]);


        $input = $request->all();
        $input['parent_id'] = empty($input['parent_id']) ? 0 : $input['parent_id'];
        $d = Department::where('id',$request['parent_id'])->first();

        // If a new created department is in 'Bosh Bank' and is a department
        if($request['branch_code'] == '09011' && $d->id == 1){
            $lastId = Department::orderBy('id', 'DESC')->first();
            $request['depart_id'] = $lastId->id + 1;
        }
        else{
            $request['depart_id'] = $d->depart_id;
        }
        Department::create($request->all());
        return back()->with('success', 'New Department added successfully.');
    }


    public function show(Department $department)
    {

        // count() //
        @include('count_message.php');

        return view('departments.show',compact('department',
            'inbox_count','sent_count','all_inbox_count'));
    }

    public function edit(Department $department)
    {
        // dd($department);
        $filials = Department::where('parent_id', '=', 0)->where('status', '=', 1)->orderBy('id','ASC')->get();

        $departments = Department::where('status', '=', 1)->where('parent_id', '=', 0)->get();

        $parent = Department::where('id', $department->parent_id)->first();

        @include('count_message.php');

        if(in_array("branch_admin", json_decode(Auth::user()->roles))){
            $departments = Department::where('branch_code', Auth::user()->branch_code)->where('status', 1)->get();
        }
        // count() //

        return view('departments.edit',compact('filials','parent', 'department','departments',
            'inbox_count','sent_count','all_inbox_count'));
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'title' => 'required',
            'title_ru' => 'required',
        ]);
        // dd($request->all());

        if(in_array("branch_admin", json_decode(Auth::user()->roles))){
            $department->update($request->all());
            return redirect()->route('departments.index')->with('success','Department updated successfully.');
        }

        $d = Department::where('id',$request['parent_id'])->first();

        if($request['branch_code'] == '09011' && $d->id == 1){
            $request['depart_id'] = $department->id;
        }
        else{
            $request['depart_id'] = $d->depart_id;
        }

        $department->update($request->all());

        return redirect()->route('departments.index')->with('success','Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return redirect()->route('departments.index')->with('success','Department deleted successfully.');
    }

}
