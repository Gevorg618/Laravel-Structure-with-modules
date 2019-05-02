@extends('admin::layouts.master')

@section('title', 'Appraiser Groups')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Appraiser', 'url' => '#'],
      ['title' => 'Appraiser Groups', 'url' => route('admin.management.appraiser.index')]
    ],
    'actions' => [
      ['title' => 'Add Appraiser Group', 'url' => route('admin.management.appraiser.create')]
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
                                    <th>Manager</th>
                                    <th>Users</th>
                                    <th>Created Date</th>
                                    <th>Created By</th>
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
        $app.datatables('#datatable', '{!! route('admin.management.appraiser.data') !!}', {
            columns: [
                { data: 'title' },
                { data: 'description' },
                { data: 'managerid' },
                { data: 'users_count' },
                { data: 'created_date' },
                { data: 'creator' },
                { data:  'options'},
            ],
            order : [ [ 0, 'asc' ] ]
        });
    });
</script>
@endpush