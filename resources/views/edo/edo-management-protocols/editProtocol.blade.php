@extends('layouts.edo.dashboard')
<!-- Select2 -->
<link href="{{ asset("/admin-lte/plugins/select2/select2.min.css") }}" rel="stylesheet" type="text/css">

@section('content')

    <!-- TRANSLATED -->

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
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang('blade.create_doc')
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- SELECT2 EXAMPLE -->
        <div class="box box-primary">
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-8">

                        <form role="form" id="frm" method="POST" action="{{ route('edo-store-edit-protocol') }}" enctype="multipart/form-data">
                            {{csrf_field()}}

                                <div class="form-group">
                                    <label>@lang('blade.doc_name')</label>
                                    <input type="text" name="title" id="messageTitle" class="form-control" maxlength="512"
                                           value="{{ $model->title }}">
                                    <input type="text" name="model_id" value="{{ $model->id }}" hidden>
                                    <span id="limitMessageTitle" class="help-block">(@lang('blade.limit_512'))</span>
                                </div>
                                <div class="form-group">
                                    <label>@lang('blade.summary')</label><sup class="text-red"> *</sup>
                                    <textarea name="text" id="editor" required>{{ $model->text }}</textarea>
                                </div>
                                <div class="form-group">
                                    <div class="box-body box-profile">
                                        <ul class="list-group list-group-unbordered" id="selectedUsers">
                                            @foreach($selectedUsers as $key => $user)
                                                <li class="list-group-item listItem{{ $user->user_id }} unselect-user">
                                                    <button value="{{ $user->user_id }}" type="button" class="close removeItem" style="display: block;">
                                                        <span aria-hidden="true">×</span></button>
                                                    <div class="user-block">
                                                        <img class="img-circle img-bordered-sm" src="{{ asset("/admin-lte/dist/img/user.png") }}" alt="user">
                                                        <span class="pull-right btn-box-tool user_role" style="display: block;">
                                                            <select class="form-control" name="user_role[]">
                                                                @if($user->user_role == 1)
                                                                    <option value="1">Boshqaruv Raisi</option>
                                                                    <option value="2">Boshqaruv a`zolari</option>
                                                                @else
                                                                    <option value="2">Boshqaruv a`zolari</option>
                                                                    <option value="1">Boshqaruv Raisi</option>
                                                                @endif

                                                            </select>
                                                        </span>
                                                        <div class="username">
                                                            {{ $user->user->substrUserName($user->user_id) }}
                                                            <i class="fa fa-check-square-o text-green"></i>
                                                            <input value="{{ $user->user_id }}" name="to_user_id[]" hidden="">
                                                            <input value="{{ $user->user_sort }}" name="user_sort[]" hidden="">
                                                        </div>
                                                        <div class="description">{{ $user->title }}</div>
                                                    </div>
                                                </li>

                                            @endforeach
                                        </ul>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <!-- /.form-group -->
                            <!-- /.col-->
                            <div class="col-md-12">
                                <div class="alert alert-warning">
                                    <i class="fa fa-info"></i> Vazifaga o`zgartirish kiritilganda Boshqaruv a`zolariga qaytadan yuboriladi!
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <a href="{{ url('edo/index-protocols') }}" class="btn btn-default">@lang('blade.cancel') </a>
                                        <button type="submit" class="btn btn-primary">@lang('blade.update')</button>
                                    </div>

                                </div>
                            </div>
                            <!-- /.col -->
                        </form>

                    </div>
                    <div class="col-md-4">
                        <div class="box" id="edoUsers">
                            <div class="box-header with-border">
                                <h3 class="box-title">Boshqaruv a`zolarini biriktirish</h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div class="box-tools">
                                    <div class="form-group">
                                        <ul class="list-group" id="unSelectedUsers"
                                            style="overflow-y: scroll; max-height: 400px">
                                            @foreach($users as $key => $value)

                                                <li class="list-group-item select-user listItem{{$value->user_id}}">
                                                    <button value="{{$value->user_id}}" type="button"
                                                            class="close removeItem">
                                                        <span aria-hidden="true">×</span></button>
                                                    <div class="user-block">
                                                        <img class="img-circle img-bordered-sm"
                                                             src="{{ asset("/admin-lte/dist/img/user.png") }}"
                                                             alt="user">
                                                        <span class="pull-right btn-box-tool user_role">
                                                            <select class="form-control" name="user_role[]">
                                                                @if($value->user_sort == 1)
                                                                    <option value="1">Boshqaruv Raisi</option>
                                                                @else
                                                                    <option value="2">Boshqaruv a`zolari</option>
                                                                    @endif

                                                            </select>
                                                        </span>
                                                        <div class="username">
                                                            {{ $value->user->substrUserName($value->user_id) }}
                                                            <i class="fa fa-check-square-o text-red"></i>
                                                            <input value="{{ $value->user_id }}" name="to_user_id[]"
                                                                   hidden/>
                                                            <input value="{{ $value->user_sort }}" name="user_sort[]"
                                                                   hidden/>
                                                        </div>
                                                        <div class="description">{{ $value->title }}</div>
                                                    </div>
                                                </li>

                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- ckeditor -->
        <script src="{{ asset ("/ckeditor/ckeditor.js") }}"></script>
        <script src="{{ asset ("/ckeditor/samples/js/sample.js") }}"></script>

        <script type="text/javascript">
            initSample();
            // limit message title
            $('#messageTitle').keyup(function () {

                $('#limitMessageTitle').show();

                if (this.value.length > 512) {
                    alert('Hujjat nomi: belgilar soni limitdan oshdi!');
                    return false;
                }

                $("#limitMessageTitle").html((512 - this.value.length) + ' belgi qoldi');
            });


            $('.select-user .user_role').hide();
            $('.select-user .removeItem').hide();

            $('ul').on('click', '.select-user', function () {

                $(this).removeClass('select-user').addClass('unselect-user').appendTo('ul#selectedUsers');

                $('#selectedUsers .user_role').show();

                $('.unselect-user .removeItem').show();

            });
            // remove selected user (in left)
            $('.removeItem').click(function () {

                var listVal = $(this).val();

                var listItem = '.listItem'+listVal;

                $(listItem).removeClass('unselect-user').removeClass('selected-user').addClass('select-user').appendTo('ul#unSelectedUsers');

                $(listItem+'.select-user span').addClass('user_role');

                $('.select-user .user_role').hide();

                $('.select-user .removeItem').hide();
            });

        </script>

    </section>

@endsection