@foreach($details as $detail)
    <tr>
        <td>{{ $detail->id }}</td>
        <td class="text-lowercase"></td>
        <td>{{ $detail->status_name }}</td>
    </tr>
@endforeach