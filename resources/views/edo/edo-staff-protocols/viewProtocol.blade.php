@extends('layouts.edo.dashboard')

@section('content')

    <div class="content-header">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>View
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Bosh sahifa</a></li>
                <li class="active">User edit</li>
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
                    <div class="col-md-6">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Read Protocol</h3>

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
                                    <div class="col-sm-12">
                                        <table style="width:100%">
                                            <tr>
                                                <th style="width: 30%">
                                                    <div class="description-block">
                                                        <h5 class="description-header text-left">
                                                            @lang('blade.management_guide')
                                                        </h5>
                                                    </div>
                                                </th>
                                                <th style="width: 30%">
                                                    @if($guide->status == 1)
                                                        <div class="attachment-block clearfix">
                                                            <img class="attachment-img" src="/FilesQR/image_icon.png">
                                                        </div>
                                                    @elseif($guide->status == 0)
                                                        <div class="attachment-block clearfix">
                                                            <img class="attachment-img" src="/FilesQR/image_cancel.jpeg">
                                                            <label class="text-danger"> Boshqaruv qarori rad etildi</label><hr>
                                                            <span class="text-danger"> {{ $guide->descr }}</span>
                                                        </div>
                                                    @else
                                                        <img class="attachment-img" src="/FilesQR/{{$guide->managementMembers->qr_file??'' }}" alt="{{$guide->user->lname}}">


                                                    @endif
                                                </th>
                                                <th style="width: 30%">
                                                    <div class="description-block">
                                                        <h5 class="description-header text-right">
                                                            _____________ {{ mb_substr($guide->user->fname ?? '', 0,1).'.'
                                                            .mb_substr($guide->user->sname ?? '', 0,1).'.'
                                                            .mb_scrub($guide->user->lname ?? '') }}
                                                        </h5>
                                                    </div>
                                                </th>
                                            </tr>
                                        </table>
                                    </div>

                                    <div class="col-md-12">
                                        @if($guide->status == 1)
                                            <div class="attachment-block clearfix">
                                                @if($guide->user_id == \Illuminate\Support\Facades\Auth::id())

                                                    <a href="{{ route('confirm-protocol', $guide->id) }}" type="submit" class="btn btn-dropbox pull-right">
                                                        <i class="glyphicon glyphicon-ok"></i> @lang('blade.approve')
                                                    </a>
                                                    <button type="button" class="btn btn-warning pull-left"
                                                            data-toggle="modal" data-target="#cancelModal">
                                                        <i class="fa fa-ban"></i> @lang('blade.cancel')
                                                    </button>
                                                @endif
                                            </div>
                                        @endif

                                    </div>
                                    <!-- /.col -->
                                </div>

                            </div>
                            <div class="box-footer">
                            </div>
                            <!-- /.box-footer -->
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <div class="pull-right">
                                    <button type="button" id="guidePrint" class="btn btn-success"><i class="fa fa-print"></i> Print</button>
                                </div>
                            </div>
                            <!-- /.box-footer -->
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="box">
                            <!-- /.box-header -->
                            <div class="box-body" id="printMembers">
                                <h3>@lang('blade.management_members'):</h3>
                                <hr>

                                <div class="row">
                                        <div class="col-md-12">

                                            <table style="width:100%">

                                                @foreach($model->viewMembers as $key => $value)

                                                    {{--<div class="col-sm-4">
                                                        <div class="description-block">
                                                            <h5 class="description-header text-left">
                                                                {{ mb_substr($value->user->fname ?? '', 0,1).'.'
                                                                    .mb_substr($value->user->sname ?? '', 0,1).'.'
                                                                    .mb_scrub($value->user->lname ?? '') }} _____________
                                                            </h5>
                                                        </div>
                                                        <!-- /.description-block -->
                                                    </div>
                                                    <!-- /.col -->
                                                    <div class="col-sm-8">
                                                        @if($value->status == 3)
                                                            <img class="attachment-img" src="/FilesQR/{{$value->managementMembers->qr_file??'' }}" alt="{{$value->user->lname}}">
                                                        @else
                                                            <div class="attachment-block clearfix">
                                                                <img class="attachment-img" src="/FilesQR/image_icon.png" style="max-width: 100px">
                                                            </div>
                                                        @endif
                                                        <hr>
                                                    </div>
                                                    <!-- /.col -->--}}

                                                    <tr>
                                                        <th>
                                                            <div class="description-block">
                                                                <h5 class="description-header text-left">
                                                                    {{ mb_substr($value->user->fname ?? '', 0,1).'.'
                                                                        .mb_substr($value->user->sname ?? '', 0,1).'.'
                                                                        .mb_scrub($value->user->lname ?? '') }}
                                                                </h5>
                                                            </div>
                                                        </th>
                                                        <th>_____________</th>
                                                        <th>
                                                            @if($value->status == 3)
                                                                <img class="attachment-img" src="/FilesQR/{{$value->managementMembers->qr_file??'' }}" alt="{{$value->user->lname}}">
                                                            @else
                                                                <div class="attachment-block clearfix">
                                                                    <img class="attachment-img" src="/FilesQR/image_icon.png" style="max-width: 100px">
                                                                </div>
                                                            @endif
                                                        </th>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <div class="pull-right">
                                    <button type="button" id="memberPrint" class="btn btn-success"><i class="fa fa-print"></i> Print</button>
                                </div>
                            </div>
                            <!-- /.box-footer -->
                        </div>
                    </div>
            </div>
            <!-- /.row -->
            <link href="/admin-lte/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" media="screen, print">

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

