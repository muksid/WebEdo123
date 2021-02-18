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
            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">
                        <form role="form" method="POST"
                              action="{{ url('groups/'.$group->id) }}">
                            {{ csrf_field() }}
                            {{ method_field('PATCH') }}
                            <div class="col-md-12">
                                <!-- /.box-header -->
                                <div class="col-md-6">
                                    <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                                        <label>@lang('blade.group_edit_groupname') <span class=""></span></label>
                                        <input type="text" id="title" name="title" value="{{$group->title}}"
                                               class="form-control" placeholder="@lang('blade.group_edit_groupname')" required autofocus>
                                        @if ($errors->has('title'))
                                            <span class="text-red" role="alert">
                                                <strong>{{ $errors->first('title') }}</strong>
                                            </span>
                                        @endif

                                    </div>
                                    <!-- /.box-header -->
                                    <div class="form-group {{ $errors->has('title_ru') ? 'has-error' : '' }}">
                                        <label>@lang('blade.group_edit_groupname') РУ<span class=""></span></label>
                                        <input type="text" id="title_ru" name="title_ru" value="{{ $group->title_ru }}"
                                               class="form-control" placeholder="@lang('blade.group_edit_groupname') РУ">
                                        @if ($errors->has('title_ru'))
                                            <span class="text-red" role="alert">
                                                <strong>{{ $errors->first('title_ru') }}</strong>
                                            </span>
                                        @endif

                                    </div>
                                    <div class="box-header with-border">
                                        <h3 class="box-title">@lang('blade.group_edit_num_user') {{ $group_users->count() }}
                                        @lang('blade.group_edit_count').</h3>
                                    </div>

                                    <div class="form-group">
                                        <ul id="tree12">
                                            @foreach($group_users as $group_user)
                                                <dl>
                                                    <dt>{{ $group_user->branch_code. ' ' .$group_user->title_ru }}</dt>
                                                    <dd>
                                                        <li>
                                                            <input type="checkbox" name="users_id[]"
                                                                   value="{{ $group_user->id }}" checked>
                                                            {{ $group_user->fname. ' ' .$group_user->lname }} <span
                                                                    style="font-size: 11px; color: #3c8dbc">{{ $group_user->job_title }}</span>
                                                        </li>
                                                    </dd>
                                                </dl>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">@lang('blade.group_edit_add_users')</h3>
                                    </div>
                                    <div class="form-group" style="max-height: 600px; overflow-y: scroll">
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
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('blade.group_edit_status')</label>
                                            <select class="form-control select2" name="status">
                                                <option selected="selected"
                                                        value="{{$group->status}}">@if($group->status == 1) Active @else
                                                        Passiv @endif </option>
                                                <option value="1">Active</option>
                                                <option value="0">Passive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- /.box-body -->
                                    <div class="box-footer">
                                        <div class="pull-right">
                                            <a href="/home" class="btn btn-default"><i class="fa fa-remove"></i>@lang('blade.cancel')</a>
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-pencil"></i>@lang('blade.update')
                                            </button>
                                        </div>
                                    </div>
                                    <!-- /.box-footer -->
                                </div>
                            </div>
                        </form>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->

                </div>
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

