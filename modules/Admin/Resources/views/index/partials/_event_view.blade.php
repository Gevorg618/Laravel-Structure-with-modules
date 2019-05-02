<div class="row">
    <div class="col-md-12">

        <div class="row">
            <div class="col-md-2">
                Start Date
            </div>
            <div class="col-md-4">
                <b>{{$row->start_date}}</b>
            </div>
            <div class="col-md-2">
                End Date
            </div>
            <div class="col-md-4">
                <b>{{$row->end_date}}</b>
            </div>
            <div class="col-md-2">
                All Day
            </div>
            <div class="col-md-4">
                <b>{{$row->all_day ? 'Yes' : 'No'}}</b>
            </div>
            <div class="col-md-2">
                Calendar
            </div>
            <div class="col-md-4">
                <div style="color:{{$row->calendar['textColor']}}; background-color:{{$row->calendar['color']}};padding:5px;border-radius:5px;margin-bottom:5px;">{{$row->calendar['title']}}</div>
            </div>
            <div class="col-md-2">
                Created By
            </div>
            <div class="col-md-4">
                <b>{{$userName}}</b>
            </div>
        </div>
        @if($row->created_by == getUserId() || isAdmin())
            <br />
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-mini btn-default" id="edit_event" data-id="{{$row->id}}">Edit Event</button>
                    <button class="btn btn-mini btn-danger" id="delete_event" data-id="{{$row->id}}">Delete Event</button>
                </div>
            </div>
        @endif
        @if(!$users->isEmpty())
            <div class="row">
                <hr />
                <div class="col-md-2">
                    Visible To:
                </div>
                <div class="col-md-10">
                    @foreach($users as $user)
                    <i>{{getUserFullNameById($user->user_id)}}</i>
                    @endforeach
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <hr />
                {!! $row->description !!}
            </div>
        </div>
    </div>
</div>
