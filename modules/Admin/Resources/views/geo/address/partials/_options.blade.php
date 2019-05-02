<div class="btn-group">
    <a href="#" data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">Actions <span class="caret"></span></a>
    <ul class="dropdown-menu pull-right" role="menu">
        <li><a href="{{ route('admin.geo.address.refresh', ['id' => $row->id]) }}"><i class="fa fa-refresh"></i> Refresh</a></li>
        <li><a href="{{ route('admin.geo.address.delete', ['id' => $row->id]) }}"><i class="fa fa-trash"></i> Delete</a></li>
    </ul>
</div>