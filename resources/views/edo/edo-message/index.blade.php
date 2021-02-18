@extends('layouts.edo.dashboard')

@section('content')

        <!-- TRANSLATED -->

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @lang('blade.user')
                <small>@lang('blade.panel')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('edo-home') }}"><i class="fa fa-dashboard"></i> @lang('blade.home_page')</a></li>
                <li><a href="#">@lang('blade.user')</a></li>
                <li class="active">@lang('blade.panel')</li>
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
            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <section class="col-lg-5 connectedSortable">
                    <div class="box box-primary">
                        <div class="box-header">
                            <i class="ion ion-clipboard"></i>

                            <h3 class="box-title">@lang('blade.tasks')</h3>
                            <div class="box-tools pull-right">
                                <ul class="pagination pagination-sm inline">
                                    <li><a href="#">&laquo;</a></li>
                                    <li><a href="#">1</a></li>
                                    <li><a href="#">2</a></li>
                                    <li><a href="#">3</a></li>
                                    <li><a href="#">&raquo;</a></li>
                                </ul>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <ul class="todo-list">

                                <li>
                                    <span class="handle">
                                        <i class="fa fa-ellipsis-v"></i>
                                        <i class="fa fa-ellipsis-v"></i>
                                    </span>

                                    <input type="checkbox" value="">

                                    <span class="text">@lang('blade.overdue')</span>

                                    <small class="label label-danger"><i class="fa fa-clock-o"></i> 2 @lang('blade.minute')</small>

                                    <div class="tools">
                                        <i class="fa fa-edit"></i>
                                        <i class="fa fa-trash-o"></i>
                                    </div>
                                </li>

                                <li>
                                    <span class="handle">
                                        <i class="fa fa-ellipsis-v"></i>
                                        <i class="fa fa-ellipsis-v"></i>
                                    </span>

                                    <input type="checkbox" value="">

                                    <span class="text">@lang('blade.term')</span>

                                    <small class="label label-info"><i class="fa fa-clock-o"></i> 4 @lang('blade.hour')</small>

                                    <div class="tools">
                                        <i class="fa fa-edit"></i>
                                        <i class="fa fa-trash-o"></i>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer clearfix no-border">
                            <button type="button" class="btn btn-default pull-right">
                                <i class="fa fa-plus"></i> @lang('blade.add_task')
                            </button>
                        </div>
                    </div>
                </section>
                <!-- /.Left col -->

                <section class="col-lg-7 connectedSortable">
                    <div class="box box-info">
                        <div class="box-header">
                            <i class="fa fa-envelope"></i>

                            <h3 class="box-title">@lang('blade.request_to_admin')</h3>
                            <!-- tools box -->
                            <div class="pull-right box-tools">
                                <button type="button" class="btn btn-info btn-sm" data-widget="remove"
                                        data-toggle="tooltip" title="Remove">
                                    <i class="fa fa-times"></i></button>
                            </div>
                            <!-- /. tools -->
                        </div>
                        <div class="box-body">
                            <form action="#" method="post">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="subject" placeholder="@lang('blade.subject')">
                                </div>
                                <div>
                                    <textarea class="textarea" placeholder="@lang('blade.text')"
                                              style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="box-footer clearfix">
                            <button type="button" class="pull-right btn btn-default" id="sendEmail">@lang('blade.send')
                                <i class="fa fa-arrow-circle-right"></i></button>
                        </div>
                    </div>
                </section>
                <!-- right col -->
            </div>
            <!-- /.row -->

        </section>
        <!-- /.content -->

        <!-- jQuery 2.2.3 -->
        <script src="{{ asset ("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

        <!-- AdminLTE App -->
        <script src="{{ asset("/admin-lte/dist/js/app.min.js") }}"></script>

        <!-- jQuery UI 1.11.4 -->
        <script src="{{ asset ("/admin-lte/dist/js/jquery-ui.min.js") }}"></script>

        <!-- Dashboard style clicked -->
        <script src="{{ asset ("/admin-lte/dist/js/pages/dashboard.js") }}"></script>
@endsection
