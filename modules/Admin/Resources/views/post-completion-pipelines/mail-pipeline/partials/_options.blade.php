<div class="dropdown">
    <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">Options<span class="caret"></span></button>
    <ul class="dropdown-menu" style="left: -100px;">
        <li>
            <a href='javascript:;' class='view-mail-row' data-id="{{$row->id}}">View</a>
        </li>
        @if(!$row->sent_date && $row->is_ready)
            <li class="option">
                <a href="javascript:;" class="mark-sent" data-id="{{$row->id}}">Mark Sent</a>
            </li>
        @endif

        @if($row->tracking_number && !$row->date_delivered)
            <li class="option">
                <a href="javascript:;" class="edit-row-tracking-number" data-id="{{$row->id}}">Edit Tracking Number</a>
            </li>
        @endif
    </ul>
</div>
