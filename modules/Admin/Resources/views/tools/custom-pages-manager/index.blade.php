@extends('admin::layouts.master')

@section('title', 'Custom Pages')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Custom Pages Manager', 'url' => '#'],
      ['title' => 'Custom Pages', 'url' => route('admin.tools.custom-pages-manager.index')]
    ],
    'actions' => [
      ['title' => 'Add Custom Page', 'url' => route('admin.tools.custom-pages-manager.create')]
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
                                    <th>Title</th>
                                    <th>Route</th>
                                    <th>Active</th>
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
        $app.datatables('#datatable', '{!! route('admin.tools.custom-pages-manager.data') !!}', {
            columns: [
                { data: 'name' },
                { data: 'title' },
                { data: 'route' },
                { data: 'active' },
                { data: 'created_by' },
                { data: 'created_date' },
                { data: 'modified_by' },
                { data: 'modified_date' },
                { data: 'options', name: 'options', orderable: false, searchable: false}
            ],
            order : [ [ 0, 'asc' ] ]
        });
    });
</script>
@endpush