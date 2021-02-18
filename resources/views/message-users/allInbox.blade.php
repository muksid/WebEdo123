<?php ?>
@extends('layouts.table')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang('blade.archive_inbox')
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

                <div class="box">
                    <div class="box-header">
                        <div class="col-md-1">
                            <a href="{{ route('ef-compose') }}" class="btn btn-flat btn-info">
                                <i class="fa fa-pencil"></i> @lang('blade.write_message')</a>
                        </div>
                    </div>

                    <div class="box-body">
                        <form action="{{route('all/search')}}" method="POST" role="search">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <select name="f" class="form-control select2" style="width: 100%;">
                                            @if($f == '')
                                                <option selected="selected" value="">@lang('blade.branch')</option>
                                            @else
                                                @php
                                                    $filial = \App\Department::where('branch_code', $f)->where('parent_id', 0)->first();
                                                @endphp
                                                <option selected="selected" value="{{$filial->branch_code}}">{{$filial->title }}</option>
                                            @endif
                                            @foreach($filials as $key => $value)
                                                <option value="{{$value->branch_code}}">{{ $value->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <select name="u" class="form-control select2" style="width: 100%;">
                                            @if($u == '')
                                            <option selected="selected" value="">@lang('blade.from_whom')</option>
                                            @else
                                                @php
                                                $user = \App\User::find($u);
                                                @endphp
                                                <option selected="selected" value="{{$user->id}}">{{$user->lname??''}} {{$user->fname??''}}</option>
                                            @endif
                                            @foreach($users as $key => $value)
                                                <option value="{{$value->user->id??''}}">{{$value->user->lname??''}} {{$value->user->fname??''}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group has-success">
                                        <input type="text" class="form-control" name="t" value="{{$t}}"
                                               placeholder="@lang('blade.text_message')">
                                        <input type="text" name="r" value="1" hidden>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-default btn-flat" onclick="location.href='/ef/all-inbox';"><i class="fa fa-refresh"></i> @lang('blade.reset')</button>
                                        <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-search"></i> @lang('blade.search')</button>
                                    </div>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </form>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <div class="mailbox-controls">
                                <div class="pull-right">
                                    @lang('blade.delete_selected')
                                    <button type="button" title="O`chirish" class="text-red delete-all" data-url="">
                                        <i class="glyphicon glyphicon-trash"></i>
                                    </button>
                                    <!-- /.btn-group -->
                                </div>
                                <!-- /.pull-right -->
                            </div>
                            <b>@lang('blade.overall'){{': '. $models->total()}} @lang('blade.group_edit_count').</b>
                            <table id="example1" class="table table-hover table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th><i class="fa fa-bank"></i> @lang('blade.branch')</th>
                                    <th><i class="fa fa-user"></i> @lang('blade.from_whom')</th>
                                    <th><i class="fa fa-link"></i> @lang('blade.position')</th>
                                    <th><i class="fa fa-text-height"></i> @lang('blade.subject')</th>
                                    <th><i class="fa fa-hourglass-start"></i> @lang('blade.deadline')</th>
                                    <th><i class="fa fa-hourglass-start"></i> @lang('blade.type_message')</th>
                                    <th><i class="fa fa-paperclip"></i></th>
                                    <th><i class="fa fa-clock-o"></i> @lang('blade.sent_date')</th>
                                    <th>
                                        <button type="button" class="btn btn-default btn-sm checkbox-toggle">
                                            <i class="fa fa-check-square-o text-red"></i></button>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i = 1;?>
                                @if($models->count())
                                @foreach ($models as $key => $model)
                                    <?php $color = 'style="color: red"'; ?>
                                    @if($model->message->mes_term == 0)
                                        <?php $color = ''; ?>
                                    @endif
                                    <tr <?php echo $color ?>>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $model->user->filial->title??'' }}</td>
                                        <td>
                                            <a href="{{ route('view',
                                                    ['mes_gen' => $model->message->mes_gen,
                                                    'id' => $model->message->id]) }}">
                                                {{ $model->user->lname??''}} {{$model->user->fname??'' }}
                                            </a>
                                        </td>
                                        <td style="font-size: 12px">
                                            @php
                                                echo wordwrap($model->user->department->title??'', 40, "<br />")
                                            @endphp
                                        </td>
                                        <td>
                                            {!! \Illuminate\Support\Str::words($model->message->subject, 5, ' ...') !!}
                                        </td>
                                        <td>
                                            @if($model->message->mes_term == 0)
                                                @lang('blade.no_deadline')
                                            @else
                                                {{ \Carbon\Carbon::parse($model->mes_term)->format('d M, Y') }}

                                                @php
                                                    $diffValue = round((strtotime($model->message->mes_term) - time()) / (3600*24))
                                                @endphp

                                                @if($diffValue > 0)
                                                    <span class="text-green"> {{ '+'.$diffValue }} </span>
                                                @else
                                                    <span class="text-red"> {{ $diffValue }} </span>
                                                @endif

                                            @endif
                                        </td>
                                        <td>{{ $model->message->messageType->title }}</td>
                                        <td>
                                            <button type="button" value="{{ $model->message_id }}" class="btn btn-link get_files">
                                                <i class="fa fa-paperclip"></i>
                                            </button>
                                        </td>
                                        <td style="min-width: 120px">
                                            {{ \Carbon\Carbon::parse($model->created_at)->format('d M,y H:i') }}
                                        </td>
                                        <td>
                                            <input type="checkbox" class="checkbox" data-id="{{$model->id}}">
                                        </td>
                                    </tr>
                                @endforeach

                                @else
                                    <td class="text-red text-center"><i class="fa fa-search"></i>
                                        <b>@lang('blade.not_found')</b></td>
                                @endif
                                </tbody>
                            </table>
                            <span class="paginate">{{ $models->links() }}</span>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>

        <!-- /.row -->
        <div id="filesModal" class="modal fade">
            <div class="modal-dialog modal-confirm" style="overflow-y: scroll; max-height:50%;  margin-top: 50px; margin-bottom:50px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><i class="fa fa-user text-green"></i> Xatga biriktirilgan fayllar</h4>
                    </div>
                    <div class="modal-body">
                        <p class="text-left" id="filesDiv"></p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success btn-block" data-dismiss="modal">Yopish</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="deleteModal" class="modal fade">
            <div class="modal-dialog modal-confirm" style="margin-top: 50px; margin-bottom:50px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><i class="fa fa-trash text-red"></i> O`chirish</h4>
                    </div>
                    <div class="modal-body">
                        <p class="text-left deletedDiv">O`chirish uchun kamida bitta xatni tanlang</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary btn-block" data-dismiss="modal">Yopish</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Select2 -->
        <script src="/admin-lte/plugins/select2/select2.full.min.js"></script>

        <script>
            $(".select2").select2();

            /*$("#search").on('keyup', function(e) {

                var paramSearchValue = $(this).val();
                var paramIsRead = 1;


                if (paramSearchValue.length > 2 && (e.which <= 90 && e.which >= 48 || e.which >= 96 && e.which <= 105)){

                    $('#loadingMessages').show();
                    $('#loading-gif').show();
                    $.ajax({
                        url:"{{ url('message-users.action') }}",
                        method:'GET',
                        data:{query:paramSearchValue, param:paramIsRead},
                        dataType:'json',
                        success:function(data)
                        {
                            $('tbody').html(data.table_data);
                            $('#total_models').text(data.total_model);
                            $('#total_records').text(data.total_data);
                        },
                        complete: function(){
                            $('.modelsCount').hide();
                            $('.totalCount').show();
                            $('#loadingMessages').hide();
                            $('.paginate').hide();
                        }
                    });
                } else if (paramSearchValue.length == ''){

                    $('.totalCount').hide();
                    $.ajax({
                        url:"{{ url('message-users.action') }}",
                        method:'GET',
                        data:{queryEmpty:paramSearchValue, param:paramIsRead},
                        dataType:'json',
                        success:function(data)
                        {
                            $('tbody').html(data.table_data);
                            $('#total_models').text(data.total_model);
                            $('.total_records').text(data.total_data);
                        },
                        complete: function(){
                            $('.modelsCount').show();
                            $('#loadingMessages').hide();
                            $('.paginate').show();
                        }
                    });
                }
            });*/

            // For post mes file ajax
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $(".get_files").click(function () {

                var message_id = $(this).val();

                $.ajax({
                    url: '/get-files-modal',
                    type: 'POST',
                    data: {_token: CSRF_TOKEN, message_id: message_id},
                    dataType: 'JSON',
                    success: function (data) {
                        //alert("inside Success...");
                        console.log(data);
                        var obj = data;
                        var files = "";
                        $.each(obj['msg'], function (key, val) {
                            key++;
                            files +=
                                "<div class='box-comment'>" +
                                "<div class='comment-text'>" +
                                "<span class='username'>" +key+ ". "+"<i class='fa fa-image text-green'></i> " +val.file_name+
                                "<span class='text-muted pull-right'>"+val.file_size+" KB</span>"+
                                "</span>" +"<br/><br/>"+
                                "</div>" +
                                "</div>";
                        });
                        $("#filesModal").modal('show');
                        //alert(users);
                        $("#filesDiv").html(files);   //// For replace with previous one
                    },
                    error: function () {
                        console.log('error');
                    }
                });
            });

            // for after search
            $('table').delegate(".get_files","click",function(){

                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                var message_id = $(this).val();

                $.ajax({
                    url: '/get-files-modal',
                    type: 'POST',
                    data: {_token: CSRF_TOKEN, message_id: message_id},
                    dataType: 'JSON',
                    success: function (data) {
                        console.log(data);
                        var obj = data;
                        var files = "";
                        $.each(obj['msg'], function (key, val) {
                            key++;
                            files +=
                                "<div class='box-comment'>" +
                                "<div class='comment-text'>" +
                                "<span class='username'>" +key+ ". "+"<i class='fa fa-image text-green'></i> " +val.file_name+
                                "<span class='text-muted pull-right'>"+val.file_size+" KB</span>"+
                                "</span>" +"<br/><br/>"+
                                "</div>" +
                                "</div>";
                        });
                        $("#filesModal").modal('show');
                        $("#filesDiv").html(files)   //// For replace with previous one
                    },
                    error: function () {
                        console.log('error');
                    }
                });
            });
            // End //

            // for multiple delete checkbox //
            $(function () {
                //Enable iCheck plugin for checkboxes
                //iCheck for checkbox and radio inputs
                $('.mailbox-messages input[type="checkbox"]').iCheck({
                    checkboxClass: 'icheckbox_flat-blue',
                    radioClass: 'iradio_flat-blue'
                });

                //Enable check and uncheck all functionality
                $(".checkbox-toggle").click(function () {
                    var clicks = $(this).data('clicks');
                    if (clicks) {
                        //Uncheck all checkboxes
                        $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
                        $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
                    } else {
                        //Check all checkboxes
                        $(".mailbox-messages input[type='checkbox']").iCheck("check");
                        $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
                    }
                    $(this).data("clicks", !clicks);
                });

            });

            $(document).ready(function () {

                $('.delete-all').on('click', function(e) {

                    var idsArr = [];

                    $(".checkbox:checked").each(function() {

                        idsArr.push($(this).attr('data-id'));

                    });

                    if(idsArr.length <=0) {

                        $("#deleteModal").modal('show');

                    }  else {

                        if(confirm("Haqiqatan ham tanlangan xatlarni o`chirmoqchimisiz?")) {

                            var strIds = idsArr.join(",");

                            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                            $.ajax({

                                url: '/delete-multiple',
                                type: 'POST',
                                data: {_token: CSRF_TOKEN, ids: strIds},
                                dataType: 'JSON',
                                success: function (data) {

                                    if (data['status']==true) {

                                        $(".checkbox:checked").each(function() {

                                            $(this).parents("tr").remove();

                                        });

                                        $("#deleteModal").modal('show');

                                        $(".deletedDiv").html(data['msg'])

                                    } else {

                                        alert('Xatolik yuz berdi!!');
                                    }
                                },

                                error: function (data) {
                                    alert(data.responseText);
                                }
                            });
                        }
                    }
                });

                $('[data-toggle=confirmation]').confirmation({

                    rootSelector: '[data-toggle=confirmation]',

                    onConfirm: function (event, element) {

                        element.closest('form').submit();

                    }

                });

            });
            // end //
        </script>

        <!-- jQuery 2.2.3 -->
        <script src="{{ asset ("admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

    </section>
    <!-- /.content -->
@endsection