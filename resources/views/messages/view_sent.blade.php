@extends('layouts.table')

@section('content')

    <section class="content-header">

        <h1>
            @lang('blade.read_sent')
        </h1>
        <ol class="breadcrumb">
            <li><a href="/home"><i class="fa fa-dashboard"></i> @lang('blade.home_page')</a></li>
            <li><a href="#">@lang('blade.messages')</a></li>
            <li class="active">@lang('blade.read_sent')</li>
        </ol>

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

        @if(session('errors'))
            <div class="box box-default">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger">
                                <h4 class="modal-title"> {{ session('errors') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-9">

                <div class="row">

                    <div class="col-sm-6 col-xs-12">
                        <div class="box box-widget widget-user-2">

                            <div class="widget-user-header bg-aqua-active">
                                <div class="widget-user-image">
                                    <img class="img-circle" src="{{ url('/admin-lte/dist/img/user.png') }}" alt="User Avatar">
                                </div>

                                <h3 class="widget-user-username">{{$model->user->lname??''}} {{$model->user->fname??''}}</h3>
                                <h5 class="widget-user-desc">MFO: {{$model->user->filial->title??''}} &amp; {{$model->user->department->title??''.' '.$model->user->job_title??''}}</h5>
                            </div>

                            <div class="box-footer">
                                <div class="row">
                                    <div class="col-sm-4 border-right">
                                        <div class="description-block">
                                            <h5 class="description-header"><i class="fa fa-clock-o"></i> @lang('blade.sent_date')</h5>
                                            <span class="description-text">{{ \Carbon\Carbon::parse($model->created_at)->format('d.m.Y H:i:s') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div id="loading" class="loading-gif" style="display: none"></div>

                    <div class="col-sm-6 col-xs-12">
                        <div class="box box-widget widget-user-2">
                            <div class="box box-warning"><div class="box-header with-border">
                                    <h3 class="box-title">@lang('blade.subject'):</h3>
                                </div>
                            </div>

                            <div class="box-body">
                                <p>{{$model->subject}}</p>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="box box-primary">
                    <div class="box-body no-padding">
                        <div class="mailbox-read-message">
                            <h3 class="box-title">@lang('blade.text'):</h3>
                            <p>{!! $model->text !!}</p>
                        </div>
                    </div>

                    <div class="box-footer">
                        <h3 class="box-title">@lang('blade.file'):</h3>
                        <ul class="mailbox-attachments clearfix">
                            @foreach ($message_files as $file)
                                <li>
                                    <span class="mailbox-attachment-icon">
                                        <i class="fa fa-file-image-o"></i></span>

                                    <div class="mailbox-attachment-info ellipsis">
                                        <a href="#" target="_blank" class="mailbox-attachment-name"
                                           onclick="window.open('<?php echo('/fe-fileView/' . $file->id); ?>',
                                                   'modal',
                                                   'width=800,height=900,top=30,left=500');
                                                   return false;">
                                            {{$file->file_name}}</a>
                                        <span class="mailbox-attachment-size">
                                                {{ $file->size($file->file_size) }}
                                          <a href="{{ url('fe-fileDownload',['id'=>$file->id]) }}"
                                             class="btn btn-danger btn-xs pull-right"><i
                                                      class="fa fa-download"></i></a>
                                        </span>
                                    </div>

                                </li>

                            @endforeach
                        </ul>
                        <div class="pull-left">
                            @if(isset($file))
                                <a href="{{ route('fe-download-all',['message_id'=>$file->message_id]) }}" class="btn btn-default">
                                    <i class="fa fa-download"></i> 
                                    @lang('blade.download_all')
                                </a>
                            @endif
                        </div>

                    </div>


                    <div id="blade_append"></div>

                    <div class="box-footer">
                        <div class="pull-right">
                            <button type="button" class="btn btn-primary to_forward" value="{{ $model->id }}">
                                <i class="fa fa-share"></i> @lang('blade.forward')
                            </button>
                        </div>

                        <button type="button" class="btn btn-danger" id="deleteMessage"
                                value="{{ $model->id }}">
                            <i class="fa fa-trash"></i> @lang('blade.delete')
                        </button>

                    </div>

                </div>
            </div>

            <div class="col-md-3">
                <div class="box box-success direct-chat direct-chat-success">

                    <div class="box-header with-border">
                        <h3 class="box-title">@lang('blade.receivers'): <b id="users_total"></b></h3>

                        <div class="box-tools pull-right">
                            <b class="text-green"><span id="is_read"></span></b> / <b class="text-maroon"><span id="not_read"></span></b>
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>

                    <div class="box-body">

                        <input id="model_id" value="{{ $model->id }}" hidden>

                        <div class="box-footer box-comments">
                            <div id="append_sent_users"></div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>

    <script>

        $("#deleteMessage").click(function () {

            let id = $(this).val();

            $.ajax({
                url: '/fe/getBlade',
                type: 'GET',
                data: {type: 'delete'},
                dataType: 'json',
                beforeSend: function(){
                    $("#loading").show();
                },
                success: function(res){
                    $('#blade_append').html(res.blade);
                    $('#ConfirmModal').data('id', id).modal('show');

                },
                complete:function(res){
                    $("#loading").hide();
                }

            });

        });

        $(".to_forward").click(function () {

            let id = $(this).val();

            $.ajax({
                url: '/fe/getBlade',
                type: 'GET',
                data: {id: id, type: 'forward'},
                dataType: 'json',
                beforeSend: function(){
                    $("#loading").show();
                    $('#forward_append').empty();
                },
                success: function(res){
                    $('#blade_append').html(res.blade);
                    $("#myModalU").modal('show');

                },
                complete:function(data){
                    $("#loading").hide();
                }

            });

        });

        function getSentUsersAuto(){

            let model_id = $('#model_id').val();

            $.ajax({
                url: '/fe/getSentUsers',
                type: 'GET',
                data: {id: model_id, type: 'sentUsersInbox'},
                dataType: 'json',
                success: function(res){

                    let uTotal = res.isRead + res.notRead;

                    $('#append_sent_users').html(res.users);

                    $('#users_total').html(uTotal);

                    $('#is_read').html(res.isRead);

                    $('#not_read').html(res.notRead);

                },
                complete:function(res){
                    $("#loading").hide();
                }

            });
        }
        setTimeout(getSentUsersAuto, 200);

    </script>

    <script src="{{ asset("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

@endsection
