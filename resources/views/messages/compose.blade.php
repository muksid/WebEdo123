@extends('layouts.compose')
@section('content')

    <div class="content-wrapper">

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
        <!-- /.content -->

        <div id="loading" class="loading-gif" style="display: none"></div>

        <section class="content">
            <div class="row">
                <form role="form" method="POST" action="{{ route('post-fe-compose') }}" enctype="multipart/form-data">
                    {{csrf_field()}}

                    <div class="col-md-6">
                        <div class="box box-primary">

                            <div class="box-body">
                                <a class="btn btn-block btn-social btn-foursquare btn-lg" id="get_search_users">
                                    <i class="fa fa-search"></i> @lang('blade.search_users')
                                </a>

                                <div id="append_search_users"></div>


                            </div>

                            <div class="box-body">

                                <a class="btn btn-block btn-social btn-bitbucket btn-lg" id="get_check_users">
                                    <i class="fa fa-check-square-o"></i> @lang('blade.select_users')
                                </a>

                                <div id="append_check_users"></div>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-6">
                        <div class="box box-primary">

                            <div class="box-header with-border">
                                <h3 class="box-title">@lang('blade.write_new_message')</h3>
                            </div>

                            <div class="box-body">

                                <div class="form-group {{ $errors->has('subject') ? 'has-error' : '' }}">
                                    <label>@lang('blade.subject') <span class=""></span></label>
                                    <input type="text" id="subject" name="subject" value="{{ old('subject') }}"
                                           class="form-control" placeholder="@lang('blade.subject') ..." autofocus>
                                    @if ($errors->has('subject'))
                                        <span class="text-red" role="alert">
                                        <strong>{{ $errors->first('subject') }}</strong>
                                    </span>
                                    @endif

                                </div>

                                <div class="form-group {{ $errors->has('text') ? 'has-error' : '' }}">
                                    <label>@lang('blade.text'):</label>
                                    <textarea name="text" id="editor1" rows="14" cols="110">
                                        <br>
                                        <br>
                                        <i class="text-muted">
                                        @lang('blade.with_respect'),
                                        <br>{{Auth::user()->fname}} {{Auth::user()->lname}}
                                        </i>
                                    </textarea>
                                    @if ($errors->has('text'))
                                        <span class="text-red" role="alert">
                                        <strong>{{ $errors->first('text') }}</strong>
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

                            <div class="box-footer">
                                <div class="pull-right">
                                    <a href="{{ url('/fe/compose') }}" class="btn btn-flat btn-default"><i class="fa fa-remove"></i> @lang('blade.cancel')</a>
                                    <button type="submit" class="btn btn-flat btn-primary prg"><i class="fa fa-envelope-o"></i> @lang('blade.send')</button>
                                </div>
                            </div>

                        </div>

                    </div>

                </form>

            </div>

        </section>

        <script src="{{ asset ("/ckeditor/ckeditor.js") }}"></script>

        <script src="{{ asset ("/ckeditor/samples/js/sample.js") }}"></script>

        <script src="{{ asset("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

        <script type="text/javascript">
            CKEDITOR.replace("editor1");
        </script>

        <script type="text/javascript">

            $("#get_search_users").click(function () {

                let id = $(this).val();

                $.ajax({
                    url: '/fe/getBlade',
                    type: 'GET',
                    data: {type: 'compose_search_users'},
                    dataType: 'json',
                    beforeSend: function(){
                        $("#loading").show();
                    },
                    success: function(res){
                        $('#append_search_users').html(res.blade);

                    },
                    complete:function(res){
                        $("#loading").hide();
                    }

                });

            });

            $("#get_check_users").click(function () {

                $.ajax({
                    url: '/fe/getBlade',
                    type: 'GET',
                    data: {type: 'compose_check_users'},
                    dataType: 'json',
                    beforeSend: function(){
                        $("#loading").show();
                    },
                    success: function(res){
                        $('#append_check_users').html(res.blade);

                    },
                    complete:function(res){
                        $("#loading").hide();
                    }

                });

            });
            function getCheckUsersAuto(){
                $.ajax({
                    url: '/fe/getBlade',
                    type: 'GET',
                    data: {type: 'compose_check_users'},
                    dataType: 'json',
                    success: function(res){
                        $('#append_check_users').html(res.blade);

                    },
                    complete:function(res){
                        $("#loading").hide();
                    }

                });
            }
            setTimeout(getCheckUsersAuto, 500);

            $(document).ready(function () {

                // For plus minus click files button
                $(".btn-success").click(function () {
                    var html = $(".clone").html();
                    $(".increment").after(html);
                });

                $("body").on("click", ".btn-danger", function () {
                    $(this).parents(".control-group").remove();
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
            });
        </script>

    </div>

@endsection

