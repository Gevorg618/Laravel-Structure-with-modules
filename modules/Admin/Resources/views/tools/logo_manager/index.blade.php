@extends('admin::layouts.master')

@section('title', 'Logo Manager')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Settings & Templates', 'url' => '#'],
        ['title' => 'Logo Manager', 'url' => route('admin.tools.logos')]
    ],
    'actions' => [
        ['title' => 'Create Logo', 'url' => route('admin.tools.logos.create')]
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
                            <table class="table table-striped table-bordered table-hover" id="datatable" style="text-align: center;">
                                <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Image</th>
                                    <th>Is Active</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
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
        var table = $app.datatables('#datatable', '{!! route('admin.tools.logos.data') !!}', {
            columns: [
                {data: 'title'},
                {data: 'image', orderable: false, searchable: false},
                {data: 'is_active'},
                {data: 'start_date'},
                {data: 'end_date'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            createdRow: function( row, data, dataIndex ) {
                $( row ).find('td:eq(1)').html($('<div />').html(data.image).text());
            },
        });
    });

</script>
@endpush
