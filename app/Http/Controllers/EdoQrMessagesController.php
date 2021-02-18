<?php

namespace App\Http\Controllers;

use App\EdoQrMessageFiles;
use App\EdoQrMessages;
use App\EdoUserRoles;
use App\EdoUsers;
use SimpleSoftwareIO\QrCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Response;
use Barryvdh\DomPDF\Facade as PDF;
use setasign\Fpdi\Fpdi;



class EdoQrMessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $models = EdoQrMessages::where('performer_user_id', Auth::id())->latest()->get();

        return view('edo.edo-qr-messages.index',compact('models'));
    }

    public function guideIndex()
    {
        //

        $models = EdoQrMessages::where('guide_user_id', Auth::id())->latest()->get();

        return view('edo.edo-qr-messages.indexGuide',compact('models'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $role = EdoUserRoles::where('role_code', 'guide')->first();

        $guide = EdoUsers::where('role_id', $role->id)->get();

        return view('edo.edo-qr-messages.create',compact('guide'));
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
            'message_number' => 'required',
            'title' => 'required',
            'text' => 'required',
            'guide_user_id' => 'required'
        ]);

        $lastId = EdoQrMessages::latest()->first();

        if (empty($lastId)) {
            $qrNumber = 1;
        } else {
            $qrNumber = $lastId->id + 1;
        }

        // model Message
        $model = new EdoQrMessages();

        $model->message_date = $request->input('message_date');

        $model->message_number = $request->input('message_number');

        $model->title = $request->input('title');

        $model->text = $request->input('text');

        $model->message_hash = strtoupper(substr(sha1($qrNumber), -8));

        $model->performer_user_id = Auth::id();

        $model->guide_user_id = $request->input('guide_user_id');

        $model->status = 1;

        $model->save();

        // model File
        if ($request->file('message_file') != null) {

            foreach ($request->file('message_file') as $file) {
                if ($file != 0) {

                    $modelFile = new EdoQrMessageFiles();

                    $modelFile->edo_qr_message_id = $model->id;

                    $modelFile->file_hash = $model->id . '_' . Auth::id() . '_' . date('dmYHis') . uniqid() . '.' . $file->getClientOriginalExtension();

                    $modelFile->file_size = $file->getSize();

                    $file->move(public_path() . '/FilesEDO2/', $modelFile->file_hash);

                    $modelFile->file_name = $file->getClientOriginalName();

                    $modelFile->file_extension = $file->getClientOriginalExtension();

                    $modelFile->save();

                }

            }
        }

        return redirect()->route('edo-qr-message-index')->with('success', 'Vazifa muvaffaqiyatli saqlandi');

    }

    public function viewQrMessage($id, $hash)
    {
        //
        $model = EdoQrMessages::where('id', $id)->where('message_hash', $hash)->firstOrFail();

        $modelFiles = EdoQrMessageFiles::where('edo_qr_message_id', $model->id)->get();

        return view('edo.edo-qr-messages.viewQrMessage',compact('model', 'modelFiles'));

    }

    public function viewPdf($id, $hash)
    {
        //
        $model = EdoQrMessages::where('id', $id)->where('message_hash', $hash)->firstOrFail();

        $modelFiles = EdoQrMessageFiles::where('edo_qr_message_id', $model->id)->get();




            $pdf = new Fpdi();
    // add a page
            $pdf->AddPage();
    // set the source file
            $pdf->setSourceFile('demo.pdf');
    // import page 1
            $tplIdx = $pdf->importPage(1);
    // use the imported page and place it at position 10,10 with a width of 100 mm
            $pdf->useTemplate($tplIdx, 10, 10, 100);

    // now write some text above the imported page
            $pdf->SetFont('Helvetica');
            $pdf->SetTextColor(255, 0, 0);
            $pdf->SetXY(30, 30);
            $pdf->Write(0, 'This is just a simple text');

            $pdf->Output('I', 'generated.pdf');

        //return view('edo.edo-qr-messages.viewPdf',compact('model', 'modelFiles'));

    }

    public function viewGuideQrMessage($id, $hash)
    {
        //
        $model = EdoQrMessages::where('id', $id)->where('message_hash', $hash)->firstOrFail();

        $modelFiles = EdoQrMessageFiles::where('edo_qr_message_id', $model->id)->get();

        return view('edo.edo-qr-messages.viewGuideQrMessage',compact('model', 'modelFiles'));

    }

    public function generatePdf($id, $hash)
    {
        $model = EdoQrMessages::where('id', $id)->where('message_hash', $hash)->firstOrFail();

        $modelFiles = EdoQrMessageFiles::where('edo_qr_message_id', $model->id)->get();

        $qrCode = base64_encode(\QrCode::format('svg')
            ->size(120)->errorCorrection('H')
            ->generate('https://online.turonbank.uz:3347/check/'.$model->id.'/'.$model->message_hash));

        $pdf = PDF::loadView('edo.edo-qr-messages.pdfQrMessage',compact('model', 'modelFiles', 'qrCode'));

        return $pdf->download('Turonbank-Qr-'.$model->message_hash.'.pdf');

        /*$model = EdoQrMessages::where('id', $id)->where('message_hash', $hash)->firstOrFail();

        $modelFiles = EdoQrMessageFiles::where('edo_qr_message_id', $model->id)->get();

        $pdf = PDF::loadView('edo.edo-qr-messages.pdfQrMessage',compact('model', 'modelFiles'));

        return $pdf->stream();*/


        /*$qrcode = base64_encode(\QrCode::format('svg')->size(120)->errorCorrection('H')->generate('string'));

        $pdf = PDF::loadView('edo.edo-qr-messages.pdfQrMessage', compact('qrcode','model', 'modelFiles'));

        //print_r($qrcode); die;

        return $pdf->stream();*/

    }

    public function downloadFile($file)
    {

        if (file_exists(public_path() . "/FilesEDO2/" . $file)) {

            $orgName = EdoQrMessageFiles::where('file_hash', '=', $file)->firstOrFail();

            return Response::download(public_path() . "/FilesEDO2/".$file,$orgName->file_name);

        } else {

            return back()->with('notFiles', 'Serverdan fayllar topilmadi!');
        }
    }

    public function deleteFile($file)
    {
        //
        $model = EdoQrMessageFiles::find($file);

        $file_path = public_path().'/FilesEDO2/'.$model->file_hash;
        if(file_exists($file_path)){
            unlink($file_path);
        }

        $model->delete();

        return back()->with('success', 'Ilova muvaffaqiyatli o`chirildi');
    }

    public function guideConfirm($id)
    {
        //
        $model = EdoQrMessages::find($id);

        $model->update([
            'status' => 2,
            'signature_date' => Carbon::now()
        ]);

        return back()->with('confirmed', 'Xat muvaffaqiyatli imzolandi');
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
        $model = EdoQrMessages::where('message_hash', $id)->firstOrFail();

        $modelFiles = EdoQrMessageFiles::where('edo_qr_message_id', $model->id)->get();

        $role = EdoUserRoles::where('role_code', 'guide')->first();

        $guide = EdoUsers::where('role_id', $role->id)->get();

        return view('edo.edo-qr-messages.edit',compact('model', 'modelFiles', 'guide'));
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
        $request->validate([
            'message_number' => 'required',
            'title' => 'required',
            'text' => 'required',
            'guide_user_id' => 'required'
        ]);

        $data = $request->all();

        $model = EdoQrMessages::find($id);

        $model->update($data);

        // model File
        if ($request->file('message_file') != null) {

            foreach ($request->file('message_file') as $file) {
                if ($file != 0) {

                    $modelFile = new EdoQrMessageFiles();

                    $modelFile->edo_qr_message_id = $model->id;

                    $modelFile->file_hash = $model->id . '_' . Auth::id() . '_' . date('dmYHis') . uniqid() . '.' . $file->getClientOriginalExtension();

                    $modelFile->file_size = $file->getSize();

                    $file->move(public_path() . '/FilesEDO2/', $modelFile->file_hash);

                    $modelFile->file_name = $file->getClientOriginalName();

                    $modelFile->file_extension = $file->getClientOriginalExtension();

                    $modelFile->save();

                }

            }
        }

        return redirect()->route('edo-qr-message-index')->with('success', 'Vazifa muvaffaqiyatli yangilandi');
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
        $model = EdoQrMessages::find($id);

        $modelFile = EdoQrMessageFiles::where('edo_qr_message_id', $model->id)->get();
        if (count($modelFile))
        {
            foreach ($modelFile as $key => $value)
            {
                $file_path = public_path().'/FilesEDO2/'.$value->file_hash;
                if(file_exists($file_path)){
                    unlink($file_path);
                }

            }

            EdoQrMessageFiles::where('edo_qr_message_id', $model->id)->delete();

        }

        $model->delete();

        return back()->with('deleted', 'Qr Message muvaffaqiyatli o`chirildi');
    }
}
