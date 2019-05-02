<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Report Results</h3>
            </div>
            <div class="panel-body full-dl-horizontal">
                <div class="alert alert-info"><b>Note!</b> Regardless of the Date Type selected above, this report will use the Date From & Date To based of the date the appraiser was paid.</div>

                <div class="col-md-6">
                    <dl>
                        <dt>Total Completed Orders</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalPaymentsCompletedCount'], 'number') !!}</dd>
                    </dl>
                    <dl>
                        <dt>Total In Progress Orders</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalPaymentsInProgressCount'], 'number') !!}</dd>
                    </dl>

                </div>
                <div class="col-md-6">
                    <dl>
                        <dt>Total Completed Paid</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalPaymentsCompletedAmount'], 'currency') !!}</dd>
                    </dl>
                    <dl>
                        <dt>Total In Progress Paid</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalPaymentsInProgressAmount'], 'currency') !!}</dd>
                    </dl>
                    <dl>
                        <dt>Total Checks Amount</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalPaymentsAmount'], 'currency') !!}</dd>
                    </dl>


                </div>
            </div>
        </div>
    </div>
</div>