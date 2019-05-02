<div class="btn-group">
    <a href="#" data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">Actions <span class="caret"></span></a>
    <ul class="dropdown-menu pull-right" role="menu">
        <li><a href="{{ route('admin.tools.custom-pages-manager.edit',$customPage->id) }}"><i class="fa fa-edit"></i> Edit</a></li>
        <li><a href="{{ route('admin.tools.custom-pages-manager.view', $customPage->id) }}" target="_blank"><i class="fa fa-eye"></i> View</a></li>
    </ul>
</div>