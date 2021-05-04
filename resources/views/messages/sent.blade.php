
@extends('layouts.table')

@section('content')

    <section class="content-header">
        <h1>
            @lang('blade.sent_message')
        </h1>
        <ol class="breadcrumb">
            <li><a href="/homr"><i class="fa fa-dashboard"></i> @lang('blade.home_page')</a></li>
            <li><a href="#">@lang('blade.messages')</a></li>
            <li class="active">@lang('blade.sent_message')</li>
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
                                    <input type="text" name="r" id="search_r" value="1" hidden>
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

                    <div class="box-body mailbox-messages" id="search_table">
                        <b>@lang('blade.overall'){{': '. $models->total()}} @lang('blade.group_edit_count').</b>
                        <table class="table table-hover table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th><i class="fa fa-user-plus"></i> @lang('blade.to_whom')</th>
                                <th><i class="fa fa-text-height"></i> @lang('blade.subject')</th>
                                <th><i class="fa fa-clock-o"></i> @lang('blade.sent_date')</th>
                                <th><i class="fa fa-paperclip"></i> @lang('blade.file')</th>
                                <th><i class="fa fa-trash-o"></i> @lang('blade.delete')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($models->count())
                                @foreach ($models as $key => $model)
                                    @php($key = $key+1)
                                    <tr id="rowId_{{$model->id}}">
                                        <td>{{ $key++ }}</td>
                                        <td class="text-maroon" style="min-width: 30px">

                                            <button type="button" class="btn btn-flat btn-info getUsers"
                                                    value="{{ $model->id }}">
                                                <i class="fa fa-search-plus"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <a href="{{route('fe-view-sent',['id'=>$model->id,'mes_gen'=>$model->mes_gen])}}">
                                                {!! \Illuminate\Support\Str::words($model->subject, 10, '...'); !!}
                                            </a>
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($model->created_at)->format('d M, Y H:i') }}
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-flat btn-primary getFiles"
                                                    value="{{ $model->id }}">
                                                <i class="fa fa-paperclip"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-flat btn-danger deleteMessage"
                                                    value="{{ $model->id }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach

                            @else
                                <td colspan="6" class="text-red text-center"><i class="fa fa-search"></i>
                                    <b>@lang('blade.not_found')</b></td>
                            @endif
                            </tbody>
                        </table>
                        <nav>{{ $models->appends(Request::except('page'))->links() }}</nav>
                    </div>

                </div>

            </div>

        </div>

        <div id="blade_append"></div>

        <script>

            $(".getUsers").click(function () {

                let id = $(this).val();

                $.ajax({
                    url: '/fe/getBlade',
                    type: 'GET',
                    data: {id: id, type: 'users'},
                    dataType: 'json',
                    beforeSend: function(){
                        $("#loading").show();
                    },
                    success: function(res){
                        $('#blade_append').html(res.blade);
                        $('#usersModal').modal('show');

                    },
                    complete:function(res){
                        $("#loading").hide();
                    }

                });

            });

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

            $(".deleteMessage").click(function () {

                let id = $(this).val();

                $.ajax({
                    url: '/fe/getBlade',
                    type: 'GET',
                    data: {type: 'delete'},
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
                    data :{'user_type' : 'sent'},
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
                    url : '/fe/sent',
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
                    url : '/fe/sent',
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
            });

            $(document).ready(function () {

                $(function () {
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

            });

        </script>

    </section>

    <script src="{{ asset("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

    <script src="{{ asset("/admin-lte/plugins/select2/select2.full.min.js") }}"></script>
@endsection
