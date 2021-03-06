@extends('layouts.compose')
@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>@lang('blade.groups_create_group')
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home_page')</a></li>
                <li class="active">@lang('blade.groups_create_group')</li>
            </ol>

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>@lang('blade.error')</strong> @lang('blade.to_create_group_choose')<br><br>
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
                <form role="form" method="POST" action="{{ route('cgroup') }}" enctype="multipart/form-data">
                    {{csrf_field()}}

                    <div class="col-md-8">
                        <div class="box box-primary">
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                                    <label>@lang('blade.group_edit_groupname') <span class=""></span></label>
                                    <input type="text" id="title" name="title" value="{{ old('title') }}"
                                           class="form-control" placeholder="@lang('blade.group_edit_groupname')" required autofocus>
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
                                    <label>@lang('blade.group_edit_groupname') Ru<span class=""></span></label>
                                    <input type="text" id="title_ru" name="title_ru" value="{{ old('title_ru') }}"
                                           class="form-control" placeholder="@lang('blade.group_edit_groupname') Ru">
                                    @if ($errors->has('title_ru'))
                                        <span class="text-red" role="alert">
                                        <strong>{{ $errors->first('title_ru') }}</strong>
                                    </span>
                                    @endif

                                </div>
                            </div>
                            <div class="box-header with-border">
                                <h3 class="box-title">@lang('blade.group_create_add_users')</h3>
                            </div>
                            <div class="box-body">
                                <div class="form-group">
                                    <ul id="tree1">
                                        @foreach($departments as $department)
                                            <li>
                                                {{ $department->title }}
                                                @if(count($department->childs))
                                                    @include('groups.ajax',['childs' => $department->childs])
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <div class="pull-right">
                                    <a href="/home"class="btn btn-default"><i class="fa fa-remove"></i> 
                                        @lang('blade.cancel')
                                    </a>
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i>
                                        @lang('blade.save')
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
        </section>
        <!-- /.content -->
    </div>

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
            var branch_code = $("input[name=branch_code]").val();
            $(".post_users").click(function () {
                var label = $(this).text();
                var user_depart_id = $(this).next().val();
                $.ajax({
                    url: '/postajax',
                    type: 'POST',
                    data: {_token: CSRF_TOKEN, name: user_depart_id},
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

                        $("#data" + data.branch).append(user_input); //// For Append

                        $("#mydiv" + data.branch).html(user_input)   //// For replace with previous one
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
    {{-- for post ajax js lib<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>--}}

@endsection

