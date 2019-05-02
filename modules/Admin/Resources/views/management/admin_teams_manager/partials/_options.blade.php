<a
    class="btn btn-info edit_btn"
    href="{{ route('admin.management.admin-teams-manager.edit', ['id' => $row->id]) }}"
>
    <i class="fa fa-edit"></i>
    Edit
</a>

{{ Form::open( ['route' => ['admin.management.admin-teams-manager.delete', 'id' => $row->id], 'class' => 'form-horizontal']) }}
    {{method_field('delete')}}
    <button type="submit" class="btn btn-danger delete_btn"><i class="fa fa-trash"></i> Delete</button>
{{ Form::close() }}
