@extends('admin::layouts.master')

@section('title', 'Statistics & User Tracking')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Statistics & User Tracking', 'url' => '#'],
      ['title' => 'Sales Commission Report', 'url' => route('admin.statistics.index')]
    ]
])
@endcomponent

@section('content')
 
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <h2><b>Sales Report</b></h2>
                    <div class="panel-body panel-body-table">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        {{ Form::open([ 'route' => 'admin.statistics.sales-commission.show', 'class' => 'form-group', 'id' => 'order-details', 'enctype' => 'multipart/form-data'])}}
                            <input type="hidden" id="request_type" name="request_type" value="team">
                            @include('admin::statistics.sales-commission.partials._form', ['button_label' => 'Show'])
                        {{ Form::close() }}                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row hidden" id="nav-content">
        <div class="col-lg-12">
            <div class="tab-content">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        <div class="panel-body panel-body-table">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="orders-datatable">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Type</th>
                                        <th>Delivered</th>
                                        <th>Client</th>
                                        <th>Address</th>
                                        <th>Borrower</th>
                                        <th>Status</th>
                                        <th>Type</th>
                                        <th>Payment</th>
                                        <th>Client Fee</th>
                                        <th>Appr. Fee</th>
                                        <th>Margin</th>
                                        <th>Comm.</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@push('scripts')
    {{-- <script src="{{ masset('js/modules/admin/statistics/dashboard/index.js') }}"></script> --}}
    <script>
        
    $(document).ready(function() {

        

        $('#show_button').on('click',function(e) {

            var date_from = $('#date_from').val();
            var date_to = $('#date_to').val();
            var dateType = $('#datetype').val();
            var userData = $('#user_data').val();
            var requestData = {
                    url: '/admin/statistics-user-tracking/sales-commission/show',
                    data: {date_from:date_from, date_to:date_to, date_type:dateType, user_data:userData}
                };

            $app.datatables('#orders-datatable', requestData, {
                columns: [
                     {data: 'id'},
                     {data: 'client_type'},
                     {data: 'delivered'},
                     {data: 'client'},
                     {data: 'address'},
                     {data: 'borrower'},
                     {data: 'status'},
                     {data: 'type'},
                     {data: 'payment'},
                     {data: 'client_fee'},
                     {data: 'appr_fee'},
                     {data: 'margin'},
                     {data: 'comm'}
                ],
                iDisplayLength: 10,
                lengthMenu: [ 10, 25, 50, 75, 100 ],
                order : false,
                retrieve: false,
                destroy: true,
                searchable: false,
                searching: false
            });
        });
    });        
    </script>
@endpush