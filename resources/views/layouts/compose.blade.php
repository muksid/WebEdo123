<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">

    <title>TuronBank | WebEDO</title>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 3.3.6 -->
    <link href="{{ asset("/admin-lte/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css">

    <!-- Font Awesome -->
    <link href="{{ asset("/admin-lte/bootstrap/css/font-awesome.min.css") }}" rel="stylesheet" type="text/css">

    <!-- Theme style -->
    <link href="{{ asset("/admin-lte/dist/css/AdminLTE.css") }}" rel="stylesheet" type="text/css">

    <link href="{{ asset("/admin-lte/dist/css/AdminLTE.min.css") }}" rel="stylesheet" type="text/css">

    <link href="{{ asset("/admin-lte/dist/css/skins/_all-skins.min.css") }}" rel="stylesheet" type="text/css">

    <link href="{{ asset("/css/main.css") }}" rel="stylesheet" type="text/css">

    <link href="{{ asset('css/treeview.css') }}" rel="stylesheet">

    <script src="{{ asset('js/treejquery.js') }}"></script>

</head>

<body class="skin-blue">

<!-- Header -->
@include('layouts.header')

<!-- Sidebar -->
@include('layouts.sidebar')

@yield('content')

<!-- bootstrap date picker this is mes term input-->
<link href="{{ asset("admin-lte/plugins/datepicker/datepicker3.css") }}" rel="stylesheet">

<!-- Select2 -->
<link href="{{ asset("admin-lte/plugins/select2/select2.min.css") }}" rel="stylesheet">

<!-- Theme style this is for select2 input -->
<link href="{{ asset("admin-lte/dist/css/AdminLTE.min.css") }}" rel="stylesheet">

<!-- Select2 -->
<script src="{{ asset("admin-lte/plugins/select2/select2.full.min.js") }}"></script>

<!-- bootstrap date picker this is for mes term -->
<script src="{{ asset("admin-lte/plugins/datepicker/bootstrap-datepicker.js") }}"></script>

<script src="{{ asset("/admin-lte/dist/js/app.min.js") }}"></script>

<!-- Bootstrap 3.3.6 button logOut -->
<script src="{{ asset ("admin-lte/bootstrap/js/bootstrap.min.js") }}"></script>

@include('layouts.footer')
