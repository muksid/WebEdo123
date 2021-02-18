<?php

namespace App\Http\Controllers;

use App\EdoJournals;
use App\EdoMessageJournal;
use App\EdoUsers;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EdoJournalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $edoUsers = EdoUsers::where('user_id', Auth::id())->where('status',1)->firstOrFail();
        # If user kanc or helper
        switch ($edoUsers->role_id) {
            case 2:
            case 4:
            case 17:
            case 18:
            case 19:
                $user = EdoUsers::where('user_id', Auth::id())->firstOrFail();

                $models = EdoJournals::where('depart_id', $user->department_id)->orderBy('id', 'DESC')->get();

                return view('edo.edo-journals.index',compact('models'));

                break;
            
            default:
                return response()->view('errors.' . '404', [], 404);
                break;
        }
        
    }


    public function viewTasks($id)
    {
        $edoUsers = EdoUsers::where('user_id', Auth::id())->firstOrFail();
        # If user kanc or helper
        if($edoUsers->role_id === 2){

            $journal = EdoJournals::where('id', $id)->first();

            $r   = Input::get ( 'r');
            $i_r = Input::get ( 'i_r');
            $t   = Input::get ( 't');
            
            $search = EdoMessageJournal::where('edo_journal_id', $id);
            
            if($r) {
                $search->where('in_number', 'like', '%'.$r.'%');
            } 


            if($i_r){
                $search->whereHas('message', function($query) use($i_r){
                    $query->where('out_number', 'like', '%'.$i_r.'%');
                });
            }

            if($t){
                $search->whereHas('message', function($query) use($t){
                    $query->where('from_name', 'like', '%'.$t.'%');
                });
            }

            //
            $models = $search->orderBy('in_number', 'DESC')->paginate(25);


            $models->appends ( array (
                'r'     => $r,
                'i_r'   => $i_r,
                't'     => $t,
            ) );


            if (empty($models)) {
                return response()->view('errors.' . '404', [], 404);
            }

            return view('edo.edo-journals.viewTasks',compact('models','journal','r','i_r','t','id'))
                ->with('i', (request()->input('page', 1) - 1) * 25);
        }
        else{
            return response()->view('errors.' . '404', [], 404);
        }
    }

    public function getExecutors(Request $request){

        $message_id = $request->input('message_id');

        $journals = EdoMessageJournal::where('edo_message_id', $message_id)->first();

        if ($journals->status == 0){
            $status = 'Kanselariyadan ketgan';
        } elseif ($journals->status == 1){
            $status = 'Rahbariyat yordamchisida';
        } elseif ($journals->status == 2){
            $status = 'Ijrochilarga yuborilgan';
        } elseif ($journals->status == 3){
            $status = 'Vazifa yopilgan';
        } else{
            $status = 'Aniqlanmagan';
        }

        $users = DB::table('edo_type_messages as a')
            ->join('edo_message_users as m', 'a.id', '=', 'm.performer_user')
            ->join('users as u', 'm.to_user_id', '=', 'u.id')
            ->select('a.title_ru','a.id as mes_type_id','u.id as user_id','u.branch_code','u.job_title','user_sort',
                'm.is_read','m.read_date','m.status',
                DB::raw('CONCAT(lname, " ", fname) AS full_name'))
            ->where('m.edo_message_id', $message_id)
            ->orderBy('a.sort', 'ASC')
            ->get();

        return response()->json(array('success' => true, 'msg' => $users, 'status' => $status));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        return view('edo.edo-journals.create');
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

        $user = EdoUsers::where('user_id', Auth::id())->firstOrFail();

        $model = new EdoJournals();

        $model->title = $request->input('title');

        $model->title_ru = $request->input('title');

        $model->journal_type = $request->input('journal_type');

        $model->status = $request->input('status');

        $model->user_id = $user->user_id;

        $model->depart_id = $user->department_id;

        $model->save();

        return back()->with('success', 'Journal muvaffaqiyatli yaratildi');
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
        $user = EdoUsers::where('user_id', Auth::id())->firstOrFail();

        $model = EdoJournals::where('depart_id', $user->department_id)->where('id',$id)->first();

        if (empty($model)) {
            return response()->view('errors.' . '404', [], 404);
        }

        return view('edo.edo-journals.edit',compact('model'));
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

        $user = EdoUsers::where('user_id', Auth::id())->firstOrFail();

        $model = EdoJournals::find($id);

        $model->depart_id = $user->department_id;

        $model->title = $request->input('title');

        $model->title_ru = $request->input('title_ru');

        $model->journal_type = $request->input('journal_type');

        $model->status = $request->input('status');

        $model->user_id = Auth::id();

        $model->save();

        return back()->with('success', 'Sizning journal muvaffaqiyatli yangilandi');
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
        print_r('error'); die;
        $model = EdoJournals::find($id);

        $model->delete();

        return back()->with('success', 'Journals deleted successfully');
    }
}
