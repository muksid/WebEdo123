@extends('layouts.edo.dashboard')

@section('content')

    <!-- Select2 -->
    <link href="{{ asset("/admin-lte/plugins/select2/select2.min.css") }}" rel="stylesheet" type="text/css">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-sm-12">
                <div class="col-sm-2">
                    <h3 style="margin-top: 0">
                        @switch(Auth::user()->department->depart_id)
                            @case(3)
                                @lang('blade.protocol_management')
                                @break
                            @case(11)
                                @lang('blade.hr_orders')
                                @break
                            @case(20)
                                @lang('blade.kazna_protocols')
                                @break
                            @case(24)
                                @lang('blade.strategy_orders')
                                @break
                            @default
                                @break
                        @endswitch

                        <small>@lang('blade.groups_table')</small>
                    </h3> 
                </div>
                <div class="col-sm-1" align="center">
                    <form action="{{ url('/edo/staff-protocols') }}" method="post">
                        @csrf
                        <input id="new" type="text" name="type" value="new" hidden />
                        <button type="submit" class="btn btn-danger"> @lang('blade.new') [{{ $new_count??'' }}] </button>
                    </form>
                </div>
                <div class="col-sm-2" align="center">
                    <form action="{{ url('/edo/staff-protocols') }}" method="post">
                        @csrf
                        <input id="on_process" type="text" name="type" value="on_process" hidden />
                        <button type="submit" class="btn btn-warning"> @lang('blade.on_process') [{{ $on_process_count??'' }}] </button>
                    </form>
                </div>
                <div class="col-sm-1" align="center">
                    <form action="{{ url('/edo/staff-protocols') }}" method="post">
                        @csrf
                        <input id="archive" type="text" name="type" value="archive" hidden />
                        <button type="submit" class="btn btn-success"> @lang('blade.archive') [{{ $archive_count??'' }}] </button>
                    </form>
                </div>
                
            </div>
        </div>



        <!-- <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home')</a></li>
            <li><a href="#">@lang('blade.hr_orders')</a></li>
            <li class="active">@lang('blade.hr_orders')</li>
        </ol> -->
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

                <div class="box box-primary" style="clear: both;">

                    <div class="box-header with-border">
                        <div class="col-md-2">
                            @if($memberStatus == 0)
                            <a href="{{ url('/edo/create-staff-protocol') }}" class="btn btn-flat btn-primary">
                                <i class="fa fa-plus"></i> @lang('blade.create_doc')</a>
                            @endif
                        </div>

                        <div class="col-md-8">
                            <form action="{{ url('/edo/staff-protocols') }}" method="POST" role="search">
                                @csrf
                                <div class="row">

                                    <input type="text" name="type" id="" value="{{ $type??'' }}" hidden>
                                    <div class="col-md-2">
                                        <div class="form-group has-success">
                                            <input type="text" class="form-control" name="reg_num" value="{{ $reg_num??'' }}" placeholder="@lang('blade.reg_num')">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group has-success">
                                            <input type="text" class="form-control" name="title" value="{{ $title??'' }}" placeholder="@lang('blade.doc_name')">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group has-success">
                                            <input type="date" class="form-control" name="date" value="{{ $date??'' }}" placeholder="@lang('blade.reg_date_only')">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <a href="{{ url('/edo/staff-protocols') }}" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> @lang('blade.reset')</a>
                                            <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-search"></i> @lang('blade.search')</button>
                                        </div>
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->
                            </form>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('blade.from_whom')</th>
                                <th>@lang('blade.reg')</th>
                                <th>@lang('blade.doc_name')</th>
                                <th>
                                @switch(Auth::user()->department->depart_id)
                                    @case(3)
                                    @case(11)
                                        @lang('blade.management_members')
                                        @break
                                    @case(20)
                                    @case(24)
                                        @lang('blade.committe_members')
                                        @break
                                    @default
                                        @lang('blade.members')
                                        @break
                                @endswitch
                                </th>
                                <th>@lang('blade.status')</th>
                                <th>@lang('blade.date')</th>
                                <th><i class="fa fa-pencil-square-o"></i></th>
                            </tr>
                            </thead>
                            <tbody>
                           <?php $i = 1 ?>
                            @foreach ($models as $key => $model)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td class="text-green">
                                        {{ mb_substr($model->user->fname ?? '', 0,1).'.'.mb_scrub($model->user->lname ?? '') }}
                                    </td>
                                    <td class="text-bold text-center">
                                        @if($model->status == 1)
                                            <span class="text-center text-muted text-sm">(raqam/sana)</span>
                                        @else
                                            <span id="post_id_{{ $model->id }}">
                                            {{ $model->stf_number }}<br>
                                            @if(!empty($model->stf_date))
                                                    {{ \Carbon\Carbon::parse($model->stf_date??'')->format('d.m.Y') }}
                                                @endif
                                        </span><br>

                                            <a href="javascript:void(0)" id="edit-post" data-id="{{ $model->id }}"
                                               class="btn btn-flat btn-xs btn-primary">
                                                <i class="fa fa-refresh"></i></a>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('view-stf-protocol',
                                            ['id' => $model->id,
                                            'hash' => $model->protocol_hash]) }}">
                                            {{ $model->title }}
                                        </a>
                                    </td>
                                    <td class="text-maroon" style="min-width: 120px">
                                        @if($model->status == 1)
                                            <span class="text-center text-muted text-sm">(boshqaruv a`zolari)</span>
                                        @else
                                            @foreach($model->members as $key => $user)
                                                {{ $user->user->substrUserName($user->user_id) }}
                                                @if($user->status == 0)
                                                    <i class="fa fa-ban text-yellow"></i>
                                                @elseif($user->status == -1)
                                                    <i class="fa fa-ban text-yellow"></i>
                                                @elseif($user->status == 1)
                                                    <i class="fa fa-check text-red"></i>
                                                @elseif($user->status == 2)
                                                    <span class="fa fa-qrcode text-black"></span>
                                                @else
                                                    <i class="fa fa-check-square text-primary"></i>
                                                @endif
                                                <br>
                                            @endforeach

                                        @endif

                                    </td>
                                    <td>
                                        @if($model->status == 1)
                                            <span class="label label-danger">@lang('blade.new')</span>
                                        @elseif($model->status == -1)
                                            <span class="label label-warning">@lang('blade.cancel')</span>
                                            <br>
                                            @if($model->comments)
                                                <span class="text-muted">{{ $model->comments }}</span>
                                            @endif
                                        @elseif($model->status == 2)
                                            <span class="label label-primary">@lang('blade.on_process')</span>
                                        @elseif($model->status == 3)
                                            <span class="label label-success">Tasdiqlangan</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($model->created_at??'')->format('d.m.Y H:i') }}</td>
                                    <td>
                                        @if($memberStatus == 0)
                                            <a href="{{ route('edit-stf-protocol',
                                            ['id' => $model->id,
                                            'hash' => $model->protocol_hash]) }}" class="btn btn-info btn-flat">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        @endif
                                        @if($model->status == 1 || $model->status == -1)
                                            <a href="{{ route('delete-stf-protocol', ['id' => $model->id ] ) }}" class="btn btn-danger btn-flat">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $models->links() }}
                    <!-- /.box-body -->

                    <div class="modal fade modal-info" id="ajax-crud-modal" aria-hidden="true">
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
                                            <input type="text" class="form-control" style="width: 50%" id="stf_number"
                                                   name="stf_number" value="" required="">
                                        </div>

                                        <div class="form-group">
                                            <label>@lang('blade.incoming_date')</label><sup class="text-red"> *</sup>

                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <div class="input-group input-daterange">
                                                    <input type="text" name="stf_date" class="form-control" id="stf_date" value="" placeholder="date" required />
                                                </div>
                                            </div>
                                            <!-- /.input group -->
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
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- jQuery 2.2.3 -->
        <script src="{{ asset ("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

        <link href="{{ asset ("/admin-lte/bootstrap/css/bootstrap-datepicker.css") }}" rel="stylesheet" />
        <script src="{{ asset ("/admin-lte/bootstrap/js/bootstrap-datepicker.js") }}"></script>

        <script src="{{ asset ("/js/jquery.validate.js") }}"></script>
        <script>
            $(function () {
                $("#example1").DataTable();
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
                    $.get('/stf-number-edit/'+model_id, function (data) {
                        $('#postCrudModal').html("Protocol Number Date");
                        $('#btn-save').val("edit-post");
                        $('#ajax-crud-modal').modal('show');
                        $('#model_id').val(model_id);
                        $('#stf_number').val(data.stf_number);
                        $('#stf_date').val(data.stf_date);

                    })
                });

            });

            if ($("#postForm").length > 0) {
                $("#postForm").validate({

                    submitHandler: function(form) {

                        var actionType = $('#btn-save').val();
                        $('#btn-save').html('Sending..');


                        $.ajax({
                            data: $('#postForm').serialize(),
                            url: "{{ route('stf-number-post') }}",
                            type: "POST",
                            dataType: 'json',
                            success: function (data) {
                                var stfData = data.stf_number+"<br>"+data.stf_date;

                                if (actionType == "create-post") {

                                    $('#posts-crud').prepend(post);

                                } else {

                                    $("#post_id_" + data.id).replaceWith(stfData);

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
