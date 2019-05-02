<div class="row">
    <div class="col-md-6">
        <h2>Actions</h2>
        @foreach($permissionList['actions'] as $actionName => $actions)
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ucwords($actionName)}}</h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-condensed">
                            <tr>
                                <th>
                                    <input type="checkbox" name="select-all-actions_{{$actionName}}" class="select-all-actions" value="1">
                                </th>
                                <th>Key</th>
                                <th>Title</th>
                            </tr>
                            @if($actions)
                                @foreach($actions as $key => $value)
                                <tr>
                                    <td>
                                        <input
                                            type="checkbox"
                                            name="permissions[actions][]"
                                            class="action-checkbox"
                                            value="{{$key}}"
                                            {{($savedPermissions &&
                                                count($savedPermissions) &&
                                                isset($savedPermissions['actions']) &&
                                                count($savedPermissions['actions']) &&
                                                in_array($key, $savedPermissions['actions'])) ? 'checked' : ''
                                            }}>
                                    </td>
                                    <td>{{$key}}</td>
                                    <td>{{$value}}</td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6"><i>None Found</i></td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="col-md-6">
        <h2>Fields</h2>
        @foreach($permissionList['fields'] as $fieldName => $fields)
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">{{ucwords($fieldName)}}</h3>
              </div>
              <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-hover table-condensed">
                        <tr>
                            <th>View</th>
                            <th>Update</th>
                            <th>Key</th>
                            <th>Title</th>
                        </tr>
                        <tr>
                            <th>
                                <input type="checkbox" name="select-all-fields_view_{{$fieldName}}" class="select-all-fields-view" value="1">
                            </th>
                            <th>
                                <input type="checkbox" name="select-all-fields_update_{{$fieldName}}" class="select-all-fields-update" value="1">
                            </th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>
                        @if($fields)
                            @foreach($fields as $key => $value)
                                <tr>
                                    <td>
                                        <input
                                            type="checkbox"
                                            name="permissions[fields_view][{{$fieldName}}][]"
                                            value="{{$key}}"
                                            class="field-view-checkbox"
                                            {{($savedPermissions &&
                                                count($savedPermissions) &&
                                                isset($savedPermissions['fields_view']) &&
                                                isset($savedPermissions['fields_view'][$fieldName]) &&
                                                count($savedPermissions['fields_view'][$fieldName]) &&
                                                in_array($key, $savedPermissions['fields_view'][$fieldName])) ? 'checked' : ''
                                            }}>
                                    </td>
                                    <td>
                                        <input
                                            type="checkbox"
                                            name="permissions[fields_update][{{$fieldName}}][]"
                                            value="{{$key}}"
                                            class="field-update-checkbox"
                                            {{($savedPermissions &&
                                                count($savedPermissions) &&
                                                isset($savedPermissions['fields_update']) &&
                                                count($savedPermissions['fields_update']) &&
                                                isset($savedPermissions['fields_update'][$fieldName]) &&
                                                count($savedPermissions['fields_update'][$fieldName]) &&
                                                in_array($key, $savedPermissions['fields_update'][$fieldName])) ? 'checked' : ''
                                            }}>
                                    </td>
                                    <td>{{$key}}</td>
                                    <td>{{$value}}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6"><i>None Found</i></td>
                            </tr>
                        @endif
                    </table>
                </div>
              </div>
            </div>
        @endforeach
    </div>
</div>
