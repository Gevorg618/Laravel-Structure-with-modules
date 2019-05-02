@extends('admin::layouts.master')

@section('title', 'Auto Select Turn Times')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Auto Select & Pricing', 'url' => '#'],
      ['title' => 'Auto Select Turn Times', 'url' => route('admin.autoselect.turn.times.index')]
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <h2><b>Default Turn Times</b></h2>
                    <div class="panel-body panel-body-table">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="datatable_default">
                                <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Records Saved</th>
                                    <th>Appraisal Types</th>
                                    <th>Last Edited By</th>
                                    <th>Last Edited Date</th>
                                    <th>Options</th>
                                </tr>
                                </thead>
                                <tbody>
                                        <tr>
                                            <td>Default</td>
                                            <td>{{ $defaultTurnTimesCount }}</td>
                                            <td>{{ $typesCount }}</td>
                                            <td>{{ $defaultTurnTime->editedBy->userData->firstname.' '.$defaultTurnTime->editedBy->userData->lastname }}</td>
                                            <td>{{ date('m/d/Y H:i', $defaultTurnTime->last_edited_date)}}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="#" data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">Actions <span class="caret"></span></a>
                                                    <ul class="dropdown-menu pull-right" role="menu">
                                                        <li><a href="{{ route('admin.autoselect.turn.times.edit', 'default') }}"><i class="fa fa-edit"></i> Edit</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <h2><b>Client Specific Turn Times</b> 
                        <a href="{{ route('admin.autoselect.turn.times.create') }}" class="btn btn-primary btn-sm pull-right">Add Client Specific Turn Time</a>
                    </h2>
                    <div class="panel-body panel-body-table">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="datatable_client_specific">
                                <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Records Saved</th>
                                    <th>Appraisal Types</th>
                                    <th>Created By</th>
                                    <th>Created Date</th>
                                    <th>Last Edited By</th>
                                    <th>Last Edited Date</th>
                                    <th>Options</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($clientTurnTimes as $clientTurnTime)
                                        <tr>
                                            <td>{{ $clientTurnTime->client->descrip }}</td>
                                            <td>{{ $clientTurnTimes->count() }}</td>
                                            <td>{{ $typesCount }}</td>
                                            <td>{{ $clientTurnTime->createdBy->userData->firstname.' '.$clientTurnTime->createdBy->userData->lastname }}</td>
                                            <td>{{ date('m/d/Y H:i', $clientTurnTime->created_date)}}</td>
                                            <td>{{ $clientTurnTime->editedBy->userData->firstname.' '.$clientTurnTime->editedBy->userData->lastname }}</td>
                                            <td>{{ date('m/d/Y H:i', $clientTurnTime->last_edited_date)}}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="#" data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">Actions <span class="caret"></span></a>
                                                    <ul class="dropdown-menu pull-right" role="menu">
                                                        <li><a href="{{ route('admin.autoselect.turn.times.edit', $clientTurnTime->client_id) }}"><i class="fa fa-edit"></i> Edit</a></li>
                                                        <li><a href="{{ route('admin.autoselect.turn.times.destroy', $clientTurnTime->client_id) }}" data-method="delete"><i class="fa fa-trash"></i> Delete</a></li>
                                                    </ul>
                                                </div>                                                
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
@stop
@push('scripts')
<script>
$(document).ready(function () {
    $('#datatable_default').DataTable({
        "pageLength": 5
    });
    $('#datatable_client_specific').DataTable({
        "pageLength": 5
    });
});
</script>
@endpush

