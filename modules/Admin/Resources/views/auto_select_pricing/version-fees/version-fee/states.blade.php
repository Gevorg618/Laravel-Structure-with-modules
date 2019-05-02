@extends('admin::layouts.master')

@section('title', 'Auto Select Turn Times')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Auto Select & Pricing', 'url' => '#'],
      ['title' => 'AutoSelect Pricing Version Fees', 'url' => route('admin.autoselect.pricing.fees.index')],
      ['title' => 'View Version States', 'url' => '#']
    ]
])
@endcomponent
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">Viewing Pricing Version Fees <b><i>{{ $version->title }}</i></b> States</h3>
                </div>
                <div class="panel-body">
                    <div class="container">
                        <div class="col-lg-6 col-lg-offset-3">
                            <table class="table table-striped table-bordered table-hover" id="datatable">
                                <thead>
                                    <tr>
                                        <th>State Name</th>
                                        <th>State Abbreviation</th>
                                        <th>Download</th>
                                        <th>View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($states as $state)
                                    <tr>
                                        <td>{{ $state->state }}</td>
                                        <td>{{ $state->abbr }}</td>
                                        <td class="center">
                                            <a href="{{ route('admin.autoselect.pricing.version.fees.download', [$version->id, $state->abbr ]) }}"  class="btn btn-primary btn-md" title="Download"><i class="fa fa-download"></i></a>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.autoselect.pricing.version.fees.state', [$version->id, $state->abbr ]) }}"  class="btn btn-warning btn-md" title="View"><i class="fa fa-arrow-right"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <hr>
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
                    <h3 class="panel-title">State Fees Import</h3>
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
                    <div class="container">
                        <div class="col-lg-6 col-lg-offset-3">
                            {{ Form::open(['route' => ['admin.autoselect.pricing.version.fees.import', $version->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
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
                                                <label for="upload_csv" class="btn btn-warning">Click to choose CSV file</label>
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
        </div>
    </div>
@stop
@push('scripts')
<script>
$(document).ready(function () {
    $('#datatable').DataTable({
        "pageLength": 15
    });
    $('.states').select2();
});
</script>
@endpush