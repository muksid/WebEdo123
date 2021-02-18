@extends('layouts.edo.dashboard')

@section('content')

    <!-- TRANSLATED -->

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang('blade.inbox_doc')
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

                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-hover table-bordered table-striped">
                            <thead>
                            <tr>
                                <th class="text-center" style="max-width: 100px;"> #</th>
                                <th class="text-center" style="max-width: 100px;"> @lang('blade.reg_num')</th>
                                <th class="text-center" style="max-width: 100px;"> @lang('blade.from_whom')</th>
                                <th class="text-center" style="max-width: 100px;"> @lang('blade.sender_organization')</th>
                                <th class="text-center" style="max-width: 100px;"> Канс №</th>
                                <th class="text-center" style="max-width: 100px;"> @lang('blade.type_of_doc')</th>
                                <th class="text-center" style="max-width: 100px;"> @lang('blade.doc_name')</th>
                                <th class="text-center" style="max-width: 100px;"> Исх. №</th>
                                <th class="text-center" style="max-width: 100px;"> @lang('blade.deadline')</th>
                                <th class="text-center" style="max-width: 100px;"> @lang('blade.status')</th>
                                <th class="text-center" style="max-width: 100px;"> @lang('blade.purpose')</th>
                                <th class="text-center" style="max-width: 100px;"> @lang('blade.task')</th>
                                <th class="text-center" style="max-width: 100px;"> @lang('blade.received_date')</th>
                            </tr>
                            </thead>
                            <tbody>
                           <?php $i = 1; $key = 1; ?>
                            @foreach ($models as $key => $model)
                                <tr>

                                    <td>{{ $i++ }}</td>
                                    <td class="text-center text-bold">{{ $model->journal->in_number??'' }}{{ $model->journal->in_number_a??'' }}</td>
                                    <td class="text-maroon" style="min-width: 150px">
                                        {{$model->signatureUser->lname.' '.$model->signatureUser->fname}}
                                    </td>
                                    <td>
                                        <a href="{{ route('view-d-task',
                                            ['id' => $model->id,
                                            'slug' => $model->message->message_hash]) }}">
                                            {!! \Illuminate\Support\Str::words($model->message->from_name, 5, '...'); !!}
                                        </a>
                                    </td>
                                    <td class="text-sm text-center">
                                        <b>{{ $model->message->in_number??''}}</b><br>
                                        {{ \Carbon\Carbon::parse($model->message->in_date)->format('d.m.Y')}}
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
                                        {{ \Carbon\Carbon::parse($model->message->out_date)->format('d.m.Y')}}
                                    </td>
                                    <td>
                                        @if(!empty($model->helper->term_date))
                                            {{ \Carbon\Carbon::parse($model->helper->term_date)->format('j F,Y')  }}
                                            {{--{{ $model->helper->term_date ?? 'null'}}--}}
                                        @else
                                            <i class="text-muted text-sm">(Muddat yo`q)</i>
                                        @endif
                                    </td>
                                    <td>
                                        @if($model->sub_status == null)
                                           <span class="label label-danger">@lang('blade.new')</span>
                                            @elseif($model->sub_status == 1)
                                            <span class="label label-primary">@lang('blade.on_process')</span>
                                            @else
                                            <span class="label label-success">@lang('blade.in_execution')</span>
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
                                    <td style="min-width: 130px">
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
        <script>
            $(function () {
                $("#example1").DataTable();
            });

            // close Modal
            $('.closeModal').click(function () {

                $('#myModal').hide();

            });

        </script>
    </section>
    <!-- /.content -->
@endsection
