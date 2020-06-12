@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <div class="sticky-top mb-3">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Draggable Events</h4>
                            </div>
                            <div class="card-body">
                                <!-- the events -->
                                <div id="external-events">
                                    @if($eventsWithOutDate->isNotEmpty())
                                        @foreach($eventsWithOutDate as $item)
                                            <div class="external-event" style="background: {{ $item->background_color }}; color: white" data-id="{{ $item->id }}">{{ $item->title }}</div>
                                        @endforeach
                                    @endif
                                    <div class="checkbox">
                                        <label for="drop-remove">
                                            <input type="checkbox" id="drop-remove">
                                            remove after drop
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Create Event</h3>
                            </div>
                            <div class="card-body">
                                <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
                                    <!--<button type="button" id="color-chooser-btn" class="btn btn-info btn-block dropdown-toggle" data-toggle="dropdown">Color <span class="caret"></span></button>-->
                                    <ul class="fc-color-picker" id="color-chooser">
                                        <li><a class="text-primary" data-color="#1240AB" href="#"><i
                                                    class="fas fa-square"></i></a></li>
                                        <li><a class="text-warning" data-color="#FFFF73" href="#"><i
                                                    class="fas fa-square"></i></a></li>
                                        <li><a class="text-success" data-color="#008500" href="#"><i
                                                    class="fas fa-square"></i></a></li>
                                        <li><a class="text-danger" data-color="#A60000" href="#"><i
                                                    class="fas fa-square"></i></a></li>
                                        <li><a class="text-muted" data-color="#235B79" href="#"><i
                                                    class="fas fa-square"></i></a></li>
                                    </ul>
                                </div>
                                <!-- /btn-group -->
                                <div class="input-group">
                                    <input id="new-event" type="text" class="form-control" placeholder="Event Title">

                                    <div class="input-group-append">
                                        <button id="add-new-event" type="button" class="btn btn-primary">Add</button>
                                    </div>
                                    <!-- /btn-group -->
                                </div>
                                <!-- /input-group -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="card card-primary">
                        <div class="card-body p-0">
                            <!-- THE CALENDAR -->
                            <div id="calendar"></div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@stop

@section('css')
    <link href='{{ asset('packages/core/main.css') }}' rel='stylesheet'/>
    <link href='{{ asset('packages/daygrid/main.css') }}' rel='stylesheet'/>
@stop

