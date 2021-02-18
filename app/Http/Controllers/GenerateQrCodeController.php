<?php

namespace App\Http\Controllers;

use BaconQrCode\Encoder\QrCode;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;

class GenerateQrCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    public function viewMessageQR()
    {
        //


        return view('pdf_view');
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

    public function qCode()
    {

        //return \QrCode::size(300)->generate('Muksid4010123 3 12313123');

        return view('qrCode');

    }

    public function simpleQrCode()
    {

        QrCode::size(300)->generate('A basic example of QR code!');

    }

    public function colorQrCode()
    {

        /*return \QrCode::size(300)
            //->backgroundColor(255,55,0)
            //->format('png')
            ->generate('A simple example of QR code');*/

        \QrCode::size(500)
            ->format('png')
            ->generate('tertwetw twtwtw rw re rwrwrewrewer we rerwrewe we rewrewrwer', public_path('images/laravel.png'));
        return view('qrCode');

/*        \QrCode::size(500)
            ->format('png')
            ->generate('turonbank.uz:3347', public_path('images/qrcode1.png'));
        return view('qrCode');*/

    }

    public function imageQrCode()
    {

        $image = \QrCode::format('png')
            ->merge(public_path('images/qrcode1.png'), 0.5, true)
            ->size(500)->errorCorrection('H')
            ->generate('Muksid');
        return response($image)->header('Content-type','image/png');

    }

    public function printPDF()
    {
        // This  $data array will be passed to our PDF blade
        $data = [
            'title' => 'First PDF for Medium',
            'heading' => 'Hello from 99Points.info',
            'content' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard 
            dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. 
            It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.'
            ];

        $pdf = PDF::loadView('pdf_view', $data);
        return $pdf->download('medium.pdf');
    }


}
