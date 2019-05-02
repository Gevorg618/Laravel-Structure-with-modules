@foreach($categories as $category)
<h3>{{ $category->title }}</h3>

@foreach($userPermissionService->groups($category->id) as $group)
<table>
    <tr>
        <th colspan="2">{{ $group->title }}</th>
    </tr>
    @foreach($userPermissionService->items($group->id) as $item)
    <tr>
        <td>{{ $item->title }}<br /><small>{{ $item->description }}</small></td>
        {!! Form::select('userpermissions['.$item->id.']', $yesNo, $userPermissionService->can($user->id, $item->key) ? 1 : 0, ['class' => 'form-control']) !!}
    </tr>
    @endforeach
</table>
@endforeach
@endforeach