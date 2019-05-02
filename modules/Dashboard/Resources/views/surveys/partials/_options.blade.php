<div class="btn-group">
    <a href="#" data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">Actions <span class="caret"></span></a>
    <ul class="dropdown-menu pull-right" role="menu">
        <li><a href="{{ route('admin.management.surveys.edit', ['id' => $row->id]) }}"><i class="fa fa-edit"></i> Edit</a></li>
        <li><a href="{{ route('admin.management.surveys.delete', ['id' => $row->id]) }}" onclick="return confirm('Are you sure you would like to proceed with this action?');"><i class="fa fa-trash"></i> Delete</a></li>
    </ul>

</div>
<div class="btn-group">
    <a class="btn btn-success btn-xs" href="{{ route('admin.management.surveys.show.questions', ['id' => $row->id]) }}"><i class="fa fa-search"></i> View</a>
</div>
<div class="btn-group">
    <a class="btn btn-success btn-xs" href="{{ route('admin.management.surveys.questions.create', ['id' => $row->id]) }}"><i class="fa fa-plus"></i> Add Question</a>
</div>