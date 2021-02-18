<?php ?>
@extends('layouts.edo.dashboard')

@section('content')

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- Message Succes -->

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
                        <h3 class="box-title">@lang('blade.doc')</h3>
                    </div>
                    <div class="box-body no-padding">
                        <table class="table table-condensed">
                            <tr>
                                <th style="width: 50%"><i class="fa fa-sort-numeric-asc"></i> @lang('blade.reg_num')</th>
                                <th><i class="fa fa-clock-o"></i>  @lang('blade.date')</th>
                            </tr>
                            <tr>
                                <td style="width: 50%">{{ $model->out_number }}</td>
                                <td>{{ \Carbon\Carbon::parse($model->out_date)->format('j F, Y') }}</td>
                            </tr>
                        </table>
                    </div><br>
                    <div class="box-body no-padding">
                        <table class="table table-condensed">
                            <tr>
                                <th><i class="fa fa-list-ol"></i> @lang('blade.type_of_doc')</th>
                            </tr>
                            <tr>
                                <td>{{ $model->journalUser->messageType->title }}</td>
                            </tr>
                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-body" style="word-wrap: break-word;">
                        <h5><strong>@lang('blade.sender_organization')</strong></h5>
                        <p>{{ $model->from_name }}</p>
                        <hr>
                        <strong><i class="fa fa-file margin-r-5"></i> @lang('blade.doc_app') </strong><br><br>

                        @foreach ($model->files as $file)
                            <?php $file_ext = strtolower($file->file_extension) ?>
                            @switch($file_ext)
                                @case('jpg')
                                @case('jpeg')
                                @case('png')
                                    <a href="{{ route('edoPreViewImg',['imgId'=>$file->id]) }}"
                                        class="text-info text-bold mailbox-attachment-name"
                                        target="_blank"
                                        onclick="window.open('<?php echo('/edoPreViewImg/' . $file->id); ?>',
                                            'modal',
                                            'width=800,height=900,top=30,left=500');
                                            return false;">
                                            <i class="fa fa-search-plus"></i> {{ $file->file_name }}
                                    </a>
                                    <ul class="list-inline pull-right">
                                        <li>
                                            <a href="{{ route('edo-load',['file'=>$file->id]) }}"
                                            class="link-black text-sm"><i
                                                        class="fa fa-cloud-download text-primary"></i> @lang('blade.download')
                                            </a>
                                        </li>
                                    </ul>
                                    @break

                                @case('pdf')
                                    <a href="{{ route('edoPreView',['preViewFile'=>$file->file_hash]) }}"
                                        class="text-info text-bold mailbox-attachment-name"
                                        target="_blank"
                                        onclick="window.open('<?php echo('/edoPreView/' . $file->file_hash); ?>',
                                                'modal',
                                                'width=800,height=900,top=30,left=500');
                                                return false;"> 
                                                <i class="fa fa-search-plus"></i> {{ $file->file_name }}
                                    </a>
                                    <ul class="list-inline pull-right">
                                        <li>
                                            <a href="{{ route('edo-load',['file'=>$file->id]) }}"
                                            class="link-black text-sm"><i
                                                        class="fa fa-cloud-download text-primary"></i> @lang('blade.download')
                                            </a>
                                        </li>
                                    </ul>
                                @break

                                @default
                                    <a  class="text-info text-bold mailbox-attachment-name"> 
                                        <i class="fa fa-search-plus"></i> {{ $file->file_name }}
                                    </a>
                                    <ul class="list-inline pull-right">
                                        <li>
                                            <a href="{{ route('edo-load',['file'=>$file->id]) }}" class="link-black text-sm">
                                                <i class="fa fa-cloud-download text-primary"></i> @lang('blade.download')
                                            </a>
                                        </li>
                                    </ul>
                                @break

                            @endswitch
                            <i class="text-red">({{ \App\Message::formatSizeUnits($file->file_size) }})</i><br><br>
                        @endforeach
                        <hr>
                        @if(!empty($model->title))
                            <p class="text-bold text-center">{{ $model->title }}</p>
                        @else
                            <p class="text-muted text-center">(Mavzu yo`q)</p>
                        @endif
                        <p><?php echo $model->text ?? '' ?></p>
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
                                <td>{{ $model->journalUser->officeUser->full_name ?? 'Null' }}
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
                                <td>1. {{ $model->journalUser->guideUser->full_name ?? 'Null'  }}</td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                    <!-- /.box-body -->
                    @if($messageUser->status == 2)
                        @switch ($role->role_code)
                            @case ('director_department')
                            @case ('guide_manager')
                            @case ('deputy_of_director')
                            @case ('filial_manager')
                                <div class="box-footer">
                                
                                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#cancelModal">
                                        <i class="fa fa-ban"></i> @lang('blade.reject_task')</button>
                                    <button type="submit" class="btn btn-info btn-bitbucket pull-right">
                                        <i class="glyphicon glyphicon-pushpin"></i> @lang('blade.add_mini_task')
                                    </button>
                                </div>
                                @break
                            @default
                                @break
                        @endswitch
                    @endif
                <!-- /.box-footer -->

                    <div class="box-footer">
                    </div>
                    <!-- /.box-footer -->

                    <form role="form" method="POST" action="{{ url('edo-message-sub-users') }}"
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
                                                <select class="form-control select2 type_send"
                                                        name="edo_type_message_id" required>
                                                    <option value="" selected> @lang('blade.select')</option>
                                                    @foreach($messageTypes as $key => $value)

                                                        <option value="{{ $value->id }}"> {{ $value->title_ru }} </option>

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
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
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
                                    <textarea name="text" id="editor">text</textarea>
                                    <input name="message_user_id" value="{{ $messageUser->id }}" hidden />
                                    <input name="depart_id" value="{{ $messageUser->depart_id }}" hidden />
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
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <div class="widget-user-header bg-blue-gradient">
                            <img class="profile-user-img img-responsive" style="width: 40%; border: hidden" src="{{ asset("/admin-lte/dist/img/footer__logo.svg") }}" alt="Turonbank"><br>
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
                                            <li class="list-group-item">{{ $i++.'. '.$user->full_name }}
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
                                <h5 class="text-center"><?php echo $model->messageHelper->text ?? 'Null'; ?>
                                    @if(!empty($model->messageHelper->term_date))
                                        <br/><br/>
                                        <span class="pull-left text-maroon">
                                            <b><i class="fa fa-check-circle-o"></i> {{ $model->messageHelper->controlType->title?? 'null' }}</b>
                                        </span>
                                        <span class="pull-right text-red">@lang('blade.deadline'): {{ $model->messageHelper->term_date ?? 'null' }}</span>
                                    @else
                                        <br/><br/>
                                        <span class="pull-left text-maroon">
                                            <b><i class="fa fa-check-circle-o"></i> {{ $model->messageHelper->controlType->title?? 'null' }}</b>
                                        </span>
                                    @endif
                                </h5><hr>
                                <h5 class="text-bold">№ {{ $model->out_number ?? 'Null' }}
                                    <span class="pull-right">{{ $messageUser->signatureUser->lname??'' }} {{ $messageUser->signatureUser->fname??'' }}.</span>
                                </h5>
                                <h5>{{ $messageUser->created_at->format('d-m-Y H:i') }}<span class="pull-right">{{ $messageUser->signatureUser->job_title??'' }} </span></h5>
                            </div>

                            <div class="box-footer">
                                <div class="slider__pagination-item ng-scope is-active" title="{{ $model->journalUser->guideUser->full_name ?? 'Null' }}">
                                    <div class="slider__thumb">
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-footer -->
                        </div>
                    </div>
                    <!-- /.widget-user -->
                @if($messageUser->sub_status == 1)
                    <div class="tab-pane active text-center" id="timeline">
                        <i class="glyphicon glyphicon-chevron-down text-red text-center"></i>
                    </div>
                    <!-- Widget: user widget style 1 -->
                        <div class="box box-widget widget-user-2">
                            <div class="widget-user-header bg-light-blue">
                                <div class="widget-user-image">
                                    <img class="profile-user-img img-responsive" style="width: 35%; border: hidden" src="{{ asset("/admin-lte/dist/img/footer__logo.svg") }}" alt="Turonbank">
                                </div>
                                <!-- /.widget-user-image -->
                                <h3 class="widget-user-username text-center">@lang('blade.tb')</h3>
                                <h5 class="widget-user-desc text-center">{{$messageUser->director->title}}</h5>
                            </div>
                            <div class="box-footer no-padding">
                                <ul class="nav nav-stacked">
                                    @foreach($perfSubUsers as $key => $user)
                                        @php($key = $key+1)
                                        <li class="list-group-item">{{ $key++.'. '.$user->full_name }} <span class="pull-right badge bg-aqua">{{ $user->title_ru }}</span></li>

                                    @endforeach
                                </ul>
                                <div class="box-body">
                                    <h5 class="text-center"><?php echo $model->messageSubHelper->text ?? 'Null'; ?>
                                        @if(!empty($model->messageSubHelper->term_date))
                                            <br/><br/><span class="pull-left text-red">Muddat: <b>{{ $model->messageHelper->term_date ?? 'null' }}</b></span>
                                        @endif
                                    </h5><hr>
                                    @if(Auth::user()->depart_id == $model->depInboxJournal->id??'')
                                        <h5 class="text-bold">
                                            № {{ $model->depInboxJournal->id ?? 'Null' }} {{ $model->depInboxJournal->in_number_a ?? '' }}
                                            <span class="pull-right">{{ ($messageUser->director->full_name) ?? 'Null' }}.</span>
                                        </h5>
                                        <h5>
                                            {{ $model->subUserOrdinary->created_at??'Null' }}
                                            <span class="pull-right">{{ $messageUser->director->job_title }}</span>
                                        </h5>
                                    @endif
                                </div>

                                <div class="box-footer">
                                    <div class="slider__pagination-item ng-scope is-active" title="{{ ($messageUser->director->full_name) ?? 'Null' }}">
                                        <div class="slider__thumb">
                                        </div>
                                    </div>
                                </div>

                                <div class="box-footer">
                                    @if($messageUser->sub_status == 1 && ($role->role_code == 'guide' || $role->role_code == 'director_department'))

                                        <a href="{{ url('director-confirm', ['m_id' => $messageUser->edo_message_id, 'u_id' => $messageUser->to_user_id, 'mu_id' => $messageUser->id]) }}" type="submit" class="btn btn-dropbox pull-right">
                                            <i class="glyphicon glyphicon-ok"></i> @lang('blade.approve')
                                        </a>

                                    @endif

                                    <a href="{{ url('edo/edit-performer', ['id' => $messageUser->id, 'slug' => $model->message_hash]) }}" type="submit" class="btn btn-bitbucket">
                                        <i class="glyphicon glyphicon-pencil"></i> @lang('blade.update')
                                    </a>
                                </div>
                                <!-- /.box-footer -->
                            </div>
                        </div>
                        <!-- /.widget-user -->
                @elseif($messageUser->sub_status == 2)
                    <div class="tab-pane active text-center" id="timeline">
                        <i class="glyphicon glyphicon-chevron-down text-red text-center"></i>
                    </div>
                    <!-- Widget: user widget style 1 -->
                        <div class="box box-widget widget-user-2">
                            <div class="widget-user-header bg-light-blue">
                                <div class="widget-user-image">
                                    <img class="profile-user-img img-responsive" style="width: 35%; border: hidden" src="{{ asset("/admin-lte/dist/img/footer__logo.svg") }}" alt="Turonbank">
                                </div>
                                <!-- /.widget-user-image -->
                                <h3 class="widget-user-username text-center">@lang('blade.tb')</h3>
                                <h5 class="widget-user-desc text-center">{{$messageUser->director->title}}</h5>
                            </div>
                            <div class="box-footer no-padding">
                                <ul class="nav nav-stacked">
                                    @foreach($perfSubUsers as $key => $user)
                                        @php($key = $key+1)
                                        <li class="list-group-item">{{ $key++.'. '.$user->full_name }} <span class="pull-right badge bg-aqua">{{ $user->title_ru }}</span></li>

                                    @endforeach
                                </ul>
                                <div class="box-body">
                                    <h5 class="text-center">{{ $model->messageSubHelper->text ?? 'Null' }}
                                        @if(!empty($model->messageSubHelper->term_date))
                                            <br/><br/><span class="pull-left text-red">Muddat: <b>{{ $model->messageHelper->term_date ?? 'null' }}</b></span>
                                        @endif
                                    </h5><hr>
                                    <h5 class="text-bold">№ {{ $model->out_number ?? 'Null' }}
                                        <span class="pull-right">{{ ($messageUser->director->full_name) ?? 'Null' }}.</span>
                                    </h5>
                                    <h5>{{ $messageUser->created_at->format('d M. Y H:i') }}<span class="pull-right">{{ $messageUser->director->job_title }} </span></h5>
                                </div>

                                <div class="box-footer">
                                    <div class="slider__pagination-item ng-scope is-active" title="{{ ($messageUser->director->full_name) ?? 'Null' }}">
                                        <div class="slider__thumb">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.widget-user -->

                @endif

                <div class="box box-success" id="edoUsers">
                    <div class="box-header with-border">
                        <h3 class="box-title">@lang('blade.select_receiver')</h3><sup class="text-red"> *</sup>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="box-tools">
                            <div class="form-group has-success has-feedback">
                                <input type="text" id="userSearch" class="form-control" onkeyup="subUserSearchFunction()"
                                       placeholder="@lang('blade.search_executors')">
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
                                                     src="{{ asset("/admin-lte/dist/img/user.png") }}"
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
                                                    <input value="{{ $value->department_id }}" name="depart_id[]" hidden />
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

                <div class="modal fade" id="cancelModal1" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-aqua-active">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title"><i class="fa fa-reply"></i> @lang('blade.reject_task')</h4>
                            </div>
                            <form method="POST" action="{{ route('cancel-d-task') }}" class="form-horizontal">
                                {{csrf_field()}}
                                <div class="modal-body">
                                    <label>@lang('blade.leave_comment')</label><sup class="text-red"> *</sup>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <input name="user_id" value="{{ Auth::id() }}" hidden/>
                                            <input name="from_guide_id" value="{{ $messageUser->to_user_id }}" hidden/>
                                            <input name="to_guide_id" value="{{ $model->journalUser->guideUser->user_id??'' }}" hidden/>
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
            <!--/.col (right) -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->

    <!-- Main content -->
    <section class="content">
        <!-- AdminLTE App -->
        <script src="{{ asset("/admin-lte/plugins/select2/select2.full.min.js") }}"></script>

        <link href="{{ asset ("/admin-lte/bootstrap/css/bootstrap-datepicker.css") }}" rel="stylesheet"/>
        <script src="{{ asset ("/admin-lte/bootstrap/js/bootstrap-datepicker.js") }}"></script>

        <!-- ckeditor -->
        <script src="{{ asset ("/ckeditor/ckeditor.js") }}"></script>
        <script src="{{ asset ("/ckeditor/samples/js/sample.js") }}"></script>

        <script type="text/javascript">

            initSample();



            function subUserSearchFunction() {
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

            $(document).ready(function () {

                // For Users
                $('#edoUsers').hide();

                $('.form-panel').hide();

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

                    CKEDITOR.instances.editor.insertText(tasks);

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