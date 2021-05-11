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
use Illuminate\Support\Facades\Storage;
use Response;
use Illuminate\Support\Facades\Auth;
use ZipArchive;

class MessageController extends Controller
{

    public function store(Request $request)
    {
        $this->validate($request, [
            'to_users' => 'required',
            'mes_files' => 'max:131072'
        ]);

        $auth = Auth::user();

        $model = new Message();

        $model->user_id = $auth->getAuthIdentifier();

        $model->from_branch = $auth->branch_code;

        $model->subject = $request->subject;

        if (!$model->subject)
        {
            $model->subject = $auth->lname.' '.$auth->fname;
        }

        $model->text = $request->text;

        $model->mes_gen = Auth::id().'_'.sha1(strtotime(now()));

        $model->save();

        foreach ($request->input('to_users') as $user) {
            if ($user != 0) {
                $message_users = new MessageUsers();
                $message_users->to_users_id = $user;
                $message_users->message_id = $model->id;
                $message_users->from_users_id = $auth->getAuthIdentifier();
                $message_users->is_readed = 0;
                $message_users->is_deleted = 0;
                $message_users->save();
            }
        }

        if ($request->file('mes_files') != null) {
            foreach ($request->file('mes_files') as $file) {
                if ($file != 0) {
                    $today = Carbon::today();
                    $year = $today->year;
                    $month = $today->month;
                    $day = $today->day;
                    $path = 'fe/'.$year.'/'.$month.'/'.$day.'/';

                    $message_files = new MessageFiles();
                    $message_files->message_id = $model->id;
                    $message_files->file_path = $path;
                    $message_files->file_hash = $model->id.'_'.$auth->getAuthIdentifier().'_'.date('dmYHis').uniqid().'.'.$file->getClientOriginalExtension();
                    $message_files->file_size = $file->getSize();
                    Storage::disk('ftp_edo')->put($path.$message_files->file_hash, file_get_contents($file->getRealPath()));
                    $message_files->file_name = $file->getClientOriginalName();
                    $message_files->file_extension = $file->getClientOriginalExtension();
                    $message_files->save();
                }
            }
        }

        return back()->with('success', 'Sizning xatingiz muvaffaqiyatli jo`natildi');
    }

    public function storeGroup(Request $request)
    {
        $this->validate($request, [
            'groups_id' => 'required',
            'mes_files' => 'max:131072'
        ]);

        $auth = Auth::user();

        $model = new Message();

        $model->user_id = $auth->getAuthIdentifier();

        $model->from_branch = $auth->branch_code;

        $model->subject = $request->subject;

        if (!$model->subject)
        {
            $model->subject = $auth->lname.' '.$auth->fname;
        }

        $model->text = $request->text;

        $model->mes_gen = Auth::id().'_'.sha1(strtotime(now()));

        $model->save();

        // send message to users with search
        if ($request->input('to_users') != null) {
            foreach ($request->input('to_users') as $user) {
                if ($user != 0) {
                    $message_users = new MessageUsers();
                    $message_users->to_users_id = $user;
                    $message_users->message_id = $model->id;
                    $message_users->from_users_id = $auth->getAuthIdentifier();
                    $message_users->is_readed = 0;
                    $message_users->is_deleted = 0;
                    $message_users->save();
                }
            }
        }

        foreach ($request->input('groups_id') as $g_user){

            $group_users = GroupUsers::where('group_id', $g_user)->get();

            foreach ($group_users as $user){
                $message_users = new MessageUsers();
                $message_users->to_users_id = $user->users_id;
                $message_users->message_id = $model->id;
                $message_users->from_users_id = $auth->getAuthIdentifier();
                $message_users->is_readed = 0;
                $message_users->is_deleted = 0;
                $message_users->save();
            }
        }

        if ($request->file('mes_files') != null) {
            foreach ($request->file('mes_files') as $file) {
                if ($file != 0) {
                    $today = Carbon::today();
                    $year = $today->year;
                    $month = $today->month;
                    $day = $today->day;
                    $path = 'fe/'.$year.'/'.$month.'/'.$day.'/';

                    $message_files = new MessageFiles();
                    $message_files->message_id = $model->id;
                    $message_files->file_path = $path;
                    $message_files->file_hash = $model->id.'_'.$auth->getAuthIdentifier().'_'.date('dmYHis').uniqid().'.'.$file->getClientOriginalExtension();
                    $message_files->file_size = $file->getSize();
                    Storage::disk('ftp_edo')->put($path.$message_files->file_hash, file_get_contents($file->getRealPath()));
                    $message_files->file_name = $file->getClientOriginalName();
                    $message_files->file_extension = $file->getClientOriginalExtension();
                    $message_files->save();
                }
            }
        }

        return back()->with('success', 'Sizning xatingiz muvaffaqiyatli jo`natildi');
    }

