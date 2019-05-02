<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Report Results</h3>
            </div>
            <div class="panel-body full-dl-horizontal">
                <div class="alert alert-info"><b>Note!</b> Regardless of the Date Type selected above, this report will use the Date From & Date To based of the date the Credit Card or Check payment applied.</div>
                <div class="col-md-6">
                    <dl>
                        <dt>CC Completed Orders Count (One payment)</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['creditCardsCompletedOne'], 'number') !!}</dd>
                    </dl>
                    <dl>
                        <dt>CC Completed Orders Count (More than one)</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['creditCardsCompletedMoreThanOne'], 'number') !!}</dd>
                    </dl>
                    <dl>
                        <dt>CC Completed Orders Count (All)</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalCreditCardCompletedOrders'], 'number') !!}</dd>
                    </dl>
                    <dl>
                        <dt>CC In Progress Orders Count</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalCreditCardInProgressOrders'], 'number') !!}</dd>
                    </dl>
                    <dl>
                        <dt>Checks Completed Orders Count (One payment)</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['checksCompletedOne'], 'number') !!}</dd>
                    </dl>
                    <dl>
                        <dt>Checks Completed Orders Count (More than one)</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['checksCompletedMoreThanOne'], 'number') !!}</dd>
                    </dl>
                    <dl>
                        <dt>Checks Completed Orders Count (All)</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalChecksCompletedOrders'], 'number') !!}</dd>
                    </dl>
                    <dl>
                        <dt>Checks In Progress Orders Count</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalChecksInProgressOrders'], 'number') !!}</dd>
                    </dl>
                    <dl>
                        <dt>CC Total Orders Count (Completed+In Progress)</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalCreditCardOrdersCount'], 'number') !!}</dd>
                    </dl>

                    <dl>
                        <dt>Checks Total Orders Count (Completed+In Progress)</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalChecksOrdersCount'], 'number') !!}</dd>
                    </dl>

                    <dl>
                        <dt>Checks Total Orders Count (CC + Checks (Completed+In Progress))</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalOrdersCount'], 'number') !!}</dd>
                    </dl>

                </div>
                <div class="col-md-6">
                    <dl>
                        <dt>CC Completed Orders Amount</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalCreditCardCompletedAmount'], 'currency') !!}</dd>
                    </dl>
                    <dl>
                        <dt>CC In Progress Orders Amount</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalCreditCardInProgressAmount'], 'currency') !!}</dd>
                    </dl>
                    <dl>
                        <dt>Checks Completed Orders Amount</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalChecksCompletedAmount'], 'currency') !!}</dd>
                    </dl>
                    <dl>
                        <dt>Checks In Progress Orders Amount</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalChecksInProgressAmount'], 'currency') !!}</dd>
                    </dl>
                    <dl>
                        <dt>CC Total Orders Amount (Completed+In Progress)</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalCreditCardOrdersAmount'], 'currency') !!}</dd>
                    </dl>

                    <dl>
                        <dt>Checks Total Orders Amount (Completed+In Progress)</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalChecksOrdersAmount'], 'currency') !!}</dd>
                    </dl>

                    <dl>
                        <dt>Checks Total Orders Amount (CC + Checks (Completed+In Progress))</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalOrdersAmount'], 'currency') !!}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>