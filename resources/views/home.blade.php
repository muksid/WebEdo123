@extends('layouts.dashboard')
@section('content')
    <!-- fullCalendar 2.2.5-->
    <link rel="stylesheet" href="{{ asset('/admin-lte/plugins/fullcalendar/fullcalendar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/admin-lte/plugins/fullcalendar/fullcalendar.print.css') }}" media="print">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang('blade.home_page')
            <small>@lang('blade.home_control_panel')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> @lang('blade.webEdo')</a></li>
            <li class="active">@lang('blade.home_page')</li>
        </ol>

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>@lang('blade.error')</strong> @lang('blade.to_send_choose')<br><br>
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
    
    
    @if(Auth::id() == 1)

    @else
    <!-- Main content -->
    <section class="content">
        <!-- Main row -->
        <div class="row">
            
                <section class="col-lg-2 connectedSortable">
                    <div class="box box-warning">
                        <div class="box-header">
                        <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                        <h3 class="box-title">@lang('blade.warning_of_email')</h3>

                        <hr>
                        <p>
                            <span class="text-muted">@lang('blade.warning_email_text')</span>
                        </p>

                            <!-- /. tools -->
                        </div>
                        <div class="box-body">
                            
                        </div>
                    </div>
                    
                </section>

                
                <section class="col-lg-6 connectedSortable">
                    <div class="box box-info">
                        <div class="box-header">
                            <i class="fa fa-paper-plane"></i>
                            <h3 class="box-title">@lang('blade.email_to_chairman')</h3>
                        </div>
                        <div class="box-body">
                            <form action="{{ route('home-applies') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <input type="text" class="form-control" name="subject" placeholder="@lang('blade.subject')" required>
                                </div>
                                <div>
                                    <textarea id="editor1" class="textarea" placeholder="@lang('blade.text')" name="text" 
                                        style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" required>
                                        <br><br>
                                        <i>
                                            @lang('blade.with_respect'),<br>    
                                            {{Auth::user()->fname}} {{Auth::user()->lname}}
                                        </i>
                                    </textarea>
                                </div>
                                <div class="box-footer clearfix">
                                    <button type="submit" class="pull-right btn btn-info" id="sendEmail">@lang('blade.send')
                                        <i class="fa fa-arrow-circle-right"></i></button>
                                </div>

                            </form>
                        </div>
                        
                    </div>
                </section>
                <!-- right col -->

                <!-- Left col -->
                <section class="col-lg-4 connectedSortable">
                    <div class="box box-warning">
                        <div class="box-header">
                            <i class="fa fa-envelope"></i>
                            <h3 class="box-title">@lang('blade.my_letters')</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body" style="overflow-y: scroll; max-height: 80rem">
                            <ul class="list-group">
                                @foreach($models as $key => $model )
                                <li class="list-group-item openLetter" data-id="{{ $key }}">
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <span class="text-bold">
                                            {{ $key+1 }}. {{ $model->subject }}
                                            </span>
                                            @if(in_array('admin', json_decode(Auth::user()->roles)))
                                                <span class="text-light-blue pull-right"> {{ $model->user->substrUserName($model->user_id)??'' }}</span>
                                            @endif
                                        </div>
                                        
                                        <div class="col-sm-4">
                                            <i id="rotate-icon-{{ $key }}" class="fa fa-arrow-right pull-right" aria-hidden="true"></i>
                                            <span class="small pull-right">{{ \Carbon\Carbon::parse($model->created_at)->format('d-m-Y h:i:s') }}</span>
                                        </div>
                                    </div>

                                    <div id="card-show-{{ $key }}" class="card card-show" style="width: 100%;" hidden>
                                        <div class="card-body">
                                            <p class="card-text">{!! $model->text !!}</p>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                                

                            </ul>
                                @if(in_array('admin', json_decode(Auth::user()->roles)))
                                    {{ $models->links() }}
                                @endif
                        </div>
                        <!-- /.box-body -->
                      
                    </div>
                </section>
                <!-- /.Left col -->

        </div>
        <!-- /.row -->

    </section>
    <!-- /.content -->
    @endif






    <link href="{{ asset("/admin-lte/bootstrap/css/ionicons.min.css") }}" rel="stylesheet" >

    <link href="{{ asset("/admin-lte/bootstrap/css/font-awesome.min.css") }}" rel="stylesheet" >

    <script src="{{ asset("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>

    <script src="{{ asset("/admin-lte/dist/js/app.min.js") }}"></script>

    <!-- jQuery UI 1.11.4 -->
    <script src="/admin-lte/dist/js/jquery-ui.min.js"></script>
    <!-- Slimscroll -->
    <script src="/admin-lte/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="/admin-lte/plugins/fastclick/fastclick.js"></script>
    <!-- fullCalendar 2.2.5 -->
    <script src="/admin-lte/dist/js/calendar-moment.js"></script>
    <script src="/admin-lte/plugins/fullcalendar/fullcalendar.min.js"></script>
 
    <script src="{{ asset ("/ckeditor-basic/ckeditor.js") }}"></script>
    <script src="{{ asset ("/ckeditor/samples/js/sample.js") }}"></script>
    <script type="text/javascript">
        CKEDITOR.replace("editor1");
    </script>
    <script>

        $(function () {


            /* initialize the external events
                -----------------------------------------------------------------*/
            function ini_events(ele) {
                ele.each(function () {

                    // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                    // it doesn't need to have a start or end
                    var eventObject = {
                        title: $.trim($(this).text()) // use the element's text as the event title
                    };

                    // store the Event Object in the DOM element so we can get to it later
                    $(this).data('eventObject', eventObject);

                    // make the event draggable using jQuery UI
                    $(this).draggable({
                        zIndex: 1070,
                        revert: true, // will cause the event to go back to its
                        revertDuration: 0  //  original position after the drag
                    });

                });
            }

            ini_events($('#external-events div.external-event'));

            /* initialize the calendar
                -----------------------------------------------------------------*/
            //Date for the calendar events (dummy data)
            var date = new Date();
            var d = date.getDate(),
                m = date.getMonth(),
                y = date.getFullYear();
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                buttonText: {
                    today: 'Bugun',
                    month: 'Oy',
                    week: 'hafta',
                    day: 'kun'
                },

                //Random default events
                events: [
                    {
                        title: 'All Day Event',
                        start: new Date(y, m, 1),
                        backgroundColor: "#f56954", //red
                        borderColor: "#f56954" //red
                    },
                    {
                        title: 'Long Event',
                        start: new Date(y, m, d - 5),
                        end: new Date(y, m, d - 2),
                        backgroundColor: "#f39c12", //yellow
                        borderColor: "#f39c12" //yellow
                    },
                    {
                        title: 'Meeting',
                        start: new Date(y, m, d, 10, 30),
                        allDay: false,
                        backgroundColor: "#0073b7", //Blue
                        borderColor: "#0073b7" //Blue
                    },
                    {
                        title: 'Lunch',
                        start: new Date(y, m, d, 12, 0),
                        end: new Date(y, m, d, 14, 0),
                        allDay: false,
                        backgroundColor: "#00c0ef", //Info (aqua)
                        borderColor: "#00c0ef" //Info (aqua)
                    },
                    {
                        title: 'Birthday Party',
                        start: new Date(y, m, d + 1, 19, 0),
                        end: new Date(y, m, d + 1, 22, 30),
                        allDay: false,
                        backgroundColor: "#00a65a", //Success (green)
                        borderColor: "#00a65a" //Success (green)
                    },
                    {
                        title: 'Click for Google',
                        start: new Date(y, m, 28),
                        end: new Date(y, m, 29),
                        url: 'http://google.com/',
                        backgroundColor: "#3c8dbc", //Primary (light-blue)
                        borderColor: "#3c8dbc" //Primary (light-blue)
                    }
                ],
                editable: true,
                droppable: true, // this allows things to be dropped onto the calendar !!!
                drop: function (date, allDay) { // this function is called when something is dropped

                    // retrieve the dropped element's stored Event Object
                    var originalEventObject = $(this).data('eventObject');

                    // we need to copy it, so that multiple events don't have a reference to the same object
                    var copiedEventObject = $.extend({}, originalEventObject);

                    // assign it the date that was reported
                    copiedEventObject.start = date;
                    copiedEventObject.allDay = allDay;
                    copiedEventObject.backgroundColor = $(this).css("background-color");
                    copiedEventObject.borderColor = $(this).css("border-color");

                    // render the event on the calendar
                    // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                    $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

                    // is the "remove after drop" checkbox checked?
                    if ($('#drop-remove').is(':checked')) {
                        // if so, remove the element from the "Draggable Events" list
                        $(this).remove();
                    }

                }
            });

            /* ADDING EVENTS */
            var currColor = "#3c8dbc"; //Red by default
            //Color chooser button
            var colorChooser = $("#color-chooser-btn");
            $("#color-chooser > li > a").click(function (e) {
                e.preventDefault();
                //Save color
                currColor = $(this).css("color");
                //Add color effect to button
                $('#add-new-event').css({"background-color": currColor, "border-color": currColor});
            });
            $("#add-new-event").click(function (e) {
                e.preventDefault();
                //Get value and make sure it is not null
                var val = $("#new-event").val();
                if (val.length == 0) {
                    return;
                }

                //Create events
                var event = $("<div />");
                event.css({"background-color": currColor, "border-color": currColor, "color": "#fff"}).addClass("external-event");
                event.html(val);
                $('#external-events').prepend(event);

                //Add draggable funtionality
                ini_events(event);

                //Remove event from text input
                $("#new-event").val("");
            });

            let oldId
            
            $(".openLetter").click(function() {
                let id = $(this).data('id')
                if(oldId == id){

                    $('.fa-arrow-right').css({"transform": "rotate(0deg)"})
                    $('.card-show').slideUp()
                    oldId = 123456
                }else{

                    $('.fa-arrow-right').toggleClass("down");
                
                    $('.card-show').slideUp()
                    $('#card-show-'+id).slideDown()

                    $('.fa-arrow-right').css({"transform": "rotate(0deg)"})
                    $('#rotate-icon-'+id).css({"transform": "rotate(90deg)"})
                    oldId = id
                }

                
            
            // Toggle the flag
            // rotated = !rotated

            })


        })
    </script>


@endsection