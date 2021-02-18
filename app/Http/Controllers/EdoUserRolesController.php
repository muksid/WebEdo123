<?php

namespace App\Http\Controllers;

use App\EdoUserRoles;
use Illuminate\Http\Request;

class EdoUserRolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $models = EdoUserRoles::orderBy('id', 'DESC')->get();

        return view('edo.edo-user-roles.index',compact('models'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        return view('edo.edo-user-roles.create');
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
            'title' => 'required',
            'role_code' => 'required'
        ]);

        $model = $request->all();

        $model = EdoUserRoles::create($model);

        return back()->with('success', 'UserRole muvaffaqiyatli yaratildi');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EdoUserRoles  $edoRoleUsers
     * @return \Illuminate\Http\Response
     */
    public function show(EdoUserRoles $edoRoleUsers)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EdoUserRoles  $edoRoleUsers
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $model = EdoUserRoles::find($id);

        if (empty($model)) {
            return response()->view('errors.' . '404', [], 404);
        }

        return view('edo.edo-user-roles.edit',compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EdoUserRoles  $edoRoleUsers
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'title' => 'required',
            'role_code' => 'required',
        ]);

        $model = EdoUserRoles::find($id);

        $model->title = $request->input('title');
        $model->title_ru = $request->input('title_ru');
        $model->role_code = $request->input('role_code');

        $model->save();

        return back()->with('success', 'Sizning UserRole muvaffaqiyatli yangilandi');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EdoUserRoles  $edoRoleUsers
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $model = EdoUserRoles::find($id);

        $model->delete();

        return back()->with('success', 'Role deleted successfully');
    }
}
