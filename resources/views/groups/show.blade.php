<?php ?>
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Department Info</div>
                    <div class="row">
                        <div class="col-lg-12 text-center" style="margin-top:10px;margin-bottom: 10px;">
                            <a class="btn btn-success " href="{{ route('departments.index') }}"> Home page</a>
                        </div>
                    </div>

                    <div class="col-md-12">

                        <h3>{{ $department->title }}</h3>

                        <ul id="tree1">

                            @foreach($department->childs as $department)

                                <li>

                                    {{ $department->title }}

                                    @if(count($department->childs))

                                        @include('departments.departChild',['childs' => $department->childs])

                                    @endif

                                </li>

                            @endforeach

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection