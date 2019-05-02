@extends('admin::layouts.master')

@section('title', 'Unassigned Pipeline')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Appraisal Pipeline', 'url' => '#'],
        ['title' => 'Unassigned Pipeline', 'url' => route('admin.appraisal-pipeline.unassigned-pipeline')]
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body panel-body-table">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="is_rush">Rush</label>
                                        <select class='form-control filter-change' id="is_rush" name="is_rush">
                                            <option value="">All</option>
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="team" class="col-md-12 row">Team</label>
                                        <select class='form-control filter-change multiselect bootstrap-multiselect' multiple="multiple" id="team" name="team">
                                            @foreach($teams as $team)
                                                <option value="{{$team->id}}">{{$team->team_title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="client" class="col-md-12 row">Client</label>
                                        <select class='form-control filter-change multiselect bootstrap-multiselect' multiple="multiple" id="client" name="client">
                                            @foreach($clients as $client)
                                                <option value="{{$client->id}}">{{$client->descrip}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="due_date">Due Date</label>
                                        <input type="text" class='form-control date-selector filter-change' id="due_date" name="due_date" value="" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="state" class="col-md-12 row">State</label>
                                        <select class='form-control filter-change multiselect bootstrap-multiselect' multiple="multiple" id="state" name="state">
                                            @foreach($states as $key => $state)
                                                <option value="{{$key}}">{{$state}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="timezone" class="col-md-12 row">Time Zone</label>
                                        <select class='form-control filter-change multiselect bootstrap-multiselect' multiple="multiple" id="timezone" name="timezone">
                                            @foreach($timeZones as $key => $zone)
                                                <option value="{{$key}}">{{$zone}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="loanreason" class="col-md-12 row">Loan Reason</label>
                                        <select class='form-control filter-change multiselect bootstrap-multiselect' multiple="multiple" id="loanreason" name="loanreason">
                                            @foreach($reasons as $reason)
                                                <option value="{{$reason->id}}">{{$reason->descrip}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="autoselect">Autoselect</label>
                                        <select class='form-control filter-change' id="autoselect" name="autoselect">
                                            <option value="">All</option>
                                            <option value="inactive">No longer Sending Invites</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 row">
                            <div style="margin-left:15px;margin-bottom:5px;">
                                <strong>Legend:</strong> &nbsp;
                                <span class="order-purchased">Purchase</span> |
                                <span class="due-date-today">Due Today</span> |
                                <span class="due-date-past">Past Due</span> |
                                <span class="order-reassign">Re-Assign</span> |
                                <span class="order-has-invites">Invites</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                            <button type="button" id="refresh_button" class="btn btn-primary btn-sm">Refresh</button>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body panel-body-table">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="datatable">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Address</th>
                                    <th>Client</th>
                                    <th>Unassigned Date</th>
                                    <th>Appraisal Type</th>
                                    <th>Worked</th>
                                    <th>Invites</th>
                                    <th>Tickets</th>
                                    <th>Actions</th>
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

@push('style')
    <link href="{{ masset('js/bootstrap-multiselect/dist/css/bootstrap-multiselect.css') }}" rel="stylesheet" />
    <link href="{{ masset('css/appraisal/unassigned_pipeline/index.css') }}" rel="stylesheet" />
@endpush

@push('scripts')
    <script src="{{ masset('js/bootstrap-multiselect/dist/js/bootstrap-multiselect.js') }}"></script>
    <script src="{{ masset('js/appraisal/unassigned_pipeline/index.js') }}"></script>
    <script src="//cdn.datatables.net/plug-ins/1.10.16/pagination/full_numbers_no_ellipses.js"></script>
@endpush

