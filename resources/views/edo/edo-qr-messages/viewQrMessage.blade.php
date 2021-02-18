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
                                            @if($model->status == 2)

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
                        <!-- /.box-footer -->
                    </section>
                    <!-- /.content -->
                    <div class="clearfix"></div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

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
            </script>

        </section>

    </div>

@endsection

