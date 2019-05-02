@extends('admin::layouts.master')

@section('title', 'Templates')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Tools', 'url' => '#'],
  ['title' => 'Templates', 'url' => route('admin.tools.templates')]
],
'actions' => [
  ['title' => 'Create Template', 'url' => route('admin.tools.templates.create')],
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
                                    <th>Created By</th>
                                    <th>Created Date</th>
                                    <th>Last Modified By</th>
                                    <th>Last Modified Date</th>
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
        $app.datatables('#datatable', '{!! route('admin.tools.templates.data') !!}', {
            columns: [
                { data: 'name' },
                { data: 'created_by' },
                { data: 'created_date' },
                { data: 'last_modified_by' },
                { data: 'last_modified' },
                { data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            order : [ [ 0, 'asc' ] ]
        });
    });
</script>
@endpush