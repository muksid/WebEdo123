<?php

namespace App\Http\Controllers;

use App\EdoDepInboxJournals;
use Illuminate\Http\Request;

class EdoDepInboxJournalsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    public function officeJournalEdit(Request $request, $id){

        $model = EdoDepInboxJournals::findOrFail($id);

        return response()->json(array(
            'success' => true,
            'msg' => $id,
            'journal_number' => $model->in_number,
            'journal_number_a' => $model->in_number_a
        ));
    }

    public function officeJournalPost(Request $request)
    {
        //
        $this->validate($request, [
            'model_id' => 'required',
            'in_number' => 'required'
        ]);


        $id = $request->input('model_id');

        $in_number = $request->input('in_number');

        $in_number_a = $request->input('in_number_a');

        $model = EdoDepInboxJournals::find($id);

        $model->update(['in_number' => $in_number, 'in_number_a' => $in_number_a]);

        return response()->json(array(
                'success' => true,
                'id' => $id,
                'in_number' => $in_number,
                'in_number_a' => $in_number_a)
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    }
}
