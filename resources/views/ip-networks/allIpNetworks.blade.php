@extends('layouts.table')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Ip Networks
            <small>jadval</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Bosh sahifa</a></li>
            <li><a href="#">ip</a></li>
            <li class="active">ip jadvali</li>
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
                        <div class="col-md-12">
                            <a href="{{ route('ip-networks.create') }}" class="btn btn-primary">
                                <i class="fa fa-plus"></i> Create</a>

                            <div class="box-body">
                                <form action="{{route('ip-network-search')}}" method="POST" role="search">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <select name="f" class="form-control select2" style="width: 100%;">
                                                    <option selected="selected" value="{{$f}}">{{$f}}</option>
                                                    @foreach($filials as $key => $value)
                                                        <option value="{{$value->branch_code}}">{{$value->title}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="q" value="{{$q}}"
                                                       placeholder="First ip">
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <select name="s" class="form-control" style="width: 100%;">
                                                    @if($s == '')
                                                        <option selected="selected" value="">Internet</option>
                                                    @elseif($s == 1)
                                                        <option selected="selected" value="1">Active</option>
                                                    @elseif($s == 0)
                                                        <option selected="selected" value="0">Passive</option>
                                                    @endif
                                                    <option value="">Internet</option>
                                                    <option value="1">Active</option>
                                                    <option value="0">Passive</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <button type="button" class="btn btn-default" onclick="location.href='/admin/ip-networks';"><i class="fa fa-refresh"></i> @lang('blade.reset')</button>
                                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> @lang('blade.search')</button>
                                            </div>
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-md-6">
                                        </div>
                                        <!-- /.col -->
                                    </div>
                                    <!-- /.row -->
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Filial</th>
                                <th>Owner</th>
                                <th>Job</th>
                                <th>First ip</th>
                                <th>Second ip</th>
                                <th>Gateway</th>
                                <th>Internet</th>
                                <th>Status</th>
                                <th>Description</th>
                                <th><i class="fa fa-pencil-square-o"></i></th>
                                <th><i class="fa fa-trash-o"></i></th>
                            </tr>
                            </thead>
                            <tbody>
                           <?php $i = 1 ?>
                            @foreach ($models as $key => $model)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $model->filial->title??'null' }}</td>
                                    <td>{{ $model->user->lname??'' }} {{ $model->user->fname??'' }}</td>
                                    <td>
                                        <span class="text-sm text-muted">{{$model->user->department->title??'null'}} {{ $model->user->job_title??'' }}</span></td>
                                    <td>{{ $model->ip_first }}</td>
                                    <td>{{ $model->ip_second }}</td>
                                    <td>{{ $model->ip_route }}</td>
                                    <td>
                                        @if($model->ip_net == 1)
                                            <i class="fa fa-globe text-blue"></i>
                                        @else
                                            <i class="fa fa-exclamation-triangle text-yellow"></i>

                                        @endif
                                    </td>
                                    <td>
                                        @if($model->ip_status == 1)
                                            <i class="fa fa-check-circle text-green"></i>
                                        @else
                                            <i class="fa fa-ban text-red"></i>

                                        @endif
                                    </td>
                                    <td><span class="text-sm">{{ $model->ip_description }}</span></td>
                                    <td>
                                        <a class="btn btn-primary" href="{{ route('ip-networks.edit', $model->id) }}"><i class="fa fa-pencil"></i></a>
                                    </td>
                                    <td>
                                        <form action="{{ url('ip-networks/'.$model->id) }}" method="POST" style="display: inline-block">
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
                        <span class="paginate">{{ $models->links() }}</span>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- Select2 -->
        <script src="{{ asset('/admin-lte/plugins/select2/select2.full.min.js') }}"></script>
        <!-- InputMask -->
        <script src="{{ asset('/admin-lte/plugins/input-mask/jquery.inputmask.js') }}"></script>
        <script src="{{ asset('/admin-lte/plugins/input-mask/jquery.inputmask.extensions.js') }}"></script>

        <script type="text/javascript">

            $(function () {
                //Initialize Select2 Elements
                $(".select2").select2();

                $("[data-mask]").inputmask();
            });


        </script>
    </section>
    <!-- /.content -->
@endsection
