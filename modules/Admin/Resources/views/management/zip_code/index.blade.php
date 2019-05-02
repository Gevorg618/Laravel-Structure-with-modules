@extends('admin::layouts.master')

@section('title', 'ZIP Code Manager')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Management', 'url' => '#'],
  ['title' => 'ZIP Code Manager', 'url' => route('admin.management.zipcodes')]
],
'actions' => [
  ['title' => 'Add Zip Code', 'url' => route('admin.management.zipcodes.create')],
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
                                    <th>Zip Code</th>
                                    <th>State</th>
                                    <th>County</th>
                                    <th>City</th>
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
<script>
    $(function() {
        $app.datatables('#datatable', '{!! route('admin.management.zipcodes.data') !!}', {
            columns: [
                { data: 'zip_code' },
                { data: 'state' },
                { data: 'county' },
                { data: 'city' },
                { data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            order : [ [ 0, 'asc' ] ]
        });
    });
</script>
@endpush