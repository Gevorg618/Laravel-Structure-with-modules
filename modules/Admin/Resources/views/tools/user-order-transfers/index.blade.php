@extends('admin::layouts.master')

@section('title', 'Auto Select Turn Times')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'User Order Transfers', 'url' => route('admin.tools.user-order-transfers.index')]
    ]
])
@endcomponent
@push('style')
<link rel="stylesheet" href="{{ masset('css/autocomplete-pricing/appraiser-fee-pricing-form.css') }}">
@endpush
@section('content')
    <div class="row">

        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <h2><b>Process</b></h2>
                    <div class="panel-body panel-body-table">
                        <form id="myform">
                            <div class="form-group col-md-6">
                                
                                <label name="from_user" class="control-label col-lg-3 col-xs-12">From ID/Email
                                    <span class="required" aria-required="true"></span>
                                </label>
                                <div class="col-lg-12 col-xs-12">
                                    {!! Form::text('from_user', null, ['class' => 'typeahead  form-control', 'id' => 'from_user']) !!}
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label name="to_user" class="control-label col-lg-3 col-xs-12">To ID/Email
                                    <span class="required" aria-required="true"></span>
                                </label>
                                <div class="col-lg-12 col-xs-12">
                                    {!! Form::text('to_user', null, ['class' => 'typeahead form-control', 'id' => 'to_user']) !!}
                                    <span class="help-block to_user-error-block"></span>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="col-lg-12 col-xs-12">
                                    <button type="button" class="btn btn-primary pull-right load-info">Load User Info</button>
                                </div>
                            </div>
                        </form> 
                    </div>
                </div>
            </div>
        </div>

        <!-- content load info with ajax -->
        <div class="col-md-12" id="user_info_content"></div>
        <!-- content load info with ajax ///-->

        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <h2><b>Previous Transactions</b>
                    </h2>
                    <div class="panel-body panel-body-table">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="datatable">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>From User</th>
                                    <th>To User</th>
                                    <th>Date</th>
                                    <th>Processed By</th>
                                    <th>Order Count</th>
                                    <th>Options</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @include('admin::tools.user-order-transfers.partials._modal')

    @if(Session::has('transfer-log-id'))

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <script type="text/javascript">
            var ordertransferId = {{Session::get('transfer-log-id')}}

            $(document).ready(function() {
                
                $.get( '/admin/tools/user-order-transfers/transfered-orders/'+ordertransferId, function( data ) {
                    $("#content_orders").html(data);
                    $("#modal-orders").modal('show');
                });

            });

        </script>
    @endif

@stop
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script> 
    <script src="{{ masset('js/modules/admin/tools/user-order-transfers/user-order.js') }}"></script>
    <script>

        $('input.typeahead').typeahead({
            source:  function (query, process) {
            return $.get('{!! route('admin.tools.user-order-transfers.search') !!}', { query: query }, function (data) {
                    return process(data);
                });
            }
        });

        $(function() {
            $app.datatables('#datatable', '{!! route('admin.tools.user-order-transfers.orders') !!}', {
                columns: [
                    { data: 'id' },
                    { data: 'from_user' },
                    { data: 'to_user' },
                    { data: 'created_date' },
                    { data: 'proccessed_by' },
                    { data: 'order_count' },
                    { data: 'options', name: 'options', orderable: false, searchable: false}
                ],
                order : [ [ 0, 'asc' ] ],
                iDisplayLength : 10
            });
        });
    </script>
@endpush

