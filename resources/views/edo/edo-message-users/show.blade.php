<?php ?>
@extends('layouts.edo.dashboard')

@section('content')

    <!-- TRANSLATED -->

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- Message Succes -->
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    {{ $message }}
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
        <!-- left column -->
            <div class="col-md-4">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">@lang('blade.doc')</h3>
                    </div>
                    <div class="box-body no-padding">
                        <table class="table table-condensed">
                            <tr>
                                <th style="width: 50%"><i class="fa fa-list-ol"></i> @lang('blade.reg_no')</th>
                                <th><i class="fa fa-clock-o"></i>  @lang('blade.date')</th>
                            </tr>
                            <tr>
                                <td style="width: 50%">{{ $model->message->in_number }}</td>
                                <td>{{ $model->message->in_date }}</td>
                            </tr>
                        </table>
                    </div><br>
                    <div class="box-body no-padding">
                        <table class="table table-condensed">
                            <tr>
                                <th><i class="fa fa-list-ol"></i> @lang('blade.type_of_doc')</th>
                            </tr>
                            <tr>
                                <td>{{ $model->message->out_number }}</td>
                            </tr>
                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-body">
                        <h5><strong>Jo`natuvchi (Tashkilot)</strong></h5>
                        <p>{{ $model->message->from_name }}</p>
                        <hr>
                        <strong><i class="fa fa-file margin-r-5"></i> @lang('blade.doc_app') </strong><br><br>

                        @foreach ($model->files as $file)
                            <i class="fa fa-file-pdf-o text-yellow"></i> {{ $file->file_name }} <a
                                    href="#" class="pull-right">@lang('blade.donwload')</a><br><br>
                        @endforeach
                        <hr>
                        <p class="text-bold text-center">{{ $model->message->title }}</p>
                        <p>{{ $model->message->text }}</p>
                    </div>
                </div>
                <!-- /.box -->

            </div>
            <!--/.col (left) -->
            <!-- right column -->
            <div class="col-md-4">
                <!-- Horizontal Form -->
                <div class="box box-info box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">@lang('blade.reply_doc')</h3>
                    </div>

                    <div class="box-body table-responsive no-padding">
                        <table class="table">
                            <tr>
                                <th></th>
                                <th></th>
                            </tr>
                            <tr>
                                <td>@lang('blade.purpose')</td>
                                <td>@lang('blade.to_resolution')</td>
                            </tr>
                            <tr>
                                <td>@lang('blade.sender')</td>
                                <td>{{ $model->fromUser->lname. ' ' .$model->fromUser->fname }}</td>
                            </tr>
                            <tr>
                                <td>@lang('blade.sent_date')</td>
                                <td>{{ $model->created_at->format('d M. Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td>@lang('blade.status')</td>
                                <td><span class="label label-warning">@lang('blade.on_process')</span></td>
                            </tr>
                            <tr>
                                <td>1. {{ $model->fromUser->lname. ' ' .$model->fromUser->fname }}</td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                    </div>
                    <!-- /.box-footer -->

                    <form role="form" method="POST" action="{{ route('edit-users-helper') }}"
                          enctype="multipart/form-data">
                        {{csrf_field()}}

                        <div class="col-md-12 form-panel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="box-body">
                                                <label>@lang('blade.purpose')</label><sup class="text-red"> *</sup>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="box-body">
                                                <label>@lang('blade.execution_time')</label><sup class="text-red"> *</sup>
                                                <div class="input-group date">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                    <div class="input-group input-daterange">
                                                    </div>
                                                </div>
                                                <!-- /.input group -->
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.form-group -->
                                </div>

                            </div>
                        </div>

                        <div class="col-md-pull-12 form-panel">

                            <div class="box-body">
                                <ul>
                                </ul>
                            </div>

                            <div class="box-body">
                                <div class="form-group">
                                    <textarea name="text" class="form-control" id="text_tasks" rows="5">{{ $model->messageHelper->text ?? 'Null' }}
                                    </textarea>
                                </div>
                            </div>

                            <div class="box-footer">
                                <button type="submit" class="btn btn-bitbucket">
                                    <i class="fa fa-forward"></i> @lang('blade.forward')
                                </button>
                                <button type="submit" class="btn btn-info pull-right">
                                    <i class="fa fa-send-o"></i> @lang('blade.send')
                                </button>
                            </div>
                            <!-- /.box-footer -->
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>
            <!--/.col (right) -->
            <!-- right column -->

            <div class="col-md-4">
                    <!-- Widget: user widget style 1 -->
                    <div class="box box-widget widget-user-2">
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <div class="widget-user-header bg-blue-gradient">
                            <img class="profile-user-img img-responsive" style="width: 40%; border: hidden" src="/admin-lte/dist/img/footer__logo.svg" alt="Turonbank"><br>
                            <h2 class="profile-username text-center">{{ $model->signatureUser->job_title ?? 'Null' }}</h2>
                        </div>
                        <div class="box-footer no-padding">
                            <ul class="nav nav-stacked">
                                @foreach($perfUsers as $key => $user)
                                    @php($key = $key+1)
                                    <li class="list-group-item">{{ $key++.'. '.$user->full_name }} <span class="pull-right badge bg-aqua">{{ $user->title_ru }}</span></li>

                                @endforeach
                            </ul>
                            <div class="box-body">
                                <h5 class="text-center">{{ $model->messageHelper->text ?? 'Null' }}</h5><hr>
                                <h5 class="text-bold">№ {{ $model->message->out_number ?? 'Null' }} <span
                                            class="pull-right">{{ $model->signatureUser->lname ?? 'Null' }}.</span>
                                </h5>
                                <h5><span class="pull-right">{{ $model->updated_at->format('d M. Y H:i') }} </span></h5>
                            </div>

                            <div class="box-footer">
                                <div class="slider__pagination-item ng-scope is-active" title="{{ $model->signatureUser->lname ?? 'Null' }}">
                                    <div class="slider__thumb">
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-footer -->
                        </div>
                    </div>
                    <!-- /.widget-user -->
            </div>
            <!--/.col (right) -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->

    <!-- Main content -->
    <section class="content">
        <!-- AdminLTE App -->

        <script src="{{ asset("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>
        <!-- Select2 -->
        <script src="{{ asset("/admin-lte/plugins/select2/select2.full.min.js") }}"></script>

        <link href="{{ asset ("/admin-lte/bootstrap/css/bootstrap-datepicker.css") }}" rel="stylesheet"/>
        <script src="{{ asset ("/admin-lte/bootstrap/js/datepicker/bootstrap.min.js") }}"></script>
        <script src="{{ asset ("/admin-lte/bootstrap/js/bootstrap-datepicker.js") }}"></script>
        <!-- Bootstrap 3.3.6 -->
        <script src="{{ asset ("admin-lte/bootstrap/js/bootstrap.min.js") }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ asset ("admin-lte/dist/js/app.min.js") }}"></script>

        <script type="text/javascript">
            $(document).ready(function () {


                // Send type for create
                $('.send-type-div').hide();

                $('.type_send').on('change', function () {

                    if (this.value == 4) {

                        $(".send-type-div").show();

                    } else {

                        $(".send-type-div").hide();
                    }
                });

                // For Users
                $('.btn-bitbucket').on('click', function () {

                    $('#edoUsers').slideToggle(800);
                    $('.form-panel').slideToggle(800);

                });

                // add user (in right)
                $('.performer_type').hide();
                $('.select-user .removeItem').hide();
                $('ul').on('click', '.select-user', function () {

                    $(this).removeClass('select-user').addClass('unselect-user').appendTo('ul#selectedUsers');

                    $('#selectedUsers .performer_type').show();

                    $('.unselect-user .removeItem').show();

                });

                // remove selected user (in left)
                $('.removeItem').click(function () {

                    var listVal = $(this).val();

                    var listItem = '.listItem'+listVal;

                    $(listItem).removeClass('unselect-user').removeClass('selected-user').addClass('select-user').appendTo('ul#unSelectedUsers');

                    $(listItem+'.select-user span').addClass('performer_type');

                    $('.select-user .performer_type').hide();

                    $('.select-user .removeItem').hide();
                });

                // Tasks
                $('.tasks a').click(function () {

                    var tasks = $(this).text();

                    $('#text_tasks').empty().append(tasks);

                });

            });


            //Date picker
            $('#datepicker').datepicker({
                autoclose: true
            });
            $('.input-datepicker').datepicker({
                todayBtn: 'linked',
                format: 'yyyy-mm-dd',
                autoclose: true
            });
            $('.input-daterange').datepicker({
                todayBtn: 'linked',
                format: 'yyyy-mm-dd',
                autoclose: true
            });
        </script>
    </section>
    <!-- /.content -->
@endsection