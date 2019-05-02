<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoices</title>
    <style type="text/css">
        .page-break {
            page-break-after: always;
        }

        h1, h2, h3, h4, h5, h6 {
            margin: 0;
        }

        * {
            font-size: 13px;
        }

        table {
            border-collapse: collapse;
        }

        th,
        td {
            padding: 5px 10px;
            border: 1px solid #d7d7d7;
        }

        h1 {
            font-size: 2.5em;
            font-weight: 400;
            color: #293172;
        }

        h2.title {
            color: #293172;
            font-size: 18px;
        }

        h3 {
            margin: 0;
            font-weight: 500;
            font-size: 14px;
        }

        .row {
            width: 100%;
            border: none;
        }

        .row td {
            vertical-align: top;
            padding: 0;
            border: none;
        }

        .header-page-align {
            text-align: right;
        }

        .sub-title-header {
            font-weight: 400;
        }

        .table-holder {
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            overflow: hidden;
            border: 1px solid #d7d7d7;
        }

        .table-holder td {
            word-break: break-all;
            width: 50%;
        }

        .table-top-gray {
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
            width: 99.9%;
        }

        .table-top-gray.xl td {
            border: none;
        }

        .table-top-gray.xl tr:nth-child(2n) td {
            background: #e8e8e8;
        }

        .table-top-gray th {
            border: none;
            background: #666;
            color: #fff;
        }

        .table-top-gray table {
            width: 100%;
        }

        .table-holder tr:first-child > * {
            border-top: none;
        }

        .table-holder tr:last-child > * {
            border-bottom: none;
        }

        .table-holder td:first-child {
            border-left: none;
        }

        .table-holder td:last-child {
            border-right: none;
        }

        .pdf-page {
            margin: 0 auto;
            max-width: 800px;
            font-family: Sans-serif, Arial;
            font-size: 14px;
            border: none;
        }

        .pdf-page.xl {
            max-width: 1200px;
            width: auto;
        }

        .logo {
            height: 51px;
        }

        .header {
            width: 100%;
        }

        .header > * {
            margin-bottom: 15px;
        }

        .header td {
            border: none;
            padding: 0 0 15px;
            vertical-align: bottom;
        }

        .header h1 {
        }

        .card {
            border: 1px solid #d7d7d7;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 15px;
        }

        .card .title {
            background: #666;
            color: #fff;
            padding: 10px;
        }

        .card .body {
            padding: 10px;
            font-size: 13px;
        }

        .card .body * {
            padding: 3px 0;
            font-size: 13px;
        }

        .td-lev-1 {
            padding: 0;
            border: none;
            vertical-align: top;
        }

        .sub-title {
            font-weight: 600;
        }

        .column {
            width: 90%;
        }

        .right-col {
            padding-left: 10%;
        }

        .invoice {
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #d7d7d7;
        }

        .invoice table {
            width: 100%;
        }

        .invoice th,
        .invoice td {
            text-align: center;
        }

        .invoice td {
            border-top: none;
            border-right: none;
        }

        .invoice th {
            border-top: none;
            border-left: none;
        }

        .invoice tr:last-child > * {
            border-bottom: none;
        }

        .description .title {
            margin-bottom: 5px;
        }

        .description table {
            width: 100%;
        }

        .description td div {
            display: inline-block;
        }

        .description-text-block .sub-title {
            margin: 10px 0 0;
        }

        .fees {
            overflow: hidden;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .fees .title {
            margin-bottom: 5px;
        }

        .fees table {
            width: 100%;
        }

        .fees th:first-child {
            text-align: left;
        }

        .fees td:last-child {
            text-align: right;
        }

        /*footer*/
        .info-card {
            border-radius: 5px;
            text-align: center;
            background: #293172;
            margin-bottom: 15px;
            border: 1px solid #293172;
            width: 200px;
        }

        .info-card .title {
            color: #fff;
            padding: 5px 50px;
        }

        .info-card .number {
            font-weight: 700;
            background: #e8e8e8;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
            padding: 10px 0;
        }

        .top-row {
            width: 100%;
        }

        .top-row td {
            padding: 0;
            border: none;
        }

        .copyright > * {
            display: inline-block;
        }

        .copyright > * {
            padding: 0 7px 0 5px;
            border-right: 1px solid;
        }

        .col-4 {
            width: 30%;
            display: inline-block;
            vertical-align: top;
            margin-bottom: 0;
        }

        .receipt {
            vertical-align: bottom;
            width: 33%;
            padding-left: 6%;
        }

        .receipt h2 {
            font-weight: 400;
            text-align: right;
            margin-bottom: 5px;
        }

        .receipt .bottom-row {
            border: 1px solid #d7d7d7;
            border-radius: 8px;
        }

        .receipt .bottom-row .col {
            display: inline-block;
            width: 49%;
            text-align: center;
            padding: 15px 0;
        }

        .receipt .bottom-row .last-col {
            border-left: 1px solid #d7d7d7;
        }

        .info-block {
            padding-bottom: 15px;
        }

        .table-blue table {
            width: 100%;
            text-align: center;
        }

        .table-blue {
            margin-bottom: 15px;
            border-radius: 8px;
            border: 2px solid #18275a;
        }

        .table-blue th {
            background: #18275a;
            color: #fff;
        }

        .table-blue td {
            background: #e8e8e8;
        }
    </style>
</head>
<body>
@foreach($orders as $order)
    <div class="pdf-page">

        <table class="header">
            <tr>
                <td>
                    <img class="logo" src="{{ config('app.url') . '/images/landmark-small-logo-new.png' }}" alt="Logo">
                </td>
                <td style="text-align: right">
                    <h1>Invoice</h1>
                </td>
            </tr>
        </table>

        <div class="content">

            <table class="row">
                <tr>
                    <td class="td-lev-1">
                        <div class="column left-col">

                            <div class="card">

                                <h3 class="title">To:</h3>

                                <div class="body">
                                    <div class="sub-title">{{ $order->groupData->descrip }}</div>
                                    <div>{{ trim($order->groupData->address1 . ' ' . $order->groupData->address2) }}</div>
                                    <div>{{ $order->groupData->city }}, {{ $order->groupData->state }}
                                        , {{ $order->groupData->zip }}</div>
                                </div>

                            </div>

                            <div class="card">

                                <h3 class="title">Remit Payment To:</h3>

                                <div class="body">
                                    <div class="sub-title">{{ \App\Models\Tools\Setting::getSetting('company_name') }}</div>
                                    <div>{{ trim(\App\Models\Tools\Setting::getSetting('company_address') . ' ', \App\Models\Tools\Setting::getSetting('company_address2')) }}</div>
                                    <div>{{ trim(\App\Models\Tools\Setting::getSetting('company_city') . ' ' . \App\Models\Tools\Setting::getSetting('company_state') . ' ' . \App\Models\Tools\Setting::getSetting('company_zip')) }}</div>
                                    <div>Telephone
                                        Number: {{ \App\Models\Tools\Setting::getSetting('company_phone') }}</div>
                                </div>

                            </div>

                        </div>
                    </td>

                    <td class="td-lev-1">
                        <div class="column right-col">

                            <div class="invoice">
                                <table>
                                    <tbody>
                                    <tr>
                                        <th style="border-top: none; border-left: none; text-align: center;">Invoice Number:</th>
                                        <td style="border-top: none; border-right: none; text-align: center; vertical-align: middle;">{{ $order->id }}</td>
                                    </tr>
                                    <tr style="vertical-align: center;">
                                        <th style="border-bottom: none; border-left: none; text-align: center;">Ordered Date:</th>
                                        <td style="border-bottom: none; border-right: none; text-align: center; vertical-align: middle;">{{ date('M d Y', strtotime($order->ordereddate)) }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </td>
                </tr>


            </table>

            <div class="description">

                <h2 class="title">Description</h2>

                <div class="table-holder">
                    <table>
                        <tbody>
                        <tr>
                            <td>
                                <div class="sub-title">Purchaser/Borrower:</div>
                                <div>{{ $order->borrower . ' ' . $order->groupData->borrower_suffix }}</div>
                            </td>
                            <td>
                                <span class="sub-title">Lender:</span>
                                <span>{{ $order->groupData->descrip }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="sub-title">Property Address:</div>
                                <div>{{ ucwords(strtolower(trim($order->propaddress1 . ' ' . $order->propaddress2))) }}</div>
                            </td>
                            <td>
                                <div class="sub-title">Loan Refrence Number:</div>
                                <div>{{ $order->loanrefnum ?? '--' }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="sub-title">City</div>
                                <div>{{ $order->propcity }}</div>
                                <div class="sub-title">State:</div>
                                <div>{{ $order->propstate }}</div>
                                <div class="sub-title">ZIP:</div>
                                <div>{{ $order->propzip }}</div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="sub-title">County:</div>
                                <div>{{ optional(\App\Models\Management\ZipCode::where('zip_code', $order->propzip)->first())->county }}</div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="description-text-block">

                    <div class="sub-title">Legal Description:</div>

                    <p>{{ $order->legal_descrip ?? '--'}}</p>

                </div>

            </div>

            @php
                list($chargeAmount, $user, $group, $addendas, $history, $checks, $adjustments) = \Modules\Admin\Services\InvoiceService::getHistoryData($order);
                // Did we have sales tax

                if($group->sub_deliveryfee_frominvoice == "Y" AND $group->mail_appr_addfee != 0 AND $order->final_appraisal_borrower_sendtopostalmail == "Y") {
                    $order->amountdue -= ($group->mail_appr_addfee * $order->final_appraisal_postal_count);
                }

            @endphp

            <div class="fees">

                <h2 class="title">Fees</h2>

                <div class="table-top-gray">
                    <table>
                        <tbody>
                        <tr>
                            <th>Product</th>
                            <th>Amount</th>
                        </tr>
                        @if($order->trip_fee == "Y")
                            <tr>
                                <td>Inspection/Trip Fee</td>
                                <td class="align-right">$ {{ number_format($chargeAmount, 2) }}</td>
                            </tr>
                        @endif

                        @if($order->loantype == 2 AND $group->item_fhainvoice == "Y" AND $order->acceptedby != 0)
                            <tr>
                                <td>{{ $order->apprTypeName }} - Appraiser Fee</td>
                                <td class="align-right">$ {{ number_format($order->split_amount, 2) }}</td>
                            </tr>
                        @elseif($order->loantype == 2)
                            <tr>
                                <td>{{ $order->apprTypeName }}</td>
                                <td class="align-right">$ {{ number_format($chargeAmount, 2) }}</td>
                            </tr>
                        @elseif($group->item_allinvoice == "Y" AND $order->acceptedby != 0)
                            <tr>
                                <td>{{ $order->apprTypeName }} - Appraiser Fee</td>
                                <td class="align-right">$ {{ number_format($order->split_amount, 2) }}</td>
                            </tr>
                        @else
                            <tr>
                                <td>{{ $order->apprTypeName }}</td>
                                <td class="align-right">$ {{ number_format($chargeAmount, 2) }}</td>
                            </tr>
                        @endif

                        @if($order->loantype == 2 AND $order->trip_fee != "Y" AND $group->item_fhainvoice == "Y" AND $order->acceptedby != 0)
                            @if($addendas && count($addendas) && $order->trip_fee != "Y")
                                @foreach($addendas as $addenda)
                                    <tr>
                                        <td>{{ $addenda->descrip }}</td>
                                        <td class="align-right">--</td>
                                    </tr>
                                @endforeach
                            @endif

                            <tr>
                                <td>{{ $order->apprTypeName }}
                                    - {{ \App\Models\Tools\Setting::getSetting('company_name') }} Management and
                                    Fanatical Service Fee
                                </td>
                                <td class="align-right">
                                    $ {{ number_format($order->amountdue - $order->split_amount,2) }}</td>
                            </tr>

                        @elseif($order->trip_fee != "Y" AND $group->item_allinvoice == "Y" AND $order->acceptedby != 0)
                            @if($addendas && count($addendas) && $order->trip_fee != "Y")
                                @foreach($addendas as $addenda)
                                    <tr>
                                        <td>{{ $addenda->descrip }}</td>
                                        <td class="align-right">--</td>
                                    </tr>
                                @endforeach
                            @endif

                            <tr>
                                <td>{{ $order->apprTypeName }}
                                    - {{ \App\Models\Tools\Setting::getSetting('company_name') }} Management and
                                    Fanatical Service Fee
                                </td>
                                <td class="align-right">
                                    $ {{ number_format($order->amountdue - $order->split_amount,2) }}</td>
                            </tr>
                        @endif

                        @if($order->status == 10 AND $order->refund_date != "" AND $order->refund_date)
                            <tr>
                                <td>Order Cancelation - {{ date("M j, Y g:i A",strtotime($order->refund_date)) }}</td>
                                <td class="align-right">-$ {{ number_format($order->amountdue,2) }}</td>
                            </tr>
                            @php $order->amountdue = 0; @endphp
                        @elseif($order->status == 10 AND !$order->refund_date)
                            <tr>
                                <td>Order Cancelation</td>
                                <td class="align-right">-$ {{ number_format($order->amountdue,2) }}</td>
                            </tr>
                            @php $order->amountdue = 0; @endphp
                        @elseif($order->status == 10 AND $order->refund_date == "")
                            <tr>
                                <td>Order Cancelation</td>
                                <td class="align-right">-$ {{ number_format($order->amountdue,2) }}</td>
                            </tr>
                            @php $order->amountdue = 0; @endphp
                        @endif

                        @if($order->sales_tax > 0)
                            @php
                                list($postal, $states, $salesMsg, $taxPrice) = \Modules\Admin\Services\InvoiceService::getTaxData($order, $chargeAmount);
                            @endphp
                            <tr>
                                <td>{{ $salesMsg }}</td>
                                <td class="align-right">$ {{ number_format($taxPrice, 2) }}</td>
                            </tr>
                            @php $order->amountdue = 0; @endphp
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="fees">

                <h2 class="title">Payments</h2>

                <div class="table-top-gray">
                    <table>
                        <tbody>
                        <tr>
                            <th>Date</th>
                            <th>Payment</th>
                            <th>Amount</th>
                        </tr>
                        @if($history)
                            @foreach($history as $item)
                                @if($item->is_success != 1 || $item->is_void == 1 || !$item->is_visible)
                                    @php continue; @endphp
                                @endif
                                @php
                                    list($amount, $info) = \Modules\Admin\Services\InvoiceService::getPaymentHistoryData($item);
                                @endphp
                                <tr>
                                    <td><{{ date('m/d/Y H:i:s', $item->created_date) }}</td>
                                    <td>{{ $info }}</td>
                                    <td class="align-right">{{ $amount }}</td>
                                </tr>
                            @endforeach
                        @endif

                        @if($checks && count($checks))
                            @foreach($checks as $item)
                                @if(!$item->is_visible)
                                    @php continue; @endphp
                                @endif
                                @php
                                    list($amount, $info) = \Modules\Admin\Services\InvoiceService::getCheckHistoryData($item);
                                @endphp
                                <tr>
                                    <td>{{ date('m/d/Y H:i:s', $item->created_date) }}</td>
                                    <td>{{ $info }}</td>
                                    <td class="align-right">{{ $amount }}</td>
                                </tr>
                            @endforeach
                        @endif

                        @if($adjustments)
                            @foreach($adjustments as $item)
                                @if(!$item->is_visible)
                                    @php continue; @endphp
                                @endif
                                @php
                                    list($amount, $info) = \Modules\Admin\Services\InvoiceService::getAdjustmentsData($item);
                                @endphp
                                <tr>
                                    <td>{{ date('m/d/Y H:i:s', $item->created_date) }}</td>
                                    <td>{{ $info }}</td>
                                    <td class="align-right">{{ (getAdminAmountTypeSymbol($item->amount_type)) . $amount }}</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <div class="footer">

            <table class="top-row">
                <tbody>
                <tr>
                    <td align="right">
                        <div class="info-card">

                            <h4 class="title">Amount Due</h4>

                            <div class="number">
                                $ {{ \Modules\Admin\Services\InvoiceService::getAmountDue($order) }}</div>

                        </div>
                    </td>
                </tr>
                </tbody>


            </table>

            <div class="copyright">
                <b>{{ \App\Models\Tools\Setting::getSetting('company_name') }}</b>
                <span>{{ trim(\App\Models\Tools\Setting::getSetting('company_address') . ' ', \App\Models\Tools\Setting::getSetting('company_address2')) }}</span>
                <span>P: {{ \App\Models\Tools\Setting::getSetting('company_phone') }}</span>
                <span>F: {{ \App\Models\Tools\Setting::getSetting('company_phone') }}</span>
            </div>

        </div>

    </div>
    @if($order != $orders->last())
        <div class="page-break"></div> @endif
@endforeach

</body>
</html>
