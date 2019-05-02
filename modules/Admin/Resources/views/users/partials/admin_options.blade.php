<div class="row">
    <div class="span8">
        <h4 class="text-error">Admin Privileges </h4>
        <div class="control-group" style="margin-bottom: 0px;">
            <label>Admin User Type</label>
            @if(checkPermission($adminPermissionCategory, 'can_edit_admin_team'))
                {!! Form::select('admin_priv', $adminTypes, $user->admin_priv, ['class' => 'form-control']) !!}
            @else
                <p style="margin-top: 10px;">{{ $adminTypes[$user->admin_priv] ?? 'N/A' }}</p>
                <input type='hidden' name='admin_priv' id='admin_priv' value='{{ $user->admin_priv }}'/>
            @endif

        </div>
        <div class="control-group" style="margin-bottom: 0px;">
            <label>Admin Group</label>
            @if(checkPermission($adminPermissionCategory, 'can_edit_admin_group'))
                {!! Form::select('admin_group', $adminGroupsList, $user->admin_group, ['class' => 'form-control', 'placeholder' => 'Choose admin group']) !!}
            @else
                <p style="margin-top: 10px;">{{ $formattedAdminGroupName }}</p>
                <input type='hidden' name='admin_group' id='admin_group' value='{{ $user->admin_group }}'/>
            @endif
        </div>

        <div class="control-group" style="margin-bottom: 0px;">
            <label>Show In Assignment</label>
            {!! Form::select('show_in_assign', $yesNo, $user->show_in_assign) !!}
        </div>

        <h4 class="text-error">Permissions
            Set @if(checkPermission($adminPermissionCategory, 'can_edit_user_permissions'))
                <button class="btn btn-mini btn btn-danger reset-user-permissions" type="button">Reset User
                    Permissions
                </button>@endif</h4>
        <table width="100%" id="permissions_group">
            @foreach($perms as $key => $permGroup)
                @foreach($permGroup['groups'] as $group)
                    <tr>
                        <th>{{ $permGroup['title'] }} - {{ $group['header'] }}</th>
                        <th>Allowed</th>
                    </tr>
                    @foreach($group['items'] as $item)
                        <tr>
                            <td>{{ $item['title'] }}</td>
                            <td>{{ checkPermission($adminPermissionCategory, $item['key']) ? 'Yes' : 'No' }}</td>
                        </tr>
                    @endforeach

                @endforeach
            @endforeach
        </table>
    </div>
</div>
