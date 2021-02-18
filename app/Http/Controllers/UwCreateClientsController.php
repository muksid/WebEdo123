<?php

namespace App\Http\Controllers;

use App\UnDistricts;
use App\UnRegions;
use App\UwClientComments;
use App\UwClientFiles;
use App\UwClientGuars;
use App\UwClients;
use App\UwLoanTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UwCreateClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        //
        $models = UwLoanTypes::where('short_code', 'M')->where('isActive', 1)->orderBy('id', 'DESC')->get();

        //$request->session()->forget('model');

        $loan_models = UwLoanTypes::get()->unique('credit_type');

        return view('uw/create-clients/.index',compact('models', 'loan_models'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return string
     */
    public function getLoanType(Request $request)
    {
        //
        if ($request->ajax())
        {
            $output="";
            $models = UwLoanTypes::where('credit_type', $request->credit_type)->where('isActive', 1)->get();
            if ($models)
            {
                $output .='<tr>'.
                    '<th>'.'ID'.'</th>'.
                    '<th>'.'Kredit nomi'.'</th>'.
                    '<th>'.'Foiz %'.'</th>'.
                    '<th>'.'Kredit Davri'.'</th>'.
                    '<th>'.'Imtiy davr'.'</th>'.
                    '<th>'.'Valyuta'.'</th>'.
                    '<tr>';
                foreach ($models as $key => $values) {
                    $key = $key+1;
                    $output .='<tr class="clickable-row tr-cursor" data-href="'.route("uw.create.step.one",["id" => $values->id]).'">'.
                        '<td>'.$key++.'</td>'.
                        '<td><a href="'.route("uw.create.step.one",["id" => $values->id]).'">'.$values->title.'</a></td>'.
                        '<td>'.$values->procent.' %</td>'.
                        '<td>'.$values->credit_duration.' oy</td>'.
                        '<td>'.$values->credit_exemtion.' oy</td>'.
                        '<td>'.$values->currency.'</td>'.
                        '</tr>';
                }
            }
            return $output;
        }

    }

    /**
     * Show the step One Form for creating a new product.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createStepOne(Request $request, $id)
    {
        $loan = UwLoanTypes::find($id);

        $model = $request->session()->get('model');

        $regions = UnRegions::orderBy('code', 'ASC')->get();

        $districts = UnDistricts::orderBy('code', 'ASC')->get();

        $blade = mb_strtolower($loan->short_code);

        return view('uw.create-clients.create-'.$blade.'-step-one',compact('model','regions', 'districts', 'loan'));
    }

    /**
     * Post Request to store step1 info in session
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateStepOne(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'family_name' => 'required',
            'patronymic' => 'required',
            'document_region' => 'required',
            'document_district' => 'required',
            'registration_region' => 'required',
            'registration_district' => 'required',
            'birth_date' => 'required',
            'gender' => 'required',
            'document_serial' => 'required',
            'document_number' => 'required',
            'document_date' => 'required',
            'pin' => 'required',
            'inn' => 'required',
            'is_inps' => 'required',
            'registration_address' => 'required',
            'live_number' => 'required',
            'phone' => 'required',
            'job_address' => 'required',
        ]);

        $loan_type = $request->input('loan_type');

        if(empty($request->session()->get('model'))){
            $model = new UwClients();
            $model->fill($validatedData);
            $request->session()->put('model', $model);
        }else{
            $model = $request->session()->get('model');
            $model->fill($validatedData);
            $request->session()->put('model', $model);
        }

        return redirect()->route('uw.create.step.two', ['id' => $loan_type]);
    }

    /**
     * Show the step One Form for creating a new product.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createStepTwo(Request $request, $id)
    {
        $loan = UwLoanTypes::find($id);

        $model = $request->session()->get('model');

        $blade = mb_strtolower($loan->short_code);

        return view('uw.create-clients.create-'.$blade.'-step-two',compact('model', 'id', 'loan'));
    }

    /**
     * Show the step One Form for creating a new product.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateStepTwo(Request $request)
    {
        $validatedData = $request->validate([
            'summa' => 'required',
        ]);

        $loan_type = $request->input('loan_type');

        $model = $request->session()->get('model');
        $model->fill($validatedData);
        $request->session()->put('model', $model);

        return redirect()->route('uw.create.step.three', ['id' => $loan_type]);
    }

    /**
     * Show the step One Form for creating a new product.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createStepThree(Request $request, $id)
    {
        $loan = UwLoanTypes::find($id);

        $model = $request->session()->get('model');

        $blade = mb_strtolower($loan->short_code);

        return view('uw.create-clients.create-'.$blade.'-step-three',compact('model', 'id', 'loan'));
    }

    /**
     * Show the step One Form for creating a new product.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateStepThree(Request $request)
    {

        $model = $request->session()->get('model');

        $inn = preg_replace('/[^A-Za-z0-9]/', '', $model->inn);
        $phone = preg_replace('/[^A-Za-z0-9]/', '', $model->phone);
        $str_summa = str_replace(',', '.', $model->summa);
        $summa = str_replace(' ', '', $str_summa);

        $branchCode = Auth::user()->branch_code;

        $lastModelId = UwClients::where('branch_code', '=', $branchCode)->latest()->first();

        $loanType = UwLoanTypes::find($request->loan_type);

        $claim_number = $lastModelId->claim_number + 1;
        if ($lastModelId->claim_number < 40000){
            $claim_number = 40000;
        }
        $claim_id = '1'.$branchCode.$claim_number;
        $model->user_id = Auth::id();
        $model->branch_code = $branchCode;
        $model->claim_id = $claim_id;
        $model->claim_date = today();
        $model->claim_number = $claim_number;
        $model->agreement_number = $claim_number;
        $model->agreement_date = today();
        $model->client_type = "08";
        $model->resident = 1;
        $model->document_type = 6;
        $model->nibbd = "";
        $model->live_address = $model->registration_address;
        $model->inn = $inn;
        $model->phone = $phone;
        $model->katm_sir = "";
        $model->loan_type_id = $loanType->id;
        $model->summa = $summa;
        $model->status = 1;

        $model->save();

        $request->session()->forget('model');

        return redirect()->route('uw.create.step.result', ['id' => $model->id])->with(
            [
                'status' => 'info',
                'message' => 'Mijoz muvaffaqiyatli tizimga qo`shildi',
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createStepResult($id)
    {
        //
        $model = UwClients::find($id);

        $blade = mb_strtolower($model->loanType->short_code);

        $modelComments = UwClientComments::where('uw_clients_id', $id)->get();

        $regions = UnRegions::where('status', 1)->get();

        $districts = UnDistricts::where('status', 1)->get();

        return view('uw.create-clients.create-'.$blade.'-step-result',compact('model', 'modelComments', 'regions', 'districts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getClientGuars($id)
    {
        //
        if(request()->ajax())
        {
            $model = UwClients::find($id);
            $disabled = '';
            if ($model->status == 2 || $model->status == 3){
                $disabled = 'btn disabled';
            }

            return datatables()->of(UwClientGuars::where('uw_clients_id', $id)->get())
                ->addColumn('action', function($data) use ($disabled) {
                    $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Edit" class="edit edit-post '.$disabled.'">
<span class="glyphicon glyphicon-pencil"></span></a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= ' | <a href="javascript:void(0);" id="delete-post" data-toggle="tooltip" data-original-title="Delete" data-id="'.$data->id.'" class="delete text-maroon  '.$disabled.'">
 <span class="glyphicon glyphicon-trash"></span></a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function createClientGuar(Request $request)
    {
        //
        $postId = $request->post_id;

        $post = UwClientGuars::updateOrCreate(['id' => $postId],
            [
                'uw_clients_id' => $request->model_id,
                'claim_id' => $request->claim_id,
                'guar_type' => $request->guar_type,
                'title' => $request->title,
                'address' => $request->address,
                'guar_owner' => $request->guar_owner,
                'guar_sum' => $request->guar_sum
            ]);

        return response()->json(
            [
                'success'=>'Post successfully added',
                'data'=> $post,
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function editClientGuar($id)
    {
        //
        $post  = UwClientGuars::find($id);

        return response()->json($post);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteClientGuar($id)
    {
        //
        $checkClient = UwClientGuars::find($id);

        if($checkClient){

            $checkClient->delete();

            return response()->json(
                [
                    'message'=>'Client deleted',
                    'code'=>200
                ]);
        }

        return response()->json(
            [
                'message'=>'Check Client not deleted!',
                'code'=>201
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getClientFiles($id)
    {
        //
        if(request()->ajax())
        {
            $model = UwClients::find($id);
            $disabled = '';
            if ($model->status == 2 || $model->status == 3){
                $disabled = 'btn disabled';
            }

            return datatables()->of(UwClientFiles::where('uw_client_id', $id)->get())
                ->addColumn('view', function($data){
                    $button  = '
                    <a href="'.route('file-load', ['file' => $data->id]).'" id="download-file" data-toggle="tooltip" data-original-title="Download" data-id="'.$data->id.'" class="text-primary">
 <span class="glyphicon glyphicon-download-alt"></span></a>';
                    return $button;
                })
                ->addColumn('trash', function($data) use($disabled){
                    $button1  = '
                    <a href="javascript:void(0);" id="delete-file" data-toggle="tooltip" data-original-title="Delete" data-id="'.$data->id.'" class="delete text-maroon  '.$disabled.'">
<span class="glyphicon glyphicon-trash"></span></a>';
                    return $button1;
                })
                ->rawColumns(['view', 'trash'])
                ->make(true);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function createClientFile(Request $request)
    {
        //

        $file = $request->file('model_file');

        $model_id = $request->model_file_id;

        $modelFile = new UwClientFiles();

        $modelFile->uw_client_id = $model_id;

        $modelFile->file_hash = $model_id . '_' . Auth::id() . '_' . date('dmYHis') . uniqid() . '.'
            . $file->getClientOriginalExtension();

        $modelFile->file_size = $file->getSize();

        $file->move(public_path() . '/uwFiles/', $modelFile->file_hash);

        $modelFile->file_name = $file->getClientOriginalName();

        $modelFile->file_extension = $file->getClientOriginalExtension();

        $modelFile->save();

        return response()->json(array(
                'success' => true,
                'message'   => 'File Successfully upload',
                'class_name'  => 'alert-success'
            )
        );

    }

    public function deleteClientFile($id)
    {
        //
        $model = UwClientFiles::find($id);
        $file_path = public_path().'/uwFiles/'.$model->file_hash;
        if(file_exists($file_path)){
            unlink($file_path);
        }

        $model->delete();

        return response()->json(array(
                'success' => true,
                'message' => 'File Successfully deleted'
            )
        );
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
