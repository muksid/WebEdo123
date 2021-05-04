<div class="box-body users-tree">
    <div class="form-group">
        <ul id="tree1">
            @foreach($departments as $department)
                <li>
                    {{ $department->branch_code .' '. $department->title }}
                    @if(count($department->childs))
                        @include('messages.ajax',['childs' => $department->childs])
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</div>

<script src="{{ asset('js/treeview.js') }}"></script>

<script type="text/javascript">
    // For post ajax
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

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
                $('.post_users').show();

                $.each(obj['msg'], function (key, val) {
                    user_input +=
                        "<label class='text-black'>" +
                        "<input id='to_users' name='to_users[]' value=" + val.id + " class='user_checkbox' type='checkbox'>" +
                        " "+val.lname + " " + val.fname +" " + val.sname +
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
