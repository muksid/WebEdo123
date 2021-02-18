@extends('layouts.compose')

@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>@lang('blade.write_message')
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home_page')</a></li>
                <li class="active">@lang('blade.write_message')</li>
            </ol>

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>@lang('blade.error')</strong> @lang('blade.to_send_choose')<br><br>
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
                <form role="form" method="POST" action="{{ route('ef-group-compose') }}" enctype="multipart/form-data" id="formGroup">
                    {{csrf_field()}}
                    <div class="col-md-6">
                        <div class="box box-primary">

                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group has-inputError">
                                            <label class="control-label text-red"><i class="fa fa-search text-red"></i> @lang('blade.search_users')</label>
                                            <select class="form-control select2" name="to_users[]" multiple="multiple" data-placeholder="@lang('blade.search_users')" style="width: 100%;">
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->lname.' '.$user->fname }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!-- /.form-group -->
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->
                            </div>

                            <div class="box-header with-border">
                                <h3 class="box-title">@lang('blade.choose_group')
                                    <a href="{{ url('/groups/create') }}"><i class="fa fa-plus"></i>@lang('blade.groups_create_group')</a>
                                </h3>
                            </div>
                            <!-- group buttons -->
                            <div class="box">
                                <div class="box-body">

                                        <div class="table-responsive mailbox-messages">
                                            <table class="table table-hover table-striped">
                                                <tbody>
                                                @foreach($groups as $group)
                                                    <tr>
                                                        <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                                                        <td><input type="checkbox" name="groups_id[]" value="{{ $group->id }}" class="user_checkbox" /></td>
                                                        <td class="mailbox-name">
                                                            <button type="button" value="{{ $group->id }}" class="btn btn-block btn-social btn-dropbox">
                                                                <i class="fa fa-list"></i> {{ $group->title }}
                                                            </button>
                                                        </td>
                                                        <td class="mailbox-subject">
                                                            <button type="button" value="{{ $group->id }}" class="btn btn-block btn-social btn-dropbox group_users">
                                                                <i class="fa fa-user-plus"></i> Xodimlar
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                            <!-- /.table -->
                                        </div>
                                </div>
                            </div>
                            <!-- /.box -->

                            <div class="box-body">
                                <div class="box-footer box-comments">
                                    <div id="mydiv">
                                        <p id="data"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">@lang('blade.write_new_message')</h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div class="form-group {{ $errors->has('subject') ? 'has-error' : '' }}">
                                    <label>@lang('blade.write_message') <span class=""></span></label>
                                    <input type="text" id="subject" name="subject" value="{{ old('subject') }}"
                                           class="form-control" placeholder="@lang('blade.subject')" required autofocus>
                                    @if ($errors->has('subject'))
                                        <span class="text-red" role="alert">
                                        <strong>{{ $errors->first('subject') }}</strong>
                                    </span>
                                    @endif

                                </div>

                                <div class="form-group {{ $errors->has('editor1') ? 'has-error' : '' }}">
                                    <label>@lang('blade.text'):</label>
                                    <textarea name="text" id="editor1" rows="14" cols="110">
                                        <br>
                                        <br>
                                        <i class="text-muted">
                                        @lang('blade.with_respect'),
                                        <br>{{Auth::user()->fname}} {{Auth::user()->lname}}
                                        </i>
                                    </textarea>
                                    @if ($errors->has('editor1'))
                                        <span class="text-red" role="alert">
                                        <strong>{{ $errors->first('editor1') }}</strong>
                                    </span>
                                    @endif

                                </div>

                                <div class="form-group{{ $errors->has('mes_type') ? ' has-error' : '' }}">
                                    <label for="mes_type" class="col-md-4 control-label">@lang('blade.type_of_message')</label>
                                    <select id="mes_type" name="mes_type" class="form-control">
                                        <option value="other">@lang('blade.others')</option>
                                        @foreach ($mes_type as $key => $type)
                                            <option value="{{$type->message_type}}">{{$type->title}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('mes_type'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('mes_type') }}</strong>
                                    </span>
                                    @endif

                                </div>

                                <div class="form-group {{ $errors->has('mes_term') ? 'has-error' : '' }}">
                                    <label>
                                        <input type="checkbox" class="user_checkbox mes_term">
                                        @lang('blade.deadline'):
                                    </label>
                                </div>

                                <div id="autoUpdate"
                                     class="form-group {{ $errors->has('mes_term') ? 'has-error' : '' }}"
                                     style="display: none">
                                    <label>@lang('blade.deadline_date'):</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" name="mes_term" class="form-control pull-right" id="datepicker">
                                    </div>
                                    @if ($errors->has('mes_term'))
                                        <span class="text-red" role="alert">
                                        <strong>{{ $errors->first('mes_term') }}</strong>
                                    </span>
                                    @endif

                                </div>

                                <div class="form-group">
                                    <strong>@lang('blade.upload_file'):</strong>
                                    <div class="input-group control-group increment">
                                        <input type="file" id="uploadFile" name="mes_files[]" class="form-control" multiple>
                                        <div class="input-group-btn">
                                            <button class="btn btn-success" type="button">
                                                <i class="glyphicon glyphicon-paperclip"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="clone hide">
                                        <div class="control-group input-group" style="margin-top:10px">
                                            <input type="file" name="mes_files[]" class="form-control" multiple>
                                            <div class="input-group-btn">
                                                <button class="btn btn-danger" type="button">
                                                    <i class="glyphicon glyphicon-trash"></i>
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
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <div class="pull-right">
                                    <a href="{{ url('/ef/compose') }}" class="btn btn-flat btn-default"><i class="fa fa-remove"></i> @lang('blade.cancel')</a>
                                    <button type="submit" class="btn btn-flat btn-primary" id="submitBtn"><i class="fa fa-envelope-o"></i> @lang('blade.send')</button>
                                </div>
                            </div>
                            <!-- /.box-footer -->
                        </div>
                        <!-- /. box -->
                    </div>
                </form>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->

        <!-- ckeditor -->
        <script src="{{ asset ("/ckeditor/ckeditor.js") }}"></script>
        <script src="{{ asset ("/ckeditor/samples/js/sample.js") }}"></script>
        <script type="text/javascript">
            CKEDITOR.replace("editor1");
        </script>
        <script type="text/javascript">
            $(document).ready(function () {
                // For plus minus click departments
                $(".btn-success").click(function () {
                    var html = $(".clone").html();
                    $(".increment").after(html);
                });

                $("body").on("click", ".btn-danger", function () {
                    $(this).parents(".control-group").remove();
                });
                // End //

                //For message term (hat muddati)
                $('.mes_term').change(function () {
                    if (this.checked)
                        $('#autoUpdate').fadeIn(200);
                    else
                        $('#autoUpdate').fadeOut(200);
                });
                // End //
                $(function () {
                    //Initialize Select2 Elements
                    $(".select2").select2();

                    //Date picker
                    $('#datepicker').datepicker({
                        autoclose: true,
                        dateFormat: 'yy-mm-dd'
                    });
                });
                // End //

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

                // For post group users
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                $(".group_users").click(function () {
                    var group_id = $(this).val();
                    $.ajax({
                        url: '/get-group-users',
                        type: 'POST',
                        data: {_token: CSRF_TOKEN, group_id: group_id},
                        dataType: 'JSON',
                        success: function (data) {
                            //console.log(data);
                            var obj = data;
                            var user_input = "";

                            $.each(obj['msg'], function (key, val) {
                                key++;
                                user_input += "" +
                                    "<div class='box-comment'>" +
                                    "<div class='comment-text'>" +
                                    "<span class='username'>" +key+ ". " +val.lname + " " + val.fname+"</span>" +
                                    "<i class='fa fa-bank'>" +val.branch_code+ " "+val.job_title+ "</i>"+
                                    "</div>" +
                                    "</div>";
                            });
                            $("#mydiv").html(user_input)   //// For replace with previous one
                        },
                        error: function () {
                            console.log('error');
                        }
                    });
                });
                // End //

                $('#submitBtn').click(function(e){
                    e.preventDefault();
                    $('#formGroup').submit();
                    $(this).attr("disabled",true)
                })
            });
        </script>
        <script src="{{ asset('js/treeview.js') }}"></script>

        <script src="{{ asset("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

        <script src="{{ asset("/admin-lte/dist/js/app.min.js") }}"></script>
    </div>

@endsection

