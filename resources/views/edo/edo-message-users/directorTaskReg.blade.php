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
                        <table id="example1"  class="table table-hover table-bordered table-striped">
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
