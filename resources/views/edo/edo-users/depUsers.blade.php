@extends('layouts.edo.dashboard')

@section('content')

    <section class="content-header">
        <h1>
            @lang('blade.sidebar_edo') @lang('blade.users')
            <small>@lang('blade.groups_table')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home_page')</a></li>
            <li><a href="#">@lang('blade.users')</a></li>
            <li class="active">@lang('blade.sidebar_edo') @lang('blade.users')</li>
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

                <div class="box">
                    <div class="box-header">
                        <div class="col-md-1">
                            <a href="javascript:void(0)" id="create-d-user" class="btn btn-primary">
                                <i class="fa fa-plus"></i> @lang('blade.user_create')</a>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('blade.full_name')</th>
                                <th>@lang('blade.dep')</th>
                                <th>@lang('blade.status')</th>
                                {{--<th><i class="fa fa-pencil-square-o"></i></th>--}}
                                <th><i class="fa fa-trash-o"></i></th>
                            </tr>
                            </thead>
                            <tbody id="users-crud">
                           <?php $i = 1 ?>
                            @foreach ($models as $key => $model)
                                <tr id="user_id_{{ $model->id }}">
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $model->user->lname.' '.$model->user->fname }}</td>
                                    <td>{{ $model->user->job_title }} <sup>{{ $model->user->branch_code }}</sup></td>
                                    <td>
                                        @if($model->status == 1)
                                        <i class="fa fa-check-circle-o text-green"></i> <sup>{{ $model->status }}</sup></td>
                                    @else
                                        <i class="fa fa-ban text-red"></i> <sup>{{ $model->status }}</sup>
                                    @endif
                                    {{--<td>
                                        <a href="javascript:void(0)" id="edit-user-none" data-id="{{ $model->id }}"
                                           class="btn btn-info"><i class="fa fa-edit"></i>
                                        </a>
                                    </td>--}}
                                    <td>
                                        <a href="javascript:void(0)" id="delete-user" data-id="{{ $model->id }}"
                                           class="btn btn-danger delete-user"><i class="fa fa-trash"></i>
                                        </a>
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
        <div class="modal fade" id="ajax-crud-modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="userCrudModal"></h4>
                    </div>
                    <div class="modal-body">
                        <form id="userForm" name="userForm" class="form-horizontal">
                            <input type="hidden" name="role_id" id="role_id" value="8">
                            <input type="hidden" name="department_id" id="department_id" value="{{Auth::user()->depart_id}}">
                            <input type="hidden" name="sort" id="sort" value="1">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label class="control-label">@lang('blade.user')</label>
                                            <select name="user_id" id="user_id" class="form-control">
                                                <option selected="selected">@lang('blade.select_users')</option>
                                                @foreach($users as $key => $value)
                                                    <option value="{{ $value->id }}">
                                                        {{ $value->full_name.' - '.$value->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label class="control-label">@lang('blade.status')</label>
                                            <select name="status" id="status" class="form-control">
                                                <option value="1">Active</option>
                                                <option value="0">Passive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary" id="btn-save" value="create">@lang('blade.save')
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>
        <!-- jQuery 2.2.3 -->
        <script src="/admin-lte/plugins/jQuery/jquery-2.2.3.min.js"></script>
        <!-- Bootstrap 3.3.6 -->
        <script src="/admin-lte/bootstrap/js/bootstrap.min.js"></script>
        <!-- DataTables -->
        <script src="/admin-lte/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="/admin-lte/plugins/datatables/dataTables.bootstrap.min.js"></script>
        <!-- AdminLTE App -->
        <script src="/admin-lte/dist/js/app.min.js"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="/js/jquery.validate.js"></script>

        <script>
            $(document).ready(function () {

                $.ajaxSetup({
                    headers:
                        {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                });

                $('#create-d-user').click(function () {

                    $('#btn-save').val("create-user");

                    $('#userForm').trigger("reset");

                    $('#userCrudModal').html("Add User");

                    $('#ajax-crud-modal').modal('show');

                });

                $('body').on('click', '#edit-user', function () {

                    var user_id = $(this).data('id');

                    $.get('/d-user-edit/'+user_id, function (data) {

                        $('#userCrudModal').html("Edit User");

                        $('#btn-save').val("edit-user");

                        $('#ajax-crud-modal').modal('show');

                        $('#user_id').val(data.lname+' '+data.fname);

                        $('#status').val(data.status);

                        $('#sort').val(data.sort);

                    });
                });

                $('body').on('click', '.delete-user', function () {

                    var user_id = $(this).data("id");

                    confirm("Are You sure want to delete !");

                    $.ajax({

                        url: "{{ url('d-user-delete')}}"+'/'+user_id,

                        type: "POST",

                        dataType: 'json',

                        success: function (data)
                        {
                            $("#user_id_" + user_id).remove();
                        },
                        error: function (data)
                        {
                            console.log('Error:', data);
                        }
                    });
                });
            });

            if ($("#userForm").length > 0) {

                $("#userForm").validate({

                    submitHandler: function(form) {

                        var actionType = $('#btn-save').val();

                        $('#btn-save').html('Sending..');

                        $.ajax({
                            data: $('#userForm').serialize(),
                            url: "{{ route('d-user-post') }}",
                            type: "POST",
                            dataType: 'json',
                            success: function (data) {
                                //console.log(data);
                                var user = '' +
                                    '<tr id="user_id_' + data.id + '">' +
                                        '<td>' + data.id + '</td>' +
                                        '<td>' + data.full_name + '</td>' +
                                        '<td>department name </td>' +
                                        '<td>' + data.status + '</td>';
                                    /*user += '' +
                                        '<td>' +
                                            '<a href="javascript:void(0)" id="edit-user" data-id="' + data.id +
                                            '" class="btn btn-info"><i class="fa fa-edit"></i></a>' +
                                        '</td>';*/
                                    user +=
                                        '<td>' +
                                            '<a href="javascript:void(0)" id="delete-user" data-id="' + data.id +
                                            '" class="btn btn-danger delete-post"><i class="fa fa-trash"></i></a>' +
                                        '</td>' +
                                    '</tr>';


                                if (actionType == "create-user")
                                {
                                    $('#users-crud').prepend(user);
                                }
                                else
                                {
                                    $("#user_id_" + data.id).replaceWith(user);
                                }

                                $('#userForm').trigger("reset");

                                $('#ajax-crud-modal').modal('hide');

                                $('#btn-save').html('Save Changes');

                            },
                            error: function (data) {
                                //console.log(data);
                                $('#btn-save').html('Save Changes');
                            }
                        });
                    }
                });
            }

            // for table
            $(function () {
                $("#example1").DataTable();
            });

        </script>

    </section>
    <!-- /.content -->
@endsection
