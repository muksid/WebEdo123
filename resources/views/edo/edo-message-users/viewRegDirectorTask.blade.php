@extends('layouts.edo.dashboard')

@section('content')

    <section class="content">
        <div class="row">

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
                                <th style="width: 50%"><i class="fa fa-sort-numeric-asc"></i> @lang('blade.reg_num')</th>
                                <th><i class="fa fa-clock-o"></i>  @lang('blade.date')</th>
                            </tr>
                            <tr>
                                <td style="width: 50%">{{ $model->out_number }}</td>
                                <td>{{ \Carbon\Carbon::parse($model->out_date)->format('j F, Y') }}</td>
                            </tr>
                        </table>
                    </div><br>
                    <div class="box-body no-padding">
                        <table class="table table-condensed">
                            <tr>
                                <th><i class="fa fa-list-ol"></i> @lang('blade.type_of_doc')</th>
                            </tr>
                            <tr>
                                <td>{{ $model->journalUser->messageType->title }}</td>
                            </tr>
                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-body">
                        <h5><strong>@lang('blade.sender_organization')</strong></h5>
                        <p>{{ $model->from_name }}</p>
                        <hr>
                        <strong><i class="fa fa-file margin-r-5"></i> @lang('blade.doc_app') </strong><br><br>

                        @foreach ($model->files as $file)
                            <a href="#"
                               class="text-info text-bold mailbox-attachment-name"
                               target="_blank"
                               onclick="window.open('<?php echo('/edo-fileView/' . $file->id); ?>',
                                       'modal',
                                       'width=800,height=900,top=30,left=500');
                                       return false;">
                                <i class="fa fa-search-plus"></i> {{ \Illuminate\Support\Str::limit($file->file_name, 35,'...') }}
                            </a>
                            <ul class="list-inline pull-right">
                                <li>
                                    <a href="{{ url('edo-fileDownload',['id'=>$file->id]) }}"
                                       class="link-black text-sm"><i
                                                class="fa fa-cloud-download text-primary"></i> @lang('blade.download')
                                    </a>
                                </li>
                            </ul>
                            <i class="text-red">({{ $file->size($file->file_size)??'' }}
                                )</i><br><br>
                        @endforeach
                        <hr>
                        @if(!empty($model->title))
                            <p class="text-bold text-center">{{ $model->title }}</p>
                        @else
                            <p class="text-muted text-center">(Mavzu yo`q)</p>
                        @endif
                        <p><?php echo $model->text ?? '' ?></p>
                    </div>
                </div>

            </div>

            <div class="col-md-4">

                <div class="box box-info box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">@lang('blade.task')</h3>
                    </div>

                    <div class="box-body table-responsive no-padding">
                        <table class="table">
                            <tr>
                                <th></th>
                                <th></th>
                            </tr>
                            <tr>
                                <td class="text-muted">@lang('blade.purpose')</td>
                                <td>@lang('blade.to_resolution')</td>
                            </tr>
                            <tr>
                                <td class="text-muted">@lang('blade.sender')</td>
                                <td>{{ $model->journalUser->officeUser->full_name ?? 'Null' }}
                                    <span class="text-maroon">
                                        ({{ $model->journalUser->userJob->userRole->title_ru }})
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">@lang('blade.date')</td>
                                <td>{{ $model->created_at->format('d-M-Y H:i') }}
                                    <span class="text-maroon">({{$model->created_at->diffForHumans()}})</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">@lang('blade.status')</td>
                                <td><span class="label label-warning">@lang('blade.on_process')</span></td>
                            </tr>
                            <tr>
                                <td>1. {{ $model->journalUser->guideUser->full_name ?? 'Null'  }}</td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" class="btn btn-default" data-toggle="modal">
                            <i class="fa fa-ban"></i> @lang('blade.reject_task')</button>
                        <button type="submit" class="btn btn-info btn-bitbucket pull-right">
                            <i class="glyphicon glyphicon-pushpin"></i> @lang('blade.reg')
                        </button>
                    </div>

                    <form role="form" method="POST" action="{{ route('dep-reg-task') }}"
                          enctype="multipart/form-data">
                        {{csrf_field()}}

                        <div class="box-body">
                            <div class="col-md-12 form-panel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <h4 class="text-center">@lang('blade.reg_journal')</h4>
                                            <div class="form-group">
                                                <div class="box-body">
                                                    <label>@lang('blade.sender_organization')</label><sup
                                                            class="text-red"> *</sup>
                                                    <input name="from_name" value="{{ $model->from_name }}"
                                                           class="form-control" required/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="box-body">
                                                    <label>@lang('blade.doc_name')</label><sup class="text-red"> *</sup>
                                                    <input name="title" value="{{ $model->title }}"
                                                           class="form-control" required/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="form-group">
                                                <div class="box-body">
                                                    <label>@lang('blade.reg_num')</label><sup class="text-red"> *</sup>
                                                    <input type="number" name="in_number" value="{{ $inboxNum }}"
                                                           class="form-control" required/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="form-group">
                                                <div class="box-body">
                                                    <label>Reg# /a</label>
                                                    <input type="text" name="in_number_a"
                                                           class="form-control" placeholder="/a" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box-body">
                                        <div class="form-group">
                                            <input name="edo_message_id" value="{{ $model->id }}" hidden/>
                                            <input name="depart_id" value="{{ $department->department_id }}" hidden/>
                                            <input name="director_id" value="{{ $department->user_id }}" hidden/>
                                            <input name="user_id" value="{{ Auth::id() }}" hidden/>
                                        </div>
                                    </div>
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-info pull-right">
                                            <i class="fa fa-save"></i> @lang('blade.save')
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

            <div class="col-md-4">
                    <div class="box box-widget widget-user-2">

                        <div class="widget-user-header bg-blue-gradient">
                            <img class="profile-user-img img-responsive" style="width: 40%; border: hidden" src="{{ asset("/admin-lte/dist/img/footer__logo.svg") }}" alt="Turonbank"><br>
                            <h2 class="profile-username text-center">@lang('blade.tb_wide')</h2>
                        </div>

                        <div class="box-footer no-padding">
                            <ul class="nav nav-stacked">
                                <?php
                                $i = 1;
                                $sort = array(1,2,3,4);
                                ?>
                                @foreach($perfUsers->sortBy('user_sort') as $key => $user)
                                    @if($user->sort != 1)
                                        @switch($user->user_sort)
                                            @case(1)
                                            @case(2)
                                            @case(3)
                                            @case(4)
                                            <li class="list-group-item">{{ $i++.'. '.$user->full_name }}
                                                <span class="pull-right badge bg-aqua">{{ $user->title_ru }}</span>
                                            </li>
                                            @break
                                        @endswitch
                                    @endif
                                @endforeach

                                @foreach($perfUsers as $key => $user)
                                    @if(!in_array($user->user_sort, $sort) && $user->sort != 1 && $user->sort != 2)
                                        <li class="list-group-item">{{ $i++.'. '.$user->full_name }}
                                            <span class="pull-right badge bg-aqua">{{ $user->title_ru }}</span>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>

                            <ul class="nav nav-stacked">
                                @foreach($perfUsers as $element)
                                    @if($element->sort == 1)
                                        <li class="list-group-item">{{ $i++ }}. @lang('blade.all_dep_name')
                                            <span class="pull-right badge bg-aqua">@lang('blade.executors')</span>
                                        </li>
                                        @break
                                    @endif
                                @endforeach

                                @foreach($perfUsers as $element)
                                    @if($element->sort == 2)
                                        <li class="list-group-item">{{ $i++ }}. @lang('blade.all_filial_name')
                                            <span class="pull-right badge bg-aqua">@lang('blade.executors')</span>
                                        </li>
                                        @break
                                    @endif
                                @endforeach
                            </ul>

                            <div class="box-body">
                                <h5 class="text-center"><?php echo $model->messageHelper->text ?? 'Null'; ?>
                                    @if(!empty($model->messageHelper->term_date))
                                        <br/><br/>
                                        <span class="pull-left text-maroon">
                                            <b><i class="fa fa-check-circle-o"></i> {{ $model->messageHelper->controlType->title?? 'null' }}</b>
                                        </span>
                                        <span class="pull-right text-red">@lang('blade.deadline'): {{ $model->messageHelper->term_date ?? 'null' }}</span>
                                    @endif
                                </h5><hr>
                                <h5 class="text-bold">№ {{ $model->out_number ?? 'Null' }}
                                    <span class="pull-right">{{ $messageUser->signatureUser->lname??'' }} {{ $messageUser->signatureUser->fname??'' }}.</span>
                                </h5>
                                <h5>{{ $messageUser->created_at->format('d-m-Y H:i') }}<span class="pull-right">{{ $messageUser->signatureUser->job_title??'' }} </span></h5>
                            </div>

                            <div class="box-footer">
                                <div class="slider__pagination-item ng-scope is-active" title="{{ $messageUser->signatureUser->lname??'' }} {{ $messageUser->signatureUser->fname??'' }}">
                                    <div class="slider__thumb">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

            </div>

        </div>

    </section>

    <!-- Main content -->
    <section class="content">
        <!-- AdminLTE App -->
        <script src="{{ asset("/admin-lte/plugins/select2/select2.full.min.js") }}"></script>

        <link href="{{ asset ("/admin-lte/bootstrap/css/bootstrap-datepicker.css") }}" rel="stylesheet"/>
        <script src="{{ asset ("/admin-lte/bootstrap/js/bootstrap-datepicker.js") }}"></script>

        <!-- ckeditor -->
        <script src="{{ asset ("/ckeditor/ckeditor.js") }}"></script>
        <script src="{{ asset ("/ckeditor/samples/js/sample.js") }}"></script>

        <script type="text/javascript">

            initSample();

            $(document).ready(function () {

                // For Users
                $('#edoUsers').hide();

                $('.form-panel').hide();

                $('.btn-bitbucket').on('click', function () {

                    $('.form-panel').toggle();

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

                    CKEDITOR.instances.editor.insertText(tasks);

                });

                // reset date
                $('#resetTermDate').click(function () {

                    $('input[name=term_date]').val('');
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
                startDate: '-Infinity',
                autoclose: true
            });
        </script>
    </section>
    <!-- /.content -->
@endsection
