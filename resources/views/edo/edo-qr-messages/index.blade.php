@extends('layouts.edo.dashboard')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang('blade.qr_documents')
            <small>{{ $models->count() }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Bosh sahifa</a></li>
            <li><a href="#">qr</a></li>
            <li class="active">qr jadvali</li>
        </ol>
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Xatolik!</strong> xatolik bor.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

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
                            <button type="button" class="btn btn-info closeModal" data-dismiss="modal"><i
                                        class="fa fa-check-circle"></i> Ok
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        @elseif($message = Session::get('deleted'))

            <div class="modal fade in text-danger" id="delModal" role="dialog" style="display: block">
                <div class="modal-dialog modal-sm">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header bg-danger">
                            <h4 class="modal-title">
                                @lang('blade.task') <i class="fa fa-trash"></i>
                            </h4>
                        </div>
                        <div class="modal-body">
                            <h5>{{ $message }}</h5>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger closeModal" data-dismiss="modal"><i
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
            <div class="col-xs-12">

                <div class="box">
                    <div class="box-header">
                        <div class="col-md-1">
                            <a href="{{ route('edo-qr-messages.create') }}" class="btn btn-primary">
                                <i class="fa fa-plus"></i> @lang('blade.create_doc')</a>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('blade.executors')</th>
                                <th>@lang('blade.doc_name')</th>
                                <th>@lang('blade.qr_number_d')</th>
                                <th>@lang('blade.reg_date')</th>
                                <th>@lang('blade.qr_signed_by')</th>
                                <th>@lang('blade.qr_signed_date')</th>
                                <th>@lang('blade.status')</th>
                                <th><i class="fa fa-pencil-square-o"></i></th>
                                <th><i class="fa fa-trash-o"></i></th>
                                <th>@lang('blade.date')</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1 ?>
                            @foreach ($models as $key => $model)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>
                                        {{ mb_substr($model->user->fname??'', 0 ,1).'.'.
                                           mb_substr($model->user->sname??'', 0 ,1).'.'.
                                           $model->user->lname??'' }}
                                        <sup class="text-muted">{{ $model->user->department->title }}</sup>
                                    </td>
                                    <td>
                                        <a href="{{ route('view-qr-message',
                                            ['name' => $model->id,
                                            'hash' => $model->message_hash]) }}">
                                            {{ $model->title }}
                                        </a>
                                    </td>
                                    <td>{{ $model->message_hash }}</td>
                                    <td class="text-center">
                                        <b>{{ $model->message_number }}</b><br>
                                        {{ \Carbon\Carbon::parse($model->message_date)->format('d.m.Y')}}
                                    </td>
                                    <td class="text-bold">
                                        {{ mb_substr($model->guide->fname??'', 0 ,1).'.'.
                                           mb_substr($model->guide->sname??'', 0 ,1).'.'.
                                           $model->guide->lname??'' }}
                                        <sup class="text-muted">{{ $model->guide->department->title }}</sup>
                                    </td>

                                    <td>
                                        @if(!empty($model->signature_date))
                                            {{ \Carbon\Carbon::parse($model->signature_date)->format('d-M-Y')  }}
                                        @else
                                            <span class="text-muted">(imzo yo`q)</span>
                                        @endif

                                    </td>
                                    <td>
                                        @if($model->status == 2)
                                            <small class="label bg-aqua-active">Imzolangan</small>
                                        @else
                                            <small class="label bg-red">yangi</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($model->status == 2)
                                            <a class="btn btn-xs btn-primary disabled" href="#">
                                                <span class="glyphicon glyphicon-pencil"></span>
                                            </a>
                                        @else
                                            <a class="btn btn-xs btn-primary"
                                               href="{{ route('edo-qr-messages.edit', $model->message_hash) }}">
                                                <span class="glyphicon glyphicon-pencil"></span>
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if($model->status == 2)

                                            <a href="javascript:;" data-toggle="modal"
                                               data-target="#DeleteModal" class="btn btn-xs btn-danger disabled">
                                                <span class="glyphicon glyphicon-trash"></span></a>
                                        @else

                                            <a href="javascript:;" data-toggle="modal"
                                               onclick="deleteUrl({{$model->id}})"
                                               data-target="#DeleteModal" class="btn btn-xs btn-danger">
                                                <span class="glyphicon glyphicon-trash"></span></a>
                                        @endif

                                    </td>
                                    <td style="min-width: 100px">
                                        {{ \Carbon\Carbon::parse($model->created_at)->format('d-M-Y')  }}<br>
                                        <span class="text-maroon"> ({{$model->created_at->diffForHumans()}})</span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->

                    <div id="DeleteModal" class="modal fade text-danger" role="dialog">
                        <div class="modal-dialog modal-sm">
                            <!-- Modal content-->
                            <form action="" id="deleteForm" method="post">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title text-center">O`chirishni tasdiqlash</h4>
                                    </div>
                                    <div class="modal-body">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <p class="text-center">Siz xatni o`chirmoqchimisiz?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <center>
                                            <button type="button" class="btn btn-success" data-dismiss="modal">Bekor
                                                qilish
                                            </button>
                                            <button type="submit" name="" class="btn btn-danger" data-dismiss="modal"
                                                    onclick="formSubmit()">Ha, O`chirish
                                            </button>
                                        </center>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->


        <!-- AdminLTE for demo purposes -->
        <script>
            $(function () {
                $("#example1").DataTable();
            });

            // close Modal
            $('.closeModal').click(function () {

                $('#myModal').hide();

            });

            // delete model
            function deleteUrl(id) {
                var id = id;
                var url = '{{ url("edo-qr-messages") }}/' + id;
                url = url.replace(':id', id);
                $("#deleteForm").attr('action', url);
            }

            function formSubmit() {
                $("#deleteForm").submit();
            }

            // close Modal
            $('.closeModal').click(function () {

                $('#delModal').hide();

            });

        </script>
    </section>
    <!-- /.content -->
@endsection
