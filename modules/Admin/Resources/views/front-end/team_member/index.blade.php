@extends('admin::layouts.master')

@section('title', 'Team Member')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Front end Site', 'url' => '#'],
        ['title' => 'Team Member', 'url' => route('admin.frontend-site.team-member.index')]
    ],
    'actions' => [
        ['title' => 'Add Team Member Item', 'url' => route('admin.frontend-site.team-member.create')]
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
                                    <th>Name</th>
                                    <th>Icon</th>
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
    <script src="{{masset('js/modules/admin/front-end/team-member/index.js')}}"></script>
@endpush