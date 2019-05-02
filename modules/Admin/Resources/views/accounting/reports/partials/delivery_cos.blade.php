<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Report Results</h3>
            </div>
            <div class="panel-body full-dl-horizontal">
                <div class="col-md-6">
                    <dl>
                        <dt>Total Orders Appraiser Paid</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalOrdersAppraiserPaidCount'], 'number') !!}</dd>
                    </dl>
                    <dl>
                        <dt>Total Orders Appraiser Not Paid</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalOrdersAppraiserUnPaidCount'], 'number') !!}</dd>
                    </dl>
                    <dl>
                        <dt>Total Orders Count</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalOverallOrdersCount'], 'number') !!}</dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <dl>
                        <dt>Total Orders Appraiser Paid Amount</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalOrdersAppraiserPaidAmount'], 'currency') !!}</dd>
                    </dl>
                    <dl>
                        <dt>Total Orders Appraiser Not Paid Amount</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalOrdersAppraiserUnPaidAmount'], 'currency') !!}</dd>
                    </dl>
                    <dl>
                        <dt>Total Split Amount</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalOverallOrdersAmount'], 'currency') !!}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>