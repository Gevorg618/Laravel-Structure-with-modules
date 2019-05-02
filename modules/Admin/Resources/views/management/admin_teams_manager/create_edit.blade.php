@extends('admin::layouts.master')

@section('title', isset($adminTeam) ? "Edit Team" : 'Add Team')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Admin User', 'url' => '#'],
        ['title' => 'Admin Teams Manager', 'url' => route('admin.management.admin-teams-manager')],
        ['title' => isset($adminTeam) ? "Edit Team" : 'Add Team', 'url' => isset($adminTeam) ? '#' : route('admin.management.admin-teams-manager.create')]
    ],
    'actions' => [
        ['title' => 'Admin Teams Manager', 'url' => route('admin.management.admin-teams-manager')]
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body panel-body-table">
                        <div class="col-md-offset-1 col-md-10">
                            <form method="POST" action="{{!isset($adminTeam) ? route('admin.management.admin-teams-manager.store') : route('admin.management.admin-teams-manager.update', ['id' => $adminTeam->id])}}">
                                {{ csrf_field() }}
                                {{isset($adminTeam) ? method_field('put') : ''}}
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a data-toggle="tab" href="#information">Information</a>
                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#associations">Associations</a>
                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#status_select">Status Select</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div id="information" class="tab-pane fade in active">
                                        {!! $informationView !!}
                                    </div>
                                    <div id="associations" class="tab-pane fade">
                                        {!! $associationsView !!}
                                    </div>
                                    <div id="status_select" class="tab-pane fade">
                                        {!! $statusSelectView !!}
                                    </div>
                                    <hr>
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('scripts')
    <link href="{{ masset('css/management/admin_teams_manager/create_edit.css') }}" rel="stylesheet" />
@endpush
