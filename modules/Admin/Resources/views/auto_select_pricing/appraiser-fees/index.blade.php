@extends('admin::layouts.master')

@section('title', 'Auto Select Appraiser Fees')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Auto Select & Pricing', 'url' => '#'],
      ['title' => 'Auto Select Appraiser Fees', 'url' => route('admin.autoselect.appraiser.fees.index')]
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div ><a href="{{ route('admin.autoselect.appraiser.fees.template.download') }}">Download Template</a></div>
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body panel-body-table">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="datatable">
                                <thead>
                                <tr>
                                    <th>State</th>
                                    <th>Options</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($states as $state)
                                        <tr>
                                            <td><a href="{{ route('admin.autoselect.appraiser.fees.state.form', $state->abbr) }}">{{ $state->state }}</a></td>
                                            <td>
                                                <a href="{{ route('admin.autoselect.appraiser.fees.state.form', $state->abbr) }}">view</a> |
                                                <a href="{{ route('admin.autoselect.appraiser.fees.template.state.download', $state->abbr) }}">download</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title"></h3>
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
                    {{ Form::open(['route' => 'admin.autoselect.appraiser.fees.store', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                    <select class="states form-control" name="state[]"  data-placeholder="Select states" multiple="multiple">
                                        @foreach($states as $state)
                                            <option value="{{$state->abbr}}">{{$state->state}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div>
                                        <label for="upload_csv" class="btn btn-default">Click to choose CSV file</label>
                                        <input type="file" style="overflow: hidden; width: 0px; height: 0px" id="upload_csv" name="fees" accept=".csv">
                                    </div>
                                </div>
                            </div>
                            <div class="row m-t-lg">
                                <div class="col-md-12 ">
                                    {!! Form::submit('Import', ['class' => 'btn btn-success form-control']) !!}
                                </div>
                            </div>
                        </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
    </div>
@stop
@push('scripts')
<script>
$(document).ready(function () {
    $('#datatable').DataTable({
        "pageLength": 25
    });
    $('.states').select2();
});
</script>
@endpush

