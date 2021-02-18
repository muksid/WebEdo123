<?php

namespace App\Http\Controllers;

use App\ChatMessage;
use App\User;
use App\Message;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function index()
    {
        // count() //
        @include('count_message.php');

        return view('home', compact('inbox_count','sent_count','term_inbox_count','all_inbox_count',
            'chatUnReadCount','allActiveUsers'));
    }

    public function index_old()
    {
        //
        $lastConversationUser = ChatMessage::where('is_deleted', 0)
            ->where('from_user_id', Auth::id())
            ->orderBy('created_at', 'DESC')->first();
        if (empty($lastConversationUser)) {
            $currentMessage = DB::table('chat_messages as a')
                ->join('users as u', 'a.from_user_id', '=', 'u.id')
                ->select('a.*', DB::raw("CONCAT('u.lname',' ','u.fname') AS full_name"), 'u.branch_code')
                ->whereIn('a.from_user_id', [Auth::id(), 0])
                ->whereIn('a.to_user_id', [Auth::id(), 0])
                ->where('a.is_deleted', '=', 0)
                ->orderby('a.created_at', 'ASC')
                ->get();

            $user = User::find(Auth::id());
        } else {
            $currentMessage = DB::table('chat_messages as a')
                ->join('users as u', 'a.from_user_id', '=', 'u.id')
                ->select('a.*', DB::raw("CONCAT('u.lname',' ','u.fname') AS full_name"), 'u.branch_code')
                ->whereIn('a.from_user_id', [Auth::id(), $lastConversationUser->to_user_id])
                ->whereIn('a.to_user_id', [Auth::id(), $lastConversationUser->to_user_id])
                ->where('a.is_deleted', '=', 0)
                ->orderby('a.created_at', 'ASC')
                ->get();

            $user = User::find($lastConversationUser->to_user_id);
        }

        $lastConversationUsersCount = DB::table('chat_messages')
            ->join('users', 'users.id', '=', 'chat_messages.to_user_id')
            ->join('departments as b', function ($join_user) {
                $join_user->on('users.branch_code', '=', 'b.branch_code')
                    ->where('b.parent_id', '=', 0);
            })
            ->select('users.*', 'to_user_id', DB::raw('count(*) as total'))
            ->where('from_user_id', Auth::id())
            ->where('users.status',1)
            ->groupBy('to_user_id')
            ->count();

        $currentUserInShow = Auth::id();

        if ($lastConversationUsersCount == 0){

            $lastConversationUsers = DB::select( DB::raw("SELECT 
                u.id, concat(u.lname,' ', u.fname) AS full_name, u.job_title, u.branch_code
                from users u
                where 1=1
                and u.status = 1
                ") );

        } else {
            $lastConversationUsers = DB::select( DB::raw("select * from (SELECT 
                u.id, concat(u.lname,' ', u.fname) AS full_name, u.job_title, u.branch_code,
                get_last_msg_time(u.id, '$currentUserInShow') as last_msg_time
                from users u
                ) b 
                WHERE b.last_msg_time IS NOT null
                order by b.last_msg_time desc;") );

        }

        /*$unReadMessages = DB::select(
            "SELECT CONCAT(b.lname,'',b.fname) AS full_name, a.from_user_id,b.branch_code,b.job_title,b.id, 
                COUNT(*) AS total_count FROM chat_messages a
                JOIN users b ON b.id = a.from_user_id
                WHERE a.to_user_id = '$currentUserInShow'
                and a.is_readed = 0
                GROUP BY a.from_user_id");*/

        $unReadMessages = DB::select(
            "SELECT CONCAT(b.lname,'',b.fname) AS full_name, a.from_user_id,b.branch_code,b.job_title,b.id, 
                COUNT(*) AS total_count FROM chat_messages a
                JOIN users b ON b.id = a.from_user_id
                WHERE a.to_user_id = '$currentUserInShow'
                and a.is_readed = 0
                GROUP BY a.from_user_id, b.lname, b.fname, b.branch_code,b.job_title,b.id");

        // count() //
        @include('count_message.php');

        return view('home',
            compact('index','currentMessage', 'user', 'lastConversationUsers', 'unReadMessages',
                'inbox_count','sent_count','term_inbox_count','all_inbox_count', 'chatUnReadCount','allActiveUsers'))
            ->with('i', (request()->input('page', 1) - 1) * 20);
    }

    public function  getMessage(){
        $message = Message::all();
        return response()->json($message);
    }

    public function storageLog()
    {
        // count() //
        @include('count_message.php');

        return view('storage',
            compact('inbox_count','sent_count','term_inbox_count','all_inbox_count'));
    }

}
