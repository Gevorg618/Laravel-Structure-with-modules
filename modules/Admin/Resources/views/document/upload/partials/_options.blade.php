<div class="btn-group">
  <a href="#" data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">Actions <span class="caret"></span></a>
  <ul class="dropdown-menu pull-right" role="menu">
    <li><a href="{{ route('admin.document.upload.update_status', ['id' => $row->id]) }}">
        <i class="fa fa-eye"></i> Change Visible Status</a></li>
    <li><a href="{{ route('admin.document.upload.delete', ['id' => $row->id]) }}"
           onclick="return confirm('Are you sure you would like to proceed with this action?');">
        <i class="fa fa-trash"></i> Delete</a></li>
  </ul>
</div>