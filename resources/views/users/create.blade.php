<?php ?>
@extends('layouts.dashboard')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang('blade.create_user')
        </h1>
        <ol class="breadcrumb">
            <li><a href="/home"><i class="fa fa-dashboard"></i> @lang('blade.home_page')</a></li>
            <li><a href="#">@lang('blade.users')</a></li>
            <li class="active">@lang('blade.create_user')</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- SELECT2 EXAMPLE -->
        <div class="box box-primary">
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <!-- Message Succes -->
                    @if ($message = Session::get('success'))
                        <div class="modal fade in" id="myModal" role="dialog" style="display: block">
                            <div class="modal-dialog modal-sm">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header bg-aqua-active">
                                        <h4 class="modal-title">
                                            @lang('blade.users') <i class="fa fa-check-circle"></i>
                                        </h4>
                                    </div>
                                    <div class="modal-body">
                                        <h5>{{ $message }}</h5>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-info closeModal" data-dismiss="modal"><i
                                                    class="fa fa-check-circle"></i> Ok
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endif
                <!-- Display Validation Errors -->
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>@lang('blade.error')</strong> @lang('blade.error_check').<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form role="form" method="POST" action="{{ url('admin/users') }}">
                        {{ csrf_field() }}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('blade.surname') <span style="color:red">*</span> </label>
                                <input type="text" name="lname" class="form-control"
                                       placeholder="@lang('blade.ph_surname')" required>
                            </div>
                            <!-- /.form-group -->
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('blade.name') <span style="color:red">*</span> </label>
                                <input type="text" name="fname" class="form-control"
                                       placeholder="@lang('blade.ph_name')" required>
                            </div>
                            <!-- /.form-group -->
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('blade.fathers_name') <span style="color:red">*</span> </label>
                                <input type="text" name="sname" class="form-control"
                                       placeholder="@lang('blade.ph_fathers_name')" required>
                            </div>
                            <!-- /.form-group -->
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Username (Login) <span style="color:red">*</span> </label>
                                <input type="text" name="username" class="form-control"
                                       placeholder="@lang('blade.ph_login')" required>
                            </div>
                            <!-- /.form-group -->
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('blade.tabel_num') (card num) <span style="color:red">*</span> </label>
                                <input type="text" name="card_num" class="form-control"
                                       placeholder="@lang('blade.ph_tabel_num')" required>
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <!-- email -> branch_code + card_num + @turonbank.uz by default -->
                        <input type="hidden" name="email" class="form-control" id="exampleInputEmail1" value="">

                        @foreach(json_decode(Auth::user()->roles) as $role)

                            @switch($role)
                                @case('admin')
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>User sort <span style="color:red">*</span> </label>
                                        <input type="number" min="0" max="254" name="user_sort" class="form-control"
                                               placeholder="sort number" value="0" required>
                                    </div>
                                    <!-- /.form-group -->
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('blade.branch') <span style="color:red">*</span> </label>
                                        <select id="filial_code" class="form-control select2" name="branch_code"
                                                style="width: 100%;">
                                            <option disabled selected value>@lang('blade.select_branch')</option>
                                            @foreach($filial as $filial)
                                                <option value="{{$filial->branch_code}}">{{$filial->branch_code. ' ' .$filial->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group departdiv">
                                        <label>@lang('blade.dep') </label>
                                        <select id="depart" class="form-control select2" name="depart_id">
                                            <option selected="selected" value=""></option>
                                        </select>
                                    </div>
                                    <div class="form-group departdiv2" hidden>
                                        <label>@lang('blade.dep') </label>
                                        <select id="sub_depart" class="form-control select2" name="depart_id">
                                            <option selected="selected" value=""></option>
                                        </select>
                                    </div>
                                    <div class="form-group departdiv3" hidden>
                                        <label>@lang('blade.dep') </label>
                                        <select id="sub_sub_depart" class="form-control select2" name="depart_id">
                                            <option selected="selected" value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <!-- Role -->
                                    <div class="form-group">
                                        <label>@lang('blade.role') <span style="color:red">*</span> </label>
                                        <select class="form-control" name="roles[]" style="width: 100%; height: 260px"
                                                multiple>
                                            @foreach($roles as $role)
                                                @if($role->role_code === "user")
                                                    <option value="{{$role->role_code}}"
                                                            {{ $role->role_code, $role->title ? "selected" : null }} selected>{{$role->title}}</option>
                                                @else
                                                    <option value="{{$role->role_code}}" {{ $role->role_code, $role->title ? "selected" : null }}>{{$role->title}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            @break
                            @case('branch_admin')
                            <!-- branch code  -->
                                <input type="hidden" name="branch_code" class="form-control"
                                       value="{{Auth::user()->branch_code}}">
                                <!-- Role -->
                                <select class="form-control" name="roles[]"
                                        style="width: 100%; height: 110px; display: none;" multiple>
                                    <option value="user" selected></option>
                                </select>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>User sort <span style="color:red">*</span> </label>
                                        <input type="number" min="0" max="254" name="user_sort" class="form-control"
                                               placeholder="sort number" value="0" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group departdiv">
                                        <label>@lang('blade.dep') </label>
                                        <select id="depart" class="form-control select2" name="depart_id">
                                            <option disabled selected value>Select dep</option>
                                            @foreach($departments as $department)
                                                <option value="{{$department->id}}">{{$department->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group departdiv2" hidden>
                                        <label>@lang('blade.dep') </label>
                                        <select id="sub_depart" class="form-control select2" name="depart_id">
                                            <option selected="selected" value=""></option>
                                        </select>
                                    </div>
                                    <div class="form-group departdiv3" hidden>
                                        <label>@lang('blade.dep') </label>
                                        <select id="sub_sub_depart" class="form-control select2" name="depart_id">
                                            <option selected="selected" value=""></option>
                                        </select>
                                    </div>
                                </div>

                                <!-- /.form-group -->
                </div>

                @break
                @endswitch
                @endforeach

                <div class="col-md-4">
                    <div class="form-group">
                        <label>@lang('blade.position') <span style="color:red">*</span> </label>
                        <input type="text" name="job_title" class="form-control"
                               placeholder="@lang('blade.ph_enter_position')">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group{{ $errors->has('job_date') ? ' has-error' : '' }}">
                        <label>@lang('blade.position_date') <span style="color:red">*</span> </label>
                        <input type="date" name="job_date" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>@lang('blade.status') <span style="color:red">*</span> </label>
                        <select class="form-control" name="status">
                            <option value="1" selected>Active</option>
                            <option value="0">Passive</option>
                        </select>
                    </div>
                </div>

                <!-- /.form-group -->
                <div class="col-md-3">
                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="exampleInputPassword1">@lang('blade.password') <span style="color:red">*</span>
                        </label>
                        <input type="password" name="password" class="form-control" id="inputError"
                               placeholder="@lang('blade.password')">
                        <span class="help-block">@lang('blade.enter_pass_at_least_6')</span>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        <label for="exampleInputPassword1">@lang('blade.repeat_pass') <span style="color:red">*</span>
                        </label>
                        <input type="password" name="password_confirmation" class="form-control" id="inputError"
                               placeholder="@lang('blade.repeat_pass')">
                        <span class="help-block">@lang('blade.repeat_pass')</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group" style="margin-top: 20px; float:right; padding-right: 7%;">
                        <a href="/admin/users" class="btn btn-default">@lang('blade.cancel') </a>
                        <button type="submit" class="btn btn-primary">@lang('blade.save')</button>
                    </div>
                </div>

                </form>
                <!-- /.form-group -->
            </div>
        </div>
        <!-- /.row -->
        </div>
        <script src="{{ asset("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>
        <script src="{{ asset("/admin-lte/dist/js/app.min.js") }}"></script>
        <script src="{{ asset("/admin-lte/plugins/select2/select2.full.min.js") }}"></script>
        <script type="text/javascript">

            $(document).ready(function () {
                $("#loding1").hide();
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $("#filial_code").change(function () {
                    $("#loding1").show();
                    var branch_code = $(this).val();
                    $.ajax({
                        type: "POST",
                        url: "/get-department",
                        data: {_token: CSRF_TOKEN, branch_code: branch_code},
                        dataType: 'JSON',
                        success: function (res) {
                            var user_input = "";
                            var obj = res;

                            if (res) {
                                $("#loding1").hide();
                                $("#depart").empty();
                                if (obj['msg'] != 0) {
                                    $('.departdiv').show();
                                    $('.departdiv2').hide();
                                    $('.departdiv3').hide();
                                    $("#depart").append('<option value=' + res.req + ' disabled selected> Departamentni tanlang </option>');
                                    $("#sub_depart").append('<option value=' + res.req + ' selected></option>');
                                    $("#sub_sub_depart").append('<option value=' + res.req + ' selected></option>');

                                    $.each(obj['msg'], function (key, val) {
                                        user_input += '<option value="' + val.id + '"> --- ' + val.title + '</option>';
                                    });
                                } else {
                                    $('#departdiv').hide();
                                }
                                $("#depart").append(user_input); //// For Append
                            }
                        },

                        error: function () {
                            console.log('error');
                        }
                    });
                }); // End of #filial_code
                $("#depart").change(function () {
                    $("#loding1").show();
                    var dataString = $(this).val();
                    $.ajax({
                        type: "POST",
                        url: "/postbranch",
                        data: {_token: CSRF_TOKEN, name: dataString},
                        dataType: 'JSON',
                        success: function (res) {
                            var user_input = "";
                            var obj = res;

                            if (res) {
                                $("#loding1").hide();
                                $("#sub_depart").empty();
                                $("#sub_depart").append('<option value=' + res.req + ' disabled selected> Sub Departamentni tanlang</option>');
                                $("#sub_sub_depart").append('<option value=' + res.req + ' selected> Sub Departamentni tanlang </option>');
                                if (obj['msg'] != 0) {
                                    $('.departdiv2').show();
                                    $('.departdiv3').hide();
                                    $.each(obj['msg'], function (key, val) {
                                        user_input += '<option value="' + val.id + '">' + val.title + '</option>';

                                    });
                                } else {
                                    $('.departdiv2').hide();
                                    $('.departdiv3').hide();
                                }
                                $("#sub_depart").append(user_input); //// For Append
                            }
                        },
                        error: function () {
                            console.log('error');
                        }
                    });
                }); // End Of #depart
                $("#sub_depart").change(function () {
                    $("#loding1").show();
                    var dataString = $(this).val();
                    $.ajax({
                        type: "POST",
                        url: "/postbranch",
                        data: {_token: CSRF_TOKEN, name: dataString},
                        dataType: 'JSON',
                        success: function (res) {
                            var user_input = "";
                            var obj = res;

                            if (res) {
                                $("#loding1").hide();
                                $("#sub_sub_depart").empty();
                                $("#sub_sub_depart").append('<option  value=' + res.req + ' disabled selected> Sub Sub Departamentni tanlang </option>');

                                if (obj['msg'] != 0) {
                                    $('.departdiv3').show();
                                    $.each(obj['msg'], function (key, val) {
                                        user_input += '<option value="' + val.id + '">' + val.title + '</option>';
                                    });
                                } else {
                                    $('.departdiv3').hide();
                                }

                                $("#sub_sub_depart").append(user_input); //// For Append
                            }
                        },
                        error: function () {
                            console.log('error');
                        }
                    });
                }); // End #sub_depart
            }); //End of the function ()

            // close my Modal
            $('.closeModal').click(function () {

                $('#myModal').hide();

            });

        </script>

    </section>
    <!-- /.content -->

@endsection
