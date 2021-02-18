@extends('layouts.edo.dashboard')

@section('content')

    <!-- TRANSLATED -->

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Sent to Change
            <small>@lang('blade.groups_table')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i>@lang('blade.home_page') </a></li>
            <li><a href="#">@lang('blade.doc')</a></li>
            <li class="active">@lang('blade.groups_table')</li>
        </ol>

        <!-- Message Succes -->
        @if ($message = Session::get('success'))
            <div class="modal fade in" id="myModal" role="dialog" style="display: block">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header bg-aqua-active">
                            <h4 class="modal-title">
                                @lang('blade.task')<i class="fa fa-check-circle"></i>
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
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box">

                    <div class="box-body">
                        <form action="{{route('g-tasks-sent-change')}}" method="POST" role="search">
                            {{ csrf_field() }}
                            <div class="row">
                                {{--<div class="col-md-2">
                                    <div class="form-group">
                                        <select name="j" class="form-control" style="width: 100%;">
                                            @if($j == '')
                                                <option selected="selected" value="">Journalni tanlang</option>
                                            @else
                                                @php
                                                    $journal = \App\EdoJournals::where('id', $j)->first();
                                                @endphp
                                                <option selected="selected" value="{{$journal->id}}">{{$journal->title }}</option>
                                            @endif
                                            @foreach($journals as $key => $value)
                                                <option value="{{$value->id}}">{{ $value->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>--}}
                                {{--<div class="col-md-2">
                                    <div class="form-group">
                                        <select name="u" class="form-control select2" style="width: 100%;">
                                            --}}{{-- @if($u == '')
                                                 <option selected="selected" value="">@lang('blade.from_whom')</option>
                                             @else
                                                 @php
                                                     $user = \App\User::find($u);
                                                 @endphp
                                                 <option selected="selected" value="{{$user->id}}">{{$user->lname .' '. $user->fname}}</option>
                                             @endif
                                             @foreach($users as $key => $value)
                                                 <option value="{{$value->user->id}}">{{ $value->user->lname.' '.$value->user->fname }}</option>
                                             @endforeach--}}{{--
                                        </select>
                                    </div>
                                </div>--}}
                                <div class="col-md-1">
                                    <div class="form-group has-success">
                                        <input type="number" class="form-control" name="r" value="{{$r}}"
                                               placeholder="@lang('blade.reg')">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group has-success">
                                        <input type="text" class="form-control" name="t" value="{{$t}}"
                                               placeholder="@lang('blade.sender_organization')">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-default" onclick="location.href='g-tasks-sent-change'"><i class="fa fa-refresh"></i> @lang('blade.reset')</button>
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> @lang('blade.search')</button>
                                    </div>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </form>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        @lang('blade.overall')<b>{{': '. $models->total()}}</b>
                        <table class="table table-hover table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('blade.receivers')</th>
                                <th>@lang('blade.sender_organization')</th>
                                <th>@lang('blade.reg_date')</th>
                                <th>@lang('blade.send_date')</th>
                                <th>@lang('blade.deadline')</th>
                                <th>@lang('blade.status')</th>
                                <th>@lang('blade.purpose')</th>
                            </tr>
                            </thead>
                            <tbody>
                           <?php $i = 1; $count = 0; $count2 = 0 ?>
                            @foreach ($models as $key => $model)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td class="text-maroon" style="min-width: 180px">
                                        @foreach($model->perfUsers as $key => $user)

                                                @php($userName = \App\User::find($user->to_user_id ?? 'null'))

                                            @if($key < 4  && $user->sort == null)

                                                {{ $userName->substrUserName($user->to_user_id)  }}
                                                
                                                @if($user->is_read == 1)
                                                    @if($user->sub_status == 1)
                                                        <i class="fa fa-hourglass-2 text-blue"></i>
                                                    @elseif($user->sub_status == 2)
                                                        <i id="div1"
                                                            class="fa fa-envelope-o text-aqua text-bold"></i>
                                                    @elseif($user->sub_status == 3)
                                                        <i id="div1"
                                                            class="fa fa-file-archive-o text-bold"></i>
                                                    @else
                                                        <i class="fa fa-check-square text-primary"></i>
                                                    @endif
                                                    
                                                
                                                @else
                                                    <i class="fa fa-check text-red"></i>
                                                @endif
                                                <br/>
                                            @elseif($user->sort == 1)
                                                @if(!$count++)
                                                    {!! 'Барча Дир. Деп.<br>' !!}
                                                @endif
                                            @elseif($user->sort == 2)
                                                @if(!$count2++)
                                                    {!! 'Барча Филиал Бош. <br>' !!}
                                                @endif
                                            @endif
                                            @if($key == sizeof($model->perfUsers)-1)
                                                @php($count = 0) 
                                                @php($count2 = 0)
                                            @endif
                                            

                                        @endforeach
                                        @if(count($model->perfUsers) > 4)
                                            <span class="text-primary text-bold">+{{count($model->perfUsers)-4}}</span>
                                        @endif

                                    </td>
                                    <td>
                                        <a href="{{ url('edo/edit-g-task-change', ['slug' => $model->message->message_hash]) }}">
                                            {!! \Illuminate\Support\Str::words($model->message->from_name, 5, '...'); !!}
                                        </a>
                                    </td>
                                    <td class="text-sm text-center">
                                        <b>{{ $model->message->in_number??''}}</b><br>
                                        {{ \Carbon\Carbon::parse($model->message->in_date)->format('d.m.Y')}}
                                    </td>
                                    <td>{{ $model->updated_at->format('d M. Y H:i') }}</td>
                                    <td>
                                        @if(!empty($model->taskText->term_date))
                                            {{ \Carbon\Carbon::parse($model->taskText->term_date??'')->format('j F,Y') }}
                                        @else
                                            <i class="text-muted text-sm">(Muddat yo`q)</i>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($model->status)
                                            @case(2)
                                            <span class="label label-primary">@lang('blade.in_execution')</span>
                                            @break
                                            @case(3)
                                            <span class="label label-success">@lang('blade.closed')</span>
                                            @break
                                            @default
                                            @lang('blade.not_detected')
                                        @endswitch
                                    </td>
                                    <td>
                                        @if(!empty($model->taskText->controlType->title))
                                            @if($model->taskText->controlType->type_message_code == 'control')
                                                <span class="text-maroon text-sm"><i class="fa fa-check-circle"></i> {{ $model->taskText->controlType->title }}</span>
                                            @else
                                                <i class="text-muted text-sm">{{ $model->taskText->controlType->title??'' }}</i>
                                            @endif
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <span class="paginate">{{ $models->links() }}</span>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- jQuery 2.2.3 -->
        <script src="{{ asset ("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

        <script>

            // close Modal
            $('.closeModal').click(function () {

                $('#myModal').hide();

            });
        </script>
    </section>
    <!-- /.content -->
@endsection
