<?php

namespace App\Http\Controllers;

use App\EdoTypeMessages;
use App\EdoUserRoles;
use Illuminate\Http\Request;

class EdoTypeMessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $models = EdoTypeMessages::orderBy('id', 'DESC')->get();

        return view('edo.edo-type-messages.index',compact('models'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $roles = EdoUserRoles::all();

        return view('edo.edo-type-messages.create', compact('roles'));
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
            'type_code' => 'required'
        ]);

        $model = $request->all();

        $model = EdoTypeMessages::create($model);

        return back()->with('success', 'TypeMessage muvaffaqiyatli yaratildi');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EdoTypeMessages  $edoTypeMessages
     * @return \Illuminate\Http\Response
     */
    public function show(EdoTypeMessages $edoTypeMessages)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EdoTypeMessages  $edoTypeMessages
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $model = EdoTypeMessages::find($id);

        $roles = EdoUserRoles::all();

        if (empty($model)) {
            return response()->view('errors.' . '404', [], 404);
        }

        return view('edo.edo-type-messages.edit',compact('model', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EdoTypeMessages  $edoTypeMessages
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'title' => 'required',
            'type_code' => 'required',
        ]);

        $model = EdoTypeMessages::find($id);

        $model->title = $request->input('title');
        $model->title_ru = $request->input('title_ru');
        $model->type_code = $request->input('type_code');
        $model->type_message_code = $request->input('type_message_code');
        $model->sort = $request->input('sort');

        $model->save();

        return back()->with('success', 'Sizning typeMessage muvaffaqiyatli yangilandi');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EdoTypeMessages  $edoTypeMessages
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $model = EdoTypeMessages::find($id);

        $model->delete();

        return back()->with('success', 'TypeMessage deleted successfully');
    }
}
