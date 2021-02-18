<?php

namespace App\Http\Controllers;

use App\EdoCancelMessage;
use App\EdoHelperMessage;
use App\EdoJournals;
use App\EdoMessage;
use App\EdoMessageFile;
use App\EdoMessageJournal;
use App\EdoMessageSubUsers;
use App\EdoMessageUsers;
use App\EdoRedirectMessage;
use App\EdoTypeMessages;
use App\EdoUserRoles;
use App\EdoUsers;
use App\HelperTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class EdoMessageJournalsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function guideTasksSent()
    {
        //
        $edoUsers  = EdoUsers::where('user_id','=', Auth::id())->first();

        $edoUsersManager  = EdoUsers::where('user_manager', $edoUsers->user_id)->first();

        $edoUsersChild  = EdoUsers::where('user_child', $edoUsers->user_id)->first();

        // manager
        if ($edoUsersManager == null){
            $manager = 0;
        } else {
            $manager = $edoUsersManager->user_id;
        }

        // child
        if ($edoUsersChild == null){
            $child = 0;
        } else {
            $child = $edoUsersChild->user_id;
        }

        // search filter
        $search = EdoMessageJournal::whereIn('to_user_id', [$edoUsers->user_id, $manager, $child])
            ->where('status', 2)
            ->orderBy('updated_at', 'DESC');

        $t = Input::get ( 't' );

        $reservation = Input::get('reservation');

        if ($t)
        {
            $search->whereHas('message', function ($query) use ($t) {
                $query->where('from_name', 'like', '%' . $t . '%');
                $query->orWhere('title', 'like', '%' . $t . '%');
                $query->orWhere('in_number', 'like', '%' . $t . '%');
                $query->orWhere('out_number', 'like', '%' . $t . '%');
                $query->orWhere(DB::raw('CONCAT_WS("", in_number, in_number_a)'), 'like', '%' . $t . '%');
            });
        }

        if ($reservation)
        {
            $from = substr($reservation, 0, 10);
            $to = substr($reservation, 13, 23);
            $from_date = date('Y-m-d', strtotime($from));
            $to_date = date('Y-m-d', strtotime($to));

            $search->whereBetween('created_at', [$from_date." 00:00:00", $to_date." 23:59:59"]);
        }

        $models = $search->paginate(25);

        $models->appends ( array (
            't' => Input::get ( 't' ),
            'reservation' => Input::get ( 'reservation' )
        ) );

        return view('edo.edo-message-journals.guideTaskSent',
            compact('models','t', 'reservation'))
            ->with('i', (request()->input('page', 1) - 1) * 25);

    }


    # Jamshid To change Receivers
      public function guideTasksSentChange()
      {
          //
          $edoUsers  = EdoUsers::where('user_id','=', Auth::id())->first();
  
          $edoUsersManager  = EdoUsers::where('user_manager', $edoUsers->user_id)->first();
  
          $edoUsersChild  = EdoUsers::where('user_child', $edoUsers->user_id)->first();
  
          // manager
          if ($edoUsersManager == null){
              $manager = 0;
          } else {
              $manager = $edoUsersManager->user_id;
          }
  
          // child
          if ($edoUsersChild == null){
              $child = 0;
          } else {
              $child = $edoUsersChild->user_id;
          }
  
          // search filter
          $r = Input::get ( 'r' );
          $t = Input::get ( 't' );
  
          if($r != '' || $t != ''){
  
              $models = EdoMessageJournal::whereHas('message', function ($query) use ($t,$r) {
                  $query->where('from_name', 'like', '%' . $t . '%');
                  $query->where('in_number', 'like', '%' . $r . '%');
              })
                  ->whereIn('to_user_id', [$edoUsers->user_id, $manager, $child])
                  ->where('status', 2)
                  ->orderBy('updated_at', 'DESC')
                  ->paginate(25);
  
              $models->appends ( array (
                  'r' => Input::get ( 'r' ),
                  't' => Input::get ( 't' )
              ) );
              if (count ( $models ) > 0)
                  return view ( 'edo.edo-message-journals.guideTaskChange',
                      compact('models','r','t'))
                      ->withDetails ( $models )->withQuery ( $r, $t );
          }
  
          $models = EdoMessageJournal::whereIn('to_user_id', [$edoUsers->user_id, $manager, $child])
              ->where('status', 2)
              
              ->orderBy('updated_at', 'DESC')
              ->paginate(25);
  
          return view('edo.edo-message-journals.guideTaskChange',
              compact('models','r','t'))
              ->with('i', (request()->input('page', 1) - 1) * 25);
  
      }

    public function guideTasksClosed()
    {
        //
        $edoUsers  = EdoUsers::where('user_id','=', Auth::id())->first();

        $edoUsersManager  = EdoUsers::where('user_manager', $edoUsers->user_id)->first();

        $edoUsersChild  = EdoUsers::where('user_child', $edoUsers->user_id)->first();

        // manager
        if ($edoUsersManager == null){
            $manager = 0;
        } else {
            $manager = $edoUsersManager->user_id;
        }

        // child
        if ($edoUsersChild == null){
            $child = 0;
        } else {
            $child = $edoUsersChild->user_id;
        }

        $models = EdoMessageJournal::whereIn('to_user_id', [$edoUsers->user_id, $manager, $child])
            ->where('status', 3)
            ->orderBy('updated_at', 'DESC')
            ->get();

        return view('edo.edo-message-journals.guideTaskClosed',compact('models'));
    }

    // control
    public function control()
    {
        //
        $models = EdoMessageJournal::where('t.type_message_code','control')
            ->leftJoin('edo_helper_messages as h', 'edo_message_journals.id','=','h.edo_message_journals_id')
            ->leftJoin('edo_type_messages as t', 'h.edo_type_message_id','=','t.id')
            ->select('edo_message_journals.*')
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('edo.edo-message-journals.control',compact('models'));
    }

    public function officeTasksSent()
    {
        //
        $edoUsers = EdoUsers::where('user_id', Auth::id())->where('status',1)->firstOrFail();
        # If user kanc or helper
        switch($edoUsers->role_id){
            case 2:
            case 3:
            case 4:
            case 5:
            case 17:
            case 18:
            case 19:
             // journal
             $journals = EdoJournals::where('depart_id', $edoUsers->department_id)
             ->where('journal_type', 'inbox')
             ->where('status', 1)
             ->get();
 
             // search filter
             $j  = Input::get ( 'j'  );
             $r  = Input::get ( 'r'  );
             $i_r= Input::get ( 'i_r');
             $t  = Input::get ( 't'  );
             
             $search = EdoMessageJournal::where('depart_id', $edoUsers->department_id);
 
             if($j)  $search->where('edo_journal_id', 'like', '%'.$j.'%');
 
             if($r)  $search->where('in_number', $r);
 
             if($i_r){
                 $search->whereHas('message', function($query) use($i_r){
                     $query->where('out_number', $i_r );
                 });
             }
 
             if($t){
                 $search->whereHas('message', function($query) use($t){
                     $query->where('from_name', 'like', '%'.$t.'%');
                 });
             }
 
 
             $models = $search->orderBy('created_at', 'DESC')->paginate(25);
             
             
             $models->appends ( array (
                 'j'   => $j,
                 'r'   => $r,
                 'i_r' => $i_r,
                 't'   => $t
             ) );
 
 
             return view('edo.edo-message-journals.sent',
                 compact('models','j','r','i_r','t','journals'))
                 ->with('i', (request()->input('page', 1) - 1) * 25);

             break;
        

           
        
        default:
            return response()->view('errors.' . '404', [], 404);
            break;
        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // pomoshnikdagi hatlar
    public function guideTaskInbox()
    {
        //
        $edoUsers  = EdoUsers::where('user_id','=', Auth::id())->first();

        $edoUsersChild  = EdoUsers::where('user_child', $edoUsers->user_id)->first();

        if ($edoUsersChild == null){
            $models = EdoMessageJournal::whereIn('to_user_id', [$edoUsers->user_id])
                ->whereIn('status', ['-1',0])
                ->orderBy('id', 'DESC')
                ->get();
        } else {
            $models = EdoMessageJournal::whereIn('to_user_id', [$edoUsers->user_id, $edoUsersChild->user_id])
                ->whereIn('status', ['-1',0])
                ->orderBy('id', 'DESC')
                ->get();
        }

        return view('edo.edo-message-journals.guideTaskInbox',compact('models'));
    }

    // tasdiqlashga
    public function guideTaskResolution()
    {
        //
        $edoUsers  = EdoUsers::where('user_id','=', Auth::id())->first();

        $edoUsersManager  = EdoUsers::where('user_manager', $edoUsers->user_id)->first();

        $edoUsersChild  = EdoUsers::where('user_child', $edoUsers->user_id)->first();

        // manager
        if ($edoUsersManager == null){
            $manager = 0;
        } else {
            $manager = $edoUsersManager->user_id;
        }

        // child
        if ($edoUsersChild == null){
            $child = 0;
        } else {
            $child = $edoUsersChild->user_id;
        }

        $models = EdoMessageJournal::whereIn('to_user_id', [$edoUsers->user_id, $manager, $child])
            ->where('status', 1)
            ->orderBy('id', 'DESC')
            ->get();

        return view('edo.edo-message-journals.guideTaskResolution',compact('models'));
    }

    // redirect guide
    public function redirectTask(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'from_guide_id' => 'required',
            'to_guide_id' => 'required',
            'edo_message_id' => 'required',
            'redirect_desc' => 'required'
        ]);

        // redirect Task
        $guide_id = $request->input('to_guide_id');

        $user_id = $request->input('user_id');

        $edo_journal_id = $request->input('jrnl_id');

        $message_journal = EdoMessageJournal::find($edo_journal_id);

        $message_journal->update([
            'to_user_id' => $guide_id
        ]);

        $redirect = new EdoRedirectMessage($request->all());

        $redirect->save();

        return redirect()->route('g-tasks-redirect')
            ->with('success', 'Vazifa muvaffaqiyatli saqlandi');

    }

    // redirect Tasks
    public function guideTasksRedirect()
    {
        $models = EdoRedirectMessage::where('user_id', Auth::id())->orWhere('from_guide_id', Auth::id())
            ->where('status', 1)
            ->orderBy('id', 'DESC')
            ->get();

        return view('edo.edo-message-journals.guideTaskRedirect',
            compact('models'));
    }

    // cancel guide task
    public function cancelGuideTask(Request $request)
    {
        //
        $this->validate($request, [
            'user_id' => 'required',
            'edo_message_id' => 'required',
            'description' => 'required'
        ]);

        $edo_journal_id = $request->input('jrnl_id');

        // cancel task
        $message_journal = EdoMessageJournal::find($edo_journal_id);

        $message_journal->update([
            'status' => '-1'
        ]);

        // delete users
        $modelUsers = EdoMessageUsers::where('edo_mes_jrls_id', $edo_journal_id);

        $modelUsers->delete();

        // delete helper text
        $modelHelper = EdoHelperMessage::where('edo_message_journals_id', $edo_journal_id);

        $modelHelper->delete();

        $modelCancel = new EdoCancelMessage($request->all());

        $modelCancel->save();

        return redirect()->route('guide-tasks-resolution')
            ->with('success', 'Vazifa muvaffaqiyatli bekor qilindi');
    }

    // cancel director task
    public function cancelDirectorTask(Request $request)
    {
        //
        $this->validate($request, [
            'user_id' => 'required',
            'from_guide_id' => 'required',
            'to_guide_id' => 'required',
            'edo_message_id' => 'required',
            'jrnl_id' => 'required',
            'redirect_desc' => 'required'
        ]);

        $edo_journal_id = $request->input('jrnl_id');

        // cancel guide task
        $message_journal = EdoMessageJournal::find($edo_journal_id);

        $message_journal->update([
            'status' => 1
        ]);

        // cancel director task
        $message_users = EdoMessageUsers::where('edo_mes_jrls_id',$edo_journal_id);

        $message_users->update([
            'status' => 0
        ]);

        $modelCancel = new EdoRedirectMessage($request->all());

        $modelCancel->save();

        return redirect()->route('guide-tasks-resolution')
            ->with('success', 'Vazifa muvaffaqiyatli bekor qilindi');
    }

    public function directorConfirm($m_id, $u_id, $mu_id)
    {
        // message journals
        $messageUsers = EdoMessageUsers::findOrFail($mu_id);

        $messageUsers->update(['sub_status' => 2]);

        // message users
        $messageUsers = EdoMessageSubUsers::where('edo_message_id', $m_id)->where('from_user_id', $u_id);

        $messageUsers->update(['status' => 1, 'from_user_id' => Auth::id()]);


        return redirect()->route('edo-message-journals')->with('success', 'Vazifa muvaffaqiyatli tasdiqlandi va ijrochilarga yuborildi!');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        return view('edo.edo-message-journals.create');
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

        $model = EdoMessageJournal::create($model);

        return back()->with('success', 'Journal muvaffaqiyatli yaratildi');
    }

    public function getJournalNumber(Request $request)
    {
        //
        $journalType = $request->input('id');

        $edoUsers = EdoUsers::where('user_id', Auth::id())->firstOrFail();

        $lastJournal = EdoMessageJournal::where('depart_id', $edoUsers->department_id)
            ->where('edo_journal_id', $journalType)
            ->latest()
            ->first();
        if (empty($lastJournal)){
            $last = 0;
        } else {
            $last = $lastJournal->in_number;
        }

        return response()->json(array('success' => true, 'jType' => $journalType, 'lastNumber' => $last));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EdoTypeMessages  $edoTypeMessages
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $model = EdoMessageJournal::findOrFail($id);

        if (empty($model)) {
            return response()->view('errors.' . '404', [], 404);
        }

        return view('edo.edo-message-journals.show',compact('model'));
    }

    public function officeJournalEdit(Request $request, $id){

        $model = EdoMessageJournal::findOrFail($id);

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

        $model = EdoMessageJournal::find($id);

        $model->update(['in_number' => $in_number, 'in_number_a' => $in_number_a]);

        return response()->json(array(
                'success' => true,
                'id' => $id,
                'in_number' => $in_number,
                'in_number_a' => $in_number_a)
        );
    }

    public function helperJournalDelete($id)
    {
        //
        $model = EdoMessageJournal::find($id);

        $model->update(['status' => '-2']);

        return response()->json(array(
                'success' => true,
                'id' => $id)
        );
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
        $model = EdoMessageJournal::findOrFail($id);

        if (empty($model)) {
            return response()->view('errors.' . '404', [], 404);
        }

        return view('edo.edo-message-journals.edit',compact('model'));
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
            'title' => 'required'
        ]);

        $model = EdoTypeMessages::find($id);

        $model->title = $request->input('title');

        $model->title_ru = $request->input('title_ru');

        $model->save();

        return back()->with('success', 'Sizning Journal muvaffaqiyatli yangilandi');
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
        $journal = EdoMessageJournal::findOrFail($id);

        // files
        $modelFiles = EdoMessageFile::where('edo_message_id', $journal->edo_message_id)->get();

        //if (count($modelFiles)){
        foreach ($modelFiles as $file) {

            if (file_exists(public_path() . '/FilesEDO2/' . $file->file_hash)) {
                unlink(public_path() . '/FilesEDO2/' . $file->file_hash);
            }
        }

        EdoMessageFile::where('edo_message_id', '=', $journal->edo_message_id)->delete();

        // message
        $message = EdoMessage::find($journal->edo_message_id);

        $message->delete();

        // journal
        $journal->delete();

        return response()->json(array(
                'deleted' => 'Xat muvaffaqiyatli o`chirildi',
                'id' => $id)
        );

    }

    /*
     * Status
     * Office task create - 0
     *
     */
}