    public function storeFR(Request $request)
    {
        //
        $id = $request->message_id;

        $auth = Auth::user();

        $model = new Message();

        $model->user_id = $auth->getAuthIdentifier();

        $model->from_branch = $auth->branch_code;

        $model->subject = $request->subject;

        if (!$model->subject)
        {
            $model->subject = $auth->lname.' '.$auth->fname;
        }

        $model->text = $request->text;

        $model->mes_gen = Auth::id().'_'.sha1(strtotime(now()));

        $model->save();

        foreach ($request->input('to_users') as $user) {
            if ($user != 0) {
                $message_users = new MessageUsers();
                $message_users->to_users_id = $user;
                $message_users->message_id = $model->id;
                $message_users->from_users_id = $auth->getAuthIdentifier();
                $message_users->is_readed = 0;
                $message_users->is_deleted = 0;
                $message_users->save();
            }
        }

        if($request->input('status') == "reply"){

            foreach ($request->input('to_users') as $item) {
                if ($item != 0) {
                    $message_foward = new MessageForward();
                    $message_foward->message_id     =   $id;
                    $message_foward->new_message_id =   $model->id;
                    $message_foward->from_user_id   =   $auth->getAuthIdentifier();
                    $message_foward->to_user_id     =   $item;
                    $message_foward->title          =   $request->subject;
                    $message_foward->text           =   $request->text;
                    $message_foward->status         =   $request->input('status');
                    $message_foward->save();
                }
            }

        }

        if($request->input('status') == "forward"){

            $var = MessageForward::where('new_message_id',$id)->first();

            foreach ($request->input('to_users') as $item) {
                if ($item != 0) {
                    $message_foward = new MessageForward();
                    if($var != null){
                        $message_foward->message_id     = $var->message_id;
                    }else{
                        $message_foward->message_id     =   $request->input('message_id');
                    }

                    $message_foward->new_message_id =   $model->id;
                    $message_foward->from_user_id   =   Auth::id();
                    $message_foward->to_user_id     =   $item;
                    $message_foward->title          =   $request->input('subject');
                    $message_foward->text           =   $request->text;
                    $message_foward->status         =   $request->input('status');
                    $message_foward->save();
                }
            }

        }

        return back()->with('success', 'Sizning xatingiz muvaffaqiyatli jo`natildi');

    }

    public function compose()
    {
        // count() //
        @include('count_message.php');

        return view('messages.compose', compact(
            'inbox_count', 'sent_count', 'all_inbox_count'));
    }

