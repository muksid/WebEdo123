@extends('layouts.table')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang('blade.deleted_messages') <span class="text-red">@lang('blade.for_last_3_months')!</span>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/home"><i class="fa fa-dashboard"></i>@lang('blade.home_page')</a></li>
            <li><a href="#">@lang('blade.messages')</a></li>
            <li class="active">@lang('blade.deleted_messages')</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box box-default">
                    <!-- /.box-header -->
                    <div class="box-body">
                            <table id="example1" class="table table-hover table-striped"
                                   style="color: #999; text-decoration: line-through;">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th><i class="fa fa-bank"></i> @lang('blade.branch')</th>
                                    <th><i class="fa fa-user"></i> @lang('blade.from_whom')</th>
                                    <th><i class="fa fa-link"></i> @lang('blade.position')</th>
                                    <th><i class="fa fa-text-height"></i> @lang('blade.subject')</th>
                                    <th><i class="fa fa-hourglass-start"></i> @lang('blade.deadline')</th>
                                    <th><i class="fa fa-tag"></i> @lang('blade.type_message')</th>
                                    <th><i class="fa fa-clock-o"></i> @lang('blade.received_date')</th>
                                    <th><i class="fa fa-trash"></i> @lang('blade.deleted_date')</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i = 1; ?>
                                @foreach ($deleted as $key => $value)
                                    <?php $color = 'style="color: red"'; ?>
                                    @if($value->mes_term == 0)
                                        <?php $color = ''; ?>
                                    @endif
                                    <tr <?php echo $color ?>>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $value->branch }}</td>
                                        <td><a href="{{ route('messages.show',
                                            ['mes_gen' => $value->mes_gen,
                                            'id' => $value->message_id]) }}">
                                                {{ $value->lname .' '. $value->fname }}</a>
                                        </td>
                                        <td style="font-size: 12px">{{ $value->dep_name.' '.$value->job_title }}</td>
                                        <td>@if (strlen($value->subject) > 100)
                                                {{substr($value->subject, 0, 90) . '...' }}
                                            @else
                                                {{ $value->subject }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($value->mes_term == 0)
                                                @lang('blade.no_deadline')
                                            @else
                                                <i class="fa fa-clock-o"></i> {{$value->mes_term}}
                                            @endif
                                        </td>
                                        <td>{{ $value->title }}</td>
                                        <td>{{ $value->created_at }}</td>
                                        <td>{{ $value->deleted }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <script>

            $(function () {
                $("#example1").DataTable();
            });

        </script>

        <!-- jQuery 2.2.3 -->
        <script src="{{ asset ("admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

        <!-- DataTables -->
        <script src="{{ asset ("admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>

        <script src="{{ asset ("admin-lte/plugins/datatables/dataTables.bootstrap.min.js") }}"></script>

        <!-- AdminLTE App -->
        <script src="{{ asset("/admin-lte/dist/js/app.min.js") }}"></script>

    </section>
    <!-- /.content -->
@endsection
