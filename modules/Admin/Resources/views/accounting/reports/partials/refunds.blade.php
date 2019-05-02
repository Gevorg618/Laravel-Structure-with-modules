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
                        <dt>CC Refund Order Count</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalCreditCardsRefundCount'], 'number') !!}</dd>
                    </dl>
                    <dl>
                        <dt>Check Refund Order Count</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalChecksRefundCount'], 'number') !!}</dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <dl>
                        <dt>CC Refund Amount</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalCreditCardsRefundAmount'], 'currency') !!}</dd>
                    </dl>
                    <dl>
                        <dt>Check Refund Amount</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['totalChecksRefundAmount'], 'currency') !!}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>