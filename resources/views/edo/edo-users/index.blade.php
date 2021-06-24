@extends('layouts.edo.dashboard')
@section('content')

    <section class="content-header">
        <h1>
            EDO Users
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
                <div class="box box-primary">
                    <div class="box-header">
                            <a href="{{ route('edo-users.create') }}" class="btn btn-primary btn-flat">
                                <i class="fa fa-plus"></i> Create user</a>
                            <h3 class="box-title modelsCount">@lang('blade.overall'): <b>{{ $models->total() }}</b></h3>
                            <div class="box-body">
                                <form action="{{url('edo-users-search')}}" method="post" role="search">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="l" value="{{$l}}"
                                                       placeholder="@lang('blade.username')">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="f" value="{{$f}}"
                                                       placeholder="@lang('blade.full_name')">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="r" value="{{$r}}"
                                                       placeholder="@lang('blade.role')">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <button type="button" class="btn btn-default btn-flat"
                                                        onclick="location.href='/edo-users';"><i
                                                            class="fa fa-refresh"></i> @lang('blade.reset')</button>
                                                <button type="submit" class="btn btn-primary btn-flat"><i
                                                            class="fa fa-search"></i> @lang('blade.search')</button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.row -->
                                </form>
                            </div>
                    </div>

                    <div class="box-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('blade.username')</th>
                                <th>@lang('blade.full_name')</th>
                                <th>@lang('blade.dep')</th>
                                <th>@lang('blade.position')</th>
                                <th>@lang('blade.role')</th>
                                <th>Manager</th>
                                <th>Child</th>
                                <th>@lang('blade.sort')</th>
                                <th>@lang('blade.status')</th>
                                <th><i class="fa fa-pencil-square-o"></i></th>
                                <th><i class="fa fa-trash-o"></i></th>
                            </tr>
                            </thead>
                            <tbody>
                           <?php $i = 1 ?>
                           @if($models->count())
                            @foreach ($models as $key => $model)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $model->user->username??'' }}</td>
                                    <td>{{ $model->user->lname??''}} {{ $model->user->fname??'' }}</td>
                                    <td class="text-sm text-muted"><sup>{{ $model->user->branch_code??'' }}</sup> {{ $model->department->title ?? 'null' }}</td>
                                    <td class="text-sm text-muted">{{ $model->user->job_title??'' }}</td>
                                    <td>{{ $model->role->title??'' }} <sup class="text-primary">{{ $model->role->role_code??'' }}</sup></td>
                                    <td>{{ $model->manager->fname ?? '' }}</td>
                                    <td>{{ $model->child->fname ?? '' }}</td>
                                    <td class="text-center">{{ $model->sort }}</td>
                                    <td>
                                        @if($model->status == 1)
                                        <i class="fa fa-check-circle-o text-green"></i> <sup>{{ $model->status }}</sup></td>
                                    @else
                                        <i class="fa fa-ban text-red"></i> <sup>{{ $model->status }}</sup>
                                    @endif
                                    <td>
                                        <a class="btn btn-primary" href="{{ route('edo-users.edit', $model->id) }}"><i class="fa fa-pencil"></i></a>
                                    </td>
                                    <td>
                                        <form action="{{ url('edo-users/'.$model->id) }}" method="POST" style="display: inline-block">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}

                                            <button onclick="return confirm('@lang('blade.are_you_sure_delete')')" type="submit" id="delete-group-{{ $model->id }}" class="btn btn-danger">
                                                <i class="fa fa-btn fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                           @else
                               <td class="text-red text-center" colspan="12"><i class="fa fa-refresh"></i>
                                   <b>@lang('blade.not_found')</b></td>
                           @endif
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
    </section>
    <!-- /.content -->
@endsection
