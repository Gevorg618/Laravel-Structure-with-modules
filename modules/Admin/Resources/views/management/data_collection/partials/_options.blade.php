<div class="btn-group">
    <button type="button" class="btn btn-primary btn-xs dropdown-toggle"
            data-toggle="dropdown">Options <span class="caret"></span></button>

    <ul class="dropdown-menu pull-right" role="menu">
        <li class="option">
            <a href="{{ route('admin.qc.collection.edit', ['id' => $row->id]) }}">Update</a>
        </li>
        <li class="divider"></li>
        <li class="option">
            <a href="{{ route('admin.qc.collection.delete', ['id' => $row->id]) }}" onclick="return confirm('Are you sure you want to delete?');">Delete</a>
        </li>
    </ul>
</div>