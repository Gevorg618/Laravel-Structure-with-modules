@extends('admin::layouts.master')

@section('title', 'Manager Reports')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Tasks', 'url' => '#']
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
                                    <th>Created</th>
                                    <th>Next Run</th>
                                    <th>Active</th>
                                    <th>Subject</th>
                                    <th>Emails</th>
                                    <th>File Name</th>
                                    <th>Date Range</th>
                                    <th>On Weekends</th>
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
        $app.datatables('#datatable', '{!! route('admin.reports.tasks.data') !!}', {
            columns: [
                { data: 'title', orderable: true },
                { data: 'description', orderable: false, searchable: false },
                { data: 'created' },
                { data: 'next_run', orderable: false, searchable: false },
                { data: 'active', orderable: false, searchable: false },
                { data: 'subject' },
                { data: 'emails', orderable: false, searchable: false },
                { data: 'file_name' },
                { data: 'date_range' },
                { data: 'on_weekends', orderable: false, searchable: false},
                { data: 'options', name: 'options', orderable: false, searchable: false}
            ],
            order : [ [ 0, 'asc' ] ]
        });
    });
</script>
@endpush