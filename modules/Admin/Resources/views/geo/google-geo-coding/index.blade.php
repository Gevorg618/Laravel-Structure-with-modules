@extends('admin::layouts.master')

@section('title', 'Google Geo Coding')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Admin User', 'url' => '#'],
      ['title' => 'Google Geo Coding', 'url' => route('admin.geo.google-coding.index')]
    ]
])
@endcomponent

@section('content')
    
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body panel-body-table">
                        <form id="myform">
                            <div class="form-group col-md-6">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Order ID <span class="required" aria-required="true"></span></label>
                                <div class="col-sm-4">
                                  <input type="text" class="form-control" id="orderId">
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <div class="col-lg-12 col-xs-12">
                                    <button type="button" class="btn btn-primary pull-left load-info" id="order_geo_code">Geo Code</button>
                                </div>
                            </div>
                        </form> 
                        <div class="col-md-6" id="order_address_info">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <p><b>Over Query Limit: @if($ordersCount > 0 ) <span style="color:red;">Yes</span> @else No @endif</b> </p>
        </div>
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <h2><b>Orders</b></h2>
                    <div class="panel-body panel-body-table">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="datatable">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Address</th>
                                    <th>Status</th>
                                    <th>Failed?</th>
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
@stop

@push('scripts')
    <script src="{{ masset('js/modules/admin/geo/google-coding/index.js') }}"></script>
    <script>
        $(function() {
            $app.datatables('#datatable', '{!! route('admin.geo.google-coding.data') !!}', {
                columns: [
                    { data: 'id' },
                    { data: 'date' },
                    { data: 'address' },
                    { data: 'status' },
                    { data: 'failed' },
                    { data: 'options', name: 'options', orderable: false, searchable: false}
                ],
                order : [ [ 0, 'asc' ] ],
                iDisplayLength : 10
            });
        });
    </script>
@endpush