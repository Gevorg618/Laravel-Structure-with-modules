<table class="table table-striped table-bordered table-hover">
    <thead>
    <tr>
        <th>Vendor</th>
        <th>ID</th>
        <th>Date Placed</th>
        <th>Date Completed</th>
        <th>Address</th>
        <th>Client</th>
        <th>Status</th>
        <th>Amount</th>
        <th>Client Balance</th>
    </tr>
    </thead>
    <tbody>

    @foreach($appraisals as $appraisal)
        <tr>
            <td>
                (<a href='pay_appr.php?userid={!! $appraisal->appr_accepted !!}&ordertype=completed&fromdate={!! $dateFrom !!}&todate={!! $dateTo !!}' target='_blank' title='View Appraiser Payables'>
                    <i class="fa fa-usd" aria-hidden="true"></i>
                </a>)
                <a href='/admin/user.php?action=view-user&id={!! $appraisal->appr_accepted !!}' target='_blank'>{!! $appraisal->fullname !!}</a>
            </td>
            <td><a href='/admin/order.php?id={!! $appraisal->id !!}' target='_blank'>{!! $appraisal->id !!}</a></td>
            <td>{!! date('m/d/Y', strtotime($appraisal->ordereddate)) !!}</td>
            <td>{!! date('m/d/Y', strtotime($appraisal->completed_date)) !!}</td>
            <td>{!! trim($appraisal->fulladdress) !!}</td>
            <td>{!! $appraisal->company_name !!}</td>
            <td>{!! $appraisal->status !!}</td>
            <td>{!! $appraisal->amount !!}</td>
            <td>{!! $appraisal->balance !!}</td>
        </tr>
    @endforeach

    </tbody>
</table>