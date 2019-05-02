@extends('admin::layouts.master')

@section('title', 'Survey Reporting')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Management', 'url' => '#'],
  ['title' => 'Survey Reporting', 'url' => route('admin.management.surveys.answers.index')],
  ['title' => 'Viewing Results For '.$survey->title, 'url' => '#']
]
])
@endcomponent
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1>Total Results: {{ count($surveyAnswers) }}</h1>
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body panel-body-table">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="datatable">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Property Address</th>
                                    <th>Borrower</th>
                                    <th>Ordered Date</th>
                                    <th>Delivered Date</th>
                                    <th>Appraiser</th>
                                    <th>Engaged</th>
                                    <th>Date Answered</th>
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
        $app.datatables('#datatable', '{!! route('admin.management.surveys.answers.show.data', ['survey_id' => $survey->id]) !!}', {
            columns: [
                { data: 'order_id' },
                { data: 'property_address' },
                { data: 'borrower' },
                { data: 'date_order' },
                { data: 'date_delivered' },
                { data: 'appraiser' },
                { data: 'engager' },
                { data: 'date_answered' }
            ],
            order : [ 7, 'asc' ],
        });
    });

</script>
@endpush