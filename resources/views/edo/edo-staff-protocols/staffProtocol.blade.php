@extends('layouts.edo.dashboard')

@section('content')

    <!-- Select2 -->
    <link href="{{ asset("/admin-lte/plugins/select2/select2.min.css") }}" rel="stylesheet" type="text/css">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang('blade.hr_orders')
            <small>jadval</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home')</a></li>
            <li><a href="#">@lang('blade.hr_orders')</a></li>
            <li class="active">@lang('blade.hr_orders')</li>
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

                <div class="box box-primary" style="clear: both;">

                    <div class="box-header with-border">
                        <div class="col-md-1">
                            @if($memberStatus == 0)
                            <a href="{{ url('/edo/create-staff-protocol') }}" class="btn btn-flat btn-primary">
                                <i class="fa fa-plus"></i> @lang('blade.create_doc')</a>
                            @endif
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('blade.from_whom')</th>
                                <!-- <th>@lang('blade.dep_staff')</th> -->
                                <th>@lang('blade.reg')</th>
                                <th>@lang('blade.doc_name')</th>
                                <th>@lang('blade.management_members')</th>
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
                                    <!-- <td style="max-width: 150px;">
                                        @if($model->to_user_id)
                                            {{ $model->userEmp->lname??''}} {{ $model->userEmp->fname??'' }}
                                            <br>
                                            <span class="text-sm text-muted">
                                        
                                            <i class="fa fa-bank"></i> 

                                            {{ $model->userEmp->department->title ?? '' }} - {{ $model->userEmp->job_title??'' }}
                                        
                                            </span>
                                        @else
                                            <span class="text-sm text-muted">
                                                None
                                            </span>
                                        @endif

                                    </td> -->
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
                                                @if($key < 2 )
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
                                                @endif

                                            @endforeach

                                            @if(count($model->members) > 2)
                                                <span class="text-primary text-bold">+{{count($model->members)-2}}</span>
                                            @endif
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
