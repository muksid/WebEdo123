@extends('layouts.edo.dashboard')
<!-- Select2 -->
<link href="{{ asset("/admin-lte/plugins/select2/select2.min.css") }}" rel="stylesheet" type="text/css">

@section('content')

    <div class="content-header">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>@lang('blade.qr_documents')
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Bosh sahifa</a></li>
                <li class="active">qr edit</li>
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
                            <div class="modal-header bg-red-active">
                                <h4 class="modal-title">
                                    @lang('blade.task') <i class="fa fa-trash"></i>
                                </h4>
                            </div>
                            <div class="modal-body">
                                <h3>{{ $message }}</h3>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger closeModal" data-dismiss="modal"><i
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

            <div class="box box-primary">
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">

                            <form role="form" method="POST"
                                  action="{{ url('edo-qr-messages/'.$model->id) }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                {{ method_field('PATCH') }}

                                <div class="form-group">
                                    <label>@lang('blade.doc_name')</label><sup class="text-red"> *</sup>
                                    <input type="text" name="title" id="messageTitle" class="form-control"
                                           maxlength="512"
                                           value="{{ $model->title }}" required>
                                    <span id="limitMessageTitle" class="help-block">(@lang('blade.limit_512'))</span>
                                </div>

                                <div class="form-group">
                                    <label>@lang('blade.summary')</label><sup class="text-red"> *</sup>
                                    <textarea name="text" id="editor" required>{{ $model->text }}</textarea>
                                </div>

                                <div class="col-md-12">
                                    <div class="box box-danger">
                                        <div class="row">

                                            <div class="col-md-2">
                                                <div class="box-body">
                                                    <div class="form-group {{ $errors->has('message_number') ? 'has-error' : '' }}">
                                                        <label>@lang('blade.qr_number_d')<span class=""></span></label>
                                                        <input type="text" id="message_number" name="message_number"
                                                               class="form-control"
                                                               value="{{ $model->message_number }}">
                                                        @if ($errors->has('message_number'))
                                                            <span class="text-red" role="alert">
                                        <strong>{{ $errors->first('message_number') }}</strong>
                                    </span>
                                                        @endif

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="box-body">
                                                    <div class="form-group">
                                                        <label>@lang('blade.date')</label><sup class="text-red">
                                                            *</sup>

                                                        <div class="input-group date">
                                                            <div class="input-group-addon">
                                                                <i class="fa fa-calendar"></i>
                                                            </div>
                                                            <div class="input-group input-daterange">
                                                                <input type="text" name="message_date" id="out_date"
                                                                       class="form-control"
                                                                       value="{{ $model->message_date }}" required/>
                                                            </div>
                                                        </div>
                                                        <!-- /.input group -->
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="box-body">
                                                    <div class="form-group {{ $errors->has('guide_user_id') ? 'has-error' : '' }}">
                                                        <label>@lang('blade.qr_signed_by')</label><sup class="text-red">
                                                            *</sup>

                                                        <select name="guide_user_id" id="guide_user_id"
                                                                class="form-control select2"
                                                                style="width: 100%;">
                                                            <option selected="selected"
                                                                    value="{{ $model->guide_user_id }}">
                                                                {{ mb_substr($model->guide->fname??'', 0 ,1).'.'
                                                                                    .mb_substr($model->guide->sname??'', 0 ,1).'.'
                                                                                    .$model->guide->lname??'' }}
                                                            </option>
                                                            @foreach($guide as $key => $value)
                                                                <option value="{{ $value->user_id }}">
                                                                    {{ mb_substr($value->user->fname??'', 0 ,1).'.'
                                                                                    .mb_substr($value->user->sname??'', 0 ,1).'.'
                                                                                    .$value->user->lname??'' }}
                                                                </option>

                                                            @endforeach
                                                        </select>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="box-body">
                                                    <div class="col-md-3">
                                                    
                                                        @foreach (modelFiles as $file)
                                                            <a href="#"
                                                                class="text-info text-bold mailbox-attachment-name"
                                                                target="_blank"
                                                                onclick="window.open('<?php echo('/edo-fileView/' . $file->id); ?>',
                                                                        'modal',
                                                                        'width=800,height=900,top=30,left=500');
                                                                        return false;">
                                                                <i class="fa fa-search-plus"></i> {{ \Illuminate\Support\Str::limit($file->file_name, 35,'...') }}
                                                            </a>
                                                            <ul class="list-inline pull-right">
                                                                <li>
                                                                    <a href="{{ url('edo-fileDownload',['id'=>$file->id]) }}"
                                                                        class="link-black text-sm"><i
                                                                                class="fa fa-cloud-download text-primary"></i> @lang('blade.download')
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                            <i class="text-red">({{ $file->size($file->file_size)??'' }})</i>
                                                            <br><br>
                                                        @endforeach

                                                    </div>
                                                    <div class="col-md-12">

                                                        <div class="form-group">
                                                            <strong>@lang('blade.upload_file'):</strong>
                                                            <div class="input-group control-group increment">
                                                                <input type="file" id="uploadFile" name="message_file[]"
                                                                       class="form-control" multiple>
                                                            </div>

                                                            <div class="box box-solid">
                                                                <div id="box_body_prev"></div>
                                                                <!-- /.box-header -->
                                                                <div class="box-body">
                                                                    <dl>
                                                                        <div id="upload_prev"></div>
                                                                    </dl>
                                                                </div>
                                                                <!-- /.box-body -->
                                                            </div>
                                                            <!-- /.box -->
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <!-- /.col-->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <a href="{{ url('qr-edo-messages') }}"
                                               class="btn btn-default">@lang('blade.cancel') </a>
                                            <button type="submit" class="btn btn-primary">@lang('blade.update')</button>
                                        </div>

                                    </div>
                                </div>
                                <!-- /.col -->
                            </form>

                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.row -->
            <script src="{{ asset("/admin-lte/plugins/select2/select2.full.min.js") }}"></script>

            <link href="{{ asset ("/admin-lte/bootstrap/css/bootstrap-datepicker.css") }}" rel="stylesheet"/>

            <script src="{{ asset ("/admin-lte/bootstrap/js/bootstrap-datepicker.js") }}"></script>

            <!-- ckeditor -->
            <script src="{{ asset ("/ckeditor/ckeditor.js") }}"></script>

            <script src="{{ asset ("/ckeditor/samples/js/sample.js") }}"></script>

            <script type="text/javascript">
                initSample();
                // limit message title
                $('#messageTitle').keyup(function () {

                    $('#limitMessageTitle').show();

                    if (this.value.length > 512) {
                        alert('Hujjat nomi: belgilar soni limitdan oshdi!');
                        return false;
                    }

                    $("#limitMessageTitle").html((512 - this.value.length) + ' belgi qoldi');
                });


                $(function () {
                    //Initialize Select2 Elements
                    $(".select2").select2();
                });

                //Date picker
                $('#datepicker').datepicker({
                    autoclose: true
                });
                $('.input-datepicker').datepicker({
                    todayBtn: 'linked',
                    todayHighlight: true,
                    format: 'yyyy-mm-dd',
                    autoclose: true
                });
                $('.input-daterange').datepicker({
                    todayBtn: 'linked',
                    forceParse: false,
                    todayHighlight: true,
                    format: 'yyyy-mm-dd',
                    autoclose: true
                });


                // This is for selected files
                var fileCount = 0;

                var showFileCount = function () {
                    $('#file_count').text('# Files selected: ' + fileCount);
                };

                showFileCount();

                $(document).on('click', '.close', function () {
                    $(this).parents('span').remove();
                    fileCount -= 1;
                    showFileCount();
                });

                $('#uploadFile').on('change', function () {

                    $("#upload_prev").empty();

                    $("#box_body_prev").empty();

                    $("#box_body_prev").append('<div class="box-header with-border">' +
                        '<i class="fa fa-paperclip"></i>' +
                        '<h3 class="box-title"> Tanlangan fayllar </h3>' +
                        '</div>');


                    var files = $('#uploadFile')[0].files;
                    var totalSize = 0;

                    for (var i = 0; i < files.length; i++) {
                        // calculate total size of all files
                        totalSize += files[i].size;
                    }
                    //1x10^9 = 1 GB
                    var sizeInGb = totalSize / 128000000;
                    if (sizeInGb > 1) {
                        alert("Siz limitdan ortiq fayl belgiladingiz. (max: 120 MB)");
                        this.value = null;
                        $("#upload_prev").empty();
                    }

                    for (var j = 0; j < files.length; j++) {
                        var fileSize = (files[j].size / 1024 / 1024).toFixed(2);
                        var num = j + 1;
                        $("#upload_prev").append(
                            '<dd>' + num + ". " + files[j].name + ' (' + fileSize + ' MB)' + '</dd>');
                    }
                    fileCount += files.length;
                    showFileCount();
                });
                // End //


                // close Modal
                $('.closeModal').click(function () {

                    $('#myModal').hide();

                });

            </script>

        </section>
        <!-- /.content -->
    </div>


@endsection

