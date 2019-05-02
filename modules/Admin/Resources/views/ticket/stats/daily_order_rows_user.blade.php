@if(count($list))
    <thead>
    <th>User</th>
    <th>Closed</th>
    <th>AVG Time To Close</th>
    <th>Total Time Spent</th>
    </thead>
    <tbody>
    @foreach($list as $userId => $data)
    <tr>
        <td>{!! $data['name'] !!}</td>
        <td>{!! number_format($data['closed']) !!}</td>
        <td>{!! number_format($data['avg'], 2) !!}</td>
        <td>{!! number_format($data['totalspent'], 2) !!}</td>
    </tr>
    @endforeach
    </tbody>
@endif