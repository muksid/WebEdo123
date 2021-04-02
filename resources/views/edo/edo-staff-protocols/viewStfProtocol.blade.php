@extends('layouts.edo.dashboard')
@section('content')

    <div class="content-header">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @switch($model->protocol_type)
                    @case(3)
                        @lang('blade.protocol_management')
                    @break
                    @case(11)
                        @lang('blade.hr_orders')
                    @break
                    @case(20)
                        @lang('blade.kazna_protocols')
                    @break
                    @case(24)
                        @lang('blade.strategy_orders')
                    @break
                    @default
                    @break
                @endswitch
                * {{ $model->title }}
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home')</a></li>
                <li class="active">@lang('blade.hr_orders')</li>
            </ol>

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Xatolik!</strong> Errors.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        <!-- Message Succes -->
            @if ($message = Session::get('success'))
                <div class="modal fade in" id="myModal" role="dialog" style="display: block">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header bg-aqua-active">
                                <h4 class="modal-title">
                                    Boshqaruv qarori <i class="fa fa-qrcode"></i>
                                </h4>
                            </div>
                            <div class="modal-body">
                                <h3>{{ $message }}</h3>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-info closeModal" data-dismiss="modal">
                                    <i class="fa fa-check-circle"></i> Ok
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            @endif
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">

                <div class="col-md-7" id="printProtocol">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            @if($model->stf_number??'')
                             <h4 class="" style="margin: 1px">
                                {{ $model->stf_number??'' }}  <span style="margin-left: 1%">{{ date('d-m-Y', strtotime($model->stf_date)) }}</span>
                            </h4>
                             @endif
                            <!--
                            <div class="box-tools pull-right">
                                <a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="Orqaga">
                                    <i  class="fa fa-chevron-left"></i>
                                </a>
                                <a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="Keyingisi">
                                    <i class="fa fa-chevron-right"></i>
                                </a>
                            </div> -->
                        </div>

                        <div class="box-body">
                            
                            <div class="mailbox-read-message" style="padding: 0 11% !important;">
                                <?php echo $model->text ?? ''; ?>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="row">
                                <div class="description-block">

                                    @if($guide)
                                        <span class="description-header text-left stf-vertical-middle" style="padding-right:30px">
                                            {{ $guide->user->job_title??'' }}
                                        </span>
                                        @if(!empty($guide) && ($guide->status == 0 || $guide->status == 1))
                                            <img class="attachment-img stf-img-center-image" src="{{ url('/FilesQR/image_icon.png') }}" style="height: 65px; width:auto">
                                        @elseif($guide->status == -1)
                                            <span class="label label-danger"> Rad etildi</span>
                                            <br>
                                            <span><i class="text-muted">Izox:</i> {{ $guide->descr }}</span>
                                        @elseif($guide->status == 2)
                                        {!! QrCode::size(70)->generate('https://online.turonbank.uz:3347/acc/'.$guide->managementMembers->qr_name.
                                                '/'.$guide->managementMembers->qr_hash.'/'.$model->id.'/'.substr($model->protocol_hash, 0,4) ); !!}
                                        @endif
                                        <span class="description-header text-right stf-vertical-middle" style="padding-left:20px">
                                            _____________ {{ $guide->user->substrUserName($guide->user_id) }}
                                        </span> 
                                    @endif
                                </div>
                                   

                                @if(in_array('bank_apparat', json_decode(Auth::user()->roles)) && $model->status == 1)
                                    <div class="box-footer">
                                        <div class="pull-right">
                                            <button type="button" id="cancel-protocol" class="btn btn-flat btn-default" data-id="{{ $model->id }}" data-toggle="modal" data-target="#cancel-modal">
                                                <i class="fa fa-ban"></i> @lang('blade.cancel')
                                            </button>

                                            <button type="button" id="confirm-protocol" data-id="{{ $model->id }}" class="btn btn-flat btn-info">
                                                <i class="fa fa-check-circle-o"></i> @lang('blade.approve')
                                            </button>
                                        </div>
                                    </div>
                                @endif
                                @if(in_array('main_staff', json_decode(Auth::user()->roles)) && $model->status == 1)
                                    <div class="col-md-12">
                                            <div class="box-footer">
                                                <div class="pull-right">
                                                    <button type="button" id="cancel-protocol" class="btn btn-flat btn-default" data-id="{{ $model->id }}" data-toggle="modal" data-target="#cancel-modal">
                                                        <i class="fa fa-ban"></i> @lang('blade.cancel')
                                                    </button>

                                                    <button type="button" id="confirm-protocol" data-id="{{ $model->id }}" class="btn btn-flat btn-info">
                                                        <i class="fa fa-check-circle-o"></i> @lang('blade.approve')
                                                    </button>
                                                </div>
                                            </div>
                                    </div>
                                @endif
                                @if(in_array('kazna', json_decode(Auth::user()->roles)) && $model->status == 1)
                                    <div class="col-md-12">
                                            <div class="box-footer">
                                                <div class="pull-right">
                                                    <button type="button" id="cancel-protocol" class="btn btn-flat btn-default" data-id="{{ $model->id }}" data-toggle="modal" data-target="#cancel-modal">
                                                        <i class="fa fa-ban"></i> @lang('blade.cancel')
                                                    </button>

                                                    <button type="button" id="confirm-protocol" data-id="{{ $model->id }}" class="btn btn-flat btn-info">
                                                        <i class="fa fa-check-circle-o"></i> @lang('blade.approve')
                                                    </button>
                                                </div>
                                            </div>
                                    </div>
                                @endif
                                @if(in_array('strategy', json_decode(Auth::user()->roles)) && $model->status == 1)
                                    <div class="col-md-12">
                                            <div class="box-footer">
                                                <div class="pull-right">
                                                    <button type="button" id="cancel-protocol" class="btn btn-flat btn-default" data-id="{{ $model->id }}" data-toggle="modal" data-target="#cancel-modal">
                                                        <i class="fa fa-ban"></i> @lang('blade.cancel')
                                                    </button>

                                                    <button type="button" id="confirm-protocol" data-id="{{ $model->id }}" class="btn btn-flat btn-info">
                                                        <i class="fa fa-check-circle-o"></i> @lang('blade.approve')
                                                    </button>
                                                </div>
                                            </div>
                                    </div>
                                @endif

                            </div>

                        </div>
                        <div class="box-footer">
                            <div class="pull-right">
                                <button type="button" id="guidePrint" class="btn btn-success"><i class="fa fa-print"></i> @lang('blade.print')</button>
                            </div>
                        </div>

                    </div>
                    <div class="col-md" id="application">
                        <div class="box" style="padding: 5px">
                            <h4>Ilovalar:</h4>
                            <table class="table" style="max-width: 576px">
                                <tbody>

                                @if($model_files)
                                    @foreach($model_files as $key => $file)   

                                            <tr>
                                                <th scope="row">{{ $key+1 }}</th>
                                                <td>
                                                    @switch($file->file_extension)
                                                        @case('doc')                                                        
                                                        @case('docx')                                                        
                                                        @case('xls')                                                        
                                                        @case('xlsx')                                                        
                                                        @case('pptx')                                                        
                                                        <a href="{{ route('download-protocol-file', ['id' => $file->id]) }}" 
                                                            class="text-info text-bold"> 
                                                            <i class="fa fa-search-plus"></i> {{ $file->file_name }}
                                                        </a>
                                                        @break
                                                        @default
                                                        <a href="#" class="text-info text-bold previewSingleFile" data-id="{{ $file->id }}"> 
                                                            <i class="fa fa-search-plus"></i> {{ $file->file_name }} -  {{ $file->file_extension }}
                                                        </a>
                                                        @break
                                                    @endswitch

                                                </td>
                                                <td>
                                                    <a href="{{ route('download-protocol-file', ['id' => $file->id]) }}" class="text-bold"> 
                                                        @lang('blade.download') <i class="fa fa-download"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        
                                    @endforeach
                                @endif

                                </tbody>
                                
                            </table>

                        </div>
                    </div>

                </div>

                <div class="col-md-5">
                    <div class="box box-primary">
                        <div class="box-body" id="printMembers">
                            <h4>
                                @lang('blade.members'):
                            </h4>
                            <hr>
                            <div class="row justify-content-between">
                                @foreach($model->viewMembers as $key => $value)

                                    <div class="col-sm">
                                        <div class="description-block">
                                            @if($value->user_role == 3)
                                                <div class="col-sm">
                                                    <span class="text-bold">@lang('blade.prepared_by'): «{{ $value->user->department->title??'' }}»</span> 
                                                </div>
                                                <br>
                                            @endif
                                            @if($value->user_role == 4)
                                                <div class="col-sm">
                                                    <span class="text-bold">@lang('blade.suggested_protocol_member')</span> 
                                                </div>
                                                <br>
                                            @endif
                                            <span class="description-header text-left stf-vertical-middle" style="padding-right: 15%">
                                                {{ $value->user->substrUserName($value->user_id) }} _____________
                                            </span>

                                            @if($value->status == 1)
                                            <img class="attachment-img stf-img-center-image pull-right" src="{{ url('/FilesQR/image_icon.png') }}" style="height: 65px; width:auto">
                                            @if($value->user_id == \Illuminate\Support\Facades\Auth::id())

                                                <!-- <a href="{{ route('confirm-protocol', $value->id) }}" type="submit" class="btn btn-dropbox pull-right">
                                                    <i class="glyphicon glyphicon-ok"></i> @lang('blade.approve')
                                                </a>
                                                <button type="button" class="btn btn-warning pull-right" data-toggle="modal" data-target="#cancelModal">
                                                    <i class="fa fa-ban"></i> @lang('blade.cancel')
                                                </button> -->
                                            @endif
                                            @elseif($value->status == -1)
                                                <span class="label label-danger"> Rad etildi</span>
                                                <br>
                                                <span><i class="text-muted">Izox:</i> {{ $value->descr }} </span>
                                            @elseif($value->status == 0)
                                                <img class="attachment-img stf-img-center-image" src="{{ url('/FilesQR/image_icon.png') }}" style="height: 65px; width:auto">
                                            @else
                                            
                                                {!! QrCode::size(70)->generate('https://online.turonbank.uz:3347/acc/'.$value->managementMembers->qr_name.
                                                    '/'.$value->managementMembers->qr_hash.'/'.$model->id.'/'.substr($model->protocol_hash, 0,4) ); !!}
                                            @endif
                                        </div>
                                    </div>
                                    <hr style="margin-top: 5px; margin-bottom: 5px">

                                    <!-- /.col -->
                                @endforeach
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="pull-right">
                                <button type="button" id="memberPrint" class="btn btn-success"><i class="fa fa-print"></i> @lang('blade.print')</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{--Cancel modal--}}
            <div class="modal fade modal-warning" id="cancel-modal" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><i class="fa fa-check-circle-o"></i> @lang('blade.cancel')</h4>
                        </div>
                        <form id="cancelForm" name="cancelForm" action="{{ route('stf-main-cancel') }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <p class="text-center">@lang('blade.qr_cancel')</p>
                                <input type="text" name="protocol_id" id="protocol_id_to_cancel" hidden>
                                <label for="cancelText">@lang('blade.limit_100') <span class="text-red">*</span> </label>
                                <textarea type="text" class="text-muted" name="cancel_text" id="cancelText" maxlength="100"  rows="3" cols="35" required></textarea>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal"> @lang('blade.cancel') </button>
                                <button type="submit" class="btn btn-outline" id="" value=""> @lang('blade.qr_i_sign') </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{--Confirm modal--}}
            <div class="modal fade modal-info" id="confirm-modal" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title"><i class="fa fa-check-circle-o"></i> @lang('blade.confirm')</h4>
                        </div>
                        <form id="confirmForm" name="confirmForm">
                            <div class="modal-body">
                                <p class="text-center">@lang('blade.qr_you_sign')</p>
                                <p id="hr_id"></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">
                                    @lang('blade.cancel')
                                </button>
                                <button type="submit" class="btn btn-outline" id="btn-save" value="create">
                                    @lang('blade.qr_i_sign')
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{--Success modal--}}
            <div class="modal fade modal-success" id="success-modal" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">@lang('blade.hr_orders')</h4>
                        </div>
                        <div class="modal-body">
                            <p id="success-msg" class="text-center"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline pull-right"
                                    data-dismiss="modal">@lang('blade.close')</button>
                        </div>
                    </div>
                </div>
            </div>

            {{--Error modal--}}
            <div class="modal fade modal-danger" id="danger-modal" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title">@lang('blade.hr_orders')</h4>
                        </div>
                        <div class="modal-body">
                            <p id="danger-msg" class="text-center"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline pull-right" data-dismiss="modal">
                                @lang('blade.close')
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- jQuery 2.2.3 -->
            <script src="{{ asset ("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

            <script src="{{ asset ("/js/jquery.validate.js") }}"></script>

            <script type="text/javascript">
                document.getElementById("guidePrint").addEventListener("click", function() {
                    document.head.insertAdjacentHTML( 'beforeend', '<link rel="stylesheet" type="text/css" href="/admin-lte/dist/css/AdminLTE.min.css"/>' );
                    document.head.insertAdjacentHTML( 'beforeend', '<link rel="stylesheet" type="text/css" href="/admin-lte/bootstrap/css/bootstrap.css"/>' );
                    $("#printProtocol button").hide();
                    $("#application").hide();
                    $('.mailbox-read-message').css('padding', '0');

                    var printContents = document.getElementById('printProtocol').innerHTML;
                    var originalContents = document.body.innerHTML;
                    document.body.innerHTML = printContents;
                    window.print();
                    document.body.innerHTML = originalContents;
                    location.reload();
                });

                document.getElementById("memberPrint").addEventListener("click", function() {
                    document.head.insertAdjacentHTML( 'beforeend', '<link rel="stylesheet" type="text/css" href="/admin-lte/dist/css/AdminLTE.min.css"/>' );
                    document.head.insertAdjacentHTML( 'beforeend', '<link rel="stylesheet" type="text/css" href="/admin-lte/bootstrap/css/bootstrap.css"/>' );
                    $("#printMembers button").hide();

                    var printContents = document.getElementById('printMembers').innerHTML;
                    var originalContents = document.body.innerHTML;
                    document.body.innerHTML = printContents;
                    window.print();
                    document.body.innerHTML = originalContents;
                    location.reload();
                });

                function PrintDiv(id) {
                    var data=document.getElementById(id).innerHTML;
                    var myWindow = window.open('', 'my div', 'height=400,width=600');
                    myWindow.document.write('<html><head><title>Turonbank ATB</title>');
                    myWindow.document.write('</head><body style="width: 400px;">');
                    myWindow.document.write(data);
                    myWindow.document.write('</body></html>');
                    myWindow.document.close(); // necessary for IE >= 10

                    myWindow.onload=function(){ // necessary if the div contain images

                        myWindow.focus(); // necessary for IE >= 10
                        myWindow.print();
                        myWindow.close();
                    };
                }

                $(document).ready(function () {

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $('#create-new-post').click(function () {
                        $('#btn-save').val("create-post");
                        $('#postForm').trigger("reset");
                        $('#postCrudModal').html("Add New post");
                        $('#ajax-crud-modal').modal('show');
                    });

                    $('body').on('click', '#confirm-protocol', function () {

                        var hr_id = $(this).data('id');

                        var mData = "<input name='model_id' value='" + hr_id + "' hidden/>";

                        $('#hr_id').empty();

                        $('#hr_id').prepend(mData);

                        $('#confirm-modal').modal('show');

                    });

                    if ($("#confirmForm").length > 0) {

                        $("#confirmForm").validate({

                            submitHandler: function (form) {

                                $.ajax({

                                    data: $('#confirmForm').serialize(),

                                    url: "{{ route('stf-main-confirm') }}",

                                    type: "POST",

                                    dataType: 'json',

                                    success: function (data) {

                                        if(data.success){
                                            $('#success-msg').prepend(data.msg);
                                            $('#success-modal').modal('show');
                                        }else{
                                            $('#danger-msg').prepend(data.msg);
                                            $('#danger-modal').modal('show');
                                        }

                                        setTimeout(function () {
                                            window.location.href = "/edo/staff-protocols";
                                        }, 3000);

                                        $('#confirm-modal').modal('hide');

                                    },
                                    error: function (data) {
                                        console.log('Error:', data);
                                        $('#btn-save').html('Save Changes');
                                    }
                                });
                            }
                        })
                    }

                    $('#cancel-protocol').unbind().click(function(){
                        let id = $(this).data('id')
                        $('#protocol_id_to_cancel').val(id)
                        let i = $('#protocol_id_to_cancel').val(id)

                    })

                    // Preview File
                    $('.previewSingleFile').unbind().click(function(){

                        let id = $(this).data('id')

                        window.open('/edo/preview-protocol-file/' + id, 'modal', 'width=800,height=900,top=30,left=500')

                        return false
                    })

                });
            </script>

        </section>
        <!-- /.content -->
    </div>


@endsection

