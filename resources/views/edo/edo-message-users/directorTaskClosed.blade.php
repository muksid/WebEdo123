@extends('layouts.edo.dashboard')

@section('content')

    <!-- TRANSLATED -->

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang('blade.closed')
            <small>@lang('blade.groups_table')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home_page')</a></li>
            <li><a href="#">@lang('blade.doc')</a></li>
            <li class="active">@lang('blade.groups_table')</li>
        </ol>
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>@lang('blade.error')</strong> @lang('blade.exist').<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($message = Session::get('success'))
            <div class="modal fade in" id="myModal" role="dialog" style="display: block">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header bg-aqua-active">
                            <h4 class="modal-title">
                                @lang('blade.task') <i class="fa fa-check-circle"></i>
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
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box">
                    <form action="{{route('d-tasks-closed')}}" method="POST" role="search">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group has-success">
                                    <input type="text" class="form-control" name="search_t" value="{{ $search_t??'' }}"
                                            placeholder="@lang('blade.text')">
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="form-group">
                                    <div class="input-group">
                                        <button type="button" class="btn btn-default" id="daterange-btn">
                                                <span>
                                                    <i class="fa fa-calendar"></i> Davr oraliq
                                                </span>
                                            <i class="fa fa-caret-down"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <input name="s_start" id="s_start" value="{{$s_start??''}}" hidden>
                            <input name="s_end" id="s_end" value="{{$s_end??''}}" hidden>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <a href="{{ route('d-tasks-closed') }}" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> @lang('blade.reset')</a>
                                    <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-search"></i> @lang('blade.search')</button>
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </form>
                    <!-- /.box-header -->
                    <div class="table-responsive-sm">
                        <b>@lang('blade.overall'){{': '. $models->total()}} @lang('blade.group_edit_count').</b>
                        <table id="" class="table table-hover table-bordered table-striped">
                            <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">@lang('blade.from_whom')</th>
                                <th class="text-center">@lang('blade.reg_num')</th>
                                <th class="text-center">@lang('blade.to_whom')</th>
                                <th class="text-center">@lang('blade.sender_organization')</th>
                                <th class="text-center">Канс №</th>
                                <th class="text-center">@lang('blade.doc_name')</th>
                                <th class="text-center">Исх. №</th>
                                <th class="text-center">@lang('blade.deadline')</th>
                                <th class="text-center">@lang('blade.status')</th>
                                <th class="text-center">@lang('blade.purpose')</th>
                                <th class="text-center">@lang('blade.task')</th>
                                <th class="text-center col-xs-1">@lang('blade.received_date')</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1 ?>
                            @foreach ($models as $key => $model)
                                <tr>

                                    <td>{{ $i++ }}</td>
                                    <td class="text-green" style="min-width: 100px">
                                        {{$model->signatureUser->lname.' '.$model->signatureUser->fname}}
                                    </td>
                                    <td class="text-bold text-center">{{ $model->journal->in_number??'' }}</td>
                                    <td class="text-maroon" style="min-width: 135px">
                                        @foreach($model->perfSubUsers as $key => $user)

                                            @php($userName = \App\User::find($user->to_user_id ?? 'null'))

                                            @if($key < 4 )
                                                {{ mb_substr($userName->fname ?? '', 0,1).'.'.mb_substr($userName->sname ?? '', 0,1).'.'.mb_scrub($userName->lname ?? 'null2') }}
                                                @if($user->is_read == 1)
                                                    @if($user->status == 0)
                                                        <i class="fa fa-check-square text-primary"></i>
                                                    @elseif($user->status == 1)
                                                        <i id="div1"
                                                           class="fa fa-envelope-o text-aqua text-bold"></i>
                                                    @else
                                                        <i class="fa fa-check-square text-primary"></i>
                                                    @endif
                                                @else
                                                    <i class="fa fa-check text-red"></i>
                                                @endif
                                                <br/>
                                            @endif

                                        @endforeach
                                        @if(count($model->perfSubUsers) > 4)
                                            <span class="text-primary text-bold">+{{count($model->perfSubUsers)-4}}</span>
                                        @endif

                                    </td>
                                    <td>
                                        <a href="{{ route('view-d-task-process', ['id' => $model->id, 'slug' => $model->message->message_hash]) }}">
                                            {!! \Illuminate\Support\Str::words($model->message->from_name, 5, '...'); !!}
                                        </a><br>
                                        @if($model->messageLogFile->count())
                                            <span class="text-maroon">Updated Files</span>
                                            <div class="box-footer box-comments bg-aqua">
                                                @foreach($model->messageLogFile as $key => $value)
                                                    @if($value->comments != null)
                                                    <div class="box-comment">
                                                        <div class="comment">
                                                              <span class="username">
                                                                {{ mb_substr($value->fromUser->fname??'null',0,1).'.'.$value->fromUser->lname??'null' }}
                                                                <span class="text-muted pull-right">
                                                                    {{ date_format($value->created_at, 'd M,Y') }}
                                                                </span>
                                                              </span>
                                                            <i class="text-muted">@lang('blade.comment'): </i>{{ $value->comments??'No comment' }}
                                                        </div>
                                                    </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-sm text-center">
                                        <b>{{ $model->message->in_number??''}}</b><br>
                                        {{ \Carbon\Carbon::parse($model->message->out_date)->format('d.m.Y')}}
                                    </td>
                                    <td>
                                        @if(!empty($model->message->title))
                                            {!! \Illuminate\Support\Str::words(($model->message->title ?? ''), 8, '...'); !!}
                                        @else
                                            <i class="text-muted text-sm">(Mavzu yo`q)</i>
                                        @endif
                                    </td>
                                    <td class="text-sm text-center">
                                        <b>{{ $model->message->out_number??''}}</b><br>
                                        {{ \Carbon\Carbon::parse($model->message->out_date)->format('d.m.Y')}}
                                    </td>
                                    <td>
                                        @if(!empty($model->helper->term_date))
                                            {{ \Carbon\Carbon::parse($model->helper->term_date)->format('d.m.Y')}}
                                            @else
                                            <i class="text-muted text-sm">(Muddat yo`q)</i>
                                        @endif
                                    </td>
                                    <td>
                                        @if($model->sub_status == null)
                                           <span class="label label-danger">@lang('blade.new')</span>
                                            @elseif($model->sub_status == 1)
                                            <span class="label label-default">@lang('blade.execution_in_process') ...</span>
                                            @elseif($model->sub_status == 2)
                                            <span class="label label-primary">@lang('blade.sent_to_approve')</span>
                                            @elseif($model->sub_status == 3)
                                            <span class="label label-success">@lang('blade.task_closed')</span>
                                            @endif
                                    </td>
                                    <td class="text-sm">
                                        @if($model->helper->controlType->type_message_code == 'control')
                                            <span class="text-maroon">{{ $model->helper->controlType->title??'' }}</span>
                                        @else
                                            {{ $model->helper->controlType->title??'' }}
                                        @endif
                                    </td>
                                    <td style="min-width: 200px;">
                                        <button class="btn btn-flat btn-xs bg-aqua-active center-block" type="button" data-toggle="collapse" data-target="#collapseExample{{ $model->id }}" aria-expanded="false" aria-controls="collapseExample">
                                            <i class="fa fa-search"></i> @lang('blade.home_more')
                                        </button>

                                        <div class="collapse" id="collapseExample{{ $model->id }}">
                                            <div class="card card-body">
                                                {!! $model->helper->text ?? '' !!}
                                            </div>
                                        </div>
                                    </td>
                                    <td style="min-width: 100px">
                                        {{ \Carbon\Carbon::parse($model->updated_at)->format('d-M-Y')  }}<br>
                                        <span class="text-maroon"> ({{$model->created_at->diffForHumans()}})</span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <span class="paginate">{{ $models->links() }}</span>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- jQuery 2.2.3 -->
        <script src="{{ asset ("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>
        <script src="{{ asset("/admin-lte/plugins/daterangepicker/moment.js") }}"></script>
        <script src="{{ asset("/admin-lte/plugins/daterangepicker/moment.min.js") }}"></script>
        <script src="{{ asset("/admin-lte/plugins/daterangepicker/daterangepicker.js") }}"></script>
        <script src="{{ asset ("/admin-lte/bootstrap/js/bootstrap-datepicker.js") }}"></script>
        <script src="{{ asset("/admin-lte/plugins/select2/select2.full.min.js") }}"></script>

        <script>
            $(function () {
                $("#example1").DataTable();

                //Date picker
                $('#datepicker').datepicker({
                    autoclose: true
                });
               
                $('.input-datepicker').datepicker({
                    todayBtn: 'linked',
                    todayHighlight: true,
                    format: 'yyyy-mm-dd',
                    autoclose: true
                });
                $('.input-daterange').datepicker({
                    todayBtn: 'linked',
                    forceParse: false,
                    todayHighlight: true,
                    format: 'yyyy-mm-dd',
                    autoclose: true
                });

                //Date range as a button
                $('#daterange-btn').daterangepicker(
                    {
                        ranges: {
                            'Bugun': [moment(), moment()],
                            'Kecha': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                            'Ohirgi 7 kun': [moment().subtract(6, 'days'), moment()],
                            'Ohirgi 30 kun': [moment().subtract(29, 'days'), moment()],
                            'Bu oyda': [moment().startOf('month'), moment().endOf('month')],
                            'O`tgan oyda': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                        },
                        startDate: moment().subtract(29, 'days'),
                        endDate: moment()
                    },
                    function (start, end) {
                        var s_start = start.format('YYYY-MM-DD');

                        var s_end = end.format('YYYY-MM-DD');

                        $('#s_start').val(s_start);
                        $('#s_end').val(s_end);

                        $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                    }
                );

            });
            // close Modal
            $('.closeModal').click(function () {

                $('#myModal').hide();

            });
        </script>
    </section>
    <!-- /.content -->
@endsection
