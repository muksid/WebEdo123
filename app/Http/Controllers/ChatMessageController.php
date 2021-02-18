<?php

namespace App\Http\Controllers;

use App\ChatMessage;
use App\Department;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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

        $unReadMessages = DB::select(
            "SELECT CONCAT(b.lname,' ',b.fname) AS full_name, a.from_user_id,b.branch_code,b.job_title,b.id, 
                COUNT(*) AS total_count FROM chat_messages a
                JOIN users b ON b.id = a.from_user_id
                WHERE a.to_user_id = '$currentUserInShow'
                and a.is_readed = 0
                GROUP BY a.from_user_id,b.lname,b.fname,a.from_user_id,b.branch_code,b.job_title,b.id ");

        // count() //
        @include('count_message.php');

        return view('chat.index',
            compact('currentMessage', 'user', 'lastConversationUsers', 'unReadMessages',
                'inbox_count','sent_count','term_inbox_count','all_inbox_count'));
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        //
        $currentMessage = DB::table('chat_messages as a')
            ->join('users as u', 'a.from_user_id', '=', 'u.id')
            ->select('a.*', 'u.fname', 'u.lname', 'u.branch_code')
            ->whereIn('a.from_user_id', [Auth::id(), $id])
            ->whereIn('a.to_user_id', [Auth::id(), $id])
            ->where('a.is_deleted', '=', 0)
            ->orderby('a.created_at', 'ASC')
            ->get();

        $visible = ChatMessage::where('from_user_id', $id)->where('to_user_id', Auth::id())->where('is_readed', 0)->get();

        foreach ($visible as $value) {
            $value->is_readed = 1;
            $value->save();
        }

        $user = \App\User::find($id);

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

            $lastConversationUsers = DB::select( DB::raw("select * from (SELECT 
                u.id, concat(u.lname,' ', u.fname) AS full_name, u.job_title, u.branch_code,
                get_last_msg_time(u.id, '$currentUserInShow') as last_msg_time
                from users u
                ) b 
                WHERE b.last_msg_time IS NOT null
                order by b.last_msg_time desc;") );

        } else {

            $lastConversationUsers = DB::select( DB::raw("select * from (SELECT 
                u.id, concat(u.lname,' ', u.fname) AS full_name, u.job_title, u.branch_code,
                get_last_msg_time(u.id, '$currentUserInShow') as last_msg_time
                from users u
                ) b 
                WHERE b.last_msg_time IS NOT null
                order by b.last_msg_time desc;") );

        }

        $unReadMessages = DB::select(
            "SELECT CONCAT(b.lname,'',b.fname) AS full_name, a.from_user_id,b.branch_code,b.job_title,b.id, 
                COUNT(*) AS total_count FROM chat_messages a
                JOIN users b ON b.id = a.from_user_id
                WHERE a.to_user_id = '$currentUserInShow'
                and a.is_readed = 0
                GROUP BY a.from_user_id");


        // count() //
        @include('count_message.php');

        return view('chat.show',
            compact('currentMessage','user', 'unReadMessages', 'lastConversationUsers',
                'inbox_count','sent_count','term_inbox_count','all_inbox_count'));
    }


    public function sendMessage(Request $request){

        $send = new ChatMessage();

        $send->from_user_id = $request->from_user_id;

        $send->message = $request->message;

        $send->to_user_id = $request->to_user_id;

        $send->is_readed = 0;

        $send->is_deleted = 0;

        $send->save();

        return response()->json($send);
    }

    public function  getMessage(Request $request){

        $current_user = $request->current_user;

        /*$message = DB::table('chat_messages as a')
            ->join('users as u', 'a.from_user_id', '=', 'u.id')
            ->select('a.*', 'u.fname', 'u.lname', 'u.branch_code')
            ->where('a.to_user_id', '=', Auth::id())
            ->orWhere('a.to_user_id', '=', $current_user)
            ->where('a.is_deleted', '=', 0)
            ->orderby('a.created_at', 'ASC')
            ->get();*/

        $message = DB::table('chat_messages as a')
            ->join('users as u', 'a.from_user_id', '=', 'u.id')
            ->select('a.*', 'u.fname', 'u.lname', 'u.branch_code')
            ->whereIn('a.from_user_id', [Auth::id(), $current_user])
            ->whereIn('a.to_user_id', [Auth::id(), $current_user])
            ->where('a.is_deleted', '=', 0)
            ->orderby('a.created_at', 'ASC')
            ->get();

        return response()->json($message);
    }

    public function getSearchUsers(Request $request)
    {
        //
        $user = $request->users;

        $search_user = User::where('users.lname', 'LIKE', '%'.$user.'%')
            ->join('departments as b', function ($join_user) {
                $join_user->on('users.branch_code', '=', 'b.branch_code')
                    ->where('b.parent_id', '=', 0);
            })
            ->select('users.*','b.title as branch')
            ->orWhere('users.fname', 'LIKE', '%'.$user.'%')
            ->orWhere('users.sname', 'LIKE', '%'.$user.'%')
            ->where('users.status', '=', 1)
            ->get();

        // count() //
        return response()->json($search_user);
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
