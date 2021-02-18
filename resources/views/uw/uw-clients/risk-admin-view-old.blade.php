@extends('layouts.uw.dashboard')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            KATM
            <small>jadval</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home')</a></li>
            <li><a href="#">katm</a></li>
            <li class="active">online</li>
        </ol>
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Xatolik!</strong> xatolik bor.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box box-primary">
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="col-xs-6">
                        <form method="POST" action="{{ url('uw-online-reg-katm') }}" >
                            {{csrf_field()}}
                            <input type="hidden" name="id" value="{{ $model->id }}">
                            <input type="hidden" name="claim_id" value="{{ $model->claim_id }}">
                                <div class="box-body">
                                    <div class="box-header">
                                        <h3 class="box-title">ИНФОРМАЦИЯ IABS ({{ $model->iabs_num }})</h3>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body table-responsive no-padding">
                                        <table class="table table-hover">
                                            <tbody><tr>
                                                <th>#</th>
                                                <th>ТИП ИНФОРМАЦИИ</th>
                                                <th>ИНФОРМАЦИЯ</th>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>Наименование заёмщика</td>
                                                <td>{{ $model->family_name.' '.$model->name.' '.$model->patronymic }}</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Дата рождения</td>
                                                <td>{{ \Carbon\Carbon::parse($model->birth_date)->format('d.m.Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Данные документа</td>
                                                <td>{{ $model->document_serial.' '.$model->document_number.' от ' }}{{ \Carbon\Carbon::parse($model->document_date)->format('d.m.Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>ИНН</td>
                                                <td>{{ $model->inn }}</td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>ПИНФЛ</td>
                                                <td>{{ $model->pin }}</td>
                                            </tr>
                                            <tr>
                                                <td>6</td>
                                                <td>Пол</td>
                                                <td>
                                                    @if($model->gender == 1)
                                                        Erkak
                                                    @else
                                                        Ayol
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>7</td>
                                                <td>Адрес</td>
                                                <td>{{ $model->region->name??'' }}, {{ $model->district->name??'' }}</td>
                                            </tr>
                                            <tr>
                                                <td>8</td>
                                                <td>Адрес по прописке</td>
                                                <td>{{ $model->registration_address }}</td>
                                            </tr>
                                            <tr>
                                                <td>9</td>
                                                <td>Телефон</td>
                                                <td>{{ $model->phone }}</td>
                                            </tr>
                                            <tr>
                                                <td>10</td>
                                                <td>Кредитная заявка</td>
                                                <td>{{ $model->claim_id.' от ' }}{{ \Carbon\Carbon::parse($model->claim_date)->format('d.m.Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td>11</td>
                                                <td>Филиал БХО</td>
                                                <td>{{ $model->branch_code.' - '.$model->filial->title??'' }}</td>
                                            </tr>
                                            <tr>
                                                <td>12</td>
                                                <td>Филиал Инспектор</td>
                                                <td>{{ $model->user->lname??'' }} {{ $model->user->fname??'' }}</td>
                                            </tr>
                                            <tr>
                                                <td>13</td>
                                                <td>Кредит</td>
                                                <td>{{ number_format($model->summa, 2) }} so`m, {{ $model->procent }}%, {{ $model->credit_duration }} oy muddatga</td>
                                            </tr>
                                            <tr>
                                                <td>14</td>
                                                <td>Иш жойи</td>
                                                <td>{{ $model->job_address }}</td>
                                            </tr>
                                            <tr class="bg-danger">
                                                <td>15</td>
                                                <td>ИНПС</td>
                                                <td>
                                                    @if($model->is_inps == 1)
                                                        Tashkilot hodimi (INPS bor)
                                                    @elseif($model->is_inps == 2)
                                                        Organ hodimi (INPS yo`q)
                                                    @elseif($model->is_inps == 3)
                                                        Nafaqada (INPS yo`q)
                                                    @endif

                                                    @if($model->is_inps > 1)
                                                        <div class="box-header with-border">
                                                            @if($model->status == 2)
                                                                @switch(Auth::user()->uwUsers())
                                                                    @case('super_admin')
                                                                    @case('risk_adminstrator')
                                                                    <div class="col-md-4">
                                                                        <button type="button" class="btn btn-flat btn-success" id="confirmLoan"
                                                                                data-id="{{ $model->claim_id }}"><i class="fa fa-check-square"></i> Tasdiqlash
                                                                        </button>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <button type="button" class="btn btn-flat btn-warning" id="cancelLoan"
                                                                                data-id="{{ $model->claim_id }}"><i class="fa fa-pencil-square-o"></i> Qayta ko`rishga yuborish
                                                                        </button>
                                                                    </div>
                                                                    @break
                                                                @endswitch

                                                            @elseif($model->status == 3)

                                                                <div class="col-md-4">
                                                                    <button type="button" class="btn btn-flat btn-success disabled"><i class="fa fa-check"></i> Tasdiqlangan
                                                                    </button>
                                                                </div>
                                                            @elseif($model->status == 0)

                                                                <div class="col-md-12">
                                                                    <div class="callout callout-warning">
                                                                        <h4><i class="icon fa fa-pencil-square-o"></i> Taxrirlashda!</h4>
                                                                        <p>
                                                                            {{ $model->descr }}
                                                                        </p>
                                                                    </div>
                                                                </div>


                                                            @endif

                                                            <div class="col-md-4">
                                                                <button type="button" class="btn btn-flat btn-success" id="successLoan"><i class="fa fa-check"></i> Tasdiqlangan
                                                                </button>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <button type="button" class="btn btn-flat btn-warning" id="cancelLoan1"><i class="fa fa-ban"></i> Taxrirlashda
                                                                </button>
                                                            </div>
                                                        </div>
                                                        @endif
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <!-- /.box -->
                        </form>
                            <h3 class="widget-user-username">Mijoz Kredit xujjatlari</h3>
                            @foreach ($model->files as $file)

                                <div id="fileId_{{$file->id}}">

                                    <a href="{{ route('edoPreView',['preViewFile'=>$file->file_hash]) }}"
                                       class="text-info text-bold"
                                       target="_blank" class="mailbox-attachment-name"
                                       onclick="window.open('<?php echo('/uw/filePreView/' . $file->file_hash); ?>',
                                               'modal',
                                               'width=800,height=900,top=30,left=500');
                                               return false;"> <i class="fa fa-search-plus"></i> {{ $file->file_name }}</a>
                                    <ul class="list-inline pull-right">
                                        <li>
                                            <a href="{{ route('file-load',['file'=>$file->file_hash]) }}" class="link-black text-sm"><i
                                                        class="fa fa-cloud-download text-primary"></i> @lang('blade.download')</a>
                                        </li>
                                    </ul>
                                    <i class="text-red">({{ \App\Message::formatSizeUnits($file->file_size) }})</i><br><br>

                                </div>

                            @endforeach

                        </div>

                        <div class="col-xs-6">
                            <div class="box box-widget widget-user-2">
                                <!-- Add the bg color to the header using any of the bg-* classes -->
                                <div class="widget-user-header bg-aqua-active">
                                    <!-- /.widget-user-image -->
                                    <h3 class="widget-user-username">Mijoz Natijalari</h3>
                                    <h5 class="widget-user-desc">online</h5>
                                </div>
                            </div>
                            <div class="box-header with-border">
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-flat btn-primary" id="getResultKATM"
                                            data-id="{{ $model->claim_id }}"><i class="fa fa-search-plus"></i> KATM Skoring bali
                                    </button>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-flat btn-primary" id="getResultINPS"
                                            data-id="{{ $model->claim_id }}"><i class="fa fa-search-plus"></i> INPS oylik ish xaqi
                                    </button>
                                </div>
                            </div>
                            @if(!empty($summ_en))
                            <div class="box box-widget widget-user-2">
                                <div class="widget-user-header">
                                    <h3 class="widget-user-username">Kredit ajratish natijasi</h3>
                                </div>
                                <div class="box-footer no-padding">
                                    <ul class="nav nav-stacked">
                                        <li><a href="#">1. Kredit qarzdorligi:
                                                <span class="pull-right badge bg-red-active" style="font-size: large">
                                                    -{{ number_format($katm_sum, 2).' so`m' }}
                                                </span>
                                            </a>
                                        </li>
                                        <li><a href="#">2. Jami oylik ish xaqi:
                                                <span class="pull-right badge bg-light-blue">
                                                    {{ number_format($costs, 2).' so`m' }} ({{$costs_m.' oyda'}})
                                                </span>
                                            </a>
                                        </li>
                                        <li><a href="#">3. Xar oy to`lov qobiliyati:
                                                <span class="pull-right badge bg-danger" style="font-size: large">
                                                    {{ number_format($summ_en, 2).' so`m' }}
                                                </span>
                                            </a>
                                        </li>
                                        <li><a href="#">4. Mijoz so`ragan kredit:
                                                <span class="pull-right badge bg-gray-active" style="font-size: large">
                                                    {{ number_format($model->summa, 2).' so`m' }}
                                                </span>
                                            </a>
                                        </li>
                                        <li><a href="#">5. Kredit ajratish mumkin:
                                                @if($loan_summ_en >= 0)
                                                <span class="pull-right badge bg-aqua-active" style="font-size: large">
                                                    {{ number_format($loan_summ_en, 2).' so`m' }}
                                                </span>
                                                    @else

                                                    <span class="pull-right badge bg-aqua-active" style="font-size: large">
                                                        0 so`m <p class="text-danger text-sm">({{ number_format($loan_summ_en, 2).' so`m' }})</p>
                                                    </span>

                                                @endif
                                            </a>
                                        </li>

                                    </ul>
                                </div>

                                <div class="box-header with-border">
                                    @if($model->status == 2)
                                        @switch(Auth::user()->uwUsers())
                                            @case('super_admin')
                                            @case('risk_adminstrator')
                                            <div class="col-md-4">
                                                <button type="button" class="btn btn-flat btn-success" id="confirmLoan"
                                                        data-id="{{ $model->claim_id }}"><i class="fa fa-check-square"></i> Tasdiqlash
                                                </button>
                                            </div>
                                            <div class="col-md-4">
                                                <button type="button" class="btn btn-flat btn-warning" id="cancelLoan"
                                                        data-id="{{ $model->claim_id }}"><i class="fa fa-pencil-square-o"></i> Qayta ko`rishga yuborish
                                                </button>
                                            </div>
                                            @break
                                        @endswitch

                                    @elseif($model->status == 3)
                                        <div class="col-md-4">
                                            <div class="callout callout-success">
                                                <h4><i class="icon fa fa-pencil-square-o"></i> Tasdiqlangan</h4>
                                            </div>
                                        </div>
                                    @elseif($model->status == 0)

                                        <div class="col-md-12">
                                            <div class="callout callout-warning">
                                                <h4><i class="icon fa fa-pencil-square-o"></i> Taxrirlashda!</h4>
                                                <p>
                                                    {{ $model->descr }}
                                                </p>
                                            </div>
                                        </div>

                                    @endif

                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-flat btn-success" id="successLoan"><i class="fa fa-check"></i> Tasdiqlangan
                                        </button>
                                    </div>

                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-flat btn-warning" id="cancelLoan1"><i class="fa fa-ban"></i> Taxrirlashda
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!--inps result from datatable-->
                    <div class="modal fade" id="resultINPSModal" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-aqua-active">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title text-center" id="success_inps">INPS Result</h4>
                                </div>
                                <div class="modal-body">
                                    <h4 id="base64INPSSuccess_result" class="text-center">Mijoz:
                                        <b>{{ $model->family_name.' '.$model->name.' '.$model->patronymic }}</b>
                                    </h4>
                                    <div id="resultDataINPS"></div>
                                    <div id="resultDataINPSTotal" class="text-bold"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{--KATM scoring ball--}}
                    <div class="modal fade" id="resultKATMModal" aria-hidden="true">
                        <div class="modal-dialog modal-lg" style="width: 1100px">
                            <div class="modal-content">
                                <div class="modal-header bg-aqua-active">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title text-center" id="success">KATM Result</h4>
                                </div>
                                <div id="scoringPage"></div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline" data-dismiss="modal">@lang('blade.close')
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="resultKATMModalNull" aria-hidden="true">
                        <div class="modal-dialog modal-lg" style="width: 1100px">
                            <div class="modal-content">
                                <div class="modal-header bg-danger">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title text-center" id="success">KATM ma`lumotari topilmadi</h4>
                                </div>
                                <div id="ckoring_k" style=" margin: auto; width: 1000px; line-height: normal; background-color: #fff; padding: 10px;">
                                    <table style="width: 1000px;">
                                        <tbody>
                                        <tr>
                                            <td style="text-align: center; color:#02497f;">КРЕДИТНОЕ БЮРО <br>"КРЕДИТНО-ИНФОРМАЦИОННЫЙ <br>АНАЛИТИЧЕСКИЙ ЦЕНТР"</td>
                                            <td style="text-align: center;">
                                                <img src="data:image/jpeg;base64,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paJ4gsptO1C0kJCXVvMhjkjbBBwyMwOCDg1+EHx1/wCCU37TP/BL/wDaUvfGvwGtfFfifw5b/JpGuaFFFfaosU5YG1urBVZpWTaNziF4WHlyfI2Uj/fWkKg8EAj6V7OUZ3Xy9yVNKUZbxlqmeLnGRUMw5JVG4yhtKLs0fzt/Ef4I/tz/APBUb4geG/D/AMRfCnxAuo9Ikke1ufEPhpPDemaesrRrLK7+RAjkBVOAHlwG2KckV+03/BM39iWD9gL9kTw58P2urHUNahMuoa7f2kRjiv7+Zt0jrnDMqKI4UZgCUhQkKcivf9o6YGKK1zTiCrjKMcMoRhTi7qMVZXMMp4dpYKtLEynKpUkrc0nrYKKKK8E+hGzE7Dxmv5+f+CLv7Ffxj+D3/BS34aeJPFfwo+I/hzQNNbUjd6jqXhu9trW336XdxrvkeMKu52VRk8lgOpr+ggjPUZpCoPUA/hXr5ZnNXBUq1GnFNVVyu99FZrT7zxc0ySljqtGrUk06TurW11T1+4F6Z9aKXpwBgUV5CPZQUUUUDCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKAP/Z" width="220" height="90">
                                            </td>
                                            <td style="text-align: center; color:#02497f;">CREDIT BUREAU <br>"CREDIT-INFORMATION <br>ANALYTICAL CENTER"</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center;font-size: 16px;border-top: 2px solid #02497f;border-bottom: 2px solid #02497f;padding: 6px;" colspan="3">100027, Тоshkent shahar, Qoratosh ko'chasi, 1-uy | www.infokredit.uz | info@infokredit.uz | lotus manzil: katm | Tel: (95) 195-99-02, (71) 238-69-31</td>
                                        </tr>
                                        <tr style="text-align: center; color: #969696;">
                                            <td colspan="3" style=" padding: 10px; "> КРЕДИТНЫЙ ОТЧЕТ "СКОРИНГ КИАЦ" (№021)</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline" data-dismiss="modal">@lang('blade.close')
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--confirm form-->
                    <div class="modal fade modal-primary" id="modalFormCancel" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="modalHeader"></h4>
                                </div>
                                <div class="modal-body">
                                    <h4><span class="glyphicon glyphicon-comment"></span> <span id="modalBody"></span></h4>
                                </div>
                                <form id="cancelForm" name="cancelForm">
                                    <input type="hidden" name="uw_clients_id" id="uw_clients_id" value="{{ $model->id }}">
                                    <div class="box-body bg-primary">
                                        <input name="descr" class="form-control input-lg" type="text" placeholder="Izoh..." required>
                                    </div>
                                    <div class="modal-footer">
                                        <center>
                                            <button type="submit" class="btn btn-outline" id="btn-save-cancel"
                                                    value="create">Ha, Tasdiqlash
                                            </button>
                                            <button type="button" class="btn btn-outline pull-left"
                                                    data-dismiss="modal">@lang('blade.cancel')</button>
                                        </center>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>

                    <!--confirm form-->
                    <div class="modal fade modal-primary" id="modalFormConfirm" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title"><i class="fa fa-check-circle-o"></i>  <span id="modalHeaderConf"></span></h4>
                                </div>
                                <div class="modal-body">
                                    <h4><span class="glyphicon glyphicon-info-sign"></span> <span id="modalBodyConf"></span></h4>
                                </div>
                                <form id="confirmForm" name="confirmForm">
                                    <input type="hidden" name="uw_clients_id" id="uw_clients_id" value="{{ $model->id }}">
                                    <div class="modal-footer">
                                        <center>
                                            <button type="submit" class="btn btn-outline" id="btn-save-confirm"
                                                    value="create">Ha, Tasdiqlash
                                            </button>
                                            <button type="button" class="btn btn-outline pull-left"
                                                    data-dismiss="modal">@lang('blade.cancel')</button>
                                        </center>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>

                    <!--end success modal-->
                    <div class="modal fade modal-success" id="modalEndSuccess" tabindex="-1" role="dialog"
                         aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-header bg-aqua-active">
                                    <h4 class="modal-title" id="success_header"></h4>
                                </div>
                                <div class="modal-body">
                                    <h5 id="success_result"></h5>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline" data-dismiss="modal">@lang('blade.close')
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <style type="text/css">
            table{ border-collapse:collapse; width:100%; }
            table td, th{ border:1px solid #d0d0d0; }
        </style>
        <style type="text/css">
            #main_block table {font-size:9px;}
            .header-color {background-color:#8EB2E2;}
            .mip-color {background-color:#e3fbf7;}
            .procent25_class {width:25%;}
        </style>
        <script src="{{ asset ("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

        <script src="{{ asset ("/js/jquery.validate.js") }}"></script>

        <script>

            // crud form
            $(document).ready(function () {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $('body').on('click', '#getResultKATM', function () {

                    var cid = $('#getResultKATM').data('id');

                    $.get('/uw/get-client-katm/' + cid, function (data) {
                        console.log(data);

                        $('#scoringPage').empty();
                        $("#scoringPage").prepend(data.scoring_page);

                        $(".client_name").html(data.client.family_name+' '+data.client.name+' '+data.client.patronymic);

                        var formattedDate = new Date(data.client.birth_date);
                        var d = formattedDate.getDate();
                        var m =  formattedDate.getMonth();
                        m += 1;  // JavaScript months are 0-11
                        if (d < 10) {
                            d = "0" + d;
                        }
                        if (m < 10) {
                            m = "0" + m;
                        }
                        var y = formattedDate.getFullYear();
                        $(".client_birth_date").html(d + "." + m + "." + y);
                        if (data.client.gender == 1){
                            var gender = 'Erkak';
                        } else {
                            gender = 'Ayol';
                        }
                        $(".client_gender").html(gender);
                        $(".client_live_address").html(data.client.live_address);
                        $(".client_pin").html(data.client.pin);
                        $(".client_inn").html(data.client.inn);
                        $(".client_phone").html(data.client.phone);

                        var doc_formattedDate = new Date(data.client.document_date);
                        var doc_d = doc_formattedDate.getDate();
                        var doc_m =  doc_formattedDate.getMonth();
                        doc_m += 1;  // JavaScript months are 0-11
                        if (doc_d < 10) {
                            doc_d = "0" + doc_d;
                        }
                        if (doc_m < 10) {
                            doc_m = "0" + doc_m;
                        }
                        var doc_y = doc_formattedDate.getFullYear();
                        $(".client_document").html(data.client.document_serial+' '+data.client.document_number+' '
                            +doc_d + "." + doc_m + "." + doc_y);

                        $('.sc_ball').html(data.scoring.sc_ball);
                        $('.sc_level_info').html(data.scoring.sc_level_info);
                        $('.sc_version').html(data.scoring.sc_version);
                        $('.score_date').html(data.scoring.score_date);


                        $('.client_info_1').html(data.scoring.client_info_1); // fio
                        $('.client_info_2_text').html(data.scoring.client_info_2_text); // den roj
                        $('.client_info_4').html(data.scoring.client_info_4); // adress
                        $('.client_info_5').html(data.scoring.client_info_5); // pinfl
                        $('.client_info_6').html(data.scoring.client_info_6); // inn
                        $('.client_info_8').html(data.scoring.client_info_8); // passport


                        $('.score_img').html('' +
                            '<img id="score_chart" style="padding-left: 9px; margin-bottom: 15px; width: 311px;"' +
                            ' src="'+data.scoring_page1+'" height="149">\n');

                        $('#tb_row_1_ot').html(data.table.row_1.open_total);
                        $('#tb_row_1_os').html(data.table.row_1.open_summ);
                        $('#tb_row_1_ct').html(data.table.row_1.open_total);
                        $('#tb_row_1_cs').html(data.table.row_1.open_summ);

                        $('#tb_row_2_ot').html(data.table.row_2.open_total);
                        $('#tb_row_2_os').html(data.table.row_2.open_summ);
                        $('#tb_row_2_ct').html(data.table.row_2.open_total);
                        $('#tb_row_2_cs').html(data.table.row_2.open_summ);

                        $('#tb_row_3_ot').html(data.table.row_3.open_total);
                        $('#tb_row_3_os').html(data.table.row_3.open_summ);
                        $('#tb_row_3_ct').html(data.table.row_3.open_total);
                        $('#tb_row_3_cs').html(data.table.row_3.open_summ);

                        $('#tb_row_4_ot').html(data.table.row_4.open_total);
                        $('#tb_row_4_os').html(data.table.row_4.open_summ);
                        $('#tb_row_4_ct').html(data.table.row_4.open_total);
                        $('#tb_row_4_cs').html(data.table.row_4.open_summ);

                        $('#tb_row_5_ot').html(data.table.row_5.open_total);
                        $('#tb_row_5_os').html(data.table.row_5.open_summ);
                        $('#tb_row_5_ct').html(data.table.row_5.open_total);
                        $('#tb_row_5_cs').html(data.table.row_5.open_summ);

                        $('#tb_row_6_ot').html(data.table.row_6.open_total);
                        $('#tb_row_6_os').html(data.table.row_6.open_summ);
                        $('#tb_row_6_ct').html(data.table.row_6.open_total);
                        $('#tb_row_6_cs').html(data.table.row_6.open_summ);

                        $('#tb_row_7_ot').html(0);
                        $('#tb_row_7_os').html(0);
                        $('#tb_row_7_ct').html(0);
                        $('#tb_row_7_cs').html(0);

                        $('#tb_row_8_ot').html(0);
                        $('#tb_row_8_os').html(0);
                        $('#tb_row_8_ct').html(0);
                        $('#tb_row_8_cs').html(0);

                        $('#tb_row_9_ot').html(0);
                        $('#tb_row_9_os').html(0);
                        $('#tb_row_9_ct').html(0);
                        $('#tb_row_9_cs').html(0);
                        /*$('#tb_row_7_ot').html(data.table.row_7.open_total);
                        $('#tb_row_7_os').html(data.table.row_7.open_summ);
                        $('#tb_row_7_ct').html(data.table.row_7.open_total);
                        $('#tb_row_7_cs').html(data.table.row_7.open_summ);

                        $('#tb_row_8_ot').html(data.table.row_8.open_total);
                        $('#tb_row_8_os').html(data.table.row_8.open_summ);
                        $('#tb_row_8_ct').html(data.table.row_8.open_total);
                        $('#tb_row_8_cs').html(data.table.row_8.open_summ);

                        $('#tb_row_9_ot').html(data.table.row_9.open_total);
                        $('#tb_row_9_os').html(data.table.row_9.open_summ);
                        $('#tb_row_9_ct').html(data.table.row_9.open_total);
                        $('#tb_row_9_cs').html(data.table.row_9.open_summ);*/

                        $('#tb_row_12_agr_summ').html(data.table.row_12.agr_summ);
                        $('#tb_row_12_agr_comm2').html(data.table.row_12.agr_comm2.content);
                        $('#tb_row_12_agr_comm3').html(data.table.row_12.agr_comm3);
                        $('#tb_row_12_agr_comm4').html(data.table.row_12.agr_comm4);

                        $('#btn-save').val("KatmResult");

                        $('#katmClaimId').val(cid);

                        $('#resultKATMModal').modal('show');
                    })
                });

                $('#successLoan').hide();
                $('#cancelLoan1').hide();

                $('#confirmLoan').click(function () {

                    $('#btn-save-confirm').val("confirmLoan");

                    $('#confirmForm').trigger("reset");

                    $('#modalHeaderConf').html("Confirm Loan");
                    $('#modalBodyConf').html("Arizani to`g`riligini tasdiqlash");

                    $('#modalFormConfirm').modal('show');
                });

                $('#cancelLoan').click(function () {

                    $('#btn-save-cancel').val("cancelLoan");

                    $('#cancelForm').trigger("reset");

                    $('#modalHeader').html("Cancel Loan");

                    $('#modalFormCancel').modal('show');
                });

                if ($("#confirmForm").length > 0) {

                    $("#confirmForm").validate({

                        submitHandler: function (form) {

                            var actionType = $('#btn-save-confirm').val();

                            $('#btn-save-confirm').html('Sending..');

                            $.ajax({
                                data: $('#confirmForm').serialize(),

                                url: "{{ url('/uw/risk-admin-confirm') }}",

                                type: "POST",

                                dataType: 'json',

                                success: function (data) {

                                    $('#confirmForm').trigger("reset");

                                    $('#modalFormConfirm').modal('hide');

                                    $('#modalEndSuccess').modal('show');

                                    $('#confirmLoan').hide();

                                    $('#cancelLoan').hide();

                                    $('#successLoan').show();

                                    $('#success_header').html('Confirm');
                                    $('#success_result').html('Kredit Tasdiqlandi');

                                    $('#btn-save-confirm').html('Save Changes');

                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                    $('#btn-save-confirm').html('Save Changes');
                                }
                            });
                        }
                    })
                }

                if ($("#cancelForm").length > 0) {

                    $("#cancelForm").validate({

                        submitHandler: function (form) {

                            var actionType = $('#btn-save-cancel').val();

                            $('#btn-save-cancel').html('Sending..');

                            $.ajax({
                                data: $('#cancelForm').serialize(),

                                url: "{{ url('/uw/risk-admin-cancel') }}",

                                type: "POST",

                                dataType: 'json',

                                success: function (data) {

                                    $('#cancelForm').trigger("reset");

                                    $('#modalFormCancel').modal('hide');

                                    $('#modalEndSuccess').modal('show');

                                    $('#cancelLoan').hide();

                                    $('#cancelLoan').hide();

                                    $('#confirmLoan').hide();
                                    $('#cancelLoan1').show();

                                    $('#success_header').html('Cancel');
                                    $('#success_result').html('Kredit Qayta ko`rishga yuborildi');

                                    $('#btn-save-cancel').html('Save Changes');

                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                    $('#btn-save-confirm').html('Save Changes');
                                }
                            });
                        }
                    })
                }

                /* -- INPS --*/
                $('body').on('click', '#getResultINPS', function () {

                    var cid = $('#getResultKATM').data('id');

                    $.get('/uw/get-client-inps/' + cid, function (data) {
                        //console.log(data);
                        var data_inps = "";

                        data_inps +="<div class='box-body table-responsive no-padding'>" +
                        "<table class='tabla table-hover'>"+
                        "<tbody>" +
                            "<tr>" +
                            "<th>#</th>" +
                            "<th>Org INN</th>" +
                            "<th>Summa</th>" +
                            "<th>Num</th>" +
                            "<th>Period</th>" +
                            "<th>Org Name</th>" +
                            "</tr>";

                        var tot=0;
                        $.each(data, function (key, val) {
                            key++;
                            tot += val.INCOME_SUMMA;
                            var SUMM = (val.INCOME_SUMMA).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                            data_inps +="<tr>" +
                                "<td>"+key+"</td>" +
                                "<td>"+val.ORG_INN+"</td>" +
                                "<td>"+SUMM+"</td>" +
                                "<td>"+val.NUM+"</td>" +
                                "<td>"+val.PERIOD+"</td>" +
                                "<td>"+val.ORGNAME+"</td>" +
                                "</tr>"
                        });
                        data_inps += "</tbody></table></div>";
                        var total = (tot).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');

                        $('#resultINPSModal').modal('show');
                        $("#resultDataINPS").html(data_inps);
                        $("#resultDataINPSTotal").html("Total: "+total);
                    })
                });

            });

        </script>
    </section>
    <!-- /.content -->
@endsection
