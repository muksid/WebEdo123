<?php
namespace App\Http\Controllers;

use App\MessageFiles;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\User;
use App\MessageUsers;
use Illuminate\Support\Facades\DB;
use Response;


class AjaxController extends Controller {

    public function getDepartUsers(Request $request){

        $ajaxDepartId = $request->input('depart_id');

        $users = User::where('depart_id', '=', $ajaxDepartId)->where('status', '=', 1)->orderby('user_sort', 'ASC')->get();

        return response()->json(array('success' => true, 'msg' => $users, 'depart_id' => $ajaxDepartId));
    }

    public function deleteMultiple(Request $request){

        $id = $request->input('ids');

        $messages = MessageUsers::whereIn('id', explode(",",$id))->get();

        foreach ($messages as $message){

            $message->update(['is_deleted' => 1]);

        }

        return response()->json(['status'=>true,'msg'=>'Tanlangan xatlar muvaffaqiyatli o`chirildi']);
    }

    public function getGroupUsers(Request $request){

        $groupId = $request->input('group_id');
        $groupUsers = DB::table('group_users')
            ->join('users', 'group_users.users_id', '=', 'users.id')
            ->select('users.branch_code','users.lname','users.fname','users.job_title')
            ->where('group_users.group_id', '=', $groupId)
            ->get();

        return response()->json(array('success' => true, 'msg' => $groupUsers, 'groupId' => $groupId));
    }

    public function getSentUsers(Request $request){

        $message_id = $request->input('message_id');
        $users = DB::table('message_users as a')
            ->join('users', 'a.to_users_id', '=', 'users.id')
            ->join('departments as d', 'users.depart_id', '=', 'd.id')
            ->join('departments as b', function ($join_user) {
                $join_user->on('users.branch_code', '=', 'b.branch_code')
                    ->where('b.parent_id', '=', 0);
            })
            ->select('users.*','a.readed_date','b.title as branch_name','d.title as depart_name')
            ->where('a.message_id', '=', $message_id)
            ->get();

        return response()->json(array('success' => true, 'msg' => $users, 'message_id' => $message_id));
    }

    public function getFilesModal(Request $request){

        $message_id = $request->input('message_id');

        $files = MessageFiles::where('message_id', '=', $message_id)->get();

        return response()->json(array('success' => true, 'msg' => $files, 'message_id' => $message_id));
    }

    public function controlMessages(Request $request){

        $from = $request->input('f_date');

        $to = $request->input('t_date');

        $controlMessages = DB::table('messages as a')
            ->join('users as u', function ($join) {
                $join->on('u.id', '=', 'a.user_id');
            })

            ->select('a.*','a.id as mu_id','u.lname','u.fname')
            ->where('a.is_deleted', '=', 0)
            ->where('a.mes_type', '=', 'control')
            ->where('u.roles', '=', '["office"]')
            ->whereBetween('a.created_at', [$from, $to])
            ->orderBy('a.created_at', 'asc')
            ->get();

        return response()->json(array('success' => true, 'msg' => $controlMessages, 'from_date' => $from, 'to_date' => $to));
    }

    public function testApi(Request $request){

        //$id = $request->all();

        //$name = $request->input('name');

        $data = User::select('id','username','created_at')->where('id', 199)->get();

        return response()->json($data);
    }
}