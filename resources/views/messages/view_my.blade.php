<?php ?>
<?php
function formatSizeUnits($bytes)
{
    if ($bytes >= 1073741824)
    {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    }
    elseif ($bytes >= 1048576)
    {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    }
    elseif ($bytes >= 1024)
    {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    }
    elseif ($bytes > 1)
    {
        $bytes = $bytes . ' bytes';
    }
    elseif ($bytes == 1)
    {
        $bytes = $bytes . ' byte';
    }
    else
    {
        $bytes = '0 bytes';
    }

    return $bytes;
}
?>
<style>
    .ellipsis {
        overflow-wrap: break-word;
    }
    .mailbox-attachment-icon {
        padding: 10px 0px !important;
    }


    .downloadAllButton {
        float: right;
        border: none;
        background: #404040;
        color: #ffffff !important;
        font-weight: 100;
        padding: 20px;
        text-transform: uppercase;
        border-radius: 6px;
        display: inline-block;
        transition: all 0.3s ease 0s;
    }
    .downloadAllButton:hover {
        color: green !important;
        font-weight: 700 !important;
        letter-spacing: 3px;
        background: none;
        -webkit-box-shadow: 0px 5px 40px -10px rgba(0,0,0,0.57);
        -moz-box-shadow: 0px 5px 40px -10px rgba(0,0,0,0.57);
        transition: all 0.3s ease 0s;
    }


