@extends('layouts.edo.dashboard')

@section('content')

    <!-- TRANSLATED -->

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang('blade.reg_journal')
            <small>@lang('blade.groups_table')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home_page')</a></li>
            <li><a href="#">@lang('blade.doc')</a></li>
            <li class="active">@lang('blade.groups_table')</li>
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
                    <div class="box-body">
                        <form action="{{route('d-tasks-journal')}}" method="POST" role="search">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group has-success">
                                        <input type="text" class="form-control" name="search_t" value="{{ $search_t??'' }}"
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
                                        <a href="{{ route('d-tasks-journal') }}" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> @lang('blade.reset')</a>
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
                        <b>@lang('blade.overall'){{': '. $models->total()}} @lang('blade.group_edit_count').</b>
                        <table class="table table-hover table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Деп №</th>
                                <th>@lang('blade.to_whom')</th>
                                <th>@lang('blade.office')</th>
                                <th>Канс №</th>
                                <th>@lang('blade.sender_organization')</th>
                                <th>Исх. №</th>
                                <th>@lang('blade.doc_name')</th>
                                <th>@lang('blade.to_whom')</th>
                                <th>@lang('blade.received_date')</th>
                            </tr>
                            </thead>
                            <tbody>
                           <?php $i = 1; $key = 1; ?>
                           @if($models->count())
                            @foreach ($models as $key => $model)
                                <tr>
                                    <td>{{ $models->firstItem() + $key }} </td>
                                    <td class="text-bold text-left" style="min-width: 90px">
                                        <span id="post_id_{{ $model->id }}">{{ $model->in_number.$model->in_number_a  }} </span>

                                        <a href="javascript:void(0)" id="edit-post" data-id="{{ $model->id }}"> <i
                                                    class="fa fa-pencil text-green"></i></a>
                                    </td>
                                    <td class="text-green">
                                        {{ mb_substr($model->director->fname??'', 0 ,1).'.'.
                                           $model->director->lname??'' }}
                                    </td>
                                    
                                    <td class="text-maroon">
                                        {{ mb_substr($model->user->fname??'', 0 ,1).'.'.
                                           $model->user->lname??'' }}
                                    </td>

                                    <td class="text-sm text-center">
                                        <b>{{ $model->message->in_number??''}}</b><br>
                                        {{ \Carbon\Carbon::parse($model->message->in_date??'')->format('d.m.Y')}}
                                    </td>

                                    <td>
                                        @if(!count($model->edoMessageUsers))
                                            <a href="{{ route('view-depart-d-task',
                                                ['id' => $model->edo_message_id,
                                                'slug' => $model->message->message_hash??'']) }}">
                                                {!! \Illuminate\Support\Str::words($model->from_name, 5, '...'); !!}
                                            </a>
                                        @endif
                                        @foreach($model->edoMessageUsers as $value)
                                            @if($value->depart_id == Auth::user()->department->depart_id)
                                                <a href="{{ route('view-d-task-process',
                                                    ['id' => $value->id??'Null',
                                                    'slug' => $model->message->message_hash??'']) }}">
                                                    {!! \Illuminate\Support\Str::words($model->from_name, 5, '...'); !!}
                                                </a>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td class="text-sm text-center">
                                        <b>{{ $model->message->out_number??''}}</b><br>
                                        {{ \Carbon\Carbon::parse($model->message->out_date??'')->format('d.m.Y')}}
                                    </td>
                                    
                                    <td>
                                        @if(!empty($model->title))
                                            {!! \Illuminate\Support\Str::words(($model->title ?? ''), 8, '...'); !!}
                                        @else
                                            <i class="text-muted text-sm">(Mavzu yo`q)</i>
                                        @endif
                                    </td>
                                    <td class="text-maroon" style="min-width: 180px">
                                        @if(count($model->subUsers))
                                            @foreach($model->subUsers as $key => $user)
                                                @php($userName = \App\User::find($user->to_user_id ?? 'null'))
                                                @if($key < 4 )
                                                    {{ mb_substr($userName->fname ?? '', 0,1).'.'.mb_substr($userName->sname ?? '', 0,1).'.'.mb_scrub($userName->lname ?? 'null2') }}
                                                    @if($user->is_read == 1)
                                                        @if($user->status == 0)
                                                            <i class="fa fa-check-square text-primary"></i>
                                                        @elseif($user->status == 1)
                                                            <i id="div1"
                                                               class="fa fa-envelope-o text-aqua text-bold"></i>
                                                        @else
                                                            <i class="fa fa-check-square text-primary"></i>
                                                        @endif
                                                    @else
                                                        <i class="fa fa-check text-red"></i>
                                                    @endif
                                                    <br/>
                                                @endif
                                            @endforeach
                                            @if(count($model->perfSubUsers) > 4)
                                                <span class="text-primary text-bold">+{{count($model->perfSubUsers)-4}}</span>
                                            @endif
                                        @else
                                            <i class="text-muted text-center text-sm">(Fishka yo`q)</i>
                                        @endif
                                    </td>
                                    <td style="min-width: 190px">
                                        {{ \Carbon\Carbon::parse($model->created_at)->format('d-M-Y H:i')  }}<br>
                                        <span class="text-maroon"> ({{$model->created_at?$model->created_at->diffForHumans():''}})</span>
                                    </td>
                                </tr>
                            @endforeach
                           @else
                               <td class="text-red text-center"><i class="fa fa-search"></i> <b>@lang('blade.not_found')</b></td>
                           @endif
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

        <div class="modal fade modal-primary" id="ajax-crud-modal" aria-hidden="true">
            <div class="modal-dialog modal-sm">
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

        <!-- jQuery 2.2.3 -->
        <script src="{{ asset ("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="{{ asset ("/js/jquery.validate.js") }}"></script>
        <script src="{{ asset("/admin-lte/plugins/daterangepicker/moment.js") }}"></script>
        <script src="{{ asset("/admin-lte/plugins/daterangepicker/moment.min.js") }}"></script>
        <script src="{{ asset("/admin-lte/plugins/daterangepicker/daterangepicker.js") }}"></script>
        <script src="{{ asset ("/admin-lte/bootstrap/js/bootstrap-datepicker.js") }}"></script>
        <script src="{{ asset("/admin-lte/plugins/select2/select2.full.min.js") }}"></script>


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

                $('body').on('click', '#edit-post', function () {
                    var model_id = $(this).data('id');
                    $.get('/d-office-journal-edit/'+model_id, function (data) {
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

            if ($("#postForm").length > 0) {
                $("#postForm").validate({

                    submitHandler: function(form) {

                        var actionType = $('#btn-save').val();
                        $('#btn-save').html('Sending..');


                        $.ajax({
                            data: $('#postForm').serialize(),
                            url: "{{ route('d-office-journal-post') }}",
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
