@extends('admin::layouts.master')

@section('title', 'Latest News')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Front end Site', 'url' => '#'],
        ['title' => 'Latest News', 'url' => route('admin.frontend-site.latest-news.index')]
    ],
    'actions' => [
        ['title' => 'Add Latest News Item', 'url' => route('admin.frontend-site.latest-news.create')]
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
                                    <th>Header Title</th>
                                    <th>Description</th>
                                    <th>Active</th>
                                    <th>Created At</th>
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
    <script src="{{masset('js/modules/admin/front-end/latest-news/index.js')}}"></script>
@endpush