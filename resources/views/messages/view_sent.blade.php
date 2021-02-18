<?php
use App\Message;
?>
@extends('layouts.table')

@section('content')
    <link href="{{ asset('css/treeview.css') }}" rel="stylesheet">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang('blade.read_sent')
        </h1>
        <ol class="breadcrumb">
            <li><a href="/home"><i class="fa fa-dashboard"></i> @lang('blade.home_page')</a></li>
            <li><a href="#">@lang('blade.messages')</a></li>
            <li class="active">@lang('blade.read_sent')</li>
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
                                <h3 class="widget-user-username">{{$message->user->lname}} {{$message->user->fname}}</h3>
                                <h5 class="widget-user-desc">MFO: {{$message->user->filial->title}} &amp; {{$message->user->department->title.' '.$message->user->job_title}}</h5>
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

                                @switch($file_ext)
                                    @case('doc')
                                    @case('docx')
                                    <li>
                                    <span class="mailbox-attachment-icon">
                                        <i class="fa fa-file-word-o text-blue"></i></span>

                                        <div class="mailbox-attachment-info ellipsis">
                                            <a href="{{ route('load',['file'=>$file->id]) }}" class="mailbox-attachment-name"><i
                                                        class="fa fa-paperclip"></i> {{$file->file_name}}</a>
                                            <span class="mailbox-attachment-size">
                                                {{ \App\Message::formatSizeUnits($file->file_size) }}
                                                <a href="{{ route('load',['file'=>$file->id]) }}" class="btn btn-default btn-xs pull-right"><i class="fa fa-download"></i></a>
                                            </span>
                                        </div>
                                    </li>
                                    @break
                                    @case('jpg')
                                    @case('jpeg')
                                    @case('png')
                                    <li>
                                    <span class="mailbox-attachment-icon has-img"><img
                                                src="{{ asset('/FilesFTP/'.$file->file_hash) }}"></span>
                                        <div class="mailbox-attachment-info ellipsis">
                                            <a href="{{ route('myjpg',['myjpg'=>$file->id]) }}"
                                               target="_blank" class="mailbox-attachment-name"
                                               onclick="window.open('<?php echo ('/myjpg/'. $file->id); ?>',
                                                       'modal',
                                                       'width=800,height=900,top=30,left=500');
                                                       return false;">
                                                <i class="fa fa-camera"></i> {{$file->file_name}}
                                            </a>
                                            <span class="mailbox-attachment-size">
                                          {{ Message::formatSizeUnits($file->file_size) }}
                                          <a href="{{ route('load',['file'=>$file->id]) }}"
                                             class="btn btn-default btn-xs pull-right"><i class="fa fa-download"></i></a>

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
                                            <i class="fa fa-file-excel-o text-success"></i>
                                        </span>

                                        <div class="mailbox-attachment-info ellipsis">
                                            <a href="{{ route('load',['file'=>$file->id]) }}" class="mailbox-attachment-name">
                                                <i class="fa fa-paperclip"></i> {{$file->file_name}}
                                            </a>
                                            <span class="mailbox-attachment-size">
                                                {{ \App\Message::formatSizeUnits($file->file_size) }}
                                                <a href="{{ route('load',['file'=>$file->id]) }}" class="btn btn-default btn-xs pull-right">
                                                    <i class="fa fa-download"></i>
                                                </a>
                                            </span>
                                        </div>
                                    </li>
                                    @break

                                    @default
                                    <li>
                                    <span class="mailbox-attachment-icon">
                                        <i class="fa fa-file-o text-aqua"></i></span>

                                        <div class="mailbox-attachment-info ellipsis">
                                            <a href="{{ route('load',['file'=>$file->id]) }}" class="mailbox-attachment-name"><i
                                                        class="fa fa-paperclip"></i> {{$file->file_name}}</a>
                                            <span class="mailbox-attachment-size">
                                                {{ \App\Message::formatSizeUnits($file->file_size) }}
                                                <a href="{{ route('load',['file'=>$file->id]) }}" class="btn btn-default btn-xs pull-right">
                                                    <i class="fa fa-download"></i>
                                                </a>
                                            </span>
                                        </div>
                                    </li>
                                @endswitch

                            @endforeach
                        </ul>
                    </div>
                    <!-- /.box-footer -->
                    <div class="box-footer">
                        <div class="pull-right">
                            <button type="button" class="btn btn-primary to_forward"><i class="fa fa-share"></i> @lang('blade.forward')</button>
                        </div>
                        <form action="{{ url('messages/'.$message->id) }}" method="POST" style="display: inline-block">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}

                            <button type="submit" title="O`chirish" class="btn btn-danger">
                                <i class="fa fa-trash-o"></i> @lang('blade.delete')
                            </button>
                        </form>
                    </div>
                </div>
                <!-- /. box -->
            </div>

            <div class="col-md-3">
                <div class="box box-success direct-chat direct-chat-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">@lang('blade.receivers')</h3>
                        <div class="box-tools pull-right">
                            <span data-toggle="tooltip" title="{{$message->messageUsers->count()}} ta xodimga" class="badge bg-green">{{$message->messageUsers->count()}}</span>
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>

                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="box-footer box-comments">
                            @foreach($message->messageUsers as $user)
                                <div class="box-comment">
                                <span class="username">
                                @if($user->is_readed == 1)
                                        <i class="fa fa-fw fa-check-square-o" style="color: #00a65a"></i> {{$user->toUsers->lname}} {{$user->toUsers->fname}}
                                        <span class="text-muted pull-right" style="color: #00a65a"><i class="fa fa-clock-o"></i> {{$user->readed_date}}</span>
                                    @else
                                        <i class="fa fa-fw fa-check" style="color: #dd4b39"></i> {{$user->toUsers->lname}} {{$user->toUsers->fname}}
                                        <span class="text-muted pull-right"></span>
                                    @endif

                                </span><!-- /.username -->
                                    <i class="fa fa-bank" style="color: #2196F3"></i> {{$user->toUsers->branch_code}}  <i>{{$user->toUsers->job_title}}</i>
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



    <div id="myModalU" class="modal fade">
        <!-- Jamshid changed style -->
        <div class="modal-dialog modal-confirm" style="width: 50%;  margin-top: 5px; margin-bottom:15px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fa fa-user text-green"></i> {{ $message->subject }} <i class="fa fa-reply"> @lang('blade.forward')</i></h4>
                </div>

                <!-- FForward Jamshid -->
                <form role="form" method="POST" action="{{ route('ef-compose') }}" enctype="multipart/form-data">
                    {{csrf_field()}}

                    <input name="status"        value="forward"                type="text"  style="display: none">
                    <input name="message_id"    value="{{$message->id}}"                    style="display: none">
                    <input name="text"          value="{{$message->text}}"     type="text"  style="display: none">
                    <input name="mes_type"      value="{{$message->mes_type}}" type="text"  style="display: none">
                    <input name="mes_term"      value="{{$message->mes_term}}" type="text"  style="display: none">

                    <div id="forwardDiv" class="modal-body">
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
                            <!-- Jamshid and fixed limitation -->
                            <textarea name="subject"  type="text" style="hight:30px; resize:none;" class="form-control"  maxlength="250" placeholder="@lang('blade.forward'): {{$message->subject}}">@lang('blade.forward'): {{$message->subject}}</textarea>
                        </div>
                        <div class="pull-right">
                            <a href="" class="btn btn-default" data-dismiss="modal"><i class="fa fa-remove"></i> @lang('blade.cancel')
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
<section class="content">
    <script>
        // Reply to user
        $(".to_forward").click(function () {

            $("#myModalU").modal('show');

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
    <script src="{{ asset('js/treeview.js') }}"></script>

    <script src="{{ asset("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

    <script src="{{ asset("/admin-lte/dist/js/app.min.js") }}"></script>
</section>
@endsection