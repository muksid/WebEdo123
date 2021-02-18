@extends('layouts.edo.dashboard')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang('blade.of_orders')
            <small>jadval</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home')</a></li>
            <li><a href="#">@lang('blade.of_orders')</a></li>
            <li class="active">@lang('blade.of_orders')</li>
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
                            <a href="{{ url('/edo/create-protocol') }}" class="btn btn-flat btn-primary">
                                <i class="fa fa-plus"></i> @lang('blade.create_doc')</a>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('blade.from_whom')</th>
                                <th>@lang('blade.receivers')</th>
                                <th>@lang('blade.doc_name')</th>
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
                                    <td class="text-green">{{ $model->user->lname.' '.$model->user->fname }}</td>
                                    <td class="text-maroon" style="min-width: 180px">
                                        @foreach($model->members as $key => $user)

                                            @php($userName = \App\User::find($user->user_id ?? 'null'))

                                                {{ mb_substr($userName->fname ?? '', 0,1).'.'.mb_substr($userName->sname ?? '', 0,1).'.'.mb_scrub($userName->lname ?? 'null2') }}

                                                    @if($user->status == 0)
                                                        <i class="fa fa-ban text-yellow"></i>
                                                    @elseif($user->status == 1)
                                                        <i class="fa fa-check text-red"></i>
                                                    @elseif($user->status == 2)
                                                        <span class="fa fa-check text-green"></span>
                                                    @elseif($user->status == 3)
                                                <span class="glyphicon glyphicon-qrcode bg-gray"></span>
                                                    @else
                                                        <i class="fa fa-check-square text-primary"></i>
                                                    @endif
                                                <br>
                                        @endforeach

                                    </td>
                                    <td>
                                        <a href="{{ route('view-protocol',
                                            ['id' => $model->id,
                                            'hash' => $model->protocol_hash]) }}">
                                            {{ $model->title }}
                                        </a>
                                    </td>
                                    <td>
                                        @if($model->status == 0)
                                            <span class="label label-warning">@lang('blade.rejected_task')</span>
                                        @elseif($model->status == 1)
                                            <span class="label label-danger">@lang('blade.new')</span>
                                        @elseif($model->status == 2)
                                            <span class="label label-primary">@lang('blade.on_process')</span>
                                        @elseif($model->status == 3)
                                            <span class="label label-success">Tasdiqlangan</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($model->created_at??'')->format('d-m-Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('edit-protocol',
                                            ['id' => $model->id,
                                            'hash' => $model->protocol_hash]) }}" class="btn btn-info btn-flat">
                                            <i class="fa fa-pencil"></i>
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
