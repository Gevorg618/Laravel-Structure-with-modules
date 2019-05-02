<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Report Results</h3>
            </div>
            <div class="panel-body full-dl-horizontal">
                <div class="col-md-6">
                    <dl>
                        <dt>Total Orders Count</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalOverallOrdersCount'], 'number') !!}</dd>
                    </dl>
                    <dl>
                        <dt>Total Orders Paid In Full</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalOrdersPaidInFullCount'], 'number') !!}</dd>
                    </dl>
                    <dl>
                        <dt>Total Orders Balance Due</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalOrdersBalanceDueCount'], 'number') !!}</dd>
                    </dl>
                    <dl>
                        <dt>&nbsp;</dt>
                        <dd>&nbsp;</dd>
                    </dl>
                    <dl>
                        <dt>&nbsp;</dt>
                        <dd>&nbsp;</dd>
                    </dl>
                    <dl>
                        <dt>Total Orders Unpaid</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalOrdersUnpaidCount'], 'number') !!}</dd>
                    </dl>
                    <dl style="display:none;">
                        <dt>Total Orders Paid</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalOrdersPaidCount'], 'number') !!}</dd>
                    </dl>

                </div>
                <div class="col-md-6">
                    <dl>
                        <dt>Total Invoice Amount</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalOverallOrdersAmount'], 'currency') !!}</dd>
                    </dl>
                    <dl>
                        <dt>Total Amount Paid In Full</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalOrdersPaidInFullAmount'], 'currency') !!}</dd>
                    </dl>
                    <dl>
                        <dt>Total Amount Balance Due</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalOrdersBalanceDueAmount'], 'currency') !!}</dd>
                    </dl>
                    <dl>
                        <dt>Total Paid Amount Not Paid In Full</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalOrdersBalancePaidAmount'], 'currency') !!}</dd>
                    </dl>
                    <dl>
                        <dt>Total Invoice Amount Not Paid In Full</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalOrdersBalancePaidFullAmount'], 'currency') !!}</dd>
                    </dl>
                    <dl>
                        <dt>Total Unpaid Amount</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalOrdersUnpaidAmount'], 'currency') !!}</dd>
                    </dl>
                    <dl style="display:none;">
                        <dt>Total Paid Amount</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalOrdersPaidAmount'], 'currency') !!}</dd>
                    </dl>

                </div>
            </div>
        </div>
    </div>
</div>