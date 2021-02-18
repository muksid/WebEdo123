@extends('layouts.dashboard')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Yuborilgan
            <small>jadval</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Bosh sahifa</a></li>
            <li><a href="#">hujjat</a></li>
            <li class="active">jadvali</li>
        </ol>

        <!-- Message Succes -->
        @if ($message = Session::get('success'))
            <div id="myModal" class="modal fade in" style="display: block">
                <div class="modal-default">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close closeModal" data-dismiss="modal" aria-label=""><span>×</span></button>
                                </div>
                                <div class="modal-body">
                                    <div class="thank-you-pop">
                                        <img src="/admin-lte/dist/img/success.png" alt="Success">
                                        <p>{{ $message }}</p>
                                    </div>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.example-modal -->
        @endif
    <!-- Display Validation Errors -->
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Xatolik!</strong> Ma`lumotlarni qaytadan tekshiring.<br><br>
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
                        <table id="example1" class="table table-hover table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Account ID</th>
                                <th>Filial</th>
                                <th>Tashkilot nomi</th>
                                <th>INN (Tashkilot)</th>
                                <th>FISH (Rahbar)</th>
                                <th>Telefon (Rahbar)</th>
                                <th>Holati</th>
                                <th>Reg sanasi</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1 ?>
                            @foreach ($models as $key => $model)
                                @if($model->account->accFilial->branch_code == Auth::user()->branch_code)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $model->account_code }}</td>
                                    <td>{{ $model->account->accFilial->branch_code.' - '.$model->account->accFilial->title }}</td>
                                    <td>
                                        <a href="{{ url('/acc-b', ['id' => $model->account->acc_gen]) }}">
                                            {{ $model->account->acc_name }}
                                        </a>
                                    </td>
                                    <td>{{ $model->account_inn }}</td>
                                    <td>{{ $model->account->owner_lname.' '.$model->account->owner_fname }}</td>
                                    <td>{{ $model->account->owner_phone }}</td>
                                    <td>
                                       @switch($model->status)
                                            @case(1)
                                            <span class="label label-danger">Yangi</span>
                                            @break
                                            @case(2)
                                            <span class="label label-success">Jarayonda</span>
                                            @break
                                            @default
                                            not
                                        @endswitch
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($model->created_at)->format('d M. Y H:i') }}</td>
                                </tr>
                                @endif
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
        <script src="{{ asset ("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

        <script>

            $(function () {
                $("#example1").DataTable();
            });

            // close Modal
            $('.closeModal').click(function () {

                $('#myModal').hide();

            });
        </script>

        <!-- jQuery 2.2.3 -->
        <script src="{{ asset ("admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

        <!-- DataTables -->
        <script src="{{ asset ("admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>

        <script src="{{ asset ("admin-lte/plugins/datatables/dataTables.bootstrap.min.js") }}"></script>

        <!-- AdminLTE App -->
        <script src="{{ asset("/admin-lte/dist/js/app.min.js") }}"></script>
    </section>
    <!-- /.content -->
@endsection
