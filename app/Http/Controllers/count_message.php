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
$inbox_count = MessageUsers::where('to_users_id', Auth::id())
    ->where('is_readed', 0)
    ->where('is_deleted', 0)
    ->count();

$all_inbox_count = MessageUsers::where('to_users_id', Auth::id())
    ->where('is_readed', 1)
    ->where('is_deleted', 0)
    ->count();

$sent_count = Message::where('user_id', Auth::id())
    ->where('is_deleted', 0)
    ->count();

$allActiveUsers = User::where('status', 1)->count();
