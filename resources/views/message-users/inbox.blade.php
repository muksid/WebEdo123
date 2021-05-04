@extends('layouts.table')

@section('content')

    <section class="content-header">
        <h1>
            @lang('blade.unread_message')
        </h1>
        <ol class="breadcrumb">
            <li><a href="/home"><i class="fa fa-dashboard"></i> @lang('blade.home_page')</a></li>
            <li><a href="#">@lang('blade.messages')</a></li>
            <li class="active">@lang('blade.archive_inbox')</li>
        </ol>

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>@lang('blade.error')</strong> @lang('blade.exist').<br><br>
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
            <div class="col-xs-12">

                <div class="box box-primary">
                    <div class="box-header">
                        <div class="col-md-1">
                            <a href="{{ route('fe-compose') }}" class="btn btn-flat btn-info">
                                <i class="fa fa-pencil"></i> @lang('blade.write_message')</a>
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-1">
                                <div class="form-group">
                                    <button type="button" id="search_filter" class="btn btn-primary btn-flat"><i class="fa fa-filter"></i> Filter</button>
                                </div>
                            </div>

                            <div id="search_adv"></div>

                            <div class="col-md-2">
                                <div class="form-group has-success">
                                    <input type="text" class="form-control" name="t" id="search_t"
                                           placeholder="@lang('blade.text_message')">
                                    <input type="text" name="r" id="search_r" value="0" hidden>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <button type="button" id="search_refresh" class="btn btn-default btn-flat"><i class="fa fa-undo"></i></button>
                                    <button type="submit" id="search" class="btn btn-success btn-flat"><i class="fa fa-search"></i> @lang('blade.search')</button>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div id="loading" class="loading-gif" style="display: none"></div>

                    <div class="box-body">
                        <div class="table-responsive mailbox-messages" id="search_table">

                            <b id="search_total">@lang('blade.overall'){{': '. $models->total()}} @lang('blade.group_edit_count').</b>
                            <table id="example1" class="table table-hover table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th><i class="fa fa-bank"></i> @lang('blade.branch')</th>
                                    <th><i class="fa fa-user"></i> @lang('blade.from_whom')</th>
                                    <th><i class="fa fa-link"></i> @lang('blade.position')</th>
                                    <th><i class="fa fa-text-height"></i> @lang('blade.subject')</th>
                                    <th><i class="fa fa-clock-o"></i> @lang('blade.sent_date')</th>
                                    <th><i class="fa fa-paperclip"></i> @lang('blade.file')</th>
                                    <th>
                                        <button type="button" class="btn btn-outline-danger checkbox-toggle">
                                            <i class="fa fa-trash-o text-red"></i> <strong class="text-maroon">Barchasini belgilash</strong>
                                        </button>
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-danger btn-flat deleteMessage" data-url="">
                                                <i class="fa fa-trash-o"></i> @lang('blade.delete_selected')
                                            </button>
                                        </div>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i = 1;?>
                                @if($models->count())
                                    @foreach ($models as $key => $model)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $model->user->filial->title??'' }}</td>
                                            <td>
                                                <a href="{{route('fe-view',['id'=>$model->message->id, 'mes_gen'=>$model->message->mes_gen])}}">
                                                    {{ $model->user->lname??''}} {{$model->user->fname??'' }}
                                                </a>
                                            </td>
                                            <td>
                                                <span class="text-sm">{!! wordwrap($model->user->department->title??'', 80, "<br />") !!}</span>
                                            </td>
                                            <td>
                                                {!! \Illuminate\Support\Str::words($model->message->subject??'', 5, ' ...') !!}
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($model->created_at)->format('d M, Y H:i') }}
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-flat btn-primary getFiles"
                                                        value="{{ $model->message_id }}">
                                                    <i class="fa fa-paperclip"></i>
                                                </button>
                                            </td>
                                            <td class="text-center">
                                                <input type="checkbox" class="checkbox checkbox-checked" data-id="{{$model->id}}">
                                            </td>
                                        </tr>
                                    @endforeach

                                @else
                                    <td colspan="8" class="text-red text-center"><i class="fa fa-search"></i>
                                        <b>@lang('blade.not_found')</b>
                                    </td>
                                @endif
                                </tbody>
                            </table>
                            <nav>{{ $models->appends(Request::except('page'))->links() }}</nav>
                        </div>
                    </div>

                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>

        <div id="blade_append"></div>

    </section>

    <script src="{{ asset ("admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

    <script src="{{ asset("/admin-lte/plugins/select2/select2.full.min.js") }}"></script>

    <script>

        $(".getFiles").click(function () {

            let id = $(this).val();

            $.ajax({
                url: '/fe/getBlade',
                type: 'GET',
                data: {id: id, type: 'files'},
                dataType: 'json',
                beforeSend: function(){
                    $("#loading").show();
                },
                success: function(res){
                    $('#blade_append').html(res.blade);
                    $('#filesModal').modal('show');

                },
                complete:function(res){
                    $("#loading").hide();
                }

            });

        });

        $(".select2").select2();

        $(".deleteMessage").click(function () {

            let id = $(this).val();

            $.ajax({
                url: '/fe/getBlade',
                type: 'GET',
                data: {type: 'deleteAll'},
                dataType: 'json',
                beforeSend: function(){
                    $("#loading").show();
                },
                success: function(res){
                    $('#blade_append').html(res.blade);
                    $('#ConfirmModal').data('id', id).modal('show');

                },
                complete:function(res){
                    $("#loading").hide();
                }

            });

        });

        $('#search_filter').click(function(){

            $.ajax({
                type : 'get',
                url : '/fe/get-filial',
                data :{'user_type' : 'inbox'},
                beforeSend: function(){
                    $("#loading").show();
                },
                success: function(res){
                    //console.log(res);
                    $('#search_adv').html(res);

                },
                complete:function(res){
                    $("#loading").hide();
                }
            });
        });

        $('#search_refresh').click(function(){

            $('#search_adv').empty();

            $('#search_t').val('');

            let filial = $('#search_f').val();
            let user = $('#search_u').val();
            let text = $('#search_t').val();
            let read = $('#search_r').val();

            $.ajax({
                type : 'get',
                url : '/fe/all-inbox',
                data:{
                    'filial':filial,
                    'user'  :user,
                    'text'  :text,
                    'read'  :read,
                },
                beforeSend: function(){
                    $("#loading").show();
                },
                success: function(res){
                    //console.log(res);
                    $('#search_table').html(res);

                },
                complete:function(res){
                    $("#loading").hide();
                }
            });


        });

        $('#search_t').keydown(function(event){

            var keyCode = (event.keyCode ? event.keyCode : event.which);
            if (keyCode === 13) {

                $('#search').trigger('click');

            }
        });

        $('#search').click(function(){

            let filial = $('#search_f').val();
            let user = $('#search_u').val();
            let text = $('#search_t').val();
            let read = $('#search_r').val();

            let s_start = $('#s_start').val();
            let s_end = $('#s_end').val();

            $.ajax({
                type : 'get',
                url : '/fe/all-inbox',
                data:{
                    'filial':filial,
                    'user'  :user,
                    'text'  :text,
                    'read'  :read,
                    's_start'  :s_start,
                    's_end'  :s_end,
                },
                beforeSend: function(){
                    $("#loading").show();
                },
                success: function(res){
                    console.log(res)
                    $('#search_table').html(res);

                },
                complete:function(res){
                    $("#loading").hide();
                }
            });
        })

        $(function () {

            $('.mailbox-messages input[type="checkbox"]').iCheck({
                checkboxClass: 'icheckbox_flat-blue',
                radioClass: 'iradio_flat-blue'
            });

            $(".deleteMessage").hide();

            $(".checkbox-toggle").click(function () {


                var clicks = $(this).data('clicks');
                if (clicks) {
                    $(".deleteMessage").hide(200);
                    //Uncheck all checkboxes
                    $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
                    $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');

                } else {
                    //if ($('input:checkbox:checked').length !== 0){
                        $(".deleteMessage").show(300);
                    //}
                    //Check all checkboxes
                    $(".mailbox-messages input[type='checkbox']").iCheck("check");
                    $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
                }
                $(this).data("clicks", !clicks);
            });

            $(".iCheck-helper").click(function() {
                var numberNotChecked = $('input:checkbox:checked').length;
                if(numberNotChecked > 0) {
                    $(".deleteMessage").show(300);
                } else {
                    $(".deleteMessage").hide(200);
                }
            });

        });
    </script>

@endsection
