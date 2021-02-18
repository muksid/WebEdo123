@extends('layouts.edo.dashboard')
@section('content')

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Vazifa create
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Bosh sahifa</a></li>
                <li class="active">Vazifa create</li>
            </ol>

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Xatolik!</strong> Vazifa yaratishda xatolik mavjud.<br><br>
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
                <form role="form" method="POST" action="{{ url('helper-tasks') }}"
                      enctype="multipart/form-data">
                {{csrf_field()}}
                <!-- /.box-header -->
                    <div class="col-md-8">
                        <div class="box box-primary">
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                                    <label>Title <span class=""></span></label>
                                    <input type="text" id="title" name="title" value="{{ old('title') }}"
                                           class="form-control" placeholder="Title" required autofocus>
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
                                    <label>Title Ru<span class=""></span></label>
                                    <input type="text" id="title_ru" name="title_ru" value="{{ old('title_ru') }}"
                                           class="form-control" placeholder="Title Ru">
                                    @if ($errors->has('title_ru'))
                                        <span class="text-red" role="alert">
                                        <strong>{{ $errors->first('title_ru') }}</strong>
                                    </span>
                                    @endif

                                </div>
                            </div>
                            <input name="user_id" value="{{ \Illuminate\Support\Facades\Auth::id() }}" hidden>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <div class="pull-right">
                                    <a href="/helper-tasks" class="btn btn-default"><i class="fa fa-remove"></i> Bekor
                                        qilish
                                    </a>
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i>
                                        Saqlash
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


@endsection

