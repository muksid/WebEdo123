@extends('layouts.table')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Chat
            <small>yangi</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/home"><i class="fa fa-dashboard"></i> Bosh sahifa</a></li>
            <li><a href="/chat">Chat</a></li>
            <li class="active">{{ $user->lname. ' ' .$user->fname }}</li>
        </ol>
    </section>


    <?php
    $chatDate = \Carbon\Carbon::parse();
    //echo $chatDate->setTimeFromTimeString($user->created_at)->diffForHumans();
    //\Carbon\Carbon::createFromTimeStamp(strtotime($value->created_at))->diffForHumans()
    ?>
    <!-- Main content -->
    <section class="content">
        <!-- Direct Chat -->
        <div class="row">
            <!-- /.col -->
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="form-group">
                            <div class="has-feedback">
                                <input type="text" id="search_user" class="form-control" placeholder="Barcha xodimlardan izlash">
                                {{csrf_field()}}
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            </div>
                        </div>
                        <!-- /.box-tools -->
                    </div>
                    <div class="#mydiv"></div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding" style="overflow-y: scroll; max-height: 700px;">
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-hover table-striped" id="after_searched">
                                <tbody>
                                <tr class="left-current-user">
                                    <td class="mailbox-star"></td>
                                    <td class="mailbox-subject">
                                        <a href="/chat/{{ $user->id }}">{{ $user->lname. ' ' .$user->fname }}</a>
                                    </td>
                                    <td class="mailbox-subject">
                                        <small>{{ $user->job_title }}</small>
                                    </td>
                                    <td class="mailbox-date">
                                        <b><i class="fa fa-send text-green"></i></b>
                                    </td>
                                </tr>
                                @foreach($unReadMessages as $key => $message)
                                    <div class="bg-gray disabled color-palette text-center">O`qilmagan xabarlar</div>
                                    <tr>
                                        <td class="mailbox-star"></td>
                                        <td class="mailbox-subject">
                                            <a href="/chat/{{ $message->id }}">{{ $message->full_name }}</a>
                                        </td>
                                        <td class="mailbox-subject">
                                            <small>{{ $message->job_title }}</small>
                                        </td>
                                        <td class="mailbox-date">
                                            <b><small class="label label-success">{{ $message->total_count }}</small></b>
                                        </td>
                                    </tr>
                                @endforeach

                                @foreach($lastConversationUsers as $key => $value)
                                    <tr>
                                        <td class="mailbox-star"></td>
                                        <td class="mailbox-subject">
                                            <a href="/chat/{{ $value->id }}">{{ $value->full_name }}</a>
                                        </td>
                                        <td class="mailbox-subject">
                                            <small>{{ $value->job_title }}</small>
                                        </td>
                                        <td class="mailbox-date">
                                            <b><small>{{ $value->branch_code }}</small></b>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <!-- /.table -->
                        </div>
                        <!-- /.mail-box-messages -->
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /. box -->
            </div>
            <!-- /.col -->

            <div class="col-md-5">
                <!-- DIRECT CHAT SUCCESS -->
                <div class="box box-success direct-chat direct-chat-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-user text-green"></i> {{ $user->lname. ' ' .$user->fname }}</h3>

                        <div class="box-tools pull-right">
                            <span data-toggle="tooltip" title="3 New Messages" class="badge bg-green"></span>
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="Profil"
                                    data-widget="chat-pane-toggle">
                                <i class="fa fa-comments"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <!-- Conversations are loaded here -->
                        <div id="chat_page" class="direct-chat-messages" style="height: 500px">
                            <!-- Message. Default to the left -->
                            <div id="message">
                                @foreach($currentMessage as $current)
                                    <div class="direct-chat-msg">
                                        <div class="direct-chat-info clearfix">
                                            <span class="direct-chat-name pull-left">{{ $current->lname. ' ' .$current->fname }}</span>
                                            <span class="direct-chat-timestamp pull-right">{{ $current->created_at  }}</span>
                                        </div>
                                        <!-- /.direct-chat-info -->
                                        <img class="direct-chat-img" src="{{ url('/admin-lte/dist/img/user.png') }}"
                                             alt="{{ $current->fname }}"><!-- /.direct-chat-img -->
                                        <div class="direct-chat-text">
                                            {{ $current->message }}
                                        </div>
                                        <!-- /.direct-chat-text -->
                                    </div>
                                    <!-- /.direct-chat-msg -->
                                @endforeach
                            </div>

                        </div>
                        <!--/.direct-chat-messages-->

                        <!-- Contacts are loaded here -->
                        <div class="direct-chat-contacts">
                            <ul class="contacts-list">
                                <li>
                                    <a href="#">
                                        <img class="contacts-list-img" src="{{ url('/admin-lte/dist/img/user.png') }}"
                                             alt="{{ $user->fname }}">

                                        <div class="contacts-list-info">
                            <span class="contacts-list-name">
                              {{ $user->lname. ' ' .$user->fname }}
                              <small class="contacts-list-date pull-right">{{ $user->created_at }}</small>
                            </span>
                                            <span class="contacts-list-msg">{{ $user->branch_code. ' ' .$user->job_title }}</span>
                                        </div>
                                        <!-- /.contacts-list-info -->
                                    </a>
                                </li>
                                <!-- End Contact Item -->
                            </ul>
                            <!-- /.contatcts-list -->
                        </div>
                        <!-- /.direct-chat-pane -->
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <div class="input-group form-group has-success">
                            <input type="text" name="message" id="message_chat" placeholder="Xabar yozish ..."
                                   class="form-control">
                            {{csrf_field()}}
                            <span class="input-group-btn">
                                <button type="submit" id="send" class="btn btn-success btn-flat"><i class="fa fa-send"></i> Send</button>
                                  </span>
                        </div>
                    </div>
                    <!-- /.box-footer-->
                </div>
                <!--/.direct-chat -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <script>
            $(function () {

                $("#search_user").autocomplete({
                    source: function (request, response) {
                        $.ajax({
                            url: '/get-search-users',
                            type: 'POST',
                            dataType: 'JSON',
                            data: {
                                _token: $('input[name=_token]').val(),
                                users: $('#search_user').val(),
                                q: request.term
                            },
                            success: function (data) {
                                //console.log(data);
                                var searched = '';
                                for (var i = 0; i < data.length; i++) {
                                    searched +=
                                        '<tr>' +
                                        '<td class="mailbox-star">' + '</td>' +
                                        '<td class="mailbox-subject"><a href="/chat/' + data[i].id + '">' + data[i].lname + ' ' + data[i].fname + '</a></td>' +
                                        '<td class="mailbox-subject"><small>' + data[i].job_title + '</small></td>' +
                                        '<td class="mailbox-date"><small>' + data[i].branch + '</small></td>' +
                                        '</tr>';
                                }
                                $('#after_searched').html('<tbody>' + searched + '</tbody>');
                            }
                        });
                    },
                    minLength: 3,
                    select: function (event, ui) {
                        log(ui.item ?
                            "Selected: " + ui.item.label :
                            "Nothing selected, input was " + this.value);
                    },
                    open: function () {
                        $(this).removeClass("table-striped1").addClass("table-striped2");
                    },
                    close: function () {
                        $(this).removeClass("table-striped2").addClass("table-striped1");
                    }
                });
            });
        </script>

        <script type="text/javascript">
            $('#chat_page').animate({
                scrollTop: $('#chat_page').get(0).scrollHeight
            }, 1500);

            $(document).ready(function () {
                setTimeout(realTime, 2000);
            });

            var from_user_id1 = "{{ Auth::id() }}";
            var to_user_id1 = "{{ $user->id }}";

            function realTime() {
                $.ajax({
                    type: 'post',
                    url: '/get-message',
                    data: {
                        '_token': $('input[name=_token]').val(),
                        current_user: to_user_id1,
                    },
                    success: function (data) {

                        $('#message').replaceWith(' <div id="message"></div>');

                        if (!$.trim(data)){
                            $('#message').append('<div class="alert alert-info alert-dismissible">'+
                                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+
                                '<h4><i class="icon fa fa-ban"></i> Xabarlar!</h4>'+
                                'Yozishmalar topilmadi.'+'</div>');
                        }

                        var lr = '';
                        var read   = ' <i class="fa fa-check text-green"></i>';
                        var unRead = ' <i class="fa fa-check text-red"></i>';
                        for (var i = 0; i < data.length; i++) {
                            if(data[i].is_readed === 0){
                                read = unRead;
                            }
                            $('#message').append(
                                '<div class="direct-chat-msg ' + lr + '">' +
                                '<div class="direct-chat-info clearfix">' +
                                '<span class="direct-chat-name pull-left">' + data[i].lname + ' ' + data[i].fname + '</span>' +read+
                                '<span class="direct-chat-timestamp pull-right">' + data[i].created_at + '</span>' +
                                '</div>' +
                                '<img class="direct-chat-img" src="/admin-lte/dist/img/user.png" alt="' + data[i].fname + '">' +
                                '<div class="direct-chat-text">' + data[i].message + '</div>' +
                                '</div>');
                        }
                    },
                });
                setTimeout(realTime, 2000);
            }

            // For send message keyUp //
            $('#message_chat').keyup(function (e) {
                if (e.keyCode == 13) {
                    $(this).trigger("enterKey");

                    $('#chat_page').animate({
                        scrollTop: $('#chat_page').get(0).scrollHeight
                    }, 1500);
                    setTimeout((function () {
                        $("#chat_page").animate({top: 0}, {duration: 500});
                    }), 1000);

                    $.ajax({
                        type: 'post',
                        url: '/send-message',
                        data: {
                            '_token': $('input[name=_token]').val(),
                            from_user_id: from_user_id1,
                            to_user_id: to_user_id1,
                            message: $('#message_chat').val(),
                        },
                        success: function (data) {
                            //console.log(data);
                            $('#message').append(
                                '<div class="direct-chat-msg right">' +
                                '<div class="direct-chat-info clearfix">' +
                                '<span class="direct-chat-timestamp pull-right">' + data.created_at + '</span>' +
                                '</div>' +
                                '<img class="direct-chat-img" src="/admin-lte/dist/img/user.png">' +
                                '<div class="direct-chat-text">' + data.message + '</div>' +
                                '</div>');

                            $('#message_chat').val('');
                        },
                        error: function () {
                            console.log('error sent');
                        }
                    });
                    $('input[name=subject]').val('');
                }
            });
            // For click send //
            $(document).on('click', '#send', function () {
                $('#chat_page').animate({
                    scrollTop: $('#chat_page').get(0).scrollHeight
                }, 1500);
                $.ajax({
                    type: 'post',
                    url: '/send-message',
                    data: {
                        '_token': $('input[name=_token]').val(),
                        from_user_id: from_user_id1,
                        to_user_id: to_user_id1,
                        message: $('#message_chat').val(),
                    },
                    success: function (data) {
                        //console.log(data);
                        $('#message').append(
                            '<div class="direct-chat-msg right">' +
                            '<div class="direct-chat-info clearfix">' +
                            '<span class="direct-chat-timestamp pull-right">' + data.created_at + '</span>' +
                            '</div>' +
                            '<img class="direct-chat-img" src="/admin-lte/dist/img/user.png">' +
                            '<div class="direct-chat-text">' + data.message + '</div>' +
                            '</div>');

                        $('#message_chat').val('');
                    },
                    error: function () {
                        console.log('error sent');
                    }
                });
                $('input[name=subject]').val('');
            });
        </script>

        <!-- jQuery 2.2.3 -->
        <script src="{{ asset ("admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

        <!-- AdminLTE App -->
        <script src="{{ asset("/admin-lte/dist/js/app.min.js") }}"></script>

        <script src="{{ asset("/admin-lte/dist/js/jquery-ui.min.js") }}"></script>

    </section>
    <!-- /.content -->
@endsection
