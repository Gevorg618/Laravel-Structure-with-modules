@extends('admin::layouts.master')

@section('title', 'Document Types')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Documents & Uploads', 'url' => '#'],
  ['title' => 'Document Types Manager', 'url' => route('admin.document.types')]
],
'actions' => [
  ['title' => 'Add Document Type', 'url' => route('admin.document.types.create')],
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
                                    <th>Key</th>
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
        $app.datatables('#datatable', '{!! route('admin.document.types.data') !!}', {
            columns: [
                { data: 'name' },
                { data: 'code' },
//                { data: 'is_protected', searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            order : [ [ 0, 'asc' ] ]
        });
    });
</script>
@endpush