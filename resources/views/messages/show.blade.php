<?php
use App\Message;
?>
@extends('layouts.table')

@section('content')
    <link href="{{ asset('css/treeview.css') }}" rel="stylesheet">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang('blade.read_received')
        </h1>
        <ol class="breadcrumb">
            <li><a href="/home"><i class="fa fa-dashboard"></i>@lang('blade.home_page')</a></li>
            <li><a href="#">@lang('blade.messages')</a></li>
            <li class="active">@lang('blade.read_received')</li>
        </ol>
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>@lang('blade.error')</strong> @lang('blade.to_send_choose')<br><br>
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

        @if(session('notFiles'))
            <div class="box box-default">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger">
                                <h4 class="modal-title"> {{ session('notFiles') }}</h4>
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
            <!-- /.col -->
            <div class="col-md-9">

                <div class="row">
                    <!-- /.col -->
                    <div class="col-sm-6 col-xs-12">
                        <!-- Widget: user widget style 1 -->
                        <div class="box box-widget widget-user-2">
                            <!-- Add the bg color to the header using any of the bg-* classes -->
                            <div class="widget-user-header bg-aqua-active">
                                <div class="widget-user-image">
                                    <img class="img-circle" src="{{ url('/admin-lte/dist/img/user.png') }}" alt="User Avatar">
                                </div>
                                <!-- /.widget-user-image -->
                                <h3 class="widget-user-username">{{$message->lname}} {{$message->fname}}</h3>
                                <h5 class="widget-user-desc">МФО: {{$message->branch}} &amp; {{$message->job_title}}</h5>
                            </div>
                            <div class="box-footer">
                                <div class="row">
                                    <div class="col-sm-4 border-right">
                                        <div class="description-block">
                                            <h5 class="description-header"><i class="fa fa-clock-o"></i> @lang('blade.sent_date')</h5>
                                            <span class="description-text">{{ $message->created_at }}</span>
                                        </div>
                                    </div>

                                    <div class="col-sm-4 border-right">
                                        <div class="description-block">
                                            <h5 class="description-header"><i class="fa fa-thumb-tack"></i> @lang('blade.type_message')</h5>
                                            <span class="description-text">
                                                @foreach(\App\MesType::all() as $value)
                                                    @if($value->message_type == $message->mes_type)
                                                        <span style="color: red">{{$value->title}}</span>
                                                    @endif
                                                @endforeach
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="description-block">
                                            <h5 class="description-header"><i class="fa fa-hourglass-half"></i> @lang('blade.deadline')</h5>
                                            <span class="description-text">
                                                @if($message->mes_term !== '0')
                                                    {{$message->mes_term}}
                                                @else
                                                    <span style="color: red"><i class="fa  fa-ban"></i></span>
                                                @endif
                                            </span>
                                        </div>

                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->
                            </div>
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6 col-xs-12">
                        <div class="box box-widget widget-user-2">

                            <div class="box box-warning"><div class="box-header with-border">
                                    <h3 class="box-title">@lang('blade.subject'):</h3>
                                </div>
                            </div>

                            <div class="box-body">
                                <p>{{$message->subject}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->

                <div class="box box-primary">

                    <div class="box-body no-padding">
                        <div class="mailbox-read-message">
                            <h3 class="box-title">@lang('blade.text'):</h3>
                            <p>{!! $message->text !!}</p>
                        </div>
                    </div>

                    <div class="box-footer">
                        <h3 class="box-title">@lang('blade.file'):</h3>
                        <ul class="mailbox-attachments clearfix">
                            @foreach ($message_files as $file)
                                <?php $file_ext = strtolower($file->file_extension) ?>
                                @switch(strtolower($file_ext))
                                    @case('doc')
                                    @case('docx')
                                    <li>
                                        <span class="mailbox-attachment-icon">
                                            <i class="fa fa-file-word-o text-blue"></i>
                                        </span>
                                        <div class="mailbox-attachment-info ellipsis">
                                            <a href="{{ route('load',['file'=>$file->id]) }}"
                                               class="mailbox-attachment-name">
                                                <i class="fa fa-paperclip"></i> {{$file->file_name}}</a>
                                            <span class="mailbox-attachment-size">
                                                {{ Message::formatSizeUnits($file->file_size) }}
                                                <a href="{{ route('load',['file'=>$file->id]) }}" class="btn btn-default btn-xs pull-right">
                                                    <i class="fa fa-download"></i>
                                                </a>
                                            </span>
                                        </div>
                                    </li>
                                    @break
                                    @case('jpg')
                                    @case('jpeg')
                                    @case('png')
                                    <li>
                                    <span class="mailbox-attachment-icon has-img">
                                        <img src="{{ asset('/FilesFTP/'.$file->file_hash) }}"></span>
                                        <div class="mailbox-attachment-info ellipsis">
                                            <a href="{{ route('myjpg',['file'=>$file->id]) }}"
                                               target="_blank" class="mailbox-attachment-name"
                                               onclick="window.open('<?php echo('/myjpg/' . $file->id); ?>',
                                                       'modal',
                                                       'width=800,height=900,left=500,top=30');
                                                       return false;">
                                                <i class="fa fa-camera"></i> {{$file->file_name}}
                                            </a>
                                            <span class="mailbox-attachment-size">
                                              {{ Message::formatSizeUnits($file->file_size) }}
                                              <a href="{{ route('load',['file'=>$file->id]) }}"
                                                 class="btn btn-default btn-xs pull-right">
                                                  <i class="fa fa-download"></i></a>
                                            </span>
                                        </div>
                                    </li>
                                    @break
                                    @case('pdf')
                                    <li>
                                    <span class="mailbox-attachment-icon">
                                        <i class="fa fa-file-pdf-o text-danger"></i></span>
                                        <div class="mailbox-attachment-info ellipsis">
                                            <a href="{{ route('preview',['previewFile'=>$file->file_hash]) }}"
                                               target="_blank" class="mailbox-attachment-name"
                                               onclick="window.open('<?php echo ('/preview/'. $file->file_hash); ?>',
                                                       'modal',
                                                       'width=800,height=900,top=30,left=500');
                                                       return false;">
                                                {{$file->file_name}}</a>
                                            <span class="mailbox-attachment-size">
                                          {{ Message::formatSizeUnits($file->file_size) }}
                                          <a href="{{ route('load',['file'=>$file->id]) }}"
                                             class="btn btn-default btn-xs pull-right"><i class="fa fa-download"></i></a>
                                        </span>
                                        </div>
                                    </li>
                                    @break
                                    @case('xls')
                                    @case('xlsx')
                                    <li>
                                    <span class="mailbox-attachment-icon">
                                        <i class="fa fa-file-excel-o text-success"></i></span>
                                        <div class="mailbox-attachment-info ellipsis">
                                            <a href="{{ route('load',['file'=>$file->id]) }}" class="mailbox-attachment-name"><i
                                                        class="fa fa-paperclip"></i> {{$file->file_name}}</a>
                                            <span class="mailbox-attachment-size">
                                          {{ Message::formatSizeUnits($file->file_size) }}
                                          <a href="{{ route('load',['file'=>$file->id]) }}"
                                             class="btn btn-default btn-xs pull-right"><i class="fa fa-download"></i></a>
                                        </span>
                                        </div>
                                    </li>
                                    @break

                                    @default
                                    <li>
                                         <span class="mailbox-attachment-icon">
                                             <i class="fa fa-file-o text-aqua"></i></span>

                                        <div class="mailbox-attachment-info ellipsis">
                                            <a href="{{ route('load',['file'=>$file->id]) }}"
                                               class="mailbox-attachment-name"><i
                                                        class="fa fa-paperclip"></i> {{$file->file_name}}</a>
                                            <span class="mailbox-attachment-size">
                                               {{ Message::formatSizeUnits($file->file_size) }}
                                               <a href="{{ route('load',['file'=>$file->id]) }}"
                                                  class="btn btn-default btn-xs pull-right"><i
                                                           class="fa fa-download"></i></a>
                                             </span>
                                        </div>
                                    </li>
                                @endswitch

                            @endforeach
                        </ul>
                        <div class="pull-right">
                            @if(isset($file))
                                <a href="{{ route('load-all',['file'=>$file->message_id]) }}" class="btn btn-default allDownload"><i class="fa fa-download"></i> @lang('blade.download_all')</a>
                            @endif
                        </div>

                    </div>
                    <!-- /.box-footer -->
                    <!-- /.box-footer -->
                    <div class="box-footer">
                        <div class="pull-right">
                            <button type="button" class="btn btn-warning to_reply"><i class="fa fa-reply"></i> @lang('blade.reply')</button>
                            <button type="button" class="btn btn-primary to_forward"><i class="fa fa-share"></i> @lang('blade.forward')</button>
                        </div>
                        <button type="submit" class="btn btn-danger"><i class="fa fa-trash-o"></i> @lang('blade.delete')</button>
                    </div>
                </div>
                <!-- /. box -->

                @if(sizeof($fromUserAllMessages) > 0)
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{$message->lname .' '. $message->fname}} - @lang('blade.from_spesific_user').</h3>

                            <div class="box-tools pull-right">
                                <div class="has-feedback">
                                    <input type="text" class="form-control input-sm" placeholder="@lang('blade.search')">
                                    <span class="glyphicon glyphicon-search form-control-feedback"></span>
                                </div>
                            </div>
                            <!-- /.box-tools -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body no-padding">
                            <div class="mailbox-controls">
                                <!-- Check all button -->
                                <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i>
                                </button>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm text-red delete-all"  title="O`chirish" data-url=""><i class="fa fa-trash-o text-red"></i></button>
                                </div>
                                <div class="pull-right">
                                {{ $fromUserAllMessages->count() }}
                                <!-- /.btn-group -->
                                </div>
                                <!-- /.pull-right -->
                            </div>
                            <div class="table-responsive mailbox-messages">
                                <table class="table table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><i class="fa fa-eye-slash"></i> @lang('blade.group_table_status')</th>
                                        <th><i class="fa fa-text-height"></i> @lang('blade.subject')</th>
                                        <th><i class="fa fa-hourglass"></i> @lang('blade.deadline')</th>
                                        <th><i class="fa fa-tasks"></i> @lang('blade.type_message')</th>
                                        <th><i class="fa fa-link"></i> @lang('blade.file')</th>
                                        <th><i class="fa fa-clock-o"></i> @lang('blade.received_date')</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($fromUserAllMessages as $key => $value)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="checkbox" data-id="{{$value->mes_user_id}}">
                                            </td>
                                            <td class="mailbox-star">
                                                @if($value->is_readed == 0)
                                                    <i class="fa fa-eye-slash text-danger"></i>
                                                @else
                                                    <i class="fa fa-eye text-green"></i>
                                                @endif
                                            </td>
                                            <td class="mailbox-name">
                                                <a href="{{ route('messages.show',['mes_gen' => $value->mes_gen,
                                                            'id' => $value->id]) }}">
                                                    {{ $value->subject }}
                                                </a>
                                            </td>
                                            <td class="mailbox-subject">
                                                @if($value->mes_term == 0)
                                                    @lang('blade.no_deadline')
                                                @else
                                                    <i class="fa fa-clock-o"></i> {{$value->mes_term}}
                                                @endif
                                            </td>
                                            <td>
                                                {{ $value->message_type }}
                                            </td>
                                            <td class="mailbox-attachment"><i class="fa fa-paperclip"></i></td>
                                            <td class="mailbox-date">{{$value->created_at}}</td>
                                        </tr>

                                    @endforeach
                                    </tbody>
                                </table>
                                <!-- /.table -->
                                {{ $fromUserAllMessages->links() }}
                            </div>
                            <!-- /.mail-box-messages -->
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /. box -->
                @endif
            </div>

            <div class="col-md-3">
                <div class="box box-success direct-chat direct-chat-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">@lang('blade.receivers')</h3>
                        <div class="box-tools pull-right">
                            <span data-toggle="tooltip" title="{{$to_users->count()}} ta xodimga" class="badge bg-green">{{$to_users->count()}}</span>
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>

                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="box-footer box-comments">
                            @foreach($to_users as $user)
                                <div class="box-comment">
                                <span class="username">
                                @if($user->is_readed == 1)
                                        <i class="fa fa-fw fa-check-square-o" style="color: #00a65a"></i> {{$user->lname}} {{$user->fname}}
                                        <span class="text-muted pull-right" style="color: #00a65a"><i class="fa fa-clock-o"></i> {{$user->readed_date}}</span>
                                    @else
                                        <i class="fa fa-fw fa-check" style="color: #dd4b39"></i> {{$user->lname}} {{$user->fname}}
                                        <span class="text-muted pull-right"></span>
                                    @endif

                                </span><!-- /.username -->
                                    <i class="fa fa-bank" style="color: #2196F3"></i> {{$user->branch_code}}  <i>{{$user->job_title}}</i>
                                </div>
                            @endforeach
                        </div>
                        <!-- /.direct-chat-pane -->
                    </div>
                </div>
                <!--/.direct-chat -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
    <div id="myModal" class="modal fade">
        <div class="modal-dialog modal-confirm" style="overflow-y: scroll; max-height:70%;  margin-top: 50px; margin-bottom:50px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fa fa-user text-green"></i> {{ $message->lname.' '.$message->fname }} <i class="fa fa-reply"> @lang('blade.reply')</i></h4>
                </div>
                <!-- ReplyMessage -->
                <form role="form" method="POST" action="{{ route('ef-compose') }}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="modal-body">

                        <input name="status" value="reply" hidden />
                        <input name="message_id" value="{{ $message->id }}" hidden />
                        <input name="to_users[]" value="{{$message->u_id}}" hidden />
                        <input name="mes_type" value="other" hidden />

                        <div class="form-group {{ $errors->has('subject') ? 'has-error' : '' }}">
                            <label>@lang('blade.subject') <span class=""></span></label>
                            <input name="subject" value="{{ old('subject') }}" id="subject"  class="form-control" type="text" placeholder="@lang('blade.subject')" required autofocus>
                            @if ($errors->has('subject'))
                                <span class="text-red" role="alert">
                                        <strong>{{ $errors->first('subject') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('text') ? 'has-error' : '' }}">
                            <label>@lang('blade.text'):</label>
                            <textarea name="text" id="editor1" rows="14" cols="110">
                                        <br>
                                        <br>
                                        <i class="text-muted">
                                        @lang('blade.with_respect'),
                                        <br>{{Auth::user()->fname}} {{Auth::user()->lname}}
                                        </i>
                                    </textarea>
                            @if ($errors->has('text'))
                                <span class="text-red" role="alert">
                                        <strong>{{ $errors->first('text') }}</strong>
                                    </span>
                            @endif

                        </div>

                        <div class="form-group">
                            <strong>@lang('blade.upload_file'):</strong>
                            <div class="input-group control-group increment">
                                <input type="file" name="mes_files[]" class="form-control" multiple>
                                <div class="input-group-btn">
                                    <button class="btn btn-success" type="button">
                                        <i class="glyphicon glyphicon-paperclip"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="clone hide">
                                <div class="control-group input-group" style="margin-top:10px">
                                    <input type="file" name="mes_files[]" class="form-control" multiple>
                                    <div class="input-group-btn">
                                        <button class="btn btn-danger" type="button">
                                            <i class="glyphicon glyphicon-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <div class="pull-right">
                            <a href="" class="btn btn-flat btn-default" data-dismiss="modal"><i class="fa fa-remove"></i> @lang('blade.cancel')
                            </a>

                            <button type="submit" class="btn btn-flat btn-primary"><i class="fa fa-envelope-o"></i>
                                @lang('blade.send')
                            </button>
                        </div>
                    </div>
                    <!-- /.modal-footer -->
                </form>
                <!-- ReplyMessage End -->

            </div>
        </div>
    </div>

    <div id="myModalf" class="modal fade">
        <div class="modal-dialog modal-confirm" style="width: 50%;  margin-top: 15px; margin-bottom:15px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fa fa-file-text-o text-green"></i> {{ $message->subject }} <i class="fa fa-reply"> @lang('blade.forward')</i></h4>
                </div>

                <!-- FForward Jamshid -->
                <form role="form" method="POST" action="{{ route('ef-compose') }}" enctype="multipart/form-data">
                    {{csrf_field()}}

                    <input name="status" value="forward" type="text" hidden>
                    <input name="message_id" value="{{$message->id}}" hidden />
                    <input name="text" value="{{$message->text}}" type="text" hidden />
                    <input name="mes_type" value="{{$message->mes_type}}" type="text" hidden />

                    <div id="forwardDiv" class="modal-body" style="overflow-y: scroll; max-height: 600px;">
                        <div class="box-body">
                            <div class="form-group">
                                <ul id="tree1">
                                    @foreach($departments as $department)
                                        <li>
                                            {{ $department->title }}
                                            @if(count($department->childs))
                                                @include('messages.ajax',['childs' => $department->childs])
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group">
                            <input name="subject" value="{{$message->subject}}" type="text" style="hight:100px;" class="form-control" placeholder="@lang('blade.additional') ..." >
                        </div>
                        <div class="pull-right">
                            <a href="" class="btn btn-default" data-dismiss="modal"><i class="fa fa-remove"></i>@lang('blade.cancel')
                            </a>

                            <button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i>
                                @lang('blade.send')
                            </button>
                        </div>
                    </div>
                    <!-- /.modal-footer -->
                </form>
                <!-- FForward End -->

            </div>
        </div>
    </div>

    <div id="deleteModal" class="modal fade">
        <div class="modal-dialog modal-confirm" style="margin-top: 50px; margin-bottom:50px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fa fa-trash text-red"></i> @lang('blade.delete')</h4>
                </div>
                <div class="modal-body">
                    <p class="text-left deletedDiv">@lang('blade.select_at_least_one')</p>
                </div>
                <div class="modal-footer">
                    <div class="pull-right">
                        <button class="btn btn-primary btn-block" data-dismiss="modal"><i class="fa fa-check"></i>Ok</button>
                    </div>
                </div>
                <!-- /.modal-footer -->
            </div>
        </div>
    </div>

<section class="content">

    <!-- ckeditor -->
    <script src="{{ asset ("/ckeditor/ckeditor.js") }}"></script>
    <script src="{{ asset ("/ckeditor/samples/js/sample.js") }}"></script>
    <script type="text/javascript">
        CKEDITOR.replace("editor1");
    </script>

    <script>
        // Reply to user
        $(".to_reply").click(function () {

            $("#myModal").modal('show');

        });

        // For plus minus click files button
        $(".btn-success").click(function () {
            var html = $(".clone").html();
            $(".increment").after(html);
        });
        $("body").on("click", ".btn-danger", function () {
            $(this).parents(".control-group").remove();
        });

        // Reply to user
        $(".to_forward").click(function () {

            $("#myModalf").modal('show');

        });
        // End //

        // For post ajax
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var branch_code = $("input[name=branch_code]").val();
        $(".post_users").click(function () {
            var depart_id = $(this).next().val();
            $.ajax({
                url: '/get-depart-users',
                type: 'POST',
                data: {_token: CSRF_TOKEN, depart_id: depart_id},
                dataType: 'JSON',
                success: function (data) {
                    console.log(data);
                    var obj = data;
                    var user_input = "";

                    $.each(obj['msg'], function (key, val) {
                        user_input +=
                            "<label class='text-black'>" +
                            "<input id='to_users' name='to_users[]' value=" + val.id + " class='user_checkbox' type='checkbox'>" +
                            " "+val.lname + " " + val.fname +
                            " <span style='font-size: x-small;font-style: italic;color: #31708f;'>" +
                            val.job_title + "</span>" +
                            "</label></br>";
                    });

                    $("#data" + data.depart_id).append(user_input); //// For Append

                    $("#usersdiv" + data.depart_id).html(user_input)   //// For replace with previous one
                },
                error: function () {

                    console.log(data);
                }
            });
        });
        // End //
    </script>

    <script>

        // for multiple delete checkbox //
        $(function () {
            //Enable iCheck plugin for checkboxes
            //iCheck for checkbox and radio inputs
            $('.mailbox-messages input[type="checkbox"]').iCheck({
                checkboxClass: 'icheckbox_flat-blue',
                radioClass: 'iradio_flat-blue'
            });

            //Enable check and uncheck all functionality
            $(".checkbox-toggle").click(function () {
                var clicks = $(this).data('clicks');
                if (clicks) {
                    //Uncheck all checkboxes
                    $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
                    $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
                } else {
                    //Check all checkboxes
                    $(".mailbox-messages input[type='checkbox']").iCheck("check");
                    $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
                }
                $(this).data("clicks", !clicks);
            });

        });

        $(document).ready(function () {

            $('.delete-all').on('click', function(e) {

                var idsArr = [];

                $(".checkbox:checked").each(function() {

                    idsArr.push($(this).attr('data-id'));

                });

                if(idsArr.length <=0) {

                    $("#deleteModal").modal('show');

                }  else {

                    if(confirm("Haqiqatan ham tanlangan xatlarni o`chirmoqchimisiz?")) {

                        var strIds = idsArr.join(",");

                        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                        $.ajax({

                            url: '/delete-multiple',
                            type: 'POST',
                            data: {_token: CSRF_TOKEN, ids: strIds},
                            dataType: 'JSON',
                            success: function (data) {

                                if (data['status']==true) {

                                    $(".checkbox:checked").each(function() {

                                        $(this).parents("tr").remove();

                                    });

                                    $("#deleteModal").modal('show');

                                    $(".deletedDiv").html(data['msg'])

                                } else {

                                    alert('Xatolik yuz berdi!!');
                                }
                            },

                            error: function (data) {
                                alert(data.responseText);
                            }
                        });
                    }
                }
            });

            $('[data-toggle=confirmation]').confirmation({

                rootSelector: '[data-toggle=confirmation]',

                onConfirm: function (event, element) {

                    element.closest('form').submit();

                }

            });

        });
        // end //
    </script>


    <script src="{{ asset('js/treeview.js') }}"></script>
    <!-- jQuery 2.2.3 -->
    <script src="{{ asset ("admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

    <!-- DataTables -->
    <script src="{{ asset ("admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>

    <script src="{{ asset ("admin-lte/plugins/datatables/dataTables.bootstrap.min.js") }}"></script>

    <!-- AdminLTE App -->
    <script src="{{ asset("/admin-lte/dist/js/app.min.js") }}"></script>

    <!-- iCheck -->
    <script src="{{ asset("/admin-lte/plugins/iCheck/icheck.min.js") }}"></script>
</section>
@endsection