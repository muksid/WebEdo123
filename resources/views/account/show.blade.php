@extends('layouts.dashboard')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1 class="text-center text-bold" style="color: white">
                    Online hisob raqam ochish
                    <small></small>
                </h1>
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
                    </div>
                @endif
            <!-- Display Validation Errors -->
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Xatolik!</strong> Ma`lumotlarni qaytadan tekshiring.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="box box-solid" style="max-height: 700px; overflow-y: scroll; opacity: 0.9">
                    <div class="box-header with-border">
                        <h3 class="box-title">Mijoz ma`lumotlari</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">

                        <!-- First row -->
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <strong><i class="fa fa-bank margin-r-5"></i> Tashkilot ma`lumotlari</strong>
                            <p class="text-muted">
                                <b>Filial: </b> {{ $model->accFilial->branch_code ?? 'null' }} {{ $model->accFilial->title ?? 'null' }}
                            </p>
                            <p class="text-muted">
                                <b>Nomi: </b> {{ $model->acc_name }} {{ $model->accType->title ?? 'null' }}
                            </p>
                            <p class="text-muted">
                                <b>INN: </b> {{ $model->acc_inn }}
                            </p>
                            <hr>
                        </div>
                        <!-- /.col -->
                        <div class="col-md-6 col-sm-6 col-xs-12">

                            <strong><i class="fa fa-user margin-r-5"></i> Rahbar ma`lumotlari</strong>
                            <p class="text-muted">
                                <b>FISH: </b> {{ $model->owner_lname.' '.$model->owner_fname.' '.$model->owner_sname }}
                            </p>
                            <p class="text-muted">
                                <b>Telefon: </b> {{ $model->owner_phone }}
                            </p>
                            <p class="text-muted">
                                <b>Ro`yhatdan o`tgan sanasi: </b> {{ $model->created_at }}
                            </p>

                            <hr>
                        </div>
                        <!-- /.col -->

                        <strong><i class="fa fa-pencil margin-r-5"></i> Ariza holati</strong>

                        <p>
                            <span class="label label-primary">Jarayonda</span>
                        </p>

                        <hr>
                        @if(count($model->files))
                            <strong><i class="fa fa-file margin-r-5"></i> Hujjat ilovalari </strong><br><br>

                            @foreach ($model->files as $file)
                                <i class="fa fa-file-pdf-o text-yellow"></i> {{ $file->file_name }} <a
                                        href="{{ route('load',['file'=>$file->file_hash]) }}" class="text-red"><i class="fa fa-cloud-download"></i> Yuklash</a><br><br>
                            @endforeach

                        @endif
                    </div>
                    @if(count($reply))
                        @foreach($reply as $key => $value)

                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <strong>
                                    @if($value->bank_user_id == 0)
                                        <i class="fa fa-user margin-r-5"></i> Men
                                    @else
                                        <i class="fa fa-user margin-r-5 text-green"></i> <span class="text-green">Bank hodimi</span>
                                    @endif
                                </strong>
                                <p>{{ $value->title }}</p>
                                @if(count($value->repFiles))
                                    <strong><i class="fa fa-file margin-r-5"></i> Hujjat ilovalari </strong><br><br>

                                    @foreach ($value->repFiles as $file)
                                        <i class="fa fa-file-pdf-o text-yellow"></i> {{ $file->file_name }} <a
                                                href="{{ route('load-rep',['file'=>$file->file_hash]) }}" class="text-red"><i class="fa fa-cloud-download"></i> Yuklash</a><br><br>
                                    @endforeach

                                @endif
                            </div>
                        @endforeach
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <form action="{{ route('reply.acc') }}" method="post" enctype="multipart/form-data" >
                                {{ csrf_field() }}
                                <div class="box-body">
                                    <label>Qisqa mazmuni</label><sup class="text-red"> *</sup>
                                    <div class="form-group">
                                        <textarea name="title" class="form-control" rows="3" required></textarea>
                                        <input name="account_id" value="{{$model->id}}" hidden />
                                        <input name="account_inn" value="{{$model->acc_inn}}" hidden />
                                        <input name="bank_user_id" value="0" hidden />
                                    </div>
                                    <div class="form-group">

                                        <div class="btn btn-default btn-file">
                                            <i class="fa fa-paperclip"></i> Faylni biriktirish
                                            <input type="file" name="rep_account_file[]" multiple>
                                        </div>
                                        <p class="help-block">Max. 32MB</p>

                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-reddit btn-submit-reply" type="submit">
                                            <i class="fa fa-envelope-o"></i> Xabar jo`natish
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif

                    <div class="box-footer">
                        <div class="pull-right">
                            <!-- oferta -->
                            <div class="form-group">
                                <a href="{{url('/')}}" class="btn btn-bitbucket"><i class="fa fa-backward"></i> Bosh sahifa</a>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

            </div>

        </section>
        <!-- /.content -->
    </div>

    <!-- jQuery 2.2.3 -->
    <script src="{{ asset ("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="{{ asset ("/admin-lte/bootstrap/js/bootstrap.min.js") }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset ("/admin-lte/dist/js/app.min.js") }}"></script>
@endsection
