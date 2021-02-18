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

                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-hover table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('blade.sender_organization')</th>
                                <th>@lang('blade.received_num')</th>
                                <th>@lang('blade.outgoing_num')</th>
                                <th>@lang('blade.doc_name')</th>
                                <th>@lang('blade.send_date')</th>
                                <th>@lang('blade.status')</th>
                                <th>@lang('blade.type_of_doc')</th>
                            </tr>
                            </thead>
                            <tbody>
                           <?php $i = 1 ?>
                            @foreach ($models as $key => $model)
                                <tr href="{{ url('view-helper', $model->id) }}" class="tr-cursor">

                                    <td>{{ $i++ }}</td>
                                    <td>{{ $model->message->from_name }}</td>
                                    <td>{{ $model->message->in_number.' dan ' .\Carbon\Carbon::parse($model->message->in_date)->format('d M, Y') }}</td>
                                    <td>{{ $model->message->out_number.' dan ' .\Carbon\Carbon::parse($model->message->out_date)->format('d M, Y') }}</td>
                                    <td>{{ $model->message->title }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($model->created_at)->format('d M,y H:i') }}
                                    </td>
                                    <td>
                                        @switch($model->status)
                                            @case(0)
                                            <span class="label label-warning" style = "text-transform:capitalize;">@lang('blade.new')</span>
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

                                    </td>
                                    <td>{{ $model->messageType->title }}</td>
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
            $(document).ready(function(){
                $('table tr').click(function(){
                    window.location = $(this).attr('href');
                    return false;
                });
            });
        </script>
    </section>
    <!-- /.content -->
@endsection
