@extends('layouts.edo.dashboard')

@section('content')

    <!-- TRANSLATED -->

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang('blade.inbox_doc')
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

                <div class="box box-primary">

                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-hover table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('blade.from_whom')</th>
                                <th>@lang('blade.sender_organization')</th>
                                <th>@lang('blade.doc_num')</th>
                                <th>@lang('blade.type_of_doc')</th>
                                <th>@lang('blade.doc_name')</th>
                                <th>@lang('blade.status')</th>
                                <th>@lang('blade.received_date')</th>
                            </tr>
                            </thead>
                            <tbody>
                           <?php $i = 1 ?>
                            @foreach ($models as $key => $model)
                                <tr id="post_id{{$model->id}}">

                                    <td>{{ $i++ }}</td>
                                    <td>{{ $model->officeUser->full_name }}
                                        <sup class="text-maroon"> ({{ $model->userJob->userRole->title_ru??'' }})</sup>
                                    </td>
                                    <td>
                                        <a href="{{ route('view-guide-task', $model->message->message_hash) }}">
                                            {!! \Illuminate\Support\Str::words($model->message->from_name, 5, '...'); !!}
                                        </a>
                                    </td>
                                    <td class="text-sm text-center">
                                        <b>{{ $model->message->in_number??''}} {{ $model->message->in_number_a??''}}</b><br>
                                        {{ \Carbon\Carbon::parse($model->message->in_date)->format('d.m.Y')}}
                                    </td>
                                    <td>{{ $model->messageType->title ?? 'null' }}</td>
                                    <td>
                                        @if(!empty($model->message->title))
                                            {!! \Illuminate\Support\Str::words($model->message->title??'', 5, '...'); !!}
                                        @else
                                            <i class="text-muted text-sm">(Mavzu yo`q)</i>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($model->status)
                                            @case(-1)
                                            <span class="label label-danger">@lang('blade.canceled')</span>
                                            @break
                                            @case(0)
                                            <span class="label label-warning" style = "text-transform:capitalize;">@lang('blade.new')</span>
                                            <a href="javascript:void(0)" class="edit-task" data-id="{{ $model->id }}">
                                                <i class="glyphicon glyphicon-trash text-maroon"></i>
                                            </a>
                                            @break
                                            @case(1)
                                            <span class="label label-primary">@lang('blade.on_process')</span>
                                            @break
                                            @case(2)
                                            <span class="label label-success">@lang('blade.in_execution')</span>
                                            @break
                                            @case(3)
                                            <span class="label label-default">@lang('blade.closed')</span>
                                            @break
                                            @default
                                            @lang('blade.not_detected')
                                        @endswitch

                                        @if($model->message->urgent == 1)
                                            <sup><i class="fa fa-bell-o text-red fa-lg"></i></sup>
                                        @endif
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
                                    </td>
                                    <td style="min-width: 190px">
                                        {{ \Carbon\Carbon::parse($model->created_at)->format('d-M-Y H:i')  }}
                                        <span class="text-maroon"> ({{$model->created_at->diffForHumans()}})</span>
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

        <div id="trashModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-red-active">
                        <h4 class="modal-title"><i class="fa fa-trash"></i> O`chirish</h4>
                    </div>
                    <div class="modal-body">
                        <p class="text-left">Sizning xatingiz muvaffaqiyatli o`chirildi</p>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-default" data-dismiss="modal">@lang('blade.close')</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- jQuery 2.2.3 -->
        <script src="{{ asset ("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>
        <!-- AdminLTE for demo purposes -->
        <script>


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

                $('body').on('click', '.edit-task', function () {

                    var task_id = $(this).data("id");
                    var tr = confirm("Are You sure want to delete !");

                    if (tr){
                        $.ajax({
                            type: "GET",
                            url: "{{ url('helper-journal-delete')}}"+'/'+task_id,
                            success: function (data) {
                                $("#post_id" + task_id).remove();
                                $('#trashModal').modal('show');
                            },
                            error: function (data) {
                                console.log('Error:', data);
                            }
                        });
                    }
                   /* var model_id = $(this).data('id');
                    $.get('/office-journal-edit/'+model_id, function (data) {
                        $('#postCrudModal').html("Edit Journal number");
                        $('#btn-save').val("edit-post");
                        $('#ajax-crud-modal').modal('show');
                        $('#model_id').val(model_id);
                        $('#in_number').val(data.journal_number);
                        $('#in_number_a').val(data.journal_number_a);

                    })*/
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
            $(function () {
                $("#example1").DataTable();
            });

            // close Modal
            $('.closeModal').click(function () {

                $('#myModal').hide();

            });
        </script>
    </section>
    <!-- /.content -->
@endsection
