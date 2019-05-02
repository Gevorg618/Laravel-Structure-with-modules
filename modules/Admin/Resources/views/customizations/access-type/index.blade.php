@extends('admin::layouts.master')

@section('title', 'Access Type')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Appraisal', 'url' => '#'],
      ['title' => 'Access Type', 'url' => route('admin.appraisal.access_type.index')]
    ],
    'actions' => [
      ['title' => 'Add access type', 'url' => route('admin.appraisal.access_type.create')],
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
                                    <th>Is active</th>
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
    $(function() {
        $app.datatables('#datatable', '{!! route('admin.appraisal.access_type.data') !!}', {
            columns: [
                { data: 'name', name: 'name' },
                { data: 'is_active', name: 'is_active'},
                { data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            order : [ [ 0, 'asc' ] ]
        });
    });
</script>
@endpush