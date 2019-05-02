<form id="add_event_form" class="form-horizontal" role="form">
    <input type='hidden' name='form_event_id' id='form_event_id' value="{{$row->id}}"/>
    <div class="row">
        <div class="col-md-11">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-error" id="event_error_msg" style="display:none;"></div>
                    <div class="alert alert-success" id="event_ok_msg" style="display:none;"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Event Title</label>
                        <div class="col-md-9">
                            <input type="text" name="event_title" value="{{$row->title}}" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Start Date</label>
                        <div class="col-md-9">
                            <input type="text" name="event_start_date" value="{{$row->start_date}}" readonly="readonly"
                                   class="form-control date-time-picker">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">All Day</label>
                        <div class="col-md-9">
                            <select name="all_day" id="all_day" class="form-control">
                                <option value="0" {{$row->all_day ? '' : 'selected="selected"'}}>No</option>
                                <option value="1" {{$row->all_day ? 'selected="selected"' : ''}}>Yes</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Private</label>
                        <div class="col-md-9">
                            <select name="event_private" id="event_private" class="form-control">
                                <option value="0" {{$row->is_private ? '' : 'selected="selected"'}}>No</option>
                                <option value="1" {{$row->is_private ? 'selected="selected"' : ''}}>Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">End Date</label>
                        <div class="col-md-9">
                            <input type="text" name="event_end_date" class="form-control date-time-picker"
                                   value="{{$row->end_date}}" readonly="readonly">
                        </div>
                    </div>
                    <div class="public_event_settings" style="display:none;">
                        <div class="form-group" style="margin-bottom: 2px;">
                            <label class="col-md-3
                            control-label">Calendar</label>
                            <div class="col-md-9">
                                <select name="event_calendar_id" id="event_calendar_id" class="form-control">
                                    @foreach($calendarData as $calendar)
                                        <option value="{{$calendar['key']}}"{{$calendar['key'] === $row->calendar['key'] ? 'selected="selected"' : ''}}>{{$calendar['title']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 14px;">
                            <label class="col-md-3 control-label">Visible To</label>
                            <div class="col-md-9">
                                <select name="users[]" id="users" class="bootstrap-multiselect form-control"
                                        multiple="multiple">
                                    @foreach($calendarAdminUserList as $key => $value)
                                        <option value="{{$key}}"
                                                {{isset($visibleUsers[$key]) ?
                                                'selected="selected"' : ''}}>{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="public_event_settings" style="display:none;">
                        <div class="alert alert-info">
                            <b>Event Calendar</b> When you add an event which is not a private event (only visible to
                            you) you must select a calendar to publish this event under.<br/>
                            <b>Visible To Users</b> A non-private event (Public event) can be viewed by all or some
                            users. If you do not select any users in the selection box it will be visible to all users.
                            If one or more users are selected it will be visible only to those who were selected.
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group" style="margin-bottom: 2px;">
                        <textarea name="event_content" id="event_content">
                            {!! $row->description !!}
                        </textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
