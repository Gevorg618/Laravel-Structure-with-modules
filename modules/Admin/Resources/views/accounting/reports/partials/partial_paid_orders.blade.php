<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Report Results</h3>
            </div>
            <div class="panel-body full-dl-horizontal">
                <div class="col-md-6">
                    <dl>
                        <dt>Client Deposits Begining Of Day</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['creditCardsInProgressYesterdayAmount'], 'currency') !!}</dd>
                    </dl>
                    <dl>
                        <dt>Client Deposits Today</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['creditCardsInProgressAmount'], 'currency') !!}</dd>
                    </dl>
                    <dl>
                        <dt>Deliveries Paid Today</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['deliveriesTodayPaidTodayAmount'], 'currency') !!}</dd>
                    </dl>
                    <dl>
                        <dt>Deliveries Paid Past</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['deliveriesTodayPaidPastAmount'], 'currency') !!}</dd>
                    </dl>
                    <dl>
                        <dt>Client Deposits End Of Day</dt>
                        <dd>{!! \Modules\Admin\Helpers\StringHelper::formatValue($result['creditCardsInProgressCurrentAmount'], 'currency') !!}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>