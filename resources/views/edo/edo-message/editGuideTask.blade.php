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
                        <h3 class="box-title"><i class="fa fa-file margin-r-5"></i> @lang('blade.doc')</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <table class="table table-condensed">
                            <tr>
                                <th style="width: 50%">@lang('blade.reg_journal')</th>
                                <th>@lang('blade.doc_form')/th>
                            </tr>
                            <tr>
                                <td style="width: 50%">@lang('blade.incoming_letter')</td>
                                <td>{{ $model->journalUser->messageType->title }}</td>
                            </tr>
                        </table>
                    </div>
                    <h4><i class="fa fa-backward"></i></h4>
                    <div class="box-body no-padding">
                        <table class="table table-condensed">
                            <tr>
                                <th style="width: 50%"><i class="fa fa-sort-numeric-asc"></i> @lang('blade.incoming_num')</th>
                                <th><i class="fa fa-clock-o"></i> @lang('blade.incoming_date')</th>
                            </tr>
                            <tr>
                                <td style="width: 50%">{{ $model->in_number }}</td>
                                <td>{{ $model->in_date }}</td>
                            </tr>
                        </table>
                    </div>
                    <h4><i class="fa fa-forward"></i></h4>
                    <div class="box-body no-padding">
                        <table class="table table-condensed">
                            <tr>
                                <th><i class="fa fa-sort-numeric-asc"></i> @lang('blade.outgoing_num')</th>
                                <th><i class="fa fa-clock-o"></i> @lang('blade.outgoing_date')</th>
                            </tr>
                            <tr>
                                <td>{{ $model->out_number }}</td>
                                <td>{{ $model->out_date }}</td>
                            </tr>
                        </table>
                    </div>
                    <hr>
                    <!-- /.box-body -->
                    <div class="box-body">
                        <h5><strong>@lang('blade.sender_organization')</strong></h5>
                        <p>{{ $model->from_name }}</p>
                        <hr>
                        <strong><i class="fa fa-file margin-r-5"></i> @lang('blade.doc_app') </strong><br><br>

                        @foreach ($model->files as $file)


                            <a href="{{ route('edoPreView',['preViewFile'=>$file->file_hash]) }}" class="text-info text-bold"
                               target="_blank" class="mailbox-attachment-name"
                               onclick="window.open('<?php echo ('/edoPreView/'. $file->file_hash); ?>',
                                       'modal',
                                       'width=800,height=900,top=30,left=500');
                                       return false;"> <i class="fa fa-search-plus"></i> {{ $file->file_name }}</a>
                            <a href="{{ route('edo-load',['file'=>$file->file_hash]) }}" class="pull-right"><i class="fa fa-cloud-download text-primary"></i> @lang('blade.download')</a>
                            <i class="text-red">({{ \App\Message::formatSizeUnits($file->file_size) }})</i><br><br>
                        @endforeach
                        <hr>
                        <p class="text-bold text-center">{{ $model->title }}</p>
                        <?php echo $model->text ?? 'null';  ?>
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
                        <h3 class="box-title">@lang('blade.task')</h3>
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
                                <td>{{$model->journalUser->officeUser->full_name ?? 'Null'}}
                                    <span class="text-maroon">({{ $model->journalUser->userJob->userRole->title_ru }})</span>
                                </td>
                            </tr>
                            <tr>
                                <td>@lang('blade.date')</td>
                                <td>{{ $model->created_at->format('d M. Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td>@lang('blade.status')</td>
                                <td><span class="label label-warning">@lang('blade.on_process')</span></td>
                            </tr>
                            <tr>
                                <td>1. {{$model->journalUser->guideUser->full_name ?? 'null'}}</td>
                                <td>
                                    @if($model->urgent == 1)
                                        <div class="text-maroon"><i class="fa fa-bell-o text-red fa-lg"></i> @lang('blade.urgent')</div>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <!-- /.box-body -->
                    @if($model->journalUser->status == 0)
                        <div class="box-footer">
                            <button type="submit" class="btn btn-default">@lang('blade.forward_task')</button>
                            <button type="submit" id="letterChip" class="btn btn-info btn-bitbucket pull-right">
                                <i class="glyphicon glyphicon-pushpin"></i> @lang('blade.add_mini_task')
                            </button>
                        </div>
                    @endif
                    <!-- /.box-footer -->

                    <div class="box-footer">
                    </div>
                    <!-- /.box-footer -->

                    <form role="form" method="POST" action="{{ route('edit-g-s_task') }}"
                          enctype="multipart/form-data" id="formResolution">
                        {{csrf_field()}}

                        <div class="form-group">
                            <div class="box-body box-profile">
                                <ul class="list-group list-group-unbordered" id="selectedUsers">
                                    @if(count($perfUsers))
                                        @foreach($perfUsers as $key => $user)
                                            @php
                                                $key = $key+1;
                                            @endphp

                                            <li class="list-group-item listItem{{$user->user_id}} selected-user">
                                                <button value="{{$user->user_id}}" type="button"
                                                        class="close removeItem">
                                                    <span aria-hidden="true">×</span></button>
                                                <div class="user-block">
                                                    <img class="img-circle img-bordered-sm"
                                                         src="/../admin-lte/dist/img/user.png"
                                                         alt="User Image">
                                                    <span class="pull-right btn-box-tool">
                                                    <select class="form-control" name="performer_user[]">
                                                        
                                                        <option value="{{ $user->mes_type_id }}"selected>
                                                            {{ $user->title_ru }}
                                                        </option>

                                                        @foreach($perfUserTypes as $key => $type)

                                                            <option value="{{ $type->id }}">{{ $type->title_ru }}</option>

                                                        @endforeach
                                                    </select>
                                                    
                                                </span>
                                                <div class="username">

                                                    {{ mb_substr($user->fname??'', 0 ,1).'.'.mb_substr($user->sname??'', 0 ,1).'.'.$user->lname??'' }}
                                                    <input value="{{ $user->user_id }}" name="to_user_id[]" hidden/>
                                                    <input value="{{ $user->depart_id }}" name="depart_id[]" hidden/>
                                                </div>
                                                
                                                <div class="description">
                                                    {{ $user->depart_name }} - {{ $user->job_title }}</div>
                                                </div>
                                                
                                            </li>

                                        @endforeach
                                    @endif
                                    @if($all_dep_exists->count())
                                        <li class="list-group-item listItem{{$all_dep_name->id}} selected-user">
                                            <button value="{{$all_dep_name->id}}" type="button"
                                                    class="close removeItem">
                                                <span aria-hidden="true">×</span></button>
                                            <div class="user-block">
                                                <img class="img-circle img-bordered-sm"
                                                        src="/../admin-lte/dist/img/user.png"
                                                        alt="User Image">
                                                <span class="pull-right btn-box-tool">
                                                <select class="form-control" name="performer_user[]">
                                                    
                                                    <option value="{{$all_dep_exists[0]->performerType->id}}"selected>
                                                        {{$all_dep_exists[0]->performerType->title_ru}}
                                                    </option>

                                                    @foreach($perfUserTypes as $key => $type)

                                                        <option value="{{ $type->id }}">{{ $type->title_ru }}</option>

                                                    @endforeach
                                                    
                                                </select>
                                                
                                            </span>
                                            <div class="username">

                                                {{ $all_dep_name->sname.' '.$all_dep_name->lname.' '.$all_dep_name->fname }}
                                                <input value="{{ $all_dep_name->id }}" name="to_user_id[]" hidden/>
                                                <input value="{{ $all_dep_name->depart_id }}" name="depart_id[]" hidden/>
                                            </div>
                                            <div class="description">
                                                - {{ $all_dep_name->job_title }}</div>
                                            </div>                               
                                        </li>
            
                                    @endif
                                    @if($all_filial_exists->count())
                                        <li class="list-group-item listItem{{$all_filial_name->id}} selected-user">
                                            <button value="{{$all_filial_name->id}}" type="button"
                                                    class="close removeItem">
                                                <span aria-hidden="true">×</span></button>
                                            <div class="user-block">
                                                <img class="img-circle img-bordered-sm"
                                                        src="/../admin-lte/dist/img/user.png"
                                                        alt="User Image">
                                                <span class="pull-right btn-box-tool">
                                                <select class="form-control" name="performer_user[]">
                                                    
                                                    <option value="{{$all_filial_exists[0]->performerType->id??''}}"selected>
                                                        {{$all_filial_exists[0]->performerType->title_ru??''}}
                                                    </option>

                                                    @foreach($perfUserTypes as $key => $type)

                                                        <option value="{{ $type->id }}">{{ $type->title_ru }}</option>

                                                    @endforeach
                                                    
                                                </select>
                                                
                                            </span>
                                            <div class="username">

                                                {{ $all_filial_name->lname.' '
                                                .$all_filial_name->fname }}
                                                <input value="{{ $all_filial_name->id }}" name="to_user_id[]" hidden/>
                                                <input value="{{ $all_filial_name->depart_id }}" name="depart_id[]" hidden/>
                                            </div>
                                            <div class="description">
                                                - {{ $all_filial_name->job_title }}</div>
                                            </div>                               
                                        </li>
            
                                    @endif
                                </ul>


                                
                            </div>

                            <!-- /.box-body -->
                        </div>

           

                        <div class="col-md-12 form-panel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="box-body">
                                                <label>@lang('blade.purpose')</label><sup class="text-red"> *</sup>
                                                @php
                                                    $data = $model->messageHelper->edo_type_message_id ?? 'null';
                                                    $type_m = \App\EdoTypeMessages::find($data);
                                                @endphp
                                                <select class="form-control select2 type_send"
                                                        name="edo_type_message_id" required>
                                                    <option value="{{ $type_m->id ?? 1 }}" selected> {{ $type_m->title ?? 'Null' }}</option>
                                                    @foreach($messageTypes as $key => $value)

                                                        <option value="{{ $value->id }}"> {{ $value->title }} </option>

                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="box-body">
                                                <label>@lang('blade.deadline')</label>
                                                <div class="input-group date">
                                                    <div class="input-group input-daterange">
                                                        <input type="text" name="term_date" value="{{ $model->messageHelper->term_date }}" class="form-control"
                                                               readonly/>
                                                    </div>
                                                    <div class="input-group-addon">
                                                        <button type="reset" class="fa fa-remove text-maroon"></button>
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
                                    @foreach($tasks as $task)
                                        <li class="tasks"><a>{{ $task->title }}</a></li>

                                    @endforeach
                                </ul>
                            </div>
                            <div class="box-body">
                                <div class="form-group">
                                    <textarea name="text" id="editor" required>
                                    {{ $model->messageHelper->text ?? 'Null' }}
                                    </textarea>

                                    <input name="model_id" value="{{ $model->id }}" hidden />

                                    <input name="jrnl_id" value="{{ $model->journalUser->id }}" hidden />
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right" id="saveResolutionBtn">
                                    <i class="fa fa-save"></i> @lang('blade.save')
                                </button>
                            </div>
                            <!-- /.box-footer -->
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>
            <!--/.col (right) -->

            <div class="col-md-4">
                <!-- Horizontal Form -->
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">@lang('blade.select_receiver')</h3><sup class="text-red"> *</sup>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="box-tools">
                            <div class="form-group has-warning has-feedback">
                                <input type="text" id="userSearch" class="form-control" onkeyup="userSearchFunction()"
                                       placeholder="@lang('blade.search_executors')">
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                                <span class="help-block">@lang('blade.at_least_3_letters')</span>
                            </div>

                            <div class="form-group">
                                <ul class="list-group" id="unSelectedUsers" style="overflow-y: scroll; max-height: 700px;">
                                    @if(count($users))
                                        @foreach($users as $key => $value)
                                            <li class="list-group-item select-user listItem{{$value->user_id}}">
                                                <button value="{{$value->user_id}}" type="button" class="close removeItem">
                                                    <span aria-hidden="true">×</span></button>
                                                <div class="user-block">
                                                    <img class="img-circle img-bordered-sm"
                                                         src="/../admin-lte/dist/img/user.png"
                                                         alt="User Image">
                                                    <span class="pull-right btn-box-tool performer_type">
                                                    <select class="form-control" name="performer_user[]">
                                                        @foreach($perfUserTypes as $key => $type)

                                                            <option value="{{ $type->id }}">{{ $type->title_ru }}</option>

                                                        @endforeach
                                                    </select>
                                                </span>
                                                    <div class="username">
                                                        {{ mb_substr($value->user->fname??'', 0 ,1).'.'.mb_substr($value->user->sname??'', 0 ,1).'.'.$value->user->lname??'' }}
                                                        <input value="{{ $value->user_id }}" name="to_user_id[]"
                                                               hidden/>
                                                        <input value="{{ $value->department_id }}" name="depart_id[]"
                                                               hidden/>
                                                    </div>
                                                    <div class="description">{{ $value->department->title??'' }}
                                                        - {{ $value->user->job_title??'' }}</div>
                                                </div>
                                            </li>

                                        @endforeach

                                        @foreach($filial_users as $key => $value)

                                            <li class="list-group-item filial_style select-user listItem{{$value->u_id}}">
                                                <button value="{{$value->u_id}}" type="button" class="close removeItem">
                                                    <span aria-hidden="true">×</span></button>
                                                <div class="user-block">
                                                    <img class="img-circle img-bordered-sm"
                                                         src="{{ asset("/admin-lte/dist/img/user.png") }}"
                                                         alt="User Image">
                                                    <span class="pull-right btn-box-tool performer_type">
                                                    <select class="form-control" name="performer_user[]">
                                                        @foreach($perfUserTypes as $key => $type)

                                                            <option value="{{ $type->id }}"><label for="txt206451"></label>{{ $type->title_ru }}</option>

                                                        @endforeach
                                                    </select>
                                                </span>
                                                    <div class="username">
                                                        {{ $value->full_name }}
                                                        <input value="{{ $value->user_id }}" name="to_user_id[]" hidden />
                                                        <input value="{{ $value->department_id }}" name="depart_id[]" hidden />
                                                    </div>
                                                    <div class="description">{{ $value->branch_name }} - {{ $value->job_title }}</div>
                                                </div>
                                            </li>

                                        @endforeach
                                    @endif
                                    @if(!$all_dep_exists->count())
                                        <li class="list-group-item select-user listItem{{$all_dep_name->id}}">
                                            <button value="{{$all_dep_name->id}}" type="button" class="close removeItem">
                                                <span aria-hidden="true">×</span></button>
                                            <div class="user-block">
                                                <img class="img-circle img-bordered-sm"
                                                        src="/../admin-lte/dist/img/user.png"
                                                        alt="User Image">
                                                <span class="pull-right btn-box-tool performer_type">
                                                <select class="form-control" name="performer_user[]">
                                                    @foreach($perfUserTypes as $key => $type)

                                                        <option value="{{ $type->id }}">{{ $type->title_ru }}</option>

                                                    @endforeach
                                                </select>
                                            </span>
                                                <div class="username">
                                                    {{ $all_dep_name->sname.' '.$all_dep_name->lname.' '.$all_dep_name->fname }}
                                                    <input value="{{ $all_dep_name->id }}" name="to_user_id[]"
                                                            hidden/>
                                                    <input value="{{ $all_dep_name->depart_id }}" name="depart_id[]"
                                                            hidden/>
                                                </div>
                                                <div class="description">
                                                    {{ $all_dep_name->job_title }}
                                                </div>
                                            </div>
                                        </li>
                                    @endif

                                    @if(!$all_filial_exists->count())
                                        <li class="list-group-item filial_style select-user listItem{{$all_filial_name->id}}">
                                            <button value="{{$all_filial_name->id}}" type="button" class="close removeItem">
                                                <span aria-hidden="true">×</span></button>
                                            <div class="user-block">
                                                <img class="img-circle img-bordered-sm"
                                                        src="{{ asset("/admin-lte/dist/img/user.png") }}"
                                                        alt="User Image">
                                                <span class="pull-right btn-box-tool performer_type">
                                                <select class="form-control" name="performer_user[]">
                                                    @foreach($perfUserTypes as $key => $type)

                                                        <option value="{{ $type->id }}"><label for="txt206451"></label>{{ $type->title_ru }}</option>

                                                    @endforeach
                                                </select>
                                            </span>
                                                <div class="username">
                                                    {{ $all_filial_name->lname.' '.$all_filial_name->fname.' ' }}
                                                    <input value="{{ $all_filial_name->id }}" name="to_user_id[]" hidden />
                                                    <input value="{{ $all_filial_name->depart_id }}" name="depart_id[]" hidden />
                                                </div>
                                                <div class="description"> {{ $all_filial_name->job_title }}</div>
                                            </div>
                                        </li>
                                    @endif

                                </ul>
                            </div>
                            <!-- /.box-body -->
                        </div>

                    </div>

                </div>
                <!-- /.box -->
            </div>
            <!--/.col (right) -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->

    <!-- Main content -->
    <section class="content">
        <!-- Select2 -->
        <script src="{{ asset("/admin-lte/plugins/select2/select2.full.min.js") }}"></script>

        <link href="{{ asset ("/admin-lte/bootstrap/css/bootstrap-datepicker.css") }}" rel="stylesheet"/>
        <script src="{{ asset ("/admin-lte/bootstrap/js/datepicker/bootstrap.min.js") }}"></script>
        <script src="{{ asset ("/admin-lte/bootstrap/js/bootstrap-datepicker.js") }}"></script>

        <!-- ckeditor -->
        <script src="{{ asset ("/ckeditor/ckeditor.js") }}"></script>
        <script src="{{ asset ("/ckeditor/samples/js/sample.js") }}"></script>

        <script type="text/javascript">

            initSample();
            $(document).ready(function () {


                // Send type for create
                /*$('.send-type-div').hide();

                $('.type_send').on('change', function () {

                    if (this.value == 4) {

                        $(".send-type-div").show();

                    } else {

                        $(".send-type-div").hide();
                    }
                });*/

                // For Users
                $('letterChip').on('click', function () {

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

                    CKEDITOR.instances.editor.insertText(tasks);

                });

            });

            function userSearchFunction() {
                var input = document.getElementById("userSearch");
                var filter = input.value.toLowerCase();
                var nodes = document.getElementsByClassName('select-user');

                for (i = 0; i < nodes.length; i++) {
                    if (nodes[i].innerText.toLowerCase().includes(filter)) {
                        nodes[i].style.display = "block";
                    } else {
                        nodes[i].style.display = "none";
                    }
                }
            }

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
            
            $('#saveResolutionBtn').click(function(e){
                e.preventDefault();
                $('#formResolution').submit();
                $(this).attr("disabled",true)
            })
        </script>
    </section>
    <!-- /.content -->
@endsection