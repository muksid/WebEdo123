<?php ?>
@extends('layouts.edo.dashboard')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Department Info</div>
                    <div class="row">
                        <div class="col-lg-12 text-center" style="margin-top:10px;margin-bottom: 10px;">
                        </div>
                    </div>

                    <div class="col-md-12">

                        <h3>{{ $model->id }}</h3>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection