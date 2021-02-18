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

                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-hover table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('blade.from_whom')</th>
                                    <th>@lang('blade.reg_num')</th>
                                    <th>@lang('blade.sender_organization')</th>
                                    <th>Xat raqami</th>
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
                                @if(($model->status == 0 && ($model->mesUsers->sub_status??0) == 2) || $model->status > 0)
                                    <tr>
                                        <td>{{ $i++ }}</td>
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
                                            @if(!empty($model->subHelper->term_date))
                                                <span class="text-maroon">{{ \Carbon\Carbon::parse($model->subHelper->term_date??'')->format('d-M-Y')  }}</span>
                                            @else
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
                                @endif
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
        <script src="/admin-lte/plugins/jQuery/jquery-2.2.3.min.js"></script>
        <!-- Bootstrap 3.3.6 -->
        <script src="/admin-lte/bootstrap/js/bootstrap.min.js"></script>
        <!-- DataTables -->
        <script src="/admin-lte/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="/admin-lte/plugins/datatables/dataTables.bootstrap.min.js"></script>
        <!-- AdminLTE App -->
        <script src="/admin-lte/dist/js/app.min.js"></script>
        <!-- AdminLTE for demo purposes -->
        <script>
            $(function () {
                $("#example1").DataTable();
            });
        </script>
    </section>
    <!-- /.content -->
@endsection
