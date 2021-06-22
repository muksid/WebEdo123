<?php ?>
@extends('layouts.edo.dashboard')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            @if ($message = Session::get('success'))
                <div class="modal fade in" id="myModal" role="dialog" style="display: block">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header bg-aqua-active">
                                <h4 class="modal-title">
                                    @lang('blade.task') <i class="fa fa-check-circle"></i>
                                </h4>
                            </div>
                            <div class="modal-body">
                                <h3>{{ $message }}</h3>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-info closeModal" data-dismiss="modal"><i class="fa fa-check-circle"></i> Ok</button>
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
            <!-- left column -->
            <div class="col-md-4">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-file margin-r-5"></i> @lang('blade.doc')</h3>
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
                                <td>{{ $model->journalUser->messageType->title }}</td>
                            </tr>
                        </table>
                    </div>
                    <h4><i class="fa fa-backward"></i></h4>
                    <div class="box-body no-padding">
                        <table class="table table-condensed">
                            <tr>
                                <th style="width: 50%"><i class="fa fa-sort-numeric-asc"></i> @lang('blade.incoming_num')</th>
                                <th><i class="fa fa-clock-o"></i> @lang('blade.incoming_date')</th>
                            </tr>
                            <tr>
                                <td style="width: 50%">{{ $model->in_number }}</td>
                                <td>{{ date('d M, Y', strtotime($model->in_date)) }}</td>
                            </tr>
                        </table>
                    </div>
                    <h4><i class="fa fa-forward"></i></h4>
                    <div class="box-body no-padding">
                        <table class="table table-condensed">
                            <tr>
                                <th><i class="fa fa-sort-numeric-asc"></i> @lang('blade.outgoing_num')</th>
                                <th><i class="fa fa-clock-o"></i> @lang('blade.outgoing_date')</th>
                            </tr>
                            <tr>
                                <td>{{ $model->out_number }}</td>
                                <td>{{ \Carbon\Carbon::parse($model->out_date)->format('j F, Y') }}</td>
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

                        <!-- Attachment -->
                        @foreach ($model->files as $file)
                            <a href="#"
                                class="text-info text-bold mailbox-attachment-name"
                                target="_blank"
                                onclick="window.open('<?php echo('/edo-fileView/' . $file->id); ?>',
                                        'modal',
                                        'width=800,height=900,top=30,left=500');
                                        return false;">
                                <i class="fa fa-search-plus"></i> {{ \Illuminate\Support\Str::limit($file->file_name, 35,'...') }}
                            </a>
                            <ul class="list-inline pull-right">
                                <li>
                                    <a href="{{ url('edo-fileDownload',['id'=>$file->id]) }}"
                                        class="link-black text-sm"><i
                                                class="fa fa-cloud-download text-primary"></i> @lang('blade.download')
                                    </a>
                                </li>
                            </ul>
                            <i class="text-red">({{ $file->size($file->file_size)??'' }})</i>
                            <br><br>
                        @endforeach
                        <hr>
                        <p class="text-bold text-center">{{ $model->title }}</p>
                        <?php echo $model->text ?? 'null';  ?>
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
                                <td class="text-muted">@lang('blade.purpose')</td>
                                <td>@lang('blade.to_resolution')</td>
                            </tr>
                            <tr>
                                <td class="text-muted">@lang('blade.sender')</td>
                                <td>{{$model->journalUser->officeUser->full_name ?? 'null'}}
                                    <span class="text-maroon">({{ $model->journalUser->userJob->userRole->title_ru }})</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">@lang('blade.date')</td>
                                <td>{{ $model->created_at->format('d-M-Y H:i') }}<span class="text-maroon"> ({{$model->created_at->diffForHumans()}})</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">@lang('blade.status')</td>
                                <td><span class="label label-warning">@lang('blade.on_process')</span></td>
                            </tr>
                            <tr>
                                <td>1. {{$model->journalUser->guideUser->full_name}}</td>
                                <td>
                                    @if($model->urgent == 1)
                                        <div class="text-maroon"><i class="fa fa-bell-o text-red fa-lg"></i> @lang('blade.urgent')</div>
                                    @endif
                                </td>
                            </tr>

                            @if(count($redirectTasks))
                                <tr>
                                    <td class="text-muted">@lang('blade.execution_steps')</td>
                                    <td>
                                        @foreach($redirectTasks as $key => $value)
                                                <div class="post clearfix">
                                                    <div class="user-block">
                                                        <img class="img-circle img-bordered-sm" src="{{ url('/admin-lte/dist/img/user.png') }}">
                                                        <span class="username">
                                                          <a href="#">
                                                                    {{mb_substr($value->fromUser->fname??'', 0,1).'.'
                                                                    .mb_substr($value->fromUser->sname??'', 0,1).'.'
                                                                    .$value->fromUser->lname??''}}
                                                          </a>
                                                        </span>
                                                        <span class="description">
                                                            {{ \Carbon\Carbon::parse($model->created_at)->format('d-M-Y')}}
                                                            <span class="text-maroon">
                                                                ({{$model->created_at->diffForHumans()}})
                                                            </span>
                                                        </span>
                                                    </div>
                                                    <!-- /.user-block -->
                                                    <p>{{ $value->redirect_desc }}</p>
                                                    <p><i class="fa fa-angle-double-down"></i></p>
                                                    <span class="username">
                                                        <i class="fa fa-forward"></i>
                                                          <a href="#" class="text-maroon">
                                                                    {{mb_substr($value->user->fname??'', 0,1).'.'
                                                                    .mb_substr($value->user->sname??'', 0,1).'.'
                                                                    .$value->user->lname??''}}
                                                          </a>
                                                        </span>
                                                </div>
                                        @endforeach

                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                    <!-- /.box-body -->
                    @if($model->journalUser->status == 0)
                        <form role="form" method="POST" action="{{ route('dep-reg-task1') }}"
                              enctype="multipart/form-data">
                            {{csrf_field()}}

                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <h4 class="text-center">Kelgan xatlarni ro`yhatga olish</h4>
                                                <div class="form-group">
                                                    <div class="box-body">
                                                        <label>@lang('blade.sender_organization')</label><sup
                                                                class="text-red"> *</sup>
                                                        <input name="from_name" value="{{ $model->from_name }}"
                                                               class="form-control" required/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="box-body">
                                                        <label>@lang('blade.doc_name')</label><sup class="text-red"> *</sup>
                                                        <input name="title" value="{{ $model->title }}"
                                                               class="form-control" required/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="box-body">
                                                        <label>@lang('blade.reg_num')</label><sup class="text-red"> *</sup>
                                                        <input type="number" name="in_number" value="{{ $inboxNum }}"
                                                               class="form-control" required/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="box-body">
                                                        <label>Reg# /a</label>
                                                        <input type="text" name="in_number_a"
                                                               class="form-control" placeholder="/a" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box-body">
                                            <div class="form-group">
                                                <input name="edo_message_id" value="{{ $model->id }}" hidden/>
                                                <input name="depart_id" value="{{ $department->department_id }}" hidden/>
                                                <input name="director_id" value="{{ $department->user_id }}" hidden/>
                                                <input name="user_id" value="{{ Auth::id() }}" hidden/>
                                            </div>
                                        </div>
                                        <div class="box-footer">
                                            <button type="submit" class="btn btn-info pull-right">
                                                <i class="fa fa-save"></i> @lang('blade.save')
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </form>
                        @elseif($model->journalUser->status == 1)
                        <div class="box-footer">
                            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#redirectModal">@lang('blade.forward_task')</button>
                            <button type="submit" class="btn btn-info btn-bitbucket pull-right">
                                <i class="glyphicon glyphicon-pushpin"></i> @lang('blade.add_mini_task')
                            </button>
                        </div>
                    @endif
                    <!-- /.box-footer -->

                    <div class="box-footer">
                    </div>
                    <!-- /.box-footer -->

                    <form role="form" method="POST" action="{{ route('create-e-task-emp') }}"
                          enctype="multipart/form-data">
                        {{csrf_field()}}

                        <div class="form-group">
                            <div class="box-body box-profile">
                                <ul class="list-group list-group-unbordered" id="selectedUsers">
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
                                                <select class="form-control select2"
                                                        name="edo_type_message_id" required>
                                                    <option value="" selected> @lang('blade.select')</option>
                                                    @foreach($messageTypes as $key => $value)

                                                        <option value="{{ $value->id }}"> {{ $value->title }} </option>

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
                                                <label>@lang('blade.deadline')</label>
                                                <div class="input-group date">
                                                    <div class="input-group input-daterange">
                                                        <input type="text" name="term_date" class="form-control"
                                                               readonly/>
                                                    </div>
                                                    <div id="resetTermDate" class="input-group-addon">
                                                        <i class="fa fa-remove text-maroon"></i>
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
                                    <textarea name="text" id="editor"></textarea>
                                    <input name="model_id" value="{{ $model->id }}" hidden />
                                    <input name="depart_id" value="{{ $department->department_id }}" hidden />
                                    <input name="jrl_id" value="{{ $model->journalUser->id }}" hidden />

                                </div>
                            </div>

                            <div class="box-footer">
                                <button type="submit" class="btn btn-dropbox pull-right">
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
            @if($model->journalUser->status == 2)
                <!-- Widget: user widget style 1 -->
                    <div class="box box-widget widget-user-2">
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <div class="widget-user-header bg-blue-gradient">
                            <img class="profile-user-img img-responsive" style="width: 40%; border: hidden" src="/admin-lte/dist/img/footer__logo.svg" alt="Turonbank"><br>
                            <h2 class="profile-username text-center">@lang('blade.tb')</h2>
                        </div>
                        <div class="box-footer no-padding">
                            <ul class="nav nav-stacked">
                                @foreach($perfUsers as $key => $user)
                                        @php($key = $key+1)
                                    <li class="list-group-item">{{ $key++.'. '.$user->full_name }}
                                        <span class="pull-right badge bg-aqua">{{ $user->title_ru }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="box-body">
                                <h5 class="text-center">
                                   <?php
                                        echo $model->helperMessageType->text ?? 'null';
                                   ?>
                                    @if(!empty($model->messageHelper->term_date))
                                        <br/><br/>
                                        <span class="pull-left text-maroon">
                                            <b><i class="fa fa-check-circle-o"></i> {{ $model->messageHelper->controlType->title?? 'null' }}</b>
                                        </span>
                                        <span class="pull-right text-red">Muddat: {{ $model->messageHelper->term_date ?? 'null' }}</span>
                                    @endif</h5><hr>
                                <h5 class="text-bold">№ {{ $model->out_number ?? 'Null' }}
                                    <span class="pull-right">{{ $model->journalUser->guideUser->full_name }}.</span>
                                </h5>
                                <h5><span class="pull-left">{{ $model->messageHelper->updated_at->format('d M. Y H:i') }} </span><span
                                            class="pull-right">{{ $model->journalUser->guideUser->job_title?? 'null' }}.</span></h5>
                            </div><hr>

                            <div class="box-footer">
                                @if($model->journalUser->status == 1 && ($role->role_code == 'guide' || $role->role_code == 'director_department'))

                                    <a href="{{ route('guide-task-confirm', $model->id) }}" type="submit" class="btn btn-dropbox pull-right">
                                        <i class="glyphicon glyphicon-ok"></i> @lang('blade.approve')
                                    </a>

                                @endif

                                <a href="{{ route('edit-guide-task', $model->message_hash) }}" type="submit" class="btn btn-bitbucket">
                                    <i class="glyphicon glyphicon-pencil"></i> @lang('blade.update')
                                </a>
                            </div>
                            <!-- /.box-footer -->
                        </div>
                    </div>
                    <!-- /.widget-user -->
            @elseif($model->status == 3)
                <!-- Widget: user widget style 1 -->
                    <div class="box box-widget widget-user-2">
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <div class="widget-user-header bg-blue-gradient">
                            <img class="profile-user-img img-responsive" style="width: 40%; border: hidden" src="/admin-lte/dist/img/footer__logo.svg" alt="Turonbank"><br>
                            <h2 class="profile-username text-center">@lang('blade.tb')</h2>
                        </div>
                        <div class="box-footer no-padding">
                            <ul class="nav nav-stacked">
                                @foreach($perfUsers as $key => $user)
                                    @php($key = $key+1)
                                    <li class="list-group-item">{{ $key++.'. '.$user->full_name }} <span class="pull-right badge bg-aqua">{{ $user->title_ru }}</span></li>

                                @endforeach
                            </ul>
                            <div class="box-body">
                                <h5 class="text-center">{{ $model->messageHelper->text ?? 'Null' }}</h5><hr>
                                <h5 class="text-bold">№ {{ $model->message->out_number ?? 'Null' }} <span
                                            class="pull-right">{{ $model->toUser->full_name ?? 'Null' }}.</span>
                                </h5>
                                <h5><span class="pull-right">{{ $model->messageHelper->updated_at->format('d M. Y H:i') }} </span></h5>
                            </div>

                            <div class="box-footer">
                                <div class="slider__pagination-item ng-scope is-active" title="{{ $model->toUser->full_name ?? 'Null' }}">
                                    <div class="slider__thumb">
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-footer -->
                        </div>
                    </div>
                    <!-- /.widget-user -->
            @endif

            </div>

            <div class="col-md-4" id="edoUsers">
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
                                       placeholder="@lang('blade.search_executer')">
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

                                                            <option value="{{ $type->id }}"><label for="txt206451"></label>{{ $type->title_ru }}</option>

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

            <div class="modal fade" id="redirectModal" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header bg-aqua-active">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title"><i class="fa fa-reply"></i> Vazifani yo`naltirish</h4>
                        </div>
                        <form method="POST" action="{{ route('redirect-task') }}" class="form-horizontal">
                            {{csrf_field()}}
                            <div class="modal-body">

                                    <label>@lang('blade.select_receiver')</label><sup class="text-red"> *</sup>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <select name="to_guide_id" class="form-control">
                                                @foreach($redirectDepartUsers as $key => $value)
                                                    @if($value->user_id == $model->journalUser->guideUser->user_id)
                                                    <option disabled>{{ $value->full_name }}</option>
                                                    @else
                                                    <option value="{{ $value->user_id }}">{{ $value->full_name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <label>@lang('blade.leave_comment')</label><sup class="text-red"> *</sup>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <input name="user_id" value="{{ Auth::id() }}" hidden/>
                                            <input name="from_guide_id" value="{{ $model->journalUser->guideUser->user_id }}" hidden/>
                                            <input name="edo_message_id" value="{{ $model->id }}" hidden/>
                                            <input name="jrnl_id" value="{{ $model->journalUser->id }}" hidden/>
                                            <input name="status" value="1" hidden/>
                                            <textarea name="redirect_desc" class="form-control" rows="3" required></textarea>
                                        </div>
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> @lang('blade.save')</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('blade.cancel')</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->

    <!-- Main content -->
    <section class="content">
        <!-- Select2 -->
        <script src="{{ asset("/admin-lte/plugins/select2/select2.full.min.js") }}"></script>

        <link href="{{ asset ("/admin-lte/bootstrap/css/bootstrap-datepicker.css") }}" rel="stylesheet"/>
        {{--<script src="{{ asset ("/admin-lte/bootstrap/js/datepicker/bootstrap.min.js") }}"></script>--}}
        <script src="{{ asset ("/admin-lte/bootstrap/js/bootstrap-datepicker.js") }}"></script>

        <!-- ckeditor -->
        <script src="{{ asset ("/ckeditor/ckeditor.js") }}"></script>
        <script src="{{ asset ("/ckeditor/samples/js/sample.js") }}"></script>

        <script type="text/javascript">

            initSample();

            $(document).ready(function () {

                // Send type for create
                /*$('.send-type-div').hide();

                $('.type_send').on('change', function () {

                    if ($(this).val() == 23) {
                        $(".send-type-div").show();
                    } else {
                        $(".send-type-div").hide();
                    }
                });*/

                // For Users
                $('#edoUsers').hide();

                $('.form-panel').hide();

                $('.btn-bitbucket').on('click', function () {

                    $('#edoUsers').slideToggle(800);

                    $('.form-panel').slideToggle(800);

                });

                // add user (in right)
                $('.performer_type').hide();
                $('ul').on('click', '.select-user', function () {

                    $(this).removeClass('select-user').addClass('unselect-user').appendTo('ul#selectedUsers');

                    $('#selectedUsers .performer_type').show();

                    $('.unselect-user .removeItem').show();

                });

                // remove selected user (in left)
                $('.removeItem').hide();

                $('.removeItem').click(function () {

                    var listVal = $(this).val();

                    var listItem = '.listItem'+listVal;

                    $(listItem).removeClass('unselect-user').addClass('select-user').appendTo('ul#unSelectedUsers');

                    $(listItem+'.select-user span').addClass('performer_type');

                    $('.select-user .performer_type').hide();
                });

                // Tasks
                $('.tasks a').click(function () {
                    var tasks = $(this).text();

                    CKEDITOR.instances.editor.insertText(tasks);

                });

                // close Modal
                $('.closeModal').click(function () {

                    $('#myModal').hide();

                });

                // reset date
                $('#resetTermDate').click(function () {

                    $('input[name=term_date]').val('');
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
                startDate: '-Infinity',
                autoclose: true
            });
        </script>
    </section>
    <!-- /.content -->
@endsection