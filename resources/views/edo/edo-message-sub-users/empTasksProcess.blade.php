@extends('layouts.edo.dashboard')

@section('content')

    <!-- TRANSLATED -->
    
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang('blade.on_process')
            <small>@lang('blade.groups_table')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home_page')</a></li>
            <li><a href="#">@lang('blade.doc')</a></li>
            <li class="active">@lang('blade.groups_table')</li>
        </ol>
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

        @if(session('success'))
            <div class="box box-default">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-success">
                                <h4 class="modal-title"> {{ session('success') }}</h4>
                            </div>
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
                    <div class="box-body">
                        <form action="{{route('e-tasks-process')}}" method="POST" role="search">
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
                                        <a href="{{ route('e-tasks-process') }}" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> @lang('blade.reset')</a>
                                        <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-search"></i> @lang('blade.search')</button>
                                    </div>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </form>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <b>@lang('blade.overall'){{': '. $models->total()}} @lang('blade.group_edit_count').</b>
                        <table id="" class="table table-hover table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Деп №</th>
                                    <th>@lang('blade.from_whom')</th>
                                    <th>@lang('blade.in_num')</th>
                                    <th>@lang('blade.sender_organization')</th>
                                    <th>@lang('blade.out_num')</th>
                                    <th>@lang('blade.type_of_doc')</th>
                                    <th>@lang('blade.summary')</th>
                                    <th>@lang('blade.deadline')</th>
                                    <th>@lang('blade.status')</th>
                                    <th>@lang('blade.task')</th>
                                    <th>@lang('blade.received_date')</th>
                                </tr>
                            </thead>
                            <tbody>
                           <?php $i = 1 ?>
                            @foreach ($models as $key => $model)
                                    <tr class="{{ ($model->helper->term_date??'') ? 'bg-yellow':'' }}">
                                        <td>{{ $models->firstItem()+$key }}</td>
                                        <td class="text-bold">{{ $model->message->depInboxJournal->in_number??'' }}{{ $model->message->depInboxJournal->in_number_a??'' }}</td>
                                        <td class="text-maroon" style="min-width: 150px">
                                            {{$model->signatureUser->lname.' '.$model->signatureUser->fname}}
                                        </td>
                                        <td class="text-sm text-center">
                                            <b>{{ $model->message->in_number??''}}</b><br>
                                            {{ \Carbon\Carbon::parse($model->message->in_date)->format('d.m.Y')}}
                                        </td>
                                        <td>
                                            <a href="{{ route('view-e-task', ['id' => $model->id,
                                                'slug' => $model->message->message_hash]) }}">
                                                {!!\Illuminate\Support\Str::words($model->message->from_name, 5, '...');!!}
                                            </a>
                                        </td>
                                        <td class="text-sm text-center">
                                            <b>{{ $model->message->out_number??''}}</b><br>
                                            {{ \Carbon\Carbon::parse($model->message->out_date)->format('d.m.Y')}}
                                        </td>
                                        <td>{{ $model->messageJournal->messageType->title??'' }}</td>
                                        <td>
                                            @if(!empty($model->message->title))
                                                {!! \Illuminate\Support\Str::words(($model->message->title ?? ''), 8, '...'); !!}
                                            @else
                                                <i class="text-muted">(Mavzu yo`q)</i>
                                            @endif
                                        </td>
                                        <td>
                                            @if($model->helper->term_date??'')
                                                <span class="text-maroon">@lang('blade.office'): {{ \Carbon\Carbon::parse($model->helper->term_date??'')->format('d-M-Y')  }}</span>
                                            @endif
                                            @if(!empty($model->subHelper->term_date))
                                                <span class="text-maroon">@lang('blade.dep'): {{ \Carbon\Carbon::parse($model->subHelper->term_date??'')->format('d-M-Y')  }}</span>
                                            @endif
                                            @if(empty($model->subHelper->term_date) && empty($model->helper->term_date))
                                                <i class="text-muted">(Muddat yo`q)</i>
                                            @endif
                                        </td>
                                        <td>
                                            @if($model->status == null)
                                                <span class="label label-danger">@lang('blade.new')</span>
                                            @elseif($model->status == 1)
                                                <span class="label label-primary">@lang('blade.on_process')</span>
                                            @elseif($model->status == 2)
                                                <span class="label label-success">@lang('blade.sent_to_approve')</span>
                                            @elseif($model->status == 3)
                                                <span class="label label-success">@lang('blade.task_closed')</span>
                                            @endif
                                        </td>
                                        <td>
                                            {!! \Illuminate\Support\Str::words(($model->subHelper->text??''), 5, '...'); !!}
                                        </td>
                                        <td style="min-width: 130px" class="text-center">
                                            {{ \Carbon\Carbon::parse($model->created_at)->format('d-M-Y')  }}<br>
                                            <span class="text-maroon"> ({{$model->created_at->diffForHumans()}})</span>
                                        </td>
                                    </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $models->links() }}
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- jQuery 2.2.3 -->
        <script src="/admin-lte/plugins/jQuery/jquery-2.2.3.min.js"></script>
        <!-- Bootstrap 3.3.6 -->
        <script src="/admin-lte/bootstrap/js/bootstrap.min.js"></script>
        <!-- DataTables -->
        <script src="/admin-lte/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="/admin-lte/plugins/datatables/dataTables.bootstrap.min.js"></script>
        
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
        </script>
    </section>
    <!-- /.content -->
@endsection
