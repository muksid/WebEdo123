<?php ?>
@extends('layouts.edo.dashboard')

@section('content')

    <!-- TRANSLATED -->

    <!-- Main content -->
    <section class="content">
        <div class="row">
                <!-- Message Succes -->
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    {{ $message }}
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
            <!-- left column -->
            <div class="col-md-4">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">@lang('blade.doc')</h3>
                    </div>
                    <h4><i class="glyphicon glyphicon-pushpin"></i></h4>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <table class="table table-condensed">
                            <tr>
                                <th style="width: 50%">@lang('blade.reg_journal')</th>
                                <th>@lang('blade.doc_form')</th>
                            </tr>
                            <tr>
                                <td style="width: 50%">@lang('blade.incoming_letter')</td>
                                <td>{{ $model->id }}</td>
                            </tr>
                        </table>
                    </div>
                    <h4><i class="fa fa-arrow-down"></i></h4>
                    <div class="box-body no-padding">
                        <table class="table table-condensed">
                            <tr>
                                <th style="width: 50%"><i class="fa fa-list-ol"></i> @lang('blade.incoming_num')</th>
                                <th><i class="fa fa-clock-o"></i> @lang('blade.incoming_date')</th>
                            </tr>
                            <tr>
                                <td style="width: 50%">{{ $model->in_number }}</td>
                                <td>{{ $model->in_date }}</td>
                            </tr>
                        </table>
                    </div>
                    <h4><i class="fa fa-arrow-up"></i></h4>
                    <div class="box-body no-padding">
                        <table class="table table-condensed">
                            <tr>
                                <th><i class="fa fa-list-ol"></i> @lang('blade.outgoing_num')</th>
                                <th><i class="fa fa-clock-o"></i> @lang('blade.outgoing_date')</th>
                            </tr>
                            <tr>
                                <td>{{ $model->out_number }}</td>
                                <td>{{ $model->out_date }}</td>
                            </tr>
                        </table>
                    </div>
                    <hr>
                    <!-- /.box-body -->
                    <div class="box-body">
                        <h5><strong>@lang('blade.sender_organization')</strong></h5>
                        <p>{{ $model->from_name }}</p>
                        <hr>
                        <strong><i class="fa fa-file margin-r-5"></i> @lang('blade.doc_app') </strong><br><br>

                        @foreach ($model->files as $file)
                            <i class="fa fa-file-pdf-o text-yellow"></i> {{ $file->file_name }} <a
                                    href="#" class="pull-right">@lang('blade.download')</a><br><br>
                        @endforeach
                        <hr>
                        <p class="text-bold text-center">{{ $model->id }}</p>
                        <p>{{ $model->text }}</p>
                    </div>
                </div>
                <!-- /.box -->

            </div>
            <!--/.col (left) -->
            <!-- right column -->
            <div class="col-md-4">
                <!-- Horizontal Form -->
                <div class="box box-info box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">@lang('blade.task')</h3>
                    </div>

                    <div class="box-body table-responsive no-padding">
                        <table class="table">
                            <tr>
                                <th></th>
                                <th></th>
                            </tr>
                            <tr>
                                <td>@lang('blade.purpose')</td>
                                <td>@lang('blade.to_resolution')</td>
                            </tr>
                            <tr>
                                <td>@lang('blade.sender')</td>
                                <td>{{ $model->from_name }}</td>
                            </tr>
                            <tr>
                                <td>@lang('blade.date')</td>
                                <td>{{ $model->created_at->format('d M. Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td>@lang('blade.status')</td>
                                <td><span class="label label-warning">@lang('blade.on_process')</span></td>
                            </tr>
                            <tr>
                                <td>1. {{ ($isRead->director->full_name) ?? 'Full Null' }}</td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                    <!-- /.box-body -->
                    @if($model->status == 0)
                        <div class="box-footer">
                            <button type="submit" class="btn btn-default">@lang('blade.forward_task')</button>
                            <button type="submit" class="btn btn-info btn-bitbucket pull-right">
                                <i class="glyphicon glyphicon-pushpin"></i> @lang('blade.add_mini_task')
                            </button>
                        </div>
                    @endif
                    <!-- /.box-footer -->

                    <div class="box-footer">
                    </div>
                    <!-- /.box-footer -->

                    <form role="form" method="POST" action="{{ route('edit-sub-performer') }}"
                          enctype="multipart/form-data">
                        {{csrf_field()}}

                        <div class="form-group">
                            <div class="box-body box-profile">
                                <ul class="list-group list-group-unbordered" id="selectedUsers">
                                  @foreach($perfSubUsers as $key => $user)
                                        @php
                                            $key = $key+1;
                                        @endphp

                                        <li class="list-group-item listItem{{$user->user_id}} selected-user">
                                            <button value="{{$user->user_id}}" type="button" class="close removeItem">
                                                <span aria-hidden="true">×</span></button>
                                            <div class="user-block">
                                                <img class="img-circle img-bordered-sm"
                                                     src="/../admin-lte/dist/img/user.png"
                                                     alt="User Image">
                                                <span class="pull-right btn-box-tool">
                                                    <select class="form-control" name="performer_user[]">
                                                        <option value="{{ $user->mes_type_id }}" selected>{{ $user->title_ru }}</option>
                                                        @foreach($perfUserTypes as $key => $type)

                                                            <option value="{{ $type->id }}">{{ $type->title_ru }}</option>

                                                        @endforeach
                                                    </select>
                                                </span>
                                                <div class="username">
                                                    {{ $user->full_name }}
                                                    <input value="{{ $user->user_id }}" name="to_user_id[]" hidden />
                                                </div>
                                                <div class="description">{{ $user->branch_code }} - {{ $user->job_title }}</div>
                                            </div>
                                        </li>

                                    @endforeach
                                </ul>
                            </div>
                            <!-- /.box-body -->
                        </div>

                        <div class="col-md-12 form-panel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="box-body">
                                                <label>@lang('blade.purpose')</label><sup class="text-red"> *</sup>
                                                @php
                                                    $data = $model->messageHelper->edo_type_message_id;
                                                    $type_m = \App\EdoTypeMessages::find($data);
                                                @endphp
                                                <select class="form-control select2 type_send"
                                                        name="edo_type_message_id">
                                                    <option value="{{ $type_m->id ?? 1 }}" selected> {{ $type_m->title_ru ?? 'Null' }}</option>
                                                    @foreach($messageTypes as $key => $value)

                                                        <option value="{{ $value->id }}"> {{ $value->title_ru }} </option>

                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="box-body">
                                                <label>@lang('blade.deadline')</label><sup class="text-red"> *</sup>
                                                <div class="input-group date">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                    <div class="input-group input-daterange">
                                                        <input type="text" name="term_date" value="{{ $model->messageHelper->term_date }}" class="form-control"
                                                               readonly/>
                                                    </div>
                                                </div>
                                                <!-- /.input group -->
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.form-group -->
                                </div>

                            </div>
                        </div>

                        <div class="col-md-pull-12 form-panel">

                            <div class="box-body">
                                <ul>
                                    @foreach($tasks as $task)

                                        <li class="tasks"><a>{{ $task->title }}</a></li>

                                    @endforeach
                                </ul>
                            </div>

                            <div class="box-body">
                                <div class="form-group">
                                    <textarea name="text" class="form-control" id="text_tasks" rows="5">{{ $model->messageHelper->text ?? 'Null' }}
                                    </textarea>

                                    <input name="message_user_id" value="{{ $isRead->to_user_id }}" hidden />
                                    <input name="model_id" value="{{ $model->id }}" hidden />
                                    <input name="jrl_id" value="{{ $model->journalUser->id }}" hidden />
                                </div>
                            </div>

                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right">
                                    <i class="fa fa-save"></i> @lang('blade.save')
                                </button>
                            </div>
                            <!-- /.box-footer -->
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>
            <!--/.col (right) -->
            <!-- right column -->

            <div class="col-md-4">

                <!-- Widget: user widget style 1 -->
                <div class="box box-widget widget-user-2">
                    <div class="widget-user-header bg-light-blue">
                        <div class="widget-user-image">
                            <img class="profile-user-img img-responsive" style="width: 35%; border: hidden" src="/admin-lte/dist/img/footer__logo.svg" alt="User Avatar">
                        </div>
                        <!-- /.widget-user-image -->
                        <h3 class="widget-user-username text-center">"TURONBANK" ATB</h3>
                        <h5 class="widget-user-desc text-center">{{$isRead->director->title}}</h5>
                    </div>
                    <div class="box-footer no-padding">
                        <ul class="nav nav-stacked">
                            @foreach($perfSubUsers as $key => $user)
                                @php($key = $key+1)
                                <li class="list-group-item">{{ $key++.'. '.$user->full_name }} <span class="pull-right badge bg-aqua">{{ $user->title_ru }}</span></li>

                            @endforeach
                        </ul>
                        <div class="box-body">
                            <h5 class="text-center">{{ $model->messageSubHelper->text ?? 'Null' }}</h5><hr>
                            <h5 class="text-bold">№ {{ $model->out_number ?? 'Null' }}
                                <span class="pull-right">{{ ($isRead->director->full_name) ?? 'Null' }}.</span>
                            </h5>
                            <h5>{{ $model->updated_at->format('d M. Y H:i') }}<span class="pull-right">{{ $isRead->director->job_title }} </span></h5>
                        </div>

                        <div class="box-footer">
                            <div class="slider__pagination-item ng-scope is-active" title="{{ ($isRead->director->full_name) ?? 'Null' }}">
                                <div class="slider__thumb">
                                </div>
                            </div>
                        </div>

                        <div class="box-footer">
                            @if($isRead->sub_status == 1 && ($role->role_code == 'guide' || $role->role_code == 'director_department'))

                                <a href="{{ url('guide-confirm', $model->id) }}" type="submit" class="btn btn-dropbox pull-right">
                                    <i class="glyphicon glyphicon-ok"></i> @lang('blade.approve')
                                </a>

                            @endif

                            <a href="{{ url('edo/edit-performer', $model->message_hash) }}" type="submit" class="btn btn-bitbucket">
                                <i class="glyphicon glyphicon-pencil"></i> @lang('blade.update')
                            </a>
                        </div>
                        <!-- /.box-footer -->
                    </div>
                </div>
                <!-- /.widget-user -->
                <!-- Horizontal Form -->
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">@lang('blade.select_receiver')</h3><sup class="text-red"> *</sup>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="box-tools">
                            <div class="form-group has-success has-feedback">
                                <input type="text" class="form-control"
                                       placeholder="Ijrochilarni qidiring">
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                                <span class="help-block">@lang('blade.at_least_3_letters')</span>
                            </div>

                            <div class="form-group">
                                <ul class="list-group" id="unSelectedUsers" style="overflow-y: scroll; max-height: 500px;">
                                    @foreach($users as $key => $value)

                                        <li class="list-group-item select-user listItem{{$value->u_id}}">
                                            <button value="{{$value->u_id}}" type="button" class="close removeItem">
                                                <span aria-hidden="true">×</span></button>
                                            <div class="user-block">
                                                <img class="img-circle img-bordered-sm"
                                                     src="/../admin-lte/dist/img/user.png"
                                                     alt="User Image">
                                                <span class="pull-right btn-box-tool performer_type">
                                                    <select class="form-control" name="performer_user[]">
                                                        @foreach($perfUserTypes as $key => $type)

                                                            <option value="{{ $type->id }}">{{ $type->title_ru }}</option>

                                                        @endforeach
                                                    </select>
                                                </span>
                                                <div class="username">
                                                    {{ $value->full_name }}
                                                    <input value="{{ $value->user_id }}" name="to_user_id[]" hidden />
                                                </div>
                                                <div class="description">{{ $value->branch_code }} - {{ $value->job_title }}</div>
                                            </div>
                                        </li>

                                    @endforeach
                                </ul>
                            </div>
                            <!-- /.box-body -->
                        </div>

                    </div>

                </div>
                <!-- /.box -->
            </div>
            <!--/.col (right) -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->

    <!-- Main content -->
    <section class="content">
        <!-- AdminLTE App -->

        <script src="{{ asset("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>
        <!-- Select2 -->
        <script src="{{ asset("/admin-lte/plugins/select2/select2.full.min.js") }}"></script>

        <link href="{{ asset ("/admin-lte/bootstrap/css/bootstrap-datepicker.css") }}" rel="stylesheet"/>
        <script src="{{ asset ("/admin-lte/bootstrap/js/datepicker/bootstrap.min.js") }}"></script>
        <script src="{{ asset ("/admin-lte/bootstrap/js/bootstrap-datepicker.js") }}"></script>
        <!-- Bootstrap 3.3.6 -->
        <script src="{{ asset ("admin-lte/bootstrap/js/bootstrap.min.js") }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ asset ("admin-lte/dist/js/app.min.js") }}"></script>

        <script type="text/javascript">
            $(document).ready(function () {


                // Send type for create
                $('.send-type-div').hide();

                $('.type_send').on('change', function () {

                    if (this.value == 4) {

                        $(".send-type-div").show();

                    } else {

                        $(".send-type-div").hide();
                    }
                });

                // For Users
                $('.btn-bitbucket').on('click', function () {

                    $('#edoUsers').slideToggle(800);
                    $('.form-panel').slideToggle(800);

                });

                // add user (in right)
                $('.performer_type').hide();
                $('.select-user .removeItem').hide();
                $('ul').on('click', '.select-user', function () {

                    $(this).removeClass('select-user').addClass('unselect-user').appendTo('ul#selectedUsers');

                    $('#selectedUsers .performer_type').show();

                    $('.unselect-user .removeItem').show();

                });

                // remove selected user (in left)
                $('.removeItem').click(function () {

                    var listVal = $(this).val();

                    var listItem = '.listItem'+listVal;

                    $(listItem).removeClass('unselect-user').removeClass('selected-user').addClass('select-user').appendTo('ul#unSelectedUsers');

                    $(listItem+'.select-user span').addClass('performer_type');

                    $('.select-user .performer_type').hide();

                    $('.select-user .removeItem').hide();
                });

                // Tasks
                $('.tasks a').click(function () {

                    var tasks = $(this).text();

                    $('#text_tasks').empty().append(tasks);

                });

            });


            //Date picker
            $('#datepicker').datepicker({
                autoclose: true
            });
            $('.input-datepicker').datepicker({
                todayBtn: 'linked',
                format: 'yyyy-mm-dd',
                autoclose: true
            });
            $('.input-daterange').datepicker({
                todayBtn: 'linked',
                format: 'yyyy-mm-dd',
                autoclose: true
            });
        </script>
    </section>
    <!-- /.content -->
@endsection