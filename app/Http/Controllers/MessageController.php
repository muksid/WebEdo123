<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 30.12.2019
 * Time: 18:10
 */

namespace App\Http\Controllers;

use App\Group;
use App\GroupUsers;
use App\Message;
use App\Department;
use App\MessageForward;
use App\MesType;
use App\MessageUsers;
use App\MessageFiles;
use App\User;
use Carbon\Carbon;
use File;
use Illuminate\Http\Request;
use DB;
use Hash;
use Illuminate\Support\Facades\Input;
use Response;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{

    public function store(Request $request)
    {
        $this->validate($request, [
            'subject' => 'required',
            'to_users' => 'required',
            'mes_files' => 'max:131072'
            //'mes_files' => 'size:50000' // 131072 / 128 * 1024 = 128 MB
        ]);

        $file = new Message();

        if ($request->input('mes_term') !== null) {
            $file->mes_term = $request->input('mes_term');
        } else {
            $file->mes_term = 0;
        }

        if ($request->input('text') !== null) {
            $file->text = $request->input('text');
        } else {
            $file->text = '';
        }
        $lastMessage = Message::orderBy('id', 'DESC')->first();
        $getLastInsertId = $lastMessage->id+1;
        $file->mes_gen = $getLastInsertId.'_'.md5(Auth::id()) . strtotime(date('Y-m-d H:i:s', strtotime(date_default_timezone_get())));

        $file->mes_type = $request->input('mes_type');

        $file->subject = $request->input('subject');

        $file->from_branch = Auth::user()->branch_code;

        $file->user_id = Auth::id();

        $file->is_deleted = 0;

        $file->save();


        foreach ($request->input('to_users') as $item) {
            if ($item != 0) {
                $message_users = new MessageUsers();
                $message_users->to_users_id = $item;
                $message_users->message_id = $file->id;
                $message_users->from_users_id = Auth::id();
                $message_users->is_readed = 0;
                $message_users->is_deleted = 0;
                $message_users->save();
            }
        }

        if ($request->file('mes_files') != null) {
            foreach ($request->file('mes_files') as $item) {
                if ($item != 0) {
                    $message_files = new MessageFiles();
                    $message_files->message_id = $file->id;
                    $message_files->file_hash = $file->id . '_' . Auth::id(). '_' . date('dmYHis') . uniqid() . '.' . $item->getClientOriginalExtension();
                    $message_files->file_size = $item->getSize();
                    $item->move(public_path() . '/FilesFTP/', $message_files->file_hash);
                    $message_files->file_name = $item->getClientOriginalName();
                    $message_files->file_extension = $item->getClientOriginalExtension();
                    $message_files->save();
                }
            }
        }


        // Jamshid store reply and forward messages
        $message_id_for_forward = Message::where('mes_gen', '=', $file->mes_gen)->first();
        if($request->input('status') == "reply"){

            foreach ($request->input('to_users') as $item) {
                if ($item != 0) {
                    $message_foward = new MessageForward();
                    $message_foward->message_id     =   $request->input('message_id');
                    $message_foward->new_message_id =   $message_id_for_forward->id;
                    $message_foward->from_user_id   =   Auth::id();
                    $message_foward->to_user_id     =   $item;
                    $message_foward->title          =   $request->input('subject');
                    $message_foward->text           =   $request->input('text');
                    $message_foward->status         =   $request->input('status');
                    $message_foward->save();
                }
            }

        }
        if($request->input('status') == "forward"){

            $var = MessageForward::where('new_message_id',$request->input('message_id'))->first();

            foreach ($request->input('to_users') as $item) {
                if ($item != 0) {
                    $message_foward = new MessageForward();
                    if($var != null){
                        $message_foward->message_id     = $var->message_id;
                    }else{
                        $message_foward->message_id     =   $request->input('message_id');
                    }

                    $message_foward->new_message_id =   $message_id_for_forward->id;
                    $message_foward->from_user_id   =   Auth::id();
                    $message_foward->to_user_id     =   $item;
                    $message_foward->title          =   $request->input('subject');
                    $message_foward->text           =   $request->input('text');
                    $message_foward->status         =   $request->input('status');
                    $message_foward->save();
                }
            }

        }

        return back()->with('success', 'Sizning xatingiz muvaffaqiyatli jo`natildi');
    }

    public function store1(Request $request)
    {
        $this->validate($request, [
            'subject' => 'required',
            'groups_id' => 'required',
            'mes_files' => 'max:131072'
            //'mes_files' => 'size:50000' // 131072 / 128 * 1024 = 128 MB
        ]);

        $file = new Message();

        if ($request->input('mes_term') !== null) {
            $file->mes_term = $request->input('mes_term');
        } else {
            $file->mes_term = 0;
        }

        if ($request->input('text') !== null) {
            $file->text = $request->input('text');
        } else {
            $file->text = '';
        }
        $lastMessage = Message::orderBy('id', 'DESC')->first();
        $getLastInsertId = $lastMessage->id+1;
        $file->mes_gen = $getLastInsertId.'_'.md5(Auth::id()) . strtotime(date('Y-m-d H:i:s', strtotime(date_default_timezone_get())));

        $file->mes_type = $request->input('mes_type');

        $file->subject = $request->input('subject');

        $file->from_branch = Auth::user()->branch_code;

        $file->user_id = Auth::id();

        $file->is_deleted = 0;

        $file->save();

        // send message to users with search
        if ($request->input('to_users') != null) {
            foreach ($request->input('to_users') as $item) {
                if ($item != 0) {
                    $message_users = new MessageUsers();
                    $message_users->to_users_id = $item;
                    $message_users->message_id = $file->id;
                    $message_users->from_users_id = Auth::id();
                    $message_users->is_readed = 0;
                    $message_users->is_deleted = 0;
                    $message_users->save();
                }
            }
        }

        foreach ($request->input('groups_id') as $item){
            $group_users = GroupUsers::where('group_id', '=', $item)->get();
            foreach ($group_users as $user){
                $message_users = new MessageUsers();
                $message_users->to_users_id = $user->users_id;
                $message_users->message_id = $file->id;
                $message_users->from_users_id = Auth::id();
                $message_users->is_readed = 0;
                $message_users->is_deleted = 0;
                $message_users->save();
            }
        }

        if ($request->file('mes_files') != null) {
            foreach ($request->file('mes_files') as $item) {
                if ($item != 0) {
                    $message_files = new MessageFiles();
                    $message_files->message_id = $file->id;
                    $message_files->file_hash = $file->id . '_' . Auth::id(). '_' . date('dmYHis') . uniqid() . '.' . $item->getClientOriginalExtension();
                    $message_files->file_size = $item->getSize();
                    $item->move(public_path() . '/FilesFTP/', $message_files->file_hash);
                    $message_files->file_name = $item->getClientOriginalName();
                    $message_files->file_extension = $item->getClientOriginalExtension();
                    $message_files->save();
                }
            }
        }

        return back()->with('success', 'Sizning xatingiz muvaffaqiyatli jo`natildi');
    }

    public function compose()
    {
        $departments = Department::where('parent_id', 0)->where('status', 1)->orderBy('depart_id', 'ASC')->get();
        $users = DB::table('users as a')->where('a.status', '=', 1)->select('a.id', 'a.lname', 'a.fname')->get();
        $mes_type = MesType::all();

        // count() //
        @include('count_message.php');

        return view('messages.compose', compact('users','departments', 'mes_type',
            'inbox_count', 'sent_count', 'term_inbox_count', 'all_inbox_count'));
    }

    public function groupCompose()
    {
        $departments = Department::where('parent_id', '=', 0)->where('status', '=', 1)->get();

        $groups = Group::where('user_id', '=', Auth::id())->where('status', '=', 1)->get();

        $users = DB::table('users as a')
            ->where('a.status', '=', 1)
            ->where('a.id', '!=', Auth::id())
            ->select('a.id', 'a.lname', 'a.fname')->get();

        $mes_type = MesType::all();

        // count() //
        @include('count_message.php');

        return view('messages.groupCompose', compact('departments', 'groups', 'users',
            'mes_type', 'inbox_count', 'sent_count', 'term_inbox_count', 'all_inbox_count'));
    }

    public function eFSent()
    {
        //
        $search = Message::where('from_branch', Auth::user()->branch_code)
            ->where('user_id', Auth::id())
            ->where('is_deleted', 0);

        $u = Input::get ( 'u' );
        $t = Input::get ( 't' );
        $d = Input::get ( 'd' );

        if($u) {
            $search->whereHas('messageUsers', function ($query) use ($u) {

                $query->where('to_users_id', $u);

                $query->where('from_users_id', Auth::id());

            });
        }
        if($t) {
            $search->where(function ($query) use ($t) {

                $query->where('subject', 'LIKE', '%'.$t.'%');

                $query->where('user_id', Auth::id());

            });
        }
        if($d) {
            $search->where(function ($query) use ($d) {

                $query->where('created_at', 'LIKE', '%'.$d.'%');

                $query->where('user_id', Auth::id());

            });
        }

        $models = $search->orderBy('id', 'DESC')
            ->paginate(25);

        $models->appends ( array (
            'u' => Input::get ( 'u' ),
            't' => Input::get ( 't' ),
            'd' => Input::get ( 'd' )
        ) );

        $users = User::select('id', 'fname', 'lname', 'branch_code')->where('status', 1)->get();

        $searchUser = User::select('id', 'fname', 'lname', 'branch_code')->where('id', $u)->first();


        // count() //
        @include('count_message.php');

        return view('messages.sent',compact('models','u','t','d','users', 'searchUser',
            'inbox_count','sent_count','term_inbox_count','all_inbox_count'));
    }

    // for Performance control
    public function control()
    {
        foreach (json_decode(Auth::user()->roles) as $user){
            switch($user){
                case('admin');
                    $control = DB::table('messages as a')
                        ->join('users as u', function ($join) {
                            $join->on('a.user_id', '=', 'u.id')
                                ->where('u.roles', '=', '["office"]');
                        })
                        ->select('a.*','a.id as mc_id','u.lname','u.fname')
                        ->where('a.mes_type', '=', 'control')
                        ->where('a.is_deleted', '=', 0)
                        ->orderBy('a.id', 'desc')
                        ->paginate(100);
                    break;
                case('control');
                    $control = DB::table('messages as a')
                        ->join('users as u', function ($join) {
                            $join->on('a.user_id', '=', 'u.id')
                                ->where('u.roles', '=', '["office"]');
                        })
                        ->select('a.*','a.id as mc_id','u.lname','u.fname')
                        ->where('a.mes_type', '=', 'control')
                        ->where('a.is_deleted', '=', 0)
                        ->orderBy('a.id', 'desc')
                        ->paginate(100);
                    break;
                default;
                    $control = MessageUsers::where('id', '=', 0)->first();

                    if (empty($control)) {
                        return response()->view('errors.' . '404', [], 404);
                    }
                    break;

            }
        }

        // count() //
        @include('count_message.php');

        return view('messages.control',
            compact('control','inbox_count','sent_count','term_inbox_count','all_inbox_count'))
            ->with('i', (request()->input('page', 1) - 1) * 100);
    }

    public function show($mes_gen)
    {

        $departments = Department::where('parent_id', '=', 0)->where('status', '=', 1)->orderBy('depart_id', 'ASC')->get();

        $message = DB::table('messages as a')
            ->join('users as u', 'a.user_id', '=', 'u.id')
            ->join('departments as b', 'u.branch_code', '=', 'b.branch_code')
            ->select('a.*','u.id as u_id','u.fname','u.lname','u.job_title','b.title as branch')
            ->where('a.mes_gen', '=', $mes_gen)
            ->where('b.parent_id', '=', 0)
            ->first();
        if (empty($message)) {
            return response()->view('errors.' . '404', [], 404);
        }

        // Jamshid if the message forwarded display files from orign

        $messageForwards = MessageForward::where('new_message_id','=', $message->id)->first();
        $message_files = MessageFiles::where('message_id', '=', $message->id)->orderby('file_extension')->get();
        if($messageForwards != null && $messageForwards->status != 'reply'){
            $message_files = MessageFiles::where('message_id', '=', $messageForwards->message_id)->orderby('file_extension')->get();
        }

        $message_users = MessageUsers::where('message_id', '=', $message->id)->where('to_users_id', '=', Auth::id())->first();
        //$message_files = MessageFiles::where('message_id', '=', $message->id)->orderby('file_extension')->get();
        $to_users = DB::table('message_users as a')
            ->join('users as u', 'a.to_users_id', '=', 'u.id')
            ->select('u.*', 'a.is_readed', 'a.created_at', 'a.readed_date')
            ->where('a.message_id', '=', $message->id)
            ->get();
        if ($message_users->is_readed == 0){
            $message_users->update(['is_readed' => 1, 'readed_date' => Carbon::now()]);
        } else{
            $message_users->update(['is_readed' => 1]);
        }

        $fromUserAllMessages = DB::table('message_users as a')
            ->join('messages as m', 'a.message_id', '=', 'm.id')
            ->join('mes_types as mt', 'm.mes_type', '=', 'mt.message_type')
            ->select('m.*', 'a.id as mes_user_id', 'a.is_readed', 'a.readed_date', 'mt.title AS message_type')
            ->where('a.from_users_id', $message->u_id)
            ->where('a.to_users_id', Auth::id())
            ->where('a.is_deleted', 0)
            ->where('m.mes_gen', '!=', $mes_gen)
            ->orderBy('a.id', 'DESC')
            ->paginate(10);

        // count() //
        @include('count_message.php');

        return view('messages.show',compact('message','departments', 'message_files','to_users','fromUserAllMessages',
            'inbox_count','sent_count','term_inbox_count','all_inbox_count'));
    }

    public function view($mes_gen)
    {
        $departments = Department::where('parent_id', '=', 0)->where('status', 1)->orderBy('depart_id', 'ASC')->get();

        $message = DB::table('messages as a')
            ->join('users as u', 'a.user_id', '=', 'u.id')
            ->join('departments as b', 'u.branch_code', '=', 'b.branch_code')
            ->select('a.*','u.id as u_id','u.fname','u.lname','u.job_title','b.title as branch')
            ->where('a.mes_gen', '=', $mes_gen)
            ->where('b.parent_id', '=', 0)
            ->first();
        if (empty($message)) {
            return response()->view('errors.' . '404', [], 404);
        }


        // Jamshid if the message forwarded display files from orign

        $messageForwards = MessageForward::where('new_message_id','=', $message->id)->first();
        $message_files = MessageFiles::where('message_id', '=', $message->id)->orderby('file_extension')->get();
        // print_r($messageForwards->status);die;
        if($messageForwards != null && $messageForwards->status != 'reply'){
            $message_files = MessageFiles::where('message_id', '=', $messageForwards->message_id)->orderby('file_extension')->get();
        }

        $to_users = DB::table('message_users as a')
            ->join('users as u', 'a.to_users_id', '=', 'u.id')
            ->select('a.*','u.id','u.lname','u.fname','u.branch_code','u.job_title')
            ->where('a.message_id', $message->id)
            ->get();

        $fromUserAllMessages = DB::table('message_users as a')
            ->join('messages as m', 'a.message_id', '=', 'm.id')
            ->join('mes_types as mt', 'm.mes_type', '=', 'mt.message_type')
            ->select('m.*', 'a.id as mes_user_id', 'a.is_readed', 'a.readed_date', 'mt.title AS message_type')
            ->where('a.from_users_id', $message->u_id)
            ->where('a.to_users_id', Auth::id())
            ->where('a.is_deleted', 0)
            ->where('m.mes_gen', '!=', $mes_gen)
            ->orderBy('a.id', 'DESC')
            ->paginate(10);

        // count() //
        @include('count_message.php');

        return view('messages.view',compact('message', 'departments','message_files','to_users','fromUserAllMessages',
            'inbox_count','sent_count','term_inbox_count','all_inbox_count'));
    }

    public function viewEFSent($user_id, $mes_gen)
    {
        $departments = Department::where('parent_id', 0)->where('status', 1)->orderBy('depart_id', 'ASC')->get();

        $message = Message::where('user_id', $user_id)->where('mes_gen', $mes_gen)->firstOrFail();

        // Jamshid if the message forwarded display files from orign
        $messageForwards = MessageForward::where('new_message_id','=', $message->id)->first();
        $message_files = MessageFiles::where('message_id', '=', $message->id)->orderby('file_extension')->get();
        if($messageForwards != null && $messageForwards->status != 'reply'){
            $message_files = MessageFiles::where('message_id', '=', $messageForwards->message_id)->orderby('file_extension')->get();
        }

        // count() //
        @include('count_message.php');

        return view('messages.view_sent',compact('message','departments','message_files','inbox_count','sent_count','term_inbox_count','all_inbox_count'));
    }

    public function viewControl($mes_gen)
    {
        $departments = Department::where('parent_id', 0)->where('status', 1)->orderBy('depart_id', 'ASC')->get();

        $message = Message::where('mes_gen', '=', $mes_gen)
            ->join('users as u', 'messages.user_id', '=', 'u.id')
            ->select('messages.*','u.lname','u.fname','u.job_title','u.branch_code')
            ->first();
        if (empty($message)) {
            return response()->view('errors.' . '404', [], 404);
        }

        // Jamshid if the message forwarded display files from orign
        $messageForwards = MessageForward::where('new_message_id','=', $message->id)->first();
        $message_files = MessageFiles::where('message_id', '=', $message->id)->orderby('file_extension')->get();
        if($messageForwards != null && $messageForwards->status != 'reply'){
            $message_files = MessageFiles::where('message_id', '=', $messageForwards->message_id)->orderby('file_extension')->get();
        }

        //$message_files = MessageFiles::where('message_id', '=', $message->id)->orderby('file_extension')->get();
        $to_users = DB::table('message_users as a')
            ->join('users as u', 'a.to_users_id', '=', 'u.id')
            ->select('u.*', 'a.is_readed', 'a.readed_date')
            ->where('a.message_id', '=', $message->id)
            ->get();

        // count() //
        @include('count_message.php');

        return view('messages.view_control',compact('message','departments','message_files','to_users','inbox_count','sent_count','term_inbox_count','all_inbox_count'));
    }

    public function view_my($mes_gen)
    {
        $message = Message::where('mes_gen', '=', $mes_gen)
            ->join('users as u', 'messages.user_id', '=', 'u.id')
            ->select('messages.*','u.lname','u.fname','u.job_title','u.branch_code')
            ->first();
        if (empty($message)) {
            return response()->view('errors.' . '404', [], 404);
        }

        // Jamshid if the message forwarded display files from orign
        $messageForwards = MessageForward::where('new_message_id','=', $message->id)->first();
        $message_files = MessageFiles::where('message_id', '=', $message->id)->orderby('file_extension')->get();
        if($messageForwards != null && $messageForwards->status != 'reply'){
            $message_files = MessageFiles::where('message_id', '=', $messageForwards->message_id)->orderby('file_extension')->get();
        }

        //$message_files = MessageFiles::where('message_id', '=', $message->id)->orderby('file_extension')->get();
        $to_users = DB::table('message_users as a')
            ->join('users as u', 'a.to_users_id', '=', 'u.id')
            ->select('u.*', 'a.is_readed', 'a.readed_date')
            ->where('a.message_id', '=', $message->id)
            ->get();

        // count() //
        @include('count_message.php');

        return view('messages.view_my',compact('message','message_files','to_users','inbox_count','sent_count','term_inbox_count','all_inbox_count'));
    }

    public function downloadFile($file){

        if (file_exists(public_path() . "/FilesFTP/" . $file)) {

            $orgName = MessageFiles::where('file_hash', '=', $file)->first();

            return Response::download(public_path() . "/FilesFTP/".$file,$orgName->file_name);

        } else {

            return back()->with('notFiles', 'Serverdan fayllar topilmadi!');
        }
    }

    public function getDownload($id)
    {

        $model = MessageFiles::find($id);

        $file= public_path(). "/FilesFTP/".$model->file_hash;

        $headers = array(
            'Content-Type: application/octet-stream',
        );

        if(file_exists(public_path() . "/FilesFTP/" . $model->file_hash)){

            return Response::download($file, $model->file_name, $headers);

        } else {

            return back()->with('notFiles', 'Serverdan fayllar topilmadi!');
        }
    }

    public function downloadAll($file){

        $files = MessageFiles::where('message_id', $file)->get();
        if (empty($file)) {
            return back()->with('notFiles', 'Serverdan fayllar topilmadi!');
        }

        $zip_name  = './FilesFTP/' . 'zip_all_' . $files[0]->file_name.'.zip';

        $zip = new \ZipArchive;

        if ($zip->open($zip_name, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {

            foreach($files as $file){

                $zip->addFile(public_path() . "/FilesFTP/" . $file->file_hash, $file->file_name);

            }

            $zip->close();

            return response()->download(public_path() . "/" . $zip_name)->deleteFileAfterSend(true);
        } else {

            return response()->json('Yuklashda xatolik');

        }

    }

    public function previewPdf($file)
    {
        if (file_exists(public_path() . "/FilesFTP/" . $file)) {

            $pathToFile = public_path() . "/FilesFTP/". $file;

            return response()->file($pathToFile);

        } else {

            return response()->json('Serverdan fayl topilmadi!');
        }

    }

    public function previewJpg($file)
    {

        $model = MessageFiles::find($file);
        if (file_exists(public_path() . "/FilesFTP/" . $model->file_hash)) {

            $pathToFile = public_path() . "/FilesFTP/". $model->file_hash;

            return response()->file($pathToFile);

        } else {

            return response()->json('Serverdan fayl topilmadi!');
        }

    }

    public function getAllUsers($q){
        $users = User::select('id', 'lname', 'fname')
            ->where(function ($query) use($q) {

                $query->whereRaw("concat(lname, ' ', fname, ' ', sname) like '%".$q."%' ");

            })
            ->where('status', 1)
            ->get();

        return response()
            ->json(array(
                    'success' => true,
                    'users_count' => $users->count(),
                    'users'   => $users
                )
            );
    }

    public function destroy($id)
    {
        //
        // message users
        $messageUsers = MessageUsers::where('message_id', $id)->get();
        if (!empty($messageUsers)) {
            MessageUsers::where('message_id', $id)->delete();
        }

        // message files
        $messageFiles = MessageFiles::where('message_id', $id)->get();

        if (!empty($messageFiles)) {

            foreach ($messageFiles as $key => $value) {

                $file_path = public_path().'/FilesFTP/'.$value->file_hash;

                if(file_exists($file_path)){
                    unlink($file_path);
                }

            }
            MessageFiles::where('message_id', $id)->delete();
        }

        // model
        $model = Message::findOrFail($id);

        $model->delete();

        return response()->json(array(
                'success' => true,
                'message'   => 'Row Successfully deleted'
            )
        );

    }
}