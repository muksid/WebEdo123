@extends('layouts.edo.dashboard')
@section('content')

    <!-- TRANSLATED -->

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>@lang('blade.create_journal')
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home_page')</a></li>
                <li class="active">@lang('blade.create_journal')</li>
            </ol>

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>@lang('blade.error')</strong> @lang('blade.error_journal_add').<br><br>
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
                <form role="form" method="POST"
                      action="{{ url('edo-journals/'.$model->id) }}">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}

                    <div class="col-md-8">
                        <div class="box box-primary">
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                                    <label>@lang('blade.title_uz') <span class=""></span></label>
                                    <input type="text" id="title" name="title" value="{{$model->title}}"
                                           class="form-control" placeholder="title" required autofocus>
                                    @if ($errors->has('title'))
                                        <span class="text-red" role="alert">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                    @endif

                                </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div class="form-group {{ $errors->has('title_ru') ? 'has-error' : '' }}">
                                    <label>@lang('blade.title_ru')<span class=""></span></label>
                                    <input type="text" id="title_ru" name="title_ru" value="{{ $model->title_ru }}"
                                           class="form-control" placeholder="Title Ru">
                                    @if ($errors->has('title_ru'))
                                        <span class="text-red" role="alert">
                                        <strong>{{ $errors->first('title_ru') }}</strong>
                                    </span>
                                    @endif

                                </div>
                            </div>
                            <!-- /.box-header -->

                            <div class="box-body">
                                <div class="form-group {{ $errors->has('journal_type') ? 'has-error' : '' }}">
                                    <label>Tip jurnala<span class=""></span></label>

                                    <select name="journal_type" id="journal_type" class="form-control" style="width: 100%;">
                                        @if($model->journal_type == 'inbox')
                                            <option value="{{ $model->journal_type }}" selected="selected">Kiruvchi</option>
                                            <option value="sent">Chiquvchi</option>
                                        @else
                                            <option value="{{ $model->journal_type }}" selected="selected">Chiquvchi</option>
                                            <option value="inbox">Kiruvchi</option>
                                        @endif
                                    </select>

                                </div>
                            </div>

                            <div class="box-body">
                                <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                                    <label>status<span class=""></span></label>

                                    <select name="status" id="status" class="form-control" style="width: 100%;">
                                        @if($model->status == 1)
                                            <option value="{{ $model->status }}" selected="selected">Active</option>
                                            <option value="0">Passive</option>
                                        @else
                                            <option value="{{ $model->status }}" selected="selected">Passive</option>
                                            <option value="1">Active</option>
                                        @endif
                                    </select>

                                </div>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <div class="pull-right">
                                    <a href="/edo-journals" class="btn btn-default"><i class="fa fa-remove"></i> 
                                        @lang('blade.cancel')
                                    </a>
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-pencil"></i>
                                        @lang('blade.update')
                                    </button>
                                </div>
                            </div>
                            <!-- /.box-footer -->
                        </div>
                        <!-- /. box -->
                    </div>
                </form>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <script src="{{ asset("/admin-lte/dist/js/app.min.js") }}"></script>

        </section>
        <!-- /.content -->
    </div>


@endsection

