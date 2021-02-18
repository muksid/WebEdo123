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
                    <!-- Horizontal Form -->
                    <div class="">
                        <div class="box-header with-border bg-gray">
                            <h3 class="box-title">@lang('blade.task')</h3>
                        </div>
                        <div class="box-body table-responsive no-padding">
                            <table class="table">
                                <tr>
                                    <td>@lang('blade.purpose')</td>
                                    <td>@lang('blade.to_resolution')</td>
                                </tr>
                                <tr>
                                    <td>@lang('blade.sender')</td>
                                    <td>{{ $model->journalUser->officeUser->full_name??'' }}
                                        <span class="text-maroon">({{ $model->journalUser->userJob->userRole->title_ru??'' }})</span></td>
                                </tr>
                                <tr>
                                    <td>@lang('blade.sent_date')</td>
                                    <td>{{ $model->created_at->format('d M. Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td><span class="label label-warning">@lang('blade.on_process')</span></td>
                                </tr>
                                <tr>
                                    <td>1. {{ $model->journalUser->guideUser->full_name ?? ''  }}</td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                    <hr>
                    <div class="box-header with-border bg-gray">
                        <h3 class="box-title">@lang('blade.doc')</h3>
                    </div>
                    <div class="box-body no-padding">
                        <table class="table table-condensed">
                            <tr>
                                <th style="width: 50%"><i class="fa fa-list-ol"></i> @lang('blade.reg_num')</th>
                                <th><i class="fa fa-clock-o"></i>  @lang('blade.date')</th>
                            </tr>
                            <tr>
                                <td style="width: 50%">{{ $model->out_number }}</td>
                                <td>{{ $model->out_date }}</td>
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
                        <p class="text-bold text-center">{{ $model->title }}</p>
                        <?php echo $model->text ?? '' ?>
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
                        <h3 class="box-title">@lang('blade.reply_letters')</h3>
                    </div>
                    @if(!count($replyMessage))
                        <h4 class="text-center text-yellow">@lang('blade.not_found_reply_letters')!</h4>
                    @endif

                    @foreach($replyMessageGroupBy as $key => $value)
                        <div class="box box-widget ">
                            <div class="box-header with-border bg-info">
                                <div class="user-block ">
                                    <img class="img-circle" src="{{ asset("/admin-lte/dist/img/user.png") }}" alt="user">
                                    
                                        <span class="username">{{ $value->replyDirector->lname.' '.$value->replyDirector->fname }}.</span>
                                        <p> {{ $value->replyDirector->department->title??''.' - '.$value->replyDirector->job_title??'' }}</p>
                                </div>
                                <!-- /.user-block -->
                                <div class="box-tools">
                                    <button type="button" id=""  onClick="collapseBox({{$value->replyDirectorDepartment->depart_id}})" class="btn btn-box-tool text-red" >
                                        <i id="" 
                                            class="plusMinus{{$value->replyDirectorDepartment->depart_id}} {{ ($value->replyDirectorDepartment->depart_id == (Auth::user()->department->depart_id??'')) ? 'fa fa-minus':'fa fa-plus'  }}">
                                        </i>
                                    </button>
                                </div>
                                <!-- /.box-tools -->

                                @foreach($replyMessage as $key => $reply)

                                    @if($reply->director_id === $value->director_id)

                                        <div id="" class="box box-widget userReplyBox{{$reply->replyDirectorDepartment->depart_id}}" style="{{ ($reply->replyDirectorDepartment->depart_id != (Auth::user()->department->depart_id??'')) ? 'display: none':''  }}">
                                            <div class="box-header with-border">
                                                <div class="user-block">
                                                    <img class="img-circle" src="{{ asset("/admin-lte/dist/img/user.png") }}" alt="user">
                                                    @if($reply->user_id == Auth::id())
                                                        <span class="username text-green">@lang('blade.me').</span>
                                                        @else
                                                            @if($reply->director_id === $reply->user_id)
                                                            <span class="username text-capitalize">{{ $reply->replyUser->job_title }}.</span>
                                                            @else
                                                            <span class="username">{{ $reply->replyUser->lname.' '.$reply->replyUser->fname }}.</span>
                                                            @endif
                                                    @endif
                                                    <span class="description">{{ $reply->created_at->format('d-F Y') }} - {{ $reply->created_at->diffForHumans() }}</span>
                                                </div>
                                                <!-- /.user-block -->
                                                <div class="box-tools">
                                                    <button type="button" class="btn btn-box-tool text-red" data-widget="collapse">
                                                        <i class="fa fa-minus"></i>
                                                    </button>
                                                </div>
                                                <!-- /.box-tools -->
                                            </div>
                                            <!-- /.box-header -->
                                            <div class="box-body">
                                                <!-- post text -->
                                                <p>{{ $reply->text }}</p>

                                                <!-- Attachment -->
                                                @if(!empty($reply->files))
                                                    <div class="attachment-block clearfix">
                                                        @foreach($reply->files as $file)
                                                            <div class="attachment-heading">
                                                                <a href="{{ route('edoPreView',['preViewFile'=>$file->file_hash]) }}" class="text-info text-bold"
                                                                target="_blank" class="mailbox-attachment-name"
                                                                onclick="window.open('<?php echo ('/edoPreView/'. $file->file_hash); ?>',
                                                                        'modal',
                                                                        'width=800,height=900,top=30,left=500');
                                                                        return false;"> <i class="fa fa-search-plus"></i> {{ $file->file_name }}</a>
                                                                <a href="{{ route('edo-reply-load',['file'=>$file->file_hash]) }}" class="pull-right"><i class="fa fa-cloud-download text-primary"></i> @lang('blade.download')</a>
                                                                <i class="text-red">({{ \App\Message::formatSizeUnits($file->file_size) }})</i><br><br>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                                <!-- /.attachment-block -->

                                                @if($reply->status == 0)
                                                    <div id="rec{{$reply->id}}"></div>
                                                    
                                                    @if( $reply->replyDirectorDepartment->depart_id == Auth::user()->department->depart_id)
                                                        @switch(Auth::user()->edoUsers())
                                                            @case ('director_department')
                                                            @case ('deputy_of_director')
                                                            @case ('filial_manager')
                                                            @case ('filial_helper')
                                                                <button type="button" value="{{$reply->id}}" id="receive{{$reply->id}}"
                                                                        class="btn btn-default btn-xs reply-receive"
                                                                        style="{{ Auth::user() }}"
                                                                        >
                                                                    <i class="fa fa-check-circle text-green"></i> @lang('blade.receive')
                                                                </button>
                                                                @if(Auth::user()->edoUsers() != 'filial_office' && Auth::user()->edoUsers() != 'dep_helper' )
                                                                    <form action="{{ url('edo-reply-messages/'.$reply->id) }}" method="POST"
                                                                        style="display: inline-block;">
                                                                        {{ csrf_field() }}
                                                                        {{ method_field('DELETE') }}
                                                                        <button type="submit" class="btn btn-danger btn-xs"
                                                                                style="">
                                                                            <i class="fa fa-trash"></i> 
                                                                            @lang('blade.delete')
                                                                        </button>
                                                                    </form>
                                                                @endif    

                                                            @break
                                                                @default
                                                                    @break
                                                            @endswitch
                                                            
                                                    
                                                    @endif
                                                
                                                @elseif($reply->status == 1)
                                                    <span class="label label-primary"><i class="fa fa-hourglass-start"></i> @lang('blade.on_process')</span>

                                                    @if( $reply->replyDirectorDepartment->depart_id == Auth::user()->department->depart_id )
                                                        @switch(Auth::user()->edoUsers())
                                                            @case ('director_department')
                                                            @case ('deputy_of_director')
                                                            @case ('filial_manager')
                                                            @case ('filial_helper')
                                                                <form action="{{ url('edo-reply-messages/'.$reply->id) }}" method="POST"
                                                                    style="display: inline-block">
                                                                    {{ csrf_field() }}
                                                                    {{ method_field('DELETE') }}
                                                                        <button type="submit" class="btn btn-danger btn-xs">
                                                                            <i class="fa fa-trash"></i> 
                                                                            @lang('blade.delete')
                                                                        </button>
                                                                </form>
                                                                @break
                                                            @default
                                                                @break
                                                        @endswitch

                                                    @endif

                                                @elseif($reply->status == -1)
                                                    <span class="label label-warning"><i class="fa fa-ban"></i> @lang('blade.rejected')</span>

                                                    @if( $reply->replyDirectorDepartment->depart_id == Auth::user()->department->depart_id )
                                                        @switch(Auth::user()->edoUsers())
                                                            @case ('director_department')
                                                            @case ('deputy_of_director')
                                                            @case ('filial_manager')
                                                            @case ('filial_helper')


                                                            <form action="{{ url('edo-reply-messages/'.$reply->id) }}" method="POST"
                                                                style="display: inline-block">
                                                                {{ csrf_field() }}
                                                                {{ method_field('DELETE') }}
                                                                <button type="submit" class="btn btn-danger btn-xs">
                                                                    <i class="fa fa-trash"></i> @lang('blade.delete')
                                                                </button>
                                                            </form>
                                                                @break
                                                            @default
                                                                @break
                                                        @endswitch

                                                    @endif

                                                @elseif($reply->status == 2)
                                                    <span class="label label-success"><i class="fa fa-hourglass-start"></i> @lang('blade.sent_to_approve')</span>
                                                @elseif($reply->status == 3)
                                                    <span class="label label-success"><i class="fa fa-hourglass-3"></i> Javob xati qabul qilindi</span>
                                                @elseif($model->journalUser->status == 3)
                                                    <h4 class="text-center text-green text-bold"><i
                                                                class="fa fa-check-circle"></i> @lang('blade.task_closed')</h4>
                                                @endif

                                            </div>
                                            <!-- /.box-body -->
                                        </div>
                                    @endif
                                @endforeach
                                
                            </div>
                        </div>
                    @endforeach

                    @if($messageUsers->sub_status == 1)
                        @switch(Auth::user()->edoUsers())
                            @case ('director_department')
                            @case ('deputy_of_director')
                            @case ('filial_manager')
                            @case ('filial_helper')
                                <div class="box-header with-border bg-gray">
                                    <i class="fa fa-reply"></i>
                                    <h3 class="box-title">@lang('blade.reply')</h3>
                                </div>
                                <form action="{{ route('reply.message') }}" method="post" enctype="multipart/form-data" >
                                    {{ csrf_field() }}
                                    <div class="box-body">
                                        <label>@lang('blade.summary')</label><sup class="text-red"> *</sup>
                                        <div class="form-group">
                                            <textarea name="text" class="form-control" rows="3" required></textarea>
                                            <input name="edo_message_id" value="{{ $model->id }}" hidden />
                                            <input name="director_id" value="{{ $messageUsers->to_user_id }}" hidden />
                                            <input name="depart_id" value="{{ $messageUsers->depart_id }}" hidden />
                                            <input name="user_id" value="{{ Auth::id() }}" hidden />
                                            <input name="status" value="1" hidden />
                                        </div>
                                        <div class="form-group">
                                            <div class="btn btn-default increment">
                                                <input type="file" id="uploadFile" name="files[]" class="form-control" multiple>
                                            </div>
                                            <p class="help-block">Max. 12MB</p>

                                            <div class="box box-solid">
                                                <div id="box_body_prev"></div>
                                                <!-- /.box-header -->
                                                <div class="box-body">
                                                    <dl>
                                                        <div id="upload_prev"></div>
                                                    </dl>
                                                </div>
                                                <!-- /.box-body -->
                                            </div>
                                            <!-- /.box -->
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-reddit btn-submit-reply">
                                                <i class="fa fa-envelope-o"></i> @lang('blade.send_message')
                                            </button>
                                            @if($model->helperMessageType->controlType->type_message_code == 'for_information')
                                                <button type="button" onclick="window.location.href='/director-close-task/{{$model->id}}/{{$messageUsers->depart_id}}'"
                                                        id="closeTask" class="btn btn-success" data-dismiss="modal">
                                                    <i class="fa fa-check-circle"></i> Qabul qilish va yopish
                                                </button>
                                            @elseif(count($replyMessage))
                                                
                                                @switch($role->role_code)
                                                    @case('director_department')
                                                    @case('deputy_of_director')
                                                    @case('filial_manager')
                                                    @foreach($replyMessage as $key => $r)
                                                    
                                                        @if( $r->replyDirectorDepartment->depart_id == Auth::user()->department->depart_id )
                                                            <button type="button" value="{{$model->id}}" id="replyConfirm"
                                                                    class="btn btn-bitbucket pull-right">
                                                                <i class="fa fa-check-circle"></i> @lang('blade.approve_reply')
                                                            </button>
                                                            @break
                                                        @endif
                                                    @endforeach

                                                    
                                                    @break;
                                                    @default
                                                    @break;
                                                @endswitch

                                            @endif
                                        </div>
                                    </div>
                                </form>
                                @break
                            @default
                                @break
                        @endswitch

                    @endif
                    <!-- /.box -->
                    @if($model->edoMessageUsersOrdinary->sub_status == 3)
                        <h4 class="text-center text-green text-bold">
                            <i class="fa fa-check-circle"></i> @lang('blade.task_closed') 
                        </h4>
                    @endif
                    @if(($model->edoMessageUsersOrdinary->sub_status??0) == '2')
                        <h4 class="text-center text-green text-bold">
                            <i class="fa fa-check-circle"></i> @lang('blade.sent_to_approve')
                        </h4>
                    @endif  

                </div>
                <!-- /.box -->
            </div>
            <!--/.col (right) -->

            <!-- Attachment Modal -->
            <div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="edit-modal-label">@lang('blade.update')</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="frmProducts" name="frmProducts" class="form-horizontal" novalidate="">
                                <label>@lang('blade.summary')</label><sup class="text-red"> *</sup>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <textarea name="text" class="form-control" rows="5" id="modal_text" required></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="btn btn-default btn-file">
                                            <i class="fa fa-paperclip"></i> @lang('blade.upload_file')
                                            <input type="file" name="files[]" multiple>
                                        </div>
                                        <p class="help-block">Max. 32MB</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <p id="modal_files"></p>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('blade.save')</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('blade.cancel')</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Receive Modal -->
            <div class="modal fade" id="receive-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <h4 class="modal-title" id="myModalLabel"><i class="fa fa-check-square text-green"></i> @lang('blade.approve')</h4>
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

            <!-- Confirm Modal -->
            <div class="modal fade" id="confirm-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <h4 class="modal-title" id="myModalLabel"><i class="fa fa-check-square text-green"></i> @lang('blade.approve')</h4>
                        </div>
                        <div class="modal-body">
                            <h4 class="modal-title" id="req-message"></h4>
                        </div>
                        <div class="modal-footer">
                            <button type="button" onclick="window.location.href='/reply-confirm/{{$model->id}}/{{$messageUsers->to_user_id}}/{{$messageUsers->depart_id}}'" 
                                id="mConfirm" class="btn btn-success" data-dismiss="modal">
                                <i class="fa fa-check-circle"></i> 
                                @lang('blade.approve')
                            </button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('blade.close')</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4" id="printFishka">
                    <!-- Widget: user widget style 1 -->
                    <div class="box box-widget widget-user-2">
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
                                <h5 class="text-bold">№ {{ $model->in_number ?? 'Null' }}
                                    <span class="pull-right">{{ $messageUsers->signatureUser->lname??'' }} {{ $messageUsers->signatureUser->fname??'' }}.</span>
                                </h5>
                                <h5>{{ $messageUsers->created_at->format('d-m-Y H:i') }}<span class="pull-right">{{ $messageUsers->signatureUser->job_title??'' }} </span></h5>
                            </div>

                            <div class="box-footer">
                                <div class="slider__pagination-item ng-scope is-active" title="{{ $messageUsers->signatureUser->lname??'' }} {{ $messageUsers->signatureUser->fname??'' }}">
                                    <div class="slider__thumb">
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-footer -->
                        </div>
                    </div>
                    <!-- /.widget-user -->
                @if($model->subUserOrdinary??'null' == null)
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
                        <h5 class="widget-user-desc text-center">
                            @if($role->role_code == 'filial_manager')

                                {{$messageUsers->filial->filial_name}}

                            @else
                                {{$messageUsers->director->title}}

                            @endif

                        </h5>
                    </div>
                    <div class="box-footer no-padding">
                        <ul class="nav nav-stacked">
                        <?php $key = 1; ?>
                            @foreach($perfSubUsers as $user)
                                @if( $user->depart_id == Auth::user()->department->depart_id)
                                    @switch($user->signatureUserRole->role_id)
                                        @case(4)
                                        @case(11)
                                        @case(12)
                                        @case(18)
                                            <li class="list-group-item">
                                                {{ $key++.'. '.$user->toSubUser->lname??'' }} {{ $user->toSubUser->fname??'' }} 
                                                <span class="pull-right badge bg-aqua">
                                                    {{ $user->edoTypeMessage->title_ru??'' }}
                                                </span>
                                                    <ul class="nav nav-stacked">
                                                    <?php $key2 = 1; ?>

                                                    @foreach($perfSubUsers as $k => $sub_user)
                                                        @if( $sub_user->from_user_id == $user->to_user_id )
                                                        
                                                            <li class="list-group-item bg-gray-active">
                                                                {{ $key2++.'. '.$sub_user->toSubUser->lname??'' }} {{ $sub_user->toSubUser->fname??'' }} 
                                                                <span class="pull-right badge bg-aqua">{{ $sub_user->edoTypeMessage->title_ru??'' }}</span>
                                                            </li>
                                            
                                                        @endif

                                                    @endforeach
                                                </ul>
                                            </li>

                                        @break

                                        @default
                                        @break
                                        
                                    @endswitch
                                @endif
                            @endforeach
                        </ul>
                        <div class="box-body">
                            <h5 class="text-center"><?php echo $model->messageSubHelper->text??''; ?>
                                @if(!empty($model->messageSubHelper->term_date))
                                    <br/><br/>
                                    <span class="pull-left text-maroon">
                                            <b><i class="fa fa-check-circle-o"></i> {{ $model->messageHelper->controlType->title?? 'null' }}</b>
                                        </span>
                                    <span class="pull-right text-red">@lang('blade.deadline'): {{ $model->messageSubHelper->term_date ?? 'null' }}</span>
                                @else
                                    <br/><br/>
                                    <span class="pull-left text-maroon">
                                            <b><i class="fa fa-check-circle-o"></i> {{ $model->messageSubHelper->controlType->title?? 'null' }}</b>
                                        </span>
                                @endif
                            </h5><hr>
                                <h5 class="text-bold">
                                    № {{ $modelDepart->in_number??'Null' }} {{ $modelDepart->in_number_a??'' }}
                                    <span class="pull-right">{{ $messageUsers->director->full_name?? 'Null' }}.</span>
                                </h5>
                                <h5>
                                    {{ $model->subUserOrdinary->created_at->format('d-m-Y H:i')??'Null' }}
                                    <span class="pull-right">{{ $messageUsers->director->job_title??'' }}</span>
                                </h5>
                            
                        </div>

                        <div class="box-footer">
                            <div class="slider__pagination-item ng-scope is-active" title="{{ ($messageUsers->director->full_name) ?? 'Null' }}">
                                <div class="slider__thumb">
                                </div>
                            </div>
                        </div>

                        <div class="box-footer">
                            <input type="button" class="btn btn-primary" value="Print" onclick="PrintDiv('printFishka')" />
                        </div>
                    </div>
                </div>
                @endif
            </div>

        </div>

    </section>

    <section class="content">

        <link href="{{ asset ("/admin-lte/bootstrap/css/bootstrap-datepicker.css") }}" rel="stylesheet"/>
        <script src="{{ asset ("/admin-lte/bootstrap/js/datepicker/bootstrap.min.js") }}"></script>
        <script src="{{ asset ("/admin-lte/bootstrap/js/bootstrap-datepicker.js") }}"></script>

        <script type="text/javascript">
            function PrintDiv(id) {
                var data=document.getElementById(id).innerHTML;
                var myWindow = window.open('', 'my div', 'height=400,width=600');
                myWindow.document.write('<html><head><title>Turonbank ATB</title>');
                myWindow.document.write('</head><body style="width: 400px;">');
                myWindow.document.write(data);
                myWindow.document.write('</body></html>');
                myWindow.document.close(); // necessary for IE >= 10

                myWindow.onload=function(){ // necessary if the div contain images

                    myWindow.focus(); // necessary for IE >= 10
                    myWindow.print();
                    myWindow.close();
                };
            }

            function collapseBox(id){
                
                $(document).ready(function () {

                    $(".userReplyBox"+id).toggle();


                    var className = $('.plusMinus'+id).attr('class');
                    
                    if(className != ('plusMinus'+id+' fa fa-plus')){

                        $('.plusMinus'+id).removeClass('plusMinus'+id +' fa fa-minus').addClass('plusMinus'+id +' fa fa-plus');

                    }
                    else{

                        $('.plusMinus'+id).removeClass('plusMinus'+id +' fa fa-plus').addClass('plusMinus'+id +' fa fa-minus');

                    }


                });
            }

            $(document).ready(function () {

                // For post ajax
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                $(".open_modal").click(function () {
                    var message_id = $(this).val();
                    $.ajax({
                        url: '/reply-edit',
                        type: 'POST',
                        data: {_token: CSRF_TOKEN, id: message_id},
                        dataType: 'JSON',
                        success: function (data) {
                            console.log(data.files);
                            var obj = data;
                            $('#modal_id').val(data.id);
                            $('#modal_text').val(data.text);

                            var files = "";
                            $.each(obj['files'], function (key, val) {
                                key = key+1;
                                files +=
                                    "<div class='user-block'>" +
                                        "<span class='username'>"+
                                        "<p class='pull-left'>" +key+'. ' + val.file_name + "</p>"+
                                        "<p class='btn-box-tool'>" +
                                            "<i class='fa fa-times repl-edit-form' title='@lang('blade.delete')'>" +
                                                "<input name='files[]' value='"+val.id+"' hidden />"+
                                            "</i>" +
                                        "</p>"+
                                        "</span>"+
                                    "</div>";
                            });
                            $("#modal_files").html(files);
                            $('#edit-modal').modal('show');
                        },
                        error: function () {
                            console.log(data);
                        }
                    });
                });
                // End //

            });
            // reply receive
            $('.reply-receive').click(function () {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                var message_id = $(this).val();
                $.ajax({
                    url: '/reply-receive',
                    type: 'POST',
                    data: {_token: CSRF_TOKEN, id: message_id},
                    dataType: 'JSON',
                    success: function (data) {
                        var rec='';
                        $('#receive-message').html(data.message);
                        rec+="<span class='label label-primary'>@lang('blade.on_process')</span>";
                        $('#receive'+message_id+'').remove();
                        $('#delete-group-'+message_id+'').remove();
                        $('#rec'+message_id+'').append(rec);
                        $('#receive-modal').modal('show');
                    },
                    error: function () {
                        console.log(data);
                    }
                });

            });

            // req confirm
            $('#replyConfirm').click(function () {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                var message_id = $(this).val();
                var director_id = $("input[name=director_id]").val();
                var depart_id = $("input[name=depart_id]").val();
                $.ajax({
                    url: '/req-confirm',
                    type: 'POST',
                    data: {_token: CSRF_TOKEN, id: message_id, dId: director_id, departId: depart_id},
                    dataType: 'JSON',
                    success: function (data) {
                        var req='';
                        $('#mConfirm').hide();
                        if (data.mCount == 0 && data.rejectCount == 0){
                            $('#mConfirm').show();
                        }
                        $('#req-message').html(data.message);
                        req+="<span class='label label-primary'>@lang('blade.receive')</span>";
                        $('#conf').append(req);
                        $('#confirm-modal').modal('show');
                    },
                    error: function () {
                        console.log("data");
                    }
                });

            });

            // confirm
            $('#mConfirm').click(function () {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                var message_id = $(this).val();
                var director_id = $("input[name=director_id]").val();
                var depart_id = $("input[name=depart_id]").val();

                $.ajax({
                    url: '/reply-confirm',
                    type: 'POST',
                    data: {_token: CSRF_TOKEN, id: message_id, dId: director_id, departId: depart_id},
                    dataType: 'JSON',
                    success: function (data) {
                        console.log(message_id);
                    },
                    error: function () {
                        console.log(data);
                    }
                });

            });

            // confirm
            $('#closeTask').click(function () {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                var message_id = $(this).val();
                var depart_id = $("input[name=depart_id]").val();
                $.ajax({
                    url: '/director-close-task',
                    type: 'POST',
                    data: {_token: CSRF_TOKEN, id: message_id, dId: depart_id},
                    dataType: 'JSON',
                    success: function (data) {
                        console.log(message_id);
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


            // This is for selected files
            var fileCount = 0;

            var showFileCount = function() {
                $('#file_count').text('# Files selected: ' + fileCount);
            };

            showFileCount();

            $(document).on('click', '.close', function() {
                $(this).parents('span').remove();
                fileCount -= 1;
                showFileCount();
            });

            $('#uploadFile').on('change', function() {

                $("#upload_prev").empty();

                $("#box_body_prev").empty();

                $("#box_body_prev").append('<div class="box-header with-border">'+
                    '<i class="fa fa-paperclip"></i>' +
                    '<h3 class="box-title"> @lang('blade.selected_files') </h3>'+
                    '</div>');


                var files = $('#uploadFile')[0].files;
                var totalSize = 0;

                for (var i = 0; i < files.length; i++) {
                    // calculate total size of all files
                    totalSize += files[i].size;
                }
                //1x10^9 = 1 GB
                var sizeInGb = totalSize / 12000000;
                if(sizeInGb > 1){
                    alert("@lang('blade.max_limit_err_12'). (max: 12 MB)");
                    this.value = null;
                    $("#upload_prev").empty();
                }

                for (var j = 0; j < files.length; j++) {
                    var fileSize = (files[j].size / 1024 / 1024).toFixed(2);
                    var num = j + 1;
                    $("#upload_prev").append(

                        '<dd>' +num + ". " + files[j].name + ' (' + fileSize + ' MB)' + '</dd>');
                }
                fileCount += files.length;
                showFileCount();
            });
            // End //

        </script>
    </section>
    <!-- /.content -->
@endsection