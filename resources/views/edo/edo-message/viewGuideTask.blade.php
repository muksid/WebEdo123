<?php ?>
@extends('layouts.edo.dashboard')

@section('content')

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- Message Succes -->
            @if ($message = Session::get('success'))
                <div class="modal fade in" id="myModal" role="dialog" style="display: block">
                    <div class="modal-dialog modal-sm">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header bg-aqua-active">
                                <h4 class="modal-title">
                                    Vazifa <i class="fa fa-check-circle"></i>
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
        <!-- left column -->
            <div class="col-md-4">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-file margin-r-5"></i> @lang('blade.doc')</h3>
                    </div>
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
                                <th style="width: 50%"><i
                                            class="fa fa-sort-numeric-asc"></i> @lang('blade.incoming_num')</th>
                                <th><i class="fa fa-clock-o"></i> @lang('blade.incoming_date')</th>
                            </tr>
                            <tr>
                                <td style="width: 50%">{{ $model->in_number }}</td>
                                <td>{{ \Carbon\Carbon::parse($model->in_date)->format('j F, Y') }}</td>
                            </tr>
                        </table>
                    </div>
                    <h4><i class="fa fa-forward"></i></h4>
                    <div class="box-body no-padding">
                        <table class="table table-condensed">
                            <tr>
                                <th style="width: 50%"><i
                                            class="fa fa-sort-numeric-asc"></i> @lang('blade.outgoing_num')</th>
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
                        <strong><i class="fa fa-paperclip margin-r-5"></i> @lang('blade.doc_app') </strong><br><br>

                        @foreach ($model->files as $file)
                            <div id="fileId_{{$file->id}}">
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
                                    @if($role->id === 2)
                                        <li> |</li>
                                        <li class="pull-right">
                                            <button class="btn btn-xs btn-danger deleteFile" data-id="{{ $file->id }}">
                                                <i class="fa fa-trash"></i> @lang('blade.delete')
                                            </button>
                                        </li>
                                    @endif
                                </ul>
                                <i class="text-red">({{ $file->size($file->file_size)??'' }}
                                    )</i><br><br>
                            </div><br>
                        @endforeach

                        <div class="col-md-12">
                            <div class="alert" id="message" style="display: none"></div>
                            <form method="post" id="fileUpload" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <table class="table">
                                        <tr>
                                            <td width="30">
                                                <input type="file" name="message_file[]" id="message_file" multiple />
                                                <input type="text" name="model_id" value="{{ $model->id }}" hidden />
                                            </td>
                                            <td width="30%" align="left">
                                                <button type="submit" name="upload" id="upload" class="btn btn-flat btn-xs btn-info">
                                                    <i class="fa fa-upload"></i> @lang('blade.upload_file')
                                                </button>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </form>

                            <div id="ConfirmModal" class="modal fade text-danger" role="dialog">
                                <div class="modal-dialog modal-sm">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title text-center">O`chirishni tasdiqlash</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p class="text-center">Siz xatni o`chirmoqchimisiz?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <center>
                                                <button type="button" class="btn btn-success" data-dismiss="modal">Bekor
                                                    qilish
                                                </button>
                                                <button type="button" href="#" id="yesDelete" class="btn btn-danger">
                                                    Ha, O`chirish
                                                </button>
                                            </center>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="successModal" tabindex="-1" role="dialog"
                                 aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <div class="modal-header bg-aqua-active">
                                            <h4 class="modal-title">
                                                File <i class="fa fa-check-circle"></i>
                                            </h4>
                                        </div>
                                        <div class="modal-body">
                                            <h5>File Successfully deleted</h5>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-info closeModal" data-dismiss="modal"><i
                                                        class="fa fa-check-circle"></i> Ok
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <hr>
                        @if(!empty($model->title))
                            <p class="text-bold text-center">{{ $model->title }}</p>
                        @else
                            <p class="text-muted text-center">(Mavzu yo`q)</p>
                        @endif

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
                                <td>
                                    <span class="label label-warning">@lang('blade.on_process')</span>
                                </td>
                            </tr>
                            <tr>
                                <td>1. {{$model->journalUser->guideUser->full_name}}</td>
                                <td>
                                    @if($model->urgent == 1)
                                        <div class="text-maroon"><i
                                                    class="fa fa-bell-o text-red fa-lg"></i> @lang('blade.urgent')</div>
                                    @endif
                                </td>
                            </tr>
                            @if($model->journalUser->status == -1)
                                <tr>
                                    <td class="text-muted">@lang('blade.cancel_task')</td>
                                    <td>
                                        @if($cancelModel)
                                            @foreach($cancelModel as $key => $value)
                                                <div class="post clearfix">
                                                    <div class="user-block">
                                                        <img class="img-circle img-bordered-sm"
                                                             src="{{ url('/admin-lte/dist/img/user.png') }}">
                                                        <span class="username">
                                                          <a href="#">
                                                                    {{mb_substr($value->user->fname??'', 0,1).'.'
                                                                    .mb_substr($value->user->sname??'', 0,1).'.'
                                                                    .$value->user->lname??''}}
                                                          </a>
                                                        </span>
                                                        <span class="description">
                                                            {{ \Carbon\Carbon::parse($value->created_at)->format('d-M-Y')}}
                                                            <span class="text-maroon">
                                                                ({{$value->created_at->diffForHumans()}})
                                                            </span>
                                                        </span>
                                                    </div>
                                                    <!-- /.user-block -->
                                                    <p><i class="text-bold">@lang('blade.comment')
                                                            : </i>{{ $value->description }}</p>
                                                </div>
                                            @endforeach
                                        @endif
                                        <button type="button" class="btn btn-default" data-toggle="modal"
                                                data-target="#redirectModal">@lang('blade.forward_task')</button>
                                        <button type="submit" id="letterChip"
                                                class="btn btn-info btn-bitbucket pull-right">
                                            <i class="glyphicon glyphicon-pushpin"></i> @lang('blade.add_mini_task')
                                        </button>

                                    </td>
                                </tr>
                            @endif

                            @if(count($redirectTasks))
                                <tr>
                                    <td class="text-muted">@lang('blade.forward_steps')</td>
                                    <td>
                                        @foreach($redirectTasks as $key => $value)
                                            <div class="post clearfix">
                                                <div class="user-block">
                                                    <img class="img-circle img-bordered-sm"
                                                         src="{{ url('/admin-lte/dist/img/user.png') }}">
                                                    <span class="username">
                                                          <a href="#">
                                                                    {{mb_substr($value->fromUser->fname??'', 0,1).'.'
                                                                    .mb_substr($value->fromUser->sname??'', 0,1).'.'
                                                                    .$value->fromUser->lname??''}}
                                                          </a>
                                                        </span>
                                                    <span class="description">
                                                            {{ \Carbon\Carbon::parse($value->created_at)->format('d-M-Y')}}
                                                            <span class="text-maroon">
                                                                ({{$value->created_at->diffForHumans()}})
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
                        <div class="box-footer">
                            <button type="button" class="btn btn-default" data-toggle="modal"
                                    data-target="#redirectModal">@lang('blade.forward_task')</button>
                            <button type="submit" id="letterChip" class="btn btn-info btn-bitbucket pull-right">
                                <i class="glyphicon glyphicon-pushpin"></i> @lang('blade.add_mini_task')
                            </button>
                        </div>

                @endif
                <!-- /.box-footer -->

                    <div class="box-footer">
                    </div>
                    <!-- /.box-footer -->

                    <form role="form" method="POST" action="{{ route('create-g-s_task') }}"
                          enctype="multipart/form-data" id="formResolution">
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
                                                <select class="form-control select2 type_send"
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

                                <div class="col-md-6 send-type-div">
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
                                    <input name="model_id" value="{{ $model->id }}" hidden/>
                                    <input name="jrnl_id" value="{{ $model->journalUser->id }}" hidden/>

                                </div>
                            </div>

                            <div class="box-footer">
                                <button type="submit" class="btn btn-dropbox pull-right" id="saveResolutionBtn">
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
            @if($model->journalUser->status == 1)
                    <div class="box box-widget widget-user-2">
                        <div class="widget-user-header bg-blue-gradient">
                            <img class="profile-user-img img-responsive" style="width: 40%; border: hidden"
                                 src="{{ asset("/admin-lte/dist/img/footer__logo.svg") }}"><br>
                            <h2 class="profile-username text-center">@lang('blade.tb_wide')</h2>
                        </div>
                        <div class="box-footer no-padding">
                            <ul class="nav nav-stacked">
                                <?php
                                $i = 1;
                                $sort = array(1,2,3,4);
                                ?>
                                @foreach($perfUsers->sortBy('user_sort') as $key => $user)
                                    @if($user->sort != 1)
                                    @switch($user->user_sort)
                                        @case(1)
                                        @case(2)
                                        @case(3)
                                        @case(4)
                                        <li class="list-group-item">{{ $i++.'. '.$user->full_name}}
                                            <span class="pull-right badge bg-aqua">{{ $user->title_ru }}</span>
                                        </li>
                                        @break
                                    @endswitch
                                        @endif
                                @endforeach

                               @foreach($perfUsers as $key => $user)
                                    @if(!in_array($user->user_sort, $sort) && $user->sort != 1 && $user->sort != 2)
                                        <li class="list-group-item">{{ $i++.'. '.$user->full_name }}
                                            <span class="pull-right badge bg-aqua">{{ $user->title_ru }}</span>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>

                            <ul class="nav nav-stacked">
                                @foreach($perfUsers as $element)
                                    @if($element->sort == 1)
                                        <li class="list-group-item">{{ $i++ }}. @lang('blade.all_dep_name')
                                            <span class="pull-right badge bg-aqua">@lang('blade.executors')</span>
                                        </li>
                                        @break
                                    @endif
                                @endforeach

                                @foreach($perfUsers as $element)
                                    @if($element->sort == 2)
                                        <li class="list-group-item">{{ $i++ }}. @lang('blade.all_filial_name')
                                            <span class="pull-right badge bg-aqua">@lang('blade.executors')</span>
                                        </li>
                                        @break
                                    @endif
                                @endforeach
                            </ul>

                            <div class="box-body">
                                <h5 class="text-center">
                                    <?php

                                    echo $model->helperMessageType->text ?? 'null';

                                    $control = '';
                                    $controlIcon = '';
                                    if ($model->messageHelper->controlType->type_message_code == 'control') {
                                        $control = 'text-maroon';
                                        $controlIcon = '<i class="fa fa-check-circle-o"></i>';
                                    }

                                    ?>
                                    <br/><br/>
                                    <span class="pull-left {{ $control }}">
                                            <b><?php echo $controlIcon?> {{ $model->messageHelper->controlType->title?? 'null' }}</b>
                                        </span>
                                    @if($model->messageHelper->term_date != null)
                                        <span class="pull-right text-red">@lang('blade.deadline'): {{ $model->messageHelper->term_date??'' }}</span>

                                    @endif
                                </h5>
                                <hr>
                                <h5 class="text-bold">№ {{ $model->in_number ?? 'Null' }}
                                    <span class="pull-right">{{ $model->journalUser->guideUser->full_name }}.</span>
                                </h5>
                                <h5>
                                    <span class="pull-left">{{ $model->messageHelper->updated_at->format('d.m.Y H:i') }} </span><span
                                            class="pull-right">{{ $model->journalUser->guideUser->job_title?? 'null' }}.</span>
                                </h5>
                            </div>

                            <div class="box-footer">
                                <div class="modal-footer" style="margin-top:0;">
                                    <div style="float:left;">
                                        <a href="{{ route('edit-guide-task', $model->message_hash) }}" type="submit"
                                           class="btn btn-bitbucket">
                                            <i class="glyphicon glyphicon-pencil"></i> @lang('blade.update')
                                        </a>
                                    </div>

                                    <div style="float:right">

                                        @if($model->journalUser->status == 1 && ($role->role_code == 'guide' ||
                                            $role->role_code == 'guide_manager' ||
                                            $role->role_code == 'director_department'))

                                            <button type="button" class="btn btn-danger" data-toggle="modal"
                                                    data-target="#cancelModal"><i
                                                        class="fa fa-ban"></i> @lang('blade.cancel')</button>

                                            <a href="{{ route('guide-task-confirm', $model->id) }}" type="submit"
                                               class="btn btn-dropbox pull-right">
                                                <i class="glyphicon glyphicon-ok"></i> @lang('blade.approve')
                                            </a>

                                        @endif
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

            @elseif($model->status == 2)

                    <div class="box box-widget widget-user-2">
                        <div class="widget-user-header bg-blue-gradient">
                            <img class="profile-user-img img-responsive" style="width: 40%; border: hidden"
                                 src="{{ asset("/admin-lte/dist/img/footer__logo.svg") }}" alt="Turonbank"><br>
                            <h2 class="profile-username text-center">@lang('blade.tb')</h2>
                        </div>
                        <div class="box-footer no-padding">
                            <ul class="nav nav-stacked">
                                @foreach($perfUsers as $key => $user)
                                    @php($key = $key+1)
                                    <li class="list-group-item">{{ $key++.'. '.$user->full_name }} <span
                                                class="pull-right badge bg-aqua">{{ $user->title_ru }}</span></li>

                                @endforeach
                            </ul>
                            <div class="box-body">
                                <h5 class="text-center">{{ $model->messageHelper->text ?? 'Null' }}</h5>
                                <hr>
                                <h5 class="text-bold">№ {{ $model->message->out_number ?? 'Null' }} <span
                                            class="pull-right">{{ $model->toUser->full_name ?? 'Null' }}.</span>
                                </h5>
                                <h5>
                                    <span class="pull-right">{{ $model->messageHelper->updated_at->format('d M. Y H:i') }} </span>
                                </h5>
                            </div>

                            <div class="box-footer">
                                <div class="slider__pagination-item ng-scope is-active"
                                     title="{{ $model->toUser->full_name ?? 'Null' }}">
                                    <div class="slider__thumb">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            <div class="col-md-4" id="edoUsers">
                <!-- Horizontal Form -->
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">@lang('blade.select_receiver')</h3><sup class="text-red"> *</sup>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="box-tools">
                            <div class="form-group has-success has-feedback">
                                <input type="text" id="userSearch" class="form-control" onkeyup="userSearchFunction()"
                                       placeholder="@lang('blade.search_executors')">
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                                <span class="help-block">@lang('blade.at_least_3_letters')</span>
                            </div>

                            <div class="form-group">
                                <ul class="list-group" id="unSelectedUsers"
                                    style="overflow-y: scroll; max-height: 700px;">
                                    @foreach($users as $key => $value)

                                        <li class="list-group-item select-user listItem{{$value->user_id}}">
                                            <button value="{{$value->user_id}}" type="button" class="close removeItem">
                                                <span aria-hidden="true">×</span></button>
                                            <div class="user-block">
                                                <img class="img-circle img-bordered-sm"
                                                     src="{{ asset("/admin-lte/dist/img/user.png") }}"
                                                     alt="User Image">
                                                <span class="pull-right btn-box-tool performer_type">
                                                    <select class="form-control" name="performer_user[]">
                                                        @foreach($perfUserTypes as $key => $type)

                                                            <option value="{{ $type->id }}"><label
                                                                        for="txt206451"></label>{{ $type->title_ru }}</option>

                                                        @endforeach
                                                    </select>
                                                </span>
                                                <div class="username">
                                                    @if($value->user->id === 1134)
                                                        {{$value->user->sname??''}} {{ $value->user->lname??''}} {{$value->user->fname??'' }}
                                                    @else
                                                        {{ mb_substr($value->user->fname??'', 0 ,1).'.'.mb_substr($value->user->sname??'', 0 ,1).'.'.$value->user->lname??'' }}
                                                    @endif
                                                    <input value="{{ $value->user_id }}" name="to_user_id[]" hidden/>
                                                    <input value="{{ $value->department_id }}" name="depart_id[]"
                                                           hidden/>
                                                </div>
                                                <div class="description">{{ $value->department->title??'' }}
                                                    - {{ $value->user->job_title??'' }}</div>
                                            </div>
                                        </li>

                                    @endforeach

                                    @foreach($filial_users as $key => $value)

                                        <li class="list-group-item filial_style select-user listItem{{$value->u_id}}">
                                            <button value="{{$value->u_id}}" type="button" class="close removeItem">
                                                <span aria-hidden="true">×</span></button>
                                            <div class="user-block">
                                                <img class="img-circle img-bordered-sm"
                                                     src="{{ asset("/admin-lte/dist/img/user.png") }}"
                                                     alt="User Image">
                                                <span class="pull-right btn-box-tool performer_type">
                                                    <select class="form-control" name="performer_user[]">
                                                        @foreach($perfUserTypes as $key => $type)

                                                            <option value="{{ $type->id }}"><label
                                                                        for="txt206451"></label>{{ $type->title_ru }}</option>

                                                        @endforeach
                                                    </select>
                                                </span>
                                                <div class="username">
                                                    {{ $value->full_name }}
                                                    <input value="{{ $value->user_id }}" name="to_user_id[]" hidden/>
                                                    <input value="{{ $value->depart_id }}" name="depart_id[]" hidden/>
                                                </div>
                                                <div class="description">{{ $value->branch_name }}
                                                    - {{ $value->job_title }}</div>
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
                    <div class="modal-content">
                        <div class="modal-header bg-aqua-active">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title"><i class="fa fa-reply"></i> @lang('blade.forward_task')</h4>
                        </div>
                        <form method="POST" action="{{ route('redirect-task') }}" class="form-horizontal">
                            {{csrf_field()}}
                            <div class="modal-body">

                                <label>@lang('blade.select_receiver')</label><sup class="text-red"> *</sup>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <select name="to_guide_id" class="form-control">
                                            @foreach($redirectGuideUsers as $key => $value)
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
                                        <input name="from_guide_id"
                                               value="{{ $model->journalUser->guideUser->user_id }}" hidden/>
                                        <input name="edo_message_id" value="{{ $model->id }}" hidden/>
                                        <input name="jrnl_id" value="{{ $model->journalUser->id }}" hidden/>
                                        <input name="status" value="1" hidden/>
                                        <textarea name="redirect_desc" class="form-control" rows="3"
                                                  required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary"><i
                                            class="fa fa-save"></i> @lang('blade.save')</button>
                                <button type="button" class="btn btn-default"
                                        data-dismiss="modal">@lang('blade.cancel')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="cancelModal" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-aqua-active">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title"><i class="fa fa-reply"></i> @lang('blade.cancel_task')</h4>
                        </div>
                        <form method="POST" action="{{ route('cancel-g-task') }}" class="form-horizontal">
                            {{csrf_field()}}
                            <div class="modal-body">
                                <label>@lang('blade.leave_comment')</label><sup class="text-red"> *</sup>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <input name="user_id" value="{{ Auth::id() }}" hidden/>
                                        <input name="edo_message_id" value="{{ $model->id }}" hidden/>
                                        <input name="jrnl_id" value="{{ $model->journalUser->id }}" hidden/>
                                        <textarea name="description" class="form-control" rows="3" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary"><i
                                            class="fa fa-save"></i> @lang('blade.save')</button>
                                <button type="button" class="btn btn-default"
                                        data-dismiss="modal">@lang('blade.cancel')</button>
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
        <script src="{{ asset ("/admin-lte/bootstrap/js/bootstrap-datepicker.js") }}"></script>

        <!-- ckeditor -->
        <script src="{{ asset ("/ckeditor/ckeditor.js") }}"></script>
        <script src="{{ asset ("/ckeditor/samples/js/sample.js") }}"></script>

        <script type="text/javascript">

            initSample();

            $(document).ready(function () {

                // For Users
                $('#edoUsers').hide();

                $('.form-panel').hide();

                $('#letterChip').on('click', function () {

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

                    var listItem = '.listItem' + listVal;

                    $(listItem).removeClass('unselect-user').addClass('select-user').appendTo('ul#unSelectedUsers');

                    $(listItem + '.select-user span').addClass('performer_type');

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

            function userSearchFunction() {
                var input = document.getElementById("userSearch");
                var filter = input.value.toLowerCase();
                var nodes = document.getElementsByClassName('select-user');

                for (i = 0; i < nodes.length; i++) {
                    if (nodes[i].innerText.toLowerCase().includes(filter)) {
                        nodes[i].style.display = "block";
                    } else {
                        nodes[i].style.display = "none";
                    }
                }
            }

            //Date picker
            $('#datepicker').datepicker({
                autoclose: true
            });
            $('.input-datepicker').datepicker({
                todayBtn: 'linked',
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                autoclose: true
            });
            $('.input-daterange').datepicker({
                todayBtn: 'linked',
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                startDate: '-Infinity',
                autoclose: true
            });

            // file upload
            $('#fileUpload').on('submit', function(event){
                event.preventDefault();
                $.ajax({
                    url:"{{ url('/edo-message-file/upload') }}",
                    method:"POST",
                    data:new FormData(this),
                    dataType:'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success:function(data)
                    {
                        if(data.success == true){ // if true (1)
                            location.reload();
                        }
                        $('#message').css('display', 'block');
                        $('#message').html(data.message);
                        $('#message').addClass(data.class_name);
                    }
                })
            });

            // file delete
            $('.deleteFile').on('click', function (e) {
                e.preventDefault();
                var id = $(this).data("id");

                $('#ConfirmModal').data('id', id).modal('show');
            });

            $('#yesDelete').click(function () {

                var token = $('meta[name="csrf-token"]').attr('content');

                var id = $('#ConfirmModal').data('id');

                $('#fileId_'+id).remove();

                $.ajax(
                    {
                        url: '/edo-message-file/delete/'+id,
                        type: 'GET',
                        dataType: "JSON",
                        data: {
                            "id": id,
                            "_token": token,
                        },
                        success: function (data)
                        {
                            //console.log(data);
                            $('#successModal').modal('show');
                        }
                    });

                $('#ConfirmModal').modal('hide');
            });

            $('#saveResolutionBtn').click(function(e){
                e.preventDefault();
                $('#formResolution').submit();
                $(this).attr("disabled",true)

            })
        </script>
    </section>
    <!-- /.content -->
@endsection
