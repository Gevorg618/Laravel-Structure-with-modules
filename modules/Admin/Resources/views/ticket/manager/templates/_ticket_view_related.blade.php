<div class="row">
    <div class="col-md-12">
        <div class="alert alert-warning">We will show the last 500 related tickets.</div>
        <div class="table-responsive">
            <table class="table table-hover table-condensed">
                <tr>
                    <th>ID</th>
                    <th>Subject</th>
                    <th>Created Date</th>
                    <th>Time</th>
                </tr>

                @if ($relatedTickets->count())
                    @foreach ($relatedTickets as $row)
                        <tr>
                            <td><a href="{{ route('admin.ticket.manager.view', ['id' => $row->id]) }}"
                                   target="_blank">{{ $row->id }}</a></td>
                            <td>@include('admin::ticket.manager.partials._subject_line', ['row' => $row])</td>
                            <td>{{ date('m/d/Y g:i A', $row->created_date) }}</td>
                            <td>@include('admin::ticket.manager.partials._ticket_time', ['row' => $row])</td>
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