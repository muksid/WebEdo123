@extends('layouts.edo.dashboard')

@section('content')

    <!-- TRANSLATED -->

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ $journal->title  }}
            <small>@lang('blade.groups_table')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home_page')</a></li>
            <li><a href="#">@lang('blade.doc')</a></li>
            <li class="active">@lang('blade.groups_table')</li>
        </ol>

        <!-- Message Succes -->
        @if ($message = Session::get('success'))
            <div class="modal fade in" id="myModal" role="dialog" style="display: block">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header bg-aqua-active">
                            <h4 class="modal-title">
                                @lang('blade.task') <i class="fa fa-check-circle"></i>
                            </h4>
                        </div>
                        <div class="modal-body">
                            <h3>{{ $message }}</h3>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-info closeModal" data-dismiss="modal"><i class="fa fa-check-circle"></i> Ok</button>
                        </div>
                    </div>

                </div>
            </div>
        @endif
        <!-- Display Validation Errors -->
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>@lang('blade.error')</strong> @lang('blade.error_check').<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box">

                    <div class="box-body" style="padding-bottom:0">
                        <form action="{{route('edo-journals.viewTasks' ,$id)}}" method="POST" role="search">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-2 has-success">
                                    <div class="form-group">
                                        <select name="u" class="form-control" style="width: 100%;">                                                
                                            @if(($u??'') == '')
                                                <option selected="selected" value=""> @lang('blade.to_whom') </option>
                                            @else
                                                @php
                                                    $edo_user = \App\EdoUsers::where('user_id', $u)->first();
                                                @endphp
                                                <option value="{{$edo_user->user_id}}" selected="selected">{{$edo_user->user->substrUserName($edo_user->user_id)??'' }}</option>
                                            @endif
                                            
                                            @foreach($models as $key => $value)
                                                <option class="{{ (($value->director->status??1) != 1) ? 'text-red':'' }}" value="{{$value->to_user_id}}">
                                                    {{ $value->director->full_name??'' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="form-group has-success">
                                        <input type="text" class="form-control" name="t" value="{{$t??''}}"
                                                placeholder="@lang('blade.text')">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <button type="button" class="btn btn-default" id="daterange-btn">
                                                    <span>
                                                        <i class="fa fa-calendar"></i> Davr oraliq
                                                    </span>
                                                <i class="fa fa-caret-down"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <input name="s_start" id="s_start" value="{{$s_start??''}}" hidden>
                                <input name="s_end" id="s_end" value="{{$s_end??''}}" hidden>

                                
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-default" onclick="location.href='/edo-journals.viewTasks/{{$id}}';"><i class="fa fa-refresh"></i> @lang('blade.reset')</button>
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> @lang('blade.search')</button>
                                    </div>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </form>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body" style="padding-top:0">
                    @lang('blade.overall')<b>{{': '. $models->total()}}</b>
                        <table id="" class="table table-hover table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-center" style="max-width: 100px;">       @lang('blade.reg_date_num_kanc')</th>
                                <th class="text-center" style="vertical-align: inherit;">@lang('blade.journal')</th>
                                <th class="text-center" style="vertical-align: inherit;">@lang('blade.from_whom')</th>
                                <th class="text-center" style="vertical-align: inherit;">@lang('blade.sender_organization')</th>
                                <th class="text-center" style="max-width: 100px;">       @lang('blade.incoming_num_doc')</th>
                                <th class="text-center" style="max-width: 100px;">       @lang('blade.incoming_date_doc')</th>
                                <th class="text-center" style="vertical-align: inherit;">@lang('blade.to_whom')</th>
                                <th class="text-center" style="vertical-align: inherit;">@lang('blade.status')</th>
                                <th class="text-center" style="vertical-align: inherit;">@lang('blade.sent_date_time')  </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1 ?>
                            @foreach ($models as $key => $model)

                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td class="text-bold text-center" style="min-width: 90px">
                                     
                                        <span id="post_id_{{ $model->id }}">{{ $model->in_number.$model->in_number_a  }}</span>

                                        <a href="javascript:void(0)" id="edit-post" data-id="{{ $model->id }}"> <i
                                                    class="fa fa-pencil text-green"></i></a>
                                                    <br>
                                        {{ \Carbon\Carbon::parse($model->message->in_date)->format('d.m.Y')}}
                                    </td>
                                    <td class="text-center">{{ $model->journalName->title??''  }}</td>
                                    <td class="text-green">
                                        {{ $model->officeUser->full_name??''  }}

                                        @if(count($model->redirectTasks))
                                            <span class="text-maroon">@lang('blade.forwarded_docs')</span>
                                            <div class="box-footer box-comments bg-yellow">
                                                @foreach($model->redirectTasks as $key => $value)
                                                    <div class="box-comment">
                                                        <div class="comment">
                                                              <span class="username">
                                                                {{ mb_substr($value->fromUser->fname??'null',0,1).'.'.$value->fromUser->lname??'null' }}
                                                                <span class="text-muted pull-right">
                                                                    {{ date_format($value->created_at, 'd M,Y') }}
                                                                </span>
                                                              </span>
                                                            <i class="text-muted">@lang('blade.comment'): </i>{{ $value->redirect_desc }}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                        @if($model->messageLogFile->count())
                                            <span class="text-maroon">Updated Files</span>
                                            <div class="box-footer box-comments bg-aqua">
                                                @foreach($model->messageLogFile as $key => $value)
                                                    @if($value->comments != null)
                                                    <div class="box-comment">
                                                        <div class="comment">
                                                              <span class="username">
                                                                {{ mb_substr($value->fromUser->fname??'null',0,1).'.'.$value->fromUser->lname??'null' }}
                                                                <span class="text-muted pull-right">
                                                                    {{ date_format($value->created_at, 'd M,Y') }}
                                                                </span>
                                                              </span>
                                                            <i class="text-muted">@lang('blade.comment'): </i>{{ $value->comments??'No comment' }}
                                                        </div>
                                                    </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif

                                    </td>
                                    <td>
                                        <a href="{{ url('edo/view-task-process', ['id'=>$model->edo_message_id,'slug' => $model->message->message_hash??'']) }}">
                                            {!! \Illuminate\Support\Str::words($model->message->from_name??'', 5, '...'); !!}
                                        </a>
                                    </td>
                                    <td class="text-left">
                                        <b> {{ $model->message->out_number??''}}</b>
                                    </td>
                                    <td class="text-sm text-center">
                                        {{ \Carbon\Carbon::parse($model->message->out_date)->format('d.m.Y')}} 
                                    </td>
                                    <td class="text-maroon">
                                        {{ $model->toUser->full_name??'' }}
                                    </td>
                                    <td>
                                        @switch($model->status)
                                            @case(0)
                                            <span class="label label-warning" style = "text-transform:capitalize;">@lang('blade.new')</span>
                                            @break
                                            @case(1)
                                            <span class="label label-default">@lang('blade.on_process')</span>
                                            @break
                                            @case(2)
                                            <span class="label label-primary">@lang('blade.in_execution')</span>
                                            @break
                                            @case(3)
                                            <span class="label label-success">@lang('blade.closed')</span>
                                            @break
                                            @default
                                            @lang('blade.not_detected')
                                        @endswitch

                                        @if($model->message->urgent??'' == 1)
                                            <sup><i class="fa fa-bell-o text-red fa-lg"></i></sup>
                                        @endif
                                    </td>
                                    <td style="min-width: 190px">
                                        {{ \Carbon\Carbon::parse($model->created_at)->format('d-M-Y H:i')  }}
                                        <span class="text-maroon"> ({{$model->created_at->diffForHumans()}})</span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <span class="paginate">{{ $models->links() }}</span>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <div id="usersModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-aqua-active">
                        <h4 class="modal-title"><span id="taskStatus"></span> <span id="countDiv" class="text-red"></span> @lang('blade.group_edit_count').</h4>
                    </div>
                    <div class="modal-body" style="overflow-y: scroll; max-height:50%;  margin-top: 30px; margin-bottom:50px;">
                        <p class="text-left" id="usersDiv"></p>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-default" data-dismiss="modal">@lang('blade.close')</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade modal-primary" id="ajax-crud-modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="postCrudModal"></h4>
                    </div>
                    <form id="postForm" name="postForm">
                        <div class="modal-body">
                            <input type="hidden" name="model_id" id="model_id">
                            <div class="form-group">
                                <label for="name" class="control-label">@lang('blade.reg_num')</label>
                                <input type="number" class="form-control" style="width: 50%" id="in_number"
                                       name="in_number" value="" required="">
                            </div>
                            <div class="form-group">
                                <label for="name" class="control-label">Reg#/a</label>
                                <input type="text" class="form-control" style="width: 50%" id="in_number_a"
                                       name="in_number_a" value="" placeholder="/a">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">@lang('blade.cancel')</button>
                            <button type="submit" class="btn btn-outline" id="btn-save" value="create">@lang('blade.save')
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- DataTables -->
        <script src="/admin-lte/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="/admin-lte/plugins/datatables/dataTables.bootstrap.min.js"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="/js/jquery.validate.js"></script>
        <script src="{{ asset("/admin-lte/plugins/daterangepicker/moment.min.js") }}"></script>
        <script src="{{ asset("/admin-lte/plugins/daterangepicker/daterangepicker.js") }}"></script>
        <script src="{{ asset ("/admin-lte/bootstrap/js/bootstrap-datepicker.js") }}"></script>
        <script src="{{ asset("/admin-lte/plugins/select2/select2.full.min.js") }}"></script>

        

        <script>

            // For post ajax
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $(".get_users").click(function () {

                var message_id = $(this).val();
                $.ajax({
                    url: '/get-executors',
                    type: 'POST',
                    data: {_token: CSRF_TOKEN, message_id: message_id},
                    dataType: 'JSON',
                    success: function (data) {
                        //console.log(data);
                        var obj = data;
                        var user_input = "";
                        var is_readed = "";
                        var d = '';

                        $.each(obj['msg'], function (key, val) {
                            key++;
                            if (val.is_read !== null){
                                //is_readed = val.read_date;
                                d = new Date(val.read_date);
                                is_readed = ("0"+(d.getDay()+1)).slice(-2)+'.'+("0"+(d.getMonth()+1)).slice(-2)+'.'+d.getFullYear()+' <i class="fa fa-clock-o text-blue"></i>'+d.getHours()+':'+d.getMinutes();
                            } else {
                                is_readed = "<i class='fa fa-eye-slash text-red'></i>";
                            }
                            user_input +=
                                "<div class='box-comment'>" +
                                "<div class='comment-text'>" +
                                "<span class='username text-maroon'>" +key+ ". " +val.full_name+ " -> " + val.title_ru +
                                "<span class='text-muted pull-right'>"+is_readed+"</span>"+
                                "</span>" +"<br/>"+
                                "<i class='fa fa-bank text-blue'></i>" +val.branch_code+ ", "+val.job_title+ ""+
                                "</div>" +
                                "</div>";
                        });
                        $("#usersModal").modal('show');
                        //alert(users);
                        $("#usersDiv").html(user_input);   //// For replace with previous one
                        $("#countDiv").html(obj['msg'].length);
                        $("#taskStatus").html(data.status)
                    },
                    error: function () {
                        console.log('error');
                    }
                });
            });

            $(function () {
                $("#example1").DataTable();

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

                //Date range as a button
                $('#daterange-btn').daterangepicker(
                    {
                        ranges: {
                            'Bugun': [moment(), moment()],
                            'Kecha': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                            'Ohirgi 7 kun': [moment().subtract(6, 'days'), moment()],
                            'Ohirgi 30 kun': [moment().subtract(29, 'days'), moment()],
                            'Bu oyda': [moment().startOf('month'), moment().endOf('month')],
                            'O`tgan oyda': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                        },
                        startDate: moment().subtract(29, 'days'),
                        endDate: moment()
                    },
                    function (start, end) {
                        var s_start = start.format('YYYY-MM-DD');

                        var s_end = end.format('YYYY-MM-DD');

                        $('#s_start').val(s_start);
                        $('#s_end').val(s_end);

                        $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                    }
                );
            });

            // close Modal
            $('.closeModal').click(function () {

                $('#myModal').hide();

            });

            // edit journal number
            $(document).ready(function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $('#create-new-post').click(function () {
                    $('#btn-save').val("create-post");
                    $('#postForm').trigger("reset");
                    $('#postCrudModal').html("Add New post");
                    $('#ajax-crud-modal').modal('show');
                });

                $('body').on('click', '#edit-post', function () {
                    var model_id = $(this).data('id');
                    $.get('/office-journal-edit/'+model_id, function (data) {
                        $('#postCrudModal').html("Edit Journal number");
                        $('#btn-save').val("edit-post");
                        $('#ajax-crud-modal').modal('show');
                        $('#model_id').val(model_id);
                        $('#in_number').val(data.journal_number);
                        $('#in_number_a').val(data.journal_number_a);

                    })
                });

                $('body').on('click', '.delete-post', function () {
                    var post_id = $(this).data("id");
                    confirm("Are You sure want to delete !");

                    $.ajax({
                        type: "DELETE",
                        url: "{{ url('ajax-posts')}}"+'/'+post_id,
                        success: function (data) {
                            $("#post_id_" + post_id).remove();
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                });
            });

            if ($("#postForm").length > 0) {
                $("#postForm").validate({

                    submitHandler: function(form) {

                        var actionType = $('#btn-save').val();
                        $('#btn-save').html('Sending..');


                        $.ajax({
                            data: $('#postForm').serialize(),
                            url: "{{ route('office-journal-post') }}",
                            type: "POST",
                            dataType: 'json',
                            success: function (data) {
                                var post = '<tr id="post_id_' + data.id + '"><td>' + data.id + '</td><td>' + data.title + '</td><td>' + data.body + '</td>';
                                post += '<td><a href="javascript:void(0)" id="edit-post" data-id="' + data.id + '" class="btn btn-info">Edit</a></td>';
                                post += '<td><a href="javascript:void(0)" id="delete-post" data-id="' + data.id + '" class="btn btn-danger delete-post">Delete</a></td></tr>';



                                if (actionType == "create-post") {
                                    $('#posts-crud').prepend(post);
                                } else {
                                    //$("#post_id_" + data.id).replaceWith(post);
                                    $("#post_id_" + data.id).replaceWith(data.in_number+data.in_number_a);
                                }

                                $('#postForm').trigger("reset");
                                $('#ajax-crud-modal').modal('hide');
                                $('#btn-save').html('Save Changes');

                            },
                            error: function (data) {
                                console.log('Error:', data);
                                $('#btn-save').html('Save Changes');
                            }
                        });
                    }
                })
            }
        </script>
    </section>
    <!-- /.content -->
@endsection
