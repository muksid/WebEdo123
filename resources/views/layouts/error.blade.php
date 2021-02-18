<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 06.01.2020
 * Time: 16:56
 */
?>
        <!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>TuronBank | EDO</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link href="{{ asset("admin-lte/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css">

    <!-- Font Awesome -->
    <link href="{{ asset("admin-lte/bootstrap/css/font-awesome.min.css") }}" rel="stylesheet" type="text/css">
    <!-- Ionicons -->
    <link href="{{ asset("admin-lte/bootstrap/css//ionicons.min.css") }}" rel="stylesheet" type="text/css">

    <link href="{{ asset("admin-lte/dist/css/AdminLTE.min.css") }}" rel="stylesheet" type="text/css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link href="{{ asset("admin-lte/dist/css/skins/_all-skins.min.css") }}" rel="stylesheet" type="text/css">
    <!-- iCheck -->
    <link href="{{ asset("admin-lte/plugins/iCheck/flat/blue.css") }}" rel="stylesheet" type="text/css">
    <!-- Morris chart -->
    <link href="{{ asset("admin-lte/plugins/morris/morris.css") }}" rel="stylesheet" type="text/css">
    <!-- jvectormap -->
    <link href="{{ asset("admin-lte/plugins/jvectormap/jquery-jvectormap-1.2.2.css") }}" rel="stylesheet" type="text/css">
    <!-- Date Picker -->
    <link href="{{ asset("admin-lte/plugins/datepicker/datepicker3.css") }}" rel="stylesheet" type="text/css">
    <!-- Daterange picker -->
    <link href="{{ asset("admin-lte/plugins/daterangepicker/daterangepicker.css") }}" rel="stylesheet" type="text/css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link href="{{ asset("admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css") }}" rel="stylesheet" type="text/css">
    <!-- DataTables -->
    <link href="{{ asset("admin-lte/plugins/datatables/dataTables.bootstrap.css") }}" rel="stylesheet" type="text/css">


</head>
<body class="skin-blue">
<div class="wrapper">

    <!-- Header -->
@include('layouts.error_header')
<!-- Sidebar -->
@include('layouts.error_sidebar')

<!-- Sidebar -->
    {{--@include('layouts.sidebar')--}}

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        @yield('content')
    </div>
    <!-- /.content-wrapper -->

    @include('layouts.footer')

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 2.2.3 -->
<script src="{{ asset ("admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.6 -->
<script src="{{ asset ("admin-lte/bootstrap/js/bootstrap.min.js") }}"></script>
<!-- Morris.js charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="{{ asset ("admin-lte/plugins/morris/morris.min.js") }}"></script>
<!-- Sparkline -->
<script src="{{ asset ("admin-lte/plugins/sparkline/jquery.sparkline.min.js") }}"></script>
<!-- jvectormap -->
<script src="{{ asset ("admin-lte/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js") }}"></script>
<script src="{{ asset ("admin-lte/plugins/jvectormap/jquery-jvectormap-world-mill-en.js") }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset ("admin-lte/plugins/knob/jquery.knob.js") }}"></script>
<!-- daterangepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="{{ asset ("admin-lte/plugins/daterangepicker/daterangepicker.js") }}"></script>
<!-- datepicker -->
<script src="{{ asset ("admin-lte/plugins/datepicker/bootstrap-datepicker.js") }}"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{ asset ("admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js") }}"></script>
<!-- Slimscroll -->
<script src="{{ asset ("admin-lte/plugins/slimScroll/jquery.slimscroll.min.js") }}"></script>
<!-- FastClick -->
<script src="{{ asset ("admin-lte/plugins/fastclick/fastclick.js") }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset ("admin-lte/dist/js/app.min.js") }}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset ("admin-lte/dist/js/pages/dashboard.js") }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset ("admin-lte/dist/js/demo.js") }}"></script>

    <!-- DataTables -->
<script src="{{ asset ("admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>
<script src="{{ asset ("admin-lte/plugins/datatables/dataTables.bootstrap.min.js") }}"></script>


</body>
</html>