@extends('layouts.edo.dashboard')

@section('content')

    <!-- TRANSLATED -->


    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang('blade.sent')
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

                    <!-- /.box-header -->
                    <div class="box-body">

                        <div class="box-body">
                            <form action="{{route('office-tasks-sent')}}" method="POST" role="search">
                                {{ csrf_field() }}
                                <div class="row">
                                <div class="col-md-2">
                                        <div class="form-group has-success">
                                            <select name="j" class="form-control" style="width: 100%;">
                                                @if(($j??'') == '')
                                                    <option selected="selected" value="">Journalni tanlang</option>
                                                @else
                                                    @php
                                                        $journal = \App\EdoJournals::where('id', $j)->first();
                                                    @endphp
                                                    <option selected="selected" value="{{$journal->id}}">{{$journal->title }}</option>
                                                @endif
                                                @foreach($journals as $key => $value)
                                                    <option value="{{$value->id}}">{{ $value->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
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
                                                


                                                @foreach($to_whom as $key => $value)
                                                    <option class="{{ ($value->status != 1) ? 'text-red':'' }}" value="{{$value->user_id}}">
                                                        {{ $value->user->substrUserName($value->user_id)??'' }}
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
                                            <button type="button" class="btn btn-default" onclick="location.href='/office-tasks-sent';"><i class="fa fa-refresh"></i> @lang('blade.reset')</button>
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> @lang('blade.search')</button>
                                        </div>
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->
                            </form>
                        </div>
                        @lang('blade.overall')<b>{{': '. $models->total()}}</b>
                        <table class="table table-hover table-bordered table-striped">
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
                                <?php
                                    $cancel = '';
                                    if ($model->status == '-2'){
                                        $cancel = 'bg-yellow';
                                    }
                                ?>
                                
                                <tr class="<?php echo $cancel ?>" id="post_id{{$model->id}}">
                                    <td>{{ $i++ }}</td>
                                    <td class="text-center">
                                        <b>{{ $model->message->in_number??''}}</b>
                                        <br>
                                        {{ \Carbon\Carbon::parse($model->message->in_date)->format('d.m.Y')}}
                                    </td>
                                    <td class="text-center" style="max-width: 180px;">{{ $model->journalName->title??''  }}</td>
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
 
                                        @if($model->messageLogFile()->count())
                                        
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
                                    <td class="text-sm text-center">
                                        <b>{{ $model->message->out_number??''}}</b><br>
                                        
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($model->message->out_date)->format('d.m.Y')}}
                                    </td>
                                    <td class="text-maroon">{{ $model->toUser->full_name }}</td>
                                    <td>
                                        @switch($model->status)
                                            @case(-2)
                                            <span class="label bg-red-active">Bekor qilingan</span>
                                            <a href="javascript:void(0)" class="delete-task" data-id="{{ $model->id }}">
                                                <i class="glyphicon glyphicon-trash text-maroon"></i>
                                            </a>
                                            @break
                                            @case(-1)
                                            <span class="label bg-yellow-active">Yo`naltirilgan</span>
                                            <a href="javascript:void(0)" class="delete-task" data-id="{{ $model->id }}">
                                                <i class="glyphicon glyphicon-trash text-maroon"></i>
                                            </a>
                                            @break
                                            @case(0)
                                            <span class="label label-warning" style = "text-transform:capitalize;">@lang('blade.new')</span>

                                            <a href="javascript:void(0)" class="delete-task" data-id="{{ $model->id }}">
                                                <i class="glyphicon glyphicon-trash text-maroon"></i>
                                            </a>
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

        <div id="trashModal" class="modal fade text-danger" role="dialog">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h4 class="modal-title">
                            @lang('blade.task') <i class="fa fa-trash"></i>
                        </h4>
                    </div>
                    <div class="modal-body">
                        <p class="text-left">Sizning xatingiz muvaffaqiyatli o`chirildi</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger closeModal" data-dismiss="modal"><i
                                    class="fa fa-check-circle"></i> Ok
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- jQuery 2.2.3 -->
        <script src="{{ asset("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

        <script src="{{ asset("/admin-lte/plugins/daterangepicker/moment.js") }}"></script>
        <script src="{{ asset("/admin-lte/plugins/daterangepicker/moment.min.js") }}"></script>
        <script src="{{ asset("/admin-lte/plugins/daterangepicker/daterangepicker.js") }}"></script>
        <script src="{{ asset ("/admin-lte/bootstrap/js/bootstrap-datepicker.js") }}"></script>
        <script src="{{ asset("/admin-lte/plugins/select2/select2.full.min.js") }}"></script>

        

        <script>

        


            // edit journal number
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

                $('body').on('click', '.delete-task', function () {
                    var task_id = $(this).data("id");
                    var tr = confirm("Are You sure want to delete !");

                    if (tr){
                        $.ajax({
                            type: "DELETE",
                            url: "{{ url('edo-message-journals')}}"+'/'+task_id,
                            success: function (data) {
                                $("#post_id" + task_id).remove();
                                $('#trashModal').modal('show');
                            },
                            error: function (data) {
                                console.log('Error:', data);
                            }
                        });
                    }
                });
            });

            // close Modal
            $('.closeModal').click(function () {

                $('#myModal').hide();

            });
        </script>
    </section>
    <!-- /.content -->
@endsection
