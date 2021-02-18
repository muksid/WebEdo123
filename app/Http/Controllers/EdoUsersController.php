<?php

namespace App\Http\Controllers;

use App\Department;
use App\EdoUserRoles;
use App\EdoUsers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class EdoUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //
        $admin = EdoUsers::where('user_id', Auth::id())->firstOrFail();

        switch ($admin->role->role_code) {
            case('admin'):

                // search filter
                $l = Input::get ( 'l' );
                $f = Input::get ( 'f' );
                $r = Input::get ( 'r' );

                $search = EdoUsers::where('status', '>=', 0);

                if($l){
                    $search->whereHas('user', function($query) use($l){
                        $query->where('username', 'like', '%'.$l.'%');
                    });
                }

                if($f){
                    $search->whereHas('user', function($query) use($f){
                        $query->whereRaw("concat(lname, ' ', fname, ' ', sname) like '%".$f."%' ");
                    });
                }

                if($r){
                    $search->whereHas('userRole', function($query) use($r){
                        $query->where('role_code', 'like', '%'.$r.'%')
                            ->orWhere('title', 'like', '%' . $r . '%');
                    });
                }

                $models = $search->orderBy('created_at', 'DESC')->paginate(25);

                $models->appends ( array (
                    'l'   => $l,
                    'f'   => $f,
                    'r' => $r
                ) );

                return view('edo.edo-users.index',
                    compact('models','l','f','r'))
                    ->with('i', (request()->input('page', 1) - 1) * 25);

                break;

            case('filial_admin'):

                // search filter
                $l = Input::get ( 'l' );
                $f = Input::get ( 'f' );
                $r = Input::get ( 'r' );
                $user_branch = Auth::user()->branch_code;
                $search = EdoUsers::where('status', '>=', 0);

                $search->whereHas('user', function($query) use ($user_branch) {

                    $query->where('branch_code', $user_branch);

                });

                if($l){
                    $search->whereHas('user', function($query) use($l){
                        $query->where('username', 'like', '%'.$l.'%');
                    });
                }

                if($f){
                    $search->whereHas('user', function($query) use($f){
                        $query->whereRaw("concat(lname, ' ', fname, ' ', sname) like '%".$f."%' ");
                    });
                }

                if($r){
                    $search->whereHas('userRole', function($query) use($r){
                        $query->where('role_code', 'like', '%'.$r.'%')
                            ->orWhere('title', 'like', '%' . $r . '%');
                    });
                }

                $models = $search->orderBy('created_at', 'DESC')->paginate(25);

                $models->appends ( array (
                    'l'   => $l,
                    'f'   => $f,
                    'r' => $r
                ) );

                return view('edo.edo-users.index',
                    compact('models','l','f','r'))
                    ->with('i', (request()->input('page', 1) - 1) * 25);
                

                break;

            default:

                return response()->view('errors.' . '404', [], 404);

                break;

        }
    }

    public function departmentUsers()
    {
        //
        $models = EdoUsers::where('department_id', Auth::user()->depart_id)
            ->where('user_id', '!=', Auth::id())
            ->orderBy('sort', 'ASC')
            ->get();

        $users = User::select(DB::raw('CONCAT(lname, " ", fname) AS full_name'),'d.title', 'users.id')
            ->join('departments as d', 'users.depart_id', '=', 'd.id')
            ->where('d.parent_id', Auth::user()->depart_id)
            ->where('users.id', '!=', 199)
            ->where('users.status', 1)
            ->get();

        return view('edo.edo-users.depUsers',compact('models','users'));
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

            $child = DB::table('edo_users as a')
                ->join('users as u', 'a.user_id', '=', 'u.id')
                ->select('a.*', DB::raw('CONCAT(u.lname, " ", u.fname) AS full_name'))
                ->where('a.status', 1)
                ->orderBy('id', 'desc')
                ->get();

            $lastSort = EdoUsers::latest()->first();

            $roles = EdoUserRoles::all();

        } elseif ($admin->role->role_code == 'filial_admin')
        {
            $users = User::select('id', DB::raw('CONCAT(branch_code, " - ", lname, " ", fname) AS full_name'))
                ->where('branch_code', Auth::user()->branch_code)
                ->where('status', 1)
                ->get();

            $child = DB::table('edo_users as a')
                ->join('users as u', 'a.user_id', '=', 'u.id')
                ->select('a.*', DB::raw('CONCAT(u.lname, " ", u.fname) AS full_name'))
                ->where('a.status', 1)
                ->where('u.branch_code', Auth::user()->branch_code)
                ->orderBy('id', 'desc')
                ->get();

            $lastSort = EdoUsers::latest()->first();

            $roles = EdoUserRoles::where('role_code', 'LIKE', 'filial_%')->get();

        }

        return view('edo.edo-users.create',compact('users','roles', 'lastSort', 'child'));
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
            'user_id' => 'required',
            'role_id' => 'required'
        ]);

        $department = User::find($request->input('user_id'));

        $model = new EdoUsers();

        $model->user_id = $request->input('user_id');

        $model->role_id = $request->input('role_id');

        $model->sort = $request->input('sort');

        $model->user_child = $request->input('user_child');

        $model->department_id = $department->depart_id;

        $model->status = $request->input('status');

        $model->save();

        return back()->with('success', 'User muvaffaqiyatli yaratildi');
    }

    public function storeDUser(Request $request)
    {
        //
        $this->validate($request, [
            'user_id' => 'required',
            'role_id' => 'required'
        ]);

        $model = $request->all();

        $model = EdoUsers::create($model);

        $user = User::find($request->input('user_id'));
        $full_name = $user->lname.' '.$user->fname;

        return response()->json(array(
            'success' => true,
            'id' => $model->id,
            'full_name' => $full_name,
            'sort' => $request->input('sort'),
            'status' => $request->input('status'))
        );
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
        $model = EdoUsers::find($id);


        $admin = EdoUsers::where('user_id', Auth::id())->firstOrFail();
        if ($admin->role->role_code == 'admin')
        {

            $users = User::select('id', DB::raw('CONCAT(branch_code, " - ", lname, " ", fname) AS full_name'))->where('status', 1)->get();

            $manager = DB::table('edo_users as a')
                ->join('users as u', 'a.user_id', '=', 'u.id')
                ->select('a.*', DB::raw('CONCAT(u.lname, " ", u.fname) AS full_name'))
                ->where('a.status', 1)
                ->orderBy('id', 'desc')
                ->get();

            $child = DB::table('edo_users as a')
                ->join('users as u', 'a.user_id', '=', 'u.id')
                ->select('a.*', DB::raw('CONCAT(u.lname, " ", u.fname) AS full_name'))
                ->where('a.status', 1)
                ->orderBy('id', 'desc')
                ->get();

            $departments = Department::whereIn('parent_id', [0,1])->where('status', 1)->get();

            $roles = EdoUserRoles::all();

        } elseif ($admin->role->role_code == 'filial_admin')
        {

            $users = User::select('id', DB::raw('CONCAT(branch_code, " - ", lname, " ", fname) AS full_name'))
                ->where('branch_code', Auth::user()->branch_code)
                ->where('status', 1)->get();

            $manager = DB::table('edo_users as a')
                ->join('users as u', 'a.user_id', '=', 'u.id')
                ->select('a.*', DB::raw('CONCAT(u.lname, " ", u.fname) AS full_name'))
                ->where('a.status', 1)
                ->where('u.branch_code', Auth::user()->branch_code)
                ->orderBy('id', 'desc')
                ->get();

            $child = DB::table('edo_users as a')
                ->join('users as u', 'a.user_id', '=', 'u.id')
                ->select('a.*', DB::raw('CONCAT(u.lname, " ", u.fname) AS full_name'))
                ->where('a.status', 1)
                ->where('u.branch_code', Auth::user()->branch_code)
                ->orderBy('id', 'desc')
                ->get();

            $departments = Department::where('branch_code', Auth::user()->branch_code)->where('status', 1)->get();

            $roles = EdoUserRoles::where('role_code', 'LIKE', 'filial_%')->get();

        }

        if (empty($model)) {
            return response()->view('errors.' . '404', [], 404);
        }

        return view('edo.edo-users.edit',
            compact('model','users', 'departments', 'roles','manager', 'child'));
    }

    // edit dep emp
    public function editD($id)
    {
        //
        $model = EdoUsers::find($id);

        $curUser = User::find($model->user_id);

        return \response()->json($curUser);
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
            'user_id' => 'required',
            'role_id' => 'required',
        ]);

        $model = EdoUsers::find($id);
        $model->user_id = $request->input('user_id');
        $model->department_id = $request->input('department_id');
        $model->role_id = $request->input('role_id');
        $model->user_manager = $request->input('user_manager');
        $model->user_child = $request->input('user_child');
        $model->sort = $request->input('sort');
        $model->status = $request->input('status');

        $model->save();

        return back()->with('success', 'Sizning Users muvaffaqiyatli yangilandi');
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
        $model = EdoUsers::find($id);

        $model->delete();

        return back()->with('success', 'User deleted successfully');
    }

    // department emp
    public function destroyD($id)
    {
        //
        $user = EdoUsers::find($id)->delete();

        return response()->json($user);
    }
}
