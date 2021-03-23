@extends('layouts.edo.dashboard')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang('blade.read_received')
            <small>@lang('blade.groups_table')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i>@lang('blade.home_page')</a></li>
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
                    <div class="box-body">
                        <form action="{{route('e-tasks-inbox')}}" method="POST" role="search">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-1">
                                    <div class="form-group has-success">
                                        <input type="text" class="form-control" name="dep_num" value="{{ $dep_num??'' }}"
                                               placeholder="Деп №">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group has-success">
                                        <input type="text" class="form-control" name="kanc_num" value="{{ $kanc_num??'' }}"
                                               placeholder="Kanc №">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group has-success">
                                        <input type="text" class="form-control" name="org_name" value="{{ $org_name??'' }}"
                                               placeholder="@lang('blade.sender_organization')">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group has-success">
                                        <input type="text" class="form-control" name="doc_name" value="{{ $doc_name??'' }}"
                                               placeholder="@lang('blade.doc_name')">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group has-success">
                                        <input type="text" class="form-control" name="in_num" value="{{ $in_num??'' }}"
                                               placeholder="Исх. №">
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <a href="{{ route('e-tasks-inbox') }}" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> @lang('blade.reset')</a>
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
                                <th>@lang('blade.doc_name')</th>
                                <th>@lang('blade.deadline')</th>
                                <th>@lang('blade.status')</th>
                                <th>@lang('blade.task')</th>
                                <th>@lang('blade.received_date')</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1 ?>
                           
                            @foreach ($models as $key => $model)
                                <tr>
                                    <td>{{ $models->firstItem()+$key }}</td>
                                    <td class="text-bold">{{ $model->depInboxJournal->in_number??'' }}{{ $model->depInboxJournal->in_number_a??'' }}</td>

                                    <td class="text-maroon" style="min-width: 100px">
                                        {{mb_substr($model->signatureUser->fname??'', 0, 1).'.'.$model->signatureUser->lname??''}}
                                    </td>
                                    <td class="text-sm text-center">
                                        <b>{{ $model->message->in_number??''}}</b><br>
                                        {{ \Carbon\Carbon::parse($model->message->in_date)->format('d.m.Y')}}
                                    </td>
                                    <td>
                                        <a href="{{ route('view-e-task', ['id' => $model->id, 'slug' => $model->message->message_hash??'']) }}">
                                            {!! \Illuminate\Support\Str::words($model->message->from_name, 5, '...'); !!}
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
                                        @if($model->status == 0)
                                            <span class="label label-danger">@lang('blade.new')</span>
                                        @elseif($model->status == 1)
                                            <span class="label label-primary">@lang('blade.on_process')</span>
                                        @elseif($model->status == 2)
                                            <span class="label label-success">@lang('blade.sent_to_approve')</span>
                                        @else
                                            <span class="label label-default">@lang('blade.not_detected')</span>
                                        @endif

                                        @if($model->message->urgent == 1)
                                            <sup><i class="fa fa-bell-o text-red fa-lg"></i></sup>
                                        @endif
                                    </td>
                                    <td>{!! \Illuminate\Support\Str::words(($model->subHelper->text??''), 5, '...'); !!}</td>
                                    <td style="min-width: 190px">
                                        {{ \Carbon\Carbon::parse($model->created_at)->format('d-M-Y')  }}
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
        <script src="{{ asset ("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>
        <script>
            $(function () {
                $("#example1").DataTable();
            });
        </script>
    </section>
    <!-- /.content -->
@endsection
