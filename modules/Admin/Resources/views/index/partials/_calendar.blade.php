<div class="row">
    <div class="col-md-2">
        <button class="btn btn-mini btn-primary" id="add_calendar_event">Add Event</button>
        <br/><br/>
        <b>Calendars</b>
        <br/><br/>
        <div>
            @foreach($calendarData as $calendar)
                <div style="color: {{$calendar['textColor']}}; background-color: {{$calendar['color']}}; padding: 5px; border-radius: 5px; margin-bottom: 5px">{{$calendar['title']}}</div>
            @endforeach
        </div>
    </div>
    <div class="col-md-10">
        <div class="alert alert-info">
            Note! Click on an event to view all the information about the event. Click 'Add Event' to add a new event to
            your personal (Private) calendar or to a different calendar that can be viewed by some or all users.
        </div>
        <div id="event_calendar">

        </div>
    </div>
</div>

<div id="modal_add_event" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                            class="sr-only">Close</span></button>
                <h4 class="modal-title" id="modal_add_event_title"></h4>
            </div>
            <div class="modal-body" id="modal_add_event_content">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="do_add_calendar_event">Submit</button>
            </div>
        </div>
    </div>
</div>

<div id="modal_view_event" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                            class="sr-only">Close</span></button>
                <h4 class="modal-title" id="modal_view_event_title"></h4>
            </div>
            <div class="modal-body" id="modal_view_event_content">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <link rel="stylesheet" href="{{ masset('css/plugins/fullcalendar/fullcalendar.css') }}"/>
    <script src="{{masset('js/plugins/fullcalendar/fullcalendar.min.js') }}"></script>
    <script src="{{ masset('js/plugins/bootstrap-multiselect/js/bootstrap-multiselect.js') }}"></script>
    <script src="{{ masset('js/use-multiselect.js') }}"></script>
    <script src="{{ masset('js/index/calendar.js') }}"></script>
@endpush
