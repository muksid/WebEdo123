<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Department;
use App\MessageUsers;
use App\Message;
use Auth;
use DB;
use App\User;


class GroupUserController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $departments = Department::orderBy('id','DESC')->get();

        $allDepartments = Department::all();

        // count() //
        @include('count_message.php');

        return view('departments.index',compact('departments','allDepartments','inbox_count','sent_count','term_inbox_count','all_inbox_count'));

    }

    public function create()
    {
        $departments = Department::where('status', '=', 1)->where('parent_id', '=', 0)->get();

        $allDepartments = Department::all();

        $to_users = User::all();

        return view('departments.create',compact('departments','allDepartments', 'to_users'));

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

        Department::create($request->all());
        return back()->with('success', 'New Department added successfully.');
    }


    public function show(Department $department)
    {
        return view('departments.show',compact('department'));
    }

    public function edit(Department $department)
    {
        $allDepartments = Department::all();
        return view('departments.edit',compact('department','allDepartments'));
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'title' => 'required',
            'title_ru' => 'required',
        ]);

        $department->update($request->all());
        return redirect()->route('departments.index')->with('success','Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('departments.index')->with('success','Department deleted successfully.');
    }

}