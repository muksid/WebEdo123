@extends('layouts.edo.dashboard')

@section('content')

    <!-- TRANSLATED -->

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang('blade.forwarded_docs')
            <small>@lang('blade.groups_table')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home_page')</a></li>
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

                <div class="box box-primary">

                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-hover table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('blade.to_whom')</th>
                                <th>@lang('blade.sender_organization')</th>
                                <th>@lang('blade.doc_num')</th>
                                <th>@lang('blade.subject')</th>
                                <th>@lang('blade.comment')</th>
                                <th>@lang('blade.forwarded_date')</th>
                            </tr>
                            </thead>
                            <tbody>
                           <?php $i = 1 ?>
                            @foreach ($models as $key => $model)
                                <tr>

                                    <td>{{ $i++ }}</td>
                                    <td>
                                        <span class="text-maroon">
                                        {{mb_substr($model->user->fname ??'null' ,0,1).'.'
                                        .mb_substr($model->user->sname ??'null' ,0,1).'.'.$model->user->lname??'null'}}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('view-task-process',
                                                    [$model->edo_message_id,$model->message->message_hash??'']) }}">
                                            {!!\Illuminate\Support\Str::words($model->message->from_name??'', 5, '...');!!}
                                        </a>
                                    </td>
                                    <td style="min-width: 170px">
                                        {{--{{ \Carbon\Carbon::parse($model->message->in_date)->format('d-m-Y') }}y.--}} <b>
                                            {{ $model->message->in_number??'' }}</b>
                                    </td>
                                    <td>{!! \Illuminate\Support\Str::words($model->message->title??'', 5, '...'); !!}</td>
                                    <td>{!! \Illuminate\Support\Str::words($model->redirect_desc??'', 5, '...'); !!}</td>
                                    <td style="min-width: 190px">
                                        {{ \Carbon\Carbon::parse($model->created_at)->format('d-M-Y')  }}
                                        <span class="text-maroon"> ({{$model->created_at->diffForHumans()}})</span>
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
        <!-- AdminLTE for demo purposes -->
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
