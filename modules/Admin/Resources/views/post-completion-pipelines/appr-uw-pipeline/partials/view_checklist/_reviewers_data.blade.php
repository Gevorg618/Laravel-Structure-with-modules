<div class="row">
    <div class="col-md-12">
        <table class="table table-hover table-striped">
            <tr>
                <th>Date/Time</th>
                <th>Reviewer</th>
                <th>Time</th>
                <th>Result</th>
            </tr>
             @if($reviewerActivity)
                @foreach($reviewerActivity as $r)
                    <tr>
                        <td><?php echo date('m/d/Y H:i', $r->created_date); ?></td>
                        <td>{{getUserFullNameById($r->created_userid)}}</td>
                        <td data-seconds="{{($r->end_time - $r->start_time)}}" class='format-seconds-to-human'></td>
                        @if($r->is_approved)
                            <td>Approved</td>
                        @elseif($r->is_sent_back)
                            <td>Sent Back</td>
                        @elseif($r->is_hold)
                            <td>Hold</td>
                        @else
                            <td>Saved</td>
                        @endif
                    </tr>
                @endforeach
            @endif
            <tr>
                <td><?php echo date('m/d/Y H:i', time()); ?></td>
                <td> </td>
                <td><span id="current-timer"></span></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td><span id="total-timer"></span></td>
                <td>&nbsp;</td>
            </tr>
        </table>
    </div>
</div>
