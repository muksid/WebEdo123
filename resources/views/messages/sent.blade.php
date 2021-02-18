<?php ?>
<link href="{{asset('/admin-lte/plugins/select2/select2.min.css')}}" rel="stylesheet">
@extends('layouts.dashboard')

@section('content')
    <!-- Content Header (Page header) -->
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
                            <a href="{{ route('ef-compose') }}" class="btn btn-flat btn-info">
                                <i class="fa fa-pencil"></i> @lang('blade.write_message')</a>
                        </div>
                    </div>

                    <div class="box-body">

                        <form action="{{route('ef-sent')}}" method="POST" role="search">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select name="u" class="form-control select2" style="width: 100%;">
                                            @if(!empty($searchUser))
                                                <option value="{{$searchUser->id}}" selected>
                                                    {{$searchUser->branch_code.'-'.$searchUser->lname.' '.$searchUser->fname}}
                                                </option>
                                            @else
                                                <option value="" selected>
                                                    @lang('blade.select_employee')
                                                </option>
                                            @endif

                                            @if(!empty($users))
                                                @foreach($users as $key => $value)
                                                    <option value="{{$value->id}}">
                                                        {{$value->branch_code.' - '.$value->lname.' '.$value->fname}}
                                                    </option>
                                                @endforeach
                                            @endif

                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group has-success">
                                        <input type="text" class="form-control" name="t" value="{{ $t }}"
                                               placeholder="@lang('blade.doc_name')">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group has-success">
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <div class="input-group input-daterange">
                                                <input type="text" name="d" id="out_date" value="{{ $d }}"
                                                       class="form-control" placeholder="@lang('blade.sent_date')"
                                                       readonly/>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <a href="{{ route('ef-sent') }}" class="btn btn-default btn-flat">
                                            <i class="fa fa-refresh"></i> @lang('blade.reset')
                                        </a>
                                        <button type="submit" class="btn btn-primary btn-flat">
                                            <i class="fa fa-search"></i> @lang('blade.search')
                                        </button>
                                    </div>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </form>
                    </div>

                    <div class="box-body mailbox-messages">
                        <b>@lang('blade.overall'){{': '. $models->total()}} @lang('blade.group_edit_count').</b>
                        <table class="table table-hover table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th><i class="fa fa-user-plus"></i> @lang('blade.to_whom')</th>
                                <th><i class="fa fa-text-height"></i> @lang('blade.subject')</th>
                                <th><i class="fa fa-tag"></i> @lang('blade.type_message')</th>
                                <th><i class="fa fa-hourglass-start"></i> @lang('blade.deadline')</th>
                                <th><i class="fa fa-paperclip"></i></th>
                                <th><i class="fa fa-clock-o"></i> @lang('blade.sent_date')</th>
                                <th><i class="fa fa-trash-o"></i></th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($models->count())
                                @foreach ($models as $key => $model)
                                    @php($key = $key+1)
                                    <tr id="rowId_{{$model->id}}">
                                        <td>{{ $key++ }}</td>
                                        <td class="text-maroon" style="min-width: 30px">
                                            <button type="button" value="{{ $model->id }}"
                                                    class="btn btn-xs btn-success btn-flat get_users">
                                                <i class="fa fa-search"></i></button>
                                        </td>
                                        <td style="max-width: 200px"><a href="{{route('view-sent',
                                                [
                                                    'user_id' => \Illuminate\Support\Facades\Auth::id(),
                                                    'mes_gen' => $model->mes_gen
                                                    ])}}">
                                                {!! \Illuminate\Support\Str::words($model->subject, 10, '...'); !!}
                                            </a>
                                        </td>
                                        <td><span class="text-muted text-sm">{{ $model->messageType->title ?? '' }}</span></td>
                                        <td>
                                            @if($model->mes_term == 0)
                                                <span class="text-sm text-muted">(@lang('blade.no_deadline'))</span>
                                            @else
                                                <span class="text-red">
                                                <i class="fa fa-clock-o"></i>
                                                {{ \Carbon\Carbon::parse($model->mes_term)->format('d.m.Y') }}
                                                </span>

                                            @endif

                                        </td>
                                        <td>
                                            <button type="button" value="{{ $model->id }}" class="get_files">
                                                <i class="fa fa-paperclip"></i>
                                            </button>
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($model->created_at)->format('d M,y H:i') }}
                                        </td>
                                        <td>
                                            <button class="btn btn-xs btn-danger deleteRow" data-id="{{ $model->id }}" >
                                                <i class="fa fa-trash"></i> @lang('blade.delete')
                                            </button>
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

        <div id="usersModal" class="modal fade">
            <div class="modal-dialog modal-confirm"
                 style="overflow-y: scroll; max-height:50%;  margin-top: 50px; margin-bottom:50px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><i class="fa fa-user text-green"></i> @lang('blade.senders')
                            <span id="countDiv" class="text-red"></span> @lang('blade.group_edit_count').</h4>
                    </div>
                    <div class="modal-body">
                        <p class="text-left" id="usersDiv"></p>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-default" data-dismiss="modal">@lang('blade.close')</a>
                    </div>
                </div>
            </div>
        </div>

        <div id="filesModal" class="modal fade">
            <div class="modal-dialog modal-confirm"
                 style="overflow-y: scroll; max-height:50%;  margin-top: 50px; margin-bottom:50px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><i class="fa fa-user text-green"></i> @lang('blade.uploaded_files')</h4>
                    </div>
                    <div class="modal-body">
                        <p class="text-left" id="filesDiv"></p>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-default" data-dismiss="modal">@lang('blade.close')</a>
                    </div>
                </div>
            </div>
        </div>

        {{--// delete row--}}
        <div id="ConfirmModal" class="modal fade text-danger" role="dialog">
            <div class="modal-dialog modal-sm">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title text-center">O`chirishni tasdiqlash</h4>
                    </div>
                    <div class="modal-body">
                        <h4 class="text-center"><span class="glyphicon glyphicon-info-sign"></span> Xatning asl nusxasi, Siz yuborgan xodimlardanham o`chiriladi!</h4>
                    </div>
                    <div class="modal-footer">
                        <center>
                            <button type="button" class="btn btn-success" data-dismiss="modal">Bekor
                                qilish
                            </button>
                            <button type="button" href="#" id="yesDelete" class="btn btn-danger">
                                Ha, O`chirish
                            </button>
                        </center>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="successModal" tabindex="-1" role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header bg-aqua-active">
                        <h4 class="modal-title">
                            Message <i class="fa fa-check-circle"></i>
                        </h4>
                    </div>
                    <div class="modal-body">
                        <h5>Message Successfully deleted</h5>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info closeModal" data-dismiss="modal"><i
                                    class="fa fa-check-circle"></i> Ok
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function () {

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

            });

            $(function () {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                $(".get_users").click(function () {

                    var message_id = $(this).val();
                    $.ajax({
                        url: '/get-sent-users',
                        type: 'POST',
                        data: {_token: CSRF_TOKEN, message_id: message_id},
                        dataType: 'JSON',
                        success: function (data) {
                            var obj = data;
                            var user_input = "";
                            var is_readed = "";
                            $.each(obj['msg'], function (key, val) {
                                key++;
                                if (val.readed_date !== null) {
                                    is_readed = val.readed_date;
                                } else {
                                    is_readed = "<i class='fa fa-eye-slash text-red'></i>";
                                }
                                user_input +=
                                    "<div class='box-comment'>" +
                                    "<div class='comment-text'>" +
                                    "<span class='username'>" + key + ". " + val.lname + " " + val.fname +
                                    "<span class='text-muted pull-right'>" + is_readed + "</span>" +
                                    "</span>" + "<br/>" +
                                    "<i class='fa fa-bank text-blue'></i>" + val.branch_name + ", " + val.depart_name + " " + val.job_title + "" +
                                    "</div>" +
                                    "</div>";
                            });
                            $("#usersModal").modal('show');
                            //alert(users);
                            $("#usersDiv").html(user_input);   //// For replace with previous one
                            $("#countDiv").html(obj['msg'].length)
                        },
                        error: function () {
                            console.log('error');
                        }
                    });
                });
                // End //

                $(".get_files").click(function () {

                    var message_id = $(this).val();

                    $.ajax({
                        url: '/get-files-modal',
                        type: 'POST',
                        data: {_token: CSRF_TOKEN, message_id: message_id},
                        dataType: 'JSON',
                        success: function (data) {
                            var obj = data;
                            var files = "";
                            $.each(obj['msg'], function (key, val) {
                                key++;
                                files +=
                                    "<div class='box-comment'>" +
                                    "<div class='comment-text'>" +
                                    "<span class='username'>" + key + ". " + "<i class='fa fa-image text-green'></i> " + val.file_name +
                                    "<span class='text-muted pull-right'>" + val.file_size + " KB</span>" +
                                    "</span>" + "<br/><br/>" +
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
            });


            // file delete
            $('.deleteRow').on('click', function (e) {
                e.preventDefault();
                var id = $(this).data("id");

                $('#ConfirmModal').data('id', id).modal('show');
            });

            $('#yesDelete').click(function () {

                var token = $('meta[name="csrf-token"]').attr('content');

                var id = $('#ConfirmModal').data('id');

                $('#rowId_'+id).remove();

                $.ajax(
                    {
                        url: '/ef-sent/delete/'+id,
                        type: 'GET',
                        dataType: "JSON",
                        data: {
                            "id": id,
                            "_token": token,
                        },
                        success: function (data)
                        {
                            $('#successModal').modal('show');
                        }
                    });

                $('#ConfirmModal').modal('hide');
            });
        </script>

        <script src="{{ asset("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

        <script src="{{ asset("/admin-lte/dist/js/app.min.js") }}"></script>

        <script src="{{ asset("/admin-lte/plugins/select2/select2.full.min.js") }}"></script>

        <link href="{{ asset ("/admin-lte/bootstrap/css/bootstrap-datepicker.css") }}" rel="stylesheet"/>

        <script src="{{ asset ("/admin-lte/bootstrap/js/bootstrap-datepicker.js") }}"></script>

    </section>
@endsection