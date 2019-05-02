<link href="{{ masset('css/management/admin_groups/options.css') }}" rel="stylesheet" />
<a
    class="btn btn-info buttons_style admin_groups_edit_button"
    href="{{ route('admin.management.admin-groups.edit', ['id' => $row->id]) }}"
>
    <i class="fa fa-edit"></i>
    Edit
</a>

@if(!$row->is_protected)
    <button
        type="button"
        class="btn btn-danger buttons_style admin_groups_delete_button"
        data-id="{{$row->id}}"
        data-toggle="modal"
        data-target="#confirm_delete"
    >
        <i class="fa fa-trash"></i>
        Delete
    </button>
@endif
