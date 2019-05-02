<table class="table table-condensed">
    <tr>
        <th>User</th>
        <th style="width:1px;">Remove</th>
    </tr>
    @if($preferredGroups)
    @foreach($preferredGroups as $row)
    <tr>
        <td>{{ ucwords(strtolower(trim($row->groupName))) }}</td>
        <td><a href="javascript:void(removePreferredGroup('{{ $row->groupid }}', '{{ $user->id }}'));"><img src='/images/icons/famfamfam/delete.png' alt='remove' /></a></td>
    </tr>
    @endforeach
    @else
    <tr>
        <td colspan="2">No Records.</td>
    </tr>
    @endif
</table>