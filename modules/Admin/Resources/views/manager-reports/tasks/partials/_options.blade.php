<div class="btn-group">
    <a href="#" data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">Actions <span class="caret"></span></a>
    <ul class="dropdown-menu pull-right" role="menu">
    	<li><a href="#"><i class="fa fa-play"></i> Run</a></li>
        <li><a href="{{ route('admin.reports.tasks.edit', $task->id) }}"><i class="fa fa-edit"></i> Edit</a></li>
        <li><a href="{{ route('admin.reports.tasks.destroy', $task->id) }}"><i class="fa fa-trash"></i> Delete</a></li>
    </ul>
</div>