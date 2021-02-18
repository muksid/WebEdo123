@extends('layouts.table')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang('blade.sidebar_control')
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home_page')</a></li>
            <li><a href="#">@lang('blade.messages')</a></li>
            <li class="active">@lang('blade.sidebar_control')</li>
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

                <div class="box box-success">
                    <div class="box-header">
                        <div class="col-md-1">
                        @lang('blade.choose_period'):
                        </div>
                        <div class="col-md-3">
                            <div class="input-group input-daterange">
                                <input type="text" name="from_date" id="from_date" readonly class="form-control" placeholder="2019-01-01" />
                                <div class="input-group-addon">@lang('blade.from')</div>
                                <input type="text"  name="to_date" id="to_date" readonly class="form-control" placeholder="2020-12-31" />
                                <div class="input-group-addon">@lang('blade.till')</div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="button" name="refresh" id="refresh" class="btn btn-warning btn-sm">@lang('blade.refresh')</button>
                            <button type="button" name="filter" id="filter" class="btn btn-info btn-sm sent_files1">@lang('blade.print')</button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th><i class="fa fa-user"></i> @lang('blade.from_whom')</th>
                                <th><i class="fa fa-users"></i> @lang('blade.to_whom')</th>
                                <th><i class="fa fa-text-height"></i> @lang('blade.subject')</th>
                                <th><i class="fa fa-hourglass"></i> @lang('blade.deadline')</th>
                                <th><i class="fa fa-paperclip"></i> @lang('blade.file')</th>
                                <th><i class="fa fa-clock-o"></i> @lang('blade.sent_date')</th>
                                <th><i class="fa fa-trash-o"></i></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1;?>
                            @foreach ($control as $key => $value)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $value->lname. ' ' .$value->fname }}</td>
                                    <td><button type="button" value="{{ $value->mc_id }}" class="sent_users"><i class="fa fa-user-plus text-blue"></i></button></td>
                                    <td><a href="{{ url('view_control',
                                            ['mes_gen' => $value->mes_gen]) }}">
                                            {{ $value->subject }}</a>
                                    </td>
                                    <td>
                                        @if($value->mes_term == 0)
                                            @lang('blade.no_deadline')
                                        @else
                                            <i class="fa fa-clock-o text-red"></i> {{$value->mes_term}}
                                        <!-- Srok Jamshid -->
                                            <?php
                                            $now = time(); // or your date as well
                                            $your_date = strtotime($value->mes_term);
                                            $datediff = $now - $your_date;
                                            $differanceDisplayValue = " ".round($datediff / (60 * 60 * 24));
                                            if($differanceDisplayValue > 0){
                                                echo "<span style='color: green;'>&nbsp +".$differanceDisplayValue."</span>";
                                            }else{
                                                echo "<span style='color: red;'>&nbsp".$differanceDisplayValue."</span>";
                                            }
                                            ?>
                                        <!-- End -->
                                        @endif
                                    </td>
                                    <td><button type="button" value="{{ $value->mc_id }}" class="sent_files"><i class="fa fa-paperclip text-blue"></i></button></td>
                                    <td>{{ $value->created_at }}</td>
                                    <td>
                                        <form action="{{ url('messages/'.$value->mc_id) }}" method="POST" style="display: inline-block">
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
        <div id="myModal" class="modal fade">
            <div class="modal-dialog modal-confirm" style="overflow-y: scroll; max-height:50%;  margin-top: 50px; margin-bottom:50px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><i class="fa fa-user text-green"></i> @lang('blade.receivers')</h4>
                    </div>
                    <div class="modal-body">
                        <p class="text-left" id="mydiv"></p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success btn-block" data-dismiss="modal">@lang('blade.close')</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="myModalf" class="modal fade">
            <div class="modal-dialog modal-confirm" style="overflow-y: scroll; max-height:50%;  margin-top: 50px; margin-bottom:50px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><i class="fa fa-user text-green"></i> @lang('blade.uploaded_files')</h4>
                    </div>
                    <div class="modal-body">
                        <p class="text-left" id="mydivf"></p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success btn-block" data-dismiss="modal">@lang('blade.close')</button>
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
                            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">@lang('blade.close')</button>
                            <button type="button" class="btn btn-outline">@lang('blade.save')</button>
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
                        <h4 class="modal-title">@lang('blade.read_control') <p id="cmcount"></p></h4>
                        <button type="button" class="btn pull-left text-black" onClick="printDiv()">Print</button>
                        <!-- Jamshid added id to button -->
                        <button id="btnId" type="button" class="btn pull-left text-black" style="margin-left: 5px;" onClick="downloadDiv()">@lang('blade.download')</button>
                        <!--        Jamshid end         -->
                    </div>
                    <div class="modal-body" style="overflow-y: scroll; max-height: 600px;">
                        <table id="example2" class="table table-bordered table-striped">
                            <thead id="exportTable">
                            <tr>
                                <th>#</th>
                                <th><i class="fa fa-user"></i> @lang('blade.from_whom')</th>
                                <th><i class="fa fa-text-height"></i> @lang('blade.subject')</th>
                                <th><i class="fa fa-hourglass"></i> @lang('blade.deadline')</th>
                                <th><i class="fa fa-clock-o"></i> @lang('blade.sent_date')</th>
                            </tr>
                            </thead>
                            <div id="divDuration"></div>
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
                    url: '/get-sent-users',
                    type: 'POST',
                    data: {_token: CSRF_TOKEN, message_id: sent_user_id},
                    dataType: 'JSON',
                    success: function (data) {
                        //alert("inside Success...");
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
                        $("#mydiv").html(user_input)   // For replace with previous one
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
                    url: '/get-files-modal',
                    type: 'POST',
                    data: {_token: CSRF_TOKEN, message_id: sent_user_id},
                    dataType: 'JSON',
                    success: function (data) {
                        //alert("inside Success...");
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
                            var obj = data;
                            var control_messages = "";
                            var time = from_date + " @lang('blade.from') " + to_date + " @lang('blade.till')";

                            $.each(obj['msg'], function (key, val) {
                                key++;
                                control_messages +=
                                    "<tr>" +
                                    "<td>" + key + "</td>" +
                                    "<td>" + val.lname + ' ' +val.fname+ "</td>" +
                                    "<td>" + val.subject + "</td>" +
                                    "<td>" + val.mes_term + "</td>" +
                                    "<td>" + val.created_at + "</td>" +
                                    "</tr>";
                            });
                            $("#myModalControl").modal('show');
                            $('#tBodyControl').html(control_messages);
                            $('#divDuration').html(time);
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

            let s = new Date();
            s = s.getFullYear() + "-" + (s.getMonth() + 1) + "-" + s.getDate();
            let oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds

            function parseDate(str) {
                var mdy = str.split('-');
                return new Date(mdy[0]-1, mdy[1],mdy[2]);
            }

            function datediff(first, second) {
                // Take the difference between the dates and divide by milliseconds per day.
                // Round to nearest whole number to deal with DST.
                return Math.round((second-first)/oneDay);
            }


            // *************************** Jamshid function for donwload to xls *********************************** //


            function downloadDiv()
            {
                var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
                var textRange; var j=0;
                tab = document.getElementById('example2'); // id of table

                for(j = 0 ; j < tab.rows.length ; j++)
                {
                    tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
                }
                var message_duration_for_download = document.getElementById("divDuration").innerHTML;
                tab_text=tab_text+message_duration_for_download;
                tab_text=tab_text+"</table>";

                tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
                tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
                tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

                var ua = window.navigator.userAgent;
                var msie = ua.indexOf("MSIE ");

                if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
                {
                    txtArea1.document.open("txt/html","replace");
                    txtArea1.document.write(tab_text);
                    txtArea1.document.close();
                    txtArea1.focus();
                    sa=txtArea1.document.execCommand("SaveAs",true,"Say Thanks to Sumit.xls");
                }
                else                 //other browser not tested on IE 11
                    sa = window.open('data:application/vnd.ms-excel,\ufeff' + encodeURIComponent(tab_text));

                return (sa);
            }

            // ************************* End export ********************************** //

            // ************************* Jamshid Print Table ********************************* //

            function printDiv() {
                var divContents = document.getElementById("tBodyControl").innerHTML;
                var message_duration = document.getElementById("divDuration").innerHTML;
                var a = window.open('', '', 'height=800, width=800');
                a.document.write('<html>');
                a.document.write('<body> <h4>Nazoratdagi xatlar</h4><br>');
                a.document.write('<style>table { border-collapse: collapse;} table, th, td { border: 1px solid black; padding: 2px; text-align: left;}</style>');
                a.document.write(message_duration);
                a.document.write('<br>');
                a.document.write('<table style="font-size: 10px;">');
                a.document.write('<thead><tr><th>#</th><th>Kimdan</th><th>Mavzu</th>');
                a.document.write('<th>Muddat</th><th> Xat jo`natilgan sana</th></tr></thead>');
                a.document.write(divContents);
                a.document.write('</table>');
                a.document.write('</body></html>');
                a.document.close();
                a.print();
            }
            // ******************************** END ********************************** //

        </script>
    </section>
    <!-- /.content -->
@endsection