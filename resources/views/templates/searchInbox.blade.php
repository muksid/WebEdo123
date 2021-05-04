<div class="table-responsive mailbox-messages">

    <b id="search_total">@lang('blade.overall'){{': '. $models->total()}} @lang('blade.group_edit_count').</b>
    <table id="example1" class="table table-hover table-striped">
        <thead>
        <tr>
            <th>#</th>
            <th><i class="fa fa-bank"></i> @lang('blade.branch')</th>
            <th><i class="fa fa-user"></i> @lang('blade.from_whom')</th>
            <th><i class="fa fa-link"></i> @lang('blade.position')</th>
            <th><i class="fa fa-text-height"></i> @lang('blade.subject')</th>
            <th><i class="fa fa-clock-o"></i> @lang('blade.sent_date')</th>
            <th><i class="fa fa-paperclip"></i> @lang('blade.file')</th>
            <th>
                <button type="button" class="btn btn-outline-danger checkbox-toggle">
                    <i class="fa fa-trash-o text-red"></i> <strong class="text-maroon">Barchasini belgilash</strong>
                </button>
                <div class="pull-right">
                    <button type="button" class="btn btn-danger btn-flat deleteMessage" data-url="">
                        <i class="fa fa-trash-o"></i> @lang('blade.delete_selected')
                    </button>
                </div>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php $i = 1;?>
        @if($models->count())
            @foreach ($models as $key => $model)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $model->user->filial->title??'' }}</td>
                    <td>
                        <a href="{{route('fe-view',['id'=>$model->message->id, 'mes_gen'=>$model->message->mes_gen])}}">
                            {{ $model->user->lname??''}} {{$model->user->fname??'' }}
                        </a>
                    </td>
                    <td>
                        <span class="text-sm">{!! wordwrap($model->user->department->title??'', 80, "<br />") !!}</span>
                    </td>
                    <td>
                        {!! \Illuminate\Support\Str::words($model->message->subject??'', 5, ' ...') !!}
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($model->created_at)->format('d M, Y H:i') }}
                    </td>
                    <td>
                        <button type="button" class="btn btn-flat btn-primary getFiles"
                                value="{{ $model->message_id }}">
                            <i class="fa fa-paperclip"></i>
                        </button>
                    </td>
                    <td class="text-center">
                        <input type="checkbox" class="checkbox checkbox-checked" data-id="{{$model->id}}">
                    </td>
                </tr>
            @endforeach

        @else
            <td colspan="8" class="text-red text-center"><i class="fa fa-search"></i>
                <b>@lang('blade.not_found')</b>
            </td>
        @endif
        </tbody>
    </table>

    <nav>{{ $models->appends(Request::except('page'))->links() }}</nav>

</div>
<script>

    $(".getFiles").click(function () {

        let id = $(this).val();

        $.ajax({
            url: '/fe/getBlade',
            type: 'GET',
            data: {id: id, type: 'files'},
            dataType: 'json',
            beforeSend: function(){
                $("#loading").show();
            },
            success: function(res){
                $('#blade_append').html(res.blade);
                $('#filesModal').modal('show');

            },
            complete:function(res){
                $("#loading").hide();
            }

        });

    });

    $('.deleteMessage').hide();

    $(".deleteMessage").click(function () {

        let id = $(this).val();

        $.ajax({
            url: '/fe/getBlade',
            type: 'GET',
            data: {type: 'deleteAll'},
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

    $(function () {

        $('.mailbox-messages input[type="checkbox"]').iCheck({
            checkboxClass: 'icheckbox_flat-blue',
            radioClass: 'iradio_flat-blue'
        });

        $(".deleteMessage").hide();

        $(".checkbox-toggle").click(function () {
            var clicks = $(this).data('clicks');
            if (clicks) {
                $(".deleteMessage").hide(200);
                //Uncheck all checkboxes
                $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
                $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');

            } else {
                //if ($('input:checkbox:checked').length !== 0){
                    $(".deleteMessage").show(300);
                //}
                //Check all checkboxes
                $(".mailbox-messages input[type='checkbox']").iCheck("check");
                $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
            }
            $(this).data("clicks", !clicks);
        });

        $(".iCheck-helper").click(function() {
            var numberNotChecked = $('input:checkbox:checked').length;
            if(numberNotChecked > 0) {
                $(".deleteMessage").show(300);
            } else {
                $(".deleteMessage").hide(200);
            }
        });

    });

</script>