@section('js')
    <script
        src="https://code.jquery.com/ui/1.12.0-rc.1/jquery-ui.min.js"
        integrity="sha256-mFypf4R+nyQVTrc8dBd0DKddGB5AedThU73sLmLWdc0="
        crossorigin="anonymous"></script>
    <script src='{{ asset('packages/core/main.js') }}'></script>
    <script src='{{ asset('packages/daygrid/main.js') }}'></script>
    <script src="{{ asset('packages/timegrid/main.min.js') }}"></script>
    <script src="{{ asset('packages/interaction/main.min.js') }}"></script>
    <script src="{{ asset('packages/bootstrap/main.min.js') }}"></script>
    <script>
        $(function () {

            /* initialize the external events
             -----------------------------------------------------------------*/
            function ini_events(ele) {
                ele.each(function () {

                    // create an Event Object (https://fullcalendar.io/docs/event-object)
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
                    })

                })
            }

            ini_events($('#external-events div.external-event'))

            /* initialize the calendar
             -----------------------------------------------------------------*/
            //Date for the calendar events (dummy data)
            var date = new Date();
            var d = date.getDate(),
                m = date.getMonth(),
                y = date.getFullYear();
            var userEventsList;

            var Calendar = FullCalendar.Calendar;
            var Draggable = FullCalendarInteraction.Draggable;

            var containerEl = document.getElementById('external-events');
            var checkbox = document.getElementById('drop-remove');
            var calendarEl = document.getElementById('calendar');

            // initialize the external events
            // -----------------------------------------------------------------

            new Draggable(containerEl, {
                itemSelector: '.external-event',
                eventData: function (eventEl) {
                    return {
                        id: $(eventEl).data('id'),
                        title: eventEl.innerText,
                        backgroundColor: window.getComputedStyle(eventEl, null).getPropertyValue('background-color'),
                        borderColor: window.getComputedStyle(eventEl, null).getPropertyValue('background-color'),
                        textColor: window.getComputedStyle(eventEl, null).getPropertyValue('color'),
                    };
                }
            });

            $.ajax({
                type: "GET",
                url: "{{ route('user.events') }}",
                async: false,
                success: function (response) {
                    userEventsList = response.events;
                },
                error: function (response) {
                    console.log(response);
                }
            });
            for (var key in userEventsList) {
                userEventsList[key].start = new Date(userEventsList[key].start_at);
                userEventsList[key].end = new Date(userEventsList[key].end_at);
                userEventsList[key].backgroundColor = userEventsList[key].background_color;
                userEventsList[key].borderColor = userEventsList[key].border_color;
                userEventsList[key].url = '/admin/event/'+userEventsList[key].id+'/edit';

            }

            var calendar = new Calendar(calendarEl, {
                plugins: ['bootstrap', 'interaction', 'dayGrid', 'timeGrid'],
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                'themeSystem': 'bootstrap',
                //Random default events
                events: userEventsList,
                editable: true,
                droppable: true, // this allows things to be dropped onto the calendar !!!
                eventDrop: function(info) {
                    console.log(info.event);
                    var obj = info.event;
                    saveEdit(obj.id, obj.start, obj.end);
                },
                drop: function (info) {
                    // is the "remove after drop" checkbox checked?
                    if (checkbox.checked) {
                        // if so, remove the element from the "Draggable Events" list
                        info.draggedEl.parentNode.removeChild(info.draggedEl);
                    }
                    saveEdit($(info.draggedEl).data('id'), info.date, info.date);
                }
            });
            calendar.setOption('locale', 'ru');
            calendar.render();
            // $('#calendar').fullCalendar()

            function saveEdit(id, start, end) {
                $.post(
                    "/api/event/update",
                    {
                        _token: "{{ csrf_token() }}",
                        _method: "PUT",
                        id: id,
                        start_at: new Date(start).toISOString(),
                        end_at: new Date(end).toISOString(),
                    }
                );
            }

            /* ADDING EVENTS */
            var currColor = '#3c8dbc'; //Red by default
            var hashColor = '';
            //Color chooser button
            $('#color-chooser > li > a').click(function (e) {
                e.preventDefault();
                //Save color
                currColor = $(this).css('color');
                hashColor = $(this).data("color");
                //Add color effect to button
                $('#add-new-event').css({
                    'background-color': currColor,
                    'border-color': currColor
                })
            });

            $('#add-new-event').click(function (e) {
                e.preventDefault();
                //Get value and make sure it is not null
                var val = $('#new-event').val();
                if (val.length == 0) {
                    return
                }
                var id = '';
                $.ajax({
                    type: "POST",
                    url: "{{ route('event.create') }}",
                    async: false,
                    data: {
                            _token: "{{ csrf_token() }}",
                            title: val,
                            color: hashColor,
                        },
                    success: function (response) {
                        id = response.id;
                    },
                    error: function (response) {
                        console.log(response);
                    }
                });

                //Create events
                var event = $('<div />');
                event.css({
                    'background-color': currColor,
                    'border-color': currColor,
                    'color': '#fff'
                }).addClass('external-event').attr('data-id', id);
                event.html(val);
                $('#external-events').prepend(event);

                //Add draggable funtionality
                ini_events(event);

                //Remove event from text input
                $('#new-event').val('')
            });

            function rgbToHex(r, g, b) {
                return "#" + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
            }
        })
    </script>
@stop
