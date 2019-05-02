<div class="btn-group">
  <a href="#" data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">Actions <span class="caret"></span></a>
  <ul class="dropdown-menu pull-right" role="menu">
    <li><a href="{{ route('admin.document.user.types.create', ['id' => $row->id]) }}"><i class="fa fa-edit"></i> Edit</a></li>
    <li><a href="{{ route('admin.document.user.types.delete', ['id' => $row->id]) }}"><i class="fa fa-trash"></i> Delete</a></li>
  </ul>
</div>