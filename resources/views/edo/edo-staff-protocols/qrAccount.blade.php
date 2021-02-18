@extends('layouts.dashboard')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1 class="text-center text-bold" style="color: white">
                    @lang('blade.open_acc')
                    <small><button type="button" onclick="location.href='/'" class="btn btn-block bg-blue-gradient btn-flat">@lang('blade.home')</button></small>
                </h1>
                <ul class="multi-language">
                    <li><a><i class="fa fa-language"></i></a></li>
                    <li><a href="https://online.turonbank.uz:3347/locale/ru" >@lang('blade.lang_ru')</a></li>
                    <li> | </li>
                    <li><a href="https://online.turonbank.uz:3347/locale/uz" >@lang('blade.lang_uz')</a></li>
                </ul>
            </section>
            <hr>
        </section>


        <!-- Main content -->
        <section class="content">
            <div class="container">
                <!-- Message Succes -->
                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        {{ $message }}
                        <button type="button" id="showApp" class="btn bg-red-active btn-flat margin">@lang('blade.apply_status')</button>
                    </div>
                @endif
            <!-- Display Validation Errors -->
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Xatolik!</strong> Ma`lumotlarni qaytadan tekshiring.<br><br>
                        <ul>
                            {{--@foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach--}}
                        </ul>
                    </div>
                @endif
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">@lang('blade.client_inf')</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">

                        <?php
                        $actual_link = 'https://'.$_SERVER['HTTP_HOST'];
                        ?>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
                <div id="offerModal" class="modal fade in">
                    <div class="modal-default">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title text-center"></h4>
                                    </div>
                                    <div class="modal-body">
                                        <div style="height: 450px; overflow-y: scroll;padding-right: 15px">
                                        </div>
                                        <div class="modal-footer">
                                            <label class="text-red text-right">@lang('blade.fr_offer_modal')
                                                <input type="checkbox" id="offerUnchecked" class="closeModal text-red"/>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="appModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                <h4 class="modal-title text-center" id="myModalLabel">@lang('blade.apply_status')</h4>
                            </div>
                            <div class="modal-body">

                                <form >
                                    <meta name="csrf-token" content="{{ csrf_token() }}" />
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('blade.id_number')<sup class="text-red">*</sup></label>
                                            <input type="number" name="m_acc_id" class="form-control" required="">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('blade.tin_number')<sup class="text-red">*</sup></label>
                                            <input type="number" name="m_acc_inn" class="form-control" required="">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div id="acc_success"></div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button id="getAccId" class="btn btn-bitbucket center-block"><i class="fa fa-check-circle"></i> Enter</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- jQuery 2.2.3 -->
                <script src="/admin-lte/plugins/jQuery/jquery-2.2.3.min.js"></script>
                <!-- Bootstrap 3.3.6 -->
                <script src="/admin-lte/bootstrap/js/bootstrap.min.js"></script>
                <!-- Select2 -->
                <script src="/admin-lte/plugins/select2/select2.full.min.js"></script>
                <!-- InputMask -->
                <script src="/admin-lte/plugins/input-mask/jquery.inputmask.js"></script>
                <script src="/admin-lte/plugins/input-mask/jquery.inputmask.extensions.js"></script>
                <!-- date-range-picker -->
                <script src="/admin-lte/bootstrap/js/moment2.11.2/moment.min.js"></script>
                <!-- SlimScroll 1.3.0 -->
                <script src="/admin-lte/plugins/slimScroll/jquery.slimscroll.min.js"></script>
                <!-- iCheck 1.0.1 -->
                <script src="/admin-lte/plugins/iCheck/icheck.min.js"></script>
                <!-- AdminLTE App -->
                <script src="/admin-lte/dist/js/app.min.js"></script>

                <script type="text/javascript">

                    $(document).ready(function () {

                        $('button.mycls').attr("disabled", true);
                        $('#offerConfirm').click(function(){
                            if($(this).is(':checked')){
                                $('#offerModal').show();
                                $('button.mycls').removeClass("disabled");
                                $('button.mycls').attr("disabled", false);
                                $( "#offerUnchecked" ).prop( "checked", false );
                            } else {
                                $('button.mycls').attr("disabled", true);
                                $("#offerModal").hide();
                            }
                        });

                        // close Modal
                        $('.closeModal').click(function () {

                            $('#offerModal').fadeOut('slow');

                        });


                        $("#showApp").click(function () {
                            //alert('ok');
                            $('#appModal').modal('show');
                        });

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $("#getAccId").click(function(e){
                            e.preventDefault();
                            var id = $("input[name=m_acc_id]").val();
                            var inn = $("input[name=m_acc_inn]").val();
                            $.ajax({
                                type:'POST',
                                url:'/get-acc-id',
                                data:{id:id, inn:inn},
                                success:function(data){
                                    if (data.hash == '#'){
                                        var acc_not= "@lang('blade.org_not_found')";
                                        $('#acc_success').html(acc_not);

                                    } else {
                                        var acc_success='';
                                        acc_success+=
                                            "<div class='timeline-footer'><b>@lang('blade.org_found') </b>"+data.name+
                                            "<a href='acc/"+data.hash+"' class='btn btn-flat btn-xs'>@lang('blade.more')</a>"+
                                            "</div>";
                                        $('#acc_success').html(acc_success);

                                    }
                                },
                                error: function () {
                                    console.log('error');
                                }
                            });
                        });

                        $(function () {
                            //Initialize Select2 Elements
                            $(".select2").select2();

                            //Money Euro
                            $("[data-mask]").inputmask("99-999-999-99-99");

                            $("[data-mask-inn]").inputmask("999-999-999");

                            //iCheck for checkbox and radio inputs
                            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                                checkboxClass: 'icheckbox_minimal-blue',
                                radioClass: 'iradio_minimal-blue'
                            });
                            //Red color scheme for iCheck
                            $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
                                checkboxClass: 'icheckbox_minimal-red',
                                radioClass: 'iradio_minimal-red'
                            });
                            //Flat red color scheme for iCheck
                            /*$('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                                checkboxClass: 'icheckbox_flat-green',
                                radioClass: 'iradio_flat-green'
                            });*/
                        });
                    });
                </script>
        </section>
        <!-- /.content -->
    </div>
@endsection
