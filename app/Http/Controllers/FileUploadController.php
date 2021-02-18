<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 20.01.2020
 * Time: 15:30
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;

class FileUploadController extends Controller
{
    function index()
    {
        return view('file_upload');
    }

    function upload(Request $request)
    {
        $rules = array(
            'file.*'  => 'mimes:zip,rar,jpg,JPG,jpeg,JPEG,png,PNG,gif,pdf,PDF,doc,DOC,docx,DOCX,xls,XLS,xlsx,XLSX,ppt,PPT,pptx,PPTX,txt,TXT|max:131072'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $image = $request->file('file');

        $new_name = rand() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images'), $new_name);

        $output = array(
            'success' => 'Image uploaded successfully',
            'image'  => '<img src="/images/'.$new_name.'" class="img-thumbnail" />'
        );

        return response()->json($output);
    }
}

?>