<div class="btn-group">
    <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown">
        Options <span class="caret"></span>
    </button>
    <ul class="dropdown-menu dropdown-menu-right" role="menu">
        @if (!$row->locked_by || ($row->locked_by && $row->locked_by == admin()->id))
            <li class="option option-open" data-id="{{ $row->id }}">
                <a href="{{ route('admin.ticket.manager.view', ['id' => $row->id, 'params' => $hashedQuery]) }}"
                        target="_blank">View Ticket</a></li>
            <li class="divider"></li>
        @endif

        @if ($row->closed_date)
            <li class="option">
                <a href="javascript:;" class="option-open"  data-id="{{ $row->id }}">Open Ticket</a>
            </li>
        @else
            <li class="option">
                <a href="javascript:;" class="option-close" data-id="{{ $row->id }}">Close Ticket</a>
            </li>
        @endif

        @if ($row->locked_date)
            <li class="option">
                <a href="javascript:;" class="option-unlock" data-id="{{ $row->id }}">Unlock Ticket</a>
            </li>
        @endif
    </ul>
</div>