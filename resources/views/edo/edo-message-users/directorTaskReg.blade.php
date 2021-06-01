@extends('layouts.edo.dashboard')

@section('content')

    <!-- TRANSLATED -->

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang('blade.reg_journal')
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

                <div class="box box-danger">
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form action="{{route('d-tasks-reg')}}" method="POST" role="search">
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
                                        <a href="{{ route('d-tasks-reg') }}" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> @lang('blade.reset')</a>
                                        <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-search"></i> @lang('blade.search')</button>
                                    </div>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </form>
                        <table  class="table table-hover table-bordered table-striped">
                            <thead>
                            <tr>
                                <th class="text-center" style="max-width: 110px;">#</th>
                                <th class="text-center" style="max-width: 110px;">@lang('blade.from_whom')</th>
                                <th class="text-center" style="max-width: 110px;">@lang('blade.sender_organization')</th>
                                <th class="text-center" style="max-width: 110px;">@lang('blade.reg_date_num_kanc')</th>
                                <th class="text-center" style="max-width: 110px;">@lang('blade.type_of_doc')</th>
                                <th class="text-center" style="max-width: 110px;">@lang('blade.doc_name')</th>
                                <th class="text-center" style="max-width: 100px;">@lang('blade.incoming_num_doc')</th>
                                <th class="text-center" style="max-width: 110px;">@lang('blade.deadline')</th>
                                <th class="text-center" style="max-width: 110px;">@lang('blade.task')</th>
                                <th class="text-center" style="max-width: 110px;">@lang('blade.received_date')</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1; ?>
                            @foreach ($models as $key => $model)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td class="text-maroon" style="min-width: 150px">
                                        {{$model->signatureUser->lname.' '.$model->signatureUser->fname}}
                                    </td>
                                    <td>
                                        <a href="{{ route('view-reg-task',
                                            ['id' => $model->id,
                                            'slug' => $model->message->message_hash??'']) }}">
                                            {!! \Illuminate\Support\Str::words($model->message->from_name??'', 5, '...'); !!}
                                        </a>
                                    </td>
                                    <td class="text-sm text-center">
                                        <b>{{ $model->message->in_number??''}}</b><br>
                                        {{ \Carbon\Carbon::parse($model->message->in_date??'')->format('d.m.Y')}}
                                    </td>
                                    <td>{{ $model->messageJournal->messageType->title??'' }}</td>
                                    <td>
                                        @if(!empty($model->message->title))
                                            {!! \Illuminate\Support\Str::words(($model->message->title ?? ''), 8, '...'); !!}
                                        @else
                                            <i class="text-muted text-sm">(Mavzu yo`q)</i>
                                        @endif
                                    </td>
                                    <td class="text-sm text-center">
                                        <b>{{ $model->message->out_number??''}}</b><br>
                                        {{ \Carbon\Carbon::parse($model->message->out_date??'')->format('d.m.Y')}}
                                    </td>
                                    <td>
                                        @if(!empty($model->helper->term_date))
                                            {{ \Carbon\Carbon::parse($model->helper->term_date)->format('j F,Y')  }}
                                            {{--{{ $model->helper->term_date ?? 'null'}}--}}
                                        @else
                                            <i class="text-muted text-sm">(Muddat yo`q)</i>
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
                                    <td style="min-width: 190px">
                                        {{ \Carbon\Carbon::parse($model->updated_at)->format('d-M-Y H:i')  }}<br>
                                        <span class="text-maroon"> ({{$model->updated_at->diffForHumans()}})</span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
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
