<?php

namespace App\Http\Controllers;

use App\HelperTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HelperTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $models = HelperTask::where('user_id', Auth::id())->orderBy('id', 'DESC')->get();

        return view('edo.helper-tasks.index',compact('models'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        return view('edo.helper-tasks.create');
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
            'title' => 'required'
        ]);

        $model = $request->all();

        $model = HelperTask::create($model);

        return back()->with('success', 'Vazifa muvaffaqiyatli yaratildi');
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
        $model = HelperTask::find($id);

        if (empty($model)) {
            return response()->view('errors.' . '404', [], 404);
        }

        return view('edo.helper-tasks.edit',compact('model'));
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
            'title' => 'required'
        ]);

        $model = HelperTask::find($id);

        $model->title = $request->input('title');

        $model->title_ru = $request->input('title_ru');

        $model->user_id = Auth::id();

        $model->save();

        return back()->with('success', 'Sizning vazifa muvaffaqiyatli yangilandi');
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
        $model = HelperTask::find($id);

        $model->delete();

        return back()->with('success', 'Vazifa deleted successfully');
    }
}
