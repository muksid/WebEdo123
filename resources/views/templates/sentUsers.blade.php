<div id="usersModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><i class="fa fa-user-plus text-green"></i> @lang('blade.senders')
                    {{ $users->count() }} @lang('blade.group_edit_count'). <span class="text-maroon">{{ $users->count()-$isRead }}</span> / <span class="text-green">{{ $isRead }}</span>
                </h4>
            </div>

            <div class="modal-body">
                <div class="box-comment">
                    @foreach($users as $key => $user)
                        <span class="pull-left text-bold">{{ $key = $key+1 }}. </span>
                        <b>{{ $user->toUsers->lname??'' }} {{ $user->toUsers->fname??'' }} {{ $user->toUsers->sname??'' }}</b>
                        <span class="username">
                            <span class="text-muted pull-right text-green">
                                @if($user->is_readed == 1)
                                    <i class="fa fa-clock-o"></i> {{ \Carbon\Carbon::parse($user->readed_date)->format('d M, Y H:i') }}
                                @else
                                    <i class="fa fa-check text-maroon"></i>
                                @endif
                            </span>
                          </span>

                        <div class="comment-text">
                            <span class="text-sm">{{ $user->toUsers->filial->branch_code??'' }} - {{ $user->toUsers->filial->title??'' }}, {{ $user->toUsers->department->title??'' }}</span>

                        </div><br>
                    @endforeach
                </div>
            </div>

            <div class="modal-footer">
                <a class="btn btn-default" data-dismiss="modal">@lang('blade.close')</a>
            </div>

        </div>
    </div>
</div>
