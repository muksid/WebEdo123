<?php

namespace App\Http\Controllers;

use App\Department;
use App\IpNetworks;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class IpNetworksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $models = IpNetworks::where('filial_code', Auth::user()->branch_code)->get();

        // count() //
        @include('count_message.php');

        return view('ip-networks.index',compact('models','inbox_count','sent_count','term_inbox_count','all_inbox_count'));
    }
    public function test()
    {
        //
        //$models = IpNetworks::where('filial_code', Auth::user()->branch_code)->get();

        // count() //
        @include('count_message.php');

        return view('ip-networks.test',compact('inbox_count','sent_count','term_inbox_count','all_inbox_count'));
    }


    public function allIPNetworks()
    {
        //
        $q = Input::get ( 'q' );
        $f = Input::get ( 'f' );
        $s = Input::get ( 's' );

        $models = IpNetworks::orderBy('created_at', 'DESC')
            ->paginate(25);

        // count() //
        @include('count_message.php');

        $filials = Department::where('parent_id', 0)->where('status', 1)->get();


        if($q != '' or $f != '' or $s != '') {

            $models = IpNetworks::where('filial_code', 'LIKE', '%'.$f.'%')
                ->where('ip_first', 'LIKE', '%' . $q . '%')
                ->where('ip_net', 'LIKE', '%' . $s . '%')
                ->orderby('created_at', 'DESC')
                ->paginate(25);

            $pagination = $models->appends(array(
                'q' => Input::get('q'),
                'f' => Input::get('f'),
                's' => Input::get('s')
            ));
            if (count($models) > 0)
                return view('ip-networks.allIpNetworks',
                    compact('models', 'q', 'f', 's', 'filials', 'modelsCount', 'inbox_count', 'sent_count', 'term_inbox_count', 'all_inbox_count'))
                    ->withDetails($models)->withQuery($q);
        }


        return view('ip-networks.allIpNetworks',
            compact('models','filials','q','f','s','inbox_count','sent_count','term_inbox_count','all_inbox_count'))
            ->with('i', (request()->input('page', 1) - 1) * 25);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $users = User::where('branch_code', Auth::user()->branch_code)->get();

        // count() //
        @include('count_message.php');

        return view('ip-networks.create', compact('users','inbox_count','sent_count','term_inbox_count','all_inbox_count'));
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
        $this->validate($request, [
            'ip_first' => 'required',
            'ip_route' => 'required'
        ]);

        $model = $request->all();

        IpNetworks::create($model);

        return back()->with('success', 'IP muvaffaqiyatli yaratildi');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        if (Auth::user()->branch_code == '09011'){
            $model = IpNetworks::find($id);
        } else{
            $model = IpNetworks::where('id',$id)->where('filial_code', Auth::user()->branch_code)->first();

        }

        $users = User::where('branch_code', Auth::user()->branch_code)->get();

        // count() //
        @include('count_message.php');

        if (empty($model)) {
            return response()->view('errors.' . '404', [], 404);
        }

        return view('ip-networks.edit',compact('model', 'users','inbox_count','sent_count','term_inbox_count','all_inbox_count'));
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
        $this->validate($request, [
            'ip_first' => 'required',
            'ip_route' => 'required'
        ]);

        $reqModel = $request->all();

        $model = IpNetworks::find($id);


        $model->update($reqModel);

        return back()->with('success', 'IP muvaffaqiyatli yaratildi');
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
        $model = IpNetworks::find($id);

        $model->delete();

        return back()->with('success', 'ip deleted successfully');
    }
}
