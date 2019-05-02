<hr />
<div class="row">
    <div class="span8">
        <div class="accordion" id="view_order_activity_accordion">
            @foreach($activities as $activityId => $row)
                @php $count = 1; @endphp
            <div class="accordion-group">
                <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#view_order_activity_accordion" href="#activity_record_collapse_{{ $activityId }}">{{ sprintf("#%s By %s On %s (%s)", $count, $row['authorname'], date('m/d/Y G:i A', $row['date']), \Modules\Admin\Helpers\StringHelper::formatValue(count($row['rows']), 'number')) }}</a>
                </div>
                <div id="activity_record_collapse_{{ $activityId }}" class="accordion-body collapse in">
                    <div class="accordion-inner">
                        <table>
                            <tr>
                                <th>Column</th>
                                <th>From</th>
                                <th>To</th>
                            </tr>
                            @foreach($row['rows'] as $r)
                            <tr>
                                <td>{{ $r->title }}</td>
                                <td>{{ $r->from_value }}</td>
                                <td>{{ $r->to_value }}</td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
                @php $count++; @endphp
            @endforeach
        </div>
    </div>
</div>