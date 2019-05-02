<div class="dropdown">
    <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">Options<span class="caret"></span></button>
    <ul class="dropdown-menu">
        @if($row->is_escalated_worked_today)
            <li>
                <a href='javascript:;' class='mark-as-worked' data-id="{{$row->id}}">Un-Mark As Worked Today</a>
            </li>
        @else
            <li>
                <a href='javascript:;' class='mark-as-worked' data-id="{{$row->id}}">Mark As Worked Today</a>
            </li>
        @endif
        <li role="separator" class="divider"></li>
        @if($row->unassigned_priority == 2)
            <li>
                <a href='javascript:;' class='mark-priority' data-priority="1" data-id="{{$row->id}}">Mark as Medium Priority</a>
            </li>
        @elseif($row->unassigned_priority == 1)
            <li>
                <a href='javascript:;' class='mark-priority' data-priority="2" data-id="{{$row->id}}">Mark as High Priority</a>
            </li>
        @else
            <li>
                <a href='javascript:;' class='mark-priority' data-priority="1" data-id="{{$row->id}}">Mark as Medium Priority</a>
            </li>
            <li>
                <a href='javascript:;' class='mark-priority' data-priority="2" data-id="{{$row->id}}">Mark as High Priority</a>
            </li>
        @endif
        @if($row->unassigned_priority)
            <li>
                <a href='javascript:;' class='mark-priority' data-priority="0" data-id="{{$row->id}}">Mark as None</a>
            </li>
        @endif
    </ul>
</div>
