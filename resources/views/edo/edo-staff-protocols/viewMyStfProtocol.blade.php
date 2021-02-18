@extends('layouts.edo.dashboard')
@section('content')

    <div class="content-header">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>@lang('blade.hr_orders')
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home')</a></li>
                <li class="active">@lang('blade.hr_orders')</li>
            </ol>

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Xatolik!</strong> Errors.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        <!-- Message Succes -->
            @if ($message = Session::get('success'))
                <div class="modal fade in" id="myModal" role="dialog" style="display: block">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header bg-aqua-active">
                                <h4 class="modal-title">
                                    Boshqaruv qarori <i class="fa fa-qrcode"></i>
                                </h4>
                            </div>
                            <div class="modal-body">
                                <h3>{{ $message }}</h3>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-info closeModal" data-dismiss="modal"><i
                                            class="fa fa-check-circle"></i> Ok
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            @endif
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-7">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('blade.hr_orders')</h3>

                            <div class="box-tools pull-right">
                                <a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="Orqaga"><i
                                            class="fa fa-chevron-left"></i></a>
                                <a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="Keyingisi"><i
                                            class="fa fa-chevron-right"></i></a>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body no-padding">
                            <div class="mailbox-read-info text-center">
                                <h3>{{ $model->title }}</h3>
                            </div>
                            <!-- /.mailbox-controls -->
                            <div class="mailbox-read-message">
                                <?php echo $model->text ?? ''; ?>
                            </div>
                            <!-- /.mailbox-read-message -->
                        </div>
                        <!-- /.box-body -->
                        <div class="col-md-12">
                            <div class="row">

                                <div class="col-sm-4">
                                    <div class="description-block">
                                        <h5 class="description-header text-left stf-vertical-middle">
                                            @lang('blade.management_guide')
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    @if(!empty($guide))
                                        @if($guide->status == 0 || $guide->status == 1)
                                            <div class="attachment-block clearfix stf-img-center">
                                                <img class="attachment-img stf-img-center-image" src="{{ url('/FilesQR/image_icon.png') }}">
                                            </div>
                                        @elseif($guide->status == -1)
                                            <div class="attachment-block clearfix stf-img-center stf-vertical-middle">
                                                <span class="label label-danger"> Rad etildi</span>
                                                <br>
                                                <span><i class="text-muted">Izox:</i> {{ $guide->descr }}</span>
                                            </div>
                                        @elseif($guide->status == 2)
                                            <div class="attachment-block clearfix stf-img-center">
                                                <img class="attachment-img stf-img-center-image"
                                                     src="/FilesQR/{{$guide->managementMembers->qr_file??'' }}"
                                                     alt="{{$guide->user->lname}}">
                                            </div>
                                        @endif
                                    @endif
                                </div>
                                <div class="col-sm-4">
                                    <div class="description-block">
                                        <h5 class="description-header text-right stf-vertical-middle">
                                            _____________ {{ $guide->user->substrUserName($guide->user_id) }}
                                        </h5>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="box-footer">
                        </div>
                        <!-- /.box-footer -->
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="box box-primary">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <h4>@lang('blade.management_members'):</h4>
                            <hr>
                            <div class="row">
                                @foreach($model->viewMembers as $key => $value)

                                    <div class="col-sm-3">
                                        <div class="description-block">
                                            <h5 class="description-header text-left">
                                                {{ $value->user->substrUserName($value->user_id) }}
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="description-block">
                                            <h5 class="description-header text-left">
                                                 _____________
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        @if($value->status == 1)
                                            <div class="attachment-block clearfix stf-img-center">
                                                <img class="attachment-img stf-img-center-image" src="{{ url('/FilesQR/image_icon.png') }}">
                                            </div>
                                        @elseif($value->status == 0)
                                            <div class="attachment-block clearfix stf-img-center">
                                                <label class="text-danger"> Rad etildi</label>
                                                <span> {{ $value->descr }}</span>
                                            </div>
                                        @else
                                            <div class="attachment-block clearfix stf-img-center">
                                                <img class="attachment-img stf-img-center-image"
                                                     src="/FilesQR/{{$value->managementMembers->qr_file??'' }}"
                                                     alt="{{$value->user->lname}}">
                                            </div>
                                        @endif
                                        <br>
                                    </div>
                                    <!-- /.col -->
                                @endforeach
                            </div>
                        </div>
                        <div class="clearfix"></div>

                    </div>
                </div>
            </div>

            {{--Confirm modal--}}
            <div class="modal fade modal-info" id="confirm-modal" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><i class="fa fa-check-circle-o"></i> @lang('blade.confirm')</h4>
                        </div>
                        <form id="confirmForm" name="confirmForm">
                            <div class="modal-body">
                                <p class="text-center">@lang('blade.qr_you_sign')</p>
                                <p id="hr_id"></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline pull-left"
                                        data-dismiss="modal">@lang('blade.cancel')</button>
                                <button type="submit" class="btn btn-outline" id="btn-save"
                                        value="create">@lang('blade.qr_i_sign')
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{--Success modal--}}
            <div class="modal fade modal-success" id="success-modal" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">@lang('blade.hr_orders')</h4>
                        </div>
                        <div class="modal-body">
                            <p id="success-msg" class="text-center"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline pull-right"
                                    data-dismiss="modal">@lang('blade.close')</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- jQuery 2.2.3 -->
            <script src="{{ asset ("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

            <script src="{{ asset ("/js/jquery.validate.js") }}"></script>

            <script type="text/javascript">

                $(document).ready(function () {

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $('#create-new-post').click(function () {
                        $('#btn-save').val("create-post");
                        $('#postForm').trigger("reset");
                        $('#postCrudModal').html("Add New post");
                        $('#ajax-crud-modal').modal('show');
                    });

                    $('body').on('click', '#confirm-protocol', function () {

                        var hr_id = $(this).data('id');

                        var mData = "<input name='model_id' value='" + hr_id + "' hidden/>";

                        $('#hr_id').empty();

                        $('#hr_id').prepend(mData);

                        $('#confirm-modal').modal('show');

                    });

                if ($("#confirmForm").length > 0) {

                    $("#confirmForm").validate({

                        submitHandler: function (form) {

                            $.ajax({

                                data: $('#confirmForm').serialize(),

                                url: "{{ route('stf-main-confirm') }}",

                                type: "POST",

                                dataType: 'json',

                                success: function (data) {

                                    $('#success-msg').prepend(data.msg);

                                    $('#success-modal').modal('show');

                                    setTimeout(function () {
                                        window.location.href = "/edo/staff-protocols";
                                    }, 3000);

                                    $('#confirm-modal').modal('hide');

                                },
                                error: function (data) {
                                    console.log('Error:', data);
                                    $('#btn-save').html('Save Changes');
                                }
                            });
                        }
                    })
                }

                });
            </script>

        </section>
        <!-- /.content -->
    </div>


@endsection

