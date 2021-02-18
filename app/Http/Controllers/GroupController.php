<?php

namespace App\Http\Controllers;

use App\Group;
use App\GroupUsers;
use Illuminate\Http\Request;
use App\Department;
use App\MessageUsers;
use App\Message;
use Auth;
use DB;


class GroupController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $groups = Group::where('user_id', '=', Auth::id())->orderBy('id','DESC')->get();

        // count() //
        @include('count_message.php');

        return view('groups.index',compact('groups','inbox_count','sent_count','term_inbox_count','all_inbox_count'));

    }

    public function create()
    {
        $departments = Department::where('parent_id', '=', 0)->where('status', '=', 1)->get();

        // count() //
        @include('count_message.php');

        return view('groups.create',compact('departments','inbox_count','sent_count','term_inbox_count','all_inbox_count'));

    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required'
        ]);

        $group = new Group();

        $group->title = $request->input('title');

        $group->title_ru = $request->input('title_ru');

        $group->user_id = Auth::id();

        $group->status = 1;

        $group->save();

        //print_r('sfsdf'); die;

        foreach ($request->input('users_id') as $item) {
            if ($item != 0) {
                $group_users = new GroupUsers();
                $group_users->users_id = $item;
                $group_users->group_id = $group->id;
                $group_users->save();
            }
        }

        return back()->with('success', 'Guruh muvaffaqiyatli yaratildi');
    }


    public function show(Department $department)
    {
        return view('departments.show',compact('department'));
    }

    public function edit($id)
    {

        $group = Group::where('id', '=', $id)->find($id);
        if (empty($group)) {
            return response()->view('errors.' . '404', [], 404);
        }
        $group_users = GroupUsers::where('group_id', '=', $id)
            ->join('users', 'group_users.users_id', '=', 'users.id')
            ->join('departments', 'users.depart_id', '=', 'departments.id')
            ->select('users.*','group_users.users_id','departments.title_ru')
            ->get();
        $departments = Department::where('parent_id', '=', 0)->where('status', '=', 1)->get();

        // count() //
        @include('count_message.php');

        return view('groups.edit',compact('group', 'group_users', 'departments', 'inbox_count','sent_count','term_inbox_count','all_inbox_count'));

    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
        ]);

        $group_users = GroupUsers::where('group_id', '=', $id);
        $group_users->delete();

        $input = $request->only('title', 'title_ru', 'user_id', 'status');
        $group = Group::find($id);

        $group->update($input);

        foreach ($request->input('users_id') as $item) {
            if ($item != 0) {
                $group_users = new GroupUsers();
                $group_users->users_id = $item;
                $group_users->group_id = $group->id;
                $group_users->save();
            }
        }

        return back()->with('success', 'Sizning profilingiz muvaffaqiyatli yangilandi');

    }

    public function destroy($id)
    {
        Group::find($id)->delete();

        $group_users = GroupUsers::where('group_id', '=', $id);
        $group_users->delete();
        return back()->with('success', 'Sizning guruhingiz muvaffaqiyatli o`chirildi');
    }

}