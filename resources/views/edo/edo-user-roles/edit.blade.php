@extends('layouts.edo.dashboard')
@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Guruh yaratish
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Bosh sahifa</a></li>
                <li class="active">Guruh yaratish</li>
            </ol>

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Xatolik!</strong> Guruh yaratish uchun xodimlarni belgilashingiz kerak.<br><br>
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
                      action="{{ url('edo-user-roles/'.$model->id) }}">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}

                    <div class="col-md-8">
                        <div class="box box-primary">
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                                    <label>Title <span class=""></span></label>
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
                                    <label>Title Ru<span class=""></span></label>
                                    <input type="text" id="title_ru" name="title_ru" value="{{ $model->title_ru }}"
                                           class="form-control" placeholder="Title Ru">
                                    @if ($errors->has('title_ru'))
                                        <span class="text-red" role="alert">
                                        <strong>{{ $errors->first('title_ru') }}</strong>
                                    </span>
                                    @endif

                                </div>
                            </div>
                            <div class="box-body">
                                <div class="form-group {{ $errors->has('role_code') ? 'has-error' : '' }}">
                                    <label>Role Code<span class=""></span></label>
                                    <input type="text" id="role_code" name="role_code" value="{{ $model->role_code }}"
                                           class="form-control" placeholder="Role Code">
                                    @if ($errors->has('role_code'))
                                        <span class="text-red" role="alert">
                                        <strong>{{ $errors->first('role_code') }}</strong>
                                    </span>
                                    @endif

                                </div>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <div class="pull-right">
                                    <a href="/edo-user-roles"class="btn btn-default"><i class="fa fa-remove"></i> Bekor
                                        qilish
                                    </a>
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-pencil"></i>
                                        O`zgartirish
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
                    // For plus minus click departments
                    $(".btn-success").click(function () {
                        var html = $(".clone").html();
                        $(".increment").after(html);
                    });

                    $("body").on("click", ".btn-danger", function () {
                        $(this).parents(".control-group").remove();
                    });
                    // End //

                    //For message term (hat muddati)
                    $('#checkbox1').change(function () {
                        if (this.checked)
                            $('#autoUpdate').fadeIn(200);
                        else
                            $('#autoUpdate').fadeOut(200);
                    });
                    // End //

                    // For post ajax
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $(".post_users").click(function () {
                        var depart_id = $(this).next().val();
                        $.ajax({
                            url: '/get-depart-users',
                            type: 'POST',
                            data: {_token: CSRF_TOKEN, depart_id: depart_id},
                            dataType: 'JSON',
                            success: function (data) {
                                var obj = data;
                                var user_input = "";

                                $.each(obj['msg'], function (key, val) {
                                    user_input += "<div class='form-check .form-group'>" +
                                        "<input id='to_users' name='users_id[]' value=" + val.id + " class='flat-red' type='checkbox'>" +
                                        "<label class='form-check-label' style='color: initial' for='materialUnchecked'>" +
                                        val.lname + " " + val.fname + " <span style='font-size: x-small;font-style: italic;color: #31708f;'>" +
                                        val.job_title + "</span></div>";
                                });

                                $("#data" + data.depart_id).append(user_input); //// For Append

                                $("#mydiv" + data.depart_id).html(user_input)   //// For replace with previous one
                            },
                            error: function () {

                                console.log(data);
                            }
                        });
                    });
                    // End //

                });
            </script>

            <script src="{{ asset('js/treeview.js') }}"></script>

            <script src="{{ asset("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

            <script src="{{ asset("/admin-lte/dist/js/app.min.js") }}"></script>

        </section>
        <!-- /.content -->
    </div>


@endsection

