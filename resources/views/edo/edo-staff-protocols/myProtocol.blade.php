@extends('layouts.edo.dashboard')

@section('content')

    <!-- Select2 -->
    <link href="{{ asset("/admin-lte/plugins/select2/select2.min.css") }}" rel="stylesheet" type="text/css">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang('blade.my_orders')
            <small>jadval</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home')</a></li>
            <li><a href="#">@lang('blade.my_orders')</a></li>
            <li class="active">@lang('blade.my_orders')</li>
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
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('blade.from_whom')</th>
                                <th>@lang('blade.doc_name')</th>
                                <th>@lang('blade.management_members')</th>
                                <th>@lang('blade.status')</th>
                                <th>@lang('blade.date')</th>
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
                                    <td>
                                        <a href="{{ route('view-my-stf-protocol',
                                            ['id' => $model->id,
                                            'hash' => $model->protocol_hash]) }}">
                                            {{ $model->title }}
                                        </a>
                                    </td>
                                    <td class="text-maroon" style="min-width: 120px">
                                        @foreach($model->members as $key => $user)
                                                {{ $user->user->substrUserName($user->user_id) }}
                                                @if($user->status == 0)
                                                    <i class="fa fa-check text-yellow"></i>
                                                @elseif($user->status == -1)
                                                    <i class="fa fa-ban text-yellow"></i> {{ $user->descr }}
                                                @elseif($user->status == 2)
                                                    <span class="fa fa-qrcode text-black"></span>
                                                @elseif($user->status == 3)
                                                    <span class="glyphicon glyphicon-qrcode bg-gray"></span>
                                                @else
                                                    <i class="fa fa-check text-red"></i>
                                                @endif
                                                <br>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if($model->status == 0)
                                            <span class="label label-warning">@lang('blade.rejected_task')</span>
                                        @elseif($model->status == -1)
                                            <span class="label label-danger">@lang('blade.cancel')</span>
                                        @elseif($model->status == 2)
                                            <span class="label label-primary">@lang('blade.on_process')</span>
                                        @elseif($model->status == 3)
                                            <span class="label label-success">Tasdiqlangan</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($model->created_at??'')->format('d-m-Y H:i') }}</td>
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
        <script>
            $(function () {
                $("#example1").DataTable();
            });
        </script>
    </section>
    <!-- /.content -->
@endsection
