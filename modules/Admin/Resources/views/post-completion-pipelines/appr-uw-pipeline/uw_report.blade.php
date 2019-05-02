@extends('admin::layouts.master')

@section('title', 'UW Condition Stats')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Post Completion Pipelines', 'url' => '#'],
  ['title' => 'Appraisal UW Pipeline', 'url' => route('admin.post-completion-pipelines.appr-uw-pipeline')],
  ['title' => 'View UW Condition Stats', 'url' => route('admin.post-completion-pipelines.appr-uw-pipeline.uw-report')]
]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body panel-body-table">
                        <div class="table-responsive" style="min-height:500px;">
                            <div class="container">
                                <div class="col-md-6">
                                    <form action="{{route('admin.post-completion-pipelines.appr-uw-pipeline.uw-report-download')}}" method="POST" class="form-horizontal">
                                        {{ csrf_field() }}
                                        <div class="form-group">
                                            {{ Form::label('client', 'Client(s)', ['class' => 'col-lg-3 col-xs-12']) }}
                                            <div class="col-lg-9 col-xs-12">
                                              {{ Form::select('client[]', collect($clients)->pluck('descrip', 'id')->all(), null, ['class' => 'form-control bootstrap-multiselect', 'multiple' => 'multiple']) }}
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            {{ Form::label('date_from', 'Date', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                            <div class="col-lg-4 col-xs-12">
                                              <input type="text" name="date_from" id="" class="form-control" required placeholder="From">
                                            </div>
                                            <div class="col-lg-4 col-xs-12">
                                              <input type="text" name="date_to" id="" class="form-control" required placeholder="To">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            {{ Form::label('datetype', 'Date Type', ['class' => 'col-lg-3 col-xs-12']) }}
                                            <div class="col-lg-9 col-xs-12">
                                              <select name="datetype" id="datetype" class="form-control">
                                                    <option value="date_uw_received">UW Received</option>
                                                    <option value="date_uw_completed">UW Completed</option>
                                              </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            {{ Form::label('user', 'User', ['class' => 'col-lg-3 col-xs-12']) }}
                                            <div class="col-lg-9 col-xs-12">
                                              {{ Form::select('user', [null => '-- Select --'] + collect($admins)->pluck('fullname', 'id')->all(), null, ['class' => 'form-control']) }}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button type="reset" class="btn btn-default">Reset</button>
                                            <button type="submit" class="btn btn-success">Download Report</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('style')
    <link href="{{ masset('js/bootstrap-multiselect/dist/css/bootstrap-multiselect.css') }}" rel="stylesheet" />
@endpush

@push('scripts')
    <script src="{{ masset('js/bootstrap-multiselect/dist/js/bootstrap-multiselect.js') }}"></script>
    <script src="{{ masset('js/appraisal/appr_uw_pipeline/uw_report.js')}}"></script>
@endpush
