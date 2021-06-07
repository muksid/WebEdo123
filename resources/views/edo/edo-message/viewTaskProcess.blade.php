@extends('layouts.edo.dashboard')

@section('content')

    <section class="content">
        <div class="row">
            <!-- Message Succes -->
            @if ($message = Session::get('success'))
                <div id="myModal" class="modal fade in" style="display: block">
                    <div class="modal-default">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close closeModal" data-dismiss="modal"
                                                aria-label=""><span>×</span></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="thank-you-pop">
                                            <img src="{{ asset('/admin-lte/dist/img/success.png') }}" alt="Success">
                                            <p>{{ $message }}</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.example-modal -->
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
                    <div class="box-header with-border bg-gray">
                        <h3 class="box-title">@lang('blade.office')</h3>
                    </div>

                    <div class="box-body table-responsive no-padding">
                        <table class="table">
                            <tr>
                                <td class="text-muted">@lang('blade.purpose')</td>
                                <td>@lang('blade.to_resolution')</td>
                            </tr>
                            <tr>
                                <td class="text-muted">@lang('blade.sender')</td>
                                <td>{{$model->journalUser->officeUser->full_name??''}}
                                    <span class="text-maroon">({{ $model->journalUser->userJob->userRole->title_ru??'' }})</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">@lang('blade.sent_date')</td>
                                <td>
                                    {{ $model->created_at->format('d.m.Y H:i') }}
                                    <span class="text-maroon"> ({{$model->created_at->diffForHumans()}})</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted"> @lang('blade.reg_journal')</td>
                                <td> {{ $model->journalUser->journalName->title??''}}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">  @lang('blade.incoming_num')</td>
                                <td>{{ $model->in_number }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">@lang('blade.reg_date_only')</td>
                                <td>{{ date('d.m.Y', strtotime($model->in_date)) }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">@lang('blade.status')</td>
                                <td>
                                    @switch($model->journalUser->status??'')
                                        @case(-2)
                                        <span class="label bg-red-active">Bekor qilingan</span>

                                        @break
                                        @case(-1)
                                        <span class="label bg-yellow-active">Yo`naltirilgan</span>

                                        @break
                                        @case(0)
                                        <span class="label label-warning"
                                              style="text-transform:capitalize;">@lang('blade.new')</span>

                                        @break
                                        @case(1)
                                        <span class="label label-default">@lang('blade.on_process')</span>
                                        @break
                                        @case(2)
                                        <span class="label label-primary">@lang('blade.in_execution')</span>
                                        @break
                                        @case(3)
                                        <span class="label label-success">@lang('blade.closed')</span>
                                        @break
                                        @default
                                        @lang('blade.not_detected')
                                    @endswitch
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">@lang('blade.superviser')</td>
                                <td>{{$model->journalUser->guideUser->full_name??''}}</td>
                            </tr>

                        </table>
                    </div>

                    <!-- /.box-body -->
                    <div class="box-header with-border bg-gray">
                        <h3 class="box-title">@lang('blade.doc')</h3>
                    </div>
                    <br>

                    <div class="col-md-12">
                        <div class="box-body no-padding">
                            <table class="table table-condensed">
                                <tr>
                                    <th>@lang('blade.sender_organization')</th>
                                </tr>
                                <tr>
                                    <td>{{ $model->from_name }}</td>
                                </tr>
                            </table>
                        </div>
                        <br>
                        <div class="box-body no-padding">
                            <table class="table table-condensed">
                                <tr>
                                    <th>@lang('blade.outgoing_num_doc')</th>
                                    <th>@lang('blade.outgoing_date_doc')</th>
                                </tr>
                                <tr>
                                    <td>{{ $model->out_number }}</td>
                                    <td>{{ \Carbon\Carbon::parse($model->out_date)->format('d.m.Y') }}</td>
                                </tr>
                            </table>
                        </div>
                        <hr>
                    </div>

                    <div class="box-body" style="word-break: break-word;"><hr>
                        <strong><i class="fa fa-file margin-r-5"></i> @lang('blade.doc_app') </strong><br>

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
                                <i class="text-red">({{ $file->size($file->file_size)??'' }})</i>
                                <br><br>
                            </div>
                            <br>

                        @endforeach

                        @if($role->id === 2)
                            <div class="col-md-12">
                                <div class="alert" id="message" style="display: none"></div>
                                <form method="post" id="fileUpload" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <table class="table">
                                            <tr>
                                                <td width="30">
                                                    <input type="file" name="message_file[]" id="message_file"
                                                           multiple/>
                                                    <input type="text" name="model_id" value="{{ $model->id }}" hidden/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type="textarea" name="comments" id="comments"
                                                           placeholder="Comment..." style="width:100%" required/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right">
                                                    <button type="submit" name="upload" id="upload"
                                                            class="btn btn-flat btn-xs btn-info">
                                                        <i class="fa fa-upload"></i> @lang('blade.upload_file')
                                                    </button>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </form>
                                <hr>
                                <div id="ConfirmModal" class="modal fade text-danger" role="dialog">
                                    <div class="modal-dialog modal-sm">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger">
                                                <button type="button" class="close" data-dismiss="modal">&times;
                                                </button>
                                                <h4 class="modal-title text-center">O`chirishni tasdiqlash</h4>
                                            </div>
                                            <div class="modal-body">

                                                <p class="text-center">Siz xatni o`chirmoqchimisiz? Izoh qoldiring!</p>

                                                <textarea id="delete_comment" name="" rows="3" cols="35"
                                                          style="resize: none"></textarea>

                                            </div>
                                            <div class="modal-footer">

                                                <center>
                                                    <button type="button" class="btn btn-success" data-dismiss="modal">
                                                        Bekor
                                                        qilish
                                                    </button>

                                                    <button type="button" href="#" id="yesDelete"
                                                            class="btn btn-danger">
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
                                                <button type="button" class="btn btn-info closeModal"
                                                        data-dismiss="modal"><i
                                                            class="fa fa-check-circle"></i> Ok
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        @endif

                        <hr>
                        @if(!empty($model->title))
                            <p class="text-bold text-center">{{ $model->title }}</p>
                        @else
                            <p class="text-muted text-center">(Mavzu yo`q)</p>
                        @endif
                        <?php echo $model->text??'';  ?>
                    </div>
                </div>

            </div>

            <div class="col-md-4">

                <div class="box box-info box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">@lang('blade.reply_letters')</h3>
                    </div>
                    @if(!count($model->replyTasks))
                        <h4 class="text-center text-danger">@lang('blade.not_found_reply_letters')!</h4>
                    @endif
                    @foreach($model->replyTasks as $key => $reply)
                        <div class="box box-widget">
                            <div class="box-header with-border">
                                <div class="user-block">
                                    <img class="img-circle" src="{{ asset('/admin-lte/dist/img/user.png') }}" alt="User">
                                    @if($reply->user_id == Auth::id())
                                        <span class="username text-green">@lang('blade.me').</span>
                                    @else
                                        <span class="username">{{ $reply->replyDirector->lname.' '.$reply->replyDirector->fname }}.</span>
                                    @endif
                                    <span class="description">{{ $reply->created_at->format('d-F Y') }} - {{ $reply->created_at->diffForHumans() }}</span>
                                </div>
                                <!-- /.user-block -->
                                <div class="box-tools">
                                    <button type="button" class="btn btn-box-tool text-red" data-widget="collapse"><i
                                                class="fa fa-minus"></i>
                                    </button>
                                </div>
                                <!-- /.box-tools -->
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <!-- post text -->
                                <p>{{ $reply->text }}... <a href="#">@lang('blade.home_more')</a></p>

                                <!-- Attachment -->
                                @if(!empty($reply->files))
                                    <div class="attachment-block clearfix">
                                        @foreach($reply->files as $file)
                                            <div class="attachment-heading">
                                                <a href="{{ route('edoPreView',['preViewFile'=>$file->file_hash]) }}"
                                                   class="text-info text-bold"
                                                   target="_blank" class="mailbox-attachment-name"
                                                   onclick="window.open('<?php echo('/edoPreView/' . $file->file_hash); ?>',
                                                           'modal',
                                                           'width=800,height=900,top=30,left=500');
                                                           return false;"> <i
                                                            class="fa fa-search-plus"></i> {{ $file->file_name }}
                                                </a>
                                                <a href="{{ route('edo-reply-load',['file'=>$file->file_hash]) }}"
                                                   class="pull-right"><i
                                                            class="fa fa-cloud-download text-primary"></i> @lang('blade.download')
                                                </a>
                                                <i class="text-red">({{ \App\Message::formatSizeUnits($file->file_size) }}
                                                    )</i><br><br>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            <!-- /.attachment-block -->

                                @if($reply->status == 2)
                                    <div id="rec{{$reply->id}}"></div>
                                    @if(Auth::user()->edoUsers() == 'guide')
                                        <button type="button" value="{{$reply->id}}" id="receive{{$reply->id}}"
                                                class="btn btn-default btn-xs reply-receive">
                                            <i class="fa fa-check-circle text-green"></i> @lang('blade.receive')
                                        </button>
                                        <button type="button" value="{{$reply->id}}" id="cancel{{$reply->id}}"
                                                class="btn btn-danger btn-xs reply-cancel">
                                            <i class="fa fa-ban"></i> @lang('blade.reject')
                                        </button>
                                    @endif
                                @elseif($reply->status == 3)
                                    <span class="label label-primary"><i class="fa fa-check-circle"></i> @lang('blade.receive') ...</span>
                                    <span class="pull-right text-muted"><i class="fa fa-clock-o"></i> {{ $reply->updated_at->format('d-M-y H:i') }}</span>

                                @elseif($reply->status == 0)
                                    <span class="label label-success"><i class="fa fa-hourglass-start"></i> @lang('blade.sent_to_approve') ...</span>
                                @endif

                            </div>
                        </div>
                    @endforeach
                    <div class="box-body">
                        <div class="form-group">
                            <?php
                            $conUser = \Illuminate\Support\Facades\DB::table('edo_users as a')
                                ->join('edo_user_roles as r', 'a.role_id', '=', 'r.id')
                                ->select('a.user_id', 'a.role_id', 'a.status', 'r.role_code')
                                ->where('a.user_id', Auth::id())
                                ->where('a.status', 1)
                                ->first();
                            ?>

                            @if(count($model->replyTasks) && $model->journalUser->status==2)
                                @switch($conUser->role_code)
                                    @case('control')
                                    <button type="button" value="{{$model->id}}" id="taskConfirm"
                                            class="btn btn-bitbucket pull-right">
                                        <i class="fa fa-check-circle"></i> @lang('blade.approve_and_close_task')
                                    </button>
                                    @break
                                @endswitch
                            @endif

                            @if(count($model->replyTasks) && ($model->journalUser->status??'') ==2 && ($model->journalUser->guideUser->user_id??0) == Auth::id())
                                <button type="button" value="{{$model->id}}" id="taskConfirm"
                                        class="btn btn-bitbucket pull-right">
                                    <i class="fa fa-check-circle"></i> @lang('blade.approve_and_close_task')
                                </button>
                            @elseif($model->journalUser->status == 3)
                                <h4 class="text-center text-green text-bold"><i
                                            class="fa fa-check-circle"></i> @lang('blade.task_closed')</h4>
                            @endif
                        </div>
                    </div>

                </div>

            </div>

            <div class="col-md-4">
                <div class="box box-widget widget-user-2" id="printFishka">
                    <div class="widget-user-header bg-blue-gradient">
                        <img class="profile-user-img img-responsive" style="width: 40%; border: hidden"
                             src="{{ asset("/admin-lte/dist/img/footer__logo.svg") }}" alt="Turonbank"><br>
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

                        <div class="box-body" style="word-break: break-word;">
                            <h5 class="text-center"><?php echo $model->helperMessageType->text ?? '' ?>
                                @if(!empty($model->messageHelper->term_date))
                                    <br/><br/>
                                    <span class="pull-left text-maroon">
                                            <b><i class="fa fa-check-circle-o"></i> {{ $model->messageHelper->controlType->title?? 'null' }}</b>
                                        </span>
                                    <span class="pull-right text-red">@lang('blade.deadline'): {{ $model->messageHelper->term_date ?? 'null' }}</span>
                                @endif
                            </h5>
                            <hr>
                            <h5 class="text-bold">№ {{ $model->in_number ?? 'Null' }}
                                <span class="pull-right">{{ $model->journalUser->guideUser->full_name??'' }}.</span>
                            </h5>
                            <h5>
                                <span class="pull-left">{{ \Carbon\Carbon::parse($model->perfUsers->first()->created_at??'')->format('d M, Y H:s')  }} </span>
                                <span class="pull-right">{{ $model->journalUser->guideUser->job_title?? 'null' }}.</span>
                            </h5>
                        </div>
                    </div>
                    <div class="box-footer">
                        <input type="button" class="btn btn-primary" value="Print" onclick="PrintDiv('printFishka')"/>
                    </div>
                </div>
                <!-- /.widget-user -->
                <div class="box">
                    <div class="box-header with-border bg-gray">
                        <h3 class="box-title">@lang('blade.execution_steps')</h3>
                    </div>
                    <hr>
                    <div class="tab-pane" id="timeline">
                        <ul class="timeline timeline-inverse">
                            <li class="time-label">
                                <span class="bg-purple">
                                    @lang('blade.to_resolution')
                                </span>
                            </li>

                            <li>
                                <i class="fa fa-envelope bg-blue"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fa fa-clock-o"></i> {{ $model->created_at->format('d.m.Y H:i') }}</span>
                                    <h3 class="timeline-header"><a
                                                href="#">{{$model->journalUser->officeUser->full_name ?? 'null'}}</a>
                                        <span class="text-maroon">({{ $model->journalUser->userJob->userRole->title_ru }})</span>
                                    </h3>
                                </div>
                            </li>
                            @if($model->messageRedirect??'')
                                <li title="Перенаправленный документ">
                                    <i class="fa fa-reply fa-rotate-180 bg-aqua"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fa fa-clock-o"></i> {{ $model->messageRedirect->updated_at->format('d.m.Y H:i') }}</span>
                                        <p class="no-border text-bold">

                                        </p>
                                        <p class="timeline-header no-border">

                                            <a href="#">
                                                {{ $model->messageRedirect->fromUser->lname??''}} {{ $model->messageRedirect->fromUser->fname??'' }}
                                            </a>

                                            {{ $model->messageRedirect->fromUser->job_title?? 'null' }}
                                        </p>
                                        <p class="text-md no-border" style="padding-left: 6px">
                                            <span class="text-bold"> @lang('blade.comment'):</span> {{ $model->messageRedirect->redirect_desc?? 'null' }}
                                        </p>
                                    </div>
                                </li>
                            @endif
                            <li>
                                <i class="fa fa-user bg-aqua"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fa fa-clock-o"></i> {{ $model->perfUsers->first()->created_at??'' }} </span>

                                    <h3 class="timeline-header no-border"><a
                                                href="#">{{ $model->journalUser->guideUser->full_name??'' }}</a>
                                        {{ $model->journalUser->guideUser->job_title?? 'null' }}
                                    </h3>
                                </div>
                            </li>

                            <li>
                                <i class="fa fa-users bg-aqua-active"></i>

                                <div class="timeline-item">
                                    <span class="time"><i class="fa fa-clock-o"></i> </span>
                                    <?php
                                    $j = 1;
                                    $sortj = array(1, 2, 3, 4);
                                    ?>
                                    @foreach($perfUsers->sortBy('user_sort') as $key1 => $user)
                                        @switch($user->user_sort)
                                            @case(1)
                                            @case(2)
                                            @case(3)
                                            @case(4)

                                            <h3 class="timeline-header">
                                                <a href="#perf={{$user->id}}"
                                                   class="perfUsers">{{ $j++.'. '.$user->full_name }}</a> {{ $user->title_ru }}
                                            </h3>
                                            <div class="text-center" id="subUsers{{ $user->id }}"
                                                 style="display: none">
                                                <div id="sub_user{{$user->id}}" class="box box-widget"></div>
                                            </div>
                                            @break
                                        @endswitch
                                    @endforeach

                                    @foreach($perfUsers as $key => $user)
                                        @if($user->user_sort != 1 && $user->user_sort != 2 &&
                                            $user->user_sort != 3 && $user->user_sort != 4)


                                            <h3 class="timeline-header">

                                                <a href="#perf={{$user->id}}" class="perfUsers">
                                                    {{ $j++.'. '.$user->full_name }}
                                                </a>

                                                {{ $user->title_ru }}

                                                <i class="fa fa-clock-o text-sm text-muted">
                                                    <?php
                                                        $edoMessage = \App\EdoDepInboxJournals::where('edo_message_id', $user->edo_message_id??0)
                                                            ->where('depart_id', $user->depart_id??0)
                                                            ->first();
                                                        if($edoMessage){
                                                            echo $edoMessage->created_at;
                                                            echo " - Time";
                                                        }

                                                    ?>
                                                </i>

                                            </h3>
                                            <div class="text-center" id="subUsers{{ $user->id }}"
                                                 style="display: none">
                                                <div id="sub_user{{$user->id}}" class="box box-widget"></div>
                                            </div>
                                        @endif
                                    @endforeach

                                </div>
                            </li>

                            <li class="time-label">
                                <span class="bg-green">
                                    @lang('blade.execution_control')
                                </span>
                            </li>

                            <li>
                                <i class="fa fa-power-off bg-red"></i>

                                <div class="timeline-item">
                                    <span class="time"><i class="fa fa-clock-o"></i> </span>

                                    <h3 class="timeline-header"><a
                                                href="#">@lang('blade.execution')</a> @lang('blade.close_task')</h3>

                                    <div class="timeline-body">
                                    </div>
                                </div>
                                <hr>
                            </li>

                        </ul>
                    </div>
                </div>

            </div>

            <!-- Confirm Modal -->
            <div class="modal fade" id="confirm-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">×</span></button>
                            <h4 class="modal-title" id="myModalLabel"><i
                                        class="fa fa-check-square text-green"></i> @lang('blade.approve')</h4>
                        </div>
                        <div class="modal-body">
                            <h4 class="modal-title" id="req-message"></h4>
                        </div>
                        <div class="modal-footer">
                            <button type="button"
                                    onclick="window.location.href='/reply-task-confirm/{{$model->id}}/{{$model->id}}'"
                                    id="mConfirm" class="btn btn-success" data-dismiss="modal"><i
                                        class="fa fa-check-circle"></i> @lang('blade.approve')</button>
                            <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">@lang('blade.close')</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Receive Modal -->
            <div class="modal fade" id="receive-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">×</span></button>
                            <h4 class="modal-title" id="myModalLabel"><i
                                        class="fa fa-check-square text-green"></i> @lang('blade.approve')</h4>
                        </div>
                        <div class="modal-body">
                            <h4 class="modal-title" id="receive-message"></h4>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ok</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Select2 -->
        <script src="{{ asset("/admin-lte/plugins/select2/select2.full.min.js") }}"></script>

        <link href="{{ asset ("/admin-lte/bootstrap/css/bootstrap-datepicker.css") }}" rel="stylesheet"/>
        <script src="{{ asset ("/admin-lte/bootstrap/js/datepicker/bootstrap.min.js") }}"></script>
        <script src="{{ asset ("/admin-lte/bootstrap/js/bootstrap-datepicker.js") }}"></script>

        <script type="text/javascript">
            function PrintDiv(id) {
                var data = document.getElementById(id).innerHTML;
                var myWindow = window.open('', 'my div', 'height=400,width=600');
                myWindow.document.write('<html><head><title>Turonbank ATB</title>');
                myWindow.document.write('</head><body >');
                myWindow.document.write(data);
                myWindow.document.write('</body></html>');
                myWindow.document.close(); // necessary for IE >= 10

                myWindow.onload = function () { // necessary if the div contain images

                    myWindow.focus(); // necessary for IE >= 10
                    myWindow.print();
                    myWindow.close();
                };
            }

            $(document).ready(function () {

                $(".perfUsers").click(function () {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                    var value = $(this).attr("href").split('=');
                    var userId = value[1];
                    var modelId = '{{$model->id}}';
                    $.ajax({
                        url: '/get-sub-perf-users',
                        type: 'POST',
                        data: {_token: CSRF_TOKEN, id: userId, model: modelId},
                        dataType: 'JSON',
                        success: function (data) {
                            //console.log(data);
                            var obj = data;
                            var users = "";
                            $.each(obj['msg'], function (key, val) {
                                key = key + 1;
                                var isRead = "<i class='fa fa-check-circle text-red'></i>";
                                if (val.is_read === 1) {
                                    var formattedDate = new Date(val.read_date);
                                    var d = formattedDate.getDate();
                                    var m = formattedDate.getMonth();
                                    m += 1;  // JavaScript months are 0-11
                                    var y = formattedDate.getFullYear();

                                    //$("#txtDate").val(d + "." + m + "." + y);
                                    isRead = "<i class='fa fa-check-circle text-green'></i> " +
                                        d + "-" + m + "-" + y + " <i class='fa fa-clock-o'></i>";
                                }

                                users +=
                                    "<div class='user-block'>" +
                                    "<span class='username'>" +
                                    "<p class='pull-left'>" + key + '. ' + val.full_name + "</p>" +
                                    "<p class='btn-box-tool'>"
                                    + val.title + " " + isRead +
                                    "</p>" +
                                    "</span>" +
                                    "</div>";
                            });
                            $("#subUsers" + userId + "").toggle();
                            $("#sub_user" + userId + "").html(users);
                        },
                        error: function () {
                            console.log(data);
                        }
                    });
                });
                // End //

                $(".perfUsers1").click(function () {
                    var value = $(this).attr("href").split('=');
                    var result = value[1];
                    $("#subUsers" + result + "").toggle();
                    console.log(result);
                    return false;
                });

                // task confirm
                $('#taskConfirm').click(function () {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    var message_id = $(this).val();
                    $.ajax({
                        url: '/req-task-confirm',
                        type: 'POST',
                        data: {_token: CSRF_TOKEN, id: message_id},
                        dataType: 'JSON',
                        success: function (data) {
                            var req = '';
                            $('#mConfirm').hide();
                            if (data.mCount == 0) {
                                $('#mConfirm').show();
                            }
                            $('#req-message').html(data.message);
                            req += "<span class='label label-primary'>qabul qilish ...</span>";
                            $('#conf').append(req);
                            $('#confirm-modal').modal('show');
                        },
                        error: function () {
                            console.log(data);
                        }
                    });

                });

                // confirm
                $('#mConfirm').click(function () {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    var message_id = $(this).val();
                    var director_id = $("input[name=director_id]").val();
                    $.ajax({
                        url: '/reply-confirm',
                        type: 'POST',
                        data: {_token: CSRF_TOKEN, id: message_id, dId: director_id},
                        dataType: 'JSON',
                        success: function (data) {
                            console.log(message_id);
                        },
                        error: function () {
                            console.log(data);
                        }
                    });

                });

                // reply receive
                $('.reply-receive').click(function () {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    var message_id = $(this).val();
                    $.ajax({
                        url: '/guide-receive',
                        type: 'POST',
                        data: {_token: CSRF_TOKEN, id: message_id},
                        dataType: 'JSON',
                        success: function (data) {
                            var rec = '';
                            $('#receive-message').html(data.message);
                            rec += "<span class='label label-primary'>qabul qilindi ...</span>";
                            $('#receive' + message_id + '').remove();
                            $('#cancel' + message_id + '').remove();
                            $('#rec' + message_id + '').append(rec);
                            $('#receive-modal').modal('show');
                        },
                        error: function () {
                            console.log(data);
                        }
                    });

                });

                // reply cancel
                $('.reply-cancel').click(function () {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    var message_id = $(this).val();
                    $.ajax({
                        url: '/guide-cancel',
                        type: 'POST',
                        data: {_token: CSRF_TOKEN, id: message_id},
                        dataType: 'JSON',
                        success: function (data) {
                            var rec = '';
                            $('#receive-message').html(data.message);
                            rec += "<span class='label label-danger'>rad qilindi ...</span>";
                            $('#receive' + message_id + '').remove();
                            $('#cancel' + message_id + '').remove();
                            $('#rec' + message_id + '').append(rec);
                            $('#receive-modal').modal('show');
                        },
                        error: function () {
                            console.log(data);
                        }
                    });

                });

                // close Modal
                $('.closeModal').click(function () {

                    $('#myModal').hide();

                });


                // file upload
                $('#fileUpload').on('submit', function (event) {

                    var comment = 'Без комментариев';

                    if (comment === '') {

                        console.log("Без комментариев");

                    } else {
                        event.preventDefault();
                        var fd = new FormData(this);

                        $.ajax({
                            url: "{{ url('/edo-message-file/upload') }}",
                            method: "POST",
                            data: fd,
                            dataType: 'JSON',
                            contentType: false,
                            cache: false,
                            processData: false,
                            success: function (data) {
                                if (data.success == true) { // if true (1)
                                    location.reload();
                                }
                                $('#message').css('display', 'block');
                                $('#message').html(data.message);
                                $('#message').addClass(data.class_name);
                            }
                        })
                    }

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

                    var comment = $('#delete_comment').val();

                    if (comment === '') {

                        $('#delete_comment').css("border", "3px solid red");

                    } else {
                        $('#fileId_' + id).remove();

                        $.ajax(
                            {
                                url: '/edo-message-file/delete/' + id,
                                type: 'GET',
                                dataType: "JSON",
                                data: {
                                    "id": id,
                                    "_token": token,
                                    "comments": comment
                                },
                                success: function (data) {
                                    //console.log(data);
                                    $('#successModal').modal('show');
                                }
                            });

                        $('#ConfirmModal').modal('hide');

                    }
                });

            });
        </script>
    </section>
    <!-- /.content -->
@endsection
