<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta charset="UTF-8">
    <title>Statement</title>
    <style type="text/css">
        .page-break {
            page-break-after: always;
        }

        h1, h2, h3, h4, h5, h6 {
            margin: 0;
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

        * {
            font-size: 13px;
        }

        .row {
            width: 100%;
        }

        .td-lev-1 {
            padding: 0;
            border: none;
            vertical-align: top;
        }

        .row tbody {
            vertical-align: top;
        }

        .header-page-align {
            text-align: right;
        }

        .sub-title-header {
            font-weight: 400;
            color: #293172;
        }

        .table-top-gray {
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
            width: 100%;
        }

        .table-top-gray.xl td {
            border: none;
        }

        .table-top-gray.xl tr:nth-child(2n) td {
            background: #e8e8e8;
        }

        .table-top-gray.xl .gray td {
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

        .pdf-page {
            margin: 0 auto;
            max-width: 800px;
            font-family: Sans-serif, Arial;
        }

        .pdf-page.xl {
            max-width: 1200px;
        }

        .logo {
            height: 51px;
        }

        .header {
            width: 100%;
        }

        .header >* {
            margin-bottom: 15px;
        }

        .header td {
            border: none;
            padding: 0 0 15px;
            vertical-align: bottom;
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
            padding: 5px 10px;
        }

        .card .body {
            padding: 10px;
            font-size: 13px;
        }

        .card .body * {
            padding: 3px 0;
            font-size: 13px;
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

        .invoice tr:last-child >* {
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

        .copyright >* {
            padding: 0 7px 0 5px;
        }

        .col-4 {
            vertical-align: top;
            margin-bottom: 0;
        }

        .receipt {
            padding-left: 6%;
        }

        .receipt h2 {
            font-size: 21px;
            color: #293172;
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
            margin-bottom: 15px;
        }

        .info-block td {
            padding: 0 7px;
            border: none;
        }

        .table-blue table {
            width: 100%;
            text-align: center;
        }

        .table-blue {
            margin-bottom: 15px;
            border-radius: 8px;
            border: 1px solid #18275a;
            overflow: hidden;
        }

        .table-blue th {
            background: #18275a;
            color: #fff;
            border-top: 0;
        }

        .table-blue td {
            background: #e8e8e8;
        }

        .footer {
          position: absolute;
          bottom: 0;
        }
    </style>
</head>
<body>
@php $total = 0; @endphp
@foreach($items as $id => $item)
<div class="pdf-page xl">

    <table class="header">
        <tr>
            <td>
                <img class="logo" src="{{ companyLogo() }}" alt="Logo">
            </td>
            <td style="text-align: right;">
                <div class="header-page-align">
                    <h1>Statement</h1>
                    <h2 class="sub-title-header">
                    {{ count($item) - 2  }}
                     Orders</h2>
                </div>
            </td>
        </tr>
    </table>

    <div class="content">

        <table class="row info-block">
            <tbody>
            <tr>
                <td>
                    <div class="card col-4">

                        <h3 class="title">To:</h3>

                        <div class="body">
                            <div>{{ $item['client_data']['title'] }}</div>
                            <div>{{ trim($item['client_data']['address'] . ' ' . $item['client_data']['address2']) }}</div>
                        </div>

                    </div>
                </td>
                <td>
                    <div class="card col-4">

                        <h3 class="title">Remit Payment To:</h3>

                        <div class="body">
                            <div class="sub-title">{{ \App\Models\Tools\Setting::getSetting('company_name') }}</div>
                            <div>{{ trim(\App\Models\Tools\Setting::getSetting('company_address') . ' ', \App\Models\Tools\Setting::getSetting('company_address2')) }}</div>
                            <div>{{ trim(\App\Models\Tools\Setting::getSetting('company_city') . ' ' . \App\Models\Tools\Setting::getSetting('company_state') . ' ' . \App\Models\Tools\Setting::getSetting('company_zip')) }}</div>
                            <div>Telephone
                                Number: {{ \App\Models\Tools\Setting::getSetting('company_phone') }}</div>
                        </div>

                    </div>
                </td>
                <td style="vertical-align: bottom">
                    <div class="col-4 receipt">

                        <h2>Due Upon Receipt</h2>

                        <div class="bottom-row">

                            <div class="col">{{ date('M d Y') }}</div>
                        </div>

                    </div>
                </td>
            </tr>
            </tbody>
        </table>

        <div class="table-top-gray xl">
            <table>
                <tbody>
                <tr>
                    <th>Order ID</th>
                    <th>Date Completed</th>
                    <th>Borrower</th>
                    <th>Product</th>
                    <th>Address</th>
                    <th>Invoice Total</th>
                    <th>Amount Paid</th>
                    <th>Balance Due</th>
                </tr>
                @foreach($item as $key => $order)
                    @if($key !== 'client_data' && $key !== 'counts') 
                        <tr class="">
                            <td>{{ $order['id'] }}</td>
                            <td>{{ date('m/d/Y', strtotime($order['date_delivered'])) }}</td>
                            <td>{{ $order['borrower'] }}</td>
                            <td>{{ $order['appr_type_name'] }}</td>
                            <td>{{ $order['address'] . ' ' . $order['city'] . ', ' . $order['state'] }}</td>
                            <td>$ {{ number_format($order['amount'], 2) }}</td>
                            <td>$ {{ number_format($order['paidamount'], 2) }}</td>
                            <td>$ {{ number_format($order['amount'] - $order['paidamount']) }}</td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>

    </div>

    <div class="footer">

        <div class="table-blue">
            <table>
                <tbody>
                <tr>
                    <th style="border-left: 0">60 Days</th>
                    <th>60-90 Days</th>
                    <th>90-120 Days</th>
                    <th>120+ Days</th>
                    <th>Total Past Due</th>
                    <th style="border-right: 0">Total Due</th>
                </tr>
                <tr>
                    <td>$ {{ number_format($item['counts']['stats']['totals'][60], 2) }}</td>
                    <td>$ {{ number_format($item['counts']['stats']['totals']['60-90'], 2) }}</td>
                    <td>$ {{ number_format($item['counts']['stats']['totals']['90-120'], 2) }}</td>
                    <td>$ {{ number_format($item['counts']['stats']['totals'][120], 2) }}</td>
                    <td>$ {{ number_format($item['counts']['stats']['totals']['pastdue'], 2) }}</td>
                    <td>$ {{ number_format($item['counts']['stats']['totals']['total'], 2) }}</td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="copyright">
            <b>{{ \App\Models\Tools\Setting::getSetting('company_name') }}</b>
            <span>{{ trim(\App\Models\Tools\Setting::getSetting('company_address') . ' ', \App\Models\Tools\Setting::getSetting('company_address2')) }}</span>
            <span>P: {{ \App\Models\Tools\Setting::getSetting('company_phone') }}</span>
            <span>F: {{ \App\Models\Tools\Setting::getSetting('company_phone') }}</span>
        </div>
    </div>
</div>
@php $total++; @endphp
@if($total < count($items))
<div class="page-break"></div>
@endif

@endforeach

</body>
</html>