@extends('admin::layouts.master')

@section('title', 'AutoSelect Counties')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'AutoSelect & Pricing', 'url' => '#'],
        ['title' => 'AutoSelect Counties', 'url' => route('admin.autoselect.counties')]
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
                                    <th>State</th>
                                    <th>Total Counties</th>
                                    <th>Selected Counties</th>
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
        $app.datatables('#datatable', '{!! route('admin.autoselect.counties.data') !!}', {
            columns: [
                {data: 'state'},
                {data: 'total_counties', name: 'total_counties'},
                {data: 'selected_counties', name: 'selected_counties'},
                {data: 'action', orderable: false, searchable: false}
            ],
            order: [0 ,'asc'],
            "searching": false
        });
    });

</script>
@endpush
