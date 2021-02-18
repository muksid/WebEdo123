@extends('layouts.table')

@section('content')
    <link href="{{ asset('css/treeview.css') }}" rel="stylesheet">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Mening guruhlarim
            <small>jadval</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Bosh sahifa</a></li>
            <li><a href="#">Guruh</a></li>
            <li class="active">guruh jadvali</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Direct Chat -->
        <div class="row">
            <div class="col-md-6">
                <div class="box-body">
                    <div class="form-group">
                        <ul id="tree1">
                            @foreach($departments as $department)
                                <li>
                                    {{ $department->title }}
                                    @if(count($department->childs))
                                        @include('chat.ajax',['childs' => $department->childs])
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /.col -->

            <div class="col-md-3">
                <!-- DIRECT CHAT SUCCESS -->
                <div class="box box-success direct-chat direct-chat-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Chat</h3>

                        <div class="box-tools pull-right">
                            <span data-toggle="tooltip" title="3 New Messages" class="badge bg-green">3</span>
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="Contacts" data-widget="chat-pane-toggle">
                                <i class="fa fa-comments"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <!-- Conversations are loaded here -->
                        <div class="direct-chat-messages">
                            <!-- Message. Default to the left -->
                            <div class="direct-chat-msg">
                                <div class="direct-chat-info clearfix">
                                    <span class="direct-chat-name pull-left">Alexander Pierce</span>
                                    <span class="direct-chat-timestamp pull-right">23 Jan 2:00 pm</span>
                                </div>
                                <!-- /.direct-chat-info -->
                                <img class="direct-chat-img" src="{{ url('/admin-lte/dist/img/user.png') }}" alt="Message User Image"><!-- /.direct-chat-img -->
                                <div class="direct-chat-text">
                                    Is this template really for free? That's unbelievable!
                                </div>
                                <!-- /.direct-chat-text -->
                            </div>
                            <!-- /.direct-chat-msg -->

                            <!-- Message to the right -->
                            <div class="direct-chat-msg right">
                                <div class="direct-chat-info clearfix">
                                    <span class="direct-chat-name pull-right">Sarah Bullock</span>
                                    <span class="direct-chat-timestamp pull-left">23 Jan 2:05 pm</span>
                                </div>
                                <!-- /.direct-chat-info -->
                                <img class="direct-chat-img" src="{{ url('/admin-lte/dist/img/user.png') }}" alt="Message User Image"><!-- /.direct-chat-img -->
                                <div class="direct-chat-text">
                                    You better believe it!
                                </div>
                                <!-- /.direct-chat-text -->
                            </div>
                            <!-- /.direct-chat-msg -->
                        </div>
                        <!--/.direct-chat-messages-->

                        <!-- Contacts are loaded here -->
                        <div class="direct-chat-contacts">
                            <ul class="contacts-list">
                                <li>
                                    <a href="#">
                                        <img class="contacts-list-img" src="{{ url('/admin-lte/dist/img/user.png') }}" alt="User Image">

                                        <div class="contacts-list-info">
                            <span class="contacts-list-name">
                              Count Dracula
                              <small class="contacts-list-date pull-right">2/28/2015</small>
                            </span>
                                            <span class="contacts-list-msg">How have you been? I was...</span>
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
                        <form action="#" method="post">
                            <div class="input-group">
                                <input type="text" name="message" placeholder="Type Message ..." class="form-control">
                                <span class="input-group-btn">
                        <button type="submit" class="btn btn-success btn-flat">Send</button>
                      </span>
                            </div>
                        </form>
                    </div>
                    <!-- /.box-footer-->
                </div>
                <!--/.direct-chat -->
            </div>
            <!-- /.col -->

            <!-- /.col -->
        </div>
        <!-- /.row -->
        <script type="text/javascript">
            $(document).ready(function () {
                // For plus minus click departments
                $(".btn-success").click(function () {
                    var html = $(".clone").html();
                    $(".increment").after(html);
                });

                $("body").on("click", ".btn-danger", function () {
                    $(this).parents(".control-group").remove();
                });
                // End //

                // For post ajax
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                var branch_code = $("input[name=branch_code]").val();
                $(".post_users").click(function () {
                    var user_depart_id = $(this).next().val();
                    $.ajax({
                        url: '/postajax',
                        type: 'POST',
                        data: {_token: CSRF_TOKEN, name: user_depart_id},
                        dataType: 'JSON',
                        success: function (data) {
                            var obj = data;
                            var user_input = "";
                            var users = "";

                            $.each(obj['msg'], function (key, val) {
                                user_input += "<div class='form-check .form-group'>" +
                                    "<input id='to_users' name='users_id[]' value=" + val.id + " class='flat-red' type='checkbox'>" +
                                    "<label class='form-check-label' style='color: initial' for='materialUnchecked'>" +
                                    "<a href='chat/"+val.id+"/edit'>"+val.lname+' '+val.fname+ "</a>"+
                                    " <span style='font-size: x-small;font-style: italic;color: #31708f;'>" +
                                    val.job_title + "</span></div>";
                            });

                            $("#mydiv" + data.branch).html(user_input)   //// For replace with previous one
                        },
                        error: function () {

                            console.log(data);
                        }
                    });
                });
                // End //

            });
        </script>
    </section>
    <!-- /.content -->
    <!-- Slimscroll -->
    <script src="admin-lte/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="admin-lte/plugins/fastclick/fastclick.js"></script>
    <script src="{{ asset('js/treeview.js') }}"></script>
@endsection
