<?php ?>
@extends('layouts.edo.dashboard')

    <!-- TRANSLATED -->
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
                                    @lang('blade.reply_doc') <i class="fa fa-envelope"></i>
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
            @elseif($message = Session::get('delete'))
                <div class="modal fade in" id="myModal" role="dialog" style="display: block">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header bg-danger">
                                <h4 class="modal-title">
                                    @lang('blade.reply_doc') <i class="fa fa-envelope"></i>
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
                                    <td>{{ $model->journalUser->officeUser->full_name ?? 'Null' }}
                                        <span class="text-sm">({{ $model->journalUser->officeUser->job_title??'' }})</span></td>
                                </tr>
                                <tr>
                                    <td>@lang('blade.sent_date')</td>
                                    <td>{{ $model->created_at->format('d M. Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td>@lang('blade.status')</td>
                                    <td><span class="label label-warning">@lang('blade.on_process')</span></td>
                                </tr>
                                <tr>
                                    <td>1. {{ $model->journalUser->guideUser->full_name ?? 'Null'  }}</td>
                                    <td>
                                        @if($model->urgent == 1)
                                            <span class="text-maroon"><i class="fa fa-bell-o text-red fa-lg"></i> @lang('blade.urgent')</span>
                                        @endif
                                    </td>
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
                                <td>{{ $model->journalUser->messageType->title??'' }}</td>
                            </tr>
                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-body">
                        <h5><strong>@lang('blade.sender_organization')</strong></h5>
                        <p>{{ $model->from_name }}</p>
                        <hr>
                        <strong><i class="fa fa-file margin-r-5"></i> @lang('blade.doc_app') </strong><br><br>

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
                            <i class="text-red">({{ $file->size($file->file_size)??'' }}
                                )</i><br><br>
                        @endforeach
                        <hr>
                        <p class="text-bold text-center">{{ $model->title??'' }}</p>
                        <?php echo $model->text ?? 'Null' ?>
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
                        <div class="box box-widget rounded">
                            <div class="box-header with-border bg-info">
                                <div class="user-block ">
                                    <img class="img-circle" src="{{ asset("/admin-lte/dist/img/user.png") }}" alt="user">
                                        <span class="username">{{ $value->replyDirector->lname.' '.$value->replyDirector->fname }}.</span>
                                        <p> {{ $value->replyDirector->department->title??''.' - '.$value->replyDirector->job_title??'' }}</p>
                                </div>
                                <!-- /.user-block -->
                                <div class="box-tools">
                                    <button type="button" id=""  onClick="collapseBox({{$value->replyDirectorDepartment->depart_id??''}})" class="btn btn-box-tool text-red" >
                                        <i id=""
                                            class="plusMinus{{$value->replyDirectorDepartment->depart_id??''}} {{ (($value->replyDirectorDepartment->depart_id??'') == (Auth::user()->department->depart_id??'')) ? 'fa fa-minus':'fa fa-plus'  }}">
                                        </i>
                                    </button>
                                </div>
                                <!-- /.box-tools -->

                                @foreach($replyMessage as $key => $reply)

                                    @if($reply->director_id === $value->director_id)

                                        <div id="" class="box box-widget userReplyBox{{$reply->replyDirectorDepartment->depart_id??''}}"
                                            style="{{ (($reply->replyDirectorDepartment->depart_id??'') == (Auth::user()->department->depart_id??'')) ? '':'display: none'  }}">

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
                                                                <a href="#"
                                                                   class="text-info text-bold mailbox-attachment-name"
                                                                   target="_blank"
                                                                   onclick="window.open('<?php echo('/edo-fileReplyView/' . $file->id); ?>',
                                                                           'modal',
                                                                           'width=800,height=900,top=30,left=500');
                                                                           return false;">
                                                                    <i class="fa fa-search-plus"></i> {{ $file->file_name }}
                                                                </a>
                                                                <ul class="list-inline pull-right">
                                                                    <li>
                                                                        <a href="{{ url('edo-fileReplyDownload',['id'=>$file->id]) }}"
                                                                           class="link-black text-sm"><i
                                                                                    class="fa fa-cloud-download text-primary"></i> @lang('blade.download')
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                                <i class="text-red">({{ $file->size($file->file_size)??'' }}
                                                                    )</i><br><br>
                                                            </div>


                                                        @endforeach
                                                    </div>
                                                @endif
                                                <!-- /.attachment-block -->

                                                @if($reply->status == 0)
                                                    <div id="rec{{$reply->id}}"></div>
                                                    @if($reply->director_id == Auth::id())
                                                        <button type="button" value="{{$reply->id}}" id="receive{{$reply->id}}"
                                                                class="btn btn-default btn-xs reply-receive">
                                                            <i class="fa fa-check-circle text-green"></i> @lang('blade.receive')
                                                        </button>
                                                    @endif
                                                    <form action="{{ url('edo-reply-messages/'.$reply->id) }}" method="POST"
                                                        style="display: inline-block">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                        <button type="submit" class="btn btn-danger btn-xs"
                                                                style="{{ ($reply->director_id == Auth::id() || $reply->user_id == Auth::id()) ? '':'display: none'  }}">
                                                            <i class="fa fa-trash"></i>
                                                            @lang('blade.delete')
                                                        </button>
                                                    </form>
                                                @elseif($reply->status == 1)
                                                    <span class="label label-primary"><i class="fa fa-hourglass-start"></i> @lang('blade.on_process')</span>
                                                    <form action="{{ url('edo-reply-messages/'.$reply->id) }}" method="POST"
                                                        style="display: inline-block">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                        <button type="submit" class="btn btn-danger btn-xs"
                                                        style="{{ ($reply->director_id == Auth::id() || $reply->user_id == Auth::id()) ? '':'display: none'  }}">
                                                            <i class="fa fa-trash"></i>
                                                            @lang('blade.delete')
                                                        </button>
                                                    </form>
                                                @elseif($reply->status == -1)
                                                    <span class="label label-warning"><i class="fa fa-ban"></i> @lang('blade.rejected')</span>
                                                    <form action="{{ url('edo-reply-messages/'.$reply->id) }}" method="POST"
                                                        style="display: inline-block">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                        <button type="submit" class="btn btn-danger btn-xs"
                                                        style="{{ ($reply->director_id == Auth::id() || $reply->user_id == Auth::id()) ? '':'display: none'  }}">
                                                            <i class="fa fa-trash"></i> @lang('blade.delete')
                                                        </button>
                                                    </form>
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

                <!-- Post -->
                @if(($model->subUser->status??'') == 3)
                    <h4 class="text-center text-green text-bold">
                        <i class="fa fa-check-circle"></i> @lang('blade.task_closed')
                    </h4>
                @endif
                    <!-- /.box -->
                    <div class="box-header with-border bg-gray">
                        <i class="fa fa-reply"></i>
                        <h3 class="box-title">@lang('blade.reply')</h3>{{ $messageSubUser->sub_status }}
                    </div>
                    <form action="{{ route('reply.message') }}" method="post" enctype="multipart/form-data" >
                        {{ csrf_field() }}
                        <div class="box-body">
                            <label>@lang('blade.summary')</label><sup class="text-red"> *</sup>
                            <div class="form-group">
                                <textarea name="text" class="form-control" rows="3" required></textarea>
                                <input name="depart_id" value="{{ $edoUsers->department->depart_id??'' }}" hidden />
                                <input name="edo_message_id" value="{{ $model->id }}" hidden />
                                <input name="director_id" value="{{ $messageSubUser->from_user_id }}" hidden />
                                <input name="user_id" value="{{ Auth::id() }}" hidden />
                                <input name="status" value="0" hidden />
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
                                <button class="btn btn-reddit btn-submit-reply"><i class="fa fa-envelope"></i> @lang('blade.send')</button>
                            </div>
                        </div>
                    </form>
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
            <div class="col-md-4">
                @if(!empty($model->messageHelper->text))
                    <div class="box box-widget widget-user-2">
                        <div class="widget-user-header bg-blue-gradient">
                            <img class="profile-user-img img-responsive" style="width: 40%; border: hidden" src="{{ asset("/admin-lte/dist/img/footer__logo.svg") }}" alt="Turonbank"><br>
                            <h2 class="profile-username text-center">@lang('blade.tb_wide')</h2>
                        </div>
                            <?php
                                $i = 1;
                            ?>
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
                                    <span class="pull-right">{{ $messageJournal->signatureUser2->lname??'' }} {{ $messageJournal->signatureUser2->fname??''}}.</span>
                                </h5>
                                <h5>{{ \Carbon\Carbon::parse($model->edoMessageUsersOrdinary->created_at??'')->format('d M, Y H:s')  }}<span class="pull-right">{{ $messageJournal->signatureUser2->job_title??'' }} </span></h5>
                            </div>

                            <div class="box-footer">
                                <div class="slider__pagination-item ng-scope is-active" title="{{ $messageJournal->signatureUser->lname??'' }} {{ $messageJournal->signatureUser->fname??''}}">
                                    <div class="slider__thumb">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                <div class="tab-pane active text-center" id="timeline">
                    <i class="glyphicon glyphicon-chevron-down text-red text-center"></i>
                </div>
                    @endif
                <!-- Widget: user widget style 1 -->
                <div class="box box-widget widget-user-2">
                    <div class="widget-user-header bg-light-blue">
                        <div class="widget-user-image">
                            <img class="profile-user-img img-responsive" style="width: 35%; border: hidden" src="{{ asset("/admin-lte/dist/img/footer__logo.svg") }}" alt="Turonbank">
                        </div>
                        <!-- /.widget-user-image -->
                        <h3 class="widget-user-username text-center">@lang('blade.tb')</h3>
                        <h5 class="widget-user-desc text-center">{{ $messageSubUser->director->title??'Null' }}</h5>
                    </div>
                    <div class="box-footer no-padding">

                        <ul class="nav nav-stacked">
                            <?php $k1 = 1; ?>
                            @foreach($perfSubUsers as $key => $user)
                                <li class="list-group-item">{{ $k1++.'. '.$user->full_name }} <span class="pull-right badge bg-aqua">{{ $user->title_ru }}</span>
                                    <ul class="nav nav-stacked">
                                        <?php $key2 = 1; ?>
                                        @foreach($perfEmpUsers as $key1 => $user1)
                                            @if($user->to_user_id == $user1->from_user_id )
                                                <li class="list-group-item bg-gray-active">{{$key2++.'. '.$user1->full_name }}
                                                    <span class="pull-right badge bg-aqua">{{ $user1->title_ru }}</span>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </li>

                            @endforeach
                        </ul>
                        <div class="box-body">
                        <h5 class="text-center"><?php echo $model->messageSubHelper->text??'Null'; ?>
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
                            <h5 class="text-bold">№ {{ $model->depInboxJournal->in_number ?? 'Null' }} {{ $model->depInboxJournal->in_number_a ?? '' }}
                                <span class="pull-right">{{ ($messageSubUser->director->full_name) ?? 'Null' }}.</span>
                            </h5>
                            <h5>{{ \Carbon\Carbon::parse($model->subUserOrdinary->created_at??'')->format('d M, Y H:s') }}<span class="pull-right">{{ $messageSubUser->director->job_title??'' }} </span></h5>
                        </div>

                        <div class="box-footer">
                            <div class="slider__pagination-item ng-scope is-active" title="{{ ($messageSubUser->director->full_name) ?? 'Null' }}">
                                <div class="slider__thumb">
                                </div>
                            </div>
                        </div>
                        <!-- /.box-footer -->
                    </div>
                </div>
                @if(count($users) && ($model->subUser->status??'') < 2)
                    <div class="box box-widget widget-user-2">
                        <div class="box box-success" id="edoUsers">
                            <div class="box-header with-border">
                                <h3 class="box-title">Xatga xodimlarni biriktirish</h3>
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
                                        <ul class="list-group" id="unSelectedUsers" style="overflow-y: scroll; max-height: 400px">
                                            @foreach($users as $key => $value)

                                                <li class="list-group-item select-user listItem{{$value->u_id}}">
                                                    <button value="{{$value->u_id}}" type="button"
                                                            class="close removeItem">
                                                        <span aria-hidden="true">×</span></button>
                                                    <div class="user-block">
                                                        <img class="img-circle img-bordered-sm"
                                                            src="{{ asset("/admin-lte/dist/img/user.png") }}"
                                                            alt="user">
                                                        <span class="pull-right btn-box-tool performer_type">
                                                            <select class="form-control" name="performer_user[]">
                                                                @foreach($perfUserTypes as $key => $type)
                                                                    <option value="{{ $type->id }}">{{ $type->title_ru }}</option>
                                                                @endforeach
                                                            </select>
                                                        </span>
                                                        <div class="username">
                                                            {{ $value->full_name }}
                                                            <input value="{{ $value->user_id }}" name="to_user_id[]"
                                                                hidden/>
                                                            <input value="{{ $value->department_id }}"
                                                                name="depart_id[]" hidden/>
                                                        </div>
                                                        <div class="description">{{ $value->branch_code }}
                                                            - {{ $value->job_title }}</div>
                                                    </div>
                                                </li>

                                            @endforeach
                                        </ul>
                                    </div>

                                </div>

                            </div>

                        </div>
                        <form role="form" method="POST" action="{{ url('add-sub-users') }}"
                            enctype="multipart/form-data">
                            {{csrf_field()}}

                            <div class="form-group">
                                <div class="box-body box-profile">
                                    <ul class="list-group list-group-unbordered" id="selectedUsers">
                                    </ul>
                                </div>
                                <!-- /.box-body -->
                            </div>

                            <div class="col-md-pull-12 form-panel">

                                <div class="box-body">
                                    <div class="form-group">
                                        <input name="model_id" value="{{ $model->id }}" hidden/>
                                        <input name="jrl_id" value="{{ $model->journalUser->id }}" hidden/>
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
                @endif

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

        <link href="{{ asset ("/admin-lte/bootstrap/css/bootstrap-datepicker.css") }}" rel="stylesheet"/>
        <script src="{{ asset ("/admin-lte/bootstrap/js/datepicker/bootstrap.min.js") }}"></script>
        <script src="{{ asset ("/admin-lte/bootstrap/js/bootstrap-datepicker.js") }}"></script>
        <!-- Bootstrap 3.3.6 -->
        <script src="{{ asset ("admin-lte/bootstrap/js/bootstrap.min.js") }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ asset ("admin-lte/dist/js/app.min.js") }}"></script>

        <script type="text/javascript">


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


            function collapseBox(id){

                $(document).ready(function () {

                    $(".userReplyBox"+id).toggle();


                    var className = $('.plusMinus'+id).attr('class');

                    if(className != ('plusMinus'+id+' fa fa-plus')){

                        $('.plusMinus'+id).removeClass(' fa fa-minus').addClass(' fa fa-plus');

                    }
                    else{

                        $('.plusMinus'+id).removeClass(' fa fa-plus').addClass(' fa fa-minus');

                    }


                });
            }

            $(document).ready(function () {

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
                            //console.log(data);
                        }
                    });
                });
                // End //

            });
            // remove file
            $('.repl-edit-form').click(function () {

                //console.log('asdasdas');

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
