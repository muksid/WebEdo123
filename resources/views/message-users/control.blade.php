@extends('layouts.table')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Nazoratdagi xatlar
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Bosh sahifa</a></li>
            <li><a href="#">Xatlar</a></li>
            <li class="active">Nazoratdagi xatlar</li>
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

                <div class="box box-success">
                    <div class="box-header">
                        <div class="col-md-1">
                            Davrni tanlang:
                        </div>
                            <div class="col-md-3">
                                <div class="input-group input-daterange">
                                    <input type="text" name="from_date" id="from_date" readonly class="form-control" />
                                    <div class="input-group-addon">dan</div>
                                    <input type="text"  name="to_date" id="to_date" readonly class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="button" name="refresh" id="refresh" class="btn btn-warning btn-sm">Refresh</button>
                                <button type="button" name="filter" id="filter" class="btn btn-info btn-sm sent_files1">Print</button>
                            </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th><i class="fa fa-user"></i> Kimdan</th>
                                <th><i class="fa fa-users"></i> Kimlarga</th>
                                <th><i class="fa fa-text-height"></i> Mavzu</th>
                                <th><i class="fa fa-hourglass"></i> Muddat</th>
                                <th><i class="fa fa-paperclip"></i> Fayl</th>
                                <th><i class="fa fa-clock-o"></i> Xat jo`natilgan sana</th>
                                <th><i class="fa fa-trash-o"></i></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1;?>
                            @foreach ($control as $key => $value)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $value->lname. ' ' .$value->fname }}</td>
                                    <td><button type="button" value="{{ $value->message_id }}" class="sent_users"><i class="fa fa-user-plus text-blue"></i></button></td>
                                    <td><a href="{{ url('view_control',
                                            ['mes_gen' => $value->mes_gen]) }}">
                                            {{ $value->subject }}</a>
                                    </td>
                                    <td>
                                        @if($value->mes_term == 0)
                                            Muddat yo`q
                                        @else
                                            <i class="fa fa-clock-o text-red"></i> {{$value->mes_term}}
                                        @endif
                                    </td>
                                    <td><button type="button" value="{{ $value->message_id }}" class="sent_files"><i class="fa fa-paperclip text-blue"></i> Fayl</button></td>
                                    <td>{{ $value->created_at }}</td>
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
    </section>
    <!-- /.content -->
    <div id="myModal" class="modal fade">
        <div class="modal-dialog modal-confirm" style="overflow-y: scroll; max-height:50%;  margin-top: 50px; margin-bottom:50px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fa fa-user text-green"></i> Xat jo`natilgan xodimlar</h4>
                </div>
                <div class="modal-body">
                    <p class="text-left" id="mydiv"></p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success btn-block" data-dismiss="modal">Yopish</button>
                </div>
            </div>
        </div>
    </div>
    <div id="myModalf" class="modal fade">
        <div class="modal-dialog modal-confirm" style="overflow-y: scroll; max-height:50%;  margin-top: 50px; margin-bottom:50px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fa fa-user text-green"></i> Xatga biriktirilgan fayllar</h4>
                </div>
                <div class="modal-body">
                    <p class="text-left" id="mydivf"></p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success btn-block" data-dismiss="modal">Yopish</button>
                </div>
            </div>
        </div>
    </div>


    <div class="example-modal">
        <div class="modal modal-info">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Info Modal</h4>
                    </div>
                    <div class="modal-body">
                        <p>One fine body&hellip;</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-outline">Save changes</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    </div>
    <!-- /.example-modal -->

    <div id="myModalControl" class="modal fade">
        <div class="modal-dialog modal-confirm" style="max-height:70%; width: 90%; margin-bottom:50px;">
            <div class="modal-content box box-success">
                <div class="modal-header" style="background-color: #00a65a; color: #ffffff">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Nazoratga olingan xatlar <p id="cmcount"></p></h4>
                    <button type="button" class="btn pull-left text-black">Print</button>
                </div>
                <div class="modal-body" style="overflow-y: scroll; max-height: 600px;">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th><i class="fa fa-user"></i> Kimdan</th>
                            <th><i class="fa fa-text-height"></i> Mavzu</th>
                            <th><i class="fa fa-hourglass"></i> Muddat</th>
                            <th><i class="fa fa-clock-o"></i> Xat sanasi</th>
                        </tr>
                        </thead>
                        <tbody id="tBodyControl">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- jQuery 2.2.3 -->
{{--    <script src="{{ asset ("admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>--}}
    <link href="{{ asset ("/admin-lte/bootstrap/css/bootstrap-datepicker.css") }}" rel="stylesheet" />
    <script src="{{ asset ("/admin-lte/bootstrap/js/datepicker/bootstrap.min.js") }}"></script>
    <script src="{{ asset ("/admin-lte/bootstrap/js/bootstrap-datepicker.js") }}"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="{{ asset ("admin-lte/bootstrap/js/bootstrap.min.js") }}"></script>
    <!-- DataTables -->
    <script src="{{ asset ("admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>
    <script src="{{ asset ("admin-lte/plugins/datatables/dataTables.bootstrap.min.js") }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset ("admin-lte/dist/js/app.min.js") }}"></script>

    <script>
        $(function () {
            $("#example1").DataTable();
        });
        $(".sent_users").click(function () {
            var sent_user_id = $(this).val();
            $.ajax({
                url: '/sentuserajax',
                type: 'POST',
                data: {_token: CSRF_TOKEN, name: sent_user_id},
                dataType: 'JSON',
                success: function (data) {
                    //alert("inside Success...");
                    console.log(data);
                    var obj = data;
                    var user_input = "";
                    var is_readed = "";
                    $.each(obj['msg'], function (key, val) {
                        key++;
                        if (val.readed_date !== null){
                            is_readed = val.readed_date;
                        } else {
                            is_readed = "<i class='fa fa-eye-slash text-red'></i>";
                        }
                        user_input +=
                            "<div class='box-comment'>" +
                            "<div class='comment-text'>" +
                            "<span class='username'>" +key+ ". " +val.lname + " " + val.fname+
                            "<span class='text-muted pull-right'>"+is_readed+"</span>"+
                            "</span>" +"<br/>"+
                            "<i class='fa fa-bank text-blue'></i>" +val.branch_name+ ", "+val.depart_name+ " "+val.job_title+ ""+
                            "</div>" +
                            "</div>";
                    });
                    $("#myModal").modal('show');
                    //alert(users);
                    $("#mydiv").html(user_input)   //// For replace with previous one
                },
                error: function () {
                    console.log('error');
                }
            });
        });
        // End //

        // For post mes file ajax
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $(".sent_files").click(function () {
            var sent_user_id = $(this).val();
            $.ajax({
                url: '/sentfileajax',
                type: 'POST',
                data: {_token: CSRF_TOKEN, name: sent_user_id},
                dataType: 'JSON',
                success: function (data) {
                    //alert("inside Success...");
                    console.log(data);
                    var obj = data;
                    var user_input = "";
                    $.each(obj['msg'], function (key, val) {
                        key++;
                        user_input +=
                            "<div class='box-comment'>" +
                            "<div class='comment-text'>" +
                            "<span class='username'>" +key+ ". "+"<i class='fa fa-image text-green'></i> " +val.file_name+
                            "<span class='text-muted pull-right'>"+val.file_size+" KB</span>"+
                            "</span>" +"<br/><br/>"+
                            "</div>" +
                            "</div>";
                    });
                    $("#myModalf").modal('show');
                    //alert(users);
                    $("#mydivf").html(user_input)   //// For replace with previous one
                },
                error: function () {
                    console.log('error');
                }
            });
        });
        // End //
        $(document).ready(function(){

            $('.input-daterange').datepicker({
                todayBtn: 'linked',
                format: 'yyyy-mm-dd',
                autoclose: true
            });

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $(".sent_files1").click(function () {
                var from_date = $('input[name="from_date"]').val();
                var to_date = $('input[name="to_date"]').val();
                $.ajax({
                    url: '/controlDateAjax',
                    type: 'POST',
                    data: {_token: CSRF_TOKEN, f_date: from_date, t_date: to_date},
                    dataType: 'JSON',
                    success: function (data) {
                        console.log(data);
                        var obj = data;
                        var control_messages = "";
                        $.each(obj['msg'], function (key, val) {
                            key++;
                            control_messages +=
                                "<tr>" +
                                "<td>" +key+ "</td>" +
                                "<td>" +val.lname+ ' ' +val.fname+ "</td>" +
                                "<td>" +val.subject+ "</td>" +
                                "<td>" +val.mes_term+ "</td>" +
                                "<td>" +val.created_at+ "</td>" +
                                "</tr>";
                        });
                        $("#myModalControl").modal('show');
                        $('#tBodyControl').html(control_messages);
                    },
                    error: function () {
                        console.log('error');
                    }
                });
            });

            $('#refresh').click(function(){
                $('#from_date').val('');
                $('#to_date').val('');
                fetch_data();
            });
        });
    </script>
@endsection