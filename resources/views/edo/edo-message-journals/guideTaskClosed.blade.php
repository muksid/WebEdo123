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
            <li><a href="#"><i class="fa fa-dashboard"></i>@lang('blade.home_page') </a></li>
            <li><a href="#">@lang('blade.doc')</a></li>
            <li class="active">@lang('blade.groups_table')</li>
        </ol>

        <!-- Message Succes -->
        @if ($message = Session::get('success'))
            <div class="modal fade in" id="myModal" role="dialog" style="display: block">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header bg-aqua-active">
                            <h4 class="modal-title">
                                @lang('blade.task')<i class="fa fa-check-circle"></i>
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
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box">
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form action="{{route('g-tasks-closed')}}" method="GET" role="search">
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
                                        <a href="{{ route('g-tasks-closed') }}" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> @lang('blade.reset')</a>
                                        <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-search"></i> @lang('blade.search')</button>
                                    </div>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </form>
                        <table id="" class="table table-hover table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('blade.receivers')</th>
                                <th>@lang('blade.sender_organization')</th>
                                <th>@lang('blade.reg_date')</th>
                                <th>@lang('blade.send_date')</th>
                                <th>@lang('blade.deadline')</th>
                                <th>@lang('blade.status')</th>
                                <th>@lang('blade.purpose')</th>
                                <th>@lang('blade.task')</th>
                            </tr>
                            </thead>
                            <tbody>
                           <?php $i = 1 ?>
                            @foreach ($models as $key => $model)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td class="text-maroon" style="min-width: 180px">
                                        @foreach($model->perfUsers as $key => $user)

                                                @php($userName = \App\User::find($user->to_user_id ?? 'null'))

                                            @if($key < 4 )
                                                    {{ mb_substr($userName->fname ?? '', 0,1).'.'.mb_substr($userName->sname ?? '', 0,1).'.'.mb_scrub($userName->lname ?? 'null2') }}
                                                    @if($user->is_read == 1)
                                                        @if($user->sub_status == 1)
                                                            <i class="fa fa-hourglass-2 text-blue"></i>
                                                        @elseif($user->sub_status == 2)
                                                            <i id="div1"
                                                               class="fa fa-envelope-o text-aqua text-bold"></i>
                                                        @elseif($user->sub_status == 3)
                                                            <i id="div1"
                                                               class="fa fa-file-archive-o text-bold"></i>
                                                        @else
                                                            <i class="fa fa-check-square text-primary"></i>
                                                        @endif
                                                    @else
                                                        <i class="fa fa-check text-red"></i>
                                                    @endif
                                                    <br/>
                                                @endif

                                        @endforeach
                                        @if(count($model->perfUsers) > 4)
                                            <span class="text-primary text-bold">+{{count($model->perfUsers)-4}}</span>
                                        @endif

                                    </td>
                                    <td>
                                        <a href="{{ url('edo/view-task-process', ['id' => $model->id,'slug' => $model->message->message_hash]) }}">
                                            {!! \Illuminate\Support\Str::words($model->message->from_name, 5, '...'); !!}
                                        </a>
                                    </td>
                                    <td class="text-sm text-center">
                                        <b>{{ $model->message->in_number??''}}</b><br>
                                        {{ \Carbon\Carbon::parse($model->message->in_date)->format('d.m.Y')}}
                                    </td>
                                    <td>{{ $model->updated_at->format('d M. Y H:i') }}</td>
                                    <td>
                                        @if(!empty($model->taskText->term_date))
                                            {{ \Carbon\Carbon::parse($model->taskText->term_date??'')->format('j F,Y') }}
                                        @else
                                            <i class="text-muted text-sm">(Muddat yo`q)</i>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($model->status)
                                            @case(2)
                                            <span class="label label-primary">@lang('blade.in_execution')</span>
                                            @break
                                            @case(3)
                                            <span class="label label-success">@lang('blade.closed')</span>
                                            @break
                                            @default
                                            @lang('blade.not_detected')
                                        @endswitch
                                    </td>
                                    <td>
                                        @if(!empty($model->taskText->controlType->title))
                                            @if($model->taskText->controlType->type_message_code == 'control')
                                                <span class="text-maroon text-sm"><i class="fa fa-check-circle"></i> {{ $model->taskText->controlType->title }}</span>
                                            @else
                                                <i class="text-muted text-sm">{{ $model->taskText->controlType->title??'' }}</i>
                                            @endif
                                        @endif

                                    </td>
                                    <td style="max-width: 280px"><?php echo $model->taskText->text??'' ?></td>
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
