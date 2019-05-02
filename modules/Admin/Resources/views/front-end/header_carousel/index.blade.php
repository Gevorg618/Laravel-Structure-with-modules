@extends('admin::layouts.master')

@section('title', 'Header Carousel')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Front end Site', 'url' => '#'],
        ['title' => 'Header Carousel', 'url' => route('admin.frontend-site.header-carousel.index')]
    ],
    'actions' => [
        ['title' => 'Add Header Carousel Item', 'url' => route('admin.frontend-site.header-carousel.create')]
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
                                    <th>Position</th>
                                    <th>Active</th>
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
    <script src="{{masset('js/modules/admin/front-end/header-carousel/index.js')}}"></script>
@endpush