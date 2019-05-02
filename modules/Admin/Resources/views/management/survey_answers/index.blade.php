@extends('admin::layouts.master')

@section('title', 'Survey Reporting')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Management', 'url' => '#'],
  ['title' => 'Survey Reporting', 'url' => route('admin.management.surveys.answers.index')]
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
                                    <th>Type</th>
                                    <th>Questions</th>
                                    <th>Answered</th>
                                    <th>Is Active</th>
                                    <th>Expires</th>
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

    <div class="row">
        <div class="col-lg-6">
            {{ Form::open(['route' => ['admin.management.surveys.answers.report'], 'method' => 'post', 'class' => 'form-horizontal']) }}

            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">Download Results</h3>
                </div>
                <div class="panel-body">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {{ Form::label('survey_id', 'Survey', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('survey_id', $surveys, (!empty($survey) ? $survey : null), ['class' => 'form-control']) }}

                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {{ Form::label('date_start', 'Date', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            <div class="col-md-4">{{ Form::text('date_start', null, ['class' => 'form-control datepicker']) }}</div>
                                            <div class="col-md-4">{{ Form::text('date_end', null, ['class' => 'form-control datepicker']) }}</div>
                                            <div class="col-md-4">{{ Form::select('type', ['answered' => 'Survey Answered Date', 'date_delivered' => 'Date Delivered'], null, ['class' => 'form-control']) }}</div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="ibox-footer">
                                    <button class="btn btn-success pull-right">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @push('scripts')
                <script type="text/javascript">
                    $(document).ready(function(){
                        $('.datepicker').datetimepicker({
                            format:'YYYY-MM-DD'
                        });
                    });
                </script>
                @endpush
            </div>
        </div>
        {{ Form::close() }}
    </div>

@stop

@push('scripts')
<script>
    $(function() {
        $app.datatables('#datatable', '{!! route('admin.management.surveys.answers.data') !!}', {
            columns: [
                { data: 'title' },
                { data: 'type' },
                { data: 'questions' },
                { data: 'answers' },
                { data: 'is_active' },
                { data: 'expires_date' },
                { data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            order : [ [ 0, 'asc' ] ]
        });
    });

</script>
@endpush