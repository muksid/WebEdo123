@extends('layouts.edo.dashboard')

@section('content')

    <div class="content-header">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>@lang('blade.qr_documents')</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home')</a></li>
                <li class="active">@lang('blade.doc_view')</li>
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

            @if ($message = Session::get('success'))
                <div class="modal fade in" id="myModal" role="dialog" style="display: block">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header bg-aqua-active">
                                <h4 class="modal-title">
                                    @lang('blade.task') <i class="fa fa-check-circle"></i>
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
            @elseif($message = Session::get('confirmed'))

                <div class="modal fade in" id="myConfModal" role="dialog" style="display: block">
                    <div class="modal-dialog modal-sm">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header bg-aqua-active">
                                <h4 class="modal-title">
                                    @lang('blade.task') <i class="fa fa-check-circle"></i>
                                </h4>
                            </div>
                            <div class="modal-body">
                                <h5>{{ $message }}</h5>
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
                <div class="col-md-3">
                    <!-- Main content -->
                    <section class="invoice">
                        <div class="row">
                            <div class="col-xs-12 table-responsive">
                                <div class="box-body">
                                    <p class="text-muted"><i class="fa fa-bank margin-r-5"></i> @lang('blade.qr_sender')
                                    </p>
                                    <strong>@lang('blade.tb_wide')</strong>
                                    <hr>

                                    <p class="text-muted"><i
                                                class="fa fa-pencil margin-r-5"></i> @lang('blade.qr_signed_by')</p>
                                    <strong>{{ mb_strtoupper($model->guide->lname.' '.$model->guide->fname.' '.$model->guide->sname) }}</strong>
                                    <hr>

                                    <p class="text-muted"><i
                                                class="fa fa-user margin-r-5"></i> @lang('blade.qr_performer')</p>
                                    <strong>{{ mb_strtoupper($model->user->lname.' '.$model->user->fname.' '.$model->user->sname) }}</strong>
                                    <hr>

                                    <p class="text-muted"><i
                                                class="fa fa-calendar margin-r-5"></i> @lang('blade.qr_signed_date')</p>
                                    <strong>
                                        @if($model->status == 1)

                                            <i class="fa fa-calendar-times-o"></i>

                                        @else

                                            {{ \Carbon\Carbon::parse($model->signature_date)->format('d.m.Y')}}

                                        @endif
                                    </strong>
                                    <hr>

                                    <p class="text-muted"><i
                                                class="fa fa-sort-numeric-asc margin-r-5"></i> @lang('blade.qr_number_d')
                                    </p>
                                    <strong>{{ $model->message_hash }}</strong>
                                    <hr>

                                    <p class="text-muted"><i
                                                class="fa fa-paperclip margin-r-5"></i> @lang('blade.qr_files')</p>

                                    <div class="box-body">
                                        @foreach ($modelFiles as $key => $file)
                                            @php($key = $key+1)
                                            {{ $key++.'. '.$file->file_name }}

                                            <a href="{{ route('edo-qr-load',['file'=>$file->file_hash]) }}"
                                               class="pull-right">
                                                <i class="fa fa-cloud-download text-primary"></i> @lang('blade.download')
                                            </a>
                                            <br>
                                        @endforeach
                                        <hr>
                                    </div>
                                </div>
                                <!-- /.box-body -->

                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                    </section>
                    <!-- /.content -->
                    <div class="clearfix"></div>
                </div>
                <!-- /.col -->
                <div class="col-md-7">

                    <!-- Main content -->
                    <section class="invoice">
                        <div id="printQrMessage">
                            <div class="pull-right">
                                @lang('blade.qr_number_d1') {{ $model->message_hash }}
                            </div>
                            <!-- /.col -->

                            <div class="row">
                                <!-- accepted payments column -->
                                <div class="col-xs-12">
                                    <img src="{{ url('/../blank_hd.png') }}" alt="Turonbank" style="width: 100%">
                                </div>
                                <!-- /.col -->
                            </div>
                            <hr style="border-top: 2px solid #2998cb">
                            <!-- /.row -->
                            <div class="widget-user-header bg-blank-image">

                                <div class="row invoice-info">
                                    <div class="col-sm-6 invoice-col">
                                        <b>{{ \Carbon\Carbon::parse($model->message_date)->format('d.m.Y')}}</b>
                                        <b>№ {{ $model->message_number }}</b>
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->

                                <div class="row">
                                    <!-- accepted payments column -->
                                    <div class="col-xs-12">
                                        {!! $model->text !!}
                                    </div>
                                    <!-- /.col -->
                                </div><br>
                                <!-- /.row -->
                                <div class="row invoice-info qr-position">

                                    <div class="col-sm-4 invoice-col">
                                        <address>
                                            <strong>
                                                {{ $model->guide->job_title??'' }}
                                            </strong>
                                        </address>
                                    </div>

                                    <div class="col-sm-4 invoice-col">
                                        <address>
                                            @if($model->status == 1)
                                                <a href="javascript:;" data-toggle="modal" onclick="confUrl({{$model->id}})"
                                                   data-target="#ConfirmModal" class="btn btn-lg btn-reddit">
                                                    <i class="fa fa-check-circle"></i> @lang('blade.qr_sign')</a>
                                            @else

                                                {!! QrCode::size(120)->generate('https://online.turonbank.uz:3347/check/'.$model->id.'/'.$model->message_hash); !!}

                                            @endif
                                        </address>
                                    </div>

                                    <div class="col-sm-4 invoice-col">
                                        <address>
                                            <strong>
                                                {{ mb_substr($model->guide->fname??'', 0 ,1).'.'.
                                                   mb_substr($model->guide->sname??'', 0 ,1).'.'.
                                                   $model->guide->lname??'' }}
                                            </strong>
                                        </address>
                                    </div>

                                </div>
                                <!-- /.row -->
                                <div class="row invoice-info">
                                    <div class="col-sm-4 invoice-col pull-left">
                                        <b>@lang('blade.qr_executor')</b> {{ mb_substr($model->user->fname??'', 0 ,1).'.'.
                                           $model->user->lname??'' }}<br>
                                        <b>@lang('blade.qr_tel')</b> (71) 202-01-01<br>
                                        {{--<b>@lang('blade.qr_mobile')</b> (90) 186-40-89--}}
                                    </div>
                                    <!-- /.col -->
                                </div>
                            </div>
                        </div>

                        <div class="box-footer">
                            <div class="pull-right">
                                <a href="{{ route('pdf-qr-message',
                                            ['name' => $model->id,
                                            'hash' => $model->message_hash]) }}" class="btn btn-primary"><i
                                            class="fa fa-file-pdf-o"></i> @lang('blade.qr_download_pdf')</a>
                                <button type="button" id="printQrMessage" class="btn btn-success"
                                        onclick="printDiv('#printQrMessage');"><i
                                            class="fa fa-print"></i> @lang('blade.print')</button>

                            </div>
                        </div>
                    </section>
                    <!-- /.content -->
                    <div class="clearfix"></div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <div id="ConfirmModal" class="modal fade" role="dialog">
                <div class="modal-dialog modal-sm">
                    <!-- Modal content-->
                    <form action="" id="confirmForm" method="POST">
                        {{ csrf_field() }}
                        <div class="modal-content">
                            <div class="modal-header bg-aqua-active">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title text-center">
                                    <i class="fa fa-check-circle-o"></i> @lang('blade.qr_conf_sign')
                                </h4>
                            </div>
                            <div class="modal-body">
                                <p class="text-center">@lang('blade.qr_you_sign')</p>
                            </div>
                            <div class="modal-footer">
                                <center>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('blade.cancel')
                                    </button>
                                    <button type="submit" name="" class="btn btn-info" data-dismiss="modal"
                                            onclick="formSubmit()">@lang('blade.qr_i_sign')
                                    </button>
                                </center>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <script type="text/javascript">

                function printDiv(elem) {
                    renderMe($('<div/>').append($(elem).clone()).html());
                }

                function renderMe(data) {
                    var mywindow = window.open('', 'Qr Code Message', 'height=900,width=800,top=30,left=500');
                    mywindow.document.write('<html><head><title>Turonbank ATB</title>');
                    mywindow.document.write('<link rel="stylesheet" href="/admin-lte/dist/css/AdminLTE.min.css" type="text/css" />');
                    mywindow.document.write('<link rel="stylesheet" href="/admin-lte/dist/css/AdminLTE.css" type="text/css" />');
                    mywindow.document.write('<link rel="stylesheet" href="/admin-lte/bootstrap/css/bootstrap.css" type="text/css" />');

                    mywindow.document.write('</head><body >');
                    mywindow.document.write(data);
                    mywindow.document.write('</body></html>');

                    setTimeout(function () {
                        mywindow.print();
                        mywindow.close();
                    }, 1000);
                    return true;
                }

                // delete model
                function confUrl(id) {
                    var id = id;
                    var url = '{{ url("edo-rq-guide-confirm") }}/' + id;
                    url = url.replace(':id', id);
                    $("#confirmForm").attr('action', url);
                }

                function formSubmit() {
                    $("#confirmForm").submit();
                }

                // close Modal
                $('.closeModal').click(function () {

                    $('#ConfirmModal').hide();

                });


                // close Modal
                $('.closeModal').click(function () {

                    $('#myConfModal').hide();

                });

            </script>

        </section>
        <!-- /.content -->

    </div>


@endsection

