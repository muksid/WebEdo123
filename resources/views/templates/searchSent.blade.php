<div class="box-body mailbox-messages">
    <b>@lang('blade.overall'){{': '. $models->total()}} @lang('blade.group_edit_count').</b>
    <table class="table table-hover table-striped">
        <thead>
        <tr>
            <th>#</th>
            <th><i class="fa fa-user-plus"></i> @lang('blade.to_whom')</th>
            <th><i class="fa fa-text-height"></i> @lang('blade.subject')</th>
            <th><i class="fa fa-clock-o"></i> @lang('blade.sent_date')</th>
            <th><i class="fa fa-paperclip"></i> @lang('blade.file')</th>
            <th><i class="fa fa-trash-o"></i> @lang('blade.delete')</th>
        </tr>
        </thead>
        <tbody>
        @if($models->count())
            @foreach ($models as $key => $model)
                @php($key = $key+1)
                <tr id="rowId_{{$model->id}}">
                    <td>{{ $key++ }}</td>
                    <td class="text-maroon" style="min-width: 30px">

                        <button type="button" class="btn btn-flat btn-info getUsers"
                                value="{{ $model->id }}">
                            <i class="fa fa-search-plus"></i>
                        </button>
                    </td>
                    <td>
                        <a href="{{route('fe-view-sent',['id'=>$model->id,'mes_gen'=>$model->mes_gen])}}">
                            {!! \Illuminate\Support\Str::words($model->subject, 10, '...'); !!}
                        </a>
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($model->created_at)->format('d M, Y H:i') }}
                    </td>
                    <td>
                        <button type="button" class="btn btn-flat btn-primary getFiles"
                                value="{{ $model->id }}">
                            <i class="fa fa-paperclip"></i>
                        </button>
                    </td>
                    <td>
                        <button type="button" class="btn btn-flat btn-danger deleteMessage"
                                value="{{ $model->id }}">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach

        @else
            <td colspan="6" class="text-red text-center"><i class="fa fa-search"></i>
                <b>@lang('blade.not_found')</b></td>
        @endif
        </tbody>
    </table>
    <nav>{{ $models->appends(Request::except('page'))->links() }}</nav>
</div>
<script>

    $(".getUsers").click(function () {

        let id = $(this).val();

        $.ajax({
            url: '/fe/getBlade',
            type: 'GET',
            data: {id: id, type: 'users'},
            dataType: 'json',
            beforeSend: function(){
                $("#loading").show();
            },
            success: function(res){
                $('#blade_append').html(res.blade);
                $('#usersModal').modal('show');

            },
            complete:function(res){
                $("#loading").hide();
            }

        });

    });

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

    $(".deleteMessage").click(function () {

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

</script>

