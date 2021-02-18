@extends('layouts.table')
@section('content')

    <!-- DO NOT TRANSLATE -->

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>ip create
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Bosh sahifa</a></li>
                <li class="active">ip create</li>
            </ol>

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Xatolik!</strong> ip yaratishda xatolik mavjud.<br><br>
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
            <div class="row">
                <form role="form" method="POST" action="{{ url('ip-networks') }}"
                      enctype="multipart/form-data">
                {{csrf_field()}}
                <!-- /.box-header -->
                    <div class="col-md-10">
                        <div class="box box-primary">
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>Filial</label>
                                            <input type="text" name="filial_code" value="{{ Auth::user()->branch_code }}"
                                                   class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="box-body">
                                        <div class="form-group {{ $errors->has('ip_owner_id') ? 'has-error' : '' }}">
                                            <label>User</label><sup class="text-red"> *</sup>
                                            <select class="form-control select2" name="ip_owner_id">
                                                @foreach($users as $key => $user)

                                                    <option value="{{ $user->id }}">{{ $user->lname.' '.$user->fname }}</option>

                                                @endforeach
                                            </select>
                                            @if ($errors->has('ip_owner_id'))
                                                <span class="text-red" role="alert">
                                        <strong>{{ $errors->first('ip_owner_id') }}</strong>
                                    </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Second row --}}
                                <div class="col-md-3">
                                    <div class="box-body">
                                        <label>IP 1</label><sup class="text-red"> *</sup>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-laptop"></i>
                                            </div>
                                            <input type="text" name="ip_first" class="form-control" placeholder="10.11.64.47" data-inputmask="'alias': 'ip'" data-mask>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="box-body">
                                        <label>IP 2</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-laptop"></i>
                                            </div>
                                            <input type="text" name="ip_second" class="form-control" placeholder="192.168.91.47" data-inputmask="'alias': 'ip'" data-mask>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="box-body">
                                        <label>IP route</label><sup class="text-red"> *</sup>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-laptop"></i>
                                            </div>
                                            <input type="text" name="ip_route" class="form-control" placeholder="192.168.91.1" data-inputmask="'alias': 'ip'" data-mask>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="box-body">
                                        <div class="form-group {{ $errors->has('ip_net') ? 'has-error' : '' }}">
                                            <label>Internet</label><sup class="text-red"> *</sup>
                                            <select class="form-control select2" name="ip_net">
                                                    <option value="0">Yo`q</option>
                                                    <option value="1">Bor</option>
                                            </select>
                                            @if ($errors->has('ip_net'))
                                                <span class="text-red" role="alert">
                                        <strong>{{ $errors->first('ip_net') }}</strong>
                                    </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>Izoh <span class=""></span></label>
                                            <input type="text" name="ip_description" placeholder="Izoh" class="form-control">
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="box-body">
                                        <div class="form-group {{ $errors->has('ip_status') ? 'has-error' : '' }}">
                                            <label>Status</label><sup class="text-red"> *</sup>
                                            <select class="form-control select2" name="ip_status">
                                                <option value="1">Active</option>
                                                <option value="0">Passive</option>
                                            </select>
                                            @if ($errors->has('ip_status'))
                                                <span class="text-red" role="alert">
                                        <strong>{{ $errors->first('ip_status') }}</strong>
                                    </span>
                                            @endif
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <div class="box-footer">
                                <div class="pull-right">
                                    @php($url_c = '/ip-networks')
                                    @if(Auth::user()->branch_code == '09011')
                                        @php($url_c = '/admin/ip-networks')
                                    @endif
                                    <a href="{{url($url_c)}}" class="btn btn-default"><i class="fa fa-ban"></i> Bekor
                                        qilish
                                    </a>
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>
                                        Saqlash
                                    </button>
                                </div>
                            </div>
                            <!-- /.box-footer -->
                        </div>
                        <!-- /. box -->
                    </div>

                </form>
                <!-- /.col -->
            </div>
            <!-- /.row -->
            <!-- Select2 -->
            <script src="{{ asset('/admin-lte/plugins/select2/select2.full.min.js') }}"></script>
            <!-- InputMask -->
            <script src="{{ asset('/admin-lte/plugins/input-mask/jquery.inputmask.js') }}"></script>
            <script src="{{ asset('/admin-lte/plugins/input-mask/jquery.inputmask.extensions.js') }}"></script>

            <script type="text/javascript">

                $(function () {
                    //Initialize Select2 Elements
                    $(".select2").select2();

                    $("[data-mask]").inputmask();
                });


            </script>
        </section>
        <!-- /.content -->


@endsection

