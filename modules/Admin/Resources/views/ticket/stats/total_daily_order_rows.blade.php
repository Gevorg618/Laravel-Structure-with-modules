<thead>
<th>Closed During Business</th>
<th>Created During Business</th>
<th>Total Closed</th>
<th>Total Created</th>
<th>AVG Closing Time</th>
</thead>
<tbody>
<tr>
    <td>
        {!! number_format($closedBusiness['total']) !!}
    </td>
    <td>
        {!! number_format($createdBusiness['total']) !!}
    </td>
    <td>{!! number_format($totalClosed) !!}</td>
    <td>{!! number_format($totalCreated) !!}</td>
    <td>{!! number_format($closeAvgTime, 2) !!}</td>

</tr>
</tbody>