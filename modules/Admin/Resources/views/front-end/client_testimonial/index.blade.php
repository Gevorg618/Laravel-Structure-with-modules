@extends('admin::layouts.master')

@section('title', 'Client Testimonials')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Front end Site', 'url' => '#'],
        ['title' => 'Client Testimonials', 'url' => route('admin.frontend-site.client-testimonials.index')]
    ],
    'actions' => [
        ['title' => 'Add Client Testimonial Item', 'url' => route('admin.frontend-site.client-testimonials.create')]
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
                                    <th>Name</th>
                                    <th>Title</th>
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
    <script src="{{masset('js/modules/admin/front-end/client_testimonial/index.js')}}"></script>
@endpush