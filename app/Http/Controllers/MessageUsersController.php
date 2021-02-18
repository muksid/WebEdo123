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

    public function inbox()
    {
        $inbox = DB::table('message_users as a')
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
            ->select('m.*','a.id as mu_id','b.title as branch','d.title as dep_name','mt.title','a.message_id','u.lname','u.branch_code','u.fname','u.job_title')
            ->where('a.to_users_id', '=', Auth::id())
            ->where('a.is_readed', '=', 0)
            ->where('a.is_deleted', '=', 0)
            ->orderby('a.created_at', 'desc')
            ->get();

        $models = MessageUsers::where('to_users_id', Auth::id())
            ->where('is_readed', 0)
            ->where('is_deleted', 0)
            ->orderBy('id', 'DESC')
            ->paginate(25);

        $users = MessageUsers::select('from_users_id')->distinct()
            ->where('to_users_id', Auth::id())
            ->where('is_readed', 0)
            ->where('is_deleted', 0)
            ->get();
        $u = '';
        $t = '';
        $f = '';

        // count() //
        @include('count_message.php');
        $filials = Department::where('parent_id', 0)->where('status', 1)->get();

        return view('message-users.inbox', compact('models', 'users','u','t','f','filials','inbox_count', 'sent_count', 'term_inbox_count', 'all_inbox_count'));
    }

    public function termInbox()
    {
        $term_inbox = DB::table('message_users as a')
            ->join('messages as m', 'a.message_id', '=', 'm.id')
            ->join('mes_types as t', 'm.mes_type', '=', 't.message_type')
            ->join('users as u', 'a.from_users_id', '=', 'u.id')
            ->join('departments as d', 'u.depart_id', '=', 'd.id')
            ->join('departments as b', function ($join_branch) {
                $join_branch->on('u.branch_code', '=', 'b.branch_code')
                    ->where('b.parent_id', '=', 0);
            })
            ->select('m.*','a.id as mu_id', 'b.title as branch', 'd.title as dep_name', 'a.is_readed', 't.title', 'a.message_id', 'u.lname', 'u.fname','u.job_title')
            ->where('a.to_users_id', '=', Auth::id())
            ->where('m.mes_term', '>', 0)
            ->where('a.is_deleted', '=', 0)
            ->orderBy('a.id', 'desc')
            ->get();

        // count() //
        @include('count_message.php');

        return view('message-users.termInbox',compact('term_inbox','inbox_count','sent_count','term_inbox_count','all_inbox_count'));
    }

    public function allInbox()
    {
        $models = MessageUsers::where('to_users_id', Auth::id())
            ->where('is_readed', 1)
            ->where('is_deleted', 0)
            ->orderBy('id', 'DESC')
            ->paginate(25);

        $users = MessageUsers::select('from_users_id')->distinct()
            ->where('to_users_id', Auth::id())
            ->where('is_readed', 1)
            ->where('is_deleted', 0)
            ->get();
        $u = '';
        $t = '';
        $f = '';

        // count() //
        @include('count_message.php');
        $filials = Department::where('parent_id', 0)->where('status', 1)->get();

        return view('message-users.allInbox',compact('models','users','u','t','f','filials','inbox_count','sent_count','term_inbox_count','all_inbox_count'));
    }

    // search
    public function search()
    {
        $models = MessageUsers::where('to_users_id', Auth::id())
            ->where('is_readed', 1)
            ->where('is_deleted', 0)
            ->orderBy('id', 'DESC')
            ->paginate(25);

        $u = Input::get ( 'u' );
        $t = Input::get ( 't' );
        $f = Input::get ( 'f' );
        $r = Input::get ( 'r' );

        // count() //
        @include('count_message.php');
        $filials = Department::where('parent_id', 0)->where('status', 1)->get();
        $users = MessageUsers::select('from_users_id')->distinct()
            ->where('to_users_id', Auth::id())
            ->where('is_readed', $r)
            ->where('is_deleted', 0)
            ->get();

        $page = 'message-users.allInbox';
        if ($r == 0)
            $page = 'message-users.inbox';


        if($u != '' || $t !='' || $f !='' || $r !=''){

            if ($u == ''){
                $models = MessageUsers::select('message_users.*','m.subject','m.from_branch')
                    ->join('messages as m', function ($j) use ($t, $f) {
                        $j->on('message_users.message_id', '=', 'm.id')
                            ->where('m.subject', 'LIKE', '%'.$t.'%')
                            ->where('m.from_branch', 'LIKE', '%'.$f.'%');
                    })
                    ->where('message_users.to_users_id', Auth::id())
                    ->where('message_users.is_readed', $r)
                    ->where('message_users.is_deleted', 0)
                    ->orderBy('message_users.id', 'DESC')
                    ->paginate(25);

            } else{
                $models = MessageUsers::select('message_users.*','m.subject','m.from_branch')
                    ->join('messages as m', function ($j) use ($t, $f) {
                        $j->on('message_users.message_id', '=', 'm.id')
                            ->where('m.subject', 'LIKE', '%'.$t.'%')
                            ->where('m.from_branch', 'LIKE', '%'.$f.'%');
                    })
                    ->where('message_users.to_users_id', Auth::id())
                    ->where('message_users.is_readed', $r)
                    ->where('message_users.is_deleted', 0)
                    ->where('message_users.from_users_id', $u)
                    ->orderBy('message_users.id', 'DESC')
                    ->paginate(25);
            }

            $pagination = $models->appends ( array (
                'u' => Input::get ( 'u' ),
                't' => Input::get ( 't' ),
                'f' => Input::get ( 'f' ),
                'r' => Input::get ( 'r' )
            ) );
            if (count ( $models ) > 0)
                return view ( $page,
                    compact('models','users','u','t','f','filials','inbox_count','sent_count','term_inbox_count','all_inbox_count'))
                    ->withDetails ( $models )->withQuery ( $u );
        }

        return view($page,compact('models','users','u','t','f','filials','inbox_count','sent_count','term_inbox_count','all_inbox_count'));


    }

    public function action4(Request $request) {

        $models = MessageUsers::where('to_users_id', Auth::id())
            ->where('is_readed', 1)
            ->where('is_deleted', 0)
            ->orderBy('id', 'DESC')
            ->get();

        $query = $request->get('query','');
        $products=Message::where('subject', 'LIKE', '%'.$query.'%')->get();
        $news=User::where('fname','LIKE','%'.$query.'%')->get();

        $searchResults = $news->merge($products);

        $data=array();
        foreach ($searchResults as $results) {
            $data[]=array('value'=>$results->fname,'subject'=>$results->subject,'id'=>$results->id);
        }
        if(count($data))
            return $data;
        else
            return ['value'=>'No Result Found','id'=>''];
    }


    function action(Request $request)
    {
        if($request->ajax())
        {
            $result = '';

            $query = $request->get('query','');

            $is_readed = $request->get('param','');

            $queryEmpty = $request->get('queryEmpty');
            if ($queryEmpty == '') {
                $models = MessageUsers::where('to_users_id', Auth::id())
                    ->where('is_deleted', 0)
                    ->where('is_readed', $is_readed)
                    ->orderBy('id', 'DESC')
                    ->paginate(25);
            }
            if($query != '')
            {
                $models = MessageUsers::whereHas('user', function ($query) use ($request, $is_readed) {
                    $query->where('to_users_id', Auth::id())
                        ->where('is_deleted', 0)
                        ->where('is_readed', $is_readed)
                        ->where('to_users_id', '=', Auth::id())
                        ->whereRaw("CONCAT(`lname`, ' ', `fname`) LIKE ?", ['%'.$request->get('query').'%']);
                    })
                    ->orWhereHas('message', function ($query) use ($request, $is_readed) {
                        $query->where('subject', 'LIKE', '%'.$request->get('query').'%')
                            ->where('is_deleted', 0)
                            ->where('is_readed', $is_readed)
                            ->where('to_users_id', '=', Auth::id());
                    })
                    ->orderBy('id', 'DESC')
                    ->paginate(25);
            }

            $total_model = MessageUsers::count();

            $total_row = $models->count();
            $route = 'view';
            if ($is_readed == 0){
                $route = 'messages.show';
            }

            if($total_row > 0)
            {
                foreach ($models as $key => $model) {
                    $key = $key+1;

                    if ($model->message->mes_term == 0) {
                        $model->message->mes_term = 'Muddat yo`q';
                    }
                    $result .= '
                        <tr>
                             <td>' . $key++ . '</td>
                             <td>' .$model->user->filial->title. '</td>
                             <td>
                                <a href="'.route($route,
                            ['mes_gen' => $model->message->mes_gen,
                                'id' => $model->message->id]).'">
                                    '.$model->user->lname .' '. $model->user->fname.'
                                </a>
                             </td>
                             <td style="font-size: 11px">'.wordwrap($model->user->department->title, 40, "<br />").'</td>
                             <td>'.\Illuminate\Support\Str::words($model->message->subject, 5, ' ...').'</td>
                             <td>'.$model->message->mes_term.'</td>
                             <td>'.$model->message->messageType->title.'</td>
                             <td class="text-sm">
                                 <button type="button" value="'.$model->message->id.'" class="btn btn-link get_files">
                                    <i class="fa fa-paperclip"></i>
                                 </button>
                             </td>
                             <td>
                                '.\Carbon\Carbon::parse($model->created_at)->format('d M,y H:i').'
                             </td>
                             <td>
                                <input type="checkbox" class="checkbox" data-id="'.$model->id.'">
                             </td>
                        </tr>
                        ';
                }
            }
            else
            {
                $result = '
                   <tr>
                    <td align="center" colspan="11">Xatlar topilmadi!</td>
                   </tr>
                   ';
            }
            $models = array(
                'table_data'  => $result,
                'total_model'  => $total_model,
                'total_data'  => $total_row
            );

            echo json_encode($models);
        }
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

        return view('message-users.deleted', compact('deleted', 'inbox_count', 'sent_count', 'term_inbox_count', 'all_inbox_count'));
    }

    public function ymNewm1()
    {
        $all_message = DB::table('message_users')
            ->join('messages', 'message_users.message_id', '=', 'messages.id')
            ->join('users', 'message_users.from_users_id', '=', 'users.id')
            ->select('users.*','message_users.created_at',  'message_users.message_id',
                'messages.subject', 'messages.mes_term', 'messages.mes_gen', 'users.lname',
                'users.fname', 'users.branch_code')
            ->orderBy('message_users.id', 'desc')
            ->paginate(100);

        // count() //
        $all_count = DB::table('message_users')
            ->join('messages', 'message_users.message_id', '=', 'messages.id')
            ->join('users', 'message_users.from_users_id', '=', 'users.id')
            ->select('users.*','message_users.created_at', 'message_users.message_id',
                'messages.subject', 'messages.mes_term', 'messages.mes_gen', 'users.lname',
                'users.fname', 'users.branch_code')
            ->orderBy('message_users.id', 'desc')
            ->count();
        @include('count_message.php');

        return view('message-users.ymNewm1', compact('all_message', 'all_count', 'inbox_count', 'sent_count', 'term_inbox_count', 'all_inbox_count'))
            ->with('i', (request()->input('page', 1) - 1) * 100);
    }

    public function destroy($id)
    {
        $message_users = MessageUsers::find($id);

        $message_users->update(['is_deleted' => 1]);

        //Group::find($id)->delete();
        //$group_users = GroupUsers::where('group_id', '=', $id);
        //$group_users->delete();

        return back()->with('success', 'Xat muvaffaqiyatli o`chirildi');
    }
}