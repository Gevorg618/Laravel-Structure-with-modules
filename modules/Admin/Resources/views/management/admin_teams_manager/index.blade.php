@extends('admin::layouts.master')

@section('title', 'Admin Teams Manager')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Admin User', 'url' => '#'],
        ['title' => 'Admin Teams Manager', 'url' => route('admin.management.admin-teams-manager')]
    ],
    'actions' => [
        ['title' => 'Add Team', 'url' => route('admin.management.admin-teams-manager.create')],
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
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Key</th>
                                        <th>Type</th>
                                        <th>Members</th>
                                        <th>Clients</th>
                                        <th>States</th>
                                        <th>Active</th>
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
            $app.datatables('#datatable', '{!! route('admin.management.admin-teams-manager.data') !!}', {
                columns: [
                    { data: 'id', orderable: false, searchable: false },
                    { data: 'team_title', orderable: false },
                    { data: 'team_key', orderable: false },
                    { data: 'team_type', orderable: false },
                    { data: 'members', orderable: false, searchable: false },
                    { data: 'clients', orderable: false, searchable: false },
                    { data: 'states', orderable: false, searchable: false },
                    { data: 'is_active', orderable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });

    </script>
    <link rel="stylesheet" type="text/css" href="{{masset('css/management/admin_teams_manager/options.css')}}">
@endpush
