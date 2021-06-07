@extends('layouts.edo.dashboard')
<!-- Select2 -->
<link href="{{ asset("/admin-lte/plugins/select2/select2.min.css") }}" rel="stylesheet" type="text/css">

@section('content')

    <!-- TRANSLATED -->

      <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang('blade.create_doc')
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- SELECT2 EXAMPLE -->
        <div class="box box-primary">
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <!-- Message Succes -->
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            {{ $message }}
                        </div>
                    @endif
                <!-- Display Validation Errors -->
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>@lang('blade.error')</strong> @lang('blade.error_check').<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form role="form" id="frm" method="POST" action="{{ url('edo-message') }}" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('blade.doc_view')</label><sup class="text-red"> *</sup>
                                <select class="form-control select2 type_message" name="message_view">
                                    <option value="" selected></option>
                                    <option value="1">Kiruvchi</option>
                                    <option value="2" disabled="disabled">@lang('blade.outgoing')</option>
                                    <option value="3" disabled="disabled">@lang('blade.doc')</option>
                                    <option value="4" disabled="disabled">@lang('blade.request')</option>
                                </select>
                            </div>
                            <div class="message-type-div">
                                <div class="form-group">
                                    <label>@lang('blade.doc_form')</label><sup class="text-red"> *</sup>

                                    <select name="message_type" id="user_id" class="form-control"
                                            style="width: 100%;" required>
                                        <option value="" selected="selected">@lang('blade.select_doc')</option>
                                        @foreach($messageTypes as $key => $value)
                                            <option value="{{ $value->id }}">{{ $value->title }}</option>

                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <strong>@lang('blade.upload_file'):</strong>
                                    <div class="input-group control-group increment">
                                        <input type="file" id="uploadFile" name="message_file[]" class="form-control" multiple required>
                                        <div class="input-group-btn">
                                            <button class="btn btn-success" type="button">
                                                <i class="glyphicon glyphicon-plus"></i> @lang('blade.add')
                                            </button>
                                        </div>
                                    </div>
                                    <div class="clone hide">
                                        <div class="control-group input-group" style="margin-top:10px">
                                            <input type="file" name="message_file[]" class="form-control" multiple>
                                            <div class="input-group-btn">
                                                <button class="btn btn-danger" type="button">
                                                    <i class="glyphicon glyphicon-trash"></i> @lang('blade.delete')
                                                </button>
                                            </div>
                                        </div>
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

                                <div class="form-group {{ $errors->has('to_user_id') ? 'has-error' : '' }}">
                                    <label>@lang('blade.select_receiver')</label><sup class="text-red"> *</sup>

                                    <select name="to_user_id" id="to_user_id" class="form-control select2"
                                            style="width: 100%;" required>
                                        <option value="" selected="selected">@lang('blade.select_employee')</option>
                                        @foreach($users as $key => $value)

                                            <option value="{{ $value->user_id }}">{{ $value->full_name }} - {{ $value->user->job_title }}</option>

                                        @endforeach
                                    </select>

                                </div>

                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="urgent" value="1"> @lang('blade.urgent')
                                    </label>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-6 message-type-div">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>@lang('blade.outgoing_num')</label><sup class="text-red"> *</sup>
                                                <input type="text" name="out_number" maxlength="50" class="form-control"
                                                       placeholder="@lang('blade.enter_num')" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('blade.outgoing_date')</label><sup class="text-red">
                                                    *</sup>

                                                <div class="input-group date">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                    <div class="input-group input-daterange">
                                                        <input type="text" name="out_date" id="out_date"
                                                               class="form-control" required/>
                                                    </div>
                                                </div>
                                                <!-- /.input group -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>@lang('blade.sender_name')</label><sup class="text-red"> *</sup>
                                <input type="text" name="from_name" id="fromName" class="form-control" maxlength="512"
                                       placeholder="@lang('blade.from_whom')" required>
                                <span id="limitFromName" class="help-block">(@lang('blade.limit_512'))</span>
                            </div>
                            <div class="form-group">
                                <label>@lang('blade.doc_name')</label>
                                <input type="text" name="title" id="messageTitle" class="form-control" maxlength="512"
                                       placeholder="@lang('blade.enter_title')">
                                <span id="limitMessageTitle" class="help-block">(@lang('blade.limit_512'))</span>
                            </div>
                            <div class="form-group">
                                <label>@lang('blade.summary')</label><sup class="text-red"> *</sup>
                                <textarea name="text" id="editor" required></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>@lang('blade.reg_journal')</label><sup class="text-red"> *</sup>

                                                <select id="journalType" name="edo_journal_id" class="form-control"
                                                        style="width: 100%;" required>
                                                    <option value="" selected="selected">@lang('blade.select_journal')</option>
                                                    @foreach($journals as $key => $value)
                                                        <option value="{{ $value->id }}">{{ $value->title }}</option>

                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>@lang('blade.incoming_date')</label><sup class="text-red"> *</sup>

                                                <div class="input-group date">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                    <div class="input-group input-daterange">
                                                        <input type="text" name="in_date" class="form-control" required />
                                                    </div>
                                                </div>
                                                <!-- /.input group -->
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div id="lastNumber"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div id="lastNumbera"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <!-- /.col-->
                        <div class="col-md-12 message-type-div">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <a href="/edo-message" class="btn btn-default">@lang('blade.cancel') </a>
                                    <button type="submit" class="btn btn-primary">@lang('blade.save')</button>
                                </div>

                            </div>
                        </div>
                        <!-- /.col -->
                    </form>
                    <!-- /.form-group -->
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- Select2 -->
        <script src="{{ asset("/admin-lte/plugins/select2/select2.full.min.js") }}"></script>

        <link href="{{ asset ("/admin-lte/bootstrap/css/bootstrap-datepicker.css") }}" rel="stylesheet" />
        <script src="{{ asset ("/admin-lte/bootstrap/js/bootstrap-datepicker.js") }}"></script>

        <!-- ckeditor -->
        <script src="{{ asset ("/ckeditor/ckeditor.js") }}"></script>
        <script src="{{ asset ("/ckeditor/samples/js/sample.js") }}"></script>

        <script type="text/javascript">
            initSample();

            // get journal number
            $('#journalType').on('change', function () {

                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                var journalType = $(this).val();

                $.ajax({
                    url: '/get-journal-number',
                    type: 'POST',
                    data: {_token: CSRF_TOKEN, id: journalType},
                    dataType: 'JSON',
                    success: function (data) {

                        var last_number='';
                        var last_numbera='';

                        var inNumber = parseInt(data.lastNumber)+1;

                        last_number+="<label>Last number ("+data.lastNumber+") :</label><sup class='text-red'> *</sup>" +
                            "<input type='number' class='form-control' name='office_number' value='"+inNumber+"' />";

                        last_numbera+="<label>Reg# a:</label>" +
                            "<input type='text' class='form-control' name='office_number_a' placeholder='/a' />";

                        $("#lastNumber").empty().append(last_number);
                        $("#lastNumbera").empty().append(last_numbera);
                    },
                    error: function () {
                        console.log(data);
                    }
                });
            });

            // limit from name
            $('#fromName').keyup(function () {

                $('#limitFromName').show();

                if (this.value.length > 512) {
                    alert('Yuboruvchi nomi: belgilar soni limitdan oshdi!');
                    return false;
                }

                $("#limitFromName").html((512 - this.value.length) + ' belgi qoldi');
            });

            // limit message title
            $('#messageTitle').keyup(function () {

                $('#limitMessageTitle').show();

                if (this.value.length > 512) {
                    alert('Hujjat nomi: belgilar soni limitdan oshdi!');
                    return false;
                }

                $("#limitMessageTitle").html((512 - this.value.length) + ' belgi qoldi');
            });

            $(document).ready(function(){
                $('.message-type-div').hide();

                $('.type_message').on('change', function() {

                    if (this.value == 1)
                    {
                        $(".message-type-div").show();
                    }
                    else
                    {
                        $(".message-type-div").hide();
                    }
                });
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

            // For plus minus click files button
            $(".btn-success").click(function () {
                var html = $(".clone").html();
                $(".increment").after(html);
            });

            $("body").on("click", ".btn-danger", function () {
                $(this).parents(".control-group").remove();
            });

            // This is for selected files
            var fileCount = 0;

            var showFileCount = function() {
                $('#file_count').text('# Files selected: ' + fileCount);
            };

            showFileCount();

            $(document).on('click', '.close', function() {
                $(this).parents('span').remove();
                fileCount -= 1;
                showFileCount();
            });

            $('#uploadFile').on('change', function() {

                $("#upload_prev").empty();

                $("#box_body_prev").empty();

                $("#box_body_prev").append('<div class="box-header with-border">'+
                    '<i class="fa fa-paperclip"></i>' +
                    '<h3 class="box-title"> Tanlangan fayllar </h3>'+
                    '</div>');


                var files = $('#uploadFile')[0].files;
                var totalSize = 0;

                for (var i = 0; i < files.length; i++) {
                    // calculate total size of all files
                    totalSize += files[i].size;
                }
                //1x10^9 = 1 GB
                var sizeInGb = totalSize / 128000000;
                if(sizeInGb > 1){
                    alert("Siz limitdan ortiq fayl belgiladingiz. (max: 120 MB)");
                    this.value = null;
                    $("#upload_prev").empty();
                }

                for (var j = 0; j < files.length; j++) {
                    var fileSize = (files[j].size / 1024 / 1024).toFixed(2);
                    var num = j + 1;
                    $("#upload_prev").append(

                        '<dd>' +num + ". " + files[j].name + ' (' + fileSize + ' MB)' + '</dd>');
                }
                fileCount += files.length;
                showFileCount();
            });
            // End //
        </script>

    </section>

@endsection