@extends('admin::layouts.master')

@section('title', 'Accounting')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Lookup Checks Sent/Recv', 'url' => route('admin.qc.collection.index')]
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body">
                        <h1>Locate Payments</h1>
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="pull-left">
                            <form method='get' action='/admin/accounting/locate-payments'>
                                <div class="form-group row">
                                    <label for="term" class="col-md-2 control-label">Title</label>
                                    <div class="col-md-10">
                                        {!! Form::text('term', Request::get('term'),
                                            ['id' => 'term', 'class' => 'form-control', 'placeholder' => 'Term']
                                        ) !!}
                                    </div>
                                </div>
                                <div class="clear"></div>


                                <div class="form-group row">
                                    <input type="button" class="btn btn-info" value="Reset" id="reset_locate_payments"/>
                                    <input type="submit" class="btn btn-primary" id="submit" name="submit"
                                           value='Search'/>
                                </div>
                                <div class="clear"></div>
                            </form>
                        </div>
                    </div>

                    @if(Request::get('submit'))
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#appr_checks_sent">Appraiser Payments Sent ({!! count($apprChecksSent) !!})</a>
                            </li>
                            <li><a data-toggle="tab" href="#appr_checks">Appraisal Checks Applied To Orders ({!! count($apprChecks) !!}) </a></li>
                            <li><a data-toggle="tab" href="#appr_cards">Appraisal Credit Card Transactions Applied To
                                    Orders ({!! count($apprCards) !!}) </a></li>
                            <li><a data-toggle="tab" href="#appr_fd_card">Appraisal Credit Card Transactions Applied To
                                    Orders (First Data) ({!! count($apprFDCards) !!})</a></li>
                            <li><a data-toggle="tab" href="#mercury_payments">Appraisal Mercury Transactions Applied To
                                    Orders (TSYS) ({!! count($mercuryPayments) !!})</a></li>
                            <li><a data-toggle="tab" href="#al_checks">Alternative Valuation Checks Applied To
                                    Orders ({!! count($alChecks) !!})</a></li>
                            <li><a data-toggle="tab" href="#al_cards">Alternative Valuation Credit Card Transactions
                                    Applied To Orders ({!! count($alCards) !!}
                                        )</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="appr_checks_sent" class="tab-pane fade in active">
                                @if(isset($apprChecksSent))
                                    <h2>Appraiser Payments Sent ({!! count($apprChecksSent) !!})</h2>
                                    <div class="panel-body panel-body-table">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover">
                                                <thead>
                                                <tr>
                                                    <th>Order ID</th>
                                                    <th>Address</th>
                                                    <th>Check Number</th>
                                                    <th>Check Amount</th>
                                                    <th>Appraiser Name</th>
                                                    <th>Date Sent</th>
                                                    <th>Date Created</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                @foreach($apprChecksSent as $apprCheckSent)
                                                    <tr class="appr-order-tr-row"
                                                        id="appr-order-tr-row-{!! $apprCheckSent->orderid !!}">
                                                        <td>{!! $apprCheckSent->orderid !!}</td>
                                                        <td>{!! optional($apprChecksSentOrders->get($apprCheckSent->orderid))->address !!}</td>
                                                        <td>{!! \Modules\Admin\Helpers\StringHelper::highlightWords($apprCheckSent->checknum, $term) !!}</td>
                                                        <td>${!! number_format($apprCheckSent->checkamount, 2) !!}</td>
                                                        <td>{!! optional($apprCheckSent->user)->full_name !!}</td>
                                                        <td>{!! $apprCheckSent->date_sent ? date('m/d/Y', strtotime($apprCheckSent->date_sent)) : '' !!}</td>
                                                        <td>{!! $apprCheckSent->paid ? date('m/d/Y H:i', strtotime($apprCheckSent->paid)) : '' !!}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @else
                                    <p>No data</p>
                                @endif
                            </div>
                            <div id="appr_checks" class="tab-pane fade">
                                @if(isset($apprChecks))
                                    <h2>Appraisal Checks Applied To Orders ({!! count($apprChecks) !!})</h2>
                                    <div class="panel-body panel-body-table">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover">
                                                <thead>
                                                <tr>
                                                    <th>Order ID</th>
                                                    <th>Address</th>
                                                    <th>Check Number</th>
                                                    <th>Check Amount</th>
                                                    <th>Check From</th>
                                                    <th>Date Placed</th>
                                                    <th>Date Created</th>
                                                    <th>Placed By</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                @foreach($apprChecks as $apprCheck)
                                                    <tr class="appr-order-tr-row"
                                                        id="appr-order-tr-row-{!! $apprCheck->order_id !!}">
                                                        <td>{!! $apprCheck->order_id !!}</td>
                                                        <td>{!! optional($apprChecksOrders->get($apprCheck->order_id))->address !!}</td>
                                                        <td>{!! \Modules\Admin\Helpers\StringHelper::highlightWords($apprCheck->check_number, $term) !!}</td>
                                                        <td>${!! number_format($apprCheck->amount, 2) !!}</td>
                                                        <td>{!! \Modules\Admin\Helpers\StringHelper::highlightWords($apprCheck->check_from, $term) !!}</td>
                                                        <td>{!! $apprCheck->date_received ? date('m/d/Y', $apprCheck->date_received) : '' !!}</td>
                                                        <td>{!! $apprCheck->created_date ? date('m/d/Y H:i', $apprCheck->created_date) : '' !!}</td>
                                                        <td>{!! optional($apprCheck->user)->full_name !!}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @else
                                    <p>No data</p>
                                @endif
                            </div>
                            <div id="appr_cards" class="tab-pane fade">
                                @if(isset($apprCards))
                                    <h2>Appraisal Credit Card Transactions Applied To Orders (Authorize.net)
                                        ({!! count($apprCards) !!})</h2>
                                    <div class="panel-body panel-body-table">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover">
                                                <thead>
                                                <tr>
                                                    <th>Order ID</th>
                                                    <th>Address</th>
                                                    <th>Transaction ID</th>
                                                    <th>Credit Number</th>
                                                    <th>Card Holder</th>
                                                    <th>Amount</th>
                                                    <th>Date Placed</th>
                                                    <th>Placed By</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                @foreach($apprCards as $apprCard)
                                                    <tr class="appr-order-tr-row"
                                                        id="appr-order-tr-row-{!! $apprCard->order_id !!}">
                                                        <td>{!! $apprCard->order_id !!}</td>
                                                        <td>{!! optional($apprCardsOrders->get($apprCard->order_id))->address !!}</td>
                                                        <td>{!! \Modules\Admin\Helpers\StringHelper::highlightWords($apprCard->trans_id, $term) !!}</td>
                                                        <td>{!! \Modules\Admin\Helpers\StringHelper::highlightWords(str_replace('XXXX-XXXX-', '', $apprCard->credit_number), $term) !!}</td>
                                                        <td>{!! \Modules\Admin\Helpers\StringHelper::highlightWords($apprCard->card_name, $term) !!}</td>
                                                        <td>${!! number_format($apprCard->amount, 2) !!}</td>
                                                        <td>{!! $apprCard->created_date ? date('m/d/Y H:i', $apprCard->created_date) : '' !!}</td>
                                                        <td>{!! optional($apprCard->user)->full_name !!}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @else
                                    <p>No data</p>
                                @endif
                            </div>
                            <div id="appr_fd_card" class="tab-pane fade">
                                @if(isset($apprFDCards))
                                    <h2>Appraisal Credit Card Transactions Applied To Orders (First Data)
                                        ({!! count($apprFDCards) !!})</h2>
                                    <div class="panel-body panel-body-table">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover">
                                                <thead>
                                                <tr>
                                                    <th>Order ID</th>
                                                    <th>Address</th>
                                                    <th>Transaction ID</th>
                                                    <th>Credit Number</th>
                                                    <th>Card Holder</th>
                                                    <th>Amount</th>
                                                    <th>Date Placed</th>
                                                    <th>Placed By</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                @foreach($apprFDCards as $apprCard)
                                                    <tr class="appr-order-tr-row"
                                                        id="appr-order-tr-row-{!! $apprCard->order_id !!}">
                                                        <td>{!! $apprCard->order_id !!}</td>
                                                        <td>{!! optional($apprFDCardsOrders->get($apprCard->order_id))->address !!}</td>
                                                        <td>{!! \Modules\Admin\Helpers\StringHelper::highlightWords($apprCard->auth_code, $term) !!}</td>
                                                        <td>{!! \Modules\Admin\Helpers\StringHelper::highlightWords(str_replace('XXXX-XXXX-', '', $apprCard->credit_number), $term) !!}</td>
                                                        <td>{!! \Modules\Admin\Helpers\StringHelper::highlightWords($apprCard->card_name, $term) !!}</td>
                                                        <td>${!! number_format($apprCard->amount, 2) !!}</td>
                                                        <td>{!! $apprCard->created_date ? date('m/d/Y H:i', $apprCard->created_date) : '' !!}</td>
                                                        <td>{!! optional($apprCard->user)->full_name !!}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @else
                                    <p>No data</p>
                                @endif
                            </div>
                            <div id="mercury_payments" class="tab-pane fade">
                                @if(isset($mercuryPayments) && count($mercuryPayments))
                                    <h2>Appraisal Mercury Transactions Applied To Orders (TSYS)
                                        ({!! count($mercuryPayments) !!}
                                        )</h2>
                                    <div class="panel-body panel-body-table">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover">
                                                <thead>
                                                <tr>
                                                    <th>Order ID</th>
                                                    <th>Address</th>
                                                    <th>Transaction ID</th>
                                                    <th>Credit Number</th>
                                                    <th>Card Holder</th>
                                                    <th>Amount</th>
                                                    <th>Date Placed</th>
                                                    <th>Placed By</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                @foreach($mercuryPayments as $apprCard)
                                                    <tr class="appr-order-tr-row"
                                                        id="appr-order-tr-row-{!! $apprCard->lni_id !!}">
                                                        <td>{!! $apprCard->order_id !!}</td>
                                                        <td>{!! optional($mercuryPaymentsOrders->get($apprCard->lni_id))->address !!}</td>
                                                        <td>{!! \Modules\Admin\Helpers\StringHelper::highlightWords($apprCard->transaction_id, $term) !!}</td>
                                                        <td>{!! \Modules\Admin\Helpers\StringHelper::highlightWords(str_replace('XXXX-XXXX-', '', $apprCard->cc_last_four), $term) !!}</td>
                                                        <td>{!! \Modules\Admin\Helpers\StringHelper::highlightWords($apprCard->card_holder_name, $term) !!}</td>
                                                        <td>${!! number_format($apprCard->amount, 2) !!}</td>
                                                        <td>{!!  $apprCard->created_date ? date('m/d/Y H:i', $apprCard->created_date) : '' !!}</td>
                                                        <td>{!! optional($apprCard->user)->full_name !!}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @else
                                    <p>No data</p>
                                @endif
                            </div>
                            <div id="al_checks" class="tab-pane fade">
                                @if(isset($alChecks) && count($alChecks))
                                    <h2>Alternative Valuation Checks Applied To Orders ({!! count($alChecks) !!})</h2>
                                    <div class="panel-body panel-body-table">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover">
                                                <thead>
                                                <tr>
                                                    <th>Order ID</th>
                                                    <th>Address</th>
                                                    <th>Check Number</th>
                                                    <th>Check Amount</th>
                                                    <th>Check From</th>
                                                    <th>Date Placed</th>
                                                    <th>Date Created</th>
                                                    <th>Placed By</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                @foreach($alChecks as $alCheck)
                                                    <tr class="al-order-tr-row"
                                                        id="al-order-tr-row-{!! $alCheck->order_id !!}">
                                                        <td>{!! $alCheck->order_id !!}</td>
                                                        <td>{!! optional($alChecksOrders->get($alCheck->order_id))->address !!}</td>
                                                        <td>{!! \Modules\Admin\Helpers\StringHelper::highlightWords($alCheck->check_number, $term) !!}</td>
                                                        <td>${!! number_format($alCheck->amount, 2) !!}</td>
                                                        <td>{!! \Modules\Admin\Helpers\StringHelper::highlightWords($alCheck->check_from, $term) !!}</td>
                                                        <td>{!! $alCheck->date_received ? date('m/d/Y', $alCheck->date_received) : '' !!}</td>
                                                        <td>{!! $alCheck->created_date ? date('m/d/Y H:i', $alCheck->created_date) : '' !!}</td>
                                                        <td>{!! optional($alCheck->user)->full_name !!}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @else
                                    <p>No data</p>
                                @endif
                            </div>
                            <div id="al_cards" class="tab-pane fade">
                                @if(isset($alCards) && count($alCards))
                                    <h2>Alternative Valuation Credit Card Transactions Applied To Orders
                                        ({!! count($alCards) !!}
                                        )</h2>
                                    <div class="panel-body panel-body-table">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover">
                                                <thead>
                                                <tr>
                                                    <th>Order ID</th>
                                                    <th>Address</th>
                                                    <th>Transaction ID</th>
                                                    <th>Credit Number</th>
                                                    <th>Card Holder</th>
                                                    <th>Amount</th>
                                                    <th>Date Placed</th>
                                                    <th>Placed By</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                @foreach($alCards as $alCard)
                                                    <tr class="al-order-tr-row"
                                                        id="al-order-tr-row-{!! $alCard->order_id !!}">
                                                        <td>{!! $alCard->order_id !!}</td>
                                                        <td>{!! optional($alCardsOrders->get($alCard->order_id))->address !!}</td>
                                                        <td>{!! \Modules\Admin\Helpers\StringHelper::highlightWords($alCard->trans_id, $term) !!}</td>
                                                        <td>{!! \Modules\Admin\Helpers\StringHelper::highlightWords(str_replace('XXXX-XXXX-', '', $alCard->credit_number), $term) !!}</td>
                                                        <td>{!! \Modules\Admin\Helpers\StringHelper::highlightWords($alCard->card_name, $term) !!}</td>
                                                        <td>${!! number_format($alCard->amount, 2) !!}</td>
                                                        <td>{!! $alCard->created_date ? date('m/d/Y H:i', $alCard->created_date) : '' !!}</td>
                                                        <td>{!! optional($alCard->user)->full_name !!}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @else
                                    <p>No data</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@stop
