@extends('layouts.edo.dashboard')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-sm-12">
                <div class="col-sm-2">
                    <h3 style="margin-top: 0">
                        @switch($protocol_dep)
                            @case(3)
                                @lang('blade.protocol_management')
                            @break
                            @case(11)
                                @lang('blade.hr_orders')
                            @break
                            @case(24)
                                Strategiya Hujjatlari
                            @break
                        @endswitch
<<<<<<< HEAD
                        
                        <small>@lang('blade.groups_table')</small>
                    </h3> 
=======
                    </h3>
>>>>>>> master
                </div>
                <div class="col-sm-1" align="center">
                    <form action="{{ url('edo/hr-member-protocols/'.$protocol_dep) }}" method="post">
                        @csrf
                        <input id="new" type="text" name="type" value="new" hidden />
                        <button type="submit" class="btn btn-danger"> @lang('blade.new') [{{ $new_count }}] </button>
                    </form>
                </div>
                <div class="col-sm-2" align="center">
                    <form action="{{ url('edo/hr-member-protocols/'.$protocol_dep) }}" method="post">
                        @csrf
                        <input id="on_process" type="text" name="type" value="on_process" hidden />
                        <button type="submit" class="btn btn-warning"> @lang('blade.on_process') [{{ $on_process_count }}] </button>
                    </form>
                </div>
                <div class="col-sm-1" align="center">
                    <form action="{{ url('edo/hr-member-protocols/'.$protocol_dep) }}" method="post">
                        @csrf
                        <input id="archive" type="text" name="type" value="archive" hidden />
                        <button type="submit" class="btn btn-success"> @lang('blade.archive') [{{ $archive_count }}] </button>
                    </form>
                </div>
<<<<<<< HEAD
                
=======
>>>>>>> master
            </div>
        </div>

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

<<<<<<< HEAD
            

=======
>>>>>>> master
                <div class="box box-primary">
                    <div class="box-header">
                        <div class="col-md-1">
                        </div>

                        <div class="col-md-8">
                            <form action="{{ url('/edo/hr-member-protocols/'.$protocol_dep) }}" method="POST" role="search">
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
<<<<<<< HEAD
                                    
=======
>>>>>>> master
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <a href="{{ url('/edo/hr-member-protocols/'.$protocol_dep) }}" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> @lang('blade.reset')</a>
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
                        <table id="" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('blade.from_whom')</th>
                                <th>@lang('blade.reg_num')</th>
                                <th>@lang('blade.doc_name')</th>
                                <th>@lang('blade.to_whom')</th>
                                <th>@lang('blade.status')</th>
                                <th>@lang('blade.date')</th>
                            </tr>
                            </thead>
                            <tbody>
                           <?php $i = 1 ?>
                            @foreach ($models as $key => $model)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td class="text-green">{{ $model->protocol->user->lname??'' }} {{ $model->protocol->user->fname??'' }}</td>
                                    <td style="color:grey">
<<<<<<< HEAD
                                        {{ $model->protocol->stf_number??'(Пусто)' }} 
=======
                                        {{ $model->protocol->stf_number??'(Пусто)' }}

                                        {{ $model->protocol->stf_number??'(Пусто)' }}

>>>>>>> master
                                        @if($model->protocol->stf_date??null)
                                            {{ (new DateTime($model->protocol->stf_date??''))->format('d-m-Y') }}
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('view-m-protocol',
                                            ['id' => $model->protocol->id,
                                            'hash' => $model->protocol->protocol_hash]) }}">
                                            {{ $model->protocol->title }}
                                        </a>
                                    </td>
                                    <td class="text-maroon" style="min-width: 180px">
                                        @foreach($model->members as $key => $user)

                                            @php($userName = \App\User::find($user->user_id ?? 'null'))

                                            {{ $userName->substrUserName($userName->id) }}

                                            @if($user->status == -1)
                                                <i class="fa fa-ban text-yellow"></i>
                                                <span class="text-yellow">{{ $user->descr }}</span>
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
                                        @if($model->protocol->status == -1)
                                            <span class="label label-warning">bekor qilingan</span>
                                        @elseif($model->protocol->status == 1)
                                            <span class="label label-danger">@lang('blade.new')</span>
                                        @elseif($model->protocol->status == 2)
                                            <span class="label label-primary">@lang('blade.sh_apply_proc')</span>
                                        @elseif($model->protocol->status == 3)
                                            <span class="label label-success">@lang('blade.approved')</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($model->updated_at)->format('d.m.Y H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $models->links() }}
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
