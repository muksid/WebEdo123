@extends('layouts.table')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang('blade.term_message')
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home_page')</a></li>
            <li><a href="#">@lang('blade.messages')</a></li>
            <li class="active">@lang('blade.term_message')</li>
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

                <div class="box box-danger">
                    <div class="box-header">
                        <div class="col-md-1">
                            <a href="{{ route('ef-compose') }}" class="btn btn-flat btn-info">
                                <i class="fa fa-pencil"></i> @lang('blade.write_message')</a>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th><i class="fa fa-bank"></i> @lang('blade.branch')</th>
                                <th><i class="fa fa-user"></i> @lang('blade.from_whom')</th>
                                <th><i class="fa fa-link"></i> @lang('blade.position')</th>
                                <th><i class="fa fa-text-height"></i> @lang('blade.subject')</th>
                                <th><i class="fa fa-check-square-o"></i></th>
                                <th><i class="fa fa-hourglass-start"></i> @lang('blade.deadline')</th>
                                <th><i class="fa fa-tag"></i> @lang('blade.type_message')</th>
                                <th><i class="fa fa-paperclip"></i></th>
                                <th><i class="fa fa-clock-o"></i> @lang('blade.received_date')</th>
                                <th><i class="fa fa-trash-o"></i></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1;?>
                            @foreach ($term_inbox as $key => $value)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td class="text-sm">{{ $value->branch }}</td>
                                    <td><a href="{{ route('messages.show',
                                            ['mes_gen' => $value->mes_gen,
                                            'id' => $value->message_id]) }}">
                                            {{ $value->lname .' '. $value->fname }}</a>
                                    </td>
                                    <td class="text-sm">{{ $value->dep_name.' '.$value->job_title }}</td>
                                    <td class="text-sm">
                                        @if (strlen($value->subject) > 70)
                                            {{substr($value->subject, 0, 60) . '...' }}
                                        @else
                                            {{ $value->subject }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($value->is_readed == 0)
                                            <i class="fa fa-eye-slash" style="color: red"></i>
                                        @else
                                            <i class="fa fa-eye" style="color: #00a65a"></i>
                                        @endif
                                    </td>
                                    <td>
                                        @if($value->mes_term == 0)
                                            M. yo`q
                                        @else
                                            {{ \Carbon\Carbon::parse($value->mes_term)->format('d M, Y') }}

                                            @php
                                                $diffValue = round((strtotime($value->mes_term) - time()) / (3600*24))
                                            @endphp

                                            @if($diffValue > 0)
                                                <span class="text-green"> {{ '+'.$diffValue }} </span>
                                            @else
                                                <span class="text-red"> {{ $diffValue }} </span>
                                            @endif

                                        @endif
                                    </td>
                                    <td>{{ $value->title }}</td>
                                    <td><button type="button" value="{{ $value->message_id }}" class="btn btn-link get_files"><i class="fa fa-paperclip"></i></button></td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($value->created_at)->format('d M,y H:i') }}
                                    </td>
                                    <td>
                                        <form action="{{ url('message-users/'.$value->mu_id) }}" method="POST" style="display: inline-block">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}

                                            <button type="submit" title="O`chirish" id="delete-group-{{ $value->id }}" class="text-red">
                                                <i class="fa fa-btn fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
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
                        <a class="btn btn-default" data-dismiss="modal">Yopish</a>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(function () {
                $("#example1").DataTable();
            });

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
                        //console.log(data);
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
                        $("#filesDiv").html(files)   //// For replace with previous one
                    },
                    error: function () {
                        console.log('error');
                    }
                });
            });
            // End //
        </script>

        <!-- jQuery 2.2.3 -->
        <script src="{{ asset ("admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

        <!-- DataTables -->
        <script src="{{ asset ("admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>

        <script src="{{ asset ("admin-lte/plugins/datatables/dataTables.bootstrap.min.js") }}"></script>
        <!-- AdminLTE App -->

        <script src="{{ asset("/admin-lte/dist/js/app.min.js") }}"></script>

    </section>
    <!-- /.content -->
@endsection