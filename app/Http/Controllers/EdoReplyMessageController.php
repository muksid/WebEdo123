<?php

namespace App\Http\Controllers;

use App\EdoMessageJournal;
use App\EdoMessageSubUsers;
use App\EdoMessageUsers;
use App\EdoReplyMessage;
use App\EdoReplyMessageFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EdoReplyMessageController extends Controller
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function replyMessagePost(Request $request)
    {
        $input = $request->all();

        $model = new EdoReplyMessage($input);

        $model->save();

        if ($request->file('files') != null) {
            foreach ($request->file('files') as $item) {
                if ($item != 0) {
                    $message_files = new EdoReplyMessageFile();
                    $message_files->edo_reply_message_id = $model->id;
                    $message_files->file_hash = $model->id . '_' . Auth::id(). '_' . date('mdYHis') . uniqid() .
                        '.' . $item->getClientOriginalExtension();
                    $message_files->file_size = $item->getSize();
                    $item->move(public_path() . '/FilesEDO2/', $message_files->file_hash);
                    $message_files->file_name = $item->getClientOriginalName();
                    $message_files->file_extension = $item->getClientOriginalExtension();
                    $message_files->save();
                }
            }
        }
        $empStatus = EdoMessageSubUsers::where('edo_message_id', $request->input('edo_message_id'))
            ->where('to_user_id', Auth::id());

        $empStatus->update(['status' => 1]);

        return redirect()->back()->with('success', 'Sizning xatingiz muvaffaqiyatli yuborildi');

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
    public function replyEdit(Request $request)
    {
        //
        $id = $request->input('id');

        $model = EdoReplyMessage::where('id', $id)->where('status', 0)->where('user_id', Auth::id())->firstOrFail();

        $files = EdoReplyMessageFile::where('edo_reply_message_id', $id)->get();

        return response()->json(array(
            'success' => true,
            'id' => $model->id,
            'text' => $model->text,
            'files' => $files)
        );
    }

    public function replyReceive(Request $request)
    {
        //
        $id = $request->input('id');

        $model = EdoReplyMessage::findOrFail($id);

        $model->update(['status' => 1]);

        return response()->json(array(
            'success' => true,
            'message' => 'Javob xati muvaffaqiyatli qabul qilindi!')
        );
    }

    public function reqConfirm(Request $request)
    {
        //
        $id = $request->input('id');

        $director_id = $request->input('dId');

        $depart_id = $request->input('departId');

        $model = EdoReplyMessage::where('edo_message_id', $id)
            ->where('depart_id', $depart_id)
            ->where('status', 0)
            ->get();
        $modelCheckRejected = EdoReplyMessage::where('edo_message_id', $id)
            ->where('depart_id', $depart_id)
            ->where('status', '-1')
            ->get();

        $message = 'Siz ushbu xatni tasdiqlashingiz mumkin';

        if (count($model)){
            $message = 'Sizda <b class="text-red">'.count($model).'</b> ta qabul qilinmagan javob xatlari mavjud!';
        }
        if(count($modelCheckRejected)){
            if(count($model)){
                $message =$message . '<br>Sizda <b class="text-red">'.count($modelCheckRejected).'</b> ta rad qilinmagan javob xatlari mavjud!';
            }else{
                $message = 'Sizda <b class="text-red">'.count($modelCheckRejected).'</b> ta rad qilinmagan javob xatlari mavjud!';
            }
            
        }


        return response()->json(array(
            'success'           => true,
            'mCount'            => count($model),
            'rejectCount'       => count($modelCheckRejected),
            'message'           => $message,
            )
        );
    }

    public function directorCloseTask($id, $dId)
    {
        //
        // edo message journals
        $closeGuide = EdoMessageJournal::where('edo_message_id', $id);

        $closeGuide->update(['status' => 3]);

        // edo message users
        $closeDirectors = EdoMessageUsers::where('edo_message_id', $id)->where('depart_id', $dId);

        $closeDirectors->update(['sub_status' => 3]);

        // edo message sub users
        $closeEmp = EdoMessageSubUsers::where('edo_message_id', $id)->where('depart_id', $dId);

        $closeEmp->update(['status' => 3]);

        return redirect()->route('d-tasks-process')->with('success', 'Vazifa muvaffaqiyatli yopildi');
    }

    public function replyConfirm($id, $dId, $departId)
    {
        //
        $model = EdoReplyMessage::where('edo_message_id', $id)->where('depart_id', $departId);
            //->where('director_id', $dId);

        $model->update(['status' => 2]);

        $messageUsers = EdoMessageUsers::where('edo_message_id', $id)->where('depart_id', $departId);
            //->where('director_id', $dId);

        $messageUsers->update(['sub_status' => 2]);

        $messageSubUsers = EdoMessageSubUsers::where('edo_message_id', $id)->where('depart_id', $departId)->where('status', 1);

        $messageSubUsers->update(['status' => 2]);

        return redirect()->route('d-tasks-process')->with('success', 'Vazifa muvaffaqiyatli tasdiqlashga yuborildi');
    }

    public function replyDepartDConfirm($id, $dId)
    {
        //
        $model = EdoReplyMessage::where('edo_message_id', $id)->where('director_id', $dId);

        $model->update(['status' => 2]);

        $messageJournals = EdoMessageJournal::where('edo_message_id', $id)->where('to_user_id', $dId);

        $messageJournals->update(['status' => 3]);

        $messageSubUsers = EdoMessageSubUsers::where('edo_message_id', $id)->where('from_user_id', $dId);

        $messageSubUsers->update(['status' => 2]);

        return redirect()->route('department-tasks')->with('success', 'Vazifa muvaffaqiyatli tasdiqlandi');
    }

    // for guide receive message
    public function guideReceive(Request $request)
    {
        //
        $id = $request->input('id');

        $model = EdoReplyMessage::findOrFail($id);

        $model->update(['status' => 3]);

        return response()->json(array(
                'success' => true,
                'message' => 'Javob xati muvaffaqiyatli qabul qilindi!')
        );
    }

    // for guide cancel message
    public function guideCancel(Request $request)
    {
        //
        $id = $request->input('id');

        $model = EdoReplyMessage::findOrFail($id);

        $model->update(['status' => -1]);

        $departId = $model->depart_id;

        $modelEdoMessageUsers = EdoMessageUsers::where('depart_id', $departId);

        $modelEdoMessageUsers->update(['sub_status' => 1]);

        return response()->json(array(
                'success' => true,
                'message' => 'Javob xati rad qilindi!',
                'user_id' => $departId
                )
        );
    }


    public function reqTaskConfirm(Request $request)
    {
        //
        $id = $request->input('id');

        $model = EdoReplyMessage::where('edo_message_id', $id)->where('status', 2)->get();
        $message = 'Siz ushbu xatni tasdiqlashingiz mumkin';
        if (count($model)){
            $message = 'Sizda <b class="text-red">'.count($model).'</b> ta qabul qilinmagan javob xatlari mavjud!';
        }

        return response()->json(array(
                'success' => true,
                'mCount' => count($model),
                'message' => $message)
        );
    }

    public function replyTaskConfirm($id, $dId)
    {
        //
        // edo message journal
        $model = EdoMessageJournal::where('edo_message_id', $id);

        $model->update(['status' => 3]);

        // edo message users
        $messageUsers = EdoMessageUsers::where('edo_message_id', $id);

        $messageUsers->update(['sub_status' => 3]);

        // edo message sub users
        $messageSubUsers = EdoMessageSubUsers::where('edo_message_id', $id);

        $messageSubUsers->update(['status' => 3]);

        return redirect()->route('g-tasks-sent')->with('success', 'Vazifa muvaffaqiyatli tasdiqlandi');
    }

    // for view task process get sub users
    public function getSubPerfUsers(Request $request)
    {
        //
        $edo_message_users_id = $request->input('id');
        
        

        $edoMessageUser = EdoMessageUsers::where('id',$edo_message_users_id)->first();

        $model = DB::table('edo_message_sub_users as a')
            ->join('users as u', 'a.to_user_id', '=', 'u.id')
            ->join('edo_type_messages as t', 'a.performer_user', '=', 't.id')
            ->select('a.is_read','a.read_date','a.status','t.title',
                DB::raw('CONCAT(lname, " ", fname) AS full_name'))
            ->where('a.edo_message_id', $edoMessageUser->edo_message_id)
            ->where('a.from_user_id', $edoMessageUser->to_user_id)
            ->orderBy('t.sort', 'ASC')
            ->get();

        // $model = EdoMessageSubUsers::where('edo_message_id', $edoMessageUser->edo_message_id)
        //     ->where('from_user_id', $edoMessageUser->to_user_id)
        //     ->get();
            
        if(!count($model)){
            $model = null;
        }
   

        return response()->json(array(
                'success' => true,
                'msg' => $model,
                )
        );
    }

    public function edit($request,$id)
    {
        //
        $id = $request->input('id');

        //$users = User::where('depart_id', '=', $ajaxDepartId)->where('status', '=', 1)->get();

        return response()->json(array('success' => true, 'msg' => 'test repl', 'id' => $id));
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

    // cancel reply
    public function cancelReply(Request $request, $id)
    {
        //
        $model = EdoReplyMessage::findOrFail($id);

        $modelFiles = EdoReplyMessageFile::where('edo_reply_message_id', $id)->get();

        foreach($modelFiles as $file) {

            if(file_exists(public_path() . '/FilesEDO2/' . $file->file_hash)) {
                unlink(public_path() . '/FilesEDO2/' . $file->file_hash);
            }

        }

        $model->files()->delete();

        $model->update(['status' => '-1']);

        return redirect()->back()->with('cancel', 'Javob xati rad qilindi!');


    }

    public function destroy($id)
    {
        //
        $model = EdoReplyMessage::findOrFail($id);

        $modelFiles = EdoReplyMessageFile::where('edo_reply_message_id', $id)->get();

        foreach($modelFiles as $file) {

            if(file_exists(public_path() . '/FilesEDO2/' . $file->file_hash)) {
                unlink(public_path() . '/FilesEDO2/' . $file->file_hash);
            }

        }

        $model->files()->delete();

        $model->delete();

        return redirect()->back()->with('delete', 'Javob xati o`chirildi qilindi!');
    }
}