</style>
@extends('layouts.table')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <a href="/ym-newm1"><i class="fa fa-list"></i> list</a>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/home"><i class="fa fa-dashboard"></i> @lang('blade.home_page')</a></li>
            <li><a href="#">@lang('blade.messages')</a></li>
            <li class="active">my</li>
        </ol>
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
                                <h5 class="widget-user-desc">MFO: {{$message->branch_code}} &amp; {{$message->job_title}}</h5>
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
                            <p>{{$message->text}}</p>
                        </div>
                    </div>

                    <div class="box-footer">
                        <h3 class="box-title">@lang('blade.file'):</h3>
                        <ul class="mailbox-attachments clearfix">

                            @foreach ($message_files as $file)
                                @switch($file->file_extension)
                                @case('doc')
                                <li>
                                    <span class="mailbox-attachment-icon">
                                        <i class="fa fa-file-word-o" style="color: #3c8dbc"></i>
                                    </span>

                                    <div class="mailbox-attachment-info">
                                        <a href="#" class="mailbox-attachment-name">
                                            <i class="fa fa-paperclip"></i> {{$file->file_name}}
                                        </a>
                                        <span class="mailbox-attachment-size">{{ \App\Message::formatSizeUnits($file->file_size) }}
                                            <a href="{{ route('load',['file'=>$file->file_hash]) }}"
                                               class="btn btn-default btn-xs pull-right"><i class="fa fa-download"></i>
                                            </a>
                                        </span>
                                    </div>
                                </li>

                                @break
                                @case('docx')
                                <li>
                                    <span class="mailbox-attachment-icon">
                                        <i class="fa fa-file-word-o" style="color: #3c8dbc"></i>
                                    </span>

                                    <div class="mailbox-attachment-info">
                                        <a href="#" class="mailbox-attachment-name">
                                            <i class="fa fa-paperclip"></i> {{$file->file_name}}
                                        </a>
                                        <span class="mailbox-attachment-size">{{ \App\Message::formatSizeUnits($file->file_size) }}
                                          <a href="{{ route('load',['file'=>$file->file_hash]) }}"
                                             class="btn btn-default btn-xs pull-right"><i class="fa fa-download"></i>
                                          </a>
                                        </span>
                                    </div>
                                </li>
                                @break
                                @case('jpg')
                                <li>
                                    <span class="mailbox-attachment-icon has-img">
                                        <img src="{{ asset('/FilesFTP/'.$file->file_hash) }}">
                                    </span>

                                    <div class="mailbox-attachment-info">
                                        <a href="#" class="mailbox-attachment-name">
                                            <i class="fa fa-camera"></i> {{$file->file_name}}
                                        </a>
                                        <span class="mailbox-attachment-size">{{ \App\Message::formatSizeUnits($file->file_size) }}
                                          <a href="{{ route('load',['file'=>$file->file_hash]) }}"
                                             class="btn btn-default btn-xs pull-right"><i class="fa fa-download"></i>
                                          </a>
                                        </span>
                                    </div>
                                </li>
                                @break
                                @case('png')
                                <li>
                                    <span class="mailbox-attachment-icon has-img">
                                        <img src="{{ asset('/FilesFTP/'.$file->file_hash) }}">
                                    </span>

                                    <div class="mailbox-attachment-info">
                                        <a href="#" class="mailbox-attachment-name">
                                            <i class="fa fa-camera"></i> {{$file->file_name}}
                                        </a>
                                        <span class="mailbox-attachment-size">{{ \App\Message::formatSizeUnits($file->file_size) }}
                          <a href="{{ route('load',['file'=>$file->file_hash]) }}"
                             class="btn btn-default btn-xs pull-right"><i class="fa fa-download"></i></a>
                        </span>
                                    </div>
                                </li>
                                @break
                                @case('pdf')
                                <li>
                                    <span class="mailbox-attachment-icon"><i class="fa fa-file-pdf-o"
                                                                             style="color: #FF9800"></i></span>

                                    <div class="mailbox-attachment-info">
                                        <a href="#" class="mailbox-attachment-name"><i
                                                    class="fa fa-paperclip"></i> {{$file->file_name}}</a>
                                        <span class="mailbox-attachment-size">
                          {{ \App\Message::formatSizeUnits($file->file_size) }}
                          <a href="{{ route('load',['file'=>$file->file_hash]) }}"
                             class="btn btn-default btn-xs pull-right"><i class="fa fa-download"></i></a>
                        </span>
                                    </div>
                                </li>
                                @break
                                @case('xls')
                                <li>
                                    <span class="mailbox-attachment-icon"><i class="fa fa-file-excel-o"
                                                                             style="color: #009688"></i></span>

                                    <div class="mailbox-attachment-info">
                                        <a href="#" class="mailbox-attachment-name"><i
                                                    class="fa fa-paperclip"></i> {{$file->file_name}}</a>
                                        <span class="mailbox-attachment-size">
                          {{ \App\Message::formatSizeUnits($file->file_size) }}
                          <a href="{{ route('load',['file'=>$file->file_hash]) }}"
                             class="btn btn-default btn-xs pull-right"><i class="fa fa-download"></i></a>
                        </span>
                                    </div>
                                </li>
                                @break
                                @case('xlsx')
                                <li>
                                    <span class="mailbox-attachment-icon"><i class="fa fa-file-excel-o"
                                                                             style="color: #009688"></i></span>

                                    <div class="mailbox-attachment-info">
                                        <a href="#" class="mailbox-attachment-name"><i
                                                    class="fa fa-paperclip"></i> {{$file->file_name}}</a>
                                        <span class="mailbox-attachment-size">
                          {{ \App\Message::formatSizeUnits($file->file_size) }}
                          <a href="{{ route('load',['file'=>$file->file_hash]) }}"
                             class="btn btn-default btn-xs pull-right"><i class="fa fa-download"></i></a>
                        </span>
                                    </div>
                                </li>
                                @break

                                @default
                                <li>
                                    <span class="mailbox-attachment-icon"><i class="fa fa-file-o"></i></span>

                                    <div class="mailbox-attachment-info">
                                        <a href="#" class="mailbox-attachment-name"><i
                                                    class="fa fa-paperclip"></i> {{$file->file_name}}</a>
                                        <span class="mailbox-attachment-size">
                          {{ \App\Message::formatSizeUnits($file->file_size) }}
                          <a href="{{ route('load',['file'=>$file->file_hash]) }}"
                             class="btn btn-default btn-xs pull-right"><i class="fa fa-download"></i></a>
                        </span>
                                    </div>
                                </li>
                                @endswitch

                            @endforeach
                        </ul>
                    </div>
                    <!-- /.box-footer -->
                </div>
                <!-- /. box -->
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

        <script src="{{ asset("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>
        <script src="{{ asset("/admin-lte/dist/js/app.min.js") }}"></script>
    </section>
    <!-- /.content -->

@endsection