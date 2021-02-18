@extends('layouts.edo.dashboard')

@section('content')

    <!-- NOT CLARIFIED  -->

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            EDO Type Message
            <small>jadval</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Bosh sahifa</a></li>
            <li><a href="#">Type</a></li>
            <li class="active">message jadvali</li>
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
                            <a href="{{ route('edo-type-messages.create') }}" class="btn btn-primary">
                                <i class="fa fa-plus"></i> Type Message create</a>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Title Ru</th>
                                <th>Type Code</th>
                                <th>Type Message Code</th>
                                <th>Sort</th>
                                <th><i class="fa fa-pencil-square-o"></i></th>
                                <th><i class="fa fa-trash-o"></i></th>
                            </tr>
                            </thead>
                            <tbody>
                           <?php $i = 1 ?>
                            @foreach ($models as $key => $model)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $model->title }}</td>
                                    <td>{{ $model->title_ru }}</td>
                                    <td>{{ $model->roles->title_ru }}</td>
                                    <td>{{ $model->type_message_code }}</td>
                                    <td>{{ $model->sort }}</td>
                                    <td>
                                        <a class="btn btn-primary" href="{{ route('edo-type-messages.edit', $model->id) }}"><i class="fa fa-pencil"></i></a>
                                    </td>
                                    <td>
                                        <form action="{{ url('edo-type-messages/'.$model->id) }}" method="POST" style="display: inline-block">
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
