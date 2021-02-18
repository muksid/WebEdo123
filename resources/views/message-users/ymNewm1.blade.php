<?php ?>
@extends('layouts.table')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            All message {{ $all_count }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="/home"><i class="fa fa-dashboard"></i> @lang('blade.home_page')</a></li>
            <li><a href="#">@lang('blade.messages')</a></li>
            <li class="active">all message</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box">
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th><i class="fa fa-bank"></i> @lang('blade.branch')</th>
                                <th><i class="fa fa-user"></i> @lang('blade.from_whom')</th>
                                <th><i class="fa fa-text-height"></i> @lang('blade.subject')</th>
                                <th><i class="fa fa-hourglass-start"></i> @lang('blade.deadline')</th>
                                <th><i class="fa fa-tag"></i> @lang('blade.type_message')</th>
                                <th><i class="fa fa-paperclip"></i> @lang('blade.file')</th>
                                <th><i class="fa fa-clock-o"></i> @lang('blade.received_date')</th>
                                <th><i class="fa fa-link"></i></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1;?>
                            @foreach ($all_message as $key => $value)
                                @if($value->mes_term == 0)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $value->branch_code }}</td>
                                        <td><a href="{{url('view_my', ['mes_gen' => $value->mes_gen])}}">
                                                {{ $value->lname }} {{ $value->fname }}</a></td>
                                        <td>{{ $value->subject }}</td>
                                        @if($value->mes_term == 0)
                                            <td>Muddat yo`q</td>
                                        @else
                                            <td><i class="fa fa-clock-o"></i> {{$value->mes_term}}</td>
                                        @endif
                                        <td>{{ $value->title }}</td>
                                        <td></td>
                                        <td>{{ $value->created_at }}</td>
                                        <td>
                                            <a href="{{url('view_my', ['mes_gen' => $value->mes_gen])}}">
                                                <i class="fa fa-eye"></i></a>
                                        </td>
                                    </tr>
                                @else
                                    <tr style="color: red">
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $value->branch_code }}</td>
                                        <td><a href="{{url('view_my', ['mes_gen' => $value->mes_gen])}}">
                                                {{ $value->lname }} {{ $value->fname }}</a></td>
                                        <td>{{ $value->subject }}</td>
                                        @if($value->mes_term == 0)
                                            <td>Muddat yo`q</td>
                                        @else
                                            <td><i class="fa fa-clock-o"></i> {{$value->mes_term}}</td>
                                        @endif
                                        <td>{{ $value->title }}</td>
                                        <td></td>
                                        <td>{{ $value->created_at }}</td>
                                        <td>
                                            <a href="{{url('view_my', ['mes_gen' => $value->mes_gen])}}">
                                                <i class="fa fa-eye"></i></a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                        {{ $all_message->links() }}
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
    <!-- jQuery 2.2.3 -->
    <script src="admin-lte/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="admin-lte/bootstrap/js/bootstrap.min.js"></script>
    <!-- DataTables -->
    <script src="admin-lte/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="admin-lte/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <!-- AdminLTE App -->
    <script src="admin-lte/dist/js/app.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script>
        $(function () {
            $("#example1").DataTable();
        });
    </script>
@endsection