    public function feAjaxCompose(Request $request)
    {
        $auth = Auth::user();

        $model = new Message();

        $model->user_id = $auth->getAuthIdentifier();

        $model->from_branch = $auth->branch_code;

        $model->subject = $request->subject;

        if (!$model->subject)
        {
            $model->subject = $auth->lname.' '.$auth->fname;
        }

        $model->text = $request->text;

        $model->mes_gen = Auth::id().'_'.sha1(strtotime(now()));

        $model->save();

        foreach ($request->input('to_users') as $user) {
            if ($user != 0) {
                $message_users = new MessageUsers();
                $message_users->to_users_id = $user;
                $message_users->message_id = $model->id;
                $message_users->from_users_id = $auth->getAuthIdentifier();
                $message_users->is_readed = 0;
                $message_users->is_deleted = 0;
                $message_users->save();
            }
        }

        if ($request->file('mes_files') != null) {
            foreach ($request->file('mes_files') as $file) {
                if ($file != 0) {
                    $today = Carbon::today();
                    $year = $today->year;
                    $month = $today->month;
                    $day = $today->day;
                    $path = 'fe/'.$year.'/'.$month.'/'.$day.'/';

                    $message_files = new MessageFiles();
                    $message_files->message_id = $model->id;
                    $message_files->file_path = $path;
                    $message_files->file_hash = $model->id.'_'.$auth->getAuthIdentifier().'_'.date('dmYHis').uniqid().'.'.$file->getClientOriginalExtension();
                    $message_files->file_size = $file->getSize();
                    Storage::disk('ftp_edo')->put($path.$message_files->file_hash, file_get_contents($file->getRealPath()));
                    $message_files->file_name = $file->getClientOriginalName();
                    $message_files->file_extension = $file->getClientOriginalExtension();
                    $message_files->save();
                }
            }
        }

        return response()->json(array('success' => true, 'data'=>$request->all()));
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
            'mes_type', 'inbox_count', 'sent_count', 'all_inbox_count'));
    }

    public function feSent(Request $request)
    {
        // count() //
        @include('count_message.php');

        $f = $request->filial;

        $u = $request->user;

        $t = $request->text;

        $r = $request->read;

        $s_d = $request->s_start;

        $e_d = $request->s_end;

        if ($u != '' || $t !='' || $f !='' || $r !=''|| $s_d !='')
        {

            $search = Message::where('user_id', Auth::id())
                ->where('is_deleted', 0);

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

            if($s_d) {
                $search->whereBetween('created_at', [$s_d.' 00:00:00',$e_d.' 23:59:59']);
            }

            $models = $search->orderBy('id', 'DESC')
                ->paginate(25);

            $page = 'templates.searchSent';

            if ($request->page){

                $page = 'messages.sent';
                if (count ( $models ) > 0)
                    return view ( $page,
                        compact('models','u','t','f','s_d','e_d','inbox_count','sent_count','all_inbox_count'));
            }

            return view($page,compact('models'));

        }
        else
        {
            $models = Message::where('user_id', Auth::id())
                ->where('is_deleted', 0)
                ->orderBy('id', 'DESC')
                ->paginate(25);

            return view('messages.sent',compact('models','inbox_count','sent_count','all_inbox_count'));

        }
    }

    public function show(Request $request, $hash)
    {
        //
        $message_id = $request->id;

        $model = Message::findOrFail($message_id);

        $departments = Department::where('parent_id', '=', 0)->where('status', '=', 1)->orderBy('depart_id', 'ASC')->get();

        $messageForwards = MessageForward::where('new_message_id', $message_id)->first();
        $message_files = MessageFiles::where('message_id', '=', $message_id)->orderby('file_extension')->get();
        if($messageForwards != null && $messageForwards->status != 'reply'){
            $message_files = MessageFiles::where('message_id', '=', $messageForwards->message_id)->orderby('file_extension')->get();
        }

        $authAllMessages = MessageUsers::where('from_users_id', $model->user_id)
            ->where('to_users_id', Auth::id())
            ->where('is_deleted', 0)
            ->where('id', '!=', $message_id)
            ->orderBy('id', 'DESC')
            ->paginate(10);

        $message_users = MessageUsers::where('message_id', $message_id)
            ->where('to_users_id', Auth::id())
            ->first();
        if ($message_users->is_readed == 0){
            $message_users->update(['is_readed' => 1, 'readed_date' => Carbon::now()]);
        }

        // count() //
        @include('count_message.php');

        return view('messages.show',compact('model','departments', 'message_files','authAllMessages',
            'inbox_count','sent_count','all_inbox_count'));
    }

    public function view($id, $hash)
    {
        //
        $model = Message::where('id', $id)->where('mes_gen', $hash)->firstOrFail();

        $departments = Department::where('parent_id', '=', 0)->where('status', '=', 1)->orderBy('depart_id', 'ASC')->get();

        $messageForwards = MessageForward::where('new_message_id', $id)->first();

        $message_files = MessageFiles::where('message_id', '=', $id)->orderby('file_extension')->get();
        if($messageForwards != null && $messageForwards->status != 'reply'){
            $message_files = MessageFiles::where('message_id', '=', $messageForwards->message_id)->orderby('file_extension')->get();
        }

        $authAllMessages = MessageUsers::where('from_users_id', $model->user_id)
            ->where('to_users_id', Auth::id())
            ->where('is_deleted', 0)
            ->where('id', '!=', $id)
            ->orderBy('id', 'DESC')
            ->paginate(10);

        $message_users = MessageUsers::where('message_id', $id)
            ->where('to_users_id', Auth::id())
            ->first();
        if ($message_users->is_readed == 0){
            $message_users->update(['is_readed' => 1, 'readed_date' => Carbon::now()]);
        }

        // count() //
        @include('count_message.php');

        return view('messages.view',compact('model', 'message_users','departments','message_files','messageForwards','authAllMessages',
            'inbox_count','sent_count','all_inbox_count'));
    }

    public function viewFESent($id, $hash)
    {
        //
        $model = Message::where('id',$id)->where('mes_gen', $hash)->firstOrFail();

        $messageForwards = MessageForward::where('new_message_id', $id)->first();

        $message_files = MessageFiles::where('message_id', $id)->get();

        if($messageForwards != null && $messageForwards->status != 'reply'){
            $message_files = MessageFiles::where('message_id', $messageForwards->message_id)->get();
        }

        // count() //
        @include('count_message.php');

        return view('messages.view_sent',compact('model','message_files','inbox_count','sent_count','all_inbox_count'));
    }

    public function getBlade(Request $request){

        $type = $request->input('type');

        if ($type == 'compose_search_users')
        {
            $users = DB::table('users as a')->where('a.status', 1)->select('a.id', 'a.lname', 'a.fname')->get();

            $blade = view('templates.composeUsers', compact('users', $users))->render();
        }
        elseif ($type == 'compose_check_users')
        {
            $departments = Department::where('parent_id', 0)->where('status', 1)->orderBy('depart_id', 'ASC')->get();

            $blade = view('templates.composeUsersTree', compact('departments', $departments))->render();
        }
        elseif ($type == 'delete')
        {
            $blade = view('templates.delSentConfirm')->render();
        }
        elseif ($type == 'deleteInbox')
        {
            $blade = view('templates.delInboxConfirm')->render();
        }
        elseif ($type == 'deleteAll')
        {
            $blade = view('templates.delAllConfirm')->render();
        }
        elseif ($type == 'files')
        {
            $id = $request->input('id');

            $forward = MessageForward::where('new_message_id', $id)->first();

            $forwardFiles = 0;

            if ($forward)
            {
                $forwardFiles = $forward->message_id;
            }

            $files = MessageFiles::whereIn('message_id', [$id, $forwardFiles])->get();

            $blade = view('templates.files', compact('files', $files))->render();
        }
        elseif ($type == 'users')
        {
            $id = $request->input('id');

            $users = MessageUsers::where('message_id', $id)->get();

            $isRead = MessageUsers::where('message_id', $id)->where('is_readed', 1)->count();

            $blade = view('templates.sentUsers', compact('users', $users, 'isRead', $isRead))->render();
        }
        elseif ($type == 'forward')
        {
            $id = $request->input('id');

            $model = Message::find($id);

            $departments = Department::where('parent_id', 0)->where('status', 1)->orderBy('depart_id', 'ASC')->get();

            $blade = view('templates.forward', compact('model', 'departments'))->render();
        }
        elseif ($type == 'reply')
        {
            $id = $request->input('id');

            $model = Message::find($id);

            $blade = view('templates.reply', compact('model', $model))->render();
        }
        elseif ($type == 'senderMessages')
        {
            $id = $request->input('id');

            $model = Message::find($id);

            $models = MessageUsers::where('from_users_id', $model->user_id)
                ->where('to_users_id', Auth::id())
                ->where('is_deleted', 0)
                ->where('id', '!=', $id)
                ->orderBy('id', 'DESC')
                ->paginate(25);

            $blade = view('templates.senderMessages', compact('model', $model, 'models', $models))->render();
        }
        else {
            $blade = '';
        }

        return response()->json(array('success' => true, 'blade'=>$blade));

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

        return view('messages.view_control',compact('message','departments','message_files','to_users','inbox_count','sent_count','all_inbox_count'));
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

        return view('messages.view_my',compact('message','message_files','to_users','inbox_count','sent_count','all_inbox_count'));
    }

    public function fileDownload($id)
    {
        //
        $model = MessageFiles::findOrFail($id);

        $path = '/'.$model->file_path.$model->file_hash;

        if (Storage::disk('ftp_edo')->exists($path)){

            return Storage::disk('ftp_edo')->download($path, $model->file_name);
        }

        return back()->with('errors', 'Ilova (lar) Serverdan topilmadi!');
    }

    public function fileDownloadAll($message_id){
       
        $models = MessageFiles::where('message_id', $message_id)->get();
        if (empty($message_id)) {
            return back()->with('notFiles', 'Serverdan fayllar topilmadi!');
        }

        $ftp_server = '172.16.2.9';
        $ftp_user_name = 'ftp_9';
        $ftp_user_pass = 'ftp_9';

        // set up basic connection
        $conn_id = ftp_connect($ftp_server);

        // login with username and password
        $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
        
        // try to download $server_file and save to $local_file
        foreach ($models as $key => $model) {
            
            // define some variables
            $local_file = 'storage/'.$model->file_hash;
            $server_file = $model->file_path.$model->file_hash;
            $path = '/'.$model->file_path.$model->file_hash;
            
            if(Storage::disk('ftp_edo')->exists($path)) ftp_get($conn_id, $local_file, $server_file, FTP_BINARY); 
        }  

        // close the connection
        ftp_close($conn_id);

        $first_file_word    = explode(' ',trim($models[0]->file_name)); 
        $zip_download_name  = 'zip_all_' . $first_file_word[0].'_' .date('d-m-Y h-i-s').'.zip';
        $zip_name           = storage_path('app/public').'/'.$zip_download_name ;


        $zip = new ZipArchive;

        if ($zip->open($zip_name, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            foreach($models as $model){

                $zip->addFile(storage_path('app/public').'/'. $model->file_hash, $model->file_name);
            }
            
            $zip->close();

            // Delete temporary files from local
            foreach ($models as $key => $model) {
                unlink(storage_path('app/public').'/'. $model->file_hash);
            }
            return response()->download($zip_name, $zip_download_name )->deleteFileAfterSend(true);
    
        } else {

            return response()->json('Yuklashda xatolik');

        }
        
    }


    public function fileView($id)
    {
        $model = MessageFiles::findOrFail($id);

        $path = '/'.$model->file_path.$model->file_hash;

        if (Storage::disk('ftp_edo')->exists($path)){

            $res = Storage::disk('ftp_edo')->get($path);

            if (strtoupper($model->file_extension) == 'PDF'){

                return Response::make($res, 200, [
                    'Content-Type'        => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="'.$model->file_name.'"'
                ]);

            } elseif (strtoupper($model->file_extension) == 'PNG' || strtoupper($model->file_extension) == 'JPG' ||
                strtoupper($model->file_extension) == 'JPEG'){

                return Response::make($res, 200, [
                    'Content-Type'        => 'image/png',
                    'Content-Disposition' => 'inline; filename="'.$model->file_name.'"'
                ]);
            } else {

                return Storage::disk('ftp_edo')->download($path, $model->file_name);
            }
        }

        return response()->json('Ilova (lar) Serverdan topilmadi!');

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

    public function destroy(Request $request)
    {
        //
        $id = $request->input('id');

        $model = Message::find($id);

        // message files
        $messageFiles = MessageFiles::where('message_id', $id)->get();

        if (!empty($messageFiles)) {

            foreach ($messageFiles as $key => $value) {

                $path = '/'.$value->file_path.$value->file_hash;

                if (Storage::disk('ftp_edo')->exists($path)){

                    Storage::disk('ftp_edo')->delete($path);
                }

            }
            MessageFiles::where('message_id', $id)->delete();
        }

        // message users
        $messageUsers = MessageUsers::where('message_id', $id)->get();

        if (!empty($messageUsers)) {
            MessageUsers::where('message_id', $id)->delete();
        }

        // message forward
        $messageForward = MessageForward::where('message_id', $id)->get();

        if (!empty($messageForward)) {
            MessageForward::where('message_id', $id)->delete();
        }

        $model->delete();

        $blade = view('templates.success')->render();

        return response()->json(array(
                'success' => true,
                'blade'   => $blade,
                'message'   => 'Message Successfully deleted',
            )
        );

    }
}
