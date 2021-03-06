@extends('layouts.table')

@section('content')
    <link href="{{ asset('css/treeview.css') }}" rel="stylesheet">
    <link href="{{ asset ("/admin-lte/bootstrap/css/bootstrap-datepicker.css") }}" rel="stylesheet"/>

    <!-- Content Header (Page header) -->
    <section class="content-header">
    <h1>
            Message Users Delete &#8213; Count: <b style="color:red">{{ $message_users->total() }}</b>
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
                            <form action="{{route('message-users-delete/search')}}" method="POST" role="search">
                                {{ csrf_field() }}
                                <div class="row">

                                    <div class="col-md-12">

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <select name="f" class="form-control select2" style="width: 100%;">
                                                @if($f =='')
                                                    <option selected="selected" value="{{$f}}"> Select Branch </option>
                                                @else
                                                    <option selected="selected" value="{{$f}}">{{ $f.' - '.$f_title->title }}</option>
                                                @endif
                                                    @foreach($filials as $key => $value)
                                                        <option value="{{$value->branch_code}}">{{ $value->branch_code.' - '.$value->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <select name="u_search" class="form-control select2" style="width: 100%;">
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

                                        
                                    <!-- /.col -->
                                    </div>
                                    <div class="col-md-12" style="text-align:center">
                                        <div class="form-group"> 
                                            <button type="button" class="btn btn-default" onclick="location.href='/message-users-delete';"><i class="fa fa-refresh"></i> @lang('blade.reset')</button>
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> @lang('blade.search')</button>
                                        </div>
                                    </div>
                                        
                                </div>
                                <!-- /.row -->
                            </form>
                        </div>
<!-- ////////////////////////////////////   Header END   ////////////////////////////////////////////// -->


                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding mailbox-messages">
                        <button type="button" title="O`chirish" class="text-red delete-all" style="float:right" data-url="">
                            <i class="glyphicon glyphicon-trash"></i>
                        </button>
                        <button type="button" title="O`chirish" class="text-red delete-result" style="float:right; margin-right: 5px;" data-url="">
                            <b>Delete All Result</b> 
                        </button>
                        <span class="paginate"> {{ $message_users->links() }}  </span>
                        <table id="" class="table table-hover table-bordered table-striped">
                            <thead >       
                                <tr>
                                    <th>#</th>
                                    <th><i class="fa fa-id-badge"></i>  ID          </th>
                                    <th><i class="fa fa-id-badge"></i>  From Branch </th>
                                    <th><i class="fa fa-user"></i>      Sender      </th>
                                    <th><i class=""></i>                Status      </th>
                                    <th><i class="fa fa-id-badge"></i>  Subject     </th>
                                    <th><i class=""></i>                Type        </th>
                                    <th><i class=""></i>                isRead      </th>
                                    <th><i class="fa fa-id-badge"></i>  To Branch   </th>
                                    <th><i class="fa fa-user"></i>      Receiver    </th>
                                    <th><i class=""></i>                Status      </th>
                                    <th><i class="fa fa-paperclip"></i>             </th>
                                    <th><i class=""></i>                To Users    </th>
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
                                @foreach ($message_users as $key => $value)
                                    <tr>
                                        <td>{{ $message_users->firstItem()+$key }}</td>
                                        <td>{{ $value->message_id       }}</td>
                                        <td>{{ $value->user->branch_code??''      }}</td>
                                        <td>
                                            <b>{{ $value->user->lname??'Deleted User' }} {{$value->user->fname??''}} {{$value->user->sname??''}}</b>  
                                            <i style="font-size: 12px;color: #31708f;">{{$value->user->job_title??''}}</i>
                                        </td>
                                        <td>
                                            @switch($value->user->status??'')
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
                                        <td style="max-width: 500px;word-wrap: break-word;"> 
                                            <a href="{{ route('view-delete',['id' => $value->message_id, 'user_id' => $value->from_users_id ]) }}">
                                                {{ $value->message->subject??'' }}
                                            </a> 
                                        </td>
                                        <td>{{ $value->message->mes_type??''         }}</td>
                                        <td>
                                            @if($value->is_readed == 0)
                                                <i class="fa fa-eye" style="color:red"></i>
                                            @else
                                                <span class="label label-success">read</span>
                                            @endif
                                        </td>
                                        <td>{{ $value->toUsers->branch_code??''        }}</td>
                                        <td>
                                            <b>{{ $value->toUsers->lname??'Deleted User' }} {{$value->toUsers->fname??''}} {{$value->toUsers->sname??''}} </b>
                                            <i style="font-size: 12px;color: #31708f;">{{$value->toUsers->job_title??''}}</i>
                                        </td>
                                        <td>
                                            @switch($value->toUsers->status??'')
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
                                        <td>
                                            <button type="button" value="{{ $value->message_id }}" class="btn btn-link get_files">
                                                <i class="fa fa-paperclip"></i>
                                            </button>
                                        </td>
                                        <td class="text-sm">
                                            <button type="button" value="{{ $value->message_id }}" class="btn btn-sm btn-success btn-flat get_users">
                                                <i class="fa fa-user-plus"></i>
                                            </button>
                                        </td>   
                                        <td>{{ $value->message->created_at??''       }}</td>
                                        <td>
                                            @switch($value->is_deleted)
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
                        <span class="paginate"> {{ $message_users->links() }}  </span>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
    </section>
    <!-- Delete Messages Alert -->
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
                    <button class="btn btn-primary btn-block" data-dismiss="modal" onClick="window.location.reload();">Yopish</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->

    <!-- Show Message Files Alert -->
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

    <!-- Show Receivers Alert -->
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
    <script src="{{ asset ("admin-lte/plugins/jQueryUI/jquery-ui.js") }}"></script>
    <script src="{{ asset ("admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>

    <!-- AdminLTE App -->
    <script src="{{ asset("/admin-lte/dist/js/app.min.js") }}"></script>
    <script src="/admin-lte/plugins/select2/select2.full.min.js"></script>
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
            
 
            
            // for multiple delete checkbox //
        
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
                        $("#usersDiv").html(user_input);   //// For replace with previous one
                        $("#countDiv").html(obj['msg'].length)
                    },
                    error: function () {
                        console.log('error');
                    }
                }); // ajax end 
            }); // get-users End


            $(".get_files").click(function () {
                var message_id = $(this).val();
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
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
                                "<span class='username'>" +key+ ". "+"<i class='fa fa-image text-green'></i> " +val.file_name+
                                "<span class='text-muted pull-right'>" + Math.round(val.file_size/1024) + " KB</span>"+
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
                    console.log(typeof strIds);
                    console.log(strIds);
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        url: '/delete-multiple-mess',
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

            $('.delete-result').on('click', function(e) {
                if(confirm("Haqiqatan ham tanlangan xatlarni o`chirmoqchimisiz?")) {

                    var del_arr = <?php echo json_encode($delete_arr); ?>;
                    var strIds = del_arr.join(",");

                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        url: '/delete-multiple-mess',
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
        });
    </script>
@endsection
