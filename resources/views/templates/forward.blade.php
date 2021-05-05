<link href="{{ asset('css/treeview.css') }}" rel="stylesheet">

<div id="myModalU" class="modal fade">

    <div class="modal-dialog modal-confirm modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-primary">
                <h4 class="modal-title">
                    <i class="fa fa-mail-forward"> @lang('blade.forward')</i></h4>
            </div>

            <form role="form" method="POST" action="{{ route('post-fe-forward') }}" enctype="multipart/form-data">
                {{csrf_field()}}

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
                        <textarea name="subject" rows="2" class="form-control"
                                  maxlength="250">{{$model->subject}}</textarea>
                        <input name="message_id" value="{{$model->id}}" hidden>
                        <input name="status" value="forward" hidden>
                    </div>
                    <div class="pull-right">
                        <a href="" class="btn btn-default" data-dismiss="modal">
                            <i class="fa fa-remove"></i> @lang('blade.cancel')
                        </a>

                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-envelope-o"></i> @lang('blade.send')
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>

<script>

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
                var obj = data;
                var user_input = "";

                $.each(obj['msg'], function (key, val) {
                    user_input +=
                        "<label class='text-black'>" +
                        "<input id='to_users' name='to_users[]' value=" + val.id + " class='user_checkbox' type='checkbox'>" +
                        " " + val.lname + " " + val.fname +
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

</script>

<script src="{{ asset('js/treeview.js') }}"></script>
