@extends('admin::layouts.master')

@section('title', 'AMC Registrations')
@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Customizations', 'url' => '#'],
      ['title' => 'AMC Registrations', 'url' => route('admin.management.amc-licenses')]
    ],
    'actions' => [
      ['title' => 'Add AMC Registration', 'url' => route('admin.management.amc-licenses.create')],
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
                                    <th>State</th>
                                    <th>License</th>
                                    <th>Added</th>
                                    <th>License Expiration</th>
                                    <th>Secretary State Expiration</th>
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
        $app.datatables('#datatable', '{!! route('admin.management.amc-licenses.data') !!}', {
            columns: [
                { data: 'state' },
                { data: 'reg_number' },
                { data: 'created_at' },
                { data: 'expires' },
                { data: 'sec_expires' },
                { data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            order : [ [ 0, 'asc' ] ]
        });
    });
</script>
@endpush