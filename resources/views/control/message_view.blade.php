<?php
use App\Message;
?>
@extends('layouts.table')

@section('content')
    <link href="{{ asset('css/treeview.css') }}" rel="stylesheet">
    

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 style="font-weight: bold">
            View Message
        </h1>
        <ol class="breadcrumb">
            <li><a href="/home"><i class="fa fa-dashboard"></i> @lang('blade.home_page')</a></li>
            <li><a href="/monitoring-message">Control Messages</a></li>
            <li class="active">View Message</li>
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
                                <h3 class="widget-user-username">{{$message->user->lname??'Deleted User'}} {{$message->user->fname??''}}</h3>
                                <h5 class="widget-user-desc">MFO: {{$message->user->filial->title??''}} & {{$message->user->department->title??''}}
                                    &amp; {{$message->user->job_title??''}}</h5>
                            </div>
                            <div class="box-footer">
                                <div class="row">
                                    <div class="col-sm-4 border-right">
                                        <div class="description-block">
                                            <h5 class="description-header"><i class="fa fa-clock-o"></i> @lang('blade.sent_date')</h5>
                                            <span class="description-text">{{ $message->created_at }}</span>
                                        </div>
                                        <!-- /.description-block -->
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-4 border-right">
                                        <div class="description-block">
                                            <h5 class="description-header"><i class="fa fa-thumb-tack"></i> @lang('blade.type_message')
                                            </h5>
                                            <span class="description-text">
                                                @foreach(\App\MesType::all() as $value)
                                                    @if($value->message_type == $message->mes_type)
                                                        <span style="color: red">{{$value->title}}</span>
                                                    @endif
                                                @endforeach
                                            </span>
                                        </div>
                                        <!-- /.description-block -->
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-4">
                                        <div class="description-block">
                                            <h5 class="description-header"><i class="fa fa-hourglass-half"></i> @lang('blade.deadline')
                                            </h5>
                                            <span class="description-text">
                                                @if($message->mes_term !== '0')
                                                    {{$message->mes_term}}
                                                @else
                                                    @lang('blade.no_deadline')
                                                @endif
                                            </span>
                                        </div>
                                        <!-- /.description-block -->
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
                            <!-- Add the bg color to the header using any of the bg-* classes -->
                            <div class="box box-warning">
                                <div class="box-header with-border">
                                    <h3 class="box-title">@lang('blade.subject'):</h3>
                                </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <!-- post text -->

                                <p>{{$message->subject}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->

                <div class="box box-primary">
                    <div class="box-body no-padding">
                        <!-- /.mailbox-read-info -->
                        <!-- /.mailbox-controls -->
                        <div class="mailbox-read-message">
                            <h3 class="box-title">@lang('blade.text'):</h3>

                            <p>{{$message->text}}</p>

                        </div>
                        <!-- /.mailbox-read-message -->
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <h3 class="box-title">@lang('blade.file'):</h3>
                        <ul class="mailbox-attachments clearfix">
                            @foreach ($message_files as $file)
                                @switch(strtolower($file->file_extension))
                                @case('doc')
                                    <li>
                                    <span class="mailbox-attachment-icon">
                                        <i class="fa fa-file-word-o text-blue"></i></span>
                                        <div class="mailbox-attachment-info ellipsis">
                                            <a href="{{ route('load',['file'=>$file->file_hash]) }}"
                                               class="mailbox-attachment-name">
                                                <i class="fa fa-paperclip"></i> {{$file->file_name}}</a>
                                            <span class="mailbox-attachment-size">
                                              {{ Message::formatSizeUnits($file->file_size) }}
                                              <a href="{{ route('load',['file'=>$file->file_hash]) }}"
                                                 class="btn btn-default btn-xs pull-right">
                                                  <i class="fa fa-download"></i></a>
                                            </span>
                                        </div>
                                    </li>
                                    <li>
                                    <span class="mailbox-attachment-icon">
                                        <i class="fa fa-file-word-o text-blue"></i></span>
                                        <div class="mailbox-attachment-info ellipsis">
                                            <a href="{{ route('load',['file'=>$file->file_hash]) }}"
                                               class="mailbox-attachment-name">
                                                <i class="fa fa-paperclip"></i> {{$file->file_name}}</a>
                                            <span class="mailbox-attachment-size">
                                              {{ Message::formatSizeUnits($file->file_size) }}
                                              <a href="{{ route('load',['file'=>$file->file_hash]) }}"
                                                 class="btn btn-default btn-xs pull-right">
                                                  <i class="fa fa-download"></i></a>
                                            </span>
                                        </div>
                                    </li>
                                @break
                                @case('docx')
                                    <li>
                                    <span class="mailbox-attachment-icon">
                                        <i class="fa fa-file-word-o text-blue"></i></span>
                                        <div class="mailbox-attachment-info ellipsis">
                                            <a href="{{ route('load',['file'=>$file->file_hash]) }}"
                                               class="mailbox-attachment-name">
                                                <i class="fa fa-paperclip"></i> {{$file->file_name}}</a>
                                            <span class="mailbox-attachment-size">
                                          {{ Message::formatSizeUnits($file->file_size) }}
                                          <a href="{{ route('load',['file'=>$file->file_hash]) }}"
                                             class="btn btn-default btn-xs pull-right">
                                              <i class="fa fa-download"></i></a>
                                        </span>
                                        </div>
                                    </li>
                                @break
                                @case('jpg')
                                    <li>
                                    <span class="mailbox-attachment-icon has-img">
                                        <img src="{{ asset('/FilesFTP/'.$file->file_hash) }}"></span>
                                        <div class="mailbox-attachment-info ellipsis">
                                            <a href="{{ route('preview',['previewFile'=>$file->file_hash]) }}"
                                               target="_blank" class="mailbox-attachment-name"
                                               onclick="window.open('<?php echo('/preview/' . $file->file_hash); ?>',
                                                       'modal','width=800,height=900,left=500,top=30');
                                                       return false;">
                                                <i class="fa fa-camera"></i>{{$file->file_name}}
                                            </a>
                                            <span class="mailbox-attachment-size">
                                          {{ Message::formatSizeUnits($file->file_size) }}
                                          <a href="{{ route('load',['file'=>$file->file_hash]) }}"
                                             class="btn btn-default btn-xs pull-right">
                                              <i class="fa fa-download"></i></a>
                                        </span>
                                        </div>
                                    </li>
                                @break
                                @case('png')
                                    <li>
                                    <span class="mailbox-attachment-icon has-img">
                                        <img src="{{ asset('/FilesFTP/'.$file->file_hash) }}"></span>
                                        <div class="mailbox-attachment-info ellipsis">
                                            <a href="{{ route('preview',['previewFile'=>$file->file_hash]) }}"
                                               target="_blank" class="mailbox-attachment-name"
                                               onclick="window.open('<?php echo('/preview/' . $file->file_hash); ?>',
                                                       'modal',
                                                       'width=800,height=900,left=500,top=30');
                                                       return false;">
                                                <i class="fa fa-camera"></i>{{$file->file_name}}
                                            </a>
                                            <span class="mailbox-attachment-size">
                                              {{ Message::formatSizeUnits($file->file_size) }}
                                              <a href="{{ route('load',['file'=>$file->file_hash]) }}"
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
                                          <a href="{{ route('load',['file'=>$file->file_hash]) }}"
                                             class="btn btn-default btn-xs pull-right"><i class="fa fa-download"></i></a>
                                        </span>
                                    </div>
                                </li>
                                @break
                                @case('xls')
                                <li>
                                    <span class="mailbox-attachment-icon">
                                        <i class="fa fa-file-excel-o text-success"></i>
                                    </span>
                                    <div class="mailbox-attachment-info ellipsis">

                                        <a href="{{ route('load',['file'=>$file->file_hash]) }}" class="mailbox-attachment-name">
                                            <i class="fa fa-paperclip"></i> 
                                            {{$file->file_name}}
                                        </a>

                                        <span class="mailbox-attachment-size">
                                            {{ Message::formatSizeUnits($file->file_size) }}
                                            
                                            <a href="{{ route('load',['file'=>$file->file_hash]) }}" class="btn btn-default btn-xs pull-right">
                                                <i class="fa fa-download"></i>
                                            </a>
                                        </span>
                                    </div>
                                </li>
                                @break
                                @case('xlsx')
                                <li>
                                    <span class="mailbox-attachment-icon">
                                        <i class="fa fa-file-excel-o text-success"></i>
                                    </span>
                                    <div class="mailbox-attachment-info ellipsis">
                                        
                                        <a href="{{ route('load',['file'=>$file->file_hash]) }}" class="mailbox-attachment-name">
                                            <i class="fa fa-paperclip"></i> 
                                            {{$file->file_name}}
                                        </a>
                                        <span class="mailbox-attachment-size">
                                            
                                            {{ Message::formatSizeUnits($file->file_size) }}
                                            
                                            <a href="{{ route('load',['file'=>$file->file_hash]) }}" class="btn btn-default btn-xs pull-right">
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
                                            <a href="{{ route('load',['file'=>$file->file_hash]) }}"
                                               class="mailbox-attachment-name"><i
                                                        class="fa fa-paperclip"></i> {{$file->file_name}}</a>
                                            <span class="mailbox-attachment-size">
                                               {{ Message::formatSizeUnits($file->file_size) }}
                                               <a href="{{ route('load',['file'=>$file->file_hash]) }}"
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
                    <div class="box-footer">

                        <button type="button" title="O`chirish" class="btn btn-danger delete-message">
                            <i class="fa fa-trash-o"></i> @lang('blade.delete')
                        </button>

                    </div>
                </div>
                <!-- /. box -->

            </div>

            <div class="col-md-3">
                <!-- DIRECT CHAT SUCCESS -->
                <div class="box box-success direct-chat direct-chat-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">@lang('blade.receivers')</h3>

                        <div class="box-tools pull-right">
                            <span data-toggle="tooltip" title="{{$to_users->count()}} ta xodimga"class="badge bg-green">
                                {{$to_users->count()}}
                            </span>
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                            </button>
                            
                            
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <!-- Conversations are loaded here -->
                        <!-- /.box-body -->
                        <div class="box-footer box-comments mailbox-messages">

                            <table class="table table-hover table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <td>
                                            <div class="has-feedback has-success" style="width: 300px">
                                                <input type="text" id="userSearch" class="form-control" onkeyup="userSearchFunction()"
                                                    placeholder="@lang('blade.search_executors')">
                                                    <span class="glyphicon glyphicon-search form-control-feedback"></span>
                                                <span class="help-block">@lang('blade.at_least_3_letters')</span>
                                            </div>

                                            

                                        <td>
                                        <button type="button" title="O`chirish" class="text-red delete-all" data-url="">
                                            <i class="glyphicon glyphicon-trash"></i>
                                        </button>
                                        <button type="button" class="btn btn-default btn-sm checkbox-toggle">
                                            <i class="fa fa-check-square-o text-red"></i>
                                        </button>
                                        </td>
                                    </tr>
                                </thead>
                                <tbody> 
                                @foreach($to_users as $u)
                                    <tr class="select-user">
                                        <td>
                                            <span class="username">
                                                @if($u->is_readed == 1)
                                                    <i class="fa fa-fw fa-check-square-o"
                                                    style="color: #00a65a"></i> {{$u->toUsers->lname??'Deleted User'}} {{$u->toUsers->fname??''}}
                                                    <span class="text-muted pull-right"
                                                        style="color: #00a65a"><i class="fa fa-clock-o"></i> {{$u->readed_date}}</span>
                                                @else
                                                    <i class="fa fa-fw fa-check"
                                                    style="color: #dd4b39"></i> {{$u->toUsers->lname??''}} {{$u->toUsers->fname??''}}
                                                    <span class="text-muted pull-right"></span>
                                                @endif
                                            </span><!-- /.username -->
                                            <i class="fa fa-bank" style="color: #2196F3"></i> {{$u->toUsers->branch_code??''}}
                                            <i>{{$u->toUsers->job_title??''}}</i>
                                        </td>
                                        <td>

                                            <input type="checkbox" class="checkbox" data-id="{{$u->toUsers->id??''}}">
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody> 
                            </table>

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


    <div id="deleteModal" class="modal fade">
        <div class="modal-dialog modal-confirm" style="margin-top: 50px; margin-bottom:50px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fa fa-trash text-red"></i> @lang('blade.delete')</h4>
                </div>
                <div class="modal-body">
                    <p class="text-left deletedDiv">@lang('blade.selet_at_least_one')</p>
                </div>
                <div class="modal-footer">
                    <div class="pull-right okButton">
                        
                    </div>
                </div>
                <!-- /.modal-footer -->
            </div>
        </div>
    </div>
    <!-- Delete Messages Alert -->
    <div id="usersModal" class="modal fade">
        <div class="modal-dialog modal-confirm" style="overflow-y: scroll; max-height:50%;  margin-top: 50px; margin-bottom:50px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fa fa-user text-green"></i> @lang('blade.senders') <span id="countDiv" class="text-red"></span> @lang('blade.group_edit_count').</h4>
                </div>
                <div class="modal-body">
                    <p class="text-left" id="usersDiv"></p>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-default" data-dismiss="modal">@lang('blade.close')</a>
                </div>
            </div>
        </div>
    </div>

  
    <!-- jQuery 2.2.3 -->
    <script src="{{ asset ("admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>
    <script src="{{ asset ("admin-lte/plugins/jQueryUI/jquery-ui.js") }}"></script>
    <script src="{{ asset ("admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>

    <!-- AdminLTE App -->
    <script src="{{ asset("/admin-lte/dist/js/app.min.js") }}"></script>
    <script src="/admin-lte/plugins/select2/select2.full.min.js"></script>
    <script src="{{ asset ("/admin-lte/bootstrap/js/bootstrap-datepicker.js") }}"></script>
    <!-- iCheck -->
    <script src="{{ asset("/admin-lte/plugins/iCheck/icheck.min.js") }}"></script>


    <script>
        function userSearchFunction() {
            console.log("Working");
            var input = document.getElementById("userSearch");
            var filter = input.value.toLowerCase();
            var nodes = document.getElementsByClassName('select-user');

            for (i = 0; i < nodes.length; i++) {
                if (nodes[i].innerText.toLowerCase().includes(filter)) {
                    nodes[i].style.display = "";
                } else {
                    nodes[i].style.display = "none";
                }
            }
        }
        
        // for multiple delete checkbox //
        $(document).ready(function (){

            

            $(".select2").select2();
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
            
            $('.delete-all').on('click', function(e) {
                var idsArr = [];
                $(".checkbox:checked").each(function() {
                    idsArr.push($(this).attr('data-id'));
                });

                var mess_id = <?php echo json_encode($id); ?>;
                        
                if(idsArr.length <=0) {
                    $("#deleteModal").modal('show');
                }
                else{

                    if(confirm("Haqiqatan ham tanlangan xatlarni o`chirmoqchimisiz?")) {
                        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                        $.ajax({
                            url: '/delete-inside-message',
                            type: 'POST',
                            data: {_token: CSRF_TOKEN, user_id: idsArr, message_id: mess_id},
                            dataType: 'JSON',
                            success: function (data) {
                                if (data['status']==true) {
                                    $(".checkbox:checked").each(function() {
                                        $(this).parents("tr").remove();
                                    });

                                    $("#deleteModal").modal('show');
                                    $(".deletedDiv").html(data['msg'])
                                    $(".okButton").append("<button class='btn btn-primary btn-block' data-dismiss='modal' onClick='window.location.reload();'><i class='fa fa-check'></i>Ok</button>");

                                } 
                                else {
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
          


            $('.delete-message').on('click', function(e) {
                if(confirm("Haqiqatan ham tanlangan xatlarni o`chirmoqchimisiz?")) {
                    
                    var id = <?php echo json_encode($id); ?>;
                    
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        url: '/delete-multiple-general-mess',
                        type: 'POST',
                        data: {_token: CSRF_TOKEN, ids: id},
                        dataType: 'JSON',
                        success: function (data) {
                            console.log(data);
                            if (data['status']==true) {
                                $("#deleteModal").modal('show');
                                $(".deletedDiv").html(data['msg']);
                                $(".okButton").append("<button class='btn btn-primary btn-block' data-dismiss='modal' onClick='location.replace(document.referrer);'><i class='fa fa-check'></i>Ok</button>");
                            } 
                            else {
                                alert('Xatolik yuz berdi!!');
                            }
                        },

                        error: function (data) {
                            alert(data.responseText);
                        }
                    });
                }
            });
            // End Delete Results


        });
        // end //
        
    </script>

@endsection