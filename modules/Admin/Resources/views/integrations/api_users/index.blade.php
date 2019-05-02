@extends('admin::layouts.master')

@section('title', 'API Users')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Integrations', 'url' => '#'],
        ['title' => 'API Users', 'url' => route('admin.integrations.api-users')]
    ],
    'actions' => [
        ['title' => 'Add User', 'url' => route('admin.integrations.api-users.create')],
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
                                        <th>Title</th>
                                        <th>Mode</th>
                                        <th>Status</th>
                                        <th>Full Access</th>
                                        <th>API Key</th>
                                        <th>Created</th>
                                        <th>Last Used</th>
                                        <th>Successful Requests</th>
                                        <th>Failed Requests</th>
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
            $app.datatables('#datatable', '{!! route('admin.integrations.api-users.data') !!}', {
                columns: [
                    {data: 'title', orderable: false},
                    {data: 'in_production', orderable: false},
                    {data: 'is_active', orderable: false},
                    {data: 'is_visible_all', orderable: false},
                    {data: 'api_key', orderable: false},
                    {data: 'created', orderable: false},
                    {data: 'last_call', orderable: false},
                    {data: 'passed_calls', orderable: false},
                    {data: 'failed_calls', orderable: false},
                    {data: 'action', orderable: false, searchable: false}
                ],
                "searching": false
            });
        });

    </script>
    <link href="{{ masset('css/integrations/api_users/options.css') }}" rel="stylesheet" />
@endpush
