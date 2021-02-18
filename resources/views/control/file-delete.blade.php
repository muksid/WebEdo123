@extends('layouts.table')

@section('content')
    <link href="{{ asset('css/treeview.css') }}" rel="stylesheet">
    <link href="{{ asset ("/admin-lte/bootstrap/css/bootstrap-datepicker.css") }}" rel="stylesheet"/>
    <!-- Content Header (Page header) -->
    <section class="content-header">
    <h1>
        @lang('blade.file') &#8213; Count: <b style="color:red">{{ $file_info->total() }}</b>, 
        Size: 
        <b style="color:red">
            @if($file_inf_size->sum('file_size') < 1073741824)
                {{ round($file_inf_size->sum('file_size')/1048576,2)." MB"}}
            @else
                {{ round($file_inf_size->sum('file_size')/1073741824,2)." GB"}}
            @endif
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

                <div class="box box-danger">
                    <div class="box-header with-boreder">

<!-- /////////////////////////////////////   HEADER   ///////////////////////////////////////////// -->                   
                    <div class="box-body">
                        <form action="{{route('file-search')}}" method="POST" role="search">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <select name="f" class="form-control select2" style="width: 100%;">
                                            @if($f =='')
                                                <option selected="selected" value="{{$f}}">Select Branch</option>
                                            @else
                                                <option selected="selected" value="{{$f}}">{{$f.' - '.$f_title->title}}</option>
                                            @endif
                                                @foreach($filials as $key => $value)
                                                    <option value="{{$value->branch_code}}">{{$value->branch_code.' - '.$value->title}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="f_search" value="{{$f_search}}" placeholder=" Filename ">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group has-success">
                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <div class="input-group input-daterange">
                                                    <input type="text" name="from" id="out_date" value="{{ $from }}" 
                                                    class="form-control" placeholder="@lang('blade.sent_date')" readonly/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group has-success">
                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <div class="input-group input-daterange">
                                                    <input type="text" name="till" id="out_date" value="{{ $till }}" 
                                                    class="form-control" placeholder="@lang('blade.sent_date')" readonly/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <select name="user_status" class="form-control" style="width: 100%;">
                                                    @if($user_status == '')
                                                        <option selected="selected" value="">Status *</option>
                                                        @elseif($user_status == 1)
                                                            <option selected="selected" value="1">Active *</option>
                                                        @elseif($user_status == 0)
                                                            <option selected="selected" value="0">Passive *</option>
                                                        @elseif($user_status == 2)
                                                            <option selected="selected" value="2">Deleted *</option>
                                                    @endif
                                                <option value="">Status</option>
                                                <option value="1">Active</option>
                                                <option value="0">Passive</option>
                                                <option value="2">Deleted</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                            <select name="isDeleted" class="form-control">
                                                    @if($isDeleted == '')
                                                        <option selected="selected" value="">Is Deleted? *</option>
                                                        @elseif($isDeleted == 1)
                                                            <option selected="selected" value="1">Deleted *</option>
                                                        @elseif($isDeleted == 3 || $isDeleted == 0)
                                                            <option selected="selected" value="3">Not Deleted *</option>
                                                        @elseif($isDeleted == 2)
                                                            <option selected="selected" value="2">Both *</option>
                                                        @endif
                                                <option value="">Is Deleted?</option>      
                                                <option value="1">Deleted</option>
                                                <option value="3">Not Deleted</option>
                                                <option value="2">Both</option>
                                            </select>
                                        </div>


                                </div>    

                                <div class="col-md-12" >
                                    
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <select name="u_search" class="form-control select2">
                                                @if($u_search == '')
                                                <option selected="selected" value="">@lang('blade.from_whom')</option>
                                                @else
                                                    @php
                                                    $user = \App\User::find($u_search);
                                                    @endphp
                                                    <option selected="selected" value="{{$user->id}}">{{$user->lname .' '. $user->fname}}</option>
                                                @endif
                                                @foreach($users as $key => $value)
                                                    <option value="{{$value->user->id??''}}">{{ $value->user->lname??'' }} {{ $value->user->fname??'' }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="m_search" value="{{$m_search}}" placeholder=" Subject">
                                            </div>
                                        </div>

                                    <div class="col-md-6" style="text-align:center">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-default" onclick="location.href='/file-control';"><i class="fa fa-refresh"></i> @lang('blade.reset')</button>
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> @lang('blade.search')</button>
                                        </div>
                                    </div>
                                        
                                </div>
                                


                            </div>
                            <!-- /.row -->
                        </form>
                    </div>
<!-- ////////////////////////////////////   HEADER END ////////////////////////////////////////////// -->
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding mailbox-messages">
                        <button type="button" title="O`chirish" class="text-red delete-all" data-url="" style="float:right">
                            <i class="glyphicon glyphicon-trash"></i>
                        </button>
                        <button type="button" title="O`chirish" class="text-red delete-result" style="float:right; margin-right: 5px;" data-url="">
                            <b>Delete All Result</b> 
                        </button>
                        <span class="paginate"> {{ $file_info->links() }} </span>
                        <table id="" class="table table-hover table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th><i class="fa fa-id-badge"></i>  F.ID        </th>
                                <th><i class="fa fa-user-plus"></i> To          </th>
                                <th><i class="fa fa-id-badge"></i>  M.ID        </th>
                                <th><i class=""></i>                MFO         </th>
                                <th><i class="fa fa-user"></i>      Sender      </th>
                                <th><i class=""></i>                Status      </th>
                                <th><i class="fa fa-paperclip"></i> Filename    </th>
                                <th><i class="fa fa-align-left"></i>Subject     </th>
                                <th><i class="fa fa-user"></i>      Size        </th>
                                <th><i class="fa fa-file"></i>      Format      </th>
                                <th><i class="fa fa-hourglass"></i> Created at  </th>
                                <th><i class="fa fa-trash-o"></i>               </th>
                                <th>
                                    <button type="button" class="btn btn-default btn-sm checkbox-toggle">
                                        <i class="fa fa-check-square-o text-red"></i>
                                    </button>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1;?>
                            @foreach ($file_info as $key => $value)
                                <tr>
                                    <td>{{ $file_info->firstItem()+$key  }} </td>
                                    <td>{{ $value->id }}            </td>
                                    <td class="text-sm"><button type="button" value="{{ $value->message_id }}" class="btn btn-sm btn-success btn-flat get_users"><i class="fa fa-user-plus"></i></button></td>
                                    <td>{{ $value->message_id }}    </td>
                                    <td>{{ $value->message->from_branch??'' }}   </td>
                                    <td>
                                        <b>{{ $value->message->user->lname??'Deleted User' }} {{$value->message->user->fname??''}} {{$value->message->user->sname??''}} </b>
                                        <i style="font-size: 12px;color: #31708f;">{{$value->message->user->job_title??''}}</i>
                                    </td>
                                    <td>
                                        @switch($value->message->user->status??'')
                                            @case('0')
                                            <span class="label label-warning">passive</span>
                                            @break
                                            @case(1)
                                            <span class="label label-success">active</span>
                                            @break
                                            @case(2)
                                            <span class="label label-danger">deleted</span>
                                            @break
                                            @default
                                            <span class="label label-default">unknown</span>
                                        @endswitch
                                    </td>
                                    <td style="max-width: 500px;word-wrap: break-word;">{{ $value->file_name }}     </td>
                                    <td style="max-width: 500px;word-wrap: break-word;">{{ $value->message->subject??'Not Found' }}       </td>
                                    <!-- round(intval($file_total_size)/1073741824,2) -->
                                    @if( $value->file_size > 1048576  )
                                        <td>{{ round($value->file_size/1048576,2) }} MB    </td>
                                    @elseif($value->file_size >1024 && $value->file_size < 1048576 )
                                        <td>{{ round($value->file_size/1024,2) }} KB    </td>
                                    @else
                                        <td>{{ $value->file_size }} B    </td>
                                    @endif
                                    <td>{{ $value->file_extension}} </td>
                                    <td>{{ $value->created_at }}    </td>
                                    <td>
                                        @switch($value->message->is_deleted??'')
                                        @case(0)
                                            <span class="label label-success">Not deleted</span>
                                        @break
                                        @case(1)
                                            <span class="label label-danger">Deleted</span>
                                        @break
                                        @case(2)
                                            <span class="label label-warning">Both</span>
                                        @break
                                        @default
                                            <span class="label label-default">unknown</span>
                                        @break
                                        @endswitch
                                    </td>
                                    <td><input type="checkbox" class="checkbox" data-id="{{$value->id}}"></td>
                                </tr>

                            @endforeach
                            </tbody>
                        </table>
                        <span class="paginate"> {{ $file_info->links() }} </span>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
    </section>
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
    <!-- /.content -->
    <div id="usersModal" class="modal fade">
        <div class="modal-dialog modal-confirm" style="overflow-y: scroll; max-height:50%;  margin-top: 50px; margin-bottom:50px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fa fa-user text-green"></i> @lang('blade.senders') <span id="countDiv" class="text-red"></span> @lang('blade.group_edit_count').</h4>
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
    <!-- jQuery 2.2.3 -->
    <script src="{{ asset ("admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset("/admin-lte/dist/js/app.min.js") }}"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="admin-lte/bootstrap/js/bootstrap.min.js"></script>
    <!-- DataTables -->
    <script src="admin-lte/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="admin-lte/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <!-- SlimScroll -->
    <script src="admin-lte/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="admin-lte/plugins/fastclick/fastclick.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="admin-lte/dist/js/demo.js"></script>
    <script src="/admin-lte/plugins/select2/select2.full.min.js"></script>

    <!-- iCheck -->
    <script src="{{ asset("/admin-lte/plugins/iCheck/icheck.min.js") }}"></script>
    <script src="{{ asset ("/admin-lte/bootstrap/js/bootstrap-datepicker.js") }}"></script>

    <script type="text/javascript">       
    
         $(document).ready(function(){

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


            $(".select2").select2();

            $('.total_records').hide();
            
            $(document).on('keyup', '#search', function(){
                var query = $(this).val();
                if (query.length >= 3) {
                    $('#loadingUsers').show();
                    $('#loading-image').show();
                    $.ajax({
                        url:"{{ url('file-control.action') }}",
                        method:'GET',
                        data:{query:query},
                        dataType:'json',
                        success:function(data)
                        {
                            $('tbody').html(data.table_data);
                            $('#total_models').text(data.total_model);
                            $('#total_records').text(data.total_data);
                        },
                        complete: function(){
                            $('.modelsCount').hide();
                            $('.total_records').show();
                            $('#loadingUsers').hide();
                            $('.paginate').hide();
                        }
                    });
                } else if (query == ''){
                    console.log('test');
                    $('#loadingUsers').show();
                    $('#loading-image').show();
                    $.ajax({
                        url:"{{ url('users.action') }}",
                        method:'GET',
                        data:{queryEmpty:query},
                        dataType:'json',
                        success:function(data)
                        {
                            $('tbody').html(data.table_data);
                            $('#total_models').text(data.total_model);
                            $('#total_records').text(data.total_data);
                        },
                        complete: function(){
                            $('.modelsCount').hide();
                            $('.total_records').show();
                            $('#loadingUsers').hide();
                            $('.paginate').show();
                        }
                    });
                }
            });
            
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

            $('.delete-all').on('click', function(e) {

                var idsArr = [];

                $(".checkbox:checked").each(function() {
                    idsArr.push($(this).attr('data-id'));
                });

                if(idsArr.length <=0) {
                    $("#deleteModal").modal('show');
                }
                else{

                    if(confirm("Haqiqatan ham tanlangan xatlarni o`chirmoqchimisiz?")) {
                        var strIds = idsArr.join(",");
                        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                        $.ajax({

                            url: '/delete-multiple-files',
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

                                } 
                                else {
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

            // Here
            // For post ajax
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $(".get_users").click(function () {

                var message_id = $(this).val();
                $.ajax({
                    url: '/get-sent-users',
                    type: 'POST',
                    data: {_token: CSRF_TOKEN, message_id: message_id},
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
            // Ends
        
            $('table').delegate(".get_users","click",function(){

                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                var message_id = $(this).val();
                $.ajax({
                    url: '/get-sent-users',
                    type: 'POST',
                    data: {_token: CSRF_TOKEN, message_id: message_id},
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
            
            $('.delete-result').on('click', function(e) {
                if(confirm("Haqiqatan ham tanlangan fayllarni o`chirmoqchimisiz?")) {
                    
                    var del_arr = <?php echo json_encode($delete_arr); ?>;
                    var strIds = del_arr.join(",");

                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        url: '/delete-multiple-files',
                        type: 'POST',
                        data: {_token: CSRF_TOKEN, ids: strIds},
                        dataType: 'JSON',
                        success: function (data) {
                            console.log(data);
                            if (data['status']==true) {
                                $(".checkbox:checked").each(function() {
                                    $(this).parents("tr").remove();
                                });
                                $("#deleteModal").modal('show');
                                $(".deletedDiv").html(data['msg']);
                                location.reload();
                            } 
                            else {
                                alert('Xatolik yuz berdi!!');
                            }
                        },

                        error: function (data) {
                            alert(data.responseText);
                        }
                    });
                }
            });
            // End Delete Results

        });
    </script>

@endsection
