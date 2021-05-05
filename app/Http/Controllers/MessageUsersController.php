<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 30.12.2019
 * Time: 18:10
 */

namespace App\Http\Controllers;


use App\Department;
use App\Message;
use App\MessageUsers;
use App\User;
use Carbon\Carbon;
use File;
use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use PhpParser\Builder;
use Response;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;


class MessageUsersController extends Controller
{

    public function exportControl($id){
        //print_r($id); die;
        $exc = MessageUsers::where('id', '=', $id)->get();

        return Excel::download($exc, 'report_control.xls');
    }

    public function inbox(Request $request)
    {
        //
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

            $search = MessageUsers::select('message_users.*', 'm.subject', 'm.from_branch');

            $search ->join('messages as m', function ($j) {
                $j->on('message_users.message_id', '=', 'm.id');
            });

            if($u) {
                $search->where('message_users.from_users_id', '=', $u);
            }

            if($f) {
                $search ->where('m.from_branch', '=', $f);
            }

            if($t) {
                $search->where('m.subject', 'LIKE', '%' . $t . '%');
            }

            if($s_d) {
                $search->whereBetween('m.created_at', [$s_d.' 00:00:00',$e_d.' 23:59:59']);
            }

            $models = $search->where('message_users.to_users_id', Auth::id())
                ->where('message_users.is_readed', $r)
                ->where('message_users.is_deleted', 0)
                ->orderBy('message_users.id', 'DESC')
                ->paginate(25);

            $page = 'templates.searchInbox';

            if ($request->page){

                $page = 'message-users.inbox';
                if (count ( $models ) > 0)
                    return view ( $page,
                        compact('models','u','t','f','s_d','e_d','inbox_count','sent_count','all_inbox_count'));
            }

            return view($page,compact('models'));

        }
        else
        {
            $models = MessageUsers::where('to_users_id', Auth::id())
                ->where('is_readed', 0)
                ->where('is_deleted', 0)
                ->orderBy('id', 'DESC')
                ->paginate(25);

            return view('message-users.inbox',compact('models','inbox_count','sent_count','all_inbox_count'));

        }
    }

    public function allInbox(Request $request)
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

            $search = MessageUsers::select('message_users.*', 'm.subject', 'm.from_branch');

            $search ->join('messages as m', function ($j) {
                $j->on('message_users.message_id', '=', 'm.id');
            });

            if($u) {
                $search->where('message_users.from_users_id', '=', $u);
            }

            if($f) {
                $search ->where('m.from_branch', '=', $f);
            }

            if($t) {
                $search->where('m.subject', 'LIKE', '%' . $t . '%');
            }

            if($s_d) {
                $search->whereBetween('m.created_at', [$s_d.' 00:00:00',$e_d.' 23:59:59']);
            }

            $models = $search->where('message_users.to_users_id', Auth::id())
                ->where('message_users.is_readed', $r)
                ->where('message_users.is_deleted', 0)
                ->orderBy('message_users.id', 'DESC')
                ->paginate(25);

                $page = 'templates.searchInbox';

                if ($request->page){

                    $page = 'message-users.allInbox';
                    if (count ( $models ) > 0)
                        return view ( $page,
                            compact('models','u','t','f','s_d','e_d','inbox_count','sent_count','all_inbox_count'));
                }

                return view($page,compact('models'));

        }
        else
        {
            $models = MessageUsers::where('to_users_id', Auth::id())
                ->where('is_readed', 1)
                ->where('is_deleted', 0)
                ->orderBy('id', 'DESC')
                ->paginate(25);

            return view('message-users.allInbox',compact('models','inbox_count','sent_count','all_inbox_count'));

        }

    }

    public function getSentUsers(Request $request)
    {

        if ($request->ajax())
        {
            $id = $request->input('id');
            $output="";
            $models= MessageUsers::where('message_id', $id)->get();

            $isRead = MessageUsers::where('message_id', $id)->where('is_readed', 1)->count();

            $notRead = $models->count() - $isRead;

            if ($models)
            {
                foreach ($models as $key => $model) {
                    $key = $key + 1;
                    $output.='<span class="box-comment">
                        <span class="username">';
                            $output.='</span>';
                            if ($model->is_readed == 1){
                                $output.= '<b>'.$key.'. '.$model->toUsers->lname.' '.$model->toUsers->fname.'</b>';
                                $output.= '<i class="fa fa-check text-green"></i>';
                                $output.= '<span class="text-muted pull-right text-green"><i class="fa fa-clock-o"></i> ';
                                $output.= Carbon::parse($model->readed_date)->format('d M, y H:i').'</span>';
                            } else {
                                $output.= '<b>'.$key.'. '.$model->toUsers->lname.' '.$model->toUsers->fname.'</b>';
                                $output.='<i class="fa fa-check text-maroon"></i>';
                            }
                    $output.= '</span>';

                    $output.= '<i class="fa fa-bank text-primary"></i>';
                    $output.= '<span class="text-sm">';

                    $output.= $model->toUsers->filial->title.' - '.$model->toUsers->department->title;

                    $output.= ' <i>'.$model->toUsers->job_title.'</i>';
                    $output.= '</span><hr>';
                }

                return response()->json(['users'=> $output, 'isRead' =>$isRead, 'notRead' =>$notRead]);
            }
        }

    }

    public function getFilial(Request $request)
    {
        $user_type = $request->user_type;

        $filials = Department::where('parent_id', 0)->where('status', 1)->orderBy('depart_id', 'ASC')->get();

        if ($user_type == 'inbox'){

            $users =DB::table('message_users')
                ->join('users','message_users.from_users_id', '=', 'users.id')
                ->distinct('message_users.from_users_id')
                ->select('users.id', 'users.fname','users.lname')
                ->where('message_users.to_users_id', Auth::id())
                ->where('message_users.is_readed', 0)
                ->orderBy('message_users.created_at', 'ASC')
                ->get();
        } elseif ($user_type == 'sent'){

            $users =DB::table('message_users')
                ->join('users','message_users.to_users_id', '=', 'users.id')
                ->distinct('message_users.to_users_id')
                ->select('users.id', 'users.fname','users.lname')
                ->where('message_users.from_users_id', Auth::id())
                ->orderBy('message_users.created_at', 'ASC')
                ->get();

        } elseif ($user_type == 'allInbox'){

            $users =DB::table('message_users')
                ->join('users','message_users.from_users_id', '=', 'users.id')
                ->distinct('message_users.from_users_id')
                ->select('users.id', 'users.fname','users.lname')
                ->where('message_users.to_users_id', Auth::id())
                ->where('message_users.is_readed', 1)
                ->orderBy('message_users.created_at', 'ASC')
                ->get();

        } else {

            $users = MessageUsers::select('from_users_id')->distinct()
                ->where('to_users_id', Auth::id())
                ->where('is_readed', 1)
                ->where('is_deleted', 0)
                ->get();

        }

        $blade = view('templates.search_adv', compact('filials', 'users'))->render();

        return response()->json($blade);
    }

    public function deletedMessages()
    {
        $deleted = DB::table('message_users as a')
            ->join('messages as m', function ($join) {
                $join->on('a.message_id', '=', 'm.id')
                    ->orderBy('m.id', 'desc');
            })
            ->join('mes_types as mt', 'm.mes_type', '=', 'mt.message_type')
            ->join('users as u', 'a.from_users_id', '=', 'u.id')
            ->join('departments as d', 'u.depart_id', '=', 'd.id')
            ->join('departments as b', function ($join_branch) {
                $join_branch->on('u.branch_code', '=', 'b.branch_code')
                    ->where('b.parent_id', '=', 0);
            })
            ->select('m.*','a.id as mu_id','b.title as branch','d.title as dep_name','mt.title','a.message_id',
                'a.updated_at as deleted', 'u.lname','u.branch_code','u.fname','u.job_title')
            ->where('a.to_users_id', '=', Auth::id())
            ->where('a.is_deleted', '=', 1)
            ->orderby('a.updated_at', 'desc')
            ->get();

        // count() //
        @include('count_message.php');

        return view('message-users.deleted', compact('deleted', 'inbox_count', 'sent_count',  'all_inbox_count'));
    }

    public function destroy(Request $request)
    {
        //
        $id = $request->input('id');

        $messages = MessageUsers::whereIn('id', explode(",",$id))->get();

        foreach ($messages as $message){

            $message->update(['is_deleted' => 1]);

        }

        $blade = view('templates.success')->render();

        return response()->json(array(
                'success' => true,
                'blade'   => $blade,
                'message'   => 'Messages Successfully deleted',
            )
        );
    }

    public function destroyInbox(Request $request)
    {
        //
        $id = $request->input('id');

        $messages = MessageUsers::where('message_id', $id)->where('to_users_id', Auth::id())->get();

        foreach ($messages as $message){

            $message->update(['is_deleted' => 1]);

        }

        $blade = view('templates.success')->render();

        return response()->json(array(
                'success' => true,
                'blade'   => $blade,
                'message'   => 'Messages Successfully deleted',
            )
        );
    }

}
