@extends('admin::layouts.master')

@section('title', 'Navigation Menu')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Front end Site', 'url' => '#'],
        ['title' => 'Navigation Menu', 'url' => route('admin.frontend-site.navigation-menu.index')]
    ],
    'actions' => [
        ['title' => 'Add Navigation Menu Item', 'url' => route('admin.frontend-site.navigation-menu.create')]
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
                                    <th>Url</th>
                                    <th>Slug</th>
                                    <th>DropDown</th>
                                    <th>Active</th>
                                    <th>Quick Link</th>
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
    <script src="{{masset('js/modules/admin/front-end/navigation-menu/index.js')}}"></script>
@endpush