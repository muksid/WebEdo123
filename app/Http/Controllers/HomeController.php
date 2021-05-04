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

        return view('home', compact('inbox_count','sent_count','all_inbox_count',
            'allActiveUsers'));
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
            compact('inbox_count','sent_count','all_inbox_count'));
    }

}
