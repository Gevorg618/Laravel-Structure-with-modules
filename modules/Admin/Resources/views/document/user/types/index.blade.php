@extends('admin::layouts.master')

@section('title', 'User Document Types')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Documents & Uploads', 'url' => '#'],
  ['title' => 'User Document Types Manager', 'url' => route('admin.document.user.types')]
],
'actions' => [
  ['title' => 'Add User Document Type', 'url' => route('admin.document.user.types.create')],
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
        $app.datatables('#datatable', '{!! route('admin.document.user.types.data') !!}', {
            columns: [
                { data: 'title' },
                { data: 'code' },
//                { data: 'is_protected', searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            order : [ [ 0, 'asc' ] ]
        });
    });
</script>
@endpush