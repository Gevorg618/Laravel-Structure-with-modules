@extends('admin::layouts.master')

@section('title', 'Resource Documents')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Documents & Uploads', 'url' => '#'],
  ['title' => 'Resource Documents', 'url' => route('admin.document.resource')]
],
'actions' => [
  ['title' => 'Add Document', 'url' => route('admin.document.resource.create')],
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
                                    <th>Description</th>
                                    <th>Type</th>
                                    <th>Link</th>
                                    <th>Created By</th>
                                    <th>Created Date</th>
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
        $app.datatables('#datatable', '{!! route('admin.document.resource.data') !!}', {
            columns: [
                { data: 'title' },
                { data: 'description' },
                { data: 'type' },
                { data: 'link' },
                { data: 'created_by' },
                { data: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            order : [ [ 0, 'asc' ] ]
        });
    });
</script>
@endpush