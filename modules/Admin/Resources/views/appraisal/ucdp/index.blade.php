@extends('admin::layouts.master')

@section('title', 'UCDP Business Units Manager')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Appraisal', 'url' => '#'],
  ['title' => 'UCDP Business Units Manager', 'url' => route('admin.appraisal.ucdp-unit')]
],
'actions' => [
  ['title' => 'Add Business Units', 'url' => route('admin.appraisal.ucdp-unit.create')],
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
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Unit ID</th>
                                    <th>Active</th>
                                    <th>FNM Active</th>
                                    <th>FRE Active</th>
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
        $app.datatables('#datatable', '{!! route('admin.appraisal.ucdp-unit.data') !!}', {
            columns: [
                { data: 'id' },
                { data: 'title' },
                { data: 'unit_id' },
                { data: 'is_active' },
                { data: 'fnm_active' },
                { data: 'fre_active' },
                { data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            order : [ [ 0, 'asc' ] ]
        });
    });
</script>
@endpush