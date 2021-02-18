<?php

namespace App\Http\Controllers;

use App\Account;
use App\AccountFiles;
use App\AccType;
use App\AppAccount;
use App\AppRepAccount;
use App\AppRepAccountFiles;
use App\Department;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Hash;
use Response;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function bankUserIndex()
    {
        //
        //$branch = Department::find()
        $models = AppAccount::orderby('id', 'DESC')->get();

        // count() //
        @include('count_message.php');
        return view('account.bankUserIndex', compact('models','inbox_count','sent_count','term_inbox_count','all_inbox_count'));
    }

    public function index()
    {
        //
        $filial = Department::where('parent_id', 0)->where('id', '!=', 1)->get();

        $accType = AccType::all();

        // count() //
        @include('count_message.php');
        return view('account.index', compact('filial', 'accType'));
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
        $request1 = $request->all();
        //print_r($request1); die;
        $this->validate($request, [
            'filial_code' => 'required',
            'acc_type' => 'required',
            'acc_name' => 'required',
            'acc_inn' => 'required|min:9',
            'owner_fname' => 'required|min:3|max:25',
            'owner_lname' => 'required|min:3|max:25',
            'acc_address' => 'required|min:5',
            'type_activity' => 'required',
            'owner_phone' => 'required'
        ]);

        // model Message
        $model = new Account();

        $model->filial_code = $request->input('filial_code');

        $model->acc_type = $request->input('acc_type');

        $model->acc_name = $request->input('acc_name');

        $model->acc_inn = str_replace('-', '', $request->input('acc_inn'));

        $model->owner_fname = $request->input('owner_fname');

        $model->owner_lname = $request->input('owner_lname');

        $model->owner_sname = $request->input('owner_sname');

        $model->acc_address = $request->input('acc_address');

        $model->type_activity = $request->input('type_activity');

        $model->owner_phone = str_replace('-', '', $request->input('owner_phone'));

        $model->acc_gen = sha1($model->acc_inn).uniqid().date('dmYHis');

        $model->save();

        // model AppAccount
        $modelApp = new AppAccount();

        $modelApp->account_id = $model->id;

        $modelApp->account_code = rand(0000000,9999999);

        $modelApp->account_inn = $model->acc_inn;

        $modelApp->status = 1;

        $modelApp->save();

        // model File
        if ($request->file('account_file') != null) {

            foreach ($request->file('account_file') as $file) {
                if ($file != 0) {

                    $modelFile = new AccountFiles();

                    $modelFile->account_id = $model->id;

                    $modelFile->file_hash = $model->id .  '_' . date('mdYHis') . uniqid() . '.' . $file->getClientOriginalExtension();

                    $modelFile->file_size = $file->getSize();

                    $file->move(public_path() . '/AccFiles/', $modelFile->file_hash);

                    $modelFile->file_name = $file->getClientOriginalName();

                    $modelFile->file_extension = $file->getClientOriginalExtension();

                    $modelFile->save();

                }

            }
        }

        return back()->with('success', 'Ok '.$modelApp->account_code.' test');
    }


    // get Account
    public function getAccId(Request $request)
    {
        //
        $id = $request->input('id');

        $inn = $request->input('inn');

        $account = DB::table('app_accounts as a')
            ->join('accounts as c', 'a.account_id', '=', 'c.id')
            ->select('c.acc_name','c.acc_gen','c.id')
            ->where('a.account_inn', $inn)
            ->where('a.account_code', $id)
            ->first();

        if (!empty($account)){
            $account_name = $account->acc_name;
            $account_hash = $account->acc_gen;
        } else {
            $account_name = 'account not fount';
            $account_hash = '#';
        }

        return response()->json(array('success' => true, 'name' => $account_name, 'hash' => $account_hash));
    }

    // reply Bank user
    public function replyAccPost(Request $request)
    {
        //
        $input = $request->all();

        $model = new AppRepAccount($input);

        $model->save();

        // model File
        if ($request->file('rep_account_file') != null) {

            foreach ($request->file('rep_account_file') as $file) {
                if ($file != 0) {

                    $modelFile = new AppRepAccountFiles();

                    $modelFile->rep_account_id = $model->id;

                    $modelFile->file_hash = $model->id .  '_' . date('mdYHis') . uniqid() . '.' . $file->getClientOriginalExtension();

                    $modelFile->file_size = $file->getSize();

                    $file->move(public_path() . '/AccFiles/', $modelFile->file_hash);

                    $modelFile->file_name = $file->getClientOriginalName();

                    $modelFile->file_extension = $file->getClientOriginalExtension();

                    $modelFile->save();

                }

            }
        }

        return redirect()->back()->with('success', 'Sizning xatingiz muvaffaqiyatli jo`natildi');
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
        $model = Account::where('acc_gen', $id)->first();

        if (empty($model)) {
            return redirect('/');
        }


        $reply = AppRepAccount::where('account_id', $model->id)->get();

        return view('account.show', compact('model', 'reply'));
    }

    public function showB($id)
    {
        //
        $model = Account::where('acc_gen', $id)->first();

        $reply = AppRepAccount::where('account_id', $model->id)->get();
        if (empty($model)) {
            return redirect('/');
        }
        // count() //
        @include('count_message.php');

        return view('account.showB', compact('model', 'reply','inbox_count','sent_count','term_inbox_count','all_inbox_count'));
    }

   // account file
    public function downloadFile($file){

        if (file_exists(public_path() . "/AccFiles/" . $file)) {

            $model = AccountFiles::where('file_hash', '=', $file)->first();

            return Response::download(public_path() . "/AccFiles/".$file,$model->file_name);

        } else {

            return back()->with('notFiles', 'Serverdan fayllar topilmadi!');
        }
    }

    // account rep file
    public function downloadRepFile($file){

        if (file_exists(public_path() . "/AccFiles/" . $file)) {

            $model = AppRepAccountFiles::where('file_hash', '=', $file)->first();

            return Response::download(public_path() . "/AccFiles/".$file,$model->file_name);

        } else {

            return back()->with('notFiles', 'Serverdan fayllar topilmadi!');
        }
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
