@extends('layouts.edo.dashboard')

@section('content')

    <div class="content-header">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>@lang('blade.protocol_management')
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Bosh sahifa</a></li>
                <li class="active">@lang('blade.protocol_management')</li>
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

            @if(session('success'))
                <div class="box box-default">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-success">
                                    <h4 class="modal-title"> {{ session('success') }}</h4>
                                </div>
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
                    <div class="col-md-7">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">@lang('blade.protocol_management')</h3>

                                <div class="box-tools pull-right">
                                    <a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="Previous"><i class="fa fa-chevron-left"></i></a>
                                    <a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="Next"><i class="fa fa-chevron-right"></i></a>
                                </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body no-padding">
                                <div class="mailbox-read-info text-center">
                                    <h3>{{ $model->title }}</h3>
                                </div>
                                <!-- /.mailbox-controls -->
                            </div>

                            <!-- /.box-body -->
                            <div class="col-md-12" id="printProtocol">
                                <div class="row">
                                    <div class="mailbox-read-message">
                                        <?php echo $model->text??''; ?>
                                    </div><br>
                                    @if($guide)
                                    <div class="col-sm-12">

                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="description-block">
                                                    <h5 class="description-header text-left stf-vertical-middle">
                                                        @lang('blade.management_guide')
                                                    </h5>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                @if($guide->status == -1)
                                                    <div class="attachment-block clearfix stf-img-center stf-vertical-middle">
                                                        <span class="label label-danger"> Rad etildi</span>
                                                        <br>
                                                        <span><i class="text-muted">Izox:</i> {{ $guide->descr }}</span>
                                                    </div>
                                                @elseif($guide->status == 0)
                                                    <div class="attachment-block clearfix stf-img-center">
                                                        <img class="attachment-img stf-img-center-image" src="{{ url('/FilesQR/image_icon.png') }}">
                                                    </div>
                                                @elseif($guide->status == 2)
                                                    <div class="attachment-block clearfix stf-img-center">
                                                        <img class="attachment-img stf-img-center-image"
                                                             src="/FilesQR/{{$guide->managementMembers->qr_file??'' }}"
                                                             alt="{{$guide->user->lname}}">
                                                    </div>
                                                @elseif($guide->status == 3)
                                                    <div class="attachment-block clearfix stf-img-center">
                                                        <img class="attachment-img stf-img-center-image"
                                                             src="/FilesQR/{{$guide->managementMembers->qr_file??'' }}"
                                                             alt="{{$guide->user->lname}}">
                                                    </div>
                                                @else
                                                    <div class="attachment-block clearfix stf-img-center">
                                                        <img class="attachment-img stf-img-center-image" src="{{ url('/FilesQR/image_icon.png') }}">
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="description-block">
                                                    <h5 class="description-header text-right stf-vertical-middle">
                                                        _____________ {{ $guide->user->substrUserName($guide->user_id) }}
                                                    </h5>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                @if($model->status == 1 && $guide->user_id == \Illuminate\Support\Facades\Auth::id())
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
                                        </div>

                                    </div>

                                    @endif
                                </div>

                            </div>
                            <div class="clearfix"></div>
                            <div class="box-footer">
                                <div class="pull-right">
                                    <button type="button" id="guidePrint" class="btn btn-success"><i class="fa fa-print"></i> @lang('blade.print')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="box box-primary">
                            <!-- /.box-header -->
                            <div class="box-body" id="printMembers">
                                <h4>@lang('blade.management_members'):</h4>
                                <hr>

                                <div class="row">
                                    @foreach($model->viewMembers as $key => $value)

                                        <div class="col-sm-3">
                                            <div class="description-block">
                                                <h5 class="description-header text-left stf-vertical-middle">
                                                    {{ $value->user->substrUserName($value->user_id) }}
                                                </h5>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="description-block">
                                                <h5 class="description-header text-left stf-vertical-middle">
                                                    _____________
                                                </h5>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            @if($value->status == 1)
                                                <div class="attachment-block clearfix stf-img-center">
                                                    <img class="attachment-img stf-img-center-image"
                                                         src="{{ url('/FilesQR/image_icon.png') }}">
                                                </div>
                                            @elseif($value->status == -1)
                                                <div class="attachment-block clearfix stf-img-center">
                                                    <span class="label label-danger"> Rad etildi</span>
                                                    <br>
                                                    <span><i class="text-muted">Izox:</i> {{ $value->descr }}</span>
                                                    <hr>
                                                </div>
                                            @elseif($value->status == 0)
                                                <div class="attachment-block clearfix stf-img-center">
                                                    <img class="attachment-img stf-img-center-image"
                                                         src="{{ url('/FilesQR/image_icon.png') }}">
                                                </div>
                                            @else
                                                <div class="attachment-block clearfix stf-img-center">
                                                    <img class="attachment-img stf-img-center-image"
                                                         src="/FilesQR/{{$value->managementMembers->qr_file??'' }}"
                                                         alt="{{$value->user->lname}}">
                                                </div>

                                            @endif
                                            <hr>
                                        </div>
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
            <!-- /.row -->
            <link href="{{ asset('/admin-lte/dist/css/AdminLTE.min.css') }}" rel="stylesheet" type="text/css" media="screen, print">

            <link href="{{ asset("/admin-lte/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css" media="screen, print">


            <script type="text/javascript">
                document.getElementById("guidePrint").addEventListener("click", function() {
                    document.head.insertAdjacentHTML( 'beforeend', '<link rel="stylesheet" type="text/css" href="/admin-lte/dist/css/AdminLTE.min.css"/>' );
                    document.head.insertAdjacentHTML( 'beforeend', '<link rel="stylesheet" type="text/css" href="/admin-lte/bootstrap/css/bootstrap.css"/>' );

                    var printContents = document.getElementById('printProtocol').innerHTML;
                    var originalContents = document.body.innerHTML;
                    document.body.innerHTML = printContents;
                    window.print();
                    document.body.innerHTML = originalContents;
                });

                document.getElementById("memberPrint").addEventListener("click", function() {
                    document.head.insertAdjacentHTML( 'beforeend', '<link rel="stylesheet" type="text/css" href="/admin-lte/dist/css/AdminLTE.min.css"/>' );
                    document.head.insertAdjacentHTML( 'beforeend', '<link rel="stylesheet" type="text/css" href="/admin-lte/bootstrap/css/bootstrap.css"/>' );

                    var printContents = document.getElementById('printMembers').innerHTML;
                    var originalContents = document.body.innerHTML;
                    document.body.innerHTML = printContents;
                    window.print();
                    document.body.innerHTML = originalContents;
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
                //admin-lte/dist/css/AdminLTE.min.css
                $(document).ready(function () {

                    $(function () {
                        //Initialize Select2 Elements
                        $(".select2").select2();
                    });

                });
            </script>

            <script src="{{ asset("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

            <script src="{{ asset("/admin-lte/dist/js/app.min.js") }}"></script>
            <!-- Select2 -->
            <script src="{{ asset("/admin-lte/plugins/select2/select2.full.min.js") }}"></script>

        </section>
        <!-- /.content -->
    </div>


@endsection

