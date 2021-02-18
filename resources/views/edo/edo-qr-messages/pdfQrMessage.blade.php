<html>
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Turonbank ATB - Qr Code Message</title>

    {{--<link rel="stylesheet" href="{{ public_path('/admin-lte/dist/css/AdminLTE.min.css') }}" type="text/css" />--}}

    <style>
        body {
            font-family: DejaVu Sans, sans-serif !important;
            padding: 0 10px 0 20px;
        }
        .qr_header {
            padding-bottom: 10px;
        }

        .qr_header img {
            width: 100%;
            border: none;
        }

        .qr_bg_image {
            background:url({{ public_path('blank_bg.png') }}) center center;
            background-repeat: no-repeat;
            background-origin: content-box;
            background-position: center;
            background-size: contain;
        }

        .qr_doc_number {
            text-align: right;
            font-size: 13px;
            font-weight: normal;
        }

        .table-responsive table tr{
            width: 100%;
        }

        .table-responsive table tr th{
            width: 30%;
            height: 200px;
            padding: 30px;
        }

    </style>
</head>
<body>

<section class="content">

    <div class="row">
        <!-- /.col -->
        <div class="col-md-12">

            <!-- Main content -->
            <section class="invoice">
                <div class="pull-right qr_doc_number">
                    <p>Хужжат коди: {{ $model->message_hash }}</p>
                </div>
                <!-- /.col -->

                <div class="row">
                    <!-- accepted payments column -->
                    <div class="col-xs-12 qr_header">
                        <img src="{{ public_path('blank_hd.png') }}" alt="Turonbank">
                        <hr style="width:100%;text-align:center;border: 1px solid  #2998cb">
                    </div>
                </div>
                <!-- /.row -->
                <div class="widget-user-header qr_bg_image">

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
                    </div>
                    <!-- /.row -->
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>{{ $model->guide->job_title }}</th>
                                <th>
                                    @if($model->status == 2)

                                        {{--<img src="data:image/png;base64,
                                            {!! base64_encode(
                                                \QrCode::format('png')->size(120)
                                                ->generate(
                                                'https://online.turonbank.uz:3347/check/'
                                                .$model->id.'/'
                                                .$model->message_hash))
                                            !!} ">--}}

                                        <img src="data:image/png;base64, {!! $qrCode !!}">

                                    @endif
                                    </th>
                                <th>{{ mb_substr($model->guide->fname??'', 0 ,1).'.'.
                                           mb_substr($model->guide->sname??'', 0 ,1).'.'.
                                           $model->guide->lname??'' }}</th>
                            </tr>
                        </table>
                    </div>

                    <!-- /.row -->
                    <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col pull-left">
                            <b>Ижрочи:</b> {{ mb_substr($model->user->fname??'', 0 ,1).'.'.
                                           $model->user->lname??'' }}<br>
                            <b>Тел:</b> (71) 202-01-01<br>
                            {{--<b>Сот:</b> (90) 186-40-89--}}
                        </div>
                        <!-- /.col -->
                    </div><br><br>
                </div>
                <!-- /.box-footer -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

</section>
</body>
</html>

