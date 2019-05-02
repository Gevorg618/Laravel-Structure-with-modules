<table class="table table-responsive">
    <tr>
        <th style="width:50%;">&nbsp;</th>
        <th>&nbsp;</th>
    </tr>
    <tr>
        <td>Register Date/Time</td>
        <td>{{ date("M j, Y H:i",strtotime($user->register_date)) }}</td>
    </tr>
    <tr>
        <td>Last Login</td>
        <td>{{ date("M j, Y H:i",strtotime($user->last_activity)) }}</td>
    </tr>
    <tr>
        <td>Last User Agent</td>
        <td>{{ \Modules\Admin\Helpers\StringHelper::encode($user->last_ua) }}</td>
    </tr>


    @if($user->user_type == 4)
    <tr>
        <td>Orders Accepted</td>
        <td>{{ number_format($service->getOrdersAcceptedByUserId($user->id)) }} (Landscape {{ number_format($service->getOrdersAcceptedByUserIdWithDeliveredDate($user->id)) }})</td>
    </tr>
    <tr>
        <td>Orders Completed</td>
        <td>{{ number_format($service->getApprOrdersCompletedByUserId($user->id)) }} (Landscape {{ $ordersCompleted }})</td>
    </tr>
    @elseif($user->user_type == 14)
    <tr>
        <td>Orders Accepted</td>
        <td>{{ number_format($service->getALOrdersAcceptedByUserIdCount($user->id)) }}</td>
    </tr>
    <tr>
        <td>Orders Completed</td>
        <td>{{ number_format($service->getALOrdersCompletedByUserId($user->id)) }}</td>
    </tr>
    @elseif($user->user_type == 5)
    <tr>
        <td>Orders Placed</td>
        <td>{{ number_format($service->getOrdersPlacedByUserId($user->id)) }}</td>
    </tr>
    <tr>
        <td>Orders Completed</td>
        <td>{{ number_format($service->getOrdersCompletedByUserId($user->id)) }}</td>
    </tr>
    @endif


    @if($user->user_type == 4)
    @php $completed = $service->getApprOrdersCompletedByUserId($user->id);
    $types = $service->getAppraiserOrderTypeBreakdown($user->id); @endphp
    <tr>
        <td>Order Type Breakdown</td>
        <td>
            @if($types)
            @foreach($types as $type)
            {{ sprintf("%s - %s", $type->appraisalType->form, $type->appraisalType->descrip) }}<br />
            @endforeach
            @endif
        </td>
    </tr>
    <tr>
        <td>Orders Turned in less than 7 days</td>
        <td>{{ $turnOut }} ({{ $completed ? number_format($turnOut/$completed*100,0) : 0 }}%)</td>
    </tr>

    <tr>
        <td>Total Orders With QC Corrections</td>
        <td>@if(isset($qcStats['count'])){{ count($qcStats['count']) }} ({{ $ordersCompleted ? number_format((count($qcStats['count'])*100/$ordersCompleted), 2) : 0 }}%) ({{ number_format($qcStats['total']) }} Corrections Overall)@endif</td>
    </tr>
    <tr>
        <td>Average QC Corrections</td>
        <td>{{ $qcStats['total'] ? number_format(count($qcStats['all']) / $qcStats['total'], 2) : 0 }} </td>
    </tr>

    <tr>
        <td>Total Orders With UW Conditions</td>
        <td>@if(isset($uwStats['count'])){{ count($uwStats['count']) }} ({{ $ordersCompleted ? number_format((count($uwStats['count'])*100/$ordersCompleted), 2) : 0 }}%) ({{ number_format($uwStats['total']) }} Conditions Overall)@endif</td>
    </tr>
    <tr>
        <td>Average UW Conditions</td>
        <td>{{ $uwStats['total'] ? number_format(count($uwStats['all']) / $uwStats['total'], 2) : 0 }} </td>
    </tr>

    <tr>
        <td>Excluded From User Groups</td>
        <td>
            @if($excluded)
            @foreach($excluded as $exclude)
            {{ $exclude->descrip }}<br />
            @endforeach
            @else
            --
            @endif
        </td>
    </tr>

    <tr>
        <td>Excluded From Wholesale Lenders</td>
        <td>
            @if($lenders)
            @foreach($lenders as $lender)
            {{ $lender->lender }} ({{ $lender->created_date ? date('m/d/Y G:i A', $lender->created_date) : '--' }})<br />
            @endforeach
            @else
            --
            @endif
        </td>
    </tr>

    <tr>
        <th colspan="2">Overall Average</th>
    </tr>
    <tr>
        <td>Total Completed</td>
        <td>@if(isset($appraiserAverage['totalCompleted'])){{ number_format($appraiserAverage['totalCompleted']) }} @else 0 @endif Orders </td>
    </tr>
    <tr>
        <td>Average Turn Time (Accepted -> Delivery)</td>
        <td>{{ number_format($appraiserAverage['total'], 3) }} Day(s)</td>
    </tr>
    <tr>
        <td>Average Turn Time (Accepted -> Scheduled)</td>
        <td>{{ number_format($appraiserAverage['accepted_to_scheduled'], 3) }} Day(s)</td>
    </tr>
    <tr>
        <td>Average Turn Time (Scheduled -> Delivery)</td>
        <td>{{ number_format($appraiserAverage['scheduled_to_delivered'], 3) }} Day(s)</td>
    </tr>
    <tr>
        <td>Average Turn Time (Accepted -> Submitted)</td>
        <td>{{ number_format($appraiserAverage['submit'], 3) }} Day(s)</td>
    </tr>
    <tr>
        <td>Average Turn Time (QC+QC Corrections -> Delivery)</td>
        <td>{{ number_format($appraiserAverage['qc'], 3) }} Day(s)</td>
    </tr>
    <tr>
        <td>Average U/W Condition Turn Time</td>
        <td>{{ number_format($appraiserAverage['uw'], 3) }} Day(s)</td>
    </tr>

    <tr>
        <th colspan="2">90 Days Average</th>
    </tr>
    <tr>
        <td>Total Completed</td>
        <td>@if(isset($daysRangeAverage['totalCompleted'])){{ number_format($daysRangeAverage['totalCompleted']) }} @else 0 @endif Orders</td>
    </tr>
    <tr>
        <td>Average Turn Time (Accepted -> Delivery)</td>
        <td>{{ number_format($daysRangeAverage['total'], 3) }} Day(s)</td>
    </tr>
    <tr>
        <td>Average Turn Time (Accepted -> Scheduled)</td>
        <td>{{ number_format($daysRangeAverage['accepted_to_scheduled'], 3) }} Day(s)</td>
    </tr>
    <tr>
        <td>Average Turn Time (Scheduled -> Delivery)</td>
        <td>{{ number_format($daysRangeAverage['scheduled_to_delivered'], 3) }} Day(s)</td>
    </tr>
    <tr>
        <td>Average Turn Time (Accepted -> Submitted)</td>
        <td>{{ number_format($daysRangeAverage['submit'], 3) }} Day(s)</td>
    </tr>
    <tr>
        <td>Average Turn Time (QC+QC Corrections -> Delivery)</td>
        <td>{{ number_format($daysRangeAverage['qc'], 3) }} Day(s)</td>
    </tr>
    <tr>
        <td>Average U/W Condition Turn Time</td>
        <td>{{ number_format($daysRangeAverage['uw'], 3) }} Day(s)</td>
    </tr>





    @if($user->state_compliance_date)
    @if($stateCompliance)
    <tr>
        <td>{{ sprintf("%s Compliance", getStateByAbbr($stateCompliance->state)) }}</td>
        <td>{{ sprintf("Release Date %s", date('m/d/Y', $user->state_compliance_date)) }}</td>
    </tr>
    @endif
    @endif

    @endif

</table>


@if($user->user_type == 4)
<!-- Preferred Appraiser -->

<div class="row">
    <div class="span8">
        <h4>Preferred Groups</h4>
        {!! Form::text('group_title', null, ['class' => 'form-control']) !!}
        <div class="clear"></div>
        <div id="group_manager_table">
            @include('users.partials.preferred_groups')
        </div>
    </div>
</div>
@endif