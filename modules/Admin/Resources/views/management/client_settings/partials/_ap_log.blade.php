<div class="logs_table_overflow">
    <table class="table table-bordered">
        <thead class="borderless">
        <tr>
            <th>Date Created</th>
            <th>Created By</th>
            <th>Message</th>
        </tr>
        </thead>
        <tbody class="borderless">
        @foreach($apLogs as $apLog)
            <tr>
                <td>{{date('m/d/Y H:i', $apLog->created_date)}}</td>
                <td>{{getUserFullNameById($apLog->created_by)}}</td>
                <td>{{$apLog->message}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
