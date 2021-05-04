<?php

namespace App\Http\Controllers;

use App\Message;
use Illuminate\Http\Request;

use App\User;
use App\Role;
use App\Department;
use App\MessageUsers;
use App\MessageFiles;
use App\MessageForward;
use Auth;
use DB;
use Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Http\UploadedFile;


class MainController extends Controller
{

    // Search User Message Control
    public function spesificUserMessageControlSearch(){
        foreach (json_decode(Auth::user()->roles) as $user){
            if($user == 'admin'){
                @include('count_message.php');

                $search = User::orderBy('id', 'ASC');

                $f = Input::get ( 'f' );
                $q = Input::get ( 'q' );
                $s = Input::get ( 's' );

                $f_title    = Department::select('title')->where('branch_code', $f)->where('parent_id', '0')->first();
                $filials    = Department::where('parent_id', 0)->where('status', 1)->orderBy('id','ASC')->get();


                if($f)  $search->where('branch_code', $f);


                if($q){
                    $search->where(function ($query) use($q) {
                        $query  ->whereRaw("concat(lname, ' ', fname, ' ', sname) like '%".$q."%' ")
                                ->orWhere('username', 'like', '%' . $q . '%');
                        });
                }

                if($s){
                    if($s == '3')  $s = '0';

                    $search->where('status', $s);

                }

                $users = $search->orderBy('id', 'ASC')->paginate(50);

                $users->appends ( array (
                    'f' => $f,
                    'q' => $q,
                    's' => $s
                ) );

                return view('control.user-message',
                    compact('users','q','f','s','filials','f_title','inbox_count','sent_count','all_inbox_count'))
                    ->with('i', (request()->input('page', 1) - 1) * 50);
            }else{
                return response()->view('errors.' . '404', [], 404);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */

    // Filter the User's Message attributes: From when till when, Sent or Received, Deleted or Not
    public function spesificUserseMessagesSearch($id){

        foreach (json_decode(Auth::user()->roles) as $user){
            if($user == 'admin'){
                @include('count_message.php');

                $m_search       = Input::get ( 'm_search'   );
                $from           = Input::get ( 'from'       );
                $till           = Input::get ( 'till'       );
                $isDeleted      = Input::get ( 'isDeleted'  );
                $typeOfMessage  = Input::get ( 'typeOfMessage' );


                if($from == '')     $from="2020-01-01";
                if($till == '')     $till=date("Y-m-d",strtotime('tomorrow'));


                $users = User::where('id', $id)->first();


                if($typeOfMessage == 0){

                    $search = MessageUsers::whereBetween('created_at',[$from, $till]);
                    $search_sent = Message::whereBetween('created_at',[$from, $till]);

                    if($m_search){

                        $search->where('message',function ($query) use($m_search){
                            $query->where('subject', 'like', '%'.$m_search.'%');
                        });

                        $search_sent->where('subject', 'like', '%'.$m_search.'%');

                    }

                    if($isDeleted){
                        if($isDeleted == '3')  $isDeleted = '0';

                        $search->where('is_deleted',$isDeleted);
                        $search_sent->where('is_deleted',$isDeleted);

                    }
                    $user_message   = $search->where('to_users_id', $id)->orderBy('id', 'DESC')->paginate(50);

                    $s_count        = $search_sent->where('user_id', $id)->count('id');

                    $r_count  = $user_message->total();

                    // print_r($s_count);die;

                    $delete_arr = array();
                    foreach ($user_message as $key => $value) {
                        array_push($delete_arr,$value->id);
                    }

                    $user_message->appends ( array (
                        'm_search'      => $m_search,
                        'from'          => $from,
                        'till'          => $till,
                        'isDeleted'     => $isDeleted,
                        'typeOfMessage' => $typeOfMessage
                    ) );

                    return view('control.user-message-delete',
                    compact('id','users','s_count','r_count','user_message','delete_arr','m_search','from','till','isDeleted','typeOfMessage','inbox_count','sent_count','all_inbox_count'))
                    ->with('i', (request()->input('page', 1) - 1) * 50);
                }else{

                    $search = Message::whereBetween('created_at',[$from, $till]);                                       # For sent

                    $search_rec = MessageUsers::whereBetween('created_at',[$from, $till])->where('to_users_id', $id);    # For received


                    if($m_search){
                        $search->where('subject', 'like', '%'.$m_search.'%');

                        $search_rec->where('message', function($query) use($m_search){

                            $query->where('subject', 'like', '%'.$m_search.'%');

                        });
                    }

                    if($isDeleted){

                        $search->where('is_deleted', $isDeleted);

                        $search_rec->where('is_deleted', $isDeleted);

                    }

                    $messages = $search->where('user_id', $id)->orderBy('id', 'DESC')->paginate(50);

                    $s_count = $messages->total();
                    $r_count = $search_rec->count('id');

                    $delete_arr = array();
                    foreach ($messages as $key => $value) {
                        array_push($delete_arr,$value->id);
                    }

                    $messages->appends ( array (
                        'from'          => $from,
                        'till'          => $till,
                        'isDeleted'     => $isDeleted,
                        'typeOfMessage' => $typeOfMessage
                    ) );

                    return view('control.user-message-delete',
                    compact('id','users','s_count','r_count','messages','delete_arr','m_search','from','till','isDeleted','typeOfMessage','inbox_count','sent_count','all_inbox_count'))
                    ->with('i', (request()->input('page', 1) - 1) * 50);
                }
            }else{
                return response()->view('errors.' . '404', [], 404);
            }
        }
    }


    // Message view

    public function viewMessageDelete($id, $user_id){
        foreach (json_decode(Auth::user()->roles) as $user){
            if($user == 'admin'){

                $departments = Department::where('parent_id', '=', 0)->where('status', '=', 1)->get();

                $message = Message::where('id', $id)->first();

                if (empty($message)) {
                    return response()->view('errors.' . '404', [], 404);
                }


                // Jamshid if the message forwarded display files from orign

                $messageForwards = MessageForward::where('new_message_id','=', $message->id)->first();
                $message_files = MessageFiles::where('message_id', '=', $message->id)->orderby('file_extension')->get();

                if($messageForwards != null && $messageForwards->status != 'reply'){
                    $message_files = MessageFiles::where('message_id', '=', $messageForwards->message_id)->orderby('file_extension')->get();
                }

                $to_users = MessageUsers::where('message_id', $message->id)->get();


                @include('count_message.php');

                return view('control.message_view',
                    compact('message','id', 'departments','message_files','to_users','inbox_count','sent_count','all_inbox_count'));
            }
            else{
                return response()->view('errors.' . '404', [], 404);
            }
        }
    }

    // *************** Monitoring Message or Control Message ************************** //

    public function monitoringMessSearch(){
        foreach (json_decode(Auth::user()->roles) as $user){
            if($user == 'admin'){
                @include('count_message.php');

                $f              = Input::get ( 'f'          );
                $m_search       = Input::get ( 'm_search'   );
                $u_search       = Input::get ( 'u_search'   );
                $user_status    = Input::get ( 'user_status');
                $from           = Input::get ( 'from'       );
                $till           = Input::get ( 'till'       );
                $isDeleted      = Input::get ( 'isDeleted'  );


                if($from == '') $from="2020-01-01";
                if($till == '') $till=date("Y-m-d",strtotime('tomorrow'));

                $search  = Message::whereBetween('created_at',[$from,$till]);
                $f_title = Department::select('title')->where('branch_code', $f)->where('parent_id', '0')->first();
                $filials = Department::where('parent_id', 0)->where('status', 1)->orderBy('id','ASC')->get();
                $users   = Message::select('user_id')->distinct()->get();


                if($u_search)   $search->where('user_id', $u_search);


                if($f)          $search->where('from_branch', $f);


                if($m_search)   $search->where('subject', 'like', '%'.$m_search.'%');


                if ($user_status) {
                    if($user_status == '3')  $user_status = '0';
                    $search->whereHas('user', function($query) use($user_status){
                        $query->where('status', $user_status);

                    });
                }


                if($isDeleted){
                    if($isDeleted == '3')  $isDeleted = '0';
                    $search->where('is_deleted',$isDeleted);

                }

                $messages_result = $search->get();

                $delete_arr = array();
                foreach ($messages_result as $key => $value) {
                    array_push($delete_arr,$value->id);
                }

                $messages = $search->orderBy('id', 'DESC')->paginate(50);


                // Collect all message_users->id into array "delete_arr" to delete all result



                $messages->appends ( array (
                    'f'             => $f,
                    'm_search'      => $m_search,
                    'u_search'      => $u_search,
                    'user_status'   => $user_status,
                    'from'          => $from,
                    'till'          => $till,
                    'isDeleted'     => $isDeleted
                ) );

                return view('control.mes-control',
                compact('messages','delete_arr','filials','users','f','user_status','m_search','u_search',
                    'f_title','from','till','isDeleted','inbox_count','sent_count','all_inbox_count'))
                ->with('i', (request()->input('page', 1) - 1) * 50);
            }else{
                return response()->view('errors.' . '404', [], 404);
            }
        }
    }


    public function messageUsersDeleteSearch(){
        foreach (json_decode(Auth::user()->roles) as $user){
            if($user == 'admin'){

                @include('count_message.php');

                $f              = Input::get ( 'f' );
                $m_search       = Input::get ( 'm_search' );
                $u_search       = Input::get ( 'u_search' );
                $from           = Input::get ( 'from' );
                $till           = Input::get ( 'till' );
                $isDeleted      = Input::get ( 'isDeleted' );

                if($from == '') $from="2020-01-01";
                if($till == '') $till=date("Y-m-d",strtotime('tomorrow'));

                $f_title    = Department::select('title')->where('branch_code', $f)->where('parent_id', '0')->first();
                $filials    = Department::where('parent_id', 0)->where('status', 1)->orderBy('id','ASC')->get();
                $search     = MessageUsers::whereBetween('created_at',[$from, $till]);
                $users      = MessageUsers::select('from_users_id')->distinct()->get();

                if($isDeleted){
                    if($isDeleted == '3')  $isDeleted = '0';
                    $search->where('is_deleted', $isDeleted);

                }

                if($f){
                    $search->whereHas('user', function($query) use ($f) {

                        $query->where('branch_code', $f);

                    });
                }


                if($u_search)   $search->where('to_users_id', $u_search);


                if($m_search){
                    $search->whereHas('message', function ($query) use($m_search) {

                        $query->where('subject', 'like', '%'.$m_search.'%');

                    });
                }


                // Collect all message_users->id into array "delete_arr" to delete all result

                $messages_result = $search->get();

                $delete_arr = array();
                foreach ($messages_result as $key => $value) {
                    array_push($delete_arr,$value->id);
                }


                $message_users = $search->orderBy('id', 'DESC')->paginate(100);

                $message_users->appends ( array (
                    'f'         => $f,
                    'm_search'  => $m_search,
                    'u_search'  => $u_search,
                    'isDeleted' => $isDeleted,
                    'from'      => $from,
                    'till'      => $till
                ) );

                return view('control.message-users',
                compact('message_users','users','delete_arr','filials','f_title','f','m_search','u_search','from','till','isDeleted','inbox_count','sent_count','all_inbox_count'))
                ->with('i', (request()->input('page', 1) - 1) * 100);
            }
            else{
                return response()->view('errors.' . '404', [], 404);
            }
        }
    }

    // ******************************** File Control ********************************** //

    public function fileControlSearch(){
        foreach (json_decode(Auth::user()->roles) as $user){
            if($user == 'admin'){

                @include('count_message.php');

                $f          = Input::get ( 'f'          );
                $f_search   = Input::get ( 'f_search'   );
                $m_search   = Input::get ( 'm_search'   );
                $u_search   = Input::get ( 'u_search'   );
                $user_status= Input::get ( 'user_status');
                $from       = Input::get ( 'from'       );
                $till       = Input::get ( 'till'       );
                $isDeleted  = Input::get ( 'isDeleted'  );

                if($from == '') $from="2020-01-01";
                if($till == '') $till=date("Y-m-d",strtotime('tomorrow'));

                $f_title = Department::  select('title')
                    ->where('branch_code', $f)
                    ->where('parent_id', '0')
                    ->first();

                $filials = Department::  where('parent_id', 0)
                    ->where('status', 1)
                    ->orderBy('id','ASC')
                    ->get();

                $users  = MessageUsers::select('from_users_id')->distinct()->get();
                $search = MessageFiles::whereBetween('created_at', [$from, $till]);

                if($isDeleted){
                    if($isDeleted == '3')  $isDeleted = '0';
                    $search->whereHas('message', function($query) use($isDeleted) {
                        $query->where('is_deleted', $isDeleted);
                    });

                }

                if($f){
                    $search->whereHas('message', function($query) use($f) {

                        $query->where('from_branch', $f);

                    });
                }


                if($m_search){
                    $search->whereHas('message', function($query) use($m_search) {

                        $query->where('subject', 'like', '%'.$m_search.'%');

                    });
                }


                if($f_search)   $search->where('file_name','like', '%'.$f_search.'%' );


                if($user_status){
                    $search->whereHas('message', function($query) use($user_status) {

                        $query->whereHas('user', function ($q) use($user_status) {
                            $q->where('status', $user_status);
                        });

                    });
                }


                if($u_search){
                    $search->whereHas('message', function($query) use($u_search) {

                        $query->whereHas('user', function ($q) use($u_search) {
                            $q->where('id', $u_search);
                        });

                    });
                }

                $delete_arr = array();

                $file_inf_size = $search->get();


                foreach ($file_inf_size as $key => $value) {

                    array_push($delete_arr,$value->id);

                }

                $file_info = $search->orderBy('file_size', 'DESC')->paginate(50);





                $file_info->appends ( array (
                    'f'             => $f,
                    'f_search'      => $f_search,
                    'm_search'      => $m_search,
                    'u_search'      => $u_search,
                    'user_status'   => $user_status,
                    'from'          => $from,
                    'till'          => $till,
                    'isDeleted'     => $isDeleted
                ) );


                return view('control.file-delete',
                    compact('file_info','f_search','u_search','m_search','users','delete_arr','f','f_title',
                        'user_status','from','till','isDeleted','filials','inbox_count',
                        'sent_count','all_inbox_count','file_inf_size'))
                    ->with('i', (request()->input('page', 1) - 1) * 50);
            }else{
                return response()->view('errors.' . '404', [], 404);
            }
        }
    }




    // ******************************* Delete  Functions ******************************* //
    // WARNING !!!
    // Delete Selected the Spesific User's Messages
    // If you try to delete sent messages, you will have to delete the message from 3 tables which are 'messages', 'message-users' and 'message_files'.
    // If you want to delete received messages, you need to delete from only 'message_users' table .

    public function deleteMultipleFiles(Request $request){
        $id = $request->input('ids');
        $filename = MessageFiles::whereIn('id', explode(',',$id))
        ->select('file_hash')
        ->get();

        foreach($filename as $f){
            MessageFiles::where('file_hash', $f->file_hash)->delete();
            $file_path = public_path().'/FilesFTP/'.$f->file_hash;
            if(file_exists($file_path)){
                unlink($file_path);
            }
        }

        return response()->json(['status'=>true,'msg'=>'Tanlangan fayllar muvaffaqiyatli o`chirildi']);
    }
    // end

    // Delete General Received Messages
    public function deleteMultipleGeneralMessages(Request $request){
        $id = $request->input('ids');
        // 1. Delete from messages
        // 2. Message files
        // 3. Message users
        // 4. Message forward
        //      a. Messages
        //      b. Message file
        //      c. Message users
        //      d. Message forward
        $filename = MessageFiles::whereIn('message_id', explode(',',$id))->select('id','file_hash')->get();

        if(count($filename)){
            foreach($filename as $f){
                $file_path = public_path().'/FilesFTP/'.$f->file_hash;
                if(file_exists($file_path)){
                    unlink($file_path);
                }
                MessageFiles::where('id', $f->id)->delete();
            }
        }
        MessageUsers::whereIn('message_id', explode(",",$id))->delete();

        $mesForward = MessageForward::whereIn('message_id', explode(",",$id))->get();
        if(count($mesForward)){
            foreach($mesForward as $mf){
                $fname = MessageFiles::where('message_id', $mf->new_message_id)->select('id','file_hash')->get();
                if(count($fname)){
                    foreach($fname as $fn){
                        $file_path = public_path().'/FilesFTP/'.$fn->file_hash;
                        if(file_exists($file_path)){
                            unlink($file_path);
                        }
                        MessageFiles::where('id', $fn->id)->delete();
                    }
                }
                Message::where('id',$mf->new_message_id)->delete();
                MessageUsers::where('message_id',$mf->new_message_id)->delete();
                $mForward = MessageForward::where('message_id',$mf->new_message_id)->get();
                if(count($mForward)){
                    foreach ($mForward as $key => $mf2){
                        $f2name = MessageFiles::where('message_id',$mf2->message_id)->select('id','file_hash')->get();
                        if(count($f2name)){
                            foreach($f2name as $f2n){
                                $file_path = public_path().'/FilesFTP/'.$f2n->file_hash;
                                if(file_exists($file_path)){
                                    unlink($file_path);
                                }
                                MessageFiles::where('id', $f2n->id)->delete();
                            }
                        }
                        Message::where('id',$mf2->new_message_id)->delete();
                        MessageUsers::where('message_id',$mf2->new_message_id)->delete();
                        MessageForward::where('message_id',$mf2->message_id)->delete();
                    }
                }
            }
            MessageForward::whereIn('message_id', explode(",",$id))->delete();
        }
        Message::whereIn('id', explode(",",$id))->delete();



        return response()->json(['status'=>true,'msg'=>'Tanlangan xatlar muvaffaqiyatli o`chirildi']);
    }
    // end

    // Delete Spesific User Received Messages
    public function deleteMultipleSpesificUserMessages(Request $request){

        $id = $request->input('ids');
        MessageUsers::whereIn('id', explode(",",$id))->delete();
        return response()->json(['status'=>true,'msg'=>'Tanlangan RECEIVED xatlar muvaffaqiyatli o`chirildi']);
    }


    // Delete from Inside of the Message
    public function deleteInsideMessage(Request $request){
        $to_users = $request->input('user_id');
        $message_id = $request->input('message_id');

        MessageUsers::where('message_id', $message_id)->whereIn('to_users_id', $to_users)->delete();
        return response()->json(['status'=>true,'ids' => $to_users,'message_id' => $message_id,'msg'=>'MUVOFFAQIYAT! Tanlangan xodimlarga yuborilgan xatlar muvaffaqiyatli o`chirildi!']);
    }
}
?>
