@extends('admin::layouts.master')

@section('title', 'ASC Appraiser List')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Management', 'url' => '#'],
  ['title' => 'ASC Appraiser List', 'url' => route('admin.management.asc-licenses')]
]
])
@endcomponent
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="datatable">
                                    <thead>
                                    <tr>
                                        <th>Firstname</th>
                                        <th>Lastname</th>
                                        <th>Status</th>
                                        <th>License Number</th>
                                        <th>Expiration</th>
                                        <th>Type</th>
                                        <th>Address</th>
                                        <th>City</th>
                                        <th>State</th>
                                        <th>Zip</th>
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
<script>
    $(function() {
        $app.datatables('#datatable', '{!! route('admin.management.asc-licenses.data') !!}', {
            columns: [
                { data: 'fname', name: 'fname' },
                { data: 'lname', name: 'lname' },
                { data: 'status', name: 'status' },
                { data: 'lic_number', name: 'lic_number'},
                { data: 'exp_date', name: 'exp_date'},
                { data: 'lic_type', name: 'lic_type'},
                { data: 'address', name: 'street'},
                { data: 'city', name: 'city'},
                { data: 'state', name: 'state'},
                { data: 'zip', name: 'zip'}
            ],
            order : [ [ 1, 'asc' ], [ 0, 'asc' ] ]
        });
    });
</script>
@endpush