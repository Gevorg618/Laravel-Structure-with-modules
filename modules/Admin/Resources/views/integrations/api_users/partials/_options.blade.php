<a class="btn btn-info buttons_style api_users_logs_button" data-id="{{$row->id}}" href="{{ route('admin.integrations.api-users.logs', ['id' => $row->id]) }}">Logs</a>
<a class="btn btn-info buttons_style api_users_edit_button" data-id="{{$row->id}}" href="{{ route('admin.integrations.api-users.edit', ['id' => $row->id]) }}"><i class="fa fa-edit"></i> Edit</a>
