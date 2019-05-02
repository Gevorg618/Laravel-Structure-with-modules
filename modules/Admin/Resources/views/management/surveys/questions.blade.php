@extends('admin::layouts.master')

@section('title', 'Survey Questions for '.$survey->title)

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Management', 'url' => '#'],
  ['title' => 'Surveys', 'url' => route('admin.management.surveys.index')],
  ['title' => 'Questions for '.$survey->title, 'url' => '']
],
'actions' => [
  ['title' => 'Add Survey', 'url' => route('admin.management.surveys.create')],
  ['title' => 'Add Survey Question', 'url' => route('admin.management.surveys.questions.create', ['id' => $survey->id])],
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
                            <table class="table table-striped table-bordered table-hover" id="datatable-questions">
                                <thead>
                                <tr>
                                    <th>Title</th>
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
                        <div>
                            <input type="text" class="col-lg-12" readonly value="{{ route('dashboard.surveys.prepare', ['survey_id' => $survey->id]).($survey->type == 'order' ? '/{oid}' : '') }}">
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
        $app.datatables('#datatable-questions', '{!! route('admin.management.surveys.questions.data', ['id' => $survey->id]) !!}', {
            columns: [
                { data: 'title' },
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