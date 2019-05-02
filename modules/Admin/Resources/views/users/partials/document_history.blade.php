<table class="table table-condensed">
    <tr>
        <th>Uploaded Date</th>
        <th>Uploaded By</th>
        <th>Type</th>
        <th>Name</th>
    </tr>

    @if($documents)

    @foreach($documents as $row)
    <tr>
        <td>{{ date('m/d/Y H:i', $row->created_date) }}</td>
        <td>{{ getUserFullNameById($row->created_by) }}</td>
        <td>{{ $service->getUserDocumentTypeName($row->type) }}</td>
        <td><a href='{{ route('admin.users.download', [$user->id, $row->id]) }}'>{{ $row->name }}</a></td>
    </tr>
    @endforeach
    @else
    <tr>
        <td colspan="5">No Documents Found.</td>
    </tr>
    @endif
</table>
