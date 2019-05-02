<div class="row">
    <div class="col-md-6">
        <div class="table-responsive">
            <table class="table table-hover table-condensed">
                <tr>
                    <th>Team</th>
                    <th>Open</th>
                    <th>Closed Today</th>
                    <th>Closed Yesterday</th>
                    <th>Oldest Open</th>
                </tr>
                @if ($pipelines['teams'])
                    @foreach ($pipelines['teams'] as $r)
                        <tr>
                            <td>{{ $r['title'] }}</td>
                            <td>{{ number_format($r['open']) }}</td>
                            <td>{{ number_format($r['closed_today']) }}</td>
                            <td>{{ number_format($r['closed_yesterday']) }}</td>
                            <td><span data-time="{{ $r['oldest_opened'] }}"
                                      class="time-show-since label">{{ $r['oldest_opened'] }}</span></td>
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
    <div class="col-md-6 hidden">
        <div class="table-responsive">
            <table class="table table-hover table-condensed">
                <tr>
                    <th>User</th>
                    <th>Open</th>
                    <th>Closed Today</th>
                    <th>Closed Yesterday</th>
                </tr>
                @if ($pipelines['users'])
                    @foreach ($pipelines['users'] as $r)
                        <tr>
                            <td>{{ $r['title'] }}</td>
                            <td>{{ number_format($r['open']) }}</td>
                            <td>{{ number_format($r['closed_today']) }}</td>
                            <td>{{ number_format($r['closed_yesterday']) }}</td>
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