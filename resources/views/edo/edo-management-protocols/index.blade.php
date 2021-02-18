@extends('layouts.edo.dashboard')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            EDO Management Users
            <small>jadval</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Bosh sahifa</a></li>
            <li><a href="#">Users</a></li>
            <li class="active">users jadvali</li>
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

                <div class="box">
                    <div class="box-header">
                        <div class="col-md-1">
                            <a href="{{ route('edo-management-protocols.create') }}" class="btn btn-primary">
                                <i class="fa fa-plus"></i> User create</a>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>full name</th>
                                <th>position</th>
                                <th>qr_name</th>
                                <th>qr_hash</th>
                                <th>qr_file</th>
                                <th>title</th>
                                <th>sort</th>
                                <th>status</th>
                                <th><i class="fa fa-pencil-square-o"></i></th>
                                <th><i class="fa fa-trash-o"></i></th>
                            </tr>
                            </thead>
                            <tbody>
                           <?php $i = 1 ?>
                            @foreach ($models as $key => $model)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $model->user->lname.' '.$model->user->fname }}</td>
                                    <td>{{ $model->user->job_title }} <sup>{{ $model->user->branch_code }}</sup></td>
                                    <td>
                                        <a href="{{ route('qr-account',
                                            ['name' => $model->qr_name,
                                            'hash' => $model->qr_hash]) }}">
                                            {{ $model->qr_name }}
                                        </a>
                                    </td>
                                    <td>{{ $model->qr_hash }}</td>
                                    <td>
                                        <div class="user-block">
                                            @if(!empty($model->qr_file))
                                                <img class="img-rounded" src="/FilesQR/{{ $model->qr_file }}" alt="User QR">
                                            @else
                                                <img class="img-circle" src="/admin-lte/dist/img/user.png" alt="User QR">
                                            @endif
                                        </div>

                                    </td>
                                    <td>{{ $model->title }}</td>
                                    <td class="text-center">{{ $model->user_sort }}</td>
                                    <td>
                                        @if($model->status == 1)
                                        <i class="fa fa-check-circle-o text-green"></i> <sup>{{ $model->status }}</sup></td>
                                    @else
                                        <i class="fa fa-ban text-red"></i> <sup>{{ $model->status }}</sup>
                                    @endif
                                    <td>
                                        <a class="btn btn-primary" href="{{ route('edo-management-protocols.edit', $model->id) }}">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <form action="{{ url('edo-management-protocols/'.$model->id) }}" method="POST" style="display: inline-block">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}

                                            <button type="submit" id="delete-group-{{ $model->id }}" class="btn btn-danger">
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
    </section>
    <!-- /.content -->
    <!-- /.content -->
    <!-- jQuery 2.2.3 -->
    <script src="admin-lte/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="admin-lte/bootstrap/js/bootstrap.min.js"></script>
    <!-- DataTables -->
    <script src="admin-lte/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="admin-lte/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <!-- AdminLTE App -->
    <script src="admin-lte/dist/js/app.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script>
        $(function () {
            $("#example1").DataTable();
        });
    </script>
@endsection
