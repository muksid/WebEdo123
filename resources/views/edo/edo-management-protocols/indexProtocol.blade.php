@extends('layouts.edo.dashboard')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang('blade.protocol_management')
            <small>jadval</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Bosh sahifa</a></li>
            <li><a href="#">@lang('blade.protocol_management')</a></li>
            <li class="active">@lang('blade.protocol_management')</li>
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

                <div class="box box-primary">
                    <div class="box-header">
                        <div class="col-md-1">
                            @if($admin->role->role_code == 'secretary_management')
                            <a href="{{ url('/edo/create-protocol') }}" class="btn btn-flat btn-primary">
                                <i class="fa fa-plus"></i> @lang('blade.create_doc')</a>
                            @endif
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('blade.from_whom')</th>
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
                                    <td class="text-green">{{ $model->user->lname.' '.$model->user->fname }}</td>
                                    <td>
                                        <a href="{{ route('view-protocol',
                                            ['id' => $model->id,
                                            'hash' => $model->protocol_hash]) }}">
                                            {{ $model->title }}
                                        </a>
                                    </td>
                                    <td class="text-maroon" style="min-width: 180px">
                                        @foreach($model->members as $key => $user)

                                            @php($userName = \App\User::find($user->user_id ?? 'null'))

                                                {{ mb_substr($userName->fname ?? '', 0,1).'.'.mb_substr($userName->sname ?? '', 0,1).'.'.mb_scrub($userName->lname ?? 'null2') }}

                                                    @if($user->status == 0)
                                                        <i class="fa fa-ban text-yellow"></i>
                                                    @elseif($user->status == -1)
                                                        <i class="fa fa-ban text-yellow"></i> {{ $user->descr }}
                                                    @elseif($user->status == 1)
                                                        <i class="fa fa-check text-red"></i>
                                                    @elseif($user->status == 2)
                                                        <span class="fa fa-qrcode text-black"></span>
                                                    @elseif($user->status == 3)
                                                <span class="glyphicon glyphicon-qrcode bg-gray"></span>
                                                    @else
                                                        <i class="fa fa-check-square text-primary"></i>
                                                    @endif
                                                <br>
                                        @endforeach

                                    </td>
                                    <td>
                                        @if($model->status == 0)
                                            <span class="label label-warning">bekor qilingan</span>
                                        @elseif($model->status == 1)
                                            <span class="label label-danger">yangi</span>
                                        @elseif($model->status == 2)
                                            <span class="label label-primary">jarayonda</span>
                                        @elseif($model->status == 3)
                                            <span class="label label-success">Tasdiqlangan</span>
                                        @endif
                                    </td>
                                    <td>{{ $model->created_at }}</td>
                                    <td class="text-center">
                                        @if($admin->role->role_code == 'secretary_management')
                                        <a href="{{ route('edit-protocol',
                                            ['id' => $model->id,
                                            'hash' => $model->protocol_hash]) }}" class="btn btn-flat btn-success">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        @endif
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
    <script>
        $(function () {
            $("#example1").DataTable();
        });
    </script>
@endsection
