@extends('admin::layouts.master')

@section('title', 'Documents')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Global Documents', 'url' => route('admin.document.global.index')]
    ],
    'actions' => [
      ['title' => 'Add Record', 'url' => route('admin.document.global.create')]
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
                                    <th>File Name</th>
                                    <th>Created</th>
                                    <th>User</th>
                                    <th>Active</th>
                                    <th>Client</th>
                                    <th>Appraiser</th>
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
        $app.datatables('#datatable', '{!! route('admin.document.global.data') !!}', {
            columns: [
                { data: 'file_name' },
                { data: 'created' },
                { data: 'user' },
                { data: 'active' },
                { data: 'client' },
                { data: 'appraiser' },
                { data: 'options', name: 'options', orderable: false, searchable: false}
            ],
            order : [ [ 0, 'asc' ] ]
        });
    });
</script>
@endpush