@extends('admin::layouts.master')

@section('title', 'FHA Licenses')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Management', 'url' => '#'],
  ['title' => 'FHA Licenses', 'url' => route('admin.management.fha-licenses.index')]
]
])
@endcomponent
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body panel-body-table">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="datatable">
                                <thead>
                                <tr>
                                    <th>Firstname</th>
                                    <th>Lastname</th>
                                    <th>Company</th>
                                    <th>Number</th>
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
@stop

@push('scripts')
<script>
    $(function() {
        $app.datatables('#datatable', '{!! route('admin.management.fha-licenses.data') !!}', {
            columns: [
                { data: 'firstname', name: 'firstname' },
                { data: 'lastname', name: 'lastname' },
                { data: 'company', name: 'company' },
                { data: 'license_number', name: 'license_number'},
                { data: 'expiration', name: 'expiration'},
                { data: 'license_type', name: 'license_type'},
                { data: 'address', name: 'address'},
                { data: 'city', name: 'city'},
                { data: 'state', name: 'state'},
                { data: 'zip', name: 'zip'}
            ],
            order : [ [ 1, 'asc' ], [ 0, 'asc' ] ]
        });
    });
</script>
@endpush