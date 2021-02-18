@extends('layouts.edo.dashboard')
@section('content')

    <div class="content-header">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>@lang('blade.hr_orders')
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
                                <button type="button" class="btn btn-info closeModal" data-dismiss="modal"><i
                                            class="fa fa-check-circle"></i> Ok
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
                <div class="col-md-1"></div>

                <div class="col-md-6" id="printProtocol">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <!-- <h3 class="box-title">@lang('blade.hr_orders')</h3>

                            <div class="box-tools pull-right">
                                <a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="Orqaga">
                                    <i  class="fa fa-chevron-left"></i>
                                </a>
                                <a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="Keyingisi">
                                    <i class="fa fa-chevron-right"></i>
                                </a>
                            </div> -->
                        </div>

                        <div class="box-body no-padding">
                            <div class="mailbox-read-info text-center">
                                <h3>{{ $model->title }}</h3>
                            </div>

                            <div class="mailbox-read-message">
                                <?php echo $model->text ?? ''; ?>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="row">
                                <div class="description-block">
                                    <?php 
                                        // dd($guide);
                                    ?>
                                    @if($guide)
                                        <span class="description-header text-left stf-vertical-middle" style="padding-right:30px">
                                            {{ $guide->user->job_title??'' }}
                                        </span>
                                        @if(!empty($guide) && ($guide->status == 0 || $guide->status == 1))
                                            <img class="attachment-img stf-img-center-image" src="{{ url('/FilesQR/image_icon.png') }}" style="height: 60px; width:auto">
                                        @elseif($guide->status == -1)
                                            <span class="label label-danger"> Rad etildi</span>
                                            <br>
                                            <span><i class="text-muted">Izox:</i> {{ $guide->descr }}</span>
                                        @elseif($guide->status == 2)
                                            <img class="attachment-img stf-img-center-image"
                                                src="/FilesQR/{{$guide->managementMembers->qr_file??'' }}"
                                                alt="{{$guide->user->lname}}" style="height: 60px; width:auto">
                                        @endif
                                        <span class="description-header text-right stf-vertical-middle" style="padding-left:30px">
                                            _____________ {{ $guide->user->substrUserName($guide->user_id) }}
                                        </span>
                                    @endif
                                </div>
                                   

                                @foreach(json_decode(Auth::user()->roles) as $user)
                                    @switch($user)

                                        @case('main_staff')
                                        <div class="col-md-12">
                                            @if($model->status == 1)
                                                <div class="box-footer">
                                                    <div class="pull-right">
                                                        <button type="button" id="cancel-protocol"
                                                                data-id="{{ $model->id }}" class="btn btn-flat btn-default">
                                                            <i class="fa fa-ban"></i> @lang('blade.cancel')
                                                        </button>

                                                        <button type="button" id="confirm-protocol"
                                                                data-id="{{ $model->id }}" class="btn btn-flat btn-info">
                                                            <i class="fa fa-check-circle-o"></i> @lang('blade.approve')
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        @break
                                    @endswitch
                                @endforeach

                            </div>

                        </div>
                        <div class="box-footer">
                            <div class="pull-right">
                                <button type="button" id="guidePrint" class="btn btn-success"><i class="fa fa-print"></i> @lang('blade.print')</button>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-body" id="printMembers">
                            <h4>@lang('blade.management_members'):</h4>
                            <hr>
                            <div class="row justify-content-between">
                                @foreach($model->viewMembers as $key => $value)

                                    <div class="col-sm">
                                        <div class="description-block">
                                            <span class="description-header text-left stf-vertical-middle" style="padding-right: 60px">
                                                {{ $value->user->substrUserName($value->user_id) }} _____________
                                            </span>

                                            @if($value->status == 1)
                                            <img class="attachment-img stf-img-center-image pull-right" src="{{ url('/FilesQR/image_icon.png') }}" style="height: 60px; width:auto">
                                            @if($value->user_id == \Illuminate\Support\Facades\Auth::id())

                                                <a href="{{ route('confirm-protocol', $value->id) }}" type="submit" class="btn btn-dropbox pull-right">
                                                    <i class="glyphicon glyphicon-ok"></i> @lang('blade.approve')
                                                </a>
                                                <button type="button" class="btn btn-warning pull-right" data-toggle="modal" data-target="#cancelModal">
                                                    <i class="fa fa-ban"></i> @lang('blade.cancel')
                                                </button>
                                            @endif
                                            @elseif($value->status == -1)
                                                <span class="label label-danger"> Rad etildi</span>
                                                <br>
                                                <span><i class="text-muted">Izox:</i> {{ $value->descr }}</span>
                                            @elseif($value->status == 0)
                                                <img class="attachment-img stf-img-center-image" src="{{ url('/FilesQR/image_icon.png') }}" style="height: 60px; width:auto">
                                            @else
                                                <img class="attachment-img stf-img-center-image" src="/FilesQR/{{$value->managementMembers->qr_file??'' }}" alt="{{$value->user->lname}}" style="height: 60px; width:auto">
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

            {{--Confirm modal--}}
            <div class="modal fade modal-info" id="confirm-modal" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><i class="fa fa-check-circle-o"></i> @lang('blade.confirm')</h4>
                        </div>
                        <form id="confirmForm" name="confirmForm">
                            <div class="modal-body">
                                <p class="text-center">@lang('blade.qr_you_sign')</p>
                                <p id="hr_id"></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline pull-left"
                                        data-dismiss="modal">@lang('blade.cancel')</button>
                                <button type="submit" class="btn btn-outline" id="btn-save"
                                        value="create">@lang('blade.qr_i_sign')
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
            <!-- jQuery 2.2.3 -->
            <script src="{{ asset ("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

            <script src="{{ asset ("/js/jquery.validate.js") }}"></script>

            <script type="text/javascript">
                document.getElementById("guidePrint").addEventListener("click", function() {
                    document.head.insertAdjacentHTML( 'beforeend', '<link rel="stylesheet" type="text/css" href="/admin-lte/dist/css/AdminLTE.min.css"/>' );
                    document.head.insertAdjacentHTML( 'beforeend', '<link rel="stylesheet" type="text/css" href="/admin-lte/bootstrap/css/bootstrap.css"/>' );
                    $("#printProtocol button").hide();

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

                                        $('#success-msg').prepend(data.msg);

                                        $('#success-modal').modal('show');

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

                });
            </script>

        </section>
        <!-- /.content -->
    </div>


@endsection

