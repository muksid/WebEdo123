@extends('layouts.edo.dashboard')
<!-- Select2 -->
<link rel="stylesheet" href="/admin-lte/plugins/select2/select2.min.css">
@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>User edit
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Bosh sahifa</a></li>
                <li class="active">User edit</li>
            </ol>

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Xatolik!</strong> Errors.<br><br>
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
                      action="{{ url('edo-management-protocols/'.$model->id) }}">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}

                    <div class="col-md-8">
                        <div class="box box-primary">
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div class="form-group {{ $errors->has('user_id') ? 'has-error' : '' }}">
                                    <label>User name <span class=""></span></label>

                                    <select name="user_id" id="user_id" class="form-control select2" style="width: 100%;" required>
                                        <option value="{{ $model->user_id }}" selected="selected">{{ $model->user->lname.' '.$model->user->fname }}</option>
                                        @foreach($users as $key => $value)
                                            <option value="{{ $value->id }}">{{ $value->full_name }}</option>

                                        @endforeach
                                    </select>

                                </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div class="form-group {{ $errors->has('qr_name') ? 'has-error' : '' }}">
                                    <label>QR Name<span class=""></span></label>
                                    <input type="text" id="qr_name" name="qr_name" class="form-control" value="{{ $model->qr_name }}" required>
                                    @if ($errors->has('qr_name'))
                                        <span class="text-red" role="alert">
                                        <strong>{{ $errors->first('qr_name') }}</strong>
                                    </span>
                                    @endif

                                </div>
                            </div>
                            <!-- /.box-header -->

                            <div class="box-body">
                                <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                                    <label>Title<span class=""></span></label>
                                    <input type="text" id="title" name="title" class="form-control" value="{{ $model->title }}" required>
                                    @if ($errors->has('title'))
                                        <span class="text-red" role="alert">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                    @endif

                                </div>
                            </div>
                            <div class="box-body">
                                <div class="form-group {{ $errors->has('user_sort') ? 'has-error' : '' }}">
                                    <label>User sort<span class=""></span></label>
                                    <input type="number" id="user_sort" name="user_sort" class="form-control" value="{{ $model->user_sort }}" required>
                                    @if ($errors->has('user_sort'))
                                        <span class="text-red" role="alert">
                                        <strong>{{ $errors->first('user_sort') }}</strong>
                                    </span>
                                    @endif

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
                                    <a href="/edo-management-protocols"class="btn btn-default">
                                        <i class="fa fa-remove"></i> Bekor qilish
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-pencil"></i> O`zgartirish
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
            <script type="text/javascript">
                $(document).ready(function () {

                    $(function () {
                        //Initialize Select2 Elements
                        $(".select2").select2();
                    });

                });
            </script>

            <script src="{{ asset("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

            <script src="{{ asset("/admin-lte/dist/js/app.min.js") }}"></script>
            <!-- Select2 -->
            <script src="{{ asset("/admin-lte/plugins/select2/select2.full.min.js") }}"></script>

        </section>
        <!-- /.content -->
    </div>


@endsection

