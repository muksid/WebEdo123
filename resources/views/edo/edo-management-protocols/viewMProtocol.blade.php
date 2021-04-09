@extends('layouts.edo.dashboard')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('/admin-lte/plugins/select2/select2.min.css') }}">
@section('content')
    <div class="content-header">
        <section class="content-header">
            <h1>
                @switch($model->protocol_type)
                    @case(3)
                        @lang('blade.protocol_management')
                        @break
                    @case(11)
                        @lang('blade.hr_orders')
                        @break
                    @case(20)
                        @lang('blade.kazna_protocols')
                        @break
                    @case(24)
                        @lang('blade.strategy_orders')
                        @break
                    @default
                        @break
                @endswitch
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Bosh sahifa</a></li>
                <li class="active">protocol</li>
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
                    <div class="col-md-7">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                @if($model->stf_number??'')
                                    <h4 class="" style="margin: 1px">
                                        {{ $model->stf_number??'' }}  <span style="margin-left: 1%">{{ date('d-m-Y', strtotime($model->stf_date)) }}</span>
                                    </h4>
                                @endif

                                <div class="box-tools pull-right">
                                    <a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="Orqaga"><i class="fa fa-chevron-left"></i></a>
                                    <a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="Keyingisi"><i class="fa fa-chevron-right"></i></a>
                                </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div class="mailbox-read-info text-center">
                                    <h3>{{ $model->title }}</h3>
                                </div>

                                <div class="mailbox-read-message" style="padding: 0 12% !important;">
                                    <?php echo $model->text??''; ?>
                                </div>
                            </div>
                            <!-- /.box-body -->
                            @if($guide)
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="description-block">
                                        <span class="description-header text-left stf-vertical-middle" style="padding-right:30px">
                                            @lang('blade.management_guide')
                                        </span>
                                        @if(!empty($guide) && ($guide->status == 0 || $guide->status == 1))
                                            <img class="attachment-img stf-img-center-image" src="{{ url('/FilesQR/image_icon.png') }}" style="height: 65px; width:auto">
                                        @elseif($guide->status == -1)
                                            <span class="label label-danger"> Rad etildi</span>
                                            <br>
                                            <span><i class="text-muted">Izox:</i> {{ $guide->descr }}</span>
                                        @elseif($guide->status == 2)
                                            {!! QrCode::size(70)->generate('https://online.turonbank.uz:3347/acc/'.$guide->managementMembers->qr_name.
                                                    '/'.$guide->managementMembers->qr_hash.'/'.$model->id.'/'.substr($model->protocol_hash, 0,4) ); !!}                                        @endif
                                            <span class="description-header text-right stf-vertical-middle" style="padding-left:30px">
                                                _____________ {{ $guide->user->substrUserName($guide->user_id) }}
                                            </span>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="col-md-3"></div>
                                        <div class="col-md-6">

                                        @if($guide->status == 1 && $guide->user_id == \Illuminate\Support\Facades\Auth::id())
                                            <button type="button" class="btn btn-flat btn-primary pull-right"
                                                    data-id="{{ $guide->id }}" id="confirmModal">
                                                @lang('blade.approve')
                                            </button>

                                            <button type="button" class="btn btn-flat btn-warning"
                                                    data-toggle="modal" data-target="#cancelModal">
                                                @lang('blade.cancel')
                                            </button>
                                        @endif
                                        </div>
                                        <div class="col-md-3"></div>

                                    </div>

                                </div>

                            </div>
                            @endif
                            <div class="clearfix"></div>

                        </div>
                        <div class="col-md">
                            <div class="box" style="padding: 5px">
                                <h4>Ilovalar:</h4>
                                <table class="table" style="max-width: 576px">
                                    <tbody>

                                    @if($model_files)
                                        @foreach($model_files as $key => $file)

                                                <tr>
                                                    <th scope="row">{{ $key+1 }}.</th>
                                                    <td>
                                                        @switch($file->file_extension)
                                                            @case('doc')
                                                            @case('docx')
                                                            @case('xls')
                                                            @case('xlsx')
                                                            @case('pptx')
                                                            <a href="{{ route('download-protocol-file', ['id' => $file->id]) }}"
                                                                class="text-info text-bold">
                                                                <i class="fa fa-search-plus"></i> {{ $file->file_name }}
                                                            </a>
                                                            @break
                                                            @default
                                                            <a href="#" class="text-info text-bold previewSingleFile" data-id="{{ $file->id }}">
                                                                <i class="fa fa-search-plus"></i> {{ $file->file_name }} -  {{ $file->file_extension }}
                                                            </a>
                                                            @break
                                                        @endswitch

                                                    </td>
                                                    <td>
                                                        <a href="{{ route('download-protocol-file', ['id' => $file->id]) }}" class="text-bold">
                                                            @lang('blade.download') <i class="fa fa-download"></i>
                                                        </a>
                                                    </td>
                                                </tr>

                                        @endforeach
                                    @endif

                                    </tbody>

                                </table>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="box box-primary">
                            <div class="box-body">
                                @switch($model->protocol_type)
                                    @case(3)
                                    @case(11)
                                        <h4>@lang('blade.committe_members'):</h4>
                                        @break
                                    @case(24)
                                        <h4>@lang('blade.management_members'):</h4>
                                        @break
                                    @default
                                        @break
                                @endswitch

                                <hr>
                                <div class="row">
                                    @foreach($model->viewMembers as $key => $value)
                                        <div class="col-sm">
                                            <div class="description-block">
                                                @if($value->user_role == 3)
                                                    <div class="col-sm">
                                                        <span class="text-bold">@lang('blade.prepared_by'): «{{ $value->user->department->title??'' }}»</span>
                                                    </div>
                                                    <br>
                                                @endif
                                                @if($value->user_role == 4)
                                                    <div class="col-sm">
                                                        <span class="text-bold">@lang('blade.suggested_protocol_member')</span>
                                                    </div>
                                                    <br>
                                                @endif
                                                @if($value->user_role == 4)
                                                    <div class="col-sm">
                                                        <span class="text-bold">@lang('blade.suggested_protocol_member')</span>
                                                    </div>
                                                    <br>
                                                @endif
                                                <span class="description-header text-left stf-vertical-middle" style="padding-right: 65px">
                                                    {{ $value->user->substrUserName($value->user_id) }} _____________
                                                </span>

                                                @if($value->status == 1)
                                                    <img class="attachment-img" src="{{ url('/FilesQR/image_icon.png') }}" style="height: 65px; width:auto">
                                                    @if($value->user_id == \Illuminate\Support\Facades\Auth::id())
                                                        <button type="button" class="btn btn-xs btn-flat btn-primary" data-id="{{ $value->id }}" id="confirmModal">
                                                            @lang('blade.approve')
                                                        </button>

                                                        <button type="button" class="btn btn-xs btn-flat btn-warning" data-toggle="modal" data-target="#cancelModal">
                                                            @lang('blade.cancel')
                                                        </button>
                                                    @endif
                                            @elseif($value->status == -1)
                                                    <span class="label label-danger"> Rad etildi</span><br>
                                                    <span><i class="text-muted">Izox:</i> {{ $value->descr }}</span><hr>
                                                    @if($value->user_id == \Illuminate\Support\Facades\Auth::id())
                                                        <button type="button" class="btn btn-xs btn-flat btn-primary" data-id="{{ $value->id }}" id="confirmModal">
                                                            @lang('blade.approve')
                                                        </button>

                                                        <button type="button" class="btn btn-xs btn-flat btn-warning" data-toggle="modal" data-target="#cancelModal">
                                                            @lang('blade.cancel')
                                                        </button>
                                                    @endif
                                            @else
                                                <?php
                                                    $guide_qr_name = $guide->managementMembers->qr_name??'';
                                                    $guide_qr_hash = $guide->managementMembers->qr_hash??'';
                                                ?>
                                                {!! QrCode::size(70)->generate('https://online.turonbank.uz:3347/acc/'.$value->managementMembers->qr_name.
                                                    '/'.$value->managementMembers->qr_hash.'/'.$model->id.'/'.substr($model->protocol_hash, 0,4) ); !!}
                                            @endif
                                            </div>
                                        </div>
                                        <hr style="margin-top: 0px;margin-bottom: 0px;">
                                    @endforeach
                                </div>
                            </div>

                            <div class="box-footer"></div>
                        </div>
                    </div>
            </div>

            <!-- Confirm modal -->
            <div id="ConfirmModal" class="modal fade modal-primary" role="dialog">
                <div class="modal-dialog modal-sm">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header bg-danger">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title text-center">@lang('blade.qr_conf_sign')</h4>
                        </div>

                        <div class="modal-body">
                            <h4 class="text-center"><span class="glyphicon glyphicon-info-sign"></span> @lang('blade.qr_you_sign')</h4>
                        </div>

                        <div class="modal-footer">
                            <center>
                                <button type="button" class="btn btn-outline pull-left"
                                        data-dismiss="modal">@lang('blade.cancel')</button>
                                <button type="button" class="btn btn-outline" id="yesConfirm"
                                        value="create">@lang('blade.qr_i_sign')
                                </button>
                            </center>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cancel modal -->
            <div id="cancelModal" class="modal fade modal-warning" role="dialog">
                <div class="modal-dialog modal-sm">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header bg-danger">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">@lang('blade.cancel')</h4>
                        </div>
                        <form method="POST" action="{{ route('cancel-protocol') }}" class="form-horizontal">
                            {{csrf_field()}}
                            <div class="modal-body">

                                <label>@lang('blade.leave_comment')</label><sup class="text-red"> *</sup>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <input name="model_id" value="{{ $model->id }}" hidden/>
                                        <textarea name="cancel_desc" class="form-control" rows="2" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <center>
                                    <button type="button" class="btn btn-outline pull-left"
                                            data-dismiss="modal">@lang('blade.cancel')</button>
                                    <button type="submit" class="btn btn-outline"
                                            >@lang('blade.send')
                                    </button>
                                </center>
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
                            <h4 class="modal-title"><span id="modalHeader"></span></h4>
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

            <!-- Select2 -->
            <script src="{{ asset("/admin-lte/plugins/select2/select2.full.min.js") }}"></script>

            <script type="text/javascript">
                $(document).ready(function () {

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $(function () {
                        //Initialize Select2 Elements
                        $(".select2").select2();
                    });

                    $('body').on('click', '#confirmModal', function (e) {
                        e.preventDefault();
                        var id = $(this).data("id");

                        $('#ConfirmModal').data('id', id).modal('show');
                    });

                    $('#yesConfirm').click(function () {

                        var id = $('#ConfirmModal').data('id');

                        $.ajax(
                            {
                                type: 'GET',
                                url: "{{ url('/edo-management-protocols') }}" + '/' + id,
                                success: function (data) {
                                    if (data.success === true){

                                        $('#ConfirmModal').modal('hide');
                                        $('#modalHeader').html("Vazifa tasdiqlandi");
                                        $('#success-msg').html(data.msg);
                                        $('#success-modal').modal('show');

                                        setTimeout(function () {
                                            location.reload();
                                        }, 2000);
                                    }
                                }
                            });
                    });

                    $('.closeModal').click( function () {

                        $('#myModal').hide();

                    })

                    // Preview File
                    $('.previewSingleFile').unbind().click(function(){

                        let id = $(this).data('id')

                        window.open('/edo/preview-protocol-file/' + id, 'modal', 'width=800,height=900,top=30,left=500')

                        return false
                    })

                });
            </script>

        </section>
        <!-- /.content -->
    </div>


@endsection

