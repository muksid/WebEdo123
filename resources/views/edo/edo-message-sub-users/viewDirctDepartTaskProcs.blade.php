<?php ?>
@extends('layouts.edo.dashboard')

@section('content')

    <!-- TRANSLATED -->

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
            @elseif($message = Session::get('cancel'))
                <div class="modal fade in" id="myModal" role="dialog" style="display: block">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header bg-yellow-active">
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
                                        <span class="text-maroon">({{ $model->journalUser->userJob->userRole->title_ru }})</span></td>
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
                                <td>{{ \Carbon\Carbon::parse($model->out_date)->format('d-M-Y')  }}</td>
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
                        <h5><strong>@lang('blade.sender_organization'))</strong></h5>
                        <p>{{ $model->from_name }}</p>
                        <hr>
                        <strong><i class="fa fa-file margin-r-5"></i> @lang('blade.doc_app') </strong><br><br>
                        @foreach($model->files as $file)
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
                        <h3 class="box-title">@lang('blade.reply_doc')</h3>
                    </div>
                    @if(!count($replyMessage))
                        <h4 class="text-center text-yellow">@lang('blade.not_found_reply_letters')!</h4>
                    @endif
                    @foreach($replyMessage as $key => $reply)
                        <div class="box box-widget">
                            <div class="box-header with-border">
                                <div class="user-block">
                                    <img class="img-circle" src="/../admin-lte/dist/img/user.png" alt="User Image">
                                    @if($reply->user_id == Auth::id())
                                        <span class="username text-green">@lang('blade.me').</span>
                                        @else
                                        <span class="username">{{ $reply->replyUser->lname.' '.$reply->replyUser->fname }}.</span>
                                    @endif
                                    <span class="description">{{ $reply->created_at->format('d-F Y') }} - {{ $reply->created_at->diffForHumans() }}</span>
                                </div>
                                <!-- /.user-block -->
                                <div class="box-tools">
                                    <button type="button" class="btn btn-box-tool text-red" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                </div>
                                <!-- /.box-tools -->
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <!-- post text -->
                                <p>{{ $reply->text }}... <a href="#">@lang('blade.more')</a></p>

                                <!-- Attachment -->
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
                                    <i class="text-red">({{ $file->size($file->file_size)??'' }})</i>
                                    <br><br>
                                @endforeach
                                <!-- /.attachment-block -->

                                @if($reply->status == 0 && (Auth::user()->edoUsers() == 'director_department' || Auth::user()->edoUsers() == 'deputy_of_director'))
                                    
                                    <div id="rec{{$reply->id}}"></div>
                                    <button type="button" value="{{$reply->id}}" id="receive{{$reply->id}}" class="btn btn-default btn-xs reply-receive">
                                        <i class="fa fa-check-circle text-green"></i> @lang('blade.receive')
                                    </button>
                                    <a href="{{ route('edo-s-reply-cancel',['id'=>$reply->id]) }}" class="text-maroon pull-right">
                                        <i class="fa fa-ban"></i> @lang('blade.reject')
                                    </a>

                                @elseif($reply->status == 1)
                                    
                                    <span class="label label-primary"><i class="fa fa-hourglass-start"></i> @lang('blade.on_process') ...</span>
                                    <form action="{{ url('edo-reply-messages/'.$reply->id) }}" method="POST" style="display: inline-block">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> @lang('blade.delete')</button>
                                    </form>

                                @elseif($reply->status == -1)
                                    
                                    <span class="label label-warning"><i class="fa fa-ban"></i> @lang('blade.rejected') ...</span>
                                    <form action="{{ url('edo-reply-messages/'.$reply->id) }}" method="POST" style="display: inline-block">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> @lang('blade.delete')</button>
                                    </form>

                                @elseif($reply->status == 2)
                                    <span class="label label-success"><i class="fa fa-hourglass-start"></i> @lang('blade.sent_to_approve') ...</span>
                                @elseif($model->journalUser->status == 3)
                                    <h4 class="text-center text-green text-bold"><i class="fa fa-check-circle"></i> @lang('blade.task_closed')</h4>
                                @endif

                            </div>
                            <!-- /.box-body -->
                        </div>
                    @endforeach
                <!-- Post -->

                    @if($isRead->sub_status != 3)
                        <div class="box-header with-border bg-gray">
                            <i class="fa fa-reply"></i>
                            <h3 class="box-title">Javob berish{{ $isRead->status }}</h3>
                        </div>
                        <form action="{{ route('reply.message') }}" method="post" enctype="multipart/form-data" >
                            {{ csrf_field() }}
                            <div class="box-body">
                                <label>Qisqa mazmuni</label><sup class="text-red"> *</sup>
                                <div class="form-group">
                                    <textarea name="text" class="form-control" rows="3" required></textarea>
                                    <input name="edo_message_id" value="{{ $model->id }}" hidden />
                                    <input name="director_id" value="{{ $isRead->from_user_id }}" hidden />
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
                                    <button class="btn btn-reddit btn-submit-reply">
                                        <i class="fa fa-envelope-o"></i> @lang('blade.send_message')
                                    </button>
                                    @if(count($replyMessage))
                                        <button type="button" value="{{$model->id}}" id="replyConfirm"
                                                class="btn btn-bitbucket pull-right">
                                            <i class="fa fa-check-circle"></i> @lang('blade.approve_reply')
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </form>

                    @endif
                    <!-- /.box -->

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
                                        <p class="help-block">Max. 12MB</p>
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
                            <button type="button"
                                    onclick="window.location.href='/reply-d-d-confirm/{{$model->id}}/{{$isRead->from_user_id}}'"
                                    id="mConfirm" class="btn btn-success" data-dismiss="modal">
                                <i class="fa fa-check-circle"></i> @lang('blade.approve')
                            </button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('blade.close')</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Widget: user widget style 1 -->
                <div class="box box-widget widget-user-2">
                    <div class="widget-user-header bg-light-blue">
                        <div class="widget-user-image">
                            <img class="profile-user-img img-responsive" style="width: 35%; border: hidden" src="{{ asset("/admin-lte/dist/img/footer__logo.svg") }}" alt="User">
                        </div>
                        <!-- /.widget-user-image -->
                        <h3 class="widget-user-username text-center">@lang('blade.tb')</h3>
                        <h5 class="widget-user-desc text-center">{{$director->director->title}}</h5>
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
                            </h5><hr>
                            <h5 class="text-bold">№ {{ $model->out_number ?? 'Null' }}
                                <span class="pull-right">{{ ($director->director->full_name) ?? 'Null' }}.</span>
                            </h5>
                            <h5>{{ $model->updated_at->format('d M. Y H:i') }}<span class="pull-right">{{ $director->director->job_title }} </span></h5>
                        </div>

                        <div class="box-footer">
                            <div class="slider__pagination-item ng-scope is-active" title="{{ ($director->director->full_name) ?? 'Null' }}">
                                <div class="slider__thumb">
                                </div>
                            </div>
                        </div>
                        <!-- /.box-footer -->
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

        <link href="{{ asset ("/admin-lte/bootstrap/css/bootstrap-datepicker.css") }}" rel="stylesheet"/>
        <script src="{{ asset ("/admin-lte/bootstrap/js/datepicker/bootstrap.min.js") }}"></script>
        <script src="{{ asset ("/admin-lte/bootstrap/js/bootstrap-datepicker.js") }}"></script>

        <script type="text/javascript">
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
                                            "<i class='fa fa-times repl-edit-form' title='o`chirish'>" +
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
                        rec+="<span class='label label-primary'>jarayonda ...</span>";
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
                $.ajax({
                    url: '/req-confirm',
                    type: 'POST',
                    data: {_token: CSRF_TOKEN, id: message_id, dId: director_id},
                    dataType: 'JSON',
                    success: function (data) {
                        var req='';
                        $('#mConfirm').hide();
                        if (data.mCount == 0){
                            $('#mConfirm').show();
                        }
                        $('#req-message').html(data.message);
                        req+="<span class='label label-primary'>@lang('blade.receive') ...</span>";
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
                    url: '/reply-d-d-confirm',
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
                    alert("@lang('blade.max_limit_err_12')). (max: 12 MB)");
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