@extends('admin::layouts.master')

@section('title', 'Stats')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Front end Site', 'url' => '#'],
        ['title' => 'Stats', 'url' => route('admin.frontend-site.stats.index')]
    ],
    'actions' => [
        ['title' => 'Add Header Carousel Item', 'url' => route('admin.frontend-site.stats.create')]
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
                                    <th>Icon</th>
                                    <th>Stat Number</th>
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
        $(function () {
            $app.datatables('#datatable', '{{ route('admin.frontend-site.stats.data') }}', {
                columns: [
                    {data: 'title', orderable: false},
                    {data: 'icon', orderable: false},
                    {data: 'stat_number', orderable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                "pageLength": 10,
            });
        });
    </script>
@endpush