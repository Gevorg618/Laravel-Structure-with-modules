@extends('admin::layouts.master')

@section('title', 'Surveys')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Management', 'url' => '#'],
  ['title' => 'Surveys', 'url' => route('admin.management.surveys.index')]
],
'actions' => [
  ['title' => 'Add Survey', 'url' => route('admin.management.surveys.create')],
  ['title' => 'Add Survey Question', 'url' => route('admin.management.surveys.questions.create')],
]
])
@endcomponent
@section('content')
    <div class="tabbable-line">
        <ul class="nav nav-tabs ">
            <li class="active">
                <a href="#surveys" data-toggle="tab">
                    Surveys </a>
            </li>
            <li class="">
                <a href="#questions" data-toggle="tab">
                    Survey Questions </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="surveys">

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
                                                <th>Type</th>
                                                <th>Questions</th>
                                                <th>Is Active</th>
                                                <th>Expires</th>
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

            </div>
            <div class="tab-pane"  id="questions">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-content">
                                <div class="panel-body panel-body-table">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover" id="datatable-questions">
                                            <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Survey</th>
                                                <th>Type</th>
                                                <th>Is Active</th>
                                                <th>Is Required</th>
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
            </div>
        </div>
    </div>

@stop

@push('scripts')
<script>
    $(function() {
        $app.datatables('#datatable', '{!! route('admin.management.surveys.data') !!}', {
            columns: [
                { data: 'title' },
                { data: 'type' },
                { data: 'questions' },
                { data: 'is_active' },
                { data: 'expires_date' },
                { data: 'created_by' },
                { data: 'created_date' },
                { data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            order : [ [ 0, 'asc' ] ]
        });
    });

    $(function() {
        $app.datatables('#datatable-questions', '{!! route('admin.management.surveys.questions.data') !!}', {
            columns: [
                { data: 'title' },
                { data: 'survey_id' },
                { data: 'type' },
                { data: 'is_active' },
                { data: 'is_required' },
                { data: 'created_by' },
                { data: 'created_date' },
                { data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            order : [ [ 0, 'asc' ] ]
        });
    });
</script>
@endpush