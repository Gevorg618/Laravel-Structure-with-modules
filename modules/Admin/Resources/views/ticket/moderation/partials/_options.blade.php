<div class="btn-group">
    <button type="button" class="btn btn-primary btn-xs dropdown-toggle"
            data-toggle="dropdown">Options <span class="caret"></span></button>

    <ul class="dropdown-menu pull-right" role="menu">
        <li class="option">
            <a href="{{ route('admin.ticket.moderation.update', ['id' => $row->id]) }}">Update</a>
        </li>
        <li class="divider"></li>
        <li class="option">
            <a href="{{ route('admin.ticket.moderation.delete', ['id' => $row->id]) }}">Delete</a>
        </li>
    </ul>
</div>