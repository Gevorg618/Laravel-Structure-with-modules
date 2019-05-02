@push('style')
    <style>
        .borderless tr, .borderless td, .borderless th  {
            border: none !important;
        }

        .logs_table_overflow {
            max-height: 600px;
            overflow-y: auto;
        }
    </style>
@endpush
<div class="container logs_table_overflow">
    <h2>Group Log Entries</h2>
    <table class="table table-bordered">
        <thead class="borderless">
            <tr>
                <th>Date</th>
                <th>By</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody class="borderless">
        @foreach($clientLogs as $clientLog)
            <tr>
                <td>{{date('m/d/Y H:i', $clientLog->created_date)}}</td>
                <td>{{getUserFullNameById($clientLog->created_by)}}</td>
                <td>{!! $clientLog->note !!}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>


