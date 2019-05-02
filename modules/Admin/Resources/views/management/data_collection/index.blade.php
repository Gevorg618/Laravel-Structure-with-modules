@extends('admin::layouts.master')

@section('title', 'Appraisal QC Data Collection')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'QC', 'url' => '#'],
        ['title' => 'Data Collection', 'url' => route('admin.qc.collection.index')]
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body">
                        <div class="pull-right">
                            <a href="{{ route('admin.qc.collection.create') }}"
                               class="btn btn-sm btn-primary">Add Question</a>
                        </div>
                    </div>

                    <div class="panel-body panel-body-table">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="datatable">
                                <thead>
                                <tr>
                                    <th>Position</th>
                                    <th>Title</th>
                                    <th>Key</th>
                                    <th>Active</th>
                                    <th>Required</th>
                                    <th>Field Type</th>
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
        $(function () {
            $app.datatables('#datatable', '{!! route('admin.qc.collection.data') !!}', {
                columns: [
                    {data: 'pos'},
                    {data: 'title'},
                    {data: 'id'},
                    {data: 'is_active'},
                    {data: 'is_required'},
                    {data: 'field_type'},
                    {data: 'created_date'},
                    {data: 'created_by'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                order: [[2, 'desc']]
            });
        });
    </script>
@endpush













