@extends('admin::layouts.master')

@section('title', 'Delayed Pipeline')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Appraisal Pipeline', 'url' => '#'],
        ['title' => 'Delayed Pipeline', 'url' => route('admin.appraisal-pipeline.delayed-pipeline')]
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
                                    <th>Order</th>
                                    <th>Client</th>
                                    <th>Address</th>
                                    <th>Status</th>
                                    <th>Team</th>
                                    <th>Reason</th>
                                    <th>Last Log</th>
                                    <th>Actions</th>
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
            $app.datatables('#datatable', '{!! route('admin.appraisal-pipeline.delayed-pipeline.data') !!}', {
                columns: [
                    { "data": "ordereddate" },
                    { "data": "client" },
                    { "data": "address" },
                    { "data": "status" },
                    { "data": "team_title" },
                    { "data": "delayed_reason" },
                    { "data": "last_log" },
                    { "data": "action" }
                ],
                createdRow: function( row, data, dataIndex ) {
                    $( row ).find('td:eq(2)').html($('<div />').html(data.address).text());
                },
                searching: false,
                ordering:  false
            });

        });

    </script>

@endpush
