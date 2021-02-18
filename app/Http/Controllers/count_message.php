<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 20.01.2020
 * Time: 19:50
 */
use App\Message;
use App\MessageUsers;
use App\User;
use File;
use DB;
use Hash;
use Response;
use Illuminate\Support\Facades\Auth;

// count() //
$inbox_count = MessageUsers::where('to_users_id', '=', Auth::id())->where('is_readed', '=', 0)->where('is_deleted', '=', 0)->count();

$term_inbox_count = DB::table('message_users as a')
    ->join('messages as m', 'a.message_id', '=', 'm.id')
    ->where('a.to_users_id', '=', Auth::id())
    ->where('a.is_readed', '=', 1)
    ->where('a.is_deleted', '=', 0)
    ->where('m.mes_term', '>', 0)->count();

$all_inbox_count = MessageUsers::where('to_users_id', '=', Auth::id())->where('is_readed', '=', 1)->where('is_deleted', '=', 0)->count();

$sent_count = Message::where('user_id', '=', Auth::id())->where('is_deleted', '=', 0)->count();

$authUser = Auth::id();
$chatUnReadCount = DB::select("SELECT a.from_user_id, Count(*)
                            from chat_messages a
                            WHERE 1=1
                            AND a.to_user_id = '$authUser'
                            AND a.is_readed = 0
                            AND a.is_deleted = 0
                            group BY a.from_user_id");

$allActiveUsers = User::where('status', 1)->count();
