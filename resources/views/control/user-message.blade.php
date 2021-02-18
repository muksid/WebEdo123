@extends('layouts.table')

@section('content')
    <link href="{{ asset('css/treeview.css') }}" rel="stylesheet">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    <h1>
            Control User Messages &#8213; Count: <b style="color:red">{{ $users->total() }}</b>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.home_page')</a></li>
            <li><a href="#">@lang('blade.messages')</a></li>
            <li class="active">@lang('blade.sidebar_control')</li>
        </ol>

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>@lang('blade.error')</strong> @lang('blade.exist').<br><br>
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
            <div class="col-xs-12">

                <div class="box box-danger">
                    <div class="box-header with-boreder">
<!-- /////////////////////////////////////   HEADER   ///////////////////////////////////////////// -->
                    <div class="box-body">
                        <form action="{{route('search-owner-of-message')}}" method="POST" role="search">
                            {{ csrf_field() }}
                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <select name="f" class="form-control select2" style="width: 100%;">
                                            @if($f =='')
                                                <option selected="selected" value="{{$f}}"> Select Branch </option>
                                            @else
                                                <option selected="selected" value="{{$f}}">{{ $f.' - '.$f_title->title }}</option>
                                            @endif
                                            @foreach($filials as $key => $value)
                                                <option value="{{$value->branch_code}}">{{$value->branch_code . " - ". $value->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="q" value="{{$q}}" placeholder="Name or Username">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <select name="s" class="form-control" style="width: 100%;">
                                                @if($s == '')
                                                    <option selected="selected" value="">All *</option>
                                                    @elseif($s == 1)
                                                <option selected="selected" value="1">Active *</option>
                                                    @elseif($s == 0)
                                                <option selected="selected" value="0">Passive *</option>
                                                    @elseif($s == 2)
                                                <option selected="selected" value="2">Deleted *</option>
                                                    @endif
                                            <option value="">All</option>
                                            <option value="1">Active</option>
                                            <option value="0">Passive</option>
                                            <option value="2">Deleted</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Submit search form  -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-default" onclick="location.href='/search-owner-of-message';"><i class="fa fa-refresh"></i> @lang('blade.reset')</button>
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> @lang('blade.search')</button>
                                    </div>
                                </div>

                            </div>
                            <!-- /.row -->
                        </form>
                    </div>
<!-- ////////////////////////////////////   Header END   ////////////////////////////////////////////// -->
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding mailbox-messages">
                        <table id="" class="table table-hover table-bordered table-striped">
                            <thead >
                                
                                <tr>
                                    <th>#</th>
                                    <th><i class=""></i>                ID          </th>
                                    <th><i class="fa fa-user"></i>      Username    </th>
                                    <th><i class=""></i>                Branch Code </th>
                                    <th><i class=""></i>                Fullname    </th>
                                    <th><i class=""></i>                Job title   </th>
                                    <th><i class="fa fa-upload"></i>    Sent        </th>
                                    <th><i class="fa fa-download"></i>  Received    </th>
                                    <th><i class=""></i>                Status      </th>
                                    <th><i class="fa fa-hourglass"></i> Created at  </th>

                                </tr>
                            </thead>
                            
                            <tbody>
                                @foreach ($users as $key => $value)
                                    <tr>
                                    <td>{{ $users->firstItem()+$key }}</td>

                                        <td>{{ $value->id                   }}</td>
                                        <td>{{ $value->username             }}</td>
                                        <td>{{ $value->branch_code          }}</td>
                                        <td>
                                            <a href="{{ route('/sending-users-id', ['id' => $value->id]) }}">
                                            <b>{{ $value->lname??'Deleted User' }} {{$value->fname??''}} {{$value->sname??''}} </b>
                                            <i style="font-size: 12px;color: #31708f;">{{$value->job_title??''}}</i> 
                                            </a>
                                        </td>
                                        
                                        <td>{{ $value->job_title            }}</td>
                                        <td> -- </td>
                                        <td> -- </td>
                                        <td>
                                            @switch($value->status)
                                                @case(0)
                                                    <span class="label label-warning">passive</span>
                                                @break
                                                @case(1)
                                                    <span class="label label-success">active</span>
                                                @break
                                                @case(2)
                                                    <span class="label label-danger">deleted</span>
                                                @break
                                                @default
                                                    <span class="label label-default">unknown</span>
                                            @endswitch
                                        </td>
                                        <td>{{ $value->created_at       }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <span class="paginate"> {{ $users->links() }}  </span>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
    </section>

    <!-- /.content -->
    <!-- jQuery 2.2.3 -->
    <script src="{{ asset ("admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="admin-lte/bootstrap/js/bootstrap.min.js"></script>
    <!-- DataTables -->
    <script src="admin-lte/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="admin-lte/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <!-- SlimScroll -->
    <script src="admin-lte/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="admin-lte/plugins/fastclick/fastclick.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="admin-lte/dist/js/demo.js"></script>
    <script src="/admin-lte/plugins/select2/select2.full.min.js"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset("/admin-lte/dist/js/app.min.js") }}"></script>
    <!-- iCheck -->
    <script src="{{ asset("/admin-lte/plugins/iCheck/icheck.min.js") }}"></script>
    


@endsection
