<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-hover table-condensed">
                <tr>
                    <th>ID</th>
                    <th>Activity</th>
                    <th>Created Date</th>
                    <th>Created By</th>
                </tr>
                @if ($rows)
                    @foreach($rows as $r)
                        <tr>
                            <td>
                                <a href="{{ route('admin.ticket.manager.view', ['id' => $r->ticket_id]) }}"
                                   target='_blank'>{{ $r->ticket_id }}</a>
                            </td>
                            <td>{!! $r->message !!}</td>
                            <td>{{ date('m/d/Y g:i A', $r->created_date) }}</td>
                            <td>{{ $r->fullname }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4"><i>None Found</i></td>
                    </tr>
                @endif
            </table>
        </div>
    </div>
</div>