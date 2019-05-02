<thead>
<th>Team Title</th>
<th>Closed During Business</th>
<th>Created During Business</th>
<th>Closed</th>
<th>Received</th>
<th>AVG Time To Close</th>
<th>MAX Closing Time</th>
</thead>
<tbody>
@foreach($teams as $team)
<tr>
    <td>{!! $team->team_title !!}</td>
    <td>
        @if(isset($closedTeamBusiness['rows'][$team->id])){!! number_format($closedTeamBusiness['rows'][$team->id]['total']) !!} @else 0 @endif
    </td>
    <td>
        @if(isset($createdTeamBusiness['rows'][$team->id])){!! number_format($createdTeamBusiness['rows'][$team->id]['total']) !!} @else 0 @endif
    </td>
    <td>@if(isset($totalTicketsClosedByTeam[$team->id])){!! number_format($totalTicketsClosedByTeam[$team->id]) !!}@endif
    </td>
    <td>@if(isset($totalTeamsTicketCreated[$team->id])){!! number_format($totalTeamsTicketCreated[$team->id]) !!} @else 0 @endif</td>
    <td>@if(isset($teamsAvgCloseTime[$team->id])){!! number_format($teamsAvgCloseTime[$team->id], 2) !!} @else 0 @endif</td>
    <td>@if(isset($totalTeamsMaxClosedTime[$team->id])){!! number_format($totalTeamsMaxClosedTime[$team->id], 2) !!} @else 0 @endif</td>

</tr>
@endforeach
</tbody>
