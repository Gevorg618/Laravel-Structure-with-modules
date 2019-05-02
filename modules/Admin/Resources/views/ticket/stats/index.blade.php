

@extends('admin::layouts.master')

@section('title', 'Ticket Statistics')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Tickets', 'url' => '/admin/ticket/manager'],
  ['title' => 'Ticket Stats', 'url' => route('admin.ticket.stats.index')]
],
'actions' => [

]
])
@endcomponent
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div id="entire_batch">

                        {!! Form::open(['method' => 'post', 'url' => '/admin/ticket_stats', 'class' => 'form-inline', 'id' => 'stats_form']) !!}

                        <div class="form-group row">
                            <label for="datefrom" class="control-label">Date <span class="required"></span></label>
                            {!! Form::text('datefrom', date('Y-m-d'), [
                                'size' => '30',
                                'style' => 'width:145px;margin-right:4px;',
                                'class' => 'datepicker form-control',
                                'readonly' => 'readonly'
                            ]) !!}
                            {!! Form::text('dateto', date('Y-m-d'), [
                                'size' => '30',
                                'style' => 'width:145px;',
                                'class' => 'form-control datepicker',
                                'readonly' => 'readonly'
                            ]) !!}

                        </div>
                        <div class="clear"></div>

                        <div class="form-group row" style="margin-top: 10px">
                            <input type='reset' class='btn btn-info' id='reset_filters' name='reset' value='Reset' />
                            <input type='submit' class='btn btn-success' id='submit' name='submit' value='Show' />
                        </div>
                        <div class="clear"></div>
                        {!! Form::close() !!}

                    </div>
                    <div class="clear"></div>


                    <table style="margin-top: 10px" cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped table-condenced" id="total-daily-order-rows">

                    </table>

                    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped table-condenced" id="daily-order-rows">

                    </table>

                    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped table-condenced" id="daily-order-rows-user">

                    </table>



                    <h1>CSV Team Specific Report</h1>
                    <p>Will create a CSV report for total tickets closed on a daily basis for the time range specified below.</p>

                    {!! Form::open(['url' => route('admin.ticket.stats.export'), 'method' => 'POST', 'class' => 'form-inline']) !!}

                    <div class="form-group row" style="position: relative">
                        <label for="datefrom" class="control-label">Date <span class="required"></span></label>
                        {!! Form::text('datefrom', date('Y-m-d'), [
                            'size' => '30',
                            'style' => 'width:100px;margin-right:4px;',
                            'class' => 'form-control datepicker',
                            'readonly' => 'readonly'
                        ]) !!}
                        {!! Form::text('dateto', date('Y-m-d'), [
                            'size' => '30',
                            'style' => 'width:100px;',
                            'class' => 'form-control datepicker',
                            'readonly' => 'readonly'
                        ]) !!}
                        {!! Form::select('type', [
                            'created_date' => 'Created',
                            'closed_date' => 'Closed'
                        ], [
                            'style' => 'width:100px;margin-bottom:0px;margin-top:0px;',
                            'class' => 'form-control'
                        ]) !!}

                    </div>
                    <div class="clear"></div>

                    <div class="form-group row" style="margin-top: 10px">
                        <input type='reset' class='btn btn-info' id='reset_filters' name='reset' value='Reset' />
                        <input type='submit' class='btn btn-success' id='submit' name='submit' value='Download Report' />
                    </div>
                    <div class="clear"></div>
                    {!! Form::close() !!}


                    <div id="order_modal_show_tickets" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="order_modalLabel" aria-hidden="true">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 id="order_modal_show_tickets_title"></h3>
                        </div>
                        <div class="modal-body" id="order_modal_show_tickets_content">
                        </div>
                        <div class="modal-footer">
                            <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.datepicker').datetimepicker({
                format: 'YYYY-MM-DD',
                ignoreReadonly: true,
                widgetPositioning: {
                    horizontal: 'auto',
                    vertical: 'auto'
                }
            });
            $('#stats_form').submit(function (event) {
                event.preventDefault();
                var action = $(this).action;
                var data = $(this).serializeArray();
                $.post(action, data).done(function (data) {
                    if (data.total_daily_order_rows) {
                        $('#total-daily-order-rows').html(data.total_daily_order_rows)
                    }
                    if (data.daily_order_rows) {
                        $('#daily-order-rows').html(data.daily_order_rows);
                    }
                    if (data.daily_order_rows_user) {
                        $('#daily-order-rows-user').html(data.daily_order_rows_user);
                    }
                });
            });
        })
    </script>
@endpush